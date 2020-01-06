<?php

if (isset($_GET) && !empty($_GET['action'])) $action=htmlentities($_GET['action']);
else $action="readName";

switch ($action) {
	case "readName" :
		readName();
		break;
	case "getNames" :
		getNames();
		break;
}


function readName() {
	global $sql_prefix,$db,$tpldir,$tplname;

	require_once ($tpldir."/forms/stagiaireseek.phtml");
}

function getNames() {

    session_start();

    $cheminabsolu = str_replace(
        '\\core',
        '',
        __DIR__
        );
    require_once ("config.inc.php");
    require_once ("../db/db.inc.php");

    $db = @new mysqli($sql_host, $sql_user, $sql_pass, $sql_db);
    if ($db->connect_errno) die('Erreur de connexion : ' . $db->connect_errno);

    $seek = "%".trim($_GET['query'])."%";
    $requete="select * from ".$sql_prefix."stagiaire WHERE nom LIKE '$seek'";
    $result = $db->query($requete);
    $retour = [];

    while ( $element = $result->fetch_array() ) {

        $retour[] = [
            "value" => 	strtoupper($element['nom']).' '.ucfirst($element['prenom']),
            "data" => $element['id_stagiaire']
            ];
    }

    echo json_encode(
        [
            "query" => "Unit",
            "suggestions" => $retour
        ]);
}

?>
