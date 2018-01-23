<?php

interface BDD {
	
	/**
	 * Creation de la connexion au SGBD
	 */
    public function db_connexion();

    /**
	 * Fermeture de la connexion au SGBD
	 */
    public function db_close();
	
    /**
	 * Test de la connexion au SGBD
	 */
    public function test_connexion();

    /**
	 * Requete d'insertion
	 * @param $inserTable : Nom de la table
	 * @param $insertData : Donnees a inserer sous forme de tableau
	 */
	public function db_insert($inserTable, $insertData, $ignore, $on_duplicate);
	
	/**
	 * @return identifiant de l'index de la derniere insertion
	 */
	public function db_insert_id();
	
	/**
	 * Requete de mise a jour
	 * @param $inserTable : Nom de la table
	 * @param $insertData : Donnees a inserer sous forme de tableau
	 */
	// public function db_update($updateTable, $updateData, $clauseWhere);

	/**
	 * Execution de la requete sql
	 * @param $sqlQuery : Corps de la requete
	 * @return : Identifiant de jeu de resultats
	 */
	public function db_query($sqlQuery);
	
	/**
	 * @param $query_id : Identifiant de jeu de resultats
	 * @return : Nombre de lignes du jeu de resultat suite a l'execution d'une requete sql
	 */
    public function db_num_rows($query_id);
    
	/**
	 * @param $query_id : Identifiant de jeu de resultats
	 * @return : Nombre de champs du jeu de resultat suite a l'execution d'une requete sql
	 */
    public function db_num_fields($query_id);
	
	/**
	 * Libere la memoire associee a un resultat
	 * @param $query_id : Identifiant de jeu de resultats
	 */
    public function db_free_result($query_id);
	
    /**
	 * Jeu de resultat suite a l'execution d'une requete sql
	 * @param $query_id : Identifiant de jeu de resultats
	 * @return : Ressource (Ligne, Jeu de resultats, booleen, ...)
	 */
    public function db_fetch($query_id);
	
	/**
	 * Fonction de controle pour l'echappement des caractere avant l'execution de la requete sql
	 * @param $string : Chaine de caractere a echapper
	 */
	public function db_escape_string($string);
}

?>
