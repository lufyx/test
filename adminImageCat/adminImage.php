<html> 
	<head> 
	
		<title>Image - Catégorie</title>
		
		<script type="text/javascript">
			function actualiserTableau()
			{
				var indexBase = document.getElementById("listeClient").selectedIndex;
				var nomBase = document.getElementById("listeClient").options[indexBase].value;
				var laVersion;
				if (document.getElementsByName("version")[0].checked) //tablet
				{
					laVersion = document.getElementsByName("version")[0].value;
					document.getElementById("largeur").innerHTML = "180";
					document.getElementById("hauteur").innerHTML = "170";
				}
				else //phone
				{
					laVersion = document.getElementsByName("version")[1].value;
					document.getElementById("largeur").innerHTML = "95";
					document.getElementById("hauteur").innerHTML = "95";
				}
				
				var OAjax;
				if (window.XMLHttpRequest) OAjax = new XMLHttpRequest();
				else if (window.ActiveXObject) OAjax = new ActiveXObject('Microsoft.XMLHTTP');
				OAjax.open('POST',"chargeTableau.php",true);
				OAjax.setRequestHeader('Content-type','application/x-www-form-urlencoded');

				OAjax.onreadystatechange = function()
				{
					if (OAjax.readyState == 4)
					{
						document.getElementById("tableauCat").innerHTML = OAjax.responseText.split("#")[0];
					}
				}
				OAjax.send('varBase='+nomBase+'&varVersion='+laVersion);
			}
		</script>
		
	</head>
	
	<body style="text-align:center;" onLoad="actualiserTableau()">
		<h1>Admin catégories</h1>
		Client :
		<select id="listeClient" onChange="actualiserTableau()">
		<?php
		$tabBases = file('mesBases.txt');
		
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
			
			echo "<option value='" .$nomDB. "'>" .$infoBase[1]. "</option>";
		}
		?>
		</select>
		
		<br/><br/>
		
		Version <input type="radio" name="version" value="tablet" onClick="actualiserTableau()" checked/> tablette <input type="radio" name="version" value="phone" onClick="actualiserTableau()"/> téléphone
		
		<br/><br/>
		
		Catégories :
		
		Tailles des images - Largeur :<span id="largeur">180</span> px / Hauteur :<span id="hauteur">170</span> px
		
		<br/><br/>
		
		<table style="margin:auto;border-collapse:collapse;text-align:center">
			<thead>
				<th>Nom</th>
				<th>Image</th>
			</thead>
				
			
			<tbody id="tableauCat">
			</tbody>
			
		</table>
	</body>
</html>