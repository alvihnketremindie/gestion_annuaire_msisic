<?php
$page = htmlentities(trim(@$_GET['page']));
$id = htmlentities(trim(@$_GET['id']));
$role = htmlentities(trim(@$_GET['role']));
if(!isset($role) or empty($role)) {
	$role = 'user';
}
if(!isset($page) or empty($page)) {
	header('Location: index.php?page=profil&id='.$id.'&role='.$role);
}

include('common.php');
if($dbi->test_connexion()) {
	$id = intval($id);
	if(isset($id) and !empty($id)) {
		$infos = getPersonneInfos($dbi, array('id' =>$id));
		if($infos){
			$personnes = new APP_Personnes($infos);
			if($personnes) {
				echo $personnes->getProfilInfos();
				$publiOK = true;
			}
		}
	}
	$dbi->db_close();
}

$infosMessage = '<footer class="w3-container w3-center">'.PHP_EOL;
$infosMessage .= '<a class="btn-liste" href="index.php?page=liste&role='.$role.'"><button class="w3-bar-item w3-button w3-dark-grey"><i class="fa fa-external-link"></i> Afficher la liste</button></a>'.PHP_EOL;
$infosMessage .= '</footer>'.PHP_EOL;
echo $infosMessage;
if(!$publiOK) {
	header('Location: index.php?page=liste&role='.$role);
}
if($role == 'user') {
	compteurAnnuaire('profil');
}
?>