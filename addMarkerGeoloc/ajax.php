<?php
include("./PDOBDD.php");

$titre = isset($_GET['titre']) ? $_GET['titre'] : "";
$description = isset($_GET['description']) ? $_GET['description'] : "";
$latitude = isset($_GET['latitude']) ? $_GET['latitude'] : "";
$longitude = isset($_GET['longitude']) ? $_GET['longitude'] : "";
$action = isset($_GET['action']) ? $_GET['action'] : "listingGeoPoint";

$PDO = new PDOBDD("localhost","geopointjapon","Nashi_Dev","marchen35610");

if (!$PDO->getErrorConnection()){
    switch ($action){
        case "listingGeoPoint":
            listingGeoPoint($PDO);
            break;
        case "addGeoPoint":
            addGeoPoint($titre, $description, $latitude, $longitude, $PDO);
            break;
    }
}

/**
 * @param $PDO PDOBDD
 */
function listingGeoPoint($PDO){
    $result = $PDO->ExecuterRequete("SELECT * FROM `geopoint`");
    if ($PDO->getErrorRequete()){
        echo $PDO->getMessageError();
    }else{
        echo json_encode($result);
    }
}

/**
 * @param $PDO PDOBDD
 */
function addGeoPoint($titre, $description, $latitude, $longitude, $PDO){
    $PDO->ExecuterRequeteNoReturn("INSERT INTO `geopoint`(`titre`, `description`, `latitude`, `longitude`) VALUES ('".$titre."','".$description."','".$latitude."','".$longitude."')");
    if ($PDO->getErrorRequete()){
        echo $PDO->getMessageError();
    }
}