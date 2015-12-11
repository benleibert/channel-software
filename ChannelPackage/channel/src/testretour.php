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


		//Insert
		$sql  = "INSERT INTO `commande` (`ID_EXERCICE` ,`CODE_FOUR` ,`CODE_COMMANDE` ,`CDE_LIBELLE` ,`CDE_DATE` ,`CDE_STATUT`, `CODE_MAGASIN`)
		VALUES ('".addslashes('2000')."', '".addslashes('0')."', '".addslashes('Z23333')."' , '".addslashes('Alpha')."' ,
		'".addslashes('2013-02-02')."' , '0','".addslashes('CSPS-S30')."')";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		var_dump($query->execute()); //Execute prepared SQL => $query		var_dump();