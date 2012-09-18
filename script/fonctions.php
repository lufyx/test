<?php

function nomOnglet($nomBase,$monString)
{
	$mavar1 = substr($monString,3,1);
	$mavar2 = substr($monString,4,1);
	$onglet1 = "PQN";
	$onglet2 = "PQR";
	$onglet3 = "Périodique";
	$onglet4 = "International";
	$ongletCDC1 = "Groupe";
	$ongletCDC2 = "Thématique";
	$ongletAffich = "";
	
	if (strlen($monString) == 11)
	{
		if ($nomBase == "cdcdbprod")
		{
			if($mavar1 == 1)
			{				
				$ongletAffich = $ongletCDC1;
			}
			else
			{
				$ongletAffich = $ongletCDC2;
			}
		}
		else
		{
		
			if (($nomBase == "minecodbprod") ||($nomBase == "minbuddbprod") ||($nomBase == "fpublicdbprod"))
			{
				if($mavar1 == 1)
				{				
					$ongletAffich = $onglet1;
				}
				else
				{
					if ($mavar1 == 2)
					{
						$ongletAffich = $onglet2;
					}
					
					else
					{
					
						if ($mavar1 == 3)
						{
							$ongletAffich = $onglet3;
						}
						
						else
						{
							$ongletAffich = $onglet4;
						}
					}
				}
				
			}
				else
				{
					$ongletAffich = "Panorama";
				}
		}
	}
	
	else
	{
		if(strlen($monString) == 10)
		{
			$ongletAffich = "Panorama";
		}								
		else 
		{
			$ongletAffich = "Problème de RDP";
		}
	}
	return $ongletAffich;
}

function catAdmin($uneCat)
{
	$value = 1;
	if ($uneCat == "OFF" || $uneCat == 'Informations "off"' || $uneCat == 'Panorama "off"' || $uneCat == "Panorama OFF" || $uneCat == "Panorama off")
	{
		$value = 2;
	}
	else
	{
		if ($uneCat == "Par Défaut" || $uneCat == "Par défaut" || $uneCat == "Defaut")
		{
			$value = 3;
		}
	}
	return $value;
}

?>
