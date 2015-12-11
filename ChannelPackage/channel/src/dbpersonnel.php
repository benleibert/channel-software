<?php
/**
 *
 * @version $Id$
 * @copyright 2011
 * KG
 * Gestion de la connexion, déconnexion
 */

//PHP Session
session_start();

//MySQL Parameters
require_once('../lib/global.inc');
//PHP functions librairy
require_once('../lib/phpfuncLib.php');

//Action to do
//This variable $act say what to do (add, delete, ...)
(isset($_GET['do']) && $_GET['do']!='' ? $do = $_GET['do'] : $do = '');
(isset($_POST['myaction']) && $_POST['myaction']!='' ? $myaction = $_POST['myaction'] : $myaction = '');

if($myaction =='' && $do !=''){
switch($do){
	//ADD PERSONNEL
	case 'add':
		(isset($_POST['nummlle']) && $_POST['nummlle']!='' 		? $nummlle 			= trim($_POST['nummlle']) 	: $nummlle = '');
		(isset($_POST['nom']) && $_POST['nom']!='' 				? $nom 				= trim($_POST['nom']) 		: $nom = '');
		(isset($_POST['prenom']) && $_POST['prenom']!='' 		? $prenom			= trim($_POST['prenom']) 	: $prenom = '');
		(isset($_POST['tel']) && $_POST['tel']!='' 				? $tel 				= trim($_POST['tel']) 		: $tel = '');
		(isset($_POST['adresse']) && $_POST['adresse']!='' 		? $adresse			= trim($_POST['adresse']) 	: $adresse = '');
		(isset($_POST['email']) && $_POST['email']!='' 			? $email 			= trim($_POST['email']) 	: $email = '');
		(isset($_POST['service']) && $_POST['service']!='0' 	? $service			= trim($_POST['service']) 	: $service = '');
		(isset($_POST['fonction']) && $_POST['fonction']!='' 	? $fonction			= trim($_POST['fonction']) 	: $fonction = '');
		(isset($_POST['adresse']) && $_POST['fonction']!='' 	? $adresse			= trim($_POST['adresse']) 	: $adresse = '');

		//SQL
		$sql  = "INSERT INTO `personnel` (`NUM_MLLE` ,`CODE_MAGASIN` ,`PERS_NOM` ,`PERS_PRENOMS` ,`PERS_TEL` ,`PERS_EMAIL` ,`PERS_ADRESSE` ,`PERS_FONCTION`) VALUES (";
		$sql .= "'".addslashes($nummlle)."' , '".addslashes($service)."', '".addslashes($nom)."' , '".addslashes($prenom)."' , ";
		$sql .= "'".addslashes($tel)."' , '".addslashes($email)."','".addslashes($adresse)."','".addslashes($fonction)."');";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'], $_SESSION['GL_USER']['MLLE'], 'Ajout d\'une personne ('.$nummlle.', '.$nom.' '.$prenom.')'); //updateLog($username, $idcust, $action='' )
		header('location:personnel.php?selectedTab=par&rs=1');
		break;

	//UPDATE BENEFICIAIRE

	case 'update':
		(isset($_POST['oldnummlle']) && $_POST['oldnummlle']!='' ? $oldnummlle 			= trim($_POST['oldnummlle']) 	: $oldnummlle = '');
		(isset($_POST['nummlle']) && $_POST['nummlle']!='' 	? $nummlle 			= trim($_POST['nummlle']) 	: $nummlle = '');
		(isset($_POST['nom']) && $_POST['nom']!='' 			? $nom 				= trim($_POST['nom']) 		: $nom = '');
		(isset($_POST['prenom']) && $_POST['prenom']!='' 	? $prenom			= trim($_POST['prenom']) 	: $prenom = '');
		(isset($_POST['tel']) && $_POST['tel']!='' 			? $tel 				= trim($_POST['tel']) 		: $tel = '');
		(isset($_POST['adresse']) && $_POST['adresse']!='' 	? $adresse			= trim($_POST['adresse']) 	: $adresse = '');
		(isset($_POST['email']) && $_POST['email']!='' 		? $email 			= trim($_POST['email']) 	: $email = '');
		(isset($_POST['service']) && $_POST['service']!='0' 	? $service			= trim($_POST['service']) 	: $service = '');
		(isset($_POST['fonction']) && $_POST['fonction']!='' 	? $fonction			= trim($_POST['fonction']) 	: $fonction = '');
		(isset($_POST['adresse']) && $_POST['fonction']!='' 	? $adresse			= trim($_POST['adresse']) 	: $adresse = '');

		//SQL
		$sql  = "UPDATE `personnel` SET  `NUM_MLLE`='".addslashes($nummlle)."' ,`CODE_MAGASIN`='".addslashes($service)."' ,`PERS_NOM`='".addslashes($nom)."' ,`PERS_PRENOMS`='".addslashes($prenom)."' ,";
		$sql .= "`PERS_TEL`='".addslashes($tel)."' ,`PERS_EMAIL`='".addslashes($email)."' ,`PERS_ADRESSE`='".addslashes($adresse)."' ,`PERS_FONCTION`='".addslashes($fonction)."' WHERE `NUM_MLLE`='".addslashes($oldnummlle)."'";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		unset($_SESSION['DATA_PER']);
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'], $_SESSION['GL_USER']['MLLE'], 'Modification d\'une personne ('.$oldnummlle.', '.$nummlle.')'); //updateLog($username, $idcust, $action='' )
		header('location:personnel.php?selectedTab=par&rs=2');
		break;


		//UPDATE BENEFICIAIRE

	case 'updatepp':
		(isset($_POST['oldnummlle']) && $_POST['oldnummlle']!='' ? $oldnummlle 			= trim($_POST['oldnummlle']) 	: $oldnummlle = '');
		(isset($_POST['nummlle']) && $_POST['nummlle']!='' 	? $nummlle 			= trim($_POST['nummlle']) 	: $nummlle = '');
		(isset($_POST['nom']) && $_POST['nom']!='' 			? $nom 				= trim($_POST['nom']) 		: $nom = '');
		(isset($_POST['prenom']) && $_POST['prenom']!='' 	? $prenom			= trim($_POST['prenom']) 	: $prenom = '');
		(isset($_POST['tel']) && $_POST['tel']!='' 			? $tel 				= trim($_POST['tel']) 		: $tel = '');
		(isset($_POST['adresse']) && $_POST['adresse']!='' 	? $adresse			= trim($_POST['adresse']) 	: $adresse = '');
		(isset($_POST['email']) && $_POST['email']!='' 		? $email 			= trim($_POST['email']) 	: $email = '');
		(isset($_POST['service']) && $_POST['service']!='0' 	? $service			= trim($_POST['service']) 	: $service = '');
		(isset($_POST['fonction']) && $_POST['fonction']!='' 	? $fonction			= trim($_POST['fonction']) 	: $fonction = '');
		(isset($_POST['adresse']) && $_POST['fonction']!='' 	? $adresse			= trim($_POST['adresse']) 	: $adresse = '');

		//SQL
		$sql  = "UPDATE `personnel` SET  `NUM_MLLE`='".addslashes($nummlle)."' ,`CODE_MAGASIN`='".addslashes($service)."' ,`PERS_NOM`='".addslashes($nom)."' ,`PERS_PRENOMS`='".addslashes($prenom)."' ,";
		$sql .= "`PERS_TEL`='".addslashes($tel)."' ,`PERS_EMAIL`='".addslashes($email)."' ,`PERS_ADRESSE`='".addslashes($adresse)."' ,`PERS_FONCTION`='".addslashes($fonction)."' WHERE `NUM_MLLE`='".addslashes($oldnummlle)."'";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		unset($_SESSION['DATA_PER']);
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'], $_SESSION['GL_USER']['MLLE'], 'Modification d\'une personne ('.$oldnummlle.', '.$nummlle.')'); //updateLog($username, $idcust, $action='' )
		header('location:index.php?selectedTab=home');
		break;

	case 'check':

		$msg = "";
		(isset($_POST['nummlle']) && $_POST['nummlle']!='' ? $nummlle = trim($_POST['nummlle']) 	: $nummlle = '');

		if($nummlle !=''){
			$sql = "SELECT COUNT(NUM_MLLE) AS NBRE FROM  `personnel` WHERE `NUM_MLLE` = '".addslashes($nummlle)."'";
			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			$row = $query->fetch(PDO::FETCH_ASSOC);

			if($row['NBRE']>0) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Ce num&eacute;ro matricule existe d&eacute;j&agrave;, veuillez entrer un autre num&eacute; matricule.';}
		}
		echo $msg;
		break;


	default : ///Nothing
		//header('location:../index.php');

}
}elseif($myaction !='')
switch($myaction){
	case 'edit':
		(isset($_POST['rowSelection']) ? $id = $_POST['rowSelection'][0] : $id ='');
		$split = preg_split('/@/',$id);
		print_r($split);
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$sql = "SELECT * FROM  `personnel` WHERE `NUM_MLLE` LIKE '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);
		$_SESSION['DATA_PER'] = $row;
		header('location:editpersonnel.php?selectedTab=par&rs=2');
		break;

	case 'delete':
		(isset($_POST['rowSelection']) ? $id = $_POST['rowSelection'] : $id =array());
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}

		foreach($id as $key => $val){
			$split = preg_split('/@/',$val);
			$sql = "DELETE FROM  `personnel` WHERE `NUM_MLLE` LIKE '".addslashes($split[0])."'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
		}
		header('location:personnel.php?selectedTab=par&rs=4');
		break;

	default : ///Nothing
		//header('location:../index.php');

}
if($myaction =='' && $do =='') header('location:../index.php');

?>
