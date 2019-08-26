<?php

function validateForm() {	
	$champsnok=null;
	
	/* True si le formulaire est ok, false sinon */
	if(isset($_POST['nom'],$_POST['prenom'],$_POST['sexe'],$_POST['dob'],$_POST['adresse'],$_POST['cp'],$_POST['ville'],$_POST['telfixe'],$_POST['telportable'],$_POST['mail'])) {
		
		// Nom, prénom
		$nom=htmlentities($_POST['nom']);
		$prenom=htmlentities($_POST['prenom']);
		
		// Sexe M ou F
		if ($_POST['sexe']=="M" || $_POST['sexe']=="F") $sexe=htmlentities($_POST['sexe']);
		else $champsnok=$champsnok."<br/>Le champs sexe doit &ecirc;tre M ou F";
		
		// Date 19/06/1980 à convertir en 06/19/1980
		if(isset($_POST['dob'])) {
			$day=substr($_POST['dob'],0,2);
			$month=substr($_POST['month'],3,2);
			$year=substr($_POST['month'],6,4);
			if(!checkdate($month, $day, $year)) $champsnok=$champsnok."<br/>La date saisie n'est pas correcte";
		} else {
			$champsnok=$champsnok."<br/>La date saisie n'est pas correcte";
		}

		// Adresse
		$adresse=htmlentities($_POST['adresse']);
		$cp=htmlentities($_POST['cp']);
		$ville=htmlentities($_POST['ville']);
		
		// Téléphone
		$telfixe=htmlentities($_POST['telfixe']);
		$telportable=htmlentities($_POST['telportable']);

		// Mail
		$mail=htmlentities($_POST['telfixe']);
	}
	return $champsnok;
}

function dateToMySQL($date) {
	// Date 19/06/1980 à convertir en 06/19/1980
	// Reçoit une date au format JJ/MM/AAAA
	$day=substr($date,0,2);
	$month=substr($date,3,2);
	$year=substr($date,6,4);
	$date=$year."-".$month."-".$day;
	return $date;
}

function mailOK($mail) {
	$mail=htmlspecialchars($mail);
	if (preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $mail)) return true;
    else return false;
}

function charOK($nom) {
	$nom=htmlentities($nom);
	if (preg_match("#^[a-zA-Z\ \-]+$#", $nom)) return true;
    else return false;
}

function dateMySQLOK($date) {
	if (preg_match("#^[0-9]{4}\-[0-1][0-9]{2}\-[0-3][0-9]{2}$#", $date)) return true;
    else return false;
}

function sexeOK($sexe) {
	if (preg_match("#^[HhFf]$#", $sexe)) return true;
    else return false;
}

function cpOK($cp) {
	if (preg_match("#^[0-9][0-9][0-9][0-9][0-9]$#", $cp)) return true;
    else return false;
}

function telOK($tel) {
	if (preg_match("#^\+?[0-9]?[0-9]{10}$#", $sexe)) return true;
    else return false;
}

?>