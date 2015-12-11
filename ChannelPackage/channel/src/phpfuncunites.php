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
	case 'ETAPE2': //Save Data
		$table1 = "stocks_unite";
		
		//Collect Data
		(isset($_POST['nomUnite']) 		? $xnomUnite 		= $_POST['nomUnite'] 		: $xnomUnite		= '');
		(isset($_POST['abreviation']) 	? $xabreviation		= $_POST['abreviation'] 	: $xabreviation 	= '');
		$_SESSION['DATA_UNIT']=array(
		'nomUnite' =>$xnomUnite,
		'abreviation' =>$xabreviation
		);
		
		//Save data
		$SQL1 ="INSERT INTO $table1 (`LIB_COURT` ,`LIBELLE_UNITE` ) 
		VALUES ('".addslashes($xabreviation)."' , '".addslashes($xnomUnite)."');";
		
		//Connection to Database server
		$diCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
		
		//Select Database
		mysql_select_db(DB) or header('location:errorPage.php&code=');
		$result = mysql_query($SQL1,$diCon) or header('location:errorPage.php&code=');
		
		mysql_close($diCon);
		//Log fils
		$log = logFile($_SESSION['GL_USER']['LOGIN'],date("Y-m-d H:i:s"),"Ajout d'une unite: $xnomUnite par ".$_SESSION['GL_USER']['LOGIN']);
		
		header('location:unites.php?selectedTab=pareters&error=0');
		break;

	case 'ETAPE3':  //Edit data
		$table1 = "stocks_unite";
		
		//Collect Data
		(isset($_POST['nomUnite']) 		? $xnomUnite 		= $_POST['nomUnite'] 		: $xnomUnite		= '');
		(isset($_POST['abreviation']) 	? $xabreviation		= $_POST['abreviation'] 	: $xabreviation 	= '');
		$_SESSION['DATA_EDIT']=array(
		'oldid' =>$_SESSION['DATA_EDIT']['oldid'],
		'nomUnite' =>$xnomUnite,
		'abreviation' =>$xabreviation
		);
		
		//Save data
		$SQL1 ="UPDATE $table1 SET `LIB_COURT`='".addslashes($xabreviation)."' ,`LIBELLE_UNITE`='".addslashes($xnomUnite)."' WHERE ID_UNITE=".$_SESSION['DATA_EDIT']['oldid'];
		
		//Connection to Database server
		$diCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
		
		//Select Database
		mysql_select_db(DB,$diCon) or header('location:errorPage.php&code=');
		$result = mysql_query($SQL1,$diCon) or header('location:errorPage.php&code=');
		mysql_close($diCon);
		//Log fils
		$log = logFile($_SESSION['GL_USER']['LOGIN'],date("Y-m-d H:i:s"),"Modification d'une unite: $xnomUnite par ".$_SESSION['GL_USER']['LOGIN']);
		
		header('location:unites.php?selectedTab=pareters&error=0');
		break;
		
	case 'EDIT':  //Fill Edit array
		$table1 = "stocks_unite";
		(isset($_POST['rowSelection']) ? $id = $_POST['rowSelection'][0] : $id = array());

		//Connection to Database server
		$diCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
		
		//Select Database
		mysql_select_db(DB,$diCon) or header('location:errorPage.php&code=');
		
		//Data
		$SQL1 ="SELECT * FROM $table1 WHERE ID_UNITE='$id'"; 
		$result = mysql_query($SQL1,$diCon) or header('location:errorPage.php&code=');
		$row = mysql_fetch_array($result);
		
		$_SESSION['DATA_EDIT']=array(
		'oldid' =>$id,
		'codeUnite' =>$row['ID_UNITE'],
		'nomUnite' =>stripslashes($row['LIBELLE_UNITE']),
		'abreviation' =>stripslashes($row['LIB_COURT'])
		);
		mysql_close($diCon);
		header('location:editunites.php?selectedTab=pareters&id='.$id);
		break;

	case 'DEL':  //Delete Data
		$table1 = "stocks_unite";
		(isset($_POST['rowSelection']) ? $id = $_POST['rowSelection'] : $id = array());
		
		//Connection to Database server
		$diCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
		
		//Select Database
		mysql_select_db(DB,$diCon) or header('location:errorPage.php&code=');
		
		//Save data
		foreach($id as $key=>$val){
			$SQL1 ="DELETE FROM $table1 WHERE ID_UNITE=$val;";
			$result = mysql_query($SQL1,$diCon) or header('location:errorPage.php&code=');
			//Log fils
			$log = logFile($_SESSION['GL_USER']['LOGIN'],date("Y-m-d H:i:s"),"Suppression d'une unite ID:$val par ".$_SESSION['GL_USER']['LOGIN']);
		}
		
		mysql_close($diCon);		
		header('location:unites.php?selectedTab=pareters');
		break;
	
	default:
	//echo 'Fonctionnement incorrect...';
}

(isset($_GET['test']) ? $test = $_GET['test'] : $test ='');
switch($test){
	case 'UNITE':
		$table1 = "stocks_exercice";
		$msg = "";
		if(isset($_POST["codeExercice"])){		
			if(!is_numeric($_POST["codeExercice"])) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Veuillez entrer un entier s\'il vous pla&icirc;t.';} 
			else{
				//Connection to Database server
				$diCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
				//Select Database
				mysql_select_db(DB,$diCon) or header('location:errorPage.php&code=');
				//SQL
				$SQL = "SELECT COUNT(ID_EXERCICE) FROM $table1 WHERE `ID_EXERCICE` =".$_POST["codeExercice"];
				$result = mysql_query($SQL,$diCon) or header('location:errorPage.php&code=');
				$row = mysql_fetch_array($result);
				if($row[0]>0) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Ce code existe d&eacute;j&agrave;, veuillez entrer un autre code exercice.';}
			}
		}	
		echo $msg;	
		break;

	
	default:
	//echo 'Fonctionnement incorrect...';
}
?>
