<?php

/**
 * @author KG
 *
 * @version $Id$
 * @copyright 2012
 * @date 08/06/2012
 *
 * What is it about?
 * Here you will fund all database access
 * I will add, edit and update user "compte" table here
 */

//PHP Session
session_start();

//MySQL Parameters
require_once('../lib/global.inc');

//PHP functions librairy
require_once('../lib/phpfuncLib.php');

//Action to do, this variable $do say what to do (add, delete, ...)
(isset($_GET['do']) && $_GET['do']!='' ? $do = $_GET['do'] : $do = '');

//Action switcher
switch($do){

	//ADD FONCTIONNALITE
	case 'add':
		//COLLECT DATA
		(isset($_POST['codemenu']) && $_POST['codemenu']!='' 	? $codemenu 	= trim($_POST['codemenu']) 	: $codemenu = '');
		(isset($_POST['libmenu']) && $_POST['libmenu']!='' 		? $libmenu	= trim($_POST['libmenu']) 		: $libmenu = '');

		//SQL
		$sql  = "INSERT INTO `menu` (`IDMENU` ,`LIBMENU`)  VALUES ('".addslashes($codemenu)."' , '".addslashes($libmenu)."');";

		//CONNECTION
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//UPDATE LOG FILE
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['IDPERSONNE'], 'Ajout d\'un menu ('.$id.', '.$libmenu.')'); //updateLog($username, $idcust, $action='' )
		header('location:menu.php?selectedTab=par&rst=1');
		break;

	//UPDATE FONCTIONNALITE
	case 'update':
		//COLLECT DATA
		(isset($_POST['id']) && $_POST['id']!='' 				? $id 		= trim($_POST['id']) 			: $id = '');
		(isset($_POST['codemenu']) && $_POST['codemenu']!='' 	? $codemenu = trim($_POST['codemenu']) 		: $codemenu = '');
		(isset($_POST['libmenu']) && $_POST['libmenu']!='' 		? $libmenu	= trim($_POST['libmenu']) 		: $libmenu = '');

		//SQL
		$sql  = "UPDATE `menu` SET `IDMENU`='".addslashes($codemenu)."' ,`LIBMENU`='".addslashes($libmenu)."' WHERE IDMENU LIKE '$id';";

		//CONNECTION
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//UPDATE LOG FILE
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['IDPERSONNE'], 'Modifcation d\'un menu ('.$id.', '.$libmenu.')'); //updateLog($username, $idcust, $action='' )
		header('location:menu.php?selectedTab=par&rst=1');
		break;

	//CHECK IF A LOGIN IS ALLREADY USED
	case 'check':
		$msg = "";
		(isset($_POST['code']) && $_POST['code']!='' ? $code = trim($_POST['code']) 	: $code = '');

		if($code !=''){
			$sql = "SELECT COUNT(IDMENU) AS NBRE FROM  `menu` WHERE `IDMENU` LIKE '".addslashes($code)."'";
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

			if($row['NBRE']>0) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Ce compte existe d&eacute;j&agrave;, veuillez entrer un autre code.';}
		}
		echo $msg;
		break;




	default : ///Nothing
}

(isset($_POST['myaction']) && $_POST['myaction']!='' ? $myaction = $_POST['myaction'] : $myaction = '');
switch($myaction){

	//EDIT FONCTIONNALITE
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
	 	$sql = "SELECT * FROM  `menu` WHERE `IDMENU` LIKE '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);
		$_SESSION['DATA_MEN'] = $row;

		header('location:editmenu.php?selectedTab=par');
		break;

	//DELET FONCTIONNALITE
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
			$sql = "DELETE FROM  `menu` WHERE `IDMENU` = '".addslashes($split[0])."'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
		}
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['IDPERSONNE'], 'Suppression d\'un menu ('.$split[0].',  '.getField('IDMENU', $split[0], 'LIBMENU', 'menu').')'); //updateLog($username, $idcust, $action='' )
		header('location:menu.php?selectedTab=par&rst=1');
		break;

	default : ///Nothing
}
if($myaction =='' && $do =='') header('location:../index.php');

?>