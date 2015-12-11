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

if($do !=''){
switch($do){
	case 'fill':
		$list = '<select name="produit[]" name="produit[]" class="formStyle"  multiple="multiple">';
		if(isset($_POST["categorie"])){
			(isset($_POST["categorie"]) && $_POST["categorie"]=='TOUS' ? $where='' : $where=" WHERE produit.CODE_CATEGORIE ='".$_POST["categorie"]."'");
			//SQL
			$sql  = "SELECT produit.CODE_CATEGORIE, conditionmt.CODE_PRODUIT, conditionmt.ID_CONDIT, conditionmt.ID_UNITE, conditionmt.CND_LIBELLE FROM conditionmt ";
			$sql .= "INNER JOIN produit ON (conditionmt.CODE_PRODUIT LIKE produit.CODE_PRODUIT) ";
			$sql .= "$where";
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
				$list .= '<option value="'.$row['ID_CONDIT'].'" >'.(stripslashes($row['CND_LIBELLE'])).'</option>';
			}
		}
		echo $list.'</select>';
		break;


	case 'journal':
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

		$in ='';
		if(count($produit)==0 && $categorie=='TOUS'){
			$sql = "SELECT ID_CONDIT FROM conditionmt ORDER BY CND_LIBELLE ASC";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				$in .=$row['ID_CONDIT'].', ';
			}
		}
		elseif(count($produit)==0 && $categorie=='PA'){
			$sql = "SELECT ID_CONDIT FROM conditionmt INNER JOIN produit ON (conditionmt.CODE_PRODUIT LIKE produit.CODE_PRODUIT) ";
			$sql .= "WHERE produit.CODE_CATEGORIE LIKE 'PA' ORDER BY CND_LIBELLE ASC";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				$in .=$row['ID_CONDIT'].', ';
			}
		}
		elseif(count($produit)==0 && $categorie=='UC'){
			$sql = "SELECT ID_CONDIT FROM conditionmt INNER JOIN produit ON (conditionmt.CODE_PRODUIT LIKE produit.CODE_PRODUIT) ";
			$sql .= "WHERE produit.CODE_CATEGORIE LIKE 'UC' ORDER BY CND_LIBELLE ASC";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				$in .=$row['ID_CONDIT'].', ';
			}
		}
		elseif(count($produit)>0){
			$in='';
			foreach($produit as $key => $val){
				$in .=$val.', ';
			}
		}

		if($in!=''){
			$in = substr($in,0, strlen($in)-2);
			$in = 'ID_CONDIT IN ('.$in.') AND ';
		}
		if($where!=''){
			$where = substr($where,0, strlen($where)-4);
		}

		//if($in!=''){
		//	$in = 'WHERE '.substr($in,0, strlen($in)-4);
		//}

		switch($typemouvement){
			case '1'://'1'=>'LIVRAISONS'

				break;

			case '2'://'2'=>'AUTRES LIVRAISONS'

				break;

			case '3'://'3'=>'DOTATIONS DES ETABLISSEMENTS'

				$whereAll = "WHERE  (mouvement.MVT_NATURE LIKE 'DOTATION - 1DOT' OR mouvement.MVT_NATURE LIKE 'DOTATION - 2DOT' OR mouvement.MVT_NATURE LIKE 'DOTATION - 3DOT') AND $in $where";

				$_SESSION['DATA_ETAT']['ligne'] =array();
				$_SESSION['DATA_ETAT']['WHERE']= $whereAll;
				$_SESSION['DATA_ETAT']['DATEJ']=$_POST['datedebut'];

				$sql = "SELECT * FROM `mouvement`  $whereAll";

				$query =  $cnx->prepare($sql); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					//Corresp
					$sqlCoresp = "SELECT * FROM `dotation` INNER JOIN dot_cnd ON (dotation.ID_DOTATION=dot_cnd.ID_DOTATION)
					WHERE dot_cnd.ID_DOTATION=".$row['ID_SOURCE'];
					$query2 =  $cnx->prepare($sqlCoresp); //Prepare the SQL
					$query2->execute(); //Execute prepared SQL => $query

					$corresp = array();
					while($row2 = $query2->fetch(PDO::FETCH_ASSOC)){
						array_push($corresp,$row2);
					}

					array_push($_SESSION['DATA_ETAT']['ligne'], array('codeproduit'=>$row['ID_CONDIT'], 'produit'=>getConditionnement($row['ID_CONDIT']),
						'nature'=>$row['MVT_NATURE'], 'magasin'=>$row['CODE_MAGASIN'],'qte'=>$row['MVT_QUANTITE'],'unite'=>$row['MVT_UNITE'],
						'correspondant'=>$corresp,));
				}
				$_SESSION['DATA_ETAT']['nbreLigne'] =$query->rowCount();
				header('location:analysejournal.php?selectedTab=int');
				break;


			case '4'://'4'=>'DOTATIONS BAC'

				break;


			case '5'://'5'=>'DOTATIONS USTENSILES'

				break;


			case '6'://'6'=>'AUTRES DOTATIONS'

				break;


			case '7'://'7'=>'PERTES'

				break;


			case '8'://'8'=>'RECONDITIONNEMENTS'

				break;


			case '9'://'9'=>'REPORTS'

				break;


			case '10'://'10'=>'TRANSFERTS'

				break;


		}
	}
}
?>
