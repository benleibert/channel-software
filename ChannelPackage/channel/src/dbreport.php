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

	case 'next':
		(isset($_POST['exercice']) && $_POST['exercice']!=''	? $exercice 	= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datereport']) && $_POST['datereport']!=''  	? $datereport = trim($_POST['datereport']) 		: $datereport = '');

		$where=" mouvement.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND ";
		$whereAll ="";

		(isset($_POST['exercice']) && $_POST['exercice']!=''	? $where .="mouvement.ID_EXERCICE = '".addslashes(trim($_POST['exercice']))."' AND " 	: $where .="");
		(isset($_POST['datereport']) && $_POST['datereport']!=''  ? $where .="mouvement.MVT_DATE <= '".addslashes(mysqlFormat(trim($_POST['datereport'])))."' AND " 	: $where .="");

		if($where!=''){
			$where = substr($where,0, strlen($where)-4);
		}
		$whereAll = 'AND '.$where;

		//Data
		$_SESSION['DATA_REP']=array(
		'exercice'=>$exercice,
		'datereport'=>$datereport,
		);

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$_SESSION['DATA_REP']['ligne'] =array();

		$sql = "SELECT CODE_PRODUIT, ID_UNITE FROM produit;";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		$sql = "SELECT * FROM mouvement INNER JOIN produit ON (mouvement.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
		WHERE mouvement.MVT_TYPE LIKE 'E' AND MVT_VALID=1 $whereAll GROUP BY mouvement.MVT_REFLOT ORDER BY produit.PRD_LIBELLE ASC; ";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$qeperime =0;
			$Livraison = StockLotParNature($row['MVT_REFLOT'], 'LIVRAISON', $whereAll);
			($Livraison['MVT_DATEPEREMP']< date('Y-m-d') ? $qeperime += $Livraison['QTE'] : $qeperime +=0);

			$bonsortie = StockLotParNature($row['MVT_REFLOT'], 'BON DE SORTIE', $whereAll);
			($bonsortie['MVT_DATEPEREMP']< date('Y-m-d') ? $qeperime += $bonsortie['QTE'] : $qeperime +=0);

			$Declassement = StockLotParNature($row['MVT_REFLOT'], 'PERTE', $whereAll);
			($Declassement['MVT_DATEPEREMP']< date('Y-m-d') ? $qeperime += $Declassement['QTE'] : $qeperime +=0);

			$transfetEnt = StockLotParNature($row['MVT_REFLOT'], 'TRANSFERT ENTRANT', $whereAll);
			($transfetEnt['MVT_DATEPEREMP']< date('Y-m-d') ? $qeperime += $transfetEnt['QTE'] : $qeperime +=0);

			$transfetSort = StockLotParNature($row['MVT_REFLOT'], 'TRANSFERT SORTANT', $whereAll);
			($transfetSort['MVT_DATEPEREMP']< date('Y-m-d') ? $qeperime += $transfetSort['QTE'] : $qeperime +=0);

			$reportEntree = StockLotParNature($row['MVT_REFLOT'], 'REPORT ENTRANT', $whereAll);
			($reportEntree['MVT_DATEPEREMP']< date('Y-m-d') ? $qeperime += $reportEntree['QTE'] : $qeperime +=0);

			$reportSortie = StockLotParNature($row['MVT_REFLOT'], 'REPORT SORTANT', $whereAll);
			($reportSortie['MVT_DATEPEREMP']< date('Y-m-d') ? $qeperime += $reportSortie['QTE'] : $qeperime +=0);

			$inventplus = StockLotParNature($row['MVT_REFLOT'], 'INVENTAIRE +', $whereAll);
			($inventplus['MVT_DATEPEREMP']< date('Y-m-d') ? $qeperime += $inventplus['QTE'] : $qeperime +=0);

			$inventmoins = StockLotParNature($row['MVT_REFLOT'], 'INVENTAIRE -', $whereAll);
			($inventmoins['MVT_DATEPEREMP']< date('Y-m-d') ? $qeperime += $inventmoins['QTE'] : $qeperime +=0);

			$entree  = $Livraison['QTE'] +  $reportEntree['QTE'] + $transfetEnt['QTE'];// ENTREE
			$sortie  = $bonsortie['QTE']  + $Declassement['QTE'] +   $reportSortie['QTE'] + $transfetSort['QTE']  ; //SORTIE
			$ecart   =	$inventmoins['QTE'] + $inventplus['QTE'];
			$rest = $entree - ($sortie) + ($ecart);


			//echo  'Ent'.$entree.' Sort'.$sortie.'<br>';
			array_push($_SESSION['DATA_REP']['ligne'], array('code_detreport'=>'', 'monlot'=>$row['MVT_MONLOT'], 'codeproduit'=>$row['CODE_PRODUIT'], 'reflot'=>$row['MVT_REFLOT'],
			'dateperemp'=>frFormat2($row['MVT_DATEPEREMP']),'produit'=>addslashes($row['PRD_LIBELLE']), 'livraison'=>$Livraison['QTE'],
			'qteentre'=>$entree,'transfertsort'=>$transfetSort['QTE'], 'transfertent'=>$transfetEnt['QTE'],'bonsortie'=>$bonsortie['QTE'],
			'qteperime'=>$qeperime, 'reportentree'=>$reportEntree['QTE'], 'reportsortie'=>$reportSortie['QTE'],'declass'=>$Declassement['QTE'],
			'qtesortie'=>$sortie, 'ecart'=>$ecart, 'stocks'=>$rest,'prix'=>$row['MVT_PV'],'unite'=>$row['ID_UNITE']));

		}
		$_SESSION['DATA_REP']['nbreLigne'] =$query->rowCount();
		//Etape 2
		header('location:addreport1.php?selectedTab=bds');
		break;

	case 'add':

		(isset($_POST['exercice']) && $_POST['exercice']!=''		? $exercice 	= trim($_POST['exercice']) 		: $exercice = '');
		(isset($_POST['datereport']) && $_POST['datereport']!=''  	? $datereport 	= trim($_POST['datereport']) 	: $datereport = '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  			? $statut 		= trim($_POST['statut']) 		: $statut = '0');
		$datereport = mysqlFormat($datereport);
		$magasin= $_SESSION['GL_USER']['MAGASIN'];
		//$statut =1;

		if(isClotureExercice($exercice)>=1){ header('location:report.php?selectedTab=bds&rs=0'); }
		$exerciceSuivant = $exercice+1;

		$libelle = "Clotûre et report de l'exervice $exercice à $exerciceSuivant";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}

		//Check exercice suivant
		if(checkExercice($exerciceSuivant)<=0){
			$sql2 ="INSERT INTO `exercice` (`ID_EXERCICE` ,`EX_LIBELLE` ,`EX_DATEDEBUT` ,`EX_DATEFIN` ,`EX_CLOTURE`)
			VALUES ('".addslashes($exerciceSuivant)."','".addslashes('Exercice budgétaire '.$exerciceSuivant)."','".$exerciceSuivant."-01-01','".$exerciceSuivant."-12-31','0');";

			$query =  $cnx->prepare($sql2); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Ajout de l'exercice (".$exerciceSuivant.')'); //updateLog($username, $idcust, $action='' )
		}
		elseif(isClotureExercice($exerciceSuivant)>=1){ header('location:report.php?selectedTab=bds&rs=1'); }

		$numauto = myDbLastId('report', 'ID_REPORT', $magasin)+1;
		$codeRep = "$numauto/$magasin";
		$codeRepSort = $codeRep;

		//Insert Sortant
		$sql  = "INSERT INTO `report` (`CODE_REPORT`, `CODE_MAGASIN`, `ID_EXERCICE`, `ID_REPORT`, `REP_LIBELLE`, `REP_NATURE`,
		`REP_DATE`, `REP_VALIDE`, `REP_DATEVALID`) VALUES ('".addslashes($codeRep)."', '".addslashes($magasin)."', '".addslashes($exercice)."',
		'".addslashes($numauto)."', '".addslashes($libelle)."','REPORT SORTANT','".addslashes($datereport)."','".addslashes($statut)."', '".date("Y-m-d H:i:s")."');";

		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout d\'un report sortant ('.$codeRep.')'); //updateLog($username, $idcust, $action='' )

		$numautoDetRep = myDbLastId('detreport', 'ID_DETREPORT', $magasin);
		$numautoMvt = myDbLastId('mouvement', 'ID_MOUVEMENT', $magasin);

		$sql1 =""; $sql2 ="";
		//Collect Data
		for($i=1; $i<=$_SESSION['DATA_REP']['nbreLigne']; $i++){
			(isset($_POST['code_detreport'.$i]) && $_POST['code_detreport'.$i] 	? $code_detreport 	= $_POST['code_detreport'.$i] 	: $code_detreport 	= '');
			(isset($_POST['iddetinventaire'.$i]) && $_POST['iddetinventaire'.$i] 	? $iddetinventaire 	= $_POST['iddetinventaire'.$i] 	: $iddetinventaire 	= '');
			(isset($_POST['codeproduit'.$i])					? $codeproduit 		= $_POST['codeproduit'.$i] 		: $codeproduit 		= '');
			(isset($_POST['produit'.$i]) 						? $produit 			= $_POST['produit'.$i] 			: $produit 			= '');
			(isset($_POST['qte'.$i]) && $_POST['qte'.$i] !='' 	? $qte 				= $_POST['qte'.$i] 				: $qte 				= '');
			(isset($_POST['unite'.$i]) 							? $unite 			= $_POST['unite'.$i] 			: $unite 			= '');
			(isset($_POST['reflot'.$i]) 						? $reflot 			= $_POST['reflot'.$i] 			: $reflot 			= '');
			(isset($_POST['monlot'.$i]) 						? $monlot			= $_POST['monlot'.$i] 			: $monlot 			= '');
			(isset($_POST['dateperemp'.$i]) 					? $dateperemp		= $_POST['dateperemp'.$i] 		: $dateperemp		= '');
			(isset($_POST['prix'.$i]) 							? $prix				= $_POST['prix'.$i] 			: $prix				= '');

			if($codeproduit!='' && $produit!='' && $qte>0) {
				$numautoDetRep++;
				$codeDetRep = "$numautoDetRep/$magasin";

				$sql1 .="INSERT INTO `detreport` (`CODE_DETREPORT`, `CODE_REPORT`, `CODE_PRODUIT`,`CODE_MAGASIN`, `ID_DETREPORT`, `REP_PRDQTE`, `REP_UNITE`,
				`REP_REFLOT`, `REP_DATEPEREMP`, `REP_PV`, `REP_PA`, `REP_PR`, `REP_MONLOT`) VALUES ( '".addslashes($codeDetRep)."',
				'".addslashes($codeRep)."', '".addslashes($codeproduit)."', '".addslashes($magasin)."', '".addslashes($numautoDetRep)."', '".addslashes($qte)."',
				'".addslashes($unite)."', '".addslashes($reflot)."', '".addslashes(mysqlFormat($dateperemp))."', '".addslashes($prix)."', '".addslashes($prix)."',
				'".addslashes($prix)."', '".addslashes($monlot)."'); ";

				$numautoMvt++;
				$codeMvt = "$numautoMvt/$magasin";

				$sql2 .="INSERT INTO `mouvement` (`CODE_MOUVEMENT`, `ID_EXERCICE`, `CODE_PRODUIT`, `CODE_MAGASIN`, `ID_MOUVEMENT`, `ID_SOURCE`,
				`MVT_DATE`, `MVT_TIME`, `MVT_QUANTITE`, `MVT_UNITE`, `MVT_NATURE`, `MVT_VALID`, `MVT_DATEVALID`, `MVT_TYPE`, `MVT_REFLOT`,
				`MVT_DATEPEREMP`,  `MVT_PV`,  `MVT_MONLOT`) VALUES ('".addslashes($codeMvt)."',  '".addslashes($exercice)."',
				'".addslashes($codeproduit)."',	'".addslashes($magasin)."',	'".addslashes($numautoMvt)."', '".addslashes($codeRep)."',
				'".addslashes($datereport)."' ,'".addslashes(date('H:i:s'))."' , '".addslashes($qte)."' , '".addslashes($unite)."',
				'REPORT SORTANT', '$statut', '".date('Y-m-d H:i:s')."','S','".addslashes($reflot)."','".addslashes(mysqlFormat($dateperemp))."',
				'".addslashes($prix)."', '".addslashes($monlot)."') ; ";
			}
		}
		if (($sql1 !='')) {
			$query =  $cnx->prepare($sql1); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout des lignes de report sortant ('.$codeRep.', Exercice '.$exercice.')'); //updateLog($username, $idcust, $action='' )

			$query =  $cnx->prepare($sql2); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout des mouvements de report sortant ('.$codeRep.', Exercice '.$exercice.')'); //updateLog($username, $idcust, $action='' )

		}
		//Insert Entrant
		$numauto2 = myDbLastId('report', 'ID_REPORT', $magasin)+1;
		$codeRep2 = "$numauto2/$magasin";

		$sql  = "INSERT INTO `report` (`CODE_REPORT`, `CODE_MAGASIN`, `ID_EXERCICE`, `ID_REPORT`, `REP_LIBELLE`, `REP_NATURE`,
		`REP_DATE`, `REP_VALIDE`, `REP_DATEVALID`, CODE_REP_SORT) VALUES ('".addslashes($codeRep2)."', '".addslashes($magasin)."', '".addslashes($exerciceSuivant)."',
		'".addslashes($numauto2)."', '".addslashes($libelle)."','REPORT ENTRANT','".addslashes($datereport)."',
		'".addslashes($statut)."', '".date("Y-m-d H:i:s")."', '".addslashes($codeRepSort)."');";

		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout d\'un report entrant ('.$codeRep2.')'); //updateLog($username, $idcust, $action='' )

		$numautoDetRep2 = myDbLastId('detreport', 'ID_DETREPORT', $magasin);
		$numautoMvt2 = myDbLastId('mouvement', 'ID_MOUVEMENT', $magasin);

		$sql1 =""; 	$sql2 ="";
		//Collect Data
		for($i=1; $i<=$_SESSION['DATA_REP']['nbreLigne']; $i++){
			(isset($_POST['code_detreport'.$i]) && $_POST['code_detreport'.$i] 	? $code_detreport 	= $_POST['code_detreport'.$i] 	: $code_detreport 	= '');
			(isset($_POST['iddetinventaire'.$i]) && $_POST['iddetinventaire'.$i] 	? $iddetinventaire 	= $_POST['iddetinventaire'.$i] 	: $iddetinventaire 	= '');
			(isset($_POST['codeproduit'.$i])					? $codeproduit 		= $_POST['codeproduit'.$i] 		: $codeproduit 		= '');
			(isset($_POST['produit'.$i]) 						? $produit 			= $_POST['produit'.$i] 			: $produit 			= '');
			(isset($_POST['qte'.$i]) && $_POST['qte'.$i] !='' 	? $qte 				= $_POST['qte'.$i] 				: $qte 				= '');
			(isset($_POST['unite'.$i]) 							? $unite 			= $_POST['unite'.$i] 			: $unite 			= '');
			(isset($_POST['reflot'.$i]) 						? $reflot 			= $_POST['reflot'.$i] 			: $reflot 			= '');
			(isset($_POST['monlot'.$i]) 						? $monlot			= $_POST['monlot'.$i] 			: $monlot 			= '');
			(isset($_POST['dateperemp'.$i]) 					? $dateperemp		= $_POST['dateperemp'.$i] 		: $dateperemp		= '');
			(isset($_POST['prix'.$i]) 							? $prix				= $_POST['prix'.$i] 			: $prix				= '');

			if($codeproduit!='' && $produit!='' && $qte>0) {
				$numautoDetRep2++;
				$codeDetRep2 = "$numautoDetRep2/$magasin";

				$sql1 .="INSERT INTO `detreport` (`CODE_DETREPORT`, `CODE_REPORT`, `CODE_PRODUIT`, `ID_DETREPORT`, `REP_PRDQTE`, `REP_UNITE`,
				`REP_REFLOT`, `REP_DATEPEREMP`, `REP_PV`, `REP_PA`, `REP_PR`, `REP_MONLOT`) VALUES ( '".addslashes($codeDetRep2)."',
				'".addslashes($codeRep2)."', '".addslashes($codeproduit)."', '".addslashes($numautoDetRep)."', '".addslashes($qte)."',
				'".addslashes($unite)."', '".addslashes($reflot)."', '".addslashes(mysqlFormat($dateperemp))."', '".addslashes($prix)."', '".addslashes($prix)."',
				'".addslashes($prix)."', '".addslashes($monlot)."'); ";

				$numautoMvt2++;
				$codeMvt2 = "$numautoMvt2/$magasin";

				$sql2 .="INSERT INTO `mouvement` (`CODE_MOUVEMENT`, `ID_EXERCICE`, `CODE_PRODUIT`, `CODE_MAGASIN`, `ID_MOUVEMENT`, `ID_SOURCE`,
				`MVT_DATE`, `MVT_TIME`, `MVT_QUANTITE`, `MVT_UNITE`, `MVT_NATURE`, `MVT_VALID`, `MVT_DATEVALID`, `MVT_TYPE`, `MVT_REFLOT`,
				`MVT_DATEPEREMP`,  `MVT_PV`,  `MVT_MONLOT`) VALUES ('".addslashes($codeMvt2)."',  '".addslashes($exerciceSuivant)."',
				'".addslashes($codeproduit)."',	'".addslashes($magasin)."',	'".addslashes($numautoMvt)."', '".addslashes($codeRep2)."',
				'".addslashes($datereport)."' ,'".addslashes(date('H:i:s'))."' , '".addslashes($qte)."' , '".addslashes($unite)."',
				'REPORT ENTRANT', '$statut', '".date('Y-m-d H:i:s')."','E','".addslashes($reflot)."','".addslashes(mysqlFormat($dateperemp))."',
				'".addslashes($prix)."', '".addslashes($monlot)."') ; ";
			}
		}
		if (($sql1 !='')) {
			$query =  $cnx->prepare($sql1); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout des lignes de report entrant ('.$codeRep2.', Exercice '.$exercice.')'); //updateLog($username, $idcust, $action='' )

			$query =  $cnx->prepare($sql2); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout des mouvements de report entrant ('.$codeRep2.', Exercice '.$exercice.')'); //updateLog($username, $idcust, $action='' )
		}

	 	//Clorurer l'exercice en cours
		$sql2 ="UPDATE `exercice` SET EX_CLOTURE=1 ,EX_DATECLOTURE='".date('Y-m-d')."' WHERE ID_EXERCICE=$exercice;";
		$query =  $cnx->prepare($sql2); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Clôture de l'exercice (".$exercice.')'); //updateLog($username, $idcust, $action='' )


		unset($_SESSION['DATA_REP']);
		header('location:dbuser.php?do=logout');
		break;

	case 'detail':
		(isset($_GET['xid']) ? $id = $_GET['xid'] : $id ='');
			//$split = preg_split('/@/',$id);
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		//REPORT
		$sql = "SELECT * FROM  `report` WHERE CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND  `CODE_REPORT` LIKE '".addslashes($id)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data
		$_SESSION['DATA_REP']=array(
		'xid'=>$row['CODE_REPORT'],
		'exercice'=>$row['ID_EXERCICE'],
		'datereport'=>frFormat2($row['REP_DATE']),
		'statut'=>$row['REP_VALIDE'],
		'nbreLigne'=>0,
		'ligne'=>array()
		);

		//LIGNES REPORT
	 	$sql = "SELECT detreport.*, produit.PRD_LIBELLE FROM `detreport` INNER JOIN produit ON (detreport.CODE_PRODUIT  LIKE produit.CODE_PRODUIT)
	 	WHERE CODE_REPORT LIKE  '".addslashes($id)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_REP']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_REP']['ligne'], array('code_detreport'=>$row['CODE_DETREPORT'],'monlot'=>$row['REP_MONLOT'],'codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']), 'qte'=>$row['REP_PRDQTE'],'unite'=>$row['REP_UNITE'],'prix'=>$row['REP_PA'], 'reflot'=>$row['REP_REFLOT'],'dateperemp'=>$row['REP_DATEPEREMP']));
		}
		$_SESSION['DATA_REP']['nbreLigne'] = $query->rowCount();
		header('location:detailreport.php?selectedTab=bds&rst=1');
		break;

	case 'journal':
		(isset($_GET['xid']) ? $id = $_GET['xid'] : $id ='');
		//$split = preg_split('/@/',$id);
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		//REPORT
		$sql = "SELECT * FROM  `report` WHERE CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND  `CODE_REPORT` LIKE '".addslashes($id)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data
		$_SESSION['DATA_REP']=array(
		'xid'=>$row['CODE_REPORT'],
		'exercice'=>$row['ID_EXERCICE'],
		'datereport'=>frFormat2($row['REP_DATE']),
		'statut'=>$row['REP_VALIDE'],
		'nbreLigne'=>0,
		'ligne'=>array()
		);

		//LIGNES REPORT
		$sql = "SELECT detreport.*, produit.PRD_LIBELLE FROM `detreport` INNER JOIN produit ON (detreport.CODE_PRODUIT  LIKE produit.CODE_PRODUIT)
	 	WHERE CODE_REPORT LIKE  '".addslashes($id)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_REP']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_REP']['ligne'], array('code_detreport'=>$row['CODE_DETREPORT'],'monlot'=>$row['REP_MONLOT'],'codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']), 'qte'=>$row['REP_PRDQTE'],'unite'=>$row['REP_UNITE'],'prix'=>$row['REP_PA'], 'reflot'=>$row['REP_REFLOT'],'dateperemp'=>$row['REP_DATEPEREMP']));
		}
		$_SESSION['DATA_REP']['nbreLigne'] = $query->rowCount();

		//LIGNES MOUVEMENT
		$sql = "SELECT * FROM `mouvement` WHERE MVT_NATURE LIKE 'REPORT%' AND ID_SOURCE LIKE '".addslashes($id)."' ORDER BY CODE_PRODUIT ASC";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_REP']['journal'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_REP']['journal'], array('codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>'', 'qte'=>$row['MVT_QUANTITE'], 'qtelvr'=>$row['MVT_QUANTITE'], 'unite'=>$row['MVT_UNITE'], 'valide'=>$row['MVT_VALID'], 'prix'=>$row['MVT_PV']));
		}

		$_SESSION['DATA_REP']['nbreLigne2'] = $query->rowCount();
		header('location:journalreport.php?selectedTab=bds&rst=1');
		break;


	default : ///Nothing
}
}elseif($myaction !='')
switch($myaction){

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
		//REPORT
		$sql = "SELECT * FROM  `report` WHERE CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND  `CODE_REPORT` LIKE '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data
		$_SESSION['DATA_REP']=array(
		'xid'=>$row['CODE_REPORT'],
		'exercice'=>$row['ID_EXERCICE'],
		'datereport'=>frFormat2($row['REP_DATE']),
		'statut'=>$row['REP_VALIDE'],
		'nbreLigne'=>0,
		'ligne'=>array()
		);

		//LIGNES REPORT
		$sql = "SELECT detreport.*, produit.CND_LIBELLE FROM `detreport` INNER JOIN produit ON (detreport.CODE_PRODUIT =produit.CODE_PRODUIT)
		WHERE CODE_REPORT LIKE '".addslashes($id)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_REP']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_REP']['ligne'], array('code_detreport'=>$row['CODE_DETREPORT'],'monlot'=>$row['REP_MONLOT'],'codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']), 'stocks'=>$row['REP_PRDQTE'],'unite'=>$row['REP_UNITE'],'prix'=>$row['REP_PA'], 'reflot'=>$row['REP_REFLOT'],'dateperemp'=>$row['REP_DATEPEREMP']));
		}
		$_SESSION['DATA_REP']['nbreLigne'] = $query->rowCount();

		header('location:validreport.php?selectedTab=bds&rst=1');
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
			$sql = "DELETE FROM  `report` WHERE `CODE_REPORT` LIKE  '".addslashes($split[0])."'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
		}
		header('location:report.php?selectedTab=bds&rst=1');
		break;

	default : ///Nothing
		//header('location:../index.php');

}
elseif($myaction =='' && $do ='') header('location:../index.php');

?>
