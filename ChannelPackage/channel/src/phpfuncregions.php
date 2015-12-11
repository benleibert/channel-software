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
	
	//-------------------------------- ADD REGION ETAPE 1 -----------------------------------------
	// DISPALY FORM addregions.php
	
	//-------------------------------- ADD REGION ETAPE 2 -----------------------------------------
	case 'ETAPE2': //ADD DATA
		$table1 = "stocks_region";
		
		//Collect Data
		(isset($_POST['codeRegion']) 	? $xcodeRegion 		= $_POST['codeRegion'] 	: $xcodeRegion		= '');
		(isset($_POST['nomRegion']) 	? $xnomRegion 		= $_POST['nomRegion'] 	: $xnomRegion 	= '');
		
		//Array data
		$_SESSION['DATA_REG']=array(
		'codeRegion' =>$xcodeRegion,
		'nomRegion' =>$xnomRegion
		);
		
		//Save data
		$SQL1 ="INSERT INTO $table1 (`ID_REGION` ,`NOM_REGION` ) 
		VALUES ('".addslashes($xcodeRegion )."' , '".addslashes($xnomRegion)."');";
		
		//Connection to Database server
		$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=DB_C');
		
		//Select Database
		mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=DB_S');
		$result = mysql_query($SQL1,$idCon) or header('location:errorPage.php&code=DB_E');
		
		//Close connexion
		mysql_close($idCon);
		//Log fils
		$log = logFile($_SESSION['GL_USER']['LOGIN'],date("Y-m-d H:i:s"),"Ajout d'une region ID:$xcodeRegion par ".$_SESSION['GL_USER']['LOGIN']);
		
		header('location:regions.php?selectedTab=pareters');
		break;


	//-------------------------------- EDIT REGION ETAPE 1 -----------------------------------------		
	case 'EDIT':  //Fill edit array
		$table1 = "stocks_region";
		(isset($_POST['rowSelection']) ? $id = $_POST['rowSelection'][0] : $id = array());

		//Connection to Database server
		$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
		
		//Select Database
		mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');
		
		//Data
		$SQL1 ="SELECT * FROM $table1 WHERE ID_REGION='$id'"; 
		$result = mysql_query($SQL1,$idCon) or header('location:errorPage.php&code=');
		$row = mysql_fetch_array($result);
		
		$_SESSION['DATA_EDIT']=array(
		'oldid' =>$id,
		'codeRegion' =>$row['ID_REGION'],
		'nomRegion' =>stripcslashes($row['NOM_REGION'])
		);
		header('location:editregions.php?selectedTab=pareters&id='.$id);
		break;
	
		

	//-------------------------------- EDIT REGION ETAPE 2 -----------------------------------------
	case 'ETAPE3': //EDIT DATA
		$table1 = "stocks_region";
		$table2 = "stocks_province";

		//Collect Data
		(isset($_POST['codeRegion']) &&  $_SESSION['DATA_EDIT']['oldid'] != $_POST['codeRegion'] ? $xcodeRegion = $_POST['codeRegion'] : $xcodeRegion = $_SESSION['DATA_EDIT']['oldid']);
		(isset($_POST['nomRegion']) 	? $xnomRegion 		= $_POST['nomRegion'] 	: $xnomRegion 	= '');
		$_SESSION['DATA_EDIT']=array(
		'oldid'=>$_SESSION['DATA_EDIT']['oldid'],
		'codeRegion' =>$xcodeRegion,
		'nomRegion' =>$xnomRegion
		);
		
		//Save data
		$SQL1 ="UPDATE $table1 SET `ID_REGION`='".addslashes($xcodeRegion )."' ,`NOM_REGION`='".addslashes($xnomRegion)."' WHERE ID_REGION='".$_SESSION['DATA_EDIT']['oldid']."'";
		
		//Connection to Database server
		$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
		
		//Select Database
		mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');
		$result = mysql_query($SQL1,$idCon) or header('location:errorPage.php&code=');
		
		//Update table Province linked to Region
		if($_SESSION['DATA_EDIT']['oldid']!= $_POST['codeRegion']){
			$SQL1 ="UPDATE $table2 SET `ID_REGION`='".addslashes($xcodeRegion)."' WHERE ID_REGION=".$_SESSION['DATA_EDIT']['oldid'] ;
			$result = mysql_query($SQL1) or header('location:errorPage.php&code=');
		}
		mysql_close($idCon);
		//Log fils
		$log = logFile($_SESSION['GL_USER']['LOGIN'],date("Y-m-d H:i:s"),"Modification d'une région ID:$xcodeRegion par ".$_SESSION['GL_USER']['LOGIN']);
		
		header('location:regions.php?selectedTab=pareters&error=0');
		break;

	//-------------------------------- DELET REGION AND LINK PROVINCE -----------------------------------------
	case 'DEL':  // delete Data
		$table1 = "stocks_region";
		$table2 = "stocks_province";
		(isset($_POST['rowSelection']) ? $id = $_POST['rowSelection'] : $id = array());
		
		//Connection to Database server
		$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
		
		//Select Database
		mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');
		
		//Save data
		foreach($id as $key=>$val){
			$SQL1 ="DELETE FROM $table1 WHERE ID_REGION='$val';";
			$result = mysql_query($SQL1,$idCon) or header('location:errorPage.php&code=');
			//Elements liés
			$SQL1 ="DELETE FROM $table2 WHERE ID_REGION='$val';";
			$result = mysql_query($SQL1,$idCon) or header('location:errorPage.php&code=');
			//Log fils
			$log = logFile($_SESSION['GL_USER']['LOGIN'],date("Y-m-d H:i:s"),"Suppression d'une region ID:$val par ".$_SESSION['GL_USER']['LOGIN']);
		}
		
		mysql_close($idCon);
		header('location:regions.php?selectedTab=pareters');
		break;

	
	default:
	//echo 'Fonctionnement incorrect...';
}

(isset($_GET['test']) ? $test = $_GET['test'] : $test ='');
switch($test){
	case 'CODEREGION':
		$table1 = "stocks_region";
		$msg = "";
		if(isset($_POST["codeRegion"])){		
			if(!is_numeric($_POST["codeRegion"])) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Veuillez entrer un entier s\'il vous pla&icirc;t.';} 
			else{
				//Connection to Database server
				$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
				//Select Database
				mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');
				//SQL
				$SQL = "SELECT COUNT(ID_REGION) FROM $table1 WHERE `ID_REGION` =".$_POST["codeRegion"];
				$result = mysql_query($SQL,$idCon) or header('location:errorPage.php&code=');
				$row = mysql_fetch_array($result);
				if($row[0]>0) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Ce code existe d&eacute;j&agrave;, veuillez entrer un autre code r&eacute;gion.';}
			}
		}	
		echo $msg;	
		break;


	//-------------------------- CHECK LINK REGION TO PROVINCE -------------------------------------
	case 'PROVINCE':
		$table1 = "stocks_province";
		$msg = "";
		if(isset($_POST["codeRegion"])){		
			//Connection to Database server
			$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=DB_C');
			//Select Database
			mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=DB_S');
			//SQL
			$SQL = "SELECT COUNT(ID_PROVINCE) FROM $table1 WHERE `ID_REGION` =".$_POST["codeRegion"];
			$result = mysql_query($SQL,$idCon) or header('location:errorPage.php&code=DB_E');
			$row = mysql_fetch_array($result);
			($row[0]*1 >0 ? $msg =1 : $msg =0);
		}	
		echo $msg;	
		break;

	default:
	//echo 'Fonctionnement incorrect...';
}
?>
