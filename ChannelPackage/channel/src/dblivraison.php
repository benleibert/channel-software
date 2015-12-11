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
	//Commande
	case 'next':
		(isset($_POST['exercice']) && $_POST['exercice']!=''			? $exercice 		= trim($_POST['exercice']) 		: $exercice 		= '');
		(isset($_POST['datelivraison']) && $_POST['datelivraison']!=''  ? $datelivraison 	= trim($_POST['datelivraison']) : $datelivraison 	= '');
		(isset($_POST['commande']) && $_POST['commande']!=''  			? $commande 		= trim($_POST['commande']) 		: $commande 		= '');
		(isset($_POST['libcommande']) && $_POST['libcommande']!=''  	? $libcommande 		= trim($_POST['libcommande']) 	: $libcommande 		= '');
		(isset($_POST['idfournisseur']) && $_POST['idfournisseur']!=''  ? $idfournisseur 	= trim($_POST['idfournisseur']) : $idfournisseur 	= '');
		(isset($_POST['fournisseur']) && $_POST['fournisseur']!=''  	? $fournisseur 		= trim($_POST['fournisseur']) 	: $fournisseur	 	= '');
		(isset($_POST['reflivraison']) && $_POST['reflivraison']!=''  	? $reflivraison 	= trim($_POST['reflivraison'])	: $reflivraison 	= '');
		(isset($_POST['libelle']) && $_POST['libelle']!='' 				? $libelle 			= trim($_POST['libelle']) 		: $libelle 			= '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 		= trim($_POST['nbreLigne']) 	: $nbreLigne 		= '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 			= trim($_POST['statut']) 		: $statut 			= '0');

		//Data
		$_SESSION['DATA_LVR']=array(
		'exercice'=>$exercice,
		'datelivraison'=>$datelivraison,
		'commande'=>$commande,
		'libcommande'=>$libcommande,
		'fournisseur'=>$fournisseur,
		'idfournisseur'=>$idfournisseur,
		'reflivraison'=>$reflivraison,
		'libelle'=>$libelle,
		'nbreLigne'=>$nbreLigne
		);

		if ($commande!='') {

			//Retrouver la commande et charger les ligne
			$sql = "SELECT * FROM prd_cde INNER JOIN commande ON (commande.CODE_COMMANDE  = prd_cde.CODE_COMMANDE)
			INNER JOIN produit ON (produit.CODE_PRODUIT LIKE prd_cde.CODE_PRODUIT)
			WHERE prd_cde.CODE_COMMANDE LIKE '".addslashes($commande)."';";

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
				if($rest >0) {
					array_push($_SESSION['DATA_LVR']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']), 'prix'=>$row['PRD_PRIXACHATN2'], 'qte'=>$rest, 'unite'=>$row['CDEPRD_UNITE']));
					$i++;
				}
			}
			//Add line
			$_SESSION['DATA_LVR']['nbreLigne'] =$i;
			if($_SESSION['DATA_LVR']['nbreLigne']==0){
				header('location:addlivraison3.php?selectedTab=bde');
			}
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
		(isset($_POST['exercice']) && $_POST['exercice']!=''			? $exercice 		= trim($_POST['exercice']) 		: $exercice 		= '');
		(isset($_POST['datelivraison']) && $_POST['datelivraison']!=''  ? $datelivraison 	= trim($_POST['datelivraison'])	: $datelivraison 	= '');
		(isset($_POST['commande']) && $_POST['commande']!=''  			? $commande 		= trim($_POST['commande']) 		: $commande 		= '');
		(isset($_POST['libcommande']) && $_POST['libcommande']!=''  	? $libcommande 		= trim($_POST['libcommande']) 	: $libcommande 		= '');
		(isset($_POST['idfournisseur']) && $_POST['idfournisseur']!=''  ? $idfournisseur 	= trim($_POST['idfournisseur']) : $idfournisseur 	= '');
		(isset($_POST['fournisseur']) && $_POST['fournisseur']!=''  	? $fournisseur 		= trim($_POST['fournisseur']) 	: $fournisseur	 	= '');
		(isset($_POST['reflivraison']) && $_POST['reflivraison']!=''  	? $reflivraison 	= trim($_POST['reflivraison']) 	: $reflivraison 	= '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 			= trim($_POST['statut']) 		: $statut 			= '0');
		(isset($_POST['libelle']) && $_POST['libelle']!='' 				? $libelle 			= trim($_POST['libelle']) 		: $libelle 			= '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 		= trim($_POST['nbreLigne']) 	: $nbreLigne 		= '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 			= trim($_POST['statut']) 		: $statut 			= '0');

		$datelivraison = mysqlFormat($datelivraison);
		$magasin = $_SESSION['GL_USER']['MAGASIN'];
		$exercice =  $_SESSION['GL_USER']['EXERCICE'];
		//$statut = 1;

		$numauto = myDbLastId('livraison', 'ID_LIVRAISON', $magasin)+1;
		$codeLiv = "$numauto/$magasin";

		//Data
		$_SESSION['DATA_LVR']=array(
		'exercice'=>$exercice,
		'datelivraison'=>$datelivraison,
		'commande'=>$commande,
		'libcommande'=>$libcommande,
		'fournisseur'=>$fournisseur,
		'idfournisseur'=>$idfournisseur,
		'reflivraison'=>$reflivraison,
		'libelle'=>$libelle,
		'nbreLigne'=>$nbreLigne
		);

		if($commande != ''){
			//Insert
			$sql  = "INSERT INTO `livraison` (`CODE_LIVRAISON`, `CODE_COMMANDE`, `CODE_FOUR`, `CODE_MAGASIN`, `ID_EXERCICE`,
			`REF_LIVRAISON`, `ID_LIVRAISON`, `LVR_LIBELLE`, `LVR_DATE`, `LVR_VALIDE`, `LVR_DATEVALID`)
			VALUES ('".addslashes($codeLiv)."', '".addslashes($commande)."', '".addslashes($idfournisseur)."',
			'".addslashes($magasin)."', '".addslashes($exercice)."', '".addslashes($reflivraison)."', '".addslashes($numauto)."',
			 '".addslashes($libelle)."', '".addslashes($datelivraison)."' , '$statut','".date('Y-m-d H:i:s')."');";
		}
		else {
			//Insert
			$sql  = "INSERT INTO `livraison` (`CODE_LIVRAISON`, `CODE_COMMANDE`, `CODE_FOUR`, `CODE_MAGASIN`, `ID_EXERCICE`,
			`REF_LIVRAISON`, `ID_LIVRAISON`, `LVR_LIBELLE`, `LVR_DATE`, `LVR_VALIDE`, `LVR_DATEVALID`)
			 VALUES ('".addslashes($codeLiv)."',  NULL, '".addslashes($idfournisseur)."', '".addslashes($magasin)."',
			 '".addslashes($exercice)."', '".addslashes($reflivraison)."', '".addslashes($numauto)."', '".addslashes($libelle)."',
			 '".addslashes($datelivraison)."' , '$statut','".date('Y-m-d H:i:s')."');";
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
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Ajout d'une livraison ($codeLiv, commande n° $commande)"); //updateLog($username, $idcust, $action='' )

		//Data
		$_SESSION['DATA_LVR']['exercice']=$exercice;
		$_SESSION['DATA_LVR']['datelivraison']=$datelivraison;

		//Collect Data
		$sql1 ="";
		$sql2 ="";

		$numautoDetLiv = myDbLastId('detlivraison', 'ID_DETLIVRAISON', $magasin);
		$numautoMvt = myDbLastId('mouvement', 'ID_MOUVEMENT', $magasin);

		$_SESSION['DATA_LVR']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_LVR']['nbreLigne']; $i++){
			(isset($_POST['code_detlivraison'.$i]) && $_POST['code_detlivraison'.$i]!='' 	? $code_detlivraison 	= $_POST['code_detlivraison'.$i] 	: $code_detlivraison 	= '');
			(isset($_POST['codeproduit'.$i]) && $_POST['codeproduit'.$i]!='' 				? $codeproduit 			= $_POST['codeproduit'.$i] 			: $codeproduit 			= '');
			(isset($_POST['oldcodeproduit'.$i]) && $_POST['oldcodeproduit'.$i] !=''			? $oldcodeproduit 		= $_POST['oldcodeproduit'.$i] 		: $oldcodeproduit 		= '');
			(isset($_POST['produit'.$i])  && $_POST['produit'.$i]!=''						? $produit 				= $_POST['produit'.$i] 				: $produit 				= '');
			(isset($_POST['qte'.$i])  && $_POST['qte'.$i]!=''								? $qte 					= $_POST['qte'.$i] 					: $qte 					= '');
			(isset($_POST['qtelvr'.$i])  && $_POST['qtelvr'.$i]!=''							? $qtelvr 				= $_POST['qtelvr'.$i] 				: $qtelvr 				= '');
			(isset($_POST['unite'.$i])  && $_POST['unite'.$i]!=''							? $unite 				= $_POST['unite'.$i] 				: $unite 				= '');
			(isset($_POST['prix'.$i])  && $_POST['prix'.$i]!=''								? $prix 				= $_POST['prix'.$i] 				: $prix 				= '');
			(isset($_POST['reflot'.$i]) && $_POST['reflot'.$i]!='' 							? $reflot				= $_POST['reflot'.$i] 				: $reflot 				= '');
			(isset($_POST['dateperemp'.$i]) && $_POST['dateperemp'.$i] !=''					? $dateperemp 			= $_POST['dateperemp'.$i] 			: $dateperemp 			= '');

			$dateperemp = preg_replace('[\/]','-',$dateperemp);

			if($codeproduit!='' && $produit!=''  && $qtelvr!='') {
				if($commande != ''){

					$numautoDetLiv = $numautoDetLiv+1;
					$codeDetLiv = "$numautoDetLiv/$magasin";
					$monlot= "LOT/$numautoDetLiv/$i";

					$sql1 .="INSERT INTO `detlivraison` (`CODE_DETLIVRAISON`, `CODE_PRODUIT`, `CODE_LIVRAISON`, `CODE_MAGASIN`, `ID_DETLIVRAISON`, `LVR_PRDQTE`,
					`LVR_PRDRECU`,  `LVR_UNITE`, `LVR_IDCOMMANDE`, `LVR_MAGASIN`, `LVR_PA`, `LVR_REFLOT`, `LVR_DATEPEREMP`, `LVR_MONLOT`)
					VALUES ('".addslashes($codeDetLiv)."',  '".addslashes($codeproduit)."',  '".addslashes($codeLiv)."', '".addslashes($magasin)."','".addslashes($numautoDetLiv)."',
					'".addslashes($qte)."' , '".addslashes($qtelvr)."', '".addslashes($unite)."', '".addslashes($commande)."','".addslashes($magasin)."',
					'".addslashes($prix)."', '".addslashes($reflot)."', '".addslashes(mysqlFormat($dateperemp))."', '".addslashes($monlot)."'); ";
				}
				else{

					$numautoDetLiv = $numautoDetLiv+1;
					$codeDetLiv = "$numautoDetLiv/$magasin";
					$monlot= "LOT/$numautoDetLiv/$i";

					$sql1 .="INSERT INTO `detlivraison` (`CODE_DETLIVRAISON`, `CODE_PRODUIT`, `CODE_LIVRAISON`, `CODE_MAGASIN`, `ID_DETLIVRAISON`, `LVR_PRDQTE`,
					`LVR_PRDRECU`,  `LVR_UNITE`, `LVR_IDCOMMANDE`, `LVR_MAGASIN`, `LVR_PA`, `LVR_REFLOT`, `LVR_DATEPEREMP`, `LVR_MONLOT`)
					VALUES ('".addslashes($codeDetLiv)."',  '".addslashes($codeproduit)."',  '".addslashes($codeLiv)."', '".addslashes($magasin)."', '".addslashes($numautoDetLiv)."',
					'".addslashes($qte)."' , '".addslashes($qtelvr)."', '".addslashes($unite)."', '".addslashes($commande)."','".addslashes($magasin)."',
					'".addslashes($prix)."', '".addslashes($reflot)."', '".addslashes(mysqlFormat($dateperemp))."', '".addslashes($monlot)."'); ";
				}

				$numautoMvt = $numautoMvt+1;
				$codeMvt = "$numautoMvt/$magasin";

				$sql2 .="INSERT INTO `mouvement` (`CODE_MOUVEMENT`, `ID_EXERCICE`, `CODE_PRODUIT`, `CODE_MAGASIN`, `ID_MOUVEMENT`, `ID_SOURCE`,
				`MVT_DATE`, `MVT_TIME`, `MVT_QUANTITE`, `MVT_UNITE`, `MVT_NATURE`, `MVT_VALID`, `MVT_DATEVALID`, `MVT_TYPE`, `MVT_REFLOT`,
				`MVT_DATEPEREMP`,  `MVT_PA`,  `MVT_MONLOT`)
				VALUES ('".addslashes($codeMvt)."',  '".addslashes($exercice)."','".addslashes($codeproduit)."',	'".addslashes($magasin)."',
				'".addslashes($numautoMvt)."', '".addslashes($codeLiv)."', '".addslashes($datelivraison)."' ,'".addslashes(date('H:i:s'))."' ,
				'".addslashes($qtelvr)."' ,	'".addslashes($unite)."', 'LIVRAISON', '$statut', '".date('Y-m-d H:i:s')."','E','".addslashes($reflot)."',
				'".addslashes(mysqlFormat($dateperemp))."', '".addslashes($prix)."', '".addslashes($monlot)."') ; ";
			}
		}

		if (($sql1 !='')) {
			$query =  $cnx->prepare($sql1); //Prepare the SQL
			$query->execute(); //Execute prepared SQL =>
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Ajout des lignes de livraison($codeLiv, commande n°$commande)"); //updateLog($username, $idcust, $action='' )

			$query =  $cnx->prepare($sql2); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Ajout d'un mouvement($codeLiv, commande n°$commande)"); //updateLog($username, $idcust, $action='' )
		}
		//echo  $sql, '<br>',$sql1, '<br>', $sql2;
		unset($_SESSION['DATA_LVR']);
		header('location:livraison.php?selectedTab=bde&rs=1');
		break;

	case 'update':
		(isset($_POST['xid']) && $_POST['xid']!=''						? $xid 			= trim($_POST['xid']) 			: $xid = '');
		(isset($_POST['exercice']) && $_POST['exercice']!=''			? $exercice 	= trim($_POST['exercice']) 		: $exercice = '');
		(isset($_POST['datelivraison']) && $_POST['datelivraison']!=''  ? $datelivraison = trim($_POST['datelivraison']): $datelivraison = '');
		(isset($_POST['libelle']) && $_POST['libelle']!='' 				? $libelle 		= trim($_POST['libelle']) 		: $libelle 			= '');
		(isset($_POST['commande']) && $_POST['commande']!=''  			? $commande 		= trim($_POST['commande']) 			: $commande 		= '');
		(isset($_POST['libcommande']) && $_POST['libcommande']!=''  	? $libcommande 		= trim($_POST['libcommande']) 		: $libcommande 		= '');
		(isset($_POST['idfournisseur']) && $_POST['idfournisseur']!=''  ? $idfournisseur 	= trim($_POST['idfournisseur']) 	: $idfournisseur 	= '');
		(isset($_POST['fournisseur']) && $_POST['fournisseur']!=''  	? $fournisseur 		= trim($_POST['fournisseur']) 		: $fournisseur	 	= '');
		(isset($_POST['reflivraison']) && $_POST['reflivraison']!=''  	? $reflivraison = trim($_POST['reflivraison']) : $reflivraison = '');
		(isset($_POST['statut']) && $_POST['statut']!=''  				? $statut 		= trim($_POST['statut']) 		: $statut = '');

		$datelivraison = mysqlFormat($datelivraison);
		$magasin=$_SESSION['GL_USER']['MAGASIN'];
		$exercice =  $_SESSION['GL_USER']['EXERCICE'];
		//$statut = 1;

		if($commande != ''){
			//Insert
			$sql  = "UPDATE `livraison` SET `ID_EXERCICE`='".addslashes($exercice)."' ,`REF_LIVRAISON`='".addslashes($reflivraison)."',
			CODE_FOUR= '".addslashes($idfournisseur)."', `CODE_COMMANDE`='".addslashes($commande)."' ,`LVR_LIBELLE`='".addslashes($libelle)."',
			`LVR_DATE`='".addslashes($datelivraison)."' ,`LVR_VALIDE`='".addslashes($statut)."', LVR_DATEVALID='".date('Y-m-d H:i:s')."'
			WHERE CODE_LIVRAISON LIKE '".addslashes($xid)."';";
		}
		else {
			$sql  = "UPDATE `livraison` SET `ID_EXERCICE`='".addslashes($exercice)."' ,`REF_LIVRAISON`='".addslashes($reflivraison)."',
			CODE_FOUR='".addslashes($idfournisseur)."', `CODE_COMMANDE`=NULL ,`LVR_DATE`='".addslashes($datelivraison)."' ,
			`LVR_LIBELLE`='".addslashes($libelle)."', `LVR_VALIDE`='".addslashes($statut)."' WHERE CODE_LIVRAISON LIKE '".addslashes($xid)."';";
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


		$numautoDetLiv = myDbLastId('detlivraison', 'ID_DETLIVRAISON', $magasin);
		$numautoMvt = myDbLastId('mouvement', 'ID_MOUVEMENT', $magasin);

		//Collect Data
		$sql1 ="";
		$sql2 ="";

		$_SESSION['DATA_LVR']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_LVR']['nbreLigne']; $i++){
			(isset($_POST['code_detlivraison'.$i]) && $_POST['code_detlivraison'.$i]!='' 	? $code_detlivraison 	= $_POST['code_detlivraison'.$i] 	: $code_detlivraison 	= '');
			(isset($_POST['monlot'.$i]) && $_POST['monlot'.$i] !=''					? $monlot 			= $_POST['monlot'.$i] 		: $monlot 	= "LOT/$xid/$i");
			(isset($_POST['codeproduit'.$i]) && $_POST['codeproduit'.$i]!='' 		? $codeproduit 	= $_POST['codeproduit'.$i] 		: $codeproduit 	= '');
			(isset($_POST['oldcodeproduit'.$i]) && $_POST['oldcodeproduit'.$i] !=''	? $oldcodeproduit 	= $_POST['oldcodeproduit'.$i] 	: $oldcodeproduit 	= '');
			(isset($_POST['produit'.$i])  && $_POST['produit'.$i]!=''			? $produit 			= $_POST['produit'.$i] 		: $produit 		= '');
			(isset($_POST['qte'.$i])  && $_POST['qte'.$i]!=''					? $qte 				= $_POST['qte'.$i] 			: $qte 			= '');
			(isset($_POST['qtelvr'.$i])  && $_POST['qtelvr'.$i]!=''				? $qtelvr 			= $_POST['qtelvr'.$i] 		: $qtelvr 		= '');
			(isset($_POST['unite'.$i])  && $_POST['unite'.$i]!=''				? $unite 			= $_POST['unite'.$i] 		: $unite 		= '');
			(isset($_POST['prix'.$i])  && $_POST['prix'.$i]!=''					? $prix 			= $_POST['prix'.$i] 		: $prix 		= '');
			(isset($_POST['reflot'.$i]) && $_POST['reflot'.$i]!='' 				? $reflot			= $_POST['reflot'.$i] 		: $reflot 		= '');
			(isset($_POST['dateperemp'.$i]) && $_POST['dateperemp'.$i] 			? $dateperemp 		= $_POST['dateperemp'.$i] 	: $dateperemp 	= '');

			$dateperemp = preg_replace('[\/\-]','-',$dateperemp);

			if($code_detlivraison!='' && $oldcodeproduit!='' && $codeproduit!='' && $produit!='' && $qtelvr!='') {
				if($commande != ''){
					$sql1 .="UPDATE `detlivraison` SET `CODE_PRODUIT`='".addslashes($codeproduit)."' ,`LVR_PRDQTE`='".addslashes($qte)."' ,
					`LVR_PRDRECU`='".addslashes($qtelvr)."', `LVR_UNITE`='".addslashes($unite)."', `LVR_PA`='".addslashes($prix)."', `LVR_MAGASIN`='".addslashes($magasin)."',
					`LVR_IDCOMMANDE`='".addslashes($commande)."', LVR_REFLOT='".addslashes($reflot)."', LVR_DATEPEREMP= '".addslashes(mysqlFormat($dateperemp))."',
					LVR_MONLOT='".addslashes($monlot)."' WHERE `CODE_PRODUIT`='".addslashes($oldcodeproduit)."'
					AND CODE_LIVRAISON LIKE '".addslashes($xid)."' AND CODE_DETLIVRAISON LIKE '".addslashes($code_detlivraison)."'; ";
				}
				else{
					$sql1 .="UPDATE `detlivraison` SET `CODE_PRODUIT`='".addslashes($codeproduit)."' ,`LVR_PRDQTE`='".addslashes($qte)."' ,
					`LVR_PRDRECU`='".addslashes($qtelvr)."', `LVR_UNITE`='".addslashes($unite)."', `LVR_PA`='".addslashes($prix)."', `LVR_MAGASIN`='".addslashes($magasin)."',
					`LVR_IDCOMMANDE`=NULL , LVR_REFLOT='".addslashes($reflot)."', LVR_DATEPEREMP= '".addslashes(mysqlFormat($dateperemp))."', LVR_MONLOT='".addslashes($monlot)."'
					WHERE `CODE_PRODUIT`='".addslashes($oldcodeproduit)."' AND CODE_LIVRAISON LIKE '".addslashes($xid)."' AND CODE_DETLIVRAISON LIKE '".addslashes($code_detlivraison)."';";
				}

				$sql2 .="UPDATE `mouvement` SET `CODE_PRODUIT`='".addslashes($codeproduit)."' ,`ID_EXERCICE`='".addslashes($exercice)."' ,`CODE_MAGASIN`='".addslashes($magasin)."' ,
				`MVT_DATE`='".addslashes($datelivraison)."' ,`MVT_TIME`='".addslashes(date('H:i:s'))."' ,`MVT_QUANTITE`='".addslashes($qtelvr)."' ,`MVT_UNITE`='".addslashes($unite)."',
				`MVT_PA`='".addslashes($prix)."', `MVT_VALID`='$statut', `MVT_TYPE`='E', MVT_REFLOT='".addslashes($reflot)."', MVT_DATEPEREMP= '".addslashes(mysqlFormat($dateperemp))."',  MVT_MONLOT='".addslashes($monlot)."'
				WHERE `CODE_PRODUIT`='".addslashes($oldcodeproduit)."' AND `MVT_NATURE`='LIVRAISON' AND ID_SOURCE LIKE '".addslashes($xid)."' AND `MVT_TYPE`='E'; ";

			}
			elseif($code_detlivraison=='' && $oldcodeproduit=='' && $codeproduit!='' && $produit!='' && $qtelvr!='') {

				if($commande != ''){
					$numautoDetLiv++;
					$codeDetLiv = "$numautoDetLiv/$magasin";
					$monlot= "LOT/$numautoDetLiv/$i";

					$sql1 .="INSERT INTO `detlivraison` (`CODE_DETLIVRAISON`, `CODE_PRODUIT`, `CODE_LIVRAISON`, `CODE_MAGASIN`, `ID_DETLIVRAISON`, `LVR_PRDQTE`,
					`LVR_PRDRECU`,  `LVR_UNITE`, `LVR_IDCOMMANDE`, `LVR_MAGASIN`, `LVR_PA`, `LVR_REFLOT`, `LVR_DATEPEREMP`, `LVR_MONLOT`)
					VALUES ('".addslashes($codeDetLiv)."',  '".addslashes($codeproduit)."',  '".addslashes($xid)."', '".addslashes($magasin)."', '".addslashes($numautoDetLiv)."',
					'".addslashes($qte)."' , '".addslashes($qtelvr)."', '".addslashes($unite)."', '".addslashes($commande)."','".addslashes($magasin)."',
					'".addslashes($prix)."', '".addslashes($reflot)."', '".addslashes(mysqlFormat($dateperemp))."', '".addslashes($monlot)."'); ";

				}
				else{
					$numautoDetLiv++;
					$codeDetLiv = "$numautoDetLiv/$magasin";
					$monlot= "LOT/$numautoDetLiv/$i";

					$sql1 .="INSERT INTO `detlivraison` (`CODE_DETLIVRAISON`, `CODE_PRODUIT`, `CODE_LIVRAISON`, `CODE_MAGASIN`, `ID_DETLIVRAISON`, `LVR_PRDQTE`,
					`LVR_PRDRECU`,  `LVR_UNITE`, `LVR_IDCOMMANDE`, `LVR_MAGASIN`, `LVR_PA`, `LVR_REFLOT`, `LVR_DATEPEREMP`, `LVR_MONLOT`)
					VALUES ('".addslashes($codeDetLiv)."',  '".addslashes($codeproduit)."',  '".addslashes($xid)."', '".addslashes($magasin)."', '".addslashes($numautoDetLiv)."',
					'".addslashes($qte)."' , '".addslashes($qtelvr)."', '".addslashes($unite)."', '".addslashes($commande)."','".addslashes($magasin)."',
					'".addslashes($prix)."', '".addslashes($reflot)."', '".addslashes(mysqlFormat($dateperemp))."', '".addslashes($monlot)."'); ";
				}

				$numautoMvt++;
				$numautoDetLiv = myDbLastId('detlivraison', 'ID_DETLIVRAISON', $magasin);
				$codeMvt = "$numautoMvt/$magasin";

				$sql2 .="INSERT INTO `mouvement` (`CODE_MOUVEMENT`, `ID_EXERCICE`, `CODE_PRODUIT`, `CODE_MAGASIN`, `ID_MOUVEMENT`, `ID_SOURCE`,
				`MVT_DATE`, `MVT_TIME`, `MVT_QUANTITE`, `MVT_UNITE`, `MVT_NATURE`, `MVT_VALID`, `MVT_DATEVALID`, `MVT_TYPE`, `MVT_REFLOT`,
				`MVT_DATEPEREMP`,  `MVT_PA`,  `MVT_MONLOT`)
				VALUES ('".addslashes($codeMvt)."',  '".addslashes($exercice)."','".addslashes($codeproduit)."',	'".addslashes($magasin)."',
				'".addslashes($numautoMvt)."', '".addslashes($xid)."', '".addslashes($datelivraison)."' ,'".addslashes(date('H:i:s'))."' ,
				'".addslashes($qtelvr)."' ,	'".addslashes($unite)."', 'LIVRAISON', '$statut', '".date('Y-m-d H:i:s')."','E','".addslashes($reflot)."',
				'".addslashes(mysqlFormat($dateperemp))."', '".addslashes($prix)."', '".addslashes($monlot)."') ; ";
			}
		}

		if (($sql1 !='')) {
			$query =  $cnx->prepare($sql1); //Prepare the SQL
			$query->execute(); //Execute prepared SQL =>
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Modification des lignes de livraison('.$xid.', Livraison n°'.$reflivraison.')'); //updateLog($username, $idcust, $action='' )

			$query =  $cnx->prepare($sql2); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Modification d'un mouvement(".$xid.', livraison n°'.$reflivraison.')'); //updateLog($username, $idcust, $action='' )
		}
		//echo $sql1, $sql2;
		unset($_SESSION['DATA_LVR']);
		header('location:livraison.php?selectedTab=bde&rs=2');
		break;

	case 'validate':
		(isset($_POST['xid']) && $_POST['xid']!=''						? $xid 				= trim($_POST['xid']) 			: $xid 			= '');
		(isset($_POST['reflivraison']) && $_POST['reflivraison']!='' 	? $reflivraison 	= trim($_POST['reflivraison']) 	: $reflivraison = '');
		(isset($_POST['commande']) && $_POST['commande']!='0'  			? $commande 		= trim($_POST['commande']) 		: $commande 	= '');

		//Insert
		$sql  = "UPDATE `livraison` SET `LVR_VALIDE`='1', `LVR_DATEVALID`='".date('Y-m-d H:i:s')."' WHERE CODE_LIVRAISON LIKE '".addslashes($xid)."'";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Validation d'une livraison (".$xid.', Livraison n°'.$reflivraison.')'); //updateLog($username, $idcust, $action='' )

		//Collect Data
		$sql1 ="";
		for($i=1; $i<=$_SESSION['DATA_LVR']['nbreLigne']; $i++){
			(isset($_POST['code_detlivraison'.$i]) && $_POST['code_detlivraison'.$i] 	? $code_detlivraison 	= $_POST['code_detlivraison'.$i] 	: $code_detlivraison 	= '');
			(isset($_POST['monlot'.$i]) && $_POST['monlot'.$i] 					? $monlot 			= $_POST['monlot'.$i] 			: $monlot 	= '');
			(isset($_POST['codeproduit'.$i]) && $_POST['codeproduit'.$i] 		? $codeproduit 		= $_POST['codeproduit'.$i] 		: $codeproduit 	= '');
			(isset($_POST['oldcodeproduit'.$i]) && $_POST['oldcodeproduit'.$i] 	? $oldcodeproduit 	= $_POST['oldcodeproduit'.$i] 	: $oldcodeproduit 	= '');
			(isset($_POST['produit'.$i])  && $_POST['produit'.$i]!=''			? $produit 			= $_POST['produit'.$i] 			: $produit 		= '');
			(isset($_POST['qte'.$i])  && $_POST['qte'.$i]!=''					? $qte 				= $_POST['qte'.$i] 				: $qte 			= '');
			(isset($_POST['qtelvr'.$i])  && $_POST['qtelvr'.$i]!=''				? $qtelvr 			= $_POST['qtelvr'.$i] 			: $qtelvr 		= '');
			(isset($_POST['unite'.$i])  && $_POST['unite'.$i]!=''				? $unite 			= $_POST['unite'.$i] 			: $unite 		= '');
			(isset($_POST['prix'.$i])  && $_POST['prix'.$i]!=''					? $prix 			= $_POST['prix'.$i] 			: $prix 		= '');
			(isset($_POST['reflot'.$i]) && $_POST['reflot'.$i]!='' 				? $reflot			= $_POST['reflot'.$i] 			: $reflot 		= '');
			(isset($_POST['dateperemp'.$i]) && $_POST['dateperemp'.$i] 			? $dateperemp 		= $_POST['dateperemp'.$i] 		: $dateperemp 	= '');

			if($code_detlivraison != '' && $codeproduit!='' && $produit!='' && $qtelvr!='') {
				$sql1 .="UPDATE `mouvement` SET  `MVT_VALID`='1', `MVT_TYPE`='E', `MVT_DATEVALID`='".date('Y-m-d H:i:s')."'
				WHERE `CODE_PRODUIT`='".addslashes($codeproduit)."' AND `MVT_NATURE`='LIVRAISON' AND `MVT_TYPE`='E'
				AND ID_SOURCE LIKE '".addslashes($xid)."' AND MVT_MONLOT='".addslashes($monlot)."';";
			}
		}

		if (($sql1 !='')) {
			$query =  $cnx->prepare($sql1); //Prepare the SQL
			$query->execute(); //Execute prepared SQL =>
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Validation d'un mouvement(".$xid.', commande n°'.$commande.')'); //updateLog($username, $idcust, $action='' )
		}
		//echo $sql1, $sql2;
		unset($_SESSION['DATA_LVR']);
		header('location:livraison.php?selectedTab=bde&rs=3');
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
		$sql = "SELECT * FROM  `livraison` WHERE CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND `CODE_LIVRAISON` LIKE '".addslashes($id)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data  CDE_STATUT
		$_SESSION['DATA_LVR']=array(
		'xid'=>$row['CODE_LIVRAISON'],
		'exercice'=>$row['ID_EXERCICE'],
		'datelivraison'=>frFormat2($row['LVR_DATE']),
		'reflivraison'=>stripslashes($row['REF_LIVRAISON']),
		'commande'=>stripslashes($row['CODE_COMMANDE']),
		'libcommande'=>getField('CODE_COMMANDE', $row['CODE_COMMANDE'], 'CDE_LIBELLE', 'commande'), // $libcommande,
		'fournisseur'=>getField('CODE_FOUR', $row['CODE_FOUR'], 'FOUR_NOM', 'fournisseur'), //$fournisseur,
		'idfournisseur'=>$row['CODE_FOUR'],
		'libelle'=>stripslashes($row['LVR_LIBELLE']),
		'statut'=>$row['LVR_VALIDE'],
		'nbreLigne'=>0
		);

		//LIGNES COMMANDE
		$sql = "SELECT * FROM `detlivraison` INNER JOIN produit ON (detlivraison.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
		WHERE CODE_LIVRAISON LIKE '".addslashes($id)."' ORDER BY detlivraison.ID_DETLIVRAISON ASC";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_LVR']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_LVR']['ligne'], array('code_detlivraison'=>$row['ID_DETLIVRAISON'],'monlot'=>$row['LVR_MONLOT'], 'codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']), 'qte'=>$row['LVR_PRDQTE'], 'qtelvr'=>$row['LVR_PRDRECU'], 'unite'=>$row['LVR_UNITE'], 'magasin'=>$row['LVR_MAGASIN'],'prix'=>$row['LVR_PA'],'reflot'=>$row['LVR_REFLOT'],'dateperemp'=>preg_replace('[-]','/',frFormat2($row['LVR_DATEPEREMP']))));
		}
		$_SESSION['DATA_LVR']['nbreLigne'] = $query->rowCount();

		if($_SESSION['DATA_LVR']['commande']!=''){
			header('location:detaillivraison1.php?selectedTab=bde&rst=1');
		}
		else{
			header('location:detaillivraison2.php?selectedTab=bde&rst=1');
		}
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
		$sql = "SELECT * FROM  `livraison` WHERE CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND `CODE_LIVRAISON` LIKE '".addslashes($id)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data  CDE_STATUT
		$_SESSION['DATA_LVR']=array(
		'xid'=>$row['CODE_LIVRAISON'],
		'exercice'=>$row['ID_EXERCICE'],
		'datelivraison'=>frFormat2($row['LVR_DATE']),
		'reflivraison'=>stripslashes($row['REF_LIVRAISON']),
		'commande'=>stripslashes($row['CODE_COMMANDE']),
		'libcommande'=>getField('CODE_COMMANDE', $row['CODE_COMMANDE'], 'CDE_LIBELLE', 'commande'), // $libcommande,
		'fournisseur'=>getField('CODE_FOUR', $row['CODE_FOUR'], 'FOUR_NOM', 'fournisseur'), //$fournisseur,
		'idfournisseur'=>$row['CODE_FOUR'],
		'libelle'=>stripslashes($row['LVR_LIBELLE']),
		'statut'=>$row['LVR_VALIDE'],
		'nbreLigne'=>0,
		'ligne'=>array(),
		'journal'=>array()
		);

		//LIGNES COMMANDE
		$sql = "SELECT * FROM `detlivraison` INNER JOIN produit ON (detlivraison.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
		WHERE CODE_LIVRAISON LIKE '".addslashes($id)."' ORDER BY detlivraison.CODE_PRODUIT ASC";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_LVR']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_LVR']['ligne'], array('code_detlivraison'=>$row['ID_DETLIVRAISON'],'monlot'=>$row['LVR_MONLOT'],'codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']), 'qte'=>$row['LVR_PRDQTE'], 'qtelvr'=>$row['LVR_PRDRECU'], 'unite'=>$row['LVR_UNITE'], 'magasin'=>$row['LVR_MAGASIN'],'prix'=>$row['LVR_PA'],'reflot'=>$row['LVR_REFLOT'],'dateperemp'=>preg_replace('[-]','/',frFormat2($row['LVR_DATEPEREMP']))));
		}
		$_SESSION['DATA_LVR']['nbreLigne'] = $query->rowCount();


		//LIGNES MOUVEMENT
		$sql = "SELECT * FROM `mouvement` INNER JOIN produit ON (mouvement.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
		WHERE MVT_NATURE LIKE 'LIVRAISON' AND ID_SOURCE LIKE '".addslashes($id)."' ORDER BY mouvement.CODE_PRODUIT ASC";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_LVR']['journal'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_LVR']['journal'], array('codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']), 'qte'=>$row['MVT_QUANTITE'], 'qtelvr'=>$row['MVT_QUANTITE'], 'unite'=>$row['MVT_UNITE'], 'valide'=>$row['MVT_VALID'],'prix'=>$row['MVT_PA'],'reflot'=>$row['MVT_REFLOT'],'dateperemp'=>preg_replace('[-]','/',frFormat2($row['MVT_DATEPEREMP']))));
		}

		$_SESSION['DATA_LVR']['nbreLigne2'] = $query->rowCount();

		if($_SESSION['DATA_LVR']['commande']!=''){
			header('location:journallivraison1.php?selectedTab=bde&rst=1');
		}
		else{
			header('location:journallivraison2.php?selectedTab=bde&rst=1');
		}

		break;

	case 'check':
		$msg = "";
		(isset($_POST['code']) && $_POST['code']!='' 		? $code = trim($_POST['code']) 	: $code = '');
		if($code !=''){
			$sql = "SELECT COUNT(CODE_LIVRAISON) AS NBRE FROM  `livraison` WHERE `REF_LIVRAISON` LIKE '".addslashes($code)."'";
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

	case 'addline':
		(isset($_POST['exercice']) && $_POST['exercice']!=''			? $exercice 		= trim($_POST['exercice']) 		: $exercice 		= '');
		(isset($_POST['datelivraison']) && $_POST['datelivraison']!=''  ? $datelivraison 	= trim($_POST['datelivraison']) : $datelivraison 	= '');
		(isset($_POST['commande']) && $_POST['commande']!=''  			? $commande 		= trim($_POST['commande']) 		: $commande 		= '');
		(isset($_POST['libcommande']) && $_POST['libcommande']!=''  	? $libcommande 		= trim($_POST['libcommande']) 	: $libcommande 		= '');
		(isset($_POST['idfournisseur']) && $_POST['idfournisseur']!=''  ? $idfournisseur 	= trim($_POST['idfournisseur']) : $idfournisseur 	= '');
		(isset($_POST['fournisseur']) && $_POST['fournisseur']!=''  	? $fournisseur 		= trim($_POST['fournisseur']) 	: $fournisseur	 	= '');
		(isset($_POST['reflivraison']) && $_POST['reflivraison']!=''  	? $reflivraison 	= trim($_POST['reflivraison'])	: $reflivraison 	= '');
		(isset($_POST['libelle']) && $_POST['libelle']!='' 				? $libelle 			= trim($_POST['libelle']) 		: $libelle 			= '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 		= trim($_POST['nbreLigne']) 	: $nbreLigne 		= '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 			= trim($_POST['statut']) 		: $statut 			= '0');
		(isset($_POST['xid']) && $_POST['xid']!=''  					? $xid 				= trim($_POST['xid']) 			: $xid= '');

		$magasin=$_SESSION['GL_USER']['MAGASIN'];
		$exercice =  $_SESSION['GL_USER']['EXERCICE'];

		//Data
		$_SESSION['DATA_LVR']=array(
		'xid'=>$xid,
		'exercice'=>$exercice,
		'datelivraison'=>$datelivraison,
		'commande'=>$commande,
		'libcommande'=>$libcommande,
		'fournisseur'=>$fournisseur,
		'idfournisseur'=>$idfournisseur,
		'reflivraison'=>$reflivraison,
		'libelle'=>$libelle,
		'nbreLigne'=>$nbreLigne
		);

		//Collect Data
		$moins=0;
		$_SESSION['DATA_LVR']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_LVR']['nbreLigne']; $i++){
			(isset($_POST['code_detlivraison'.$i])	? $code_detlivraison= $_POST['code_detlivraison'.$i] 	: $code_detlivraison 	= '');
			(isset($_POST['monlot'.$i])				? $monlot 			= $_POST['monlot'.$i] 	: $monlot 	= '');
			(isset($_POST['codeproduit'.$i])		? $codeproduit 		= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['oldcodeproduit'.$i])		? $oldcodeproduit 	= $_POST['oldcodeproduit'.$i] 	: $oldcodeproduit 	= '');
			(isset($_POST['produit'.$i]) 			? $produit 			= $_POST['produit'.$i] 		: $produit 		= '');
			(isset($_POST['qte'.$i]) 				? $qte 				= $_POST['qte'.$i] 			: $qte 			= '');
			(isset($_POST['qtelvr'.$i]) 			? $qtelvr 			= $_POST['qtelvr'.$i] 		: $qtelvr 		= '');
			(isset($_POST['unite'.$i]) 				? $unite 			= $_POST['unite'.$i] 		: $unite 		= '');
			(isset($_POST['prix'.$i]) 				? $prix 			= $_POST['prix'.$i] 		: $prix 		= '');
			(isset($_POST['reflot'.$i]) 			? $reflot			= $_POST['reflot'.$i] 		: $reflot 		= '');
			(isset($_POST['dateperemp'.$i]) 		? $dateperemp 		= $_POST['dateperemp'.$i] 		: $dateperemp 		= '');
			(isset($_POST['idfournisseur']) && $_POST['idfournisseur']!=''  ? $idfournisseur 	= trim($_POST['idfournisseur']) : $idfournisseur 	= '');
			(isset($_POST['fournisseur']) && $_POST['fournisseur']!=''  	? $fournisseur 		= trim($_POST['fournisseur']) 	: $fournisseur	 	= '');

			//Check if exite de produit
			//$prdIndex = isExitePrd($codeproduit, $_SESSION['DATA_LVR']['ligne']);
			//if($prdIndex != -1){
			//$_SESSION['DATA_LVR']['ligne'][$prdIndex]['qte'] += $qte; $moins++;	}
			//else{//Add to list
			if($codeproduit!='' && $produit!='' && $qtelvr!='') array_push($_SESSION['DATA_LVR']['ligne'], array('code_detlivraison'=>$code_detlivraison, 'monlot'=>$monlot,'codeproduit'=>$codeproduit, 'produit'=>$produit,'qte'=>$qte, 'qtelvr'=>$qtelvr, 'unite'=>$unite, 'prix'=>$prix, 'reflot'=>$reflot, 'dateperemp'=>$dateperemp));
			//}
		}

		//Add line
		$_SESSION['DATA_LVR']['nbreLigne'] +=1;
		$_SESSION['DATA_LVR']['nbreLigne'] -=$moins;
		if($commande!=''){
			header('location:addlivraison1.php?selectedTab=bde');
		}
		else{
			header('location:addlivraison2.php?selectedTab=bde');
		}
		break;

	case 'addline1':
		(isset($_POST['exercice']) && $_POST['exercice']!=''			? $exercice 		= trim($_POST['exercice']) 		: $exercice 		= '');
		(isset($_POST['datelivraison']) && $_POST['datelivraison']!=''  ? $datelivraison 	= trim($_POST['datelivraison']) : $datelivraison 	= '');
		(isset($_POST['commande']) && $_POST['commande']!=''  			? $commande 		= trim($_POST['commande']) 		: $commande 		= '');
		(isset($_POST['libcommande']) && $_POST['libcommande']!=''  	? $libcommande 		= trim($_POST['libcommande']) 	: $libcommande 		= '');
		(isset($_POST['idfournisseur']) && $_POST['idfournisseur']!=''  ? $idfournisseur 	= trim($_POST['idfournisseur']) : $idfournisseur 	= '');
		(isset($_POST['fournisseur']) && $_POST['fournisseur']!=''  	? $fournisseur 		= trim($_POST['fournisseur']) 	: $fournisseur	 	= '');
		(isset($_POST['reflivraison']) && $_POST['reflivraison']!=''  	? $reflivraison 	= trim($_POST['reflivraison'])	: $reflivraison 	= '');
		(isset($_POST['libelle']) && $_POST['libelle']!='' 				? $libelle 			= trim($_POST['libelle']) 		: $libelle 			= '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 		= trim($_POST['nbreLigne']) 	: $nbreLigne 		= '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 			= trim($_POST['statut']) 		: $statut 			= '0');
		(isset($_POST['xid']) && $_POST['xid']!=''  					? $xid 				= trim($_POST['xid']) 			: $xid= '');

		$magasin=$_SESSION['GL_USER']['MAGASIN'];
		$exercice =  $_SESSION['GL_USER']['EXERCICE'];

		//Data
		$_SESSION['DATA_LVR']=array(
		'xid'=>$xid,
		'exercice'=>$exercice,
		'datelivraison'=>$datelivraison,
		'commande'=>$commande,
		'libcommande'=>$libcommande,
		'fournisseur'=>$fournisseur,
		'idfournisseur'=>$idfournisseur,
		'reflivraison'=>$reflivraison,
		'libelle'=>$libelle,
		'nbreLigne'=>$nbreLigne
		);

		//Collect Data
		$moins=0;
		$_SESSION['DATA_LVR']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_LVR']['nbreLigne']; $i++){
			(isset($_POST['code_detlivraison'.$i])	? $code_detlivraison 	= $_POST['code_detlivraison'.$i] 	: $code_detlivraison 	= '');
			(isset($_POST['monlot'.$i])				? $monlot 				= $_POST['monlot'.$i] 				: $monlot 				= '');
			(isset($_POST['codeproduit'.$i])		? $codeproduit 			= $_POST['codeproduit'.$i] 			: $codeproduit 			= '');
			(isset($_POST['oldcodeproduit'.$i])		? $oldcodeproduit 		= $_POST['oldcodeproduit'.$i] 		: $oldcodeproduit 		= '');
			(isset($_POST['produit'.$i]) 			? $produit 				= $_POST['produit'.$i] 				: $produit 				= '');
			(isset($_POST['qte'.$i]) 				? $qte 					= $_POST['qte'.$i] 					: $qte 					= '');
			(isset($_POST['qtelvr'.$i]) 			? $qtelvr 				= $_POST['qtelvr'.$i] 				: $qtelvr 				= '');
			(isset($_POST['unite'.$i]) 				? $unite 				= $_POST['unite'.$i] 				: $unite 				= '');
			(isset($_POST['prix'.$i]) 				? $prix 				= $_POST['prix'.$i] 				: $prix 				= '');
			(isset($_POST['reflot'.$i]) 			? $reflot				= $_POST['reflot'.$i] 				: $reflot 				= '');
			(isset($_POST['dateperemp'.$i]) 		? $dateperemp 			= $_POST['dateperemp'.$i] 			: $dateperemp 			= '');

			if($codeproduit!='' && $produit!='' && $qtelvr!='') array_push($_SESSION['DATA_LVR']['ligne'], array('code_detlivraison'=>$code_detlivraison,'monlot'=>$monlot, 'codeproduit'=>$codeproduit, 'oldcodeproduit'=>$oldcodeproduit, 'produit'=>$produit, 'qte'=>$qte, 'qtelvr'=>$qtelvr, 'unite'=>$unite, 'prix'=>$prix, 'reflot'=>$reflot, 'dateperemp'=>$dateperemp));
		}
		//Add line
		$_SESSION['DATA_LVR']['nbreLigne'] +=1;
		$_SESSION['DATA_LVR']['nbreLigne'] -=$moins;

		if($commande!=''){
			header('location:editlivraison1.php?selectedTab=bde');
		}
		else{
			header('location:editlivraison2.php?selectedTab=bde');
		}

		break;

	case 'delline':
		(isset($_POST['exercice']) && $_POST['exercice']!=''			? $exercice 		= trim($_POST['exercice']) 		: $exercice 		= '');
		(isset($_POST['datelivraison']) && $_POST['datelivraison']!=''  ? $datelivraison 	= trim($_POST['datelivraison']) : $datelivraison 	= '');
		(isset($_POST['commande']) && $_POST['commande']!=''  			? $commande 		= trim($_POST['commande']) 		: $commande 		= '');
		(isset($_POST['libcommande']) && $_POST['libcommande']!=''  	? $libcommande 		= trim($_POST['libcommande']) 	: $libcommande 		= '');
		(isset($_POST['idfournisseur']) && $_POST['idfournisseur']!=''  ? $idfournisseur 	= trim($_POST['idfournisseur']) : $idfournisseur 	= '');
		(isset($_POST['fournisseur']) && $_POST['fournisseur']!=''  	? $fournisseur 		= trim($_POST['fournisseur']) 	: $fournisseur	 	= '');
		(isset($_POST['reflivraison']) && $_POST['reflivraison']!=''  	? $reflivraison 	= trim($_POST['reflivraison'])	: $reflivraison 	= '');
		(isset($_POST['libelle']) && $_POST['libelle']!='' 				? $libelle 			= trim($_POST['libelle']) 		: $libelle 			= '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 		= trim($_POST['nbreLigne']) 	: $nbreLigne 		= '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 			= trim($_POST['statut']) 		: $statut 			= '0');
		(isset($_POST['xid']) && $_POST['xid']!=''  					? $xid 				= trim($_POST['xid']) 			: $xid= '');

		$supp =0;
		//Data
		$_SESSION['DATA_LVR']=array(
			'xid'=>$xid,
			'exercice'=>$exercice,
			'datelivraison'=>$datelivraison,
			'commande'=>$commande,
			'libcommande'=>$libcommande,
			'fournisseur'=>$fournisseur,
			'idfournisseur'=>$idfournisseur,
			'reflivraison'=>$reflivraison,
			'libelle'=>$libelle,
			'nbreLigne'=>$nbreLigne
			);

		//Collect Data
		$_SESSION['DATA_LVR']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_LVR']['nbreLigne']; $i++){
			(isset($_POST['code_detlivraison'.$i])? $code_detlivraison 	= $_POST['code_detlivraison'.$i] 	: $code_detlivraison 	= '');
			(isset($_POST['monlot'.$i])			? $monlot 		= $_POST['monlot'.$i] 	: $monlot 	= '');
			(isset($_POST['codeproduit'.$i])	? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['oldcodeproduit'.$i])	? $oldcodeproduit 	= $_POST['oldcodeproduit'.$i] 	: $oldcodeproduit 	= '');
			(isset($_POST['produit'.$i]) 		? $produit 		= $_POST['produit'.$i] 		: $produit 		= '');
			(isset($_POST['qte'.$i]) 			? $qte 			= $_POST['qte'.$i] 			: $qte 			= '');
			(isset($_POST['qtelvr'.$i]) 		? $qtelvr 		= $_POST['qtelvr'.$i] 		: $qtelvr 		= '');
			(isset($_POST['unite'.$i]) 			? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');
			(isset($_POST['prix'.$i]) 			? $prix 		= $_POST['prix'.$i] 		: $prix 		= '');
			(isset($_POST['reflot'.$i]) 		? $reflot		= $_POST['reflot'.$i] 		: $reflot 		= '');
			(isset($_POST['dateperemp'.$i]) 	? $dateperemp 	= $_POST['dateperemp'.$i] 	: $dateperemp 	= '');
			(isset($_POST['rowSelection']) && $_POST['rowSelection']!=''  	? $rowSelection = trim($_POST['rowSelection']) 		: $rowSelection 	= $codeproduit);

			//Add to list
			if($codeproduit!='' && $produit!='' && $qtelvr!='' && $rowSelection!=$codeproduit){
				array_push($_SESSION['DATA_LVR']['ligne'], array('code_detlivraison'=>$code_detlivraison,'monlot'=>$monlot,'codeproduit'=>$codeproduit, 'oldcodeproduit'=>$oldcodeproduit, 'produit'=>$produit,  'qte'=>$qte, 'qtelvr'=>$qtelvr, 'prix'=>$prix, 'unite'=>$unite, 'reflot'=>$reflot, 'dateperemp'=>$dateperemp));
			}
			elseif($codeproduit!='' && $produit!='' && $qtelvr!='' && $rowSelection==$codeproduit){$supp++;}
		}
		//Add line
		$_SESSION['DATA_LVR']['nbreLigne'] -=$supp;
		if($commande!=''){
			header('location:addlivraison1.php?selectedTab=bde');
		}
		else{
			header('location:addlivraison2.php?selectedTab=bde');
		}

		break;

	case 'delline1':
		(isset($_POST['exercice']) && $_POST['exercice']!=''			? $exercice 		= trim($_POST['exercice']) 		: $exercice 		= '');
		(isset($_POST['datelivraison']) && $_POST['datelivraison']!=''  ? $datelivraison 	= trim($_POST['datelivraison']) : $datelivraison 	= '');
		(isset($_POST['commande']) && $_POST['commande']!=''  			? $commande 		= trim($_POST['commande']) 		: $commande 		= '');
		(isset($_POST['libcommande']) && $_POST['libcommande']!=''  	? $libcommande 		= trim($_POST['libcommande']) 	: $libcommande 		= '');
		(isset($_POST['idfournisseur']) && $_POST['idfournisseur']!=''  ? $idfournisseur 	= trim($_POST['idfournisseur']) : $idfournisseur 	= '');
		(isset($_POST['fournisseur']) && $_POST['fournisseur']!=''  	? $fournisseur 		= trim($_POST['fournisseur']) 	: $fournisseur	 	= '');
		(isset($_POST['reflivraison']) && $_POST['reflivraison']!=''  	? $reflivraison 	= trim($_POST['reflivraison'])	: $reflivraison 	= '');
		(isset($_POST['libelle']) && $_POST['libelle']!='' 				? $libelle 			= trim($_POST['libelle']) 		: $libelle 			= '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 		= trim($_POST['nbreLigne']) 	: $nbreLigne 		= '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 			= trim($_POST['statut']) 		: $statut 			= '0');
		(isset($_POST['xid']) && $_POST['xid']!=''  					? $xid 				= trim($_POST['xid']) 			: $xid= '');

		$supp =0;
		//Data
		$_SESSION['DATA_LVR']=array(
		'xid'=>$xid,
		'exercice'=>$exercice,
		'datelivraison'=>$datelivraison,
		'commande'=>$commande,
		'libcommande'=>$libcommande,
		'fournisseur'=>$fournisseur,
		'idfournisseur'=>$idfournisseur,
		'reflivraison'=>$reflivraison,
		'libelle'=>$libelle,
		'nbreLigne'=>$nbreLigne
		);

		//Collect Data
		$_SESSION['DATA_LVR']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_LVR']['nbreLigne']; $i++){
			(isset($_POST['code_detlivraison'.$i])? $code_detlivraison 	= $_POST['code_detlivraison'.$i] 	: $code_detlivraison 	= '');
			(isset($_POST['monlot'.$i])			? $monlot 		= $_POST['monlot'.$i] 	: $monlot 	= '');
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['oldcodeproduit'.$i])? $oldcodeproduit 	= $_POST['oldcodeproduit'.$i] 	: $oldcodeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 		= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 			= '');
			(isset($_POST['qtelvr'.$i]) 	? $qtelvr 		= $_POST['qtelvr'.$i] 		: $qtelvr 		= '');
			(isset($_POST['unite'.$i]) 		? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');
			(isset($_POST['prix'.$i]) 		? $prix 		= $_POST['prix'.$i] 		: $prix 		= '');
			(isset($_POST['reflot'.$i]) 	? $reflot		= $_POST['reflot'.$i] 		: $reflot 		= '');
			(isset($_POST['dateperemp'.$i]) ? $dateperemp 		= $_POST['dateperemp'.$i] 		: $dateperemp 		= '');
			(isset($_POST['rowSelection']) && $_POST['rowSelection']!=''  	? $rowSelection = trim($_POST['rowSelection']) 		: $rowSelection 	= $codeproduit);

			//Add to list
			if($codeproduit!='' && $produit!='' && $qtelvr!='' && $rowSelection!=$codeproduit){
				array_push($_SESSION['DATA_LVR']['ligne'], array('code_detlivraison'=>$code_detlivraison,'monlot'=>$monlot,'codeproduit'=>$codeproduit, 'oldcodeproduit'=>$oldcodeproduit,'produit'=>$produit,  'qte'=>$qte, 'qtelvr'=>$qtelvr,'unite'=>$unite, 'prix'=>$prix, 'reflot'=>$reflot, 'dateperemp'=>$dateperemp));
			}
			elseif($codeproduit!='' && $produit!='' && $qtelvr!='' && $rowSelection==$codeproduit){
				$supp++;
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}
				$sql = "DELETE FROM  `detlivraison` WHERE CODE_PRODUIT='".addslashes($codeproduit)."' AND `CODE_DETLIVRAISON` LIKE '".addslashes($code_detlivraison)."';
				DELETE FROM  `mouvement` WHERE CODE_PRODUIT='".addslashes($codeproduit)."' 	AND MVT_NATURE LIKE 'LIVRAISON'
				AND `ID_SOURCE` LIKE '".addslashes($xid)."' AND MVT_MONLOT LIKE '".addslashes($monlot)."';";
				$query =  $cnx->prepare($sql); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query
			}
		}
		$_SESSION['DATA_LVR']['nbreLigne'] -=$supp;

		if($_SESSION['DATA_LVR']['commande']!=''){
			header('location:editlivraison1.php?selectedTab=bde&rst=1');
		}
		else{
			header('location:editlivraison2.php?selectedTab=bde&rst=1');
		}
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
		$sql = "SELECT * FROM  `livraison` WHERE CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND `CODE_LIVRAISON` LIKE  '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data
		$_SESSION['DATA_LVR']=array(
		'xid'=>$row['CODE_LIVRAISON'],
		'exercice'=>$row['ID_EXERCICE'],
		'datelivraison'=>frFormat2($row['LVR_DATE']),
		'reflivraison'=>stripslashes($row['REF_LIVRAISON']),
		'commande'=>stripslashes($row['CODE_COMMANDE']),
		'libcommande'=>getField('CODE_COMMANDE', $row['CODE_COMMANDE'], 'CDE_LIBELLE', 'commande'), // $libcommande,
		'fournisseur'=>getField('CODE_FOUR', $row['CODE_FOUR'], 'FOUR_NOM', 'fournisseur'), //$fournisseur,
		'idfournisseur'=>$row['CODE_FOUR'],
		'libelle'=>stripslashes($row['LVR_LIBELLE']),
		'statut'=>$row['LVR_VALIDE'],
		'nbreLigne'=>0
		);

		//LIGNES LIVRAISONS
		$sql = "SELECT * FROM `detlivraison` INNER JOIN produit
		ON (detlivraison.CODE_PRODUIT LIKE produit.CODE_PRODUIT) WHERE CODE_LIVRAISON LIKE '".addslashes($split[0])."' ORDER BY detlivraison.ID_DETLIVRAISON ASC";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_LVR']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_LVR']['ligne'], array('code_detlivraison'=>$row['CODE_DETLIVRAISON'], 'monlot'=>$row['LVR_MONLOT'],'codeproduit'=>$row['CODE_PRODUIT'],'oldcodeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']), 'qte'=>$row['LVR_PRDQTE'], 'qtelvr'=>$row['LVR_PRDRECU'], 'unite'=>$row['LVR_UNITE'], 'mag'=>$row['LVR_MAGASIN'], 'prix'=>$row['LVR_PA'],'reflot'=>$row['LVR_REFLOT'],'dateperemp'=>preg_replace('[-]','/',frFormat2($row['LVR_DATEPEREMP']))));
		}
		$_SESSION['DATA_LVR']['nbreLigne'] = $query->rowCount();

		if($_SESSION['DATA_LVR']['commande']!=''){
			header('location:editlivraison1.php?selectedTab=bde&rs=2');
		}
		else{
			header('location:editlivraison2.php?selectedTab=bde&rs=2');
		}
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
		 $sql = "UPDATE `livraison` SET  LVR_VALIDE=2, LVR_DATEVALID='".date('Y-m-d H:i:s')."' WHERE `CODE_LIVRAISON` LIKE '".addslashes($xid)."';
		UPDATE mouvement SET MVT_VALID=2, MVT_DATEVALID='".addslashes(date('Y-m-d H:i:s'))."' WHERE (MVT_NATURE LIKE 'LIVRAISON')
		AND ID_SOURCE LIKE '".addslashes($xid)."';";
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
		$sql = "SELECT * FROM  `livraison` WHERE CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND `CODE_LIVRAISON` LIKE  '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data
		$_SESSION['DATA_LVR']=array(
		'xid'=>$row['CODE_LIVRAISON'],
		'exercice'=>$row['ID_EXERCICE'],
		'datelivraison'=>frFormat2($row['LVR_DATE']),
		'reflivraison'=>stripslashes($row['REF_LIVRAISON']),
		'commande'=>stripslashes($row['CODE_COMMANDE']),
		'libcommande'=>getField('CODE_COMMANDE', $row['CODE_COMMANDE'], 'CDE_LIBELLE', 'commande'), // $libcommande,
		'fournisseur'=>getField('CODE_FOUR', $row['CODE_FOUR'], 'FOUR_NOM', 'fournisseur'), //$fournisseur,
		'idfournisseur'=>$row['CODE_FOUR'],
		'libelle'=>stripslashes($row['LVR_LIBELLE']),
		'statut'=>$row['LVR_VALIDE'],
		'nbreLigne'=>0
		);

		//LIGNES COMMANDE
		$sql = "SELECT * FROM `detlivraison` INNER JOIN produit
		ON (detlivraison.CODE_PRODUIT LIKE produit.CODE_PRODUIT) WHERE CODE_LIVRAISON = '".addslashes($split[0])."' ORDER BY detlivraison.ID_DETLIVRAISON ASC";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_LVR']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_LVR']['ligne'], array('code_detlivraison'=>$row['CODE_DETLIVRAISON'], 'code_detlivraison'=>$row['ID_DETLIVRAISON'],'monlot'=>$row['LVR_MONLOT'],'codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']), 'qte'=>$row['LVR_PRDQTE'], 'qtelvr'=>$row['LVR_PRDRECU'], 'unite'=>$row['LVR_UNITE'], 'mag'=>$row['LVR_MAGASIN'], 'prix'=>$row['LVR_PA'],'reflot'=>$row['LVR_REFLOT'],'dateperemp'=>preg_replace('[-]','/',frFormat2($row['LVR_DATEPEREMP']))));
		}
		$_SESSION['DATA_LVR']['nbreLigne'] = $query->rowCount();
		if($_SESSION['DATA_LVR']['commande']!=''){
			header('location:validlivraison1.php?selectedTab=bde&rs=3');
		}
		else{
			header('location:validlivraison2.php?selectedTab=bde&rs=3');
		}
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
			$sql = "DELETE FROM  `detlivraison` WHERE `CODE_LIVRAISON` LIKE '".addslashes($split[0])."';
			DELETE FROM  `livraison` WHERE `CODE_LIVRAISON` LIKE '".addslashes($split[0])."';";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			//Suppression dans Mouvement
			$sql = "DELETE FROM  `mouvement` WHERE `ID_SOURCE` LIKE '".addslashes($split[0])."' AND MVT_NATURE LIKE 'LIVRAISON'";
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
