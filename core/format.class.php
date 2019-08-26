<?php

/* Classe de formatage des données */

class format {
	
//	private $erreur;
		
	public function dateMysqlToFr($date) {
		$result=substr($date,8,2)."/".substr($date,5,2)."/".substr($date,0,4);
		return $result;
	}

	public function dateFrToMysql($date) {
		$result=substr($date,6,4)."-".substr($date,3,2)."-".substr($date,0,2);
		return $result;
	}

	public function mailFromMySQL($mail) {
		if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) $mail = "Non renseign&eacute;";
		else $mail = "<a href='mailto:".$mail."'>".$mail."</a>";
		return $mail;
	}

	public function phoneFromMySQL($phone) {
		if ($phone && $phone!="" && $phone!="Non renseign&eacute;") $phone = "<a href='tel:".$phone."'>".$phone."</a>";
		else $phone = "Non renseign&eacute;";
		return $phone;
	}

	public function mailToMySQL($mail) {
		if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) $mail = "Non renseign&eacute;";
		return $mail;
	}

	public function phoneToMySQL($phone) {
		if (!preg_match("#^(0|\+[1-9]{2})[1-9]([-. ]?[0-9]{2}){4}$#", $phone))  $phone = "Non renseign&eacute;";
		return $phone;
	}

	public function sexe($MouF) {
		switch ($MouF) {
			case "M" : $MouF="Homme"; break;
			case "F" : $MouF="Femme"; break;
			default : $MouF="Non renseign&eacute;"; break;
		}
		return $MouF;
	}
	
	public function toMysql($data) {
		global $db;
		$result=null;
		foreach ($data as $key => $value) {
			/* Si c'est vide */
			if ($value==null || $value=="") $value = "Non renseign&eacute;";
			if ($key=="mail") $value=$this->mailToMySQL($value);
			if ($key=="telfixe" || $key=="telportable") $value=$this->phoneToMySQL($value);
			/* Arrondi des valeurs */
			if (round($value,2)!=0 && $key!="prof_sport" && $key!="taille" && $key!="date_deb" && $key!="date_fin") $value=round($value,1);
			/* Anti-injection SQL */ 
			$result[$key]=$db->real_escape_string($value);
		}
		return $result;
	}

}

?>
