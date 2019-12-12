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

    $result = [
        [
            'value' => 'DUPONT',
            'data' => '450',
        ],
        [
            'value' => 'DURAND',
            'data' => '488',
        ],
    ];

    echo json_encode(
        array( 	"query" => "Unit",
            "suggestions" => $result )
        );
}

?>
