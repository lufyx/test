<?
$nomfichier = $_POST["nomDossierEtFichier"];

if(isset($_FILES['explorateurImg']))
{
	$temp = $_FILES['explorateurImg']['tmp_name'];
	
	if (move_uploaded_file($temp, $nomfichier))
	{
		header('Location: adminImage.php');
	}
	else
	{
		echo "erreur";
	}
}
?>