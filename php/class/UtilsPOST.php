<?php

class UtilsPOST extends Utils{
    protected $dbi;
	protected $alreadyExist = false;
	protected $infos_personnes = null;
	protected $tab_post;
	protected $links_field = array();
	protected $id_personnes_cree;
	protected $chemin_image = null;
	protected $valeur_transmise_dans_le_post = false;
	
    public function __construct($array, $dbi, $tab_temoin = array(), $tab_pattern = array()) {
		parent::__construct($array, $tab_temoin, $tab_pattern);
		if($this->boolVerif) {
			$this->dbi = $dbi;
			$this->email = retourneEmail($this->prenom, $this->nom);
			$this->checkAlreadyExist();
			$this->tab_post = $array;
			$this->findLinkInfos();
		}
    }

    protected function checkAlreadyExist() {
		if($row = getPersonneInfos($this->dbi, array('email' =>$this->email, 'categorie' => $this->categorie))) {
			$this->alreadyExist = true;
			$this->infos_personnes = new APP_Personnes($row);
		}
	}
	
	public function getExistence() {
        return $this->alreadyExist;
    }
	
	protected function findLinkInfos(){
		if($this->boolVerif) {
		foreach($this->tab_post as $key => $value) {
			if(preg_match("#urllien#", $key , $matches)) {
				$icone = substr(strrchr($key, '__'),1);
				$result = $this->dbi->db_find_record("pages_persos", "*", array("where" => "icone = '$icone'"));
				if($result) {
					if($row = $this->dbi->db_fetch($result)) {
						$row['url'] = $this->{$key};
						$this->links_field[$icone] = $row;
					}
				}
			}
		}
		}
	}
	
	public function setNewUserInfos($chemin_image = null){
		$this->chemin_image = $chemin_image;
		if(!$this->alreadyExist and $this->boolVerif) {
			$insertData["nom"] = $this->nom;
			$insertData["prenom"] = $this->prenom;
			$insertData["age"] = $this->age;
			$insertData["categorie"] = $this->categorie;
			$insertData["sexe"] = $this->sexe;
			$insertData["email"] = $this->email;
			$insertData["date_inscription"] = @date("Y-m-d H:i:s");
			$insertData["chemin_image"] = $this->chemin_image;
			$insertData["statut"] = "YES";
			
			$id_personne = $this->dbi->db_insert("personnes", $insertData, true);
			
			// addNewLink
			if($row = getPersonneInfos($this->dbi, array('id' =>$id_personne))) {
				$this->alreadyExist = true;
				$this->infos_personnes = new APP_Personnes($row);
				$this->setNewUserLinkInfos($id_personne);
			}
			
			return true;
		}
		return false;
	}
	
	public function setNewUserLinkInfos($id_personne) {
		if($this->infos_personnes and $this->boolVerif and is_array($this->links_field)) {
			foreach($this->links_field as $key => $value) {
				$insertData["id_personne"] = $id_personne;
				$insertData["titres_page"] = $value["titre"];
				$insertData["url"] = $value["url"];
				$insertData["date_ajout"] = @date("Y-m-d H:i:s");
				$insertData["statut"] = "YES";
				
				$this->infos_personnes->addNewLink($this->dbi, $value["titre"], $value["url"], $key);
			}
		}
	}
	
	public function updateUserInfos($id_personne, $chemin_image = null){
		$this->chemin_image = $chemin_image;
		if($this->boolVerif) {
			$updateData["nom"] = $this->nom;
			$updateData["prenom"] = $this->prenom;
			$updateData["age"] = $this->age;
			$updateData["categorie"] = $this->categorie;
			$updateData["sexe"] = $this->sexe;
			$updateData["email"] = $this->email;
			if($this->chemin_image) $updateData["chemin_image"] = $this->chemin_image;
			$updateData["statut"] = "YES";
			$this->dbi->db_update("personnes", $updateData, "id = $id_personne");
			if($row = getPersonneInfos($this->dbi, array('id' =>$id_personne))) {
				$this->alreadyExist = true;
				$this->infos_personnes = new APP_Personnes($row);
				// $this->findLinkInfos();
				$this->updateUserLinkInfos();
			}
			
			return true;
		}
		return false;
	}
	public function updateUserLinkInfos() {
		if($this->boolVerif and $this->infos_personnes) {
				// print_r($this->tab_post);
			foreach($this->tab_post as $key => $value) {
				if(preg_match("#urllien#", $key , $matches)) {
					$icone = substr(strrchr($key, '__'),1);
					$result = $this->dbi->db_find_record("pages_persos", "*", array("where" => "icone = '$icone'"));
					if($result) {
						if($row = $this->dbi->db_fetch($result)) {
							// echo "icone = $icone, valeur = $value".PHP_EOL;
							$this->infos_personnes->addNewLink($this->dbi, $row["titre"], $value, $icone);
						}
					}
				}
			}
		}
	}
	
	public function getUser(){
		return @$this->infos_personnes;
	}
}

?>
