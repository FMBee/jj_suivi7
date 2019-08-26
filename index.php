<?php

/* 
 * JoliJeune par Nicolas Dewaele / Adminrezo
 */


// Désactiver le rapport d'erreurs
error_reporting(0);

session_start();

$cheminabsolu=__DIR__;
require_once (__DIR__."/core/config.inc.php");
require_once (__DIR__."/db/db.inc.php");
require_once (__DIR__."/core/format.class.php");

$db = @new mysqli($sql_host, $sql_user, $sql_pass, $sql_db);
if ($db->connect_errno) die('Erreur de connexion : ' . $db->connect_errno);

/* Vérification du login */
require_once (__DIR__."/core/login.php");

require_once ($tpldir."/head.phtml");
if (loggedIn()) require_once ($tpldir."/menu.phtml");
echo "<div id='main'>";

if (!loggedIn() && !loginFormOk()) require_once ($tpldir."/login.phtml");
else {
	if (isset($_GET) && !empty($_GET['page'])) {
		$query=htmlentities($_GET['page']);
	} else {
		$query="home";
	}
	switch ($query) {
		case "home": require_once ($tpldir."/home.phtml"); break;
		case "about": require_once ($tpldir."/about.phtml"); break;
		case "deconnexion": logOut(); break;
//		default: require_once ($tpldir."/query.phtml");		
		default: require_once ("core/".$query.".php");		
	}
}

require_once ($tpldir."/footer.phtml");

$db->close();

?>
