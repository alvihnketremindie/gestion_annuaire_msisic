<?php

class Utils {
    protected $errorMessage = "";
    protected $boolVerif = false;
	
    public function __construct($array, $tab_temoin, $tab_pattern, $hasArrVal = false) {
		if (isset($array) and !empty($array)){
			if($hasArrVal) $array = convertHTMLData ($array);
			foreach ($array as $key => $value) {
				$this->{$key} = $value;
			}
			$this->boolVerif = $this->verificationCoherenceParametre($tab_temoin, $tab_pattern);
		}
		else {
			$this->errorMessage = "Probleme a la creation de l'objet";
		}
		
		// print_r($this);
    }
	
	protected function verificationCoherenceParametre($tab_temoin, $tab_pattern) {
		$bool = false;
		foreach($tab_temoin as $key){
			if(!isset($this->{$key})) {
				$this->errorMessage = "Le champ $key n'a pas pu etre recupere";
				return $bool;
			}
			elseif(empty($this->{$key}) && $this->{$key} !== 0 && $this->{$key} !== "0") {
				$this->errorMessage = "Le champ $key ne doit pas rester vide";
				return $bool;
			}
			elseif(isset($tab_pattern[$key]) and !empty($tab_pattern[$key]) and !preg_match($tab_pattern[$key], $this->{$key} , $matches)) {
				$this->errorMessage = "Le champ $key n'est pas au bon format";
				return $bool;
			}
		}
		return true;
	}
	
	public function getErrorMessage() {
        return $this->errorMessage;
    }
	
	public function getBoolVerif() {
        return $this->boolVerif;
    }
}
?>