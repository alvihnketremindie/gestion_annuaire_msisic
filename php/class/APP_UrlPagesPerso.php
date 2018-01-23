<?php

class APP_UrlPagesPerso {
	
    protected $titre;
    protected $lien;
    protected $icone;
    protected $date_ajout_url_page;
    protected $date_ajout_url_perso;
	
    public function __construct($array) {
		foreach($array as $key => $value) {
			$this->{$key} = $value;
		}
    }
	
	public function getDescriptionNormal(){
		return "<i class='fa fa-{$this->icone}'><a href='{$this->lien}'> {$this->titre}</a></i>";
	}
	
	public function getDescriptionInLink() {
		return "<li><i class='fa-li fa fa-{$this->icone}'></i><a href='{$this->lien}'> {$this->titre}</a></li>";
	}
}

?>