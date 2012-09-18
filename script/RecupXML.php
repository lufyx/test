<?php

include "fonctions.php";

$mail = $_POST['mail'];

$loginDB = "apmobile";
$mdpDB = "ap2012";

$tabBases = file('mesBases_3.10.txt');

mysql_connect("192.168.3.10",$loginDB,$mdpDB) or die("Connexion impossible (3.10)");

foreach ($tabBases as $lineNumber => $lineContent)
{
	$infoBase = explode("#", $lineContent);
	$nomDB = $infoBase[0]."dbprod";
	
	mysql_select_db($nomDB) or die("Echec de selection de la base ".$nomDB);
	
	$reqmail = mysql_query("SELECT * FROM mt_customer WHERE customer_email='".$mail."' AND '".$mail."' <> '' ");
	
	if($lignemail = mysql_fetch_array($reqmail))
	{			
       $mesBDD .= ";".$nomDB;
       $mesBDD .= "/" .$lignemail['customer_status'];
	}
}

mysql_close();

$tabBases = file('mesBases_3.11.txt');

mysql_connect("192.168.3.11",$loginDB,$mdpDB) or die("Connexion impossible (3.11)");

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
	
	mysql_select_db($nomDB) or die("Echec de selection de la base ".$nomDB);
	
	$reqmail = mysql_query("SELECT * FROM mt_customer WHERE customer_email='".$mail."' AND '".$mail."' <> '' ");
	
	if($lignemail = mysql_fetch_array($reqmail))
	{			
       $mesBDD .= ";".$nomDB;
       $mesBDD .= "/" .$lignemail['customer_status'];
	}
}

mysql_close();

echo $mesBDD;
?>