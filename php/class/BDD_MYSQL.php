<?php

class BDD_MYSQL extends BDDAbstract implements BDD {
	
	/**
     * Constructor
     */
    function __construct($dbParams) {
        parent::__construct($dbParams);
    }
	
    public function db_connexion() {
		$dbAccess = @mysql_connect($this->dbParams['host'], $this->dbParams['user'], $this->dbParams['password']);
		if($dbAccess) {
			$select_db = @mysql_select_db($this->dbParams['database'], $dbAccess);
			$set_charset = @mysql_set_charset('utf8',$dbAccess);
			if($select_db and $set_charset) {
				logger(__FUNCTION__, "Connexion a la base de donnes en utf8 OK : identifiant $dbAccess");
			} else {
				$this->error_code_req = @mysql_errno();
				$this->error_message_req = @mysql_error();
				if(!$select_db) $this->db_query_error("Erreur lors de la selection de la base de donnees");
				elseif(!$set_charset) $this->db_query_error("Erreur lors du chargement du jeu de caracteres utf8");
				else $this->db_query_error("Erreur innatendu lors de la connexion a la base de donnees");
			}
		} else {
			$this->error_code_con = @mysql_errno();
            $this->error_message_con = @mysql_error();
			$this->db_connect_error();
		}
        return $dbAccess;
    }

    public function test_connexion() {
        if ($this->connexion) return mysql_ping($this->connexion);
		else return false;
    }

    public function db_close() {
        if ($this->connexion) {
            mysql_close($this->connexion);
        }
    }

    public function db_reconnect() {
        $this->db_close();
		$this->connexion = $this->db_connexion();
		if(!$this->test_connexion()) {
			$to_log = "Tentative de Reconnexion a la BDD avec les parametres : " . $this->dbParams['host'] . ", " . $this->dbParams['user'] . ", " . $this->dbParams['password'] . ", " . $this->dbParams['database'];
			$this->db_log("bdd_reconnect", $this->error_code_con, $this->error_message_con, $to_log);
        }
    }

    public function db_get_affected_rows() {
        return $this->affected_rows;
    }

    public function db_escape_string($string) {
        if (get_magic_quotes_runtime()) {
            $string = stripslashes($string);
        }
        if (!preg_match("/^adddate\\(/i", $string)) {
            return @mysql_real_escape_string($string, $this->connexion);
        }
        return $string;
    }
	
    public function db_insert_id() {
		return @mysql_insert_id($this->connexion);
    }
	
	public function db_insert($inserTable, $insertData, $ignore = false, $on_duplicate = false) {
		return $this->db_mysql_insert($inserTable, $insertData, $ignore, $on_duplicate);
    }
	
    public function db_query($sqlQuery) {
		$query_id = null;
        if (!$this->test_connexion()) {
            $this->db_reconnect();
        }
        if($this->test_connexion()) {
            $query_id = @mysql_query($sqlQuery, $this->connexion);
            $this->affected_rows = @mysql_affected_rows($this->connexion);
			if(!$query_id) {
				$this->error_code_req = @mysql_errno();
				$this->error_message_req = @mysql_error();
				// $query_id;
				$this->affected_rows = -1;
				$this->db_query_error($sqlQuery);
			} else {
				// echo "$sqlQuery | affected_rows = {$this->affected_rows}</br>".PHP_EOL;
				$this->db_query_log($this->affected_rows, $sqlQuery);
			}
        }
        return $query_id;
    }

    public function db_free_result($query_id) {
        if($query_id and $query_id !== -1) {
            @mysql_free_result($query_id);
        }
    }

    public function db_fetch($query_id) {
		$record = null;
		if($query_id and $query_id !== -1) {
            $record = @mysql_fetch_assoc($query_id);
			if(!$record) {
				$to_log = "Probleme dans la recherche de resultat sous forme de tableau associatif";
				$this->db_log("bdd_fetch", @mysql_errno(), @mysql_error(), $to_log, "", -2);
			}
        }
        return $record;
    }

    public function db_num_rows($query_id) {
		$row = 0;
        if($query_id and $query_id !== -1) {
            $row = @mysql_num_rows($query_id);
			if(!$row) {
				$to_log = "Probleme dans le compte des numeros de lignes";
				$this->db_log("bdd_nums_rows", @mysql_errno(), @mysql_error(), $to_log, -2);
			}
        }
        return $row;
    }
	
    public function db_num_fields($query_id) {
		$field = 0;
        if($query_id and $query_id !== -1) {
            $field = @mysql_num_fields($query_id);
			if(!$field) {
				$to_log = "Probleme dans le compte du nombre de champs";
				$this->db_log("bdd_num_fields", @mysql_errno(), @mysql_error(), $to_log, -2);
			}
        }
        return $field;
    }
}

?>
