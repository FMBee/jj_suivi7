<?php

/* 
 * JoliJeune par Nicolas Dewaele / Adminrezo
 */


// Désactiver le rapport d'erreurs
//error_reporting(0);

session_start();

$cheminabsolu=__DIR__;
require_once ("core/config.inc.php");
require_once ("db/db.inc.php");
require_once ("core/format.class.php");

$db = @new mysqli($sql_host, $sql_user, $sql_pass, $sql_db);
if ($db->connect_errno) die('Erreur de connexion : ' . $db->connect_errno);

/* Vérification du login */
require_once ("core/login.php");

require_once ($tpldir."/head.phtml");
#if (loggedIn()) require_once ($tpldir."/menu.phtml");
echo "<div id='main'>";

if (!loggedIn() && !loginFormOk()) require_once ($tpldir."/login.phtml");
else {
	if (isset($_POST['submit'])) {
		$login=htmlentities($_POST['login']);
		$hashedpass=hash("sha512",$_POST['pass']);
		$query="update jj_utilisateur set hashedpass='$hashedpass' where login='$login'";
		#echo $query;
		$db->query($query);
		if ($db->error) echo $db->error;
		else echo "Le mot de passe a bien &eacute;t&eacute; modifi&eacute;<br/>
		<a href='$url/?page=deconnexion'>D&eacute;connexion</a><br/>";
	} else {
		require_once("tpl/bluefish/forms/pass.phtml");
	}
}

require_once ($tpldir."/footer.phtml");

$db->close();

?>
