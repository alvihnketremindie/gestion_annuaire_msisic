<?php

class UtilsFILE extends Utils{
    protected $extension_fichier;
    protected $extensions_valides;
	protected $taille_maxi;
	protected $nouveau_chemin = null;
	
    public function __construct($array, $extensions_valides = null, $taille_maxi = 100000) {
		parent::__construct($array, array(), array());
		$this->extensions_valides = $extensions_valides;
		$this->taille_maxi = $taille_maxi;
		// $this->extension_fichier = pathinfo($this->name);
		$this->extension_fichier = strtolower(substr(strrchr($this->name, '.'),1));
		$this->validation();
    }

	protected function validation() {
		if($this->boolVerif) {
			$this->checkErreurUpload();
			$this->checkErreurTaille();
			$this->checkErreurExtension();
		}
		// return $this->boolVerif;
	}
	
	protected function checkErreurUpload() {
		if($this->error != 0) {
			$this->boolVerif = false;
			switch ($this->error) {
				case UPLOAD_ERR_NO_FILE:
					$this->errorMessage = "Fichier manquant";
					break;
				case UPLOAD_ERR_INI_SIZE:
					$this->errorMessage = "Fichier d&eacute;passant la taille maximale autoris&eacute;e par PHP.";
					break;
				case UPLOAD_ERR_FORM_SIZE:
					$this->errorMessage = "Fichier d&eacute;passant la taille maximale autoris&eacute;e par le formulaire.";
					break;
				case UPLOAD_ERR_PARTIAL:
					$this->errorMessage = "Fichier transf&eacute;r&eacute; partiellement.";
					break;
				default:
					$this->errorMessage = "Erreur sur le fichier innatendu a ce niveau";
					break;
			}
		}
	}
	
	protected function checkErreurTaille() {
		$taille = filesize($this->tmp_name);
		if($this->size > $this->taille_maxi or $taille > $this->taille_maxi){
			$this->boolVerif = false;
			$this->errorMessage = 'Le fichier est trop gros...';
		}
	}
	protected function checkErreurExtension() {
		if ($this->extensions_valides and !in_array($this->extension_fichier,$this->extensions_valides) ) {
			$this->boolVerif = false;
			$this->errorMessage = "L'extension du fichier n'est pas reconnu";
		}
	}
	
	public function deplacerPhoto() {
		// $id_photo = crypt(uniqid(rand(), true));
		$id_photo = @date("YmdHis")."_".mt_rand(1000,9999)."_".substr((string)microtime(), 2, 8);
		$nom = "photo_profils/{$id_photo}.{$this->extension_fichier}";
		$resultat = move_uploaded_file($this->tmp_name, IMG_PATH.$nom);
		if ($resultat) {
			$this->nouveau_chemin = $nom;
		} else {
			$this->boolVerif = false;
			$this->errorMessage = "Probl&egrave;me lors du d&eacute;placement du fichier";
		}
		return $resultat;
	}
	
	public function getCheminImage(){
		return $this->nouveau_chemin;
	}
}

?>
