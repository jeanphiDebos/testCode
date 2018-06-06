<?php
require_once("./PDOBDD.php");

$action=isset($_GET['action'])?$_GET['action']:"";
$cheminSon=isset($_GET['cheminSon'])?$_GET['cheminSon']:"";
$dateLancementClient=isset($_GET['dateLancementClient'])?$_GET['dateLancementClient']:"";
$idSession=isset($_GET['idSession'])?$_GET['idSession']:"";

$PDOBDD = new PDOBDD("localhost","test_son","root","c4rt3l");

// selection des requetes sql à effectuer
if (!$PDOBDD->getErrorConnection()){
	switch($action){
		case "jouerSon":
			jouerSon($cheminSon);
		break;
		case "doitjouerSon":
			doitjouerSon($dateLancementClient,$idSession);
		break;
	}
}

function jouerSon($cheminSon){
	global $PDOBDD;
	$requete = "INSERT INTO `jouerSon` (dateTime, cheminSon) VALUES (NOW(), '".$cheminSon."')";
	
	$PDOBDD->ExecuterRequeteNoReturn($requete);
	if ($PDOBDD->getErrorRequete()){
		echo $PDOBDD->getMessageError();
	}
}

function doitjouerSon($dateLancementClient,$idSession){
	global $PDOBDD;
	$requete = "SELECT * FROM `jouerSon` WHERE `dateTime` >= '".$dateLancementClient."' AND `id` NOT IN (SELECT `idSon` FROM `historisationsonjouer` WHERE `idSession` = '".$idSession."')";
		
	$result = $PDOBDD->ExecuterRequete($requete);
	if ($PDOBDD->getErrorRequete()){
		echo "erreur:".$PDOBDD->getMessageError();
	}else{
		if (isset($result[0])){
			$requete = "INSERT INTO `historisationsonjouer` (idSon, idSession) VALUES (".$result[0]['id'].", '".$idSession."')";
			
			$PDOBDD->ExecuterRequeteNoReturn($requete);
			if ($PDOBDD->getErrorRequete()){
				echo "erreur:".$PDOBDD->getMessageError();
			}
			echo $result[0]['cheminSon'];
		}
	}
}
?>