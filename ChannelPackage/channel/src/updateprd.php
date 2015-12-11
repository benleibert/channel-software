<?php

//IMPORTANT VARIABLES
define('DBSERVER','localhost');
define('DB','patrimoine2_db');
define('DBUSER','root');
define('DBPWD','');
define('DBD','mysql:host=localhost;dbname=patrimoine2_db');

try {
	$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
}
catch (PDOException $error) { //Treat error
	//("Erreur de connexion : " . $error->getMessage() );
	header('location:errorPage.php');
}
//echo 	$sql = "SELECT 	produit.* FROM 	produit  order by CODE_PRODUIT ASC;";
//$query =  $cnx->prepare($sql); //Prepare the SQL
//$query->execute(); //Execute prepared SQL => $query
//
//$bs ='';
//$be ='';
//while($row = $query->fetch(PDO::FETCH_ASSOC)){
//
//	$bs .= 'UPDATE `prd_bonsortie` SET 	BSPRD_UNITE="'.$row['ID_UNITE'].'"
//			WHERE  CODE_PRODUIT LIKE "'.addslashes(stripslashes($row['CODE_PRODUIT'])).'";'."\n";
//}
//
//		$fp = fopen('../db/updateproduit.sql', 'w');
//		fwrite($fp, $bs);
//		fclose($fp);
//echo "Terminé <br />";

//echo 	$sql = "SELECT 	produit.* FROM 	produit  order by CODE_PRODUIT ASC;";
//$query =  $cnx->prepare($sql); //Prepare the SQL
//$query->execute(); //Execute prepared SQL => $query
//
//$bs ='';
//$be ='';
//while($row = $query->fetch(PDO::FETCH_ASSOC)){
//
//	$bs .= 'UPDATE `prd_livraison` SET 	LIV_UNITE="'.$row['ID_UNITE'].'"
//			WHERE  CODE_PRODUIT LIKE "'.addslashes(stripslashes($row['CODE_PRODUIT'])).'";'."\n";
//}
//
//$fp = fopen('../db/updateproduitliv.sql', 'w');
//fwrite($fp, $bs);
//fclose($fp);
//echo "Terminé <br />";

echo 	$sql = "SELECT 	produit.* FROM 	produit  order by CODE_PRODUIT ASC;";
$query =  $cnx->prepare($sql); //Prepare the SQL
$query->execute(); //Execute prepared SQL => $query

$bs ='';
$be ='';
while($row = $query->fetch(PDO::FETCH_ASSOC)){

	$bs .= 'UPDATE `mouvement` SET 	MVT_UNITE="'.$row['ID_UNITE'].'"
			WHERE  CODE_PRODUIT LIKE "'.addslashes(stripslashes($row['CODE_PRODUIT'])).'";'."\n";
}

$fp = fopen('../db/updateproduitmvt.sql', 'w');
fwrite($fp, $bs);
fclose($fp);
echo "Terminé <br />";


?>