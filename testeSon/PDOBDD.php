<?php
class PDOBDD{
	private $serveur;
	private $nomBDD;
	private $loginBDD;
	private $passwordBDD;
	private $PDOConnection;
	private $errorConnection;
	private $errorRequete;
	private $MessageError;

	public function __construct($serveur,$nomBDD,$loginBDD,$passwordBDD){
		$this->serveur = $serveur;
		$this->nomBDD = $nomBDD;
		$this->loginBDD = $loginBDD;
		$this->passwordBDD = $passwordBDD;
		$this->errorConnection = false;
		$this->errorRequete = false;
		$this->MessageError = "";
		$this->ConnectionPDOBDD();
	}
	
	public function newConnection($serveur,$nomBDD,$loginBDD,$passwordBDD){
		$this->__construct($serveur,$nomBDD,$loginBDD,$passwordBDD);
	}
	
	private function ConnectionPDOBDD(){
		try{
			$dns = 'mysql:host='.$this->serveur.';dbname='.$this->nomBDD;
			$PDOConnection = new PDO($dns,$this->loginBDD,$this->passwordBDD,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC));
			$PDOConnection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
			$PDOConnection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			
			$this->PDOConnection = $PDOConnection;
		}catch(PDOException $e){
			$this->errorConnection = true;
			$this->MessageError = $e->getMessage();
		}
	}
	
	public function ExecuterRequete($requete){
		$this->errorRequete = false;
		$this->MessageError = "";
			
		try{
			$resultatRequete = $this->PDOConnection->query($requete)->fetchAll();
			return $resultatRequete;
		}catch(PDOException $e){
			$this->errorRequete = true;
			$this->MessageError = $e->getMessage();
			return "";
		}
	}
	
	public function ExecuterRequeteNoReturn($requete){
		$this->errorRequete = false;
		$this->MessageError = "";
			
		try{
			$resultatRequete = $this->PDOConnection->query($requete);
		}catch(PDOException $e){
			$this->errorRequete = true;
			$this->MessageError = $e->getMessage();
		}
	}
	
	public function getPDOConnection(){
		return $this->PDOConnection;
	}
	
	public function getErrorConnection(){
		return $this->errorConnection;
	}
	
	public function getErrorRequete(){
		return $this->errorRequete;
	}
	
	public function getMessageError(){
		return $this->MessageError;
	}
	
	public function __destruct(){
		
	}
}
?>