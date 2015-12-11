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

//Verifie si une ligne existe dans Profil_menu
function existeProfilMenu($idprofil, $idmenu){
	//SQL
 	$sql  = "SELECT * FROM  `profil_menu` WHERE IDPROFIL='$idprofil' AND IDMENU LIKE '$idmenu'";

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

	if($query->rowCount()>0) {return 1;	}
	else return 0;
}

//Action switcher
switch($do){

	//ADD COMPTE
	case 'add':
		//COLLECT DATA
		(isset($_POST['codeprofil']) && $_POST['codeprofil']!='' ? $codeprofil 	= trim($_POST['codeprofil']) 		: $codeprofil = '');
		(isset($_POST['libprofil']) && $_POST['libprofil']!='' ? $libprofil 	= trim($_POST['libprofil']) 		: $libprofil = '');

		//SQL
		$sql  = "INSERT INTO `profil` (IDPROFIL, `LIBPROFIL` ,`DCPROF` ,`DMPROF`) VALUES ('".addslashes($codeprofil)."','".addslashes($libprofil)."', '".addslashes(date('Y-m-d'))."', '".addslashes(date('Y-m-d'))."');";

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

		//SQL DROIT
		$sql1 ='';
		$menu = getMenu();//Liste des menus

		foreach($menu as $key => $val){
			//COLLECT DATA
			$visible =$key.'visible';
			$ajout =$key.'ajout';
			$modif =$key.'modif';
			$suppr =$key.'suppr';
			$annul =$key.'annul';
			$valid =$key.'valid';

			(isset($_POST[$visible]) && $_POST[$visible]!='' 	? $visible 	= trim($_POST[$visible])  	: $visible = '0');
			(isset($_POST[$ajout]) && $_POST[$ajout]!='' 		? $ajout 	= trim($_POST[$ajout])  	: $ajout = '0');
			(isset($_POST[$modif]) && $_POST[$modif]!='' 		? $modif 	= trim($_POST[$modif])  	: $modif = '0');
			(isset($_POST[$suppr]) && $_POST[$suppr]!='' 		? $suppr 	= trim($_POST[$suppr])  	: $suppr = '0');
			(isset($_POST[$annul]) && $_POST[$annul]!='' 		? $annul 	= trim($_POST[$annul])  	: $annul = '0');
			(isset($_POST[$valid]) && $_POST[$valid]!='' 		? $valid 	= trim($_POST[$valid])  	: $valid = '0');

			$sql1 .= "INSERT INTO `profil_menu` (`IDPROFIL` ,`IDMENU` ,`VISIBLE` ,`AJOUT` ,`MODIF` ,`SUPPR` ,`ANNUL` ,`VALID`) VALUES (
			'$codeprofil', '$key', '$visible' , '$ajout', '$modif' , '$suppr' , '$annul' , '$valid'); ";

		}

		$query =  $cnx->prepare($sql1); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//UPDATE LOG FILE
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'], $_SESSION['GL_USER']['MLLE'], 'Ajout d\'un profil ('.$libprofil.')'); //updateLog($username, $idcust, $action='' )
		header('location:profil.php?selectedTab=par&rs=1');
		break;

	//UPDATE BENEFICIAIRE

	case 'update':

		(isset($_POST['id']) && $_POST['id']!='' 				? $id = trim($_POST['id']) : 				$id = '');
		(isset($_POST['libprofil']) && $_POST['libprofil']!='' 	? $libprofil = trim($_POST['libprofil']) : 	$libprofil = '');

		//SQL
		$sql  = "UPDATE `profil` SET `LIBPROFIL`='".addslashes($libprofil)."' ,`DMPROF`='".addslashes(date('Y-m-d'))."' WHERE IDPROFIL LIKE '$id';";

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

		//SQL DROIT
		$sql1 ='';
		$menu = getMenu();

		foreach($menu as $key => $val){
			//COLLECT DATA
			$visible =$key.'visible';
			$ajout =$key.'ajout';
			$modif =$key.'modif';
			$suppr =$key.'suppr';
			$annul =$key.'annul';
			$valid =$key.'valid';

			(isset($_POST[$visible]) && $_POST[$visible]!='' 	? $visible 	= trim($_POST[$visible])  	: $visible = '0');
			(isset($_POST[$ajout]) && $_POST[$ajout]!='' 		? $ajout 	= trim($_POST[$ajout])  	: $ajout = '0');
			(isset($_POST[$modif]) && $_POST[$modif]!='' 		? $modif 	= trim($_POST[$modif])  	: $modif = '0');
			(isset($_POST[$suppr]) && $_POST[$suppr]!='' 		? $suppr 	= trim($_POST[$suppr])  	: $suppr = '0');
			(isset($_POST[$annul]) && $_POST[$annul]!='' 		? $annul 	= trim($_POST[$annul])  	: $annul = '0');
			(isset($_POST[$valid]) && $_POST[$valid]!='' 		? $valid 	= trim($_POST[$valid])  	: $valid = '0');

			if(existeProfilMenu($id, $key)==1){
				$sql1 .= "UPDATE `profil_menu` SET `IDPROFIL`='$id' ,`IDMENU`='$key' ,`VISIBLE`='$visible' ,`AJOUT`='$ajout' ,`MODIF`='$modif' ,
				`SUPPR`='$suppr' ,`ANNUL`='$annul' ,`VALID`='$valid' WHERE IDPROFIL LIKE '$id' AND IDMENU LIKE '$key'; ";
			}
			else{
				$sql1 .= "INSERT INTO `profil_menu` (`IDPROFIL` ,`IDMENU` ,`VISIBLE` ,`AJOUT` ,`MODIF` ,`SUPPR` ,`ANNUL` ,`VALID`) VALUES (
				'$id', '$key', '$visible' , '$ajout', '$modif' , '$suppr' , '$annul' , '$valid'); ";
			}

		}

		$query =  $cnx->prepare($sql1); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//UPDATE LOG FILE
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Modification d\'un profil ('.$libprofil.')'); //updateLog($username, $idcust, $action='' )
		header('location:profil.php?selectedTab=par&rs=2');
		break;

	case 'check':

		$msg = "";
		(isset($_POST['code']) && $_POST['code']!='' ? $code = trim($_POST['code']) : $code = '');

		if($code !=''){
			$sql = "SELECT COUNT(IDPROFIL) AS NBRE FROM  `profil` WHERE `IDPROFIL` = '".addslashes($code)."'";
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

			if($row['NBRE']>0) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Ce code profil existe d&eacute;j&agrave;, veuillez entrer un autre code.';}
		}
		echo $msg;
		break;

	default : ///Nothing
}

(isset($_POST['myaction']) && $_POST['myaction']!='' ? $myaction = $_POST['myaction'] : $myaction = '');
switch($myaction){

	//EDITER PROFIL
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
	 	$sql = "SELECT profil.* FROM  `profil` WHERE `IDPROFIL` LIKE '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);
		$_SESSION['DATA_PROF'] = $row;

		$sql = "SELECT profil_menu.* FROM  `profil_menu` WHERE `IDPROFIL` LIKE '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		$_SESSION['DATA_PROF_MENU']= array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)) {
			$key = $row['IDMENU'];
			$_SESSION['DATA_PROF_MENU'][$key]=$row;
		}
		header('location:editprofil.php?selectedTab=par');
		break;

	//DELET PROFIL
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
			$sql = "DELETE FROM  `profil_menu` WHERE `IDPROFIL` LIKE '".addslashes($split[0])."';
					DELETE FROM  `profil` WHERE `IDPROFIL` LIKE '".addslashes($split[0])."'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
		}
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Suppression d\'un profil utilisateur ('.$split[0].',  '.getField('IDPROFIL', $split[0], 'LIBPROFIL','profil').')'); //updateLog($username, $idcust, $action='' )
		//header('location:profil.php?selectedTab=par&rst=1');
		break;

	default : ///Nothing
}
if($myaction =='' && $do =='') header('location:../index.php');

?>