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
		(isset($_POST['codeexercice']) && $_POST['codeexercice']!=''? $codeexercice = trim($_POST['codeexercice']) 	: $codeexercice = '');
		(isset($_POST['exercice']) && $_POST['exercice']!=''  		? $exercice 	= trim($_POST['exercice']) 		: $exercice = '');
		(isset($_POST['datefin']) && $_POST['datefin']!=''  		? $datefin 		= trim($_POST['datefin']) 		: $datefin = '');
		(isset($_POST['datedebut']) && $_POST['datedebut']!=''  	? $datedebut 	= trim($_POST['datedebut']) 	: $datedebut = '');
		(isset($_POST['cloture']) && $_POST['cloture']!=''  		? $cloture 		= trim($_POST['cloture']) 		: $cloture = '');
		(isset($_POST['datecloture']) && $_POST['datecloture']!=''  ? $datecloture 	= trim($_POST['datecloture']) 	: $datecloture = '');
		if($datecloture !='') $datecloture = mysqlFormat($datecloture);
		if($datedebut !='') $datedebut = mysqlFormat($datedebut);
		if($datefin !='') $datefin = mysqlFormat($datefin);

		//SQL
		$sql  = "INSERT INTO `exercice` (`ID_EXERCICE` ,`EX_LIBELLE` ,`EX_DATEDEBUT` ,`EX_DATEFIN` ,`EX_CLOTURE` ,`EX_DATECLOTURE`) VALUES ('".addslashes($codeexercice)."', '".addslashes($exercice)."', '".addslashes($datedebut)."', '".addslashes($datefin)."', '".addslashes($cloture)."', '".addslashes($datecloture)."')";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout d\'une nature ('.$codenature.', '.$nature.')'); //updateLog($username, $idcust, $action='' )
		header('location:exercice.php?selectedTab=par&rst=1');
		break;

	//UPDATE DOTATION
	case 'update':
		(isset($_POST['codeexercice']) && $_POST['codeexercice']!=''? $codeexercice = trim($_POST['codeexercice']) 	: $codeexercice = '');
		(isset($_POST['oldcodeexercice']) && $_POST['oldcodeexercice']!=''? $oldcodeexercice = trim($_POST['oldcodeexercice']) 	: $oldcodeexercice = '');
		(isset($_POST['exercice']) && $_POST['exercice']!=''  		? $exercice 	= trim($_POST['exercice']) 		: $exercice = '');
		(isset($_POST['datefin']) && $_POST['datefin']!=''  		? $datefin 		= trim($_POST['datefin']) 		: $datefin = '');
		(isset($_POST['datedebut']) && $_POST['datedebut']!=''  	? $datedebut 	= trim($_POST['datedebut']) 	: $datedebut = '');
		(isset($_POST['cloture']) && $_POST['cloture']!=''  		? $cloture 		= trim($_POST['cloture']) 		: $cloture = '');
		(isset($_POST['datecloture']) && $_POST['datecloture']!=''  ? $datecloture 	= trim($_POST['datecloture']) 	: $datecloture = '');
		if($datecloture !='') $datecloture = mysqlFormat($datecloture);
		if($datedebut !='') $datedebut = mysqlFormat($datedebut);
		if($datefin !='') $datefin = mysqlFormat($datefin);

		//SQL
		$sql  = "UPDATE `exercice` SET `ID_EXERCICE`= '".addslashes($codeexercice)."',`EX_LIBELLE`='".addslashes($exercice)."' ,`EX_DATEDEBUT`='".addslashes($datedebut)."' ";
		$sql .= " ,`EX_DATEFIN`='".addslashes($datefin)."' ,`EX_CLOTURE`='".addslashes($cloture)."' ,`EX_DATECLOTURE`='".addslashes($datecloture)."' WHERE ID_EXERCICE = '".addslashes($oldcodeexercice)."'";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Modification d\'un type de dotation ('.$oldcodetypedotation.', '.$codetypedotation.' - '.$categorie.')'); //updateLog($username, $idcust, $action='' )
		unset($GLOBALS['DATA_CAT']);
		header('location:exercice.php?selectedTab=par&rst=1');
		break;

	case 'check':

		$msg = "";
		(isset($_POST['codeexercice']) && $_POST['codeexercice']!='' ? $codeexercice = trim($_POST['codeexercice']) 	: $codeexercice = '');

		if($codeexercice !=''){
			$sql = "SELECT COUNT(ID_EXERCICE) AS NBRE FROM  `exercice` WHERE `ID_EXERCICE` = '".addslashes($codeexercice)."'";
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

			if($row['NBRE']>0) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Ce code existe d&eacute;j&agrave;, veuillez entrer un autre code exercice.';}
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
		$split = preg_split('/ /',$id);
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$sql = "SELECT * FROM  `exercice` WHERE `ID_EXERCICE` = '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);
		$_SESSION['DATA_EX'] = $row;
		header('location:editexercice.php?selectedTab=par&rst=1');
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
			$split = preg_split('/ /',$val);
			$sql = "DELETE FROM  `exercice` WHERE `ID_EXERCICE` = '".addslashes($split[0])."' AND EX_CLOTURE=0";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
		}
		header('location:exercice.php?selectedTab=par&rst=1');
		break;

	default : ///Nothing
		//header('location:../index.php');

}
if($myaction =='' && $do =='') header('location:../index.php');

?>
