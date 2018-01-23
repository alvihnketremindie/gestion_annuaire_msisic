<?php

class BDD_MSQLI extends BDDAbstract implements BDD {
	
    /**
     * Constructor
     */
    function __construct($dbParams) {
        parent::__construct($dbParams);
    }

    function db_connexion() {
        try {
            $dbAccess = mysqli_connect($this->dbParams['host'], $this->dbParams['user'], $this->dbParams['password'], $this->dbParams['database']);
        } catch (Exception $e) {
            $this->error_code_con = $e->getCode();
            $this->error_message_con = $e->getMessage();
            $dbAccess = null;
            $this->db_connect_error();
        }
        if (isset($dbAccess)) {
            /* Modification du jeu de resultats en utf8 */
            try {
                mysqli_set_charset($dbAccess, "utf8");
            } catch (Exception $ex) {
                $this->error_code_req = $ex->getCode();
                $this->error_message_req = $ex->getMessage();
                $dbAccess = null;
                $this->db_error("Erreur lors du chargement du jeu de caracteres utf8 :");
            }
        }
        return $dbAccess;
    }

    public function test_connexion() {
        if ($this->connexion) return mysqli_ping($this->connexion);
		else return false;
    }

    public function db_close() {
        if ($this->connexion) {
            mysqli_close($this->connexion);
        }
    }

    public function db_reconnect() {
        $this->db_close();
        try {
            $this->connexion = db_connexion();
        } catch (Exception $e_reco) {
            $to_log = "Tentative de Reconnexion a la BDD avec les parametres : " . $this->dbParams['host'] . ", " . $this->dbParams['user'] . ", " . $this->dbParams['password'] . ", " . $this->dbParams['database'];
            $this->db_log("bdd_reconnect", $e_reco->getCode(), $e_reco->getMessage(), $to_log);
        }
    }

    public function db_get_affected_rows() {
        return $this->affected_rows;
    }

    private function db_escape_string($string) {
        if (get_magic_quotes_runtime()) {
            $string = stripslashes($string);
        }
        if (!preg_match("/^adddate\\(/i", $string)) {
            return mysqli_real_escape_string($this->connexion, $string);
        }
        return $string;
    }

	public function db_insert_id() {
		return @mysqli_insert_id($this->connexion);
    }
	
	public function db_insert($inserTable, $insertData, $ignore = false, $on_duplicate = false) {
		return $this->db_mysql_insert($inserTable, $insertData, $ignore, $on_duplicate);
    }
	
	public function db_query($sqlQuery) {
        if (!$this->test_connexion()) {
            $this->db_reconnect();
        }
        try {
            $query_id = mysqli_query($this->connexion, $sqlQuery);
            $this->affected_rows = mysqli_affected_rows($this->connexion);
			$this->db_query_log($this->affected_rows, $sqlQuery);
        } catch (Exception $e_query) {
            $this->error_code_req = $e_query->getCode();
            $this->error_message_req = $e_query->getMessage();
            $query_id;
            $this->affected_rows = 0;
            $this->db_error($sqlQuery);
        }
        return $query_id;
    }

    private function db_free_result($query_id) {
        try {
            mysqli_free_result($query_id);
        } catch (Exception $ex) {
            $to_log = "Probleme dans la Liberation de resultat";
            $this->db_log("bdd_free_result", $ex->getCode(), $ex->getMessage(), $to_log);
        }
    }

    public function db_fetch($query_id) {
        try {
            $record = mysqli_fetch_assoc($query_id);
        } catch (Exception $ex) {
            $to_log = "Probleme dans la recherche de resultat sous forme de tableau associatif";
            $this->db_log("bdd_fetch_array", $ex->getCode(), $ex->getMessage(), $to_log);
            $record = null;
        }
        return $record;
    }

    public function db_num_rows($query_id) {
        try {
            $row = mysqli_num_rows($query_id);
        } catch (Exception $ex) {
            $to_log = "Probleme dans le compte des numeros de lignes";
            $this->db_log("bdd_nums_rows", $ex->getCode(), $ex->getMessage(), $to_log);
            $row = 0;
        }
        return $row;
    }

    public function db_num_fields($query_id) {
        try {
            $row = mysqli_num_fields($query_id);
        } catch (Exception $ex) {
            $to_log = "Probleme dans le compte du nombre de champs";
            $this->db_log("bdd_nums_rows", $ex->getCode(), $ex->getMessage(), $to_log);
            $row = 0;
        }
        return $row;
    }
}

?>
