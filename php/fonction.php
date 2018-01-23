<?php

/**
 * Fonction de connection a la base de donnes.
 * En cas d'erreur l'execution est interrompu et un message est levee
 * @return : Renvoi l'information de connection.
*/
function db_connect(){
	$dbParams = array('host' => DB_SERVER, 'user' => DB_USER, 'password' => DB_PASS, 'database' => DB_NAME);
	return new BDD_MYSQL($dbParams);
}

function file_exists_in_dir($dir, $filename) {
	return file_exists("$dir/$filename");
}

function getPersonneInfos($dbi, $search) {
	$row = null;
	// /*
	$search['statut'] = 'YES';
	$keys = array_map('htmlentities', array_keys($search));
	$htmlentities_values = array_map('htmlentities', $search);
	$escaped_values = array_map(array($dbi, 'buildAttributes'), $htmlentities_values);
	$count = count($search);
	$combine = array_combine($keys, $escaped_values);
	$where = buildMessageCondition($combine, "and");
	// */
	$result = $dbi->db_find_record("personnes", "*", array("where" => $where));
	if($result) {
		if($row = $dbi->db_fetch($result)) {
			$row['urls'] = getPersonneLinksInfos ($dbi, $row['id']);
		}
	}
	return $row;
}

function getPersonneLinksInfos ($dbi, $id) {
	$tableLink = "url_pages_persos url JOIN pages_persos page";
	$findLink = "page.titre as titre, page.icone as icone, url.url as lien";
	$findParamsLink = array(
		"on" => "url.titres_page = page.titre",
		"where" =>"url.id_personne = $id  AND page.statut = 'YES' AND url.statut = 'YES' AND url.url != '#' AND url.url != ''"
	);
	$resultatLink = $dbi->db_find_record($tableLink, $findLink, $findParamsLink, true);
	return $resultatLink;
}

function buildMessageCondition($tab, $condition) {
	$out = array();
	foreach($tab as $cle => $valeur) {
		if(!is_numeric($valeur)) {
		}
		$out[] = "$cle = $valeur";
	}
	return implode(" $condition ", $out);
}

function logger($type, $to_log) {
	if(DEBUG) {
		$log_chemin = LOG_DIR . @date("Ymd") . ".log";
		$aLogger = "[" . $type . "]  " . " date_log=" . @date("Y-m-d H:i:s") ." | ". parse_reponse($to_log) . PHP_EOL;
		// file_put_contents($log_chemin, $aLogger, FILE_APPEND);
		// echo $aLogger."</br>";
	}
}

function testerExistence($variable) {
    if ((isset($variable) and ! empty($variable)) or $variable === "0" or $variable === 0) {
        return true;
    } else {
        return false;
    }
}

function parse_reponse($array) {
    $toReturn = '';
    if (is_array($array)) {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $toReturn .= ' [ ' . $key . ' => (' . parse_reponse($value) . ')' . ' ] ';
            } else {
                $toReturn .= ' | ' . $key . '=' . $value;
            }
        }
    } else {
        $toReturn .= $array;
    }
    return nettoyerChaine($toReturn);
}

function enleverCaracteresSpeciaux($text) {
    $utf8 = array(
        '/[áàâãªä]/u' => 'a',
        '/[ÁÀÂÃÄ]/u' => 'A',
        '/[ÍÌÎÏ]/u' => 'I',
        '/[íìîï]/u' => 'i',
        '/[éèêë]/u' => 'e',
        '/[ÉÈÊË]/u' => 'E',
        '/[óòôõºö]/u' => 'o',
        '/[ÓÒÔÕÖ]/u' => 'O',
        '/[úùûü]/u' => 'u',
        '/[ÚÙÛÜ]/u' => 'U',
        '/ç/' => 'c', '/Ç/' => 'C', '/ñ/' => 'n', '/Ñ/' => 'N',
        '/Œ/' => 'OE', '/œ/' => 'oe', '/æ/' => 'ae', '/Æ/' => 'AE',
        '/–/' => '-', '/[‹«]/u' => '<', '/[›»]/u' => '>', '/[“‘‚”’‚“”„"]/u' => "'", '/ /' => ' '
    );
    return preg_replace(array_keys($utf8), array_values($utf8), $text);
}

function nettoyerChaine($string) {
    $dict = array("\r" => '', "\t" => ' ', '{CR}' => " ", "\n\n" => " ", "  " => " ", "</br>" => " ");
    $string = str_ireplace(array_keys($dict), array_values($dict), $string);
	$string = preg_replace('/\s\s+/', ' ', $string);
    $string = str_ireplace('&#039;', "'", $string);
    $string = str_ireplace('&quot;', '"', $string);
    $string = enleverCaracteresSpeciaux($string);
    return $string;
}

function afficheInfos($array, $infosPages) {
	foreach($array as $key=>$value) {
		$patterns ='#\$'.$key.'#i';
		// $affiche .= ucfirst($key)." : ".$value."</br>";
		$infosPages = preg_replace($patterns, $value, $infosPages);
	}
	return $infosPages;
}

function retourneEmail($prenom, $nom) {
	$nom = strtolower(nettoyerChaine($nom));
	$prenom = strtolower(nettoyerChaine($prenom));
	$dict = array(" " => '', "'" => '');
	$nom = str_ireplace(array_keys($dict), array_values($dict), $nom);
    $prenom = str_ireplace(array_keys($dict), array_values($dict), $prenom);
	return strtolower($prenom).".".strtolower($nom)."@telecom-bretagne.eu";
}

function convertHTMLData ($array) {
	return array_combine(array_keys($array), array_map('htmlentities', $array));
}
	
function verificationCoherenceParametre ($array, $tab_temoin) {
	// $message = "";
	foreach($tab_temoin as $keys){
		if(!isset($array[$keys]))	return "Le champ ".$keys." n'a pas pu etre recupere";
		if(empty($array[$keys]) && $array[$keys] !== 0 && $array[$keys] !== "0")	return "Le champ ".$keys." ne doit pas rester vide";
	}
	if (!is_numeric ($array["age"]))	return "La valeur du champ age doit etre numerique";
	return "";
}

function compteurAnnuaire($nomPage) {
	$compte = json_decode(trim(@file_get_contents(FILE_COMPTEUR)), true);
	if(!isset($compte['page'][$nomPage]['vues']) or empty($compte['page'][$nomPage]['vues'])) {
		$compte['page'][$nomPage]['vues'] = 0;
	}
	if(!isset($compte['visite_utilisateur']) or empty($compte['visite_utilisateur'])) {
		$compte['visite_utilisateur'] = 0;
	}
	if(!isset($_SESSION['compteur_de_visite'])) {
		$_SESSION['compteur_de_visite'] = 'visite';
		$compte['visite_utilisateur']++;
	}
	$compte['page'][$nomPage]['vues']++;
	$compte['page'][$nomPage]['visite'] = $compte['visite_utilisateur'];
	$contents = json_encode($compte);
	file_put_contents(FILE_COMPTEUR, $contents);
}

function infosStatsAnnuaire() {
	$nomFichierPagesVues = LOG_DIR.'compteur.txt';
	$compte = json_decode(trim(@file_get_contents(FILE_COMPTEUR)), true);
	return $compte;
}

?>