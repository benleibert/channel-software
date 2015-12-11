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
	//Log in User
	case 'add':
		(isset($_POST['codeproduit']) && $_POST['codeproduit']!=''  ? $codeproduit	= trim($_POST['codeproduit']) 	: $codeproduit = '');
		(isset($_POST['souscategorie']) && $_POST['souscategorie']!='0' ? $souscategorie 	= trim($_POST['souscategorie']) 	: $souscategorie = '');
		(isset($_POST['produit']) && $_POST['produit']!='' 			? $produit 		= trim($_POST['produit']) 		: $produit = '');
		(isset($_POST['description']) && $_POST['description']!='' 	? $description 	= trim($_POST['description']) 	: $description = '');
		(isset($_POST['unite']) && $_POST['unite']!='' 				? $unite 		= trim($_POST['unite']) 		: $unite = '');
		(isset($_POST['prix']) && $_POST['prix']!='' 				? $prix 		= trim($_POST['prix']) 			: $prix = '');
		(isset($_POST['sousproduit']) && $_POST['sousproduit']!='' 	? $sousproduit 	= trim($_POST['sousproduit']) 	: $sousproduit = '');
		(isset($_POST['nbre']) && $_POST['nbre']!='' 				? $nbre 		= trim($_POST['nbre']) 			: $nbre = '');
		(isset($_POST['conditionne']) && $_POST['conditionne']==1 	? $conditionne 	= trim($_POST['conditionne']) 	: $conditionne = 0);
		(isset($_POST['quantite']) && $_POST['quantite']!='' 		? $quantite 	= trim($_POST['quantite']) 		: $quantite = '');
		(isset($_POST['prixachat']) && $_POST['prixachat']!='' 		? $prixachat 	= trim($_POST['prixachat']) 	: $prixachat = '');
		(isset($_POST['prixrevient']) && $_POST['prixrevient']!='' 	? $prixrevient 	= trim($_POST['prixrevient']) 	: $prixrevient = '');
		(isset($_POST['prixvente']) && $_POST['prixvente']!='' 		? $prixvente 	= trim($_POST['prixvente']) 	: $prixvente = '');
		(isset($_POST['seuilmin']) && $_POST['seuilmin']!='' 		? $seuilmin 	= trim($_POST['seuilmin']) 		: $seuilmin = '');
		(isset($_POST['seuilmax']) && $_POST['seuilmax']!='' 		? $seuilmax 	= trim($_POST['seuilmax']) 		: $seuilmax = '');
		(isset($_POST['prixachatn2']) && $_POST['prixachatn2']!='' 		? $prixachatn2 	= trim($_POST['prixachatn2']) 	: $prixachatn2 = '');
		(isset($_POST['prixrevientn2']) && $_POST['prixrevientn2']!='' 	? $prixrevientn2 	= trim($_POST['prixrevientn2']) 	: $prixrevientn2 = '');
		(isset($_POST['prixventen2']) && $_POST['prixventen2']!='' 		? $prixventen2 	= trim($_POST['prixventen2']) 	: $prixventen2 = '');
		(isset($_POST['seuilminn2']) && $_POST['seuilminn2']!='' 		? $seuilminn2 	= trim($_POST['seuilminn2']) 		: $seuilminn2 ='');
		(isset($_POST['seuilmaxn2']) && $_POST['seuilmaxn2']!='' 		? $seuilmaxn2 	= trim($_POST['seuilmaxn2']) 		: $seuilmaxn2 = '');

		if($prixachat!='' && $prixachat!=NULL)  $prixachat = preg_replace('/,/','.' , $prixachat);
		if($prixrevient!='' && $prixrevient!=NULL)  $prixrevient = preg_replace('/,/','.' , $prixrevient);
		if($prixvente!='' && $prixvente!=NULL)  $prixvente = preg_replace('/,/','.' , $prixvente);

		if($prixachatn2!='' && $prixachatn2!=NULL)  $prixachatn2 = preg_replace('/,/','.' , $prixachatn2);
		if($prixrevientn2!='' && $prixrevientn2!=NULL)  $prixrevientn2 = preg_replace('/,/','.' , $prixrevientn2);
		if($prixventen2!='' && $prixventen2!=NULL)  $prixventen2 = preg_replace('/,/','.' , $prixventen2);

		(isset($_POST['sousgroupe']) && $_POST['sousgroupe']!='0' 	? $sousgroupe 	= trim($_POST['sousgroupe']) 	: $sousgroupe = '');
		(isset($_POST['traceur']) && $_POST['traceur']!='' 			? $traceur 		= trim($_POST['traceur']) 		: $traceur = '');

		(isset($sousgroupe) && $sousgroupe =='' ? $ssg='NULL' : $ssg="'".addslashes($sousgroupe)."'");
		(isset($traceur) && $traceur =='' ? $trc='NULL' : $trc="'".addslashes($traceur)."'");
		(isset($prix) && $prix =='' ? $tprix='NULL' : $tprix="'".addslashes($prix)."'");

		(isset($nbre) && $nbre =='' ? $tnbre='NULL' : $tnbre="'".addslashes($nbre)."'");
		(isset($quantite) && $quantite =='' ? $tquantite ='NULL' : $tquantite="'".addslashes($quantite)."'");

		(isset($prixachat) && $prixachat =='' ? $tprixachat='NULL' : $tprixachat="'".addslashes($prixachat)."'");
		(isset($prixrevient) && $prixrevient =='' ? $tprixrevient ='NULL' : $tprixrevient="'".addslashes($prixrevient)."'");
		(isset($prixvente) && $prixvente =='' ? $tprixvente='NULL' : $tprixvente="'".addslashes($prixvente)."'");

		(isset($seuilmin) && $seuilmin =='' ? $tseuilmin='NULL' : $tseuilmin="'".addslashes($seuilmin)."'");
		(isset($seuilmax) && $seuilmax =='' ? $tseuilmax='NULL' : $tseuilmax="'".addslashes($seuilmax)."'");

		(isset($prixachatn2) && $prixachatn2 =='' ? $tprixachatn2='NULL' : $tprixachatn2="'".addslashes($prixachatn2)."'");
		(isset($prixrevientn2) && $prixrevientn2 =='' ? $tprixrevientn2='NULL' : $tprixrevientn2="'".addslashes($prixrevientn2)."'");
		(isset($prixventen2) && $prixventen2 =='' ? $tprixventen2='NULL' : $tprixventen2="'".addslashes($prixventen2)."'");

		(isset($seuilminn2) && $seuilminn2 =='' ? $tseuilminn2='NULL' : $tseuilminn2="'".addslashes($seuilminn2)."'");
		(isset($seuilmaxn2) && $seuilmaxn2 =='' ? $tseuilmaxn2='NULL' : $tseuilmaxn2="'".addslashes($seuilmaxn2)."'");


		//SQL
	 	$sql  = "INSERT INTO `produit` (`CODE_PRODUIT` , `ID_UNITE` ,`CODE_SOUSCATEGORIE` ,`PRD_LIBELLE`,`PRD_DESCRIP`,
		PRD_PRIXACHAT, PRD_PRIXREVIENT, PRD_PRIXVENTE, PRD_SEUILMIN, PRD_SEUILMAX, PRD_TRACEUR, CODESOUSGROUP, `PRD_PRIXACHATN2`,`PRD_PRIXREVIENTN2`,
		`PRD_PRIXVENTEN2`,`PRD_SEUILMINN2`,  `PRD_SEUILMAXN2`) 	VALUES ('".addslashes($codeproduit)."','".addslashes($unite)."',
		'".addslashes($souscategorie)."', '".addslashes($produit)."' , '".addslashes($description)."' ,$tprixachat, $tprixrevient, $tprixvente ,
		$tseuilmin , $tseuilmax, $trc, $ssg,  $tprixachatn2 ,$tprixrevientn2, $tprixventen2 ,$tseuilminn2, $tseuilmaxn2);";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout d\'un produit ('.$codeproduit.', '.$produit.')'); //updateLog($username, $idcust, $action='' )
		header('location:produit.php?selectedTab=par&rs=1');
		break;

	case 'update':
		(isset($_POST['codeproduit']) && $_POST['codeproduit']!=''  ? $codeproduit	 = trim($_POST['codeproduit']) 	: $codeproduit = '');
		(isset($_POST['oldcodeproduit']) && $_POST['oldcodeproduit']!=''  ? $oldcodeproduit	 = trim($_POST['oldcodeproduit']) 	: $oldcodeproduit = '');
		(isset($_POST['souscategorie']) && $_POST['souscategorie']!='0' ? $souscategorie 	= trim($_POST['souscategorie']) 	: $souscategorie = '');
		(isset($_POST['produit']) && $_POST['produit']!='' 			? $produit 		= trim($_POST['produit']) 		: $produit = '');
		(isset($_POST['description']) && $_POST['description']!='' 	? $description 	= trim($_POST['description']) 	: $description = '');
		(isset($_POST['unite']) && $_POST['unite']!='' 				? $unite 		= trim($_POST['unite']) 		: $unite = '');
		(isset($_POST['prix']) && $_POST['prix']!='' 				? $prix 		= trim($_POST['prix']) 			: $prix = '');
		(isset($_POST['sousproduit']) && $_POST['sousproduit']!='' 	? $sousproduit 	= trim($_POST['sousproduit']) 	: $prix = '');
		(isset($_POST['nbre']) && $_POST['nbre']!='' 				? $nbre 		= trim($_POST['nbre']) 			: $nbre = '');
		(isset($_POST['conditionne']) && $_POST['conditionne']==1 	? $conditionne 	= trim($_POST['conditionne']) 	: $conditionne = 0);
		(isset($_POST['quantite']) && $_POST['quantite']!='' 		? $quantite 	= trim($_POST['quantite']) 		: $quantite = '');
		(isset($_POST['prixachat']) && $_POST['prixachat']!='' 		? $prixachat 	= trim($_POST['prixachat']) 	: $prixachat = 'NULL');
		(isset($_POST['prixrevient']) && $_POST['prixrevient']!='' 	? $prixrevient 	= trim($_POST['prixrevient']) 	: $prixrevient = 'NULL');
		(isset($_POST['prixvente']) && $_POST['prixvente']!='' 		? $prixvente 	= trim($_POST['prixvente']) 	: $prixvente = 'NULL');
		(isset($_POST['seuilmin']) && $_POST['seuilmin']!='' 		? $seuilmin 	= trim($_POST['seuilmin']) 		: $seuilmin = 'NULL');
		(isset($_POST['seuilmax']) && $_POST['seuilmax']!='' 		? $seuilmax 	= trim($_POST['seuilmax']) 		: $seuilmax = 'NULL');
		(isset($_POST['sousgroupe']) && $_POST['sousgroupe']!='0' 	? $sousgroupe 	= trim($_POST['sousgroupe']) 	: $sousgroupe = '');
		(isset($_POST['traceur']) && $_POST['traceur']!='' 			? $traceur 		= trim($_POST['traceur']) 		: $traceur = '');
		(isset($_POST['prixachatn2']) && $_POST['prixachatn2']!='' 		? $prixachatn2 	= trim($_POST['prixachatn2']) 	: $prixachatn2 = 'NULL');
		(isset($_POST['prixrevientn2']) && $_POST['prixrevientn2']!='' 	? $prixrevientn2 	= trim($_POST['prixrevientn2']) 	: $prixrevientn2 = 'NULL');
		(isset($_POST['prixventen2']) && $_POST['prixventen2']!='' 		? $prixventen2 	= trim($_POST['prixventen2']) 	: $prixventen2 = 'NULL');
		(isset($_POST['seuilminn2']) && $_POST['seuilminn2']!='' 		? $seuilminn2 	= trim($_POST['seuilminn2']) 		: $seuilminn2 = 'NULL');
		(isset($_POST['seuilmaxn2']) && $_POST['seuilmaxn2']!='' 		? $seuilmaxn2 	= trim($_POST['seuilmaxn2']) 		: $seuilmaxn2 = 'NULL');

		if($prixachat!='' && $prixachat!=NULL)  $prixachat = preg_replace('/,/','.' , $prixachat);
		if($prixrevient!='' && $prixrevient!=NULL)  $prixrevient = preg_replace('/,/','.' , $prixrevient);
		if($prixvente!='' && $prixvente!=NULL)  $prixvente = preg_replace('/,/','.' , $prixvente);

		if($prixachatn2!='' && $prixachatn2!=NULL)  $prixachatn2 = preg_replace('/,/','.' , $prixachatn2);
		if($prixrevientn2!='' && $prixrevientn2!=NULL)  $prixrevientn2 = preg_replace('/,/','.' , $prixrevientn2);
		if($prixventen2!='' && $prixventen2!=NULL)  $prixventen2 = preg_replace('/,/','.' , $prixventen2);

		(isset($sousgroupe) && $sousgroupe =='' ? $ssg='PRD_TRACEUR=NULL' : $ssg="CODESOUSGROUP='".addslashes($sousgroupe)."'");
		(isset($traceur) && $traceur =='' ? $trc='PRD_TRACEUR=NULL' : $trc="PRD_TRACEUR='".addslashes($traceur)."'");

		(isset($nbre) && $nbre =='' ? $tnbre='NULL' : $tnbre="'".addslashes($nbre)."'");
		(isset($quantite) && $quantite =='' ? $tquantite ='NULL' : $tquantite="'".addslashes($quantite)."'");

		(isset($prixachat) && $prixachat =='' ? $tprixachat='NULL' : $tprixachat="'".addslashes($prixachat)."'");
		(isset($prixrevient) && $prixrevient =='' ? $tprixrevient ='NULL' : $tprixrevient="'".addslashes($prixrevient)."'");
		(isset($prixvente) && $prixvente =='' ? $tprixvente='NULL' : $tprixvente="'".addslashes($prixvente)."'");

		(isset($seuilmin) && $seuilmin =='' ? $tseuilmin='NULL' : $tseuilmin="'".addslashes($seuilmin)."'");
		(isset($seuilmax) && $seuilmax =='' ? $tseuilmax='NULL' : $tseuilmax="'".addslashes($seuilmax)."'");

		(isset($prixachatn2) && $prixachatn2 =='' ? $tprixachatn2='NULL' : $tprixachatn2="'".addslashes($prixachatn2)."'");
		(isset($prixrevientn2) && $prixrevientn2 =='' ? $tprixrevientn2='NULL' : $tprixrevientn2="'".addslashes($prixrevientn2)."'");
		(isset($prixventen2) && $prixventen2 =='' ? $tprixventen2='NULL' : $tprixventen2="'".addslashes($prixventen2)."'");

		(isset($seuilminn2) && $seuilminn2 =='' ? $tseuilminn2='NULL' : $tseuilminn2="'".addslashes($seuilminn2)."'");
		(isset($seuilmaxn2) && $seuilmaxn2 =='' ? $tseuilmaxn2='NULL' : $tseuilmaxn2="'".addslashes($seuilmaxn2)."'");


		//SQL
		 $sql  = "UPDATE `produit` SET `CODE_PRODUIT`= '".addslashes($codeproduit)."', `ID_UNITE`='".addslashes($unite)."' ,`CODE_SOUSCATEGORIE` ='".addslashes($souscategorie)."',
		`PRD_LIBELLE`='".addslashes($produit)."' ,`PRD_CONDITIONNE`='".addslashes($conditionne)."',	`PRD_DESCRIP`='".addslashes($description)."', `PRD_PRIXACHAT`=$tprixachat,
		`PRD_PRIXREVIENT`=$tprixrevient, `PRD_PRIXVENTE`=$tprixvente, `PRD_SEUILMIN`=$tseuilmin, `PRD_SEUILMAX`=$tseuilmax,
		`PRD_PRIXACHATN2`=$tprixachatn2,`PRD_PRIXREVIENTN2`=$tprixrevientn2,`PRD_PRIXVENTEN2`=$tprixventen2,`PRD_SEUILMINN2`=$tseuilminn2,
		`PRD_SEUILMAXN2`=$tseuilmaxn2, $trc,  $ssg WHERE CODE_PRODUIT LIKE '".addslashes($oldcodeproduit)."';";


	try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Modification d\'un produit ('.$codeproduit.', '.$produit.')'); //updateLog($username, $idcust, $action='' )
		header('location:produit.php?selectedTab=par&rs=2');
		break;

	case 'updateprix':

		(isset($_POST['rowSelection']) ? $id = $_POST['rowSelection'] : $id =array());
		$sql = "";
		foreach($id as $key => $val){
			(isset($_POST['prixachat'.$key]) && $_POST['prixachat'.$key]!='' 		? $prixachat 	= trim($_POST['prixachat'.$key]) 	: $prixachat = 'NULL');
			(isset($_POST['prixrevient'.$key]) && $_POST['prixrevient'.$key]!='' 	? $prixrevient 	= trim($_POST['prixrevient'.$key]) 	: $prixrevient = 'NULL');
			(isset($_POST['prixvente'.$key]) && $_POST['prixvente'.$key]!='' 		? $prixvente 	= trim($_POST['prixvente'.$key]) 	: $prixvente = 'NULL');

			(isset($_POST['prixachatn2'.$key]) && $_POST['prixachatn2'.$key]!='' 		? $prixachatn2 		= trim($_POST['prixachatn2'.$key]) 		: $prixachatn2	 = 'NULL');
			(isset($_POST['prixrevientn2'.$key]) && $_POST['prixrevientn2'.$key]!='' 	? $prixrevientn2 	= trim($_POST['prixrevientn2'.$key]) 	: $prixrevientn2 = 'NULL');
			(isset($_POST['prixventen2'.$key]) && $_POST['prixventen2'.$key]!='' 		? $prixventen2 		= trim($_POST['prixventen2'.$key]) 		: $prixventen2	 = 'NULL');

			if($prixachat!='')  $prixachat = preg_replace('/,/','.' , $prixachat);
			if($prixrevient!='')  $prixrevient = preg_replace('/,/','.' , $prixrevient);
			if($prixvente!='')  $prixvente = preg_replace('/,/','.' , $prixvente);

			if($prixachatn2!='')  $prixachatn2 = preg_replace('/,/','.' , $prixachatn2);
			if($prixrevientn2!='')  $prixrevientn2 = preg_replace('/,/','.' , $prixrevientn2);
			if($prixventen2!='')  $prixventen2 = preg_replace('/,/','.' , $prixventen2);

			//SQL
			$sql  .= "UPDATE `produit` SET  `PRD_PRIXACHAT`=$prixachat,`PRD_PRIXREVIENT`=$prixrevient, `PRD_PRIXVENTE`=$prixvente,
			`PRD_PRIXACHATN2`=$prixachatn2,`PRD_PRIXREVIENTN2`=$prixrevientn2, `PRD_PRIXVENTEN2`=$prixventen2
			WHERE CODE_PRODUIT LIKE '".addslashes($val)."';";
		}

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Mise à jour des prix '); //updateLog($username, $idcust, $action='' )
		header('location:produit.php?selectedTab=par&rs=2');
		break;

	case 'check':

		$msg = "";
		(isset($_POST['codeproduit']) && $_POST['codeproduit']!='' 		? $codeproduit = trim($_POST['codeproduit']) 	: $codeproduit = '');

		if($codeproduit !=''){
			$sql = "SELECT COUNT(CODE_PRODUIT) AS NBRE FROM  `produit` WHERE `CODE_PRODUIT` LIKE '".addslashes($codeproduit)."'";
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

			if($row['NBRE']>0) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Ce code existe d&eacute;j&agrave;, veuillez entrer un autre code produit.';}
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
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$sql = "SELECT * FROM  `produit` WHERE `CODE_PRODUIT` LIKE '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);
		$_SESSION['DATA_PRD'] = $row;
		header('location:editproduit.php?selectedTab=par&rs=2');
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
			$sql = "DELETE FROM  `produit` WHERE `CODE_PRODUIT` LIKE '".addslashes($split[0])."'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
		}
		header('location:produit.php?selectedTab=par&rs=4');
		break;

	default : ///Nothing
		//header('location:../index.php');

}

if($myaction =='' && $do =='') header('location:../index.php');
?>
