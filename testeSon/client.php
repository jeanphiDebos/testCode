<?php
require_once("./PDOBDD.php");

$PDOBDD = new PDOBDD("localhost","test_son","root","c4rt3l");
$newSession = false;
$idSession = "";

while (!$newSession){
	$idSession = generateurSession();
	$requete = "SELECT count(*) as 'nbThisSession' FROM `historisationsonjouer` WHERE `idSession` = '".$idSession."'";
			
	$result = $PDOBDD->ExecuterRequete($requete);
	if ($PDOBDD->getErrorRequete()){
		$newSession = true;
		echo $PDOBDD->getMessageError();
	}else{
		if (!isset($requete[0]['nbThisSession']) || $requete[0]['nbThisSession'] == 0) $newSession = true;
	}
}

function generateurSession(){
	$possible = "azertyupqsdfghjkmwxcvbn0123456789AZERTYUPQSDFGHJKMWXCVBN";
	$session = "";
	
	for($i=0;$i<15;$i++){
		$alea = mt_rand(0, strlen($possible)-1);
		$caractere = substr($possible, $alea, 1);
		$session .= $caractere;
	}
	
     return $session;
}
?>
<script type="text/javascript" src="./jquery-1.11.1.min.js"></script>
<audio preload="auto" id="son">
	<source id="sonSource" src="./son/beep.mp3" type="audio/mp3">
</audio>
<script>
$(document).ready(function(){
	var myVar;
	var dateLancementClient = dateNow();
	var idSession = "<?php echo $idSession?>";
	verifJouerSon();	
	
	function verifJouerSon(){
		jQuery.ajax({
			type: "GET",
			url: "./requeteAJAX.php",
			data :{
				action : "doitjouerSon",
				dateLancementClient : dateLancementClient,
				idSession : idSession
			},
			success: function(data, textStatus, jqXHR) {
				if (data != "" && data.search("erreur:") == -1){
					$('#sonSource').attr("src", data);
					$('#son')[0].play();
					console.info("succes : "+data);
				}else if (data != "" && data.search("erreur:") != 0){
					console.error("erreur : "+data);
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.error("erreur");
			}
		});
		
		setTimeout(wait, 1000);
	}
		
	function wait(){
		verifJouerSon();
	}
	
	function dateNow(){
		var dateNow = new Date();
		var jour = dateNow.getDate();
		var mois = dateNow.getMonth();
		var annee = dateNow.getFullYear();
		
		var heure = dateNow.getHours();
		var minute = dateNow.getMinutes();
		var seconde = dateNow.getSeconds();
		
		if (jour.toString().length == 1) jour = "0"+jour.toString();
		mois = parseInt(mois)+1;
		if (mois.toString().length == 1) mois = "0"+mois.toString();
		if (heure.toString().length == 1) heure = "0"+heure.toString();
		if (minute.toString().length == 1) minute = "0"+minute.toString();
		if (seconde.toString().length == 1) seconde = "0"+seconde.toString();
		
		var now = annee+"-"+mois+"-"+jour+" "+heure+":"+minute+":"+seconde;
		return now;
	}
});
</script>