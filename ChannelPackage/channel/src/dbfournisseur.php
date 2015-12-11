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

switch($do){
	//Log in User
	case 'add':
		(isset($_POST['codefournisseur']) && $_POST['codefournisseur']!=''  ? $codefournisseur = trim($_POST['codefournisseur']) 	: $codefournisseur = '');
		(isset($_POST['typefournisseur']) && $_POST['typefournisseur']!='0'  ? $typefournisseur = trim($_POST['typefournisseur']) 	: $typefournisseur = '');
		(isset($_POST['fournisseur']) && $_POST['fournisseur']!=''  		? $fournisseur = trim($_POST['fournisseur']) 			: $fournisseur = '');
		(isset($_POST['tel']) && $_POST['tel']!=''  						? $tel = trim($_POST['tel']) 							: $tel = '');
		(isset($_POST['email']) && $_POST['email']!=''  					? $email = trim($_POST['email']) 						: $email = '');
		(isset($_POST['adresse']) && $_POST['adresse']!=''  				? $adresse = trim($_POST['adresse']) 					: $adresse = '');
		(isset($_POST['responsable']) && $_POST['responsable']!=''  		? $responsable = trim($_POST['responsable']) 			: $responsable = '');
		(isset($_POST['telresponsable']) && $_POST['telresponsable']!=''  	? $telresponsable = trim($_POST['telresponsable']) 		: $telresponsable = '');
		(isset($_POST['emailresponsable']) && $_POST['emailresponsable']!=''? $emailresponsable = trim($_POST['emailresponsable']) 	: $emailresponsable = '');

		//SQL
		$sql  = "INSERT INTO `fournisseur` ( `CODE_FOUR`, CODE_TYPEFOUR, `FOUR_NOM`, `FOUR_TEL`, `FOUR_ADRESSE`, `FOUR_EMAIL`, `FOUR_RESPONSABLE`, `FOUR_RESPTEL`, `FOUR_RESPEMAIL`) VALUES
		('".addslashes($codefournisseur)."', '".addslashes($typefournisseur)."','".addslashes($fournisseur)."', '".addslashes($tel)."','".addslashes($adresse)."', '".addslashes($email)."',
		'".addslashes($responsable)."', '".addslashes($telresponsable)."', '".addslashes($emailresponsable)."' );";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout d\'un fournisseur (, '.$fournisseur.')'); //updateLog($username, $idcust, $action='' )
		header('location:fournisseur.php?selectedTab=par&rs=1');
		break;

	case 'update':
		(isset($_POST['oldcodefournisseur']) && $_POST['oldcodefournisseur']!=''  ? $oldcodefournisseur = trim($_POST['oldcodefournisseur']) 	: $oldcodefournisseur = '');
		(isset($_POST['typefournisseur']) && $_POST['typefournisseur']!='0'  ? $typefournisseur = trim($_POST['typefournisseur']) 	: $typefournisseur = '');
		(isset($_POST['codefournisseur']) && $_POST['codefournisseur']!=''  ? $codefournisseur = trim($_POST['codefournisseur']) 	: $codefournisseur = '');
		(isset($_POST['fournisseur']) && $_POST['fournisseur']!=''  		? $fournisseur = trim($_POST['fournisseur']) 			: $fournisseur = '');
		(isset($_POST['tel']) && $_POST['tel']!=''  						? $tel = trim($_POST['tel']) 							: $tel = '');
		(isset($_POST['email']) && $_POST['email']!=''  					? $email = trim($_POST['email']) 						: $email = '');
		(isset($_POST['adresse']) && $_POST['adresse']!=''  				? $adresse = trim($_POST['adresse']) 					: $adresse = '');
		(isset($_POST['responsable']) && $_POST['responsable']!=''  		? $responsable = trim($_POST['responsable']) 			: $responsable = '');
		(isset($_POST['telresponsable']) && $_POST['telresponsable']!=''  	? $telresponsable = trim($_POST['telresponsable']) 		: $telresponsable = '');
		(isset($_POST['emailresponsable']) && $_POST['emailresponsable']!=''? $emailresponsable = trim($_POST['emailresponsable']) 	: $emailresponsable = '');

		//SQL
		$sql  = "UPDATE `fournisseur` SET `CODE_FOUR`='".addslashes($codefournisseur)."',
		CODE_TYPEFOUR='".addslashes($typefournisseur)."', `FOUR_NOM`='".addslashes($fournisseur)."', `FOUR_TEL`='".addslashes($tel)."',
		`FOUR_ADRESSE`='".addslashes($adresse)."', `FOUR_EMAIL`='".addslashes($email)."', `FOUR_RESPONSABLE`='".addslashes($responsable)."',
		`FOUR_RESPTEL`='".addslashes($telresponsable)."', `FOUR_RESPEMAIL`='".addslashes($emailresponsable)."' WHERE CODE_FOUR LIKE '".addslashes($oldcodefournisseur)."'";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Modification d\'un fournisseur ('.$id.', '.$fournisseur.')'); //updateLog($username, $idcust, $action='' )
		header('location:fournisseur.php?selectedTab=par&rs=2');
		break;

	case 'check':

		$msg = "";
		(isset($_POST['codefournisseur']) && $_POST['codefournisseur']!='' 		? $codefournisseur= trim($_POST['codefournisseur']) 	: $codefournisseur = '');

		if($codefournisseur !=''){
			$sql = "SELECT COUNT(CODE_FOUR) AS NBRE FROM  `fournisseur` WHERE `CODE_FOUR` LIKE '".addslashes($codefournisseur)."'";
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

			if($row['NBRE']>0) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Ce code existe d&eacute;j&agrave;, veuillez entrer un autre code fournisseur.';}
		}
		echo $msg;
		break;

	default : ///Nothing
		//header('location:../index.php');

}

(isset($_POST['myaction']) && $_POST['myaction']!='' ? $myaction = $_POST['myaction'] : $myaction = '');
switch($myaction){

	case 'edit':
		(isset($_POST['rowSelection']) ? $id = $_POST['rowSelection'][0] : $id ='');
		$split = preg_split('/@/',$id);
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$sql = "SELECT * FROM  `fournisseur` WHERE `CODE_FOUR` LIKE '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);
		$_SESSION['DATA_FO'] = $row;
		header('location:editfournisseur.php?selectedTab=par&rs=2');
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
			$sql = "DELETE FROM  `fournisseur` WHERE `CODE_FOUR` LIKE '".addslashes($split[0])."'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
		}
		header('location:fournisseur.php?selectedTab=par&rs=4');
		break;

	default : ///Nothing
		//header('location:../index.php');

}

if($myaction =='' && $do =='') header('location:../index.php');

?>
