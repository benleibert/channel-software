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
(isset($_GET['do']) || $_GET['do']!='' ? $do = $_GET['do'] : $do = '');

switch($do){
	//Log in User
	case 'login':
		(isset($_POST['userName']) && $_POST['userName']!='' ? $login = trim($_POST['userName']) : $login = '');
		(isset($_POST['pword']) && $_POST['pword']	? $pwd	 = trim($_POST['pword']) 	: $pwd 		= '');

		//SQL
		$sql = "SELECT * FROM `customer` WHERE `CUST_EMAIL` LIKE '".addslashes($login)."' AND `CUST_PASSWD` LIKE MD5('".addslashes($pwd)."') AND `CUST_ACTIVATED` =1";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		$_SESSION['GL_USER'] = array(); //Session Variable for User
		if($query->rowCount()) {
			$row = $query->fetch(PDO::FETCH_ASSOC); //Fetch data
			//Session
			$_SESSION['GL_USER']['NAME'] = $row['CUST_FULL_NAME'];
			$_SESSION['GL_USER']['LANG'] = $row['CODE_LANG'];
			$_SESSION['GL_USER']['LOGIN'] = $row['CUST_EMAIL'];
			$_SESSION['GL_USER']['IDCUST'] = $row['ID_CUSTOMER'];
			$_SESSION['GL_USER']['DTLOG'] = date('d-m-Y H:i:s');
			$_SESSION['GL_USER']['SESSIONID'] = session_id();

			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['IDCUST'], 'Connexion au système'); //updateLog($username, $idcust, $action='' )
			header('location:main.php?rs=welcome');
		}
		else {
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['IDCUST'], 'Tentative de connexion au système échouée'); //updateLog($username, $idcust, $action='' )
			header('location:login.php?rs=unknown');
		}
		break;

	//Log out user
	case 'logout':
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['IDCUST'], 'Déconnexion du système échouée'); //updateLog($username, $idcust, $action='' )
		header('location:login.php');
		break;

	default : ///Nothing
		header('location:login.php');

}

//This function updates the log table
function updateLog($username, $idcust, $action='' ){
	$sql = "INSERT INTO `logs` (`ID_CUSTOMER` ,`LOG_DATE` ,`LOG_NAME` ,`LOG_DESCRIP` ) ";
	$sql .= "VALUES ( '".addslashes($idcust)."', '".date("Y-m-d H:i:s")."',  '".addslashes($username)."', '".addslashes($action)."') ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query
}


/*
switch($myaction){

	case 'ETAPE2':
		$table1 = "stocks_compte";
		$table2 = "stocks_groupe";
		$table3 = "stocks_personnel";
		$table4 = "stocks_exercice";

		//Collect Data
		(isset($_POST['userName']) 		? $xuserName	= $_POST['userName'] 	: $xuserName	= '');
		(isset($_POST['pword']) 		? $xpword 		= $_POST['pword'] 		: $xpword 		= '');
		$_SESSION['GL_USER']=array();

		//Save data
		$SQL1 ="SELECT $table1.*, $table2.*, $table3.NOM_PRENOMS FROM $table1, $table2, $table3
		WHERE LOGIN LIKE '$xuserName' AND MOTPASSE LIKE md5('$xpword')
		AND $table1.ID_GROUPE = $table2.ID_GROUPE AND $table1.NUM_MATRICULE=$table3.NUM_MATRICULE";

		//Connection to Database server
		mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

		//Select Database
		mysql_select_db(DB) or header('location:errorPage.php&code=');
		$result = mysql_query($SQL1) or header('location:errorPage.php&code=');
		if(mysql_num_rows($result)<=0){
			$log = logFile($xuserName,date("Y-m-d H:i:s"),"Tentative de connexion de $xuserName. Accès refusé, paramètres incorrects");
			header('location:index.php?error=1');
		}
		else {
			$row = mysql_fetch_array($result);
			if($row['STATUS']==0){
				$log = logFile($xuserName,date("Y-m-d H:i:s"),"Tentative de connexion de $xuserName. Accès refusé, compte déactivé");
				header('location:index.php?error=2');
			}
			elseif($row['GRPE_STATUS']==0){
				$log = logFile($xuserName,date("Y-m-d H:i:s"),"Tentative de connexion de $xuserName. Accès refusé, groupe déactivé");
				header('location:index.php?error=3');
			}
			else {
				$xB = preg_split('/ /',$row['BESOINS']);
				$xAO = preg_split('/ /',$row['APPELOFFRE']);
				$xBE= preg_split('/ /',$row['BONENTREE']);
				$xBS = preg_split('/ /',$row['BONSORTIE']);
				$xP = preg_split('/ /',$row['PARAMETRE']);
				$xI = preg_split('/ /',$row['INVENTAIRE']);

				$droit = array(
				'B' =>array('Ajout'=>$xB[0],'Modif'=>$xB[1],'Suppr'=>$xB[2],'Consolid'=>$xB[3]),
				'AO'=>array('Ajout'=>$xAO[0],'Modif'=>$xAO[1],'Suppr'=>$xAO[2],'Validatit'=>$xAO[3]),
				'BE'=>array('Ajout'=>$xBE[0],'Modif'=>$xBE[1],'Suppr'=>$xBE[2],'Validatit'=>$xBE[3]),
				'BS'=>array('Ajout'=>$xBS[0],'Modif'=>$xBS[1],'Suppr'=>$xBS[2],'Validatit'=>$xBS[3]),
				'P'=>array('Ajout'=>$xP[0],'Modif'=>$xP[1],'Suppr'=>$xP[2]),
				'I'=>array('Ajout'=>$xI[0],'Modif'=>$xI[1],'Suppr'=>$xI[2],'Validatit'=>$xI[3])
				);

				//Le dernier exercice
				$SQLE ="SELECT STATUS, ID_EXERCICE FROM $table4 ORDER BY ID_EXERCICE DESC;";
				$resultE = mysql_query($SQLE) or header('location:errorPage.php&code=');
				$rowE = mysql_fetch_array($resultE);

				$_SESSION['GL_USER']=array(
				'SESSIONID'=>session_id(),
				'EXERCICE'=>$rowE['ID_EXERCICE'],
				'STATUT_EXERCICE'=>$rowE['STATUS'],
				'NOM'=> (stripslashes($row['NOM_PRENOMS'])),
				'MATRICULE'=>stripslashes($row['NUM_MATRICULE']),
				'LOGIN'=>stripslashes($row['LOGIN']),
				'STATUS'=>$row['STATUS'],
				'GRPE_STATUS'=>$row['GRPE_STATUS'],
				'DROIT'=>$droit,
				'ELEMENT'=>20
				);
				//Log fils
				$log = logFile($xuserName,date("Y-m-d H:i:s"),"Connexion de $xuserName");

				header('location:home.php?selectedTab=home&error=0');
			}
		}
		break;

	case 'LOGOUT':
		//Log fils
		$log = logFile($_SESSION['GL_USER']['LOGIN'],date("Y-m-d H:i:s"),"Déconnexion de ".$_SESSION['GL_USER']['LOGIN']);
		session_unset();
		header('location:../');

		break;

	case 'CHANGE':
		$table4 = "stocks_exercice";

		//Connection to Database server
		mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

		//Select Database
		mysql_select_db(DB) or header('location:errorPage.php&code=');
		//Collect Data
		(isset($_POST['exercice']) && $_POST['exercice'] !="00"	? $exercice	= $_POST['exercice'] 	: $exercice	= '');
		$SQLE ="SELECT STATUS, ID_EXERCICE FROM $table4 WHERE ID_EXERCICE=$exercice;";
		$resultE = mysql_query($SQLE) or header('location:errorPage.php&code=');
		$rowE = mysql_fetch_array($resultE);

		$oldExer = $_SESSION['GL_USER']['EXERCICE'];
		$_SESSION['GL_USER']['EXERCICE']= $rowE['ID_EXERCICE'];
		$_SESSION['GL_USER']['STATUT_EXERCICE']= $rowE['STATUS'];

		//Log fils
		$log = logFile($_SESSION['GL_USER']['LOGIN'],date("Y-m-d H:i:s"),"Changement d'exercice budgétaire $oldExer ->$exercice par ".$_SESSION['GL_USER']['LOGIN']);
		header('location:home.php?selectedTab=home');

		break;

	default:
		header('location:../');
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
				mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');
				//Select Database
				mysql_select_db(DB) or header('location:errorPage.php&code=');
				//SQL
				$SQL = "SELECT COUNT(ID_REGION) FROM $table1 WHERE `ID_REGION` =".$_POST["codeRegion"];
				$result = mysql_query($SQL) or header('location:errorPage.php&code=');
				$row = mysql_fetch_array($result);
				if($row[0]>0) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Ce code existe d&eacute;j&agrave;, veuillez entrer un autre code r&eacute;gion.';}
			}
		}
		echo $msg;
		break;

	default:
	//echo 'Fonctionnement incorrect...';
}
*/
?>
