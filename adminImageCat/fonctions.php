<?php

function replaceCarac($str, $charset='utf-8')
{
    $str = htmlentities($str, ENT_NOQUOTES, $charset);
    
    $str = preg_replace('#&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
    $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
	$str = strtr($str, ' ', '_');
	$str = str_replace("'","",$str);
    
    return $str;
}

function uploadImg()
{
	// Répertoire de stockage
	$rep="test/";

	if(isset($_FILES['explorateurImg']))
	{
		$savefile= $rep.$_FILES['explorateurImg']['name'];
		$temp = $_FILES['explorateurImg']['tmp_name'];
		
		if (move_uploaded_file($temp, $savefile))
		{
			echo "OK";
		}
		else
		{
			echo "erreur";
		}
	}
}
?>