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
	//ADD TYPEBENEFICIAIRE
	case 'add':
		(isset($_POST['codetypebeneficiaire']) && $_POST['codetypebeneficiaire']!=''  ? $codetypebeneficiaire = trim($_POST['codetypebeneficiaire']) 	: $codetypebeneficiaire = '');
		(isset($_POST['typebeneficiaire']) && $_POST['typebeneficiaire']!=''  ? $typebeneficiaire = trim($_POST['typebeneficiaire']) 	: $typebeneficiaire = '');

		//SQL
		echo $sql  = "INSERT INTO `typebeneficiaire` (`CODE_TYPEBENEF` ,`NOM_TYPEBENEF`) VALUES ('".addslashes($codetypebeneficiaire)."', '".addslashes($typebeneficiaire)."')";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout d\'un type de bénéficiaire ('.$typebeneficiaire.', '.$typebeneficiaire.')'); //updateLog($username, $idcust, $action='' )
		header('location:typebeneficiaire.php?selectedTab=par&rst=1');
		break;

	//UPDATE TYPEBENEFICIAIRE
	case 'update':
		(isset($_POST['oldcodetypebeneficiaire']) && $_POST['oldcodetypebeneficiaire']!=''  ? $oldcodetypebeneficiaire = trim($_POST['oldcodetypebeneficiaire']) 	: $codetypebeneficiaire = '');
		(isset($_POST['codetypebeneficiaire']) && $_POST['codetypebeneficiaire']!=''  ? $codetypebeneficiaire = trim($_POST['codetypebeneficiaire']) 	: $codetypebeneficiaire = '');
		(isset($_POST['typebeneficiaire']) && $_POST['typebeneficiaire']!=''  ? $typebeneficiaire = trim($_POST['typebeneficiaire']) 	: $typebeneficiaire = '');

		//SQL
		echo $sql  = "UPDATE `typebeneficiaire` SET `CODE_TYPEBENEF`='".addslashes($codetypebeneficiaire)."' ,`NOM_TYPEBENEF`='".addslashes($typebeneficiaire)."' WHERE CODE_TYPEBENEF LIKE '".addslashes($oldcodetypebeneficiaire)."'";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout d\'un type de bénéficiaire ('.$typebeneficiaire.', '.$typebeneficiaire.')'); //updateLog($username, $idcust, $action='' )
		header('location:typebeneficiaire.php?selectedTab=par&rst=1');
		break;

	case 'check':

		$msg = "";
		(isset($_POST['code']) && $_POST['code']!='' 		? $code = trim($_POST['code']) 	: $code = '');

		if($code !=''){
			$sql = "SELECT COUNT(CODE_TYPEBENEF) AS NBRE FROM  `typebeneficiaire` WHERE `CODE_TYPEBENEF` LIKE '".addslashes($code)."'";
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

			if($row['NBRE']>0) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Ce code existe d&eacute;j&agrave;, veuillez entrer un autre code type de<?php echo getlang(16); ?>.';}
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
		$sql = "SELECT * FROM  `typebeneficiaire` WHERE `CODE_TYPEBENEF` LIKE '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);
		$_SESSION['DATA_TBE'] = $row;
		header('location:edittypebeneficiaire.php?selectedTab=par&rst=1');
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
			$sql = "DELETE FROM  `typebeneficiaire` WHERE `CODE_TYPEBENEF` LIKE '".addslashes($split[0])."'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
		}
		header('location:typebeneficiaire.php?selectedTab=par&rst=1');
		break;

	default : ///Nothing
		//header('location:../index.php');

}

if($myaction =='' && $do =='') header('location:../index.php');

?>
