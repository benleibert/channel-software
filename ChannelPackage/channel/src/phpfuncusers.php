<?php
//PHP Session 
session_start();
//MySQL Parameters
require_once('../lib/global.inc');
//PHP functions librairy
require_once('../lib/phpfuncLib.php');

//Lib groupe
function libGroupe($id){
	//Connection to Database server
	$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
	
	$table = "stocks_groupe";
	//SQL
	$SQL = "SELECT NOM_GROUPE FROM $table WHERE ID_GROUPE=$id";
	//Select Database
	mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');
	$result = mysql_query($SQL,$idCon) or header('location:errorPage.php&code=');
	$row = mysql_fetch_array($result);
	return $row['NOM_GROUPE'];
	mysql_close($idCon);
}

//Action to do
(isset($_POST['myaction']) ? $myaction = $_POST['myaction'] : $myaction ='');
switch($myaction){
	case 'ETAPE2':  //Save data
		$table1 = "stocks_compte";
		
		//Collect Data
		(isset($_POST['numMatricule']) 	? $xnumMatricule 	= $_POST['numMatricule'] 	: $xnumMatricule	= '');
		(isset($_POST['groupe']) 		 ? $xgroupe			= $_POST['groupe'] 			: $xgroupe			= '');
		(isset($_POST['nomUtilisateur']) ? $xnomUtilisateur	= $_POST['nomUtilisateur'] 	: $xnomUtilisateur 			= '');
		(isset($_POST['motPasse1']) 	? $xmotPasse1		= $_POST['motPasse1'] 		: $xmotPasse1		= '');
		(isset($_POST['statusUser']) 	? $xstatusUser		= $_POST['statusUser'] 		: $xstatusUser		= '');
		/*
		$_SESSION['DATA_PERS']=array(
		'numMatricule' =>$xnumMatricule,
		'groupe' =>$xgroupe.' - '.libGroupe($xgroupe),
		'nomUtilisateur' =>$xnomUtilisateur,
		'motPasse1' =>$xmotPasse1,
		'statusUser'  =>$xstatusUser
		);
		*/
		//Save data
		$SQL2 ="INSERT INTO $table1 (`LOGIN` ,`NUM_MATRICULE` ,`MOTPASSE` ,`ID_GROUPE` ,`STATUS`) 
		VALUES ('".addslashes($xnomUtilisateur)."' , '".addslashes($xnumMatricule)."', md5('".addslashes($xmotPasse1)."') ,'".addslashes($xgroupe)."','".$xstatusUser."');";
		
		//Connection to Database server
		$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
		
		//Select Database
		mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');
		$result = mysql_query($SQL2,$idCon) or header('location:errorPage.php&code=');
		mysql_close($idCon);
		//Log fils
		$log = logFile($_SESSION['GL_USER']['LOGIN'],date("Y-m-d H:i:s"),"Ajout d'une compte ID:$xnomUtilisateur par ".$_SESSION['GL_USER']['LOGIN']);
		
		header('location:users.php?selectedTab=pareters&error=0');
		break;

	case 'ETAPE3':  //Edit data	
		$table1 = "stocks_compte";
		
		//Collect Data
		(isset($_POST['nomUtilisateur']) &&  $_SESSION['DATA_EDIT']['oldid'] != $_POST['nomUtilisateur']	? $xnomUtilisateur = $_POST['nomUtilisateur'] : $xnomUtilisateur = $_SESSION['DATA_EDIT']['oldid']);
		(isset($_POST['numMatricule']) 	? $xnumMatricule 	= $_POST['numMatricule'] 	: $xnumMatricule	= '');
		(isset($_POST['groupe']) 		 ? $xgroupe			= $_POST['groupe'] 			: $xgroupe			= '');
		(isset($_POST['motPasse1']) 	? $xmotPasse1		= $_POST['motPasse1'] 		: $xmotPasse1		= '');
		($xmotPasse1 == '' ? $pwd='' : $pwd = "`MOTPASSE`='".addslashes($xmotPasse1)."' ,");
		(isset($_POST['statusUser']) 	? $xstatusUser		= $_POST['statusUser'] 		: $xstatusUser		= '');
		
		/*
		$_SESSION['DATA_EDIT']=array(
		'oldid' =>$_SESSION['DATA_EDIT']['oldid'],
		'numMatricule' =>$xnumMatricule,
		'groupe' =>$xgroupe.' - '.libGroupe($xgroupe),
		'nomUtilisateur' =>$xnomUtilisateur,
		'statusUser' =>$xstatusUser,
		);
		*/
		 
		//Save data
		$SQL2 ="UPDATE $table1 SET `LOGIN`='".addslashes($xnomUtilisateur)."' , $pwd 
		`ID_GROUPE`='".addslashes($xgroupe)."' ,`STATUS`='".$xstatusUser."' 
		WHERE LOGIN LIKE '".$_SESSION['DATA_EDIT']['oldid']."'";
		
		//Connection to Database server
		$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
		
		//Select Database
		mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');
		$result = mysql_query($SQL2,$idCon) or header('location:errorPage.php&code=');
		mysql_close($idCon);
		//Log fils
		$log = logFile($_SESSION['GL_USER']['LOGIN'],date("Y-m-d H:i:s"),"Modification d'une personne ID:$xnomUtilisateur par ".$_SESSION['GL_USER']['LOGIN']);
		
		header('location:users.php?selectedTab=pareters&error=0');
		break;

	case 'EDIT':   //Fill data array
		$table1 = "stocks_compte";
		(isset($_POST['rowSelection']) ? $id = $_POST['rowSelection'][0] : $id = array());

		//Connection to Database server
		$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
		
		//Select Database
		mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');
		
		//Data
		$SQL1 ="SELECT * FROM $table1 WHERE LOGIN LIKE '$id'"; 
		$result = mysql_query($SQL1,$idCon) or header('location:errorPage.php&code=');
		$row = mysql_fetch_array($result);
		
		$_SESSION['DATA_EDIT']=array(
		'oldid' =>$id,
		'numMatricule' =>$row['NUM_MATRICULE'],
		'groupe' =>$row['ID_GROUPE'],
		'nomUtilisateur' =>$row['LOGIN'],
		'status' =>$row['STATUS']
		);
		mysql_close($idCon);
		header('location:editusers.php?selectedTab=pareters&id='.$id);
		break;

	case 'DEL':  //Delete Data
		$table1 = "stocks_compte";
		(isset($_POST['rowSelection']) ? $id = $_POST['rowSelection'] : $id = array());
		
		//Connection to Database server
		$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
		
		//Select Database
		mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');
		
		//Save data
		foreach($id as $key=>$val){
			$SQL1 ="DELETE FROM $table1 WHERE LOGIN='$val';";
			$result = mysql_query($SQL1,$idCon) or header('location:errorPage.php&code=');
		}
		
		mysql_close($idCon);
		//Log fils
		$log = logFile($_SESSION['GL_USER']['LOGIN'],date("Y-m-d H:i:s"),"Suppression d'un compte ID: $id par ".$_SESSION['GL_USER']['LOGIN']);
		
		header('location:users.php?selectedTab=pareters');
		break;
	
	default:
	//echo 'Fonctionnement incorrect...';
}

(isset($_GET['test']) ? $test = $_GET['test'] : $test ='');
switch($test){
	case 'NUMMLLE':
		$table1 = "stocks_personnel";
		$msg = "";
		if(isset($_POST["numMatricule"])){		
			//Connection to Database server
			$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
			//Select Database
			mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');
			//SQL
			$SQL = "SELECT COUNT(NUM_MATRICULE) FROM $table1 WHERE `NUM_MATRICULE` LIKE '".$_POST["numMatricule"]."'";
			$result = mysql_query($SQL,$idCon) or header('location:errorPage.php&code=');
			$row = mysql_fetch_array($result);
			if($row[0]>0) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Ce code existe d&eacute;j&agrave;, veuillez entrer un autre num&eacute;ro matricule.';}
		}	
		mysql_close($idCon);
		echo $msg;	
		break;

		case 'LOGIN':
		$table1 = "stocks_compte";
		$msg = "";
		if(isset($_POST["nomUtilisateur"])){		
			//Connection to Database server
			$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
			//Select Database
			mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');
			//SQL
			$SQL = "SELECT COUNT(LOGIN) FROM $table1 WHERE `LOGIN` LIKE '".$_POST["nomUtilisateur"]."'";
			$result = mysql_query($SQL,$idCon) or header('location:errorPage.php&code=');
			$row = mysql_fetch_array($result);
			if($row[0]>0) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Ce code existe d&eacute;j&agrave;, veuillez entrer un autre nom d\'utilisateur.';}
		}	
		mysql_close($idCon);
		echo $msg;	
		break;

	default:
	//echo 'Fonctionnement incorrect...';
}
?>
