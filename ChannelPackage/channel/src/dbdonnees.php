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

//Action to do , This variable $do and $myaction say what to do (add, delete, ...)
(isset($_GET['do']) && $_GET['do']!='' ? $do = $_GET['do'] : $do = '');
(isset($_POST['myaction']) && $_POST['myaction']!='' ? $myaction = $_POST['myaction'] : $myaction = '');

if($myaction =='' && $do !=''){
switch($do){
	case 'save':
		(isset($_POST['etab']) && $_POST['etab']!=''  			? $etab = trim($_POST['etab']) 		: $etab = '');
		(isset($_POST['effectif']) && $_POST['effectif']!=''	? $effectif = trim($_POST['effectif']) 	: $effectif = '');
		(isset($_POST['rationnel']) && $_POST['rationnel']!=''  ? $rationnel = trim($_POST['rationnel']) 	: $rationnel = '');
		$magasin=$_SESSION['GL_USER']['MAGASIN'];

		if(isUseNow('ID_BENEF', 'donnee_annuelle', "WHERE ID_BENEF=$etab AND CODE_MAGASIN LIKE '$magasin' AND ID_EXERCICE=".$_SESSION['GL_USER']['EXERCICE'])>0){
			//SQL
			$sql  = "UPDATE `donnee_annuelle` SET `ID_BENEF`='".addslashes($etab)."' ,`CODE_MAGASIN`='".addslashes($_SESSION['GL_USER']['MAGASIN'])."',
			`ID_EXERCICE`='".addslashes($_SESSION['GL_USER']['EXERCICE'])."' ,`EFFECTIF`='".addslashes($effectif)."' ,`MOY_RATIONNEL`='".addslashes($rationnel)."' ,
			`DATECREAT`='".date('Y-m-d H:i:s')."' WHERE ID_BENEF=$etab AND CODE_MAGASIN LIKE '$magasin' AND ID_EXERCICE=".$_SESSION['GL_USER']['EXERCICE'];
		}
		else{
			//SQL
			$sql  = "INSERT INTO `donnee_annuelle` (`ID_BENEF` ,`CODE_MAGASIN` ,`ID_EXERCICE` ,`EFFECTIF` ,`MOY_RATIONNEL` ,`DATECREAT`) VALUES (
			'".addslashes($etab)."','".addslashes($_SESSION['GL_USER']['MAGASIN'])."' ,'".addslashes($_SESSION['GL_USER']['EXERCICE'])."' ,'".addslashes($effectif)."' ,
			'".addslashes($rationnel)."', '".date('Y-m-d H:i:s')."');";
		}

		//Mise à jour du tableau
		$_SESSION['DATA_DON'][$etab]=array('effectif'=>$effectif, 'rationnel'=>$rationnel);

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout de données annuelles ('.$etab.')');
		echo 1;
		break;	//SERVICE

	case 'update':
		(isset($_POST['magasin']) && $_POST['magasin']!='0'  	? $magasin = trim($_POST['magasin']) 		: $magasin = '');
		(isset($_POST['beneficiaire']) && $_POST['beneficiaire']!=''	? $beneficiaire = trim($_POST['beneficiaire']) 	: $beneficiaire = '');
		(isset($_POST['datedebut']) && $_POST['datedebut']!=''  ? $datedebut = trim($_POST['datedebut']) 	: $datedebut = '');
		(isset($_POST['datefin']) && $_POST['datefin']!=''  	? $datefin = trim($_POST['datefin']) 		: $datefin = '');
		$datedebut = mysqlFormat($datedebut);
		$datefin = mysqlFormat($datefin);
		(isset($_POST['id']) && $_POST['id']!=''  			? $id = trim($_POST['id']) 				: $id = '');

		//SQL
	 	$sql  = "UPDATE `benefmag` SET `ID_BENEF`='".addslashes($beneficiaire)."' ,`CODE_MAGASIN`='".addslashes($magasin)."' ,`BM_DATEDEBUT`='".addslashes($datedebut)."' ,`BM_DATEFIN`='".addslashes($datefin)."'  WHERE ID_BENMAG=$id";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Modification d\'une affectation ('.$beneficiaire.', '.$magasin.')'); //updateLog($username, $idcust, $action='' )
		header('location:affectation.php?selectedTab=par&rs=2');
		break;

	case 'detail':
		(isset($_GET['xid']) ? $id = $_GET['xid'] : $id ='');

		$_SESSION['DATA_DON']= DataDonneesAnnuelles("ID_EXERCICE=$id", $_SESSION['GL_USER']['MAGASIN']);

//		$sql = "SELECT * FROM  `donnee_annuelle` INNER JOIN beneficiaire ON (beneficiaire.ID_BENEF=donnee_annuelle.ID_BENEF)
//			WHERE CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND ID_EXERCICE=".$_SESSION['GL_USER']['EXERCICE']."
//			ORDER BY BENEF_NOM ASC;";
//
//		try {
//			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
//		}
//		catch (PDOException $error) { //Treat error
//			//("Erreur de connexion : " . $error->getMessage() );
//			header('location:errorPage.php');
//		}
//		$query =  $cnx->prepare($sql); //Prepare the SQL
//		$query->execute(); //Execute prepared SQL => $query
//
//		$_SESSION['DATA_DON'] =array();
//		while ($row = $query->fetch(PDO::FETCH_ASSOC)){
//			$etab = $row['ID_BENEF'];
//			$_SESSION['DATA_DON'][$etab]= array('effectif'=>$row['EFFECTIF'], 'rationnel'=>$row['MOY_RATIONNEL']);
//		}
		header('location:detaildonnees.php?selectedTab=par&rst=1');
		break;

	case 'search':
		$where ="";
		(isset($_POST['exercice']) && $_POST['exercice']!='' 	? 	$where .="donnee_annuelle.ID_EXERCICE =".addslashes(trim($_POST['exercice']))."  AND " 	: $where .="");

		(isset($_POST['magasin']) && $_POST['magasin']!='' 	? 	$where .="donnee_annuelle.CODE_MAGASIN LIKE '".addslashes(trim($_POST['magasin']))."'  AND " 	: $where .="");

	if($where != '')  {$where = substr($where,0, strlen($where)-4);
		$_SESSION['WHERE'] = $where;
	}
	elseif($_SESSION['WHERE'] !='') {$where = $_SESSION['WHERE'];}
	$link ='detaildonnees.php?selectedTab=par&do=search';
	$retour = ligneDetailDonnees($where,'','', $page, $_SESSION['GL_USER']['ELEMENT']); //$where, $order, $sens, $page=1, $nelt
//}
//else {
//	$link ='detaildonnees.php?selectedTab=par';
//	$retour = ligneConDonnees('','','', $page, $_SESSION['GL_USER']['ELEMENT']); //$where, $order, $sens, $page=1, $nelt
//}
//		$_SESSION['DATA_DON']= DataDonneesAnnuelles(, $_SESSION['GL_USER']['MAGASIN']);

		//		$sql = "SELECT * FROM  `donnee_annuelle` INNER JOIN beneficiaire ON (beneficiaire.ID_BENEF=donnee_annuelle.ID_BENEF)
		//			WHERE CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND ID_EXERCICE=".$_SESSION['GL_USER']['EXERCICE']."
		//			ORDER BY BENEF_NOM ASC;";
		//
		//		try {
		//			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		//		}
		//		catch (PDOException $error) { //Treat error
		//			//("Erreur de connexion : " . $error->getMessage() );
		//			header('location:errorPage.php');
		//		}
		//		$query =  $cnx->prepare($sql); //Prepare the SQL
		//		$query->execute(); //Execute prepared SQL => $query
		//
		//		$_SESSION['DATA_DON'] =array();
		//		while ($row = $query->fetch(PDO::FETCH_ASSOC)){
		//			$etab = $row['ID_BENEF'];
		//			$_SESSION['DATA_DON'][$etab]= array('effectif'=>$row['EFFECTIF'], 'rationnel'=>$row['MOY_RATIONNEL']);
		//		}
		header('location:detaildonnees.php?selectedTab=par&rst=1');
		break;


	default : ///Nothing
		//header('location:../index.php');

}
}
elseif($myaction !='')	//myaction

	switch($myaction){

		case 'generer':

			$sql = "SELECT * FROM  `donnee_annuelle` INNER JOIN beneficiaire ON (beneficiaire.ID_BENEF=donnee_annuelle.ID_BENEF)
			WHERE CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND ID_EXERCICE=".$_SESSION['GL_USER']['EXERCICE']."
			ORDER BY BENEF_NOM ASC;";

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}
				$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			$_SESSION['DATA_DON'] =array();
			while ($row = $query->fetch(PDO::FETCH_ASSOC)){
				$etab = $row['ID_BENEF'];
				$_SESSION['DATA_DON'][$etab]= array('effectif'=>$row['EFFECTIF'], 'rationnel'=>$row['MOY_RATIONNEL']);
			}
			header('location:adddonnees.php?selectedTab=par&rst=1');
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
			$sql = "DELETE FROM  `donnee_annuelle` WHERE `ID_EXERCICE` = '".addslashes($split[0])."' AND CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
		}
		header('location:affectation.php?selectedTab=par&rs=4');
		break;

	default : ///Nothing
		//header('location:../index.php');

	}
elseif($myaction =='' && $do ='') header('location:../index.php');
?>