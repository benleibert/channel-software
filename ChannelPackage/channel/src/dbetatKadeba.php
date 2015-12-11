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
(isset($_GET['do']) && $_GET['do']!='' ? $do = $_GET['do'] : $do = '');
(isset($_POST['myaction']) && $_POST['myaction']!='' ? $myaction = $_POST['myaction'] : $myaction = '');
unset($_SESSION['DATA_ETAT']);

if($myaction =='' && $do !=''){
	switch($do){

		case 'fillProvince':

			$list = '<option value="0"></option>';

			//$produit = '<select name="produit[]" name="produit[]" class="formStyle"  multiple="multiple">';

			if(isset($_POST["region"])){
				(isset($_POST["region"]) && $_POST["region"]=='TOUS' ? $where='' : $where=" WHERE province.IDREGION ='".$_POST["region"]."'");
				//SQL
				$sql  = "SELECT * FROM province  $where ORDER BY province.PROVINCE ASC;";
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
					$list .= '<option value="'.$row['IDPROVINCE'].'" >'.stripslashes($row['PROVINCE']).'</option>';
				}
			}
			echo $list.'</select>';
			break;

		case 'fillService':

			$list = '<option value="0"></option>';

			if(isset($_POST["code"]) && $_POST["code"]!='0'){
				//SQL
				$sql  = "SELECT * FROM magasin WHERE IDPROVINCE LIKE '".stripslashes($_POST["code"])."' ORDER BY magasin.SER_NOM ASC;";
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
			echo $list.'</select>';
			break;

		case 'fillCat':
			//FILL WHEN LOAD
			break;

		case 'fillSousCat':

			$list = '<option value="0"></option>
                     <option value="TOUS"><?php echo getlang(234); ?></option>';

			//$produit = '<select name="produit[]" name="produit[]" class="formStyle"  multiple="multiple">';

			if(isset($_POST["categorie"])){
				(isset($_POST["categorie"]) && $_POST["categorie"]=='TOUS' ? $where='' : $where=" WHERE souscategorie.CODE_CATEGORIE ='".$_POST["categorie"]."'");
				//SQL
				$sql  = "SELECT * FROM souscategorie  $where ORDER BY souscategorie.SOUSCAT_LIBELLE ASC;";
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
					$list .= '<option value="'.$row['CODE_SOUSCATEGORIE'].'" >'.stripslashes($row['SOUSCAT_LIBELLE']).'</option>';
				}
			}
			echo $list.'</select>';
			break;

		case 'fillCatProduit':
			$list = '<select name="produit[]" name="produit[]" class="formStyle" size="10"  multiple="multiple">';
			if(isset($_POST["categorie"])){
				(isset($_POST["categorie"]) && $_POST["categorie"]=='TOUS' ? $where='' : $where=" WHERE souscategorie.CODE_CATEGORIE ='".$_POST["categorie"]."'");
				//SQL
				$sql  = "SELECT * FROM produit INNER JOIN souscategorie ON (produit.CODE_SOUSCATEGORIE LIKE souscategorie.CODE_SOUSCATEGORIE)
				$where ORDER BY produit.PRD_LIBELLE ASC;";
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
					$list .= '<option value="'.$row['CODE_PRODUIT'].'" >'.(stripslashes($row['PRD_LIBELLE'])).'</option>';
				}
			}
			echo $list.'</select>';
			break;

		case 'fillSousCatProduit':
			$list = '<select name="produit[]" name="produit[]" class="formStyle" size="10"  multiple="multiple">';
			if(isset($_POST["souscategorie"])){
				(isset($_POST["souscategorie"]) && $_POST["souscategorie"]=='TOUS' ? $where='' : $where=" WHERE produit.CODE_SOUSCATEGORIE ='".$_POST["souscategorie"]."'");
				//SQL
				$sql  = "SELECT * FROM produit	$where ORDER BY produit.PRD_LIBELLE ASC;";
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
					$list .= '<option value="'.$row['CODE_PRODUIT'].'" >'.(stripslashes($row['PRD_LIBELLE'])).'</option>';
				}
			}
			echo $list.'</select>';
			break;



		//Log in User

		case 'next': //Par produit

			$where=" mouvement.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND ";
			$whereAll ="";
			(isset($_POST['exercice']) && $_POST['exercice']!=''	? $where .="mouvement.ID_EXERCICE = '".addslashes(trim($_POST['exercice']))."' AND " 	: $where .="");
			(isset($_POST['datedebut']) && $_POST['datedebut']!=''  ? $where .="mouvement.MVT_DATE <= '".addslashes(mysqlFormat(trim($_POST['datedebut'])))."' AND " 	: $where .="");

			(isset($_POST['produit'])  ?  $produit =$_POST['produit'] : $produit=array());
			(isset($_POST['categorie']) && $_POST['categorie']!='0'	? $categorie = $_POST['categorie'] 	: $categorie 	= '');
			(isset($_POST['souscategorie']) && $_POST['souscategorie']!='0'	? $souscategorie = $_POST['souscategorie'] 	: $souscategorie 	= '');

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}

			$in ='';
			if(count($produit)==0 ){
				//
				if ($categorie=='TOUS'){
					if ($souscategorie!='TOUS' && $souscategorie!='0') {
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit WHERE produit.CODE_SOUSCATEGORIE LIKE '$souscategorie' ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
					else{
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit  ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
				}
				else{
					//Produit
					$sql = "SELECT CODE_PRODUIT FROM produit
					INNER JOIN souscategorie ON (produit.CODE_SOUSCATEGORIE LIKE souscategorie.CODE_SOUSCATEGORIE)
					WHERE souscategorie.CODE_CATEGORIE LIKE '$categorie' ORDER BY PRD_LIBELLE ASC";
					$query =  $cnx->prepare($sql); //Prepare the SQL
					$query->execute(); //Execute prepared SQL => $query
					while($row = $query->fetch(PDO::FETCH_ASSOC)){
						$in .="'".$row['CODE_PRODUIT']."', ";
					}
				}
			}
			elseif(count($produit)>0){
				$in='';
				foreach($produit as $key => $val){
					$in .="'$val', ";
				}
			}

			if($in!=''){
				$in = substr($in,0, strlen($in)-2);
				$in = 'produit.CODE_PRODUIT IN ('.$in.') AND ';
			}
			if($where!=''){
				$where = substr($where,0, strlen($where)-4);
			}
			$whereAll = 'AND '.$in.$where;

			if($in!=''){
				$in = 'WHERE '.substr($in,0, strlen($in)-4);
			}

			$_SESSION['DATA_ETAT']['exercice'] =$_POST['exercice'];
			$_SESSION['DATA_ETAT']['ligne'] =array();
			$_SESSION['DATA_ETAT']['WHERE']= $whereAll;
			$_SESSION['DATA_ETAT']['DATEJ']=$_POST['datedebut'];
			$_SESSION['DATA_ETAT']['DATEF']=$_POST['datefin'];
			$sql = "SELECT * FROM produit  $in ORDER BY PRD_LIBELLE ASC; ";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			$Nbre = 0;
			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				$tProduit = StockProduitPerime($row['CODE_PRODUIT'], $type='E',  $whereAll);
				$qeperime = $tProduit['QTE'];

				$Livraison = StockProduitParNature($row['CODE_PRODUIT'], 'LIVRAISON', $whereAll);

				$bonsortie = StockProduitParNature($row['CODE_PRODUIT'], 'BON DE SORTIE', $whereAll);

				$Declassement = StockProduitParNature($row['CODE_PRODUIT'], 'DECLASSEMENT', $whereAll);

				$transfetEnt = StockProduitParNature($row['CODE_PRODUIT'], 'TRANSFERT ENTRANT', $whereAll);

				$transfetSort = StockProduitParNature($row['CODE_PRODUIT'], 'TRANSFERT SORTANT', $whereAll);

				$reportEntree = StockProduitParNature($row['CODE_PRODUIT'], 'REPORT ENTRANT', $whereAll);

				$reportSortie = StockProduitParNature($row['CODE_PRODUIT'], 'REPORT SORTANT', $whereAll);

				$inventplus = StockProduitParNature($row['CODE_PRODUIT'], 'INVENTAIRE +', $whereAll);

				$inventmoins = StockProduitParNature($row['CODE_PRODUIT'], 'INVENTAIRE -', $whereAll);

				//Declassement
				$PDeclassement = StockProduitParNature($row['CODE_PRODUIT'], 'DECLASSEMENT', $whereAll);

				$entree  = $Livraison['QTE'] +  $reportEntree['QTE'] + $transfetEnt['QTE'];// ENTREE
				$sortie  = $bonsortie['QTE']  + $Declassement['QTE'] + $reportSortie['QTE'] + $transfetSort['QTE'] ; //SORTIE
				$ecart   = $inventmoins['QTE'] + $inventplus['QTE'];
				$rest 	 = $entree - ($sortie) ;

				if ($entree!=0 || $sortie!=0 || $rest!=0) {
					array_push($_SESSION['DATA_ETAT']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>addslashes($row['PRD_LIBELLE']), 'livraison'=>$Livraison['QTE'],
					'qteentre'=>$entree,'transfertsort'=>$transfetSort['QTE'], 'transfertent'=>$transfetEnt['QTE'], 'bonsortie'=>$bonsortie['QTE'],
					'qteperime'=>$qeperime, 'reportentree'=>$reportEntree['QTE'], 'reportsortie'=>$reportSortie['QTE'],'declass'=>$Declassement['QTE'],
					'qtesortie'=>$sortie, 'ecart'=>$ecart,  'stocks'=>$rest,'unite'=>$row['ID_UNITE']));
					$Nbre++;
				}

			}
			$_SESSION['DATA_ETAT']['nbreLigne'] = $Nbre; //$query->rowCount();
			header('location:etatstock1.php?selectedTab=int');
			break;

		case 'nextcde':

			$where="  mouvement.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND ";
			$whereAll ="";
			(isset($_POST['exercice']) && $_POST['exercice']!=''	? $where .="mouvement.ID_EXERCICE = '".addslashes(trim($_POST['exercice']))."' AND " 	: $where .="");
			(isset($_POST['datedebut']) && $_POST['datedebut']!=''  ? $where .="mouvement.MVT_DATE >= '".addslashes(mysqlFormat(trim($_POST['datedebut'])))."' AND " 	: $where .="");
			(isset($_POST['datefin']) && $_POST['datefin']!=''  ? $where .="mouvement.MVT_DATE <= '".addslashes(mysqlFormat(trim($_POST['datefin'])))."' AND " 	: $where .="");

			(isset($_POST['produit'])  ?  $produit =$_POST['produit'] : $produit=array());
			(isset($_POST['categorie']) && $_POST['categorie']!='0'	? $categorie = $_POST['categorie'] 	: $categorie 	= '');
			(isset($_POST['souscategorie']) && $_POST['souscategorie']!='0'	? $souscategorie = $_POST['souscategorie'] 	: $souscategorie 	= '');

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}

			$in ='';
			if(count($produit)==0 ){
				//
				if ($categorie=='TOUS'){
					if ($souscategorie!='TOUS' && $souscategorie!='0') {
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit WHERE produit.CODE_SOUSCATEGORIE LIKE '$souscategorie' ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
					else{
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit  ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
				}
				else{
					//Produit
					$sql = "SELECT CODE_PRODUIT FROM produit
					INNER JOIN souscategorie ON (produit.CODE_SOUSCATEGORIE LIKE souscategorie.CODE_SOUSCATEGORIE)
					WHERE souscategorie.CODE_CATEGORIE LIKE '$categorie' ORDER BY PRD_LIBELLE ASC";
					$query =  $cnx->prepare($sql); //Prepare the SQL
					$query->execute(); //Execute prepared SQL => $query
					while($row = $query->fetch(PDO::FETCH_ASSOC)){
						$in .="'".$row['CODE_PRODUIT']."', ";
					}
				}
			}
			elseif(count($produit)>0){
				$in='';
				foreach($produit as $key => $val){
					$in .="'$val', ";
				}
			}

			if($in!=''){
				$in = substr($in,0, strlen($in)-2);
				$in = 'mouvement.CODE_PRODUIT IN ('.$in.') AND ';
			}
			if($where!=''){
				$where = substr($where,0, strlen($where)-4);
			}
			$whereAll = 'AND '.$in.$where;

			if($in!=''){
				$in = ' AND '.substr($in,0, strlen($in)-4);
			}

			$_SESSION['DATA_ETAT']['exercice'] =$_POST['exercice'];
			$_SESSION['DATA_ETAT']['ligne'] =array();
			$_SESSION['DATA_ETAT']['WHERE']= $whereAll;
			$_SESSION['DATA_ETAT']['DATEJ']=$_POST['datedebut'];

			$sql = "SELECT * FROM mouvement INNER JOIN produit ON (mouvement.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
			WHERE mouvement.MVT_TYPE LIKE 'E' AND MVT_VALID=1 $in ORDER BY produit.PRD_LIBELLE ASC; ";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			$i = 0;
			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				$qeperime =0;
				$Livraison = StockLotParNature($row['MVT_REFLOT'], 'LIVRAISON', $whereAll);
				($Livraison['MVT_DATEPEREMP']< date('Y-m-d') ? $qeperime += $Livraison['QTE'] : $qeperime +=0);

				$bonsortie = StockLotParNature($row['MVT_REFLOT'], 'BON DE SORTIE', $whereAll);
				($bonsortie['MVT_DATEPEREMP']< date('Y-m-d') ? $qeperime += $bonsortie['QTE'] : $qeperime +=0);

				$Declassement = StockLotParNature($row['MVT_REFLOT'], 'DECLASSEMENT', $whereAll);
				($Declassement['MVT_DATEPEREMP']< date('Y-m-d') ? $qeperime += $Declassement['QTE'] : $qeperime +=0);

				$transfetEnt = StockLotParNature($row['MVT_REFLOT'], 'TRANSFERT ENTRANT', $whereAll);
				($transfetEnt['MVT_DATEPEREMP']< date('Y-m-d') ? $qeperime += $transfetEnt['QTE'] : $qeperime +=0);

				$transfetSort = StockLotParNature($row['MVT_REFLOT'], 'TRANSFERT SORTANT', $whereAll);
				($transfetSort['MVT_DATEPEREMP']< date('Y-m-d') ? $qeperime += $transfetSort['QTE'] : $qeperime +=0);

				$reportEntree = StockLotParNature($row['MVT_REFLOT'], 'REPORT ENTRANT', $whereAll);
				($reportEntree['MVT_DATEPEREMP']< date('Y-m-d') ? $qeperime += $reportEntree['QTE'] : $qeperime +=0);

				$reportSortie = StockLotParNature($row['MVT_REFLOT'], 'REPORT SORTANT', $whereAll);
				($reportSortie['MVT_DATEPEREMP']< date('Y-m-d') ? $qeperime += $reportSortie['QTE'] : $qeperime +=0);

				$inventplus = StockLotParNature($row['MVT_REFLOT'], 'INVENTAIRE +', $whereAll);
				($inventplus['MVT_DATEPEREMP']< date('Y-m-d') ? $qeperime += $inventplus['QTE'] : $qeperime +=0);

				$inventmoins = StockLotParNature($row['MVT_REFLOT'], 'INVENTAIRE -', $whereAll);
				($inventmoins['MVT_DATEPEREMP']< date('Y-m-d') ? $qeperime += $inventmoins['QTE'] : $qeperime +=0);

				$entree  = $Livraison['QTE'] +  $reportEntree['QTE'] + $transfetEnt['QTE'];// ENTREE
				$sortie  = $bonsortie['QTE']  + $Declassement['QTE'] +   $reportSortie['QTE'] + $transfetSort['QTE']  ; //SORTIE
				$ecart   =	$inventmoins['QTE'] + $inventplus['QTE'];
				$rest = $entree - ($sortie) ;

				if($rest<$row['PRD_SEUILMIN']){
					//echo  'Ent'.$entree.' Sort'.$sortie.'<br>';
					array_push($_SESSION['DATA_ETAT']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'reflot'=>$row['MVT_REFLOT'],'produit'=>addslashes($row['PRD_LIBELLE']), 'livraison'=>$Livraison['QTE'],
					'qteentre'=>$entree,'transfertsort'=>$transfetSort['QTE'], 'transfertent'=>$transfetEnt['QTE'],'bonsortie'=>$bonsortie['QTE'],
					'qteperime'=>$qeperime, 'reportentree'=>$reportEntree['QTE'], 'reportsortie'=>$reportSortie['QTE'],'declass'=>$Declassement['QTE'],
					'qtesortie'=>$sortie, 'ecart'=>$ecart, 'stocks'=>$rest,'unite'=>$row['ID_UNITE'] , 'seuilmin'=>$row['PRD_SEUILMIN'] , 'seuilmax'=>$row['PRD_SEUILMAX'] ));
					$i++;
				}
			}
			$_SESSION['DATA_ETAT']['nbreLigne'] = $i; //$query->rowCount();
			//print_r($_SESSION['DATA_ETAT']);
			header('location:prdcommande1.php?selectedTab=int');
		break;


//		case 'nextcde': // produit
//
//			$where=" mouvement.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND ";
//			$whereAll ="";
//			(isset($_POST['exercice']) && $_POST['exercice']!=''	? $where .="mouvement.ID_EXERCICE = '".addslashes(trim($_POST['exercice']))."' AND " 	: $where .="");
//			(isset($_POST['datedebut']) && $_POST['datedebut']!=''  ? $where .="mouvement.MVT_DATE >= '".addslashes(mysqlFormat(trim($_POST['datedebut'])))."' AND " 	: $where .="");
//			(isset($_POST['datefin']) && $_POST['datefin']!=''  ? $where .="mouvement.MVT_DATE <= '".addslashes(mysqlFormat(trim($_POST['datefin'])))."' AND " 	: $where .="");
//
//			(isset($_POST['produit'])  ?  $produit =$_POST['produit'] : $produit=array());
//			(isset($_POST['categorie']) && $_POST['categorie']!='0'	? $categorie = $_POST['categorie'] 	: $categorie 	= '');
//			(isset($_POST['souscategorie']) && $_POST['souscategorie']!='0'	? $souscategorie = $_POST['souscategorie'] 	: $souscategorie 	= '');
//
//			try {
//				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
//			}
//			catch (PDOException $error) { //Treat error
//				//("Erreur de connexion : " . $error->getMessage() );
//				header('location:errorPage.php');
//			}
//
//			$in ='';
//			if(count($produit)==0 ){
//				//
//				if ($categorie=='TOUS'){
//					if ($souscategorie!='TOUS' && $souscategorie!='0') {
//						//Produit
//						$sql = "SELECT CODE_PRODUIT FROM produit WHERE produit.CODE_SOUSCATEGORIE LIKE '$souscategorie' ORDER BY PRD_LIBELLE ASC";
//						$query =  $cnx->prepare($sql); //Prepare the SQL
//						$query->execute(); //Execute prepared SQL => $query
//						while($row = $query->fetch(PDO::FETCH_ASSOC)){
//							$in .="'".$row['CODE_PRODUIT']."', ";
//						}
//					}
//					else{
//						//Produit
//						$sql = "SELECT CODE_PRODUIT FROM produit  ORDER BY PRD_LIBELLE ASC";
//						$query =  $cnx->prepare($sql); //Prepare the SQL
//						$query->execute(); //Execute prepared SQL => $query
//						while($row = $query->fetch(PDO::FETCH_ASSOC)){
//							$in .="'".$row['CODE_PRODUIT']."', ";
//						}
//					}
//				}
//				else{
//					//Produit
//					$sql = "SELECT CODE_PRODUIT FROM produit
//					INNER JOIN souscategorie ON (produit.CODE_SOUSCATEGORIE LIKE souscategorie.CODE_SOUSCATEGORIE)
//					WHERE souscategorie.CODE_CATEGORIE LIKE '$categorie' ORDER BY PRD_LIBELLE ASC";
//					$query =  $cnx->prepare($sql); //Prepare the SQL
//					$query->execute(); //Execute prepared SQL => $query
//					while($row = $query->fetch(PDO::FETCH_ASSOC)){
//						$in .="'".$row['CODE_PRODUIT']."', ";
//					}
//				}
//			}
//			elseif(count($produit)>0){
//				$in='';
//				foreach($produit as $key => $val){
//					$in .="'$val', ";
//				}
//			}
//
//			if($in!=''){
//				$in = substr($in,0, strlen($in)-2);
//				$in = 'produit.CODE_PRODUIT IN ('.$in.') AND ';
//			}
//			if($where!=''){
//				$where = substr($where,0, strlen($where)-4);
//			}
//			$whereAll = 'AND '.$in.$where;
//
//			if($in!=''){
//				$in = 'WHERE '.substr($in,0, strlen($in)-4);
//			}
//
//			$_SESSION['DATA_ETAT']['exercice'] =$_POST['exercice'];
//			$_SESSION['DATA_ETAT']['ligne'] =array();
//			$_SESSION['DATA_ETAT']['WHERE']= $whereAll;
//			$_SESSION['DATA_ETAT']['DATEJ']=$_POST['datedebut'];
//
//			$sql = "SELECT * FROM produit  $in ORDER BY PRD_LIBELLE ASC; ";
//			$query =  $cnx->prepare($sql); //Prepare the SQL
//			$query->execute(); //Execute prepared SQL => $query
//
//			while($row = $query->fetch(PDO::FETCH_ASSOC)){
//				$tProduit = StockProduitPerime($row['CODE_PRODUIT'], $type='E',  $whereAll);
//				$qeperime = $tProduit['QTE'];
//
//				$Livraison = StockProduitParNature($row['CODE_PRODUIT'], 'LIVRAISON', $whereAll);
//
//				$bonsortie = StockProduitParNature($row['CODE_PRODUIT'], 'BON DE SORTIE', $whereAll);
//
//				$Declassement = StockProduitParNature($row['CODE_PRODUIT'], 'DECLASSEMENT', $whereAll);
//
//				$transfetEnt = StockProduitParNature($row['CODE_PRODUIT'], 'TRANSFERT ENTRANT', $whereAll);
//
//				$transfetSort = StockProduitParNature($row['CODE_PRODUIT'], 'TRANSFERT SORTANT', $whereAll);
//
//				$reportEntree = StockProduitParNature($row['CODE_PRODUIT'], 'REPORT ENTRANT', $whereAll);
//
//				$reportSortie = StockProduitParNature($row['CODE_PRODUIT'], 'REPORT SORTANT', $whereAll);
//
//				$inventplus = StockProduitParNature($row['CODE_PRODUIT'], 'INVENTAIRE +', $whereAll);
//
//				$inventmoins = StockProduitParNature($row['CODE_PRODUIT'], 'INVENTAIRE -', $whereAll);
//
//				$entree  = $Livraison['QTE'] +  $reportEntree['QTE'] + $transfetEnt['QTE'];// ENTREE
//				$sortie  = $bonsortie['QTE']  + $Declassement['QTE'] + $reportSortie['QTE'] + $transfetSort['QTE'] ; //SORTIE
//				$ecart   = $inventmoins['QTE'] + $inventplus['QTE'];
//				$rest 	 = $entree - ($sortie);
//
//				//Commande en cours
//				$qcde =  commandeEnCoursProduit($_SESSION['GL_USER']['EXERCICE'], $_SESSION['GL_USER']['MAGASIN'], $row['CODE_PRODUIT']); //- livraisonPourProduit($commande, $row['CODE_PRODUIT']);
//				$qteSortieP =  quantiteSortiePeriode($_SESSION['GL_USER']['EXERCICE'], $_SESSION['GL_USER']['MAGASIN'], $row['CODE_PRODUIT'], $_POST['datedebut'],$_POST['datefin']);
//
//				//echo  'Ent'.$entree.' Sort'.$sortie.'<br>';
//				array_push($_SESSION['DATA_ETAT']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>addslashes($row['PRD_LIBELLE']), 'livraison'=>$Livraison['QTE'],
//				'qteentre'=>$entree,'transfertsort'=>$transfetSort['QTE'], 'transfertent'=>$transfetEnt['QTE'], 'bonsortie'=>$bonsortie['QTE'],
//				'qteperime'=>$qeperime, 'reportentree'=>$reportEntree['QTE'], 'reportsortie'=>$reportSortie['QTE'],'declass'=>$Declassement['QTE'],
//				'qtesortie'=>$sortie, 'qtecommande'=> $qcde, 'ecart'=>$ecart, 'stocks'=>$rest, 'qtesortiep'=>$qteSortieP,'stockMax'=>$row['PRD_SEUILMAX'],  'unite'=>$row['ID_UNITE']));
//			}
//			$_SESSION['DATA_ETAT']['nbreLigne'] =$query->rowCount();
//			header('location:prdcommande1.php?selectedTab=int');
//			break;

		case 'nextlot':

			$where="  mouvement.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND ";
			$whereAll ="";
			(isset($_POST['exercice']) && $_POST['exercice']!=''	? $where .="mouvement.ID_EXERCICE = '".addslashes(trim($_POST['exercice']))."' AND " 	: $where .="");
			(isset($_POST['datedebut']) && $_POST['datedebut']!=''  ? $where .="mouvement.MVT_DATE <= '".addslashes(mysqlFormat(trim($_POST['datedebut'])))."' AND " 	: $where .="");

			(isset($_POST['produit'])  ?  $produit =$_POST['produit'] : $produit=array());
			(isset($_POST['categorie']) && $_POST['categorie']!='0'	? $categorie = $_POST['categorie'] 	: $categorie 	= '');
			(isset($_POST['souscategorie']) && $_POST['souscategorie']!='0'	? $souscategorie = $_POST['souscategorie'] 	: $souscategorie 	= '');

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}

			$in ='';
			if(count($produit)==0 ){
				//
				if ($categorie=='TOUS'){
					if ($souscategorie!='TOUS' && $souscategorie!='0') {
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit WHERE produit.CODE_SOUSCATEGORIE LIKE '$souscategorie' ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
					else{
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit  ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
				}
				else{
					//Produit
					$sql = "SELECT CODE_PRODUIT FROM produit
					INNER JOIN souscategorie ON (produit.CODE_SOUSCATEGORIE LIKE souscategorie.CODE_SOUSCATEGORIE)
					WHERE souscategorie.CODE_CATEGORIE LIKE '$categorie' ORDER BY PRD_LIBELLE ASC";
					$query =  $cnx->prepare($sql); //Prepare the SQL
					$query->execute(); //Execute prepared SQL => $query
					while($row = $query->fetch(PDO::FETCH_ASSOC)){
						$in .="'".$row['CODE_PRODUIT']."', ";
					}
				}
			}
			elseif(count($produit)>0){
				$in='';
				foreach($produit as $key => $val){
					$in .="'$val', ";
				}
			}

			if($in!=''){
				$in = substr($in,0, strlen($in)-2);
				$in = 'produit.CODE_PRODUIT IN ('.$in.') AND ';
			}
			if($where!=''){
				$where = substr($where,0, strlen($where)-4);
			}
			$whereAll = 'AND '.$in.$where;

			if($in!=''){
				$in = ' AND '.substr($in,0, strlen($in)-4);
			}

			$_SESSION['DATA_ETAT']['exercice'] =$_POST['exercice'];
			$_SESSION['DATA_ETAT']['ligne'] =array();
			$_SESSION['DATA_ETAT']['WHERE']= $whereAll;
			$_SESSION['DATA_ETAT']['DATEJ']=$_POST['datedebut'];

			echo $sql = "SELECT * FROM mouvement INNER JOIN produit ON (mouvement.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
			WHERE mouvement.MVT_TYPE LIKE 'E' AND MVT_VALID=1  $whereAll GROUP BY mouvement.MVT_REFLOT ORDER BY produit.PRD_LIBELLE ASC; ";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			$z = 0;
			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				$qeperime =0;
				$Livraison = StockLotParNature($row['MVT_REFLOT'], 'LIVRAISON', $whereAll);
				($Livraison['MVT_DATEPEREMP']< date('Y-m-d') ? $qeperime += $Livraison['QTE'] : $qeperime +=0);

				$bonsortie = StockLotParNature($row['MVT_REFLOT'], 'BON DE SORTIE', $whereAll);
				($bonsortie['MVT_DATEPEREMP']< date('Y-m-d') ? $qeperime += $bonsortie['QTE'] : $qeperime +=0);

				$Declassement = StockLotParNature($row['MVT_REFLOT'], 'DECLASSEMENT', $whereAll);
				($Declassement['MVT_DATEPEREMP']< date('Y-m-d') ? $qeperime += $Declassement['QTE'] : $qeperime +=0);

				$transfetEnt = StockLotParNature($row['MVT_REFLOT'], 'TRANSFERT ENTRANT', $whereAll);
				($transfetEnt['MVT_DATEPEREMP']< date('Y-m-d') ? $qeperime += $transfetEnt['QTE'] : $qeperime +=0);

				$transfetSort = StockLotParNature($row['MVT_REFLOT'], 'TRANSFERT SORTANT', $whereAll);
				($transfetSort['MVT_DATEPEREMP']< date('Y-m-d') ? $qeperime += $transfetSort['QTE'] : $qeperime +=0);

				$reportEntree = StockLotParNature($row['MVT_REFLOT'], 'REPORT ENTRANT', $whereAll);
				($reportEntree['MVT_DATEPEREMP']< date('Y-m-d') ? $qeperime += $reportEntree['QTE'] : $qeperime +=0);

				$reportSortie = StockLotParNature($row['MVT_REFLOT'], 'REPORT SORTANT', $whereAll);
				($reportSortie['MVT_DATEPEREMP']< date('Y-m-d') ? $qeperime += $reportSortie['QTE'] : $qeperime +=0);

				$inventplus = StockLotParNature($row['MVT_REFLOT'], 'INVENTAIRE +', $whereAll);
				($inventplus['MVT_DATEPEREMP']< date('Y-m-d') ? $qeperime += $inventplus['QTE'] : $qeperime +=0);

				$inventmoins = StockLotParNature($row['MVT_REFLOT'], 'INVENTAIRE -', $whereAll);
				($inventmoins['MVT_DATEPEREMP']< date('Y-m-d') ? $qeperime += $inventmoins['QTE'] : $qeperime +=0);

				$entree  = $Livraison['QTE'] +  $reportEntree['QTE'] + $transfetEnt['QTE'];// ENTREE
				$sortie  = $bonsortie['QTE']  + $Declassement['QTE'] +   $reportSortie['QTE'] + $transfetSort['QTE']  ; //SORTIE
				$ecart   =	$inventmoins['QTE'] + $inventplus['QTE'];
				$rest = $entree - ($sortie) ;

				if ($entree!=0 || $sortie!=0  || $rest!=0) {
					array_push($_SESSION['DATA_ETAT']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'reflot'=>$row['MVT_REFLOT'],'produit'=>addslashes($row['PRD_LIBELLE']), 'livraison'=>$Livraison['QTE'],
					'qteentre'=>$entree,'transfertsort'=>$transfetSort['QTE'], 'transfertent'=>$transfetEnt['QTE'],'bonsortie'=>$bonsortie['QTE'],
					'qteperime'=>$qeperime, 'reportentree'=>$reportEntree['QTE'], 'reportsortie'=>$reportSortie['QTE'],'declass'=>$Declassement['QTE'],
					'qtesortie'=>$sortie, 'ecart'=>$ecart, 'stocks'=>$rest,'unite'=>$row['ID_UNITE']));
					$z++;
				}

			}
			$_SESSION['DATA_ETAT']['nbreLigne'] = $z; //$query->rowCount();
			header('location:etatstocklots1.php?selectedTab=int');
			break;

		case 'rapprdcommande':

			$where="  mouvement.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND ";
			$whereAll ="";
			(isset($_POST['exercice']) && $_POST['exercice']!=''	? $where .="mouvement.ID_EXERCICE = '".addslashes(trim($_POST['exercice']))."' AND " 	: $where .="");

			(isset($_POST['produit'])  ?  $produit =$_POST['produit'] : $produit=array());
			(isset($_POST['categorie']) && $_POST['categorie']!='0'	? $categorie = $_POST['categorie'] 	: $categorie 	= '');
			(isset($_POST['souscategorie']) && $_POST['souscategorie']!='0'	? $souscategorie = $_POST['souscategorie'] 	: $souscategorie 	= '');

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}

			$in ='';
			if(count($produit)==0 ){
				//
				if ($categorie=='TOUS'){
					if ($souscategorie!='TOUS' && $souscategorie!='0') {
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit WHERE produit.CODE_SOUSCATEGORIE LIKE '$souscategorie' ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
					else{
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit  ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
				}
				else{
					//Produit
					$sql = "SELECT CODE_PRODUIT FROM produit
					INNER JOIN souscategorie ON (produit.CODE_SOUSCATEGORIE LIKE souscategorie.CODE_SOUSCATEGORIE)
					WHERE souscategorie.CODE_CATEGORIE LIKE '$categorie' ORDER BY PRD_LIBELLE ASC";
					$query =  $cnx->prepare($sql); //Prepare the SQL
					$query->execute(); //Execute prepared SQL => $query
					while($row = $query->fetch(PDO::FETCH_ASSOC)){
						$in .="'".$row['CODE_PRODUIT']."', ";
					}
				}
			}
			elseif(count($produit)>0){
				$in='';
				foreach($produit as $key => $val){
					$in .="'$val', ";
				}
			}

			if($in!=''){
				$in = substr($in,0, strlen($in)-2);
				$in = 'produit.CODE_PRODUIT IN ('.$in.') AND ';
			}
			if($where!=''){
				$where = substr($where,0, strlen($where)-4);
			}
			$whereAll = 'AND '.$in.$where;

			if($in!=''){
				$in = '  WHERE '.substr($in,0, strlen($in)-4);
			}

			$_SESSION['DATA_ETAT']['exercice'] =$_POST['exercice'];
			$_SESSION['DATA_ETAT']['ligne'] =array();
			$_SESSION['DATA_ETAT']['WHERE']= $whereAll;

			 $sql = "SELECT * FROM produit  $in ORDER BY PRD_LIBELLE ASC; ";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query


			$i = 0;
			while($row = $query->fetch(PDO::FETCH_ASSOC)){

				$qteEntree = StockProduit($row['CODE_PRODUIT'], 'E', $whereAll);
				$qteSortie = StockProduit($row['CODE_PRODUIT'], 'S', $whereAll);

				$entree  = $qteEntree['QTE'] ;	//ENTREE
				$sortie  = $qteSortie['QTE'] ; 	//SORTIE

				$stock 	 = $entree - $sortie;
				$cmm = CMM($row['CODE_PRODUIT'], $whereAll);

				if ($cmm>0) {
					$moisdispo =$stock/$cmm;
				}
				else {
					$moisdispo ='';
				}

				$seuilMin = $row['PRD_SEUILMIN'];
				$seuilMax = $row['PRD_SEUILMAX'];
				$min = $cmm*$seuilMin;
				$max = $cmm*$seuilMax;
				$qtecde = $max-$stock;

				//echo  'Ent'.$entree.' Sort'.$sortie.'<br>'; 'min'=>($cmm*3), 'max'=>($cmm*6),
				array_push($_SESSION['DATA_ETAT']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'],'produit'=>addslashes($row['PRD_LIBELLE']),
				'cmm'=>$cmm, 'min'=>$min, 'max'=>$max,'stock'=>$stock, 'moisdisp'=>$moisdispo, 'qtecde'=>$qtecde,'unite'=>$row['ID_UNITE'] ));
				$i++;
			//}
			}
			$_SESSION['DATA_ETAT']['nbreLigne'] = $i; //$query->rowCount();
			//print_r($_SESSION['DATA_ETAT']);
			header('location:rapprdcommande1.php?selectedTab=rap');
			break;

		case 'rapdetailentree':

			$where="  mouvement.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND ";
			$whereAll ="";
			(isset($_POST['exercice']) && $_POST['exercice']!=''	? $where .="mouvement.ID_EXERCICE = '".addslashes(trim($_POST['exercice']))."' AND " 	: $where .="");
			(isset($_POST['datedebut']) && $_POST['datedebut']!=''  ? $where .="mouvement.MVT_DATE >= '".addslashes(mysqlFormat(trim($_POST['datedebut'])))."' AND " 	: $where .="");
			(isset($_POST['datefin']) && $_POST['datefin']!=''  ? $where .="mouvement.MVT_DATE <= '".addslashes(mysqlFormat(trim($_POST['datefin'])))."' AND " 	: $where .="");

			(isset($_POST['produit'])  ?  $produit =$_POST['produit'] : $produit=array());
			(isset($_POST['categorie']) && $_POST['categorie']!='0'	? $categorie = $_POST['categorie'] 	: $categorie 	= '');
			(isset($_POST['souscategorie']) && $_POST['souscategorie']!='0'	? $souscategorie = $_POST['souscategorie'] 	: $souscategorie 	= '');

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}

			$in ='';
			if(count($produit)==0 ){
				//
				if ($categorie=='TOUS'){
					if ($souscategorie!='TOUS' && $souscategorie!='0') {
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit WHERE produit.CODE_SOUSCATEGORIE LIKE '$souscategorie' ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
					else{
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit  ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
				}
				else{
					//Produit
					$sql = "SELECT CODE_PRODUIT FROM produit
					INNER JOIN souscategorie ON (produit.CODE_SOUSCATEGORIE LIKE souscategorie.CODE_SOUSCATEGORIE)
					WHERE souscategorie.CODE_CATEGORIE LIKE '$categorie' ORDER BY PRD_LIBELLE ASC";
					$query =  $cnx->prepare($sql); //Prepare the SQL
					$query->execute(); //Execute prepared SQL => $query
					while($row = $query->fetch(PDO::FETCH_ASSOC)){
						$in .="'".$row['CODE_PRODUIT']."', ";
					}
				}
			}
			elseif(count($produit)>0){
				$in='';
				foreach($produit as $key => $val){
					$in .="'$val', ";
				}
			}

			if($in!=''){
				$in = substr($in,0, strlen($in)-2);
				$in = 'mouvement.CODE_PRODUIT IN ('.$in.') AND ';
			}
			if($where!=''){
				$where = substr($where,0, strlen($where)-4);
			}
			$whereAll = 'AND '.$in.$where;

			if($in!=''){
				$in = ' AND '.substr($in,0, strlen($in)-4);
			}

			$_SESSION['DATA_ETAT']['exercice'] =$_POST['exercice'];
			$_SESSION['DATA_ETAT']['ligne'] =array();
			$_SESSION['DATA_ETAT']['WHERE']= $whereAll;
			$_SESSION['DATA_ETAT']['DATEJ']=$_POST['datedebut'];

			$sql = "SELECT * FROM mouvement INNER JOIN produit ON (mouvement.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
			WHERE mouvement.MVT_TYPE LIKE 'E'  $whereAll ORDER BY produit.PRD_LIBELLE ASC; ";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			while($row = $query->fetch(PDO::FETCH_ASSOC)){

				$tperime = StockLotPerime($row['MVT_REFLOT'], $type='E', $whereAll);
				$qteperime = $tperime['QTE'];

				$Livraison = StockLotParNature($row['MVT_REFLOT'], 'LIVRAISON', $whereAll);
				$bonsortie = StockLotParNature($row['MVT_REFLOT'], 'BON DE SORTIE', $whereAll);

				$Declassement = StockLotParNature($row['MVT_REFLOT'], 'DECLASSEMENT', $whereAll);

				$transfetEnt = StockLotParNature($row['MVT_REFLOT'], 'TRANSFERT ENTRANT', $whereAll);
				$transfetSort = StockLotParNature($row['MVT_REFLOT'], 'TRANSFERT SORTANT', $whereAll);

				$reportEntree = StockLotParNature($row['MVT_REFLOT'], 'REPORT ENTRANT', $whereAll);
				$reportSortie = StockLotParNature($row['MVT_REFLOT'], 'REPORT SORTANT', $whereAll);

				$inventplus = StockLotParNature($row['MVT_REFLOT'], 'INVENTAIRE +', $whereAll);
				$inventmoins = StockLotParNature($row['MVT_REFLOT'], 'INVENTAIRE -', $whereAll);

				$entree  = $Livraison['QTE'] +  $reportEntree['QTE'] + $transfetEnt['QTE'];// ENTREE
				$sortie  = $bonsortie['QTE']  + $Declassement['QTE'] +   $reportSortie['QTE'] + $transfetSort['QTE'] ; //SORTIE
				$ecart   =	$inventmoins['QTE'] + $inventplus['QTE'];
				$rest = $entree - ($sortie) ;

				//echo  'Ent'.$entree.' Sort'.$sortie.'<br>';
				array_push($_SESSION['DATA_ETAT']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'reflot'=>$row['MVT_REFLOT'],'produit'=>addslashes($row['PRD_LIBELLE']),
				'nature'=>$row['MVT_NATURE'],'livraison'=>$Livraison['QTE'],'dateentree'=>frFormat2($row['MVT_DATE']),'dateperemp'=>$row['MVT_DATEPEREMP'],'pa'=>$row['MVT_PA'],'pv'=>$row['MVT_PV'],
				'qteentre'=>$entree,'transfertsort'=>$transfetSort['QTE'], 'transfertent'=>$transfetEnt['QTE'],'bonsortie'=>$bonsortie['QTE'],
				'reportentree'=>$reportEntree['QTE'], 'reportsortie'=>$reportSortie['QTE'],'declass'=>$Declassement['QTE'],
				'qtesortie'=>$sortie, 'ecart'=>$ecart, 'perime'=>$qteperime, 'stocks'=>$rest,'unite'=>$row['ID_UNITE']));
			}
			$_SESSION['DATA_ETAT']['nbreLigne'] =$query->rowCount();
			//print_r($_SESSION['DATA_ETAT']);
			header('location:rapdetailentree1.php?selectedTab=rap');
			break;

		case 'rapdetaillesortie':
			$where=" mouvement.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND mouvement.MVT_TYPE LIKE 'S' AND ";
			(isset($_POST['exercice']) && $_POST['exercice']!=''	? $where .="mouvement.ID_EXERCICE = '".addslashes(trim($_POST['exercice']))."' AND " 	: $where .="");
			(isset($_POST['datedebut']) && $_POST['datedebut']!=''  ? $where .="mouvement.MVT_DATE >= '".addslashes(mysqlFormat(trim($_POST['datedebut'])))."' AND " 	: $where .="");
			(isset($_POST['datefin']) && $_POST['datefin']!=''  ? $where .="mouvement.MVT_DATE <= '".addslashes(mysqlFormat(trim($_POST['datefin'])))."' AND " 	: $where .="");

			(isset($_POST['produit'])  ?  $produit =$_POST['produit'] : $produit=array());
			(isset($_POST['categorie']) && $_POST['categorie']!='0'	? $categorie = $_POST['categorie'] 	: $categorie 	= '');
			(isset($_POST['souscategorie']) && $_POST['souscategorie']!='0'	? $souscategorie = $_POST['souscategorie'] 	: $souscategorie 	= '');

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}

			$in ='';
			if(count($produit)==0 ){
				//
				if ($categorie=='TOUS'){
					if ($souscategorie!='TOUS' && $souscategorie!='0') {
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit WHERE produit.CODE_SOUSCATEGORIE LIKE '$souscategorie' ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
					else{
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit  ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
				}
				else{
					//Produit
					$sql = "SELECT CODE_PRODUIT FROM produit
					INNER JOIN souscategorie ON (produit.CODE_SOUSCATEGORIE LIKE souscategorie.CODE_SOUSCATEGORIE)
					WHERE souscategorie.CODE_CATEGORIE LIKE '$categorie' ORDER BY PRD_LIBELLE ASC";
					$query =  $cnx->prepare($sql); //Prepare the SQL
					$query->execute(); //Execute prepared SQL => $query
					while($row = $query->fetch(PDO::FETCH_ASSOC)){
						$in .="'".$row['CODE_PRODUIT']."', ";
					}
				}
			}
			elseif(count($produit)>0){
				$in='';
				foreach($produit as $key => $val){
					$in .="'$val', ";
				}
			}

			if($in!=''){
				$in = substr($in,0, strlen($in)-2);
				$in = 'mouvement.CODE_PRODUIT IN ('.$in.') AND ';
			}
			if($where!=''){
				$where = substr($where,0, strlen($where)-4);
			}
			$whereAll = 'WHERE '.$in.$where;

			$_SESSION['DATA_ETAT'] =array();
			$_SESSION['DATA_ETAT']['WHERE']= $whereAll;
			$_SESSION['DATA_ETAT']['DATEJ']=$_POST['datedebut'];

			header('location:rapdetsortie1.php?selectedTab=rap');
			break;

		case 'rapsyntheseinventaire':

			$where=" mouvement.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND ";
			$whereAll ="";
			(isset($_POST['exercice']) && $_POST['exercice']!=''	? $where .="mouvement.ID_EXERCICE = '".addslashes(trim($_POST['exercice']))."' AND " 	: $where .="");
			(isset($_POST['datedebut']) && $_POST['datedebut']!=''  ? $where .="mouvement.MVT_DATE <= '".addslashes(mysqlFormat(trim($_POST['datedebut'])))."' AND " 	: $where .="");

			(isset($_POST['produit'])  ?  $produit =$_POST['produit'] : $produit=array());
			(isset($_POST['categorie']) && $_POST['categorie']!='0'	? $categorie = $_POST['categorie'] 	: $categorie 	= '');
			(isset($_POST['souscategorie']) && $_POST['souscategorie']!='0'	? $souscategorie = $_POST['souscategorie'] 	: $souscategorie 	= '');

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}

			$in ='';
			if(count($produit)==0 ){
				//
				if ($categorie=='TOUS'){
					if ($souscategorie!='TOUS' && $souscategorie!='0') {
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit WHERE produit.CODE_SOUSCATEGORIE LIKE '$souscategorie' ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
					else{
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit  ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
				}
				else{
					//Produit
					$sql = "SELECT CODE_PRODUIT FROM produit
					INNER JOIN souscategorie ON (produit.CODE_SOUSCATEGORIE LIKE souscategorie.CODE_SOUSCATEGORIE)
					WHERE souscategorie.CODE_CATEGORIE LIKE '$categorie' ORDER BY PRD_LIBELLE ASC";
					$query =  $cnx->prepare($sql); //Prepare the SQL
					$query->execute(); //Execute prepared SQL => $query
					while($row = $query->fetch(PDO::FETCH_ASSOC)){
						$in .="'".$row['CODE_PRODUIT']."', ";
					}
				}
			}
			elseif(count($produit)>0){
				$in='';
				foreach($produit as $key => $val){
					$in .="'$val', ";
				}
			}

			if($in!=''){
				$in = substr($in,0, strlen($in)-2);
				$in = 'produit.CODE_PRODUIT IN ('.$in.') AND ';
			}
			if($where!=''){
				$where = substr($where,0, strlen($where)-4);
			}
			$whereAll = 'AND '.$in.$where;

			if($in!=''){
				$in = 'WHERE '.substr($in,0, strlen($in)-4);
			}

			$_SESSION['DATA_ETAT']['exercice'] =$_POST['exercice'];
			$_SESSION['DATA_ETAT']['ligne'] =array();
			$_SESSION['DATA_ETAT']['WHERE']= $whereAll;
			$_SESSION['DATA_ETAT']['DATEJ']=$_POST['datedebut'];

			$sql = "SELECT * FROM produit  $in ORDER BY PRD_LIBELLE ASC; ";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			while($row = $query->fetch(PDO::FETCH_ASSOC)){

				$qteEntree = StockProduit($row['CODE_PRODUIT'], 'E', $whereAll);
				$qteSortie = StockProduit($row['CODE_PRODUIT'], 'S', $whereAll);
				$tProduit = StockProduit($row['CODE_PRODUIT'], 'E',  $whereAll.' AND MVT_DATEPEREMP<NOW() ');

				$entree  = $qteEntree['QTE'] ;// ENTREE
				$sortie  = $qteSortie['QTE'] ; //SORTIE
				$perime   = $tProduit['QTE'] ;
				$rest 	 = $entree - ($sortie + $qeperime);


				//echo  'Ent'.$entree.' Sort'.$sortie.'<br>';
				array_push($_SESSION['DATA_ETAT']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>addslashes($row['PRD_LIBELLE']), 'livraison'=>$Livraison['QTE'],
				'qteentree'=>$entree, 'qtesortie'=>$sortie, 'qteperime'=>$perime, 'stocks'=>$rest,'unite'=>$row['ID_UNITE'],'seuilmax'=>$row['PRD_SEUILMAX'],'seuilmin'=>$row['PRD_SEUILMIN']));
			}
			$_SESSION['DATA_ETAT']['nbreLigne'] =$query->rowCount();
			header('location:rapsyntheseinventaire1.php?selectedTab=int');
			break;

		case 'rapmvtdestinaire':

			$where="  mouvement.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND ";
			$whereAll ="";
			(isset($_POST['exercice']) && $_POST['exercice']!=''	? $where .="mouvement.ID_EXERCICE = '".addslashes(trim($_POST['exercice']))."' AND " 	: $where .="");
			(isset($_POST['datedebut']) && $_POST['datedebut']!=''  ? $where .="mouvement.MVT_DATE >= '".addslashes(mysqlFormat(trim($_POST['datedebut'])))."' AND " 	: $where .="");
			(isset($_POST['datefin']) && $_POST['datefin']!=''  ? $where .="mouvement.MVT_DATE <= '".addslashes(mysqlFormat(trim($_POST['datefin'])))."' AND " 	: $where .="");

			(isset($_POST['produit'])  ?  $produit =$_POST['produit'] : $produit=array());
			(isset($_POST['categorie']) && $_POST['categorie']!='0'	? $categorie = $_POST['categorie'] 	: $categorie 	= '');
			(isset($_POST['souscategorie']) && $_POST['souscategorie']!='0'	? $souscategorie = $_POST['souscategorie'] 	: $souscategorie 	= '');
			(isset($_POST['beneficiaire']) && $_POST['beneficiaire']!=''	? $beneficiaire = $_POST['beneficiaire'] 	: $beneficiaire 	= '');
			(isset($_POST['idbeneficiaire']) && $_POST['idbeneficiaire']!=''	? $idbeneficiaire = $_POST['idbeneficiaire'] 	: $idbeneficiaire 	= '');

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}

			$in ='';
			if(count($produit)==0 ){
				//
				if ($categorie=='TOUS'){
					if ($souscategorie!='TOUS' && $souscategorie!='0') {
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit WHERE produit.CODE_SOUSCATEGORIE LIKE '$souscategorie' ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
					else{
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit  ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
				}
				else{
					//Produit
					$sql = "SELECT CODE_PRODUIT FROM produit
					INNER JOIN souscategorie ON (produit.CODE_SOUSCATEGORIE LIKE souscategorie.CODE_SOUSCATEGORIE)
					WHERE souscategorie.CODE_CATEGORIE LIKE '$categorie' ORDER BY PRD_LIBELLE ASC";
					$query =  $cnx->prepare($sql); //Prepare the SQL
					$query->execute(); //Execute prepared SQL => $query
					while($row = $query->fetch(PDO::FETCH_ASSOC)){
						$in .="'".$row['CODE_PRODUIT']."', ";
					}
				}
			}
			elseif(count($produit)>0){
				$in='';
				foreach($produit as $key => $val){
					$in .="'$val', ";
				}
			}

			if($in!=''){
				$in = substr($in,0, strlen($in)-2);
				$in = 'mouvement.CODE_PRODUIT IN ('.$in.') AND ';
			}
			if($where!=''){
				$where = substr($where,0, strlen($where)-4);
			}
			$whereAll = 'AND '.$in.$where;

			if($in!=''){
				$in = ' AND '.substr($in,0, strlen($in)-4);
			}

			$_SESSION['DATA_ETAT']['exercice'] =$_POST['exercice'];
			$_SESSION['DATA_ETAT']['ligne'] =array();
			$_SESSION['DATA_ETAT']['WHERE']= $whereAll;
			$_SESSION['DATA_ETAT']['DATEJ']=$_POST['datedebut'];
			$_SESSION['DATA_ETAT']['beneficiaire']=$beneficiaire;
			$_SESSION['DATA_ETAT']['idbeneficiaire']=$idbeneficiaire;

			$sql = "SELECT * FROM mouvement INNER JOIN produit ON (mouvement.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
			INNER JOIN bonsortie ON (mouvement.ID_SOURCE=bonsortie.ID_BONSORTIE)
			WHERE mouvement.MVT_TYPE LIKE 'S' AND MVT_NATURE LIKE 'BON DE SORTIE' AND bonsortie.CODE_BENEF='$idbeneficiaire'
			$whereAll ORDER BY produit.PRD_LIBELLE ASC; ";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				//echo  'Ent'.$entree.' Sort'.$sortie.'<br>';
				array_push($_SESSION['DATA_ETAT']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'reflot'=>$row['MVT_REFLOT'],'produit'=>addslashes($row['PRD_LIBELLE']),
				'dateperemp'=>$row['MVT_DATEPEREMP'],'datesortie'=>$row['MVT_DATE'],'datevalid'=>$row['MVT_DATEVALID'],'nature'=>$row['MVT_NATURE'],'qte'=>$row['MVT_QUANTITE'],'unite'=>$row['ID_UNITE']));
			}
			$_SESSION['DATA_ETAT']['nbreLigne'] =$query->rowCount();
			//print_r($_SESSION['DATA_ETAT']);
			header('location:rapmvtdestinaire1.php?selectedTab=rap');
			break;

		case 'rapmvtfournisseur':

			$where="  mouvement.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND ";
			$whereAll ="";
			(isset($_POST['exercice']) && $_POST['exercice']!=''	? $where .="mouvement.ID_EXERCICE = '".addslashes(trim($_POST['exercice']))."' AND " 	: $where .="");
			(isset($_POST['datedebut']) && $_POST['datedebut']!=''  ? $where .="mouvement.MVT_DATE >= '".addslashes(mysqlFormat(trim($_POST['datedebut'])))."' AND " 	: $where .="");
			(isset($_POST['datefin']) && $_POST['datefin']!=''  ? $where .="mouvement.MVT_DATE <= '".addslashes(mysqlFormat(trim($_POST['datefin'])))."' AND " 	: $where .="");

			(isset($_POST['produit'])  ?  $produit =$_POST['produit'] : $produit=array());
			(isset($_POST['categorie']) && $_POST['categorie']!='0'	? $categorie = $_POST['categorie'] 	: $categorie 	= '');
			(isset($_POST['souscategorie']) && $_POST['souscategorie']!='0'	? $souscategorie = $_POST['souscategorie'] 	: $souscategorie 	= '');
			(isset($_POST['fournisseur']) && $_POST['fournisseur']!=''	? $fournisseur = $_POST['fournisseur'] 	: $fournisseur 	= '');
			(isset($_POST['idfournisseur']) && $_POST['idfournisseur']!=''	? $idfournisseur = $_POST['idfournisseur'] 	: $idfournisseur 	= '');

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}

			$in ='';
			if(count($produit)==0 ){
				//
				if ($categorie=='TOUS'){
					if ($souscategorie!='TOUS' && $souscategorie!='0') {
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit WHERE produit.CODE_SOUSCATEGORIE LIKE '$souscategorie' ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
					else{
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit  ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
				}
				else{
					//Produit
					$sql = "SELECT CODE_PRODUIT FROM produit
					INNER JOIN souscategorie ON (produit.CODE_SOUSCATEGORIE LIKE souscategorie.CODE_SOUSCATEGORIE)
					WHERE souscategorie.CODE_CATEGORIE LIKE '$categorie' ORDER BY PRD_LIBELLE ASC";
					$query =  $cnx->prepare($sql); //Prepare the SQL
					$query->execute(); //Execute prepared SQL => $query
					while($row = $query->fetch(PDO::FETCH_ASSOC)){
						$in .="'".$row['CODE_PRODUIT']."', ";
					}
				}
			}
			elseif(count($produit)>0){
				$in='';
				foreach($produit as $key => $val){
					$in .="'$val', ";
				}
			}

			if($in!=''){
				$in = substr($in,0, strlen($in)-2);
				$in = 'mouvement.CODE_PRODUIT IN ('.$in.') AND ';
			}
			if($where!=''){
				$where = substr($where,0, strlen($where)-4);
			}
			$whereAll = 'AND '.$in.$where;

			if($in!=''){
				$in = ' AND '.substr($in,0, strlen($in)-4);
			}

			$_SESSION['DATA_ETAT']['exercice'] =$_POST['exercice'];
			$_SESSION['DATA_ETAT']['ligne'] =array();
			$_SESSION['DATA_ETAT']['WHERE']= $whereAll;
			$_SESSION['DATA_ETAT']['DATEJ']=$_POST['datedebut'];
			$_SESSION['DATA_ETAT']['fournisseur']=$fournisseur;
			$_SESSION['DATA_ETAT']['idfournisseur']=$idfournisseur;

			$sql = "SELECT * FROM mouvement INNER JOIN produit ON (mouvement.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
			INNER JOIN livraison ON (mouvement.ID_SOURCE=livraison.ID_LIVRAISON)
			WHERE mouvement.MVT_TYPE LIKE 'E' AND mouvement.MVT_VALID=1 AND MVT_NATURE LIKE 'LIVRAISON'
			AND livraison.CODE_FOUR='$idfournisseur' $whereAll ORDER BY produit.PRD_LIBELLE ASC; ";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				//echo  'Ent'.$entree.' Sort'.$sortie.'<br>';
				array_push($_SESSION['DATA_ETAT']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'reflot'=>$row['MVT_REFLOT'],'produit'=>addslashes($row['PRD_LIBELLE']),
				'dateperemp'=>$row['MVT_DATEPEREMP'],'dateentre'=>$row['MVT_DATE'],'qte'=>$row['MVT_QUANTITE'],'unite'=>$row['ID_UNITE']));
			}
			$_SESSION['DATA_ETAT']['nbreLigne'] =$query->rowCount();
			//print_r($_SESSION['DATA_ETAT']);
			header('location:rapmvtfournisseur1.php?selectedTab=rap');
			break;

		case 'rapprdperime':

			$where="  mouvement.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND ";
			$whereAll ="";
			(isset($_POST['exercice']) && $_POST['exercice']!=''	? $where .="mouvement.ID_EXERCICE = '".addslashes(trim($_POST['exercice']))."' AND " 	: $where .="");
			(isset($_POST['datedebut']) && $_POST['datedebut']!=''  ? $where .="mouvement.MVT_DATE <= '".addslashes(mysqlFormat(trim($_POST['datedebut'])))."' AND " 	: $where .="");

			(isset($_POST['produit'])  ?  $produit =$_POST['produit'] : $produit=array());
			(isset($_POST['categorie']) && $_POST['categorie']!='0'	? $categorie = $_POST['categorie'] 	: $categorie 	= '');
			(isset($_POST['souscategorie']) && $_POST['souscategorie']!='0'	? $souscategorie = $_POST['souscategorie'] 	: $souscategorie 	= '');

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}

			$in ='';
			if(count($produit)==0 ){
				//
				if ($categorie=='TOUS'){
					if ($souscategorie!='TOUS' && $souscategorie!='0') {
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit WHERE produit.CODE_SOUSCATEGORIE LIKE '$souscategorie' ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
					else{
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit  ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
				}
				else{
					//Produit
					$sql = "SELECT CODE_PRODUIT FROM produit
					INNER JOIN souscategorie ON (produit.CODE_SOUSCATEGORIE LIKE souscategorie.CODE_SOUSCATEGORIE)
					WHERE souscategorie.CODE_CATEGORIE LIKE '$categorie' ORDER BY PRD_LIBELLE ASC";
					$query =  $cnx->prepare($sql); //Prepare the SQL
					$query->execute(); //Execute prepared SQL => $query
					while($row = $query->fetch(PDO::FETCH_ASSOC)){
						$in .="'".$row['CODE_PRODUIT']."', ";
					}
				}
			}
			elseif(count($produit)>0){
				$in='';
				foreach($produit as $key => $val){
					$in .="'$val', ";
				}
			}

			if($in!=''){
				$in = substr($in,0, strlen($in)-2);
				$in = 'mouvement.CODE_PRODUIT IN ('.$in.') AND ';
			}
			if($where!=''){
				$where = substr($where,0, strlen($where)-4);
			}
			$whereAll = 'AND '.$in.$where;

			if($in!=''){
				$in = ' AND '.substr($in,0, strlen($in)-4);
			}

			$_SESSION['DATA_ETAT']['exercice'] =$_POST['exercice'];
			$_SESSION['DATA_ETAT']['ligne'] =array();
			$_SESSION['DATA_ETAT']['WHERE']= $whereAll;
			$_SESSION['DATA_ETAT']['DATEJ']=$_POST['datedebut'];

			$sql = "SELECT * FROM mouvement INNER JOIN produit ON (mouvement.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
			WHERE mouvement.MVT_TYPE LIKE 'E' AND  MVT_DATEPEREMP<NOW() $in ORDER BY produit.PRD_LIBELLE ASC; ";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				array_push($_SESSION['DATA_ETAT']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'reflot'=>$row['MVT_REFLOT'],'produit'=>addslashes($row['PRD_LIBELLE']),
				'dateperemp'=>$row['MVT_DATEPEREMP'],'qte'=>$row['MVT_QUANTITE'],'dateentree'=>$row['MVT_DATE'],'unite'=>$row['ID_UNITE']));

			}
			$_SESSION['DATA_ETAT']['nbreLigne'] =$query->rowCount();
			//print_r($_SESSION['DATA_ETAT']);
			header('location:rapprdperime1.php?selectedTab=rap');
			break;

		case 'rapmvtstock':

			$where="  mouvement.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND ";
			$whereAll ="";
			(isset($_POST['exercice']) && $_POST['exercice']!=''	? $where .="mouvement.ID_EXERCICE = '".addslashes(trim($_POST['exercice']))."' AND " 	: $where .="");
			(isset($_POST['datedebut']) && $_POST['datedebut']!=''  ? $where .="mouvement.MVT_DATE >= '".addslashes(mysqlFormat(trim($_POST['datedebut'])))."' AND " 	: $where .="");
			(isset($_POST['datefin']) && $_POST['datefin']!=''  ? $where .="mouvement.MVT_DATE <= '".addslashes(mysqlFormat(trim($_POST['datefin'])))."' AND " 	: $where .="");

			(isset($_POST['produit'])  ?  $produit =$_POST['produit'] : $produit=array());
			(isset($_POST['categorie']) && $_POST['categorie']!='0'	? $categorie = $_POST['categorie'] 	: $categorie 	= '');
			(isset($_POST['souscategorie']) && $_POST['souscategorie']!='0'	? $souscategorie = $_POST['souscategorie'] 	: $souscategorie 	= '');

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}

			$in ='';
			if(count($produit)==0 ){
				//
				if ($categorie=='TOUS'){
					if ($souscategorie!='TOUS' && $souscategorie!='0') {
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit WHERE produit.CODE_SOUSCATEGORIE LIKE '$souscategorie' ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
					else{
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit  ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
				}
				else{
					//Produit
					$sql = "SELECT CODE_PRODUIT FROM produit
					INNER JOIN souscategorie ON (produit.CODE_SOUSCATEGORIE LIKE souscategorie.CODE_SOUSCATEGORIE)
					WHERE souscategorie.CODE_CATEGORIE LIKE '$categorie' ORDER BY PRD_LIBELLE ASC";
					$query =  $cnx->prepare($sql); //Prepare the SQL
					$query->execute(); //Execute prepared SQL => $query
					while($row = $query->fetch(PDO::FETCH_ASSOC)){
						$in .="'".$row['CODE_PRODUIT']."', ";
					}
				}
			}
			elseif(count($produit)>0){
				$in='';
				foreach($produit as $key => $val){
					$in .="'$val', ";
				}
			}

			if($in!=''){
				$in = substr($in,0, strlen($in)-2);
				$in = 'produit.CODE_PRODUIT IN ('.$in.') AND ';
			}
			if($where!=''){
				$where = substr($where,0, strlen($where)-4);
			}
			$whereAll = 'AND '.$in.$where;

			if($in!=''){
				$in = ' WHERE '.substr($in,0, strlen($in)-4);
			}

			$_SESSION['DATA_ETAT']['exercice'] =$_POST['exercice'];
			$_SESSION['DATA_ETAT']['ligne'] =array();
			$_SESSION['DATA_ETAT']['WHERE']= $whereAll;
			$_SESSION['DATA_ETAT']['DATEJ']=$_POST['datedebut'];

			//$sql = "SELECT * FROM mouvement INNER JOIN produit ON (produit.CODE_PRODUIT LIKE mouvement.CODE_PRODUIT)

			$sql = "SELECT * FROM produit  $in ORDER BY produit.PRD_LIBELLE ASC; ";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			while($row = $query->fetch(PDO::FETCH_ASSOC)){

				$Livraison = StockProduitParNature($row['CODE_PRODUIT'], 'LIVRAISON', $whereAll);
				$bonsortie = StockProduitParNature($row['CODE_PRODUIT'], 'BON DE SORTIE', $whereAll);

				$Declassement = StockProduitParNature($row['CODE_PRODUIT'], 'DECLASSEMENT', $whereAll);

				$transfetEnt = StockProduitParNature($row['CODE_PRODUIT'], 'TRANSFERT ENTRANT', $whereAll);
				$transfetSort = StockProduitParNature($row['CODE_PRODUIT'], 'TRANSFERT SORTANT', $whereAll);

				$reportEntree = StockProduitParNature($row['CODE_PRODUIT'], 'REPORT ENTRANT', $whereAll);
				$reportSortie = StockProduitParNature($row['CODE_PRODUIT'], 'REPORT SORTANT', $whereAll);

				$inventplus = StockLotParNature($row['CODE_PRODUIT'], 'INVENTAIRE +', $whereAll);
				$inventmoins = StockLotParNature($row['CODE_PRODUIT'], 'INVENTAIRE -', $whereAll);

				$entree  = $Livraison['QTE'] +  $reportEntree['QTE'] + $transfetEnt['QTE'];// ENTREE
				$sortie  = $bonsortie['QTE']  + $Declassement['QTE'] +   $reportSortie['QTE'] + $transfetSort['QTE'] ; //SORTIE
				$ecart   =	$inventmoins['QTE'] + $inventplus['QTE'];
				$rest = $entree - ($sortie);

				//echo  'Ent'.$entree.' Sort'.$sortie.'<br>';
				array_push($_SESSION['DATA_ETAT']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>addslashes($row['PRD_LIBELLE']),
				'qteentree'=>$entree,'qtesortie'=>$sortie, 'ecart'=>$ecart, 'stocks'=>$rest,'unite'=>$row['ID_UNITE']));
			}
			$_SESSION['DATA_ETAT']['nbreLigne'] =$query->rowCount();
			//print_r($_SESSION['DATA_ETAT']);
			header('location:rapmvtstock1.php?selectedTab=rap');
			break;

		case 'rapsortiemensuelle':

			$where="  mouvement.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND ";
			$whereAll ="";
			(isset($_POST['exercice']) && $_POST['exercice']!=''	? $where .="mouvement.ID_EXERCICE = '".addslashes(trim($_POST['exercice']))."' AND " 	: $where .="");
			//(isset($_POST['datedebut']) && $_POST['datedebut']!=''  ? $where .="mouvement.MVT_DATE <= '".addslashes(mysqlFormat(trim($_POST['datedebut'])))."' AND " 	: $where .="");

			(isset($_POST['produit'])  ?  $produit =$_POST['produit'] : $produit=array());
			(isset($_POST['categorie']) && $_POST['categorie']!='0'	? $categorie = $_POST['categorie'] 	: $categorie 	= '');
			(isset($_POST['souscategorie']) && $_POST['souscategorie']!='0'	? $souscategorie = $_POST['souscategorie'] 	: $souscategorie 	= '');

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}

			$in ='';
			if(count($produit)==0 ){
				//
				if ($categorie=='TOUS'){
					if ($souscategorie!='TOUS' && $souscategorie!='0') {
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit WHERE produit.CODE_SOUSCATEGORIE LIKE '$souscategorie' ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
					else{
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit  ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
				}
				else{
					//Produit
					$sql = "SELECT CODE_PRODUIT FROM produit
					INNER JOIN souscategorie ON (produit.CODE_SOUSCATEGORIE LIKE souscategorie.CODE_SOUSCATEGORIE)
					WHERE souscategorie.CODE_CATEGORIE LIKE '$categorie' ORDER BY PRD_LIBELLE ASC";
					$query =  $cnx->prepare($sql); //Prepare the SQL
					$query->execute(); //Execute prepared SQL => $query
					while($row = $query->fetch(PDO::FETCH_ASSOC)){
						$in .="'".$row['CODE_PRODUIT']."', ";
					}
				}
			}
			elseif(count($produit)>0){
				$in='';
				foreach($produit as $key => $val){
					$in .="'$val', ";
				}
			}

			if($in!=''){
				$in = substr($in,0, strlen($in)-2);
				$in = 'mouvement.CODE_PRODUIT IN ('.$in.') AND ';
			}
			if($where!=''){
				$where = substr($where,0, strlen($where)-4);
			}
			$whereAll = 'AND '.$in.$where;

			if($in!=''){
				$in = ' WHERE '.substr($in,0, strlen($in)-4);
			}

			$_SESSION['DATA_ETAT']['exercice'] = $_POST['exercice'];
			$_SESSION['DATA_ETAT']['ligne'] =array();
			$_SESSION['DATA_ETAT']['WHERE'] = $whereAll;
			$d1 = date('Y-m-d');
			$d3= date("Y-m-d", mktime(0,0,0,date("m")-3, date("d"), date("y")));
			$d6= date("Y-m-d", mktime(0,0,0,date("m")-6, date("d"), date("y")));
			$d12= date("Y-m-d", mktime(0,0,0,date("m")-12, date("d"), date("y")));

			$sql = "SELECT * FROM produit  $in ORDER BY produit.PRD_LIBELLE ASC; ";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			while($row = $query->fetch(PDO::FETCH_ASSOC)){

				$qte3 = StockProduitPeriode($row['CODE_PRODUIT'], 'S', $d3, $d1, $whereAll);
				$qte6 = StockProduitPeriode($row['CODE_PRODUIT'], 'S', $d6, $d1, $whereAll);
				$qte12 = StockProduitPeriode($row['CODE_PRODUIT'], 'S', $d12, $d1, $whereAll);

				//echo  'Ent'.$entree.' Sort'.$sortie.'<br>';
				array_push($_SESSION['DATA_ETAT']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'reflot'=>$row['MVT_REFLOT'],'produit'=>addslashes($row['PRD_LIBELLE']),
				'qtesortie3'=>$qte3['QTE'], 'qtesortie6'=>$qte6['QTE'],'qtesortie12'=>$qte12['QTE'],'unite'=>$row['ID_UNITE']));
			}
			$_SESSION['DATA_ETAT']['nbreLigne'] =$query->rowCount();
			//print_r($_SESSION['DATA_ETAT']);
			header('location:rapsortiemensuelle1.php?selectedTab=rap');
			break;

		case 'rapsortiemoymensuelle':

			$where="  mouvement.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND ";
			$whereAll ="";
			(isset($_POST['exercice']) && $_POST['exercice']!=''	? $where .="mouvement.ID_EXERCICE = '".addslashes(trim($_POST['exercice']))."' AND " 	: $where .="");
			//(isset($_POST['datedebut']) && $_POST['datedebut']!=''  ? $where .="mouvement.MVT_DATE <= '".addslashes(mysqlFormat(trim($_POST['datedebut'])))."' AND " 	: $where .="");

			(isset($_POST['produit'])  ?  $produit =$_POST['produit'] : $produit=array());
			(isset($_POST['categorie']) && $_POST['categorie']!='0'	? $categorie = $_POST['categorie'] 	: $categorie 	= '');
			(isset($_POST['souscategorie']) && $_POST['souscategorie']!='0'	? $souscategorie = $_POST['souscategorie'] 	: $souscategorie 	= '');

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}

			$in ='';
			if(count($produit)==0 ){
				//
				if ($categorie=='TOUS'){
					if ($souscategorie!='TOUS' && $souscategorie!='0') {
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit WHERE produit.CODE_SOUSCATEGORIE LIKE '$souscategorie' ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
					else{
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit  ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
				}
				else{
					//Produit
					$sql = "SELECT CODE_PRODUIT FROM produit
					INNER JOIN souscategorie ON (produit.CODE_SOUSCATEGORIE LIKE souscategorie.CODE_SOUSCATEGORIE)
					WHERE souscategorie.CODE_CATEGORIE LIKE '$categorie' ORDER BY PRD_LIBELLE ASC";
					$query =  $cnx->prepare($sql); //Prepare the SQL
					$query->execute(); //Execute prepared SQL => $query
					while($row = $query->fetch(PDO::FETCH_ASSOC)){
						$in .="'".$row['CODE_PRODUIT']."', ";
					}
				}
			}
			elseif(count($produit)>0){
				$in='';
				foreach($produit as $key => $val){
					$in .="'$val', ";
				}
			}

			if($in!=''){
				$in = substr($in,0, strlen($in)-2);
				$in = 'mouvement.CODE_PRODUIT IN ('.$in.') AND ';
			}
			if($where!=''){
				$where = substr($where,0, strlen($where)-4);
			}
			$whereAll = 'AND '.$in.$where;

			if($in!=''){
				$in = ' WHERE '.substr($in,0, strlen($in)-4);
			}

			$_SESSION['DATA_ETAT']['exercice'] = $_POST['exercice'];
			$_SESSION['DATA_ETAT']['ligne'] =array();
			$_SESSION['DATA_ETAT']['WHERE'] = $whereAll;
			$d1 = date('Y-m-d');
			$d3= date("Y-m-d", mktime(0,0,0,date("m")-3, date("d"), date("y")));
			$d6= date("Y-m-d", mktime(0,0,0,date("m")-6, date("d"), date("y")));
			$d12= date("Y-m-d", mktime(0,0,0,date("m")-12, date("d"), date("y")));

			$sql = "SELECT * FROM produit  $in ORDER BY produit.PRD_LIBELLE ASC; ";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			while($row = $query->fetch(PDO::FETCH_ASSOC)){

				$qte3 = StockProduitPeriode($row['CODE_PRODUIT'], 'S', $d3, $d1, $whereAll);
				$qte6 = StockProduitPeriode($row['CODE_PRODUIT'], 'S', $d6, $d1, $whereAll);
				$qte12 = StockProduitPeriode($row['CODE_PRODUIT'], 'S', $d12, $d1, $whereAll);

				//echo  'Ent'.$entree.' Sort'.$sortie.'<br>';
				array_push($_SESSION['DATA_ETAT']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'reflot'=>$row['MVT_REFLOT'],'produit'=>addslashes($row['PRD_LIBELLE']),
				'qtesortie3'=>$qte3['QTE']/3, 'qtesortie6'=>$qte6['QTE']/6,'qtesortie12'=>$qte12['QTE']/12,'unite'=>$row['ID_UNITE']));
			}
			$_SESSION['DATA_ETAT']['nbreLigne'] =$query->rowCount();
			//print_r($_SESSION['DATA_ETAT']);
			header('location:rapsortiemoymensuelle1.php?selectedTab=rap');
			break;

		case 'journal':
			$where=" mouvement.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND ";
			(isset($_POST['exercice']) && $_POST['exercice']!=''	? $where .="mouvement.ID_EXERCICE = '".addslashes(trim($_POST['exercice']))."' AND " 	: $where .="");
			(isset($_POST['datedebut']) && $_POST['datedebut']!=''  ? $where .="mouvement.MVT_DATE >= '".addslashes(mysqlFormat(trim($_POST['datedebut'])))."' AND " 	: $where .="");
			(isset($_POST['datefin']) && $_POST['datefin']!=''  ? $where .="mouvement.MVT_DATE <= '".addslashes(mysqlFormat(trim($_POST['datefin'])))."' AND " 	: $where .="");

			(isset($_POST['produit'])  ?  $produit =$_POST['produit'] : $produit=array());
			(isset($_POST['categorie']) && $_POST['categorie']!='0'	? $categorie = $_POST['categorie'] 	: $categorie 	= '');
			(isset($_POST['souscategorie']) && $_POST['souscategorie']!='0'	? $souscategorie = $_POST['souscategorie'] 	: $souscategorie 	= '');

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}

			$in ='';
			if(count($produit)==0 ){
				//
				if ($categorie=='TOUS'){
					if ($souscategorie!='TOUS' && $souscategorie!='0') {
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit WHERE produit.CODE_SOUSCATEGORIE LIKE '$souscategorie' ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
					else{
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit  ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
				}
				else{
					//Produit
					$sql = "SELECT CODE_PRODUIT FROM produit
					INNER JOIN souscategorie ON (produit.CODE_SOUSCATEGORIE LIKE souscategorie.CODE_SOUSCATEGORIE)
					WHERE souscategorie.CODE_CATEGORIE LIKE '$categorie' ORDER BY PRD_LIBELLE ASC";
					$query =  $cnx->prepare($sql); //Prepare the SQL
					$query->execute(); //Execute prepared SQL => $query
					while($row = $query->fetch(PDO::FETCH_ASSOC)){
						$in .="'".$row['CODE_PRODUIT']."', ";
					}
				}
			}
			elseif(count($produit)>0){
				$in='';
				foreach($produit as $key => $val){
					$in .="'$val', ";
				}
			}

			if($in!=''){
				$in = substr($in,0, strlen($in)-2);
				$in = 'mouvement.CODE_PRODUIT IN ('.$in.') AND ';
			}
			if($where!=''){
				$where = substr($where,0, strlen($where)-4);
			}
			$whereAll = 'WHERE '.$in.$where;

			$_SESSION['DATA_ETAT'] =array();
			$_SESSION['DATA_ETAT']['WHERE']= $whereAll;
			$_SESSION['DATA_ETAT']['DATEJ']=$_POST['datedebut'];

			header('location:journal1.php?selectedTab=int');
			break;

		case 'analyse':

			$where=" mouvement.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND ";
			$whereAll ="";
			(isset($_POST['exercice']) && $_POST['exercice']!=''	? $where .="mouvement.ID_EXERCICE = '".addslashes(trim($_POST['exercice']))."' AND " 	: $where .="");
			(isset($_POST['datedebut']) && $_POST['datedebut']!=''  ? $where .="mouvement.MVT_DATE <= '".addslashes(mysqlFormat(trim($_POST['datedebut'])))."' AND " 	: $where .="");
			(isset($_POST['categorie']) && $_POST['categorie']!='0'	? $categorie = $_POST['categorie'] 	: $categorie 	= '');
			(isset($_POST['produit'])  ?  $produit =$_POST['produit'] : $produit=array());
			(isset($_POST['typemouvement'])  ?  $typemouvement =$_POST['typemouvement'] : $typemouvement='');

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}

			switch($typemouvement){

				case 1://'1'=>'LIVRAISONS'

					$_SESSION['DATA_ETAT']['Identique'] =array();
					$_SESSION['DATA_ETAT']['NonIdentique'] =array();
					$_SESSION['DATA_ETAT']['PlusIdentique'] =array();
					$_SESSION['DATA_ETAT']['Correspondant']= array();

					$Identique = array();
					$nonIdentique = array();
					$plusIdentique = array();
					$Correspondant = array();

					$sql = "SELECT * FROM  livraison INNER JOIN lvr_prd ON (livraison.ID_LIVRAISON=lvr_prd.ID_LIVRAISON)
					WHERE livraison.ID_EXERCICE=".$_POST['exercice']." AND livraison.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."'";

					$query =  $cnx->prepare($sql); //Prepare the SQL
					$query->execute(); //Execute prepared SQL => $query

					$i=0;
					while($row = $query->fetch(PDO::FETCH_ASSOC)){
						//Corresp
						$sqlCoresp = "SELECT  * FROM  mouvement WHERE mouvement.ID_SOURCE=".$row['ID_LIVRAISON']."
						AND mouvement.CODE_PRODUIT=".$row['CODE_PRODUIT']." AND mouvement.MVT_QUANTITE=".$row['LVRPRD_QUANTITE']."
						AND mouvement.ID_EXERCICE=".$row['ID_EXERCICE']." AND mouvement.MVT_NATURE LIKE 'LIVRAISON'";
						$query2 =  $cnx->prepare($sqlCoresp); //Prepare the SQL
						$query2->execute(); //Execute prepared SQL => $query

						$i++;

						if($query2->rowCount()==0){
							array_push($nonIdentique,$row);
						}
						elseif($query2->rowCount()==1){
							array_push($Identique,$row);
						}
						elseif($query2->rowCount()>1){
							while($rowCorresp = $query2->fetch(PDO::FETCH_ASSOC)){
								//print_r($rowCorresp);
								array_push($Correspondant,$rowCorresp);
							}
							array_push($plusIdentique,$row);
						}
					}
					$_SESSION['DATA_ETAT']['Identique'] =$Identique;
					$_SESSION['DATA_ETAT']['NonIdentique'] = $nonIdentique;
					$_SESSION['DATA_ETAT']['PlusIdentique'] = $plusIdentique;
					$_SESSION['DATA_ETAT']['Correspondant'] = $Correspondant;
					header('location:analysejournal.php?selectedTab=int');
					break;


				case 3://'3'=>'DOTATIONS DES ETABLISSEMENTS'

					$_SESSION['DATA_ETAT']['Identique'] =array();
					$_SESSION['DATA_ETAT']['NonIdentique'] =array();
					$_SESSION['DATA_ETAT']['PlusIdentique'] =array();
					$_SESSION['DATA_ETAT']['Correspondant']= array();

					$Identique = array();
					$nonIdentique = array();
					$plusIdentique = array();
					$Correspondant = array();

					$sql = "SELECT * FROM  dotation INNER JOIN dot_cnd ON (dot_cnd.ID_DOTATION=dotation.ID_DOTATION)
					WHERE dotation.ID_EXERCICE=".$_POST['exercice']." AND dotation.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."'
					AND (dotation.CODE_NDOTATION LIKE '1DOT' OR dotation.CODE_NDOTATION LIKE '2DOT' OR dotation.CODE_NDOTATION LIKE '3DOT')";

					$query =  $cnx->prepare($sql); //Prepare the SQL
					$query->execute(); //Execute prepared SQL => $query

					$i=0;
					while($row = $query->fetch(PDO::FETCH_ASSOC)){
						//Corresp
						$sqlCoresp = "SELECT  * FROM  mouvement WHERE mouvement.ID_SOURCE=".$row['ID_DOTATION']."
						AND mouvement.CODE_PRODUIT=".$row['CODE_PRODUIT']." AND mouvement.MVT_QUANTITE=".$row['DOTCND_QTE']."
						AND mouvement.ID_EXERCICE=".$row['ID_EXERCICE']." AND mouvement.MVT_NATURE LIKE 'DOTATION - ".$row['CODE_NDOTATION']."'";
						$query2 =  $cnx->prepare($sqlCoresp); //Prepare the SQL
						$query2->execute(); //Execute prepared SQL => $query

						$i++;

						if($query2->rowCount()==0){
							array_push($nonIdentique,$row);
						}
						elseif($query2->rowCount()==1){
							array_push($Identique,$row);
						}
						elseif($query2->rowCount()>1){
							while($rowCorresp = $query2->fetch(PDO::FETCH_ASSOC)){
								//print_r($rowCorresp);
								array_push($Correspondant,$rowCorresp);
							}
							array_push($plusIdentique,$row);
						}
					}

					$_SESSION['DATA_ETAT']['Identique'] =$Identique;
					$_SESSION['DATA_ETAT']['NonIdentique'] = $nonIdentique;
					$_SESSION['DATA_ETAT']['PlusIdentique'] = $plusIdentique;
					$_SESSION['DATA_ETAT']['Correspondant'] = $Correspondant;

					header('location:analysejournal.php?selectedTab=int');
					break;


				case 4://'4'=>'DOTATIONS BAC'

					$_SESSION['DATA_ETAT']['Identique'] =array();
					$_SESSION['DATA_ETAT']['NonIdentique'] =array();
					$_SESSION['DATA_ETAT']['PlusIdentique'] =array();
					$_SESSION['DATA_ETAT']['Correspondant']= array();

					$Identique = array();
					$nonIdentique = array();
					$plusIdentique = array();
					$Correspondant = array();

					$sql = "SELECT * FROM  dotation INNER JOIN dot_cnd ON (dot_cnd.ID_DOTATION=dotation.ID_DOTATION)
					WHERE dotation.ID_EXERCICE=".$_POST['exercice']." AND dotation.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."'
					AND (dotation.CODE_NDOTATION LIKE '10DOT')";

					$query =  $cnx->prepare($sql); //Prepare the SQL
					$query->execute(); //Execute prepared SQL => $query

					$i=0;
					while($row = $query->fetch(PDO::FETCH_ASSOC)){
						//Corresp
						$sqlCoresp = "SELECT  * FROM  mouvement WHERE mouvement.ID_SOURCE=".$row['ID_DOTATION']."
						AND mouvement.CODE_PRODUIT=".$row['CODE_PRODUIT']." AND mouvement.MVT_QUANTITE=".$row['DOTCND_QTE']."
						AND mouvement.ID_EXERCICE=".$row['ID_EXERCICE']." AND mouvement.MVT_NATURE LIKE 'DOTATION - ".$row['CODE_NDOTATION']."'";
						$query2 =  $cnx->prepare($sqlCoresp); //Prepare the SQL
						$query2->execute(); //Execute prepared SQL => $query

						$i++;

						if($query2->rowCount()==0){
							array_push($nonIdentique,$row);
						}
						elseif($query2->rowCount()==1){
							array_push($Identique,$row);
						}
						elseif($query2->rowCount()>1){
							while($rowCorresp = $query2->fetch(PDO::FETCH_ASSOC)){
								//print_r($rowCorresp);
								array_push($Correspondant,$rowCorresp);
							}
							array_push($plusIdentique,$row);
						}

					}
					$_SESSION['DATA_ETAT']['Identique'] =$Identique;
					$_SESSION['DATA_ETAT']['NonIdentique'] = $nonIdentique;
					$_SESSION['DATA_ETAT']['PlusIdentique'] = $plusIdentique;
					$_SESSION['DATA_ETAT']['Correspondant'] = $Correspondant;
					header('location:analysejournal.php?selectedTab=int');
					break;


				case 5://'5'=>'DOTATIONS USTENSILES'

					$_SESSION['DATA_ETAT']['Identique'] =array();
					$_SESSION['DATA_ETAT']['NonIdentique'] =array();
					$_SESSION['DATA_ETAT']['PlusIdentique'] =array();
					$_SESSION['DATA_ETAT']['Correspondant']= array();

					$Identique = array();
					$nonIdentique = array();
					$plusIdentique = array();
					$Correspondant = array();

					$sql = "SELECT * FROM  dotation INNER JOIN dot_cnd ON (dot_cnd.ID_DOTATION=dotation.ID_DOTATION)
					WHERE dotation.ID_EXERCICE=".$_POST['exercice']." AND dotation.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."'
					AND (dotation.CODE_NDOTATION LIKE 'UDOT')";

					$query =  $cnx->prepare($sql); //Prepare the SQL
					$query->execute(); //Execute prepared SQL => $query

					$i=0;
					while($row = $query->fetch(PDO::FETCH_ASSOC)){
						//Corresp
						$sqlCoresp = "SELECT  * FROM  mouvement WHERE mouvement.ID_SOURCE=".$row['ID_DOTATION']."
						AND mouvement.CODE_PRODUIT=".$row['CODE_PRODUIT']." AND mouvement.MVT_QUANTITE=".$row['DOTCND_QTE']."
						AND mouvement.ID_EXERCICE=".$row['ID_EXERCICE']." AND mouvement.MVT_NATURE LIKE 'DOTATION - ".$row['CODE_NDOTATION']."'";
						$query2 =  $cnx->prepare($sqlCoresp); //Prepare the SQL
						$query2->execute(); //Execute prepared SQL => $query

						$i++;

						if($query2->rowCount()==0){
							array_push($nonIdentique,$row);
						}
						elseif($query2->rowCount()==1){
							array_push($Identique,$row);
						}
						elseif($query2->rowCount()>1){
							while($rowCorresp = $query2->fetch(PDO::FETCH_ASSOC)){
								//print_r($rowCorresp);
								array_push($Correspondant,$rowCorresp);
							}
							array_push($plusIdentique,$row);
						}
					}
					$_SESSION['DATA_ETAT']['Identique'] =$Identique;
					$_SESSION['DATA_ETAT']['NonIdentique'] = $nonIdentique;
					$_SESSION['DATA_ETAT']['PlusIdentique'] = $plusIdentique;
					$_SESSION['DATA_ETAT']['Correspondant'] = $Correspondant;
					header('location:analysejournal.php?selectedTab=int');
					break;


				case 6://'6'=>'AUTRES DOTATIONS'

					$_SESSION['DATA_ETAT']['Identique'] =array();
					$_SESSION['DATA_ETAT']['NonIdentique'] =array();
					$_SESSION['DATA_ETAT']['PlusIdentique'] =array();
					$_SESSION['DATA_ETAT']['Correspondant']= array();

					$Identique = array();
					$nonIdentique = array();
					$plusIdentique = array();
					$Correspondant = array();

					$sql = "SELECT * FROM  dotation INNER JOIN dot_cnd ON (dot_cnd.ID_DOTATION=dotation.ID_DOTATION)
					WHERE dotation.ID_EXERCICE=".$_POST['exercice']." AND dotation.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."'
					AND (dotation.CODE_NDOTATION LIKE 'ADOT')";

					$query =  $cnx->prepare($sql); //Prepare the SQL
					$query->execute(); //Execute prepared SQL => $query

					$i=0;
					while($row = $query->fetch(PDO::FETCH_ASSOC)){
						//Corresp
						$sqlCoresp = "SELECT  * FROM  mouvement WHERE mouvement.ID_SOURCE=".$row['ID_DOTATION']."
						AND mouvement.CODE_PRODUIT=".$row['CODE_PRODUIT']." AND mouvement.MVT_QUANTITE=".$row['DOTCND_QTE']."
						AND mouvement.ID_EXERCICE=".$row['ID_EXERCICE']." AND mouvement.MVT_NATURE LIKE 'DOTATION - ".$row['CODE_NDOTATION']."'";
						$query2 =  $cnx->prepare($sqlCoresp); //Prepare the SQL
						$query2->execute(); //Execute prepared SQL => $query

						$i++;

						if($query2->rowCount()==0){
							array_push($nonIdentique,$row);
						}
						elseif($query2->rowCount()==1){
							array_push($Identique,$row);
						}
						elseif($query2->rowCount()>1){
							while($rowCorresp = $query2->fetch(PDO::FETCH_ASSOC)){
								//print_r($rowCorresp);
								array_push($Correspondant,$rowCorresp);
							}
							array_push($plusIdentique,$row);
						}
					}
					$_SESSION['DATA_ETAT']['Identique'] =$Identique;
					$_SESSION['DATA_ETAT']['NonIdentique'] = $nonIdentique;
					$_SESSION['DATA_ETAT']['PlusIdentique'] = $plusIdentique;
					$_SESSION['DATA_ETAT']['Correspondant'] = $Correspondant;
					header('location:analysejournal.php?selectedTab=int');
					break;


				case 7://'7'=>'DECLASSEMENTS'
					$_SESSION['DATA_ETAT']['Identique'] =array();
					$_SESSION['DATA_ETAT']['NonIdentique'] =array();
					$_SESSION['DATA_ETAT']['PlusIdentique'] =array();
					$_SESSION['DATA_ETAT']['Correspondant']= array();

					$Identique = array();
					$nonIdentique = array();
					$plusIdentique = array();
					$Correspondant = array();

					$sql = "SELECT * FROM  declass INNER JOIN declass_cnd ON (declass.ID_DECLASS=declass_cnd.ID_DECLASS)
					WHERE declass.ID_EXERCICE=".$_POST['exercice']." AND declass.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."'";

					$query =  $cnx->prepare($sql); //Prepare the SQL
					$query->execute(); //Execute prepared SQL => $query

					$i=0;
					while($row = $query->fetch(PDO::FETCH_ASSOC)){
						//Corresp
						$sqlCoresp = "SELECT  * FROM  mouvement WHERE mouvement.ID_SOURCE=".$row['ID_DECLASS']."
						AND mouvement.CODE_PRODUIT=".$row['CODE_PRODUIT']." AND mouvement.MVT_QUANTITE=".$row['DECLASSCND_QUANTITE']."
						AND mouvement.ID_EXERCICE=".$row['ID_EXERCICE']." AND mouvement.MVT_NATURE LIKE 'DECLASSEMENT'";
						$query2 =  $cnx->prepare($sqlCoresp); //Prepare the SQL
						$query2->execute(); //Execute prepared SQL => $query

						$i++;

						if($query2->rowCount()==0){
							array_push($nonIdentique,$row);
						}
						elseif($query2->rowCount()==1){
							array_push($Identique,$row);
						}
						elseif($query2->rowCount()>1){
							while($rowCorresp = $query2->fetch(PDO::FETCH_ASSOC)){
								//print_r($rowCorresp);
								array_push($Correspondant,$rowCorresp);
							}
							array_push($plusIdentique,$row);
						}
					}
					$_SESSION['DATA_ETAT']['Identique'] =$Identique;
					$_SESSION['DATA_ETAT']['NonIdentique'] = $nonIdentique;
					$_SESSION['DATA_ETAT']['PlusIdentique'] = $plusIdentique;
					$_SESSION['DATA_ETAT']['Correspondant'] = $Correspondant;
					header('location:analysejournal.php?selectedTab=int');
					break;


				case '8'://'8'=>'RECONDITIONNEMENTS'

					$_SESSION['DATA_ETAT']['Identique'] =array();
					$_SESSION['DATA_ETAT']['NonIdentique'] =array();
					$_SESSION['DATA_ETAT']['PlusIdentique'] =array();
					$_SESSION['DATA_ETAT']['Correspondant']= array();

					$Identique = array();
					$nonIdentique = array();
					$plusIdentique = array();
					$Correspondant = array();

					$sql = "SELECT * FROM  recondit INNER JOIN recond_cnd ON (recondit.ID_RECONDIT=recond_cnd.ID_RECONDIT)
					WHERE recondit.ID_EXERCICE=".$_POST['exercice']." AND recondit.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."'";

					$query =  $cnx->prepare($sql); //Prepare the SQL
					$query->execute(); //Execute prepared SQL => $query

					$i=0;
					while($row = $query->fetch(PDO::FETCH_ASSOC)){
						//Corresp
						$sqlCoresp = "SELECT  * FROM  mouvement WHERE mouvement.ID_SOURCE=".$row['ID_RECONDIT']."
						AND mouvement.CODE_PRODUIT=".$row['CODE_PRODUIT']." AND mouvement.MVT_QUANTITE=".$row['CNDREC_QTEE']."
						AND mouvement.ID_EXERCICE=".$row['ID_EXERCICE']." AND mouvement.MVT_NATURE LIKE 'RECONDITIONNEMENT%'";
						$query2 =  $cnx->prepare($sqlCoresp); //Prepare the SQL
						$query2->execute(); //Execute prepared SQL => $query

						$i++;

						if($query2->rowCount()==0){
							array_push($nonIdentique,$row);
						}
						elseif($query2->rowCount()==1){
							array_push($Identique,$row);
						}
						elseif($query2->rowCount()>1){
							while($rowCorresp = $query2->fetch(PDO::FETCH_ASSOC)){
								//print_r($rowCorresp);
								array_push($Correspondant,$rowCorresp);
							}
							array_push($plusIdentique,$row);
						}
					}
					$_SESSION['DATA_ETAT']['Identique'] =$Identique;
					$_SESSION['DATA_ETAT']['NonIdentique'] = $nonIdentique;
					$_SESSION['DATA_ETAT']['PlusIdentique'] = $plusIdentique;
					$_SESSION['DATA_ETAT']['Correspondant'] = $Correspondant;
					header('location:analysejournal.php?selectedTab=int');
					break;

				case '9'://'9'=>'REPORTS'

					$_SESSION['DATA_ETAT']['Identique'] =array();
					$_SESSION['DATA_ETAT']['NonIdentique'] =array();
					$_SESSION['DATA_ETAT']['PlusIdentique'] =array();
					$_SESSION['DATA_ETAT']['Correspondant']= array();

					$Identique = array();
					$nonIdentique = array();
					$plusIdentique = array();
					$Correspondant = array();

					$sql = "SELECT * FROM  report INNER JOIN report_cnd ON (report.ID_RECONDIT=report_cnd.ID_RECONDIT)
					WHERE report.ID_EXERCICE=".$_POST['exercice']." AND report.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."'";

					$query =  $cnx->prepare($sql); //Prepare the SQL
					$query->execute(); //Execute prepared SQL => $query

					$i=0;
					while($row = $query->fetch(PDO::FETCH_ASSOC)){
						//Corresp
						$sqlCoresp = "SELECT  * FROM  mouvement WHERE mouvement.ID_SOURCE=".$row['ID_REPORT']."
						AND mouvement.CODE_PRODUIT=".$row['CODE_PRODUIT']." AND mouvement.MVT_QUANTITE=".$row['REPCND_QTE']."
						AND mouvement.ID_EXERCICE=".$row['ID_EXERCICE']." AND mouvement.MVT_NATURE LIKE 'REPORT%'";
						$query2 =  $cnx->prepare($sqlCoresp); //Prepare the SQL
						$query2->execute(); //Execute prepared SQL => $query

						$i++;

						if($query2->rowCount()==0){
							array_push($nonIdentique,$row);
						}
						elseif($query2->rowCount()==1){
							array_push($Identique,$row);
						}
						elseif($query2->rowCount()>1){
							while($rowCorresp = $query2->fetch(PDO::FETCH_ASSOC)){
								//print_r($rowCorresp);
								array_push($Correspondant,$rowCorresp);
							}
							array_push($plusIdentique,$row);
						}
					}
					$_SESSION['DATA_ETAT']['Identique'] =$Identique;
					$_SESSION['DATA_ETAT']['NonIdentique'] = $nonIdentique;
					$_SESSION['DATA_ETAT']['PlusIdentique'] = $plusIdentique;
					$_SESSION['DATA_ETAT']['Correspondant'] = $Correspondant;
					header('location:analysejournal.php?selectedTab=int');
					break;


				case '10'://'10'=>'TRANSFERTS'
					$_SESSION['DATA_ETAT']['Identique'] =array();
					$_SESSION['DATA_ETAT']['NonIdentique'] =array();
					$_SESSION['DATA_ETAT']['PlusIdentique'] =array();
					$_SESSION['DATA_ETAT']['Correspondant']= array();

					$Identique = array();
					$nonIdentique = array();
					$plusIdentique = array();
					$Correspondant = array();

					$sql = "SELECT * FROM  transfert INNER JOIN trs_cnd ON (transfert.ID_TRANSFERT=trs_cnd.ID_TRANSFERT)
					WHERE transfert.ID_EXERCICE=".$_POST['exercice']." AND transfert.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."'";

					$query =  $cnx->prepare($sql); //Prepare the SQL
					$query->execute(); //Execute prepared SQL => $query

					$i=0;
					while($row = $query->fetch(PDO::FETCH_ASSOC)){
						//Corresp
						$sqlCoresp = "SELECT  * FROM  mouvement WHERE mouvement.ID_SOURCE=".$row['ID_TRANSFERT']."
						AND mouvement.CODE_PRODUIT=".$row['CODE_PRODUIT']." AND mouvement.MVT_QUANTITE=".$row['TRSCND_QTE']."
						AND mouvement.ID_EXERCICE=".$row['ID_EXERCICE']." AND mouvement.MVT_NATURE LIKE 'TRANSFERT%'";
						$query2 =  $cnx->prepare($sqlCoresp); //Prepare the SQL
						$query2->execute(); //Execute prepared SQL => $query

						$i++;

						if($query2->rowCount()==0){
							array_push($nonIdentique,$row);
						}
						elseif($query2->rowCount()==1){
							array_push($Identique,$row);
						}
						elseif($query2->rowCount()>1){
							while($rowCorresp = $query2->fetch(PDO::FETCH_ASSOC)){
								//print_r($rowCorresp);
								array_push($Correspondant,$rowCorresp);
							}
							array_push($plusIdentique,$row);
						}
					}
					$_SESSION['DATA_ETAT']['Identique'] =$Identique;
					$_SESSION['DATA_ETAT']['NonIdentique'] = $nonIdentique;
					$_SESSION['DATA_ETAT']['PlusIdentique'] = $plusIdentique;
					$_SESSION['DATA_ETAT']['Correspondant'] = $Correspondant;
					header('location:analysejournal.php?selectedTab=int');
					break;

			}

		case 'raprupture':

			$where=" mouvement.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND ";
			$whereAll ="";
			(isset($_POST['exercice']) && $_POST['exercice']!=''	? $where .="mouvement.ID_EXERCICE = '".addslashes(trim($_POST['exercice']))."' AND " 	: $where .="");
			(isset($_POST['datedebut']) && $_POST['datedebut']!=''  ? $where .="mouvement.MVT_DATE >= '".addslashes(mysqlFormat(trim($_POST['datedebut'])))."' AND " 	: $where .="");
			(isset($_POST['datefin']) && $_POST['datefin']!=''  ? $where .="mouvement.MVT_DATE <= '".addslashes(mysqlFormat(trim($_POST['datefin'])))."' AND " 	: $where .="");

			(isset($_POST['produit'])  ?  $produit =$_POST['produit'] : $produit=array());
			(isset($_POST['categorie']) && $_POST['categorie']!='0'	? $categorie = $_POST['categorie'] 	: $categorie 	= '');
			(isset($_POST['souscategorie']) && $_POST['souscategorie']!='0'	? $souscategorie = $_POST['souscategorie'] 	: $souscategorie 	= '');

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}

			$in ='';
			if(count($produit)==0 ){
				//
				if ($categorie=='TOUS'){
					if ($souscategorie!='TOUS' && $souscategorie!='0') {
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit WHERE produit.CODE_SOUSCATEGORIE LIKE '$souscategorie' ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
					else{
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit  ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
				}
				else{
					//Produit
					$sql = "SELECT CODE_PRODUIT FROM produit
					INNER JOIN souscategorie ON (produit.CODE_SOUSCATEGORIE LIKE souscategorie.CODE_SOUSCATEGORIE)
					WHERE souscategorie.CODE_CATEGORIE LIKE '$categorie' ORDER BY PRD_LIBELLE ASC";
					$query =  $cnx->prepare($sql); //Prepare the SQL
					$query->execute(); //Execute prepared SQL => $query
					while($row = $query->fetch(PDO::FETCH_ASSOC)){
						$in .="'".$row['CODE_PRODUIT']."', ";
					}
				}
			}
			elseif(count($produit)>0){
				$in='';
				foreach($produit as $key => $val){
					$in .="'$val', ";
				}
			}

			if($in!=''){
				$in = substr($in,0, strlen($in)-2);
				$in = 'produit.CODE_PRODUIT IN ('.$in.') AND ';
			}
			if($where!=''){
				$where = substr($where,0, strlen($where)-4);
			}
			$whereAll = 'AND '.$in.$where;

			if($in!=''){
				$in = 'WHERE '.substr($in,0, strlen($in)-4);
			}

			$_SESSION['DATA_ETAT']['exercice'] =$_POST['exercice'];
			$_SESSION['DATA_ETAT']['ligne'] =array();
			$_SESSION['DATA_ETAT']['WHERE']= $whereAll;
			$_SESSION['DATA_ETAT']['DATEJ']=$_POST['datedebut'];
			$_SESSION['DATA_ETAT']['nbreLigne'] =0;

			$sql = "SELECT * FROM produit  $in ORDER BY PRD_LIBELLE ASC; ";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				//print_r($row); echo 'Lig 1<br>';
				$Livraison = StockProduitParNature($row['CODE_PRODUIT'], 'LIVRAISON', $whereAll);
				$bonsortie = StockProduitParNature($row['CODE_PRODUIT'], 'BON DE SORTIE', $whereAll);

				$Declassement = StockProduitParNature($row['CODE_PRODUIT'], 'DECLASSEMENT', $whereAll);

				$transfetEnt = StockProduitParNature($row['CODE_PRODUIT'], 'TRANSFERT ENTRANT', $whereAll);
				$transfetSort = StockProduitParNature($row['CODE_PRODUIT'], 'TRANSFERT SORTANT', $whereAll);

				$reportEntree = StockProduitParNature($row['CODE_PRODUIT'], 'REPORT ENTRANT', $whereAll);
				$reportSortie = StockProduitParNature($row['CODE_PRODUIT'], 'REPORT SORTANT', $whereAll);

				$inventplus = StockProduitParNature($row['CODE_PRODUIT'], 'INVENTAIRE +', $whereAll);
				$inventmoins = StockProduitParNature($row['CODE_PRODUIT'], 'INVENTAIRE -', $whereAll);

				$entree  = $Livraison['QTE'] +  $reportEntree['QTE'] + $transfetEnt['QTE'];// ENTREE
				$sortie  = $bonsortie['QTE']  + $Declassement['QTE'] +   $reportSortie['QTE'] + $transfetSort['QTE'] ; //SORTIE
				$ecart   =	$inventmoins['QTE'] + $inventplus['QTE'];
				$rest = $entree - ($sortie) ;

				if ($rest==0) {
					$sql2 ="SELECT MAX(`MVT_DATE`) AS D_DATE from mouvement
					WHERE MVT_TYPE LIKE 'S' AND CODE_PRODUIT LIKE '".addslashes($row['CODE_PRODUIT'])."';";
					$query2 =  $cnx->prepare($sql2); //Prepare the SQL
					$query2->execute(); //Execute prepared SQL => $query
					$row2 = $query2->fetch(PDO::FETCH_ASSOC);
					(isset($row2['D_DATE']) && $row2['D_DATE']!='' ? $ddebut = $row2['D_DATE'] : $ddebut = $_SESSION['GL_USER']['EXERCICE'].'-01-01');

					$j = NbJours($ddebut, date('Y-m-d'));
					//	echo $ddebut, ' ', date('Y-m-d'), ' ',$j;
					array_push($_SESSION['DATA_ETAT']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'],
					'produit'=>stripslashes($row['PRD_LIBELLE']),'stocks'=>$rest,'unite'=>$row['ID_UNITE'],'date'=>frFormat2($row2['D_DATE']),'jour'=>$j, 'semaine'=>$j/7, 'mois'=>$j/30 ));
					$_SESSION['DATA_ETAT']['nbreLigne'] +=1;
				}
			}
			header('location:raprupture1.php?selectedTab=rap');
			break;

		case 'rapportmensuel': //Par produit

				$where=" mouvement.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND ";

				$whereAll ="";
				(isset($_POST['exercice']) && $_POST['exercice']!=''	? $where .="mouvement.ID_EXERCICE = '".addslashes(trim($_POST['exercice']))."' AND " 	: $where .="");
				(isset($_POST['datedebut']) && $_POST['datedebut']!=''  ? $where .="mouvement.MVT_DATE <= '".addslashes(mysqlFormat(trim($_POST['datedebut'])))."' AND " 	: $where .="");

				$wherePeriode =" mouvement.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND ";
				(isset($_POST['exercice']) && $_POST['exercice']!=''	? $wherePeriode .="mouvement.ID_EXERCICE = '".addslashes(trim($_POST['exercice']))."' AND " 	: $wherePeriode .="");
				(isset($_POST['datedebut']) && $_POST['datedebut']!=''  ? $wherePeriode .="mouvement.MVT_DATE >= '".addslashes(mysqlFormat(trim($_POST['datedebut'])))."' AND " 	: $wherePeriode .="");
				(isset($_POST['datefin']) && $_POST['datefin']!=''  ? $wherePeriode .="mouvement.MVT_DATE <= '".addslashes(mysqlFormat(trim($_POST['datefin'])))."' AND " 	: $wherePeriode .="");
				$wherePeriode = substr($wherePeriode,0, strlen($wherePeriode)-4);

				(isset($_POST['produit'])  ?  $produit =$_POST['produit'] : $produit=array());
				(isset($_POST['categorie']) && $_POST['categorie']!='0'	? $categorie = $_POST['categorie'] 	: $categorie 	= '');
				(isset($_POST['souscategorie']) && $_POST['souscategorie']!='0'	? $souscategorie = $_POST['souscategorie'] 	: $souscategorie 	= '');

				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}

				$in ='';
				if(count($produit)==0 ){
					//
					if ($categorie=='TOUS'){
						if ($souscategorie!='TOUS' && $souscategorie!='0') {
							//Produit
							$sql = "SELECT CODE_PRODUIT FROM produit WHERE produit.CODE_SOUSCATEGORIE LIKE '$souscategorie' ORDER BY PRD_LIBELLE ASC";
							$query =  $cnx->prepare($sql); //Prepare the SQL
							$query->execute(); //Execute prepared SQL => $query
							while($row = $query->fetch(PDO::FETCH_ASSOC)){
								$in .="'".$row['CODE_PRODUIT']."', ";
							}
						}
						else{
							//Produit
							$sql = "SELECT CODE_PRODUIT FROM produit  ORDER BY PRD_LIBELLE ASC";
							$query =  $cnx->prepare($sql); //Prepare the SQL
							$query->execute(); //Execute prepared SQL => $query
							while($row = $query->fetch(PDO::FETCH_ASSOC)){
								$in .="'".$row['CODE_PRODUIT']."', ";
							}
						}
					}
					else{
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit
						INNER JOIN souscategorie ON (produit.CODE_SOUSCATEGORIE LIKE souscategorie.CODE_SOUSCATEGORIE)
						WHERE souscategorie.CODE_CATEGORIE LIKE '$categorie' ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
				}
				elseif(count($produit)>0){
					$in='';
					foreach($produit as $key => $val){
						$in .="'$val', ";
					}
				}

				if($in!=''){
					$in = substr($in,0, strlen($in)-2);
					$in = 'produit.CODE_PRODUIT IN ('.$in.') AND ';
				}
				if($where!=''){
					$where = substr($where,0, strlen($where)-4);
				}
				$whereAll = 'AND '.$in.$where;

				if($in!=''){
					$in = 'WHERE '.substr($in,0, strlen($in)-4);
				}

				$_SESSION['DATA_ETAT']['exercice'] =$_POST['exercice'];
				$_SESSION['DATA_ETAT']['ligne'] =array();
				$_SESSION['DATA_ETAT']['WHERE']= $whereAll;
				$_SESSION['DATA_ETAT']['DATED']=$_POST['datedebut'];
				$_SESSION['DATA_ETAT']['DATEF']=$_POST['datefin'];
				$_SESSION['DATA_ETAT']['LIBELLEETAT']= stripslashes(getField('CODE_MAGASIN',$_SESSION['GL_USER']['MAGASIN'],'SER_NOM','magasin'));

				if($_POST['par']=='PRD'){
					$sql = "SELECT * FROM produit  $in ORDER BY PRD_LIBELLE ASC; ";
					$query =  $cnx->prepare($sql); //Prepare the SQL
					$query->execute(); //Execute prepared SQL => $query

					$z = 0;
					while($row = $query->fetch(PDO::FETCH_ASSOC)){
						$tProduit = StockProduitPerime($row['CODE_PRODUIT'], $type='E',  $whereAll);
						$qeperime = $tProduit['QTE'];

						$Livraison = StockProduitParNature($row['CODE_PRODUIT'], 'LIVRAISON', $whereAll);

						$bonsortie = StockProduitParNature($row['CODE_PRODUIT'], 'BON DE SORTIE', $whereAll);

						$Declassement = StockProduitParNature($row['CODE_PRODUIT'], 'DECLASSEMENT', $whereAll);

						$transfetEnt = StockProduitParNature($row['CODE_PRODUIT'], 'TRANSFERT ENTRANT', $whereAll);

						$transfetSort = StockProduitParNature($row['CODE_PRODUIT'], 'TRANSFERT SORTANT', $whereAll);

						$reportEntree = StockProduitParNature($row['CODE_PRODUIT'], 'REPORT ENTRANT', $whereAll);

						$reportSortie = StockProduitParNature($row['CODE_PRODUIT'], 'REPORT SORTANT', $whereAll);

						$inventplus = StockProduitParNature($row['CODE_PRODUIT'], 'INVENTAIRE +', $whereAll);

						$inventmoins = StockProduitParNature($row['CODE_PRODUIT'], 'INVENTAIRE -', $whereAll);

						$entree  = $Livraison['QTE'] +  $reportEntree['QTE'] + $transfetEnt['QTE'];// ENTREE
						$sortie  = $bonsortie['QTE']  + $Declassement['QTE'] + $reportSortie['QTE'] + $transfetSort['QTE'] ; //SORTIE
						$ecart   = $inventmoins['QTE'] + $inventplus['QTE'];
						$rest 	 = $entree - ($sortie) ;//+ ($ecart);

						//ENTREE PERIODIQUE
						$Plivraison = StockProduitParNaturePeriode($row['CODE_PRODUIT'], 'LIVRAISON', $wherePeriode);
						$PreportEntree = StockProduitParNaturePeriode($row['CODE_PRODUIT'], 'REPORT ENTRANTT', $wherePeriode);
						$PtransfetEnt = StockProduitParNaturePeriode($row['CODE_PRODUIT'], 'TRANSFERT ENTRANT', $wherePeriode);
						$PEntree = $Plivraison['QTE'] +  $PreportEntree['QTE'] + $PtransfetEnt['QTE'];// ENTREE

						//Declassement
						$PDeclassement = StockProduitParNaturePeriode($row['CODE_PRODUIT'], 'DECLASSEMENT', $wherePeriode);
						$Pperte = $PDeclassement['QTE'];

						//SORTIE PERIODIQUE
						$Pbonsortie = StockProduitParNaturePeriode($row['CODE_PRODUIT'], 'BON DE SORTIE', $wherePeriode);
						$PreportSortie = StockProduitParNaturePeriode($row['CODE_PRODUIT'], 'REPORT SORTANT', $wherePeriode);
						$PtransfetSortie = StockProduitParNaturePeriode($row['CODE_PRODUIT'], 'TRANSFERT SORTANT', $wherePeriode);
						$PSortie = $Pbonsortie['QTE'] +  $PreportSortie['QTE'] + $PtransfetSortie['QTE'];// SORTIE
						$solde 	 = $rest + $PEntree - $PSortie - $Pperte; // Les pertes sont les pertes

						if ($PEntree!=0 || $PSortie!=0 || $rest!=0){
							array_push($_SESSION['DATA_ETAT']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>addslashes($row['PRD_LIBELLE']), 'livraison'=>$Livraison['QTE'],
							'qteentre'=>$entree,'transfertsort'=>$transfetSort['QTE'], 'transfertent'=>$transfetEnt['QTE'], 'bonsortie'=>$bonsortie['QTE'],
							'qteperime'=>$qeperime, 'reportentree'=>$reportEntree['QTE'], 'reportsortie'=>$reportSortie['QTE'],'declass'=>$Declassement['QTE'],
							'qtesortie'=>$sortie, 'ecart'=>$ecart, 'stocks'=>$rest, 'Pentree'=>$PEntree, 'Psortie'=>$PSortie,  'solde'=>$solde,'unite'=>$row['ID_UNITE']));
							$z++;
						}
					}

					$_SESSION['DATA_ETAT']['nbreLigne'] = $z ; //$query->rowCount();
					header('location:rapmensuel1.php?selectedTab=rap');
				}
				else{  //PAR LOT

					$lesMag =" AND mouvement.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' ";

					if($in!=''){
						$in = ' AND '.substr($in,6, strlen($in));
					}

					$sql = "SELECT * FROM mouvement INNER JOIN produit ON (mouvement.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
					WHERE mouvement.MVT_TYPE LIKE 'E' AND MVT_VALID=1 $in  $lesMag GROUP BY MVT_REFLOT ORDER BY produit.PRD_LIBELLE ASC ; ";
					$query =  $cnx->prepare($sql); //Prepare the SQL
					$query->execute(); //Execute prepared SQL => $query

					$z=0;
					while($row = $query->fetch(PDO::FETCH_ASSOC)){
						$tProduit = StockLotPerime($row['MVT_REFLOT'], $type='E',  $whereAll);
						$qeperime = $tProduit['QTE'];

						$Livraison = StockLotParNature($row['MVT_REFLOT'], 'LIVRAISON', $whereAll);

						$bonsortie = StockLotParNature($row['MVT_REFLOT'], 'BON DE SORTIE', $whereAll);

						$Declassement = StockLotParNature($row['MVT_REFLOT'], 'DECLASSEMENT', $whereAll);

						$transfetEnt = StockLotParNature($row['MVT_REFLOT'], 'TRANSFERT ENTRANT', $whereAll);

						$transfetSort = StockLotParNature($row['MVT_REFLOT'], 'TRANSFERT SORTANT', $whereAll);

						$reportEntree = StockLotParNature($row['MVT_REFLOT'], 'REPORT ENTRANT', $whereAll);

						$reportSortie = StockLotParNature($row['MVT_REFLOT'], 'REPORT SORTANT', $whereAll);

						$inventplus = StockLotParNature($row['MVT_REFLOT'], 'INVENTAIRE +', $whereAll);

						$inventmoins = StockLotParNature($row['MVT_REFLOT'], 'INVENTAIRE -', $whereAll);

						$entree  = $Livraison['QTE'] +  $reportEntree['QTE'] + $transfetEnt['QTE'];// ENTREE
						$sortie  = $bonsortie['QTE']  + $Declassement['QTE'] + $reportSortie['QTE'] + $transfetSort['QTE'] ; //SORTIE
						$ecart   = $inventmoins['QTE'] + $inventplus['QTE'];
						$rest 	 = $entree - ($sortie); //+ ($ecart);

						//ENTREE PERIODIQUE
						$Plivraison = StockLotParNaturePeriode($row['MVT_REFLOT'], 'LIVRAISON', $wherePeriode);
						$PreportEntree = StockLotParNaturePeriode($row['MVT_REFLOT'], 'REPORT ENTRANTT', $wherePeriode);
						$PtransfetEnt = StockLotParNaturePeriode($row['MVT_REFLOT'], 'TRANSFERT ENTRANT', $wherePeriode);
						$PEntree = $Plivraison['QTE'] +  $PreportEntree['QTE'] + $PtransfetEnt['QTE'];// ENTREE

						//Declassement
						$PDeclassement = StockLotParNaturePeriode($row['MVT_REFLOT'], 'DECLASSEMENT', $wherePeriode);
						$Pperte = $PDeclassement['QTE'];

						//SORTIE PERIODIQUE
						$Pbonsortie = StockLotParNaturePeriode($row['MVT_REFLOT'], 'BON DE SORTIE', $wherePeriode);
						$PreportSortie = StockLotParNaturePeriode($row['MVT_REFLOT'], 'REPORT SORTANT', $wherePeriode);
						$PtransfetSortie = StockLotParNaturePeriode($row['MVT_REFLOT'], 'TRANSFERT SORTANT', $wherePeriode);
						$PSortie = $Pbonsortie['QTE'] +  $PreportSortie['QTE'] + $PtransfetSortie['QTE'];// SORTIE
						$solde 	 = $rest + $PEntree - $PSortie - $Pperte;

						if ($PEntree!=0 || $PSortie!=0 || $rest!=0) {
							array_push($_SESSION['DATA_ETAT']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'reflot'=>$row['MVT_REFLOT'], 'produit'=>addslashes($row['PRD_LIBELLE']), 'livraison'=>$Livraison['QTE'],
							'qteentre'=>$entree,'transfertsort'=>$transfetSort['QTE'], 'transfertent'=>$transfetEnt['QTE'], 'bonsortie'=>$bonsortie['QTE'],
							'qteperime'=>$qeperime, 'reportentree'=>$reportEntree['QTE'], 'reportsortie'=>$reportSortie['QTE'],'declass'=>$Declassement['QTE'],
							'qtesortie'=>$sortie, 'ecart'=>$ecart, 'stocks'=>$rest, 'Pentree'=>$PEntree, 'Psortie'=>$PSortie,  'solde'=>$solde,'unite'=>$row['ID_UNITE']));
							$z++;
						}
					}

					$_SESSION['DATA_ETAT']['nbreLigne'] = $z; //$query->rowCount();
					header('location:rapmensuel2.php?selectedTab=rap');
				}
			break;

		case 'rapporttrimestriel': //Par produit
			$whereAll ="";
			$Location = '';
			$whereLocation = '';
			if(isset($_POST['region']) && $_POST['region']=='0'){	//AU NIVEAU NATIONAL (toutes les regions, provinces et magasins)
				$Location ='';
			}
			else{	//AU NIVEAU REGION (tous les provinces et magasins d'une region)
				if (isset($_POST['province']) && $_POST['province']=='0') { //AU NIVEAU REGION (tous les provinces et magasins d'une region)
					$Location ="province.IDREGION='".$_POST['region']."' ";
				}
				else{ //AU NIVEAU PROVINCE (tous les provinces et magasins d'une province)
					$Location ="province.IDPROVINCE='".$_POST['province']."' ";
				}
			}

			if ($Location!='') {
				$whereLocation = ' WHERE '.$Location;
			}
			$sql = "SELECT * FROM magasin INNER JOIN province ON (magasin.IDPROVINCE=province.IDPROVINCE) $whereLocation ORDER BY SER_NOM ASC;";

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				die($error->getMessage().' '.__LINE__);
			}
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			$lesMag = '';
			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				$lesMag .= "'".$row['CODE_MAGASIN']."', ";
			} // while

			$where = ' mouvement.CODE_MAGASIN IN ('.substr($lesMag,0,strlen($lesMag)-2).') AND ';

			$whereAll ="";
			(isset($_POST['exercice']) && $_POST['exercice']!=''	? $where .="mouvement.ID_EXERCICE = '".addslashes(trim($_POST['exercice']))."' AND " 	: $where .="");
			(isset($_POST['datedebut']) && $_POST['datedebut']!=''  ? $where .="mouvement.MVT_DATE <= '".addslashes(mysqlFormat(trim($_POST['datedebut'])))."' AND " 	: $where .="");

			$wherePeriode =' mouvement.CODE_MAGASIN IN ('.substr($lesMag,0,strlen($lesMag)-2).') AND ';
			(isset($_POST['exercice']) && $_POST['exercice']!=''	? $wherePeriode .="mouvement.ID_EXERCICE = '".addslashes(trim($_POST['exercice']))."' AND " 	: $wherePeriode .="");
			(isset($_POST['datedebut']) && $_POST['datedebut']!=''  ? $wherePeriode .="mouvement.MVT_DATE >= '".addslashes(mysqlFormat(trim($_POST['datedebut'])))."' AND " 	: $wherePeriode .="");
			(isset($_POST['datefin']) && $_POST['datefin']!=''  ? $wherePeriode .="mouvement.MVT_DATE <= '".addslashes(mysqlFormat(trim($_POST['datefin'])))."' AND " 	: $wherePeriode .="");
			$wherePeriode = substr($wherePeriode,0, strlen($wherePeriode)-4);

			(isset($_POST['produit'])  ?  $produit =$_POST['produit'] : $produit=array());
			(isset($_POST['categorie']) && $_POST['categorie']!='0'	? $categorie = $_POST['categorie'] 	: $categorie 	= '');
			(isset($_POST['souscategorie']) && $_POST['souscategorie']!='0'	? $souscategorie = $_POST['souscategorie'] 	: $souscategorie 	= '');

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}

			$in ='';
			if(count($produit)==0 ){
				//
				if ($categorie=='TOUS'){
					if ($souscategorie!='TOUS' && $souscategorie!='0') {
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit WHERE produit.CODE_SOUSCATEGORIE LIKE '$souscategorie' ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
					else{
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit  ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
				}
				else{
					//Produit
					$sql = "SELECT CODE_PRODUIT FROM produit
								INNER JOIN souscategorie ON (produit.CODE_SOUSCATEGORIE LIKE souscategorie.CODE_SOUSCATEGORIE)
								WHERE souscategorie.CODE_CATEGORIE LIKE '$categorie' ORDER BY PRD_LIBELLE ASC";
					$query =  $cnx->prepare($sql); //Prepare the SQL
					$query->execute(); //Execute prepared SQL => $query
					while($row = $query->fetch(PDO::FETCH_ASSOC)){
						$in .="'".$row['CODE_PRODUIT']."', ";
					}
				}
			}
			elseif(count($produit)>0){
				$in='';
				foreach($produit as $key => $val){
					$in .="'$val', ";
				}
			}

			if($in!=''){
				$in = substr($in,0, strlen($in)-2);
				$in = 'produit.CODE_PRODUIT IN ('.$in.') AND ';
			}
			if($where!=''){
				$where = substr($where,0, strlen($where)-4);
			}
			$whereAll = 'AND '.$in.$where;

			if($in!=''){
				$in = 'WHERE '.substr($in,0, strlen($in)-4);
			}

			$_SESSION['DATA_ETAT']['exercice'] =$_POST['exercice'];
			$_SESSION['DATA_ETAT']['ligne'] =array();
			$_SESSION['DATA_ETAT']['WHERE']= $whereAll;
			$_SESSION['DATA_ETAT']['DATED']=$_POST['datedebut'];
			$_SESSION['DATA_ETAT']['DATEF']=$_POST['datefin'];
			$_SESSION['DATA_ETAT']['PROVINCE']='';
			$_SESSION['DATA_ETAT']['REGION']='';
			$_SESSION['DATA_ETAT']['LIBELLEETAT']= '';

			if($_POST['par']=='PRD'){
			    $sql = "SELECT * FROM produit  $in ORDER BY PRD_LIBELLE ASC; ";
				$query =  $cnx->prepare($sql); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$z = 0;
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					$tProduit = StockProduitPerime($row['CODE_PRODUIT'], $type='E',  $whereAll);
					$qeperime = $tProduit['QTE'];

					$Livraison = StockProduitParNature($row['CODE_PRODUIT'], 'LIVRAISON', $whereAll);

					$bonsortie = StockProduitParNature($row['CODE_PRODUIT'], 'BON DE SORTIE', $whereAll);

					$Declassement = StockProduitParNature($row['CODE_PRODUIT'], 'DECLASSEMENT', $whereAll);

					$transfetEnt = StockProduitParNature($row['CODE_PRODUIT'], 'TRANSFERT ENTRANT', $whereAll);

					$transfetSort = StockProduitParNature($row['CODE_PRODUIT'], 'TRANSFERT SORTANT', $whereAll);

					$reportEntree = StockProduitParNature($row['CODE_PRODUIT'], 'REPORT ENTRANT', $whereAll);

					$reportSortie = StockProduitParNature($row['CODE_PRODUIT'], 'REPORT SORTANT', $whereAll);

					$inventplus = StockProduitParNature($row['CODE_PRODUIT'], 'INVENTAIRE +', $whereAll);

					$inventmoins = StockProduitParNature($row['CODE_PRODUIT'], 'INVENTAIRE -', $whereAll);

					$entree  = $Livraison['QTE'] +  $reportEntree['QTE'] + $transfetEnt['QTE'];// ENTREE
					$sortie  = $bonsortie['QTE']  + $Declassement['QTE'] + $reportSortie['QTE'] + $transfetSort['QTE'] ; //SORTIE
					$ecart   = $inventmoins['QTE'] + $inventplus['QTE'];
					$rest 	 = $entree - ($sortie); //+ ($ecart);

					//ENTREE PERIODIQUE
					$Plivraison = StockProduitParNaturePeriode($row['CODE_PRODUIT'], 'LIVRAISON', $wherePeriode);
					$PreportEntree = StockProduitParNaturePeriode($row['CODE_PRODUIT'], 'REPORT ENTRANTT', $wherePeriode);
					$PtransfetEnt = StockProduitParNaturePeriode($row['CODE_PRODUIT'], 'TRANSFERT ENTRANT', $wherePeriode);
					$PEntree = $Plivraison['QTE'] +  $PreportEntree['QTE'] + $PtransfetEnt['QTE'];// ENTREE

					//Declassement
					$PDeclassement = StockProduitParNaturePeriode($row['CODE_PRODUIT'], 'DECLASSEMENT', $wherePeriode);
					$Pperte = $PDeclassement['QTE'];

					//SORTIE PERIODIQUE
					$Pbonsortie = StockProduitParNaturePeriode($row['CODE_PRODUIT'], 'BON DE SORTIE', $wherePeriode);
					$PreportSortie = StockProduitParNaturePeriode($row['CODE_PRODUIT'], 'REPORT SORTANT', $wherePeriode);
					$PtransfetSortie = StockProduitParNaturePeriode($row['CODE_PRODUIT'], 'TRANSFERT SORTANT', $wherePeriode);
					$PSortie = $Pbonsortie['QTE'] +  $PreportSortie['QTE'] + $PtransfetSortie['QTE'];// SORTIE
					$solde 	 = $rest + $PEntree - $PSortie - $Pperte;

					if($PEntree!=0 || $PSortie!=0 || $rest!=0 || $solde!=0){
						array_push($_SESSION['DATA_ETAT']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>addslashes($row['PRD_LIBELLE']), 'livraison'=>$Livraison['QTE'],
						'qteentre'=>$entree,'transfertsort'=>$transfetSort['QTE'], 'transfertent'=>$transfetEnt['QTE'], 'bonsortie'=>$bonsortie['QTE'],
						'qteperime'=>$qeperime, 'reportentree'=>$reportEntree['QTE'], 'reportsortie'=>$reportSortie['QTE'],'declass'=>$Declassement['QTE'],
						'qtesortie'=>$sortie, 'ecart'=>$ecart, 'stocks'=>$rest, 'Pentree'=>$PEntree, 'Psortie'=>$PSortie,  'solde'=>$solde,'unite'=>$row['ID_UNITE']));
						$z++;
					}
				}
				$_SESSION['DATA_ETAT']['nbreLigne'] = $z; //$query->rowCount();
				header('location:raptrimestriel1.php?selectedTab=rap');
			}

			else{
				$listeMag = ' AND  mouvement.CODE_MAGASIN IN ('.substr($lesMag,0,strlen($lesMag)-2).') ';

				if($in!=''){
					$in = ' AND '.substr($in,6, strlen($in));
				}

				$sql = "SELECT * FROM mouvement INNER JOIN produit ON (mouvement.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
				WHERE mouvement.MVT_TYPE LIKE 'E' AND MVT_VALID=1 $in $listeMag GROUP BY MVT_REFLOT ORDER BY produit.PRD_LIBELLE ASC ; ";
				$query =  $cnx->prepare($sql); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$z=0;
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					$tProduit = StockLotPerime($row['MVT_REFLOT'], $type='E',  $whereAll);
					$qeperime = $tProduit['QTE'];

					$Livraison = StockLotParNature($row['MVT_REFLOT'], 'LIVRAISON', $whereAll);

					$bonsortie = StockLotParNature($row['MVT_REFLOT'], 'BON DE SORTIE', $whereAll);

					$Declassement = StockLotParNature($row['MVT_REFLOT'], 'DECLASSEMENT', $whereAll);

					$transfetEnt = StockLotParNature($row['MVT_REFLOT'], 'TRANSFERT ENTRANT', $whereAll);

					$transfetSort = StockLotParNature($row['MVT_REFLOT'], 'TRANSFERT SORTANT', $whereAll);

					$reportEntree = StockLotParNature($row['MVT_REFLOT'], 'REPORT ENTRANT', $whereAll);

					$reportSortie = StockLotParNature($row['MVT_REFLOT'], 'REPORT SORTANT', $whereAll);

					$inventplus = StockLotParNature($row['MVT_REFLOT'], 'INVENTAIRE +', $whereAll);

					$inventmoins = StockLotParNature($row['MVT_REFLOT'], 'INVENTAIRE -', $whereAll);

					$entree  = $Livraison['QTE'] +  $reportEntree['QTE'] + $transfetEnt['QTE'];// ENTREE
					$sortie  = $bonsortie['QTE']  + $Declassement['QTE'] + $reportSortie['QTE'] + $transfetSort['QTE'] ; //SORTIE
					$ecart   = $inventmoins['QTE'] + $inventplus['QTE'];
					$rest 	 = $entree - ($sortie); //+ ($ecart);

					//ENTREE PERIODIQUE
					$Plivraison = StockLotParNaturePeriode($row['MVT_REFLOT'], 'LIVRAISON', $wherePeriode);
					$PreportEntree = StockLotParNaturePeriode($row['MVT_REFLOT'], 'REPORT ENTRANTT', $wherePeriode);
					$PtransfetEnt = StockLotParNaturePeriode($row['MVT_REFLOT'], 'TRANSFERT ENTRANT', $wherePeriode);
					$PEntree = $Plivraison['QTE'] +  $PreportEntree['QTE'] + $PtransfetEnt['QTE'];// ENTREE

					//Declassement
					$PDeclassement = StockLotParNaturePeriode($row['MVT_REFLOT'], 'DECLASSEMENT', $wherePeriode);
					$Pperte = $PDeclassement['QTE'];

					//SORTIE PERIODIQUE
					$Pbonsortie = StockLotParNaturePeriode($row['MVT_REFLOT'], 'BON DE SORTIE', $wherePeriode);
					$PreportSortie = StockLotParNaturePeriode($row['MVT_REFLOT'], 'REPORT SORTANT', $wherePeriode);
					$PtransfetSortie = StockLotParNaturePeriode($row['MVT_REFLOT'], 'TRANSFERT SORTANT', $wherePeriode);
					$PSortie = $Pbonsortie['QTE'] +  $PreportSortie['QTE'] + $PtransfetSortie['QTE'];// SORTIE
					$solde 	 = $rest + $PEntree - $PSortie - $Pperte;

					if ($PEntree!=0 || $PSortie!=0 || $rest!=0){
						array_push($_SESSION['DATA_ETAT']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'reflot'=>$row['MVT_REFLOT'], 'produit'=>addslashes($row['PRD_LIBELLE']), 'livraison'=>$Livraison['QTE'],
						'qteentre'=>$entree,'transfertsort'=>$transfetSort['QTE'], 'transfertent'=>$transfetEnt['QTE'], 'bonsortie'=>$bonsortie['QTE'],
						'qteperime'=>$qeperime, 'reportentree'=>$reportEntree['QTE'], 'reportsortie'=>$reportSortie['QTE'],'declass'=>$Declassement['QTE'],
						'qtesortie'=>$sortie, 'ecart'=>$ecart, 'stocks'=>$rest, 'Pentree'=>$PEntree, 'Psortie'=>$PSortie,  'solde'=>$solde,'unite'=>$row['ID_UNITE']));
						$z++;
					}
				}
				$_SESSION['DATA_ETAT']['nbreLigne'] = $z; //$query->rowCount();
				header('location:raptrimestriel2.php?selectedTab=rap');
			}


			break;

		case 'rapconsommation':
			$whereAll ="";
			$Location = '';
			$whereLocation = '';
			$where= " mouvement.MVT_NATURE LIKE 'BON DE SORTIE' AND ";
			$whereAll ="";

			if(isset($_POST['region']) && $_POST['region']=='0'){	//AU NIVEAU NATIONAL (toutes les regions, provinces et magasins)
				$Location ='';
			}
			else{	//AU NIVEAU REGION (tous les provinces et magasins d'une region)
				if (isset($_POST['province']) && $_POST['province']=='0') { //AU NIVEAU REGION (tous les provinces et magasins d'une region)
					$Location ="province.IDREGION='".$_POST['region']."' ";
				}
				else{ //AU NIVEAU PROVINCE (tous les provinces et magasins d'une province)
					$Location ="province.IDPROVINCE='".$_POST['province']."' ";
				}
			}

			if ($Location!='') {
				$whereLocation = ' WHERE '.$Location;
			}
			$sql = "SELECT * FROM magasin INNER JOIN province ON (magasin.IDPROVINCE=province.IDPROVINCE) $whereLocation ORDER BY SER_NOM ASC;";

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				die($error->getMessage().' '.__LINE__);
			}
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			$lesMag = '';
			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				$lesMag .= "'".$row['CODE_MAGASIN']."', ";
			} // while

			$where .= ' mouvement.CODE_MAGASIN IN ('.substr($lesMag,0,strlen($lesMag)-2).') AND ';


			(isset($_POST['exercice']) && $_POST['exercice']!=''	? $where .="mouvement.ID_EXERCICE = '".addslashes(trim($_POST['exercice']))."' AND " 	: $where .="");
			//(isset($_POST['datedebut']) && $_POST['datedebut']!=''  ? $where .="mouvement.MVT_DATE >= '".addslashes(mysqlFormat(trim($_POST['datedebut'])))."' AND " 	: $where .="");
			//(isset($_POST['datefin']) && $_POST['datefin']!=''  	? $where .="mouvement.MVT_DATE <= '".addslashes(mysqlFormat(trim($_POST['datefin'])))."' AND " 	: $where .="");
			(isset($_POST['produit'])  ?  $produit =$_POST['produit'] : $produit=array());
			(isset($_POST['service']) && $_POST['service']!='0'		? $where .="mouvement.CODE_MAGASIN = '".addslashes(trim($_POST['service']))."' AND " 	: $where .="");
			(isset($_POST['categorie']) && $_POST['categorie']!='0'	? $categorie = $_POST['categorie'] 	: $categorie 	= '');
			(isset($_POST['souscategorie']) && $_POST['souscategorie']!='0'	? $souscategorie = $_POST['souscategorie'] 	: $souscategorie 	= '');

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}

			$in ='';
			if(count($produit)==0 ){
				//
				if ($categorie=='TOUS'){
					if ($souscategorie!='TOUS' && $souscategorie!='0') {
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit WHERE produit.CODE_SOUSCATEGORIE LIKE '$souscategorie' ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
					else{
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit  ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
				}
				else{
					//Produit
					$sql = "SELECT CODE_PRODUIT FROM produit
					INNER JOIN souscategorie ON (produit.CODE_SOUSCATEGORIE LIKE souscategorie.CODE_SOUSCATEGORIE)
					WHERE souscategorie.CODE_CATEGORIE LIKE '$categorie' ORDER BY PRD_LIBELLE ASC";
					$query =  $cnx->prepare($sql); //Prepare the SQL
					$query->execute(); //Execute prepared SQL => $query
					while($row = $query->fetch(PDO::FETCH_ASSOC)){
						$in .="'".$row['CODE_PRODUIT']."', ";
					}
				}
			}
			elseif(count($produit)>0){
				$in='';
				foreach($produit as $key => $val){
					$in .="'$val', ";
				}
			}

			if($in!=''){
				$in = substr($in,0, strlen($in)-2);
				$in = 'produit.CODE_PRODUIT IN ('.$in.') AND ';
			}
			if($where!=''){
				$where = substr($where,0, strlen($where)-4);
			}
			$whereAll = 'AND '.$in.$where;

			if($in!=''){
				$in = ' WHERE '.substr($in,0, strlen($in)-4);
			}

			$_SESSION['DATA_ETAT']['exercice'] = $_POST['exercice'];
			$_SESSION['DATA_ETAT']['ligne'] =array();
			$_SESSION['DATA_ETAT']['WHERE'] = $whereAll;

			(isset($_POST['datedebut']) && $_POST['datedebut']!='' ? $d1 = mysqlFormat(trim($_POST['datedebut'])) : $d1='');
			(isset($_POST['datefin']) && $_POST['datefin']!='' ? $d2 = mysqlFormat(trim($_POST['datefin'])) : $d2='');

			$sql = "SELECT * FROM produit  $in ORDER BY produit.PRD_LIBELLE ASC; ";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			while($row = $query->fetch(PDO::FETCH_ASSOC)){

				$qte = StockProduitPeriode($row['CODE_PRODUIT'], 'S', $d1, $d2, $whereAll);

				//echo  'Ent'.$entree.' Sort'.$sortie.'<br>';
				array_push($_SESSION['DATA_ETAT']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'],'produit'=>addslashes($row['PRD_LIBELLE']),
				'qte'=>$qte['QTE'], 'unite'=>$row['ID_UNITE'], 'prix'=>$row['PRD_PRIXVENTE']));
			}
			$_SESSION['DATA_ETAT']['nbreLigne'] =$query->rowCount();
			//print_r($_SESSION['DATA_ETAT']);
			header('location:rapconsommation1.php?selectedTab=rap');
			break;

		case 'rapdeclassement':
			$where="";
			$whereAll ="";
			(isset($_POST['exercice']) && $_POST['exercice']!=''	? $where .="mouvement.ID_EXERCICE = '".addslashes(trim($_POST['exercice']))."' AND " 	: $where .="");
			(isset($_POST['datedebut']) && $_POST['datedebut']!=''  ? $where .="mouvement.MVT_DATE >= '".addslashes(mysqlFormat(trim($_POST['datedebut'])))."' AND " 	: $where .="");
			(isset($_POST['datefin']) && $_POST['datefin']!=''  	? $where .="mouvement.MVT_DATE <= '".addslashes(mysqlFormat(trim($_POST['datefin'])))."' AND " 	: $where .="");
			(isset($_POST['produit'])  ?  $produit =$_POST['produit'] : $produit=array());
			(isset($_POST['categorie']) && $_POST['categorie']!='0'	? $categorie = $_POST['categorie'] 	: $categorie 	= '');
			(isset($_POST['souscategorie']) && $_POST['souscategorie']!='0'	? $souscategorie = $_POST['souscategorie'] 	: $souscategorie 	= '');

			(isset($_POST['province']) && $_POST['province']!='0'	? $where .="magasin.IDPROVINCE = '".addslashes(trim($_POST['province']))."' AND " 	: $where .="");
			(isset($_POST['service']) && $_POST['service']!='0'		? $where .="mouvement.CODE_MAGASIN = '".addslashes(trim($_POST['service']))."' AND " 	: $where .="");

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}

			$in ='';
			if(count($produit)==0 ){
				//
				if ($categorie=='TOUS'){
					if ($souscategorie!='TOUS' && $souscategorie!='0') {
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit WHERE produit.CODE_SOUSCATEGORIE LIKE '$souscategorie' ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
					else{
						//Produit
						$sql = "SELECT CODE_PRODUIT FROM produit  ORDER BY PRD_LIBELLE ASC";
						$query =  $cnx->prepare($sql); //Prepare the SQL
						$query->execute(); //Execute prepared SQL => $query
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$in .="'".$row['CODE_PRODUIT']."', ";
						}
					}
				}
				else{
					//Produit
					$sql = "SELECT CODE_PRODUIT FROM produit
					INNER JOIN souscategorie ON (produit.CODE_SOUSCATEGORIE LIKE souscategorie.CODE_SOUSCATEGORIE)
					WHERE souscategorie.CODE_CATEGORIE LIKE '$categorie' ORDER BY PRD_LIBELLE ASC";
					$query =  $cnx->prepare($sql); //Prepare the SQL
					$query->execute(); //Execute prepared SQL => $query
					while($row = $query->fetch(PDO::FETCH_ASSOC)){
						$in .="'".$row['CODE_PRODUIT']."', ";
					}
				}
			}
			elseif(count($produit)>0){
				$in='';
				foreach($produit as $key => $val){
					$in .="'$val', ";
				}
			}

			if($in!=''){
				$in = substr($in,0, strlen($in)-2);
				$in = 'mouvement.CODE_PRODUIT IN ('.$in.') AND ';
			}
			if($where!=''){
				$where = substr($where,0, strlen($where)-4);
			}
			$whereAll = 'AND '.$in.$where;

			if($in!=''){
				$in = ' WHERE '.substr($in,0, strlen($in)-4);
			}

			$_SESSION['DATA_ETAT']['exercice'] = $_POST['exercice'];
			$_SESSION['DATA_ETAT']['ligne'] =array();
			$_SESSION['DATA_ETAT']['WHERE'] = $whereAll;

			(isset($_POST['datedebut']) && $_POST['datedebut']!='' ? $d1 = mysqlFormat(trim($_POST['datedebut'])) : $d1='');
			(isset($_POST['datefin']) && $_POST['datefin']!='' ? $d2 = mysqlFormat(trim($_POST['datefin'])) : $d2='');

			$sql = "SELECT * FROM mouvement INNER JOIN produit ON (mouvement.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
			INNER JOIN magasin ON (mouvement.CODE_MAGASIN LIKE magasin.CODE_MAGASIN)
			WHERE mouvement.MVT_TYPE LIKE 'S' AND mouvement.MVT_NATURE LIKE 'DECLASSEMENT'  $whereAll ORDER BY produit.PRD_LIBELLE ASC; ";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				//getField($key, $code, $field, $table)
				$codenature = getField('CODE_DECLASS', $row['ID_SOURCE'], 'CODENATDECLASS', 'declass');
				$nature = getField('CODENATDECLASS',$codenature, 'LIBNATDECLASS', 'natdeclass');
				//echo  'Ent'.$entree.' Sort'.$sortie.'<br>';
				array_push($_SESSION['DATA_ETAT']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'],'reflot'=>$row['MVT_REFLOT'],'produit'=>addslashes($row['PRD_LIBELLE']),
				'nature'=>$nature, 'qte'=>$row['MVT_QUANTITE'], 'unite'=>$row['ID_UNITE'], 'prix'=>$row['PRD_PRIXVENTE']));
			}
			$_SESSION['DATA_ETAT']['nbreLigne'] =$query->rowCount();
			//print_r($_SESSION['DATA_ETAT']);
			header('location:rapdeclassement1.php?selectedTab=rap');
			break;

		case 'fichestock':

			$where="  mouvement.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND mouvement.MVT_VALID=1 AND ";
			$whereAll ="";
			(isset($_POST['exercice']) && $_POST['exercice']!=''	? $where .="mouvement.ID_EXERCICE = '".addslashes(trim($_POST['exercice']))."' AND " 	: $where .="");
			(isset($_POST['datedebut']) && $_POST['datedebut']!=''  ? $where .="mouvement.MVT_DATE >= '".addslashes(mysqlFormat(trim($_POST['datedebut'])))."' AND " 	: $where .="");
			(isset($_POST['datefin']) && $_POST['datefin']!=''  ? $where .="mouvement.MVT_DATE <= '".addslashes(mysqlFormat(trim($_POST['datefin'])))."' AND " 	: $where .="");

			(isset($_POST['produit']) && $_POST['produit']!='0' ?   $where .="mouvement.CODE_PRODUIT LIKE '".addslashes(trim($_POST['produit']))."' AND " 	: $where .="");

			if($where!=''){
				$whereAll = ' WHERE '.substr($where,0, strlen($where)-4);
			}
			//$whereAll = ' WHERE '.$where;

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}


			$_SESSION['DATA_ETAT']['exercice'] =$_POST['exercice'];
			$_SESSION['DATA_ETAT']['ligne'] =array();
			$_SESSION['DATA_ETAT']['WHERE']= $whereAll;
			$_SESSION['DATA_ETAT']['DATEJ']=$_POST['datedebut'];
			$_SESSION['DATA_ETAT']['QTE_T_ENTREE']= 0;
			$_SESSION['DATA_ETAT']['QTE_T_SORTIE']= 0;


			//Stock courant
			$d1 = mysqlFormat($_SESSION['GL_USER']['DEBUT_EXERCICE']);
			$d2 = mysqlFormat($_POST['datedebut']);

			$Entree = StockProduitPeriode($_POST['produit'], 'E', $d1, $d2, " AND  mouvement.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' ");
			$Sortie = StockProduitPeriode($_POST['produit'], 'S', $d1, $d2, " AND  mouvement.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' ");
			$StockPrecedent = $Entree['QTE'] - $Sortie['QTE'];
			$_SESSION['DATA_ETAT']['STOCK']= $StockPrecedent;

			$sql = "SELECT * FROM mouvement INNER JOIN produit ON (mouvement.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
			 $whereAll ORDER BY produit.PRD_LIBELLE, mouvement.ID_MOUVEMENT, mouvement.MVT_DATE ASC; ";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query



			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				//echo  'Ent'.$entree.' Sort'.$sortie.'<br>';

				if (isset($row['MVT_TYPE']) && $row['MVT_TYPE']=='E') {
					$StockPrecedent += $row['MVT_QUANTITE'];

					array_push($_SESSION['DATA_ETAT']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'reflot'=>$row['MVT_REFLOT'],'produit'=>addslashes($row['PRD_LIBELLE']),
					'nature'=>$row['MVT_NATURE'],'typemvt'=>$row['MVT_TYPE'], 'qteentree'=>$row['MVT_QUANTITE'], 'qtesortie'=>0,'dateentree'=>frFormat2($row['MVT_DATE']),'dateperemp'=>$row['MVT_DATEPEREMP'],'pa'=>$row['MVT_PA'],'pv'=>$row['MVT_PV'],
					'unite'=>$row['ID_UNITE'], 'stock'=>$StockPrecedent,'etat'=>$row['MVT_VALID']));

					$tEntree +=$row['MVT_QUANTITE'];

				}

				if (isset($row['MVT_TYPE']) && $row['MVT_TYPE']=='S') {
					$StockPrecedent -= $row['MVT_QUANTITE'];

					array_push($_SESSION['DATA_ETAT']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'reflot'=>$row['MVT_REFLOT'],'produit'=>addslashes($row['PRD_LIBELLE']),
					'nature'=>$row['MVT_NATURE'],'typemvt'=>$row['MVT_TYPE'], 'qteentree'=>0,'qtesortie'=>$row['MVT_QUANTITE'],'dateentree'=>frFormat2($row['MVT_DATE']),'dateperemp'=>$row['MVT_DATEPEREMP'],'pa'=>$row['MVT_PA'],'pv'=>$row['MVT_PV'],
					'unite'=>$row['ID_UNITE'], 'stock'=>$StockPrecedent,'etat'=>$row['MVT_VALID']));

					$tSortie +=$row['MVT_QUANTITE'];

				}
				$_SESSION['DATA_ETAT']['PRODUIT']= addslashes($row['PRD_LIBELLE']);
			}
			$_SESSION['DATA_ETAT']['nbreLigne'] =$query->rowCount();
			$_SESSION['DATA_ETAT']['QTE_T_ENTREE']= $tEntree;
			$_SESSION['DATA_ETAT']['QTE_T_SORTIE']= $tSortie;


			//print_r($_SESSION['DATA_ETAT']);
			header('location:rapficheproduit1.php?selectedTab=rap');
			break;

		case 'fillSousCatProduitFiche':
		$list = '<select name="produit[]" name="produit[]" class="formStyle" size="10"  multiple="multiple">';
		if(isset($_POST["souscategorie"])){
			(isset($_POST["souscategorie"]) && $_POST["souscategorie"]=='TOUS' ? $where='' : $where=" WHERE produit.CODE_SOUSCATEGORIE ='".$_POST["souscategorie"]."'");
			//SQL
			$sql  = "SELECT * FROM produit	$where ORDER BY produit.PRD_LIBELLE ASC;";
			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			$list .= '<option value="0"></option>';
			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				$list .= '<option value="'.$row['CODE_PRODUIT'].'" >'.(stripslashes($row['PRD_LIBELLE'])).'</option>';
			}
		}
		echo $list.'</select>';
		break;



		//Log in User

		case 'rapprdaperimer':
			$in='';
			if(isset($_POST['service']) && $_POST['service']!='0' && $_POST['province']!='0' && $_POST['region']!='0'){
				//Un magasin
				$where="  mouvement.CODE_MAGASIN LIKE '".$_POST['service']."' AND ";
			}
			else if(isset($_POST['service']) && $_POST['service']=='0' && $_POST['province']!='0' && $_POST['region']!='0'){
				//Tous les magasin de la province
				$sql ="SELECT magasin.* FROM `magasin` WHERE IDPROVINCE LIKE '".$_POST['province']."'";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}
				$query =  $cnx->prepare($sql); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$in ='';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					$in .="'".$row['CODE_MAGASIN']."', ";
				}
				if ($in!='') {
					$in = substr($in,0, strlen($in)-2);
					$in = "  mouvement.CODE_MAGASIN IN (".$in.") AND ";
				}

			}
			else if(isset($_POST['service']) && $_POST['service']=='0' && $_POST['province']=='0' && $_POST['region']!='0'){
				//Tous les magasin de la region (National)
				$sql ="SELECT magasin.* FROM `magasin` INNER JOIN province (magasin.IDPROVINCE LIKE province.IDPROVINCE)
				INNER JOIN region (province.IDREGION LIKE region.IDREGION)
				WHERE IDREGION LIKE '".$_POST['region']."'";

				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}
				$query =  $cnx->prepare($sql); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$in ='';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					$in .="'".$row['CODE_MAGASIN']."', ";
				}
				if ($in!='') {
					$in = substr($in,0, strlen($in)-2);
					$in = "  mouvement.CODE_MAGASIN IN (".$in.") AND ";
				}

			}
			else if(isset($_POST['service']) && $_POST['service']=='0' && $_POST['province']=='0' && $_POST['region']=='0'){
				//Tous les magasin de la region (National)
				$sql ="SELECT magasin.* FROM `magasin`";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}
				$query =  $cnx->prepare($sql); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$in ='';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					$in .="'".$row['CODE_MAGASIN']."', ";
				}
				if ($in!='') {
					$in = substr($in,0, strlen($in)-2);
					$in = "  mouvement.CODE_MAGASIN IN (".$in.") AND ";
				}

			}

			$where ="";
			$whereAll ="";
			(isset($_POST['exercice']) && $_POST['exercice']!=''? $where .="mouvement.ID_EXERCICE = '".addslashes(trim($_POST['exercice']))."' AND " 	: $where .="");
			(isset($_POST['datedebut']) && $_POST['datedebut']!=''  ? $where .="mouvement.MVT_DATEPEREMP >= '".addslashes(mysqlFormat(trim($_POST['datedebut'])))."' AND " 	: $where .="");
			(isset($_POST['datefin']) && $_POST['datefin']!=''  ? $where .="mouvement.MVT_DATEPEREMP <= '".addslashes(mysqlFormat(trim($_POST['datefin'])))."' AND " 	: $where .="");

			//Générer date fin
			//$datePeremption = '';
//			if(isset($_POST['jours']) && $_POST['jours']!='0'){
//				$date = strtotime(date('Y-m-d'));
//				$date = strtotime($_POST['jours'], $date);
//				$datePeremption =date('d-m-Y',$date);
//				$where .= "mouvement.MVT_DATEPEREMP <= '".addslashes(mysqlFormat(trim($datePeremption)))."' AND " ;
//			}

			if($in!=''){
				$where = $in.$where;
			}

			if($where!=''){
				$where = substr($where,0, strlen($where)-4);
			}
			$whereAll = 'AND '.$where;

			$_SESSION['DATA_ETAT']['exercice'] = $_POST['exercice'];
			$_SESSION['DATA_ETAT']['ligne'] = array();
			$_SESSION['DATA_ETAT']['WHERE'] = $whereAll;
			$_SESSION['DATA_ETAT']['DATEJ'] = $_SESSION['GL_USER']['DEBUT_EXERCICE'];
			$_SESSION['DATA_ETAT']['DATEFIN'] = $_SESSION['GL_USER']['FIN_EXERCICE'];
			$_SESSION['DATA_ETAT']['DATEPEREMPTION'] = $_POST['datedebut']. ' - '.$_POST['datefin'];

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}

			$sql = "SELECT * FROM mouvement INNER JOIN produit ON (mouvement.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
			WHERE mouvement.MVT_TYPE LIKE 'E'  $whereAll GROUP BY mouvement.MVT_REFLOT ORDER BY produit.PRD_LIBELLE ASC; ";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			$Nbre = 0;
			while($row = $query->fetch(PDO::FETCH_ASSOC)){
					array_push($_SESSION['DATA_ETAT']['ligne'], array('reflot'=>$row['MVT_REFLOT'], 'codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>addslashes($row['PRD_LIBELLE']),
					'dateperemption'=>$row['MVT_DATEPEREMP']));
					$Nbre++;
			}
			$_SESSION['DATA_ETAT']['nbreLigne'] = $Nbre; //$query->rowCount();
			header('location:rapproduitaperime1.php?selectedTab=home');
			break;

		case 'rapstocksupseuilmax':

			$in='';
			if(isset($_POST['service']) && $_POST['service']!='0' && $_POST['province']!='0' && $_POST['region']!='0'){
				//Un magasin
				$where="  mouvement.CODE_MAGASIN LIKE '".$_POST['service']."' AND ";
			}
			else if(isset($_POST['service']) && $_POST['service']=='0' && $_POST['province']!='0' && $_POST['region']!='0'){
				//Tous les magasin de la province
				$sql ="SELECT magasin.* FROM `magasin` WHERE IDPROVINCE LIKE '".$_POST['province']."'";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}
				$query =  $cnx->prepare($sql); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$in ='';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					$in .="'".$row['CODE_MAGASIN']."', ";
				}
				if ($in!='') {
					$in = substr($in,0, strlen($in)-2);
					$in = "  mouvement.CODE_MAGASIN IN (".$in.") AND ";
				}

			}
			else if(isset($_POST['service']) && $_POST['service']=='0' && $_POST['province']=='0' && $_POST['region']!='0'){
				//Tous les magasin de la region (National)
				$sql ="SELECT magasin.* FROM `magasin` INNER JOIN province (magasin.IDPROVINCE LIKE province.IDPROVINCE)
				INNER JOIN region (province.IDREGION LIKE region.IDREGION)
				WHERE IDREGION LIKE '".$_POST['region']."'";

				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}
				$query =  $cnx->prepare($sql); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$in ='';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					$in .="'".$row['CODE_MAGASIN']."', ";
				}
				if ($in!='') {
					$in = substr($in,0, strlen($in)-2);
					$in = "  mouvement.CODE_MAGASIN IN (".$in.") AND ";
				}

			}
			else if(isset($_POST['service']) && $_POST['service']=='0' && $_POST['province']=='0' && $_POST['region']=='0'){
				//Tous les magasin de la region (National)
				$sql ="SELECT magasin.* FROM `magasin`";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}
				$query =  $cnx->prepare($sql); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$in ='';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					$in .="'".$row['CODE_MAGASIN']."', ";
				}
				if ($in!='') {
					$in = substr($in,0, strlen($in)-2);
					$in = "  mouvement.CODE_MAGASIN IN (".$in.") AND ";
				}

			}

			$where ="";
			$whereAll ="";
			(isset($_POST['exercice']) && $_POST['exercice']!=''	? $where .="mouvement.ID_EXERCICE = '".addslashes(trim($_POST['exercice']))."' AND " 	: $where .="");
			(isset($_POST['datedebut']) && $_POST['datedebut']!=''  ? $where .="mouvement.MVT_DATE >= '".addslashes(mysqlFormat(trim($_POST['datedebut'])))."' AND " 	: $where .="");
			(isset($_POST['datefin']) && $_POST['datefin']!=''  ? $where .="mouvement.MVT_DATE <= '".addslashes(mysqlFormat(trim($_POST['datefin'])))."' AND " 	: $where .="");

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}

			$sql = "SELECT * FROM produit ORDER BY PRD_LIBELLE ASC; ";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			if($in!=''){
				$where = $in.$where;
			}

			if($where!=''){
				$where = substr($where,0, strlen($where)-4);
			}
			$whereAll = 'AND '.$where;

			$_SESSION['DATA_ETAT']['exercice'] =$_POST['exercice'];
			$_SESSION['DATA_ETAT']['ligne'] =array();
			$_SESSION['DATA_ETAT']['WHERE']= $whereAll;
			$_SESSION['DATA_ETAT']['DATEJ']=$_POST['datedebut'];
			$_SESSION['DATA_ETAT']['DATEFIN']=$_POST['datefin'];

			$Nbre = 0;
			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				$tProduit = StockProduitPerime($row['CODE_PRODUIT'], $type='E',  $whereAll);
				$qeperime = $tProduit['QTE'];

				$Livraison = StockProduitParNature($row['CODE_PRODUIT'], 'LIVRAISON', $whereAll);

				$bonsortie = StockProduitParNature($row['CODE_PRODUIT'], 'BON DE SORTIE', $whereAll);

				$Declassement = StockProduitParNature($row['CODE_PRODUIT'], 'DECLASSEMENT', $whereAll);

				$transfetEnt = StockProduitParNature($row['CODE_PRODUIT'], 'TRANSFERT ENTRANT', $whereAll);

				$transfetSort = StockProduitParNature($row['CODE_PRODUIT'], 'TRANSFERT SORTANT', $whereAll);

				$reportEntree = StockProduitParNature($row['CODE_PRODUIT'], 'REPORT ENTRANT', $whereAll);

				$reportSortie = StockProduitParNature($row['CODE_PRODUIT'], 'REPORT SORTANT', $whereAll);

				$inventplus = StockProduitParNature($row['CODE_PRODUIT'], 'INVENTAIRE +', $whereAll);

				$inventmoins = StockProduitParNature($row['CODE_PRODUIT'], 'INVENTAIRE -', $whereAll);

				//Declassement
				$PDeclassement = StockProduitParNature($row['CODE_PRODUIT'], 'DECLASSEMENT', $whereAll);

				$entree  = $Livraison['QTE'] +  $reportEntree['QTE'] + $transfetEnt['QTE'];// ENTREE
				$sortie  = $bonsortie['QTE']  + $Declassement['QTE'] + $reportSortie['QTE'] + $transfetSort['QTE'] ; //SORTIE
				$ecart   = $inventmoins['QTE'] + $inventplus['QTE'];
				$rest 	 = $entree - ($sortie) ;

				if ($rest!=0 && $rest>$row['PRD_SEUILMAX']) {
					array_push($_SESSION['DATA_ETAT']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>addslashes($row['PRD_LIBELLE']), 'livraison'=>$Livraison['QTE'],
					'qteentre'=>$entree,'transfertsort'=>$transfetSort['QTE'], 'transfertent'=>$transfetEnt['QTE'], 'bonsortie'=>$bonsortie['QTE'],
					'qteperime'=>$qeperime, 'reportentree'=>$reportEntree['QTE'], 'reportsortie'=>$reportSortie['QTE'],'declass'=>$Declassement['QTE'],
					'qtesortie'=>$sortie, 'ecart'=>$ecart,  'stocks'=>$rest,  'seuil'=>$row['PRD_SEUILMAX'],'unite'=>$row['ID_UNITE']));
					$Nbre++;
				}

			}
			$_SESSION['DATA_ETAT']['nbreLigne'] = $Nbre; //$query->rowCount();
			header('location:rapstocksupseuilmax1.php?selectedTab=rap');
			break;

		case 'hrapprdaperimer':
			$in='';
			if(isset($_POST['service']) && $_POST['service']!='0' && $_POST['province']!='0' && $_POST['region']!='0'){
				//Un magasin
				$where="  mouvement.CODE_MAGASIN LIKE '".$_POST['service']."' AND ";
			}
			else if(isset($_POST['service']) && $_POST['service']=='0' && $_POST['province']!='0' && $_POST['region']!='0'){
				//Tous les magasin de la province
				$sql ="SELECT magasin.* FROM `magasin` WHERE IDPROVINCE LIKE '".$_POST['province']."'";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}
				$query =  $cnx->prepare($sql); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$in ='';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					$in .="'".$row['CODE_MAGASIN']."', ";
				}
				if ($in!='') {
					$in = substr($in,0, strlen($in)-2);
					$in = "  mouvement.CODE_MAGASIN IN (".$in.") AND ";
				}

			}
			else if(isset($_POST['service']) && $_POST['service']=='0' && $_POST['province']=='0' && $_POST['region']!='0'){
				//Tous les magasin de la region (National)
				$sql ="SELECT magasin.* FROM `magasin` INNER JOIN province (magasin.IDPROVINCE LIKE province.IDPROVINCE)
				INNER JOIN region (province.IDREGION LIKE region.IDREGION)
				WHERE IDREGION LIKE '".$_POST['region']."'";

				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}
				$query =  $cnx->prepare($sql); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$in ='';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					$in .="'".$row['CODE_MAGASIN']."', ";
				}
				if ($in!='') {
					$in = substr($in,0, strlen($in)-2);
					$in = "  mouvement.CODE_MAGASIN IN (".$in.") AND ";
				}

			}
			else if(isset($_POST['service']) && $_POST['service']=='0' && $_POST['province']=='0' && $_POST['region']=='0'){
				//Tous les magasin de la region (National)
				$sql ="SELECT magasin.* FROM `magasin`";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}
				$query =  $cnx->prepare($sql); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$in ='';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					$in .="'".$row['CODE_MAGASIN']."', ";
				}
				if ($in!='') {
					$in = substr($in,0, strlen($in)-2);
					$in = "  mouvement.CODE_MAGASIN IN (".$in.") AND ";
				}

			}

			$where ="";
			$whereAll ="";
			(isset($_POST['exercice']) && $_POST['exercice']!=''? $where .="mouvement.ID_EXERCICE = '".addslashes(trim($_POST['exercice']))."' AND " 	: $where .="");
			(isset($_POST['datedebut']) && $_POST['datedebut']!=''  ? $where .="mouvement.MVT_DATEPEREMP >= '".addslashes(mysqlFormat(trim($_POST['datedebut'])))."' AND " 	: $where .="");
			(isset($_POST['datefin']) && $_POST['datefin']!=''  ? $where .="mouvement.MVT_DATEPEREMP <= '".addslashes(mysqlFormat(trim($_POST['datefin'])))."' AND " 	: $where .="");

			if(isset($_POST['datedebut']) && isset($_POST['datefin'])){
				$where .= "mouvement.MVT_DATEPEREMP >= '".addslashes(mysqlFormat(trim($_POST['datedebut'])))."' AND mouvement.MVT_DATEPEREMP <= '".addslashes(mysqlFormat(trim($_POST['datefin'])))."' AND " ;
			}

			//Générer date fin
			//$datePeremption = '';
//			if(isset($_POST['jours']) && $_POST['jours']!='0'){
//				$date = strtotime(date('Y-m-d'));
//				$date = strtotime($_POST['jours'], $date);
//				$datePeremption =date('d-m-Y',$date);
//				$where .= "mouvement.MVT_DATEPEREMP <= '".addslashes(mysqlFormat(trim($datePeremption)))."' AND " ;
//			}

			if($in!=''){
				$where = $in.$where;
			}

			if($where!=''){
				$where = substr($where,0, strlen($where)-4);
			}
			$whereAll = 'AND '.$where;

			$_SESSION['DATA_ETAT']['exercice'] = $_POST['exercice'];
			$_SESSION['DATA_ETAT']['ligne'] = array();
			$_SESSION['DATA_ETAT']['WHERE'] = $whereAll;
			$_SESSION['DATA_ETAT']['DATEJ'] = $_SESSION['GL_USER']['DEBUT_EXERCICE'];
			$_SESSION['DATA_ETAT']['DATEFIN'] = $_SESSION['GL_USER']['FIN_EXERCICE'];
			$_SESSION['DATA_ETAT']['DATEPEREMPTION'] = $datePeremption;

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}

			$sql = "SELECT * FROM mouvement INNER JOIN produit ON (mouvement.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
			WHERE mouvement.MVT_TYPE LIKE 'E'  $whereAll GROUP BY mouvement.MVT_REFLOT ORDER BY produit.PRD_LIBELLE ASC; ";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			$Nbre = 0;
			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				array_push($_SESSION['DATA_ETAT']['ligne'], array('reflot'=>$row['MVT_REFLOT'], 'codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>addslashes($row['PRD_LIBELLE']),
				'dateperemption'=>$row['MVT_DATEPEREMP']));
				$Nbre++;
			}
			$_SESSION['DATA_ETAT']['nbreLigne'] = $Nbre; //$query->rowCount();
			header('location:hrapproduitaperime1.php?selectedTab=home');
			break;

		case 'hrapstocksupseuilmax':

			$in='';
			if(isset($_POST['service']) && $_POST['service']!='0' && $_POST['province']!='0' && $_POST['region']!='0'){
				//Un magasin
				$where="  mouvement.CODE_MAGASIN LIKE '".$_POST['service']."' AND ";
			}
			else if(isset($_POST['service']) && $_POST['service']=='0' && $_POST['province']!='0' && $_POST['region']!='0'){
				//Tous les magasin de la province
				$sql ="SELECT magasin.* FROM `magasin` WHERE IDPROVINCE LIKE '".$_POST['province']."'";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}
				$query =  $cnx->prepare($sql); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$in ='';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					$in .="'".$row['CODE_MAGASIN']."', ";
				}
				if ($in!='') {
					$in = substr($in,0, strlen($in)-2);
					$in = "  mouvement.CODE_MAGASIN IN (".$in.") AND ";
				}

			}
			else if(isset($_POST['service']) && $_POST['service']=='0' && $_POST['province']=='0' && $_POST['region']!='0'){
				//Tous les magasin de la region (National)
				$sql ="SELECT magasin.* FROM `magasin` INNER JOIN province (magasin.IDPROVINCE LIKE province.IDPROVINCE)
				INNER JOIN region (province.IDREGION LIKE region.IDREGION)
				WHERE IDREGION LIKE '".$_POST['region']."'";

				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}
				$query =  $cnx->prepare($sql); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$in ='';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					$in .="'".$row['CODE_MAGASIN']."', ";
				}
				if ($in!='') {
					$in = substr($in,0, strlen($in)-2);
					$in = "  mouvement.CODE_MAGASIN IN (".$in.") AND ";
				}

			}
			else if(isset($_POST['service']) && $_POST['service']=='0' && $_POST['province']=='0' && $_POST['region']=='0'){
				//Tous les magasin de la region (National)
				$sql ="SELECT magasin.* FROM `magasin`";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}
				$query =  $cnx->prepare($sql); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query

				$in ='';
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					$in .="'".$row['CODE_MAGASIN']."', ";
				}
				if ($in!='') {
					$in = substr($in,0, strlen($in)-2);
					$in = "  mouvement.CODE_MAGASIN IN (".$in.") AND ";
				}

			}

			$where ="";
			$whereAll ="";
			(isset($_POST['exercice']) && $_POST['exercice']!=''	? $where .="mouvement.ID_EXERCICE = '".addslashes(trim($_POST['exercice']))."' AND " 	: $where .="");
			(isset($_POST['datedebut']) && $_POST['datedebut']!=''  ? $where .="mouvement.MVT_DATE >= '".addslashes(mysqlFormat(trim($_POST['datedebut'])))."' AND " 	: $where .="");
			(isset($_POST['datefin']) && $_POST['datefin']!=''  ? $where .="mouvement.MVT_DATE <= '".addslashes(mysqlFormat(trim($_POST['datefin'])))."' AND " 	: $where .="");

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}

			$sql = "SELECT * FROM produit ORDER BY PRD_LIBELLE ASC; ";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			if($in!=''){
				$where = $in.$where;
			}

			if($where!=''){
				$where = substr($where,0, strlen($where)-4);
			}
			$whereAll = 'AND '.$where;

			$_SESSION['DATA_ETAT']['exercice'] =$_POST['exercice'];
			$_SESSION['DATA_ETAT']['ligne'] =array();
			$_SESSION['DATA_ETAT']['WHERE']= $whereAll;
			$_SESSION['DATA_ETAT']['DATEJ']=$_POST['datedebut'];
			$_SESSION['DATA_ETAT']['DATEFIN']=$_POST['datefin'];

			$Nbre = 0;
			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				$tProduit = StockProduitPerime($row['CODE_PRODUIT'], $type='E',  $whereAll);
				$qeperime = $tProduit['QTE'];

				$Livraison = StockProduitParNature($row['CODE_PRODUIT'], 'LIVRAISON', $whereAll);

				$bonsortie = StockProduitParNature($row['CODE_PRODUIT'], 'BON DE SORTIE', $whereAll);

				$Declassement = StockProduitParNature($row['CODE_PRODUIT'], 'DECLASSEMENT', $whereAll);

				$transfetEnt = StockProduitParNature($row['CODE_PRODUIT'], 'TRANSFERT ENTRANT', $whereAll);

				$transfetSort = StockProduitParNature($row['CODE_PRODUIT'], 'TRANSFERT SORTANT', $whereAll);

				$reportEntree = StockProduitParNature($row['CODE_PRODUIT'], 'REPORT ENTRANT', $whereAll);

				$reportSortie = StockProduitParNature($row['CODE_PRODUIT'], 'REPORT SORTANT', $whereAll);

				$inventplus = StockProduitParNature($row['CODE_PRODUIT'], 'INVENTAIRE +', $whereAll);

				$inventmoins = StockProduitParNature($row['CODE_PRODUIT'], 'INVENTAIRE -', $whereAll);

				//Declassement
				$PDeclassement = StockProduitParNature($row['CODE_PRODUIT'], 'DECLASSEMENT', $whereAll);

				$entree  = $Livraison['QTE'] +  $reportEntree['QTE'] + $transfetEnt['QTE'];// ENTREE
				$sortie  = $bonsortie['QTE']  + $Declassement['QTE'] + $reportSortie['QTE'] + $transfetSort['QTE'] ; //SORTIE
				$ecart   = $inventmoins['QTE'] + $inventplus['QTE'];
				$rest 	 = $entree - ($sortie) ;

				if ($rest!=0 && $rest>$row['PRD_SEUILMAX']) {
					array_push($_SESSION['DATA_ETAT']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>addslashes($row['PRD_LIBELLE']), 'livraison'=>$Livraison['QTE'],
					'qteentre'=>$entree,'transfertsort'=>$transfetSort['QTE'], 'transfertent'=>$transfetEnt['QTE'], 'bonsortie'=>$bonsortie['QTE'],
					'qteperime'=>$qeperime, 'reportentree'=>$reportEntree['QTE'], 'reportsortie'=>$reportSortie['QTE'],'declass'=>$Declassement['QTE'],
					'qtesortie'=>$sortie, 'ecart'=>$ecart,  'stocks'=>$rest,  'seuil'=>$row['PRD_SEUILMAX'],'unite'=>$row['ID_UNITE']));
					$Nbre++;
				}

			}
			$_SESSION['DATA_ETAT']['nbreLigne'] = $Nbre; //$query->rowCount();
			header('location:hrapstocksupseuilmax1.php?selectedTab=rap');
			break;

		default : ///Nothing
	}
}
elseif($myaction !='')
switch($myaction){

	default : ///Nothing
		//header('location:../index.php');

}
elseif($myaction =='' && $do ='') header('location:../index.php');

?>
