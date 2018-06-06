<?php 
    include('./phpqrcode/qrlib.php'); 

    $societe = "Le Ryad Marseille";
	$telFixe = "04 91 47 74 54";
	$telMobile = "06 88 99 99 13";
	$mail = "contact@leryad.fr";
	$site = "http://www.leryad.fr	";
	$adresse = "16 Rue Sénac de Meilhan";
	$commune = "Marseille";
	$cp = "13001";
	$localisation = "5.383552 43.297732";

    $tempDir = "./testVCard"; 

    $codeContents  = 'BEGIN:VCARD'."\n";
	$codeContents .= 'VERSION:2.1'."\n";
	if ($societe != ""){
		$codeContents .= 'FN:'.$societe."\n"; 
		$codeContents .= 'N:'.$societe."\n"; 
	}

	if ($telFixe != "") $codeContents .= 'TEL;WORK;VOICE:'.$telFixe."\n";
	if ($telMobile != "") $codeContents .= 'TEL;TYPE=cell:'.$telMobile."\n";
	if ($mail != "") $codeContents .= 'EMAIL:'.$mail."\n";
	if ($site != "") $codeContents .= 'URL:'.$site."\n";
	
	$codeContents .= 'ADR;TYPE=work;LABEL="adresse":;;';
	if ($adresse != "") $codeContents .= $adresse;
	$codeContents .= ';';
	if ($commune != "") $codeContents .= $commune;
	$codeContents .= ';';
	if ($cp != "") $codeContents .= $cp;
	$codeContents .= ';'."\n";
	if ($localisation != "") $codeContents .= 'GEO:'.str_replace(" ",";",str_replace(",",".",$localisation))."\n";

	$codeContents .= 'END:VCARD';

    QRcode::png($codeContents, $tempDir.'026.png', QR_ECLEVEL_L, 3); 

    echo '<img src="'.$tempDir.'026.png" />'; 