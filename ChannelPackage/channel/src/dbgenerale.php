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
(isset($_GET['do']) && $_GET['do']!='' ? $do = $_GET['do'] : $do = '');
(isset($_POST['myaction']) && $_POST['myaction']!='' ? $myaction = $_POST['myaction'] : $myaction = '');

if($myaction =='' && $do !=''){
switch($do){
	//Log in User

	case 'add':
		(isset($_POST['oldcodeinfogle']) && $_POST['oldcodeinfogle']!=''? $oldcodeinfogle 	= trim($_POST['oldcodeinfogle']) 	: $oldcodeinfogle = '');
		(isset($_POST['id']) && $_POST['id']!=''						? $id 				= trim($_POST['id']) 	: $id = '');
		(isset($_POST['client']) && $_POST['client']!=''  				? $client 			= trim($_POST['client']) 			: $client = '');
		(isset($_POST['dateacq']) && $_POST['dateacq']!=''  			? $dateacq 			= trim($_POST['dateacq']) 		: $dateacq = '');
		(isset($_POST['licence']) && $_POST['licence']!=''  			? $licence 			= trim($_POST['licence'])	: $licence = '');
		(isset($_POST['ministere']) && $_POST['ministere']!=''  		? $ministere 		= trim($_POST['ministere']) 				: $ministere = '');
		(isset($_POST['secretariat']) && $_POST['secretariat']!=''  	? $secretariat 		= trim($_POST['secretariat']) 		: $secretariat = '');
		(isset($_POST['direction']) && $_POST['direction']!=''  		? $direction 		= trim($_POST['direction']) 		: $direction = '');
		(isset($_POST['service']) && $_POST['service']!='' 				? $service 		= trim($_POST['service']) 		: $service = '');
		(isset($_POST['csps']) && $_POST['csps']!='' 					? $csps 			= trim($_POST['csps']) 			: $csps 			= '');
		(isset($_POST['pays']) && $_POST['pays']!=''  					? $pays 		= trim($_POST['pays']) 		: $pays = '');
		(isset($_POST['ville']) && $_POST['ville']!=''  				? $ville 	= trim($_POST['ville']) 		: $ville = '');
		(isset($_POST['devise']) && $_POST['devise']!=''  				? $devise 	= trim($_POST['devise']) 		: $devise = '');

		(isset($_POST['signateur1']) && $_POST['signateur1']!=''  		? $signateur1 	 = trim($_POST['signateur1']) 	 : $signateur1 = '');
		(isset($_POST['nomsignateur1']) && $_POST['nomsignateur1']!=''  ? $nomsignateur1 = trim($_POST['nomsignateur1']) : $nomsignateur1 = '');
		(isset($_POST['signateur2']) && $_POST['signateur2']!=''  		? $signateur2 	 = trim($_POST['signateur2']) 	 : $signateur2 = '');
		(isset($_POST['nomsignateur2']) && $_POST['nomsignateur2']!=''  ? $nomsignateur2 = trim($_POST['nomsignateur2']) : $nomsignateur2 = '');
		(isset($_POST['signateur3']) && $_POST['signateur3']!=''  		? $signateur3    = trim($_POST['signateur3']) 	 : $signateur3 = '');
		(isset($_POST['nomsignateur3']) && $_POST['nomsignateur3']!=''  ? $nomsignateur3 = trim($_POST['nomsignateur3']) : $nomsignateur3 = '');
		(isset($_POST['signateur4']) && $_POST['signateur4']!=''  		? $signateur4    = trim($_POST['signateur4']) 	 : $signateur4 = '');
		(isset($_POST['nomsignateur4']) && $_POST['nomsignateur4']!=''  ? $nomsignateur4 = trim($_POST['nomsignateur4']) : $nomsignateur4 = '');
		(isset($_POST['validauto']) && $_POST['validauto']!=''  		? $validauto 	 = trim($_POST['validauto']) 	 : $validauto = '');
		(isset($_POST['magasin']) && $_POST['magasin']!='0'  			? $magasin 	 	= trim($_POST['magasin']) 	 : $magasin = '');

		$dateacq = mysqlFormat($dateacq);
		$magasin=$_SESSION['GL_USER']['MAGASIN'];

		$extensions_valides = array("image/jpg"=> 'jpg' , "image/gif"=>'gif' , "image/png"=>'png',"image/x-png"=>'png');
		$flogo ='';


		if(isset($oldcodeinfogle) && $oldcodeinfogle!=''){

			if ($_FILES["logo"]["error"] > 0) {
				//echo "Error: " . $_FILES["logo"]["error"] . "<br>";
			}
			else {
				$flogo = '';
				if (isset($_FILES["logo"]["name"]) &&  ( ($_FILES["logo"]["type"] == "image/gif") ||  ($_FILES["logo"]["type"] == "image/jpg") || ($_FILES["logo"]["type"] == "image/x-png") 	|| ($_FILES["logo"]["type"] == "image/png")) && ($_FILES["logo"]["size"] < 50000)) {
					$flogo = "logo_".trim($id).'.'.$extensions_valides[$_FILES["logo"]["type"]];
					move_uploaded_file($_FILES["logo"]["tmp_name"],	"../upload/$flogo");
				}
			 $sql  = "UPDATE  `infogenerale`  SET CODE_MAGASIN ='".addslashes($magasin)."', `INF_CLIENT`='".addslashes($client)."', `INF_DATEACQ`='".addslashes($dateacq)."', `INF_LICENCE`='".addslashes($licence)."', `INF_MINISTERE`='".addslashes($ministere)."',
			 `INF_SECRETARIAT`='".addslashes($secretariat)."', `LOGO`='".addslashes($flogo)."', `INF_DIRECTION`='".addslashes($direction)."', `INF_SERVICE`='".addslashes($service)."', `INF_CSPS`='".addslashes($csps)."',`INF_PAYS`='".addslashes($pays)."', `INF_DEVISE`='".addslashes($devise)."',
			 `INF_VILLE`='".addslashes($ville)."', `INF_SIGNATEUR1`='".addslashes($signateur1)."', `INF_NOMSIGNATEUR1`='".addslashes($nomsignateur1)."', `INF_SIGNATEUR2`='".addslashes($signateur2)."', `INF_NOMSIGNATEUR2`='".addslashes($nomsignateur2)."',
			 `INF_SIGNATEUR3`='".addslashes($signateur3)."', `INF_NOMSIGNATEUR3`='".addslashes($nomsignateur3)."',`INF_SIGNATEUR4`='".addslashes($signateur4)."', `INF_NOMSIGNATEUR4`='".addslashes($nomsignateur4)."',
			 `INF_VALIDAUTO`='".addslashes($validauto)."', `INF_MAGASIN`='".addslashes($magasin)."' WHERE CODE_INFGLE LIKE '$oldcodeinfogle' ;";
			}
		}
		else{
			$id = myDbLastId('infogenerale', 'ID', $magasin)+1;

			if ($_FILES["logo"]["error"] > 0) {
				//echo "Error: " . $_FILES["logo"]["error"] . "<br>";
			} else {
				if (isset($_FILES["logo"]["name"]) &&  ( ($_FILES["logo"]["type"] == "image/gif") ||  ($_FILES["logo"]["type"] == "image/jpg") || ($_FILES["logo"]["type"] == "image/x-png") 	|| ($_FILES["logo"]["type"] == "image/png")) && ($_FILES["logo"]["size"] < 50000)) {
					$logo = "logo_".trim($id).'.'.$extensions_valides[$_FILES["logo"]["type"]];
					move_uploaded_file($_FILES["logo"]["tmp_name"],	"../upload/$logo");
				}
			}
			//Insert
			$sql  = "INSERT INTO `infogenerale` (CODE_INFGLE, CODE_MAGASIN, ID, `INF_CLIENT`, `INF_DATEACQ`, `INF_LICENCE`, `INF_MINISTERE`, `INF_SECRETARIAT`, `INF_DIRECTION`, `INF_SERVICE`,`INF_CSPS`, `INF_PAYS`, `INF_DEVISE`, `INF_VILLE`, `LOGO`,
			`INF_SIGNATEUR1`, `INF_NOMSIGNATEUR1`, `INF_SIGNATEUR2`, `INF_NOMSIGNATEUR2`, `INF_SIGNATEUR3`, `INF_NOMSIGNATEUR3`,`INF_SIGNATEUR4`, `INF_NOMSIGNATEUR4`, `INF_VALIDAUTO`, `INF_MAGASIN`)
			VALUES ('".addslashes("$id/$magasin")."', '".addslashes($magasin)."',  '".addslashes($id)."', '".addslashes($client)."', '".addslashes($dateacq)."', '".addslashes($licence)."' , '".addslashes($ministere)."' ,'".addslashes($secretariat)."' ,
			'".addslashes($direction)."','".addslashes($service)."','".addslashes($csps)."','".addslashes($pays)."','".addslashes($devise)."', '".addslashes($ville)."',
			'".addslashes($logo)."', '".addslashes($signateur1)."', '".addslashes($nomsignateur1)."', '".addslashes($signateur2)."', '".addslashes($nomsignateur2)."',
			'".addslashes($signateur3)."','".addslashes($nomsignateur3)."','".addslashes($signateur4)."','".addslashes($nomsignateur4)."','".addslashes($validauto)."','".addslashes($magasin)."');";
		}

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//echo  $sql;
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout ou modification des paramètres généraux'); //updateLog($username, $idcust, $action='' )
		header('location:generale.php?selectedTab=par');
		break;

	default : ///Nothing
}
}//Fin if

elseif($myaction !='')
//myaction
switch($myaction){

	default : ///Nothing
		//header('location:../index.php');

}

elseif($myaction =='' && $do ='') header('location:../index.php');

?>
