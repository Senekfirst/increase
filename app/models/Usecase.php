<?php

class Usecase extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     */
    protected $code;

    /**
     *
     * @var string
     */
    protected $nom;

    /**
     *
     * @var integer
     */
    protected $poids;

    /**
     *
     * @var integer
     */
    protected $avancement;

    /**
     *
     * @var integer
     */
    protected $idProjet;

    /**
     *
     * @var integer
     */
    protected $idDev;
    
    public function initialize(){
    	$this->belongsTo("idDev", "User", "id");
    	$this->belongsTo("idProjet", "Projet", "id");
    	$this->hasMany("code", "Tache", "codeUseCase", array("alias"=>"TachesLiees"));
    }

    /**
     * Method to set the value of field code
     *
     * @param string $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

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
     * Method to set the value of field poids
     *
     * @param integer $poids
     * @return $this
     */
    public function setPoids($poids)
    {
        $this->poids = $poids;

        return $this;
    }

    /**
     * Method to set the value of field avancement
     *
     * @param integer $avancement
     * @return $this
     */
    public function setAvancement($avancement)
    {
        $this->avancement = $avancement;

        return $this;
    }

    /**
     * Method to set the value of field idProjet
     *
     * @param integer $idProjet
     * @return $this
     */
    public function setIdprojet($idProjet)
    {
        $this->idProjet = $idProjet;

        return $this;
    }

    /**
     * Method to set the value of field idDev
     *
     * @param integer $idDev
     * @return $this
     */
    public function setIddev($idDev)
    {
        $this->idDev = $idDev;

        return $this;
    }

    /**
     * Returns the value of field code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
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
     * Returns the value of field poids
     *
     * @return integer
     */
    public function getPoids()
    {
        return $this->poids;
    }

    /**
     * Returns the value of field avancement
     *
     * @return integer
     */
    public function getAvancement()
    {
        return $this->avancement;
    }

    /**
     * Returns the value of field idProjet
     *
     * @return integer
     */
    public function getIdprojet()
    {
        return $this->idProjet;
    }

    /**
     * Returns the value of field idDev
     *
     * @return integer
     */
    public function getIddev()
    {
        return $this->idDev;
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'code' => 'code', 
            'nom' => 'nom', 
            'poids' => 'poids', 
            'avancement' => 'avancement', 
            'idProjet' => 'idProjet', 
            'idDev' => 'idDev'
        );
    }

}
