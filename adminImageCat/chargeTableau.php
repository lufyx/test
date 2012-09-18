<?php
include "fonctions.php";

$nomBase = $_POST["varBase"];
$laVersion = $_POST["varVersion"];

if ($nomBase == "cdcdbprod" || $nomBase == "transdevdbprod")
{
	mysql_connect("192.168.3.10","apmobile","ap2012") or die("Connexion impossible à ".$nomBase);
}
else
{
	mysql_connect("192.168.3.11","apmobile","ap2012") or die("Connexion impossible à ".$nomBase);
}

mysql_select_db($nomBase) or die("Echec de sélection de la base ".$nomBase);

$reqCat = mysql_query("SELECT category_label FROM mt_category");
$i = 0;

while($ligneCat = mysql_fetch_assoc($reqCat))
{
	$labelCat = utf8_encode($ligneCat["category_label"]);
	
	$urlLabelCat = replaceCarac($labelCat, $charset='utf-8');
	
	if (file_exists("../img_cat_" .$laVersion. "/" .$urlLabelCat. ".jpg"))
	{
		$urlCat = "../img_cat_" .$laVersion. "/" .$urlLabelCat. ".jpg";
	}
	else
	{
		$urlCat = "../img_source/Defaut.png";
	}
	
	echo "<tr><td style='border:1px solid black'>" .$labelCat. "</td><td style='border:1px solid black'><img id='img_" .$urlCat. "' src='" .$urlCat. "' onclick='' width='100' height='100' /></td><td style='border:1px solid black'><form method='post' action='uploader.php' enctype='multipart/form-data'><input type='file' name='explorateurImg' /><input type='hidden' value='../img_cat_".$laVersion."/".$urlLabelCat.".jpg' name='nomDossierEtFichier'/><input type='submit' name='envoyer' value='Envoyer le fichier'/></form></td></tr>";
	$i = $i + 1;
}

mysql_close();
?>