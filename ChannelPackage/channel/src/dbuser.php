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
//require_once('../lib/global.inc');
//PHP functions librairy
require_once('../lib/phpfuncLib.php');

//Action to do
//This variable $act say what to do (add, delete, ...)
(isset($_GET['do']) && $_GET['do']!='' ? $do = $_GET['do'] : $do = '');

switch($do){
	//Log in User
	case 'login':
	
		(isset($_POST['userName']) && $_POST['userName']!='' ? $login = trim($_POST['userName']) : $login = '22');
		(isset($_POST['pword']) && $_POST['pword']	? $pwd	 = trim($_POST['pword']) 	: $pwd 		= '');
		//SQL
		$sql  = "SELECT * FROM `compte` INNER JOIN `personnel` ON (`compte`.NUM_MLLE LIKE `personnel`.NUM_MLLE)
		WHERE `LOGIN` LIKE '".addslashes($login)."' AND `PWD` LIKE MD5('".addslashes($pwd)."') AND `ACTIVATED` =1";


		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			//header('location:errorPage.php');
			die($error->getMessage());
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		$_SESSION['GL_USER'] = array(); //Session Variable for User
		if($query->rowCount()) {
			$row = $query->fetch(PDO::FETCH_ASSOC); //Fetch data
			$exercice = getLastExercice();
			$droit = getUserRights($row['IDPROFIL']);

			//Session
			$_SESSION['GL_USER']['NAME'] = trim($row['PERS_PRENOMS'].' '.$row['PERS_NOM']);
			$_SESSION['GL_USER']['LOGIN'] = $row['LOGIN'];
			$_SESSION['GL_USER']['MLLE'] = $row['NUM_MLLE'];
			$_SESSION['GL_USER']['DTLOG'] = date('d-m-Y H:i:s');
			$_SESSION['GL_USER']['EXERCICE']= $exercice['EXERCICE'];
			$_SESSION['GL_USER']['DEBUT_EXERCICE']= frFormat2($exercice['DEBUT_EXERCICE']);
			$_SESSION['GL_USER']['FIN_EXERCICE']= frFormat2($exercice['FIN_EXERCICE']);
			$_SESSION['GL_USER']['EX_LIBELLE']= $exercice['EX_LIBELLE'];
			$_SESSION['GL_USER']['STATUT_EXERCICE']=$exercice['STATUT_EXERCICE'];
			$_SESSION['GL_USER']['GROUPE'] = $row['IDPROFIL'];
			$_SESSION['GL_USER']['SESSIONID'] = session_id();
			$_SESSION['GL_USER']['JOUR']='';

			//$mag = preg_split('/ /',getUsermagasin($row['LOGIN']));
			$_SESSION['GL_USER']['MAGASIN'] = ''; //$mag[0];
			$_SESSION['GL_USER']['PROVINCE'] = '';
			$_SESSION['GL_USER']['ELEMENT'] =DEFAULTVIEWLENGTH;

			//Droits
			$_SESSION['GL_USER']['DROIT'] = $droit;

			updateLog($_SESSION['GL_USER']['MAGASIN'],$_SESSION['GL_USER']['LOGIN'], $_SESSION['GL_USER']['MLLE'], 'Connexion au système'); //updateLog($username, $idcust, $action='' )
			header('location:home.php?selectedTab=home');
		}
		else {
			updateLog($login, '', 'Tentative de connexion au système échouée'); //updateLog($username, $idcust, $action='' )
			header('location:../index.php?rs=1');
		}
		break;

	//Log out user

	case 'logout':
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'], $_SESSION['GL_USER']['MLLE'], 'Déconnexion du système échouée'); //updateLog($username, $idcust, $action='' )
		unset($_SESSION['GL_USER']);
		header('location:../index.php');
		break;

	case 'close':

		//Follow the log
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'], $_SESSION['GL_USER']['MLLE'], 'Fermeture de la session inactive de '.$_SESSION['ADMIN']['NAME']); //updateLog($username, $idcust, $action='' )
		//Destroy Session vars
		unset($_SESSION['GL_USER']);
		//Go to login page
		header('location:../index.php');

		break;

	case 'add':
		(isset($_POST['personnel']) && $_POST['personnel']!='0' ? $personnel = trim($_POST['personnel']) 	: $personnel = '');
		(isset($_POST['groupe']) && $_POST['groupe']!='0' 		? $groupe 	= trim($_POST['groupe']) 		: $groupe = '');
		(isset($_POST['compte']) && $_POST['compte']!='' 		? $compte 	= trim($_POST['compte']) 		: $compte = '');
		(isset($_POST['motpasse1']) && $_POST['motpasse1']!='' 	? $motpasse = trim($_POST['motpasse1']) 	: $motpasse = '');
		(isset($_POST['langue']) && $_POST['langue']!='0' 	? $langue 	= trim($_POST['langue']) 	: $langue = '');
		(isset($_POST['statut']) && $_POST['statut']!='' 		? $statut = trim($_POST['statut']) 			: $statut = '');
		(isset($_POST['mag1']) && $_POST['mag1']!='0' 			? $mag1 	= trim($_POST['mag1']) 			: $mag1 = '');
		(isset($_POST['mag2']) && $_POST['mag2']!='0' 			? $mag2 	= trim($_POST['mag2']) 			: $mag2 = '');
		(isset($_POST['mag3']) && $_POST['mag3']!='0' 			? $mag3 	= trim($_POST['mag3']) 			: $mag3 = '');
		(isset($_POST['mag4']) && $_POST['mag4']!='0' 			? $mag4 	= trim($_POST['mag4']) 			: $mag4 = '');
		(isset($_POST['mag5']) && $_POST['mag5']!='0' 			? $mag5 	= trim($_POST['mag5']) 			: $mag5 = '');
		(isset($_POST['mag6']) && $_POST['mag6']!='0' 			? $mag6 	= trim($_POST['mag6']) 			: $mag6 = '');
		(isset($_POST['mag7']) && $_POST['mag7']!='0' 			? $mag7 	= trim($_POST['mag7']) 			: $mag7 = '');
		(isset($_POST['mag8']) && $_POST['mag8']!='0' 			? $mag8 	= trim($_POST['mag8']) 			: $mag8 = '');

		//($codebeneficiaire == '' ? $codebeneficiaire)
		//SQL
		$sql  = "INSERT INTO `compte` (`LOGIN` ,`NUM_MLLE` ,`IDPROFIL` ,`PWD` ,`idlangue`,`ACTIVATED`) VALUES (";
		$sql .= "'".addslashes($compte)."' , '".addslashes($personnel)."' , '".addslashes($groupe)."', MD5('".addslashes($motpasse)."'), '".addslashes($langue)."', '".addslashes($statut)."');";

		if($mag1!='') { $sql .= "INSERT INTO `mag_compte` (`LOGIN` ,`CODE_MAGASIN`) VALUES ('".addslashes($compte)."','".addslashes($mag1)."');" ;}
		if($mag2!='') { $sql .= "INSERT INTO `mag_compte` (`LOGIN` ,`CODE_MAGASIN`) VALUES ('".addslashes($compte)."','".addslashes($mag2)."');" ;}
		if($mag3!='') { $sql .= "INSERT INTO `mag_compte` (`LOGIN` ,`CODE_MAGASIN`) VALUES ('".addslashes($compte)."','".addslashes($mag3)."');" ;}
		if($mag4!='') { $sql .= "INSERT INTO `mag_compte` (`LOGIN` ,`CODE_MAGASIN`) VALUES ('".addslashes($compte)."','".addslashes($mag4)."');" ;}
		if($mag5!='') { $sql .= "INSERT INTO `mag_compte` (`LOGIN` ,`CODE_MAGASIN`) VALUES ('".addslashes($compte)."','".addslashes($mag5)."');" ;}
		if($mag6!='') { $sql .= "INSERT INTO `mag_compte` (`LOGIN` ,`CODE_MAGASIN`) VALUES ('".addslashes($compte)."','".addslashes($mag6)."');" ;}
		if($mag7!='') { $sql .= "INSERT INTO `mag_compte` (`LOGIN` ,`CODE_MAGASIN`) VALUES ('".addslashes($compte)."','".addslashes($mag7)."');" ;}
		if($mag8!='') { $sql .= "INSERT INTO `mag_compte` (`LOGIN` ,`CODE_MAGASIN`) VALUES ('".addslashes($compte)."','".addslashes($mag8)."');" ;}

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			die($error->getMessage().' '.__LINE__);
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'], $_SESSION['GL_USER']['MLLE'], 'Ajout d\'un compte utilisateur ('.$compte.', '.$personnel.')'); //updateLog($username, $idcust, $action='' )
		header('location:user.php?selectedTab=par&rs=1');
		break;

	//UPDATE BENEFICIAIRE

	case 'update':
		(isset($_POST['personnel']) && $_POST['personnel']!='0' ? $personnel 	= trim($_POST['personnel']) 	: $personnel = '');
		(isset($_POST['groupe']) && $_POST['groupe']!='0' 		? $groupe 		= trim($_POST['groupe']) 		: $groupe = '');
		(isset($_POST['compte']) && $_POST['compte']!='' 		? $compte 		= trim($_POST['compte']) 		: $compte = '');
		(isset($_POST['motpasse1']) && $_POST['motpasse1']!='' 	? $motpasse 	= trim($_POST['motpasse1']) 	: $motpasse = '');
		(isset($_POST['langue']) && $_POST['langue']!='0' 	? $langue 	= trim($_POST['langue']) 	: $langue = '');
		(isset($_POST['statut']) && $_POST['statut']!='' 		? $statut 		= trim($_POST['statut']) 		: $statut = '');
		(isset($_POST['oldcompte']) && $_POST['oldcompte']!='' 	? $oldcompte 	= trim($_POST['oldcompte']) 	: $oldcompte = '');
		(isset($_POST['oldnummlle']) && $_POST['oldnummlle']!='' 	? $oldnummlle 	= trim($_POST['oldnummlle']) 	: $oldnummlle = '');

		(isset($motpasse) && $motpasse!='' ? $pwd = "`PWD`=MD5('".addslashes($motpasse)."') ," : $pwd = '');

		(isset($_POST['mag1']) && $_POST['mag1']!='0' 			? $mag1 	= trim($_POST['mag1']) 		: $mag1 = '');
		(isset($_POST['mag2']) && $_POST['mag2']!='0' 			? $mag2 	= trim($_POST['mag2']) 		: $mag2 = '');
		(isset($_POST['mag3']) && $_POST['mag3']!='0' 			? $mag3 	= trim($_POST['mag3']) 		: $mag3 = '');
		(isset($_POST['mag4']) && $_POST['mag4']!='0' 			? $mag4 	= trim($_POST['mag4']) 		: $mag4 = '');
		(isset($_POST['mag5']) && $_POST['mag5']!='0' 			? $mag5 	= trim($_POST['mag5']) 		: $mag5 = '');
		(isset($_POST['mag6']) && $_POST['mag6']!='0' 			? $mag6 	= trim($_POST['mag6']) 		: $mag6 = '');
		(isset($_POST['mag7']) && $_POST['mag7']!='0' 			? $mag7 	= trim($_POST['mag7']) 		: $mag7 = '');
		(isset($_POST['mag8']) && $_POST['mag8']!='0' 			? $mag8 	= trim($_POST['mag8']) 		: $mag8 = '');

		(isset($_POST['oldmag1']) && $_POST['oldmag1']!='' 		? $oldmag1 	= trim($_POST['oldmag1']) 		: $oldmag1 = '');
		(isset($_POST['oldmag2']) && $_POST['oldmag2']!='' 		? $oldmag2 	= trim($_POST['oldmag2']) 		: $oldmag2 = '');
		(isset($_POST['oldmag3']) && $_POST['oldmag3']!='' 		? $oldmag3 	= trim($_POST['oldmag3']) 		: $oldmag3 = '');
		(isset($_POST['oldmag4']) && $_POST['oldmag4']!='' 		? $oldmag4 	= trim($_POST['oldmag4']) 		: $oldmag4 = '');
		(isset($_POST['oldmag5']) && $_POST['oldmag5']!='' 		? $oldmag5 	= trim($_POST['oldmag5']) 		: $oldmag5 = '');
		(isset($_POST['oldmag6']) && $_POST['oldmag6']!='' 		? $oldmag6 	= trim($_POST['oldmag6']) 		: $oldmag6 = '');
		(isset($_POST['oldmag7']) && $_POST['oldmag7']!='' 		? $oldmag7 	= trim($_POST['oldmag7']) 		: $oldmag7 = '');
		(isset($_POST['oldmag8']) && $_POST['oldmag8']!='' 		? $oldmag8 	= trim($_POST['oldmag8']) 		: $oldmag8 = '');

		//($codebeneficiaire == '' ? $codebeneficiaire)


		//($codebeneficiaire == '' ? $codebeneficiaire)
		//SQL
		$sql  = "UPDATE `compte` SET `LOGIN`='".addslashes($compte)."' ,`NUM_MLLE`='".addslashes($personnel)."' ,`IDPROFIL`='".addslashes($groupe)."' ,
		 $pwd `ACTIVATED`='".addslashes($statut)."',`idlangue`='".addslashes($langue)."'  WHERE `LOGIN` LIKE '".addslashes($oldcompte)."';";

		//INSERT
		if($oldmag1=='' && $mag1!='') { $sql .= "INSERT INTO `mag_compte` (`LOGIN` ,`CODE_MAGASIN`) VALUES ('".addslashes($compte)."','".addslashes($mag1)."');" ;}
		if($oldmag2=='' && $mag2!='') { $sql .= "INSERT INTO `mag_compte` (`LOGIN` ,`CODE_MAGASIN`) VALUES ('".addslashes($compte)."','".addslashes($mag2)."');" ;}
		if($oldmag3=='' && $mag3!='') { $sql .= "INSERT INTO `mag_compte` (`LOGIN` ,`CODE_MAGASIN`) VALUES ('".addslashes($compte)."','".addslashes($mag3)."');" ;}
		if($oldmag4=='' && $mag4!='') { $sql .= "INSERT INTO `mag_compte` (`LOGIN` ,`CODE_MAGASIN`) VALUES ('".addslashes($compte)."','".addslashes($mag4)."');" ;}
		if($oldmag5=='' && $mag5!='') { $sql .= "INSERT INTO `mag_compte` (`LOGIN` ,`CODE_MAGASIN`) VALUES ('".addslashes($compte)."','".addslashes($mag5)."');" ;}
		if($oldmag6=='' && $mag6!='') { $sql .= "INSERT INTO `mag_compte` (`LOGIN` ,`CODE_MAGASIN`) VALUES ('".addslashes($compte)."','".addslashes($mag6)."');" ;}
		if($oldmag7=='' && $mag7!='') { $sql .= "INSERT INTO `mag_compte` (`LOGIN` ,`CODE_MAGASIN`) VALUES ('".addslashes($compte)."','".addslashes($mag7)."');" ;}
		if($oldmag8=='' && $mag8!='') { $sql .= "INSERT INTO `mag_compte` (`LOGIN` ,`CODE_MAGASIN`) VALUES ('".addslashes($compte)."','".addslashes($mag8)."');" ;}

		//UPDATE
		if($oldmag1!='' && $mag1!=''  && $mag1!=$oldmag1) { $sql .= "UPDATE `mag_compte` SET `LOGIN`='".addslashes($compte)."' ,`CODE_MAGASIN`='".addslashes($mag1)."' WHERE CODE_MAGASIN LIKE '".addslashes($oldmag1)."' AND `LOGIN` LIKE '".addslashes($oldcompte)."';" ;}
		if($oldmag2!='' && $mag2!=''  && $mag2!=$oldmag2) { $sql .= "UPDATE `mag_compte` SET `LOGIN`='".addslashes($compte)."' ,`CODE_MAGASIN`='".addslashes($mag2)."' WHERE CODE_MAGASIN LIKE '".addslashes($oldmag2)."' AND `LOGIN` LIKE '".addslashes($oldcompte)."';" ;}
		if($oldmag3!='' && $mag3!=''  && $mag3!=$oldmag3) { $sql .= "UPDATE `mag_compte` SET `LOGIN`='".addslashes($compte)."' ,`CODE_MAGASIN`='".addslashes($mag3)."' WHERE CODE_MAGASIN LIKE '".addslashes($oldmag3)."' AND `LOGIN` LIKE '".addslashes($oldcompte)."';" ;}
		if($oldmag4!='' && $mag1!=''  && $mag1!=$oldmag4) { $sql .= "UPDATE `mag_compte` SET `LOGIN`='".addslashes($compte)."' ,`CODE_MAGASIN`='".addslashes($mag4)."' WHERE CODE_MAGASIN LIKE '".addslashes($oldmag4)."' AND `LOGIN` LIKE '".addslashes($oldcompte)."';" ;}
		if($oldmag5!='' && $mag2!=''  && $mag2!=$oldmag5) { $sql .= "UPDATE `mag_compte` SET `LOGIN`='".addslashes($compte)."' ,`CODE_MAGASIN`='".addslashes($mag5)."' WHERE CODE_MAGASIN LIKE '".addslashes($oldmag5)."' AND `LOGIN` LIKE '".addslashes($oldcompte)."';" ;}
		if($oldmag6!='' && $mag3!=''  && $mag3!=$oldmag6) { $sql .= "UPDATE `mag_compte` SET `LOGIN`='".addslashes($compte)."' ,`CODE_MAGASIN`='".addslashes($mag6)."' WHERE CODE_MAGASIN LIKE '".addslashes($oldmag6)."' AND `LOGIN` LIKE '".addslashes($oldcompte)."';" ;}
		if($oldmag7!='' && $mag3!=''  && $mag3!=$oldmag7) { $sql .= "UPDATE `mag_compte` SET `LOGIN`='".addslashes($compte)."' ,`CODE_MAGASIN`='".addslashes($mag7)."' WHERE CODE_MAGASIN LIKE '".addslashes($oldmag7)."' AND `LOGIN` LIKE '".addslashes($oldcompte)."';" ;}
		if($oldmag8!='' && $mag3!=''  && $mag3!=$oldmag8) { $sql .= "UPDATE `mag_compte` SET `LOGIN`='".addslashes($compte)."' ,`CODE_MAGASIN`='".addslashes($mag8)."' WHERE CODE_MAGASIN LIKE '".addslashes($oldmag8)."' AND `LOGIN` LIKE '".addslashes($oldcompte)."';" ;}

		//SUPPRESSION
		if($oldmag1!='' && $mag1=='') { $sql .= "DELETE FROM `mag_compte`  WHERE CODE_MAGASIN LIKE '".addslashes($oldmag1)."' AND `LOGIN` LIKE '".addslashes($oldcompte)."';" ;}
		if($oldmag2!='' && $mag2=='') { $sql .= "DELETE FROM `mag_compte`  WHERE CODE_MAGASIN LIKE '".addslashes($oldmag2)."' AND `LOGIN` LIKE '".addslashes($oldcompte)."';" ;}
		if($oldmag3!='' && $mag3=='') { $sql .= "DELETE FROM `mag_compte`  WHERE CODE_MAGASIN LIKE '".addslashes($oldmag3)."' AND `LOGIN` LIKE '".addslashes($oldcompte)."';" ;}
		if($oldmag4!='' && $mag4=='') { $sql .= "DELETE FROM `mag_compte`  WHERE CODE_MAGASIN LIKE '".addslashes($oldmag4)."' AND `LOGIN` LIKE '".addslashes($oldcompte)."';" ;}
		if($oldmag5!='' && $mag5=='') { $sql .= "DELETE FROM `mag_compte`  WHERE CODE_MAGASIN LIKE '".addslashes($oldmag5)."' AND `LOGIN` LIKE '".addslashes($oldcompte)."';" ;}
		if($oldmag6!='' && $mag6=='') { $sql .= "DELETE FROM `mag_compte`  WHERE CODE_MAGASIN LIKE '".addslashes($oldmag6)."' AND `LOGIN` LIKE '".addslashes($oldcompte)."';" ;}
		if($oldmag7!='' && $mag7=='') { $sql .= "DELETE FROM `mag_compte`  WHERE CODE_MAGASIN LIKE '".addslashes($oldmag7)."' AND `LOGIN` LIKE '".addslashes($oldcompte)."';" ;}
		if($oldmag8!='' && $mag8=='') { $sql .= "DELETE FROM `mag_compte`  WHERE CODE_MAGASIN LIKE '".addslashes($oldmag8)."' AND `LOGIN` LIKE '".addslashes($oldcompte)."';" ;}

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			die($error->getMessage().' '.__LINE__);
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'], $_SESSION['GL_USER']['MLLE'], 'Modification d\'un compte utilisateur ('.$oldcompte.', '.$personnel.')'); //updateLog($username, $idcust, $action='' )
		header('location:user.php?selectedTab=par&rs=2');
		break;

	case 'updatepwd':
		(isset($_POST['compte']) && $_POST['compte']!='' 		? $compte 		= trim($_POST['compte']) 		: $compte = '');
		(isset($_POST['oldmotpasse']) && $_POST['oldmotpasse']!='' 	? $oldmotpasse 	= trim($_POST['oldmotpasse']) 	: $oldmotpasse = '');
		(isset($_POST['motpasse1']) && $_POST['motpasse1']!='' 	? $motpasse 	= trim($_POST['motpasse1']) 	: $motpasse = '');

		(isset($motpasse) && $motpasse!='' ? $pwd = "`PWD`=MD5('".addslashes($motpasse)."') ," : $pwd = '');

		//SQL
		$sql  = "UPDATE `compte` SET  PWD=MD5('".addslashes($motpasse)."')
		WHERE `LOGIN` LIKE '".addslashes($compte)."' AND PWD=MD5('".addslashes($oldmotpasse)."');";


		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			die($error->getMessage().' '.__LINE__);
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'], $_SESSION['GL_USER']['MLLE'], 'Modification d\'un compte utilisateur ('.$oldcompte.', '.$personnel.')'); //updateLog($username, $idcust, $action='' )
		header('location:home.php?selectedTab=home');
		break;

	case 'check':

		$msg = "";
		(isset($_POST['compte']) && $_POST['compte']!='' ? $compte = trim($_POST['compte']) 	: $compte = '');

		if($compte !=''){
			$sql = "SELECT COUNT(LOGIN) AS NBRE FROM  `compte` WHERE `LOGIN` LIKE '".addslashes($compte)."'";
			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				die($error->getMessage().' '.__LINE__);
			}
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			$row = $query->fetch(PDO::FETCH_ASSOC);

			if($row['NBRE']>0) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Ce compte existe d&eacute;j&agrave;, veuillez entrer un autre compte utilisateur.';}
		}
		echo $msg;
		break;

	case 'change':
		(isset($_POST['exercice']) && $_POST['exercice']!='00' ? $exercice = trim($_POST['exercice']) 	: $exercice = '');
		(isset($_POST['province']) && $_POST['province']!='00' ? $province = trim($_POST['province']) 	: $province = '');
		(isset($_POST['cantine']) && $_POST['cantine']!='00' ? $cantine = trim($_POST['cantine']) 	: $cantine = '');

		if($exercice!='00'){
			$olexercice =$_SESSION['GL_USER']['EXERCICE'];

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				die($error->getMessage().' '.__LINE__);
			}
			$sql = "SELECT * FROM  `exercice` WHERE `ID_EXERCICE` = '".$exercice."'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			$row = $query->fetch(PDO::FETCH_ASSOC);

			$_SESSION['GL_USER']['EXERCICE']= $exercice;
			$_SESSION['GL_USER']['EX_LIBELLE']= $row['EX_LIBELLE'];
			$_SESSION['GL_USER']['STATUT_EXERCICE'] = $row['EX_CLOTURE'];
			$_SESSION['GL_USER']['DEBUT_EXERCICE']= frFormat2($row['EX_DATEDEBUT']);
			$_SESSION['GL_USER']['FIN_EXERCICE']= frFormat2($row['EX_DATEFIN']);
			$olcantine =$_SESSION['GL_USER']['MAGASIN'];
			$_SESSION['GL_USER']['PROVINCE']= $province;
			$_SESSION['GL_USER']['MAGASIN']= $cantine;

			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Changement de l\'exercice budgétaire ('.$olexercice.', '.$exercice.' / '.$olcantine.', '.$cantine.')'); //updateLog($username, $idcust, $action='' )
			header('location:home.php?selectedTab=home');
		}
		else{
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'], $_SESSION['GL_USER']['MLLE'], 'Changement de l\'exercice budgétaire a entrainé une déconnexion'); //updateLog($username, $idcust, $action='' )
			header('location:dbuser.php?do=logout');
		}
		break;

	case 'fillService':

		$list = '<option value="0"></option>';

		if(isset($_POST["province"]) && $_POST["province"]!='0'){
			$_SESSION['GL_USER']['PROVINCE']= $_POST["province"];
			//SQL
			$sql  = "SELECT * FROM magasin WHERE IDPROVINCE LIKE '".stripslashes($_POST["province"])."' ORDER BY magasin.SER_NOM ASC;";
			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				$list .= '<option value="'.$row['CODE_MAGASIN'].'" >'.stripslashes($row['SER_NOM']).'</option>';
			}
		}
		echo $list;
		break;


	case 'date':

		$msg = "";
		(isset($_POST['date']) && $_POST['date']!='' ? $date = trim($_POST['date']) 	: $date = '');

		if($date !=''){

			if($date < $_SESSION['GL_USER']['DEBUT_EXERCICE']  ||  $date > $_SESSION['GL_USER']['FIN_EXERCICE']) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> La date entrée est hors exercice, veuillez entrer une date valide.';}
		}
		echo $msg;
		break;

	default : ///Nothing
		//header('location:../index.php');

}

(isset($_POST['myaction']) && $_POST['myaction']!='' ? $myaction = $_POST['myaction'] : $myaction = '');
switch($myaction){
	case 'edit':
		(isset($_POST['rowSelection']) ? $id = $_POST['rowSelection'][0] : $id ='');
		$split = preg_split('/@/',$id);
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			die($error->getMessage().' '.__LINE__);
		}
		$sql = "SELECT compte.* FROM  `compte` WHERE `LOGIN` LIKE '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);
		$_SESSION['DATA_LO'] = $row;
		header('location:edituser.php?selectedTab=par&rs=2');
		break;

	case 'delete':
		(isset($_POST['rowSelection']) ? $id = $_POST['rowSelection'] : $id =array());
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			die($error->getMessage().' '.__LINE__);
		}

		foreach($id as $key => $val){
			$split = preg_split('/@/',$val);
			$sql = "DELETE FROM  `compte` WHERE `LOGIN` LIKE '".addslashes($split[0])."'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
		}
		header('location:user.php?selectedTab=par&rs=4');
		break;

	default : ///Nothing
		//header('location:../index.php');

}
if($myaction =='' && $do =='') header('location:../index.php');

?>
