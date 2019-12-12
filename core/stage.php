<?php

if (isset($_GET) && !empty($_GET['debut'])) $debut=(int) $_GET['debut'];
else $debut=0;

if (isset($_GET) && !empty($_GET['action'])) $action=htmlentities($_GET['action']);
else $action="readAll";

if (isset($_GET) && !empty($_GET['id'])) $id=(int) $_GET['id'];
else $debut=0;

switch ($action) {
	case "create" :
		createStage();
		break;
	case "readAll" :
		readAllStage($debut);
		break;
	case "read" :
		readStage();
		readStagiairesDuStage($id);
		break;
	case "update" :
		updateStage();
		break;
	case "delete" :
		deleteStage();
		break;
	case "associate" :
		associateStage();
		break;
	case "dissociate" :
		dissociateStage();
		break;
	default :
		readAllStage($debut);
		break;
}



function createStage() {
	global $sql_prefix,$db,$tpldir,$tplname;
	$format = new format;

	// Si le formulaire est renseign�, ajout en BDD
	if (isset($_POST) && !empty($_POST['submit'])) {

		/**** Formater les donn�es ****/
		$_POST=$format->toMysql($_POST);
		extract($_POST);
		$date_deb=$format->dateFrToMysql($date_deb);
		$date_fin=$format->dateFrToMysql($date_fin);
		$query="insert into ".$sql_prefix."stage values (NULL, '$date_deb','$date_fin','$type')";
		$db->query($query);
		echo $db->error;
		echo "<div id='retour'>La cure a bien &eacute;t&eacute; ajout&eacute;e<br /><a href='?page=stage'><img src='tpl/$tplname/img/previous.png' alt='retour' title='Retour' /></a></div>";
	}

	// Si le formulaire n'est pas renseign�, on l'ajoute
	else {
		require_once ($tpldir."/forms/stageadd.phtml");
	}
}

function readStage() {
	global $db,$sql_prefix,$tplname;

	if (isset($_GET) && !empty($_GET['id'])) {
		$id=(int) $_GET['id'];
		$requete="select * from ".$sql_prefix."stage WHERE id_stage='$id'";
		$result = $db->query($requete);
		echo $db->error;
		$array = $result->fetch_array();
		extract($array);

		$date = new DateTime($date_deb);
		$date_deb=$date->format('d/m');
		$date = new DateTime($date_fin);
		$date_fin=$date->format('d/m/Y');

		switch ($type) {
			case "md": $type="Je&ucirc;ne/Mono-di&egrave;te"; break;
			case "cd": $type="Cure d&eacute;tox"; break;
			default : $type="Non renseign&eacute;";
		}

		$_SESSION['cure']="<h3>$type - Du $date_deb au $date_fin</h3>";

		echo "<h2>$type</h2>Du $date_deb au $date_fin";
	} else {
		echo "Erreur : La cure n'a pas d'id !";
		echo "<br /><a href='?page=stage'><img src='tpl/$tplname/img/previous.png' alt='retour' title='Retour' /></a>";
	}
}

function readAllStage($debut) {
	global $sql_prefix,$db,$tplname;

	echo "<h2>Liste des cures</h2>";

// 	$requete2="select count(*) as nbln from ".$sql_prefix."stage";
// 	if ($result2 = $db->query($requete2))
// 	echo $db->error;
// 	$array2 = $result2->fetch_array();
// 	extract($array2);
// 	$result2->close();

// 	/* Dans quels cas il faut les previous et les next */
// 	$previous="<img src='tpl/$tplname/img/no-previous.png' alt='previous' title='Pr&eacute;c&eacute;dent' />";
// 	$precedent=$debut-20;
// 	if ($debut>=20) $previous="<a href='?page=stage&debut=$precedent'><img src='tpl/$tplname/img/previous.png' alt='previous' title='Pr&eacute;c&eacte;dent' /></a>";
// 	$next="<img src='tpl/$tplname/img/no-next.png' alt='next' title='Suivant' />";
// 	$suivant=$debut+20;
// 	if ($suivant < $nbln) $next="<a href='?page=stage&debut=$suivant'><img src='tpl/$tplname/img/next.png' alt='next' title='Suivant' /></a>";
	/* Affichage des 20 derniers stages */

	$requete="select * from ".$sql_prefix."stage order by date_deb desc";
	if ($result = $db->query($requete)) {
		echo $db->error;
		$i=1;
		$an = (new DateTime())->format('Y');
		echo "<label>Ann&eacute;e :&nbsp;</label><input type='text' id='filtercure' value='$an' /><br>";
		echo "<table>";
        echo "<tr><th id='col-type'>Type</th><th>Du</th><th>Au</th>"
            ."<th><a href='?page=stage&action=create'><img src='tpl/$tplname/img/create.png' alt='create' title='Cr&eacute;er' /></a></th>"
            ."<th></th></tr>";

		while ($array = $result->fetch_array()) {
			extract($array);
			$date        = new DateTime($date_deb);
			$date_deb    = $date->format('d/m/Y');
			$annee       = $date->format('Y');
			$date        = new DateTime($date_fin);
			$date_fin    = $date->format('d/m/Y');

			switch ($type) {
				case "md": $type="Je&ucirc;ne/Mono-di&egrave;te"; break;
				case "cd": $type="Cure d&eacute;tox"; break;
				default : $type="Non renseign&eacute;";
			}
        	echo "<tr class='allcures $annee'>"
        	    ."<td><a href='?page=stage&action=read&id=$id_stage' title='Voir les curistes'>$type</a></td>"
                ."<td>$date_deb</td><td>$date_fin</td><td><a href='?page=stage&action=delete&id=$id_stage'>"
                ."<img class='delete-link' src='tpl/$tplname/img/delete.png' alt='delete' title='Supprimer' /></a></td>"
                ."<td><a href='?page=stage&action=update&id=$id_stage'><img src='tpl/$tplname/img/update.png' alt='update' title='Modifier' /></a></td>"
                ."</tr>";
    	}
		echo "</table>";
	}
	echo "<div id='retour'><a href='?'><img src='tpl/$tplname/img/home.png' alt='home' title='Accueil' /></a></div>";
	$result->close();
}

function updateStage() {
	global $db,$sql_prefix,$tplname,$tpldir;
	$format= new format;

	/* J'ai bien re�u l'id ? */
	if (isset($_GET) && !empty($_GET['id'])) {
		$id=(int) $_GET['id'];

		/* Le formulaire vient d'�tre envoy� */
		if (isset($_POST) && !empty($_POST['submit'])) {
			$_POST=$format->toMysql($_POST);
			extract($_POST);

			$date_deb=$format->dateFrToMysql($date_deb);
			$date_fin=$format->dateFrToMysql($date_fin);

			$requete="UPDATE ".$sql_prefix."stage SET date_deb='$date_deb', date_fin='$date_fin', type='$type' WHERE id_stage='$id'";
			$db->query($requete);
			echo $db->error;
			echo "<br/>La cure $id a bien &eacute;t&eacute; modifi&eacute;e<br/><a href='?page=stage'><img src='tpl/$tplname/img/previous.png' alt='retour' title='Retour' /></a>";
		} else {
			/* Le formulaire n'a pas �t� envoy� donc on l'affiche */
			$requete="select * from ".$sql_prefix."stage WHERE id_stage='$id'";
			$result = $db->query($requete);
			echo $db->error;
			$array = $result->fetch_array();
			extract($array);

			$date_deb=$format->dateMysqlToFr($date_deb);
			$date_fin=$format->dateMysqlToFr($date_fin);

			require_once ($tpldir."/forms/stageupdate.phtml");
		    $result->close();
		}
	} else {
		/* Je n'ai pas re�u l'id ! */
		echo "<div id='retour'>Erreur !<br/><a href='?page=stage'><img src='tpl/$tplname/img/previous.png' alt='retour' title='Retour' /></a></div>";
	}

}

function deleteStage() {
	global $db,$sql_prefix,$tplname;
	if (isset($_GET) && !empty($_GET['id'])) {
		$id=(int) $_GET['id'];
		$requete="delete from ".$sql_prefix."stage WHERE id_stage='$id'";
		$requete2="delete from ".$sql_prefix."participer WHERE id_stage='$id'";
		$result = $db->query($requete);
		echo $db->error;
		$result2 = $db->query($requete2);
		echo $db->error;
		echo "<div id='retour'>La cure $id a bien &eacute;t&eacute; supprim&eacute;e<br/><a href='?page=stage'><img src='tpl/$tplname/img/previous.png' alt='retour' title='Retour' /></a></div>";
	} else {
		echo "<div id='retour'>Erreur ! <a href='?page=stage'><img src='tpl/$tplname/img/previous.png' alt='retour' title='Retour' /></a></div>";
	}
}

/* Sur la page d'un stage, j'ajoute un stagiaire */
function associateStage() {
	global $sql_prefix,$db,$tpldir,$tplname;
	$format = new format;
	$type_diete="";

	// Si le formulaire est renseign�, ajout en BDD
	if (isset($_POST) && !empty($_POST['submit'])) {

		/**** Formater les donn�es ****/
		$_POST=$format->toMysql($_POST);
		extract($_POST);
		$id_stage=(int) $id_stage;
		$id_stagiaire=(int) $id_stagiaire;
		$_SESSION['type_diete']=htmlentities($type_diete);
		$query="insert into ".$sql_prefix."participer values ('$id_stage','$id_stagiaire',NULL,'$type_diete')";
		$db->query($query);
		if ($db->error!="") {
			echo "<div id='retour'>Erreur : $db->error<br/>
			<a href='?page=stage&action=read&id=".$id_stage."'><img src='tpl/$tplname/img/previous.png' alt='retour' title='Retour' /></a></div>";
		} else {
			echo "<div><a alt='Stats' href='?page=stat&action=create&id_stagiaire=$id_stagiaire&id_stage=$id_stage'>Statistiques : <img src='tpl/$tplname/img/stats.png' alt='Stats' /></a></div>";
		}
	}
	// Si le formulaire n'est pas renseign�, on l'ajoute
	else {
		require_once ($tpldir."/forms/stageassoc.phtml");
	}
}


function dissociateStage() {
	global $db,$sql_prefix,$tplname;
	if (isset($_GET) && !empty($_GET['id_stagiaire']) && !empty($_GET['id_stage'])) {
		$id_stagiaire=(int) $_GET['id_stagiaire'];
		$id_stage=(int) $_GET['id_stage'];
		$requete="delete from ".$sql_prefix."participer WHERE id_stagiaire='$id_stagiaire' and  id_stage='$id_stage'";
		$result = $db->query($requete);
		echo $db->error;
		echo "<div id='retour'>Le/la curiste a &eacute;t&eacute; dissoci&eacute;(e) de la cure<br/><a href='?page=stage&action=read&id=$id_stage'><img src='tpl/$tplname/img/previous.png' alt='retour' title='Retour' /></a></div>";
	} else {
		echo "<div id='retour'>Erreur ! <a href='?page=stage'><img src='tpl/$tplname/img/previous.png' alt='retour' title='Retour' /></a></div>";
	}
}


function readStagiairesDuStage($id_stage) {
	global $db,$sql_prefix,$tplname,$tpldir;

	/* R�cup�rer les id de stages effectu�s */
	$requete="SELECT ".$sql_prefix."stagiaire.id_stagiaire , ".$sql_prefix."stagiaire.nom , ".$sql_prefix."stagiaire.prenom FROM ".$sql_prefix."participer, ".$sql_prefix."stagiaire
		WHERE id_stage = $id_stage
		AND ".$sql_prefix."participer.id_stagiaire = ".$sql_prefix."stagiaire.id_stagiaire
		ORDER BY ".$sql_prefix."stagiaire.nom asc , ".$sql_prefix."stagiaire.prenom asc";

	if ($result = $db->query($requete)) {
		echo $db->error;
		$i=1;
		echo "<h2>Participants</h2>";
		echo "<ul>";
		while ($array = $result->fetch_array()) {
			$i=1;
			extract($array);
        	echo "<li><a href='?page=stagiaire&action=read&id=$id_stagiaire'>".$nom."</a> ".$prenom;
        	echo "<a href='?page=stat&id_stagiaire=".$id_stagiaire."&id_stage=".$id_stage."'><img src='tpl/".$tplname."/img/stats.png' alt='Statistiques Poids' title='Statistiques Poids'  /></a>
        	<a href='?page=stagiaire&action=bilan&id=$id_stagiaire'><img src='tpl/".$tplname."/img/bilan.png' alt='Bilan' title='Bilan du stagiaire' /></a>
        	<a href='?page=stage&action=dissociate&id_stagiaire=".$id_stagiaire."&id_stage=".$id_stage."'><img class='delete-link' src='tpl/".$tplname."/img/delete.png' alt='Suppression' title='Supprimer' /></a></li>";
			$i++;
		}
		echo "</ul>";
    	if ($i == 1) echo "<li>Aucun curiste n'est associ&eacute; &agrave; cette cure.</li>";

    	$requete2="SELECT type from ".$sql_prefix."stage where id_stage=$id_stage";
		$type="";
		if ($result2=$db->query($requete2)) {
			$array2=$result2->fetch_array();
			if(htmlentities($array2['type']) == "md") $type="&type=md";
		}

		echo "<a href='?page=stage&action=associate&id_stage=".$id_stage."$type'><img src='tpl/$tplname/img/create.png' alt='Nouveau curiste' title='Cr&eacute;er' /></a>";
	    $result->close();
	}

	echo "<div id='retour'><a href='?page=stage'><img src='tpl/$tplname/img/previous.png' alt='retour' title='Retour' /></a></div>";
}

?>
