<?php

include "fonctions.php";

// Date du jour
$laDate = date("ymd");

$loginDB = "apmobile";
$mdpDB = "ap2012";

$tabBases = file('mesBases_3.11.txt');
	
foreach ($tabBases as $lineNumber => $lineContent)
{
	$infoBase = explode("#", $lineContent);
	$nomDB = $infoBase[0];
	if ($nomDB != "grandparis")
	{
		if ($nomDB == "u2n")
		{
			$nomDB = $nomDB. "prod";
		}
		else
		{
			$nomDB = $nomDB. "dbprod";
		}
	}
	
	mysql_connect("192.168.3.11",$loginDB,$mdpDB) or die("Connexion impossible à ".$nomDB);
	mysql_select_db($nomDB) or die("Echec de sélection de la base ".$nomDB);
	
	$reqRDP = mysql_query("SELECT DISTINCT entry_text_more FROM mt_entry WHERE entry_text_more LIKE '%" .$laDate. "'");
	
	while($ligneRDP = mysql_fetch_array($reqRDP))
	{
		$nbArticlesAll = 0;
		$nbArticlesAll2 = 0;
				
		$leFlux = new DOMDocument('1.0','UTF-8');
		$leFlux->formatOutput = true;
		
		$unEntryTextMore = $ligneRDP["entry_text_more"];
		$unRDP = utf8_encode(nomOnglet($nomDB, $unEntryTextMore));
		
		$noeudRdp = $leFlux->createElement('RDP');
		$noeudRdp = $leFlux->appendChild($noeudRdp);
		
		$reqCat = mysql_query("SELECT DISTINCT category_id, category_label FROM mt_entry JOIN mt_category ON entry_category_id = category_id WHERE entry_text_more = '" .$unEntryTextMore. "' ORDER BY category_description");
		
		$leFluxAll = new DOMDocument('1.0','UTF-8'); // Tous les articles
		$leFluxAll->formatOutput = true;
		
		$mesArticles = $leFluxAll->createElement('listeArticle');
		$mesArticles = $leFluxAll->appendChild($mesArticles);
		
		while($ligneCat = mysql_fetch_array($reqCat))
		{
			$nbArticles = 0;
			
			// XML des RDP
			$unIdCat = $ligneCat["category_id"];
			$uneCat = $ligneCat["category_label"];
			$typeCat = catAdmin($uneCat);
			
			$noeudCat = $leFlux->createElement('Category');
			$noeudCat = $noeudRdp->appendChild($noeudCat);
			$noeudCat->setAttribute("idCat",$unIdCat);
			$noeudCat->setAttribute("typeCat",$typeCat);
				$text = $leFlux->createTextNode(utf8_encode($uneCat));
				$text = $noeudCat->appendChild($text);
				
			$mesArts = $leFluxAll->createElement('Category');
			$mesArts = $mesArticles->appendChild($mesArts);
			$mesArts->setAttribute("nomCate",utf8_encode($uneCat));
			$mesArts->setAttribute("typeCat",$typeCat);
			$mesArts->setAttribute("idCat",$unIdCat);
			
			// XML des Cat
			$fluxCat = new DOMDocument('1.0','UTF-8');
			$fluxCat->formatOutput = true;
			$mesArt = $fluxCat->createElement('mesArt');
			$mesArt = $fluxCat->appendChild($mesArt);
				$mesArt->setAttribute("nomCate",utf8_encode($uneCat));
			
			$reqInfoArticle = mysql_query("SELECT * FROM mt_entry JOIN mt_author ON entry_author_id = author_id JOIN mt_placement ON entry_id = placement_entry_id WHERE entry_text_more = '" .$unEntryTextMore. "' AND entry_category_id = '" .$unIdCat. "' ORDER BY placement_norder");
			
			while($ligneInfoArticle = mysql_fetch_array($reqInfoArticle))
			{
				// XML d'un article
				$fluxArt = new DOMDocument('1.0','UTF-8');
				$fluxArt->formatOutput = true;
				
				$nbArticles = $nbArticles + 1;
				
				//On récup les infos
				$unIdArticle = $ligneInfoArticle["entry_id"];
				$uneDateArticle = $ligneInfoArticle["entry_modified_on"];
				$tabDate = explode(' ',$uneDateArticle);
				$unTitreArticle = utf8_encode($ligneInfoArticle["entry_title"]);
					$unTitreArticle = str_replace(chr(34),"",$unTitreArticle);
					$unTitreArticle = str_replace(chr(38),"",$unTitreArticle);
				$uneDescriptionArticle = utf8_encode($ligneInfoArticle["entry_text"]);
					$uneDescriptionArticle = str_replace(chr(23),"",$uneDescriptionArticle);
					$uneDescriptionArticle = str_replace(chr(13),"",$uneDescriptionArticle);
					$uneDescriptionArticle = str_replace(chr(38),"",$uneDescriptionArticle);
					$uneDescriptionArticle = str_replace(chr(60),"",$uneDescriptionArticle);
					$uneDescriptionArticle = str_replace(chr(62),"",$uneDescriptionArticle);
					$uneDescriptionArticle = str_replace(chr(31),"",$uneDescriptionArticle);
				$uneSourceArticle = utf8_encode($ligneInfoArticle["author_name"]);
					$uneSourceArticle = str_replace(chr(60),"",$uneSourceArticle);
					$uneSourceArticle = str_replace(chr(62),"",$uneSourceArticle);
				
				
				//XML Cat ALL
				$unArticle = $leFluxAll->createElement('Article');
				$unArticle = $mesArts->appendChild($unArticle);
				$unArticle->setAttribute("idArt",$unIdArticle);
				if ($uneDescriptionArticle != "")
				{
					$unArticle->setAttribute("typeArt","texte");
				}
				else
				{
					$unArticle->setAttribute("typeArt","pdf");
				}
				
				$unTitre = $leFluxAll->createElement("Titre");
				$unTitre = $unArticle->appendChild($unTitre);
					$text = $leFluxAll->createTextNode($unTitreArticle);
					$text = $unTitre->appendChild($text);
					
					$uneSource = $leFluxAll->createElement("Source");
					$uneSource = $unArticle->appendChild($uneSource);
					$text = $leFluxAll->createTextNode($uneSourceArticle);
					$text = $uneSource->appendChild($text);
					
					$uneDate = $leFluxAll->createElement("Date");
					$uneDate = $unArticle->appendChild($uneDate);
					$text = $leFluxAll->createTextNode($tabDate[0]);
					$text = $uneDate->appendChild($text);
					
				
				//XML des Cat
				$noeudCat2 = $fluxCat->createElement('Article');
				$noeudCat2 = $mesArt->appendChild($noeudCat2);
				$noeudCat2->setAttribute("idArt",$unIdArticle);
				if ($uneDescriptionArticle != "")
				{
					$noeudCat2->setAttribute("typeArt","texte");
				}
				else
				{
					$noeudCat2->setAttribute("typeArt","pdf");
				}
					
					$noeudCatTitre = $fluxCat->createElement('Titre');
					$noeudCatTitre = $noeudCat2->appendChild($noeudCatTitre);
						$text = $fluxCat->createTextNode($unTitreArticle);
						$text = $noeudCatTitre->appendChild($text);
						
					$noeudCatSrc = $fluxCat->createElement('Source');
					$noeudCatSrc = $noeudCat2->appendChild($noeudCatSrc);
						$text = $fluxCat->createTextNode($uneSourceArticle);
						$text = $noeudCatSrc->appendChild($text);
					
					$noeudCatDate = $fluxCat->createElement('Date');
					$noeudCatDate = $noeudCat2->appendChild($noeudCatDate);
						$text = $fluxCat->createTextNode($tabDate[0]);
						$text = $noeudCatDate->appendChild($text);
				
				if ($uneDescriptionArticle != "")
				{
					$noeudArt = $fluxArt->createElement('Article');
					$noeudArt = $fluxArt->appendChild($noeudArt);
					$noeudArt->setAttribute("idArt",$unIdArticle);
					
						$noeudArtTitre = $fluxArt->createElement('Titre');
						$noeudArtTitre = $noeudArt->appendChild($noeudArtTitre);
							$text = $fluxArt->createTextNode($unTitreArticle);
							$text = $noeudArtTitre->appendChild($text);
							
							$noeudArtDesc = $fluxArt->createElement('Description');
							$noeudArtDesc = $noeudArt->appendChild($noeudArtDesc);
								$text = $fluxArt->createTextNode($uneDescriptionArticle);
								$text = $noeudArtDesc->appendChild($text);
							
								$noeudArtSrc = $fluxArt->createElement('Source');
								$noeudArtSrc = $noeudArt->appendChild($noeudArtSrc);
									$text = $fluxArt->createTextNode($uneSourceArticle);
									$text = $noeudArtSrc->appendChild($text);
							
					$fluxArt->save("../data/" .$nomDB. "_" .$unRDP. "_" .$unIdCat. "_" .$unIdArticle. ".xml");
				}
			}
			
			$noeudCat->setAttribute("nbArt",$nbArticles);
			$mesArts->setAttribute("nbArt",$nbArticles);
			$mesArt->setAttribute("nbArt",$nbArticles);
			
			$fluxCat->save("../data/" .$nomDB. "_" .$unRDP. "_" .$unIdCat. ".xml");
			echo "../data/" .$nomDB. "_" .$unRDP. "_" .$unIdCat. ".xml";
		}
		
		$leFluxAll->save("../data/".$nomDB."_".$unRDP."_all.xml");
		$leFlux->save("../data/".$nomDB. "_" .$unRDP. ".xml");
	}
}

mysql_close();
?>
