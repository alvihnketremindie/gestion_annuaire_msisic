<?php
$id_personne = htmlentities(trim(@$_GET['id']));
$page = htmlentities(trim(@$_GET['page']));
$role = htmlentities(trim(@$_GET['role']));
if(!isset($role) or empty($role) or $role != 'admin') {
	header('Location: index.php');
}
if(!isset($page) or empty($page)) {
	header('Location: index.php?page=modifier&id='.$id_personne.'&role='.$role);
}
include_once('common.php');

if($dbi->test_connexion()) {
	$id_personne = intval($id_personne);
	if($rowPersonne = getPersonneInfos($dbi, array('id' =>$id_personne))) {
		$personneLinks = @$rowPersonne['urls'];
		$result = $dbi->db_find_record("categorie", "*", array("where" => "statut = 'YES'"));
		if($result) {
			$rowInfosProfils['optioncategorie'] = "";
			while ($row = $dbi->db_fetch($result)) {
				$rowInfosProfils['optioncategorie']  .= '<option value="'.$row["nom_categorie"].'"';
				if($row["nom_categorie"] == $rowPersonne['categorie']){
					$rowInfosProfils['optioncategorie']  .= ' selected';
					
				}
				$rowInfosProfils['optioncategorie']  .= '>'.$row["nom_categorie"].'</option>'.PHP_EOL;
			}
			$result2 = $dbi->db_find_record("pages_persos", "*", array("where" => "statut = 'YES'"));
			if($result2) {
				$rowInfosProfils['contact_and_link'] = '';
				while ($row2 = $dbi->db_fetch($result2)) {
					$valLink = "";
					if(is_array($personneLinks)) {
						foreach($personneLinks as $url_perso_link){
							if($row2["titre"] ==  $url_perso_link['titre']) {
								$valLink = $url_perso_link['lien'];
							}
						}
					}
					$rowInfosProfils['contact_and_link'] .= '<div class="w3-row w3-section">
	<div class="w3-col" style="width:25%">
		<i class="w3-xxlarge fa fa-'.$row2["icone"].'"></i>
		<label class="w3-text"><b>'.$row2["titre"].'</b></label>
	</div>
	<div class="w3-rest">
		<input class="w3-input w3-border" type="text" id="'.$row2["icone"].'" name="urllien___'.$row2["icone"].'" value="'.$valLink.'" >
	</div>
</div>'.PHP_EOL;
				}
				$rowInfosProfils['oldid'] = $id_personne;
				$rowInfosProfils['nom'] = $rowPersonne['nom'];
				$rowInfosProfils['prenom'] = $rowPersonne['prenom'];
				$rowInfosProfils['age'] = $rowPersonne['age'];
				if($rowPersonne['sexe'] += "homme") {
					$rowInfosProfils['selected_male'] = 'selected';
					$rowInfosProfils['selected_female'] = "";
				} elseif($rowPersonne['sexe'] == "femme") {
					$rowInfosProfils['selected_male'] = "";
					$rowInfosProfils['selected_female'] =  'selected';
				} else {
					$rowInfosProfils['selected_male'] = "";
					$rowInfosProfils['selected_female'] = "";
				}
				$html = afficheInfos($rowInfosProfils, trim(file_get_contents(FORMULAIRE_MODIF_HTML)));
				echo $html;
			}
		}
	} else {
		//La personne n'a pu etre trouve
		$infosMessage = '<header class="w3-container w3-red w3-center"><p>La personne n\'a pu etre trouv&eacute;e</p></header>'.PHP_EOL;
		$infosMessage .= '<footer class="w3-container w3-center">'.PHP_EOL;
		$infosMessage .= '<a class="btn-return" href="index.php?page=accueil"><button class="w3-bar-item w3-button w3-green w3-left-align"><i class="fa fa-angle-double-left"></i> Retour &agrave; l\'accueil</button></a>'.PHP_EOL;
		$infosMessage .= '<a class="btn-liste" href="index.php?page=liste"><button class="w3-bar-item w3-button w3-dark-grey"><i class="fa fa-external-link"></i> Afficher la liste</button></a>'.PHP_EOL;
		$infosMessage .= '</footer>'.PHP_EOL;
		echo $infosMessage;
	}
	$dbi->db_close();
} else {
	header('Location: index.php');
}
?>