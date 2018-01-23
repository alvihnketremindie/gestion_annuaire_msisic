<?php
$page = htmlentities(trim(@$_GET['page']));
$role = htmlentities(trim(@$_GET['role']));
if(!isset($role) or empty($role)) {
	$role = 'user';
}
if(!isset($page) or empty($page)) {
	header('Location: index.php?page=liste&role='.$role);
}
?>
<div class="w3-border">
<div class="w3-bar" style="display:inline-block">
	<button class="w3-bar-item w3-button w3-ripple w3-border w3-border-black w3-round-large" onclick="affichage_liste()" style="width:50%">Mode liste</button>
	<button class="w3-bar-item w3-button w3-ripple w3-border w3-border-black w3-round-large" onclick="affichage_trombinoscope()" style="width:50%">Mode Trominoscope</button>
</div>
<div class="w3-border" style="max-height: 500px; max-width: 2500px; overflow-y: auto; overflow-x: auto;">
<?php
include('common.php');
if($dbi->test_connexion()) {
	$html = trim(file_get_contents(LISTE_HTML));
	$tab['table_elements'] = "";
	$html_trombinoscope = "";
	if($role and $role == "admin") $tab['admin'] = "<th>Modifier</th><th>Effacer</th>";
	else  $tab['admin'] = "";
	$resultat = $dbi->db_find_record("personnes", "*", array("where"=>"statut = 'YES'", "order"=>"date_inscription desc"));
	while ($infos=$dbi->db_fetch($resultat)) {
	   if($infos){
			$personnes = new APP_Personnes($infos);
			if($personnes) {
				$tab['table_elements'] .= $personnes->getListeInfos($role);
				$html_trombinoscope .= $personnes->getCardInfos($role);
			}
		}
	}
	$dbi->db_close();
	$html = afficheInfos($tab, $html);
	
	$html_affichage_liste = "
<div id='html_affichage_liste' class='w3-container'>
$html
</div>
";
	$html_affichage_trombinoscope = "
<div id='html_affichage_trombinoscope' class='w3-container'>
$html_trombinoscope
</div>
";
	echo $html_affichage_liste;
	echo $html_affichage_trombinoscope;
}
if($role == 'user') {
	compteurAnnuaire('annuaire');
}
?>
</div>
</div>
<script>
affichage_liste();
function affichage_liste() {
	w3.hide('#html_affichage_trombinoscope');
	w3.show('#html_affichage_liste');
}
function affichage_trombinoscope() {
	w3.hide('#html_affichage_liste');
	w3.show('#html_affichage_trombinoscope');
}
</script>
