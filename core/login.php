<?php

function loginFormOk() {
	global $sql_prefix,$db;
	$format=new format;
	$loginOK = false;
	if ( isset($_POST) && (!empty($_POST['login'])) && (!empty($_POST['pass'])) ) {
		$_POST=$format->toMysql($_POST);
		extract($_POST);
		$hashedpass=hash("sha512",$pass);
		$query = "select login,nom,prenom from ".$sql_prefix."utilisateur where login='$login' and hashedpass='$hashedpass'";
		if ($stmt = $db->prepare($query)) {
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($login, $nom, $prenom);
			$stmt->fetch();
			$nbln=$stmt->num_rows;
			if ($nbln == 1) $loginOK = true;
			$stmt->close();
		}
		if ($loginOK) {
			$_SESSION['login'] = $login;
			$_SESSION['nom'] = $nom;
			$_SESSION['prenom'] = $prenom;
		}
	}
	return $loginOK;
}

function loggedIn() {
	if ( isset($_POST) && (!empty($_SESSION['login'])) ) return true;
	else return false;
}

function logOut() {
	global $tplname;
	session_destroy();
	echo "Vous venez d'&ecirc;tre d&eacute;connect&eacute;(e).<br /><a href='?page=chambre'><img src='tpl/$tplname/img/previous.png' alt='retour' title='Retour' /></a>";
}

?>