<?php

/**
 * Class PDOBDD
 * defini la connection et l'execution des requetes
 */
class PDOBDD{
    /**
     * @var string
     */
    private $serveur;
    /**
     * @var string
     */
    private $nomBDD;
    /**
     * @var string
     */
    private $loginBDD;
    /**
     * @var string
     */
    private $passwordBDD;
    /**
     * @var PDO
     */
    private $PDOConnection;
    /**
     * @var bool
     */
    private $errorConnection;
    /**
     * @var bool
     */
    private $errorRequete;
    /**
     * @var string
     */
    private $MessageError;

    /**
     * réinisialisation de la connection au serveurSQL
     * @param string $serveur
     * @param string $nomBDD
     * @param string $loginBDD
     * @param string $passwordBDD
     */
    public function newConnection($serveur, $nomBDD, $loginBDD, $passwordBDD){
        $this->__construct($serveur, $nomBDD, $loginBDD, $passwordBDD);
    }

    /**
     * PDOBDD constructor.
     * @param string $serveur
     * @param string $nomBDD
     * @param string $loginBDD
     * @param string $passwordBDD
     */
    public function __construct($serveur, $nomBDD, $loginBDD, $passwordBDD){
        $this->serveur = $serveur;
        $this->nomBDD = $nomBDD;
        $this->loginBDD = $loginBDD;
        $this->passwordBDD = $passwordBDD;
        $this->errorConnection = false;
        $this->errorRequete = false;
        $this->MessageError = "";
        $this->ConnectionPDOBDD();
    }

    /**
     * utilisé par le constructor
     * il initialisation de la connection au serveurSQL
     */
    private function ConnectionPDOBDD(){
        try{
            $dns = 'mysql:host='.$this->serveur.';dbname='.$this->nomBDD;
            $PDOConnection = new PDO($dns, $this->loginBDD, $this->passwordBDD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'', PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC));
            $PDOConnection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $PDOConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->PDOConnection = $PDOConnection;
        }catch (PDOException $e){
            $this->errorConnection = true;
            $this->MessageError = $e->getMessage();
        }
    }

    /**
     * execute une requete sql de type select avec gestion des erreurs
     * @param string $requete
     * @return string
     */
    public function ExecuterRequete($requete){
        $this->errorRequete = false;
        $this->MessageError = "";

        try{
            $resultatRequete = $this->PDOConnection->query($requete)->fetchAll();

            return $resultatRequete;
        }catch (PDOException $e){
            $this->errorRequete = true;
            $this->MessageError = $e->getMessage();

            return "";
        }
    }

    /**
     * execute une requete sql de type update, deletre avec gestion des erreurs
     * @param string $requete
     */
    public function ExecuterRequeteNoReturn($requete){
        $this->errorRequete = false;
        $this->MessageError = "";

        try{
            $this->PDOConnection->query($requete);
        }catch (PDOException $e){
            $this->errorRequete = true;
            $this->MessageError = $e->getMessage();
        }
    }

    /**
     * getter de la variable PDOConnection
     * @return PDO
     */
    public function getPDOConnection(){
        return $this->PDOConnection;
    }

    /**
     * getter de la variable errorConnection
     * @return bool
     */
    public function getErrorConnection(){
        return $this->errorConnection;
    }

    /**
     * getter de la variable errorRequete
     * @return bool
     */
    public function getErrorRequete(){
        return $this->errorRequete;
    }

    /**
     * getter de la variable MessageError
     * @return string
     */
    public function getMessageError(){
        return $this->MessageError;
    }

    /**
     * PDOBDD destroyer
     */
    public function __destruct(){

    }
}

?>