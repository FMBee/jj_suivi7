<?php

if (isset($_GET) && !empty($_GET['debut'])) $debut=(int) $_GET['debut'];
else $debut=0;

if (isset($_GET) && !empty($_GET['action'])) $action=htmlentities($_GET['action']);	
else $action="read";

switch ($action) {
	case "create" :
		createStat();
		break;
	case "read" :
		readStat();
		break;
	case "update" :
		updateStat();
		break;
	case "delete" :
		deleteStat();
		break;
	default :
		readStat();
		break;
}


	
function createStat() {
	global $sql_prefix,$db,$tpldir,$tplname;
	$format = new format;
	
	// Si le formulaire est renseign�, ajout en BDD
	if (isset($_POST) && !empty($_POST['submit'])) {
		
		/**** Formater les donn�es ****/
// print_r($_POST);	    
		foreach ($_POST as $k=>$v) {
// 			if (empty($v)) $_POST[$k]="NULL";
			if (empty(trim($v))) $_POST[$k]=0;
		}
// 		$_POST=$format->toMysql($_POST);
		extract($_POST);		
		$id_stage=(int) $id_stage;
		$id_stagiaire=(int) $id_stagiaire;
		require_once("calcul.class.php");
		$calcul=new calcul();
		$perte_pd_prc=$calcul->pertePoidsPrc($pd_avt, $pd_apr);
		$perte_pd_kg=$calcul->pertePoids($pd_avt, $pd_apr);
		$perte_grss=$calcul->perteGrss($pd_avt, $grss_avt, $pd_apr, $grss_apr);
		$perte_h2o=$calcul->h2o($pd_avt, $h2o_avt, $pd_apr, $h2o_apr);
		$mscl_prc_avt=$calcul->mscl_prc($mscl_kg_avt, $pd_avt);
		$mscl_prc_apr=$calcul->mscl_prc($mscl_kg_apr, $pd_apr);
		$gain_mscl=$calcul->gain_prc($mscl_kg_avt, $pd_avt, $mscl_kg_apr, $pd_apr);
		$gain_os=$calcul->gain_prc($mss_oss_avt, $pd_avt, $mss_oss_apr, $pd_apr);
		$imc_avt=$calcul->imc($pd_avt, $taille);
		$imc_apr=$calcul->imc($pd_apr, $taille);

		$query1="INSERT INTO ".$sql_prefix."stat (`age`, `taille`, `pd_avt`, `pd_apr`, `perte_pd_prc`, `perte_pd_kg`, `grss_avt`, `grss_apr`, `perte_grss`, `h2o_avt`, `h2o_apr`, `perte_h2o`, `grssv_avt`, `grssv_apr`, `mscl_kg_avt`, `mscl_kg_apr`, `mscl_prc_avt`, `mscl_prc_apr`, `gain_mscl`, `mss_oss_avt`, `mss_oss_apr`, `gain_os`, `besoin_enrg_avt`, `besoin_enrg_apr`, `age_met_avt`, `age_met_apr`, `imc_avt`, `imc_apr`, `prof_sport`)
											VALUES ('$age', '$taille', '$pd_avt', '$pd_apr', '$perte_pd_prc', '$perte_pd_kg', '$grss_avt', '$grss_apr', '$perte_grss', '$h2o_avt', '$h2o_apr', '$perte_h2o', '$grssv_avt', '$grssv_apr', '$mscl_kg_avt', '$mscl_kg_apr', '$mscl_prc_avt', '$mscl_prc_apr', '$gain_mscl', '$mss_oss_avt', '$mss_oss_apr', '$gain_os', '$besoin_enrg_avt', '$besoin_enrg_apr', '$age_met_avt', '$age_met_apr', '$imc_avt', '$imc_apr', '$prof_sport')";
		$query1=str_replace('NAN', '0', $query1);
		$query1=str_replace('NaN', '0', $query1);
// echo $query1;
		$db->query($query1);
		echo $db->error;
		$autoincremt=$db->insert_id;
		
		/* Lier � la table participer */
		$query2="UPDATE ".$sql_prefix."participer SET id_stat='$autoincremt'
		WHERE id_stagiaire='$id_stagiaire' AND id_stage='$id_stage'";	
		$db->query($query2);
		echo $db->error;
		
		echo "<div id='retour'>Les statistiques de ce/cette curiste ont bien &eacute;t&eacute; ajout&eacute;es. <a href='?page=stagiaire&action=read&id=".$id_stagiaire."'><img src='tpl/$tplname/img/previous.png' alt='retour' title='Retour' title='Retour' /></a></div>";
	}
	
	// Si le formulaire n'est pas renseign�, on l'ajoute
	else {
		require_once ($tpldir."/forms/statadd.phtml");
	}
}

function readStat() {
	global $db,$sql_prefix,$tplname;

	/* Compter le nombre de stats pour les pr�c�dents et suivants */
	$requete2="select count(*) as nbln from ".$sql_prefix."stat";
	if ($result2 = $db->query($requete2))
	echo $db->error; 
	$array2 = $result2->fetch_array();
	extract($array2);
	$result2->close();

	if (isset($_GET) && !empty($_GET['id_stagiaire']) && !empty($_GET['id_stage'])) {
		$id_stagiaire=(int) $_GET['id_stagiaire'];
		$id_stage=(int) $_GET['id_stage'];
		
		$requete="SELECT ".$sql_prefix."stat.*, type_diete FROM ".$sql_prefix."participer, ".$sql_prefix."stagiaire, ".$sql_prefix."stage, ".$sql_prefix."stat
					WHERE ".$sql_prefix."stagiaire.id_stagiaire =$id_stagiaire
					AND ".$sql_prefix."stage.id_stage =$id_stage
					AND ".$sql_prefix."participer.id_stagiaire =".$sql_prefix."stagiaire.id_stagiaire
					AND ".$sql_prefix."stage.id_stage = ".$sql_prefix."participer.id_stage
					AND ".$sql_prefix."stat.id_stat = ".$sql_prefix."participer.id_stat";
		$result = $db->query($requete);
		echo $db->error;
		$array = $result->fetch_array();
		if(!$array) {
			echo "Pas de statistiques pour cette cure. <a href='?page=stat&action=create&id_stagiaire=$id_stagiaire&id_stage=$id_stage'><img src='tpl/$tplname/img/create.png' alt='Cr&eacute;er' /></a>";
			echo "<div id='retour'><a href='?page=stagiaire&action=read&id=".$id_stagiaire."'><img src='tpl/$tplname/img/previous.png' alt='retour' title='Retour' /></a></div>";
		} else {
			/* J'affiche tout ou partie des r�sultats */
			require_once("calcul.class.php");
			$calcul=new calcul();
			extract($array);
			$id_stat=(int) $id_stat;
	
			$requete3="SELECT prenom, nom, type, date_deb, date_fin FROM ".$sql_prefix."stagiaire, ".$sql_prefix."stage where id_stagiaire=$id_stagiaire and id_stage=$id_stage";
			$result3 = $db->query($requete3);
			echo $db->error;
			$array3 = $result3->fetch_array();
			extract($array3);

			switch ($type) {
				case "cd": $type="Cure d&eacute;tox"; break;
				default : $type=$type_diete;
			}
			
			$format = new format;
			$date_deb=$format->dateMysqlToFr($date_deb);
			$date_fin=$format->dateMysqlToFr($date_fin);
			$_SESSION['nomprenom']="<h3>$nom $prenom</h3>";
			$_SESSION['cure']="<h3>$type - du $date_deb au $date_fin</h3>";
			
			echo "<h2>$prenom $nom</h2><h3>$type du $date_deb</h3>";
			echo "<br /><a href='?page=stat&action=update&id_stagiaire=$id_stagiaire&id_stage=$id_stage&id_stat=$id_stat'><img src='tpl/$tplname/img/update.png' alt='Modifier' /></a>";
			echo "<a href='?page=stat&action=delete&id_stat=$id_stat'><img class='delete-link' src='tpl/$tplname/img/delete.png' alt='Supprimer' /></a>";
			
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
			
			echo "<div id='retour'><a href='?page=stagiaire&action=read&id=$id_stagiaire'><img src='tpl/$tplname/img/previous.png' alt='retour' title='Retour' /></a></div>";
		}
	} else {
		echo "Erreur !";
		echo "<div id='retour'><a href='?page=stagiaire'><img src='tpl/$tplname/img/previous.png' alt='retour' title='Retour' /></a></div>";
	}
}
		
function updateStat() {
	global $db,$sql_prefix,$tplname,$tpldir;
	$format = new format;
	
	echo "<h2>Statistiques</h2>";

	/* J'ai bien re�u l'id ? */
	if (isset($_GET) && !empty($_GET['id_stat'])) {
		$id_stat=(int) $_GET['id_stat'];

		/* Le formulaire vient d'�tre envoy� */
		if (isset($_POST) && !empty($_POST['submit'])) {
			/**** Formater les donn�es ****/
			foreach ($_POST as $k=>$v) {
				if (empty(trim($v))) $_POST[$k]=0;
			}
// 			$_POST=$format->toMysql($_POST);
			extract($_POST);

			$requete1="UPDATE jj_stat SET `age` = '$age',`taille` = '$taille',`pd_avt` = '$pd_avt',`pd_apr` = '$pd_apr',`perte_pd_prc` = '$perte_pd_prc',`perte_pd_kg` = '$perte_pd_kg',`grss_avt` = '$grss_avt',`grss_apr` = '$grss_apr',`perte_grss` = '$perte_grss',`h2o_avt` = '$h2o_avt',`h2o_apr` = '$h2o_apr',`perte_h2o` = '$perte_h2o',`grssv_avt` = '$grssv_avt',`grssv_apr` = '$grssv_apr',`mscl_kg_avt` = '$mscl_kg_avt',`mscl_kg_apr` = '$mscl_kg_apr',`mscl_prc_avt` = '$mscl_prc_avt',`mscl_prc_apr` = '$mscl_prc_apr',`gain_mscl` = '$gain_mscl',`mss_oss_avt` = '$mss_oss_avt',`mss_oss_apr` = '$mss_oss_apr',`gain_os` = '$gain_os',`besoin_enrg_avt` = '$besoin_enrg_avt',`besoin_enrg_apr` = '$besoin_enrg_apr',`age_met_avt` = '$age_met_avt',`age_met_apr` = '$age_met_apr',`imc_avt` = '$imc_avt',`imc_apr` = '$imc_apr',`prof_sport` = '$prof_sport' WHERE `id_stat` =$id_stat;";
			$requete1=str_replace('NAN', '0', $requete1);
			$requete1=str_replace('NaN', '0', $requete1);
// echo $requete1;			
			$db->query($requete1);
			echo $db->error;

			$requete2="SELECT * FROM ".$sql_prefix."participer WHERE id_stat='$id_stat'";
			$result=$db->query($requete2);
			echo $db->error;
			$array = $result->fetch_array();
			extract($array);
			$id_stagiaire=(int) $id_stagiaire;
			$id_stage=(int) $id_stage;
			
			echo "Les statistiques pour le/la curiste ont bien &eacute;t&eacute; modifi&eacute;es<br/>
			<a href='?page=stat&action=read&id_stagiaire=$id_stagiaire&id_stage=$id_stage'><img src='tpl/$tplname/img/previous.png' alt='retour' title='Retour' /></a>";
		} else {
			/* Le formulaire n'a pas �t� envoy� donc on l'affiche */
			//$requete="select * from ".$sql_prefix."stat WHERE id_stat='$id_stat'";
			$requete = "SELECT * FROM `jj_participer` , jj_stagiaire, jj_stage, jj_stat
				WHERE jj_participer.id_stat =$id_stat
				AND jj_stagiaire.id_stagiaire = jj_participer.id_stagiaire
				AND jj_participer.id_stage = jj_stage.id_stage
				AND jj_participer.id_stat = jj_stat.id_stat";
			$result = $db->query($requete);
			echo $db->error;
			$array = $result->fetch_array();
			extract($array);
			require_once ($tpldir."/forms/statupdate.phtml");		
		    	$result->close();
		}
	} else {
	/* Je n'ai pas re�u l'id ! */		
		echo "<div id='retour'>Erreur ! <a href='?page=stat'><img src='tpl/$tplname/img/previous.png' alt='retour' title='Retour' /></a></div>";
	}

}
	
function deleteStat() {
	global $db,$sql_prefix,$tplname;
	if (isset($_GET) && !empty($_GET['id_stat'])) {
		$id_stat=(int) $_GET['id_stat'];
		$requete="delete from ".$sql_prefix."stat WHERE id_stat='$id_stat'";
		$result = $db->query($requete);
		echo $db->error;
		echo "<div id='retour'>Les statistiques de ce/cette curiste ont bien &eacute;t&eacute; supprim&eacute;es<br/><a href='?page=stagiaire'><img src='tpl/$tplname/img/previous.png' alt='retour' title='Retour' /></a></div>";
	} else {
		echo "<div id='retour'>Erreur ! <a href='?page=stat'><img src='tpl/$tplname/img/previous.png' alt='retour' title='Retour' /></a></div>";
	}
}
	
?>
