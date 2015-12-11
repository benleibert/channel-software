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

//This variable $act say what to do (add, delete, ...)
(isset($_GET['do']) && $_GET['do']!='' ? $do = $_GET['do'] : $do = '');
(isset($_POST['myaction']) && $_POST['myaction']!='' ? $myaction = $_POST['myaction'] : $myaction = '');

if($myaction =='' && $do !=''){
	switch($do){
	case 'next': //Connait la source
		(isset($_POST['exercice']) && $_POST['exercice']!=''			? $exercice 	= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datetransfert']) && $_POST['datetransfert']!=''  ? $datetransfert = trim($_POST['datetransfert']) 	: $datetransfert = '');
		(isset($_POST['reftransfert']) && $_POST['reftransfert']!=''  	? $reftransfert = trim($_POST['reftransfert']) 	: $reftransfert = '');
		(isset($_POST['magasin_dest']) && $_POST['magasin_dest']!=''  	? $magasin_dest  = trim($_POST['magasin_dest']) 	: $magasin_dest = '');
		(isset($_POST['libelleetat']) && $_POST['libelleetat']!=''  	? $libelleetat 	 = trim($_POST['libelleetat']) 		: $libelleetat = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 	 = trim($_POST['nbreLigne']) 		: $nbreLigne = '');

		//Data
		$_SESSION['DATA_TRS']=array(
		'exercice'=>$exercice,
		'datetransfert'=>$datetransfert,
		'reftransfert'=>$reftransfert,
		'nature'=>$nature,
		'magasin_srce'=>$_SESSION['GL_USER']['MAGASIN'],
		'magasin_dest'=>$magasin_dest,
		'libelleetat'=>$libelleetat,
		'nbreLigne'=>$nbreLigne
		);

		$_SESSION['GL_USER']['JOUR'] = mysqlFormat($datetransfert);

		//Etape 2
		header('location:addtransfert11.php?selectedTab=bds');

		break;

	//TRANSFERT SORTANT ET ENTRANT 1=>'Transfert sortant', 2=>'Transfert entrant'

	case 'add':  //TRANSFERT SORTANT
		(isset($_POST['exercice']) && $_POST['exercice']!=''			? $exercice 	= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datetransfert']) && $_POST['datetransfert']!=''  ? $datetransfert = trim($_POST['datetransfert']) 		: $datetransfert = '');
		(isset($_POST['reftransfert']) && $_POST['reftransfert']!=''  	? $reftransfert = trim($_POST['reftransfert']) 	: $reftransfert = '');
		(isset($_POST['nature1']) && $_POST['nature1']!='0'  ? $nature = trim($_POST['nature1']) 	: $nature = '');
		(isset($_POST['magasin_dest']) && $_POST['magasin_dest']!='0'  	? $magasin_dest 	= trim($_POST['magasin_dest']) 		: $magasin_dest = '');
		(isset($_POST['libelleetat']) && $_POST['libelleetat']!=''  	? $libelleetat 	 = trim($_POST['libelleetat']) 		: $libelleetat = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 	= trim($_POST['nbreLigne']) 		: $nbreLigne = '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 		= trim($_POST['statut']) 			: $statut = '0');

		$datetransfert = mysqlFormat($datetransfert);
		$vdate ='0000-00-00';
		$magasin_srce= $_SESSION['GL_USER']['MAGASIN'];
		$statut = 1;

		$numauto = myDbLastId('transfert', 'ID_TRANSFERT', $magasin_srce)+1;
		$codeTrs = "$numauto/$magasin_srce";

		//Insert
		$sql  = "INSERT INTO `transfert` (`CODE_TRANSFERT`, `CODE_MAGASIN`, `ID_EXERCICE`, `REF_TRANSFERT`, `ID_TRANSFERT`,
		`MAG_CODE_MAGASIN_SRCE`, `MAG_CODE_MAGASIN_DEST`,`TRS_DATE`, `TRS_NATURE`, `TRS_VALIDE`, `TRS_DATEVALID`, `TRS_LIBELLE`)
 		VALUES ('".addslashes($codeTrs)."','".addslashes($magasin_srce)."', '".addslashes($exercice)."', '".addslashes($reftransfert)."',
 		'".addslashes($numauto)."','".addslashes($magasin_srce)."', '".addslashes($magasin_dest)."',
 		'".addslashes($datetransfert)."','1', '$statut', '".addslashes(date('Y-m-d H:i:s'))."', '".addslashes($libelleetat)."')";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		if($reftransfert ==''){$reftransfert = 'TRS-'.$numauto;}
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Ajout d\'un transfert ($codeTrs, $reftransfert)"); //updateLog($username, $idcust, $action='' )

		//Data
		$_SESSION['DATA_TRS']=array(
		'exercice'=>$exercice,
		'datetransfert'=>$datetransfert,
		'reftransfert'=>$reftransfert,
		'nature'=>$nature,
		'ligne'=> array(),
		'nbreLigne'=>$nbreLigne
		);

		$sql1 ="";
		$sql2 ="";

		$numautoDetTrs = myDbLastId('dettransfert', 'ID_DETTRANSFERT', $magasin_srce);
		$numautoMvt = myDbLastId('mouvement', 'ID_MOUVEMENT', $magasin_srce);

		//Collect Data
		$_SESSION['DATA_TRS']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_TRS']['nbreLigne']; $i++){
			(isset($_POST['code_dettransfert'.$i]) && $_POST['code_dettransfert'.$i] 	? $code_dettransfert 	= $_POST['code_dettransfert'.$i] 	: $code_dettransfert 	= '');
			(isset($_POST['monlot'.$i]) && $_POST['monlot'.$i] 					? $monlot 			= $_POST['monlot'.$i] 			: $monlot 	= '');
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 	= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 		= '');
			(isset($_POST['unite'.$i]) && $_POST['unite'.$i]				? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');
			(isset($_POST['prix'.$i]) && $_POST['prix'.$i]					? $prix 		= $_POST['prix'.$i] 		: $prix 		= '');
			(isset($_POST['reflot'.$i]) && $_POST['reflot'.$i]				? $reflot		= $_POST['reflot'.$i] 		: $reflot 		= '');
			(isset($_POST['dateperemp'.$i]) && $_POST['dateperemp'.$i]		? $dateperemp 	= $_POST['dateperemp'.$i] 	: $dateperemp 	= '');

			if($codeproduit!='' && $produit!='' && $qte!='') {

				$numautoDetTrs++;
				$codeDetTrs = "$numautoDetTrs/$magasin_srce";

			 	$sql1 .="INSERT INTO `dettransfert` (`CODE_DETTRANSFERT`, `CODE_PRODUIT`, `CODE_MAGASIN`, `CODE_TRANSFERT`, `ID_DETTRANSFERT`,
				`TRS_PRDQTE`, `TRS_PRDRECU`, `TRS_UNITE`, `TRS_REFLOT`, `TRS_DATEPEREMP`, `TRS_PV`, `TRS_MONLOT`)
				VALUES ('".addslashes($codeDetTrs)."', '".addslashes($codeproduit)."',	'".addslashes($magasin_srce)."', '".addslashes($codeTrs)."','".addslashes($numautoDetTrs)."',
				'".addslashes($qte)."' , '".addslashes($qte)."' ,'".addslashes($unite)."', '".addslashes($reflot)."',
				'".addslashes(mysqlFormat($dateperemp))."',	'".addslashes($prix)."' ,'".addslashes($monlot)."' ); ";

				$numautoMvt++;
				$codeMvt = "$numautoMvt/$magasin_srce";

				$sql2 .="INSERT INTO `mouvement` (`CODE_MOUVEMENT`, `ID_EXERCICE`, `CODE_PRODUIT`, `CODE_MAGASIN`, `ID_MOUVEMENT`, `ID_SOURCE`,
				`MVT_DATE`, `MVT_TIME`, `MVT_QUANTITE`, `MVT_UNITE`, `MVT_NATURE`, `MVT_VALID`, `MVT_DATEVALID`, `MVT_TYPE`, `MVT_REFLOT`,
				`MVT_DATEPEREMP`,  `MVT_PV`,  `MVT_MONLOT`) VALUES ('".addslashes($codeMvt)."',  '".addslashes($exercice)."',
				'".addslashes($codeproduit)."',	'".addslashes($magasin_srce)."',	'".addslashes($numautoMvt)."', '".addslashes($codeTrs)."',
				'".addslashes($datetransfert)."' ,'".addslashes(date('H:i:s'))."' ,	'".addslashes($qte)."' , '".addslashes($unite)."',
				'TRANSFERT SORTANT', '$statut', '".date('Y-m-d H:i:s')."','S','".addslashes($reflot)."','".addslashes(mysqlFormat($dateperemp))."',
				'".addslashes($prix)."', '".addslashes($monlot)."') ; ";
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
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Ajout des lignes de transfert ($codeTrs, $reftransfert)"); //updateLog($username, $idcust, $action='' )

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}
			$query =  $cnx->prepare($sql2); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Ajout d\'un mouvement($codeTrs, $reftransfert)"); //updateLog($username, $idcust, $action='' )
		}



		//TRANSFERT ENTRANT
		(isset($_POST['exercice']) && $_POST['exercice']!=''			? $exercice 		= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datetransfert']) && $_POST['datetransfert']!=''  ? $datetransfert 	= trim($_POST['datetransfert']) 	: $datetransfert = '');
		(isset($_POST['reftransfert']) && $_POST['reftransfert']!=''  	? $reftransfert 	= trim($_POST['reftransfert']) 	: $reftransfert = '');
		(isset($_POST['magasin_dest']) && $_POST['magasin_dest']!='0'  	? $magasin_dest 	= trim($_POST['magasin_dest']) 		: $magasin_dest = '');
		(isset($_POST['libelleetat']) && $_POST['libelleetat']!=''  	? $libelleetat 	 	= trim($_POST['libelleetat']) 		: $libelleetat = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 		= trim($_POST['nbreLigne']) 		: $nbreLigne = '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 			= trim($_POST['statut']) 			: $statut = '0');

		$datetransfert = mysqlFormat($datetransfert);
		$vdate ='0000-00-00';
		$magasin_srce = $_SESSION['GL_USER']['MAGASIN'];
		$statut  = 1;

		$numauto = myDbLastId('transfert', 'ID_TRANSFERT', $magasin_dest)+1;
		$codeTrs = "$numauto/$magasin_dest";

		//Insert
		$sql  = "INSERT INTO `transfert` (`CODE_TRANSFERT`, `CODE_MAGASIN`, `ID_EXERCICE`, `REF_TRANSFERT`, `ID_TRANSFERT`,
		`MAG_CODE_MAGASIN_SRCE`, `MAG_CODE_MAGASIN_DEST`,`TRS_DATE`, `TRS_NATURE`, `TRS_VALIDE`, `TRS_DATEVALID`, `TRS_LIBELLE`)
 		VALUES ('".addslashes($codeTrs)."','".addslashes($magasin_dest)."','".addslashes($exercice)."', '".addslashes($reftransfert)."',
 		'".addslashes($numauto)."','".addslashes($magasin_srce)."', '".addslashes($magasin_dest)."', '".addslashes($datetransfert)."','2', '$statut',
		'".addslashes(date('Y-m-d H:i:s'))."', '".addslashes($libelleetat)."')";

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
		if($reftransfert ==''){$reftransfert = 'TRS-'.$insert_id;}
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Ajout d\'un transfert ($codeTrs, $reftransfert)"); //updateLog($username, $idcust, $action='' )

		//Data
		$_SESSION['DATA_TRS']=array(
		'exercice'=>$exercice,
		'datetransfert'=>$datetransfert,
		'reftransfert'=>$reftransfert,
		'nature'=>$nature,
		'ligne'=> array(),
		'nbreLigne'=>$nbreLigne
		);

		$sql1 ="";
		$sql2 ="";
		$numautoDetTrs = myDbLastId('dettransfert', 'ID_DETTRANSFERT', $magasin_dest);
		$numautoMvt = myDbLastId('mouvement', 'ID_MOUVEMENT', $magasin_dest);

		//Collect Data
		$_SESSION['DATA_TRS']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_TRS']['nbreLigne']; $i++){
			(isset($_POST['code_dettransfert'.$i]) && $_POST['code_dettransfert'.$i] 	? $code_dettransfert 	= $_POST['code_dettransfert'.$i] 	: $code_dettransfert 	= '');
			(isset($_POST['monlot'.$i]) && $_POST['monlot'.$i] 					? $monlot 			= $_POST['monlot'.$i] 			: $monlot 	= '');
			(isset($_POST['codeproduit'.$i])	? $codeproduit 		= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['produit'.$i]) 		? $produit 			= $_POST['produit'.$i] 		: $produit 	= '');
			(isset($_POST['qte'.$i]) 			? $qte 				= $_POST['qte'.$i] 			: $qte 		= '');
			(isset($_POST['unite'.$i]) && $_POST['unite'.$i]		? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');
			(isset($_POST['prix'.$i]) && $_POST['prix'.$i]			? $prix 		= $_POST['prix'.$i] 		: $prix 		= '');
			(isset($_POST['reflot'.$i]) && $_POST['reflot'.$i]			? $reflot		= $_POST['reflot'.$i] 		: $reflot 		= '');
			(isset($_POST['dateperemp'.$i]) && $_POST['dateperemp'.$i]	? $dateperemp 	= $_POST['dateperemp'.$i] 	: $dateperemp 	= '');

			if($codeproduit!='' && $produit!='' && $qte!='') {
				$numautoDetTrs++;
				$codeDetTrs = "$numautoDetTrs/$magasin_dest";

				$numautoMvt++;
				$codeMvt = "$numautoMvt/$magasin_dest";

				$sql1 .="INSERT INTO `dettransfert` (`CODE_DETTRANSFERT`, `CODE_PRODUIT`, `CODE_MAGASIN`, `CODE_TRANSFERT`, `ID_DETTRANSFERT`,
				`TRS_PRDQTE`, `TRS_PRDRECU`, `TRS_UNITE`, `TRS_REFLOT`, `TRS_DATEPEREMP`, `TRS_PV`, `TRS_MONLOT`)
				VALUES ('".addslashes($codeDetTrs)."', '".addslashes($codeproduit)."', 	'".addslashes($magasin_dest)."', '".addslashes($codeTrs)."','".addslashes($numautoDetTrs)."',
				'".addslashes($qte)."' , '".addslashes($qte)."' ,'".addslashes($unite)."', '".addslashes($reflot)."',
				'".addslashes(mysqlFormat($dateperemp))."',	'".addslashes($prix)."' ,'".addslashes($monlot)."' ); ";

				$sql2 .="INSERT INTO `mouvement` (`CODE_MOUVEMENT`, `ID_EXERCICE`, `CODE_PRODUIT`, `CODE_MAGASIN`, `ID_MOUVEMENT`, `ID_SOURCE`,
				`MVT_DATE`, `MVT_TIME`, `MVT_QUANTITE`, `MVT_UNITE`, `MVT_NATURE`, `MVT_VALID`, `MVT_DATEVALID`, `MVT_TYPE`, `MVT_REFLOT`,
				`MVT_DATEPEREMP`,  `MVT_PV`,  `MVT_MONLOT`) VALUES ('".addslashes($codeMvt)."',  '".addslashes($exercice)."',
				'".addslashes($codeproduit)."',	'".addslashes($magasin_dest)."',	'".addslashes($numautoMvt)."', '".addslashes($codeTrs)."',
				'".addslashes($datetransfert)."' ,'".addslashes(date('H:i:s'))."' ,	'".addslashes($qte)."' , '".addslashes($unite)."',
				'TRANSFERT ENTRANT', '$statut', '".date('Y-m-d H:i:s')."','E','".addslashes($reflot)."','".addslashes(mysqlFormat($dateperemp))."',
				 '".addslashes($prix)."', '".addslashes($monlot)."') ; ";
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
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Ajout des lignes de transfert ($codeTrs, $reftransfert)"); //updateLog($username, $idcust, $action='' )

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}
			$query =  $cnx->prepare($sql2); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Ajout d\'un mouvement($codeTrs, $reftransfert)"); //updateLog($username, $idcust, $action='' )
		}


		unset($_SESSION['DATA_TRS']);
		header('location:transfert.php?selectedTab=bds&rs=1');
		break;

	case 'update':
		(isset($_POST['xid']) && $_POST['xid']!=''			? $xid 	= trim($_POST['xid']) 			: $xid = '');
		(isset($_POST['exercice']) && $_POST['exercice']!=''				? $exercice 		= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datetransfert']) && $_POST['datetransfert']!=''  	? $datetransfert 	= trim($_POST['datetransfert']) 	: $datetransfert = '');
		(isset($_POST['reftransfert']) && $_POST['reftransfert']!=''  	? $reftransfert 	= trim($_POST['reftransfert']) 	: $reftransfert = '');
		(isset($_POST['magasin_srce']) && $_POST['magasin_srce']!='0'  		? $magasin_srce 	= trim($_POST['magasin_srce']) 		: $magasin_srce = '');
		(isset($_POST['magasin_dest']) && $_POST['magasin_dest']!='0'  		? $magasin_dest 	= trim($_POST['magasin_dest']) 		: $magasin_dest = '');
		(isset($_POST['libelleetat']) && $_POST['libelleetat']!=''  	? $libelleetat 	 = trim($_POST['libelleetat']) 		: $libelleetat = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  			? $nbreLigne 		= trim($_POST['nbreLigne']) 		: $nbreLigne = '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  					? $statut 			= trim($_POST['statut']) 			: $statut = '0');

		$datetransfert = mysqlFormat($datetransfert);
		$vdate ='0000-00-00';
		$magasin= $_SESSION['GL_USER']['MAGASIN'];
		$statut = 1;

		//Insert
		$sql  = "UPDATE `transfert` SET `TRS_LIBELLE`='".addslashes($libelleetat)."' , 	`MAG_CODE_MAGASIN_DEST`='".addslashes($magasin_dest)."' ,
		`ID_EXERCICE`='".addslashes($exercice)."' ,`TRS_DATE`='".addslashes($datetransfert)."' ,
		`TRS_VALIDE`= '$statut',`TRS_DATEVALID`='".date('Y-m-d H:i:s')."'  WHERE CODE_TRANSFERT LIKE '".addslashes($xid)."';";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Modification d\'un transfert ('.$xid.', '.$reftransfert.')'); //updateLog($username, $idcust, $action='' )

		//Data
		$_SESSION['DATA_TRS']=array(
		'xid'=>$xid,
		'exercice'=>$exercice,
		'datetransfert'=>$datetransfert,
		'reftransfert'=>$reftransfert,
		'magasin_srce'=>$magasin,
		'magasin_dest'=>$magasin_dest,
		'libelleetat'=>$libelleetat,
		'nature'=>$nature,
		'ligne'=> array(),
		'nbreLigne'=>$nbreLigne
		);

		$sql1 ="";
		$sql2 ="";

		$numautoDetTrs = myDbLastId('dettransfert', 'ID_DETTRANSFERT', $magasin);
		$numautoMvt = myDbLastId('mouvement', 'ID_MOUVEMENT', $magasin);

		//Collect Data
		$_SESSION['DATA_TRS']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_TRS']['nbreLigne']; $i++){
			(isset($_POST['code_dettransfert'.$i]) && $_POST['code_dettransfert'.$i] 	? $code_dettransfert 	= $_POST['code_dettransfert'.$i] 	: $code_dettransfert 	= '');
			(isset($_POST['monlot'.$i]) && $_POST['monlot'.$i] 					? $monlot 			= $_POST['monlot'.$i] 			: $monlot 	= '');
			(isset($_POST['oldcodeproduit'.$i])? $oldcodeproduit 	= $_POST['oldcodeproduit'.$i] 	: $oldcodeproduit 	= '');
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 	= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 		= '');
			(isset($_POST['unite'.$i]) && $_POST['unite'.$i]				? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');
			(isset($_POST['prix'.$i]) && $_POST['prix'.$i]					? $prix 		= $_POST['prix'.$i] 		: $prix 		= '');
			(isset($_POST['reflot'.$i]) && $_POST['reflot'.$i]				? $reflot		= $_POST['reflot'.$i] 		: $reflot 		= '');
			(isset($_POST['dateperemp'.$i]) && $_POST['dateperemp'.$i]		? $dateperemp 	= $_POST['dateperemp'.$i] 	: $dateperemp 	= '');

			if($oldcodeproduit!='' && $codeproduit!='' && $produit!='' && $qte!='') {
				 $sql1 .="UPDATE `dettransfert` SET `CODE_PRODUIT`='".addslashes($codeproduit)."'  ,`TRS_PRDQTE`='".addslashes($qte)."' ,
				`TRS_UNITE`='".addslashes($unite)."', CODE_MAGASIN='".addslashes($magasin)."',  TRS_MONLOT='".addslashes($monlot)."'
				WHERE CODE_DETTRANSFERT='".addslashes($code_dettransfert)."';";

				$sql2 .="UPDATE `mouvement` SET `CODE_PRODUIT`='".addslashes($codeproduit)."' ,`ID_EXERCICE`='".addslashes($exercice)."' ,`CODE_MAGASIN`='".addslashes($magasin)."' ,
				`ID_SOURCE`='".addslashes($xid)."' ,	`MVT_DATE`='".addslashes($datetransfert)."' ,`MVT_QUANTITE`='".addslashes($qte)."' ,
				`MVT_UNITE`='".addslashes($unite)."', `MVT_PV`='".addslashes($prix)."',	`MVT_NATURE`='TRANSFERT SORTANT',`MVT_VALID`='$statut',
				MVT_MONLOT=='".addslashes($monlot)."' WHERE CODE_PRODUIT='".addslashes($oldcodeproduit)."' AND `MVT_NATURE`='TRANSFERT SORTANT' AND `ID_SOURCE`='".addslashes($xid)."'
				AND `MVT_TYPE`='S' ;";

				//array_push($_SESSION['DATA_TRS']['ligne'], array('codeproduit'=>$codeproduit, 'produit'=>$produit, 'qte'=>$qte, 'unite'=>$unite));
			}
			elseif($oldcodeproduit=='' && $codeproduit!='' && $produit!='' && $qte!='') {

				$numautoDetTrs++;
				$codeDetTrs = "$numautoDetTrs/$magasin";

				$numautoMvt++;
				$codeMvt = "$numautoMvt/$magasin";

				$sql1 .="INSERT INTO `dettransfert` (`CODE_DETTRANSFERT`, `CODE_PRODUIT`, `CODE_MAGASIN`, `CODE_TRANSFERT`, `ID_DETTRANSFERT`,
				`TRS_PRDQTE`, `TRS_PRDRECU`, `TRS_UNITE`, `TRS_REFLOT`, `TRS_DATEPEREMP`, `TRS_PV`, `TRS_MONLOT`)
				VALUES ('".addslashes($codeDetTrs)."', '".addslashes($codeproduit)."',	'".addslashes($magasin)."', '".addslashes($xid)."','".addslashes($numautoDetTrs)."',
				'".addslashes($qte)."' , '".addslashes($qte)."' ,'".addslashes($unite)."', '".addslashes($reflot)."',
				'".addslashes(mysqlFormat($dateperemp))."',	'".addslashes($prix)."' ,'".addslashes($monlot)."' ); ";

				$sql2 .="INSERT INTO `mouvement` (`ID_EXERCICE` ,`CODE_PRODUIT` ,`ID_SOURCE` ,`CODE_MAGASIN` ,`MVT_DATE` ,`MVT_TIME` ,`MVT_QUANTITE` ,
				`MVT_UNITE` ,`MVT_NATURE` ,	`MVT_VALID`,`MVT_TYPE`, MVT_REFLOT, MVT_DATEPEREMP,`MVT_PV`,MVT_MONLOT) VALUES ('".addslashes($exercice)."','".addslashes($codeproduit)."',
				'".addslashes($insert_id)."', '".addslashes($magasin)."', '".addslashes($datetransfert)."' ,'".addslashes(date('H:i:s'))."',
				'".addslashes($qte)."' , '".addslashes($unite)."' ,'TRANSFERT SORTANT','$statut', 'S',
				'".addslashes($reflot)."' ,'".addslashes(mysqlFormat($dateperemp))."','".addslashes($prix)."', '".addslashes($monlot)."') ; ";
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
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Modification des lignes de transfert ('.$xid.', '.$reftransfert.')'); //updateLog($username, $idcust, $action='' )

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}
			$query =  $cnx->prepare($sql2); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Modification d\'un mouvement('.$xid.', dotation n°'.$reftransfert.')'); //updateLog($username, $idcust, $action='' )
			$sql2;
		}
		unset($_SESSION['DATA_TRS']);
		header('location:transfert.php?selectedTab=bds&rs=2');
		break;

	case 'validate':
		(isset($_POST['xid']) && $_POST['xid']!=''			? $xid 	= trim($_POST['xid']) 			: $xid = '');
		(isset($_POST['exercice']) && $_POST['exercice']!=''				? $exercice 		= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datetransfert']) && $_POST['datetransfert']!=''  	? $datetransfert 	= trim($_POST['datetransfert']) 	: $datetransfert = '');
		(isset($_POST['reftransfert']) && $_POST['reftransfert']!=''  	? $reftransfert 	= trim($_POST['reftransfert']) 	: $reftransfert = '');
		(isset($_POST['magasin_srce']) && $_POST['magasin_srce']!=''  		? $magasin_srce 	= trim($_POST['magasin_srce']) 		: $magasin_srce = '');
		(isset($_POST['nature']) && $_POST['nature']!='0'  ? $nature = trim($_POST['nature']) 	: $nature = '');
		(isset($_POST['magasin_dest']) && $_POST['magasin_dest']!='0'  		? $magasin_dest 	= trim($_POST['magasin_dest']) 		: $magasin_dest = '');
		(isset($_POST['libelleetat']) && $_POST['libelleetat']!=''  	? $libelleetat 	 = trim($_POST['libelleetat']) 		: $libelleetat = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  			? $nbreLigne 		= trim($_POST['nbreLigne']) 		: $nbreLigne = '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  					? $statut 			= trim($_POST['statut']) 			: $statut = '0');
		$datetransfert = mysqlFormat($datetransfert);
		$vdate ='0000-00-00';
		$magasin= $_SESSION['GL_USER']['MAGASIN'];

		//Insert
		$sql  = "UPDATE `transfert` SET `TRS_VALIDE`= '$statut',`TRS_DATEVALID`='".date('Y-m-d H:i:s')."'
		WHERE  CODE_TRANSFERT LIKE '".addslashes($xid)."' ";

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
		if($reftransfert ==''){$reftransfert = 'TRS-'.$insert_id;}
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Validation d\'un transfert ('.$xid.', '.$reftransfert.')'); //updateLog($username, $idcust, $action='' )

		//Data
		//Data
		$_SESSION['DATA_TRS']=array(
		'xid'=>$xid,
		'exercice'=>$exercice,
		'datetransfert'=>$datetransfert,
		'reftransfert'=>$reftransfert,
		'magasin_dest'=>$magasin_dest,
		'libelleetat'=>$libelleetat,
		'nature'=>$nature,
		'ligne'=> array(),
		'nbreLigne'=>$nbreLigne
		);

		$sql1 ="";$sql2 ="";
		//Collect Data
		$_SESSION['DATA_TRS']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_TRS']['nbreLigne']; $i++){
			(isset($_POST['code_dettransfert'.$i]) && $_POST['code_dettransfert'.$i] 	? $code_dettransfert 	= $_POST['code_dettransfert'.$i] 	: $code_dettransfert 	= '');
			(isset($_POST['monlot'.$i]) && $_POST['monlot'.$i] 					? $monlot 			= $_POST['monlot'.$i] 			: $monlot 	= '');
			(isset($_POST['oldcodeproduit'.$i])? $oldcodeproduit 	= $_POST['oldcodeproduit'.$i] 	: $oldcodeproduit 	= '');
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 	= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 		= '');
			(isset($_POST['unite'.$i]) && $_POST['unite'.$i]				? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');
			(isset($_POST['prix'.$i]) && $_POST['prix'.$i]					? $prix 		= $_POST['prix'.$i] 		: $prix 		= '');
			(isset($_POST['reflot'.$i]) && $_POST['reflot'.$i]				? $reflot		= $_POST['reflot'.$i] 		: $reflot 		= '');
			(isset($_POST['dateperemp'.$i]) && $_POST['dateperemp'.$i]		? $dateperemp 	= $_POST['dateperemp'.$i] 	: $dateperemp 	= '');

			if($oldcodeproduit!='' && $codeproduit!='' && $produit!='' && $qte!='') {

				$sql1 .="UPDATE `mouvement` SET `MVT_VALID`='1', `MVT_TYPE`='S',`MVT_DATEVALID`='".date('Y-m-d H:i:s')."'
				 WHERE `CODE_PRODUIT`='".addslashes($oldcodeproduit)."' AND ID_SOURCE LIKE '".addslashes($xid)."' AND MVT_NATURE='TRANSFERT SORTANT';";
			}
		}
		if (($sql1 !='')) {

			$query =  $cnx->prepare($sql1); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Validation des lignes de transfert ('.$xid.', '.$reftransfert.')'); //updateLog($username, $idcust, $action='' )
		}
		unset($_SESSION['DATA_TRS']);
		//echo $sql1, '<br><br>',$sql2;
		header('location:transfert.php?selectedTab=bds&rs=3');
		break;

	case 'detail':
		(isset($_GET['xid']) ? $xid = $_GET['xid'] : $xid ='');
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		//TRANSFERT
		$sql = "SELECT * FROM  `transfert` WHERE  `CODE_TRANSFERT` LIKE '".addslashes($xid)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data  CDE_STATUT
		$_SESSION['DATA_TRS']=array(
		'xid'=>$row['CODE_TRANSFERT'],
		'exercice'=>$row['ID_EXERCICE'],
		'datetransfert'=>frFormat2($row['TRS_DATE']),
		'reftransfert'=>$row['REF_TRANSFERT'],
		'magasin'=>$row['CODE_MAGASIN'],
		'magasin_srce'=>$row['MAG_CODE_MAGASIN_SRCE'],
		'magasin_dest'=>$row['MAG_CODE_MAGASIN_DEST'],
		'nature'=>$row['TRS_NATURE'],
		'libelleetat'=>$row['TRS_LIBELLE'],
		'statut'=>$row['TRS_VALIDE'],
		'nbreLigne'=>0
		);

		//LIGNES TRANSFERT
		$sql = "SELECT * FROM `dettransfert` INNER JOIN produit ON (produit.CODE_PRODUIT LIKE dettransfert.CODE_PRODUIT)
		WHERE CODE_TRANSFERT LIKE '".addslashes($xid)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_TRS']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_TRS']['ligne'], array('code_dettransfert'=>$row['CODE_DETTRANSFERT'], 'monlot'=>$row['TRS_MONLOT'],'codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>$row['PRD_LIBELLE'], 'qte'=>$row['TRS_PRDQTE'],'prix'=>$row['TRS_PV'], 'unite'=>$row['TRS_UNITE'],'reflot'=>$row['TRS_REFLOT'],'dateperemp'=>$row['TRS_DATEPEREMP']));
		}
		$_SESSION['DATA_TRS']['nbreLigne'] = $query->rowCount();
		header('location:detailtransfert.php?selectedTab=bds&rst=1');
		break;

	case 'journal':
		(isset($_GET['xid']) ? $xid = $_GET['xid'] : $xid ='');
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		//TRANSFERT
		$sql = "SELECT * FROM  `transfert` WHERE  `CODE_TRANSFERT` LIKE '".addslashes($xid)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data  CDE_STATUT
		$_SESSION['DATA_TRS']=array(
		'xid'=>$row['CODE_TRANSFERT'],
		'exercice'=>$row['ID_EXERCICE'],
		'datetransfert'=>frFormat2($row['TRS_DATE']),
		'reftransfert'=>$row['REF_TRANSFERT'],
		'magasin'=>$row['CODE_MAGASIN'],
		'magasin_srce'=>$row['MAG_CODE_MAGASIN_SRCE'],
		'magasin_dest'=>$row['MAG_CODE_MAGASIN_DEST'],
		'nature'=>$row['TRS_NATURE'],
		'libelleetat'=>$row['TRS_LIBELLE'],
		'statut'=>$row['TRS_VALIDE'],
		'nbreLigne'=>0
		);

		//LIGNES TRANSFERT
		$sql = "SELECT * FROM `dettransfert` INNER JOIN produit ON (produit.CODE_PRODUIT LIKE dettransfert.CODE_PRODUIT)
		WHERE CODE_TRANSFERT LIKE '".addslashes($xid)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_TRS']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_TRS']['ligne'], array('code_dettransfert'=>$row['CODE_DETTRANSFERT'], 'monlot'=>$row['TRS_MONLOT'],'codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>$row['PRD_LIBELLE'], 'qte'=>$row['TRS_PRDQTE'],'prix'=>$row['TRS_PV'], 'unite'=>$row['TRS_UNITE'],'reflot'=>$row['TRS_REFLOT'],'dateperemp'=>$row['TRS_DATEPEREMP']));
		}
		$_SESSION['DATA_TRS']['nbreLigne'] = $query->rowCount();

		//LIGNES MOUVEMENT
		 $sql = "SELECT * FROM `mouvement`mouvement.*, produit.PRD_LIBELLE FROM `mouvement` INNER JOIN produit ON (mouvement.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
		 WHERE MVT_NATURE LIKE 'TRANSFERT%' AND ID_SOURCE = '".addslashes($xid)."' ORDER BY CODE_PRODUIT ASC";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_TRS']['journal'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_TRS']['journal'], array('codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']), 'qte'=>$row['MVT_QUANTITE'], 'qtelvr'=>$row['MVT_QUANTITE'], 'unite'=>$row['MVT_UNITE'], 'valide'=>$row['MVT_VALID'],'prix'=>$row['MVT_PA'],'reflot'=>$row['MVT_REFLOT'],'dateperemp'=>$row['MVT_DATEPEREMP']));
		}

		$_SESSION['DATA_TRS']['nbreLigne2'] = $query->rowCount();


		header('location:journaltransfert.php?selectedTab=bds&rst=1');
		break;

	case 'check':
		$msg = "";
		(isset($_POST['reftransfert']) && $_POST['reftransfert']!='' ? $reftransfert = trim($_POST['reftransfert']) : $reftransfert = '');

		if($reftransfert !=''){
			$sql = "SELECT COUNT(CODE_TRANSFERT) AS NBRE FROM  `transfert` WHERE `CODE_TRANSFERT` LIKE '".addslashes($reftransfert)."'";
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

			if($row['NBRE']>0) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Ce code existe d&eacute;j&agrave;, veuillez entrer un autre code transfert.';}
		}
		echo $msg;
		break;

	case 'sendDate':
		$msg = "";
		(isset($_POST['code']) && $_POST['code']!='' 		? $code = trim($_POST['code']) 	: $code = '');

		if($code !=''){
			$_SESSION['GL_USER']['JOUR']= mysqlFormat($code);
		}
		echo 1;
		break;

	default : ///Nothing
		//header('location:../index.php');
}
}elseif($myaction !='')

//myaction
switch($myaction){
	case 'addline':
		(isset($_POST['exercice']) && $_POST['exercice']!=''			? $exercice 	= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datetransfert']) && $_POST['datetransfert']!=''  ? $datetransfert = trim($_POST['datetransfert']) 		: $datetransfert = '');
		(isset($_POST['reftransfert']) && $_POST['reftransfert']!=''  	? $reftransfert = trim($_POST['reftransfert']) 	: $reftransfert = '');
		(isset($_POST['magasin_srce']) && $_POST['magasin_srce']!=''  		? $magasin_srce 	= trim($_POST['magasin_srce']) 		: $raison = '');
		(isset($_POST['magasin_dest']) && $_POST['magasin_dest']!=''  		? $magasin_dest 	= trim($_POST['magasin_dest']) 		: $raison = '');
		(isset($_POST['libelleetat']) && $_POST['libelleetat']!=''  	? $libelleetat 	 = trim($_POST['libelleetat']) 		: $libelleetat = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 	= trim($_POST['nbreLigne']) 		: $nbreLigne = '');

		//Data
		$_SESSION['DATA_TRS']=array(
		'exercice'=>$exercice,
		'datetransfert'=>$datetransfert,
		'reftransfert'=>$reftransfert,
		'magasin_srce'=>$magasin_srce,
		'magasin_dest'=>$magasin_dest,
		'libelleetat'=>$libelleetat,
		'ligne'=> array(),
		'nbreLigne'=>$nbreLigne
		);

		//Collect Data
		for($i=1; $i<=$_SESSION['DATA_TRS']['nbreLigne']; $i++){
			(isset($_POST['code_dettransfert'.$i]) && $_POST['code_dettransfert'.$i] 	? $code_dettransfert 	= $_POST['code_dettransfert'.$i] 	: $code_dettransfert 	= '');
			(isset($_POST['monlot'.$i]) && $_POST['monlot'.$i] 					? $monlot 			= $_POST['monlot'.$i] 			: $monlot 	= '');
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 	= '');
			(isset($_POST['prix'.$i]) 	? $prix 	= $_POST['prix'.$i] 	: $prix 	= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 		= '');
			(isset($_POST['unite'.$i]) && $_POST['unite'.$i]				? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');
			(isset($_POST['reflot'.$i]) && $_POST['reflot'.$i]				? $reflot		= $_POST['reflot'.$i] 		: $reflot 		= '');
			(isset($_POST['dateperemp'.$i]) && $_POST['dateperemp'.$i]		? $dateperemp 	= $_POST['dateperemp'.$i] 	: $dateperemp 	= '');

			//Add to list
			if($codeproduit!='' && $produit!='' && $qte!='') array_push($_SESSION['DATA_TRS']['ligne'], array('code_dettransfert'=>$iddetbonsortie,'monlot'=>$monlot,'codeproduit'=>$codeproduit, 'produit'=>$produit, 'prix'=>$prix, 'qte'=>$qte, 'unite'=>$unite, 'reflot'=>$reflot, 'dateperemp'=>$dateperemp));

		}
		//Add line
		$_SESSION['DATA_TRS']['nbreLigne'] +=1;
		//print_r($_SESSION['DATA_DOT']);
		header('location:addtransfert11.php?selectedTab=bds');
		break;

	case 'addline1':
		(isset($_POST['xid']) && $_POST['xid']!=''						? $xid 				= trim($_POST['xid']) 			: $xid = '');
		(isset($_POST['exercice']) && $_POST['exercice']!=''			? $exercice 		= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datetransfert']) && $_POST['datetransfert']!=''  ? $datetransfert 	= trim($_POST['datetransfert']) 	: $datetransfert = '');
		(isset($_POST['reftransfert']) && $_POST['reftransfert']!=''  ? $reftransfert 	= trim($_POST['reftransfert']) 	: $reftransfert = '');
		(isset($_POST['magasin_srce']) && $_POST['magasin_srce']!='' 	? $magasin_srce 	= trim($_POST['magasin_srce']) 		: $magasin_srce = '');
		(isset($_POST['nature']) && $_POST['nature']!='0'  ? $nature = trim($_POST['nature']) 	: $nature = '');
		(isset($_POST['magasin_dest']) && $_POST['magasin_dest']!=''  ? $magasin_dest 	= trim($_POST['magasin_dest']) 		: $magasin_dest = '');
		(isset($_POST['libelleetat']) && $_POST['libelleetat']!=''  	? $libelleetat 		= trim($_POST['libelleetat']) 		: $libelleetat = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 	= trim($_POST['nbreLigne']) 		: $nbreLigne = '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 			= trim($_POST['statut']) 			: $statut = '0');
		(isset($_POST['code_transfert']) && $_POST['code_transfert']!=''  ? $code_transfert 	= trim($_POST['code_transfert']) 		: $code_transfert = '');

		//Data
		$_SESSION['DATA_TRS']=array(
		'xid'=>$xid,
		'exercice'=>$exercice,
		'datetransfert'=>$datetransfert,
		'code_transfert'=>$code_transfert,
		'reftransfert'=>$reftransfert,
		'code_dettransfert'=>$code_dettransfert,
		'nature'=>$nature,
		'magasin_srce'=>$_SESSION['GL_USER']['MAGASIN'],
		'magasin_dest'=>$magasin_dest,
		'libelleetat'=>$libelleetat,
		'nature'=>$nature,
		'ligne'=> array(),
		'nbreLigne'=>$nbreLigne
		);

		//Collect Data
		for($i=1; $i<=$_SESSION['DATA_TRS']['nbreLigne']; $i++){
			(isset($_POST['code_dettransfert'.$i]) && $_POST['code_dettransfert'.$i] 	? $code_dettransfert 	= $_POST['code_dettransfert'.$i] 	: $code_dettransfert 	= '');
			(isset($_POST['monlot'.$i]) && $_POST['monlot'.$i] 					? $monlot 			= $_POST['monlot'.$i] 			: $monlot 	= '');
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 	= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 		= '');
			(isset($_POST['unite'.$i]) && $_POST['unite'.$i]				? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');
			(isset($_POST['prix'.$i]) && $_POST['prix'.$i]					? $prix 		= $_POST['prix'.$i] 		: $prix 		= '');
			(isset($_POST['reflot'.$i]) && $_POST['reflot'.$i]				? $reflot		= $_POST['reflot'.$i] 		: $reflot 		= '');
			(isset($_POST['dateperemp'.$i]) && $_POST['dateperemp'.$i]		? $dateperemp 	= $_POST['dateperemp'.$i] 	: $dateperemp 	= '');
				$numautoDetTrs++;
				$codeDetTrs = "$numautoDetTrs/$magasin_srce";

			if($codeproduit!='' && $produit!='' && $qte!='') array_push($_SESSION['DATA_TRS']['ligne'], array('code_transfert'=>$codeTrs,'code_dettransfert'=>$codeDetTrs,'monlot'=>$monlot,'codeproduit'=>$codeproduit, 'prix'=>$prix,'produit'=>$produit,  'qte'=>$qte, 'unite'=>$unite, 'reflot'=>$reflot, 'dateperemp'=>$dateperemp));
		}
		//Add line
		$_SESSION['DATA_TRS']['nbreLigne'] +=1;
		//print_r($_SESSION['DATA_DOT']);
		header('location:addtransfert11.php?selectedTab=bds');
		break;

	case 'delline':
		(isset($_POST['exercice']) && $_POST['exercice']!=''			? $exercice 	= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datetransfert']) && $_POST['datetransfert']!=''  ? $datetransfert = trim($_POST['datetransfert']) 		: $datetransfert = '');
		(isset($_POST['reftransfert']) && $_POST['reftransfert']!=''  	? $reftransfert = trim($_POST['reftransfert']) 	: $reftransfert = '');
		(isset($_POST['service_srce']) && $_POST['service_srce']!=''  		? $service_srce 	= trim($_POST['service_srce']) 		: $service_srce = '');
		(isset($_POST['service_dest']) && $_POST['service_dest']!=''  		? $service_dest 	= trim($_POST['service_dest']) 		: $service_dest = '');
		(isset($_POST['magasin_srce']) && $_POST['magasin_srce']!=''  		? $magasin_srce 	= trim($_POST['magasin_srce']) 		: $raison = '');
		(isset($_POST['magasin_dest']) && $_POST['magasin_dest']!=''  		? $magasin_dest 	= trim($_POST['magasin_dest']) 		: $raison = '');
		(isset($_POST['libelleetat']) && $_POST['libelleetat']!=''  	? $libelleetat 	 = trim($_POST['libelleetat']) 		: $libelleetat = '');
		(isset($_POST['nature']) && $_POST['nature']!='0'  ? $nature = trim($_POST['nature']) 	: $nature = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 	= trim($_POST['nbreLigne']) 		: $nbreLigne = '');

		//Data
		$_SESSION['DATA_TRS']=array(
		'exercice'=>$exercice,
		'datetransfert'=>$datetransfert,
		'reftransfert'=>$reftransfert,
		'service_srce'=>$service_srce,
		'service_dest'=>$service_dest,
		'magasin_srce'=>$magasin_srce,
		'magasin_dest'=>$magasin_dest,
		'libelleetat'=>$libelleetat,
		'nature'=>$nature,
		'ligne'=> array(),
		'nbreLigne'=>$nbreLigne
		);

		$supp =0;
		//Collect Data
		$_SESSION['DATA_TRS']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_TRS']['nbreLigne']; $i++){
			(isset($_POST['code_dettransfert'.$i]) && $_POST['code_dettransfert'.$i] 	? $code_dettransfert 	= $_POST['code_dettransfert'.$i] 	: $code_dettransfert 	= '');
			(isset($_POST['monlot'.$i]) && $_POST['monlot'.$i] 					? $monlot 			= $_POST['monlot'.$i] 			: $monlot 	= '');
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 	= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 		= '');
			(isset($_POST['unite'.$i]) && $_POST['unite'.$i]				? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');
			(isset($_POST['prix'.$i]) && $_POST['prix'.$i]					? $prix 		= $_POST['prix'.$i] 		: $prix 		= '');
			(isset($_POST['reflot'.$i]) && $_POST['reflot'.$i]				? $reflot		= $_POST['reflot'.$i] 		: $reflot 		= '');
			(isset($_POST['dateperemp'.$i]) && $_POST['dateperemp'.$i]		? $dateperemp 	= $_POST['dateperemp'.$i] 	: $dateperemp 	= '');

			//Add to list
			if($codeproduit!='' && $produit!='' && $qte!='' && $rowSelection!=$codeproduit){
				array_push($_SESSION['DATA_TRS']['ligne'], array('code_dettransfert'=>$iddetbonsortie,'monlot'=>$monlot,'codeproduit'=>$codeproduit, 'produit'=>$produit,  'prix'=>$prix,'qte'=>$qte, 'unite'=>$unite, 'reflot'=>$reflot, 'dateperemp'=>$dateperemp));
			}
			elseif($codeproduit!='' && $produit!='' && $qte!='' && $rowSelection==$codeproduit){ $supp++;}
		}
		$_SESSION['DATA_TRS']['nbreLigne'] -=$supp;
		header('location:addtransfert1.php?selectedTab=bds');
		break;

	case 'delline1':
		(isset($_POST['xid']) && $_POST['xid']!=''			? $xid 	= trim($_POST['xid']) 			: $xid = '');
		(isset($_POST['exercice']) && $_POST['exercice']!=''			? $exercice 	= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datetransfert']) && $_POST['datetransfert']!=''  ? $datetransfert = trim($_POST['datetransfert']) 		: $datetransfert = '');
		(isset($_POST['reftransfert']) && $_POST['reftransfert']!=''  	? $reftransfert = trim($_POST['reftransfert']) 	: $reftransfert = '');
		(isset($_POST['service_srce']) && $_POST['service_srce']!=''  		? $service_srce 	= trim($_POST['service_srce']) 		: $service_srce = '');
		(isset($_POST['service_dest']) && $_POST['service_dest']!=''  		? $service_dest 	= trim($_POST['service_dest']) 		: $service_dest = '');
		(isset($_POST['magasin_srce']) && $_POST['magasin_srce']!=''  		? $magasin_srce 	= trim($_POST['magasin_srce']) 		: $raison = '');
		(isset($_POST['magasin_dest']) && $_POST['magasin_dest']!=''  		? $magasin_dest 	= trim($_POST['magasin_dest']) 		: $raison = '');
		(isset($_POST['nature']) && $_POST['nature']!='0'  ? $nature = trim($_POST['nature']) 	: $nature = '');
		(isset($_POST['libelleetat']) && $_POST['libelleetat']!=''  	? $libelleetat 	 = trim($_POST['libelleetat']) 		: $libelleetat = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 	= trim($_POST['nbreLigne']) 		: $nbreLigne = '');
		(isset($_POST['rowSelection']) && $_POST['rowSelection']!=''  	? $rowSelection = trim($_POST['rowSelection']) 		: $rowSelection = '');

		//Data
		$_SESSION['DATA_TRS']=array(
		'xid'=>$xid,
		'exercice'=>$exercice,
		'datetransfert'=>$datetransfert,
		'reftransfert'=>$reftransfert,
		'service_srce'=>$service_srce,
		'service_dest'=>$service_dest,
		'magasin_srce'=>$magasin_srce,
		'magasin_dest'=>$magasin_dest,
		'libelleetat'=>$libelleetat,
		'nature'=>$nature,
		'ligne'=> array(),
		'nbreLigne'=>$nbreLigne
		);

		$supp=0;
		//Collect Data
		$_SESSION['DATA_TRS']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_TRS']['nbreLigne']; $i++){
			(isset($_POST['code_dettransfert'.$i]) && $_POST['code_dettransfert'.$i] 	? $code_dettransfert 	= $_POST['code_dettransfert'.$i] 	: $code_dettransfert 	= '');
			(isset($_POST['monlot'.$i]) && $_POST['monlot'.$i] 					? $monlot 			= $_POST['monlot'.$i] 			: $monlot 	= '');
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 	= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 		= '');
			(isset($_POST['unite'.$i]) && $_POST['unite'.$i]				? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');
			(isset($_POST['prix'.$i]) && $_POST['prix'.$i]					? $prix 		= $_POST['prix'.$i] 		: $prix 		= '');
			(isset($_POST['reflot'.$i]) && $_POST['reflot'.$i]				? $reflot		= $_POST['reflot'.$i] 		: $reflot 		= '');
			(isset($_POST['dateperemp'.$i]) && $_POST['dateperemp'.$i]		? $dateperemp 	= $_POST['dateperemp'.$i] 	: $dateperemp 	= '');

			if($codeproduit!='' && $produit!='' && $qte!='' && $rowSelection!=$codeproduit){
				array_push($_SESSION['DATA_TRS']['ligne'], array('code_dettransfert'=>$iddetbonsortie,'monlot'=>$monlot,'codeproduit'=>$codeproduit, 'produit'=>$produit, 'prix'=>$prix,   'qte'=>$qte, 'unite'=>$unite, 'reflot'=>$reflot, 'dateperemp'=>$dateperemp));
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
				$sql = "DELETE FROM  `dettransfert` WHERE CODE_PRODUIT='".addslashes($codeproduit)."' AND `ID_TRANSFERT` = '".addslashes($xid)."';
				DELETE FROM  `mouvement` WHERE CODE_PRODUIT='".addslashes($codeproduit)."' AND MVT_NATURE LIKE 'TRANSFERT' AND `ID_SOURCE` LIKE '".addslashes($xid)."';";
				$query =  $cnx->prepare($sql); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query
			}
		}
		$_SESSION['DATA_TRS']['nbreLigne'] -=$supp;
		header('location:edittransfert.php?selectedTab=bds');
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
		//TRANSFERT
		$sql = "SELECT * FROM  `transfert`   WHERE `CODE_TRANSFERT` LIKE '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data  CDE_STATUT
		$_SESSION['DATA_TRS']=array(
		'xid'=>$row['CODE_TRANSFERT'],
		'exercice'=>$row['ID_EXERCICE'],
		'datetransfert'=>frFormat2($row['TRS_DATE']),
		'reftransfert'=>$row['REF_TRANSFERT'],
		'magasin'=>$row['CODE_MAGASIN'],
		'magasin_srce'=>$row['MAG_CODE_MAGASIN_SRCE'],
		'magasin_dest'=>$row['MAG_CODE_MAGASIN_DEST'],
		'libelleetat'=>$row['TRS_LIBELLE'],
		'nature'=>$row['TRS_NATURE'],
		'statut'=>$row['TRS_VALIDE'],
		'nbreLigne'=>0
		);

		//LIGNES TRANSFERT
	 	$sql = "SELECT * FROM `dettransfert` INNER JOIN produit ON (produit.CODE_PRODUIT LIKE dettransfert.CODE_PRODUIT)
		WHERE CODE_TRANSFERT LIKE '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_TRS']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_TRS']['ligne'], array('code_dettransfert'=>$row['CODE_DETTRANSFERT'], 'monlot'=>$row['TRS_MONLOT'],'codeproduit'=>$row['CODE_PRODUIT'], 'oldcodeproduit'=>$row['CODE_PRODUIT'],'produit'=>$row['PRD_LIBELLE'], 'qte'=>$row['TRS_PRDQTE'], 'prix'=>$row['TRS_PV'], 'unite'=>$row['TRS_UNITE'],'reflot'=>$row['TRS_REFLOT'],'dateperemp'=>$row['TRS_DATEPEREMP']));
		}
		$_SESSION['DATA_TRS']['nbreLigne'] = $query->rowCount();

		header('location:edittransfert1.php?selectedTab=bds&rs=2');
		break;

	case 'annul':
		(isset($_POST['xid']) ? $xid = $_POST['xid'] : $xid ='');
		(isset($_POST['oldreftransfert']) ? $reftransfert = $_POST['oldreftransfert'] : $reftransfert ='');
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		//TRANSFERT
		$sql = "UPDATE `transfert` SET  TRS_VALIDE=2 WHERE `ID_TRANSFERT` = '".addslashes($xid)."';
		UPDATE mouvement SET MVT_VALID=2 WHERE (MVT_NATURE LIKE 'TRANSFERT SORTANT' OR MVT_NATURE LIKE 'TRANSFERT ENTRANT')
		AND ID_SOURCE='".addslashes($xid)."';";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Annulation d\'un transfert ('.$xid.', '.$reftransfert.')'); //updateLog($username, $idcust, $action='' )
		//echo $sql;
		header('location:transfert.php?selectedTab=bds&rst=1');
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
		//TRANSFERT
		$sql = "SELECT * FROM  `transfert` WHERE (CODE_MAGASIN LIKE '". $_SESSION['GL_USER']['MAGASIN']."')  AND   `CODE_TRANSFERT` LIKE '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data  CDE_STATUT
		$_SESSION['DATA_TRS']=array(
		'xid'=>$row['CODE_TRANSFERT'],
		'exercice'=>$row['ID_EXERCICE'],
		'datetransfert'=>frFormat2($row['TRS_DATE']),
		'reftransfert'=>$row['REF_TRANSFERT'],
		'magasin'=>$row['CODE_MAGASIN'],
		'magasin_srce'=>$row['MAG_CODE_MAGASIN_SRCE'],
		'magasin_dest'=>$row['MAG_CODE_MAGASIN_DEST'],
		'libelleetat'=>$row['TRS_LIBELLE'],
		'nature'=>$row['TRS_NATURE'],
		'statut'=>$row['TRS_VALIDE'],
		'nbreLigne'=>0
		);

		//LIGNES TRANSFERT
		$sql = "SELECT * FROM `dettransfert` INNER JOIN produit ON (produit.CODE_PRODUIT LIKE dettransfert.CODE_PRODUIT)
		WHERE CODE_TRANSFERT LIKE '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_TRS']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_TRS']['ligne'], array('code_dettransfert'=>$row['CODE_DETTRANSFERT'], 'monlot'=>$row['TRS_MONLOT'],'codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>$row['PRD_LIBELLE'], 'qte'=>$row['TRS_PRDQTE'],'prix'=>$row['TRS_PV'], 'unite'=>$row['TRS_UNITE'],'reflot'=>$row['TRS_REFLOT'],'dateperemp'=>$row['TRS_DATEPEREMP']));
		}

		$_SESSION['DATA_TRS']['nbreLigne'] = $query->rowCount();
		if($_SESSION['DATA_TRS']['nature']==1) {header('location:validtransfert1.php?selectedTab=bds&rs=3');}
		elseif($_SESSION['DATA_TRS']['nature']==2) {header('location:validtransfert2.php?selectedTab=bds&rs=3');}
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
			$sql = "DELETE FROM  `dettransfert` WHERE `CODE_TRANSFERT` LIKE '".addslashes($split[0])."';
			DELETE FROM  `transfert` WHERE `CODE_TRANSFERT` LIKE '".addslashes($split[0])."'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			//Suppression dans Mouvement
			$sql = "DELETE FROM  `mouvement` WHERE `ID_SOURCE` LIKE '".addslashes($split[0])."' AND MVT_NATURE LIKE 'TRANSFERT%'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
		}
		header('location:transfert.php?selectedTab=bds&rs=4');
		break;

	default : ///Nothing
		//header('location:../index.php');

}

elseif($myaction =='' && $do ='') header('location:../index.php');
?>