<?php
$page = htmlentities(trim(@$_GET['page']));
$role = htmlentities(trim(@$_GET['role']));
if(!isset($page) or empty($page)) {
	header('Location: index.php?page=accueil&role='.$role);
}
if(!isset($role) or empty($role)) {
	$role = 'user';
}
if($role == 'admin') {
?>

<!-- First Grid -->
<div class="w3-row-padding w3-padding w3-container">
	<div class="w3-content">
		<div class="w3-twothird">
			<p class="w3-text-grey">Ajouter une personne</p>
			<ul class="w3-ul w3-hoverable">L'administrateur doit entr&eacute;e au minimum les informations suivante :
				<li>Nom</li>
				<li>Pr&eacute;nom</li>
				<li>Age</li>
			</ul>
			<p class="w3-text-grey">Des infos additionelles peuvent aussi &ecirc;tre apport&eacute; par la suite.</p>
		</div>
		<div class="w3-third w3-center">
			<a href="index.php?page=ajout&role=admin"><button class="w3-button w3-black w3-padding-large w3-large w3-margin-top">Acceder &agrave; la section <i class="fa fa-address-card-o"></i></button></a>
		</div>
	</div>
</div>
<?php
}
?>
<!-- Second Grid -->
<div class="w3-row-padding w3-light-grey w3-padding w3-container">
  <div class="w3-content">
    <div class="w3-third w3-center">
		<a href="index.php?page=liste&role=&role=<?php echo $role ?>"><button class="w3-button w3-black w3-padding-large w3-large w3-margin-top">Acceder &agrave; la section <i class="fa fa-bars"></i></button></a>
    </div>
    <div class="w3-twothird">
		<p class="w3-text-grey">Liste des personnes</p>
		<ul class="w3-ul w3-hoverable">La liste peut &ecirc;tre afficher dans les modes suivants :
			<li>Liste (avec option rechercher et trier)</li>
			<li>Trombinoscope</li>
		</ul>
    </div>
  </div>
</div>

<?php
include('common.php');
if($role == 'user') {
	compteurAnnuaire('accueil');
}
?>