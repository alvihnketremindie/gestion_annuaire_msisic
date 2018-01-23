<!doctype html>
<html lang="fr">
<head>
	<title>Liste en mode trombinoscope</title>
	<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../css/w3css.css">
	<link rel="stylesheet" href="../css/font-awesome.min.css">

</head>
<body>
<?php
include('common.php');
if($dbi->test_connexion()) {
	$html = "";
	$resultat = $dbi->db_find_record("personnes", "*", array());
	while ($infos=$dbi->db_fetch($resultat)) {
	   if($infos){
			$personnes = new APP_Personnes($infos);
			if($personnes) {
				$html .= $personnes->getCardInfos();
			}
		}
	}
	$dbi->db_close();
	echo $html;
}
?>
<script>
// Open the Modal
function openModal(theModal) {
	console.log(theModal);
	document.getElementById(theModal.id).style.display = "block";
}

// Close the Modal
function closeModal(theModal) {
	console.log(theModal);
	document.getElementById(theModal.id).style.display = "none";
}
</script>
</body>
</html>