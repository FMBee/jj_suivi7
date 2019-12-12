<?php

if (isset($_GET) && !empty($_GET['debut'])) $debut=(int) $_GET['debut'];
else $debut=0;

if (isset($_GET) && !empty($_GET['action'])) $action=htmlentities($_GET['action']);
else $action="readAll";

switch ($action) {
	case "create" :
		createStagiaire();
		break;
	case "readAll" :
		readAllStagiaire($debut);
		break;
	case "read" :
		readStagiaire();
		break;
	case "update" :
		updateStagiaire();
		break;
	case "delete" :
		deleteStagiaire();
		break;
	case "associate" :
		associateStagiaire();
		break;
	case "dissociate" :
		dissociateStagiaire();
		break;
	case "bilan" :
		bilanStagiaire();
		break;
	default :
		readAllStagiaire($debut);
		break;
}



function createStagiaire() {
	global $sql_prefix,$db,$tpldir,$tplname;
	$format=new format;

	// Si le formulaire est renseign�, ajout en BDD
	if (isset($_POST) && !empty($_POST['submit'])) {

		/**** Formater les donn�es ****/
		$_POST=$format->toMysql($_POST);
		extract($_POST);
		#$nom=strtolower($nom);
		#$prenom=strtolower($prenom);

		/* Le nom existe-il d�j� ? */
		$query1="select nom,prenom from  ".$sql_prefix."stagiaire where nom='$nom' and prenom='$prenom'";
		$result1 = $db->query($query1);
		echo $db->error;
		$nbln = $result1->num_rows;

		if ($nbln > 0) echo "<div id='retour'>Le/la curiste existe d&eacute;j&agrave; !<br /><a href='?page=stagiaire'><img src='tpl/$tplname/img/previous.png' alt='retour' title='Retour' /></a></div>";
		else {
			$query="insert into ".$sql_prefix."stagiaire values (NULL,'$nom','$prenom','$sexe')";
			$db->query($query);
			echo $db->error;
			echo "<div id='retour'>Le/la curiste a bien &eacute;t&eacute; ajout&eacute;(e)<br /><a href='?page=stagiaire'><img src='tpl/$tplname/img/previous.png' alt='retour' title='Retour' /></a></div>";
		}
	}

	// Si le formulaire n'est pas renseign�, on l'ajoute
	else {
		require_once ($tpldir."/forms/stagiaireadd.phtml");
	}
}


function readStagiaire() {
	global $db,$sql_prefix,$tplname,$tpldir;
	$id=NULL;
	$format=new format;

	/* Compter le nombre de stagiaires pour les pr�c�dents et suivants */
	$requete2="select count(*) as nbln from ".$sql_prefix."stagiaire";
	if ($result2 = $db->query($requete2))
	echo $db->error;
	$array2 = $result2->fetch_array();
	extract($array2);
	$result2->close();

	if (isset($_GET) && !empty($_GET['id'])) {
		$id=(int) $_GET['id'];
		$requete="select * from ".$sql_prefix."stagiaire WHERE id_stagiaire='$id'";
		$result = $db->query($requete);
		echo $db->error;
		$array = $result->fetch_array();
		extract($array);
		$sexe=$format->sexe($sexe);

		$_SESSION['nomprenom']="<h3>$nom $prenom</h3>";

		$Prenom = ucfirst($prenom);
		$Nom = strtoupper($nom);
		echo "<div id='stages'><h2>Cures de $Prenom $Nom &nbsp;&nbsp;&nbsp;";
		echo "<a href='?page=stagiaire&action=update&id=$id'><img src='tpl/$tplname/img/update.png' alt='update' title='Modifier' /></a></h2>";

		/* R�cup�rer les id de stages effectu�s */
		$requete="SELECT ".$sql_prefix."stage.* , ".$sql_prefix."participer.type_diete
			FROM ".$sql_prefix."participer, ".$sql_prefix."stagiaire, ".$sql_prefix."stage
			WHERE ".$sql_prefix."stagiaire.id_stagiaire =$id
			AND ".$sql_prefix."participer.id_stagiaire =".$sql_prefix."stagiaire.id_stagiaire
			AND ".$sql_prefix."stage.id_stage = ".$sql_prefix."participer.id_stage
			ORDER BY ".$sql_prefix."stage.date_deb desc";

		if ($result = $db->query($requete)) {
			echo $db->error;
			$i=1;
			while ($array = $result->fetch_array()) {
				echo "<ul>";
				extract($array);
				$tab_id_stage[$i]=$id_stage;
				$i++;
				switch ($type) {
					case "cd" :
						$type="Cure d&eacute;tox";
						break;
					default :
						$type=$type_diete;
						break;
				}
				$date = new DateTime($date_deb);
				$date_deb=$date->format('d/m/Y');
				$date = new DateTime($date_fin);
				$date_fin=$date->format('d/m/Y');
	        	echo "<li>".$type." du ".$date_deb." au ".$date_fin."
	        	<a href='?page=stat&id_stagiaire=".$id_stagiaire."&id_stage=".$id_stage."'><img src='tpl/".$tplname."/img/stats.png' alt='Statistiques Poids' title='Statistiques Poids' /></a>
	        	<a href='?page=stagiaire&action=dissociate&id_stagiaire=".$id_stagiaire."&id_stage=".$id_stage."'><img class='delete-link' src='tpl/".$tplname."/img/delete.png' alt='Suppression' title='Supprimer' /></a></li>";
				echo "</ul>";
	    	}
	    	if ($i == 1) echo "Ce/cette curiste n'a particip&eacute; a aucune cure.<br/>";
			echo "<a href='?page=stagiaire&action=associate&id_stagiaire=".$id_stagiaire."'><img src='tpl/$tplname/img/create.png' alt='Nouvelle cure' title='Cr&eacute;er' /></a>";
		    $result->close();
		}
	} else {
		echo "<div id='stages'>Erreur de lecture du curiste !</div>";
	}

	echo "<a href='?page=stagiaire&action=bilan&id=$id_stagiaire'><h2>Fiche bilan<br/>
			<img src='tpl/$tplname/img/bilan.png' alt='Bilan curiste' /></h2></a>";
	echo "<div id='retour'><a href='?page=recherche'><img src='tpl/$tplname/img/previous.png' alt='retour' title='Retour' /></a></div>";
}


function readAllStagiaire($debut) {
	global $sql_prefix,$db,$tplname;
	$format=new format;

	echo "<h2>Liste des curistes</h2>
	<div id='liste_curistes'>";

	if (isset($_GET) && !empty($_GET['lettre'])) $lettre=$_GET['lettre'];
	else $lettre="A";

	foreach(range('A','Z') as $i) {
		if ($i==$lettre) echo "<strong>";
		echo "<a href='?page=stagiaire&lettre=$i'>$i</a>";
		if ($i==$lettre) echo "</strong>";
		if ($i != "Z") echo " | ";
	}
	echo "<br/>";

	/* Affichage des stagiaires */
	$requete="select * from ".$sql_prefix."stagiaire
		where nom like '$lettre%' order by nom, prenom asc";

	echo "<table>";
	echo "<tr><th>Nom</th><th>Pr&eacute;nom</th><th><a href='?page=stagiaire&action=create'><img src='tpl/$tplname/img/create.png' alt='create' title='Cr&eacute;er' /></a></th></tr>";
	if ($result = $db->query($requete)) {
		echo $db->error;
		while ($array = $result->fetch_array()) {
			extract($array);
        	echo "<tr><td><a href='?page=stagiaire&action=read&id=".$id_stagiaire."' title='Voir ses cures'>".$nom."</a></td><td>".$prenom."</td>
				<td><a href='?page=stagiaire&action=update&id=$id_stagiaire'><img src='tpl/$tplname/img/update.png' alt='update' title='Modifier' /></a>
					<a href='?page=stagiaire&action=bilan&id=$id_stagiaire'><img src='tpl/$tplname/img/bilan.png' alt='Bilan' title='Bilan' /></a>
					<a href='?page=stagiaire&action=delete&id=$id_stagiaire'><img class='delete-link' src='tpl/$tplname/img/delete.png' alt='delete' title='Supprimer' /></a></td></tr>";
    	}
	} else {
		echo "<tr><th colspan='3'>Aucun(e) curiste pour la lettre $i</th></tr>";
	}
	$result->close();
	echo "<tr><th>Nom</th><th>Pr&eacute;nom</th><th><a href='?page=stagiaire&action=create'><img src='tpl/$tplname/img/create.png' alt='create' title='Cr&eacute;er' /></a></th></tr>";
	echo "</table>";
	echo "</div>";
	echo "<div id='retour'><a href='?'><img src='tpl/$tplname/img/home.png' alt='home' title='Accueil' /></a></div>";
}


function updateStagiaire() {
	global $db,$sql_prefix,$tplname,$tpldir;
	$format = new format;

	/* J'ai bien re�u l'id ? */
	if (isset($_GET) && !empty($_GET['id'])) {
		$id=(int) $_GET['id'];

		/* Le formulaire vient d'�tre envoy� */
		if (isset($_POST) && !empty($_POST['submit'])) {
		$_POST=$format->toMysql($_POST);
		extract($_POST);

			/**** V�rifier les donn�es ****/

			$requete="UPDATE ".$sql_prefix."stagiaire SET nom='$nom', prenom='$prenom', sexe='$sexe' WHERE id_stagiaire='$id'";
			$db->query($requete);
			echo $db->error;
			echo "Le/la curiste $id a bien &eacute;t&eacute; modifi&eacute;(e)<br/><a href='?page=stagiaire&action=read&id=$id'>"
			    ."<img src='tpl/$tplname/img/previous.png' alt='retour' title='Retour' /></a>";
		} else {
			/* Le formulaire n'a pas �t� envoy� donc on l'affiche */
			$requete="select * from ".$sql_prefix."stagiaire WHERE id_stagiaire='$id'";
			$result = $db->query($requete);
			$array = $result->fetch_array();
			extract($array);
	//		echo $id_stagiaire,$nom,$prenom,$sexe,$dob,$adresse,$cp,$ville,$telfixe,$telportable,$mail;
			require_once ($tpldir."/forms/stagiaireupdate.phtml");
		    $result->close();
		}
	} else {
	/* Je n'ai pas re�u l'id ! */
		echo "<div id='retour'>Erreur ! <a href='?page=stagiaire'><img src='tpl/$tplname/img/previous.png' alt='retour' title='Retour' /></a></div>";
	}

}


function deleteStagiaire() {
	global $db,$sql_prefix,$tplname;
	if (isset($_GET) && !empty($_GET['id'])) {
		$id=(int) $_GET['id'];
		$requete="delete from ".$sql_prefix."stagiaire WHERE id_stagiaire='$id'";
		$requete2="delete from ".$sql_prefix."participer WHERE id_stagiaire='$id'";
		$result = $db->query($requete);
		echo $db->error;
		$result2 = $db->query($requete2);
		echo $db->error;
		echo "<div id='retour'>Le/la curiste $id a bien &eacute;t&eacute; supprim&eacute;(e)<br/><a href='?page=stagiaire'><img src='tpl/$tplname/img/previous.png' alt='retour' title='Retour' /></a></div>";
	} else {
		echo "<div id='retour'>Erreur ! <a href='?page=stagiaire'><img src='tpl/$tplname/img/previous.png' alt='retour' title='Retour' /></a></div>";
	}
}


/* Sur la page d'un stagiaire, j'ajoute un stage */
function associateStagiaire() {
	global $sql_prefix,$db,$tpldir,$tplname;
	$format = new format;

	// Si le formulaire est renseign�, ajout en BDD
	if (isset($_POST) && !empty($_POST['submit'])) {

		/**** Formater les donn�es ****/
		$_POST=$format->toMysql($_POST);
		extract($_POST);
		$id_stage=(int) $id_stage;
		$id_stagiaire=(int) $id_stagiaire;
		$type_diete=htmlentities($type_diete);

		/**** Types et sous-types ****/

		$requete2="SELECT type from ".$sql_prefix."stage where id_stage=$id_stage";
		if ($result2=$db->query($requete2)) {
			$array2=$result2->fetch_array();
			if(htmlentities($array2['type']) == "cd") {
				$soustype=NULL;
				$_SESSION['type_diete']="Cure d&eacute;tox";
			} else {
				$soustype=$type_diete;
				$_SESSION['type_diete']=$type_diete;
			}
		}
		$query="insert into ".$sql_prefix."participer values ('$id_stage','$id_stagiaire',NULL,'$soustype')";
		$db->query($query);

		if ($db->error!="") {
			echo "<div id='retour'>Erreur : $db->error<br/>
			<a href='?page=stagiaire&action=read&id=".$id_stagiaire."'><img src='tpl/$tplname/img/previous.png' alt='retour' title='Retour' /></a></div>";
		} else {
			echo "<div><a alt='Stats' href='?page=stat&action=create&id_stagiaire=$id_stagiaire&id_stage=$id_stage'>Statistiques : <img src='tpl/$tplname/img/stats.png' alt='Stats' /></a></div>";
		}
	}
	// Si le formulaire n'est pas renseign�, on l'ajoute
	else {
		require_once ($tpldir."/forms/stagiaireassoc.phtml");
	}
}


function dissociateStagiaire() {
	global $db,$sql_prefix,$tplname;
	if (isset($_GET) && !empty($_GET['id_stagiaire']) && !empty($_GET['id_stage'])) {
		$id_stagiaire=(int) $_GET['id_stagiaire'];
		$id_stage=(int) $_GET['id_stage'];
		$requete="delete from ".$sql_prefix."participer WHERE id_stagiaire='$id_stagiaire' and  id_stage='$id_stage'";
		$result = $db->query($requete);
		echo $db->error;
		echo "<div id='retour'>Le/la curiste a &eacute;t&eacute; dissoci&eacute;(e) de la cure<br/><a href='?page=stagiaire&action=read&id=$id_stagiaire'><img src='tpl/$tplname/img/previous.png' alt='retour' title='Retour' /></a></div>";
	} else {
		echo "<div id='retour'>Erreur ! <a href='?page=stagiaire'><img src='tpl/$tplname/img/previous.png' alt='retour' title='Retour' /></a></div>";
	}
}


function bilanStagiaire() {
	global $db,$sql_prefix,$tplname,$tpldir;

	if (isset($_GET) && !empty($_GET['id'])) { $id=(int) $_GET['id']; }
	/* Liste des stages effectu�s */
	$requete="SELECT ".$sql_prefix."stage.*, type_diete FROM ".$sql_prefix."participer, ".$sql_prefix."stagiaire, ".$sql_prefix."stage
	WHERE ".$sql_prefix."stagiaire.id_stagiaire =$id
	AND ".$sql_prefix."participer.id_stagiaire =".$sql_prefix."stagiaire.id_stagiaire
	AND ".$sql_prefix."stage.id_stage = ".$sql_prefix."participer.id_stage
	ORDER BY ".$sql_prefix."stage.date_deb desc";

	$requete2="SELECT nom, prenom FROM ".$sql_prefix."stagiaire WHERE id_stagiaire=".$id;
	$result2 = $db->query($requete2);
	echo $db->error;
	$array2 = $result2->fetch_array();
	extract($array2);

	echo "<div id='stats'><h2>Fiche bilan de $prenom $nom</h2>";

	if ($result = $db->query($requete)) {
		echo $db->error;
		$i=1;

		/* BOUCLE DES STAGES */
		while ($array = $result->fetch_array()) {
			extract($array);

			#if ($type_diete!="Non d&eacute;fini" && $type_diete!="" && $type_diete!=null) $type_diete="(".$type_diete.")";
                        #else $type_diete="";

			$tab_id_stage[$i]=$id_stage;
			$i++;
			switch ($type) {
				case "cd" :
					$type="Cure d&eacute;tox";
					break;
				default:
					$type=$type_diete;
					break;
			}
			$date = new DateTime($date_deb);
			$date_deb=$date->format('d/m/Y');
			$date = new DateTime($date_fin);
			$date_fin=$date->format('d/m/Y');
        		echo "<h3>$type du $date_deb au $date_fin</h3>";

			/* STATS */

			$requete2="SELECT ".$sql_prefix."stat.* FROM ".$sql_prefix."participer, ".$sql_prefix."stagiaire, ".$sql_prefix."stage, ".$sql_prefix."stat
					WHERE ".$sql_prefix."stagiaire.id_stagiaire =$id
					AND ".$sql_prefix."stage.id_stage =$id_stage
					AND ".$sql_prefix."participer.id_stagiaire =".$sql_prefix."stagiaire.id_stagiaire
					AND ".$sql_prefix."stage.id_stage = ".$sql_prefix."participer.id_stage
					AND ".$sql_prefix."stat.id_stat = ".$sql_prefix."participer.id_stat";
			$result2 = $db->query($requete2);
			echo $db->error;
			$array2 = $result2->fetch_array();
			if(!$array2) {
				echo "Pas de statistiques pour cette cure. <a href='?page=stat&action=create&id_stagiaire=$id&id_stage=$id_stage'><img src='tpl/$tplname/img/create.png' alt='Cr&eacute;er' /></a>";
			} else {
				/* J'affiche tout ou partie des r�sultats */
				require_once("calcul.class.php");
				$calcul=new calcul();
				extract($array2);
				$id_stat=(int) $id_stat;
				echo "<table>\n";
				echo "<tr><th>&Acirc;ge</th><th>Taille (m)</th><th colspan='2'>Poids (kg)</th><th colspan='2'>Perte (%)</th><th colspan='2'>Perte (kg)</th></tr>\n";
				echo "<tr><td>$age</td><td>$taille</td><td>$pd_avt</td><td>$pd_apr</td><td colspan='2'>$perte_pd_prc</td><td colspan='2'>$perte_pd_kg</td></tr>\n";
				echo "<tr><th colspan='2'>Graisses (%)</th><th>Perte de gr. (kg)</th><th colspan='2'>Eau (%)</th><th>Perte en eau (l)</th><th colspan='2'>Graisse visc&eacute;rale (indice)</th></tr>\n";
				echo "<tr><td class='gris'>$grss_avt</td><td class='gris'>$grss_apr</td><td class='gris'>$perte_grss</td><td class='gris'>$h2o_avt</td><td class='gris'>$h2o_apr</td><td class='gris'>$perte_h2o</td><td class='gris'>$grssv_avt</td><td class='gris'>$grssv_apr</td></tr>\n";
				echo "<tr><th colspan='2'>Muscles (%)</th><th colspan='2'>Gain en muscles (%)</th><th colspan='2'>Masse osseuse (kg)</th><th colspan='2'>Gain en os (%)</th></tr>\n";
				echo "<tr><td>$mscl_prc_avt</td><td>$mscl_prc_apr</td><td colspan='2'>$gain_mscl</td><td>$mss_oss_avt</td><td>$mss_oss_apr</td><td colspan='2'>$gain_os</td></tr>\n";
				echo "<tr><th colspan='2'>Besoins &eacute;nerg&eacute;tiques (kcal/j)</th><th colspan='2'>&Acirc;ge m&eacute;tabolique (ans)</th><th colspan='2'>IMC (kg/m&sup2;)</th><th colspan='2'>Nb cures et profil sportif</th></tr>\n";
				echo "<tr><td class='gris'>$besoin_enrg_avt</td><td class='gris'>$besoin_enrg_apr</td><td class='gris'>$age_met_avt</td><td class='gris'>$age_met_apr</td><td class='gris'>$imc_avt</td><td class='gris'>$imc_apr</td><td class='gris' colspan='2'>$prof_sport</td></tr>\n";
				echo "</table>";
				echo "<br/><br/>";
			}
		    $result2->close();
		}
	    if ($i == 1) echo "<p>Ce/cette curiste n'a particip&eacute; a aucune cure.</p>";
	    $result->close();
	}
	echo "</div>";
	echo "<div id='img-static'><img src='static/bilan2.png' /><br/><img src='static/bilan.png' /></div>";
	echo "<div id='retour'><a href='?page=stagiaire&id=$id&action=read'><img src='tpl/$tplname/img/previous.png' alt='retour' title='Retour' /></a></div>";
}

?>
