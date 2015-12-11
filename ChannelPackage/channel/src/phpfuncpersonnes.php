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
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
	
	$table = "stocks_groupe";
	//SQL
	$SQL = "SELECT NOM_GROUPE FROM $table WHERE ID_GROUPE=$id";
	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');
	$result = mysql_query($SQL) or header('location:errorPage.php&code=');
	$row = mysql_fetch_array($result);
	return $row['NOM_GROUPE'];
}

//Action to do
(isset($_POST['myaction']) ? $myaction = $_POST['myaction'] : $myaction ='');
switch($myaction){
	case 'ETAPE2':  //Add data
		$table1 = "stocks_personnel";
		$table2 = "stocks_compte";
		
		//Collect Data
		(isset($_POST['numMatricule']) 	? $xnumMatricule 	= $_POST['numMatricule'] 	: $xnumMatricule	= '');
		(isset($_POST['nomPrenoms']) 	? $xnomPrenoms		= $_POST['nomPrenoms'] 		: $xnomPrenoms 		= '');
		(isset($_POST['fonction']) 		? $xfonction	 	= $_POST['fonction'] 		: $xfonction		= '');
		(isset($_POST['service']) 		? $xservice			= $_POST['service'] 		: $xservice		 	= '');
		(isset($_POST['adresse']) 		? $xadresse		 	= $_POST['adresse'] 		: $xadresse			= '');
		(isset($_POST['ville']) 		? $xville			= $_POST['ville'] 			: $xville 			= '');
		(isset($_POST['telephone']) 	? $xtelephone		= $_POST['telephone'] 		: $xtelephone		= '');
		(isset($_POST['email']) 		? $xemail			= $_POST['email'] 			: $xemail 			= '');
		
		(isset($_POST['groupe']) 		 ? $xgroupe			= $_POST['groupe'] 			: $xgroupe			= '');
		(isset($_POST['nomUtilisateur']) ? $xnomUtilisateur	= $_POST['nomUtilisateur'] 	: $xnomUtilisateur 			= '');
		(isset($_POST['motPasse1']) 	? $xmotPasse1		= $_POST['motPasse1'] 		: $xmotPasse1		= '');
		
		$_SESSION['DATA_PERS']=array(
		'numMatricule' =>$xnumMatricule,
		'nomPrenoms' =>$xnomPrenoms,
		'fonction' =>$xfonction,
		'service' =>$xservice,
		'adresse' =>$xadresse,
		'ville' =>$xville,
		'telephone' =>$xtelephone,
		'email' =>$xemail,
		
		'groupe' =>$xgroupe.' - '.libGroupe($xgroupe),
		'nomUtilisateur' =>$xnomUtilisateur,
		'motPasse1' =>$xmotPasse1
		);
		
		//Save data
		$SQL1 ="INSERT INTO $table1 (`NUM_MATRICULE` ,`NOM_PRENOMS`,`FONCTION` , `SERVICE` ,`VILLE` ,`ADRESSE` ,`TEL` ,`EMAIL` ) 
		VALUES ('".addslashes($xnumMatricule)."' , '".addslashes($xnomPrenoms)."', '".addslashes($xfonction)."' ,
		'".addslashes($xservice)."' , '".addslashes($xville)."' , '".addslashes($xadresse)."' , '".addslashes($xtelephone)."',
		'".addslashes($xemail)."');";
		
		$SQL2 ="INSERT INTO $table2 (`LOGIN` ,`NUM_MATRICULE` ,`MOTPASSE` ,`ID_GROUPE` ) 
		VALUES ('".addslashes($xnomUtilisateur)."' , '".addslashes($xnumMatricule)."', md5(".addslashes($xmotPasse1).") ,'".addslashes($xgroupe)."';";
		
		//Connection to Database server
		mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
		
		//Select Database
		mysql_select_db(DB) or header('location:errorPage.php&code=');
		$result = mysql_query($SQL1) or header('location:errorPage.php&code=');
		$result = mysql_query($SQL2) or header('location:errorPage.php&code=');
		mysql_close();
		//Log fils
		$log = logFile($_SESSION['GL_USER']['LOGIN'],date("Y-m-d H:i:s"),"Ajout d'une personne ID:$xnumMatricule par ".$_SESSION['GL_USER']['LOGIN']);
		
		header('location:personnes.php?selectedTab=pareters&error=0');
		break;

	case 'ETAPE3':
		$table1 = "stocks_personnel";
		$table2 = "stocks_compte";
		
		//Collect Data
		(isset($_POST['numMatricule']) &&  $_SESSION['DATA_EDIT']['oldid'] != $_POST['numMatricule']	? $xnumMatricule = $_POST['numMatricule'] : $xnumMatricule = $_SESSION['DATA_EDIT']['oldid']);
		(isset($_POST['nomPrenoms']) 	? $xnomPrenoms		= $_POST['nomPrenoms'] 		: $xnomPrenoms 		= '');
		(isset($_POST['fonction']) 		? $xfonction	 	= $_POST['fonction'] 		: $xfonction		= '');
		(isset($_POST['service']) 		? $xservice			= $_POST['service'] 		: $xservice		 	= '');
		(isset($_POST['adresse']) 		? $xadresse		 	= $_POST['adresse'] 		: $xadresse			= '');
		(isset($_POST['ville']) 		? $xville			= $_POST['ville'] 			: $xville 			= '');
		(isset($_POST['telephone']) 	? $xtelephone		= $_POST['telephone'] 		: $xtelephone		= '');
		(isset($_POST['email']) 		? $xemail			= $_POST['email'] 			: $xemail 			= '');
	
		$_SESSION['DATA_EDIT']=array(
		'oldid'=>$_SESSION['DATA_EDIT']['oldid'],
		'numMatricule' =>$xnumMatricule,
		'nomPrenoms' =>$xnomPrenoms,
		'fonction' =>$xfonction,
		'service' =>$xservice,
		'adresse' =>$xadresse,
		'ville' =>$xville,
		'telephone' =>$xtelephone,
		'email' =>$xemail
		);
		
		//Save data
		$SQL1 ="UPDATE $table1 SET `NUM_MATRICULE`='".addslashes($xnumMatricule)."' ,`NOM_PRENOMS`='".addslashes($xnomPrenoms)."', ";
		$SQL1 .="`FONCTION`='".addslashes($xfonction)."' , `SERVICE`='".addslashes($xservice)."' ,`VILLE`='".addslashes($xville)."' ,";
		$SQL1 .="`ADRESSE`='".addslashes($xadresse)."' ,`TEL`='".addslashes($xtelephone)."' ,`EMAIL`='".addslashes($xemail)."' WHERE NUM_MATRICULE LIKE '".$_SESSION['DATA_EDIT']['oldid']."'";
		
		$SQL2 ="UPDATE $table2 `NUM_MATRICULE`='".addslashes($xnumMatricule)."' WHERE NUM_MATRICULE LIKE '".$_SESSION['DATA_EDIT']['oldid']."'";
				
		//Connection to Database server
		mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
		
		//Select Database
		mysql_select_db(DB) or header('location:errorPage.php&code=');
		$result = mysql_query($SQL1) or header('location:errorPage.php&code=');
		$result = mysql_query($SQL2) or header('location:errorPage.php&code=');
		mysql_close();
		//Log fils
		$log = logFile($_SESSION['GL_USER']['LOGIN'],date("Y-m-d H:i:s"),"Modification d'une personne ID:$xnumMatricule par ".$_SESSION['GL_USER']['LOGIN']);
		
		header('location:personnes.php?selectedTab=pareters&error=0');
		break;

	case 'EDIT':
		$table1 = "stocks_personnel";
		$table2 = "stocks_compte";
		(isset($_POST['rowSelection']) ? $id = $_POST['rowSelection'][0] : $id = array());

		//Connection to Database server
		mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
		
		//Select Database
		mysql_select_db(DB) or header('location:errorPage.php&code=');
		
		//Data
		$SQL1 ="SELECT * FROM $table1 WHERE NUM_MATRICULE LIKE '$id'"; 
		$result = mysql_query($SQL1) or header('location:errorPage.php&code=');
		$row = mysql_fetch_array($result);
		
		$SQL1 ="SELECT * FROM $table2 WHERE NUM_MATRICULE LIKE '$id'"; 
		$result1 = mysql_query($SQL1) or header('location:errorPage.php&code=');
		$row1 = mysql_fetch_array($result1);
		
		$_SESSION['DATA_EDIT']=array(
		'oldid' =>$id,
		'numMatricule' =>stripcslashes($row['NUM_MATRICULE']),
		'nomPrenoms' =>stripcslashes($row['NOM_PRENOMS']),
		'fonction' =>stripcslashes($row['FONCTION']),
		'service' =>stripcslashes($row['SERVICE']),
		'adresse' =>stripcslashes($row['ADRESSE']),
		'ville' =>stripcslashes($row['VILLE']),
		'telephone' =>stripcslashes($row['TEL']),
		'email' =>stripcslashes($row['EMAIL']),		
		'groupe' =>$row1['ID_GROUPE'],
		'nomUtilisateur' =>stripcslashes($row1['LOGIN']),
		'motPasse1' =>stripcslashes($row1['MOTPASSE'])
		);
		mysql_close();
		header('location:editpersonnes.php?selectedTab=pareters&id='.$id);
		break;

	case 'DEL':
		$table1 = "stocks_personnel";
		$table2 = "stocks_compe";
		(isset($_POST['rowSelection']) ? $id = $_POST['rowSelection'] : $id = array());
		
		//Connection to Database server
		mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
		
		//Select Database
		mysql_select_db(DB) or header('location:errorPage.php&code=');
		
		//Save data
		foreach($id as $key=>$val){
			$SQL1 ="DELETE FROM $table1 WHERE NUM_MATRICULE='$val';";
			$result = mysql_query($SQL1) or header('location:errorPage.php&code=');
			
			$SQL1 ="DELETE FROM $table2 WHERE NUM_MATRICULE='$val';";
			$result = mysql_query($SQL1) or header('location:errorPage.php&code=');
			//Log fils
			$log = logFile($_SESSION['GL_USER']['LOGIN'],date("Y-m-d H:i:s"),"Suppression d'une personne ID:$xnumMtricule par ".$_SESSION['GL_USER']['LOGIN']);
		}
		
		mysql_close();
		
		header('location:personnes.php?selectedTab=pareters');
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
			mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
			//Select Database
			mysql_select_db(DB) or header('location:errorPage.php&code=');
			//SQL
			$SQL = "SELECT COUNT(NUM_MATRICULE) FROM $table1 WHERE `NUM_MATRICULE` LIKE '".$_POST["numMatricule"]."'";
			$result = mysql_query($SQL) or header('location:errorPage.php&code=');
			$row = mysql_fetch_array($result);
			if($row[0]>0) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Ce code existe d&eacute;j&agrave;, veuillez entrer un autre num&eacute;ro matricule.';}
		}	
		echo $msg;	
		break;

	case 'LOGIN':
		$table1 = "stocks_compte";
		$msg = "";
		if(isset($_POST["nomUtilisateur"])){		
			//Connection to Database server
			mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
			//Select Database
			mysql_select_db(DB) or header('location:errorPage.php&code=');
			//SQL
			$SQL = "SELECT COUNT(LOGIN) FROM $table1 WHERE `LOGIN` LIKE '".$_POST["nomUtilisateur"]."'";
			$result = mysql_query($SQL) or header('location:errorPage.php&code=');
			$row = mysql_fetch_array($result);
			if($row[0]>0) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Ce code existe d&eacute;j&agrave;, veuillez entrer un autre nom d\'utilisateur.';}
		}	
		echo $msg;	
		break;

	case 'COMPTE':
		$table1 = "stocks_compte";
		$msg = "";
		if(isset($_POST["numMatricule"])){		
			//Connection to Database server
			mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
			//Select Database
			mysql_select_db(DB) or header('location:errorPage.php&code=');
			//SQL
			$SQL = "SELECT COUNT(LOGIN) FROM $table1 WHERE `NUM_MATRICULE`  LIKE '".$_POST["numMatricule"]."'";
			$result = mysql_query($SQL) or header('location:errorPage.php&code=');
			$row = mysql_fetch_array($result);
			($row[0]>0 ? $msg =1 : $msg =0);
		}	
		echo $msg;	
		break;

	default:
	//echo 'Fonctionnement incorrect...';
}
?>
