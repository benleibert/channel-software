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

	case 'DEL':
		$table1 = "stocks_logs";
		(isset($_POST['rowSelection']) ? $id = $_POST['rowSelection'] : $id = array());
		
		//Connection to Database server
		$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
		
		//Select Database
		mysql_select_db(DB,$idCon ) or header('location:errorPage.php&code=');
		
		//Save data
		foreach($id as $key=>$val){
			$SQL1 ="DELETE FROM $table1 WHERE ID_LOG=$val;";
			$result = mysql_query($SQL1,$idCon ) or header('location:errorPage.php&code=');
		}
		
		mysql_close($idCon );
		header('location:logs.php?selectedTab=pareters');
		break;

	default:
	//echo 'Fonctionnement incorrect...';
}

?>
