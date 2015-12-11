<?php
//PHP Session 
session_start();
//MySQL Parameters
require_once('../lib/global.inc');
//PHP functions librairy
require_once('../lib/phpfuncLib.php');
//Action to do
(isset($_POST['myaction']) ? $myaction = $_POST['myaction'] : $myaction ='');
switch($myaction){
	
	
	//------------------------- ADD INVENTAIRE ETAPE 1 --------
	//Display addinventaire.php
	
	//------------------------- ADD INVENTAIRE ETAPE 2 --------
	//ADD INVENTAIRE
	case 'ETAPE2':
		$table1 = "stocks_inventaire";
		//Collect Data
		(isset($_POST['reference']) 	? $xreference 		= $_POST['reference'] 	: $xreference 		= '');
		(isset($_POST['dateAjout']) 	? $xdateAjout 		= $_POST['dateAjout'] 	: $xdateAjout 		= '');
		(isset($_POST['libelle']) 		? $xlibelle 		= $_POST['libelle'] 	: $xlibelle 		= '');
		(isset($_POST['categorie']) 	? $xcodeCategorie 	= $_POST['categorie'] 	: $xcodeCategorie 	= '');
		$exercice = $_SESSION['GL_USER']['EXERCICE']; //$_SESSION;
		
		//Fill session vars
		$_SESSION['DATA_INVENT']= array(
		'exercice'	=> $exercice,
		'reference'	=> $xreference,
		'dateAjout'	=> $xdateAjout,
		'libelle'	=> $xlibelle,
		'categorie'	=> $xcodeCategorie,
		'nbreLigne'	=> 0
		);
		header('location:addinventaire2.php?selectedTab=inputs');
		break;
	
	
	//------------------------- ADD INVENTAIRE ETAPE 3 --------
	//SAVE INVENTAIRE	
	case 'ETAPE3':
		$table1 = "stocks_inventaire";
		$table2 = "stocks_ligne_inventaire";
		//Collect Data
		$_SESSION['DATA_INVENT']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_INVENT']['nbreLigne']; $i++){
			(isset($_POST['idArticle'.$i]) 	? $xidArticle 		= $_POST['idArticle'.$i] 	: $xidArticle 	= '');
			(isset($_POST['designat'.$i]) 	? $xdesignat 		= $_POST['designat'.$i] 	: $xdesignat 	= '');
			(isset($_POST['prixUnit'.$i]) 	? $xprixUnit 		= $_POST['prixUnit'.$i] 	: $xprixUnit 	= '');
			(isset($_POST['QteT'.$i]) 		? $xQteT 			= $_POST['QteT'.$i] 		: $xQteT 		= '');
			(isset($_POST['QteP'.$i]) 		? $xQteP 			= $_POST['QteP'.$i] 		: $xQteP 		= '');
			(isset($_POST['unite'.$i]) 		? $xunite 			= $_POST['unite'.$i] 		: $xunite 		= '');
			($xQteT - $xQteP > 0 ? $xtypeinventaire = '-' : $xtypeinventaire = '+');
			$xqte = abs($xQteT-$xQteP);
		
			if($xidArticle!='' && $xprixUnit!='' && $xqte!='') array_push($_SESSION['DATA_INVENT']['ligne'], array('idArticle'=>$xidArticle, 'designat'=>$xdesignat, 'prixUnit'=>$xprixUnit, 'qte'=>$xqte, 'qteT'=>$xQteT, 'qteP'=>$xQteP, 'unite'=>$xunite, 'typeinventaire'=>$xtypeinventaire));
			
		}
		
		//Check the stock
		//Save data
		$SQL1 ="INSERT INTO $table1 (`ID_INVENTAIRE` ,`ID_EXERCICE` ,`DATE_INVENTAIRE` ,`LIBELLE_INVENTAIRE`)
		VALUES ('".addslashes($_SESSION['DATA_INVENT']['reference'])."' , 
		'".addslashes($_SESSION['DATA_INVENT']['exercice'])."', 
		'".addslashes(mysqlFormat($_SESSION['DATA_INVENT']['dateAjout']))."', 
		'".addslashes($_SESSION['DATA_INVENT']['libelle'])."');";
		
		//Connection to Database server
		$idcon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
		//Select Database
		mysql_select_db(DB) or header('location:errorPage.php&code=');
		$result = mysql_query($SQL1) or header('location:errorPage.php&code=');
		print_r($_SESSION['DATA_INVENT']);
		if(mysql_affected_rows($idcon)>0){
			foreach($_SESSION['DATA_INVENT']['ligne']  as $key=>$row){
				$SQL2 ="INSERT INTO $table2 (`ID_INVENTAIRE` ,`ID_ARTICLE` ,`ID_EXERCICE` ,`QTE_INVENTAIRE` ,`STOCK_THEORIQUE` ,`STOCK_PHYSIQUE` ,`UNITE`  ,`TYPE_INVENTAIRE`,`PU_INVENTAIRE` ,`NUM`) 
				VALUES ('".addslashes($_SESSION['DATA_INVENT']['reference'])."', '".addslashes($row['idArticle'])."','".$_SESSION['GL_USER']['EXERCICE']."', 
				".addslashes($row['qte'])." , ".addslashes($row['qteT'])." , ".addslashes($row['qteP'])." , '".addslashes($row['unite'])."','".addslashes($row['typeinventaire'])."', ".addslashes($row['prixUnit'])." , ".$key.");";
				$result = mysql_query($SQL2) or header('location:errorPage.php&code=');
			}
		}
		
		mysql_close();
		//Log fils
		$log = logFile($_SESSION['GL_USER']['LOGIN'],date("Y-m-d H:i:s"),"Ajout d'un inventaire ID:".$_SESSION['DATA_INVENT']['reference']." par ".$_SESSION['GL_USER']['LOGIN']);
		
		//header('location:inventaires.php?selectedTab=inputs');
		break;


	//------------------------- EDIT INVENTAIRE ETAPE 1 --------
	//EDIT INVENTAIRE		
	case 'EDIT':
		$table1 = "stocks_inventaire";
		$table3 = "stocks_ligne_inventaire";
		$table4 = "stocks_article";
		$exercice = $_SESSION['GL_USER']['EXERCICE'];
		
		(isset($_POST['rowSelection']) ? $id = $_POST['rowSelection'][0] : $id = array());

		//Connection to Database server
		mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
		
		//Select Database
		mysql_select_db(DB) or header('location:errorPage.php&code=');
		
		//Data
		$SQL1 ="SELECT * FROM $table1 WHERE ID_EXERCICE='$exercice' AND ID_INVENTAIRE='$id'"; 
		$result = mysql_query($SQL1) or header('location:errorPage.php&code=');
		$row = mysql_fetch_array($result);
		
		$_SESSION['EDIT_INVENT']= array(
		'reference'	=> stripcslashes($row['ID_INVENTAIRE']),
		'exercice'	=> $row['ID_EXERCICE'],
		'dateAjout'	=> frFormat($row['DATE_INVENTAIRE']),
		'libelle'	=> stripcslashes($row['LIBELLE_INVENTAIRE']),
		'nbreLigne'	=> 0,
		'ligne'=>array()
		);
		
		//SQL
		$SQL = "SELECT $table3.*, $table4.* FROM $table3, $table4 WHERE $table3.ID_EXERCICE ='$exercice' AND ID_INVENTAIRE='$id' AND $table3.ID_ARTICLE=$table4.ID_ARTICLE ORDER BY NUM ASC;";
		$result = mysql_query($SQL) or header('location:errorPage.php&code=');
		
		$i=0; //Nbre de ligne
		//Fill session vars
		$_SESSION['EDIT_INVENT']['ligne'] =array();
		while($row = mysql_fetch_array($result)){
			$i++;
			array_push($_SESSION['EDIT_INVENT']['ligne'], array('idArticle'=>stripcslashes($row['ID_ARTICLE']), 'designat'=>stripcslashes($row['LIBELLE_ARTICLE']),'prixUnit'=>$row['PU_INVENTAIRE'], 'typeinventaire'=>stripcslashes($row['TYPE_INVENTAIRE']), 'qte'=>$row['QTE_INVENTAIRE'], 'unite'=>stripcslashes($row['UNITE'])));
		}
		$_SESSION['EDIT_INVENT']['nbreLigne']=$i;
		mysql_close();
		header('location:editinventaire.php?selectedTab=inputs&id='.$id);
		break;


	//------------------------- EDIT INVENTAIRE ETAPE 2 --------
	//SAVE EDIT DATA INVENTAIRE
	case 'EDIT1':
		$table1 = "stocks_inventaire";
		$table2 = "stocks_ligne_inventaire";
		
		//Collect Data
		(isset($_POST['idInvent']) 		? $xidInvent 		= $_POST['idInvent'] 		: $xidInvent		= '');
		(isset($_POST['reference']) 	? $xreference 		= $_POST['reference'] 		: $xreference 		= '');
		(isset($_POST['dateAjout']) 	? $xdateAjout 		= $_POST['dateAjout'] 		: $xdateAjout 		= '');
		(isset($_POST['libelle']) 		? $xlibelle 		= $_POST['libelle'] 		: $xlibelle 		= '');
		(isset($_POST['nbreLigne']) 	? $xnbreLigne 		= $_POST['nbreLigne'] 		: $xnbreLigne 		= '');
		$exercice = $_SESSION['GL_USER']['EXERCICE']; //$_SESSION;
		
		//Fill session vars
		$_SESSION['EDIT_INVENT']['reference']= $xreference;
		$_SESSION['EDIT_INVENT']['exercice']= $exercice;
		$_SESSION['EDIT_INVENT']['dateAjout']= $xdateAjout;
		$_SESSION['EDIT_INVENT']['libelle']= $xlibelle;
		$_SESSION['EDIT_INVENT']['nbreLigne']= $xnbreLigne;
		
		//Collect Data
		$_SESSION['EDIT_INVENT']['ligne'] =array();
		for($i=1; $i<=$_SESSION['EDIT_INVENT']['nbreLigne']; $i++){
			(isset($_POST['oldArticle'.$i]) ? $xoldArticle 		= $_POST['oldArticle'.$i] 	: $xoldArticle 	= '');
			(isset($_POST['idArticle'.$i]) 	? $xidArticle 		= $_POST['idArticle'.$i] 	: $xidArticle 	= '');
			(isset($_POST['prixUnit'.$i]) 	? $xprixUnit 		= $_POST['prixUnit'.$i] 	: $xprixUnit 	= '');
			(isset($_POST['qte'.$i]) 		? $xqte 			= $_POST['qte'.$i] 			: $xqte 		= '');
			(isset($_POST['typeinventaire'.$i]) ? $xtypeinventaire 	= $_POST['typeinventaire'.$i] 	: $xtypeinventaire 		= '');
			(isset($_POST['designat'.$i]) 	? $xdesignat 		= $_POST['designat'.$i] 	: $xdesignat	= '');
			(isset($_POST['unite'.$i]) 		? $xunite 			= $_POST['unite'.$i] 		: $xunite 		= '');
			
			if($xidArticle!='' && $xprixUnit!='' && $xqte!='') array_push($_SESSION['EDIT_INVENT']['ligne'], array('oldidArticle'=>$xoldArticle,'idArticle'=>$xidArticle, 'designat'=>$xdesignat, 'prixUnit'=>$xprixUnit, 'typeinventaire'=>$xtypeinventaire, 'qte'=>$xqte, 'unite'=>$xunite));
			
		}

		//Save data
		$SQL1 ="UPDATE $table1 SET `ID_INVENTAIRE`='".addslashes($xreference)."', `DATE_INVENTAIRE`='".addslashes(mysqlFormat($_SESSION['EDIT_INVENT']['dateAjout']))."' , ";
		$SQL1 .="`LIBELLE_INVENTAIRE`='".addslashes($_SESSION['EDIT_INVENT']['libelle'])."' WHERE ID_INVENTAIRE='$xidInvent'";
			
		//Connection to Database server
		mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
		//Select Database
		mysql_select_db(DB) or header('location:errorPage.php&code=');
		$result = mysql_query($SQL1) or header('location:errorPage.php&code=');
		
		foreach($_SESSION['EDIT_INVENT']['ligne']  as $key=>$row){
			if($row['oldidArticle']!='' && $row['idArticle']!=''){
				$SQL2 ="UPDATE $table2 SET `ID_ARTICLE`='".addslashes($row['idArticle'])."' ,
				`ID_INVENTAIRE`='".addslashes($xreference)."',  
				`QTE_INVENTAIRE`=".addslashes($row['qte'])." , 
				`UNITE`='".addslashes($row['unite'])."' , 
				`TYPE_INVENTAIRE`='".addslashes($row['typeinventaire'])."',
				`PU_INVENTAIRE`=".addslashes($row['prixUnit']).",
				`NUM`= $key  
				WHERE ID_INVENTAIRE='$xidInvent' AND ID_ARTICLE='".addslashes($row['oldidArticle'])."'";
				$result = mysql_query($SQL2) or header('location:errorPage.php&code=');
			}
			elseif($row['idArticle']!=''){
				$SQL2 ="INSERT INTO $table2 (`ID_ARTICLE`, `ID_INVENTAIRE`,`ID_EXERCICE` , `QTE_INVENTAIRE`, `UNITE`, `TYPE_INVENTAIRE`,`PU_INVENTAIRE`,`NUM`) 
				VALUES ('".addslashes($row['idArticle'])."' ,'".addslashes($xreference)."','$exercice',  ".addslashes($row['qte']).", 
				'".addslashes($row['unite'])."', '".addslashes($row['typeinventaire'])."', ".addslashes($row['prixUnit']).", $key);";
				$result = mysql_query($SQL2) or header('location:errorPage.php&code=');			
			}
		}
		mysql_close();
		//Log fils
		$log = logFile($_SESSION['GL_USER']['LOGIN'],date("Y-m-d H:i:s"),"Modification d'un inventaire ID:$xreference par ".$_SESSION['GL_USER']['LOGIN']);
		
		header('location:inventaires.php?selectedTab=inputs');
		break;
		
	//VALIDATION ------------------------------	
	case 'VAL':
		$table1 = "stocks_inventaire";
		$table3 = "stocks_ligne_inventaire";
		$table4 = "stocks_article";
		$exercice = $_SESSION['GL_USER']['EXERCICE'];
		
		(isset($_POST['rowSelection']) ? $id = $_POST['rowSelection'][0] : $id = array());

		//Connection to Database server
		mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
		
		//Select Database
		mysql_select_db(DB) or header('location:errorPage.php&code=');
		
		//Data
		$SQL1 ="SELECT * FROM $table1 WHERE ID_EXERCICE='$exercice' AND ID_INVENTAIRE='$id'"; 
		$result = mysql_query($SQL1) or header('location:errorPage.php&code=');
		$row = mysql_fetch_array($result);
		
		$_SESSION['VAL_INVENT']= array(
		'reference'	=> stripcslashes($row['ID_INVENTAIRE']),
		'exercice'	=> $row['ID_EXERCICE'],
		'dateAjout'	=> frFormat($row['DATE_INVENTAIRE']),
		'libelle'	=> stripcslashes($row['LIBELLE_INVENTAIRE']),
		'nbreLigne'	=> 0,
		'ligne'=>array()
		);
		
		//SQL
		$SQL = "SELECT $table3.*, $table4.* FROM $table3, $table4 WHERE $table3.ID_EXERCICE ='$exercice' 
		AND ID_INVENTAIRE='$id' AND $table3.ID_ARTICLE=$table4.ID_ARTICLE ORDER BY NUM ASC;";
		$result = mysql_query($SQL) or header('location:errorPage.php&code=');
		
		$i=0; //Nbre de ligne
		//Fill session vars
		$_SESSION['VAL_INVENT']['ligne'] =array();
		while($row = mysql_fetch_array($result)){
			$i++;
			array_push($_SESSION['VAL_INVENT']['ligne'], array('idArticle'=>$row['ID_ARTICLE'], 'designat'=>$row['LIBELLE_ARTICLE'],'prixUnit'=>$row['PU_INVENTAIRE'], 'typeinventaire'=>$row['TYPE_INVENTAIRE'], 'qte'=>$row['QTE_INVENTAIRE'], 'unite'=>$row['UNITE']));
		}
		$_SESSION['VAL_INVENT']['nbreLigne']=$i;
		mysql_close();
		
		header('location:validinventaire1.php?selectedTab=inputs&id='.$id);
		break;

	case 'VAL1':  //SAVE VALIDATION
		$table1 = "stocks_inventaire";
		$table2 = "stocks_ligne_inventaire";
		
		//Collect Data
		(isset($_POST['idInvent']) 		? $xidInvent 		= $_POST['idInvent'] 		: $xidInvent		= '');
		(isset($_POST['reference']) 	? $xreference 		= $_POST['reference'] 		: $xreference 		= '');
		(isset($_POST['dateAjout']) 	? $xdateAjout 		= $_POST['dateAjout'] 		: $xdateAjout 		= '');
		(isset($_POST['libelle']) 		? $xlibelle 		= $_POST['libelle'] 		: $xlibelle 		= '');
		(isset($_POST['nbreLigne']) 	? $xnbreLigne 		= $_POST['nbreLigne'] 		: $xnbreLigne 		= '');
		$exercice = $_SESSION['GL_USER']['EXERCICE']; //$_SESSION;
		
		//$dateAjout = mysqlFormat($dateAjout);
		//Fill session vars
		$_SESSION['VAL_INVENT']= array(
		'reference'	=> $xreference,
		'exercice'	=> $exercice,
		'dateAjout'	=> $xdateAjout,
		'libelle'	=> $xlibelle,
		'nbreLigne'	=> $xnbreLigne,
		);
		//Collect Data
		$_SESSION['VAL_INVENT']['ligne'] =array();
		for($i=1; $i<=$_SESSION['VAL_INVENT']['nbreLigne']; $i++){
			(isset($_POST['oldArticle'.$i]) ? $xoldArticle 		= $_POST['oldArticle'.$i] 	: $xoldArticle 	= '');
			(isset($_POST['idArticle'.$i]) 	? $xidArticle 		= $_POST['idArticle'.$i] 	: $xidArticle 	= '');
			(isset($_POST['prixUnit'.$i]) 	? $xprixUnit 		= $_POST['prixUnit'.$i] 	: $xprixUnit 	= '');
			(isset($_POST['qte'.$i]) 		? $xqte 			= $_POST['qte'.$i] 			: $xqte 		= '');
			(isset($_POST['typeinventaire'.$i]) ? $xtypeinventaire 	= $_POST['typeinventaire'.$i] 	: $xtypeinventaire 		= '');
			(isset($_POST['designat'.$i]) 	? $xdesignat 		= $_POST['designat'.$i] 	: $xdesignat	= '');
			(isset($_POST['unite'.$i]) 		? $xunite 			= $_POST['unite'.$i] 		: $xunite 		= '');
			
			if($xidArticle!='' && $xprixUnit!='' && $xqte!='') array_push($_SESSION['VAL_INVENT']['ligne'], array('oldidArticle'=>$xoldArticle,'idArticle'=>$xidArticle, 'designat'=>$xdesignat, 'prixUnit'=>$xprixUnit, 'typeinventaire'=>$xtypeinventaire, 'qte'=>$xqte, 'unite'=>$xunite));
			
		}
		//Check the stock
		//Save data
		$SQL1 ="UPDATE $table1 SET `ID_INVENTAIRE`='".addslashes($xreference)."', `DATE_INVENTAIRE`='".addslashes(mysqlFormat($_SESSION['VAL_INVENT']['dateAjout']))."' , ";
		$SQL1 .="`LIBELLE_INVENTAIRE`='".addslashes($_SESSION['VAL_INVENT']['libelle'])."', VALIDER=1 WHERE ID_INVENTAIRE='$xidInvent'";
			
		//Connection to Database server
		mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
		//Select Database
		mysql_select_db(DB) or header('location:errorPage.php&code=');
		$result = mysql_query($SQL1) or header('location:errorPage.php&code=');
		
		foreach($_SESSION['VAL_INVENT']['ligne']  as $key=>$row){
			if($row['oldidArticle']!='' && $row['idArticle']!=''){
				$SQL2 ="UPDATE $table2 SET `ID_ARTICLE`='".addslashes($row['idArticle'])."' ,
				`QTE_INVENTAIRE`=".addslashes($row['qte'])." , 
				`UNITE`='".addslashes($row['unite'])."' , 
				`TYPE_INVENTAIRE`='".addslashes($row['typeinventaire'])."',
				`PU_INVENTAIRE`=".addslashes($row['prixUnit']).",
				`NUM`=$key 
				WHERE ID_INVENTAIRE='$xidInvent' AND ID_ARTICLE='".addslashes($row['oldidArticle'])."'";
				$result = mysql_query($SQL2) or header('location:errorPage.php&code=');
			}
			elseif($row['oldidArticle']=='' && $row['idArticle']!=''){
				$SQL2 ="INSERT INTO $table2 (`ID_ARTICLE`, `ID_INVENTAIRE`,`ID_EXERCICE` , `QTE_INVENTAIRE`, `UNITE`, `TYPE_INVENTAIRE`,`PU_INVENTAIRE`,`NUM` ) 
				VALUES ('".addslashes($row['idArticle'])."' ,'".addslashes($xreference)."','$exercice',  ".addslashes($row['qte']).", 
				'".addslashes($row['unite'])."', '".addslashes($row['typeinventaire'])."', ".addslashes($row['prixUnit']).", $key);";
				$result = mysql_query($SQL2) or header('location:errorPage.php&code=');			
			}
		}
		mysql_close();
		//Log fils
		$log = logFile($_SESSION['GL_USER']['LOGIN'],date("Y-m-d H:i:s"),"Validation d'un inventaire ID:$xreference par ".$_SESSION['GL_USER']['LOGIN']);
		
		header('location:inventaires.php?selectedTab=inputs');
		break;
	
	case 'DELLGVAL'://Delete ligne inventaire
		$table1 = "stocks_inventaire";
		$table2 = "stocks_ligne_inventaire";
		$exercice = $_SESSION['GL_USER']['EXERCICE'];
	
		(isset($_POST['idInvent']) ? $idInvent = $_POST['idInvent'] : $idInvent = '');
		(isset($_POST['rowSelection']) ? $id = $_POST['rowSelection'] : $id = '');
		
		//Connection to Database server
		mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
		
		//Select Database
		mysql_select_db(DB) or header('location:errorPage.php&code=');
		
		//Save data
		$SQL1 ="DELETE FROM $table2 WHERE ID_INVENTAIRE='$idInvent' AND ID_EXERCICE='$exercice' AND ID_ARTICLE='$id';";
		$result = mysql_query($SQL1) or header('location:errorPage.php&code=');		
		mysql_close();
		//Log fils
		$log = logFile($_SESSION['GL_USER']['LOGIN'],date("Y-m-d H:i:s"),"Suppression d'une ligne inventaire ID:$idInvent/$id par ".$_SESSION['GL_USER']['LOGIN']);
		
		//EDIT
		$table1 = "stocks_inventaire";
		$table3 = "stocks_ligne_inventaire";
		$table4 = "stocks_article";
		$exercice = $_SESSION['GL_USER']['EXERCICE'];
		
		//Connection to Database server
		mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
		
		//Select Database
		mysql_select_db(DB) or header('location:errorPage.php&code=');
		
		//Data
		$SQL1 ="SELECT * FROM $table1 WHERE ID_EXERCICE='$exercice' AND ID_INVENTAIRE='$idInvent'"; 
		$result = mysql_query($SQL1) or header('location:errorPage.php&code=');
		$row = mysql_fetch_array($result);
		
		$_SESSION['VAL_INVENT']= array(
		'reference'	=> stripcslashes($row['ID_INVENTAIRE']),
		'exercice'	=> $row['ID_EXERCICE'],
		'dateAjout'	=> frFormat($row['DATE_INVENTAIRE']),
		'libelle'	=> stripcslashes($row['LIBELLE_INVENTAIRE']),
		'nbreLigne'	=> 0,
		'ligne'=>array()
		);
		
		//SQL
		$SQL = "SELECT $table3.*, $table4.* FROM $table3, $table4 WHERE $table3.ID_EXERCICE ='$exercice' 
		AND ID_INVENTAIRE='$idInvent' AND $table3.ID_ARTICLE=$table4.ID_ARTICLE ORDER BY NUM ASC;";
		$result = mysql_query($SQL) or header('location:errorPage.php&code=');
		
		$i=0; //Nbre de ligne
		//Fill session vars
		$_SESSION['VAL_INVENT']['ligne'] =array();
		while($row = mysql_fetch_array($result)){
			$i++;
			array_push($_SESSION['VAL_INVENT']['ligne'], array('idArticle'=>stripslashes($row['ID_ARTICLE']), 'designat'=>stripslashes($row['LIBELLE_ARTICLE']),'prixUnit'=>$row['PU_INVENTAIRE'], 'typeinventaire'=>stripslashes($row['TYPE_INVENTAIRE']), 'qte'=>$row['QTE_INVENTAIRE'], 'unite'=>stripslashes($row['UNITE'])));
		}
		$_SESSION['VAL_INVENT']['nbreLigne']=$i;
		mysql_close();
		header('location:validinventaire1.php?selectedTab=inputs&id='.$id);
		break;
	
	
	case 'ADDLGVAL':
		$table1 = "stocks_inventaire";
		$table2 = "stocks_ligne_inventaire";
		
		//Collect Data
		(isset($_POST['idInvent']) 		? $xidInvent 		= $_POST['idInvent'] 		: $xidInvent		= '');
		(isset($_POST['reference']) 	? $xreference 		= $_POST['reference'] 		: $xreference 		= '');
		(isset($_POST['dateAjout']) 	? $xdateAjout 		= $_POST['dateAjout'] 		: $xdateAjout 		= '');
		(isset($_POST['libelle']) 		? $xlibelle 		= $_POST['libelle'] 		: $xlibelle 		= '');
		(isset($_POST['nbreLigne']) 	? $xnbreLigne 		= $_POST['nbreLigne'] 		: $xnbreLigne 		= '');
		$exercice = $_SESSION['GL_USER']['EXERCICE']; //$_SESSION;
		
		//$dateAjout = mysqlFormat($dateAjout);
		//Fill session vars
		$_SESSION['VAL_INVENT']= array(
		'reference'	=> $xreference,
		'exercice'	=> $exercice,
		'dateAjout'	=> $xdateAjout,
		'libelle'	=> $xlibelle,
		'nbreLigne'	=> $xnbreLigne,
		);
		//Collect Data
		$_SESSION['VAL_INVENT']['ligne'] =array();
		for($i=1; $i<=$_SESSION['EDIT_INVENT']['nbreLigne']; $i++){
			(isset($_POST['oldArticle'.$i]) ? $xoldArticle 		= $_POST['oldArticle'.$i] 	: $xoldArticle 	= '');
			(isset($_POST['idArticle'.$i]) 	? $xidArticle 		= $_POST['idArticle'.$i] 	: $xidArticle 	= '');
			(isset($_POST['prixUnit'.$i]) 	? $xprixUnit 		= $_POST['prixUnit'.$i] 	: $xprixUnit 	= '');
			(isset($_POST['qte'.$i]) 		? $xqte 			= $_POST['qte'.$i] 			: $xqte 		= '');
			(isset($_POST['typeinventaire'.$i]) ? $xtypeinventaire 	= $_POST['typeinventaire'.$i] 	: $xtypeinventaire 		= '');
			(isset($_POST['designat'.$i]) 	? $xdesignat 		= $_POST['designat'.$i] 	: $xdesignat	= '');
			(isset($_POST['unite'.$i]) 		? $xunite 			= $_POST['unite'.$i] 		: $xunite 		= '');
			
			if($xidArticle!='' && $xprixUnit!='' && $xqte!='') array_push($_SESSION['VAL_INVENT']['ligne'], array('oldidArticle'=>$xoldArticle,'idArticle'=>$xidArticle, 'designat'=>$xdesignat, 'prixUnit'=>$xprixUnit, 'typeinventaire'=>$xtypeinventaire, 'qte'=>$xqte, 'unite'=>$xunite));
			
		}
		//Check the stock
		//Save data
		$SQL1 ="UPDATE $table1 SET `ID_INVENTAIRE`='".addslashes($xreference)."', 
		`DATE_INVENTAIRE`='".addslashes(mysqlFormat($_SESSION['VAL_INVENT']['dateAjout']))."' ,
		`LIBELLE_INVENTAIRE`='".addslashes($_SESSION['VAL_INVENT']['libelle'])."' WHERE ID_INVENTAIRE='$xidInvent'";
			
		//Connection to Database server
		mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
		//Select Database
		mysql_select_db(DB) or header('location:errorPage.php&code=');
		$result = mysql_query($SQL1) or header('location:errorPage.php&code=');
		
		foreach($_SESSION['VAL_INVENT']['ligne']  as $key=>$row){
			if($row['oldidArticle']!='' && $row['idArticle']!=''){
				$SQL2 ="UPDATE $table2 SET `ID_ARTICLE`='".addslashes($row['idArticle'])."' ,
				`ID_INVENTAIRE`='".addslashes($xreference)."',
				`QTE_INVENTAIRE`=".addslashes($row['qte'])." , 
				`UNITE`='".addslashes($row['unite'])."' , 
				`TYPE_INVENTAIRE`='".addslashes($row['typeinventaire'])."',
				`PU_INVENTAIRE`=".addslashes($row['prixUnit']).",
				`NUM`=$key 
				WHERE ID_INVENTAIRE='$xidInvent' AND ID_ARTICLE='".addslashes($row['oldidArticle'])."'";
				$result = mysql_query($SQL2) or header('location:errorPage.php&code=');
			}
			elseif($row['oldidArticle']=='' && $row['idArticle']!=''){
				$SQL2 ="INSERT INTO $table2 (`ID_ARTICLE`, `ID_INVENTAIRE`,`ID_EXERCICE` , `QTE_INVENTAIRE`, `UNITE`, `TYPE_INVENTAIRE`,`PU_INVENTAIRE`,`NUM` ) 
				VALUES ('".addslashes($row['idArticle'])."' ,'".addslashes($xreference)."','$exercice',  ".addslashes($row['qte']).", 
				'".addslashes($row['unite'])."', '".addslashes($row['typeinventaire'])."', ".addslashes($row['prixUnit']).", $key);";
				$result = mysql_query($SQL2) or header('location:errorPage.php&code=');			
			}
		}
		mysql_close();
		//Log fils
		$log = logFile($_SESSION['GL_USER']['LOGIN'],date("Y-m-d H:i:s"),"Modification d'un inventaire ID:$xreference par ".$_SESSION['GL_USER']['LOGIN']);
		//Add one ligne
		array_push($_SESSION['VAL_INVENT']['ligne'], array('oldidArticle'=>'','idArticle'=>'', 'designat'=>'', 'prixUnit'=>'', 'typeinventaire'=>'', 'qte'=>'', 'unite'=>''));
		$_SESSION['VAL_INVENT']['nbreLigne']= $xnbreLigne+1;
		header('location:validinventaire1.php?selectedTab=inputs&id='.$xreference);		
		break;

	//DELETE INVENTAIRE
	case 'DEL'://Delete Inventaire
		$table1 = "stocks_inventaire";
		$table2 = "stocks_ligne_inventaire";
		$exercice = $_SESSION['GL_USER']['EXERCICE'];
	
		(isset($_POST['rowSelection']) ? $id = $_POST['rowSelection'] : $id = array());
		
		//Connection to Database server
		mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
		
		//Select Database
		mysql_select_db(DB) or header('location:errorPage.php&code=');
		
		//Save data
		foreach($id as $key=>$val){
			$SQL1 ="DELETE FROM $table1 WHERE ID_INVENTAIRE='$val' AND ID_EXERCICE='$exercice';";
			$result = mysql_query($SQL1) or header('location:errorPage.php&code=');
			
			$SQL1 ="DELETE FROM $table2 WHERE ID_INVENTAIRE='$val' AND ID_EXERCICE='$exercice';";
			$result = mysql_query($SQL1) or header('location:errorPage.php&code=');
		}
		
		mysql_close();
		//Log fils
		$log = logFile($_SESSION['GL_USER']['LOGIN'],date("Y-m-d H:i:s"),"Suppression d'un inventaire ID:$id par ".$_SESSION['GL_USER']['LOGIN']);
		
		header('location:inventaires.php?selectedTab=inputs');
		break;

	case 'DELLGEDIT'://Delete ligne inventaire
		$table1 = "stocks_inventaire";
		$table2 = "stocks_ligne_inventaire";
		$exercice = $_SESSION['GL_USER']['EXERCICE'];
	
		(isset($_POST['idInvent']) ? $idInvent = $_POST['idInvent'] : $idInvent = '');
		(isset($_POST['rowSelection']) ? $id = $_POST['rowSelection'] : $id = '');
		
		//Connection to Database server
		mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
		
		//Select Database
		mysql_select_db(DB) or header('location:errorPage.php&code=');
		
		//Save data
		$SQL1 ="DELETE FROM $table2 WHERE ID_INVENTAIRE='$idInvent' AND ID_EXERCICE='$exercice' AND ID_ARTICLE='$id';";
		$result = mysql_query($SQL1) or header('location:errorPage.php&code=');		
		mysql_close();
		//Log fils
		$log = logFile($_SESSION['GL_USER']['LOGIN'],date("Y-m-d H:i:s"),"Suppression d'une ligne inventaire ID:$idInvent/$id par ".$_SESSION['GL_USER']['LOGIN']);
		
		//EDIT
		$table1 = "stocks_inventaire";
		$table3 = "stocks_ligne_inventaire";
		$table4 = "stocks_article";
		$exercice = $_SESSION['GL_USER']['EXERCICE'];
		
		//Connection to Database server
		mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
		
		//Select Database
		mysql_select_db(DB) or header('location:errorPage.php&code=');
		
		//Data
		$SQL1 ="SELECT * FROM $table1 WHERE ID_EXERCICE='$exercice' AND ID_INVENTAIRE='$idInvent'"; 
		$result = mysql_query($SQL1) or header('location:errorPage.php&code=');
		$row = mysql_fetch_array($result);
		
		$_SESSION['EDIT_INVENT']= array(
		'reference'	=> stripcslashes($row['ID_INVENTAIRE']),
		'exercice'	=> $row['ID_EXERCICE'],
		'dateAjout'	=> frFormat($row['DATE_INVENTAIRE']),
		'libelle'	=> stripcslashes($row['LIBELLE_INVENTAIRE']),
		'nbreLigne'	=> 0,
		'ligne'=>array()
		);
		
		//SQL
		$SQL = "SELECT $table3.*, $table4.* FROM $table3, $table4 WHERE $table3.ID_EXERCICE ='$exercice' AND ID_INVENTAIRE='$idInvent' AND $table3.ID_ARTICLE=$table4.ID_ARTICLE ORDER BY NUM ASC;";
		$result = mysql_query($SQL) or header('location:errorPage.php&code=');
		
		$i=0; //Nbre de ligne
		//Fill session vars
		$_SESSION['EDIT_INVENT']['ligne'] =array();
		while($row = mysql_fetch_array($result)){
			$i++;
			array_push($_SESSION['EDIT_INVENT']['ligne'], array('idArticle'=>stripslashes($row['ID_ARTICLE']), 'designat'=>stripslashes($row['LIBELLE_ARTICLE']),'prixUnit'=>$row['PU_INVENTAIRE'], 'typeinventaire'=>stripslashes($row['TYPE_INVENTAIRE']), 'qte'=>$row['QTE_INVENTAIRE'], 'unite'=>stripslashes($row['UNITE'])));
		}
		$_SESSION['EDIT_INVENT']['nbreLigne']=$i;
		mysql_close();
		header('location:editinventaire.php?selectedTab=inputs&id='.$id);
		break;


	case 'ADDLGEDIT':
		$table1 = "stocks_inventaire";
		$table2 = "stocks_ligne_inventaire";
		
		//Collect Data
		(isset($_POST['idInvent']) 		? $xidInvent 		= $_POST['idInvent'] 		: $xidInvent		= '');
		(isset($_POST['reference']) 	? $xreference 		= $_POST['reference'] 		: $xreference 		= '');
		(isset($_POST['dateAjout']) 	? $xdateAjout 		= $_POST['dateAjout'] 		: $xdateAjout 		= '');
		(isset($_POST['libelle']) 		? $xlibelle 		= $_POST['libelle'] 		: $xlibelle 		= '');
		(isset($_POST['nbreLigne']) 	? $xnbreLigne 		= $_POST['nbreLigne'] 		: $xnbreLigne 		= '');
		$exercice = $_SESSION['GL_USER']['EXERCICE']; //$_SESSION;
		
		//$dateAjout = mysqlFormat($dateAjout);
		//Fill session vars
		$_SESSION['EDIT_INVENT']= array(
		'reference'	=> $xreference,
		'exercice'	=> $exercice,
		'dateAjout'	=> $xdateAjout,
		'libelle'	=> $xlibelle,
		'nbreLigne'	=> $xnbreLigne,
		);
		//Collect Data
		$_SESSION['EDIT_INVENT']['ligne'] =array();
		for($i=1; $i<=$_SESSION['EDIT_INVENT']['nbreLigne']; $i++){
			(isset($_POST['oldArticle'.$i]) ? $xoldArticle 		= $_POST['oldArticle'.$i] 	: $xoldArticle 	= '');
			(isset($_POST['idArticle'.$i]) 	? $xidArticle 		= $_POST['idArticle'.$i] 	: $xidArticle 	= '');
			(isset($_POST['prixUnit'.$i]) 	? $xprixUnit 		= $_POST['prixUnit'.$i] 	: $xprixUnit 	= '');
			(isset($_POST['qte'.$i]) 		? $xqte 			= $_POST['qte'.$i] 			: $xqte 		= '');
			(isset($_POST['typeinventaire'.$i]) ? $xtypeinventaire 	= $_POST['typeinventaire'.$i] 	: $xtypeinventaire 		= '');
			(isset($_POST['designat'.$i]) 	? $xdesignat 		= $_POST['designat'.$i] 	: $xdesignat	= '');
			(isset($_POST['unite'.$i]) 		? $xunite 			= $_POST['unite'.$i] 		: $xunite 		= '');
			
			if($xidArticle!='' && $xprixUnit!='' && $xqte!='') array_push($_SESSION['EDIT_INVENT']['ligne'], array('oldidArticle'=>$xoldArticle,'idArticle'=>$xidArticle, 'designat'=>$xdesignat, 'prixUnit'=>$xprixUnit, 'typeinventaire'=>$xtypeinventaire, 'qte'=>$xqte, 'unite'=>$xunite));
			
		}
		//Check the stock
		//Save data
		$SQL1 ="UPDATE $table1 SET `ID_INVENTAIRE`='".addslashes($xreference)."', `DATE_INVENTAIRE`='".addslashes(mysqlFormat($_SESSION['EDIT_INVENT']['dateAjout']))."' , ";
		$SQL1 .="`LIBELLE_INVENTAIRE`='".addslashes($_SESSION['EDIT_INVENT']['libelle'])."' WHERE ID_INVENTAIRE='$xidInvent'";
			
		//Connection to Database server
		mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
		//Select Database
		mysql_select_db(DB) or header('location:errorPage.php&code=');
		$result = mysql_query($SQL1) or header('location:errorPage.php&code=');
		
		foreach($_SESSION['EDIT_INVENT']['ligne']  as $key=>$row){
			if($row['oldidArticle']!='' && $row['idArticle']!=''){
				$SQL2 ="UPDATE $table2 SET `ID_ARTICLE`='".addslashes($row['idArticle'])."' ,
				`ID_INVENTAIRE`='".addslashes($xreference)."',
				`QTE_INVENTAIRE`=".addslashes($row['qte'])." , 
				`UNITE`='".addslashes($row['unite'])."' , 
				`TYPE_INVENTAIRE`='".addslashes($row['typeinventaire'])."',
				`PU_INVENTAIRE`=".addslashes($row['prixUnit']).",
				`NUM`=$key 
				WHERE ID_INVENTAIRE='$xidInvent' AND ID_ARTICLE='".addslashes($row['oldidArticle'])."'";
				$result = mysql_query($SQL2) or header('location:errorPage.php&code=');
			}
			elseif($row['oldidArticle']=='' && $row['idArticle']!=''){
				$SQL2 ="INSERT INTO $table2 (`ID_ARTICLE`, `ID_INVENTAIRE`,`ID_EXERCICE` , `QTE_INVENTAIRE`, `UNITE`, `TYPE_INVENTAIRE`,`PU_INVENTAIRE`,`NUM` ) 
				VALUES ('".addslashes($row['idArticle'])."' ,'".addslashes($xreference)."','$exercice',  ".addslashes($row['qte']).", 
				'".addslashes($row['unite'])."', '".addslashes($row['typeinventaire'])."', ".addslashes($row['prixUnit']).", $key);";
				$result = mysql_query($SQL2) or header('location:errorPage.php&code=');			
			}
		}
		mysql_close();
		//Log fils
		$log = logFile($_SESSION['GL_USER']['LOGIN'],date("Y-m-d H:i:s"),"Modification d'un inventaire ID:$xrefernce par ".$_SESSION['GL_USER']['LOGIN']);
		//Add one ligne
		array_push($_SESSION['EDIT_INVENT']['ligne'], array('oldidArticle'=>'','idArticle'=>'', 'designat'=>'', 'prixUnit'=>'', 'typeinventaire'=>'', 'qte'=>'', 'unite'=>''));
		$_SESSION['EDIT_INVENT']['nbreLigne']= $xnbreLigne+1;
		header('location:editinventaire.php?selectedTab=inputs&id='.$xrefernce);		
		break;
		
	case 'ETAT':
		$table1 = "stocks_inventaire";
		$table2 = "stocks_ligne_inventaire";
		$table3 = "stocks_article";
		$exercice = $_SESSION['GL_USER']['EXERCICE'];
		
		//Collect Data
		(isset($_POST['reference']) 	? $xreference 		= $_POST['reference'] 	: $xreference 	= '');
		(isset($_POST['article']) 		? $xarticle 		= $_POST['article'] 	: $xarticle 	= '');
		(isset($_POST['dateDebut']) 	? $xdateDebut 		= $_POST['dateDebut'] 	: $xdateDebut 	= '');
		(isset($_POST['dateFin']) 		? $xdateFin 		= $_POST['dateFin'] 	: $xdateFin 	= '');
		(isset($_POST['typeetat']) 		? $xtypeetat 		= $_POST['typeetat'] 	: $xtypeetat	= '');
		$exercice = $_SESSION['GL_USER']['EXERCICE']; //Exercice
		
		//Connection to Database server
		mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

		//Select Database
		mysql_select_db(DB) or header('location:errorPage.php&code=');
		
		//WHERE
		$Where = " WHERE $table1.ID_EXERCICE=$exercice AND $table1.ID_INVENTAIRE=$table2.ID_INVENTAIRE AND $table2.ID_ARTICLE=$table3.ID_ARTICLE AND ";
		$Ref ='';
		
		if (isset($xdateDebut) && $xdateDebut !='' && isset($xdateFin) && $xdateFin !='') $Per .= " ($table1.DATE_INVENTAIRE >='".addslashes(mysqlFormat($xdateDebut))."' AND $table1.DATE_INVENTAIRE <='".addslashes(mysqlFormat($xdateFin))."') OR "; //Date fin
		if (isset($xdateFin) && $xdateFin !='' && $xdateDebut =='') $Per .= " $table1.DATE_INVENTAIRE ='".addslashes(mysqlFormat($xdateFin))."' OR "; //Date début
		if (isset($xdateDebut) && $xdateDebut !='' && $xdateFin =='') $Per .= " $table1.DATE_INVENTAIRE ='".addslashes(mysqlFormat($xdateDebut))."' OR "; //Date fin
		
		if($Ref != '') $Where .= "(".substr($Ref,0,strlen($Ref)-3).") AND ";
		if($Per != '') $Where .= "(".substr($Per,0,strlen($Per)-3).") AND ";
		
		if($Where != '') $Where = substr($Where,0,strlen($Where)-4);
		//SQL
		$SQL = "SELECT * FROM $table1, $table2, $table3 $Where;";
		$result = mysql_query($SQL) or header('location:errorPage.php&code=');
		
		$SQL = "SELECT * FROM $table1, $table2, $table3 $Where GROUP BY $table1.ID_INVENTAIRE;";
		$ptr = mysql_query($SQL) or header('location:errorPage.php&code=');
		
		//LIBELLE
		$Ref ='';
		$Per ='';
		foreach ($xreference as $key=>$val) (isset($val) && $val !='' ? $Ref .= " $val, " : $Ref.='');//Référence		
		
		if (isset($xdateDebut) && $xdateDebut !='' && isset($xdateFin) && $xdateFin !='')$Per .= " du ".frFormat($xdateDebut)." au ".frFormat($xdateFin); //Date fin
		if (isset($xdateFin) && $xdateFin !='' && $xdateDebut =='') $Per .= " du ".frFormat($xdateFin); //Date début
		if (isset($xdateDebut) && $xdateDebut !='' && $xdateFin =='') $Per .= " du ".frFormat($xdateDebut); //Date fin
		
		if($Ref != '') $Ref = "Appel d'offre n°".substr($Ref,0,strlen($Ref)-2);
				
		$_SESSION['ETAT_INVENTAIRE']['libelle'] = "Etat des inventaires";
		$_SESSION['ETAT_INVENTAIRE']['critere'] = $Ref.$Art.$Per;
		$_SESSION['ETAT_INVENTAIRE']['data'] = array();
		
		while($row = mysql_fetch_array($ptr)){	
			$fils = array();
			while($row1 = mysql_fetch_array($result)){
				if($row['ID_INVENTAIRE']==$row1['ID_INVENTAIRE']){
					array_push($fils, 
					array('id'=>$row1['ID_INVENTAIRE'],
					'idArticle'=>$row1['ID_ARTICLE'],
					'designat'=>$row1['LIBELLE_ARTICLE'],
					'qte'=>$row1['TYPE_INVENTAIRE'].$row1['QTE_INVENTAIRE'],
					'prixUnit'=>$row1['PU_INVENTAIRE'],
					'unite'=>$row1['UNITE']));
				}
			}
			$pere = array('id'=>$row['ID_INVENTAIRE'], 'd'=>frFormat($row['DATE_INVENTAIRE']), 'lib'=>$row['LIBELLE_INVENTAIRE'], 'fils'=>$fils);
			array_push($_SESSION['ETAT_INVENTAIRE']['data'],$pere);
			mysql_data_seek($result,0);
		}
		
		mysql_close();
		header('location:etatinventaires1.php?selectedTab=inputs&error=0');
		break;


	case 'ETATO':
		$table1 = "stocks_inventaire";
		$table2 = "stocks_ligne_inventaire";
		$table3 = "stocks_article";
		$exercice = $_SESSION['GL_USER']['EXERCICE'];
		
		//Collect Data
		(isset($_POST['reference']) 	? $xreference 		= $_POST['reference'] 	: $xreference 	= '');
		(isset($_POST['article']) 		? $xarticle 		= $_POST['article'] 	: $xarticle 	= '');
		(isset($_POST['dateDebut']) 	? $xdateDebut 		= $_POST['dateDebut'] 	: $xdateDebut 	= '');
		(isset($_POST['dateFin']) 		? $xdateFin 		= $_POST['dateFin'] 	: $xdateFin 	= '');
		(isset($_POST['typeetat']) 		? $xtypeetat 		= $_POST['typeetat'] 	: $xtypeetat	= '');
		$exercice = $_SESSION['GL_USER']['EXERCICE']; //Exercice
		
		//Connection to Database server
		mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

		//Select Database
		mysql_select_db(DB) or header('location:errorPage.php&code=');
		
		//WHERE
		$Where = " WHERE $table1.ID_EXERCICE=$exercice AND $table1.ID_INVENTAIRE=$table2.ID_INVENTAIRE AND $table2.ID_ARTICLE=$table3.ID_ARTICLE AND ";
		$Ref ='';
		$Per ='';
		foreach ($xreference as $key=>$val) (isset($val) && $val !='' ? $Ref .= " $table1.ID_INVENTAIRE='$val' OR " : $Ref.='');//Référence		
		
		if (isset($xdateDebut) && $xdateDebut !='' && isset($xdateFin) && $xdateFin !='') $Per .= " ($table1.DATE_INVENTAIRE >='".addslashes(mysqlFormat($xdateDebut))."' AND $table1.DATE_INVENTAIRE <='".addslashes(mysqlFormat($xdateFin))."') OR "; //Date fin
		if (isset($xdateFin) && $xdateFin !='' && $xdateDebut =='') $Per .= " $table1.DATE_INVENTAIRE ='".addslashes(mysqlFormat($xdateFin))."' OR "; //Date début
		if (isset($xdateDebut) && $xdateDebut !='' && $xdateFin =='') $Per .= " $table1.DATE_INVENTAIRE ='".addslashes(mysqlFormat($xdateDebut))."' OR "; //Date fin
		
		if($Ref != '') $Where .= "(".substr($Ref,0,strlen($Ref)-3).") AND ";
		if($Per != '') $Where .= "(".substr($Per,0,strlen($Per)-3).") AND ";
		
		if($Where != '') $Where = substr($Where,0,strlen($Where)-4);
		//SQL
		$SQL = "SELECT * FROM $table1, $table2, $table3 $Where;";
		$result = mysql_query($SQL) or header('location:errorPage.php&code=');
		
		$SQL = "SELECT * FROM $table1, $table2, $table3 $Where GROUP BY $table1.ID_INVENTAIRE;";
		$ptr = mysql_query($SQL) or header('location:errorPage.php&code=');
		
		//LIBELLE
		$Ref ='';
		$Per ='';
		foreach ($xreference as $key=>$val) (isset($val) && $val !='' ? $Ref .= " $val, " : $Ref.='');//Référence		
		
		if (isset($xdateDebut) && $xdateDebut !='' && isset($xdateFin) && $xdateFin !='')$Per .= " du ".$xdateDebut." au ".$xdateFin; //Date fin
		if (isset($xdateFin) && $xdateFin !='' && $xdateDebut =='') $Per .= " du ".$xdateFin; //Date début
		if (isset($xdateDebut) && $xdateDebut !='' && $xdateFin =='') $Per .= " du ".$xdateDebut; //Date fin
		
		if($Ref != '') $Ref = "Inventaire n°".substr($Ref,0,strlen($Ref)-2);
				
		$_SESSION['ETAT_INVENTAIRE']['libelle'] = "Etat des inventaires";
		$_SESSION['ETAT_INVENTAIRE']['critere'] = $Ref.$Art.$Per;
		$_SESSION['ETAT_INVENTAIRE']['data'] = array();
		
		while($row = mysql_fetch_array($ptr)){	
			$fils = array();
			while($row1 = mysql_fetch_array($result)){
				if($row['ID_INVENTAIRE']==$row1['ID_INVENTAIRE']){
					array_push($fils, 
					array('id'=>$row1['ID_INVENTAIRE'],
					'idArticle'=>$row1['ID_ARTICLE'],
					'designat'=>$row1['LIBELLE_ARTICLE'],
					'qte'=>$row1['TYPE_INVENTAIRE'].$row1['QTE_INVENTAIRE'],
					'prixUnit'=>$row1['PU_INVENTAIRE'],
					'unite'=>$row1['UNITE']));
				}
			}
			$pere = array('id'=>$row['ID_INVENTAIRE'], 'd'=>frFormat($row['DATE_INVENTAIRE']), 'lib'=>$row['LIBELLE_INVENTAIRE'], 'fils'=>$fils);
			array_push($_SESSION['ETAT_INVENTAIRE']['data'],$pere);
			mysql_data_seek($result,0);
		}
		
		mysql_close();
		header('location:etatinventaireso1.php?selectedTab=outputs');
		break;
	
	default:
	//echo 'Fonctionnement incorrect...';
}
(isset($_GET['test']) ? $test = $_GET['test'] : $test ='');
switch($test){
	case 'VALIDER':
		$table1 = "stocks_inventaire";
		if(isset($_POST["code"])){		
			//Connection to Database server
			mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
			//Select Database
			mysql_select_db(DB) or header('location:errorPage.php&code=');
			//SQL
			$SQL = "SELECT COUNT(ID_INVENTAIRE) FROM $table1 WHERE VALIDER=1 AND `ID_INVENTAIRE` ='".$_POST["code"]."'";
			$result = mysql_query($SQL) or header('location:errorPage.php&code=');
			$row = mysql_fetch_array($result);
			($row[0]>0 ? $msg =1 : $msg =0);
		}	
		echo $msg;	
		break;

	case 'DOUBLON':
		$table1 = "stocks_inventaire";
		$msg = "";
		if(isset($_POST["codeInvent"])){		
			//Connection to Database server
			mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
			//Select Database
			mysql_select_db(DB) or header('location:errorPage.php&code=');
			//SQL
			$SQL = "SELECT COUNT(ID_INVENTAIRE) FROM $table1 WHERE `ID_INVENTAIRE` ='".$_POST["codeInvent"]."'";
			$result = mysql_query($SQL) or header('location:errorPage.php&code=');
			$row = mysql_fetch_array($result);
			if($row[0]>0) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Ce code existe d&eacute;j&agrave;, veuillez entrer une autre r&eacute;f&eacute;rence.';}
		}	
		echo $msg;	
		break;

	default:
	//echo 'Fonctionnement incorrect...';
}
?>
