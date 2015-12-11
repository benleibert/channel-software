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

set_time_limit(720);
//Action to do
(isset($_GET['do']) && $_GET['do']!='' ? $do = $_GET['do'] : $do = '');
(isset($_POST['myaction']) && $_POST['myaction']!='' ? $myaction = $_POST['myaction'] : $myaction = '');

if($myaction =='' && $do !=''){
	switch($do){
		//Log in User

		case 'vider':

			$sql  = "
			DELETE FROM mouvement;
			DELETE FROM detreport;
			DELETE FROM report;
			DELETE FROM dettransfert;
			DELETE FROM transfert;
			DELETE FROM detdeclass;
			DELETE FROM declass;
			DELETE FROM detlivraison;
			DELETE FROM livraison;
			DELETE FROM detbonsortie;
			DELETE FROM bonsortie;
			DELETE FROM detinventaire;
			DELETE FROM inventaire;
			DELETE FROM	prd_cde;
			DELETE FROM	commande;
			DELETE FROM	logs;
			";

			if(isset($_POST['beneficiaire']) && $_POST['beneficiaire']=='beneficiaire'){
				$sql  .= "DELETE FROM beneficiaire;";
			}
			if(isset($_POST['fournisseur']) && $_POST['fournisseur']=='fournisseur'){
				$sql  .= "DELETE FROM fournisseur;";
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
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Vidage de la base de données '.$_SESSION['GL_USER']['LOGIN'].'('.$_SESSION['GL_USER']['MLLE'].')'); //updateLog($username, $idcust, $action='' )
			header('location:vider.php?selectedTab=data&rs=1');
			break;

		case 'import':
			$_SESSION['DATA_BAK']['nbre']=0;
			$f = '../upload/Backupload_'.$_SESSION['GL_USER']['EXERCICE'].'_'.date('ymdHis').'.sql';
			if (move_uploaded_file($_FILES['fichiersql']['tmp_name'], $f)){
				$ptFichier = fopen($f, 'r');

				//to do
				(isset($_POST['serveur']) && $_POST['serveur']	? $serveur	 = trim($_POST['serveur']) 	: $serveur 		= '');
				(isset($_POST['basedonnees']) && $_POST['basedonnees']	? $basedonnees	 = trim($_POST['basedonnees']) 	: $basedonnees		= '');
				(isset($_POST['user']) && $_POST['user']	? $user	 = trim($_POST['user']) 	: $user 		= '');
				(isset($_POST['pwd']) && $_POST['pwd']	? $pwd	 = trim($_POST['pwd']) 	: $pwd 		= '');

				$dblink = "mysql:host=$serveur;dbname=$basedonnees";

				try {
					//$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
					$cnx = new PDO($dblink, $user, $pwd, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}

				$i=0; $f = ''; $j=0;
				while($sql = fgets($ptFichier)){
					$query =  $cnx->prepare($sql); //Prepare the SQL
					if(!$query->execute()) {
						$f .= $sql;
						$j++;
					}; //Execute prepared SQL => $query
					$i++;
				}
			}
			$_SESSION['DATA_BAK']['nbre']=$i;
			$_SESSION['DATA_BAK']['nbreerror']=$j;
			$_SESSION['DATA_BAK']['error']=$f;
			header('location:import1.php?selectedTab=data&rs=1');
			break;

		case 'sauve':
			$sql='';
			$f = '../download/Backup_'.$_SESSION['GL_USER']['EXERCICE'].'_'.date('Ymd').'_'.date('His').'.sql';
			$ptFichier = fopen($f, 'w');
			$_SESSION['DATA_BAK']['table']=array();
			$t = array();

			//REGION
			if(isset($_POST['region']) && $_POST['region']=='region'){

				$sqlregion = "SELECT * FROM region;";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}

				$query =  $cnx->prepare($sqlregion); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$i=0;
				$sql = '';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					$sql .="INSERT INTO `region` (`IDREGION`, `REGION`) VALUES ('".addslashes($row['IDREGION'])."', '".addslashes($row['REGION'])."');\r\n";
					$i++;
				}
				array_push($t, "Région -> $i");

				//echo 'REGION', $sql, '<br>';
				fwrite($ptFichier,$sql);
			}

			//PROVINCE
			if(isset($_POST['province']) && $_POST['province']=='province'){

				$sqlprovince = "SELECT * FROM province;";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}

				$query =  $cnx->prepare($sqlprovince); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$i=0;
				$sql = '';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					$sql .="INSERT INTO `province` (`IDPROVINCE`, `IDREGION`, `PROVINCE`) VALUES  ('".addslashes($row['IDPROVINCE'])."', '".addslashes($row['IDREGION'])."', '".addslashes($row['PROVINCE'])."');\r\n";
					$i++;
				}
				array_push($t, "Province -> $i");

				//echo 'PROVINCE', $sql, '<br>';
				fwrite($ptFichier,$sql);
			}
//MAGASIN
			if(isset($_POST['magasin']) && $_POST['magasin']=='magasin'){

				$sqlmagasin = "SELECT * FROM magasin;";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}

				$query =  $cnx->prepare($sqlmagasin); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$i=0;
				$sql = '';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					$sql .="INSERT INTO `magasin` (`CODE_MAGASIN`, `IDPROVINCE`, `SER_NOM`, `SER_EMAIL`, `SER_TEL`, `SER_VILLE`) VALUES  ('".addslashes($row['CODE_MAGASIN'])."', '".addslashes($row['IDPROVINCE'])."', '".addslashes($row['SER_NOM'])."', '".addslashes($row['SER_EMAIL'])."', '".addslashes($row['SER_TEL'])."', '".addslashes($row['SER_VILLE'])."');\r\n";
					$i++;
				}
				array_push($t, "Magasin -> $i");

				//echo 'MAGASIN', $sql, '<br>';
				fwrite($ptFichier,$sql);
			}

			//UNITE
			if(isset($_POST['unite']) && $_POST['unite']=='unite'){

				$sqlunite = "SELECT * FROM unite;";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}

				$query =  $cnx->prepare($sqlunite); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$i=0;
				$sql = '';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					$sql .="INSERT INTO `unite` (`ID_UNITE`, `UT_LIBELLE`) VALUES ('".addslashes($row['ID_UNITE'])."', '".addslashes($row['UT_LIBELLE'])."');\r\n";
					$i++;
				}
				array_push($t, "Unité -> $i");

				//echo 'UNITE', $sql, '<br>';
				fwrite($ptFichier,$sql);
			}

			//CATEGORIE
			if(isset($_POST['categorie']) && $_POST['categorie']=='categorie'){

				$sqlcategorie = "SELECT * FROM categorie;";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}

				$query =  $cnx->prepare($sqlcategorie); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$i=0;
				$sql = '';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					$sql .="INSERT INTO `categorie` (`CODE_CATEGORIE`, `CAT_LIBELLE`) VALUES ('".addslashes($row['CODE_CATEGORIE'])."', '".addslashes($row['CAT_LIBELLE'])."');\r\n";
					$i++;
				}
				array_push($t, "Catégorie -> $i");

				//echo 'CATEGORIE', $sql, '<br>';
				fwrite($ptFichier,$sql);
			}

			//SOUSCATEGORIE
			if(isset($_POST['souscategorie']) && $_POST['souscategorie']=='souscategorie'){

				$sqlsouscategorie = "SELECT * FROM souscategorie;";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}

				$query =  $cnx->prepare($sqlsouscategorie); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$i=0;
				$sql = '';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					$sql .="INSERT INTO `souscategorie` (`CODE_SOUSCATEGORIE`, `CODE_CATEGORIE`, `SOUSCAT_LIBELLE`) VALUES  ('".addslashes($row['CODE_SOUSCATEGORIE'])."', '".addslashes($row['CODE_CATEGORIE'])."', '".addslashes($row['SOUSCAT_LIBELLE'])."');\r\n";
					$i++;
				}
				array_push($t, "Sous-catégorie -> $i");

				//echo 'CATEGORIE', $sql, '<br>';
				fwrite($ptFichier,$sql);
			}

			//produit
			if(isset($_POST['produit']) && $_POST['produit']=='produit'){

				$sqlproduit = "SELECT * FROM produit;";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}

				$query =  $cnx->prepare($sqlproduit); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$i=0;
				$sql = '';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					$sql .="INSERT INTO `produit` (`CODE_PRODUIT`, `ID_UNITE`, `CODE_SOUSCATEGORIE`, `PRD_LIBELLE`, `PRD_DESCRIP`, `PRD_PRIXACHAT`, `PRD_PRIXREVIENT`, `PRD_PRIXVENTE`, `PRD_SEUILMIN`, `PRD_SEUILMAX`, `PRD_CONDITIONNE`, `PRD_CODEPRDUIT`, `PRD_NBRE_ELT`, `PRD_DIMENSION`) VALUES ('".addslashes($row['CODE_PRODUIT'])."', '".addslashes($row['ID_UNITE'])."', '".addslashes($row['CODE_SOUSCATEGORIE'])."','".addslashes($row['PRD_LIBELLE'])."', '".addslashes($row['PRD_DESCRIP'])."', '".addslashes($row['PRD_PRIXACHAT'])."', '".addslashes($row['PRD_PRIXREVIENT'])."', '".addslashes($row['PRD_PRIXVENTE'])."', '".addslashes($row['PRD_SEUILMIN'])."', '".addslashes($row['PRD_SEUILMAX'])."', '".addslashes($row['PRD_CONDITIONNE'])."', '".addslashes($row['PRD_CODEPRDUIT'])."','".addslashes($row['PRD_NBRE_ELT'])."','".addslashes($row['PRD_DIMENSION'])."');\r\n";
					$i++;
				}
				array_push($t, "Produit -> $i");
				//echo 'PRODUIT', $sql, '<br>';
				fwrite($ptFichier,$sql);
			}

			//menu
			if(isset($_POST['menu']) && $_POST['menu']=='menu'){

				$sqlmenu = "SELECT * FROM menu;";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}

				$query =  $cnx->prepare($sqlmenu); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$i=0;
				$sql = '';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					$sql .="INSERT INTO `menu` (`IDMENU`, `LIBMENU`) VALUES ('".addslashes($row['IDMENU'])."', '".addslashes($row['LIBMENU'])."');\r\n";
					$i++;
				}
				array_push($t, "Menu -> $i");
				//echo 'MENU', $sql, '<br>';
				fwrite($ptFichier,$sql);
			}


			//TYPE DE BENEFICIAIRE
			if(isset($_POST['typebeneficiaire']) && $_POST['typebeneficiaire']=='typebeneficiaire'){

				$sqltypebeneficiaire = "SELECT * FROM typebeneficiaire;";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}

				$query =  $cnx->prepare($sqltypebeneficiaire); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$i=0;
				$sql = '';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					$sql .="INSERT INTO `typebeneficiaire` (`CODE_TYPEBENEF`, `NOM_TYPEBENEF`) VALUES ('".addslashes($row['CODE_TYPEBENEF'])."', '".addslashes($row['NOM_TYPEBENEF'])."');\r\n";
					$i++;
				}
				array_push($t, "Type de bénéficiaire -> $i");

				//echo 'TYPE DE BENEFICIAIRE', $sql, '<br>';
				fwrite($ptFichier,$sql);
			}

			//TYPE DE FOURNISSEUR
			if(isset($_POST['typefournisseur']) && $_POST['typefournisseur']=='typefournisseur'){

				$sqltypefournisseur = "SELECT * FROM typefournisseur;";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}

				$query =  $cnx->prepare($sqltypefournisseur); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$i=0;
				$sql = '';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$sql .="INSERT INTO `typefournisseur` (`CODE_TYPEFOUR`, `TYPEFOUR_NOM`) VALUES ('".addslashes($row['CODE_TYPEFOUR'])."', '".addslashes($row['TYPEFOUR_NOM'])."');\r\n";
					$i++;
				}
				array_push($t, "Type fournisseur -> $i");

				//echo 'TYPE DE FOURNISSEUR', $sql, '<br>';
				fwrite($ptFichier,$sql);
			}

			//BENEFICIAIRE
			if(isset($_POST['beneficiaire']) && $_POST['beneficiaire']=='beneficiaire'){

				$sqlBeneficiaire = "SELECT * FROM beneficiaire;";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}

				$query =  $cnx->prepare($sqlBeneficiaire); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$i=0;
				$sql = '';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					$sql .="INSERT INTO `beneficiaire` (`CODE_BENEF`, `CODE_TYPEBENEF`, `IDPROVINCE`, `BENEF_NOM`, `BENEF_EBREVIATION`, `BENEF_TEL`, `BENEF_VILLE`, `BENEF_EMAIL`, `BENEF_DATECREAT`) VALUES ('".addslashes($row['CODE_BENEF'])."', '".addslashes($row['CODE_TYPEBENEF'])."', '".addslashes($row['IDPROVINCE'])."', '".addslashes($row['BENEF_NOM'])."', '".addslashes($row['BENEF_EBREVIATION'])."', '".addslashes($row['BENEF_TEL'])."','".addslashes($row['BENEF_VILLE'])."', '".addslashes($row['BENEF_EMAIL'])."','".addslashes($row['BENEF_DATECREAT'])."');\r\n";
					$i++;
				}
				array_push($t, "Bénéficiaire -> $i");

				//echo 'BENEFICIAIRE', $sql, '<br>';
				fwrite($ptFichier,$sql);
			}

			//fournisseur
			if(isset($_POST['fournisseur']) && $_POST['fournisseur']=='fournisseur'){

				$sqlfournisseur = "SELECT * FROM fournisseur;";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}

				$query =  $cnx->prepare($sqlfournisseur); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$i=0;
				$sql = '';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					$sql .="INSERT INTO `fournisseur` (`CODE_FOUR`, `CODE_TYPEFOUR`, `FOUR_NOM`, `FOUR_TEL`, `FOUR_ADRESSE`, `FOUR_EMAIL`, `FOUR_RESPONSABLE`, `FOUR_RESPTEL`, `FOUR_RESPEMAIL`) VALUES  ('".addslashes($row['CODE_FOUR'])."', '".addslashes($row['CODE_TYPEFOUR'])."', '".addslashes($row['FOUR_NOM'])."', '".addslashes($row['FOUR_TEL'])."', '".addslashes($row['FOUR_ADRESSE'])."', '".addslashes($row['FOUR_EMAIL'])."','".addslashes($row['FOUR_RESPONSABLE'])."', '".addslashes($row['FOUR_RESPTEL'])."', '".addslashes($row['FOUR_RESPEMAIL'])."');\r\n";
					$i++;
				}
				array_push($t, "Fournisseur -> $i");
				//echo 'BENEFICIAIRE', $sql, '<br>';
				fwrite($ptFichier,$sql);
			}

			//EXERCICE
			if(isset($_POST['exercice']) && $_POST['exercice']=='exercice'){

				$sqlexercice = "SELECT * FROM exercice;";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}

				$query =  $cnx->prepare($sqlexercice); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$i=0;
				$sql = '';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
$sql .="INSERT INTO `exercice` (`ID_EXERCICE`, `EX_LIBELLE`, `EX_DATEDEBUT`, `EX_DATEFIN`, `EX_CLOTURE`, `EX_DATECLOTURE`) VALUES ('".addslashes($row['ID_EXERCICE'])."', '".addslashes($row['EX_LIBELLE'])."', '".addslashes($row['EX_DATEDEBUT'])."', '".addslashes($row['EX_DATEFIN'])."', '".addslashes($row['EX_CLOTURE'])."', '".addslashes($row['EX_DATECLOTURE'])."');\r\n";
					$i++;
				}
				array_push($t, "Exercice -> $i");

				//echo 'EXERCICE', $sql, '<br>';
				fwrite($ptFichier,$sql);
			}


			//infogenerale
			if(isset($_POST['infogenerale']) && $_POST['infogenerale']=='infogenerale'){

				$sqlinfogenerale = "SELECT * FROM infogenerale WHERE CODE_MAGASIN LIKE '".addslashes($_SESSION['GL_USER']['MAGASIN'])."';";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}

				$query =  $cnx->prepare($sqlinfogenerale); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$i=0;
				$sql = '';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					$sql .="INSERT INTO `infogenerale` (`CODE_INFGLE`, `CODE_MAGASIN`, `ID`, `INF_CLIENT`, `INF_DATEACQ`, `INF_LICENCE`, `INF_MINISTERE`, `INF_SECRETARIAT`, `INF_DIRECTION`, `INF_SERVICE`, `INF_PAYS`, `INF_DEVISE`, `INF_VILLE`, `INF_SIGNATEUR1`, `INF_NOMSIGNATEUR1`, `INF_SIGNATEUR2`, `INF_NOMSIGNATEUR2`, `INF_SIGNATEUR3`, `INF_NOMSIGNATEUR3`, `INF_SIGNATEUR4`, `INF_NOMSIGNATEUR4`, `INF_VALIDAUTO`, `INF_MAGASIN`) VALUES ('".addslashes($row['CODE_INFGLE'])."', '".addslashes($_SESSION['GL_USER']['MAGASIN'])."','".addslashes($row['ID'])."','".addslashes($row['INF_CLIENT'])."', '".addslashes($row['INF_DATEACQ'])."','".addslashes($row['INF_LICENCE'])."','".addslashes($row['INF_MINISTERE'])."', '".addslashes($row['INF_SECRETARIAT'])."','".addslashes($row['INF_DIRECTION'])."','".addslashes($row['INF_SERVICE'])."', '".addslashes($row['INF_PAYS'])."','".addslashes($row['INF_DEVISE'])."','".addslashes($row['INF_VILLE'])."', '".addslashes($row['INF_SIGNATEUR1'])."','".addslashes($row['INF_NOMSIGNATEUR1'])."','".addslashes($row['INF_SIGNATEUR2'])."', '".addslashes($row['INF_NOMSIGNATEUR2'])."','".addslashes($row['INF_SIGNATEUR3'])."','".addslashes($row['INF_NOMSIGNATEUR3'])."', '".addslashes($row['INF_SIGNATEUR4'])."','".addslashes($row['INF_NOMSIGNATEUR4'])."','".addslashes($row['INF_VALIDAUTO'])."', '".addslashes($row['INF_MAGASIN'])."');\r\n";
					$i++;
				}
				array_push($t, "Info générale -> $i");
				//echo 'INFO GENERALE', $sql, '<br>';
				fwrite($ptFichier,$sql);
			}

			//profil
			if(isset($_POST['profil']) && $_POST['profil']=='profil'){

				$sqlinfogenerale = "SELECT * FROM infogenerale WHERE CODE_MAGASIN LIKE '".addslashes($_SESSION['GL_USER']['MAGASIN'])."';";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}

				$query =  $cnx->prepare($sqlinfogenerale); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$i=0;
				$sql = '';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					$sql .="INSERT INTO `infogenerale` (`CODE_INFGLE`, `CODE_MAGASIN`, `ID`, `INF_CLIENT`, `INF_DATEACQ`, `INF_LICENCE`,`INF_MINISTERE`, `INF_SECRETARIAT`, `INF_DIRECTION`, `INF_SERVICE`, `INF_PAYS`, `INF_DEVISE`, `INF_VILLE`, `INF_SIGNATEUR1`,`INF_NOMSIGNATEUR1`, `INF_SIGNATEUR2`, `INF_NOMSIGNATEUR2`, `INF_SIGNATEUR3`, `INF_NOMSIGNATEUR3`, `INF_SIGNATEUR4`,`INF_NOMSIGNATEUR4`, `INF_VALIDAUTO`, `INF_MAGASIN`) VALUES ('".addslashes($row['CODE_INFGLE'])."',	'".addslashes($_SESSION['GL_USER']['MAGASIN'])."','".addslashes($row['ID'])."','".addslashes($row['INF_CLIENT'])."','".addslashes($row['INF_DATEACQ'])."','".addslashes($row['INF_LICENCE'])."','".addslashes($row['INF_MINISTERE'])."','".addslashes($row['INF_SECRETARIAT'])."','".addslashes($row['INF_DIRECTION'])."','".addslashes($row['INF_SERVICE'])."','".addslashes($row['INF_PAYS'])."','".addslashes($row['INF_DEVISE'])."','".addslashes($row['INF_VILLE'])."','".addslashes($row['INF_SIGNATEUR1'])."','".addslashes($row['INF_NOMSIGNATEUR1'])."','".addslashes($row['INF_SIGNATEUR2'])."',	'".addslashes($row['INF_NOMSIGNATEUR2'])."','".addslashes($row['INF_SIGNATEUR3'])."','".addslashes($row['INF_NOMSIGNATEUR3'])."','".addslashes($row['INF_SIGNATEUR4'])."','".addslashes($row['INF_NOMSIGNATEUR4'])."','".addslashes($row['INF_VALIDAUTO'])."','".addslashes($row['INF_MAGASIN'])."');\r\n";
					$i++;
				}
				array_push($t, "Profil -> $i");
				//echo 'INFO GENERALE', $sql, '<br>';
				fwrite($ptFichier,$sql);
			}

			//profil_menu
			if(isset($_POST['profil_menu']) && $_POST['profil_menu']=='profil_menu'){

				$sqlprofil_menu = "SELECT * FROM profil_menu;";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}

				$query =  $cnx->prepare($sqlprofil_menu); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$i=0;
				$sql = '';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					$sql .="INSERT INTO `profil_menu` (`IDPROFIL`, `IDMENU`, `VISIBLE`, `AJOUT`, `MODIF`, `SUPPR`, `ANNUL`, `VALID`) VALUES ('".addslashes($row['IDPROFIL'])."', '".addslashes($row['IDMENU'])."','".addslashes($row['VISIBLE'])."','".addslashes($row['AJOUT'])."','".addslashes($row['MODIF'])."','".addslashes($row['SUPPR'])."','".addslashes($row['ANNUL'])."','".addslashes($row['VALID'])."');\r\n";
					$i++;
				}
				array_push($t, "Profil menu -> $i");
				//echo 'PROFIL MENU', $sql, '<br>';
				fwrite($ptFichier,$sql);
			}

			//personnel
			if(isset($_POST['personnel']) && $_POST['personnel']=='personnel'){

				$sqlpersonnel = "SELECT * FROM personnel;";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}

				$query =  $cnx->prepare($sqlpersonnel); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$i=0;
				$sql = '';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					$sql .="INSERT INTO `personnel` (`NUM_MLLE`, `CODE_MAGASIN`, `PERS_NOM`, `PERS_PRENOMS`, `PERS_TEL`,`PERS_ADRESSE`, `PERS_EMAIL`, `PERS_FONCTION`) VALUES  ('".addslashes($row['NUM_MLLE'])."',	'".addslashes($_SESSION['GL_USER']['MAGASIN'])."','".addslashes($row['PERS_NOM'])."', 	'".addslashes($row['PERS_PRENOMS'])."',	'".addslashes($row['PERS_TEL'])."','".addslashes($row['PERS_ADRESSE'])."', '".addslashes($row['PERS_EMAIL'])."','".addslashes($row['PERS_FONCTION'])."');\r\n";
					$i++;
				}
				array_push($t, "Personnel -> $i");
				//echo 'PERSONNEL', $sql, '<br>';
				fwrite($ptFichier,$sql);
			}

			//compte
			if(isset($_POST['compte']) && $_POST['compte']=='compte'){

				$sqlcompte = "SELECT * FROM compte;";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}

				$query =  $cnx->prepare($sqlcompte); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$i=0;
				$sql = '';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					$sql .="INSERT INTO `compte` (`LOGIN`, `NUM_MLLE`, `IDPROFIL`, `PWD`, `ACTIVATED`) VALUES ('".addslashes($row['LOGIN'])."',	'".addslashes($row['NUM_MLLE'])."','".addslashes($row['IDPROFIL'])."','".addslashes($row['PWD'])."','".addslashes($row['ACTIVATED'])."');\r\n";
					$i++;
				}
				array_push($t, "Compte -> $i");
				//echo 'COMPTE', $sql, '<br>';
				fwrite($ptFichier,$sql);
			}

			//commande et prd_cde
			if(isset($_POST['commande']) && $_POST['commande']=='commande'){

	$sqlcommande = "SELECT * FROM commande WHERE CODE_MAGASIN LIKE '".addslashes($_SESSION['GL_USER']['MAGASIN'])."' AND ID_EXERCICE='".addslashes($_SESSION['GL_USER']['EXERCICE'])."';";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}

				$query =  $cnx->prepare($sqlcommande); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$i=0;
				$sql = '';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					$sql .="INSERT INTO `commande` (`CODE_COMMANDE`, `CODE_FOUR`, `CODE_MAGASIN`, `ID_EXERCICE`, `REF_COMMANDE`,`ID_COMMANDE`, `CDE_LIBELLE`, `CDE_DATE`, `CDE_STATUT`, `CDE_DATEVALID`) VALUES ('".addslashes($row['CODE_COMMANDE'])."','".addslashes($row['CODE_FOUR'])."','".addslashes($_SESSION['GL_USER']['MAGASIN'])."','".addslashes($row['ID_EXERCICE'])."','".addslashes($row['REF_COMMANDE'])."', '".addslashes($row['ID_COMMANDE'])."', '".addslashes($row['CDE_LIBELLE'])."',	'".addslashes($row['CDE_DATE'])."', '".addslashes($row['CDE_STATUT'])."', '".addslashes($row['CDE_DATEVALID'])."');\r\n";
					$i++;
				}
				array_push($t, "Commande -> $i");

				//prd_cde
				$sqlprd_cde = "SELECT * FROM prd_cde INNER JOIN commande ON (prd_cde.CODE_COMMANDE LIKE commande.CODE_COMMANDE)	WHERE commande.CODE_MAGASIN LIKE '".addslashes($_SESSION['GL_USER']['MAGASIN'])."' AND commande.ID_EXERCICE='".addslashes($_SESSION['GL_USER']['EXERCICE'])."';";

				$query =  $cnx->prepare($sqlprd_cde); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$i=0;

				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					(is_null($row['CDEPRD_PRIX']) ? $prix = 'NULL' : $prix = "'".$row['CDEPRD_PRIX']."'");
					(is_null($row['CDEPRD_PA']) ? $pa = 'NULL' : $pa = "'".$row['CDEPRD_PA']."'");
					(is_null($row['CDEPRD_QTE']) ? $qte = 'NULL' : $qte = "'".$row['CDEPRD_QTE']."'");

					$sql .="INSERT INTO `prd_cde` (`CODE_COMMANDE`, `CODE_PRODUIT`, `CODE_MAGASIN`, `CDEPRD_QTE`, `CDEPRD_PRIX`,`CDEPRD_UNITE`, `CDEPRD_PA`) VALUES ('".addslashes($row['CODE_COMMANDE'])."','".addslashes($row['CODE_PRODUIT'])."','".addslashes($row['CODE_MAGASIN'])."', $qte, $prix, '".addslashes($row['CDEPRD_UNITE'])."', $pa);\r\n";
					$i++;
				}
				array_push($t, "Détails commande -> $i");
				//echo 'CDE DETCDE', $sql, '<br>';
				fwrite($ptFichier,$sql);
			}

			//livraison  detlivraison
			if(isset($_POST['livraison']) && $_POST['livraison']=='livraison'){

$sqllivraison = "SELECT * FROM livraison WHERE CODE_MAGASIN LIKE '".addslashes($_SESSION['GL_USER']['MAGASIN'])."' AND ID_EXERCICE='".addslashes($_SESSION['GL_USER']['EXERCICE'])."';";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}

				$query =  $cnx->prepare($sqllivraison); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$i=0;
				$sql = '';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
				(is_null($row['CODE_COMMANDE']) ? $libcom = 'NULL' : $libcom = "'".addslashes($row['CODE_COMMANDE'])."'");

$sql .="INSERT INTO livraison ( CODE_LIVRAISON, CODE_COMMANDE, CODE_FOUR, CODE_MAGASIN, ID_EXERCICE, REF_LIVRAISON, ID_LIVRAISON, LVR_LIBELLE, LVR_DATE, LVR_VALIDE, LVR_DATEVALID )	VALUES ('".addslashes($row['CODE_LIVRAISON'])."', $libcom, '".addslashes($row['CODE_FOUR'])."', '".addslashes($row['CODE_MAGASIN'])."', '".addslashes($row['ID_EXERCICE'])."', '".addslashes($row['REF_LIVRAISON'])."', '".addslashes($row['ID_LIVRAISON'])."', '".addslashes($row['LVR_LIBELLE'])."', '".addslashes($row['LVR_DATE'])."', '".addslashes($row['LVR_VALIDE'])."', '".addslashes($row['LVR_DATEVALID'])."');\r\n";
					$i++;
				}
				array_push($t, "Livraisons -> $i");

				//detlivraison

$sqldetlivraison = "SELECT * FROM detlivraison INNER JOIN livraison ON (detlivraison.CODE_LIVRAISON LIKE livraison.CODE_LIVRAISON) WHERE livraison.CODE_MAGASIN LIKE '".addslashes($_SESSION['GL_USER']['MAGASIN'])."' AND livraison.ID_EXERCICE='".addslashes($_SESSION['GL_USER']['EXERCICE'])."';";

				$query =  $cnx->prepare($sqldetlivraison); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$i=0;
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					(is_null($row['LVR_PRDQTE']) ? $qteliv = 'NULL' : $qteliv = "'".$row['LVR_PRDQTE']."'");
					(is_null($row['LVR_PRDRECU']) ? $qterecu = 'NULL' : $qterecu = "'".$row['LVR_PRDRECU']."'");
					(is_null($row['LVR_QTESORTIE']) ? $qtesortie = 'NULL' : $qtesortie = "'".$row['LVR_QTESORTIE']."'");
					(is_null($row['LVR_PA']) ? $pa = 'NULL' : $pa = "'".$row['LVR_PA']."'");
					(is_null($row['LVR_PR']) ? $pr = 'NULL' : $pr = "'".$row['LVR_PR']."'");
					(is_null($row['LVR_IDCOMMANDE']) ? $libcom = 'NULL' : $libcom = "'".addslashes($row['LVR_IDCOMMANDE'])."'");

$sql .="INSERT INTO `detlivraison` (`CODE_DETLIVRAISON`, `CODE_PRODUIT`, `CODE_LIVRAISON`, `ID_DETLIVRAISON`, `LVR_PRDQTE`,`LVR_PRDRECU`, `LVR_QTESORTIE`,`LVR_UNITE`, `LVR_IDCOMMANDE`, `LVR_MAGASIN`, `LVR_PA`, `LVR_PR`, `LVRLOT_VALID`, `LVRLOT_DATEVALID`, `LVR_REFLOT`, `LVR_DATEPEREMP`, `LVR_MONLOT`, `CODE_MAGASIN`) VALUES ('".addslashes($row['CODE_DETLIVRAISON'])."','".addslashes($row['CODE_PRODUIT'])."','".addslashes($row['CODE_LIVRAISON'])."','".addslashes($row['ID_DETLIVRAISON'])."', $qteliv,$qterecu,$qtesortie,'".addslashes($row['LVR_UNITE'])."',$libcom ,'".addslashes($row['LVR_MAGASIN'])."',$pa,$pr,'".addslashes($row['LVRLOT_VALID'])."','".addslashes($row['LVRLOT_DATEVALID'])."','".addslashes($row['LVR_REFLOT'])."','".addslashes($row['LVR_DATEPEREMP'])."','".addslashes($row['LVR_MONLOT'])."','".addslashes($row['CODE_MAGASIN'])."');\r\n";
					$i++;
				}
				array_push($t, "Détails Livraisons -> $i");
				//echo 'detbonsortie', $sql, '<br>';
				fwrite($ptFichier,$sql);
			}

			//transfert	dettransfert
			if(isset($_POST['transfert']) && $_POST['transfert']=='transfert'){

				$sqltransfert = "SELECT * FROM transfert WHERE CODE_MAGASIN LIKE '".addslashes($_SESSION['GL_USER']['MAGASIN'])."' AND ID_EXERCICE='".addslashes($_SESSION['GL_USER']['EXERCICE'])."';";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}

				$query =  $cnx->prepare($sqltransfert); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$i=0;
				$sql = '';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
$sql .="INSERT INTO `transfert` (`CODE_TRANSFERT`, `CODE_MAGASIN`, `ID_EXERCICE`, `REF_TRANSFERT`, `ID_TRANSFERT`,`MAG_CODE_MAGASIN_SRCE`, `MAG_CODE_MAGASIN_DEST`, `TRS_DATE`, `TRS_NATURE`, `TRS_RAISON`, `TRS_VALIDE`, `TRS_DATEVALID`, `TRS_LIBELLE`, `MAG_NP`, `MAG_CIB`, `MAG_DATE`,`PRE_NP`, `PRE_CIB`, `PRE_DATE`) VALUES ('".addslashes($row['CODE_TRANSFERT'])."','".addslashes($_SESSION['GL_USER']['MAGASIN'])."',	'".addslashes($row['ID_EXERCICE'])."','".addslashes($row['REF_TRANSFERT'])."','".addslashes($row['ID_TRANSFERT'])."','".addslashes($row['MAG_CODE_MAGASIN_SRCE'])."','".addslashes($row['MAG_CODE_MAGASIN_DEST'])."','".addslashes($row['TRS_DATE'])."' , '".addslashes($row['TRS_NATURE'])."','".addslashes($row['TRS_RAISON'])."', '".addslashes($row['TRS_VALIDE'])."','".addslashes($row['TRS_DATEVALID'])."','".addslashes($row['TRS_LIBELLE'])."','".addslashes($row['MAG_NP'])."','".addslashes($row['MAG_CIB'])."','".addslashes($row['MAG_DATE'])."','".addslashes($row['PRE_NP'])."','".addslashes($row['PRE_CIB'])."','".addslashes($row['PRE_DATE'])."');\r\n";
					$i++;
				}

				array_push($t, "Transfert -> $i");
				//dettransfert
$sqldettransfert = "SELECT * FROM dettransfert INNER JOIN transfert ON (dettransfert.CODE_TRANSFERT LIKE transfert.CODE_TRANSFERT) WHERE transfert.CODE_MAGASIN LIKE '".addslashes($_SESSION['GL_USER']['MAGASIN'])."' AND transfert.ID_EXERCICE='".addslashes($_SESSION['GL_USER']['EXERCICE'])."';";

				$query =  $cnx->prepare($sqldettransfert); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$i=0;
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					(is_null($row['TRS_PRDQTE']) ? $qte = 'NULL' : $qte = "'".$row['TRS_PRDQTE']."'");
					(is_null($row['TRS_PRDRECU']) ? $qterecu = 'NULL' : $qterecu = "'".$row['TRS_PRDRECU']."'");
					(is_null($row['TRS_PV']) ? $pv = 'NULL' : $pv = "'".$row['TRS_PV']."'");

$sql .="INSERT INTO `dettransfert` (`CODE_DETTRANSFERT`, `CODE_PRODUIT`, `CODE_TRANSFERT`, `CODE_MAGASIN`,`ID_DETTRANSFERT`, `TRS_PRDQTE`, `TRS_PRDRECU`,`TRS_UNITE`, `TRS_REFLOT`, `TRS_DATEPEREMP`, `TRS_PV`, `TRS_MONLOT`) VALUES ('".addslashes($row['CODE_DETTRANSFERT'])."',	'".addslashes($row['CODE_PRODUIT'])."','".addslashes($row['CODE_TRANSFERT'])."', '".addslashes($row['CODE_MAGASIN'])."', '".addslashes($row['ID_DETTRANSFERT'])."',$qte,$qterecu, '".addslashes($row['TRS_UNITE'])."','".addslashes($row['TRS_REFLOT'])."','".addslashes($row['TRS_DATEPEREMP'])."',$pv,'".addslashes($row['TRS_MONLOT'])."');\r\n";
					$i++;
				}
				array_push($t, "Détails transfert -> $i");
				//echo 'dettransfert', $sql, '<br>';
				fwrite($ptFichier,$sql);
			}


			//bonsortie  detbonsortie
			if(isset($_POST['bonsortie']) && $_POST['bonsortie']=='bonsortie'){

$sqlbonsortie = "SELECT * FROM bonsortie WHERE CODE_MAGASIN LIKE '".addslashes($_SESSION['GL_USER']['MAGASIN'])."' AND ID_EXERCICE='".addslashes($_SESSION['GL_USER']['EXERCICE'])."';";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}

				$query =  $cnx->prepare($sqlbonsortie); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$i=0;
				$sql = '';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
$sql .="INSERT INTO `bonsortie` (`CODE_BONSORTIE`, `CODE_BENEF`, `CODE_MAGASIN`, `ID_EXERCICE`, `REF_BONSORTIE`, `ID_BONSORTIE`,`SOR_LIBELLE`, `SOR_DATE`, `SOR_VALIDE`, `SOR_DATEVALID`) VALUES ('".addslashes($row['CODE_BONSORTIE'])."',	'".addslashes($row['CODE_BENEF'])."','".addslashes($row['CODE_MAGASIN'])."','".addslashes($row['ID_EXERCICE'])."','".addslashes($row['REF_BONSORTIE'])."', '".addslashes($row['ID_BONSORTIE'])."', '".addslashes($row['SOR_LIBELLE'])."','".addslashes($row['SOR_DATE'])."', '".addslashes($row['SOR_VALIDE'])."', '".addslashes($row['SOR_DATEVALID'])."');\r\n";
					$i++;
				}
				array_push($t, "bonsortie -> $i");
				//detbonsortie
$sqldetbonsortie = "SELECT * FROM detbonsortie INNER JOIN bonsortie ON (detbonsortie.CODE_BONSORTIE LIKE bonsortie.CODE_BONSORTIE) WHERE bonsortie.CODE_MAGASIN LIKE '".addslashes($_SESSION['GL_USER']['MAGASIN'])."' AND bonsortie.ID_EXERCICE='".addslashes($_SESSION['GL_USER']['EXERCICE'])."';";

				$query =  $cnx->prepare($sqldetbonsortie); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$i=0;
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					(is_null($row['BSPRD_QTE']) ? $qte = 'NULL' : $qte = "'".$row['BSPRD_QTE']."'");
					(is_null($row['BSPRD_RECU']) ? $qterecu = 'NULL' : $qterecu = "'".$row['BSPRD_RECU']."'");
					(is_null($row['BSPRD_PV']) ? $pv = 'NULL' : $pv = "'".$row['BSPRD_PV']."'");

$sql .="INSERT INTO `detbonsortie` (`CODE_DETBONSORTIE`, `CODE_PRODUIT`, `CODE_BONSORTIE`, `CODE_MAGASIN`, `ID_DETBONSORTIE`,`BSPRD_QTE`, `BSPRD_RECU`,`BSPRD_UNITE`, `BSPRD_REFLOT`, `BSPRD_DATEPEREMP`, `BSPRD_PV`, `BSPRD_MONLOT`) VALUES ('".addslashes($row['CODE_DETBONSORTIE'])."','".addslashes($row['CODE_PRODUIT'])."','".addslashes($row['CODE_BONSORTIE'])."','".addslashes($row['CODE_MAGASIN'])."', '".addslashes($row['ID_DETBONSORTIE'])."',$qte, $qterecu,'".addslashes($row['BSPRD_UNITE'])."','".addslashes($row['BSPRD_REFLOT'])."','".addslashes($row['BSPRD_DATEPEREMP'])."',$pv,'".addslashes($row['BSPRD_MONLOT'])."');\r\n";
					$i++;
				}
				array_push($t, "Détails bon de sortie -> $i");
				//echo 'detbonsortie', $sql, '<br>';
				fwrite($ptFichier,$sql);
			}

			//declass	detdeclass
			if(isset($_POST['declass']) && $_POST['declass']=='declass'){

$sqldeclass = "SELECT * FROM declass WHERE CODE_MAGASIN LIKE '".addslashes($_SESSION['GL_USER']['MAGASIN'])."' AND ID_EXERCICE='".addslashes($_SESSION['GL_USER']['EXERCICE'])."';";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}

				$query =  $cnx->prepare($sqldeclass); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$i=0;
				$sql = '';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
$sql .="INSERT INTO declass ( CODE_DECLASS, CODE_MAGASIN, ID_EXERCICE, CODENATDECLASS, ID_DECLASS, REF_DECLAS, DCL_DATE, DCL_LIBELLE, DCL_RAISON, DCL_REFRAPPORT, DCL_CABINET, DCL_VALIDE, DCL_DATEVALID )	VALUES ('".addslashes($row['CODE_DECLASS'])."', '".addslashes($row['CODE_MAGASIN'])."', '".addslashes($row['ID_EXERCICE'])."', '".addslashes($row['CODENATDECLASS'])."', '".addslashes($row['ID_DECLASS'])."', '".addslashes($row['REF_DECLAS'])."', '".addslashes($row['DCL_DATE'])."', '".addslashes($row['DCL_LIBELLE'])."', '".addslashes($row['DCL_RAISON'])."', '".addslashes($row['DCL_REFRAPPORT'])."', '".addslashes($row['DCL_CABINET'])."', '".addslashes($row['DCL_VALIDE'])."', '".addslashes($row['DCL_DATEVALID'])."');\r\n";
					$i++;
				}
				array_push($t, "Pertes -> $i");

				//detdeclass

$sqldetdeclass = "SELECT * FROM detdeclass INNER JOIN declass ON (detdeclass.CODE_DECLASS LIKE declass.CODE_DECLASS) WHERE declass.CODE_MAGASIN LIKE '".addslashes($_SESSION['GL_USER']['MAGASIN'])."' AND declass.ID_EXERCICE='".addslashes($_SESSION['GL_USER']['EXERCICE'])."';";

				$query =  $cnx->prepare($sqldetdeclass); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$i=0;
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					(is_null($row['DECL_QTE']) ? $qte = 'NULL' : $qte = "'".$row['DECL_QTE']."'");
					(is_null($row['DECL_PA']) ? $pa = 'NULL' : $pa = "'".$row['DECL_PA']."'");


$sql .="INSERT INTO `detdeclass` (`CODE_DETDECLASS`, `CODE_PRODUIT`, `CODE_DECLASS`, `CODE_MAGASIN`, `ID_DETDECLASS`,`DECL_QTE`, `DECL_UNITE`,`DECL_REFLOT`, `DECL_DATEPEREMP`, `DECL_PA`, `DECL_MONLOT`) VALUES ('".addslashes($row['CODE_DETDECLASS'])."','".addslashes($row['CODE_PRODUIT'])."','".addslashes($row['CODE_DECLASS'])."','".addslashes($row['CODE_MAGASIN'])."', '".addslashes($row['ID_DETDECLASS'])."',$qte,'".addslashes($row['DECL_UNITE'])."','".addslashes($row['DECL_REFLOT'])."','".addslashes($row['DECL_DATEPEREMP'])."',$pa,'".addslashes($row['DECL_MONLOT'])."');\r\n";
					$i++;
				}
				array_push($t, "Détails Pertes -> $i");
				//echo 'detbonsortie', $sql, '<br>';
				fwrite($ptFichier,$sql);
			}



			//inventaire	detinventaire
			if(isset($_POST['inventaire']) && $_POST['inventaire']=='inventaire'){

$sqlinventaire = "SELECT * FROM inventaire WHERE CODE_MAGASIN LIKE '".addslashes($_SESSION['GL_USER']['MAGASIN'])."' AND ID_EXERCICE='".addslashes($_SESSION['GL_USER']['EXERCICE'])."';";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}

				$query =  $cnx->prepare($sqlinventaire); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$i=0;
				$sql = '';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
$sql .="INSERT INTO `inventaire` (`CODE_INVENTAIRE`, `CODE_MAGASIN`, `ID_EXERCICE`, `REF_INVENTAIRE`, `ID_INVENTAIRE`,`INV_LIBELLE`, `INV_DATE`, `INV_VALID`, `INV_DATEVALID`) VALUES ('".addslashes($row['CODE_INVENTAIRE'])."','".addslashes($_SESSION['GL_USER']['MAGASIN'])."','".addslashes($row['ID_EXERCICE'])."','".addslashes($row['INV_DATE'])."','".addslashes($row['INV_DATEVALID'])."');\r\n";
					$i++;
				}
				array_push($t, "Inventaire -> $i");
				//detinventaire
$sqldetinventaire = "SELECT * FROM detinventaire INNER JOIN inventaire ON (detinventaire.CODE_INVENTAIRE LIKE inventaire.CODE_INVENTAIRE) WHERE  inventaire.CODE_MAGASIN LIKE '".addslashes($_SESSION['GL_USER']['MAGASIN'])."' AND inventaire.ID_EXERCICE='".addslashes($_SESSION['GL_USER']['EXERCICE'])."';";

				$query =  $cnx->prepare($sqldetinventaire); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$i=0;
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					(is_null($row['STOCK_THEO']) ? $theo = 'NULL' : $theo = "'".$row['STOCK_THEO']."'");
					(is_null($row['STOCK_PHYSIQUE']) ? $phy = 'NULL' : $phy = "'".$row['STOCK_PHYSIQUE']."'");
					(is_null($row['ECART']) ? $ecart = 'NULL' : $ecart = "'".$row['ECART']."'");
					(is_null($row['INV_PA']) ? $pa = 'NULL' : $pa = "'".$row['INV_PA']."'");

$sql .="INSERT INTO `detinventaire` (`CODE_DETINVENTAIRE`, `CODE_INVENTAIRE`, `CODE_PRODUIT`, `CODE_MAGASIN`, `ID_DETINVENTAIRE`,`STOCK_PHYSIQUE`,	`STOCK_THEO`, `ECART`, `RAISON_ECART`, `INV_PA`, `INV_UNITE`, `INV_REFLOT`, `INV_DATEPEREMP`, `INV_MONLOT`) VALUES ('".addslashes($row['CODE_DETINVENTAIRE'])."','".addslashes($row['CODE_INVENTAIRE'])."','".addslashes($row['CODE_PRODUIT'])."','".addslashes($row['CODE_MAGASIN'])."', '".addslashes($row['ID_DETINVENTAIRE'])."',$phy, $theo, $ecart,'".addslashes($row['RAISON_ECART'])."',$pa,'".addslashes($row['INV_UNITE'])."', '".addslashes($row['INV_REFLOT'])."','".addslashes($row['INV_DATEPEREMP'])."','".addslashes($row['INV_MONLOT'])."');\r\n";
					$i++;
				}
				array_push($t, "Détails inventaire -> $i");
				//echo 'detinventaire', $sql, '<br>';
				fwrite($ptFichier,$sql);
			}

			//report	detreport
			if(isset($_POST['report']) && $_POST['report']=='report'){

$sqlreport = "SELECT * FROM report WHERE CODE_MAGASIN LIKE '".addslashes($_SESSION['GL_USER']['MAGASIN'])."' AND ID_EXERCICE='".addslashes($_SESSION['GL_USER']['EXERCICE'])."';";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}

				$query =  $cnx->prepare($sqlreport); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$i=0;
				$sql = '';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
$sql .="INSERT INTO `report` (`CODE_REPORT`, `CODE_MAGASIN`, `ID_EXERCICE`, `ID_REPORT`, `REP_LIBELLE`, `REP_NATURE`, `REP_DATE`,`REP_VALIDE`, `REP_DATEVALID`, `CODE_REP_SORT`)  VALUES ('".addslashes($row['CODE_REPORT'])."',	'".addslashes($_SESSION['GL_USER']['MAGASIN'])."',	'".addslashes($row['ID_EXERCICE'])."','".addslashes($row['ID_REPORT'])."','".addslashes($row['REP_LIBELLE'])."','".addslashes($row['REP_NATURE'])."', '".addslashes($row['REP_DATE'])."' , '".addslashes($row['REP_VALIDE'])."','".addslashes($row['REP_DATEVALID'])."', '".addslashes($row['CODE_REP_SORT'])."');\r\n";
					$i++;
				}
				array_push($t, "Report -> $i");
				//detreport
$sqldetreport = "SELECT * FROM detreport INNER JOIN detreport ON (detreport.CODE_REPORT LIKE report.CODE_REPORT) WHERE report.CODE_MAGASIN LIKE '".addslashes($_SESSION['GL_USER']['MAGASIN'])."' AND report.ID_EXERCICE='".addslashes($_SESSION['GL_USER']['EXERCICE'])."';";

				$query =  $cnx->prepare($sqldetreport); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$i=0;
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					(is_null($row['STOCK_THEO']) ? $theo = 'NULL' : $theo = "'".$row['STOCK_THEO']."'");
					(is_null($row['STOCK_PHYSIQUE']) ? $phy = 'NULL' : $phy = "'".$row['STOCK_PHYSIQUE']."'");
					(is_null($row['ECART']) ? $ecart = 'NULL' : $ecart = "'".$row['ECART']."'");
					(is_null($row['INV_PA']) ? $pa = 'NULL' : $pa = "'".$row['INV_PA']."'");

$sql .="INSERT INTO `detreport` (`CODE_DETREPORT`, `CODE_REPORT`, `CODE_PRODUIT`, `CODE_MAGASIN`, `ID_DETREPORT`, `REP_PRDQTE`, `REP_UNITE`, `REP_REFLOT`,	`REP_DATEPEREMP`, `REP_PV`, `REP_PA`, `REP_PR`, `REP_MONLOT`) VALUES ('".addslashes($row['CODE_DETREPORT'])."','".addslashes($row['CODE_REPORT'])."','".addslashes($row['CODE_PRODUIT'])."', '".addslashes($row['CODE_MAGASIN'])."','".addslashes($row['ID_DETREPORT'])."',	'".addslashes($row['REP_PRDQTE'])."','".addslashes($row['REP_UNITE'])."', '".addslashes($row['REP_REFLOT'])."','".addslashes($row['REP_DATEPEREMP'])."','".addslashes($row['REP_PV'])."','".addslashes($row['REP_PA'])."', '".addslashes($row['REP_PR'])."','".addslashes($row['REP_MONLOT'])."');\r\n";
					$i++;
				}
				array_push($t, "Détails report -> $i");
				//echo 'detreport', $sql, '<br>';
				fwrite($ptFichier,$sql);
			}

			//mouvement
			if(isset($_POST['mouvement']) && $_POST['mouvement']=='mouvement'){

$sqlmouvement = "SELECT * FROM mouvement WHERE CODE_MAGASIN LIKE '".addslashes($_SESSION['GL_USER']['MAGASIN'])."' AND ID_EXERCICE='".addslashes($_SESSION['GL_USER']['EXERCICE'])."';";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}

				$query =  $cnx->prepare($sqlmouvement); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$i=0;
				$sql = '';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					(is_null($row['MVT_QUANTITE']) ? $qte = 'NULL' : $qte = "'".$row['MVT_QUANTITE']."'");
					(is_null($row['MVT_PV']) ? $pv = 'NULL' : $pv = "'".$row['MVT_PV']."'");
					(is_null($row['MVT_PA']) ? $pa = 'NULL' : $pa = "'".$row['MVT_PA']."'");
					(is_null($row['MVT_PR']) ? $pr = 'NULL' : $pr = "'".$row['MVT_PR']."'");

$sql .="INSERT INTO `mouvement` (`CODE_MOUVEMENT`, `ID_EXERCICE`, `CODE_PRODUIT`, `CODE_MAGASIN`, `ID_MOUVEMENT`,`ID_SOURCE`,`MVT_DATE`, `MVT_TIME`, `MVT_QUANTITE`, `MVT_UNITE`, `MVT_NATURE`, `MVT_VALID`, `MVT_DATEVALID`,`MVT_TYPE`, `MVT_REFLOT`, `MVT_DATEPEREMP`, `MVT_PV`, `MVT_PA`, `MVT_PR`, `MVT_MONLOT`) VALUES	('".addslashes($row['CODE_MOUVEMENT'])."', '".addslashes($row['ID_EXERCICE'])."', '".addslashes($row['CODE_PRODUIT'])."','".addslashes($_SESSION['GL_USER']['MAGASIN'])."', '".addslashes($row['ID_MOUVEMENT'])."', '".addslashes($row['ID_SOURCE'])."','".addslashes($row['MVT_DATE'])."', '".addslashes($row['MVT_TIME'])."', $qte,'".addslashes($row['MVT_UNITE'])."', '".addslashes($row['MVT_NATURE'])."', '".addslashes($row['MVT_VALID'])."', '".addslashes($row['MVT_DATEVALID'])."', '".addslashes($row['MVT_TYPE'])."', '".addslashes($row['MVT_REFLOT'])."','".addslashes($row['MVT_DATEPEREMP'])."', $pv, $pa,$pr, '".addslashes($row['MVT_MONLOT'])."');\r\n";
					$i++;
				}
				array_push($t, "Mouvement -> $i");
				//echo 'mouvement', $sql, '<br>';
				fwrite($ptFichier,$sql);
			}
			//LOGS
			if(isset($_POST['logs']) && $_POST['logs']=='logs'){

				$sqllogs = "SELECT * FROM logs;";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}

				$query =  $cnx->prepare($sqllogs); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$i=0;
				$sql = '';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
$sql .="INSERT INTO `logs` (`CODE_LOG`, `LOGIN`, `ID_LOG`, `LOG_DATE`, `LOG_DESCRIP`, `MLLE`) VALUES ('".addslashes($row['CODE_LOG'])."', '".addslashes($row['LOGIN'])."', '".addslashes($row['ID_LOG'])."', '".addslashes($row['LOG_DATE'])."', '".addslashes($row['LOG_DESCRIP'])."', '".addslashes($row['MLLE'])."');\r\n";
					$i++;
				}
				array_push($t, "Logs -> $i");

				//echo 'LOG', $sql, '<br>';
				fwrite($ptFichier,$sql);
			}



			//Fermeture
			fclose($ptFichier);
			$_SESSION['DATA_BAK']['fichier'] = "../download/$ptFichier";
			$_SESSION['DATA_BAK']['table'] =$t;

			header('location:sauvegarde1.php?selectedTab=data&rs=1');
			break;



		case 'excel':
			if(isset($_POST['fichierExcel']) && count($_POST['fichierExcel'])>0){
				if($dossier = opendir('../download')){
					while(false !== ($fichier = readdir($dossier))){
						if($fichier != '.' && $fichier != '..' && $fichier != 'index.php' && in_array($fichier, $_POST['fichierExcel'])){
							unlink('../download/'.$fichier);
						}
					}
				}
				closedir($dossier);
			}

			header('location:fichierExcel.php?selectedTab=data&rs=1');
			break;

		case 'sql':
			if(isset($_POST['fichierDB']) && count($_POST['fichierDB'])>0){
				if($dossier = opendir('../download')){
					while(false !== ($fichier = readdir($dossier))){
						if($fichier != '.' && $fichier != '..' && $fichier != 'index.php' && in_array($fichier, $_POST['fichierDB'])){
							unlink('../download/'.$fichier);
						}
					}
				}
				closedir($dossier);
			}

			header('location:fichierdb.php?selectedTab=data&rs=1');
			break;


		default : ///Nothing
	}
}//Fin if

elseif($myaction !='')
//myaction
switch($myaction){


	default : ///Nothing
		//header('location:../../index.php');

}

elseif($myaction =='' && $do ='') header('location:../../index.php');

?>
