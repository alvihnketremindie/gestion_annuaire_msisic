<?php
$role = htmlentities(trim(@$_GET['role']));
$page = htmlentities(trim(@$_GET['page']));
if(!isset($role) or empty($role) or $role != 'admin') {
	header('Location: index.php');
}
if(!isset($page) or empty($page)) {
	header('Location: index.php?page=ajout&role='.$role);
}
include_once('common.php');
if($dbi->test_connexion()) {
	$result = $dbi->db_find_record("categorie", "*", array("where" => "statut = 'YES'"));
	if($result) {
		$rowInfosProfils['optioncategorie'] = "";
		while ($row = $dbi->db_fetch($result)) {
		   $rowInfosProfils['optioncategorie']  .= '<option value="'.$row["nom_categorie"].'">'.$row["nom_categorie"].'</option>'.PHP_EOL;
		}
		
		$result2 = $dbi->db_find_record("pages_persos", "*", array("where" => "statut = 'YES'"));
		if($result2) {
			$rowInfosProfils['contact_and_link'] = '';
			while ($row2 = $dbi->db_fetch($result2)) {
			   // $texte .= '<option value="'.$row["nom_categorie"].'">'.$row["nom_categorie"].'</option>';
			   $rowInfosProfils['contact_and_link'] .= '<div class="w3-row w3-section">
	<div class="w3-col" style="width:25%">
		<i class="w3-xxlarge fa fa-'.$row2["icone"].'"></i>
		<label class="w3-text"><b>'.$row2["titre"].'</b></label>
	</div>
	<div class="w3-rest">
		<input class="w3-input w3-border" type="text" id="'.$row2["icone"].'" name="urllien___'.$row2["icone"].'" placeholder="http://">
	</div>
</div>'.PHP_EOL;
			}
			$html = afficheInfos($rowInfosProfils, trim(file_get_contents(FORMULAIRE_AJOUT_HTML)));
			echo $html;
		}
	}
	$dbi->db_close();
} else {
	header('Location: index.php');
}
?>