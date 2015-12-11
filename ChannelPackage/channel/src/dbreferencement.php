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
	case 'next':
		(isset($_POST['exercice']) && $_POST['exercice']!=''			? $exercice 		= trim($_POST['exercice']) 		: $exercice 		= '');
		(isset($_POST['datelivraison']) && $_POST['datelivraison']!=''  ? $datelivraison 	= trim($_POST['datelivraison']) : $datelivraison 	= '');
		(isset($_POST['commande']) && $_POST['commande']!='0'  			? $commande			= trim($_POST['commande']) 		: $commande 		= '');
		(isset($_POST['codelivraison']) && $_POST['codelivraison']!=''  ? $codelivraison 	= trim($_POST['codelivraison'])	: $codelivraison 	= '');
		(isset($_POST['libelle']) && $_POST['libelle']!='' 				? $libelle 			= trim($_POST['libelle']) 		: $libelle 			= '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 		= trim($_POST['nbreLigne']) 	: $nbreLigne 		= '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 			= trim($_POST['statut']) 		: $statut 			= '0');

		//Data
		$_SESSION['DATA_LVR']=array(
		'exercice'=>$exercice,
		'datelivraison'=>$datelivraison,
		'commande'=>$commande,
		'codelivraison'=>$codelivraison,
		'libelle'=>$libelle,
		'nbreLigne'=>$nbreLigne
		);

		if ($commande!='') {

			//Retrouver la commande et charger les ligne
			$sql = "SELECT * FROM prd_cde INNER JOIN commande ON (commande.ID_COMMANDE  = prd_cde.ID_COMMANDE)
			INNER JOIN produit ON (produit.CODE_PRODUIT LIKE prd_cde.CODE_PRODUIT)
			WHERE prd_cde.ID_COMMANDE=$commande ;";

			//Exécution
			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			//Collect Data
			$_SESSION['DATA_LVR']['ligne'] =array();
			$i=0;
			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				$rest =  $row['CDEPRD_QTE'] - livraisonPourProduit($commande, $row['CODE_PRODUIT']);
				//Add to list
				if($rest !=0) {
					array_push($_SESSION['DATA_LVR']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']),  'qte'=>$rest, 'unite'=>$row['CDEPRD_UNITE']));
					$i++;
				}
			}
			//Add line
			$_SESSION['DATA_LVR']['nbreLigne'] =$i;
			if($_SESSION['DATA_LVR']['nbreLigne']==0){header('location:addlivraison3.php?selectedTab=bde');}
			else{
				//Etape 2
				header('location:addlivraison1.php?selectedTab=bde');
			}
		}
		else{
				header('location:addlivraison2.php?selectedTab=bde');
		}

		break;


	case 'add':
		(isset($_POST['exercice']) && $_POST['exercice']!=''			? $exercice 		= trim($_POST['exercice']) 			: $exercice 		= '');
		(isset($_POST['datelivraison']) && $_POST['datelivraison']!=''  ? $datelivraison 	= trim($_POST['datelivraison'])		: $datelivraison 	= '');
		(isset($_POST['commande']) && $_POST['commande']!='0'  			? $commande 		= trim($_POST['commande']) 			: $commande 		= '');
		(isset($_POST['codelivraison']) && $_POST['codelivraison']!=''  ? $codelivraison 	= trim($_POST['codelivraison']) 	: $codelivraison 	= '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 			= trim($_POST['statut']) 			: $statut 			= '0');
		(isset($_POST['libelle']) && $_POST['libelle']!='' 				? $libelle 			= trim($_POST['libelle']) 			: $libelle 			= '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 		= trim($_POST['nbreLigne']) 		: $nbreLigne 		= '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 			= trim($_POST['statut']) 			: $statut 			= '0');

		$datelivraison = mysqlFormat($datelivraison);
		$magasin = $_SESSION['GL_USER']['MAGASIN'];
		$exercice =  $_SESSION['GL_USER']['EXERCICE'];

		//Data
		$_SESSION['DATA_LVR']=array(
		'exercice'=>$exercice,
		'datelivraison'=>$datelivraison,
		'commande'=>$commande,
		'codelivraison'=>$codelivraison,
		'libelle'=>$libelle,
		'nbreLigne'=>$nbreLigne
		);

		if($commande != ''){
			//Insert
			$sql  = "INSERT INTO `livraison` ( `ID_EXERCICE` ,`ID_COMMANDE` ,`CODE_LIVRAISON` ,`CODE_MAGASIN`,`LIVR_LIBELLE` ,`LVR_DATE` ,`LVR_VALIDE`)
			VALUES ('".addslashes($exercice)."', '".addslashes($commande)."','".addslashes($codelivraison)."','".addslashes($magasin)."',
			'".addslashes($libelle)."', '".addslashes($datelivraison)."' , '$statut');";
		}
		else {
			//Insert
			$sql  = "INSERT INTO `livraison` ( `ID_EXERCICE` ,`ID_COMMANDE` ,`CODE_LIVRAISON` ,`CODE_MAGASIN`,`LIVR_LIBELLE` ,`LVR_DATE` ,`LVR_VALIDE`)
			VALUES ('".addslashes($exercice)."',NULL,'".addslashes($codelivraison)."','".addslashes($magasin)."',
			'".addslashes($libelle)."', '".addslashes($datelivraison)."' , '$statut');";

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
		$insert_id =  $cnx->lastInsertId();
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Ajout d'une livraison (".$insert_id.', commande n°'.$commande.')'); //updateLog($username, $idcust, $action='' )

		//Data
		$_SESSION['DATA_LVR']['exercice']=$exercice;
		$_SESSION['DATA_LVR']['datelivraison']=$datelivraison;

		//Collect Data
		$sql1 ="";
		$sql2 ="";
		$_SESSION['DATA_LVR']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_LVR']['nbreLigne']; $i++){
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 		= '');
			(isset($_POST['qterecu'.$i]) 	? $qterecu 		= $_POST['qterecu'.$i] 		: $qterecu 		= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 			= $qterecu);
			(isset($_POST['unite'.$i]) 		? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');

			if($codeproduit!='' && $produit!='' && $qte!='' && $qterecu!='') {
				if($commande != ''){
					$sql1 .="INSERT INTO `prd_livraison` (`ID_LIVRAISON` ,`CODE_PRODUIT` ,`LVRPRD_QUANTITE` ,`LVRPRD_RECU` ,`LIV_UNITE` ,
					`LVRPRD_MAG`,`LVR_IDCOMMANDE`) VALUES ('".addslashes($insert_id)."', '".addslashes($codeproduit)."', '".addslashes($qte)."' ,
					'".addslashes($qterecu)."', '".addslashes($unite)."','".addslashes($magasin)."','".addslashes($commande)."'); ";
				}
				else{
					$sql1 .="INSERT INTO `prd_livraison` (`ID_LIVRAISON` ,`CODE_PRODUIT` ,`LVRPRD_QUANTITE` ,`LVRPRD_RECU` ,`LIV_UNITE` ,
					`LVRPRD_MAG`,`LVR_IDCOMMANDE`) VALUES ('".addslashes($insert_id)."', '".addslashes($codeproduit)."', '".addslashes($qte)."' ,
					'".addslashes($qterecu)."', '".addslashes($unite)."','".addslashes($magasin)."',NULL); ";
				}

				$sql2 .="INSERT INTO `mouvement` (`ID_EXERCICE` ,`CODE_PRODUIT` ,`ID_SOURCE` ,`CODE_MAGASIN` ,	`MVT_DATE` ,`MVT_TIME` ,`MVT_QUANTITE` ,
				`MVT_UNITE` ,`MVT_NATURE` ,	`MVT_VALID`,`MVT_TYPE`) VALUES ('".addslashes($exercice)."','".addslashes($codeproduit)."',
				'".addslashes($insert_id)."', '".addslashes($magasin)."', '".addslashes($datelivraison)."' ,'".addslashes(date('H:i:s'))."' ,
				'".addslashes($qterecu)."' , '".addslashes($unite)."', 'LIVRAISON','$statut','E') ; ";
			}
		}

		if (($sql1 !='')) {
			$query =  $cnx->prepare($sql1); //Prepare the SQL
			$query->execute(); //Execute prepared SQL =>
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout des lignes de livraison('.$insert_id.', commande n°'.$commande.')'); //updateLog($username, $idcust, $action='' )

			$query =  $cnx->prepare($sql2); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Ajout d'un mouvement(".$insert_id.', commande n°'.$commande.')'); //updateLog($username, $idcust, $action='' )
		}
		//echo $sql, $sql1, $sql2;
		unset($_SESSION['DATA_LVR']);
		header('location:livraison.php?selectedTab=bde&rst=1');
		break;

	case 'update':
		(isset($_POST['xid']) && $_POST['xid']!=''						? $xid 			= trim($_POST['xid']) 			: $xid = '');
		(isset($_POST['exercice']) && $_POST['exercice']!=''			? $exercice 	= trim($_POST['exercice']) 		: $exercice = '');
		(isset($_POST['datelivraison']) && $_POST['datelivraison']!=''  ? $datelivraison = trim($_POST['datelivraison']): $datelivraison = '');
		(isset($_POST['commande']) && $_POST['commande']!='0'  			? $commande 	= trim($_POST['commande']) 			: $commande = '');
		(isset($_POST['codelivraison']) && $_POST['codelivraison']!=''  ? $codelivraison = trim($_POST['codelivraison']) 	: $codelivraison = '');
		(isset($_POST['statut']) && $_POST['statut']!=''  				? $statut 		= trim($_POST['statut']) 			: $statut = '');

		$datelivraison = mysqlFormat($datelivraison);
		$magasin=$_SESSION['GL_USER']['MAGASIN'];
		$exercice =  $_SESSION['GL_USER']['EXERCICE'];

		if($commande != ''){
			//Insert
			$sql  = "UPDATE `livraison` SET `ID_EXERCICE`='".addslashes($exercice)."' ,`CODE_LIVRAISON`='".addslashes($codelivraison)."',
			`ID_COMMANDE`='".addslashes($commande)."' ,	`LVR_DATE`='".addslashes($datelivraison)."' ,`LVR_VALIDE`='".addslashes($statut)."'
			WHERE ID_LIVRAISON='$xid'";
		}
		else {
			$sql  = "UPDATE `livraison` SET `ID_EXERCICE`='".addslashes($exercice)."' ,`CODE_LIVRAISON`='".addslashes($codelivraison)."',
			`ID_COMMANDE`=NULL ,`LVR_DATE`='".addslashes($datelivraison)."' ,`LVR_VALIDE`='".addslashes($statut)."'
			WHERE ID_LIVRAISON='$xid'";
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
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Modification d'une livraison (".$xid.', commande n°'.$commande.')'); //updateLog($username, $idcust, $action='' )

		//Data
		$_SESSION['DATA_LVR']['exercice']=$exercice;
		$_SESSION['DATA_LVR']['datelivraison']=$datelivraison;

		//Collect Data
		$sql1 ="";
		$sql2 ="";
		$_SESSION['DATA_LVR']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_LVR']['nbreLigne']; $i++){
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['oldcodeproduit'.$i])? $oldcodeproduit 	= $_POST['oldcodeproduit'.$i] 	: $oldcodeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 		= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 			= '');
			(isset($_POST['qterecu'.$i]) 	? $qterecu 		= $_POST['qterecu'.$i] 		: $qterecu 		= '');
			(isset($_POST['unite'.$i]) 		? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');

			if($oldcodeproduit!='' && $codeproduit!='' && $produit!='' && $qte!='') {
				if($commande != ''){
					$sql1 .="UPDATE `prd_livraison` SET `CODE_PRODUIT`='".addslashes($codeproduit)."' ,`LVRPRD_QUANTITE`='".addslashes($qte)."' ,
					`LVRPRD_RECU`='".addslashes($qterecu)."', `LIV_UNITE`='".addslashes($unite)."', `LVRPRD_MAG`='".addslashes($magasin)."',
					`LVR_IDCOMMANDE`='".addslashes($commande)."'  WHERE `CODE_PRODUIT`='".addslashes($oldcodeproduit)."' AND ID_LIVRAISON='$xid' ;";
				}
				else{
					$sql1 .="UPDATE `prd_livraison` SET `CODE_PRODUIT`='".addslashes($codeproduit)."' ,`LVRPRD_QUANTITE`='".addslashes($qte)."' ,
					`LVRPRD_RECU`='".addslashes($qterecu)."', `LIV_UNITE`='".addslashes($unite)."', `LVRPRD_MAG`='".addslashes($magasin)."',
					`LVR_IDCOMMANDE`=NULL WHERE `CODE_PRODUIT`='".addslashes($oldcodeproduit)."' AND ID_LIVRAISON='$xid' ;";
				}

				$sql2 .="UPDATE `mouvement` SET `CODE_PRODUIT`='".addslashes($codeproduit)."' ,`ID_EXERCICE`='".addslashes($exercice)."' ,`CODE_MAGASIN`='".addslashes($magasin)."' ,
				`MVT_DATE`='".addslashes($datelivraison)."' ,`MVT_TIME`='".addslashes(date('H:i:s'))."' ,`MVT_QUANTITE`='".addslashes($qterecu)."' ,`MVT_UNITE`='".addslashes($unite)."',
				`MVT_VALID`='$statut', `MVT_TYPE`='E'	WHERE `CODE_PRODUIT`='".addslashes($oldcodeproduit)."' AND `MVT_NATURE`='LIVRAISON' AND ID_SOURCE='$xid'; ";

			}
			elseif($oldcodeproduit=='' && $codeproduit!='' && $produit!='' && $qte!='') {
				if($commande != ''){
					$sql1 .="INSERT INTO `prd_livraison` (`ID_LIVRAISON` ,`CODE_PRODUIT` ,`LVRPRD_QUANTITE` ,`LVRPRD_RECU` ,`LIV_UNITE` ,
					`LVRPRD_MAG`,`LVR_IDCOMMANDE`) VALUES ('".addslashes($insert_id)."', '".addslashes($codeproduit)."', '".addslashes($qte)."' ,
					'".addslashes($qterecu)."', '".addslashes($unite)."','".addslashes($magasin)."','".addslashes($commande)."'); ";
				}
				else{
					$sql1 .="INSERT INTO `prd_livraison` (`ID_LIVRAISON` ,`CODE_PRODUIT` ,`LVRPRD_QUANTITE` ,`LVRPRD_RECU` ,`LIV_UNITE` ,
					`LVRPRD_MAG`,`LVR_IDCOMMANDE`) VALUES ('".addslashes($insert_id)."', '".addslashes($codeproduit)."', '".addslashes($qte)."' ,
					'".addslashes($qterecu)."', '".addslashes($unite)."','".addslashes($magasin)."',NULL); ";
				}

				$sql2 .="INSERT INTO `mouvement` (`ID_EXERCICE` ,`CODE_PRODUIT` ,`ID_SOURCE` ,`CODE_MAGASIN` ,	`MVT_DATE` ,`MVT_TIME` ,`MVT_QUANTITE` ,
				`MVT_UNITE` ,`MVT_NATURE` ,	`MVT_VALID`,`MVT_TYPE`) VALUES ('".addslashes($exercice)."','".addslashes($codeproduit)."',
				'".addslashes($insert_id)."', '".addslashes($magasin)."', '".addslashes($datelivraison)."' ,'".addslashes(date('H:i:s'))."' ,
				'".addslashes($qterecu)."' , '".addslashes($unite)."', 'LIVRAISON','$statut','E') ; ";
			}
		}

		if (($sql1 !='')) {
			$query =  $cnx->prepare($sql1); //Prepare the SQL
			$query->execute(); //Execute prepared SQL =>
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Modification des lignes de livraison('.$xid.', commande n°'.$commande.')'); //updateLog($username, $idcust, $action='' )

			$query =  $cnx->prepare($sql2); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Modification d'un mouvement(".$xid.', commande n°'.$commande.')'); //updateLog($username, $idcust, $action='' )
		}
		unset($_SESSION['DATA_LVR']);
		header('location:livraison.php?selectedTab=bde&rs=2');
		break;

	case 'validate':
		(isset($_POST['xid']) && $_POST['xid']!=''						? $xid 				= trim($_POST['xid']) 			: $xid = '');
		(isset($_POST['exercice']) && $_POST['exercice']!=''			? $exercice 		= trim($_POST['exercice']) 		: $exercice = '');
		(isset($_POST['datelivraison']) && $_POST['datelivraison']!=''  ? $datelivraison 	= trim($_POST['datelivraison'])	: $datelivraison = '');
		(isset($_POST['codelivraison']) && $_POST['codelivraison']!=''  ? $codelivraison 	= trim($_POST['codelivraison']) : $codelivraison = '');
		(isset($_POST['commande']) && $_POST['commande']!='0'  			? $commande 		= trim($_POST['commande']) 		: $commande = '');

		$datelivraison = mysqlFormat($datelivraison);
		$statut =1;
		$magasin=$_SESSION['GL_USER']['MAGASIN'];

		if($commande != ''){
			//Insert
			$sql  = "UPDATE `livraison` SET `ID_EXERCICE`='".addslashes($exercice)."' ,`CODE_LIVRAISON`='".addslashes($codelivraison)."',
			`ID_COMMANDE`='".addslashes($commande)."' ,	`LVR_DATE`='".addslashes($datelivraison)."' ,`LVR_VALIDE`='".addslashes($statut)."'
			WHERE ID_LIVRAISON='$xid'";
		}
		else {
			$sql  = "UPDATE `livraison` SET `ID_EXERCICE`='".addslashes($exercice)."' ,`CODE_LIVRAISON`='".addslashes($codelivraison)."',
			`ID_COMMANDE`=NULL ,`LVR_DATE`='".addslashes($datelivraison)."' ,`LVR_VALIDE`='".addslashes($statut)."'
			WHERE ID_LIVRAISON='$xid'";
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
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Validation d'une livraison (".$xid.', commande n°'.$commande.')'); //updateLog($username, $idcust, $action='' )

		//Data
		$_SESSION['DATA_LVR']['exercice']=$exercice;
		$_SESSION['DATA_LVR']['datelivraison']=$datelivraison;
		$_SESSION['DATA_LVR']['nbreLigne'];

		//Collect Data
		$sql1 ="";
		$sql2 ="";
		$_SESSION['DATA_LVR']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_LVR']['nbreLigne']; $i++){
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['oldcodeproduit'.$i])? $oldcodeproduit 	= $_POST['oldcodeproduit'.$i] 	: $oldcodeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 		= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 			= '');
			(isset($_POST['qterecu'.$i]) 	? $qterecu 		= $_POST['qterecu'.$i] 		: $qterecu 		= '');
			(isset($_POST['unite'.$i]) 		? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');

			if($codeproduit!='' && $produit!='' && $qte!='') {
				$sql1 .="UPDATE `prd_livraison` SET `CODE_PRODUIT`='".addslashes($codeproduit)."'  ,`LVRPRD_QUANTITE`='".addslashes($qte)."' ,
				`LVRPRD_RECU`='".addslashes($qterecu)."', `LIV_UNITE`='".addslashes($unite)."', `LVRPRD_MAG`='".addslashes($magasin)."'
				WHERE `CODE_PRODUIT`='".addslashes($oldcodeproduit)."' AND ID_LIVRAISON='$xid' ;";

				$sql2 .="UPDATE `mouvement` SET `CODE_PRODUIT`='".addslashes($codeproduit)."' ,`ID_EXERCICE`='".addslashes($exercice)."' ,`CODE_MAGASIN`='".addslashes($magasin)."' ,
				`MVT_DATE`='".addslashes($datelivraison)."' ,`MVT_TIME`='".addslashes(date('H:i:s'))."' ,`MVT_QUANTITE`='".addslashes($qterecu)."' ,`MVT_UNITE`='".addslashes($unite)."',
				`MVT_VALID`='$statut', `MVT_TYPE`='E'	WHERE `CODE_PRODUIT`='".addslashes($oldcodeproduit)."' AND `MVT_NATURE`='LIVRAISON' AND ID_SOURCE='$xid'; ";
			}
		}

		if (($sql1 !='')) {
			$query =  $cnx->prepare($sql1); //Prepare the SQL
			$query->execute(); //Execute prepared SQL =>
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'validation des lignes de livraison('.$xid.', commande n°'.$commande.')'); //updateLog($username, $idcust, $action='' )

			$query =  $cnx->prepare($sql2); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Validation d'un mouvement(".$xid.', commande n°'.$commande.')'); //updateLog($username, $idcust, $action='' )
		}
		//echo $sql1, $sql2;
		unset($_SESSION['DATA_LVR']);
		header('location:livraison.php?selectedTab=bde&rs=2');
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
		$sql = "SELECT * FROM  `livraison` WHERE CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND `ID_LIVRAISON` = '".addslashes($id)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data  CDE_STATUT
		$_SESSION['DATA_LVR']=array(
		'xid'=>$row['ID_LIVRAISON'],
		'exercice'=>$row['ID_EXERCICE'],
		'datelivraison'=>frFormat2($row['LVR_DATE']),
		'codelivraison'=>stripslashes($row['CODE_LIVRAISON']),
		'commande'=>$row['ID_COMMANDE'],
		'libelle'=>stripslashes($row['LIVR_LIBELLE']),
		'statut'=>$row['LVR_VALIDE'],
		'nbreLigne'=>0
		);

		//LIGNES COMMANDE
		$sql = "SELECT * FROM `prd_livraison` INNER JOIN produit ON (prd_livraison.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
		WHERE ID_LIVRAISON = '".addslashes($id)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_LVR']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_LVR']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']), 'qte'=>$row['LVRPRD_QUANTITE'], 'qtelvr'=>$row['LVRPRD_RECU'], 'unite'=>$row['LIV_UNITE'], 'magasin'=>$row['LVRPRD_MAG']));
		}
		$_SESSION['DATA_LVR']['nbreLigne'] = $query->rowCount();
		header('location:detaillivraison.php?selectedTab=bde&rst=1');
		break;

	case 'journal':
		(isset($_GET['xid']) ? $id = $_GET['xid'] : $id ='');
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		//COMMANDE
		$sql = "SELECT * FROM  `livraison` WHERE CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND `ID_LIVRAISON` = '".addslashes($id)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data  CDE_STATUT
		$_SESSION['DATA_LVR']=array(
		'xid'=>$row['ID_LIVRAISON'],
		'exercice'=>$row['ID_EXERCICE'],
		'datelivraison'=>frFormat2($row['LVR_DATE']),
		'codelivraison'=>stripslashes($row['CODE_LIVRAISON']),
		'commande'=>$row['ID_COMMANDE'],
		'libelle'=>stripslashes($row['LIVR_LIBELLE']),
		'statut'=>$row['LVR_VALIDE'],
		'nbreLigne'=>0,
		'ligne'=>array(),
		'journal'=>array()
		);

		//LIGNES COMMANDE
		$sql = "SELECT * FROM `prd_livraison` INNER JOIN produit ON (prd_livraison.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
		WHERE ID_LIVRAISON = '".addslashes($id)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_LVR']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_LVR']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']), 'qte'=>$row['LVRPRD_QUANTITE'], 'qtelvr'=>$row['LVRPRD_RECU'], 'unite'=>$row['LIV_UNITE'], 'magasin'=>$row['LVRPRD_MAG']));
		}
		$_SESSION['DATA_LVR']['nbreLigne'] = $query->rowCount();


		//LIGNES MOUVEMENT
		$sql = "SELECT * FROM `mouvement` WHERE MVT_NATURE LIKE 'LIVRAISON' AND ID_SOURCE = '".addslashes($id)."' ORDER BY CODE_PRODUIT ASC";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_LVR']['journal'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_LVR']['journal'], array('codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']), 'qte'=>$row['MVT_QUANTITE'], 'qtelvr'=>$row['MVT_QUANTITE'], 'unite'=>$row['MVT_UNITE'], 'valide'=>$row['MVT_VALID']));
		}

		$_SESSION['DATA_LVR']['nbreLigne2'] = $query->rowCount();

		//print_r($_SESSION['DATA_LVR']['journal']);
		header('location:journallivraison.php?selectedTab=bde&rst=1');
		break;


	case 'check':
		$msg = "";
		(isset($_POST['code']) && $_POST['code']!='' 		? $code = trim($_POST['code']) 	: $code = '');

		if($code !=''){
			$sql = "SELECT COUNT(CODE_LIVRAISON) AS NBRE FROM  `livraison` WHERE `CODE_LIVRAISON` LIKE '".addslashes($code)."'";
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

			if($row['NBRE']>0) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Ce code existe d&eacute;j&agrave;, veuillez entrer un autre code livraison.';}
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
		//COMMANDE
		$sql = "SELECT * FROM  `livraison` WHERE CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND `ID_LIVRAISON` = '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data
		$_SESSION['DATA_LVR']=array(
		'xid'=>$row['ID_LIVRAISON'],
		'exercice'=>$row['ID_EXERCICE'],
		'datelivraison'=>frFormat2($row['LVR_DATE']),
		'commande'=>$row['ID_COMMANDE'],
		'libelle'=>stripslashes($row['LIVR_LIBELLE']),
		'codelivraison'=>stripslashes($row['CODE_LIVRAISON']),
		'statut'=>$row['LVR_VALIDE'],
		'nbreLigne'=>0
		);

		//LIGNES LIVRAISONS
		$sql = "SELECT * FROM `prd_livraison` INNER JOIN produit
		ON (prd_livraison.CODE_PRODUIT LIKE produit.CODE_PRODUIT) WHERE ID_LIVRAISON = '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_LVR']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_LVR']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']), 'qte'=>$row['LVRPRD_QUANTITE'], 'qtelvr'=>$row['LVRPRD_RECU'], 'unite'=>$row['LIV_UNITE'], 'mag'=>$row['magasin']));
		}
		$_SESSION['DATA_LVR']['nbreLigne'] = $query->rowCount();
		header('location:editlivraison.php?selectedTab=bde&rst=1');
		break;

	case 'annul':
		(isset($_POST['xid']) ? $xid = $_POST['xid'] : $xid ='');
		(isset($_POST['oldcode']) ? $oldcode = $_POST['oldcode'] : $oldcode ='');
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		//LIVRAISON
		$sql = "UPDATE `livraison` SET  LVR_VALIDE=2 WHERE `ID_LIVRAISON` = '".addslashes($xid)."';
		UPDATE mouvement SET MVT_VALID=2 WHERE (MVT_NATURE LIKE 'LIVRAISON')
		AND ID_SOURCE='".addslashes($xid)."';";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Annulation d\'une livraison ('.$xid.', '.$oldcode.')'); //updateLog($username, $idcust, $action='' )
		header('location:livraison.php?selectedTab=bde&rst=1');
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
		$sql = "SELECT * FROM  `livraison` WHERE CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND `ID_LIVRAISON` = '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data
		$_SESSION['DATA_LVR']=array(
		'xid'=>$row['ID_LIVRAISON'],
		'exercice'=>$row['ID_EXERCICE'],
		'datelivraison'=>frFormat2($row['LVR_DATE']),
		'commande'=>$row['ID_COMMANDE'],
		'codelivraison'=>stripslashes($row['CODE_LIVRAISON']),
		'statut'=>$row['LVR_VALIDE'],
		'nbreLigne'=>0
		);

		//LIGNES COMMANDE
		$sql = "SELECT * FROM `prd_livraison` INNER JOIN produit
		ON (prd_livraison.CODE_PRODUIT LIKE produit.CODE_PRODUIT) WHERE ID_LIVRAISON = '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_LVR']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_LVR']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']), 'qte'=>$row['LVRPRD_QUANTITE'], 'qtelvr'=>$row['LVRPRD_RECU'], 'unite'=>$row['LIV_UNITE'], 'mag'=>$row['magasin']));
		}
		$_SESSION['DATA_LVR']['nbreLigne'] = $query->rowCount();
		header('location:validlivraison.php?selectedTab=bde&rst=1');
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
			$sql = "DELETE FROM  `prd_livraison` WHERE `ID_LIVRAISON` = '".addslashes($split[0])."';
			DELETE FROM  `livraison` WHERE `ID_LIVRAISON` = '".addslashes($split[0])."';";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			//Suppression dans Mouvement
			$sql = "DELETE FROM  `mouvement` WHERE `ID_SOURCE` = '".addslashes($split[0])."' AND MVT_NATURE LIKE 'LIVRAISON'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
		}
		header('location:livraison.php?selectedTab=bde&rs=4');
		break;

	default : ///Nothing
		//header('location:../index.php');

}
elseif($myaction =='' && $do ='') header('location:../index.php');
?>
