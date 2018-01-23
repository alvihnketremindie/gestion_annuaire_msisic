<?php
$id_personne = htmlentities(trim(@$_GET['id']));
$page = htmlentities(trim(@$_GET['page']));
$role = htmlentities(trim(@$_GET['role']));
if(!isset($role) or empty($role) or $role != 'admin') {
	header('Location: index.php');
}
if(!isset($page) or empty($page)) {
	header('Location: index.php?page=supprimer&id='.$id_personne.'&role='.$role);
}
include_once('common.php');
if($dbi->test_connexion()) {
	$id_personne = intval($id_personne);
	if($id_personne and $rowPersonne = getPersonneInfos($dbi, array('id' =>$id_personne))) {
		$sql = "update personnes set statut = 'NO' where id = $id_personne";
		$dbi->db_query($sql);
		
		$infosMessage = '<header class="w3-container w3-green w3-center"><p>Personnes supprim&eacute;e avec success (id => '.$id_personne.')</p></header>'.PHP_EOL;
		// $infosMessage .= '<div class="w3-container w3-center"><div class="w3-panel w3-border w3-light-grey w3-round-large">id => '.$id_personne.'</div></div>'.PHP_EOL;
	} else {
		//La personne n'a pu etre trouve
		$infosMessage = '<header class="w3-container w3-red w3-center"><p>La personne n\'a pu etre trouv&eacute;e</p></header>'.PHP_EOL;
	}
	$infosMessage .= '<footer class="w3-container w3-center">'.PHP_EOL;
	$infosMessage .= '<a class="btn-return" href="index.php?page=accueil"><button class="w3-bar-item w3-button w3-green w3-left-align"><i class="fa fa-angle-double-left"></i> Retour &agrave; l\'accueil</button></a>'.PHP_EOL;
	$infosMessage .= '<a class="btn-liste" href="index.php?page=liste"><button class="w3-bar-item w3-button w3-dark-grey"><i class="fa fa-external-link"></i> Afficher la liste</button></a>'.PHP_EOL;
	$infosMessage .= '</footer>'.PHP_EOL;
	$dbi->db_close();
	echo $infosMessage;
} else {
	header('Location: index.php');
}
?>