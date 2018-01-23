<!DOCTYPE html>
<html>
<head>
	<title>TP PHP IMT ATLANTIQUE</title>
	<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/w3css.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
</head>
<body>
<!-- Header -->
<header class="w3-container w3-blue w3-center">
  <h1 class="w3-margin w3-jumbo">Bienvenue sur l'annuaire de l'IMT ATLANTIQUE</h1>
</header>

<!-- First Grid -->
<div class="w3-row-padding w3-padding w3-container">
	<div class="w3-content">
		<div class="w3-twothird">
			<ul class="w3-ul w3-hoverable w3-text-grey">Fonctionnalit&eacute;es administrateur :
				<li>Voir la liste des personnes dans l'annuaire</li>
				<li>Ajouter une personne dans l'annuaire</li>
				<li>Modifer les informations d'une personne</li>
				<li>Supprimer une personne dans l'annuaire</li>
				<li>Obtenir un tableau de statistiques sur l'annuaire</li>
			</ul>
		</div>
		<div class="w3-third w3-center">
			<a href="php/index.php?page=accueil&role=admin"><button class="w3-button w3-black w3-padding-large w3-large w3-margin-top">Access Administrateur <i class="fa fa-address-card-o"></i></button></a>
		</div>
	</div>
</div>

<!-- Second Grid -->
<div class="w3-row-padding w3-light-grey w3-padding w3-container">
	<div class="w3-content">
		<div class="w3-third w3-center">
			<a href="php/index.php?page=accueil&role=user"><button class="w3-button w3-black w3-padding-large w3-large w3-margin-top">Access Utilisateur <i class="fa fa-address-card"></i></button></a>
		</div>
		<div class="w3-twothird">
			<ul class="w3-ul w3-hoverable w3-text-grey">Fonctionnalit&eacute;es utilisateur :
				<li>Voir la liste des personnes dans l'annuaire</li>
				<li>Voir le profil d'une personne dans l'annuaire</li>
				<li>Envoyer un mail &agrave; une personne dans l'annuaire</li>
			</ul>
		</div>
  </div>
</div>
</body>
</html>