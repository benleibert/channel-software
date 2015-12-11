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


$sql = "SELECT * FROM article;";
try {
	$cnx = new PDO('mysql:host=localhost;dbname=channel_db', 'root', ''); //Connexion to database
}
catch (PDOException $error) { //Treat error
	die($error->getMessage().' '.__LINE__);
}


$query =  $cnx->prepare($sql); //Prepare the SQL
$query->execute(); //Execute prepared SQL => $query

$sqlPrd = '';
while($row = $query->fetch(PDO::FETCH_ASSOC)){
	$sqlPrd .= "INSERT INTO `produit` (`CODE_PRODUIT` ,`ID_UNITE` ,`CODE_CATEGORIE` ,`PRD_LIBELLE` ,`CONDITIONNE` ,
	`PRD_PRIX` ,`PRD_BAREME` ,`PRIX_PLAT` ,`DESCRIPTION` ,`POIDS` ,`SEUIL_APPRO`) VALUES (
	'".$row['ID_ARTICLE']."', '".$row['ID_UNITE']."', '".$row['ID_CATEGORIE']."', '".$row['ID_CATEGORIE']."',  '".$row['ID_CATEGORIE']."' ,
	 '".$row['ID_CATEGORIE']."' ,  '".$row['ID_CATEGORIE']."' ,  '".$row['ID_CATEGORIE']."' ,  '".$row['ID_CATEGORIE']."',
	 '".$row['ID_CATEGORIE']."',  '".$row['ID_CATEGORIE']."');";

} // while

?>
