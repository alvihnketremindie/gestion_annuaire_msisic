<?php
error_reporting(E_ALL);
include_once('fonction.php');
define ('ROOT', dirname(__FILE__));
define ('ROOT_PARENT', dirname(dirname(__FILE__)));
define('CLASS_DIRECTORY', ROOT . '/class/');
define ('IMG_PATH', ROOT_PARENT.'/img/');
define ('PROFIL_HTML', ROOT_PARENT.'/html/profil.html');
define ('CHIP_HTML', ROOT_PARENT.'/html/chip.html');
define ('CARD_HTML', ROOT_PARENT.'/html/card.html');
define ('LISTE_HTML', ROOT_PARENT.'/html/liste.html');
define ('FORMULAIRE_AJOUT_HTML', ROOT_PARENT.'/html/ajout_personne.html');
define ('FORMULAIRE_MODIF_HTML', ROOT_PARENT.'/html/modifier_personne.html');
define ('LOG_DIR', ROOT . '/log/');
define ('FILE_COMPTEUR', LOG_DIR.'compteur.txt');
define('DB_SERVER', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'tp1_php_imta');
define("DEBUG", false);
$dbi = db_connect();
$publiOK = false;
function __autoload($class_name) {
    $class_file = CLASS_DIRECTORY . $class_name . '.php';
    if (!file_exists($class_file)) {
        die('Class ' . $class_name . ' error');
    }
    include_once ($class_file);
}

?>