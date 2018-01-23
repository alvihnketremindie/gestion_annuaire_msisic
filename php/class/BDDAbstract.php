<?php

abstract class BDDAbstract {

    public $dbParams;
    protected $connexion;
    protected $query_id = 0;
    protected $affected_rows = 0;
    protected $error_code_con = 0;
    protected $error_message_con = 0;
    protected $error_code_req = 0;
    protected $error_message_req = 0;

    /**
     * Constructor
     */
    function __construct($dbParams) {
        $this->dbParams = $dbParams;
        $this->connexion = $this->db_connexion();
    }
	
	/**
	 * Reconnexion au SGBD
	 */
	protected function db_reconnect() {
        $this->db_close();
        try {
            $this->connexion = db_connexion();
        } catch (Exception $e_reco) {
            $to_log = "Tentative de Reconnexion a la BDD avec les parametres : " . $this->dbParams['host'] . ", " . $this->dbParams['user'] . ", " . $this->dbParams['password'] . ", " . $this->dbParams['database'];
            $this->db_log("bdd_reconnect", $e_reco->getCode(), $e_reco->getMessage(), $to_log);
        }
    }
	
	/**
	 * Nombre de lignes affecte par une requete
	 */
    public function db_get_affected_rows() {
        return $this->affected_rows;
    }

	/**
	 * Enregistre le message SQL ou l'erreur transmise
     * @param string $nom_fichier_log Anciennement le nom du fichier de log, Aujourd'hui un message informatif faisant office de filtre
     * @param string $code code de l'erreur s'il y en a
     * @param string $syntaxe code de l'erreur s'il y en a
     * @param string $rows le nombre de ligne affectees par la requete
     * @param string $requete contenu de la requête
     */
    protected function db_log($nom_fichier_log, $code, $syntaxe, $requete, $rows) {
        $info['code'] = $code;
        $info['affected_rows'] = $rows;
        $info['syntaxe'] = str_ireplace(PHP_EOL, ' ', $syntaxe);
        $info['requete'] = str_ireplace(PHP_EOL, ' ', $requete) . ";";
        logger($nom_fichier_log, $info);
    }
	
    protected function db_connect_error() {
        $messageErreur = "Tentative de connexion a la BDD avec les parametres : " . $this->dbParams['host'] . ", " . $this->dbParams['user'] . ", " . $this->dbParams['password'] . ", " . $this->dbParams['database'];
        $this->db_log("bdd_connexion", $this->error_code_con, $this->error_message_con, $messageErreur, -1);
    }

    protected function db_query_error($sqlQuery) {
        $this->db_log("bdd_requete", $this->error_code_req, $this->error_message_req, $sqlQuery, -1);
    }

    protected function db_query_log($rows, $requete) {
		$this->db_log("requetesql", "", "", $requete, $rows);
    }
	
	protected function db_mysql_insert($inserTable, $insertData, $ignore = false, $on_duplicate = false) {
		$columns = implode(", ",array_keys($insertData));
		$escaped_values = array_map(array($this, 'buildAttributes'), array_values($insertData));
		$values  = implode(", ", $escaped_values);
		$requete = "INSERT ";
		if($ignore) $requete .= "IGNORE";
		$requete .= " INTO $inserTable ($columns) VALUES ($values) ";
		if($on_duplicate) {
			$i = 0;
			$requete .= " ON DUPLICATE KEY UPDATE ";
			foreach ($insertData as $field => $value) {
				$requete .= ($i > 0 ? ', ' : '') . $field . ' = '. $this->buildAttributes($value);
				$i++;
			}
		}
		
        if ($this->db_query($requete)) {
            return @$this->db_insert_id();
        } else {
            return -1;
        }
    }
	
	/**
	 * Requete de mise a jour
	 * @param $updateTable : Nom de la table
	 * @param $updateData : Donnees a inserer sous forme de tableau
	 * @param $clauseWhere : Clause where de l'update
	 */
	public function db_update($updateTable, $updateData, $clauseWhere = '1') {
        $sqlQuery = "UPDATE " . $updateTable . " SET ";
        foreach ($updateData as $key => $value) {
            if (strtolower($value) == 'null') {
                $sqlQuery.= "$key = NULL";
            } elseif (strtolower($value) == 'now()') {
                $sqlQuery.= "$key = NOW(), ";
            } elseif (preg_match("/^increment\((\-?\d+)\)$/i", $value, $m)) {
                $sqlQuery.= "$key = $key + $m[1], ";
            } elseif (preg_match("/^decrement\((\-?\d+)\)$/i", $value, $m)) {
                $sqlQuery.= "$key = $key - $m[1], ";
            } elseif (preg_match("/^adddate\\(/i", $value)) {
                $sqlQuery.= "$key = " . $value . ", ";
            } else {
                $sqlQuery.= "$key='" . $this->db_escape_string($value) . "', ";
            }
        }
        $sqlQuery = rtrim($sqlQuery, ', ') . ' WHERE ' . $clauseWhere;
        return $this->db_query($sqlQuery);
    }
	
    public function buildAttributes($attributes) {
		if (strtolower($attributes) == 'null') {
			return "NULL";
		} elseif (strtolower($attributes) == 'now()') {
			$value = @date("Y-m-d H:i:s");
		} else {
			$value = $this->db_escape_string($attributes);
		}
		if (is_string($value)) {
			$value = "'$value'";
		}
		return $value;
    }

    protected function parse_params($params) {
        $return = '';
        if ($params != null) {
			if (array_key_exists('on', $params)) {
				$return.= ' ON ' . $params['on'];
			}
            if (array_key_exists('where', $params)) {
                $return.= ' WHERE ' . $params['where'];
            }
            if (array_key_exists('order', $params)) {
                $return .= ' ORDER BY ' . $params['order'];
            }
            if (array_key_exists('group', $params)) {
                $return .= ' GROUP BY ' . $params['group'];
            }
            if (array_key_exists('limit', $params)) {
                $return .= ' LIMIT ' . $params['limit'];
            }
        }
        return $return;
    }
	
	/**
	 * Methode de selection de toutes les lignes d'une requete de selection
	 * @param $sql : Corps de la requete sql
	 * @return : Tableau representant les lignes renvoyes par la requete
	 */
	public function db_fetch_all($sql) {
        $query_id = $this->db_query($sql);
		/*
        $out = array();
        while ($row = $this->db_fetch($query_id)) {
            $out[] = $row;
        }
        return $out;
		*/
		while(($resultArray[] = $this->db_fetch($query_id)) || array_pop($resultArray));
		return @$resultArray;
    }
	
	/**
	 * Methode de mise en place d'une selection d'elements au sein de la base de donnes
	 * @param $findTable : Nom de la table
	 * @param $find : Champs de recherche (* par defaut)
	 * @param $findParams : Parametre additionel (ON, WHERE, GROUP BY, ORDER BY, LIMIT)
	 * @param $all : Parametre indiquant si la ressource est retourne ($all = false) ou si toutes les lignes le sont sous formes de tableau
	 * @return : Tableau representant les lignes ou la ressource renvoyes par la requete
	 */
	public function db_find_record($findTable, $find = "*", $findParams = array(), $all = false) {
        $sqlQuery = "SELECT $find FROM $findTable" . $this->parse_params($findParams);
		if($all) return $this->db_fetch_all($sqlQuery);
        else return $this->db_query($sqlQuery);
    }
}

?>
