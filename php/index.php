<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<title>TP PHP IMT ATLANTIQUE</title>
	<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../css/w3css.css">
	<link rel="stylesheet" href="../css/font-awesome.min.css">
	<script src="../js/w3.js"></script>
</head>
<body>
<div class="w3-col-padding">
<div class="w3-row m4 l3">
<nav class="w3-bar w3-light-grey w3-border  w3-card-4">
<!-- Barre de menu -->
<?php
$role = htmlentities(trim(@$_GET['role']));
if(!isset($role) or empty($role)) {
	$role = 'user';
}
if($role == 'admin') {
echo'<a href="index.php?page=accueil&role=admin" class="w3-bar-item w3-mobile w3-hover-green"><button class="w3-button w3-hover-black"><i class="fa fa-home"></i> Accueil</button></a>
		<a href="index.php?page=ajout&role=admin" class="w3-bar-item w3-mobile w3-hover-green"><button class="w3-button w3-hover-black"><i class="fa fa-address-card-o"></i> Ajout</button></a>
		<a href="index.php?page=liste&role=admin" class="w3-bar-item w3-mobile w3-hover-green"><button class="w3-button w3-hover-black"><i class="fa fa-bars"></i> Annuaire</button></a>
		<a href="index.php?page=stat&role=admin" class="w3-bar-item w3-mobile w3-hover-green"><button class="w3-button w3-hover-black"><i class="fa fa-line-chart"></i> Statistiques</button></a>
'.PHP_EOL;
}
else {
echo '<a href="index.php?page=accueil&role=user" class="w3-bar-item w3-mobile w3-hover-green"><button class="w3-button w3-hover-black"><i class="fa fa-home"></i> Accueil</button></a>
		<a href="index.php?page=liste&role=user" class="w3-bar-item w3-mobile w3-hover-green"><button class="w3-button w3-hover-black"><i class="fa fa-bars"></i> Annuaire</button></a>
'.PHP_EOL;
}
?>
<a href="../index.php" class="w3-bar-item w3-mobile w3-hover-green w3-right"><button class="w3-button w3-hover-black"><i class="fa fa-arrow-left"></i> Menu Principal</button></a>
</nav>
</div>
<div class="w3-rest">

<?php
$page = htmlentities(trim(@$_GET['page']));
if(strcasecmp($page, 'ajout') == 0) {
	$page = "ajout_personne.php";
} elseif(strcasecmp($page, 'resultat') == 0) {
	$page = "resultat-formulaire.php";
} elseif(strcasecmp($page, 'liste') == 0) {
	$page = "liste.php";
} elseif(strcasecmp($page, 'modifier') == 0) {
	$page = "modifier_personne.php";
} elseif(strcasecmp($page, 'supprimer') == 0) {
	$page = "supprimer_personne.php";
} elseif(strcasecmp($page, 'stat') == 0) {
	$page = "stats_annuaire.php";
} elseif(strcasecmp($page, 'profil') == 0) {
	$page = "profil.php";
} else {
	$page = "accueil.php";
}
include_once($page);

?>

</div>
</div>
</body>
</html>
