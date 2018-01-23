<?php
include('common.php');
if($dbi->test_connexion()) {
	// print_r($_POST);
	$tab_temoin = array("nom", "prenom", "age", "sexe", "categorie");
	$tab_pattern = array("nom" => "#^([a-zA-Z'àâéèêôùûçÀÂÉÈÔÙÛÇ\s-]{1,30})$#i","prenom" => "#^([a-zA-Z'àâéèêôùûçÀÂÉÈÔÙÛÇ\s-]{1,30})$#i","age"=>"#[0-9]{2}#i");
	$extensions = array('jpg' , 'jpeg' , 'gif' , 'png');
	$infosForm = new UtilsPOST($_POST, $dbi, $tab_temoin, $tab_pattern);
	$modifier = htmlentities(trim(@$_POST['modifier']));
	$oldid = htmlentities(trim(@$_POST['oldid']));
	$infosFile = new UtilsFILE(@$_FILES['photo'], $extensions);
	if($infosFile->getBoolVerif()) {
		$infosFile->deplacerPhoto();
	}
	if(!$infosForm->getBoolVerif()) {
		//Probleme lors de recuperation du formulaire
		$infosMessage = '<header class="w3-container w3-red w3-center"><p>'.$infosForm->getErrorMessage().'</p></header>'.PHP_EOL;
		$infosMessage .= '<footer class="w3-container w3-center">'.PHP_EOL;
		$infosMessage .= '<a class="btn-return" href="index.php?page=ajout&role=admin"><button class="w3-bar-item w3-button w3-green w3-left-align"><i class="fa fa-angle-double-left"></i> Retour au formulaire</button></a>'.PHP_EOL;
		$infosMessage .= '<a class="btn-liste" href="index.php?page=liste&role=admin"><button class="w3-bar-item w3-button w3-dark-grey"><i class="fa fa-external-link"></i> Afficher la liste</button></a>'.PHP_EOL;
		$infosMessage .= '</footer>'.PHP_EOL;

	} else {
		//Le formulaire est bon
		if($modifier == "true") {
			$infosForm->updateUserInfos($oldid, $infosFile->getCheminImage());
			$user = $infosForm->getUser();
			$infosMessage = '<header class="w3-container w3-green w3-center"><p>Personnes modif&eacute;e avec success</p></header>'.PHP_EOL;
			$infosMessage .= '<div class="w3-container w3-center"><div class="w3-panel w3-border w3-light-grey w3-round-large">'.$user->getDescription().'</div></div>'.PHP_EOL;
			$infosMessage .= '<footer class="w3-container w3-center">'.PHP_EOL;
			$infosMessage .= '<a class="btn-return" href="index.php?page=modifier&id='.$oldid.'&role=admin"><button class="w3-bar-item w3-button w3-green w3-left-align"><i class="fa fa-angle-double-left"></i> Retour au formulaire</button></a>'.PHP_EOL;
			$infosMessage .= '<a class="btn-liste" href="index.php?page=liste&role=admin"><button class="w3-bar-item w3-button w3-dark-grey"><i class="fa fa-external-link"></i> Afficher la liste</button></a>'.PHP_EOL;
			$infosMessage .= '<a class="btn-profil" href="index.php?page=profil&id='.$user->getId().'&role=admin"><button class="w3-bar-item w3-button w3-red w3-right-align">Voir le profil de la personne <i class="fa fa-angle-double-right"></i></button></a>'.PHP_EOL;
			$infosMessage .= '</footer>'.PHP_EOL;
		} elseif($infosForm->getExistence()) {
			$user = $infosForm->getUser();
			// else {
			$infosMessage = '<header class="w3-container w3-red w3-center"><p>La personne que vous essayez d\'ajouter existe d&eacute;ja</p></header>'.PHP_EOL;
			$infosMessage .= '<footer class="w3-container w3-center">'.PHP_EOL;
			$infosMessage .= '<a class="btn-return" href="index.php?page=ajout&role=admin"><button class="w3-bar-item w3-button w3-green w3-left-align"><i class="fa fa-angle-double-left"></i> Retour au formulaire</button></a>'.PHP_EOL;
			$infosMessage .= '<a class="btn-liste" href="index.php?page=liste&role=admin"><button class="w3-bar-item w3-button w3-dark-grey"><i class="fa fa-external-link"></i> Afficher la liste</button></a>'.PHP_EOL;
			$infosMessage .= '<a class="btn-modify" href="index.php?page=modifier&id='.$user->getId().'&role=admin"><button class="w3-bar-item w3-button w3-red w3-right-align">Modifier la personne <i class="fa fa-angle-double-right"></i></button></a>'.PHP_EOL;
			$infosMessage .= '</footer>'.PHP_EOL;
			// }
		} else {
			if($infosForm->setNewUserInfos($infosFile->getCheminImage())) {
				if($user = $infosForm->getUser()) {
					$infosMessage = '<header class="w3-container w3-green w3-center"><p>Personnes ajout&eacute;e avec success</p></header>'.PHP_EOL;
					$infosMessage .= '<div class="w3-container w3-center"><div class="w3-panel w3-border w3-light-grey w3-round-large">'.$user->getDescription().'</div></div>'.PHP_EOL;
					$infosMessage .= '<footer class="w3-container w3-center">'.PHP_EOL;
					$infosMessage .= '<a class="btn-return" href="index.php?page=ajout&role=admin"><button class="w3-bar-item w3-button w3-green w3-left-align"><i class="fa fa-angle-double-left"></i> Retour au formulaire</button></a>'.PHP_EOL;
					$infosMessage .= '<a class="btn-liste" href="index.php?page=liste&role=admin"><button class="w3-bar-item w3-button w3-dark-grey"><i class="fa fa-external-link"></i> Afficher la liste</button></a>'.PHP_EOL;
					$infosMessage .= '<a class="btn-profil" href="index.php?page=profil&id='.$user->getId().'&role=admin"><button class="w3-bar-item w3-button w3-red w3-right-align">Voir le profil de la personne <i class="fa fa-angle-double-right"></i></button></a>'.PHP_EOL;
					$infosMessage .= '</footer>'.PHP_EOL;
				} else {
					$infosMessage = '<header class="w3-container w3-red w3-center"><p>L\'ajout de la personne a rencontr&eacute; un probleme innatendu.</p></br>Vous pourrez reprendre dans quelques instants</p></header>'.PHP_EOL;
					$infosMessage .= '<footer class="w3-container w3-center">'.PHP_EOL;
					$infosMessage .= '<a class="btn-return" href="index.php?page=ajout&role=admin"><button class="w3-bar-item w3-button w3-green w3-left-align"><i class="fa fa-angle-double-left"></i> Retour au formulaire</button></a>'.PHP_EOL;
					$infosMessage .= '<a class="btn-liste" href="index.php?page=liste&role=admin"><button class="w3-bar-item w3-button w3-dark-grey"><i class="fa fa-external-link"></i> Afficher la liste</button></a>'.PHP_EOL;
					$infosMessage .= '</footer>'.PHP_EOL;
				}
			} else {
				$infosMessage = '<header class="w3-container w3-red w3-center"><p>L\'ajout de la personne dans la base de donn&eacute;es a rencontr&eacute; un probleme.</br>Vous pourrez reprendre dans quelques instants</p></header>'.PHP_EOL;
				$infosMessage .= '<footer class="w3-container w3-center">'.PHP_EOL;
				$infosMessage .= '<a class="btn-return" href="index.php?page=ajout&role=admin"><button class="w3-bar-item w3-button w3-green w3-left-align"><i class="fa fa-angle-double-left"></i> Retour au formulaire</button></a>'.PHP_EOL;
				$infosMessage .= '<a class="btn-liste" href="index.php?page=liste&role=admin"><button class="w3-bar-item w3-button w3-dark-grey"><i class="fa fa-external-link"></i> Afficher la liste</button></a>'.PHP_EOL;
				$infosMessage .= '</footer>'.PHP_EOL;
			}
		}
		
	}
	$dbi->db_close();
	echo $infosMessage;
}
?>