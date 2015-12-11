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
	case 'fill':
		$list = '<select name="produit[]" name="produit[]" class="formStyle"  multiple="multiple">';
		if(isset($_POST["categorie"])){
			(isset($_POST["categorie"]) && $_POST["categorie"]=='TOUS' ? $where='' : $where=" WHERE produit.CODE_CATEGORIE ='".$_POST["categorie"]."'");
			//SQL
			$sql  = "SELECT produit.* FROM produit  $where";
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
				$list .= '<option value="'.$row['CODE_PRODUIT'].'" >'.(stripslashes($row['PRD_LIBELLE'])).'</option>';
			}
		}
		echo $list.'</select>';
		break;

	//Log in User

	case 'next':
		(isset($_POST['exercice']) && $_POST['exercice']!=''				? $exercice 		= trim($_POST['exercice']) 			: $exercice 		= '');
		(isset($_POST['dateinventaire']) && $_POST['dateinventaire']!=''  	? $dateinventaire 	= trim($_POST['dateinventaire']) 	: $dateinventaire 	= '');
		(isset($_POST['inventaire']) && $_POST['inventaire']!=''  			? $inventaire 		= trim($_POST['inventaire']) 		: $inventaire 		= '');
		(isset($_POST['refinventaire']) && $_POST['refinventaire']!=''  	? $refinventaire 	= trim($_POST['refinventaire']) 	: $refinventaire 	= '');
		(isset($_POST['produit'])  ?  $produit =$_POST['produit'] : $produit=array());
		(isset($_POST['categorie']) && $_POST['categorie']!='0'	? $categorie = $_POST['categorie'] 	: $categorie 	= '');
		(isset($_POST['souscategorie']) && $_POST['souscategorie']!='0'	? $souscategorie = $_POST['souscategorie'] 	: $souscategorie 	= '');

		//Data
		$_SESSION['DATA_INV']=array(
		'exercice'=>$exercice,
		'dateinventaire'=>$dateinventaire,
		'inventaire'=>$inventaire,
		'refinventaire'=> $refinventaire,
		'categorie'=> $categorie,
		'produit'=>$produit
		);

		$where=" mouvement.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND ";
		(isset($_POST['exercice']) && $_POST['exercice']!=''	? $where .="mouvement.ID_EXERCICE = '".addslashes(trim($_POST['exercice']))."' AND " 	: $where .="");
		(isset($_POST['dateinventaire']) && $_POST['dateinventaire']!=''  ? $where .="mouvement.MVT_DATE <= '".addslashes(mysqlFormat(trim($_POST['dateinventaire'])))."' AND " 	: $where .="");


		$in ='';

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}

		$in ='';
		if(count($produit)==0 ){
			//
			if ($categorie=='TOUS'){
				if ($souscategorie!='TOUS' && $souscategorie!='0') {
					//Produit
					$sql = "SELECT CODE_PRODUIT FROM produit WHERE produit.CODE_SOUSCATEGORIE LIKE '$souscategorie' ORDER BY PRD_LIBELLE ASC";
					$query =  $cnx->prepare($sql); //Prepare the SQL
					$query->execute(); //Execute prepared SQL => $query
					while($row = $query->fetch(PDO::FETCH_ASSOC)){
						$in .="'".$row['CODE_PRODUIT']."', ";
					}
				}
				else{
					//Produit
					$sql = "SELECT CODE_PRODUIT FROM produit  ORDER BY PRD_LIBELLE ASC";
					$query =  $cnx->prepare($sql); //Prepare the SQL
					$query->execute(); //Execute prepared SQL => $query
					while($row = $query->fetch(PDO::FETCH_ASSOC)){
						$in .="'".$row['CODE_PRODUIT']."', ";
					}
				}
			}
			else{
				//Produit
				$sql = "SELECT CODE_PRODUIT FROM produit
					INNER JOIN souscategorie ON (produit.CODE_SOUSCATEGORIE LIKE souscategorie.CODE_SOUSCATEGORIE)
					WHERE souscategorie.CODE_CATEGORIE LIKE '$categorie' ORDER BY PRD_LIBELLE ASC";
				$query =  $cnx->prepare($sql); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					$in .="'".$row['CODE_PRODUIT']."', ";
				}
			}
		}
		elseif(count($produit)>0){
			$in='';
			foreach($produit as $key => $val){
				$in .="'$val', ";
			}
		}

		if($in!=''){
			$in = substr($in,0, strlen($in)-2);
			$in = 'mouvement.CODE_PRODUIT IN ('.$in.') AND ';
		}
		if($where!=''){
			$where = substr($where,0, strlen($where)-4);
		}

		$whereAll = 'AND '.$in.$where;

		if($in!=''){
			$in = ' AND '.substr($in,0, strlen($in)-4);
		}

		$_SESSION['DATA_INV']['ligne'] =array();
		//Unique ici pour les produit

		$sql = "SELECT * FROM mouvement INNER JOIN produit ON (mouvement.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
		WHERE mouvement.ID_EXERCICE=".$_SESSION['GL_USER']['EXERCICE']." AND CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."'
		AND mouvement.MVT_TYPE LIKE 'E'  $in GROUP BY  mouvement.MVT_REFLOT ORDER BY produit.PRD_LIBELLE ASC; ";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			//$Livraison = StockLotParNature($row['MVT_REFLOT'], 'LIVRAISON', $whereAll);
//			$bonsortie = StockLotParNature($row['MVT_REFLOT'], 'BON DE SORTIE', $whereAll);
//
//			$Declassement = StockLotParNature($row['MVT_REFLOT'], 'PERTE', $whereAll);
//
//			$transfetEnt = StockLotParNature($row['MVT_REFLOT'], 'TRANSFERT ENTRANT', $whereAll);
//			$transfetSort = StockLotParNature($row['MVT_REFLOT'], 'TRANSFERT SORTANT', $whereAll);
//
//			$reportEntree = StockLotParNature($row['MVT_REFLOT'], 'REPORT ENTRANT', $whereAll);
//			$reportSortie = StockLotParNature($row['MVT_REFLOT'], 'REPORT SORTANT', $whereAll);
//
//			$inventplus = StockLotParNature($row['MVT_REFLOT'], 'INVENTAIRE +', $whereAll);
//			$inventmoins = StockLotParNature ($row['MVT_REFLOT'], 'INVENTAIRE -', $whereAll);
//
//			$entree  = $Livraison['QTE'] +  $reportEntree['QTE'] + $transfetEnt['QTE'];// ENTREE
//			$sortie  = $bonsortie['QTE']  + $Declassement['QTE'] +   $reportSortie['QTE'] + $transfetSort['QTE'] ; //SORTIE
//			$ecart   =	$inventmoins['QTE'] + $inventplus['QTE'];
//			$rest = $entree - $sortie + ($ecart);

			$tProduit = StockLotPerime($row['MVT_REFLOT'], $type='E',  $whereAll);
			$qeperime = $tProduit['QTE'];


			$Livraison = StockLotParNature($row['MVT_REFLOT'], 'LIVRAISON', $whereAll);

			$Livraison = StockLotParNature($row['MVT_REFLOT'], 'LIVRAISON', $whereAll);

			$bonsortie = StockLotParNature($row['MVT_REFLOT'], 'BON DE SORTIE', $whereAll);

			$Declassement = StockLotParNature($row['MVT_REFLOT'], 'PERTE', $whereAll);

			$transfetEnt = StockLotParNature($row['MVT_REFLOT'], 'TRANSFERT ENTRANT', $whereAll);

			$transfetSort = StockLotParNature($row['MVT_REFLOT'], 'TRANSFERT SORTANT', $whereAll);

			$reportEntree = StockLotParNature($row['MVT_REFLOT'], 'REPORT ENTRANT', $whereAll);

			$reportSortie = StockLotParNature($row['MVT_REFLOT'], 'REPORT SORTANT', $whereAll);

			$inventplus = StockLotParNature($row['MVT_REFLOT'], 'INVENTAIRE +', $whereAll);

			$inventmoins = StockLotParNature($row['MVT_REFLOT'], 'INVENTAIRE -', $whereAll);

			//Declassement
			$PDeclassement = StockLotParNature($row['MVT_REFLOT'], 'PERTE', $whereAll);

			$entree  = $Livraison['QTE'] +  $reportEntree['QTE'] + $transfetEnt['QTE'];// ENTREE
			$sortie  = $bonsortie['QTE']  + $Declassement['QTE'] + $reportSortie['QTE'] + $transfetSort['QTE'] ; //SORTIE
			$ecart   = $inventmoins['QTE'] + $inventplus['QTE'];
			$rest 	 = $entree - ($sortie) + ($ecart) ;

			//echo  'Ent'.$entree.' Sort'.$sortie.'<br>';
			array_push($_SESSION['DATA_INV']['ligne'], array('codeinventaire'=>'', 'reflot'=>$row['MVT_REFLOT'],'monlot'=>$row['MVT_MONLOT'],'dateperemp'=>$row['MVT_DATEPEREMP'],'codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>addslashes($row['PRD_LIBELLE']),  'qteentre'=>$entree, 'qtesortie'=>$sortie, 'stockst'=>$rest, 'stocksp'=>'','prix'=>$row['MVT_PA'],'unite'=>$row['ID_UNITE']));
		}
		$_SESSION['DATA_INV']['nbreLigne'] =$query->rowCount();
		//print_r($_SESSION['DATA_INV']);
		header('location:addinventaire1.php?selectedTab=int');
		break;

	case 'add':
		(isset($_POST['exercice']) && $_POST['exercice']!=''				? $exercice 		= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['dateinventaire']) && $_POST['dateinventaire']!=''  	? $dateinventaire 	= trim($_POST['dateinventaire']) 	: $dateinventaire = '');
		(isset($_POST['inventaire']) && $_POST['inventaire']!=''  			? $inventaire 		= trim($_POST['inventaire']) 		: $inventaire = '');
		(isset($_POST['refinventaire']) && $_POST['refinventaire']!=''  	? $refinventaire 	= trim($_POST['refinventaire']) 	: $refinventaire = '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  					? $statut 			= trim($_POST['statut']) 			: $statut = '0');
		$dateinventaire = mysqlFormat($dateinventaire);
		$magasin=$_SESSION['GL_USER']['MAGASIN'];
		//$statut =1;

		$numauto = myDbLastId('inventaire', 'ID_INVENTAIRE', $magasin)+1;
		$codeInv = "$numauto/$magasin";
		//Insert
		$sql  = "INSERT INTO `inventaire` (`CODE_INVENTAIRE`, `CODE_MAGASIN`, `ID_EXERCICE`, `REF_INVENTAIRE`, `ID_INVENTAIRE`,
		`INV_LIBELLE`, `INV_DATE`, `INV_VALID`, `INV_DATEVALID`) VALUES ('".addslashes($codeInv)."', '".addslashes($magasin)."', '".addslashes($exercice)."',
		'".addslashes($refinventaire)."', '".addslashes($numauto)."', '".addslashes($inventaire)."','".addslashes($dateinventaire)."', '$statut', '".date('Y-m-d H:i:s')."');";

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
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Ajout d\'un inventaire ($codeInv, $refinventaire)"); //updateLog($username, $idcust, $action='' )

		$sql1 ="";
		$numautoDetInv = myDbLastId('detinventaire', 'ID_DETINVENTAIRE', $magasin);
		$numautoMvt = myDbLastId('mouvement', 'ID_MOUVEMENT', $magasin);

		for($i=1; $i<=$_SESSION['DATA_INV']['nbreLigne']; $i++){
			(isset($_POST['code_detinventaire'.$i]) && $_POST['code_detinventaire'.$i] 	? $code_detinventaire 	= $_POST['code_detinventaire'.$i] 	: $code_detinventaire 	= '');
			(isset($_POST['iddetinventaire'.$i]) && $_POST['iddetinventaire'.$i] 	? $iddetinventaire 	= $_POST['iddetinventaire'.$i] 	: $iddetinventaire 	= '');
			(isset($_POST['codeproduit'.$i])	? $codeproduit 		= $_POST['codeproduit'.$i] 		: $codeproduit 		= '');
			(isset($_POST['produit'.$i]) 		? $produit 			= $_POST['produit'.$i] 			: $produit 			= '');
			(isset($_POST['stockstheorique'.$i]) && $_POST['stockstheorique'.$i] !='' 	? $stockstheorique 	= $_POST['stockstheorique'.$i] 	: $stockstheorique 		= '');
			(isset($_POST['stocksphysique'.$i])  && $_POST['stocksphysique'.$i] !='' 	? $stocksphysique 	= $_POST['stocksphysique'.$i] 	: $stocksphysique 		= '');
			(isset($_POST['unite'.$i]) 			? $unite 			= $_POST['unite'.$i] 			: $unite 			= '');
			(isset($_POST['reflot'.$i]) 		? $reflot 			= $_POST['reflot'.$i] 			: $reflot 			= '');
			(isset($_POST['monlot'.$i]) 		? $monlot			= $_POST['monlot'.$i] 			: $monlot 			= '');
			(isset($_POST['dateperemp'.$i]) 	? $dateperemp		= $_POST['dateperemp'.$i] 		: $dateperemp		= '');
			(isset($_POST['prix'.$i]) 			? $prix				= $_POST['prix'.$i] 			: $prix				= '');

			if($codeproduit!='' && $produit!='') {
				if($stocksphysique!='' && $stockstheorique!=''){
					$ecart = $stocksphysique - $stockstheorique ;

					$numautoDetInv++;
					$codeDetInv = "$numautoDetInv/$magasin";
					$sql1 .="INSERT INTO `detinventaire` (`CODE_DETINVENTAIRE`, `CODE_INVENTAIRE`, `CODE_PRODUIT`, `ID_DETINVENTAIRE`, `STOCK_PHYSIQUE`, `STOCK_THEO`,
 					 `ECART`, `RAISON_ECART`, `INV_PA`, `INV_UNITE`, `INV_REFLOT`, `INV_DATEPEREMP`, `INV_MONLOT`)
 					 VALUES ('".addslashes($codeDetInv)."', '".addslashes($codeInv)."','".addslashes($codeproduit)."', '".addslashes($numautoDetInv)."',
					'".addslashes($stocksphysique)."' ,	'".addslashes($stockstheorique)."', '".addslashes($ecart)."','','".addslashes($prix)."',
					'".addslashes($unite)."', '".addslashes($reflot)."','".addslashes(mysqlFormat($dateperemp))."','".addslashes($monlot)."'); ";

					$numautoMvt++;
					$codeMvt = "$numautoMvt/$magasin";

					//if ($ecart>0 ) {
//						$sql1 .="INSERT INTO `mouvement` (`CODE_MOUVEMENT`, `ID_EXERCICE`, `CODE_PRODUIT`, `CODE_MAGASIN`, `ID_MOUVEMENT`, `ID_SOURCE`,
//						`MVT_DATE`, `MVT_TIME`, `MVT_QUANTITE`, `MVT_UNITE`, `MVT_NATURE`, `MVT_VALID`, `MVT_DATEVALID`, `MVT_TYPE`, `MVT_REFLOT`,
//						`MVT_DATEPEREMP`,  `MVT_PV`,  `MVT_MONLOT`) VALUES ('".addslashes($codeMvt)."',  '".addslashes($exercice)."',
//						'".addslashes($codeproduit)."',	'".addslashes($magasin)."',	'".addslashes($numautoMvt)."', '".addslashes($codeInv)."',
//						'".addslashes($dateinventaire)."' ,'".addslashes(date('H:i:s'))."' , '".addslashes($ecart)."' , '".addslashes($unite)."',
//						'INVENTAIRE -', '$statut', '".date('Y-m-d H:i:s')."','S','".addslashes($reflot)."','".addslashes(mysqlFormat($dateperemp))."',
//						 '".addslashes($prix)."', '".addslashes($monlot)."') ; ";
//					}
//					if ($ecart<0) {
//						$sql1 .="INSERT INTO `mouvement` (`CODE_MOUVEMENT`, `ID_EXERCICE`, `CODE_PRODUIT`, `CODE_MAGASIN`, `ID_MOUVEMENT`, `ID_SOURCE`,
//						`MVT_DATE`, `MVT_TIME`, `MVT_QUANTITE`, `MVT_UNITE`, `MVT_NATURE`, `MVT_VALID`, `MVT_DATEVALID`, `MVT_TYPE`, `MVT_REFLOT`,
//						`MVT_DATEPEREMP`,  `MVT_PV`,  `MVT_MONLOT`) VALUES ('".addslashes($codeMvt)."',  '".addslashes($exercice)."',
//						'".addslashes($codeproduit)."',	'".addslashes($magasin)."',	'".addslashes($numautoMvt)."', '".addslashes($codeInv)."',
//						'".addslashes($dateinventaire)."' ,'".addslashes(date('H:i:s'))."' , '".addslashes($ecart)."' , '".addslashes($unite)."',
//						'INVENTAIRE +', '$statut', '".date('Y-m-d H:i:s')."','E','".addslashes($reflot)."','".addslashes(mysqlFormat($dateperemp))."',
//					 	'".addslashes($prix)."', '".addslashes($monlot)."') ; ";
//					}

				}
			}
		}
		if (($sql1 !='')) {
			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}
			$query =  $cnx->prepare($sql1); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Ajout des lignes de inventaire ($codeInv, $refinventaire)"); //updateLog($username, $idcust, $action='' )
		}
		unset($_SESSION['DATA_INV']);
		//echo $sql1;
		header('location:inventaire.php?selectedTab=int&rs=1');
		break;

	case 'update':
		(isset($_POST['xid']) && $_POST['xid']!=''				? $xid 		= trim($_POST['xid']) 			: $xid = '');
		(isset($_POST['exercice']) && $_POST['exercice']!=''				? $exercice 		= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['dateinventaire']) && $_POST['dateinventaire']!=''  	? $dateinventaire 	= trim($_POST['dateinventaire']) 	: $dateinventaire = '');
		(isset($_POST['inventaire']) && $_POST['inventaire']!=''  			? $inventaire 		= trim($_POST['inventaire']) 		: $inventaire = '');
		(isset($_POST['refinventaire']) && $_POST['refinventaire']!=''  	? $refinventaire 	= trim($_POST['refinventaire']) 	: $refinventaire = '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  					? $statut 		= trim($_POST['statut']) 			: $statut = '0');
		$dateinventaire = mysqlFormat($dateinventaire);
		$magasin=$_SESSION['GL_USER']['MAGASIN'];
		//$statut =1;

		//Insert
		$sql  = "UPDATE `inventaire` SET `CODE_MAGASIN`='".addslashes($magasin)."', `ID_EXERCICE`='".addslashes($exercice)."',
		`REF_INVENTAIRE`='".addslashes($refinventaire)."', `INV_LIBELLE`='".addslashes($inventaire)."',
		`INV_DATE`='".addslashes($dateinventaire)."', `INV_VALID`='$statut' WHERE CODE_INVENTAIRE '".addslashes($xid)."'";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Modification d\'un inventaire ($xid, $refinventaire, $dateinventaire)"); //updateLog($username, $idcust, $action='' )

		$sql1 ="";
		$numautoDetInv = myDbLastId('detinventaire', 'ID_DETINVENTAIRE', $magasin);
		$numautoMvt = myDbLastId('mouvement', 'ID_MOUVEMENT', $magasin);

		//Collect Data
		for($i=1; $i<=$_SESSION['DATA_INV']['nbreLigne']; $i++){
			(isset($_POST['code_detinventaire'.$i]) && $_POST['code_detinventaire'.$i] 	? $code_detinventaire 	= $_POST['code_detinventaire'.$i] 	: $code_detinventaire 	= '');
			(isset($_POST['iddetinventaire'.$i]) && $_POST['iddetinventaire'.$i] 	? $iddetinventaire 	= $_POST['iddetinventaire'.$i] 	: $iddetinventaire 	= '');
			(isset($_POST['codeproduit'.$i])	? $codeproduit 		= $_POST['codeproduit'.$i] 		: $codeproduit 		= '');
			(isset($_POST['oldcodeproduit'.$i])? $oldcodeproduit 	= $_POST['oldcodeproduit'.$i] 	: $oldcodeproduit 	= '');
			(isset($_POST['produit'.$i]) 		? $produit 			= $_POST['produit'.$i] 			: $produit 			= '');
			(isset($_POST['stockstheorique'.$i]) && $_POST['stockstheorique'.$i] !='' 	? $stockstheorique 	= $_POST['stockstheorique'.$i] 	: $stockstheorique 		= '');
			(isset($_POST['stocksphysique'.$i])  && $_POST['stocksphysique'.$i] !='' 	? $stocksphysique 	= $_POST['stocksphysique'.$i] 	: $stocksphysique 		= '');
			(isset($_POST['unite'.$i]) 			? $unite 			= $_POST['unite'.$i] 			: $unite 			= '');
			(isset($_POST['reflot'.$i]) 		? $reflot 			= $_POST['reflot'.$i] 			: $reflot 			= '');
			(isset($_POST['monlot'.$i]) 		? $monlot			= $_POST['monlot'.$i] 			: $monlot 			= '');
			(isset($_POST['dateperemp'.$i]) 	? $dateperemp		= $_POST['dateperemp'.$i] 		: $dateperemp		= '');
			(isset($_POST['prix'.$i]) 			? $prix				= $_POST['prix'.$i] 			: $prix				= '');

			if($codeproduit!='' && $oldcodeproduit!='' && $produit!='') {
				if($stocksphysique!='' && $stockstheorique!=''){
					$ecart = $stocksphysique - $stockstheorique ;

					$sql1 .="UPDATE `detinventaire` SET `CODE_INVENTAIRE`='".addslashes($xid)."', `CODE_PRODUIT`='".addslashes($codeproduit)."', `STOCK_PHYSIQUE`='".addslashes($stocksphysique)."',
					`STOCK_THEO`='".addslashes($stockstheorique)."',`ECART`='".addslashes($ecart)."', `INV_UNITE`='".addslashes($unite)."',
					`INV_REFLOT`='".addslashes($reflot)."', `INV_DATEPEREMP`='".addslashes(mysqlFormat($dateperemp))."', `INV_MONLOT`='".addslashes($monlot)."'
					 WHERE CODE_DETINVENTAIRE  LIKE '".addslashes($code_detinventaire)."';";

				//	if ($ecart>0 ) {
//						$sql1 .="UPDATE `mouvement` SET  `MVT_DATE`='".addslashes($dateinventaire)."', `MVT_TIME`='".addslashes(date('H:i:s'))."',
//					 	`MVT_QUANTITE`='".addslashes($ecart)."',  `MVT_NATURE`='INVENTAIRE -', `MVT_VALID`='$statut',
//					 	`MVT_DATEVALID`='".date('Y-m-d H:i:s')."', `MVT_TYPE`='S' WHERE  `CODE_PRODUIT` LIKE '".addslashes($codeproduit)."' AND
//						 `ID_SOURCE` LIKE '".addslashes($xid)."' AND `MVT_MONLOT` LIKE '".addslashes($monlot)."';";
//					}
//					if ($ecart<0) {
//						$sql1 .="UPDATE `mouvement` SET  `MVT_DATE`='".addslashes($dateinventaire)."', `MVT_TIME`='".addslashes(date('H:i:s'))."',
//					 	`MVT_QUANTITE`='".addslashes($ecart)."',  `MVT_NATURE`='INVENTAIRE +', `MVT_VALID`='$statut',
//						 `MVT_DATEVALID`='".date('Y-m-d H:i:s')."', `MVT_TYPE`='E' WHERE  `CODE_PRODUIT` LIKE '".addslashes($codeproduit)."' AND
//					 	`ID_SOURCE` LIKE '".addslashes($xid)."' AND `MVT_MONLOT` LIKE '".addslashes($monlot)."';";
//					}
				}
				else {
					$sql1 .="DELETE FROM detinventaire WHERE CODE_DETINVENTAIRE  LIKE '".addslashes($code_detinventaire)."';
					DELETE FROM mouvement WHERE ID_SOURCE LIKE '".addslashes($xid)."' AND `CODE_PRODUIT` LIKE '".addslashes($codeproduit)."'
					AND `INV_MONLOT` LIKE '".addslashes($monlot)."';";

				}
			}
		}
		if (($sql1 !='')) {
			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}
			$query =  $cnx->prepare($sql1); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Modification des lignes de inventaire ($xid, $refinventaire, $dateinventaire)"); //updateLog($username, $idcust, $action='' )
		}
		unset($_SESSION['DATA_INV']);
		header('location:inventaire.php?selectedTab=int&rs=2');
		break;

	case 'validate':
		(isset($_POST['xid']) && $_POST['xid']!=''							? $xid 				= trim($_POST['xid']) 				: $xid = '');
		(isset($_POST['exercice']) && $_POST['exercice']!=''				? $exercice 		= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['dateinventaire']) && $_POST['dateinventaire']!=''  	? $dateinventaire 	= trim($_POST['dateinventaire']) 	: $dateinventaire = '');
		(isset($_POST['inventaire']) && $_POST['inventaire']!=''  			? $inventaire 		= trim($_POST['inventaire']) 		: $inventaire = '');
		(isset($_POST['refinventaire']) && $_POST['refinventaire']!=''  	? $refinventaire 	= trim($_POST['refinventaire']) 	: $refinventaire = '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  					? $statut 			= trim($_POST['statut']) 			: $statut = '0');
		$dateinventaire = mysqlFormat($dateinventaire);
		$magasin=$_SESSION['GL_USER']['MAGASIN'];

		//Insert
		$sql  = "UPDATE `inventaire` SET `INV_VALID`='$statut' ,`INV_DATEVALID`='".date('Y-m-d H:i:s')."' WHERE CODE_INVENTAIRE LIKE '".addslashes($xid)."'";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Validation d\'un inventaire ('.$xid.', '.$refinventaire.', '.$dateinventaire.')'); //updateLog($username, $idcust, $action='' )

		$sql1 ="";
		//Collect Data
		for($i=1; $i<=$_SESSION['DATA_INV']['nbreLigne']; $i++){
			(isset($_POST['code_detinventaire'.$i]) && $_POST['code_detinventaire'.$i] 	? $code_detinventaire 	= $_POST['code_detinventaire'.$i] 	: $code_detinventaire 	= '');
			(isset($_POST['iddetinventaire'.$i]) && $_POST['iddetinventaire'.$i] 	? $iddetinventaire 	= $_POST['iddetinventaire'.$i] 	: $iddetinventaire 	= '');
			(isset($_POST['codeproduit'.$i])	? $codeproduit 		= $_POST['codeproduit'.$i] 		: $codeproduit 		= '');
			(isset($_POST['oldcodeproduit'.$i])? $oldcodeproduit 	= $_POST['oldcodeproduit'.$i] 	: $oldcodeproduit 	= '');
			(isset($_POST['produit'.$i]) 		? $produit 			= $_POST['produit'.$i] 			: $produit 			= '');
			(isset($_POST['stockstheorique'.$i]) && $_POST['stockstheorique'.$i] !='' 	? $stockstheorique 	= $_POST['stockstheorique'.$i] 	: $stockstheorique 		= '');
			(isset($_POST['stocksphysique'.$i])  && $_POST['stocksphysique'.$i] !='' 	? $stocksphysique 	= $_POST['stocksphysique'.$i] 	: $stocksphysique 		= '');
			(isset($_POST['unite'.$i]) 			? $unite 			= $_POST['unite'.$i] 			: $unite 			= '');
			(isset($_POST['reflot'.$i]) 		? $reflot 			= $_POST['reflot'.$i] 			: $reflot 			= '');
			(isset($_POST['monlot'.$i]) 		? $monlot			= $_POST['monlot'.$i] 			: $monlot 			= '');
			(isset($_POST['dateperemp'.$i]) 	? $dateperemp		= $_POST['dateperemp'.$i] 		: $dateperemp		= '');
			(isset($_POST['prix'.$i]) 			? $prix				= $_POST['prix'.$i] 			: $prix				= '');

			if($codeproduit!='' && $oldcodeproduit!='' && $produit!='') {
				if($stocksphysique!='' && $stockstheorique!=''){
					$ecart = $stocksphysique - $stockstheorique ;

					$sql1 .="UPDATE `detinventaire` SET `CODE_INVENTAIRE`='".addslashes($xid)."', `CODE_PRODUIT`='".addslashes($codeproduit)."', `STOCK_PHYSIQUE`='".addslashes($stocksphysique)."',
					`STOCK_THEO`='".addslashes($stockstheorique)."',`ECART`='".addslashes($ecart)."', `INV_UNITE`='".addslashes($unite)."',
					`INV_REFLOT`='".addslashes($reflot)."', `INV_DATEPEREMP`='".addslashes(mysqlFormat($dateperemp))."', `INV_MONLOT`='".addslashes($monlot)."'
					 WHERE CODE_DETINVENTAIRE  LIKE '".addslashes($code_detinventaire)."';";

					//if ($ecart>0 ) {
//						$sql1 .="UPDATE `mouvement` SET  `MVT_DATE`='".addslashes($dateinventaire)."', `MVT_TIME`='".addslashes(date('H:i:s'))."',
//					 	`MVT_QUANTITE`='".addslashes($ecart)."',  `MVT_NATURE`='INVENTAIRE +', `MVT_VALID`='$statut',
//					 	`MVT_DATEVALID`='".date('Y-m-d H:i:s')."', `MVT_TYPE`='E' WHERE  `CODE_PRODUIT` LIKE '".addslashes($codeproduit)."' AND
//					 	`ID_SOURCE` LIKE '".addslashes($xid)."' AND `MVT_MONLOT` LIKE '".addslashes($monlot)."';";
//					}
//					if ($ecart<0) {
//						$sql1 .="UPDATE `mouvement` SET  `MVT_DATE`='".addslashes($dateinventaire)."', `MVT_TIME`='".addslashes(date('H:i:s'))."',
//					 	`MVT_QUANTITE`='".addslashes($ecart)."',  `MVT_NATURE`='INVENTAIRE -', `MVT_VALID`='$statut',
//					 	`MVT_DATEVALID`='".date('Y-m-d H:i:s')."', `MVT_TYPE`='S' WHERE  `CODE_PRODUIT` LIKE '".addslashes($codeproduit)."' AND
//					 	`ID_SOURCE` LIKE '".addslashes($xid)."' AND `MVT_MONLOT` LIKE '".addslashes($monlot)."';";
//					}

				}
				else {
					$sql1 .="DELETE FROM detinventaire WHERE CODE_DETINVENTAIRE  LIKE '".addslashes($code_detinventaire)."';
					DELETE FROM mouvement WHERE ID_SOURCE LIKE '".addslashes($xid)."' AND `CODE_PRODUIT` LIKE '".addslashes($codeproduit)."'
					AND `INV_MONLOT` LIKE '".addslashes($monlot)."';";

				}
			}
		}
		if (($sql1 !='')) {
			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}
			$query =  $cnx->prepare($sql1); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Validation des lignes de inventaire ('.$xid.', '.$refinventaire.', '.$dateinventaire.')'); //updateLog($username, $idcust, $action='' )
		}
		unset($_SESSION['DATA_INV']);
		header('location:inventaire.php?selectedTab=int&rs=3');
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
		//INVENTAIRE
		$sql = "SELECT * FROM  `inventaire` WHERE CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND  `CODE_INVENTAIRE` LIKE '".addslashes($id)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data
		$_SESSION['DATA_INV']=array(
		'xid'=>$row['CODE_INVENTAIRE'],
		'exercice'=>$row['ID_EXERCICE'],
		'refinventaire'=>$row['REF_INVENTAIRE'],
		'dateinventaire'=>frFormat2($row['INV_DATE']),
		'inventaire'=>$row['INV_LIBELLE'],
		'statut'=>$row['INV_VALID'],
		'nbreLigne'=>0,
		'ligne'=>array()
		);

		//LIGNES INVENTAIRE
		$sql = "SELECT * FROM `detinventaire` INNER JOIN produit ON (detinventaire.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
		WHERE CODE_INVENTAIRE LIKE '".addslashes($id)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_INV']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_INV']['ligne'], array('code_detinventaire'=>$row['CODE_DETINVENTAIRE'],'monlot'=>$row['INV_MONLOT'],'codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']), 'qteentre'=>'', 'qtesortie'=>'', 'stockst'=>$row['STOCK_THEO'],'stocksp'=>$row['STOCK_PHYSIQUE'],'unite'=>$row['INV_UNITE'],'prix'=>$row['INV_PA'], 'reflot'=>$row['INV_REFLOT'],'dateperemp'=>$row['INV_DATEPEREMP']));
		}
		$_SESSION['DATA_INV']['nbreLigne'] = $query->rowCount();
		header('location:detailinventaire.php?selectedTab=int&rst=1');
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
		//INVENTAIRE
		$sql = "SELECT * FROM  `inventaire` WHERE CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."'
		AND  `CODE_INVENTAIRE` LIKE '".addslashes($id)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data
		$_SESSION['DATA_INV']=array(
		'xid'=>$row['CODE_INVENTAIRE'],
		'exercice'=>$row['ID_EXERCICE'],
		'refinventaire'=>$row['REF_INVENTAIRE'],
		'dateinventaire'=>frFormat2($row['INV_DATE']),
		'inventaire'=>$row['INV_LIBELLE'],
		'statut'=>$row['INV_VALID'],
		'nbreLigne'=>0,
		'ligne'=>array()
		);

		//LIGNES INVENTAIRE
		$sql = "SELECT * FROM `detinventaire` INNER JOIN produit ON (detinventaire.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
		WHERE CODE_INVENTAIRE LIKE '".addslashes($id)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_INV']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_INV']['ligne'], array('code_detinventaire'=>$row['CODE_DETINVENTAIRE'],'monlot'=>$row['INV_MONLOT'], 'codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']), 'qteentre'=>'', 'qtesortie'=>'', 'stockst'=>$row['STOCK_THEO'],'stocksp'=>$row['STOCK_PHYSIQUE'],'unite'=>$row['INV_UNITE'],'prix'=>$row['INV_PA'], 'reflot'=>$row['INV_REFLOT'],'dateperemp'=>$row['INV_DATEPEREMP']));
		}
		$_SESSION['DATA_INV']['nbreLigne'] = $query->rowCount();


		//LIGNES MOUVEMENT
		 $sql = "SELECT * , produit.PRD_LIBELLE FROM `mouvement` INNER JOIN produit ON (mouvement.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
		WHERE MVT_NATURE LIKE 'INVENTAIRE%' AND ID_SOURCE LIKE '".addslashes($id)."' ORDER BY mouvement.CODE_PRODUIT ASC";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_INV']['journal'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_INV']['journal'], array('codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']), 'qte'=>$row['MVT_QUANTITE'], 'qtelvr'=>$row['MVT_QUANTITE'], 'unite'=>$row['MVT_UNITE'], 'valide'=>$row['MVT_VALID'],'prix'=>$row['MVT_PA'],'reflot'=>$row['MVT_REFLOT'],'dateperemp'=>$row['MVT_DATEPEREMP']));
		}

		$_SESSION['DATA_INV']['nbreLigne2'] = $query->rowCount();

		header('location:journalinventaire.php?selectedTab=int&rst=1');
		break;

	case 'check':
		$msg = "";
		(isset($_POST['refinventaire']) && $_POST['refinventaire']!='' ? $refinventaire = trim($_POST['refinventaire']) 	: $refinventaire = '');

		if($refinventaire !=''){
			$sql = "SELECT COUNT(REF_INVENTAIRE) AS NBRE FROM  `inventaire` WHERE `REF_INVENTAIRE` LIKE '".addslashes($refinventaire)."'";
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

			if($row['NBRE']>0) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Ce code existe d&eacute;j&agrave;, veuillez entrer un autre code inventaire.';}
		}
		echo $msg;
		break;

	case 'fiche':
		(isset($_POST['exercice']) && $_POST['exercice']!=''				? $exercice 		= trim($_POST['exercice']) 			: $exercice 		= '');
		(isset($_POST['dateinventaire']) && $_POST['dateinventaire']!=''  	? $dateinventaire 	= trim($_POST['dateinventaire']) 	: $dateinventaire 	= '');
		(isset($_POST['inventaire']) && $_POST['inventaire']!=''  			? $inventaire 		= trim($_POST['inventaire']) 		: $inventaire 		= '');
		(isset($_POST['refinventaire']) && $_POST['refinventaire']!=''  	? $refinventaire 	= trim($_POST['refinventaire']) 	: $refinventaire 	= '');
		(isset($_POST['produit'])  ?  $produit =$_POST['produit'] : $produit=array());
		(isset($_POST['categorie']) && $_POST['categorie']!='0'	? $categorie = $_POST['categorie'] 	: $categorie 	= '');
		(isset($_POST['souscategorie']) && $_POST['souscategorie']!='0'	? $souscategorie = $_POST['souscategorie'] 	: $souscategorie 	= '');

		//Data
		$_SESSION['DATA_INV']=array(
		'exercice'=>$exercice,
		'dateinventaire'=>$dateinventaire,
		'inventaire'=>$inventaire,
		'refinventaire'=> $refinventaire,
		'categorie'=> $categorie,
		'produit'=>$produit
		);

		$where=" mouvement.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND ";
		(isset($_POST['exercice']) && $_POST['exercice']!=''	? $where .="mouvement.ID_EXERCICE = '".addslashes(trim($_POST['exercice']))."' AND " 	: $where .="");
		(isset($_POST['dateinventaire']) && $_POST['dateinventaire']!=''  ? $where .="mouvement.MVT_DATE <= '".addslashes(mysqlFormat(trim($_POST['dateinventaire'])))."' AND " 	: $where .="");


		$in ='';

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}

		$in ='';
		if(count($produit)==0 ){
			//
			if ($categorie=='TOUS'){
				if ($souscategorie!='TOUS' && $souscategorie!='0') {
					//Produit
					$sql = "SELECT CODE_PRODUIT FROM produit WHERE produit.CODE_SOUSCATEGORIE LIKE '$souscategorie' ORDER BY PRD_LIBELLE ASC";
					$query =  $cnx->prepare($sql); //Prepare the SQL
					$query->execute(); //Execute prepared SQL => $query
					while($row = $query->fetch(PDO::FETCH_ASSOC)){
						$in .="'".$row['CODE_PRODUIT']."', ";
					}
				}
				else{
					//Produit
					$sql = "SELECT CODE_PRODUIT FROM produit  ORDER BY PRD_LIBELLE ASC";
					$query =  $cnx->prepare($sql); //Prepare the SQL
					$query->execute(); //Execute prepared SQL => $query
					while($row = $query->fetch(PDO::FETCH_ASSOC)){
						$in .="'".$row['CODE_PRODUIT']."', ";
					}
				}
			}
			else{
				//Produit
				$sql = "SELECT CODE_PRODUIT FROM produit
					INNER JOIN souscategorie ON (produit.CODE_SOUSCATEGORIE LIKE souscategorie.CODE_SOUSCATEGORIE)
					WHERE souscategorie.CODE_CATEGORIE LIKE '$categorie' ORDER BY PRD_LIBELLE ASC";
				$query =  $cnx->prepare($sql); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					$in .="'".$row['CODE_PRODUIT']."', ";
				}
			}
		}
		elseif(count($produit)>0){
			$in='';
			foreach($produit as $key => $val){
				$in .="'$val', ";
			}
		}

		if($in!=''){
			$in = substr($in,0, strlen($in)-2);
			$in = 'mouvement.CODE_PRODUIT IN ('.$in.') AND ';
		}
		if($where!=''){
			$where = substr($where,0, strlen($where)-4);
		}

		$whereAll = 'AND '.$in.$where;

		if($in!=''){
			$in = ' AND '.substr($in,0, strlen($in)-4);
		}

		$_SESSION['DATA_INV']['ligne'] =array();
		//Unique ici pour les produit

		$sql = "SELECT * FROM mouvement INNER JOIN produit ON (mouvement.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
		WHERE mouvement.ID_EXERCICE=".$_SESSION['GL_USER']['EXERCICE']." AND CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."'
		AND mouvement.MVT_TYPE LIKE 'E'  $in GROUP BY  mouvement.MVT_REFLOT ORDER BY produit.PRD_LIBELLE ASC; ";

		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$tProduit = StockLotPerime($row['MVT_REFLOT'], $type='E',  $whereAll);
			$qeperime = $tProduit['QTE'];


			$Livraison = StockLotParNature($row['MVT_REFLOT'], 'LIVRAISON', $whereAll);

			$Livraison = StockLotParNature($row['MVT_REFLOT'], 'LIVRAISON', $whereAll);

			$bonsortie = StockLotParNature($row['MVT_REFLOT'], 'BON DE SORTIE', $whereAll);

			$Declassement = StockLotParNature($row['MVT_REFLOT'], 'PERTE', $whereAll);

			$transfetEnt = StockLotParNature($row['MVT_REFLOT'], 'TRANSFERT ENTRANT', $whereAll);

			$transfetSort = StockLotParNature($row['MVT_REFLOT'], 'TRANSFERT SORTANT', $whereAll);

			$reportEntree = StockLotParNature($row['MVT_REFLOT'], 'REPORT ENTRANT', $whereAll);

			$reportSortie = StockLotParNature($row['MVT_REFLOT'], 'REPORT SORTANT', $whereAll);

			$inventplus = StockLotParNature($row['MVT_REFLOT'], 'INVENTAIRE +', $whereAll);

			$inventmoins = StockLotParNature($row['MVT_REFLOT'], 'INVENTAIRE -', $whereAll);

			//Declassement
			$PDeclassement = StockLotParNature($row['MVT_REFLOT'], 'PERTE', $whereAll);

			$entree  = $Livraison['QTE'] +  $reportEntree['QTE'] + $transfetEnt['QTE'];// ENTREE
			$sortie  = $bonsortie['QTE']  + $Declassement['QTE'] + $reportSortie['QTE'] + $transfetSort['QTE'] ; //SORTIE
			$ecart   = $inventmoins['QTE'] + $inventplus['QTE'];
			$rest 	 = $entree - ($sortie) + ($ecart) ;


			//echo  'Ent'.$entree.' Sort'.$sortie.'<br>';
			array_push($_SESSION['DATA_INV']['ligne'], array('codeinventaire'=>'', 'reflot'=>$row['MVT_REFLOT'],'monlot'=>$row['MVT_MONLOT'],'dateperemp'=>$row['MVT_DATEPEREMP'],'codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>addslashes($row['PRD_LIBELLE']),  'qteentre'=>$entree, 'qtesortie'=>$sortie, 'stockst'=>$rest, 'stocksp'=>'','prix'=>$row['MVT_PA'],'unite'=>$row['ID_UNITE']));
		}
		$_SESSION['DATA_INV']['nbreLigne'] =$query->rowCount();
		//print_r($_SESSION['DATA_INV']);
		header('location:ficheinventaire1.php?selectedTab=int');
		break;

	default : ///Nothing
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
		//INVENTAIRE
		$sql = "SELECT * FROM  `inventaire` WHERE CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."'
		AND  `CODE_INVENTAIRE` LIKE '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data
		$_SESSION['DATA_INV']=array(
		'xid'=>$row['CODE_INVENTAIRE'],
		'exercice'=>$row['ID_EXERCICE'],
		'refinventaire'=>$row['REF_INVENTAIRE'],
		'dateinventaire'=>frFormat2($row['INV_DATE']),
		'inventaire'=>$row['INV_LIBELLE'],
		'statut'=>$row['INV_VALID'],
		'nbreLigne'=>0,
		'ligne'=>array()
		);

		//LIGNES INVENTAIRE
		$sql = "SELECT * FROM `detinventaire` INNER JOIN produit ON (detinventaire.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
		WHERE CODE_INVENTAIRE LIKE '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_INV']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_INV']['ligne'], array('code_detinventaire'=>$row['CODE_DETINVENTAIRE'],'monlot'=>$row['INV_MONLOT'],'codeproduit'=>$row['CODE_PRODUIT'], 'oldcodeproduit'=>$row['CODE_PRODUIT'],'produit'=>stripslashes($row['PRD_LIBELLE']), 'qteentre'=>'', 'qtesortie'=>'', 'stockst'=>$row['STOCK_THEO'],'stocksp'=>$row['STOCK_PHYSIQUE'],'unite'=>$row['INV_UNITE'],'prix'=>$row['INV_PA'], 'reflot'=>$row['INV_REFLOT'],'dateperemp'=>$row['INV_DATEPEREMP']));
		}
		$_SESSION['DATA_INV']['nbreLigne'] = $query->rowCount();
		header('location:editinventaire.php?selectedTab=int&rs=2');
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
		//INVENTAIRE
		$sql = "SELECT * FROM  `inventaire` WHERE CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."'
		AND  `CODE_INVENTAIRE` LIKE '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data
		$_SESSION['DATA_INV']=array(
		'xid'=>$row['CODE_INVENTAIRE'],
		'exercice'=>$row['ID_EXERCICE'],
		'refinventaire'=>$row['REF_INVENTAIRE'],
		'dateinventaire'=>frFormat2($row['INV_DATE']),
		'inventaire'=>$row['INV_LIBELLE'],
		'statut'=>$row['INV_VALID'],
		'nbreLigne'=>0,
		'ligne'=>array()
		);

		//LIGNES INVENTAIRE
		$sql = "SELECT * FROM `detinventaire` INNER JOIN produit ON (detinventaire.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
		WHERE CODE_INVENTAIRE LIKE '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_INV']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_INV']['ligne'], array('code_detinventaire'=>$row['CODE_DETINVENTAIRE'], 'monlot'=>$row['INV_MONLOT'],'codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']), 'qteentre'=>'', 'qtesortie'=>'', 'stockst'=>$row['STOCK_THEO'],'stocksp'=>$row['STOCK_PHYSIQUE'],'unite'=>$row['INV_UNITE'], 'prix'=>$row['INV_PA'], 'reflot'=>$row['INV_REFLOT'],'dateperemp'=>$row['INV_DATEPEREMP']));
		}
		$_SESSION['DATA_INV']['nbreLigne'] = $query->rowCount();
		header('location:validinventaire.php?selectedTab=int&rs=3');
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
		 	$sql = "DELETE FROM  `detinventaire` WHERE `CODE_INVENTAIRE` LIKE '".addslashes($split[0])."';
			DELETE FROM  `inventaire` WHERE `CODE_INVENTAIRE` LIKE '".addslashes($split[0])."'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			//Suppression dans Mouvement
			$sql = "DELETE FROM  `mouvement` WHERE `ID_SOURCE` LIKE '".addslashes($split[0])."' AND MVT_NATURE LIKE 'INVENTAIRE%'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

		}
		header('location:inventaire.php?selectedTab=int&rs=4');
		break;

	case 'annul':
		(isset($_POST['xid']) ? $xid = $_POST['xid'] : $xid ='');
		(isset($_POST['oldrefinventaire']) ? $oldrefinventaire = $_POST['oldrefinventaire'] : $oldrefinventaire ='');
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		//TRANSFERT
		$sql = "UPDATE `inventaire` SET  INV_VALID=2 WHERE `CODE_INVENTAIRE` LIKE '".addslashes($xid)."';
		UPDATE mouvement SET MVT_VALID=2 WHERE (MVT_NATURE LIKE 'INVENTAIRE%')
		AND ID_SOURCE LIKE '".addslashes($xid)."';";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Annulation d\'un inventaire ($xid, $oldrefinventaire)"); //updateLog($username, $idcust, $action='' )
		//echo $sql;
		header('location:inventaire.php?selectedTab=int&rst=1');
		break;

	default : ///Nothing
		//header('location:../index.php');

}
elseif($myaction =='' && $do ='') header('location:../index.php');

?>
