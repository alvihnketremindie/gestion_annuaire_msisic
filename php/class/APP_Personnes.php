<?php

class APP_Personnes {
	
    protected $id;
    protected $nom;
    protected $prenom;
    protected $age;
    protected $sexe;
    protected $categorie;
    protected $email;
    protected $date_inscription;
	protected $chemin_image;
	
	protected $page_profil_html;
	
    public function __construct($array) {
		foreach($array as $key => $value) {
			$this->{$key} = $value;
		}
		$this->checkDateInscription();
		$this->checkAndSetCheminImage(@$array['chemin_image']);
		$this->checkUrlPagePersos(@$array['urls']);
		$this->page_profil_html = trim(file_get_contents(PROFIL_HTML));
		$this->button_chip_html = trim(file_get_contents(CHIP_HTML));
		$this->button_card_html = trim(file_get_contents(CARD_HTML));
		
    }

	private function checkDateInscription() {
		list($this->jour_inscription, $this->heure_inscription) = explode(" ", @date('d/m/Y H:i:s', @strtotime(@$this->date_inscription)));
	}
	
    private function checkAndSetCheminImage($chemin_image) {
		// echo $this->chemin_image."</br>".PHP_EOL;
		if (!isset($chemin_image) or empty($chemin_image) or !file_exists(IMG_PATH.$chemin_image)) {
			$this->chemin_image = $this->sexe.'.jpg';
		} else {
			$this->chemin_image = $chemin_image;
		}
    }
	
	private function checkUrlPagePersos($urls) {
		// $urls = @$this->liens;
		$this->url_pages_persos = array();
		if($urls) {
			foreach($urls as $urlVal) {
				$this->url_pages_persos[$urlVal['icone']] = new APP_UrlPagesPerso($urlVal);
			}
		}
	}

	public function getDescription() {
		$text = '<b><label for="nom">Nom : '.$this->nom.'</label></b></br>'.PHP_EOL;
		$text .= '<label for="prenom">Prenom : '.$this->prenom.'</label></br>'.PHP_EOL;
		$text .= '<label for="age">Age : <i>'.$this->age.'</i> ans</label></br>'.PHP_EOL;
		return $text;
	}
	
	public function getDescriptionLinks() {
		$text = '<i class="fa fa-envelope"><a href="mailto:'.$this->email.'?subject=Votre demande de renseignement"> Couriel</a></i>'.PHP_EOL;
		if(is_array($this->url_pages_persos)) {
			foreach($this->url_pages_persos as $url_infos) {
				$text .= $url_infos->getDescriptionNormal().PHP_EOL;
			}
		}
		return $text;
	}
	
	public function getProfilLinks() {
		$text = '<li><i class="fa-li fa fa-envelope"></i><a href="mailto:'.$this->email.'?subject=Votre demande de renseignement"> Couriel</a></li>'.PHP_EOL;
		// print_r($this->url_pages_persos);
		if(is_array($this->url_pages_persos)) {
			foreach($this->url_pages_persos as $url_infos) {
				$text .= $url_infos->getDescriptionInLink().PHP_EOL;
			}
		}
		return $text;
	}
	
	public function getId() {
		return @$this->id;
	}
	public function addNewLink($dbi, $titre, $url, $icone) {
		// global $dbi;
		if($dbi->test_connexion()) {
			$insertData["id_personne"] = $this->id;
			$insertData["titres_page"] = $titre;
			$insertData["url"] = $url;
			$insertData["date_ajout"] = @date("Y-m-d H:i:s");
			$insertData["statut"] = "YES";
			$dbi->db_insert("url_pages_persos", $insertData, false, true);
			if($dbi->db_get_affected_rows()) {
				$row["titre"] = $titre;
				$row["lien"] = $url;
				$row["icone"] = $icone;
				$row["date_ajout_url_page"] = $insertData["date_ajout"];
				$this->url_pages_persos[$row["icone"]] = new APP_UrlPagesPerso($row);
			}
		}
	}
	
    public function getProfilInfos() {
		$rowInfosProfils['profil_image'] = $this->chemin_image;
		$rowInfosProfils['profil_infos'] = $this->getDescription();
		$rowInfosProfils['profil_liens'] = $this->getProfilLinks();
		$rowInfosProfils['pied_de_section'] = "<i>{$this->nom} {$this->prenom}</i></br>";
		if($this->date_inscription) $rowInfosProfils['pied_de_section'] .= "Inscrit le {$this->jour_inscription} &agrave; {$this->heure_inscription}".PHP_EOL;
		return afficheInfos($rowInfosProfils, $this->page_profil_html);
    }
	
	public function getChipInfos() {
		$rowInfosProfils['profil_id'] = $this->id;
		$rowInfosProfils['chemin_image'] = $this->chemin_image;
		$rowInfosProfils['nom_et_prenom'] = $this->nom." ".$this->prenom;
		$rowInfosProfils['profil_infos'] = "<i>{$this->nom} {$this->prenom}</i>";
		if($this->date_inscription) $rowInfosProfils['profil_infos'] .= ". Inscrit le {$this->jour_inscription} &agrave; {$this->heure_inscription}";
		return afficheInfos($rowInfosProfils, $this->button_chip_html);
	}
	
	public function getCardInfos($role = "") {
		$rowInfosProfils['profil_id'] = $this->id;
		$rowInfosProfils['chemin_image'] = $this->chemin_image;
		$rowInfosProfils['nom_et_prenom'] = $this->nom." ".$this->prenom;
		$rowInfosProfils['categorie'] = $this->categorie;
		$rowInfosProfils['role'] = $role;
		
		if($this->date_inscription) $rowInfosProfils['inscription_date_and_time'] = "Inscrit le {$this->jour_inscription} &agrave; {$this->heure_inscription}";
		else $rowInfosProfils['inscription_date_and_time'] = "";
		if($role and $role == "admin") $rowInfosProfils['admin'] = "<a href='index.php?page=modifier&id={$this->id}&role=$role'><button class='w3-button w3-block w3-dark-grey'><i class='fa fa-edit'> Editer le profil</i></button></a>
<a href='index.php?page=supprimer&id={$this->id}&role=$role'><button class='w3-button w3-block w3-dark-grey'><i class='fa fa-trash'> Supprimer le profil</i></button></a>";
		else $rowInfosProfils['admin'] = "";
		
		return afficheInfos($rowInfosProfils, $this->button_card_html);
	}
	
	public function getListeInfos($role = "") {
		$inscription_date_and_time = "";
		if($this->date_inscription) $inscription_date_and_time .= $this->jour_inscription." ".$this->heure_inscription;
		
		$text="<tr  class='item'>
		<td>{$this->nom}</td>
		<td>{$this->prenom}</td>
		<td>{$this->email}</td>
		<td>{$this->age}</td>
		<td>{$this->categorie}</td>
		<td>$inscription_date_and_time</td>
		<td><a href='index.php?page=profil&id={$this->id}&role=$role'><button class='w3-button w3-block w3-dark-grey'><i class='fa fa-external-link'></i></button></a></td>";

		if($role and $role == "admin") $text .= "<td><a href='index.php?page=modifier&id={$this->id}&role=$role'><button class='w3-button w3-block w3-dark-grey'><i class='fa fa-edit'></i></button></a></td>
		<td><a href='index.php?page=supprimer&id={$this->id}&role=$role'><button class='w3-button w3-block w3-dark-grey'><i class='fa fa-trash'></i></button></a></td>";

		$text .= "</tr>";
		return $text;
	}
}

?>