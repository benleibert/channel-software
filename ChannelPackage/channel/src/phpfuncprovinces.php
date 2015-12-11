<?php
//PHP Session 
session_start();
//MySQL Parameters
require_once('../lib/global.inc');
//PHP functions librairy
require_once('../lib/phpfuncLib.php');

function nomRegion($id){
	$table1 = "stocks_region";
	//Save data
	$SQL1 ="SELECT NOM_REGION FROM $table1 WHERE ID_REGION=$id;";
		
		//Connection to Database server
		$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
		
		//Select Database
		mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');
		$result = mysql_query($SQL1,$idCon) or header('location:errorPage.php&code=');
		$row = mysql_fetch_array($result);
		return $row[0];
}

//Action to do
(isset($_POST['myaction']) ? $myaction = $_POST['myaction'] : $myaction ='');
switch($myaction){
	case 'ETAPE2':  //Add Data
		$table1 = "stocks_province";
		
		//Collect Data
		(isset($_POST['codeRegion']) 	? $xcodeRegion 			= $_POST['codeRegion'] 			: $xcodeRegion		= '');
		(isset($_POST['codeProvince']) 	? $xcodeProvince 		= $_POST['codeProvince'] 		: $xcodeProvince		= '');
		(isset($_POST['nomProvince']) 	? $xnomProvince 		= $_POST['nomProvince'] 		: $xnomProvince 	= '');
		$_SESSION['DATA_PROV']=array(
		'codeRegion' =>$xcodeRegion.' - '.nomRegion($xcodeRegion),
		'codeProvince' =>$xcodeProvince,
		'nomProvince' =>$xnomProvince
		);
		
		//Save data
		$SQL1 ="INSERT INTO $table1 (`ID_PROVINCE`, `ID_REGION` ,`NOM_PROVINCE` ) 
		VALUES ('".addslashes($xcodeProvince )."' ,'".addslashes($xcodeRegion )."' , '".addslashes($xnomProvince)."');";
		
		//Connection to Database server
		$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
		
		//Select Database
		mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');
		$result = mysql_query($SQL1,$idCon) or header('location:errorPage.php&code=');
		mysql_close($idCon);
		//Log fils
		$log = logFile($_SESSION['GL_USER']['LOGIN'],date("Y-m-d H:i:s"),"Ajout d'une province: $xcodeProvince par ".$_SESSION['GL_USER']['LOGIN']);
		
		header('location:provinces.php?selectedTab=pareters&error=0');
		break;

	case 'ETAPE3':  //Save edit array
		$table1 = "stocks_province";
		$table2 = "stocks_beneficiaire";
		
		//Collect Data
		(isset($_POST['codeProvince']) &&  $_SESSION['DATA_EDIT']['oldid'] != $_POST['codeProvince']	? $xcodeProvince = $_POST['codeProvince'] : $xcodeProvince = $_SESSION['DATA_EDIT']['oldid']);
		(isset($_POST['codeRegion']) 	? $xcodeRegion 			= $_POST['codeRegion'] 			: $xcodeRegion		= '');
		(isset($_POST['nomProvince']) 	? $xnomProvince 		= $_POST['nomProvince'] 		: $xnomProvince 	= '');
		
		$_SESSION['DATA_EDIT']=array(
		'oldid'=>$_SESSION['DATA_EDIT']['oldid'],
		'codeRegion' =>$xcodeRegion.' - '.nomRegion($xcodeRegion),
		'codeProvince' =>$xcodeProvince,
		'nomProvince' =>$xnomProvince
		);
		
		//Save data
		$SQL1 ="UPDATE $table1 SET `ID_PROVINCE`='".addslashes($xcodeProvince)."', `ID_REGION`='".addslashes($xcodeRegion )."' ,";
		$SQL1 .="`NOM_PROVINCE`='".addslashes($xnomProvince)."' WHERE ID_PROVINCE='".$_SESSION['DATA_EDIT']['oldid']."'";
		
		//Connection to Database server
		$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
		
		//Select Database
		mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');
		$result = mysql_query($SQL1,$idCon) or header('location:errorPage.php&code=');
		
		//Update table Province linked to Region
		if($_SESSION['DATA_EDIT']['oldid']!= $_POST['codeProvince']){
			$SQL1 ="UPDATE $table2 SET `ID_PROVINCE`='".addslashes($xcodeProvince)."' WHERE ID_PROVINCE=".$_SESSION['DATA_EDIT']['oldid'] ;
			$result = mysql_query($SQL1,$idCon) or header('location:errorPage.php&code=');
		}
		mysql_close($idCon);
		//Log fils
		$log = logFile($_SESSION['GL_USER']['LOGIN'],date("Y-m-d H:i:s"),"Modification d'une province ID:$xcodeProvince par ".$_SESSION['GL_USER']['LOGIN']);
		
		header('location:provinces.php?selectedTab=pareters&error=0');
		break;

	case 'DEL':  //Delete Data
		$table1 = "stocks_province";
		(isset($_POST['rowSelection']) ? $id = $_POST['rowSelection'] : $id = array());
		
		//Connection to Database server
		$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
		
		//Select Database
		mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');
		
		//Save data
		foreach($id as $key=>$val){
			$SQL1 ="DELETE FROM $table1 WHERE ID_PROVINCE=$val;";
			$result = mysql_query($SQL1) or header('location:errorPage.php&code=');
			//Log fils
			$log = logFile($_SESSION['GL_USER']['LOGIN'],date("Y-m-d H:i:s"),"Suppression d'une province ID:$val par ".$_SESSION['GL_USER']['LOGIN']);
		}
		
		mysql_close($idCon);
		
		header('location:provinces.php?selectedTab=pareters');
		break;

	case 'EDIT':
		$table1 = "stocks_province";
		$table2 = "stocks_region";
		(isset($_POST['rowSelection']) ? $id = $_POST['rowSelection'][0] : $id = array());

		//Connection to Database server
		$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
		
		//Select Database
		mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');
		
		//Data
		$SQL1 ="SELECT * FROM $table1 WHERE ID_PROVINCE=".$id; 
		$result = mysql_query($SQL1,$idCon) or header('location:errorPage.php&code=');
		$row = mysql_fetch_array($result);
		
		$_SESSION['DATA_EDIT']=array(
		'oldid' =>$id,
		'codeRegion' =>$row['ID_REGION'],
		'codeProvince' =>$row['ID_PROVINCE'],
		'nomProvince' =>stripcslashes($row['NOM_PROVINCE'])
		);
		header('location:editprovinces.php?selectedTab=pareters&id='.$id);
		break;
	
	default:
	//echo 'Fonctionnement incorrect...';
}

(isset($_GET['test']) ? $test = $_GET['test'] : $test ='');
switch($test){
	case 'CODEPROVINCE':
		$table1 = "stocks_province";
		$msg = "";
		if(isset($_POST["codeProvince"])){		
			if(!is_numeric($_POST["codeProvince"])) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Veuillez entrer un entier s\'il vous pla&icirc;t.';} 
			else{
				//Connection to Database server
				$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
				//Select Database
				mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');
				//SQL
				$SQL = "SELECT COUNT(ID_REGION) FROM $table1 WHERE `ID_PROVINCE` =".$_POST["codeProvince"];
				$result = mysql_query($SQL,$idCon) or header('location:errorPage.php&code=');
				$row = mysql_fetch_array($result);
				if($row[0]>0) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Ce code existe d&eacute;j&agrave;, veuillez entrer un autre code province.';}
			}
		}	
		echo $msg;	
		break;

	case 'BENEFICIAIRE':
		$table1 = "stocks_beneficiaire";
		$msg = "";
		if(isset($_POST["codeBenef"])){		
			//Connection to Database server
			$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
			//Select Database
			mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');
			//SQL
			$SQL = "SELECT COUNT(ID_PROVINCE) FROM $table1 WHERE `ID_PROVINCE` =".$_POST["codeBenef"];
			$result = mysql_query($SQL,$idCon) or header('location:errorPage.php&code=');
			$row = mysql_fetch_array($result);
			($row[0]>0 ? $msg =1 : $msg =0);
		}	
		echo $msg;	
		break;
	default:
	//echo 'Fonctionnement incorrect...';
}
?>