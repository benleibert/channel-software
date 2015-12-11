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
//require_once('../lib/global.inc');
//PHP functions librairy
require_once('../lib/phpfuncLib.php');

//Action to do
//This variable $act say what to do (add, delete, ...)
(isset($_GET['do']) && $_GET['do']!='' ? $do = $_GET['do'] : $do = '');

function ExisteCompte($compte, $magasin){
	$sql  = "SELECT LOGIN FROM `mag_compte` WHERE `LOGIN` LIKE '".addslashes($compte)."' AND CODE_MAGASIN LIKE '".addslashes($magasin)."';";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query
	return $query->rowCount();
}

switch($do){
	case 'add':
		(isset($_POST['personnel']) && $_POST['personnel']!='0' ? $personnel = trim($_POST['personnel']) 	: $personnel = '');
		(isset($_POST['compte']) && $_POST['compte']!='' 		? $compte 	= trim($_POST['compte']) 		: $compte = '');

		$sql  = '';
		foreach($_POST['mag'] as $key=>$val){
			if(!ExisteCompte($compte,$val)){
				$sql  .= "INSERT INTO `mag_compte` (`LOGIN` ,`CODE_MAGASIN`) VALUES ('".addslashes($compte)."' , '".addslashes($val)."');";
			}
		}

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			die($error->getMessage().' '.__LINE__);
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'], $_SESSION['GL_USER']['MLLE'], 'Ajout des affectations de site ('.$compte.', '.$personnel.')'); //updateLog($username, $idcust, $action='' )
		header('location:affectation.php?selectedTab=par&rs=1');
		break;

	case 'update':
		(isset($_POST['login']) && $_POST['login']!='0' 	? $login 	= trim($_POST['login']) 	: $login = '');
		(isset($_POST['mlle']) && $_POST['mlle']!='' 		? $mlle 	= trim($_POST['mlle']) 		: $mlle = '');

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			die($error->getMessage().' '.__LINE__);
		}
		//Supprimer tout
		$delSQl = "DELETE FROM mag_compte WHERE LOGIN LIKE '$login'";
		$query =  $cnx->prepare($delSQl); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		$sql  = '';
		foreach($_POST['mag'] as $key=>$val){
			if(!ExisteCompte($login,$val)){
				$sql  .= "INSERT INTO `mag_compte` (`LOGIN` ,`CODE_MAGASIN`) VALUES ('".addslashes($login)."' , '".addslashes($val)."');";
			}
		}

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			die($error->getMessage().' '.__LINE__);
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'], $_SESSION['GL_USER']['MLLE'], 'Modification des affectations de site ('.$login.', '.$mlle.')'); //updateLog($username, $idcust, $action='' )
		header('location:affectation.php?selectedTab=par&rs=2');
		break;

	case 'fillcompte':
		$list = '<option value="0"></option>';

		if(isset($_POST["personnel"]) && $_POST["personnel"]!='0'){
			//SQL
			$sql  = "SELECT * FROM compte WHERE NUM_MLLE LIKE '".stripslashes($_POST["personnel"])."' ORDER BY LOGIN ASC;";
			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				$list .= '<option value="'.$row['LOGIN'].'" >'.stripslashes($row['LOGIN']).'</option>';
			}
		}
		echo $list.'</select>';
		break;

	case 'update':
		(isset($_POST['personnel']) && $_POST['personnel']!='0' ? $personnel 	= trim($_POST['personnel']) 	: $personnel = '');
		(isset($_POST['groupe']) && $_POST['groupe']!='0' 		? $groupe 		= trim($_POST['groupe']) 		: $groupe = '');
		(isset($_POST['compte']) && $_POST['compte']!='' 		? $compte 		= trim($_POST['compte']) 		: $compte = '');
		(isset($_POST['motpasse1']) && $_POST['motpasse1']!='' 	? $motpasse 	= trim($_POST['motpasse1']) 	: $motpasse = '');
		(isset($_POST['statut']) && $_POST['statut']!='' 		? $statut 		= trim($_POST['statut']) 		: $statut = '');
		(isset($_POST['oldcompte']) && $_POST['oldcompte']!='' 	? $oldcompte 	= trim($_POST['oldcompte']) 	: $oldcompte = '');
		(isset($_POST['oldnummlle']) && $_POST['oldnummlle']!='' 	? $oldnummlle 	= trim($_POST['oldnummlle']) 	: $oldnummlle = '');

		(isset($motpasse) && $motpasse!='' ? $pwd = "`PWD`=MD5('".addslashes($motpasse)."') ," : $pwd = '');

		(isset($_POST['mag1']) && $_POST['mag1']!='0' 			? $mag1 	= trim($_POST['mag1']) 		: $mag1 = '');
		(isset($_POST['mag2']) && $_POST['mag2']!='0' 			? $mag2 	= trim($_POST['mag2']) 		: $mag2 = '');
		(isset($_POST['mag3']) && $_POST['mag3']!='0' 			? $mag3 	= trim($_POST['mag3']) 		: $mag3 = '');
		(isset($_POST['mag4']) && $_POST['mag4']!='0' 			? $mag4 	= trim($_POST['mag4']) 		: $mag4 = '');
		(isset($_POST['mag5']) && $_POST['mag5']!='0' 			? $mag5 	= trim($_POST['mag5']) 		: $mag5 = '');
		(isset($_POST['mag6']) && $_POST['mag6']!='0' 			? $mag6 	= trim($_POST['mag6']) 		: $mag6 = '');
		(isset($_POST['mag7']) && $_POST['mag7']!='0' 			? $mag7 	= trim($_POST['mag7']) 		: $mag7 = '');
		(isset($_POST['mag8']) && $_POST['mag8']!='0' 			? $mag8 	= trim($_POST['mag8']) 		: $mag8 = '');

		(isset($_POST['oldmag1']) && $_POST['oldmag1']!='' 		? $oldmag1 	= trim($_POST['oldmag1']) 		: $oldmag1 = '');
		(isset($_POST['oldmag2']) && $_POST['oldmag2']!='' 		? $oldmag2 	= trim($_POST['oldmag2']) 		: $oldmag2 = '');
		(isset($_POST['oldmag3']) && $_POST['oldmag3']!='' 		? $oldmag3 	= trim($_POST['oldmag3']) 		: $oldmag3 = '');
		(isset($_POST['oldmag4']) && $_POST['oldmag4']!='' 		? $oldmag4 	= trim($_POST['oldmag4']) 		: $oldmag4 = '');
		(isset($_POST['oldmag5']) && $_POST['oldmag5']!='' 		? $oldmag5 	= trim($_POST['oldmag5']) 		: $oldmag5 = '');
		(isset($_POST['oldmag6']) && $_POST['oldmag6']!='' 		? $oldmag6 	= trim($_POST['oldmag6']) 		: $oldmag6 = '');
		(isset($_POST['oldmag7']) && $_POST['oldmag7']!='' 		? $oldmag7 	= trim($_POST['oldmag7']) 		: $oldmag7 = '');
		(isset($_POST['oldmag8']) && $_POST['oldmag8']!='' 		? $oldmag8 	= trim($_POST['oldmag8']) 		: $oldmag8 = '');

		//($codebeneficiaire == '' ? $codebeneficiaire)


		//($codebeneficiaire == '' ? $codebeneficiaire)
		//SQL
		$sql  = "UPDATE `compte` SET `LOGIN`='".addslashes($compte)."' ,`NUM_MLLE`='".addslashes($personnel)."' ,`IDPROFIL`='".addslashes($groupe)."' ,
		 $pwd `ACTIVATED`='".addslashes($statut)."'  WHERE `LOGIN` LIKE '".addslashes($oldcompte)."';";

		//INSERT
		if($oldmag1=='' && $mag1!='') { $sql .= "INSERT INTO `mag_compte` (`LOGIN` ,`CODE_MAGASIN`) VALUES ('".addslashes($compte)."','".addslashes($mag1)."');" ;}
		if($oldmag2=='' && $mag2!='') { $sql .= "INSERT INTO `mag_compte` (`LOGIN` ,`CODE_MAGASIN`) VALUES ('".addslashes($compte)."','".addslashes($mag2)."');" ;}
		if($oldmag3=='' && $mag3!='') { $sql .= "INSERT INTO `mag_compte` (`LOGIN` ,`CODE_MAGASIN`) VALUES ('".addslashes($compte)."','".addslashes($mag3)."');" ;}
		if($oldmag4=='' && $mag4!='') { $sql .= "INSERT INTO `mag_compte` (`LOGIN` ,`CODE_MAGASIN`) VALUES ('".addslashes($compte)."','".addslashes($mag4)."');" ;}
		if($oldmag5=='' && $mag5!='') { $sql .= "INSERT INTO `mag_compte` (`LOGIN` ,`CODE_MAGASIN`) VALUES ('".addslashes($compte)."','".addslashes($mag5)."');" ;}
		if($oldmag6=='' && $mag6!='') { $sql .= "INSERT INTO `mag_compte` (`LOGIN` ,`CODE_MAGASIN`) VALUES ('".addslashes($compte)."','".addslashes($mag6)."');" ;}
		if($oldmag7=='' && $mag7!='') { $sql .= "INSERT INTO `mag_compte` (`LOGIN` ,`CODE_MAGASIN`) VALUES ('".addslashes($compte)."','".addslashes($mag7)."');" ;}
		if($oldmag8=='' && $mag8!='') { $sql .= "INSERT INTO `mag_compte` (`LOGIN` ,`CODE_MAGASIN`) VALUES ('".addslashes($compte)."','".addslashes($mag8)."');" ;}

		//UPDATE
		if($oldmag1!='' && $mag1!=''  && $mag1!=$oldmag1) { $sql .= "UPDATE `mag_compte` SET `LOGIN`='".addslashes($compte)."' ,`CODE_MAGASIN`='".addslashes($mag1)."' WHERE CODE_MAGASIN LIKE '".addslashes($oldmag1)."' AND `LOGIN` LIKE '".addslashes($oldcompte)."';" ;}
		if($oldmag2!='' && $mag2!=''  && $mag2!=$oldmag2) { $sql .= "UPDATE `mag_compte` SET `LOGIN`='".addslashes($compte)."' ,`CODE_MAGASIN`='".addslashes($mag2)."' WHERE CODE_MAGASIN LIKE '".addslashes($oldmag2)."' AND `LOGIN` LIKE '".addslashes($oldcompte)."';" ;}
		if($oldmag3!='' && $mag3!=''  && $mag3!=$oldmag3) { $sql .= "UPDATE `mag_compte` SET `LOGIN`='".addslashes($compte)."' ,`CODE_MAGASIN`='".addslashes($mag3)."' WHERE CODE_MAGASIN LIKE '".addslashes($oldmag3)."' AND `LOGIN` LIKE '".addslashes($oldcompte)."';" ;}
		if($oldmag4!='' && $mag1!=''  && $mag1!=$oldmag4) { $sql .= "UPDATE `mag_compte` SET `LOGIN`='".addslashes($compte)."' ,`CODE_MAGASIN`='".addslashes($mag4)."' WHERE CODE_MAGASIN LIKE '".addslashes($oldmag4)."' AND `LOGIN` LIKE '".addslashes($oldcompte)."';" ;}
		if($oldmag5!='' && $mag2!=''  && $mag2!=$oldmag5) { $sql .= "UPDATE `mag_compte` SET `LOGIN`='".addslashes($compte)."' ,`CODE_MAGASIN`='".addslashes($mag5)."' WHERE CODE_MAGASIN LIKE '".addslashes($oldmag5)."' AND `LOGIN` LIKE '".addslashes($oldcompte)."';" ;}
		if($oldmag6!='' && $mag3!=''  && $mag3!=$oldmag6) { $sql .= "UPDATE `mag_compte` SET `LOGIN`='".addslashes($compte)."' ,`CODE_MAGASIN`='".addslashes($mag6)."' WHERE CODE_MAGASIN LIKE '".addslashes($oldmag6)."' AND `LOGIN` LIKE '".addslashes($oldcompte)."';" ;}
		if($oldmag7!='' && $mag3!=''  && $mag3!=$oldmag7) { $sql .= "UPDATE `mag_compte` SET `LOGIN`='".addslashes($compte)."' ,`CODE_MAGASIN`='".addslashes($mag7)."' WHERE CODE_MAGASIN LIKE '".addslashes($oldmag7)."' AND `LOGIN` LIKE '".addslashes($oldcompte)."';" ;}
		if($oldmag8!='' && $mag3!=''  && $mag3!=$oldmag8) { $sql .= "UPDATE `mag_compte` SET `LOGIN`='".addslashes($compte)."' ,`CODE_MAGASIN`='".addslashes($mag8)."' WHERE CODE_MAGASIN LIKE '".addslashes($oldmag8)."' AND `LOGIN` LIKE '".addslashes($oldcompte)."';" ;}

		//SUPPRESSION
		if($oldmag1!='' && $mag1=='') { $sql .= "DELETE FROM `mag_compte`  WHERE CODE_MAGASIN LIKE '".addslashes($oldmag1)."' AND `LOGIN` LIKE '".addslashes($oldcompte)."';" ;}
		if($oldmag2!='' && $mag2=='') { $sql .= "DELETE FROM `mag_compte`  WHERE CODE_MAGASIN LIKE '".addslashes($oldmag2)."' AND `LOGIN` LIKE '".addslashes($oldcompte)."';" ;}
		if($oldmag3!='' && $mag3=='') { $sql .= "DELETE FROM `mag_compte`  WHERE CODE_MAGASIN LIKE '".addslashes($oldmag3)."' AND `LOGIN` LIKE '".addslashes($oldcompte)."';" ;}
		if($oldmag4!='' && $mag4=='') { $sql .= "DELETE FROM `mag_compte`  WHERE CODE_MAGASIN LIKE '".addslashes($oldmag4)."' AND `LOGIN` LIKE '".addslashes($oldcompte)."';" ;}
		if($oldmag5!='' && $mag5=='') { $sql .= "DELETE FROM `mag_compte`  WHERE CODE_MAGASIN LIKE '".addslashes($oldmag5)."' AND `LOGIN` LIKE '".addslashes($oldcompte)."';" ;}
		if($oldmag6!='' && $mag6=='') { $sql .= "DELETE FROM `mag_compte`  WHERE CODE_MAGASIN LIKE '".addslashes($oldmag6)."' AND `LOGIN` LIKE '".addslashes($oldcompte)."';" ;}
		if($oldmag7!='' && $mag7=='') { $sql .= "DELETE FROM `mag_compte`  WHERE CODE_MAGASIN LIKE '".addslashes($oldmag7)."' AND `LOGIN` LIKE '".addslashes($oldcompte)."';" ;}
		if($oldmag8!='' && $mag8=='') { $sql .= "DELETE FROM `mag_compte`  WHERE CODE_MAGASIN LIKE '".addslashes($oldmag8)."' AND `LOGIN` LIKE '".addslashes($oldcompte)."';" ;}

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			die($error->getMessage().' '.__LINE__);
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'], $_SESSION['GL_USER']['MLLE'], 'Modification d\'un compte utilisateur ('.$oldcompte.', '.$personnel.')'); //updateLog($username, $idcust, $action='' )
		header('location:user.php?selectedTab=par&rs=2');
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
			die($error->getMessage().' '.__LINE__);
		}
		$sql = "SELECT * FROM  `mag_compte` WHERE `LOGIN` LIKE '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		$_SESSION['DATA_AFF']['LOGIN'] = $split[0];
		$_SESSION['DATA_AFF']['NUM_MLLE'] = getField('LOGIN', $split[0], 'NUM_MLLE', 'compte');
		$_SESSION['DATA_AFF']['MAGASIN'] = array();

		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$id = $row['CODE_MAGASIN'];
			$_SESSION['DATA_AFF']['MAGASIN']["$id"] = $row;
		}
		header('location:editaffectation.php?selectedTab=par&rs=2');
		break;

	case 'delete':
		(isset($_POST['rowSelection']) ? $id = $_POST['rowSelection'] : $id =array());
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			die($error->getMessage().' '.__LINE__);
		}

		foreach($id as $key => $val){
			$split = preg_split('/@/',$val);
			$sql = "DELETE FROM  `mag_compte` WHERE `LOGIN` LIKE '".addslashes($split[0])."'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
		}
		header('location:affectation.php?selectedTab=par&rs=4');
		break;

	default : ///Nothing
		//header('location:../index.php');

}
if($myaction =='' && $do =='') header('location:../index.php');

?>
