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
	//Commandes
	case 'next':
		(isset($_POST['exercice']) && $_POST['exercice']!=''			? $exercice 	= trim($_POST['exercice']) 		: $exercice 	= '');
		(isset($_POST['datecommande']) && $_POST['datecommande']!=''  	? $datecommande = trim($_POST['datecommande']) 	: $datecommande = '');
		(isset($_POST['refcommande']) && $_POST['refcommande']!=''  	? $refcommande = trim($_POST['refcommande'])	: $refcommande = '');
		(isset($_POST['libellecde']) && $_POST['libellecde']!=''  		? $libellecde 	= trim($_POST['libellecde']) 	: $libellecde 	= '');
		(isset($_POST['fournisseur']) && $_POST['fournisseur']!='0'  	? $fournisseur 	= trim($_POST['fournisseur']) 	: $fournisseur	= '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 	= trim($_POST['nbreLigne']) 	: $nbreLigne	= '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 		= trim($_POST['statut']) 		: $statut 		= '0');

		//Data
		$_SESSION['DATA_CDE']=array(
		'exercice'=>$exercice,
		'datecommande'=>$datecommande,
		'refcommande'=>$refcommande,
		'libellecde'=>$libellecde,
		'fournisseur'=> $fournisseur,
		'statut'=> $statut,
		'nbreLigne'=>$nbreLigne
		);
		//Etape 2
		header('location:addcommande1.php?selectedTab=bde');
		break;

	//Ajout COMMANDES

	case 'add':
		(isset($_POST['exercice']) && $_POST['exercice']!=''			? $exercice 	= trim($_POST['exercice']) 		: $exercice 	= '');
		(isset($_POST['datecommande']) && $_POST['datecommande']!='' 	? $datecommande = trim($_POST['datecommande']) 	: $datecommande = '');
		(isset($_POST['refcommande']) && $_POST['refcommande']!=''  	? $refcommande	= trim($_POST['refcommande']) 	: $refcommande 	= '');
		(isset($_POST['libellecde']) && $_POST['libellecde']!=''  		? $libellecde 	= trim($_POST['libellecde']) 	: $libellecde 	= '');
		(isset($_POST['fournisseur']) && $_POST['fournisseur']!='0'  	? $fournisseur 	= trim($_POST['fournisseur']) 	: $fournisseur 	= '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 	= trim($_POST['nbreLigne']) 	: $nbreLigne 	= '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 		= trim($_POST['statut']) 		: $statut 		= '0');
		$datecommande = mysqlFormat($datecommande);
		$magasin=$_SESSION['GL_USER']['MAGASIN'];
		$exercice =  $_SESSION['GL_USER']['EXERCICE'];
		//$statut = 1;

		$numauto = myDbLastId('commande', 'ID_COMMANDE', $magasin)+1;  //Dernier ID Cde
		$codeCde = "$numauto/$magasin";

		//Data
		$_SESSION['DATA_CDE']=array(
		'exercice'=>$exercice,
		'datecommande'=>$datecommande,
		'refcommande'=>$refcommande,
		'libellecde'=>$libellecde,
		'fournisseur'=> $fournisseur,
		'statut'=> $statut,
		'nbreLigne'=>$nbreLigne
		);

		//Insert
		$sql  = "INSERT INTO `commande` (CODE_COMMANDE, ID_COMMANDE, `ID_EXERCICE` ,`CODE_FOUR` ,`REF_COMMANDE` ,`CDE_LIBELLE` ,`CDE_DATE` ,`CDE_STATUT`, `CODE_MAGASIN`)
		VALUES ('".addslashes($codeCde)."','".addslashes($numauto)."','".addslashes($exercice)."', '".addslashes($fournisseur)."',
		'".addslashes($refcommande)."' , '".addslashes($libellecde)."' , '".addslashes($datecommande)."' , '$statut','".addslashes($magasin)."')";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Ajout d'une commande ($codeCde, $libellecde)"); //updateLog($username, $idcust, $action='' )

		$sql1 = '';
		//Collect Data
		$_SESSION['DATA_CDE']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_CDE']['nbreLigne']; $i++){
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 		= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 			= '');
			(isset($_POST['unite'.$i]) 		? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');
			(isset($_POST['prix'.$i]) 		? $prix 		= $_POST['prix'.$i] 		: $prix 		= '');

			if($codeproduit!='' && $produit!='' && $qte!='') {
				$sql1 .="INSERT INTO `prd_cde` (`CODE_COMMANDE` ,`CODE_PRODUIT` ,`CODE_MAGASIN` ,`CDEPRD_QTE` ,`CDEPRD_UNITE`,`CDEPRD_PA`)
				VALUES ( '".addslashes($codeCde)."', '".addslashes($codeproduit)."','".addslashes($magasin)."', '".addslashes($qte)."' ,  '".addslashes($unite)."',
				'".addslashes($prix)."'); ";
			}
		}
		if (($sql1 !='')) {
			$query =  $cnx->prepare($sql1); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Ajout des lignes de commandes ($codeCde, $libellecde)"); //updateLog($username, $idcust, $action='' )
		}
		unset($_SESSION['DATA_CDE']);
		header('location:commande.php?selectedTab=bde&rs=1');
		break;

	case 'update':
		(isset($_POST['xid']) && $_POST['xid']!=''						? $xid 			= trim($_POST['xid']) 			: $xid 			= '');
		(isset($_POST['exercice']) && $_POST['exercice']!=''			? $exercice 	= trim($_POST['exercice']) 		: $exercice 	= '');
		(isset($_POST['datecommande']) && $_POST['datecommande']!=''  	? $datecommande = trim($_POST['datecommande']) 	: $datecommande = '');
		(isset($_POST['refcommande']) && $_POST['refcommande']!=''  	? $refcommande 	= trim($_POST['refcommande']) 	: $refcommande 	= '');
		(isset($_POST['libellecde']) && $_POST['libellecde']!=''  		? $libellecde 	= trim($_POST['libellecde']) 	: $libellecde 	= '');
		(isset($_POST['fournisseur']) && $_POST['fournisseur']!='0'  	? $fournisseur 	= trim($_POST['fournisseur']) 	: $fournisseur 	= '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 	= trim($_POST['nbreLigne']) 	: $nbreLigne 	= '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 		= trim($_POST['statut']) 		: $statut 		= '0');

		$datecommande = mysqlFormat($datecommande);
		$magasin=$_SESSION['GL_USER']['MAGASIN'];
		$exercice =  $_SESSION['GL_USER']['EXERCICE'];
		//$statut = 1;

		//Update
		$sql  = "UPDATE `commande` SET `ID_EXERCICE`='".addslashes($exercice)."' ,`CODE_FOUR`='".addslashes($fournisseur)."' ,`REF_COMMANDE`='".addslashes($refcommande)."' ,
		`CDE_LIBELLE`='".addslashes($libellecde)."' ,`CDE_DATE`='".addslashes($datecommande)."' ,`CDE_STATUT`= '$statut' WHERE CODE_COMMANDE LIKE '".addslashes($xid)."'";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Modification d'une commande (".$xid.", ".$libellecde.')'); //updateLog($username, $idcust, $action='' )

		//Data
		$_SESSION['DATA_CDE']=array(
		'xid'=>$xid,
		'exercice'=>$exercice,
		'datecommande'=>$datecommande,
		'refcommande'=>$refcommande,
		'libellecde'=>$libellecde,
		'fournisseur'=> $fournisseur,
		'statut'=> $statut,
		'nbreLigne'=>$nbreLigne
		);

		$sql1 ="";
		//Collect Data
		$_SESSION['DATA_CDE']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_CDE']['nbreLigne']; $i++){
			(isset($_POST['oldcodeproduit'.$i])	? $oldcodeproduit 	= $_POST['oldcodeproduit'.$i] 	: $oldcodeproduit 	= '');
			(isset($_POST['codeproduit'.$i])	? $codeproduit 		= $_POST['codeproduit'.$i] 		: $codeproduit 	= '');
			(isset($_POST['produit'.$i]) 		? $produit 			= $_POST['produit'.$i] 			: $produit 		= '');
			(isset($_POST['qte'.$i]) 			? $qte 				= $_POST['qte'.$i] 				: $qte 			= '');
			(isset($_POST['unite'.$i]) 			? $unite 			= $_POST['unite'.$i] 			: $unite 		= '');
			(isset($_POST['prix'.$i]) 			? $prix 			= $_POST['prix'.$i] 			: $prix 		= '');


			if($oldcodeproduit!='' && $codeproduit!='' && $produit!='' && $qte!='') {
				$sql1 .="UPDATE `prd_cde` SET `CODE_COMMANDE`='".addslashes($xid)."' ,`CODE_PRODUIT`='".addslashes($codeproduit)."' ,`CDEPRD_QTE`='".addslashes($qte)."' ,
				`CDEPRD_UNITE`='".addslashes($unite)."',`CDEPRD_PA`='".addslashes($prix)."'  WHERE CODE_COMMANDE='$xid' AND CODE_PRODUIT='$oldcodeproduit'; ";
			}
			elseif($oldcodeproduit=='' && $codeproduit!='' && $produit!='' && $qte!=''){
				$sql1 .="INSERT INTO `prd_cde` (`CODE_COMMANDE` ,`CODE_PRODUIT` ,`CDEPRD_QTE` ,`CDEPRD_UNITE`,`CDEPRD_PA`)
				VALUES ( '".addslashes($xid)."', '".addslashes($codeproduit)."', '".addslashes($qte)."' ,  '".addslashes($unite)."' ,  '".addslashes($prix)."'); ";
			}
		}
		if (($sql1 !='')) {
			$query =  $cnx->prepare($sql1); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Modification des lignes de commandes ('.$xid.", ".$libellecde.')'); //updateLog($username, $idcust, $action='' )
		}
		//echo $sql1;
		unset($_SESSION['DATA_CDE']);
		header('location:commande.php?selectedTab=bde&rs=2');
		break;

	case 'validate':
		(isset($_POST['xid']) && $_POST['xid']!=''			? $xid 	= trim($_POST['xid']) 			: $xid = '');
		(isset($_POST['refcommande']) && $_POST['refcommande']!=''  	? $refcommande = trim($_POST['refcommande']) 		: $refcommande = '');
		(isset($_POST['libellecde']) && $_POST['libellecde']!=''  		? $libellecde 	= trim($_POST['libellecde']) 		: $libellecde = '');

		//Insert
		$sql  = "UPDATE `commande` SET `CDE_STATUT`= '1', CDE_DATEVALID='".date('Y-m-d H:i:s')."' WHERE CODE_COMMANDE LIKE '$xid'";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Validation d'une commande (".$xid.", ".$libellecde.' / '.$refcommande.')'); //updateLog($username, $idcust, $action='' )

		unset($_SESSION['DATA_CDE']);
		header('location:commande.php?selectedTab=bde&rs=3');
		break;

	case 'detail':
		(isset($_GET['xid']) ? $id = $_GET['xid'] : $id ='');
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		//COMMANDE
		$sql = "SELECT * FROM  `commande` WHERE CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND `CODE_COMMANDE` LIKE '".addslashes($id)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data  CDE_STATUT
		$_SESSION['DATA_CDE']=array(
		'xid'=>$row['CODE_COMMANDE'],
		'exercice'=>$row['ID_EXERCICE'],
		'datecommande'=>frFormat2($row['CDE_DATE']),
		'refcommande'=>stripslashes($row['REF_COMMANDE']),
		'libellecde'=>stripslashes($row['CDE_LIBELLE']),
		'fournisseur'=>$row['CODE_FOUR'],
		'statut'=>$row['CDE_STATUT'],
		'nbreLigne'=>0
		);

		//LIGNES COMMANDE
		$sql = "SELECT * FROM `prd_cde` INNER JOIN produit ON (prd_cde.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
		WHERE CODE_COMMANDE LIKE '".addslashes($id)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_CDE']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_CDE']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']), 'qte'=>$row['CDEPRD_QTE'], 'unite'=>$row['CDEPRD_UNITE'],  'prix'=>$row['CDEPRD_PA']));
		}
		$_SESSION['DATA_CDE']['nbreLigne'] = $query->rowCount();
		header('location:detailcommande.php?selectedTab=bde&rst=1');
		break;

	case 'check':
		$msg = "";
		(isset($_POST['refcommande']) && $_POST['refcommande']!='' ? $refcommande = trim($_POST['refcommande']) 	: $refcommande = '');

		if($refcommande !=''){
			$sql = "SELECT COUNT(REF_COMMANDE) AS NBRE FROM  `commande` WHERE `REF_COMMANDE` LIKE '".addslashes($refcommande)."'";
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

			if($row['NBRE']>0) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Ce code existe d&eacute;j&agrave;, veuillez entrer un autre code commande.';}
		}
		echo $msg;
		break;

	default : ///Nothing
}
}elseif($myaction !='')
switch($myaction){

	case 'addline':
		(isset($_POST['exercice']) && $_POST['exercice']!=''			? $exercice 	= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datecommande']) && $_POST['datecommande']!=''  	? $datecommande = trim($_POST['datecommande']) 		: $datecommande = '');
		(isset($_POST['refcommande']) && $_POST['refcommande']!=''  	? $refcommande = trim($_POST['refcommande']) 		: $refcommande = '');
		(isset($_POST['libellecde']) && $_POST['libellecde']!=''  		? $libellecde 	= trim($_POST['libellecde']) 		: $libellecde = '');
		(isset($_POST['fournisseur']) && $_POST['fournisseur']!='0'  	? $fournisseur 	= trim($_POST['fournisseur']) 		: $fournisseur = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 	= trim($_POST['nbreLigne']) 		: $nbreLigne = '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 		= trim($_POST['statut']) 			: $statut = '0');
		(isset($_POST['xid']) && $_POST['xid']!=''  					? $xid 			= trim($_POST['xid']) 			: $xid= '');

		//Data
		$_SESSION['DATA_CDE']=array(
		'xid'=>$xid,
		'exercice'=>$exercice,
		'datecommande'=>$datecommande,
		'refcommande'=>$refcommande,
		'libellecde'=>$libellecde,
		'fournisseur'=> $fournisseur,
		'statut'=> $statut,
		'nbreLigne'=>$nbreLigne
		);


		//Collect Data
		$moins=0;
		$_SESSION['DATA_CDE']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_CDE']['nbreLigne']; $i++){
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 	= '');
			(isset($_POST['prixUnit'.$i]) 	? $prixUnit 	= $_POST['prixUnit'.$i] 	: $prixUnit 	= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 		= '');
			(isset($_POST['unite'.$i]) 		? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');
			(isset($_POST['prix'.$i]) 		? $prix 		= $_POST['prix'.$i] 		: $prix 		= '');

			//Check if exite de produit
			$prdIndex = isExitePrd($codeproduit, $_SESSION['DATA_CDE']['ligne']);
			if($prdIndex != -1){ $_SESSION['DATA_CDE']['ligne'][$prdIndex]['qte'] += $qte; $moins++;	}
			else{
				//Add to list
				if($codeproduit!='' && $produit!='' && $qte!='') {
					array_push($_SESSION['DATA_CDE']['ligne'], array('codeproduit'=>$codeproduit, 'produit'=>$produit,  'qte'=>$qte, 'unite'=>$unite,'prix'=>$prix));
				}
			}
		}
		//Add line
		$_SESSION['DATA_CDE']['nbreLigne'] +=1;
		$_SESSION['DATA_CDE']['nbreLigne'] -= $moins;
		header('location:addcommande1.php?selectedTab=bde');
		break;

	case 'addline1':
		(isset($_POST['exercice']) && $_POST['exercice']!=''			? $exercice 	= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datecommande']) && $_POST['datecommande']!=''  	? $datecommande = trim($_POST['datecommande']) 		: $datecommande = '');
		(isset($_POST['refcommande']) && $_POST['refcommande']!=''  	? $refcommande = trim($_POST['refcommande']) 		: $refcommande = '');
		(isset($_POST['libellecde']) && $_POST['libellecde']!=''  		? $libellecde 	= trim($_POST['libellecde']) 		: $libellecde = '');
		(isset($_POST['fournisseur']) && $_POST['fournisseur']!='0'  	? $fournisseur 	= trim($_POST['fournisseur']) 		: $fournisseur = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 	= trim($_POST['nbreLigne']) 		: $nbreLigne = '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 		= trim($_POST['statut']) 			: $statut = '0');
		(isset($_POST['xid']) && $_POST['xid']!=''  					? $xid 		= trim($_POST['xid']) 			: $xid= '');

		//Data
		$_SESSION['DATA_CDE']=array(
		'xid'=>$xid,
		'exercice'=>$exercice,
		'datecommande'=>$datecommande,
		'refcommande'=>$refcommande,
		'libellecde'=>$libellecde,
		'fournisseur'=> $fournisseur,
		'statut'=> $statut,
		'nbreLigne'=>$nbreLigne
		);

		//Collect Data
		$moins=0;
		$_SESSION['DATA_CDE']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_CDE']['nbreLigne']; $i++){
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['oldcodeproduit'.$i])? $oldcodeproduit 	= $_POST['oldcodeproduit'.$i] 	: $oldcodeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 	= '');
			(isset($_POST['prixUnit'.$i]) 	? $prixUnit 	= $_POST['prixUnit'.$i] 	: $prixUnit 	= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 		= '');
			(isset($_POST['unite'.$i]) 		? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');
			(isset($_POST['prix'.$i]) 		? $prix 		= $_POST['prix'.$i] 		: $prix 		= '');

			//Check if exite de produit
			$prdIndex = isExitePrd($codeproduit, $_SESSION['DATA_CDE']['ligne']);
			if($prdIndex != -1){ $_SESSION['DATA_CDE']['ligne'][$prdIndex]['qte'] += $qte; $moins++;	}
			else{//Add to list
				if($codeproduit!='' && $produit!='' && $qte!='') array_push($_SESSION['DATA_CDE']['ligne'], array('codeproduit'=>$codeproduit, 'oldcodeproduit'=>$oldcodeproduit,'produit'=>$produit,  'qte'=>$qte, 'unite'=>$unite, 'prix'=>$prix));
			}
		}
		//Add line
		$_SESSION['DATA_CDE']['nbreLigne'] +=1;
		$_SESSION['DATA_CDE']['nbreLigne'] -= $moins;
		header('location:editcommande.php?selectedTab=bde');
		break;

	case 'delline':
		(isset($_POST['exercice']) && $_POST['exercice']!=''			? $exercice 	= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datecommande']) && $_POST['datecommande']!=''  	? $datecommande = trim($_POST['datecommande']) 		: $datecommande = '');
		(isset($_POST['refcommande']) && $_POST['refcommande']!=''  	? $refcommande = trim($_POST['refcommande']) 		: $refcommande = '');
		(isset($_POST['libellecde']) && $_POST['libellecde']!=''  		? $libellecde 	= trim($_POST['libellecde']) 		: $libellecde = '');
		(isset($_POST['fournisseur']) && $_POST['fournisseur']!='0'  	? $fournisseur 	= trim($_POST['fournisseur']) 		: $fournisseur = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 	= trim($_POST['nbreLigne']) 		: $nbreLigne = '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 		= trim($_POST['statut']) 			: $statut = '0');
		(isset($_POST['rowSelection']) && $_POST['rowSelection']!=''  	? $rowSelection = trim($_POST['rowSelection']) 		: $rowSelection = $codeproduit);
		(isset($_POST['xid']) && $_POST['xid']!=''  					? $xid 			= trim($_POST['xid']) 				: $xid= '');

		//Data
		$_SESSION['DATA_CDE']=array(
		'xid'=>$xid,
		'exercice'=>$exercice,
		'datecommande'=>$datecommande,
		'refcommande'=>$refcommande,
		'libellecde'=>$libellecde,
		'fournisseur'=> $fournisseur,
		'statut'=> $statut,
		'nbreLigne'=>$nbreLigne
		);

		$supp =0;
		//Collect Data
		$_SESSION['DATA_CDE']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_CDE']['nbreLigne']; $i++){
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 		= '');
			(isset($_POST['prixUnit'.$i]) 	? $prixUnit 	= $_POST['prixUnit'.$i] 	: $prixUnit 	= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 			= '');
			(isset($_POST['unite'.$i]) 		? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');
			(isset($_POST['prix'.$i]) 		? $prix 		= $_POST['prix'.$i] 		: $prix 		= '');

			//Add to list
			if($codeproduit!='' && $produit!='' && $qte!='' && $rowSelection!=$codeproduit){
				array_push($_SESSION['DATA_CDE']['ligne'], array('codeproduit'=>$codeproduit, 'produit'=>$produit,  'qte'=>$qte, 'unite'=>$unite, 'prix'=>$prix));
			}
			elseif($codeproduit!='' && $produit!='' && $qte!='' && $rowSelection==$codeproduit){
				$supp++;
			}
		}
		$_SESSION['DATA_CDE']['nbreLigne'] -=$supp;
		header('location:addcommande1.php?selectedTab=bde');
		break;

	case 'delline1':
		(isset($_POST['exercice']) && $_POST['exercice']!=''			? $exercice 	= trim($_POST['exercice']) 			: $exercice 		= '');
		(isset($_POST['datecommande']) && $_POST['datecommande']!=''  	? $datecommande = trim($_POST['datecommande']) 		: $datecommande 	= '');
		(isset($_POST['refcommande']) && $_POST['refcommande']!=''  	? $refcommande = trim($_POST['refcommande']) 		: $refcommande 	= '');
		(isset($_POST['libellecde']) && $_POST['libellecde']!=''  		? $libellecde 	= trim($_POST['libellecde']) 		: $libellecde 		= '');
		(isset($_POST['fournisseur']) && $_POST['fournisseur']!='0'  	? $fournisseur 	= trim($_POST['fournisseur']) 		: $fournisseur 		= '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 	= trim($_POST['nbreLigne']) 		: $nbreLigne 		= '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 		= trim($_POST['statut']) 			: $statut 			= '0');
		(isset($_POST['rowSelection']) && $_POST['rowSelection']!=''  	? $rowSelection = trim($_POST['rowSelection']) 		: $rowSelection 	= $codeproduit);
		(isset($_POST['xid']) && $_POST['xid']!=''  					? $xid 			= trim($_POST['xid']) 				: $xid				= '');

		//Data
		$_SESSION['DATA_CDE']=array(
		'xid'=>$xid,
		'exercice'=>$exercice,
		'datecommande'=>$datecommande,
		'refcommande'=>$refcommande,
		'libellecde'=>$libellecde,
		'fournisseur'=> $fournisseur,
		'statut'=> $statut,
		'nbreLigne'=>$nbreLigne
		);

		$supp=0;
		//Collect Data
		$_SESSION['DATA_CDE']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_CDE']['nbreLigne']; $i++){
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['oldcodeproduit'.$i])? $oldcodeproduit 	= $_POST['oldcodeproduit'.$i] 	: $oldcodeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 	= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 		= '');
			(isset($_POST['unite'.$i]) 		? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');
			(isset($_POST['prix'.$i]) 		? $prix 		= $_POST['prix'.$i] 		: $prix 		= '');

			//Add to list
			if($codeproduit!='' && $produit!='' && $qte!='' && $rowSelection!=$codeproduit){
				array_push($_SESSION['DATA_CDE']['ligne'], array('codeproduit'=>$codeproduit, 'oldcodeproduit'=>$oldcodeproduit, 'produit'=>$produit,  'qte'=>$qte, 'unite'=>$unite, 'prix'=>$prix));
			}
			elseif($codeproduit!='' && $produit!='' && $qte!='' && $rowSelection==$codeproduit){
				$supp++;
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}
				$sql = "DELETE FROM  `prd_cde` WHERE CODE_PRODUIT='".addslashes($codeproduit)."' AND `CODE_COMMANDE` LIKE '".addslashes($xid)."'";
				$query =  $cnx->prepare($sql); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

			}
		}
		$_SESSION['DATA_CDE']['nbreLigne'] -=$supp;
		header('location:editcommande.php?selectedTab=bde');
		break;

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
		//COMMANDE
		$sql = "SELECT * FROM  `commande` WHERE CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."'
		AND  `CODE_COMMANDE` LIKE '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data  CDE_STATUT
		$_SESSION['DATA_CDE']=array(
		'xid'=>$row['CODE_COMMANDE'],
		'exercice'=>$row['ID_EXERCICE'],
		'datecommande'=>frFormat2($row['CDE_DATE']),
		'refcommande'=>stripslashes($row['REF_COMMANDE']),
		'libellecde'=>stripslashes($row['CDE_LIBELLE']),
		'fournisseur'=>$row['CODE_FOUR'],
		'statut'=>$row['CDE_STATUT'],
		'nbreLigne'=>0
		);

		//LIGNES COMMANDE
		$sql = "SELECT * FROM `prd_cde` INNER JOIN produit ON (prd_cde.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
		WHERE CODE_COMMANDE LIKE '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_CDE']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_CDE']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'oldcodeproduit'=>$row['CODE_PRODUIT'],'produit'=>stripslashes($row['PRD_LIBELLE']), 'qte'=>$row['CDEPRD_QTE'], 'unite'=>$row['CDEPRD_UNITE'], 'prix'=>$row['CDEPRD_PA']));
		}
		$_SESSION['DATA_CDE']['nbreLigne'] = $query->rowCount();
		header('location:editcommande.php?selectedTab=bde&rs=2');
		break;

	case 'annul':
		(isset($_POST['xid']) ? $xid = $_POST['xid'] : $xid ='');
		(isset($_POST['oldrefcommande']) ? $oldrefcommande = $_POST['oldrefcommande'] : $oldrefcommande ='');
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		//TRANSFERT
		$sql = "UPDATE `commande` SET  CDE_STATUT=2 WHERE `CODE_COMMANDE` LIKE '".addslashes($xid)."';";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Annulation d\'une commande ('.$xid.', '.$oldrefcommande.')'); //updateLog($username, $idcust, $action='' )
		header('location:commande.php?selectedTab=bde&rst=1');
		break;

	case 'validate':
		(isset($_POST['rowSelection']) ? $id = $_POST['rowSelection'][0] : $id ='');
		$split = preg_split('/@/',$id);
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		//COMMANDE
		$sql = "SELECT * FROM  `commande` WHERE CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND `CODE_COMMANDE` LIKE '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data  CDE_STATUT
		$_SESSION['DATA_CDE']=array(
		'xid'=>$row['CODE_COMMANDE'],
		'exercice'=>$row['ID_EXERCICE'],
		'datecommande'=>frFormat2($row['CDE_DATE']),
		'refcommande'=>$row['REF_COMMANDE'],
		'libellecde'=>$row['CDE_LIBELLE'],
		'fournisseur'=>$row['CODE_FOUR'],
		'statut'=>$row['CDE_STATUT'],
		'nbreLigne'=>0
		);

		//LIGNES COMMANDE
		$sql = "SELECT * FROM `prd_cde` INNER JOIN produit ON (prd_cde.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
		WHERE CODE_COMMANDE LIKE  '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_CDE']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_CDE']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']), 'qte'=>$row['CDEPRD_QTE'], 'unite'=>$row['CDEPRD_UNITE'],'prix'=>$row['CDEPRD_PA']));
		}
		$_SESSION['DATA_CDE']['nbreLigne'] = $query->rowCount();
		header('location:validcommande.php?selectedTab=bde&rs=3');
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
			$sql = "DELETE FROM  `prd_cde` WHERE `CODE_COMMANDE` LIKE '".addslashes($split[0])."';
			DELETE FROM  `commande` WHERE `CODE_COMMANDE` LIKE '".addslashes($split[0])."'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
		}
		header('location:commande.php?selectedTab=bde&rs=4');
		break;

	default : ///Nothing
		//header('location:../index.php');
}
elseif($myaction =='' && $do ='') header('location:../index.php');

?>
