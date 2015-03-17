<?php

class Projet extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $nom;

    /**
     *
     * @var string
     */
    protected $description;

    /**
     *
     * @var string
     */
    protected $dateLancement;

    /**
     *
     * @var string
     */
    protected $dateFinPrevue;

    /**
     *
     * @var integer
     */
    protected $idClient;
    
    public function initialize(){
		$this->belongsTo("idClient", "User", "id");
		$this->hasMany("id", "Message", "idProjet",array("alias"=>"Messages")); //On y accèdera par getMessages()
		$this->hasMany("id", "Usecase", "idProjet",array("alias"=>"Usecases")); //On y accèdera par getUsecases()
	}

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field nom
     *
     * @param string $nom
     * @return $this
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Method to set the value of field description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Method to set the value of field dateLancement
     *
     * @param string $dateLancement
     * @return $this
     */
    public function setDatelancement($dateLancement)
    {
        $this->dateLancement = $dateLancement;

        return $this;
    }

    /**
     * Method to set the value of field dateFinPrevue
     *
     * @param string $dateFinPrevue
     * @return $this
     */
    public function setDatefinprevue($dateFinPrevue)
    {
        $this->dateFinPrevue = $dateFinPrevue;

        return $this;
    }

    /**
     * Method to set the value of field idClient
     *
     * @param integer $idClient
     * @return $this
     */
    public function setIdclient($idClient)
    {
        $this->idClient = $idClient;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Returns the value of field description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Returns the value of field dateLancement
     *
     * @return string
     */
    public function getDatelancement()
    {
        return $this->dateLancement;
    }

    /**
     * Returns the value of field dateFinPrevue
     *
     * @return string
     */
    public function getDatefinprevue()
    {
        return $this->dateFinPrevue;
    }

    /**
     * Returns the value of field idClient
     *
     * @return integer
     */
    public function getIdclient()
    {
        return $this->idClient;
    }
    
    public function getAvancement() {
    	$usecases = $this->getUsecases();
    	$poidsTotal = 0; 
    	$count = 0;
    	foreach ($usecases as $usecase) {
    		$poidsTotal += $usecase->getPoids();
    		//$count += $usecase->getPoids() * $usecase->getAvancement();
    	}
    	
    	foreach ($usecases as $usecase) {
    		$count += $usecase->getPoids() / $poidsTotal * 100 * $usecase->getAvancement();
    	}
    	
    	return round($count /100);
    }
    
    public function getDuree(){
    	$format = 'Y-m-d';
    	$resultat =  date_timestamp_get(DateTime::createFromFormat($format, $this->getDatefinprevue())) - date_timestamp_get(DateTime::createFromFormat($format, $this->getDatelancement()));
    	if ($resultat > 0){
    		return $resultat;
    	} else {
    		return 0;
    	}
    }
    
    public function getTempsDepuisDebut(){
    	$format = 'Y-m-d';
    	$resultat = date_timestamp_get(DateTime::createFromFormat($format, date($format))) - date_timestamp_get(DateTime::createFromFormat($format, $this->getDatelancement()));
    	if ($resultat > 0){
    		return $resultat;
    	} else {
    		return 0;
    	}
    }
    
    public function getPourcentageTempsEcoule(){
    	if ($this->getDuree() != 0){
    		return round(($this->getTempsDepuisDebut() / $this->getDuree())*100);
    	} else {
    		return 100;
    	}
    }
    
    public function getTempsEcoule(){
    	$format = 'Y-m-d';
    	return (date_timestamp_get(DateTime::createFromFormat($format, date($format))))>(date_timestamp_get(DateTime::createFromFormat($format, $this->getDatefinprevue())));
    }
    
    public function getClasseSelonAvancement(){
    	if (!$this->getTempsEcoule()){
	    	if ($this->getPourcentageTempsEcoule() <= $this->getAvancement()){
	    		return "success";
	    	} else if ($this->getPourcentageTempsEcoule() > $this->getAvancement()) {
	    		return "warning";
	    	}
    	} else {
    		return "danger";
    	}
    }
    
    public function getAvancementJour(){
    	$nbSecondes= 60*60*24; //Nombre de secondes par jour
    	return round(($this->getDuree() - $this->getTempsDepuisDebut())/$nbSecondes);
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'nom' => 'nom', 
            'description' => 'description', 
            'dateLancement' => 'dateLancement', 
            'dateFinPrevue' => 'dateFinPrevue', 
            'idClient' => 'idClient'
        );
    }

}
