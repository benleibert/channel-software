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
	//ADD BENEFICIAIRE
	case 'add':
		(isset($_POST['codebeneficiaire']) && $_POST['codebeneficiaire']!='' 	? $codebeneficiaire = trim($_POST['codebeneficiaire']) 	: $codebeneficiaire = '');
		(isset($_POST['beneficiaire']) && $_POST['beneficiaire']!='' 			? $beneficiaire 	= trim($_POST['beneficiaire']) 		: $beneficiaire = '');
		(isset($_POST['abbrege']) && $_POST['abbrege']!='' 						? $abbrege 			= trim($_POST['abbrege']) 			: $abbrege = '');
		(isset($_POST['typebeneficiaire']) && $_POST['typebeneficiaire']!='0' 	? $typebeneficiaire = trim($_POST['typebeneficiaire']) 	: $typebeneficiaire = '');
		(isset($_POST['tel']) && $_POST['tel']!='' 								? $tel 				= trim($_POST['tel']) 				: $tel = '');
		(isset($_POST['email']) && $_POST['email']!='' 							? $email 			= trim($_POST['email']) 			: $email = '');
		(isset($_POST['province']) && $_POST['province']!='0' 					? $province 		= trim($_POST['province']) 			: $province = '');
		(isset($_POST['ville']) && $_POST['ville']!='' 							? $ville 			= trim($_POST['ville']) 			: $ville = '');

		//SQL
		$sql  = "INSERT INTO `beneficiaire` ( `CODE_TYPEBENEF`, `IDPROVINCE`, `CODE_BENEF`, `BENEF_NOM`, `BENEF_EBREVIATION`,
		`BENEF_TEL`, `BENEF_VILLE`, `BENEF_EMAIL`, `BENEF_DATECREAT`) VALUES ('".addslashes($typebeneficiaire)."' , '".addslashes($province)."' ,
		'".addslashes($codebeneficiaire)."', '".addslashes($beneficiaire)."' , '".addslashes($abbrege)."' ,
		'".addslashes($tel)."' , '".addslashes($ville)."' , '".addslashes($email)."', '".addslashes(date('Y-m-d H:i:s'))."');";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout d\'un bénéficiaire ('.$codebeneficiaire.', '.$beneficiaire.')'); //updateLog($username, $idcust, $action='' )
		header('location:beneficiaire.php?selectedTab=par&rs=1');
		break;

	//UPDATE BENEFICIAIRE
	case 'update':
		(isset($_POST['oldcodebeneficiaire']) && $_POST['oldcodebeneficiaire']!='' 	? $oldcodebeneficiaire = trim($_POST['oldcodebeneficiaire']) 	: $oldcodebeneficiaire = '');
		(isset($_POST['codebeneficiaire']) && $_POST['codebeneficiaire']!='' 	? $codebeneficiaire = trim($_POST['codebeneficiaire']) 	: $codebeneficiaire = '');
		(isset($_POST['beneficiaire']) && $_POST['beneficiaire']!='' 			? $beneficiaire 	= trim($_POST['beneficiaire']) 		: $beneficiaire = '');
		(isset($_POST['abbrege']) && $_POST['abbrege']!='' 						? $abbrege 			= trim($_POST['abbrege']) 			: $abbrege = '');
		(isset($_POST['typebeneficiaire']) && $_POST['typebeneficiaire']!='0' 	? $typebeneficiaire = trim($_POST['typebeneficiaire']) 	: $typebeneficiaire = '');
		(isset($_POST['tel']) && $_POST['tel']!='' 								? $tel 				= trim($_POST['tel']) 				: $tel = '');
		(isset($_POST['email']) && $_POST['email']!='' 							? $email 			= trim($_POST['email']) 			: $email = '');
		(isset($_POST['province']) && $_POST['province']!='0' 					? $province 		= trim($_POST['province']) 			: $province = '');
		(isset($_POST['ville']) && $_POST['ville']!='' 							? $ville 			= trim($_POST['ville']) 			: $ville = '');
		(isset($_POST['id']) && $_POST['id']!='' 								? $id 				= trim($_POST['id']) 	: $id = '');

		$dateintegration = mysqlFormat($dateintegration);

		//SQL
		$sql  = "UPDATE `beneficiaire` SET `CODE_TYPEBENEF`='".addslashes($typebeneficiaire)."' ,`CODE_BENEF`='".addslashes($codebeneficiaire)."' ,`BENEF_NOM`='".addslashes($beneficiaire)."' ,
		`BENEF_EBREVIATION`='".addslashes($abbrege)."' ,`BENEF_TEL`='".addslashes($tel)."' ,`BENEF_VILLE`='".addslashes($ville)."' ,
		`BENEF_EMAIL`='".addslashes($email)."',`IDPROVINCE`='".addslashes($province)."'  WHERE CODE_BENEF LIKE '".addslashes($oldcodebeneficiaire)."' ";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Modification d\'un bénéficiaire ('.$codebeneficiaire.', '.$beneficiaire.')'); //updateLog($username, $idcust, $action='' )
		header('location:beneficiaire.php?selectedTab=par&rs=2');
		break;

	case 'check':

		$msg = "";
		(isset($_POST['code']) && $_POST['code']!='' ? $code = trim($_POST['code']) 	: $code = '');

		if($code !=''){
			$sql = "SELECT COUNT(CODE_BENEF) AS NBRE FROM  `beneficiaire` WHERE `CODE_BENEF` = '".addslashes($code)."'";
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

			if($row['NBRE']>0) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Ce code existe d&eacute;j&agrave;, veuillez entrer un autre code<?php echo getlang(16); ?>.';}
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
		$sql = "SELECT * FROM  `beneficiaire` WHERE `CODE_BENEF` LIKE   '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);
		$_SESSION['DATA_BE'] = $row;
		header('location:editbeneficiaire.php?selectedTab=par&rs=2');
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
			$sql = "DELETE FROM  `beneficiaire` WHERE `CODE_BENEF` LIKE '".addslashes($split[0])."'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
		}
		header('location:beneficiaire.php?selectedTab=par&rs=4');
		break;

	default : ///Nothing
		//header('location:../index.php');

}
if($myaction =='' && $do =='') header('location:../index.php');

?>
