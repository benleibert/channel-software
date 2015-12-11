<?php
//MySQL Parameters
require_once('global.inc');

//Application
define('TITLE', 'Channel2 Version 1.0-(Système de distribution des produits)');
define('DEFAULTVIEWLENGTH', 20);

//------------------------------------ Stanadrd functions -----------------------------------

function getUserServiceFormSiteAffecte($login, $magasin_srce, $default){
	//Save data

	$sql ="SELECT mag_compte.*, magasin.SER_NOM from mag_compte
	INNER JOIN magasin ON (mag_compte.CODE_MAGASIN LIKE magasin.CODE_MAGASIN)
	WHERE LOGIN LIKE '$login' ORDER BY magasin.SER_NOM ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$list = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['CODE_MAGASIN'] ? $list .= '<option value="'.$row['CODE_MAGASIN'].'" selected>'.($row['SER_NOM']).'</option>' :
		$list .= '<option value="'.$row['CODE_MAGASIN'].'">'.($row['SER_NOM']).'</option>');
	}
	return $list;
}





function getlang($id)
{
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
$sql = '';
		if($ilang=='1' && $ilang!='') { $sql .= "SELECT francais FROM `diction` WHERE idlangue LIKE '$id';" ;}
		if($ilang=='2' && $ilang!='') { $sql .= "SELECT anglais FROM `diction` WHERE idlangue LIKE '$id';" ;}
		if($ilang=='3' && $ilang!='') { $sql .= "SELECT portugais FROM `diction` WHERE idlangue LIKE '$id';" ;}

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query
$text='';
	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		if($ilang=='1' && $ilang!='') {$text=$row['francais'];}
		if($ilang=='2' && $ilang!='') {$text=$row['anglais'];}
		if($ilang=='3' && $ilang!='') {$text=$row['portugais'];}
	}
return $text ;
}

function getCodelangue($login){
	//Save data
	$sql ="SELECT * from compte WHERE LOGIN LIKE '$login';";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$ilang = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		$ilang = $row['idlangue'];
	}

	return $ilang;
}

function selectlangue($default=''){
	$sql = "SELECT * FROM langue  ORDER BY idlangue ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['idlangue'] ? $select .='<option value="'.$row['idlangue'].'"  selected="selected">'.(stripslashes($row['langue'])).'</option>' : $select .='<option value="'.$row['idlangue'].'">'.(stripslashes($row['langue'])).'</option>');
	} // while
	return $select;
}



function myDbLastId($table, $id, $magasin=''){
	 $sql = "SELECT MAX($id) AS LASTID FROM $table WHERE $table.CODE_MAGASIN LIKE '".addslashes($magasin)."'";
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
	return $row['LASTID'];
}

function getUserRights($grpe){
	//SQL
	$sql ="SELECT * FROM profil_menu WHERE IDPROFIL='$grpe' ORDER BY IDPROFIL, IDMENU ASC;";
	//Exécution
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$ret = array();
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		$ret[$row['IDMENU']] =$row;
	}
	return $ret;
}

function getDroitSrting($idprof){
	$sql = "SELECT menu.LIBMENU FROM `profil_menu` INNER JOIN menu ON (menu.IDMENU=profil_menu.IDMENU) WHERE IDPROFIL= '$idprof' AND
	(VISIBLE=1 OR AJOUT=1 OR MODIF=1 OR	SUPPR=1 OR ANNUL=1 OR VALID=1); ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$ret ='';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		$ret .=  stripcslashes($row['LIBMENU']).', ';
	}
	if($ret!='') $ret = substr($ret,0, strlen($ret)-2);
	return $ret;

}

//Nombre de ligne retourner
function nombreElement($where='', $table){
	$sql = "SELECT * FROM $table $where;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query
	return $query->rowCount();
}

//This function return Profil / Return = string
function getField($key, $code, $field, $table){
	$sql = "SELECT $field FROM $table WHERE $key='$code'; ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row[$field];
	}
	else return '';
}

//Formatage des dates en MySQL
function mysqlFormat($str){
	$str = trim($str);
	if ($str !='') {
		$split = preg_split('/[-\.\/ ]/',$str);
		return $split[2].'-'.$split[1].'-'.$split[0];
	}
}

function frFormat2($str){
	$str = trim($str);
	$ret ='';
	if ($str !='') {
		$split = preg_split('/[-\.\/ ]/',$str);
		if($str != '0000-00-00') {$ret = $split[2].'/'.$split[1].'/'.$split[0];}
	}
	return $ret;
}

//Formatage des dates en français
function frFormat($str){
	//dd-mm-aaaa h:i:s
	$ret = array('DFR'=>'-','TFR'=>'-'); // DFR, TFR
	$str = trim($str);
	if ($str !='') {
		$d = substr($str,0,10);
		$split = preg_split('/[-\.\/ ]/',$d);
		if($d == '0000-00-00') {$ret['DFR']='-';}
		else {$ret['DFR']= $split[2].'-'.$split[1].'-'.$split[0];}

		$t = trim(substr($str,11));
		if($t == '') {$ret['TFR']='-';}
		else {$ret['TFR']= $t;}
	}
	return $ret;
}

function isUseNow($field, $tble, $where){
	$sql = "SELECT COUNT($field) AS NBRE FROM $tble $where; ";

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
	return $row['NBRE'];
}

//QTE PRODUITS LIVRES
function getQteLivr($idcde, $prd){
	$sql = "SELECT SUM(LVRPRD_QUANTITE) AS NBRE FROM `lvr_prd` INNER JOIN `livraison` ON (lvr_prd.ID_LIVRAISON  = livraison.ID_LIVRAISON )
	WHERE CODE_COMMANDE LIKE '".addslashes($idcde)."' AND CODE_PRODUIT '".addslashes($prd)."'; ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row['NBRE'];
	}
	else {return 0;}
}

//PRODUIT PERIME PAR PRODUIT
function StockProduitPerime($prd='', $type='E',  $where=''){
	 $sql = "SELECT produit.`CODE_PRODUIT`,`ID_SOURCE`,`MVT_NATURE`, `MVT_VALID`, `MVT_UNITE`, MVT_DATEPEREMP, sum(`MVT_QUANTITE`) as QTE
	 FROM mouvement INNER JOIN produit ON (mouvement.CODE_PRODUIT LIKE  produit.CODE_PRODUIT)
	 WHERE MVT_VALID=1 AND `MVT_TYPE` LIKE '$type' AND MVT_DATEPEREMP<NOW() $where  group by mouvement.`CODE_PRODUIT` having mouvement.`CODE_PRODUIT`='$prd'; ";

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
	return $row;
}

//PRODUIT PERIME PAR LOT
function StockLotPerime($lot='', $type='E', $where=''){
	$sql = "SELECT `CODE_PRODUIT`,`ID_SOURCE`,`MVT_NATURE`, `MVT_VALID`, `MVT_UNITE`, MVT_DATEPEREMP, sum(`MVT_QUANTITE`) as QTE FROM mouvement
	 WHERE MVT_VALID=1 AND `MVT_TYPE` LIKE '$type' AND MVT_DATEPEREMP<NOW()  $where  group by `MVT_REFLOT` having `MVT_REFLOT`='$lot'; ";

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
	return $row;
}

//ETAT STOCK
function StockLotParNature($lot='', $nature='', $where=''){
	$sql = "SELECT mouvement.`CODE_PRODUIT`,`ID_SOURCE`,`MVT_NATURE`, `MVT_VALID`, `MVT_UNITE`, MVT_DATEPEREMP, sum(`MVT_QUANTITE`) as QTE
	FROM mouvement INNER JOIN produit ON (mouvement.CODE_PRODUIT LIKE  produit.CODE_PRODUIT)
	 WHERE MVT_VALID=1 AND mouvement.`MVT_NATURE` LIKE '$nature'  $where  group by mouvement.`MVT_REFLOT` having mouvement.`MVT_REFLOT` LIKE '$lot'; ";

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
	return $row;
}

function StockLotParNatureNonValide($lot='', $nature='', $where=''){
	$sql = "SELECT mouvement.`CODE_PRODUIT`,`ID_SOURCE`,`MVT_NATURE`, `MVT_VALID`, `MVT_UNITE`, MVT_DATEPEREMP, sum(`MVT_QUANTITE`) as QTE
	FROM mouvement INNER JOIN produit ON (mouvement.CODE_PRODUIT LIKE  produit.CODE_PRODUIT)
	 WHERE MVT_VALID=0 AND mouvement.`MVT_NATURE` LIKE '$nature'  $where  group by mouvement.`MVT_REFLOT` having mouvement.`MVT_REFLOT` LIKE '$lot'; ";

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
	return $row;
}

//ETAT STOCK PRODUIT
function StockProduitParNature($prd='', $nature='', $where=''){
	 $sql = "SELECT mouvement.`CODE_PRODUIT`,`ID_SOURCE`,`MVT_NATURE`, `MVT_VALID`, `MVT_UNITE`, MVT_DATEPEREMP, sum(`MVT_QUANTITE`) as QTE
	 FROM mouvement INNER JOIN produit ON (mouvement.CODE_PRODUIT LIKE  produit.CODE_PRODUIT)
	 WHERE MVT_VALID=1 AND `MVT_NATURE` LIKE '$nature'  $where  group by mouvement.`CODE_PRODUIT` having mouvement.`CODE_PRODUIT` LIKE '$prd'; ";

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
	return $row;
}

function StockProduitParNaturePeriode($prd='', $nature='', $where=''){
	$sql = "SELECT `CODE_PRODUIT`,`ID_SOURCE`,`MVT_NATURE`, `MVT_VALID`, `MVT_UNITE`, MVT_DATEPEREMP, sum(`MVT_QUANTITE`) as QTE FROM mouvement
	 WHERE MVT_VALID=1 AND `MVT_NATURE` LIKE '$nature' AND $where  group by `CODE_PRODUIT` having `CODE_PRODUIT`='$prd'; ";

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
	return $row;
}

function StockLotParNaturePeriode($lot='', $nature='', $where=''){
	$sql = "SELECT `CODE_PRODUIT`,`ID_SOURCE`,`MVT_NATURE`, `MVT_VALID`, `MVT_UNITE`, MVT_DATEPEREMP, sum(`MVT_QUANTITE`) as QTE FROM mouvement
	 WHERE MVT_VALID=1 AND `MVT_NATURE` LIKE '$nature' AND $where  group by `MVT_REFLOT` having `MVT_REFLOT`='$lot'; ";

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
	return $row;
}

function StockProduit($prd='', $type='', $where=''){ // E=Entree  S=Sortie
	$sql = "SELECT mouvement.`CODE_PRODUIT`,`ID_SOURCE`,`MVT_NATURE`, `MVT_VALID`, `MVT_UNITE`, MVT_DATEPEREMP, sum(`MVT_QUANTITE`) as QTE FROM mouvement
	INNER JOIN produit ON (mouvement.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
	 WHERE MVT_VALID=1 AND MVT_TYPE LIKE '$type'  $where  group by mouvement.`CODE_PRODUIT` having mouvement.`CODE_PRODUIT`='$prd'; ";

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
	return $row;
}

function CMM($prdt, $whereAll){
	$d1 = date('Y-m-d');
	$d3= date("Y-m-d", mktime(0,0,0,date("m")-3, date("d"), date("y")));

	$qte3 = StockProduitPeriode($prdt, 'S', $d3, $d1, $whereAll);
	$qteDecl3 = StockProduitDeclassePeriode($prdt, 'S', $d3, $d1, $whereAll);
	$cmm = ($qte3['QTE'] -$qteDecl3['QTE'])/3;

	return $cmm;
}

function StockProduitDeclassePeriode($prd='', $type='', $d1='', $d2='', $where=''){
	$sql = "SELECT `CODE_PRODUIT`,`ID_SOURCE`,`MVT_NATURE`, `MVT_VALID`, `MVT_UNITE`, MVT_DATEPEREMP, sum(`MVT_QUANTITE`) as QTE FROM mouvement
	 WHERE MVT_VALID=1 AND MVT_TYPE LIKE '$type' AND MVT_NATURE LIKE 'DECLASSEMENT' AND MVT_DATE>='$d1' AND MVT_DATE<='$d2'
	 $where  group by `CODE_PRODUIT` having `CODE_PRODUIT`='$prd'; ";

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
	return $row;
}

function StockProduitPeriode($prd='', $type='', $d1='', $d2='', $where=''){
	$sql = "SELECT mouvement.`CODE_PRODUIT`,`ID_SOURCE`,`MVT_NATURE`, `MVT_VALID`, `MVT_UNITE`, MVT_DATEPEREMP, sum(`MVT_QUANTITE`) as QTE FROM mouvement
	INNER JOIN produit ON ( produit.CODE_PRODUIT LIKE mouvement.CODE_PRODUIT)
	 WHERE MVT_VALID=1 AND MVT_TYPE LIKE '$type' AND MVT_DATE>='$d1' AND MVT_DATE<='$d2'  $where  group by mouvement.`CODE_PRODUIT` having mouvement.`CODE_PRODUIT`='$prd'; ";

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
	return $row;
}
//ETB  et PP
function StockCourantQte2($id, $nature,$type, $where){
 	$sql = "SELECT mouvement.`CODE_PRODUIT`,`MVT_NATURE`, `MVT_VALID`, `MVT_UNITE`, sum(`MVT_QUANTITE`) as QTE ,`ID_SOURCE`, dotation.CODE_NDOTATION,
 	mouvement.`ID_SOURCE` FROM mouvement INNER JOIN dotation ON (dotation.ID_DOTATION=mouvement.ID_SOURCE)
	 WHERE MVT_VALID=1 AND mouvement.`MVT_NATURE` LIKE '$nature'  AND dotation.CODE_NDOTATION LIKE '$type'
	 $where  group by `CODE_PRODUIT` having `CODE_PRODUIT`='$id'; ";

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
	return $row;
}

//Liste des localités
function listeDesLocalits($defaut=''){
	//SQL
	(isset($defaut) ? $where = " WHERE LOC_NOM LIKE '$defaut%'" : $where = "");
	$sql = "SELECT * FROM localite INNER JOIN groupelocalite ON (localite.ID_GRPLOC LIKE groupelocalite.ID_GRPLOC) $where ORDER BY GRPLOC_LIBELLE,LOC_NOM ASC;";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$list = '';
	$i = 0;
	$categorie = "";
	while($row = $query->fetch(PDO::FETCH_ASSOC)){

		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		$list .= '<tr class="'.$col.'">
			<td align="center" valign="middle" class="text"><a href="#" onClick="pickUp(\''.$row['ID_LOCALITE'].'\',\''.addslashes($row['LOC_NOM']).'\');">'.$row['ID_LOCALITE'].'</a></td>
			<td class="text" ><a href="#" onClick="pickUp(\''.$row['ID_LOCALITE'].'\',\''.addslashes($row['LOC_NOM']).'\');">'.($row['LOC_NOM']).'</a></td>
            <td align="left" valign="middle" class="text" ><a href="#" onClick="pickUp(\''.$row['ID_LOCALITE'].'\',\''.addslashes($row['LOC_NOM']).'\');">'.($row['GRPLOC_LIBELLE']).'</a></td>
			<td width="10%" align="left" valign="middle" class="text" ><a href="#" onClick="pickUp(\''.$row['ID_LOCALITE'].'\',\''.addslashes($row['LOC_NOM']).'\');">'.(getDependance($row['LOC_LIEN'])).'</a></td>
          </tr>';
		$i++;
	}
	if($list ==''){
		$list = '
		<tr class="tableOddRow">
            <td height="22" align="left" valign="middle" class="text" colspan="4">Aucun article disponible ...</td>
        </tr>';
	}

	return $list;
}

function listeDesDecoupages($defaut=''){
	//SQL
	(isset($defaut) ? $where = " WHERE LOC_DECOUPAGE LIKE '$defaut%'" : $where = "");
	$sql = "SELECT * FROM decoupageadm INNER JOIN groupelocalite ON (decoupageadm.ID_GRPLOC LIKE groupelocalite.ID_GRPLOC) $where ORDER BY GRPLOC_LIBELLE,LOC_DECOUPAGE ASC;";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$list = '';
	$i = 0;
	$categorie = "";
	while($row = $query->fetch(PDO::FETCH_ASSOC)){

		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		$list .= '<tr class="'.$col.'">
			<td align="center" valign="middle" class="text"><a href="#" onClick="pickUp(\''.$row['ID_DECOUPAGE'].'\',\''.addslashes($row['LOC_DECOUPAGE']).'\');">'.$row['ID_DECOUPAGE'].'</a></td>
			<td class="text" ><a href="#" onClick="pickUp(\''.$row['ID_DECOUPAGE'].'\',\''.addslashes($row['LOC_DECOUPAGE']).'\');">'.($row['LOC_DECOUPAGE']).'</a></td>
            <td align="left" valign="middle" class="text" ><a href="#" onClick="pickUp(\''.$row['ID_DECOUPAGE'].'\',\''.addslashes($row['LOC_DECOUPAGE']).'\');">'.($row['GRPLOC_LIBELLE']).'</a></td>
			<td width="10%" align="left" valign="middle" class="text" ><a href="#" onClick="pickUp(\''.$row['ID_DECOUPAGE'].'\',\''.addslashes($row['LOC_DECOUPAGE']).'\');">'.(getDependance($row['LOC_LIEN'])).'</a></td>
          </tr>';
		$i++;
	}
	if($list ==''){
		$list = '
		<tr class="tableOddRow">
            <td height="22" align="left" valign="middle" class="text" colspan="4">Aucun article disponible ...</td>
        </tr>';
	}

	return $list;
}

function listeDesProvinces($defaut=''){
	//SQL
	(isset($defaut) ? $where = "AND  LOC_DECOUPAGE LIKE '$defaut%'" : $where = "");
 	$sql = "SELECT * FROM decoupageadm INNER JOIN groupelocalite ON (decoupageadm.ID_GRPLOC LIKE groupelocalite.ID_GRPLOC)
	WHERE decoupageadm.ID_GRPLOC LIKE 'PV' $where ORDER BY GRPLOC_LIBELLE,LOC_DECOUPAGE ASC;";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$list = '';
	$i = 0;
	$categorie = "";
	while($row = $query->fetch(PDO::FETCH_ASSOC)){

		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		$list .= '<tr class="'.$col.'">
			<td align="center" valign="middle" class="text"><a href="#" onClick="pickUp(\''.$row['ID_DECOUPAGE'].'\',\''.addslashes($row['LOC_DECOUPAGE']).'\');">'.$row['ID_DECOUPAGE'].'</a></td>
			<td class="text" ><a href="#" onClick="pickUp(\''.$row['ID_DECOUPAGE'].'\',\''.addslashes($row['LOC_DECOUPAGE']).'\');">'.($row['LOC_DECOUPAGE']).'</a></td>
            <td align="left" valign="middle" class="text" ><a href="#" onClick="pickUp(\''.$row['ID_DECOUPAGE'].'\',\''.addslashes($row['LOC_DECOUPAGE']).'\');">'.($row['GRPLOC_LIBELLE']).'</a></td>
			<td width="10%" align="left" valign="middle" class="text" ><a href="#" onClick="pickUp(\''.$row['ID_DECOUPAGE'].'\',\''.addslashes($row['LOC_DECOUPAGE']).'\');">'.(getDependance($row['LOC_LIEN'])).'</a></td>
          </tr>';
		$i++;
	}
	if($list ==''){
		$list = '
		<tr class="tableOddRow">
            <td height="22" align="left" valign="middle" class="text" colspan="4">Aucun article disponible ...</td>
        </tr>';
	}

	return $list;
}

function listeDesvilles($defaut=''){
	//SQL
	(isset($defaut) ? $where = " AND LOC_NOM LIKE '$defaut%'" : $where = "");
	$sql = "SELECT * FROM localite INNER JOIN groupelocalite ON (localite.ID_GRPLOC LIKE groupelocalite.ID_GRPLOC)
	WHERE localite.ID_GRPLOC='VL' $where ORDER BY GRPLOC_LIBELLE,LOC_NOM ASC;";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$list = '';
	$i = 0;
	$categorie = "";
	while($row = $query->fetch(PDO::FETCH_ASSOC)){

		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		$list .= '<tr class="'.$col.'">
			<td align="center" valign="middle" class="text"><a href="#" onClick="pickUp(\''.$row['ID_LOCALITE'].'\',\''.addslashes($row['LOC_NOM']).'\');">'.$row['ID_LOCALITE'].'</a></td>
			<td class="text" ><a href="#" onClick="pickUp(\''.$row['ID_LOCALITE'].'\',\''.addslashes($row['LOC_NOM']).'\');">'.($row['LOC_NOM']).'</a></td>
            <td align="left" valign="middle" class="text" ><a href="#" onClick="pickUp(\''.$row['ID_LOCALITE'].'\',\''.addslashes($row['LOC_NOM']).'\');">'.($row['GRPLOC_LIBELLE']).'</a></td>
			<td width="10%" align="left" valign="middle" class="text" ><a href="#" onClick="pickUp(\''.$row['ID_LOCALITE'].'\',\''.addslashes($row['LOC_NOM']).'\');">'.(getDependance($row['LOC_LIEN'])).'</a></td>
          </tr>';
		$i++;
	}
	if($list ==''){
		$list = '
		<tr class="tableOddRow">
            <td height="22" align="left" valign="middle" class="text" colspan="4">Aucun article disponible ...</td>
        </tr>';
	}

	return $list;
}

function getSTATUT_EXERCICEExtercice($exercice){
	//SQL
	$sql = "SELECT * FROM exercice WHERE ID_EXERCICE=$exercice;";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query
	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row['EX_CLOTURE'];
	}
	else return 0;
}

function getDependance($id){
	//SQL
	$sql = "SELECT * FROM decoupageadm WHERE ID_DECOUPAGE=$id;";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query
	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row['LOC_DECOUPAGE'];
	}
	else return '';
}

function getLocalite($id){
	//SQL
	$sql = "SELECT * FROM localite WHERE ID_LOCALITE=$id;";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query
	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row['LOC_NOM'];
	}
	else return '';
}

//LISTE DES PRODUITS ET STOCK
function ProduitsQte($idproduit='', $valid=1, $type='E'){
	//SQL
	$sql = "SELECT SUM(MVT_QUANTITE) AS TOTAL FROM mouvement WHERE CODE_PRODUIT LIKE '".addslashes($idproduit)."'
	AND MVT_VALID=$valid AND MVT_TYPE LIKE '$type' AND CODE_MAGASIN LIKE '".addslashes($_SESSION['GL_USER']['MAGASIN'])."'
	AND ID_EXERCICE='".$_SESSION['GL_USER']['EXERCICE']."';";

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
	return $row['TOTAL'];
}

function listeDesProduitsQte($defaut=''){
	//SQL
	(isset($defaut) ? $where = " WHERE PRD_LIBELLE LIKE '$defaut%'" : $where = "");
	$sql = "SELECT * FROM produit  $where ORDER BY PRD_LIBELLE ASC;";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$list = '';
	$i = 0;
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		$in='';$where='';
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		$qte= ProduitsQte($row['CODE_PRODUIT'], $valid=1, $type='E')- ProduitsQte($row['CODE_PRODUIT'], $valid=1, $type='S');

		(isset($row['PRD_PRIXVENTE']) && $row['PRD_PRIXVENTE']>0 ? $prixvente = number_format($row['PRD_PRIXVENTE'],2,',',' ') : $prixvente='');
		if($qte <= 0) $col = "tableFINIRow";
		//$qte = 0;
		$list .= '<tr class="'.$col.'">
			<td align="left" valign="middle" class="text"><a href="#" onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.$qte.'\',\''.addslashes($row['PRD_PRIXVENTE']).'\');">'.$row['CODE_PRODUIT'].'</a></td>
			<td width="10%" align="left" valign="middle" class="text" ><a href="#" onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.$qte.'\',\''.addslashes($row['PRD_PRIXVENTE']).'\');">'.($qte).'</a></td>
			<td width="10%" align="right" valign="middle" class="text" nowrap="nowrap"><a href="#" onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.$qte.'\',\''.addslashes($row['PRD_PRIXVENTE']).'\');">'.addslashes($prixvente).'</a></td>
			<td class="text" ><a href="#" onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.$qte.'\',\''.addslashes($row['PRD_PRIXVENTE']).'\');">'.($row['PRD_LIBELLE']).'</a></td>
			<td width="10%" align="left" valign="middle" class="text" ><a href="#" onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.$qte.'\',\''.addslashes($row['PRD_PRIXVENTE']).'\');">'.($row['ID_UNITE']).'</a></td>
          </tr>';
		$i++;
	}
	if($list ==''){
		$list = '
		<tr class="tableOddRow">
            <td height="22" align="left" valign="middle" class="text" colspan="4">Aucun article disponible ...</td>
        </tr>';
	}

	return $list;
}

function listeDesProduitsLotQte($defaut=''){
	//SQL
	(isset($defaut) ? $where = " AND PRD_LIBELLE LIKE '$defaut%'" : $where = "");
	$sql = "SELECT * FROM detlivraison INNER JOIN produit ON (detlivraison.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
	WHERE LVR_DATEPEREMP>NOW() $where ORDER BY PRD_LIBELLE ASC;";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$list = '';
	$i = 0;
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		$in='';$where='';
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		//$qte= ProduitsQte($row['CODE_PRODUIT'], $valid=1, $type='E')- ProduitsQte($row['CODE_PRODUIT'], $valid=1, $type='S');

		$qte = $row['LVR_PRDRECU'] -$row['LVR_QTESORTIE'];
		//if($qte <= 0) $col = "tableFINIRow";

		$dp = substr($row['LVR_DATEPEREMP'],0,strlen($row['LVR_DATEPEREMP'])-3 );
		(isset($row['PRD_PRIXVENTE']) && $row['PRD_PRIXVENTE']>0 ? $prixvente = number_format($row['PRD_PRIXVENTE'],2,',',' ') : $prixvente='');

		//$qte = 0;
		if($qte > 0){
			$list .= '<tr class="'.$col.'">
				<td align="left" valign="middle" class="text"><a href="#" title="Date péremp. '.$dp.'" onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.$qte.'\',\''.addslashes($row['PRD_PRIXVENTE']).'\',\''.addslashes($row['LVR_REFLOT']).'\',\''.addslashes($row['LVR_DATEPEREMP']).'\',\''.addslashes($row['LVR_DATEPEREMP']).'\');">'.$row['CODE_PRODUIT'].'</a></td>
				<td width="10%" align="left" valign="middle" class="text" ><a href="#" title="Date péremp. '.$dp.'"  onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.$qte.'\',\''.addslashes($row['PRD_PRIXVENTE']).'\',\''.addslashes($row['LVR_REFLOT']).'\',\''.addslashes($row['LVR_DATEPEREMP']).'\');">'.($qte).'</a></td>
				<td width="10%" align="right" valign="middle" class="text" nowrap="nowrap"><a href="#" title="Date péremp. '.$dp.'"  onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.$qte.'\',\''.addslashes($row['PRD_PRIXVENTE']).'\',\''.addslashes($row['LVR_REFLOT']).'\',\''.addslashes($row['LVR_DATEPEREMP']).'\');">'.addslashes($prixvente).'</a></td>
				<td class="text" ><a href="#" title="Date péremp. '.$dp.'"  onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.$qte.'\',\''.addslashes($row['PRD_PRIXVENTE']).'\',\''.addslashes($row['LVR_REFLOT']).'\',\''.addslashes($row['LVR_DATEPEREMP']).'\');">'.($row['PRD_LIBELLE']).'</a></td>
				<td width="10%" align="left" valign="middle" class="text" ><a href="#" onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.$qte.'\',\''.addslashes($row['PRD_PRIXVENTE']).'\',\''.addslashes($row['LVR_REFLOT']).'\',\''.addslashes($row['LVR_DATEPEREMP']).'\');">'.($row['ID_UNITE']).'</a></td>
	          </tr>';
			$i++;
		}
	}
	if($list ==''){
		$list = '
		<tr class="tableOddRow">
            <td height="22" align="left" valign="middle" class="text" colspan="4">Aucun article disponible ...</td>
        </tr>';
	}

	return $list;
}

function QteSortie($lot,$prd, $whereAll){
	$sql = "SELECT SUM(MVT_QUANTITE) AS SORTIE FROM mouvement WHERE MVT_DATEPEREMP>NOW() $whereAll
	AND MVT_TYPE LIKE 'S' AND MVT_VALID=1 AND CODE_PRODUIT LIKE '".addslashes($prd)."' AND MVT_REFLOT LIKE '".addslashes($lot)."';";

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
	return $row['SORTIE'];
}

function QteSortieParProduit($prd, $whereAll){
	$sql = "SELECT SUM(MVT_QUANTITE) AS SORTIE FROM mouvement WHERE MVT_DATEPEREMP>NOW() AND $whereAll
	AND MVT_TYPE LIKE 'S' AND MVT_VALID=1 AND mouvement.CODE_PRODUIT LIKE '".addslashes($prd)."';";

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
	return $row['SORTIE'];
}

function QteEntreParProduit($prd, $whereAll){
	$sql = "SELECT SUM(MVT_QUANTITE) AS ENTREE FROM mouvement WHERE MVT_DATEPEREMP>NOW() AND $whereAll
	AND MVT_TYPE LIKE 'E' AND MVT_VALID=1 AND CODE_PRODUIT LIKE '".addslashes($prd)."';";

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
	return $row['ENTREE'];
}

function listeDesProduitsMvtQte($defaut='', $whereAll){
	//SQL
	$whereAll1 = $whereAll;
	(isset($defaut) ? $whereAll .= " AND PRD_LIBELLE LIKE '$defaut%'" : $whereAll .= "");
	$sql = "SELECT * FROM mouvement INNER JOIN produit ON (mouvement.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
	WHERE MVT_DATEPEREMP>NOW() AND MVT_TYPE LIKE 'E' $whereAll GROUP BY mouvement.MVT_REFLOT ORDER BY MVT_DATEPEREMP ASC;";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$list = '';
	$i = 0;
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		$in='';$where='';
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

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

		//Declassement
		$PDeclassement = StockLotParNature($row['MVT_REFLOT'], 'DECLASSEMENT', $whereAll);

		$NVbonsortie = StockLotParNatureNonValide($row['MVT_REFLOT'], 'BON DE SORTIE', $whereAll);
		$NVDeclassement = StockLotParNatureNonValide($row['MVT_REFLOT'], 'DECLASSEMENT', $whereAll);
		$NVtransfetSort = StockLotParNatureNonValide($row['MVT_REFLOT'], 'TRANSFERT SORTANT', $whereAll);

		$entree  = $Livraison['QTE'] +  $reportEntree['QTE'] + $transfetEnt['QTE'];// ENTREE
		$sortie  = $bonsortie['QTE']  + $Declassement['QTE'] + $reportSortie['QTE'] + $transfetSort['QTE'] ; //SORTIE
		$ecart   = $inventmoins['QTE'] + $inventplus['QTE'];
		$rest 	 = $entree - ($sortie)  ;
		$Reservation = $NVbonsortie['QTE'] + $NVDeclassement['QTE'] + $NVtransfetSort['QTE'];

		$qte = $rest -$Reservation;

		//$dp = substr($row['MVT_DATEPEREMP'],0,strlen($row['MVT_DATEPEREMP'])-3 );
		$dp = frFormat2($row['MVT_DATEPEREMP']);
		(isset($row['PRD_PRIXVENTE']) && $row['PRD_PRIXVENTE']>0 ? $prixvente = number_format($row['PRD_PRIXVENTE'],2,',',' ') : $prixvente='');
		(isset($row['PRD_PRIXVENTEN2']) && $row['PRD_PRIXVENTEN2']>0 ? $prixventen2 = number_format($row['PRD_PRIXVENTEN2'],2,',',' ') : $prixventen2='');

		if($qte > 0){
			$list .= '<tr class="'.$col.'">
				<td align="left" valign="middle" class="text"><a href="#" title="Date péremp. '.$dp.'" onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.$qte.'\',\''.addslashes($row['PRD_PRIXVENTE']).'\',\''.addslashes($row['PRD_PRIXVENTEN2']).'\',\''.addslashes($row['MVT_REFLOT']).'\',\''.addslashes($dp).'\',\''.addslashes($row['MVT_MONLOT']).'\');">'.$row['CODE_PRODUIT'].'</a></td>
				<td width="10%" align="left" valign="middle" class="text" ><a href="#" title="Date péremp. '.$dp.'"  onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.$qte.'\',\''.addslashes($row['PRD_PRIXVENTE']).'\',\''.addslashes($row['PRD_PRIXVENTEN2']).'\',\''.addslashes($row['MVT_REFLOT']).'\',\''.addslashes($dp).'\',\''.addslashes($row['MVT_MONLOT']).'\');">'.$row['MVT_REFLOT'].'</a></td>
				<td class="text" ><a href="#" title="Date péremp. '.$dp.'"  onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.$qte.'\',\''.addslashes($row['PRD_PRIXVENTE']).'\',\''.addslashes($row['PRD_PRIXVENTEN2']).'\',\''.addslashes($row['MVT_REFLOT']).'\',\''.addslashes($dp).'\',\''.addslashes($row['MVT_MONLOT']).'\');">'.($row['PRD_LIBELLE']).'</a></td>

				<td width="10%" align="right" valign="middle" class="text" ><a href="#" title="Date péremp. '.$dp.'"  onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.$qte.'\',\''.addslashes($row['PRD_PRIXVENTE']).'\',\''.addslashes($row['PRD_PRIXVENTEN2']).'\',\''.addslashes($row['MVT_REFLOT']).'\',\''.addslashes($dp).'\',\''.addslashes($row['MVT_MONLOT']).'\');">'.$rest.'</a></td>
				<td width="10%" align="right" valign="middle" class="text" ><a href="#" title="Date péremp. '.$dp.'"  onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.$qte.'\',\''.addslashes($row['PRD_PRIXVENTE']).'\',\''.addslashes($row['PRD_PRIXVENTEN2']).'\',\''.addslashes($row['MVT_REFLOT']).'\',\''.addslashes($dp).'\',\''.addslashes($row['MVT_MONLOT']).'\');">'.$Reservation.'</a></td>
				<td width="10%" align="right" valign="middle" class="text" ><a href="#" title="Date péremp. '.$dp.'"  onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.$qte.'\',\''.addslashes($row['PRD_PRIXVENTE']).'\',\''.addslashes($row['PRD_PRIXVENTEN2']).'\',\''.addslashes($row['MVT_REFLOT']).'\',\''.addslashes($dp).'\',\''.addslashes($row['MVT_MONLOT']).'\');">'.$qte.'</a></td>
				<td width="10%" align="right" valign="middle" class="text" nowrap="nowrap"><a href="#" title="Date péremp. '.$dp.'"  onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.$qte.'\',\''.addslashes($row['PRD_PRIXVENTE']).'\',\''.addslashes($row['PRD_PRIXVENTEN2']).'\',\''.addslashes($row['MVT_REFLOT']).'\',\''.addslashes($dp).'\',\''.addslashes($row['MVT_MONLOT']).'\');">'.addslashes($prixvente).'</a></td>
				<td width="10%" align="right" valign="middle" class="text" nowrap="nowrap"><a href="#" title="Date péremp. '.$dp.'"  onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.$qte.'\',\''.addslashes($row['PRD_PRIXVENTE']).'\',\''.addslashes($row['PRD_PRIXVENTEN2']).'\',\''.addslashes($row['MVT_REFLOT']).'\',\''.addslashes($dp).'\',\''.addslashes($row['MVT_MONLOT']).'\');">'.addslashes($prixventen2).'</a></td>
				<td width="10%" align="left" valign="middle" class="text" ><a href="#" onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.$qte.'\',\''.addslashes($row['PRD_PRIXVENTE']).'\',\''.addslashes($row['PRD_PRIXVENTEN2']).'\',\''.addslashes($row['MVT_REFLOT']).'\',\''.addslashes($dp).'\',\''.addslashes($row['MVT_MONLOT']).'\');">'.($row['ID_UNITE']).'</a></td>
          </tr>';
			$i++;
		}
	}
	if($list ==''){
		$list = '
		<tr class="tableOddRow">
            <td height="22" align="left" valign="middle" class="text" colspan="4">Aucun article disponible ...</td>
        </tr>';
	}

	return $list;
}

function ProduitStockDispo($lot, $whereAll){

	$tProduit = StockLotPerime($row['MVT_REFLOT'], $type='E',  $whereAll);
	$qeperime = $tProduit['QTE'];

	$Livraison = StockLotParNature($lot, 'LIVRAISON', $whereAll);

	$Livraison = StockLotParNature($lot, 'LIVRAISON', $whereAll);

	$bonsortie = StockLotParNature($lot, 'BON DE SORTIE', $whereAll);

	$Declassement = StockLotParNature($lot, 'DECLASSEMENT', $whereAll);

	$transfetEnt = StockLotParNature($lot, 'TRANSFERT ENTRANT', $whereAll);

	$transfetSort = StockLotParNature($lot, 'TRANSFERT SORTANT', $whereAll);

	$reportEntree = StockLotParNature($lot, 'REPORT ENTRANT', $whereAll);

	$reportSortie = StockLotParNature($lot, 'REPORT SORTANT', $whereAll);

	$inventplus = StockLotParNature($lot, 'INVENTAIRE +', $whereAll);

	$inventmoins = StockLotParNature($lot, 'INVENTAIRE -', $whereAll);

	//Declassement
	$PDeclassement = StockLotParNature($lot, 'DECLASSEMENT', $whereAll);

	$NVbonsortie = StockLotParNatureNonValide($lot, 'BON DE SORTIE', $whereAll);
	$NVDeclassement = StockLotParNatureNonValide($lot, 'DECLASSEMENT', $whereAll);
	$NVtransfetSort = StockLotParNatureNonValide($lot, 'TRANSFERT SORTANT', $whereAll);

	$entree  = $Livraison['QTE'] +  $reportEntree['QTE'] + $transfetEnt['QTE'];// ENTREE
	$sortie  = $bonsortie['QTE']  + $Declassement['QTE'] + $reportSortie['QTE'] + $transfetSort['QTE'] ; //SORTIE
	$ecart   = $inventmoins['QTE'] + $inventplus['QTE'];
	$rest 	 = $entree - ($sortie)  ;
	$Reservation = $NVbonsortie['QTE'] + $NVDeclassement['QTE'] + $NVtransfetSort['QTE'];

	return $qte = $rest -$Reservation;

}


function listeDesProduitsMvtQteDecl($defaut='', $whereAll){
	//SQL
	$whereAll1 = $whereAll;
	(isset($defaut) ? $whereAll .= " AND PRD_LIBELLE LIKE '$defaut%'" : $whereAll .= "");
	$sql = "SELECT * FROM mouvement INNER JOIN produit ON (mouvement.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
	WHERE MVT_TYPE LIKE 'E' $whereAll ORDER BY MVT_DATEPEREMP ASC;";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$list = '';
	$i = 0;
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
//		$in='';$where='';
//		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
//		//$qte= ProduitsQte($row['CODE_PRODUIT'], $valid=1, $type='E')- ProduitsQte($row['CODE_PRODUIT'], $valid=1, $type='S');
//		//$entree = $row['MVT_QUANTITE'];
//		$sortie = QteSortie($row['MVT_REFLOT'], $row['CODE_PRODUIT'],$whereAll1);
//		$qte = $row['MVT_QUANTITE']- $sortie;
//
//		if($row['MVT_DATEPEREMP']<date('Y-m-d'))  $col="tableFINIRow" ;
		$in='';$where='';
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		$tProduit = StockLotPerime($row['MVT_REFLOT'], $type='E',  $whereAll);
		$qeperime = $tProduit['QTE'];


		$Livraison = StockLotParNature($row['MVT_REFLOT'], 'LIVRAISON', $whereAll);

		$Livraison = StockLotParNature($row['MVT_REFLOT'], 'LIVRAISON', $whereAll);

		$bonsortie = StockLotParNature($row['MVT_REFLOT'], 'BON DE SORTIE', $whereAll);

		$Declassement = StockLotParNature($row['MVT_REFLOT'], 'DECLASSEMENT', $whereAll);

		$transfetEnt = StockLotParNature($row['MVT_REFLOT'], 'TRANSFERT ENTRANT', $whereAll);

		$transfetSort = StockLotParNature($row['MVT_REFLOT'], 'TRANSFERT SORTANT', $whereAll);

		$reportEntree = StockLotParNature($row['MVT_REFLOT'], 'REPORT ENTRANT', $whereAll);

		$reportSortie = StockLotParNature($row['MVT_REFLOT'], 'REPORT SORTANT', $whereAll);

		$inventplus = StockLotParNature($row['MVT_REFLOT'], 'INVENTAIRE +', $whereAll);

		$inventmoins = StockLotParNature($row['MVT_REFLOT'], 'INVENTAIRE -', $whereAll);

		//Declassement
		$PDeclassement = StockLotParNature($row['MVT_REFLOT'], 'DECLASSEMENT', $whereAll);

		$NVbonsortie = StockLotParNatureNonValide($row['MVT_REFLOT'], 'BON DE SORTIE', $whereAll);
		$NVDeclassement = StockLotParNatureNonValide($row['MVT_REFLOT'], 'DECLASSEMENT', $whereAll);
		$NVtransfetSort = StockLotParNatureNonValide($row['MVT_REFLOT'], 'TRANSFERT SORTANT', $whereAll);

		$entree  = $Livraison['QTE'] +  $reportEntree['QTE'] + $transfetEnt['QTE'];// ENTREE
		$sortie  = $bonsortie['QTE']  + $Declassement['QTE'] + $reportSortie['QTE'] + $transfetSort['QTE'] ; //SORTIE
		$ecart   = $inventmoins['QTE'] + $inventplus['QTE'];
		$rest 	 = $entree - ($sortie) ;
		$Reservation = $NVbonsortie['QTE'] + $NVDeclassement['QTE'] + $NVtransfetSort['QTE'];

		$qte = $rest -$Reservation;

		if($row['MVT_DATEPEREMP']<date('Y-m-d'))  $col="tableFINIRow" ;
		//$dp = substr($row['MVT_DATEPEREMP'],0,strlen($row['MVT_DATEPEREMP'])-3 );
		$dp = frFormat2($row['MVT_DATEPEREMP']);
		(isset($row['PRD_PRIXVENTE']) && $row['PRD_PRIXVENTE']>0 ? $prixvente = number_format($row['PRD_PRIXVENTE'],2,',',' ') : $prixvente='');
		(isset($row['PRD_PRIXVENTEN2']) && $row['PRD_PRIXVENTEN2']>0 ? $prixventen2 = number_format($row['PRD_PRIXVENTEN2'],2,',',' ') : $prixventen2='');

		if($qte > 0){
			$list .= '<tr class="'.$col.'">
				<td align="left" valign="middle" class="text"><a href="#" title="Date péremp. '.$dp.'" onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.$qte.'\',\''.addslashes($row['PRD_PRIXVENTE']).'\',\''.addslashes($row['PRD_PRIXVENTEN2']).'\',\''.addslashes($row['MVT_REFLOT']).'\',\''.addslashes($dp).'\',\''.addslashes($row['MVT_MONLOT']).'\');">'.$row['CODE_PRODUIT'].'</a></td>
				<td width="10%" align="left" valign="middle" class="text" ><a href="#" title="Date péremp. '.$dp.'"  onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.$qte.'\',\''.addslashes($row['PRD_PRIXVENTE']).'\',\''.addslashes($row['PRD_PRIXVENTEN2']).'\',\''.addslashes($row['MVT_REFLOT']).'\',\''.addslashes($dp).'\',\''.addslashes($row['MVT_MONLOT']).'\');">'.$row['MVT_REFLOT'].'</a></td>
				<td class="text" ><a href="#" title="Date péremp. '.$dp.'"  onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.$qte.'\',\''.addslashes($row['PRD_PRIXVENTE']).'\',\''.addslashes($row['PRD_PRIXVENTEN2']).'\',\''.addslashes($row['MVT_REFLOT']).'\',\''.addslashes($dp).'\',\''.addslashes($row['MVT_MONLOT']).'\');">'.($row['PRD_LIBELLE']).'</a></td>

				<td width="10%" align="right" valign="middle" class="text" ><a href="#" title="Date péremp. '.$dp.'"  onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.$qte.'\',\''.addslashes($row['PRD_PRIXVENTE']).'\',\''.addslashes($row['PRD_PRIXVENTEN2']).'\',\''.addslashes($row['MVT_REFLOT']).'\',\''.addslashes($dp).'\',\''.addslashes($row['MVT_MONLOT']).'\');">'.$rest.'</a></td>
				<td width="10%" align="right" valign="middle" class="text" ><a href="#" title="Date péremp. '.$dp.'"  onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.$qte.'\',\''.addslashes($row['PRD_PRIXVENTE']).'\',\''.addslashes($row['PRD_PRIXVENTEN2']).'\',\''.addslashes($row['MVT_REFLOT']).'\',\''.addslashes($dp).'\',\''.addslashes($row['MVT_MONLOT']).'\');">'.$Reservation.'</a></td>
				<td width="10%" align="right" valign="middle" class="text" ><a href="#" title="Date péremp. '.$dp.'"  onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.$qte.'\',\''.addslashes($row['PRD_PRIXVENTE']).'\',\''.addslashes($row['PRD_PRIXVENTEN2']).'\',\''.addslashes($row['MVT_REFLOT']).'\',\''.addslashes($dp).'\',\''.addslashes($row['MVT_MONLOT']).'\');">'.$qte.'</a></td>
				<td width="10%" align="right" valign="middle" class="text" nowrap="nowrap"><a href="#" title="Date péremp. '.$dp.'"  onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.$qte.'\',\''.addslashes($row['PRD_PRIXVENTE']).'\',\''.addslashes($row['PRD_PRIXVENTEN2']).'\',\''.addslashes($row['MVT_REFLOT']).'\',\''.addslashes($dp).'\',\''.addslashes($row['MVT_MONLOT']).'\');">'.addslashes($prixvente).'</a></td>
				<td width="10%" align="right" valign="middle" class="text" nowrap="nowrap"><a href="#" title="Date péremp. '.$dp.'"  onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.$qte.'\',\''.addslashes($row['PRD_PRIXVENTE']).'\',\''.addslashes($row['PRD_PRIXVENTEN2']).'\',\''.addslashes($row['MVT_REFLOT']).'\',\''.addslashes($dp).'\',\''.addslashes($row['MVT_MONLOT']).'\');">'.addslashes($prixventen2).'</a></td>
				<td width="10%" align="left" valign="middle" class="text" ><a href="#" onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.$qte.'\',\''.addslashes($row['PRD_PRIXVENTE']).'\',\''.addslashes($row['PRD_PRIXVENTEN2']).'\',\''.addslashes($row['MVT_REFLOT']).'\',\''.addslashes($dp).'\',\''.addslashes($row['MVT_MONLOT']).'\');">'.($row['ID_UNITE']).'</a></td>


          </tr>';
			$i++;
		}
	}
	if($list ==''){
		$list = '
		<tr class="tableOddRow">
            <td height="22" align="left" valign="middle" class="text" colspan="4">Aucun article disponible ...</td>
        </tr>';
	}
//	<td align="left" valign="middle" class="text"><a href="#" title="Date péremp. '.$dp.'" onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.$qte.'\',\''.addslashes($row['PRD_PRIXVENTE']).'\',\''.addslashes($row['MVT_REFLOT']).'\',\''.addslashes($dp).'\',\''.addslashes($row['MVT_MONLOT']).'\');">'.$row['CODE_PRODUIT'].'</a></td>
//	<td width="10%" align="left" valign="middle" class="text" ><a href="#" title="Date péremp. '.$dp.'"  onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.$qte.'\',\''.addslashes($row['PRD_PRIXVENTE']).'\',\''.addslashes($row['MVT_REFLOT']).'\',\''.addslashes($dp).'\',\''.addslashes($row['MVT_MONLOT']).'\');">'.$row['MVT_REFLOT'].'</a></td>
//	<td width="10%" align="right" valign="middle" class="text" ><a href="#" title="Date péremp. '.$dp.'"  onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.$qte.'\',\''.addslashes($row['PRD_PRIXVENTE']).'\',\''.addslashes($row['MVT_REFLOT']).'\',\''.addslashes($dp).'\',\''.addslashes($row['MVT_MONLOT']).'\');">'.$qte.'</a></td>
//	<td width="10%" align="right" valign="middle" class="text" nowrap="nowrap"><a href="#" title="Date péremp. '.$dp.'"  onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.$qte.'\',\''.addslashes($row['PRD_PRIXVENTE']).'\',\''.addslashes($row['MVT_REFLOT']).'\',\''.addslashes($dp).'\',\''.addslashes($row['MVT_MONLOT']).'\');">'.addslashes($prixvente).'</a></td>
//	<td class="text" ><a href="#" title="Date péremp. '.$dp.'"  onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.$qte.'\',\''.addslashes($row['PRD_PRIXVENTE']).'\',\''.addslashes($row['MVT_REFLOT']).'\',\''.addslashes($dp).'\',\''.addslashes($row['MVT_MONLOT']).'\');">'.($row['PRD_LIBELLE']).'</a></td>
//	<td width="10%" align="left" valign="middle" class="text" ><a href="#" onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.$qte.'\',\''.addslashes($row['PRD_PRIXVENTE']).'\',\''.addslashes($row['MVT_REFLOT']).'\',\''.addslashes($dp).'\',\''.addslashes($row['MVT_MONLOT']).'\');">'.($row['ID_UNITE']).'</a></td>

	return $list;
}

function listeDesProduitsStock($defaut='', $whereAll){
	//SQL
	$whereAll1 = $whereAll;
	(isset($defaut) ? $whereAll .= " AND PRD_LIBELLE LIKE '$defaut%'" : $whereAll .= "");
	$sql = "SELECT * FROM mouvement INNER JOIN produit ON (mouvement.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
	WHERE MVT_DATEPEREMP>NOW() AND MVT_TYPE LIKE 'E' $whereAll ORDER BY MVT_DATEPEREMP ASC;";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$list = '';
	$i = 0; $j=1;
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		$in='';$where='';
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		//$qte= ProduitsQte($row['CODE_PRODUIT'], $valid=1, $type='E')- ProduitsQte($row['CODE_PRODUIT'], $valid=1, $type='S');
		//$entree = $row['MVT_QUANTITE'];
		$sortie = QteSortie($row['MVT_REFLOT'], $row['CODE_PRODUIT'],$whereAll1);
		$qte = $row['MVT_QUANTITE']- $sortie;

		//if($qte <= 0) $col = "tableFINIRow";

		//$dp = substr($row['MVT_DATEPEREMP'],0,strlen($row['MVT_DATEPEREMP'])-3 );
		$dp = frFormat2($row['MVT_DATEPEREMP']);
		(isset($row['PRD_PRIXVENTE']) && $row['PRD_PRIXVENTE']>0 ? $prixvente = number_format($row['PRD_PRIXVENTE'],2,',',' ') : $prixvente='');

		$list .= '<tr class="'.$col.'">
				<td align="center" valign="middle" class="text">'.$j.'- </a></td>
				<td align="left" valign="middle" class="text">'.$row['CODE_PRODUIT'].'</a></td>
				<td  align="left" valign="middle" class="text" >'.$row['MVT_REFLOT'].'</td>
				<td  class="text" >'.$row['PRD_LIBELLE'].'</td>
				<td  align="right" valign="middle" class="text" >'.$qte.'</a></td>
				<td  align="right" valign="middle" class="text" nowrap="nowrap">'.addslashes($prixvente).'</td>
				<td align="left" valign="middle" class="text" >'.($row['ID_UNITE']).'</td>
          </tr>';
		$i++;
		$j++;
	}
	if($list ==''){
		$list = '
		<tr class="tableOddRow">
            <td height="22" align="left" valign="middle" class="text" colspan="4">Aucun article disponible ...</td>
        </tr>';
	}

	return $list;
}

function listeDesProduitsStockparproduit($defaut='', $whereAll){
	//SQL
	$whereAll1 = $whereAll;
	(isset($defaut) ? $whereAll .= " AND produit.PRD_LIBELLE LIKE '$defaut%'" : $whereAll .= "");
	$sql = "SELECT produit.CODE_PRODUIT, produit.PRD_LIBELLE, produit.PRD_PRIXVENTE, produit.ID_UNITE FROM  produit   ORDER BY CODE_PRODUIT  ASC;";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$list = '';
	$i = 0; $j=1;
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

		if ($entree!=0 || $sortie!=0  || $rest!=0) {
			($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

			(isset($Livraison['QTE'])     && $Livraison['QTE']!=0		? $livr		= $Livraison['QTE'] 	: $livr		='');
			(isset($bonsortie['QTE'])     && $bonsortie['QTE']!=0		? $bsortie		= $bonsortie['QTE'] 	: $bsortie		='');
			(isset($reportEntree['QTE'])  && $reportEntree['QTE']!=0	? $repEnt		= $reportEntree['QTE']	: $repEnt		='');
			(isset($reportSortie['QTE'])  && $reportSortie['QTE']!=0	? $repSort		= $reportSortie['QTE']	: $repSort		='');
			(isset($transfetEnt['QTE'])   && $transfetEnt['QTE']!=0		? $transfEnt 	= $transfetEnt['QTE'] 	: $transfEnt	='');
			(isset($transfetSort['QTE'])  && $transfetSort['QTE']!=0	? $transfSort 	= $transfetSort['QTE'] 	: $transfSort	='');
			(isset($Declassement['QTE'])  && $Declassement['QTE']!=0	? $declass		= $Declassement['QTE'] 	: $declass		='');
			(isset($ecart) && $ecart!=0									? $ecart	 	= $ecart 				: $ecart		='');

			(isset($eentre) && $entre!=0 	? $entre		= $entre 		: $entre	='');
			(isset($sortie) && $sortie!=0 	? $qtesortie 	= $sortie 		: $sortie		='');
			(isset($stocks)	&& $stocks!=0	? $stocks 		= $stocks 		: $stocks			='');
			(isset($qeperime) && $qeperime!=0 	? $qeperime		= $qeperime 		: $qeperime	='');

			(isset($data[$i-1]['qteperime']) && $data[$i-1]['qteperime']!=0			? $qteperime		= $data[$i-1]['qteperime'] 			: $qteperime	='');



			$list .= '
				<tr align="left" valign="middle" class="'.$col.'">
		        <td height="22" class="text" align="center">'.(stripslashes($row['CODE_PRODUIT'])).'</td>
	            <td class="text" nowrap="nowrap" >'.(stripslashes($row['PRD_LIBELLE'])).'&nbsp;</td>
				<td class="text" align="right" >'.stripslashes(number_format($row['PRD_PRIXVENTE'],2,',',' ')).'&nbsp;</td>

				<td class="text" align="right">'.(stripslashes($rest)).'&nbsp;</td>
	            <td class="text" align="center">'.(stripslashes($row['ID_UNITE'])).'</td>
	       	 </tr>';

			$i++;
			$j++;
		}

	}
	if($list ==''){
		$list = '
		<tr class="tableOddRow">
            <td height="22" align="left" valign="middle" class="text" colspan="4">Aucun article disponible ...</td>
        </tr>';
	}

//	<td class="text" align="right" >'.(stripslashes($transfEnt)).'&nbsp;</td>
//	<td class="text" align="right" >'.(stripslashes($livr)).'&nbsp;</td>
//	<td class="text" align="right">'.(stripslashes($entree)).'&nbsp;</td>
//
//	<td class="text" align="right" >'.(stripslashes($repSort)).'&nbsp;</td>
//	<td class="text" align="right" >'.(stripslashes($transfSort)).'&nbsp;</td>
//	<td class="text" align="right" >'.(stripslashes($bsortie)).'&nbsp;</td>
//	<td class="text" align="right" >'.(stripslashes($declass)).'&nbsp;</td>
//	<td class="text" align="right">'.(stripslashes($sortie)).'&nbsp;</td>
//	<td class="text" align="right">'.(stripslashes($qeperime)).'&nbsp;</td>
//
//	<td class="text" align="right">'.(stripslashes($ecart)).'&nbsp;</td>

	return $list;
}

function listeDesProduitsStockparLot($defaut='', $whereAll){
	//SQL
	$whereAll1 = $whereAll;
	(isset($defaut) ? $whereAll .= " AND produit.PRD_LIBELLE LIKE '$defaut%'" : $whereAll .= "");
	$sql = "SELECT mouvement.CODE_PRODUIT, mouvement.MVT_MONLOT, mouvement.MVT_REFLOT, produit.PRD_LIBELLE, produit.PRD_PRIXVENTE,  produit.ID_UNITE
	FROM  mouvement INNER JOIN produit ON (mouvement.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
	GROUP BY mouvement.MVT_REFLOT ORDER BY mouvement.CODE_PRODUIT ASC;";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$list = '';
	$i = 0; $j=1;
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		$tProduit = StockLotPerime($row['MVT_REFLOT'], $type='E',  $whereAll);
		$qeperime = $tProduit['QTE'];


		$Livraison = StockLotParNature($row['MVT_REFLOT'], 'LIVRAISON', $whereAll);

		$Livraison = StockLotParNature($row['MVT_REFLOT'], 'LIVRAISON', $whereAll);

		$bonsortie = StockLotParNature($row['MVT_REFLOT'], 'BON DE SORTIE', $whereAll);

		$Declassement = StockLotParNature($row['MVT_REFLOT'], 'DECLASSEMENT', $whereAll);

		$transfetEnt = StockLotParNature($row['MVT_REFLOT'], 'TRANSFERT ENTRANT', $whereAll);

		$transfetSort = StockLotParNature($row['MVT_REFLOT'], 'TRANSFERT SORTANT', $whereAll);

		$reportEntree = StockLotParNature($row['MVT_REFLOT'], 'REPORT ENTRANT', $whereAll);

		$reportSortie = StockLotParNature($row['MVT_REFLOT'], 'REPORT SORTANT', $whereAll);

		$inventplus = StockLotParNature($row['MVT_REFLOT'], 'INVENTAIRE +', $whereAll);

		$inventmoins = StockLotParNature($row['MVT_REFLOT'], 'INVENTAIRE -', $whereAll);

		//Declassement
		$PDeclassement = StockLotParNature($row['MVT_REFLOT'], 'DECLASSEMENT', $whereAll);

		$entree  = $Livraison['QTE'] +  $reportEntree['QTE'] + $transfetEnt['QTE'];// ENTREE
		$sortie  = $bonsortie['QTE']  + $Declassement['QTE'] + $reportSortie['QTE'] + $transfetSort['QTE'] ; //SORTIE
		$ecart   = $inventmoins['QTE'] + $inventplus['QTE'];
		$rest 	 = $entree - ($sortie)  ;

		if ($entree!=0 || $sortie!=0 ||  $rest!=0) {
			($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

			(isset($Livraison['QTE'])     && $Livraison['QTE']!=0		? $livr		= $Livraison['QTE'] 	: $livr		='');
			(isset($bonsortie['QTE'])     && $bonsortie['QTE']!=0		? $bsortie		= $bonsortie['QTE'] 	: $bsortie		='');
			(isset($reportEntree['QTE'])  && $reportEntree['QTE']!=0	? $repEnt		= $reportEntree['QTE']	: $repEnt		='');
			(isset($reportSortie['QTE'])  && $reportSortie['QTE']!=0	? $repSort		= $reportSortie['QTE']	: $repSort		='');
			(isset($transfetEnt['QTE'])   && $transfetEnt['QTE']!=0		? $transfEnt 	= $transfetEnt['QTE'] 	: $transfEnt	='');
			(isset($transfetSort['QTE'])  && $transfetSort['QTE']!=0	? $transfSort 	= $transfetSort['QTE'] 	: $transfSort	='');
			(isset($Declassement['QTE'])  && $Declassement['QTE']!=0	? $declass		= $Declassement['QTE'] 	: $declass		='');
			(isset($ecart) 				  && $ecart!=0					? $ecart	 	= $ecart 				: $ecart		='');


			(isset($eentre) && $entre!=0 	? $entre		= $entre 		: $entre	='');
			(isset($sortie) && $sortie!=0 	? $qtesortie 	= $sortie 		: $sortie		='');
			(isset($stocks)	&& $stocks!=0	? $stocks 		= $stocks 		: $stocks			='');
			(isset($qeperime) && $qeperime!=0 	? $qeperime		= $qeperime 		: $qeperime	='');

			(isset($data[$i-1]['qteperime']) && $data[$i-1]['qteperime']!=0		? $qteperime	= $data[$i-1]['qteperime'] 		: $qteperime	='');



			$list .= '
				<tr align="left" valign="middle" class="'.$col.'">
		        <td height="22" class="text" align="left">'.(stripslashes($row['MVT_REFLOT'])).'</td>
	            <td height="22" class="text" align="left">'.(stripslashes($row['CODE_PRODUIT'])).'</td>
	            <td class="text" nowrap="nowrap" >'.(stripslashes($row['PRD_LIBELLE'])).'&nbsp;</td>
				<td class="text" align="right">'.stripslashes(number_format($row['PRD_PRIXVENTE'],2,',', ' ')).'&nbsp;</td>
				<td class="text" align="right">'.(stripslashes($rest)).'&nbsp;</td>
	            <td class="text" align="center">'.(stripslashes($row['ID_UNITE'])).'</td>
	       	 </tr>';

			$i++;
			$j++;
		}

	}
	if($list ==''){
		$list = '
		<tr class="tableOddRow">
            <td height="22" align="left" valign="middle" class="text" colspan="4">Aucun article disponible ...</td>
        </tr>';
	}


//	<td class="text" align="right" >'.(stripslashes($repEnt)).'&nbsp;</td>
//	<td class="text" align="right" >'.(stripslashes($transfEnt)).'&nbsp;</td>
//	<td class="text" align="right" >'.(stripslashes($livr)).'&nbsp;</td>
//	<td class="text" align="right">'.(stripslashes($entree)).'&nbsp;</td>
//
//	<td class="text" align="right" >'.(stripslashes($repSort)).'&nbsp;</td>
//	<td class="text" align="right" >'.(stripslashes($transfSort)).'&nbsp;</td>
//	<td class="text" align="right" >'.(stripslashes($bsortie)).'&nbsp;</td>
//	<td class="text" align="right" >'.(stripslashes($declass)).'&nbsp;</td>
//	<td class="text" align="right">'.(stripslashes($sortie)).'&nbsp;</td>
//	<td class="text" align="right">'.(stripslashes($qeperime)).'&nbsp;</td>
//
//	<td class="text" align="right">'.(stripslashes($ecart)).'&nbsp;</td>

	return $list;
}

//LISTE DES PRODUITS
function listeDesProduits($defaut=''){
	//SQL
	(isset($defaut) ? $where = " WHERE PRD_LIBELLE LIKE '$defaut%'" : $where = "");
	$sql = "SELECT * FROM produit  $where ORDER BY PRD_LIBELLE ASC;";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$list = '';
	$i = 0;
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		$in='';$where='';
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");


		(isset($row['PRD_PRIXACHATN2']) && $row['PRD_PRIXACHATN2']>0 ? $prixachat = number_format($row['PRD_PRIXACHATN2'],2,',',' ') : $prixachat='');

		$list .= '<tr class="'.$col.'">
			<td align="left" valign="middle" class="text"><a href="#" onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.addslashes($row['PRD_PRIXACHATN2']).'\');">'.$row['CODE_PRODUIT'].'</a></td>
			<td align="center" valign="middle" class="text"><a href="#" onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.addslashes($row['PRD_PRIXACHATN2']).'\');">'.$prixachat.'</a></td>
			<td class="text" ><a href="#" onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.addslashes($row['PRD_PRIXACHATN2']).'\');">'.($row['PRD_LIBELLE']).'</a></td>
			<td width="10%" align="left" valign="middle" class="text" ><a href="#" onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.addslashes($row['PRD_PRIXACHATN2']).'\');">'.($row['ID_UNITE']).'</a></td>
          </tr>';
		$i++;
	}
	if($list ==''){
		$list = '
		<tr class="tableOddRow">
            <td height="22" align="left" valign="middle" class="text" colspan="4">Aucun article disponible ...</td>
        </tr>';
	}

	return $list;
}

function listeDesArticlestrs(){

	//SQL
	(isset($defaut) ? $where = " WHERE PRD_LIBELLE LIKE '$defaut%'" : $where = "");
	$sql = "SELECT * FROM produit  $where ORDER BY PRD_LIBELLE ASC;";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$list = '';
	$i = 0;
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		$in='';$where='';
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		$qte= ProduitsQte($row['CODE_PRODUIT'], $valid=1, $type='E')- ProduitsQte($row['CODE_PRODUIT'], $valid=1, $type='S');
		//if($qte <= 0) $col = "tableFINIRow";

		//if($qte > 0){

		$list .= '<tr class="'.$col.'">
			<td align="left" valign="middle" class="text"><a href="#" onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.addslashes($row['PRD_PRIXACHAT']).'\');">'.$row['CODE_PRODUIT'].'</a></td>
			<td align="center" valign="middle" class="text"><a href="#" onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.addslashes($row['PRD_PRIXACHAT']).'\');">'.$qte.'</a></td>
			<td align="center" valign="middle" class="text"><a href="#" onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.addslashes($row['PRD_PRIXACHAT']).'\');">'.$row['PRD_PRIXVENTE'].'</a></td>
			<td class="text" ><a href="#" onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.addslashes($row['PRD_PRIXACHAT']).'\');">'.($row['PRD_LIBELLE']).'</a></td>
			<td width="10%" align="left" valign="middle" class="text" ><a href="#" onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\',\''.addslashes($row['PRD_PRIXACHAT']).'\');">'.($row['ID_UNITE']).'</a></td>
          </tr>';
		$i++;
		//}

	}
	if($list ==''){
		$list = '
		<tr class="tableOddRow">
            <td height="22" align="left" valign="middle" class="text" colspan="4">Aucun article disponible ...</td>
        </tr>';
	}

	return $list;
}

//LISTE DES PRODUITS CDES
function listeDesProduitsCde($defaut=''){
	//SQL
	(isset($defaut) ? $where = " WHERE PRD_LIBELLE LIKE '$defaut%'" : $where = "");
	$sql = "SELECT * FROM prd_livraison INNER JOIN produit ON (prd_livraison.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
	$where ORDER BY produit.PRD_LIBELLE ASC;";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$list = '';
	$i = 0;
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		$in='';$where='';
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");


		$list .= '<tr class="'.$col.'">
			<td align="left" valign="middle" class="text"><a href="#" onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\');">'.$row['CODE_PRODUIT'].'</a></td>
			<td align="center" valign="middle" class="text"><a href="#" onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\');">'.$row['LVR_PRDRECU'].'</a></td>
			<td class="text" ><a href="#" onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\');">'.($row['PRD_LIBELLE']).'</a></td>
			<td width="10%" align="left" valign="middle" class="text" ><a href="#" onClick="pickUp(\''.$row['CODE_PRODUIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.addslashes($row['ID_UNITE']).'\');">'.($row['ID_UNITE']).'</a></td>
          </tr>';
		$i++;
	}
	if($list ==''){
		$list = '
		<tr class="tableOddRow">
            <td height="22" align="left" valign="middle" class="text" colspan="4">Aucun article disponible ...</td>
        </tr>';
	}

	return $list;
}

//Liste des Bénéficaires
function listeBeneficaires($where=''){
	//SQL
	$sql = "SELECT * FROM beneficiaire
	INNER JOIN typebeneficiaire ON (typebeneficiaire.CODE_TYPEBENEF LIKE beneficiaire.CODE_TYPEBENEF)  $where ORDER BY BENEF_NOM ASC;";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$list = '';
	$i = 0;
	$categorie = "";
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		$list .= '<tr class="'.$col.'">
			<td align="center" valign="middle" class="text"><a href="#" onClick="pickUp(\''.$row['CODE_BENEF'].'\',\''.addslashes($row['BENEF_NOM']).'\');">'.$row['CODE_BENEF'].'</a></td>
			<td class="text" ><a href="#" onClick="pickUp(\''.$row['CODE_BENEF'].'\',\''.addslashes($row['BENEF_NOM']).'\');">'.stripslashes($row['BENEF_NOM']).'</a></td>
            <td class="text" ><a href="#" onClick="pickUp(\''.$row['CODE_BENEF'].'\',\''.addslashes($row['BENEF_NOM']).'\');">'.stripslashes($row['NOM_TYPEBENEF']).'</a></td>
           </tr>';
		$i++;
	}
	if($list ==''){
		$list = '
		<tr class="tableOddRow">
            <td height="22" align="left" valign="middle" class="text" colspan="4">Aucun article disponible ...</td>
        </tr>';
	}

	return $list;
}

function listeDesCdes($defaut='', $magasin=''){
	//SQL
	(isset($defaut) ? $where = " AND CDE_LIBELLE LIKE '$defaut%'" : $where = "");
	$sql = "SELECT * FROM commande INNER JOIN fournisseur ON (commande.CODE_FOUR=fournisseur.CODE_FOUR)
	WHERE commande.CODE_MAGASIN LIKE '".addslashes($magasin)."' AND commande.CDE_STATUT=1 $where ORDER BY CDE_DATE DESC;";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$list = '';
	$i = 0;
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		$in='';$where='';
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		if(IsDelivery($row['CODE_COMMANDE'])){
			$list .= '<tr class="'.$col.'">
			<td align="left" valign="middle" class="text"><a href="#" onClick="pickUp(\''.$row['CODE_COMMANDE'].'\',\''.addslashes($row['CDE_LIBELLE']).'\',\''.addslashes($row['CODE_FOUR']).'\',\''.addslashes($row['FOUR_NOM']).'\');">'.$row['CODE_COMMANDE'].'</a></td>
			<td align="center" valign="middle" class="text"><a href="#" onClick="pickUp(\''.$row['CODE_COMMANDE'].'\',\''.addslashes($row['CDE_LIBELLE']).'\',\''.addslashes($row['CODE_FOUR']).'\',\''.addslashes($row['FOUR_NOM']).'\');">'.frFormat2($row['CDE_DATE']).'</a></td>
			<td class="text" ><a href="#" onClick="pickUp(\''.$row['CODE_COMMANDE'].'\',\''.addslashes($row['CDE_LIBELLE']).'\',\''.addslashes($row['CODE_FOUR']).'\',\''.addslashes($row['FOUR_NOM']).'\');">'.($row['CDE_LIBELLE']).'</a></td>
        	</tr>';
			$i++;
		}
	}
	if($list ==''){
		$list = '
		<tr class="tableOddRow">
            <td height="22" align="left" valign="middle" class="text" colspan="4">Aucun article disponible ...</td>
        </tr>';
	}

	return $list;
}

function listeDesFournisseurs($defaut=''){
	//SQL
	(isset($defaut) ? $where = " WHERE FOUR_NOM LIKE '$defaut%'" : $where = "");
	$sql = "SELECT * FROM fournisseur  $where ORDER BY FOUR_NOM ASC;";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$list = '';
	$i = 0;
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		$in='';$where='';
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");


		$list .= '<tr class="'.$col.'">
			<td align="left" valign="middle" class="text"><a href="#" onClick="pickUp(\''.$row['CODE_FOUR'].'\',\''.addslashes($row['FOUR_NOM']).'\');">'.$row['CODE_FOUR'].'</a></td>
			<td class="text" ><a href="#" onClick="pickUp(\''.$row['CODE_FOUR'].'\',\''.addslashes($row['FOUR_NOM']).'\');">'.($row['FOUR_NOM']).'</a></td>
         </tr>';
		$i++;
	}
	if($list ==''){
		$list = '
		<tr class="tableOddRow">
            <td height="22" align="left" valign="middle" class="text" colspan="4">Aucun article disponible ...</td>
        </tr>';
	}

	return $list;
}

function getInfoGenerale($magasin=''){
	$sql ="SELECT * FROM `infogenerale` WHERE CODE_MAGASIN LIKE '$magasin'";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row;
	}
	else return array();
}

//This function updates the log table
function updateLog($service='', $username='', $nmlle='', $action='' ){
	$id = myDbLastId('logs', 'ID_LOG', $service)+1;
	$sql = "INSERT INTO `logs` (CODE_LOG, ID_LOG, `LOGIN` ,`MLLE`,`LOG_DATE` ,`LOG_DESCRIP`, `CODE_MAGASIN` )
	VALUES ('".addslashes("$id/$service")."',  '".addslashes($id)."','".addslashes($username)."',
	'".addslashes($nmlle)."', '".date("Y-m-d H:i:s")."', '".addslashes($action)."','".addslashes($service)."') ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query
}

//This function updates the log table
function getPersonnelName($mlle){
	$sql = "SELECT PERS_NOM, PERS_PRENOMS FROM `personnel` WHERE NUM_MLLE LIKE '$mlle'; ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row['PERS_PRENOMS'].' '.$row['PERS_NOM'];
	}
	else {return '';}
}

//This function updates the log table
function getPersonnel($mlle){
	$sql = "SELECT * FROM `personnel` WHERE NUM_MLLE LIKE '$mlle'; ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row;
	}
}

//This function updates the log table
function getCompteInfo($mlle){
	$sql = "SELECT * FROM `compte` INNER JOIN personnel ON (compte.NUM_MLLE LIKE personnel.NUM_MLLE)  WHERE compte.NUM_MLLE LIKE '$mlle'; ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row;
	}
}

//CONDITIONNEMENT
function getConditionnement($id){
	$sql = "SELECT PRD_LIBELLE FROM `conditionmt` WHERE CODE_PRODUIT ='$id'; ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row['PRD_LIBELLE'];
	}
	else {return '';}
}

function getBeneficiaire($id){
	$sql = "SELECT BENEF_NOM,BENEF_EBREVIATION,CODE_TYPEBENEF FROM `beneficiaire` WHERE CODE_BENEF =$id ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row;
	}
	else {return array();}
}

function isExitePrd($idprd, $tble){
	$i = -1;
	foreach($tble as $key=> $val){
		if($idprd==$val['codeproduit']) $i=$key;
	}
	return $i;
}

function getBenefProgromme($id){
 	$sql = "SELECT BENEF_NOM,BENEF_EBREVIATION FROM programmation INNER JOIN `beneficiaire` ON (programmation.CODE_BENEF=beneficiaire.CODE_BENEF) WHERE ID_PROGR =$id ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row['BENEF_NOM'];
	}
	else {return '';}
}

function getnombeneficiaire($id){
	$sql = "SELECT BENEF_NOM FROM `beneficiaire` WHERE CODE_BENEF =$id ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row['BENEF_NOM'];
	}
	else {return '';}
}

function getDotation($id){
	$sql = "SELECT NDOT_LIBELLE FROM `nomdotation` WHERE CODE_NDOTATION ='$id' ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row['NDOT_LIBELLE'];
	}
	else {return '';}
}

//PRODUIT
function getProduit($id){
	$sql = "SELECT PRD_LIBELLE FROM `produit` WHERE CODE_PRODUIT LIKE '$id'; ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row['PRD_LIBELLE'];
	}
	else {return '';}
}

//PERSONNEL BY ID
function getMenu($id=''){
	(isset($id) && $id!='' ? $sql = "SELECT * FROM `menu` WHERE IDMENU = '$id'; " : $sql = "SELECT * FROM `menu` ; ");

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$ret = array();
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		$key = $row['IDMENU'];
		$ret[$key]=$row['LIBMENU'];
	}
	return $ret;
}

//PERSONNEL BY ID
function getProfilNameById($id){
	$sql = "SELECT LIBPROFIL FROM `profil` WHERE IDPROFIL = '$id'; ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row['LIBPROFIL'];
	}
	else {return '';}
}


//GET MAGASIN
function getService($id){
	$sql = "SELECT SER_NOM FROM `magasin` WHERE CODE_MAGASIN LIKE '$id'; ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row['SER_NOM'];
	}
	else {return '';}
}

//Check EXERCICE

function getCentre($id, $exercice){
	$sql = "SELECT CTRENOM, BENEF_NOM FROM centreexam INNER JOIN centre ON (centreexam.IDCENTRE=centre.IDCENTRE)
	INNER JOIN beneficiaire ON (beneficiaire.CODE_BENEF=centreexam.CODE_BENEF)
	WHERE centreexam.CODE_BENEF='$id' AND centre.ID_EXERCICE='$exercice'; ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row['CTRENOM'].' ['.$row['BENEF_NOM'].']';
	}
	else {return '';}
}

//TYPE SERVICE
function getTypeservice($id){
	$sql = "SELECT GRPSER_LIBELLE FROM `groupeservice` WHERE ID_GRPSERVICE LIKE '$id'; ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row['GRPSER_LIBELLE'];
	}
	else {return '';}
}

//TYPE SERVICE
function getTypebeneficiaire($id){
	$sql = "SELECT NOM_TYPEBENEF FROM `typebeneficiaire` WHERE CODE_TYPEBENEF LIKE '$id'; ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row['NOM_TYPEBENEF'];
	}
	else {return '';}
}


function getTypelocalite($id){
	$sql = "SELECT GRPLOC_LIBELLE FROM `groupelocalite` WHERE ID_GRPLOC LIKE '$id'; ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row['GRPLOC_LIBELLE'];
	}
	else {return '';}
}

//MAGASIN
function getMagasinName($id){
	$sql = "SELECT SER_NOM FROM `magasin` WHERE CODE_MAGASIN ='$id'; ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row['SER_NOM'];
	}
	else {return '';}
}

//SERVICE
function getServiceName($id){
	$sql = "SELECT SER_NOM FROM `magasin` WHERE CODE_MAGASIN ='$id'; ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row['SER_NOM'];
	}
	else {return '';}
}

//Get FOURNISSEUR
function getFournisseur($id){
	$sql = "SELECT FOUR_NOM FROM `fournisseur` WHERE CODE_FOUR  = '$id'; ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row['FOUR_NOM'];
	}
	else {return '';}
}

//This function return last exercice
function getLastExercice(){
	$sql = "SELECT * FROM `exercice` ORDER BY ID_EXERCICE DESC; ";
	$exercice=array('EXERCICE'=>'', 'STATUT_EXERCICE'=>0, 'EX_LIBELLE'=>'');
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		$exercice['EXERCICE'] = $row['ID_EXERCICE'];
		$exercice['STATUT_EXERCICE'] = $row['EX_CLOTURE'];
		$exercice['DEBUT_EXERCICE'] = $row['EX_DATEDEBUT'];
		$exercice['FIN_EXERCICE'] = $row['EX_DATEFIN'];
		$exercice['EX_LIBELLE'] = $row['EX_LIBELLE'];
	}
	return $exercice;
}

function getStatutExercice($id){
	$sql = "SELECT ID_EXERCICE, EX_CLOTURE FROM `exercice` WHERE ID_EXERCICE =$id; ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$ret = 0;
	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		$ret = $row['EX_CLOTURE'];
	}
	return $ret;
}

//
function sousMenuSansAj($page='', $tab='', $droit=''){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	if($ilang=='1' && $ilang!='') { 
	$return ='
 	<table border="0" align="left" cellpadding="0" cellspacing="4">
       <tr>
        	<td><input name="AddButton" type="button" class="button" value="Ajouter" onClick="openPage(\'add'.$page.'.php?selectedTab='.$tab.'\');"></td>
        	<td><input name="DeleteButton" type="button" class="button" value="Supprimer" onClick="msgSuppress();"></td>
			<td><input name="EditButton" type="button" class="button" value="Editer" onClick="msgModif();"></td>
            <td><input name="SearchButton" type="button" class="button" value="Rechercher" onClick="openPage(\'search'.$page.'.php?selectedTab='.$tab.'\');"></td>
        </tr>
    </table>';
	}
	if($ilang=='2' && $ilang!='') { 
	$return ='
 	<table border="0" align="left" cellpadding="0" cellspacing="4">
       <tr>
        	<td><input name="AddButton" type="button" class="button" value="Add" onClick="openPage(\'add'.$page.'.php?selectedTab='.$tab.'\');"></td>
        	<td><input name="DeleteButton" type="button" class="button" value="Remove" onClick="msgSuppress();"></td>
			<td><input name="EditButton" type="button" class="button" value="Edit" onClick="msgModif();"></td>
            <td><input name="SearchButton" type="button" class="button" value="Search" onClick="openPage(\'search'.$page.'.php?selectedTab='.$tab.'\');"></td>
        </tr>
    </table>';
	}
	if($ilang=='3' && $ilang!='') { 
	$return ='
 	<table border="0" align="left" cellpadding="0" cellspacing="4">
       <tr>
        	<td><input name="AddButton" type="button" class="button" value="Adicionar" onClick="openPage(\'add'.$page.'.php?selectedTab='.$tab.'\');"></td>
        	<td><input name="DeleteButton" type="button" class="button" value="Remover" onClick="msgSuppress();"></td>
			<td><input name="EditButton" type="button" class="button" value="Editar" onClick="msgModif();"></td>
            <td><input name="SearchButton" type="button" class="button" value="Pesquisa" onClick="openPage(\'search'.$page.'.php?selectedTab='.$tab.'\');"></td>
        </tr>
    </table>';
	}
	return $return;
}

function sousMenuAvecValide($page='', $tab='', $droit=array()){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	if($ilang=='1' && $ilang!='') { 
	$return ='
	<table border="0" align="left" cellpadding="0" cellspacing="4">
        <tr>
        	<td><input name="AddButton" type="button" class="button" value="Ajouter" onClick="openPage(\'add'.$page.'.php?selectedTab='.$tab.'\');"></td>
        	<td><input name="DeleteButton" type="button" class="button" value="Supprimer" onClick="msgSuppress();"></td>
			<td><input name="EditButton" type="button" class="button" value="Editer" onClick="msgModif();"></td>
			<td><input name="ValidateButton" type="button" class="button" value="Valider" onClick="msgValid();"></td>
            <td><input name="SearchButton" type="button" class="button" value="Rechercher" onClick="openPage(\'search'.$page.'.php?selectedTab='.$tab.'\');"></td>
        </tr>
    </table>';
	}
	if($ilang=='2' && $ilang!='') { 
	$return ='
	<table border="0" align="left" cellpadding="0" cellspacing="4">
        <tr>
        	<td><input name="AddButton" type="button" class="button" value="Add" onClick="openPage(\'add'.$page.'.php?selectedTab='.$tab.'\');"></td>
        	<td><input name="DeleteButton" type="button" class="button" value="Remove" onClick="msgSuppress();"></td>
			<td><input name="EditButton" type="button" class="button" value="Edit" onClick="msgModif();"></td>
			<td><input name="ValidateButton" type="button" class="button" value="Validate" onClick="msgValid();"></td>
            <td><input name="SearchButton" type="button" class="button" value="Search" onClick="openPage(\'search'.$page.'.php?selectedTab='.$tab.'\');"></td>
        </tr>
    </table>';
	}
	if($ilang=='3' && $ilang!='') { 
	$return ='
	<table border="0" align="left" cellpadding="0" cellspacing="4">
        <tr>
        	<td><input name="AddButton" type="button" class="button" value="Adicionar" onClick="openPage(\'add'.$page.'.php?selectedTab='.$tab.'\');"></td>
        	<td><input name="DeleteButton" type="button" class="button" value="Remover" onClick="msgSuppress();"></td>
			<td><input name="EditButton" type="button" class="button" value="Editar" onClick="msgModif();"></td>
			<td><input name="ValidateButton" type="button" class="button" value="Validar" onClick="msgValid();"></td>
            <td><input name="SearchButton" type="button" class="button" value="Pesquisa" onClick="openPage(\'search'.$page.'.php?selectedTab='.$tab.'\');"></td>
        </tr>
    </table>';
	}
	
	return $return;
}

function sousMenuDroit($page='', $tab='', $droit=array()){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	if($ilang=='1' && $ilang!='') { 
	$return ='
	<table border="0" align="left" cellpadding="0" cellspacing="4">
        <tr>';

	if($droit['AJOUT']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="AddButton" type="button" class="button" value="Ajouter" onClick="openPage(\'add'.$page.'.php?selectedTab='.$tab.'\');"></td>';}
	else {$return .='<td><input name="AddButton" type="button" class="buttonDisabled" disabled="disabled" value="Ajouter" onClick="openPage(\'add'.$page.'.php?selectedTab='.$tab.'\');"></td>';}

	if($droit['MODIF']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="EditButton" type="button" class="button" value="Editer" onClick="msgModif();"></td>';}
	else {$return .='<td><input name="EditButton" type="button" class="buttonDisabled" disabled="disabled" value="Editer" onClick="msgModif();"></td>';}

	if($droit['SUPPR']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="DeleteButton" type="button" class="button" value="Supprimer" onClick="msgSuppress();"></td>';}
	else {$return .='<td><input name="DeleteButton" type="button" class="buttonDisabled" disabled="disabled" value="Supprimer" onClick="msgSuppress();"></td>';}

	if($droit['VALID']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="ValidateButton" type="button" class="button" value="Valider" onClick="msgValid();"></td>';}
	else {$return .='<td><input name="ValidateButton" type="button" class="buttonDisabled" disabled="disabled" value="Valider" onClick="msgValid();"></td>';}

	$return .='<td><input name="SearchButton" type="button" class="button" value="Rechercher" onClick="openPage(\'search'.$page.'.php?selectedTab='.$tab.'\');"></td>
        </tr>
    </table>';
	}
	if($ilang=='2' && $ilang!='') { 
	$return ='
	<table border="0" align="left" cellpadding="0" cellspacing="4">
        <tr>';

	if($droit['AJOUT']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="AddButton" type="button" class="button" value="Add" onClick="openPage(\'add'.$page.'.php?selectedTab='.$tab.'\');"></td>';}
	else {$return .='<td><input name="AddButton" type="button" class="buttonDisabled" disabled="disabled" value="Add" onClick="openPage(\'add'.$page.'.php?selectedTab='.$tab.'\');"></td>';}

	if($droit['MODIF']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="EditButton" type="button" class="button" value="Edit" onClick="msgModif();"></td>';}
	else {$return .='<td><input name="EditButton" type="button" class="buttonDisabled" disabled="disabled" value="Edit" onClick="msgModif();"></td>';}

	if($droit['SUPPR']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="DeleteButton" type="button" class="button" value="Remove" onClick="msgSuppress();"></td>';}
	else {$return .='<td><input name="DeleteButton" type="button" class="buttonDisabled" disabled="disabled" value="Remove" onClick="msgSuppress();"></td>';}

	if($droit['VALID']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="ValidateButton" type="button" class="button" value="Validate" onClick="msgValid();"></td>';}
	else {$return .='<td><input name="ValidateButton" type="button" class="buttonDisabled" disabled="disabled" value="Validate" onClick="msgValid();"></td>';}

	$return .='<td><input name="SearchButton" type="button" class="button" value="Search" onClick="openPage(\'search'.$page.'.php?selectedTab='.$tab.'\');"></td>
        </tr>
    </table>';
	}
	if($ilang=='3' && $ilang!='') { 
	$return ='
	<table border="0" align="left" cellpadding="0" cellspacing="4">
        <tr>';

	if($droit['AJOUT']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="AddButton" type="button" class="button" value="Adicionar" onClick="openPage(\'add'.$page.'.php?selectedTab='.$tab.'\');"></td>';}
	else {$return .='<td><input name="AddButton" type="button" class="buttonDisabled" disabled="disabled" value="Adicionar" onClick="openPage(\'add'.$page.'.php?selectedTab='.$tab.'\');"></td>';}

	if($droit['MODIF']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="EditButton" type="button" class="button" value="Editar" onClick="msgModif();"></td>';}
	else {$return .='<td><input name="EditButton" type="button" class="buttonDisabled" disabled="disabled" value="Editar" onClick="msgModif();"></td>';}

	if($droit['SUPPR']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="DeleteButton" type="button" class="button" value="Remover" onClick="msgSuppress();"></td>';}
	else {$return .='<td><input name="DeleteButton" type="button" class="buttonDisabled" disabled="disabled" value="Remover" onClick="msgSuppress();"></td>';}

	if($droit['VALID']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="ValidateButton" type="button" class="button" value="Validar" onClick="msgValid();"></td>';}
	else {$return .='<td><input name="ValidateButton" type="button" class="buttonDisabled" disabled="disabled" value="Validar" onClick="msgValid();"></td>';}

	$return .='<td><input name="SearchButton" type="button" class="button" value="Pesquisa" onClick="openPage(\'search'.$page.'.php?selectedTab='.$tab.'\');"></td>
        </tr>
    </table>';
	}
	return $return;
}

function sousMenuDroitAvecAnnuler($page='', $tab='', $droit=array()){
	$return ='
	<table border="0" align="left" cellpadding="0" cellspacing="4">
        <tr>';

	if($droit['AJOUT']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="AddButton" type="button" class="button" value="Ajouter" onClick="openPage(\'add'.$page.'.php?selectedTab='.$tab.'\');"></td>';}
	else {$return .='<td><input name="AddButton" type="button" class="buttonDisabled" disabled="disabled" value="Ajouter" onClick="openPage(\'add'.$page.'.php?selectedTab='.$tab.'\');"></td>';}

	if($droit['MODIF']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="EditButton" type="button" class="button" value="Editer" onClick="msgModif();"></td>';}
	else {$return .='<td><input name="EditButton" type="button" class="buttonDisabled" disabled="disabled" value="Editer" onClick="msgModif();"></td>';}

	if($droit['SUPPR']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="DeleteButton" type="button" class="button" value="Supprimer" onClick="msgSuppress();"></td>';}
	else {$return .='<td><input name="DeleteButton" type="button" class="buttonDisabled" disabled="disabled" value="Supprimer" onClick="msgSuppress();"></td>';}

	if($droit['VALID']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="ValidateButton" type="button" class="button" value="Valider" onClick="msgValid();"></td>';}
	else {$return .='<td><input name="ValidateButton" type="button" class="buttonDisabled" disabled="disabled" value="Valider" onClick="msgValid();"></td>';}

	if($droit['ANNUL']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="AnnulerButton" type="button" class="button" value="Annuler"  onClick="msgAnnul();"></td>';}
	else {$return .='<td><input name="AnnulerButton" type="button" class="buttonDisabled" disabled="disabled" value="Annuler" onClick="msgAnnul();"></td>';}


	$return .='<td><input name="SearchButton" type="button" class="button" value="Rechercher" onClick="openPage(\'search'.$page.'.php?selectedTab='.$tab.'\');"></td>
        </tr>
    </table>';
	return $return;

}

function sousMenuAnnuler($etat, $tab='', $droit=array()){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	if($ilang=='1' && $ilang!='') { 
	$return ='';

	if($droit['ANNUL']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0 && $etat == 1) {$return .='<input name="AnnulerButton" type="button" class="button" value="Annuler la validation"  onClick="msgAnnul();">';}
	else {$return .='<input name="AnnulerButton" type="button" class="buttonDisabled" disabled="disabled" value="Annuler la validation" onClick="msgAnnul();">';}

	return $return;
}
	if($ilang=='2' && $ilang!='') { 
	$return ='';

	if($droit['ANNUL']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0 && $etat == 1) {$return .='<input name="AnnulerButton" type="button" class="button" value="Validate Cancel"  onClick="msgAnnul();">';}
	else {$return .='<input name="AnnulerButton" type="button" class="buttonDisabled" disabled="disabled" value="Validate Cancel" onClick="msgAnnul();">';}

	return $return;
}
	if($ilang=='3' && $ilang!='') { 
	$return ='';

	if($droit['ANNUL']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0 && $etat == 1) {$return .='<input name="AnnulerButton" type="button" class="button" value="Validar Cancelar"  onClick="msgAnnul();">';}
	else {$return .='<input name="AnnulerButton" type="button" class="buttonDisabled" disabled="disabled" value="Validar Cancelar" onClick="msgAnnul();">';}

	return $return;
}
}

function sousMenuDroitSansVlider($page='', $tab='', $droit=array()){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	if($ilang=='1' && $ilang!='') { 
	$return ='
	<table border="0" align="left" cellpadding="0" cellspacing="4">
        <tr>';

	if($droit['AJOUT']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="AddButton" type="button" class="button" value="Ajouter" onClick="openPage(\'add'.$page.'.php?selectedTab='.$tab.'\');"></td>';}
	else {$return .='<td><input name="AddButton" type="button" class="buttonDisabled" disabled="disabled" value="Ajouter" onClick="openPage(\'add'.$page.'.php?selectedTab='.$tab.'\');"></td>';}

	if($droit['MODIF']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="EditButton" type="button" class="button" value="Editer" onClick="msgModif();"></td>';}
	else {$return .='<td><input name="EditButton" type="button" class="buttonDisabled" disabled="disabled" value="Editer" onClick="msgModif();"></td>';}

	if($droit['SUPPR']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="DeleteButton" type="button" class="button" value="Supprimer" onClick="msgSuppress();"></td>';}
	else {$return .='<td><input name="DeleteButton" type="button" class="buttonDisabled" disabled="disabled" value="Supprimer" onClick="msgSuppress();"></td>';}

	$return .='<td><input name="SearchButton" type="button" class="button" value="Rechercher" onClick="openPage(\'search'.$page.'.php?selectedTab='.$tab.'\');"></td>
        </tr>
    </table>';
	}
	if($ilang=='2' && $ilang!='') { 
	$return ='
	<table border="0" align="left" cellpadding="0" cellspacing="4">
        <tr>';

	if($droit['AJOUT']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="AddButton" type="button" class="button" value="Add" onClick="openPage(\'add'.$page.'.php?selectedTab='.$tab.'\');"></td>';}
	else {$return .='<td><input name="AddButton" type="button" class="buttonDisabled" disabled="disabled" value="Add" onClick="openPage(\'add'.$page.'.php?selectedTab='.$tab.'\');"></td>';}

	if($droit['MODIF']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="EditButton" type="button" class="button" value="Edit" onClick="msgModif();"></td>';}
	else {$return .='<td><input name="EditButton" type="button" class="buttonDisabled" disabled="disabled" value="Edit" onClick="msgModif();"></td>';}

	if($droit['SUPPR']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="DeleteButton" type="button" class="button" value="Remove" onClick="msgSuppress();"></td>';}
	else {$return .='<td><input name="DeleteButton" type="button" class="buttonDisabled" disabled="disabled" value="Remove" onClick="msgSuppress();"></td>';}

	$return .='<td><input name="SearchButton" type="button" class="button" value="Search" onClick="openPage(\'search'.$page.'.php?selectedTab='.$tab.'\');"></td>
        </tr>
    </table>';
	}
	if($ilang=='3' && $ilang!='') { 
	$return ='
	<table border="0" align="left" cellpadding="0" cellspacing="4">
        <tr>';

	if($droit['AJOUT']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="AddButton" type="button" class="button" value="Adicionar" onClick="openPage(\'add'.$page.'.php?selectedTab='.$tab.'\');"></td>';}
	else {$return .='<td><input name="AddButton" type="button" class="buttonDisabled" disabled="disabled" value="Adicionar" onClick="openPage(\'add'.$page.'.php?selectedTab='.$tab.'\');"></td>';}

	if($droit['MODIF']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="EditButton" type="button" class="button" value="Editar" onClick="msgModif();"></td>';}
	else {$return .='<td><input name="EditButton" type="button" class="buttonDisabled" disabled="disabled" value="Editar" onClick="msgModif();"></td>';}

	if($droit['SUPPR']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="DeleteButton" type="button" class="button" value="Remover" onClick="msgSuppress();"></td>';}
	else {$return .='<td><input name="DeleteButton" type="button" class="buttonDisabled" disabled="disabled" value="Remover" onClick="msgSuppress();"></td>';}
	$return .='<td><input name="SearchButton" type="button" class="button" value="Pesquisa" onClick="openPage(\'search'.$page.'.php?selectedTab='.$tab.'\');"></td>
        </tr>
    </table>';
	}
	return $return;
}


function sousMenuDroitSansVliderEleve($page='', $tab='', $droit=array()){
	$return ='
	<table border="0" align="left" cellpadding="0" cellspacing="4">
        <tr>';

	if($droit['AJOUT']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="AddButton" type="button" class="button" value="Inscrire" onClick="openPage(\'add'.$page.'.php?selectedTab='.$tab.'\');"></td>';}
	else {$return .='<td><input name="AddButton" type="button" class="buttonDisabled" disabled="disabled" value="Inscrire" onClick="openPage(\'add'.$page.'.php?selectedTab='.$tab.'\');"></td>';}

	if($droit['AJOUT']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="ReinButton" type="button" class="button" value="Réinscrire" onClick="openPage(\'add'.$page.'.php?selectedTab='.$tab.'\');"></td>';}
	else {$return .='<td><input name="ReinButton" type="button" class="buttonDisabled" disabled="disabled" value="Réinscrire" onClick="openPage(\'add'.$page.'.php?selectedTab='.$tab.'\');"></td>';}

	if($droit['MODIF']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="EditButton" type="button" class="button" value="Editer" onClick="msgModif();"></td>';}
	else {$return .='<td><input name="EditButton" type="button" class="buttonDisabled" disabled="disabled" value="Editer" onClick="msgModif();"></td>';}

	if($droit['SUPPR']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="DeleteButton" type="button" class="button" value="Supprimer" onClick="msgSuppress();"></td>';}
	else {$return .='<td><input name="DeleteButton" type="button" class="buttonDisabled" disabled="disabled" value="Supprimer" onClick="msgSuppress();"></td>';}

	$return .='<td><input name="SearchButton" type="button" class="button" value="Rechercher" onClick="openPage(\'search'.$page.'.php?selectedTab='.$tab.'\');"></td>
        </tr>
    </table>';
	return $return;
}

function sousMenuDroitSansMaj($page='', $tab='', $droit=array()){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	if($ilang=='1' && $ilang!='') { 
	$return ='
	<table border="0" align="left" cellpadding="0" cellspacing="4">
        <tr>';
	if($droit['SUPPR']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="DeleteButton" type="button" class="button" value="Supprimer" onClick="msgSuppress();"></td>';}
	else {$return .='<td><input name="DeleteButton" type="button" class="buttonDisabled" disabled="disabled" value="Supprimer" onClick="msgSuppress();"></td>';}

	$return .='<td><input name="SearchButton" type="button" class="button" value="Rechercher" onClick="openPage(\'search'.$page.'.php?selectedTab='.$tab.'\');"></td>
        </tr>
    </table>';
	return $return;
}
	if($ilang=='2' && $ilang!='') { 
	$return ='
	<table border="0" align="left" cellpadding="0" cellspacing="4">
        <tr>';
	if($droit['SUPPR']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="DeleteButton" type="button" class="button" value="Remove" onClick="msgSuppress();"></td>';}
	else {$return .='<td><input name="DeleteButton" type="button" class="buttonDisabled" disabled="disabled" value="Remove" onClick="msgSuppress();"></td>';}

	$return .='<td><input name="SearchButton" type="button" class="button" value="Search" onClick="openPage(\'search'.$page.'.php?selectedTab='.$tab.'\');"></td>
        </tr>
    </table>';
	return $return;
}
	if($ilang=='3' && $ilang!='') { 
	$return ='
	<table border="0" align="left" cellpadding="0" cellspacing="4">
        <tr>';
	if($droit['SUPPR']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="DeleteButton" type="button" class="button" value="Remover" onClick="msgSuppress();"></td>';}
	else {$return .='<td><input name="DeleteButton" type="button" class="buttonDisabled" disabled="disabled" value="Remover" onClick="msgSuppress();"></td>';}

	$return .='<td><input name="SearchButton" type="button" class="button" value="Pesquisa" onClick="openPage(\'search'.$page.'.php?selectedTab='.$tab.'\');"></td>
        </tr>
    </table>';
	return $return;
}

}


function sousMenuDroitGerer($page='', $tab='', $droit=array()){
	$return ='
	<table border="0" align="left" cellpadding="0" cellspacing="4">
        <tr>';

	if($droit['AJOUT']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="AddGenerer" type="button" class="button" value="Générer"onClick="msgGenerer();"></td>';}
	else {$return .='<td><input name="AddButton" type="button" class="buttonDisabled" disabled="disabled" value="Ajouter" onClick="openPage(\'add'.$page.'.php?selectedTab='.$tab.'\');"></td>';}

	if($droit['MODIF']==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="EditButton" type="button" class="button" value="Editer" onClick="msgModif();"></td>';}
	else {$return .='<td><input name="EditButton" type="button" class="buttonDisabled" disabled="disabled" value="Editer" onClick="msgModif();"></td>';}

	if($droit['SUPPR']==1 &&  $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="DeleteButton" type="button" class="button" value="Supprimer" onClick="msgSuppress();"></td>';}
	else {$return .='<td><input name="DeleteButton" type="button" class="buttonDisabled" disabled="disabled" value="Supprimer" onClick="msgSuppress();"></td>';}

	$return .='<td><input name="SearchButton" type="button" class="button" value="Rechercher" onClick="openPage(\'search'.$page.'.php?selectedTab='.$tab.'\');"></td>
        </tr>
    </table>';
	return $return;
}

function EnteteProgramme($data){
	//Générer l'entete
	$entet ='<tr align="left" valign="top" nowrap>
        <td align=right valign="middle" class="text">&nbsp;</td>
        <td width="150" align="left" valign="middle" nowrap class="text"><div align="center">'.(stripslashes('Etatblissements')).'</div></td>
	';
	foreach($data as $key => $row){
		$entet .='<td width="64" align=right valign="middle" nowrap class="text"><div align="center">'.(stripslashes($row['produit'].'/'.$row['unite'])).'</div></td>
		<td width="64" align=right valign="middle" nowrap class="text"><div align="center">'.(stripslashes('Nbre/plats')).'</div></td>';
	}
	$entet .='<td align=right valign="middle" nowrap class="text"><div align="center">'.(stripslashes('Reversement')).'</div></td></tr>';
	return $entet;
}

//DROIT MAJ
function getDroitMAJ($field, $grp, $exerciceSTATUT_EXERCICE=0){
	$sql = "SELECT $field FROM groupe WHERE ID_GROUPE ='$grp' ;";
	$ret='0 0 0 0';
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

	$t ='';
	foreach ($row as $key => $val){$t.=$val.' ';}
	if($t !='')  $ret =$t;
	return $ret;
}

//DROIT MENU
function getDroitTOPMENUS($grp){
	$sql = "SELECT GRP_MENU_CPMIEP FROM groupe WHERE ID_GROUPE ='$grp' ;";
	$ret='0 0 0 0 0 0 0';
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

	$t ='';
	foreach ($row as $key => $val){$t.=$val.' ';}
	if($t !='')  $ret =$t;
	return $ret;
}

//PROGRAMME
function ligneProgramme($ligne, $data){
	//Ligne
	$sql = "SELECT * FROM beneficiaire WHERE beneficiaire.CODE_TYPEBENEF LIKE 'ETB' ;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query
	$i=1;
	$ret='';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		$ret .= '
		<tr align="left" valign="middle">
            <td class="botBorderTd" nowrap>'.$i.' - </td>
			<td class="botBorderTd"><input name="beneficiaire'.$i.'" type="text" readonly="readonly" class="formStyleFree" id="beneficiaire'.$i.'" size="50" value="'.$row['BENEF_NOM'].'"></td>';

		foreach($ligne as $key => $row1){$ret .= '<td class="botBorderTd"><input name="qte_'.$row1['codeproduit'].'_'.$i.'" type="text" class="formStyleFree" id="qte_'.$row1['codeproduit'].'_'.$i.'" size="10" value="" ></td>
		<td class="botBorderTd"><input name="plat_'.$row1['codeproduit'].'_'.$i.'" type="text" class="formStyleFree" id="plat_'.$row1['codeproduit'].'_'.$i.'" size="10" value="" ></td>';}

		$ret .= '<td class="botBorderTd"><input name="reversement'.$i.'" type="text" class="formStyleFree" id="reversement'.$i.'" size="10" value="" ></td>
        </tr>';
        $i++;
	}
	return $ret;
}

//LIVRAISON D'UN PRODUIT SUR UNE COMMANDE
function livraisonPourProduit($cde, $idproduit){
	$sql = "SELECT SUM(LVR_PRDRECU) as TOTAL FROM detlivraison WHERE CODE_PRODUIT LIKE '".addslashes($idproduit)."'
	AND LVR_IDCOMMANDE LIKE '".addslashes($cde)."';";
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
	return $row['TOTAL'];
}

//QTE SORTIE POUR UNE PERIODE
function quantiteSortiePeriode($exercice, $service, $idproduit, $datedebut, $datefin){
	$sql = "SELECT SUM(MVT_QUANTITE) as TOTAL FROM mouvement 	WHERE mouvement.CODE_MAGASIN LIKE '".addslashes($service)."' AND
	mouvement.ID_EXERCICE='$exercice' AND mouvement.MVT_VALID=1 AND CODE_PRODUIT LIKE '".addslashes($idproduit)."'
	AND mouvement.MVT_DATE >= '".addslashes(mysqlFormat($datedebut))."' AND mouvement.MVT_DATE <= '".addslashes(mysqlFormat($datefin))."' ;";
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

	//Nbre de jour
	$nbrej =  NbJours($datedebut, $datefin);
	($nbrej>0 ? $qte = 30*($row['TOTAL']/$nbrej) : $qte=0);
	return $qte;
}

//LIVRAISON D'UN PRODUIT SUR UNE COMMANDE
function commandeEnCoursProduit($exercice, $service, $idproduit){
	$sql = "SELECT SUM(CDEPRD_QTE) as TOTAL FROM prd_cde INNER JOIN commande ON (prd_cde.CODE_COMMANDE LIKE commande.CODE_COMMANDE)
	WHERE commande.CODE_MAGASIN LIKE '".addslashes($service)."' AND commande.ID_EXERCICE='$exercice' AND commande.CDE_STATUT=1 AND
	CODE_PRODUIT LIKE '".addslashes($idproduit)."';";
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
	return $row['TOTAL'];
}

function entreePourProduitRec($recSortie, $idproduit){
	$sql = "SELECT SUM(CNDREC_QTEE) as TOTAL FROM recond_entre WHERE CODE_PRODUIT='$idproduit' AND ID_RECONDIT='$recSortie';";
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
	return $row['TOTAL'];

}

function selectExercice($default=''){
	$sql = "SELECT * FROM exercice ORDER BY ID_EXERCICE DESC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['ID_EXERCICE'] ? $select .='<option value="'.$row['ID_EXERCICE'].'"  selected="selected">'.(stripslashes($row['EX_LIBELLE'])).'</option>' : $select .='<option value="'.$row['ID_EXERCICE'].'">'.(stripslashes($row['EX_LIBELLE'])).'</option>');
	} // while
	return $select;
}

function selectTypeBareme($default=''){
	$tbareme = array('DOT'=>'Barème dotation', 'BAC'=>'Barème BAC');

	$select = '';
	foreach($tbareme as $key=>$val){
		($default == $key ? $select .='<option value="'.$key.'"  selected="selected">'.(stripslashes($val)).'</option>' : $select .='<option value="'.$key.'">'.(stripslashes($val)).'</option>');
	} // while
	return $select;
}

//TYPE BENEFICIAIRE
function selectNatureTransfert($default=''){
	$tranfert = array(1=>'Transfert sortant', 2=>'Transfert entrant');

	$select = '';
	foreach($tranfert as $key => $val){
		($default == $key ? $select .='<option value="'.$key.'"  selected="selected">'.(stripslashes($val)).'</option>' : $select .='<option value="'.$key.'">'.(stripslashes($val)).'</option>');
	} // while
	return $select;
}

//ETABLISSEMENT
function selectEtablissement($default=''){
	$sql = "SELECT * FROM beneficiaire WHERE CODE_TYPEBENEF LIKE 'ETB' ORDER BY BENEF_NOM ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		(isset($row['BENEF_EBREVIATION']) ? $abbr = '('.$row['BENEF_EBREVIATION'].')' : $abbr='');
		($default == $row['CODE_BENEF'] ? $select .='<option value="'.$row['CODE_BENEF'].'"  selected="selected">'.(stripslashes($row['BENEF_NOM'])).'</option>' : $select .='<option value="'.$row['CODE_BENEF'].'">'.(stripslashes($row['BENEF_NOM'].$abbr)).'</option>');
	} // while
	return $select;
}

//PERSONNEL
function selectPersonnel($default=''){
	$sql = "SELECT * FROM personnel  ORDER BY PERS_NOM,	PERS_PRENOMS ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['NUM_MLLE'] ? $select .='<option value="'.$row['NUM_MLLE'].'"  selected="selected">'.(stripslashes($row['PERS_NOM'].' '.$row['PERS_PRENOMS'])).'</option>' : $select .='<option value="'.$row['NUM_MLLE'].'">'.(stripslashes($row['PERS_NOM'].' '.$row['PERS_PRENOMS'])).'</option>');
	} // while
	return $select;
}

//COMPTE
function selectCompte($default=''){
	$sql = "SELECT LOGIN FROM compte  ORDER BY LOGIN ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['LOGIN'] ? $select .='<option value="'.$row['LOGIN'].'"  selected="selected">'.stripslashes($row['LOGIN']).'</option>' : $select .='<option value="'.$row['LOGIN'].'">'.stripslashes($row['LOGIN']).'</option>');
	} // while
	return $select;
}

//GROUPE
function selectGroupe($default=''){
	$sql = "SELECT * FROM profil  ORDER BY LIBPROFIL ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['IDPROFIL'] ? $select .='<option value="'.$row['IDPROFIL'].'"  selected="selected">'.(stripslashes($row['LIBPROFIL'])).'</option>' : $select .='<option value="'.$row['IDPROFIL'].'">'.(stripslashes($row['LIBPROFIL'])).'</option>');
	} // while
	return $select;
}

//BENEFICIAIRE
function selectBeneficiaire($default=''){
	$sql = "SELECT * FROM beneficiaire LEFT JOIN province ON (province.IDPROVINCE=beneficiaire.IDPROVINCE) ORDER BY BENEF_NOM ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		(isset($row['BENEF_EBREVIATION']) ? $abbr = '('.$row['PROVINCE'].')' : $abbr='');
		($default == $row['CODE_BENEF'] ? $select .='<option value="'.$row['CODE_BENEF'].'"  selected="selected">'.stripslashes($row['BENEF_NOM']).'</option>' : $select .='<option value="'.$row['CODE_BENEF'].'">'.stripslashes($row['BENEF_NOM'].$abbr).'</option>');
	} // while
	return $select;
}

//FOURNISSEUR
function selectFournisseur($default=''){
	$sql = "SELECT * FROM fournisseur ORDER BY FOUR_NOM, CODE_FOUR ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['CODE_FOUR'] ? $select .='<option value="'.$row['CODE_FOUR'].'"  selected="selected">'.(stripslashes($row['FOUR_NOM'])).'</option>' : $select .='<option value="'.$row['CODE_FOUR'].'">'.(stripslashes($row['FOUR_NOM'])).'</option>');
	} // while
	return $select;
}

//CATEGORIE
function selectCategorie($default=''){
	$sql = "SELECT * FROM categorie ORDER BY CAT_LIBELLE ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['CODE_CATEGORIE'] ? $select .='<option value="'.$row['CODE_CATEGORIE'].'"  selected="selected">'.(stripslashes($row['CAT_LIBELLE'])).'</option>' : $select .='<option value="'.$row['CODE_CATEGORIE'].'">'.(stripslashes($row['CAT_LIBELLE'])).'</option>');
	} // while
	return $select;
}

//CATEGORIE
function selectsousCategorie($default=''){
	$sql = "SELECT * FROM souscategorie ORDER BY SOUSCAT_LIBELLE ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['CODE_SOUSCATEGORIE'] ? $select .='<option value="'.$row['CODE_SOUSCATEGORIE'].'"  selected="selected">'.(stripslashes($row['SOUSCAT_LIBELLE'])).'</option>' : $select .='<option value="'.$row['CODE_SOUSCATEGORIE'].'">'.(stripslashes($row['SOUSCAT_LIBELLE'])).'</option>');
	} // while
	return $select;
}

function selectsousGroupe($default=''){
	$sql = "SELECT * FROM sousgroupe ORDER BY SOUSGROUPE ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['CODESOUSGROUP'] ? $select .='<option value="'.$row['CODESOUSGROUP'].'"  selected="selected">'.(stripslashes($row['SOUSGROUPE'])).'</option>' : $select .='<option value="'.$row['CODESOUSGROUP'].'">'.(stripslashes($row['SOUSGROUPE'])).'</option>');
	} // while
	return $select;
}

//ANALYSE
function selectAnalyse($default=''){
	$da = array('1'=>'LIVRAISONS', '2'=>'AUTRES LIVRAISONS', '3'=>'DOTATIONS DES ETABLISSEMENTS', '4'=>'DOTATIONS BAC', '5'=>'DOTATIONS USTENSILES',
				'6'=>'AUTRES DOTATIONS','7'=>'DECLASSEMENTS', '8'=>'RECONDITIONNEMENTS','9'=>'REPORTS', '10'=>'TRANSFERTS');

	$select = '';
	foreach($da as $k=>$val){
		($default == $k ? $select .='<option value="'.$k.'"  selected="selected">'.(stripslashes($val)).'</option>' : $select .='<option value="'.$k.'">'.(stripslashes($val)).'</option>');
	} // while
	return $select;
}

//Mois Jours
function selectJourMois($default=''){
	$list = array('+7 day'=> '7 jours', '+15 day'=> '15 jours', '+30 day'=> '1 mois', '+60 day'=> '2 mois', '+90 day'=> '3 mois',
	'+120 day'=> '4 mois', '+150 day'=> '5 mois', '+180 day'=> '6 mois', '+210 day'=> '7 mois', '+240 day'=> '8 mois', '+270 day'=> '9 mois',
	'+300 day'=> '10 mois', '+330 day'=> '11 mois', '+360 day'=> '12 mois') ;

	$select = '';
	foreach($list as $key=>$val){
		($default == $key ? $select .='<option value="'.$key.'"  selected="selected">'.stripslashes($val).'</option>' : $select .='<option value="'.$key.'">'.stripslashes($val).'</option>');
	} // while
	return $select;
}

function selectUnite($default=''){
	$sql = "SELECT * FROM unite ORDER BY UT_LIBELLE ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['ID_UNITE'] ? $select .='<option value="'.$row['ID_UNITE'].'"  selected="selected">'.(stripslashes($row['UT_LIBELLE'])).'('.$row['ID_UNITE'].')</option>' : $select .='<option value="'.$row['ID_UNITE'].'">'.(stripslashes($row['UT_LIBELLE'])).'('.$row['ID_UNITE'].')</option>');
	} // while
	return $select;
}

//TYPE LOCALITE
function selectTypeLocalite($default=''){
	$sql = "SELECT * FROM groupelocalite ORDER BY GRPLOC_LIBELLE ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['ID_GRPLOC'] ? $select .='<option value="'.$row['ID_GRPLOC'].'"  selected="selected">'.(stripslashes($row['GRPLOC_LIBELLE'])).'</option>' : $select .='<option value="'.$row['ID_GRPLOC'].'">'.(stripslashes($row['GRPLOC_LIBELLE'])).'</option>');
	} // while
	return $select;
}

//LOCALITE
function selectLocalite($default=''){
	$sql = "SELECT * FROM localite ORDER BY LOC_NOM ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['ID_LOCALITE'] ? $select .='<option value="'.$row['ID_LOCALITE'].'"  selected="selected">'.(stripslashes($row['LOC_NOM'])).'</option>' : $select .='<option value="'.$row['ID_LOCALITE'].'">'.(stripslashes($row['LOC_NOM'])).'</option>');
	} // while
	return $select;
}

//LOCALITE
function selectRegion($default=''){
	$sql = "SELECT * FROM region ORDER BY REGION ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['IDREGION'] ? $select .='<option value="'.$row['IDREGION'].'"  selected="selected">'.(stripslashes($row['REGION'])).'</option>' : $select .='<option value="'.$row['IDREGION'].'">'.(stripslashes($row['REGION'])).'</option>');
	} // while
	return $select;
}

//LOCALITE
function selectProvince($login, $province, $default){
	//$sql = "SELECT * FROM province ORDER BY PROVINCE ASC;";
	$sql="SELECT province.IDPROVINCE, province.PROVINCE,mag_compte.LOGIN 
FROM mag_compte INNER JOIN (magasin INNER JOIN province ON magasin.IDPROVINCE = province.IDPROVINCE) ON (mag_compte.CODE_MAGASIN = magasin.CODE_MAGASIN)
WHERE LOGIN LIKE '$login' GROUP BY mag_compte.LOGIN, province.IDPROVINCE, province.PROVINCE ORDER BY PROVINCE ASC;";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['IDPROVINCE'] ? $select .='<option value="'.$row['IDPROVINCE'].'"  selected="selected">'.(stripslashes($row['PROVINCE'])).'</option>' : $select .='<option value="'.$row['IDPROVINCE'].'">'.(stripslashes($row['PROVINCE'])).'</option>');
	} // while
	return $select;
}

//MAGASIN
function selectService($default=''){
	$sql = "SELECT * FROM magasin  ORDER BY SER_NOM ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query
	$select='';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['CODE_MAGASIN'] ? $select .='<option value="'.$row['CODE_MAGASIN'].'"  selected="selected">'.(stripslashes($row['SER_NOM'])).'</option>' : $select .='<option value="'.$row['CODE_MAGASIN'].'">'.(stripslashes($row['SER_NOM'])).'</option>');
	} // while
	return $select;
}

//MAGASIN
function selectMagasinAll($default=''){
	$sql = "SELECT * FROM magasin ORDER BY SER_NOM ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query
	$select='';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['CODE_MAGASIN'] ? $select .='<option value="'.$row['CODE_MAGASIN'].'"  selected="selected">'.(stripslashes($row['SER_NOM'])).'</option>' : $select .='<option value="'.$row['CODE_MAGASIN'].'">'.(stripslashes($row['SER_NOM'])).'</option>');
	} // while
	return $select;
}

function selectMagasinForProvince($province='', $default=''){
	$select='';
	if($province!=''){
		$sql = "SELECT * FROM magasin WHERE IDPROVINCE='$province' ORDER BY SER_NOM ASC;";
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			die($error->getMessage().' '.__LINE__);
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			($default == $row['CODE_MAGASIN'] ? $select .='<option value="'.$row['CODE_MAGASIN'].'"  selected="selected">'.(stripslashes($row['SER_NOM'])).'</option>' : $select .='<option value="'.$row['CODE_MAGASIN'].'">'.(stripslashes($row['SER_NOM'])).'</option>');
		} // while

	}
	return $select;
}

//TYPE SERCICE
function selectTypeService($default=''){
	$sql = "SELECT * FROM groupeservice ORDER BY GRPSER_LIBELLE ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['ID_GRPSERVICE'] ? $select .='<option value="'.$row['ID_GRPSERVICE'].'"  selected="selected">'.(stripslashes($row['GRPSER_LIBELLE'])).'</option>' : $select .='<option value="'.$row['ID_GRPSERVICE'].'">'.(stripslashes($row['GRPSER_LIBELLE'])).'</option>');
	} // while
	return $select;
}

//TYPE SERCICE
function selectTypeFournisseur($default=''){
	$sql = "SELECT * FROM typefournisseur ORDER BY TYPEFOUR_NOM ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['CODE_TYPEFOUR'] ? $select .='<option value="'.$row['CODE_TYPEFOUR'].'"  selected="selected">'.(stripslashes($row['TYPEFOUR_NOM'])).'</option>' : $select .='<option value="'.$row['CODE_TYPEFOUR'].'">'.(stripslashes($row['TYPEFOUR_NOM'])).'</option>');
	} // while
	return $select;
}

//TYPE SERCICE
function selectNatureDeclassement($default=''){
	$sql = "SELECT * FROM natdeclass ORDER BY LIBNATDECLASS  ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['CODENATDECLASS'] ? $select .='<option value="'.$row['CODENATDECLASS'].'"  selected="selected">'.(stripslashes($row['LIBNATDECLASS'])).'</option>' : $select .='<option value="'.$row['CODENATDECLASS'].'">'.(stripslashes($row['LIBNATDECLASS'])).'</option>');
	} // while
	return $select;
}

function selectTypeBeneficiaire($default=''){
	$sql = "SELECT * FROM typebeneficiaire ORDER BY NOM_TYPEBENEF ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['CODE_TYPEBENEF'] ? $select .='<option value="'.$row['CODE_TYPEBENEF'].'"  selected="selected">'.(stripslashes($row['NOM_TYPEBENEF'])).'</option>' : $select .='<option value="'.$row['CODE_TYPEBENEF'].'">'.(stripslashes($row['NOM_TYPEBENEF'])).'</option>');
	} // while
	return $select;
}

//COMMANDE
function selectCommande($default='', $where=''){
	(isset($where) && $where !='' ? $wh = "WHERE $where": $wh='');
	$sql = "SELECT * FROM commande $wh ORDER BY CDE_DATE DESC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}

	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['CODE_COMMANDE'] ? $select .='<option value="'.$row['CODE_COMMANDE'].'"  selected="selected">'.(stripslashes($row['CODE_COMMANDE'].' - '.$row['CDE_LIBELLE'])).'</option>' : $select .='<option value="'.$row['CODE_COMMANDE'].'">'.(stripslashes($row['CODE_COMMANDE'].' - '.$row['CDE_LIBELLE'])).'</option>');
	} // while
	return $select;
}

//LIVRAISON
function selectLivraison($default='', $where=''){
	(isset($where) && $where !='' ? $wh = "WHERE $where": $wh='');
	$sql = "SELECT * FROM livraison $wh ORDER BY LVR_DATE DESC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['ID_LIVRAISON'] ? $select .='<option value="'.$row['ID_LIVRAISON'].'"  selected="selected">'.stripslashes($row['LVR_LIBELLE']).'</option>' : $select .='<option value="'.$row['ID_LIVRAISON'].'">'.stripslashes($row['LVR_LIBELLE']).'</option>');
	} // while
	return $select;
}

function selectProgrammation($default='', $where=''){
	(isset($where) && $where !='' ? $wh = "WHERE $where": $wh='');
	$sql = "SELECT * FROM programmation $wh ORDER BY CDE_DATE DESC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['ID_PROGR'] ? $select .='<option value="'.$row['ID_PROGR'].'"  selected="selected">'.(stripslashes($row['CODE_NDOTATION'].' - '.getBeneficiaire($row['CODE_BENEF']))).'</option>' : $select .='<option value="'.$row['ID_PROGR'].'">'.(stripslashes($row['CODE_NDOTATION'].' - '.getBeneficiaire($row['CODE_BENEF']))).'</option>');
	} // while
	return $select;
}

//COMMANDE NON LIVRE
function selectCommandeNonLivr($default='', $where=''){
	(isset($where) && $where !='' ? $wh = "WHERE $where": $wh='');
	$sql = "SELECT * FROM commande $wh ORDER BY CDE_DATE DESC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		//$ok = getQteCde($row['CODE_COMMANDE'],) - getQteLivr($row['CODE_COMMANDE'],);
		($default == $row['CODE_COMMANDE'] ? $select .='<option value="'.$row['CODE_COMMANDE'].'"  selected="selected">'.(stripslashes($row['CODE_COMMANDE'].' - '.$row['CDE_LIBELLE'])).'</option>' : $select .='<option value="'.$row['CODE_COMMANDE'].'">'.(stripslashes($row['CODE_COMMANDE'].' - '.$row['CDE_LIBELLE'])).'</option>');
	} // while
	return $select;
}

//SELCT DOTATION
function selectDotation($default=''){
	$sql = "SELECT * FROM nomdotation ORDER BY CODE_NDOTATION ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['CODE_NDOTATION'] ? $select .='<option value="'.$row['CODE_NDOTATION'].'"  selected="selected">'.(stripslashes($row['NDOT_LIBELLE'])).'</option>' : $select .='<option value="'.$row['CODE_NDOTATION'].'">'.(stripslashes($row['NDOT_LIBELLE'])).'</option>');
	} // while
	return $select;
}

function selectDotationSansBac($default=''){
	$sql = "SELECT * FROM nomdotation WHERE CODE_NDOTATION <> '10DOT' ORDER BY CODE_NDOTATION ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['CODE_NDOTATION'] ? $select .='<option value="'.$row['CODE_NDOTATION'].'"  selected="selected">'.(stripslashes($row['NDOT_LIBELLE'])).'</option>' : $select .='<option value="'.$row['CODE_NDOTATION'].'">'.(stripslashes($row['NDOT_LIBELLE'])).'</option>');
	} // while
	return $select;
}

function selectDotationBac($default=''){
	$sql = "SELECT * FROM nomdotation WHERE CODE_NDOTATION LIKE '10DOT' ORDER BY CODE_NDOTATION ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['CODE_NDOTATION'] ? $select .='<option value="'.$row['CODE_NDOTATION'].'"  selected="selected">'.(stripslashes($row['NDOT_LIBELLE'])).'</option>' : $select .='<option value="'.$row['CODE_NDOTATION'].'">'.(stripslashes($row['NDOT_LIBELLE'])).'</option>');
	} // while
	return $select;
}

//SELCT DOTATION PROGRAMME
function selectDotationPrg($default=''){
	$sql = "SELECT * FROM nomdotation WHERE CODE_NDOTATION NOT IN ('10DOT', 'ADOT') ORDER BY CODE_NDOTATION ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['CODE_NDOTATION'] ? $select .='<option value="'.$row['CODE_NDOTATION'].'"  selected="selected">'.(stripslashes($row['NDOT_LIBELLE'])).'</option>' : $select .='<option value="'.$row['CODE_NDOTATION'].'">'.(stripslashes($row['NDOT_LIBELLE'])).'</option>');
	} // while
	return $select;
}

//SELECT PROGRAMMATION
function selectProgramme($default='', $valid){
	(isset($valid) && $valid == 1 ? $where = " WHERE PRG_VALID=$valid " : $where ="");
	$sql = "SELECT * FROM programmation INNER JOIN nomdotation ON (programmation.CODE_NDOTATION LIKE nomdotation.CODE_NDOTATION)
	INNER JOIN beneficiaire ON (programmation.CODE_BENEF=beneficiaire.CODE_BENEF) $where ORDER BY PGR_DATE DESC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['ID_PROGR'] ? $select .='<option value="'.$row['ID_PROGR'].'"  selected="selected">'.(stripslashes($row['CODE_NDOTATION']. ' - '.$row['BENEF_NOM'])).'</option>' : $select .='<option value="'.$row['ID_PROGR'].'">'.(stripslashes($row['CODE_NDOTATION'].' - '.$row['BENEF_NOM'])).'</option>');
	} // while
	return $select;
}

function totalReversement($idprog, $exercice){
	$sql = "SELECT SUM(REV_MNTVERSE) AS TOTAL FROM reversement WHERE ID_PROGR=$idprog AND ID_EXERCICE=$exercice;";
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

	return $row['TOTAL'];
}

//PROGRAMMATION
function getDataProgrammation($id){
	$sql = "SELECT * FROM prg_bareme INNER JOIN bareme ON (bareme.ID_BAREME=prg_bareme.ID_BAREME) WHERE ID_PROGR=$id;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	//Ligne
	$newdata =array('ligne'=>array(),	'nbreLigne'=>0);
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		array_push($newdata['ligne'], array('codeproduit'=>'', 'produit'=>$row['BAR_LIBELLE'].':Sélectionnez le conditionnement', 'qte'=>$row['PRG_QTE1'], 'unite'=>$row['ID_UNITE']));
	}
	$newdata['nbreLigne'] = $query->rowCount();
	return $newdata;

}

function checkExercice($exercice){
	$sql = "SELECT ID_EXERCICE FROM exercice WHERE ID_EXERCICE=$exercice;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	//Ligne
	return $query->rowCount();
}

function isClotureExercice($exercice){
	$sql = "SELECT ID_EXERCICE,EX_CLOTURE FROM exercice WHERE ID_EXERCICE=$exercice AND EX_CLOTURE=1;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	//Ligne
	return $query->rowCount();
}


//UTILISATEUR
function selectUtilisateur($default=''){
	$sql = "SELECT * FROM compte INNER JOIN personnel ON (compte.NUM_MLLE LIKE personnel.NUM_MLLE) ORDER BY PERS_NOM , PERS_PRENOMS ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['LOGIN'] ? $select .='<option value="'.$row['LOGIN'].'"  selected="selected">'.(stripslashes($row['PERS_NOM'].' '.$row['PERS_PRENOMS'].' ('.$row['NUM_MLLE'].' - '.$row['LOGIN'].')')).'</option>' : $select .='<option value="'.$row['LOGIN'].'">'.(stripslashes($row['PERS_NOM'].' '.$row['PERS_PRENOMS'].' ('.$row['NUM_MLLE'].' - '.$row['LOGIN'].')')).'</option>');
	} // while
	return $select;
}


//PRODUIT
function selectProduit($default=''){
	$sql = "SELECT * FROM produit  ORDER BY PRD_LIBELLE ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['CODE_PRODUIT'] ? $select .='<option value="'.$row['CODE_PRODUIT'].'"  selected="selected">'.(stripslashes($row['PRD_LIBELLE'])).'</option>' : $select .='<option value="'.$row['CODE_PRODUIT'].'">'.(stripslashes($row['PRD_LIBELLE'])).'</option>');
	} // while
	return $select;
}

function selectProduitConditionalbe($default=''){
	$sql = "SELECT * FROM produit WHERE CONDITIONNE=1 ORDER BY PRD_LIBELLE ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['CODE_PRODUIT'] ? $select .='<option value="'.$row['CODE_PRODUIT'].'"  selected="selected">'.(stripslashes($row['PRD_LIBELLE'])).'</option>' : $select .='<option value="'.$row['CODE_PRODUIT'].'">'.(stripslashes($row['PRD_LIBELLE'])).'</option>');
	} // while
	return $select;
}

function selectProduitCond($default=''){
	$sql = "SELECT * FROM produit ORDER BY PRD_LIBELLE ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['CODE_PRODUIT'] ? $select .='<option value="'.$row['CODE_PRODUIT'].'"  selected="selected">'.(stripslashes($row['PRD_LIBELLE'])).'</option>' : $select .='<option value="'.$row['CODE_PRODUIT'].'">'.(stripslashes($row['PRD_LIBELLE'])).'</option>');
	} // while
	return $select;
}

function getLibConditionne($default){
	$sql = "SELECT PRD_LIBELLE  FROM conditionmt WHERE CODE_PRODUIT =$default;";
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
	return $row['PRD_LIBELLE'];
}

function IsDelivery($cde){
	//Retrouver la commande et charger les ligne
	$sql = "SELECT * FROM prd_cde INNER JOIN commande ON (commande.CODE_COMMANDE  LIKE prd_cde.CODE_COMMANDE)
			INNER JOIN produit ON (produit.CODE_PRODUIT LIKE prd_cde.CODE_PRODUIT)
			WHERE prd_cde.CODE_COMMANDE LIKE '".addslashes($cde)."';";

	//Exécution
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	//Collect Data
	$i=0;
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		$rest =  $row['CDEPRD_QTE'] - livraisonPourProduit($cde, $row['CODE_PRODUIT']);
		//Add to list
		//echo ' rest => ', $rest, ' $i=> ', $i;
		if($rest >0) {
			$i++;
		}
	}
	return $i;
}

//Return the number of day between two dates
function NbJours($debut, $fin) {

  $tDeb = explode("-", $debut);
  $tFin = explode("-", $fin);

  $diff = mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) -
          mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]);

  return round(($diff / 86400)+1);
}

//Generate le pageLength list
function pageLengh($defaut =20){
	$list = '';
	for($i=10; $i<=100; $i+=10){
		($defaut == $i ? $list .= '<option value="'.$i.'" selected>'.$i.'</option>' :
		$list .= '<option value="'.$i.'">'.$i.'</option>');
	}
	return $list;
}

function getEtatInfo(){
	$table1 = "stocks_etat";
	//Save data
	$SQL1 ="SELECT * from $table1;";

	//Connection to Database server
	$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');
	$result = mysql_query($SQL1,$idCon) or header('location:errorPage.php&code=');
	$row = mysql_fetch_array($result);
	return $row;
}

function getUserMagasin($login){
	//Save data
	$sql ="SELECT * from mag_compte INNER JOIN magasin ON (mag_compte.CODE_MAGASIN=magasin.CODE_MAGASIN) WHERE LOGIN LIKE '$login';";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$mag = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		$mag .= $row['SER_NOM'].', ';
	}

	$mag = substr($mag,0, strlen($mag)-2);
	return trim($mag);
}

function getUserServiceList($login, $default){
	//Save data
	$sql ="SELECT mag_compte.*, magasin.SER_NOM from mag_compte
	INNER JOIN magasin ON (mag_compte.CODE_MAGASIN LIKE magasin.CODE_MAGASIN)
	WHERE LOGIN LIKE '$login' ORDER BY magasin.SER_NOM ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$list = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['CODE_MAGASIN'] ? $list .= '<option value="'.$row['CODE_MAGASIN'].'" selected>'.($row['SER_NOM']).'</option>' :
		$list .= '<option value="'.$row['CODE_MAGASIN'].'">'.($row['SER_NOM']).'</option>');
	}
	return $list;
}

function getUserServiceFormProvince($login, $province, $default){
	//Save data
	$sql ="SELECT mag_compte.*, magasin.SER_NOM from mag_compte
	INNER JOIN magasin ON (mag_compte.CODE_MAGASIN LIKE magasin.CODE_MAGASIN)
	WHERE LOGIN LIKE '$login' AND magasin.IDPROVINCE LIKE '$province' ORDER BY magasin.SER_NOM ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$list = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['CODE_MAGASIN'] ? $list .= '<option value="'.$row['CODE_MAGASIN'].'" selected>'.($row['SER_NOM']).'</option>' :
		$list .= '<option value="'.$row['CODE_MAGASIN'].'">'.($row['SER_NOM']).'</option>');
	}
	return $list;
}


function getLastRespo($idmag){
	//Save data
	$SQL1 ="SELECT * from respmagasin INNER JOIN personnel ON (respmagasin.NUM_MLLE=personnel.NUM_MLLE) WHERE CODE_MAGASIN LIKE '$idmag' ORDER BY RES_DATEDEBUT ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query
	if(	$query->rowCount()>0)
		$row = $query->fetch(PDO::FETCH_ASSOC);
		//$row = array('PERS_NOM'=>'',	'PERS_PRENOMS'=>'',	'PERS_TEL'=>'',	'PERS_ADRESSE'=>'',	'PERS_EMAIL'=>'');
	else {
		$row = array('PERS_NOM'=>'',	'PERS_PRENOMS'=>'',	'PERS_TEL'=>'',	'PERS_ADRESSE'=>'',	'PERS_EMAIL'=>'');
	}
	return $row;
}


function page($nbreEng, $engPage, $page, $url){
	//Nombre de page
	($nbreEng % $engPage == 0 ? $nbrePage = (int) ($nbreEng/$engPage) : $nbrePage = (int) ($nbreEng/$engPage +1));
	$Premier = '';
	$Dernier = '';
	($nbreEng < $engPage ? $engPage= $nbreEng : $engPage=$engPage);

$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	if($ilang=='1' && $ilang!='') { 
	if($page == 1){ //Première page  Affichage de 1 &agrave; 25 of 50 | Premier | Pr&eacute;c&eacute;dente | <a href=''>Derni&egrave;re</a></
		$i = ($page-1)*$engPage+1;
		$j = $page*$engPage;
		($page < $nbrePage ? $k = $page+1 : $k = $page);
		$Premier .='Affichage de '.$i.' &agrave; '.$j.' sur '.$nbreEng.' | Premi&egrave;re | Pr&eacute;c&eacute;dente | ';
		$Dernier .='<a href="'.$url.'&page='.$k.'">Suivante</a> | <a href="'.$url.'&page='.$nbrePage.'">Derni&egrave;re</a> ';
	}
	else if($page == $nbrePage){ //Dernière page
		$i = ($page-1)*$engPage+1;
		$j = $page*$engPage;
		($page > 2 ? $k = $page-1 : $k = $page);
		$Premier .='Affichage de '.$i.' &agrave; '.$j.' sur '.$nbreEng.' | <a href="'.$url.'&page=1">Premi&egrave;re</a> | <a href="'.$url.'&page='.$k.'">Pr&eacute;c&eacute;dente</a> | ';
		$Dernier .='Suivante | Derni&egrave;re ';
	}
	else if($page > 1 && $page < $nbrePage) {
		$i = ($page-1)*$engPage+1;
		$j = $page*$engPage;
		($page < $nbrePage ? $l = $page+1 : $l = $page);
		($page > 2 ? $k = $page-1 : $k = $page);
		//$k = $page-1;
		//$l = $page+1;
		$Premier .='Affichage de '.$i.' &agrave; '.$j.' sur '.$nbreEng.' | <a href="'.$url.'&page=1">Premi&egrave;re</a> | <a href="'.$url.'&page='.$k.'">Pr&eacute;c&eacute;dente</a> | ';
		$Dernier .='<a href="'.$url.'&page='.$l.'">Suivante</a> | <a href="'.$url.'&page='.$nbrePage.'">Derni&egrave;re</a> ';
	}
	}
	if($ilang=='2' && $ilang!='') { 
	if($page == 1){ //Première page  Affichage de 1 &agrave; 25 of 50 | Premier | Pr&eacute;c&eacute;dente | <a href=''>Derni&egrave;re</a></
		$i = ($page-1)*$engPage+1;
		$j = $page*$engPage;
		($page < $nbrePage ? $k = $page+1 : $k = $page);
		$Premier .='View of '.$i.' to '.$j.' from '.$nbreEng.' | First | Previous | ';
		$Dernier .='<a href="'.$url.'&page='.$k.'">Next</a> | <a href="'.$url.'&page='.$nbrePage.'">Last</a> ';
	}
	else if($page == $nbrePage){ //Dernière page
		$i = ($page-1)*$engPage+1;
		$j = $page*$engPage;
		($page > 2 ? $k = $page-1 : $k = $page);
		$Premier .='View of '.$i.' &agrave; '.$j.' from '.$nbreEng.' | <a href="'.$url.'&page=1">First</a> | <a href="'.$url.'&page='.$k.'">Previous</a> | ';
		$Dernier .='Next | Last ';
	}
	else if($page > 1 && $page < $nbrePage) {
		$i = ($page-1)*$engPage+1;
		$j = $page*$engPage;
		($page < $nbrePage ? $l = $page+1 : $l = $page);
		($page > 2 ? $k = $page-1 : $k = $page);
		//$k = $page-1;
		//$l = $page+1;
		$Premier .='View of '.$i.' to '.$j.' from '.$nbreEng.' | <a href="'.$url.'&page=1">First</a> | <a href="'.$url.'&page='.$k.'">Previous</a> | ';
		$Dernier .='<a href="'.$url.'&page='.$l.'">Next</a> | <a href="'.$url.'&page='.$nbrePage.'">Last</a> ';
	}
	}
	if($ilang=='3' && $ilang!='') { 
	if($page == 1){ //Première page  Affichage de 1 &agrave; 25 of 50 | Premier | Pr&eacute;c&eacute;dente | <a href=''>Derni&egrave;re</a></
		$i = ($page-1)*$engPage+1;
		$j = $page*$engPage;
		($page < $nbrePage ? $k = $page+1 : $k = $page);
		$Premier .='View de '.$i.' to '.$j.' de '.$nbreEng.' | Primeiro | Anterior | ';
		$Dernier .='<a href="'.$url.'&page='.$k.'">Next</a> | <a href="'.$url.'&page='.$nbrePage.'">Último</a> ';
	}
	else if($page == $nbrePage){ //Dernière page
		$i = ($page-1)*$engPage+1;
		$j = $page*$engPage;
		($page > 2 ? $k = $page-1 : $k = $page);
		$Premier .='View de '.$i.' to '.$j.' de '.$nbreEng.' | <a href="'.$url.'&page=1">Primeiro</a> | <a href="'.$url.'&page='.$k.'">Anterior</a> | ';
		$Dernier .='Next | Último ';
	}
	else if($page > 1 && $page < $nbrePage) {
		$i = ($page-1)*$engPage+1;
		$j = $page*$engPage;
		($page < $nbrePage ? $l = $page+1 : $l = $page);
		($page > 2 ? $k = $page-1 : $k = $page);
		//$k = $page-1;
		//$l = $page+1;
		$Premier .='View de '.$i.' to '.$j.' de '.$nbreEng.' | <a href="'.$url.'&page=1">Primeiro</a> | <a href="'.$url.'&page='.$k.'">Anterior</a> | ';
		$Dernier .='<a href="'.$url.'&page='.$l.'">Next</a> | <a href="'.$url.'&page='.$nbrePage.'">Último</a> ';
	}
	}



	return $Premier.$Dernier;
}


//Fonctions Etat du stock
function etatArticleEntree($idarticle,$exercice='',$date=''){
	$table1 = "stocks_bon_entre";
	$table2 = "stocks_ligne_bon_entre";

	//Where
	$where ='';
	(isset($idarticle) and $idarticle!='' ? $where .= " $table2.ID_ARTICLE ='$idarticle' AND " : $where .= "");
	(isset($exercice) and $exercice !='' ? $where .= " $table1.ID_EXERCICE ='$exercice' AND $table2.ID_EXERCICE = '$exercice' AND " : $where .= "");
	(isset($date)  and $date !='' ? $where .= " $table1.DATE_BONENTRE <= '".mysqlFormat($date)."' AND " : $where .= "");

	if($where != '') $where = substr(" AND $where",0,strlen(" AND $where")-4);

	//Save
	$SQL1 ="SELECT SUM($table2.QTE_ENTREE) AS S_QTE_ENTREE FROM $table1, $table2 WHERE $table1.ID_BONENTRE=$table2.ID_BONENTRE AND $table1.VALIDER=1 $where;";

	//Connection to Database server
	$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');
	$result = mysql_query($SQL1,$idCon) or header('location:errorPage.php&code=');
	$row = mysql_fetch_array($result);
	return $row['S_QTE_ENTREE'];

	//return array('id'=>$idarticle,'nbre'=>$nbre);
}

function etatArticleBonus($idarticle,$exercice='',$date=''){
	// INVENTAIRE BONUS
	$table1 = "stocks_inventaire";
	$table2 = "stocks_ligne_inventaire";

	//Where
	$where ='';
	(isset($idarticle) and $idarticle!='' ? $where .= " $table2.ID_ARTICLE ='$idarticle' AND " : $where .= "");
	(isset($exercice) and $exercice !='' ? $where .= " $table1.ID_EXERCICE ='$exercice' AND $table2.ID_EXERCICE = '$exercice' AND " : $where .= "");
	(isset($date)  and $date !='' ? $where .= " $table1.DATE_INVENTAIRE <= '".mysqlFormat($date)."' AND " : $where .= "");

	if($where != '') $where = substr(" AND $where",0,strlen(" AND $where")-4);

	//Save
	$SQL1 ="SELECT SUM($table2.QTE_INVENTAIRE) AS S_QTE_INVENTAIRE FROM $table1, $table2 WHERE $table1.ID_INVENTAIRE=$table2.ID_INVENTAIRE AND VALIDER=1 AND TYPE_INVENTAIRE='+' $where;";

	//Connection to Database server
	$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');
	$result = mysql_query($SQL1,$idCon) or header('location:errorPage.php&code=');
	$row = mysql_fetch_array($result);
	return $row['S_QTE_INVENTAIRE'];
}

function etatArticleSortie($idarticle,$exercice='',$date=''){
	$table1 = "stocks_bon_sortie";
	$table2 = "stocks_ligne_bon_sortie";

	//Where
	$where ='';
	(isset($idarticle) and $idarticle!='' ? $where .= " $table2.ID_ARTICLE ='$idarticle' AND " : $where .= "");
	(isset($exercice) and $exercice !='' ? $where .= " $table1.ID_EXERCICE ='$exercice' AND $table2.ID_EXERCICE = '$exercice' AND " : $where .= "");
	(isset($date)  and $date !='' ? $where .= " $table1.DATE_BONSORTIE <= '".mysqlFormat($date)."' AND " : $where .= "");

	if($where != '') $where = substr(" AND $where",0,strlen(" AND $where")-4);

	//Save
	$SQL1 ="SELECT SUM($table2.QTE_SORTIE) AS S_QTE_SORTIE FROM $table1, $table2 WHERE $table1.ID_BONSORTIE=$table2.ID_BONSORTIE AND VALIDER=1 $where;";

	//Connection to Database server
	$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB, $idCon) or header('location:errorPage.php&code=');
	$result = mysql_query($SQL1,$idCon) or header('location:errorPage.php&code=');
	$row = mysql_fetch_array($result);
	return $row['S_QTE_SORTIE'];
}

function etatArticleMalus($idarticle,$exercice='',$date=''){
	// INVENTAIRE BONUS
	$table1 = "stocks_inventaire";
	$table2 = "stocks_ligne_inventaire";

	//Where
	$where ='';
	(isset($idarticle) and $idarticle!='' ? $where .= " $table2.ID_ARTICLE ='$idarticle' AND " : $where .= "");
	(isset($exercice) and $exercice !='' ? $where .= " $table1.ID_EXERCICE ='$exercice' AND $table2.ID_EXERCICE = '$exercice' AND " : $where .= "");
	(isset($date)  and $date !='' ? $where .= " $table1.DATE_INVENTAIRE <= '".mysqlFormat($date)."' AND " : $where .= "");

	if($where != '') $where = substr(" AND $where",0,strlen(" AND $where")-4);

	//Save
	$SQL1 ="SELECT SUM($table2.QTE_INVENTAIRE) AS S_QTE_INVENTAIRE FROM $table1, $table2 WHERE $table1.ID_INVENTAIRE=$table2.ID_INVENTAIRE AND VALIDER=1 AND TYPE_INVENTAIRE='-' $where;";

	//Connection to Database server
	$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');
	$result = mysql_query($SQL1,$idCon) or header('location:errorPage.php&code=');
	$row = mysql_fetch_array($result);
	return $row['S_QTE_INVENTAIRE'];
}

function date_diff2($date1, $date2) {
  $s = strtotime($date2)-strtotime($date1);
  $d = intval($s/86400);
  $s -= $d*86400;
  $h = intval($s/3600);
  $s -= $h*3600;
  $m = intval($s/60);
  $s -= $m*60;
  //return array("d"=>$d,"h"=>$h,"m"=>$m,"s"=>$s);
  return $d;  //.' j ';//.$h.' h '.$m.' mn '.$s.' s';
}


function errorMessage($str){
	$msg = "";
	switch($str){
	case 0:
		$msg = '<img src="../images/status50.gif" width="16" height="16" align="absmiddle"> Les donn&eacute;es ont &eacute;t&eacute; enregistr&eacute;es avec succès';
		break;

	default:
		$msg = "";
	}
	return $msg;
}
//----- NOM xxx ---------------------------------------------

function nomCategorie($id){
	$table1 = "stocks_categorie";
	//Save data
	$SQL1 ="SELECT LIBELLE_CATEGORIE  FROM $table1 WHERE ID_CATEGORIE='$id';";

		//Connection to Database server
		$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

		//Select Database
		mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');
		$result = mysql_query($SQL1,$idCon) or header('location:errorPage.php&code=');
		$row = mysql_fetch_array($result);
		return $row[0];
}

function nomBeneficiaire($id){
	$table1 = "stocks_beneficiaire";
	//Save data
	$SQL1 ="SELECT LIBELLE_BENEFICIAIRE   FROM $table1 WHERE CODE_BENEFICIAIRE ='$id';";

	//Connection to Database server
	$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');
	$result = mysql_query($SQL1,$idCon) or header('location:errorPage.php&code=');
	$row = mysql_fetch_array($result);
	return $row[0];
}

function libelleExercice($id){
	$table1 = "stocks_exercice";
	//Save data
	$SQL1 ="SELECT LIBELLE_EXERCICE FROM $table1 WHERE ID_EXERCICE ='$id';";

	//Connection to Database server
	$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');
	$result = mysql_query($SQL1,$idCon) or header('location:errorPage.php&code=');
	$row = mysql_fetch_array($result);
	return $row[0];
}
//Insert LOG

function logFile($user,$date,$descrip){
	$table1 = "stocks_logs";
	//Connection to Database server
	$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');

	//SQL
	$SQL = "INSERT INTO $table1 (`LOGIN` ,`DATE_LOG` ,`DESCRIPTION` ) ";
	$SQL .= "VALUES ('".addslashes($user)."', '".addslashes($date)."' , '".addslashes($descrip)."' );";
	$result = mysql_query($SQL,$idCon) or header('location:errorPage.php&code=');
	return 1;
}




function lignEtatArticles($idarticle='',$date=''){
	$table1 = "stocks_article";
	$exercice = $_SESSION['GL_USER']['EXERCICE'];
	//Connection to Database server
	$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($idarticle) && $idarticle!='' ? $where = " WHERE ID_ARTICLE LIKE '$idarticle'" : $where = "");

	$SQL = "SELECT * FROM $table1 $where ORDER BY LIBELLE_ARTICLE ASC;";
	$result = mysql_query($SQL,$idCon);

	$list = '';

	$i = 1;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		$sE = etatArticleEntree($row['ID_ARTICLE'],$exercice,$date);
		$sB = etatArticleBonus($row['ID_ARTICLE'],$exercice,$date);
		$sS = etatArticleSortie($row['ID_ARTICLE'],$exercice,$date);
		$sM = etatArticleMalus($row['ID_ARTICLE'],$exercice,$date);
		$stock = $sE + $sB - $sM - $sS;

		$list .= '<tr class="'.$col.'">
            <td width="3%" height="22" align="center" valign="middle" class="text">'.$i.'</td>
			<td width="10%" height="22" align="center" valign="middle" class="text">'.$row['ID_ARTICLE'].'</td>
			<td width="10%" height="22" align="left" valign="middle" class="text">'.(stripslashes($row['LIBELLE_ARTICLE'])).'</td>
            <td width="10%" height="22" align="center" valign="middle" class="text">'.$sE.'</a></td>
			<td width="10%" height="22" align="center" valign="middle" class="text">'.$sB.'</a></td>
			<td width="10%" height="22" align="center" valign="middle" class="text">'.$sM.'</a></td>
			<td width="10%" height="22" align="center" valign="middle" class="text">'.$sS.'</a></td>
			<td width="10%" height="22" align="center" valign="middle" class="text">'.$stock.'</a></td>
	        </tr>';
		  $i++;
	}
	if($list ==''){
		$list = '<tr class="tableOddRow">
            <td height="22" align="left" valign="middle" class="text" colspan="4">Aucun article disponible ...</td>
            </tr>';
	}
	//mysql_close($idCon);
	return $list;
}

function lignEtatGroupArticles($idarticle=array(),$date=''){
	$table1 = "stocks_article";
	$exercice = $_SESSION['GL_USER']['EXERCICE'];
	//Connection to Database server
	$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');

	$list = '';

	$i = 1;
	foreach($idarticle as $key =>$val ){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		$sE = etatArticleEntree($val,$exercice,$date);
		$sB = etatArticleBonus($val,$exercice,$date);
		$sS = etatArticleSortie($val,$exercice,$date);
		$sM = etatArticleMalus($val,$exercice,$date);
		$stock = $sE + $sB - $sM - $sS;

		(isset($val) && $val!='' ? $where = " WHERE ID_ARTICLE LIKE '$val'" : $where = "");
		$SQL = "SELECT * FROM $table1 $where ORDER BY LIBELLE_ARTICLE ASC;";
		$result = mysql_query($SQL,$idCon);
		$row = mysql_fetch_array($result);

		$list .= '<tr class="'.$col.'">
            <td width="3%" height="22" align="center" valign="middle" class="text">'.$i.'</td>
			<td width="10%" height="22" align="center" valign="middle" class="text">'.$row['ID_ARTICLE'].'</td>
			<td width="10%" height="22" align="left" valign="middle" class="text">'.(stripslashes($row['LIBELLE_ARTICLE'])).'</td>
            <td width="10%" height="22" align="center" valign="middle" class="text">'.$sE.'</a></td>
			<td width="10%" height="22" align="center" valign="middle" class="text">'.$sB.'</a></td>
			<td width="10%" height="22" align="center" valign="middle" class="text">'.$sM.'</a></td>
			<td width="10%" height="22" align="center" valign="middle" class="text">'.$sS.'</a></td>
			<td width="10%" height="22" align="center" valign="middle" class="text">'.$stock.'</a></td>
	        </tr>';
		  $i++;
	}
	if($list ==''){
		$list = '<tr class="tableOddRow">
            <td height="22" align="left" valign="middle" class="text" colspan="4">Aucun article disponible ...</td>
            </tr>';
	}
	mysql_close($idCon);
	return $list;
}

function lignEtatGroupArticles1($idarticle=array(),$date=''){
	$table1 = "stocks_article";
	$exercice = $_SESSION['GL_USER']['EXERCICE'];
	//Connection to Database server
	$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');

	$list = '';

	$i = 1;
	foreach($idarticle as $key =>$val ){
		//($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		$col='';
		$sE = etatArticleEntree($val,$exercice,$date);
		$sB = etatArticleBonus($val,$exercice,$date);
		$sS = etatArticleSortie($val,$exercice,$date);
		$sM = etatArticleMalus($val,$exercice,$date);
		$stock = $sE + $sB - $sM - $sS;

		(isset($val) && $val!='' ? $where = " WHERE ID_ARTICLE LIKE '$val'" : $where = "");
		$SQL = "SELECT * FROM $table1 $where ORDER BY LIBELLE_ARTICLE ASC;";
		$result = mysql_query($SQL,$idCon);
		$row = mysql_fetch_array($result);

		$list .= '<tr class="'.$col.'">
            <td width="3%" height="22" align="center" valign="middle" class="botBorderTdallEtat">'.$i.'</td>
			<td width="10%" height="22" align="center" valign="middle" class="botBorderTdallEtat">'.$row['ID_ARTICLE'].'&nbsp;</td>
			<td width="10%" height="22" align="left" valign="middle" class="botBorderTdallEtat">'.(stripslashes($row['LIBELLE_ARTICLE'])).'</td>
            <td width="10%" height="22" align="center" valign="middle" class="botBorderTdallEtat">'.$sE.'&nbsp;</a></td>
			<td width="10%" height="22" align="center" valign="middle" class="botBorderTdallEtat">'.$sB.'&nbsp;</a></td>
			<td width="10%" height="22" align="center" valign="middle" class="botBorderTdallEtat">'.$sM.'&nbsp;</a></td>
			<td width="10%" height="22" align="center" valign="middle" class="botBorderTdallEtat">'.$sS.'&nbsp;</a></td>
			<td width="10%" height="22" align="center" valign="middle" class="botBorderTdallEtat">'.$stock.'&nbsp;</a></td>
	        </tr>';
		  $i++;
	}
	if($list ==''){
		$list = '<tr class="tableOddRow">
            <td height="22" align="left" valign="middle" class="text" colspan="4">Aucun article disponible ...</td>
            </tr>';
	}
	mysql_close($idCon);
	return $list;
}



//--------------------- INVENTAIRES ------------------

//Liste type inventaire
function listeTypeInventaire($defaut=''){
	$liste = array('+'=>'Bonus', '-'=>'Malus');
	$list = '';
	foreach ($liste as $key=>$val){
		($defaut == $key ? $list .= '<option value="'.$key.'" selected>'.$key.' '.$val.'</option>' :
		$list .= '<option value="'.$key.'">'.$key.' '.$val.'</option>');
	}
	return $list;
}

function listeIdArticle($exercice){
	$table1 = "stocks_article";
	//Connection to Database server
	$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');

	//SQL
	$SQL = "SELECT ID_ARTICLE, LIBELLE_ARTICLE FROM $table1 ORDER BY LIBELLE_ARTICLE ASC;";
	$result = mysql_query($SQL,$idCon);
	$list = '';
	while($row = mysql_fetch_array($result)){
		$list .= '<option value="'.$row['ID_ARTICLE'].'" >'.$row['ID_ARTICLE'].' - '.(stripslashes($row['LIBELLE_ARTICLE'])).'</option>';
	}
	//mysql_close($idCon);
	return $list;
}

function listeIdCategorie($exercice){
	$table1 = "stocks_categorie";
	//Connection to Database server
	$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');

	//SQL
	$SQL = "SELECT * FROM $table1 ORDER BY LIBELLE_CATEGORIE ASC;";
	$result = mysql_query($SQL,$idCon);
	$list = '';
	while($row = mysql_fetch_array($result)){
		$list .= '<option value="'.$row['ID_CATEGORIE'].'" >'.$row['ID_CATEGORIE'].' - '.(stripslashes($row['LIBELLE_CATEGORIE'])).'</option>';
	}
	//mysql_close($idCon);
	return $list;
}

function listeIdBesoin($exercice){
	$table1 = "stocks_besoin";
	//Connection to Database server
	$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');

	//SQL
	$SQL = "SELECT ID_BESOIN, LIBELLE_BESOIN FROM $table1 WHERE ID_EXERCICE=$exercice ORDER BY ID_BESOIN ASC;";
	$result = mysql_query($SQL,$idCon);
	$list = '';
	while($row = mysql_fetch_array($result)){
		$list .= '<option value="'.$row['ID_BESOIN'].'" >'.$row['ID_BESOIN'].' - '.(stripslashes($row['LIBELLE_BESOIN'])).'</option>';
	}
	//mysql_close);
	return $list;
}


function listeIdInventaire($exercice){
	$table1 = "stocks_inventaire";
	//Connection to Database server
	$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');

	//SQL
	$SQL = "SELECT ID_INVENTAIRE, LIBELLE_INVENTAIRE FROM $table1 WHERE ID_EXERCICE=$exercice ORDER BY ID_INVENTAIRE ASC;";
	$result = mysql_query($SQL,$idCon);
	$list = '';
	while($row = mysql_fetch_array($result)){
		$list .= '<option value="'.$row['ID_INVENTAIRE'].'" >'.$row['ID_INVENTAIRE'].' - '.(stripslashes($row['LIBELLE_INVENTAIRE'])).'</option>';
	}
	//mysql_close($idCon);
	return $list;
}


function listeIdAppel($exercice, $etat=''){
	$table1 = "stocks_appel_offre";
	//Connection to Database server
	$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');

	(isset($etat) && $etat !='' ? $etatcr = " AND VALIDER=$etat" : $etatcr = "");

	//SQL
	$SQL = "SELECT ID_APPELOFFRE, LIBELLE_APPELOFFRE FROM $table1 WHERE ID_EXERCICE=$exercice $etatcr ORDER BY ID_APPELOFFRE ASC;";
	$result = mysql_query($SQL,$idCon);
	$list = '';
	while($row = mysql_fetch_array($result)){
		$list .= '<option value="'.$row['ID_APPELOFFRE'].'" >'.$row['ID_APPELOFFRE'].' - '.(stripslashes($row['LIBELLE_APPELOFFRE'])).'</option>';
	}
	//mysql_close($idCon);
	return $list;
}

function listeIdBonEntre($exercice){
	$table1 = "stocks_bon_entre";
	//Connection to Database server
	$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');

	//SQL
	$SQL = "SELECT ID_BONENTRE, LIBELLE_BONENTRE FROM $table1 WHERE ID_EXERCICE=$exercice ORDER BY ID_BONENTRE ASC;";
	$result = mysql_query($SQL,$idCon);
	$list = '';
	while($row = mysql_fetch_array($result)){
		$list .= '<option value="'.$row['ID_BONENTRE'].'" >'.$row['ID_BONENTRE'].' - '.(stripslashes($row['LIBELLE_BONENTRE'])).'</option>';
	}
	//mysql_close($idCon);
	return $list;
}


function listeIdBonSortie($exercice){
	$table1 = "stocks_bon_sortie";
	//Connection to Database server
	$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');

	//SQL
	$SQL = "SELECT ID_BONSORTIE, LIBELLE_BONSORTIE FROM $table1 WHERE ID_EXERCICE=$exercice ORDER BY ID_BONSORTIE ASC;";
	$result = mysql_query($SQL,$idCon);
	$list = '';
	while($row = mysql_fetch_array($result)){
		$list .= '<option value="'.$row['ID_BONSORTIE'].'" >'.$row['ID_BONSORTIE'].' - '.(stripslashes($row['LIBELLE_BONSORTIE'])).'</option>';
	}
	//mysql_close($idCon);
	return $list;
}

//Liste exercice
function listeExercice($defaut=''){
	$table1 = "exercice";
	//SQL
	$sql = "SELECT ID_EXERCICE, EX_LIBELLE FROM $table1 ORDER BY ID_EXERCICE DESC;";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$list = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($defaut == $row['ID_EXERCICE'] ? $list .= '<option value="'.$row['ID_EXERCICE'].'" selected>'.$row['ID_EXERCICE'].' - '.(stripslashes($row['EX_LIBELLE'])).'</option>' :
		$list .= '<option value="'.$row['ID_EXERCICE'].'">'.$row['ID_EXERCICE'].' - '.(stripslashes($row['EX_LIBELLE'])).'</option>');
	}
	return $list;
}

//Liste exercice
function listeCantine($defaut=''){
	$table1 = "cantine";
	//SQL
	$sql = "SELECT * FROM $table1 ORDER BY NOM_CANTINE DESC;";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		die($error->getMessage().' '.__LINE__);
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$list = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($defaut == $row['ID_CANTINE'] ? $list .= '<option value="'.$row['ID_CANTINE'].'" selected>'.(stripslashes($row['NOM_CANTINE'])).'</option>' :
		$list .= '<option value="'.$row['ID_CANTINE'].'">'.(stripslashes($row['NOM_CANTINE'])).'</option>');
	}
	return $list;
}

//Liste des groupes
function listeGroupes($defaut=''){
	$table1 = "stocks_groupe";
	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$SQL = "SELECT ID_GROUPE, NOM_GROUPE, GRPE_STATUS FROM $table1 WHERE GRPE_STATUS=1 ORDER BY ID_GROUPE ASC;";
	$result = mysql_query($SQL);
	$list = '';
	while($row = mysql_fetch_array($result)){
		($defaut == $row['ID_GROUPE'] ? $list .= '<option value="'.$row['ID_GROUPE'].'" selected>'.$row['ID_GROUPE'].' - '.$row['NOM_GROUPE'].'</option>' :
		$list .= '<option value="'.$row['ID_GROUPE'].'">'.$row['ID_GROUPE'].' - '.$row['NOM_GROUPE'].'</option>');
	}
	return $list;
}

//Liste des catégorie
function listeCategories($defaut=''){
	$table1 = "stocks_categorie";
	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$SQL = "SELECT ID_CATEGORIE, LIBELLE_CATEGORIE FROM $table1 ORDER BY LIBELLE_CATEGORIE ASC;";
	$result = mysql_query($SQL);
	$list = '';
	while($row = mysql_fetch_array($result)){
		($defaut == $row['ID_CATEGORIE'] ? $list .= '<option value="'.$row['ID_CATEGORIE'].'" selected>'.$row['ID_CATEGORIE'].' - '.$row['LIBELLE_CATEGORIE'].'</option>' :
		$list .= '<option value="'.$row['ID_CATEGORIE'].'">'.$row['ID_CATEGORIE'].' - '.$row['LIBELLE_CATEGORIE'].'</option>');
	}
	return $list;
}

//Liste des unités
function listeUnites($defaut=''){
	$table1 = "stocks_unite";
	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$SQL = "SELECT ID_UNITE,  LIBELLE_UNITE,  LIB_COURT FROM $table1 ORDER BY LIB_COURT ASC;";
	$result = mysql_query($SQL);
	$list = '';
	while($row = mysql_fetch_array($result)){
		($defaut == $row['ID_UNITE'] ? $list .= '<option value="'.$row['ID_UNITE'].'" selected>'.($row['LIBELLE_UNITE']).'</option>' :
		$list .= '<option value="'.$row['ID_UNITE'].'">'.($row['LIBELLE_UNITE']).'</option>');
	}
	return $list;
}

//Liste des bénéficiaires
function listeRegions($defaut=''){
	$table1 = "stocks_region";
	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$SQL = "SELECT * FROM $table1 ORDER BY ID_REGION;";
	$result = mysql_query($SQL);
	$list = '';
	while($row = mysql_fetch_array($result)){
		($defaut == $row['ID_REGION'] ? $list .= '<option value="'.$row['ID_REGION'].'" selected>'.$row['ID_REGION'].' - '.($row['NOM_REGION']).'</option>' :
		$list .= '<option value="'.$row['ID_REGION'].'">'.$row['ID_REGION'].' - '.($row['NOM_REGION']).'</option>');
	}
	return $list;
}

//Liste des bénéficiaires
function listeProvinces($defaut=''){
	$table1 = "stocks_province";
	$table2 = "stocks_region";
	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$SQL = "SELECT * FROM $table1, $table2 WHERE $table1.ID_REGION=$table2.ID_REGION ORDER BY $table1.ID_REGION ASC;";
	$result = mysql_query($SQL);
	$list = '';
	while($row = mysql_fetch_array($result)){
		($defaut == $row['ID_PROVINCE'] ? $list .= '<option value="'.$row['ID_PROVINCE'].'" selected>'.$row['ID_PROVINCE'].' - '.($row['NOM_PROVINCE']).'</option>' :
		$list .= '<option value="'.$row['ID_PROVINCE'].'">'.$row['ID_PROVINCE'].' - '.($row['NOM_PROVINCE']).'</option>');
	}
	return $list;
}

//Liste des personnes
function listePersonnes($defaut=''){
	$table1 = "stocks_personnel";
	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$SQL = "SELECT * FROM $table1 ORDER BY NUM_MATRICULE ASC;";
	$result = mysql_query($SQL);
	$list = '';
	while($row = mysql_fetch_array($result)){
		($defaut == $row['NUM_MATRICULE'] ? $list .= '<option value="'.$row['NUM_MATRICULE'].'" selected>'.($row['NUM_MATRICULE']).' - '.$row['NOM_PRENOMS'].'</option>' :
		$list .= '<option value="'.$row['NUM_MATRICULE'].'">'.$row['NUM_MATRICULE'].' - '.($row['NOM_PRENOMS']).'</option>');
	}
	return $list;
}


//Fill the consultation array() of Bon entrée
function setConsInventaire($id){
	$table1 = "stocks_inventaire";
	$table2 = "stocks_ligne_inventaire";
	$table3 = "stocks_article";
	$exercice = $_SESSION['GL_USER']['EXERCICE'];

	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($id) ? $where = " WHERE ID_INVENTAIRE='$id' " : $where = "");
	$SQL = "SELECT $table1.* FROM $table1 $where;";
	$result = mysql_query($SQL);
	$row = mysql_fetch_array($result);

	//Fill session vars
	$_SESSION['CONS_INVENT']= array(
	'idInvent'	=> $row['ID_INVENTAIRE'],
	'exercice'	=> $row['ID_EXERCICE'],
	'dateAjout'	=> frFormat($row['DATE_INVENTAIRE']),
	'libelle'	=> $row['LIBELLE_INVENTAIRE'],
	);

	//SQL
	$where ='';
	(isset($id) ? $where = " WHERE ID_INVENTAIRE='$id' " : $where = "");
	$where .="AND $table2.ID_ARTICLE=$table3.ID_ARTICLE ";
	$SQL = "SELECT $table2.*, $table3.* FROM $table2, $table3 $where ORDER BY NUM ASC;";
	$result = mysql_query($SQL);

	//Fill session vars
	$_SESSION['CONS_INVENT']['ligne'] =array();
	while($row = mysql_fetch_array($result)){
		array_push($_SESSION['CONS_INVENT']['ligne'], array('idArticle'=>$row['ID_ARTICLE'], 'designat'=>$row['LIBELLE_ARTICLE'],'prixUnit'=>$row['PU_INVENTAIRE'], 'typeinventaire'=>$row['TYPE_INVENTAIRE'], 'qte'=>$row['QTE_INVENTAIRE'], 'unite'=>$row['UNITE']));
	}
}

// Set Inventaire NbreLigne et Datat
function setInventaire($cat){
	$table1 = "stocks_article";
	$table2 = "stocks_unite";

	//SQL
	$SQL = "SELECT * FROM $table1 LEFT JOIN  $table2 ON ($table1.ID_UNITE=$table2.ID_UNITE) WHERE ID_CATEGORIE='$cat' ORDER BY LIBELLE_ARTICLE ASC;";
	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=DB_C');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=DB_S');

	$result = mysql_query($SQL);
	$i=0;
	$data = array();
	while($row = mysql_fetch_array($result)) {
		$i++;
		array_push($data,array('idArticle'=>stripcslashes($row['ID_ARTICLE']), 'designat'=>stripcslashes($row['LIBELLE_ARTICLE']),'prixUnit'=>$row['PRIX_UNITAIRE'], 'typeinventaire'=>'', 'qteT'=>'','qteP'=>'',  'unite'=>stripcslashes($row['LIBELLE_UNITE'])));

	}
	return array('nbreLigne'=>$i,'data'=>$data );
}


//Deplay inventairelignes
function lignInventaire($nbre, $ligne){
	$ret = '';
	for ($i=0; $i < $nbre; $i++){
		(isset($ligne[$i]['idArticle']) ? $idArticle 	= $ligne[$i]['idArticle'] 	: $idArticle	='');
		(isset($ligne[$i]['designat']) 	? $designat 	= $ligne[$i]['designat'] 	: $designat		='');
		(isset($ligne[$i]['qteT']) 		? $QteT 		= $ligne[$i]['qteT'] 		: $QteT			='');
		(isset($ligne[$i]['qteP']) 		? $QteP 		= $ligne[$i]['qteP'] 		: $QteP			='');
		(isset($ligne[$i]['unite']) 	? $unite 		= $ligne[$i]['unite'] 		: $unite		='');
		(isset($ligne[$i]['prixUnit'])	? $prixUnit 	= $ligne[$i]['prixUnit'] 	: $prixUnit		='');
		(isset($ligne[$i]['mntTotal'])	? $mntTotal 	= $ligne[$i]['mntTotal'] 	: $mntTotal		='');
		$exercice = $_SESSION['GL_USER']['EXERCICE']; //Exercice
		//$listeTypeInventaire =listeTypeInventaire();

		$sE = etatArticleEntree($idArticle,$exercice);
		$sB = etatArticleBonus($idArticle,$exercice);
		$sS = etatArticleSortie($idArticle,$exercice);
		$sM = etatArticleMalus($idArticle,$exercice);
		$QteT = ($sE + $sB) - ($sM + $sS);
		$j = $i+1;
		$ret .= '<tr align="left" valign="middle">
                    <td class="botBorderTd" nowrap>'.$j.' - </td>
					<!-- <td class="botBorderTd"><input name="openf'.$j.'" type="button" class="button"  value="..." onClick="OpenWin(\'listearticlesinv.php?lg='.$j.'\',\'Liste\');"></td> -->
					<td class="botBorderTd"><input name="idArticle'.$j.'" type="text" readonly class="formStyleFree" id="idArticle'.$j.'" size="10" value="'.$idArticle.'"></td>
                    <td class="botBorderTd"><input name="designat'.$j.'" type="text" readonly class="formStyle" id="designat'.$j.'" value="'.$designat.'"></td>
                    <td class="botBorderTd"><input name="prixUnit'.$j.'" type="text" readonly class="formStyleFree" id="prixUnit'.$j.'" size="10" value="'.$prixUnit.'" onBlur="javascript:if(document.FormInventaire.QteP'.$j.'.value!=\'\' && document.FormInventaire.prixUnit'.$j.'.value !=\'\'){document.FormInventaire.mntTotal'.$j.'.value =document.FormInventaire.qte'.$j.'.value * document.FormInventaire.prixUnit'.$j.'.value;}"></td>
                    <td class="botBorderTd"><input name="QteT'.$j.'" type="text" readonly class="formStyleFree" id="QteT'.$j.'" size="10" value="'.$QteT.'" onBlur="javascript:if(document.FormInventaire.qte'.$j.'.value!=\'\' && document.FormInventaire.prixUnit'.$j.'.value !=\'\'){document.FormInventaire.mntTotal'.$j.'.value =document.FormInventaire.qte'.$j.'.value * document.FormInventaire.prixUnit'.$j.'.value;}"></td>
                    <td class="botBorderTd"><input name="QteP'.$j.'" type="text" class="formStyleFree" id="QteP'.$j.'" size="10" value="'.$QteP.'" onBlur="javascript:if(document.FormInventaire.QteP'.$j.'.value!=\'\' && document.FormInventaire.prixUnit'.$j.'.value !=\'\'){document.FormInventaire.mntTotal'.$j.'.value =document.FormInventaire.QteP'.$j.'.value * document.FormInventaire.prixUnit'.$j.'.value;}"></td>
					<td class="botBorderTd"><input name="unite'.$j.'" type="text" readonly class="formStyleFree" id="unite'.$j.'" size="10" value="'.$unite.'"></td>
                    <td class="botBorderTd"><input name="mntTotal'.$j.'" readonly type="text" class="formStyleFree" id="mntTotal'.$j.'" size="10" value="'.$mntTotal.'"></td>
					<td class="botBorderTd"><input name="qteDispo'.$j.'" type="hidden" class="formStyleFree" id="qteDispo'.$j.'" size="10" value=""></td>
                 </tr>';
	}
	return $ret;
}


function lignConInventaire($wh='', $sens='ASC',$valider='',$page=1,$nelt){
	$ret = '';
	$t = array();
	$table1 = "stocks_inventaire";
	$exercice = $_SESSION['GL_USER']['EXERCICE'];

	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($wh) and $wh!='' ? $where = " AND $wh " : $where = "");
	(isset($valider) and $valider!='' ? $where = " AND VALIDER=$valider " : $where = "");

	$SQL = "SELECT * FROM $table1 WHERE $table1.ID_EXERCICE ='$exercice' $where ORDER BY DATE_INVENTAIRE DESC;";
	$result = mysql_query($SQL);
	$t['NE'] = mysql_num_rows($result);

	$i = ($page-1)*$nelt;
	$SQL = "SELECT * FROM $table1 WHERE $table1.ID_EXERCICE ='$exercice' $where ORDER BY DATE_INVENTAIRE DESC LIMIT $i, $nelt;";
	$result = mysql_query($SQL);
	if(mysql_num_rows($result)==0) $ret .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e</td></tr>';
	$i = 0;
	$j=6;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		($row['VALIDER']==1 ? $valid = '<img src="../images/valider.gif" width="16" height="16">' : $valid = '');
		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_INVENTAIRE'].'" onClick="go(\''.$row['ID_INVENTAIRE'].'\','.$j.');"></td>
                    <td width="2%" align="center">'.$valid.'</td>
					<td width="6%" height="22" class="text" align="center">'.$row['ID_INVENTAIRE'].'</td>
                    <td width="12%" height="22" class="text" align="center">'.frFormat($row['DATE_INVENTAIRE']).'</td>
                    <td width="50%" class="text" >'.(stripcslashes($row['LIBELLE_INVENTAIRE'])).'</td>
                    <td width="5%" class="text" align="center"><input name="detail" type="button" class="button" id="detail"  value="D&eacute;tails &gt;&gt;" onClick="javascript:window.location.href=\'inventaire2.php?selectedTab=inputs&id='.$row['ID_INVENTAIRE'].'\';"></td>
                 </tr>';
				 $i++;
				 $j++;$j++;
	}
	$t['L']=$ret;
	//mysql_close);
	return $t;
}

function lignSearchInventaire($cr1,$cr2,$cr3,$valider,$page=1,$nelt){
//$xreference,$xdateAjout,$libelle);
	$ret = '';
	$t = array();
	$table1 = "stocks_inventaire";
	$exercice = $_SESSION['GL_USER']['EXERCICE'];

	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($cr1) and $cr1!='' ? $where .= " ID_INVENTAIRE LIKE '$cr1' AND " : $where .= "");
	(isset($cr2) and $cr2!='' ? $where .= " DATE_INVENTAIRE = '".mysqlFormat($cr2)."' AND " : $where .= "");
	(isset($cr3) and $cr3!='' ? $where .= " LIBELLE_INVENTAIRE LIKE '%$cr3%' AND " : $where .= "");

	if($where != '') $where = substr(" AND $where",0,strlen(" AND $where")-4);

	$SQL = "SELECT * FROM $table1 WHERE $table1.ID_EXERCICE ='$exercice' $where ORDER BY DATE_INVENTAIRE DESC;";
	$result = mysql_query($SQL);
	$t['NE'] = mysql_num_rows($result);

	$i = ($page-1)*$nelt;
	$SQL = "SELECT * FROM $table1 WHERE $table1.ID_EXERCICE ='$exercice' $where ORDER BY DATE_INVENTAIRE DESC LIMIT $i, $nelt;";
	$result = mysql_query($SQL);
	if(mysql_num_rows($result)==0) $ret .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e</td></tr>';
	$i = 0;
	$j=6;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		($row['VALIDER']==1 ? $valid = '<img src="../images/valider.gif" width="16" height="16">' : $valid = '');
		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_INVENTAIRE'].'" onClick="go(\''.$row['ID_INVENTAIRE'].'\','.$j.');"></td>
                    <td width="2%" align="center">'.$valid.'</td>
					<td width="6%" height="22" class="text" align="center">'.$row['ID_INVENTAIRE'].'</td>
                    <td width="12%" height="22" class="text" align="center">'.frFormat($row['DATE_INVENTAIRE']).'</td>
                    <td width="50%" class="text" >'.(stripcslashes($row['LIBELLE_INVENTAIRE'])).'</td>
                    <td width="5%" class="text" align="center"><input name="detail" type="button" class="button" id="detail"  value="D&eacute;tails &gt;&gt;" onClick="javascript:window.location.href=\'inventaire2.php?selectedTab=inputs&id='.$row['ID_INVENTAIRE'].'\';"></td>
                 </tr>';
				 $i++;
				 $j++;$j++;
	}
	$t['L']=$ret;
	//mysql_close);
	return $t;
}


//Deplay inventaire lignes
function lignDetInventaire($arr){
	$ret = '';
	$i=1;
	foreach ($arr as $key=>$row){
		$ret .='<tr align="left" valign="middle">
				<td class="botBorderTd" nowrap>'.$i.' - </td>
				<td class="botBorderTd"><div class="ligneAll1" nowrap>'.$row['idArticle'].'</div></td>
            	<td class="botBorderTd"><div class="ligneAll" nowrap>'.$row['designat'].'</div></td>
			    <td class="botBorderTd"><div class="ligneAll1" align="right" nowrap>'.number_format($row['prixUnit'],0,',',' ').'</div></td>
				<td class="botBorderTd"><div class="ligneAll1" align="right" nowrap>'.$row['typeinventaire'].'</div></td>
            	<td class="botBorderTd"><div class="ligneAll1" align="right" nowrap>'.$row['qte'].'</div></td>
				<td class="botBorderTd"><div class="ligneAll1" align="left" nowrap>'.(stripcslashes($row['unite'])).'</div></td>
			    <td class="botBorderTd"><div class="ligneAll1" align="right" nowrap>'.number_format($row['qte']*$row['prixUnit'],0,',',' ').'</div></td>
			 </tr>';
			$i++;
	}
	return $ret;
}


function lignEditInventaire($data, $ligne){
	$ret = '';
	$i=1;
	foreach ($data as $key=>$val){
		$listeTypeInventaire = listeTypeInventaire($val['typeinventaire']);
		$ret .= '<tr align="left" valign="middle">
                    <td class="botBorderTd" nowrap>'.$i.' - </td>
					<td class="botBorderTd"><input name="delete'.$i.'" type="button" class="button" title="Supprimer" value=" X " onClick="msgSupprLigne(\''.$val['idArticle'].'\','.$i.');"></td>
					<td class="botBorderTd"><input name="openf'.$i.'" type="button" class="button" title="Liste des articles" value="..." onClick="OpenWin(\'listearticlesinv1.php?lg='.$i.'\',\'Liste\');"></td>
					<td class="botBorderTd"><input name="idArticle'.$i.'" type="text" readonly class="formStyleFree" id="idArticle'.$i.'" size="10" value="'.$val['idArticle'].'">
					<input name="oldArticle'.$i.'" type="hidden" class="formStyleFree" id="oldArticle'.$i.'" size="10" value="'.$val['idArticle'].'"></td>
                    <td class="botBorderTd"><input name="designat'.$i.'" type="text" readonly class="formStyle" id="designat'.$i.'" value="'.$val['designat'].'"></td>
                    <td class="botBorderTd"><input name="prixUnit'.$i.'" type="text" class="formStyleFree" id="prixUnit'.$i.'" size="10" value="'.$val['prixUnit'].'" onBlur="javascript:if(document.FormInventaire.qte'.$i.'.value!=\'\' && document.FormInventaire.prixUnit'.$i.'.value !=\'\'){document.FormInventaire.mntTotal'.$i.'.value =document.FormInventaire.qte'.$i.'.value * document.FormInventaire.prixUnit'.$i.'.value;}"></td>
                    <td class="botBorderTd"><select name="typeinventaire'.$i.'" class="formStyleFree" id="typeinventaire'.$i.'"><option value="00">[+/-]</option>'.$listeTypeInventaire.'</select></td>
					<td class="botBorderTd"><input name="qte'.$i.'" type="text" class="formStyleFree" id="qte'.$i.'" size="10" value="'.$val['qte'].'" onBlur="javascript:if(document.FormInventaire.qte'.$i.'.value!=\'\' && document.FormInventaire.prixUnit'.$i.'.value !=\'\'){document.FormInventaire.mntTotal'.$i.'.value =document.FormInventaire.qte'.$i.'.value * document.FormInventaire.prixUnit'.$i.'.value;}"></td>
					<td class="botBorderTd"><input name="unite'.$i.'" type="text" readonly class="formStyleFree" id="unite'.$i.'" size="10" value="'.$val['unite'].'"></td>
                    <td class="botBorderTd"><input name="mntTotal'.$i.'" readonly type="text" class="formStyleFree" id="mntTotal'.$i.'" size="10" value="'.$val['qte']*$val['prixUnit'].'"></td>
					<td class="botBorderTd"><input name="qteDispo'.$i.'" type="hidden" class="formStyleFree" id="qteDispo'.$i.'" size="10" value=""></td>
                 </tr>';
				 $i++;
	}
	return $ret;
}

function lignConValInventaire($wh='', $sens='ASC',$page=1,$nelt){
	$ret = '';
	$t = array();
	$table1 = "stocks_inventaire";
	$exercice = $_SESSION['GL_USER']['EXERCICE'];

	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($wh) and $wh!='' ? $where = " AND $wh " : $where = "");

	$SQL = "SELECT * FROM $table1 WHERE $table1.ID_EXERCICE ='$exercice' AND $table1.VALIDER=0 $where ORDER BY DATE_INVENTAIRE DESC;";
	$result = mysql_query($SQL);
	$t['NE'] = mysql_num_rows($result);

	$i = ($page-1)*$nelt;
	$SQL = "SELECT * FROM $table1 WHERE $table1.ID_EXERCICE ='$exercice' AND $table1.VALIDER=0 $where ORDER BY DATE_INVENTAIRE DESC LIMIT $i, $nelt;";
	$result = mysql_query($SQL);
	if(mysql_num_rows($result)==0) $ret .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e</td></tr>';
	$i = 0;
	$j = 4;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		($row['VALIDER']==1 ? $valid = '<img src="../images/valider.gif" width="16" height="16">' : $valid = '');
		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_INVENTAIRE'].'" onClick="go(\''.$row['ID_INVENTAIRE'].'\','.$j.');"></td>
					<td width="2%" align="center">'.$valid.'</td>
                    <td width="6%" height="22" class="text" align="center">'.$row['ID_INVENTAIRE'].'</td>
                    <td width="12%" height="22" class="text" align="center">'.frFormat($row['DATE_INVENTAIRE']).'</td>
                    <td width="50%" class="text" >'.(stripcslashes($row['LIBELLE_INVENTAIRE'])).'</td>
                 </tr>';
				 $i++;
				 $j++;$j++;
	}
	$t['L']=$ret;
	//mysql_close);
	return $t;
}

function lignSearchValInventaire($cr1,$cr2,$cr3, $page=1, $nelt){
	$ret = '';
	$t = array();
	$table1 = "stocks_inventaire";
	$exercice = $_SESSION['GL_USER']['EXERCICE'];

	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ="";
	(isset($cr1) and $cr1!='' ? $where .= " AND ID_INVENTAIRE LIKE '$cr1'" : $where .= "");
	(isset($cr2) and $cr2!='' ? $where .= " AND DATE_INVENTAIRE = '".mysqlFormat($cr2)."'" : $where .= "");
	(isset($cr3) and $cr3!='' ? $where .= " AND LIBELLE_INVENTAIRE LIKE '%$cr3%'" : $where .= "");

	$SQL = "SELECT * FROM $table1 WHERE $table1.ID_EXERCICE ='$exercice' AND $table1.VALIDER=0 $where ORDER BY DATE_INVENTAIRE DESC;";
	$result = mysql_query($SQL);
	$t['NE'] = mysql_num_rows($result);

	$i = ($page-1)*$nelt;
	$SQL = "SELECT * FROM $table1 WHERE $table1.ID_EXERCICE ='$exercice' AND $table1.VALIDER=0 $where ORDER BY DATE_INVENTAIRE DESC LIMIT $i, $nelt;";
	$result = mysql_query($SQL);
	if(mysql_num_rows($result)==0) $ret .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e</td></tr>';
	$i = 0;
	$j = 4;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		($row['VALIDER']==1 ? $valid = '<img src="../images/valider.gif" width="16" height="16">' : $valid = '');
		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_INVENTAIRE'].'" onClick="go(\''.$row['ID_INVENTAIRE'].'\','.$j.');"></td>
					<td width="2%" align="center">'.$valid.'</td>
                    <td width="6%" height="22" class="text" align="center">'.$row['ID_INVENTAIRE'].'</td>
                    <td width="12%" height="22" class="text" align="center">'.frFormat($row['DATE_INVENTAIRE']).'</td>
                    <td width="50%" class="text" >'.(stripcslashes($row['LIBELLE_INVENTAIRE'])).'</td>
                 </tr>';
				 $i++;
				 $j++;$j++;
	}
	$t['L']=$ret;
	//mysql_close);
	return $t;
}


// Deplay consultation ligne
function lignDetBentree($arr){
	$ret = '';
	$i=1;
	foreach ($arr as $key=>$row){
		$ret .='<tr align="left" valign="middle">
				<td class="botBorderTd" nowrap>'.$i.' - </td>
				<td class="botBorderTd"><div class="ligneAll1" nowrap>'.$row['idArticle'].'</div></td>
            	<td class="botBorderTd"><div class="ligneAll" nowrap>'.($row['designat']).'</div></td>
			    <td class="botBorderTd"><div class="ligneAll1" align="right" nowrap>'.number_format($row['prixUnit'],0,',',' ').'</div></td>
            	<td class="botBorderTd"><div class="ligneAll1" align="right" nowrap>'.$row['qte'].'</div></td>
				<td class="botBorderTd"><div class="ligneAll1" align="left" nowrap>'.($row['unite']).'</div></td>
			    <td class="botBorderTd"><div class="ligneAll1" align="right" nowrap>'.number_format($row['qte']*$row['prixUnit'],0,',',' ').'</div></td>
			 </tr>';
			$i++;
	}
	return $ret;
}

//--------- BONS SORTIE -----------------------------

//Fill the consultation array() of Bon entrée
function setConsBsortie($id){
	$table1 = "stocks_bon_sortie";
	$table2 = "stocks_ligne_bon_sortie";
	$table3 = "stocks_article";
	$exercice = $_SESSION['GL_USER']['EXERCICE'];

	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($id) ? $where = " AND ID_BONSORTIE='$id' " : $where = "");
	$SQL = "SELECT $table1.* FROM $table1 WHERE $table1.ID_EXERCICE='$exercice' $where;";
	$result = mysql_query($SQL);
	$row = mysql_fetch_array($result);

	//Fill session vars
	$_SESSION['CONS_BSORTIE']= array(
	'idBesoin'	=> $row['ID_BONSORTIE'],
	'exercice'	=> $row['ID_EXERCICE'],
	'service'	=> $row['CODE_BENEFICIAIRE'].' - '.nomBeneficiaire($row['CODE_BENEFICIAIRE']),
	'autre'	=> $row['AUTRE'],
	'dateAjout'	=> frFormat($row['DATE_BONSORTIE']),
	'libelle'	=> $row['LIBELLE_BONSORTIE']
	);

	//SQL
	$where ='';
	(isset($id) ? $where = " WHERE ID_BONSORTIE='$id' " : $where = "");
	$where .="AND $table2.ID_ARTICLE=$table3.ID_ARTICLE ";
	$SQL = "SELECT $table2.*, $table3.* FROM $table2, $table3 $where ORDER BY NUM ASC;";
	$result = mysql_query($SQL);

	//Fill session vars
	$_SESSION['CONS_BSORTIE']['ligne'] =array();
	while($row = mysql_fetch_array($result)){
		array_push($_SESSION['CONS_BSORTIE']['ligne'], array('idArticle'=>$row['ID_ARTICLE'], 'designat'=>$row['LIBELLE_ARTICLE'],'prixUnit'=>$row['PU_SORTIE'],'qte'=>$row['QTE_SORTIE'],'unite'=>$row['UNITE']));
	}
}

//Deplay bon d'sortie lignes
function lignConBsortie($wh='', $ord='', $sens='ASC',$valider='',$page=1,$nelt){
	$ret = '';
	$t = array();
	$table1 = "stocks_bon_sortie";
	$exercice = $_SESSION['GL_USER']['EXERCICE'];

	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ="";
	(isset($valider) and $valider!='' ? $where .= " AND VALIDER=$valider " : $where = "");

	//$order ='';
	//(isset($ord) and $wh!=''  ? $order = " ORDER BY $ord $sens" : $order = "");
	$SQL = "SELECT * FROM $table1 WHERE $table1.ID_EXERCICE='$exercice' $where ORDER BY DATE_BONSORTIE DESC;";
	$result = mysql_query($SQL);
	$t['NE'] = mysql_num_rows($result);

	$i = ($page-1)*$nelt;
	$SQL = "SELECT * FROM $table1 WHERE $table1.ID_EXERCICE='$exercice' $where ORDER BY DATE_BONSORTIE DESC LIMIT $i, $nelt;";
	$result = mysql_query($SQL);
	if(mysql_num_rows($result)==0) $ret .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e</td></tr>';
	$i = 0;
	$j=6;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		($row['VALIDER']==1 ? $valid = '<img src="../images/valider.gif" width="16" height="16">' : $valid = '');
		$benef = nomBeneficiaire($row['CODE_BENEFICIAIRE']);
		if($row['AUTRE']!='') $benef .="(".$row['AUTRE'].")";

		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_BONSORTIE'].'" onClick="go(\''.$row['ID_BONSORTIE'].'\','.$j.');"></td>
                    <td width="1%" height="22" class="text" align="center">'.$valid.'</td>
					<td width="6%" height="22" class="text" align="center">'.$row['ID_BONSORTIE'].'</td>
                    <td width="12%" height="22" class="text" align="center">'.frFormat($row['DATE_BONSORTIE']).'</td>
					<td width="25%" class="text" >'.(stripcslashes($row['LIBELLE_BONSORTIE'])).'</td>
                    <td width="25%" class="text" >'.($benef).'</td>
                    <td width="5%" class="text" align="center"><input name="detail" type="button" class="button" id="detail"  value="D&eacute;tails &gt;&gt;" onClick="javascript:window.location.href=\'bonsortie2.php?selectedTab=outings&displayName=outings&id='.$row['ID_BONSORTIE'].'\';"></td>
                 </tr>';
				 $i++;
				 $j++; $j++;
	}
	$t['L']=$ret;
	//mysql_close);
	return $t;
}

function lignSearchBsortie($cr1,$cr2,$cr3,$cr4,$valider,$page=1,$nelt){
//($xreference,$xdateAjout,$xlibelle,$xservice);
	$ret = '';
	$t = array();
	$t = array();
	$table1 = "stocks_bon_sortie";
	$exercice = $_SESSION['GL_USER']['EXERCICE'];

	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ="";
	(isset($cr1) and $cr1!='' ? $where .= " AND ID_BONSORTIE LIKE '$cr1'" : $where .= "");
	(isset($cr2) and $cr2!='' ? $where .= " AND DATE_BONSORTIE = '".mysqlFormat($cr2)."'" : $where .= "");
	(isset($cr3) and $cr3!='' ? $where .= " AND LIBELLE_BONSORTIE LIKE '%$cr3%'" : $where .= "");
	(isset($cr4) and $cr4!='' ? $where .= " AND CODE_BENEFICIAIRE = '$cr4'" : $where .= "");
	(isset($valider) and $valider!='' ? $where .= " AND VALIDER =$valider" : $where .= "");

	//if($where != '') $where = substr($where,0,strlen($where)-4);

	$SQL = "SELECT * FROM $table1 WHERE $table1.ID_EXERCICE='$exercice' $where ORDER BY DATE_BONSORTIE DESC;";
	$result = mysql_query($SQL);
	$t['NE'] = mysql_num_rows($result);

	$i = ($page-1)*$nelt;
	$SQL = "SELECT * FROM $table1 WHERE $table1.ID_EXERCICE='$exercice' $where ORDER BY DATE_BONSORTIE DESC LIMIT $i, $nelt;";
	$result = mysql_query($SQL);

	if(mysql_num_rows($result)==0) $ret .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e</td></tr>';
	$i = 0;
	$j=6;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		($row['VALIDER']==1 ? $valid = '<img src="../images/valider.gif" width="16" height="16">' : $valid = '');
		$benef = nomBeneficiaire($row['CODE_BENEFICIAIRE']);
		if($row['AUTRE']!='') $benef .="(".$row['AUTRE'].")";

		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_BONSORTIE'].'" onClick="go(\''.$row['ID_BONSORTIE'].'\','.$j.');"></td>
                    <td width="1%" height="22" class="text" align="center">'.$valid.'</td>
					<td width="6%" height="22" class="text" align="center">'.$row['ID_BONSORTIE'].'</td>
                    <td width="12%" height="22" class="text" align="center">'.frFormat($row['DATE_BONSORTIE']).'</td>
                    <td width="25%" class="text" >'.(stripslashes($row['LIBELLE_BONSORTIE'])).'</td>
					<td width="25%" class="text" >'.(stripslashes($benef)).'</td>
                    <td width="5%" class="text" align="center"><input name="detail" type="button" class="button" id="detail"  value="D&eacute;tails &gt;&gt;" onClick="javascript:window.location.href=\'bonsortie2.php?selectedTab=outings&displayName=outings&id='.$row['ID_BONSORTIE'].'\';"></td>
                 </tr>';
				 $i++;
				 $j++; $j++;
	}
	$t['L']=$ret;
	//mysql_close);
	return $t;
}

function lignConValBsortie($wh='', $ord='', $sens='ASC',$valider='',$page=1,$nelt){
	$ret = '';
	$t = array();
	$table1 = "stocks_bon_sortie";
	$exercice = $_SESSION['GL_USER']['EXERCICE'];

	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($wh) and $wh!='' ? $where .= " AND $wh " : $where = "");

	$SQL = "SELECT * FROM $table1 WHERE $table1.ID_EXERCICE ='$exercice' AND $table1.VALIDER=0 AND $table1.ID_EXERCICE='$exercice' $where ORDER BY DATE_BONSORTIE DESC;";
	$result = mysql_query($SQL);
	$t['NE'] = mysql_num_rows($result);

	$i = ($page-1)*$nelt;
	$SQL = "SELECT * FROM $table1 WHERE $table1.ID_EXERCICE ='$exercice' AND $table1.VALIDER=0 $where ORDER BY DATE_BONSORTIE DESC LIMIT $i, $nelt;";
	$result = mysql_query($SQL);
	if(mysql_num_rows($result)==0) $ret .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e</td></tr>';
	$i = 0;
	$j=5;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		($row['VALIDER']==1 ? $valid = '<img src="../images/valider.gif" width="16" height="16">' : $valid = '');
		$benef = nomBeneficiaire($row['CODE_BENEFICIAIRE']);
		if($row['AUTRE']!='') $benef .="(".$row['AUTRE'].")";

		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_BONSORTIE'].'" onClick="go(\''.$row['ID_BONSORTIE'].'\','.$j.');"></td>
                    <td width="1%" height="22" class="text" align="center">'.$valid.'</td>
					<td width="6%" height="22" class="text" align="center">'.$row['ID_BONSORTIE'].'</td>
                    <td width="12%" height="22" class="text" align="center">'.frFormat($row['DATE_BONSORTIE']).'</td>
					<td width="25%" class="text" >'.(stripslashes($row['LIBELLE_BONSORTIE'])).'</td>
                    <td width="25%" class="text" >'.(stripslashes($benef)).'</td>
                    <td width="5%" class="text" align="center"><input name="detail" type="button" class="button" id="detail"  value="D&eacute;tails &gt;&gt;" onClick="javascript:window.location.href=\'validbonsortie1.php?selectedTab=outings&displayName=outings&id='.$row['ID_BONSORTIE'].'\';"></td>
                 </tr>';
				 $i++;
				 $j++; $j++;
	}
	$t['L']=$ret;
	//mysql_close);
	return $t;
}

function lignSearchValBsortie($cr1,$cr2,$cr3,$cr4,$valider,$page=1,$nelt){
//($xreference,$xdateAjout,$xlibelle,$xservice);
	$ret = '';
	$t = array();
	$table1 = "stocks_bon_sortie";
	$exercice = $_SESSION['GL_USER']['EXERCICE'];

	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($cr1) and $cr1!='' ? $where .= " AND ID_BONSORTIE LIKE '$cr1'" : $where .= "");
	(isset($cr2) and $cr2!='' ? $where .= " AND DATE_BONSORTIE = '".mysqlFormat($cr2)."'" : $where .= "");
	(isset($cr3) and $cr3!='' ? $where .= " AND LIBELLE_BONSORTIE LIKE '%$cr3%'" : $where .= "");
	(isset($cr4) and $cr4!='' ? $where .= " AND CODE_BENEFICIAIRE = '$cr4'" : $where .= "");
	(isset($valider) and $valider!='' ? $where .= " AND VALIDER =$valider" : $where .= "");

	//SQL
	//$where ='';
	//(isset($wh) and $wh!='' ? $where = " AND $wh " : $where = "");

	//$order ='';
	//(isset($ord) and $wh!=''  ? $order = " ORDER BY $ord $sens" : $order = "");
	$SQL = "SELECT * FROM $table1 WHERE $table1.ID_EXERCICE ='$exercice' AND $table1.VALIDER=0 $where ORDER BY DATE_BONSORTIE DESC ;";

	$result = mysql_query($SQL);
	$t['NE'] = mysql_num_rows($result);

	$i = ($page-1)*$nelt;
	$SQL = "SELECT * FROM $table1 WHERE $table1.ID_EXERCICE ='$exercice' AND $table1.VALIDER=0 $where ORDER BY DATE_BONSORTIE DESC LIMIT $i, $nelt;";
	$result = mysql_query($SQL);
	if(mysql_num_rows($result)==0) $ret .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e</td></tr>';
	$i = 0;
	$j=5;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		($row['VALIDER']==1 ? $valid = '<img src="../images/valider.gif" width="16" height="16">' : $valid = '');
		$benef = nomBeneficiaire($row['CODE_BENEFICIAIRE']);
		if($row['AUTRE']!='') $benef .="(".$row['AUTRE'].")";

		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_BONSORTIE'].'" onClick="go(\''.$row['ID_BONSORTIE'].'\','.$j.');"></td>
                    <td width="1%" height="22" class="text" align="center">'.$valid.'</td>
					<td width="6%" height="22" class="text" align="center">'.$row['ID_BONSORTIE'].'</td>
                    <td width="12%" height="22" class="text" align="center">'.frFormat($row['DATE_BONSORTIE']).'</td>
                    <td width="25%" class="text" >'.(stripslashes($row['LIBELLE_BONSORTIE'])).'</td>
					<td width="25%" class="text" >'.(stripslashes($benef)).'</td>
                    <td width="5%" class="text" align="center"><input name="detail" type="button" class="button" id="detail"  value="D&eacute;tails &gt;&gt;" onClick="javascript:window.location.href=\'bonsortie2.php?selectedTab=outings&displayName=outings&id='.$row['ID_BONSORTIE'].'\';"></td>
                 </tr>';
				 $i++;
				 $j++; $j++;
	}
	$t['L']=$ret;
	//mysql_close);
	return $t;
}

//Deplay bon sortie lignes
function lignBonsortie($nbre, $ligne){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($ligne[$i]['idArticle']) ? $idArticle 	= $ligne[$i]['idArticle'] 	: $idArticle	='');
		(isset($ligne[$i]['designat']) 	? $designat 	= stripslashes($ligne[$i]['designat']) 	: $designat		='');
		(isset($ligne[$i]['qte']) 		? $qte 			= $ligne[$i]['qte'] 		: $qte			='');
		(isset($ligne[$i]['unite']) 	? $unite 		= stripslashes($ligne[$i]['unite']) 		: $unite		='');
		(isset($ligne[$i]['prixUnit'])	? $prixUnit 	= $ligne[$i]['prixUnit'] 	: $prixUnit		='');
		(isset($ligne[$i]['mntTotal'])	? $mntTotal 	= $ligne[$i]['mntTotal'] 	: $mntTotal		='');

		$ret .= '<tr align="left" valign="middle">
                    <td class="botBorderTd" nowrap>'.$i.' - </td>
					<td class="botBorderTd"><input name="openf'.$i.'" type="button" class="button"  value="..." onClick="OpenWin(\'listearticlesbs.php?lg='.$i.'\',\'Liste\');"></td>
					<td class="botBorderTd"><input name="idArticle'.$i.'" type="text" readonly class="formStyleFree" id="idArticle'.$i.'" size="10" value="'.$idArticle.'"></td>
                    <td class="botBorderTd"><input name="designat'.$i.'" type="text" readonly class="formStyle" id="designat'.$i.'" value="'.$designat.'"></td>
                    <td class="botBorderTd"><input name="prixUnit'.$i.'" type="text" class="formStyleFree" id="prixUnit'.$i.'" size="10" value="'.$prixUnit.'" onBlur="javascript:if(document.FormBonsortie.qte'.$i.'.value!=\'\' && document.FormBonsortie.prixUnit'.$i.'.value !=\'\'){document.FormBonsortie.mntTotal'.$i.'.value =document.FormBonsortie.qte'.$i.'.value * document.FormBonsortie.prixUnit'.$i.'.value;}"></td>
                    <td class="botBorderTd"><input name="qte'.$i.'" type="text" class="formStyleFree" id="qte'.$i.'" size="10" value="'.$qte.'" onBlur="javascript:if(document.FormBonsortie.qte'.$i.'.value!=\'\' && parseInt(document.FormBonsortie.qte'.$i.'.value)> parseInt(document.FormBonsortie.qteDispo'.$i.'.value)) {alert(\'Impossible, le stock disponible est \'+document.FormBonsortie.qteDispo'.$i.'.value);document.FormBonsortie.qte'.$i.'.focus();} if(document.FormBonsortie.qte'.$i.'.value!=\'\' && document.FormBonsortie.prixUnit'.$i.'.value !=\'\'){document.FormBonsortie.mntTotal'.$i.'.value =document.FormBonsortie.qte'.$i.'.value * document.FormBonsortie.prixUnit'.$i.'.value;}"></td>
					<td class="botBorderTd"><input name="unite'.$i.'" type="text" readonly class="formStyleFree" id="unite'.$i.'" size="10" value="'.$unite.'"></td>
                    <td class="botBorderTd"><input name="mntTotal'.$i.'" readonly type="text" class="formStyleFree" id="mntTotal'.$i.'" size="10" value="'.$mntTotal.'"></td>
					<td class="botBorderTd"><input name="qteDispo'.$i.'" type="hidden" class="formStyleFree" id="qteDispo'.$i.'" size="10" value=""></td>
                 </tr>';
	}
	return $ret;
}

//Deplay consultation ligne
function lignDetBsortie($arr){
	$ret = '';
	$i=1;
	foreach ($arr as $key=>$row){
		$ret .='<tr align="left" valign="middle">
				<td class="botBorderTd" nowrap>'.$i.' - </td>
				<td class="botBorderTd"><div class="ligneAll1" nowrap>'.$row['idArticle'].'</div></td>
            	<td class="botBorderTd"><div class="ligneAll" nowrap>'.(stripslashes($row['designat'])).'</div></td>
			    <td class="botBorderTd"><div class="ligneAll1" align="right" nowrap>'.number_format($row['prixUnit'],0,',',' ').'</div></td>
            	<td class="botBorderTd"><div class="ligneAll1" align="right" nowrap>'.$row['qte'].'</div></td>
				<td class="botBorderTd"><div class="ligneAll1" align="left" nowrap>'.(stripslashes($row['unite'])).'</div></td>
			    <td class="botBorderTd"><div class="ligneAll1" align="right" nowrap>'.number_format($row['qte']*$row['prixUnit'],0,',',' ').'</div></td>
			 </tr>';
			$i++;
	}
	return $ret;
}

function lignEditBsorties($data, $ligne){
	$ret = '';
	$i=1;
	$exercice = $_SESSION['GL_USER']['EXERCICE'];
	foreach ($data as $key=>$val){
		$sE = etatArticleEntree($val['idArticle'],$exercice);
		$sB = etatArticleBonus($val['idArticle'],$exercice);
		$sS = etatArticleSortie($val['idArticle'],$exercice);
		$sM = etatArticleMalus($val['idArticle'],$exercice);
		$stock = ($sE + $sB) - ($sM + $sS);

		$ret .= '<tr align="left" valign="middle">
                    <td class="botBorderTd" nowrap>'.$i.' - </td>
					<td class="botBorderTd"><input name="delete'.$i.'" type="button" class="button" title="Supprimer" value=" X " onClick="msgSupprLigne(\''.$val['idArticle'].'\','.$i.');"></td>
					<td class="botBorderTd"><input name="openf'.$i.'" type="button" class="button"  value="..." onClick="OpenWin(\'listearticlesbs1.php?lg='.$i.'\',\'Liste\');"></td>
					<td class="botBorderTd"><input name="idArticle'.$i.'" type="text" readonly class="formStyleFree" id="idArticle'.$i.'" size="10" value="'.$val['idArticle'].'">
					<input name="oldArticle'.$i.'" type="hidden" class="formStyleFree" id="oldArticle'.$i.'" size="10" value="'.$val['idArticle'].'"></td>
                    <td class="botBorderTd"><input name="designat'.$i.'" type="text" readonly class="formStyle" id="designat'.$i.'" value="'.stripslashes($val['designat']).'"></td>
                    <td class="botBorderTd"><input name="prixUnit'.$i.'" type="text" class="formStyleFree" id="prixUnit'.$i.'" size="10" value="'.$val['prixUnit'].'" onBlur="javascript:if(document.AddBsortieForm.qte'.$i.'.value!=\'\' && document.AddBsortieForm.prixUnit'.$i.'.value !=\'\'){document.AddBsortieForm.mntTotal'.$i.'.value =document.AddBsortieForm.qte'.$i.'.value * document.AddBsortieForm.prixUnit'.$i.'.value;}"></td>
                    <td class="botBorderTd"><input name="qte'.$i.'" type="text" class="formStyleFree" id="qte'.$i.'" size="10" value="'.$val['qte'].'" onBlur="javascript:if(document.AddBsortieForm.qte'.$i.'.value!=\'\' && parseInt(document.AddBsortieForm.qte'.$i.'.value)> parseInt(document.AddBsortieForm.qteDispo'.$i.'.value)) {alert(\'Impossible, le stock disponible est \'+document.AddBsortieForm.qteDispo'.$i.'.value);document.AddBsortieForm.qte'.$i.'.focus();}if(document.AddBsortieForm.qte'.$i.'.value!=\'\' && document.AddBsortieForm.prixUnit'.$i.'.value !=\'\'){document.AddBsortieForm.mntTotal'.$i.'.value =document.AddBsortieForm.qte'.$i.'.value * document.AddBsortieForm.prixUnit'.$i.'.value;}"></td>
					<td class="botBorderTd"><input name="unite'.$i.'" type="text" readonly class="formStyleFree" id="unite'.$i.'" size="10" value="'.stripslashes($val['unite']).'"></td>
                    <td class="botBorderTd"><input name="mntTotal'.$i.'" readonly type="text" class="formStyleFree" id="mntTotal'.$i.'" size="10" value="'.$val['qte']*$val['prixUnit'].'"></td>
					<td class="botBorderTd"><input name="qteDispo'.$i.'" type="hidden" class="formStyleFree" id="qteDispo'.$i.'" size="10" value="'.$stock.'"></td>
                 </tr>';
				 $i++;
	}
	return $ret;
}
//--------- REGIONS -----------------------------

function lignConRegions($wh='', $ord='', $sens='ASC', $page=1,$nelt){
	$ret = '';
	$t = array();

	$table1 = "stocks_region";
	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");

	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY NOM_REGION ASC");
	$SQL = "SELECT * FROM $table1 $where $order;";
	$result = mysql_query($SQL);
	$t['NE'] = mysql_num_rows($result);

	$i = ($page-1)*$nelt;
	$SQL = "SELECT * FROM $table1 $where $order LIMIT $i, $nelt;";
	$result = mysql_query($SQL);
	if(mysql_num_rows($result)==0) $ret .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e</td></tr>';
	$i = 0;
	$j=6; //Index des cases à cocher
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");  // onClick="go('.$row['ID_REGION'].','.$j.');"
		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_REGION'].'"></td>
                    <td width="6%" height="22" class="text" align="center">'.$row['ID_REGION'].'</td>
                     <td width="80%" class="text" >'.(stripslashes($row['NOM_REGION'])).'</td>
                 </tr>';
		$i++;$j++;
	}
	$t['L']=$ret;
	//mysql_close);
	return $t;
}

function lignSearchRegions($cr1,$cr2,$page=1,$nelt){
	$ret = '';
	$t = array();
	$table1 = "stocks_region";
	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($cr1) and $cr1!='' ? $where .= " ID_REGION='$cr1' AND " : $where .= "");
	(isset($cr2) and $cr2!='' ? $where .= " NOM_REGION LIKE '%$cr2%' AND " : $where .= "");

	if($where != '') $where = substr(" WHERE $where",0,strlen(" WHERE $where")-4);

	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY NOM_REGION ASC");
	$SQL = "SELECT * FROM $table1 $where $order;";
	$result = mysql_query($SQL);
	$t['NE'] = mysql_num_rows($result);

	$i = ($page-1)*$nelt;
	$SQL = "SELECT * FROM $table1 $where $order LIMIT $i, $nelt;";
	$result = mysql_query($SQL);
	if(mysql_num_rows($result)==0) $ret .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e</td></tr>';
	$i = 0;
	$j=6; //Index des cases à cocher
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");// onClick="go('.$row['ID_REGION'].','.$j.');"
		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_REGION'].'"></td>
                    <td width="6%" height="22" class="text" align="center">'.$row['ID_REGION'].'</td>
                     <td width="80%" class="text" >'.(stripslashes( $row['NOM_REGION'])).'</td>
                 </tr>';
		$i++;$j++;
	}
	$t['L']=$ret;
	//mysql_close);
	return $t;
}
//--------- PROVINCES -----------------------------

function lignConProvinces($wh='', $ord='', $sens='ASC',$page=1, $nelt){
	$ret = '';
	$t= array();
	$table1 = "stocks_province";
	$table2 = "stocks_region";
	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");

	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY $table2.ID_REGION, $table1.ID_PROVINCE ASC");
	$SQL = "SELECT * FROM $table1 LEFT JOIN $table2 ON $table1.ID_REGION=$table2.ID_REGION $where $order;";
	$result = mysql_query($SQL);
	$t['NE'] = mysql_num_rows($result);

	$i = ($page-1)*$nelt;
	$SQL = "SELECT * FROM $table1 LEFT JOIN $table2 ON $table1.ID_REGION=$table2.ID_REGION $where $order LIMIT $i, $nelt;";
	$result = mysql_query($SQL);
	if(mysql_num_rows($result)==0) $ret .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e</td></tr>';
	$i = 0;
	$j=6;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_PROVINCE'].'" onClick="go('.$row['ID_PROVINCE'].','.$j.');"></td>
                    <td width="10%" height="22" class="text" align="center">'.$row['ID_PROVINCE'].'</td>
                    <td width="30%" class="text" >'.(stripslashes($row['NOM_PROVINCE'])).'</td>
					<td width="50%" class="text" >'.(stripslashes($row['NOM_REGION'])).'</td>
                 </tr>';
				 $i++;$j++;
	}
	$t['L']=$ret;
	//mysql_close);
	return $t;
}

function lignSearchProvinces($cr1, $cr2, $cr3,$page=1, $nelt){
	$ret = '';
	$t= array();
	$table1 = "stocks_province";
	$table2 = "stocks_region";
	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($cr1) and $cr1!='00' ? $where .= " $table1.ID_REGION='$cr1' AND " : $where .= "");
	(isset($cr2) and $cr2!='' ? $where .= " ID_PROVINCE='$cr2' AND " : $where .= "");
	(isset($cr3) and $cr3!='' ? $where .= " NOM_PROVINCE='$cr3' AND " : $where .= "");

	if($where != '') $where = substr(" WHERE $where",0,strlen(" WHERE $where")-4);

	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY $table2.ID_REGION, $table1.ID_PROVINCE ASC");
	$SQL = "SELECT * FROM $table1 LEFT JOIN $table2 ON $table1.ID_REGION=$table2.ID_REGION $where $order;";
	$result = mysql_query($SQL);
	$t['NE'] = mysql_num_rows($result);

	$i = ($page-1)*$nelt;
	$SQL = "SELECT * FROM $table1 LEFT JOIN $table2 ON $table1.ID_REGION=$table2.ID_REGION $where $order LIMIT $i, $nelt;";
	$result = mysql_query($SQL);
	$i = 0; $j=5;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_PROVINCE'].'" onClick="go('.$row['ID_PROVINCE'].','.$j.');"></td>
                    <td width="10%" height="22" class="text" align="center">'.$row['ID_PROVINCE'].'</td>
                    <td width="30%" class="text" >'.(stripslashes($row['NOM_PROVINCE'])).'</td>
					<td width="50%" class="text" >'.(stripslashes($row['NOM_REGION'])).'</td>
                 </tr>';
				 $i++;$j++;
	}
	$t['L']=$ret;
	//mysql_close);
	return $t;
}

//--------- Bénéficiaires -----------------------------

function lignConBeneficiaires($wh='', $ord='', $sens='ASC',$page=1,$nelt){
	$ret = '';
	$t = array();
	$table1 = "stocks_beneficiaire";
	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");

	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY $table1.CODE_BENEFICIAIRE ASC");
	$SQL = "SELECT * FROM $table1 $where $order;";
	$result = mysql_query($SQL);
	$t['NE'] = mysql_num_rows($result);

	$i = ($page-1)*$nelt;
	$SQL = "SELECT * FROM $table1 $where $order LIMIT $i, $nelt;";
	if(mysql_num_rows($result)==0) $ret .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e</td></tr>';
	$i = 0;
	$j=6;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['CODE_BENEFICIAIRE'].'" onClick="go('.$row['CODE_BENEFICIAIRE'].','.$j.');"></td>
                    <td width="6%" height="22" class="text" align="center">'.$row['CODE_BENEFICIAIRE'].'</td>
                    <td width="40%" class="text" >'.(stripslashes($row['LIBELLE_BENEFICIAIRE'])).'</td>
					<td width="40%" class="text" >'.(stripslashes($row['VILLE'])).'</td>
                 </tr>';
				 $i++;$j++;
	}
	$t['L']=$ret;
	//mysql_close);
	return $t;
}

function lignSearchBeneficiaires($cr1,$cr2,$cr3,$cr4,$cr5,$cr6,$cr7,$page=1,$nelt){
//lignConBeneficiaires($xnomBeneficiaire,$xcodeProvince,$xresponsable,$xadresse,$xtel,$xemail);

	$ret = '';
	$table1 = "stocks_beneficiaire";
	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($cr1) and $cr1!='' ? $where .= " LIBELLE_BENEFICIAIRE LIKE '%$cr1%' AND " : $where .= "");
	(isset($cr2) and $cr2!='00' ? $where .= " ID_PROVINCE='$cr2' AND " : $where .= "");
	(isset($cr3) and $cr3!='' ? $where .= " RESPONSABLE LIKE '%$cr3%' AND " : $where .= "");
	(isset($cr4) and $cr4!='' ? $where .= " VILLE LIKE '%$cr4%' AND " : $where .= "");
	(isset($cr5) and $cr5!='' ? $where .= " ADRESSE LIKE '%$cr5%' AND " : $where .= "");
	(isset($cr6) and $cr6!='' ? $where .= " TEL LIKE '%$cr6%' AND " : $where .= "");
	(isset($cr6) and $cr6!='' ? $where .= " EMAIL LIKE '%$cr7%' AND " : $where .= "");

	if($where != '') $where = substr(" WHERE $where",0,strlen(" WHERE $where")-4);

	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY $table1.CODE_BENEFICIAIRE ASC");
	$SQL = "SELECT * FROM $table1 $where $order;";
	$result = mysql_query($SQL);
	$t['NE'] = mysql_num_rows($result);

	$i = ($page-1)*$nelt;
	$SQL = "SELECT * FROM $table1 $where $order LIMIT $i, $nelt;";
	$result = mysql_query($SQL);
	if(mysql_num_rows($result)==0) $ret .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e</td></tr>';
	$i = 0;
	$j=6;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['CODE_BENEFICIAIRE'].'" onClick="go('.$row['CODE_BENEFICIAIRE'].','.$j.');"></td>
                    <td width="6%" height="22" class="text" align="center">'.$row['CODE_BENEFICIAIRE'].'</td>
                    <td width="40%" class="text" >'.(stripslashes($row['LIBELLE_BENEFICIAIRE'])).'</td>
					<td width="40%" class="text" >'.(stripslashes($row['VILLE'])).'</td>
                 </tr>';
				 $i++;$j++;
	}
	$t['L']=$ret;
	//mysql_close);
	return $t;
}

//--------- Exercices budgétaires -----------------------------

function lignConExercices($wh='', $ord='', $sens='ASC'){
	$ret = '';
	$table1 = "stocks_exercice";
	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");

	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY $table1.ID_EXERCICE ASC");
	$SQL = "SELECT * FROM $table1 $where $order;";
	$result = mysql_query($SQL);
	if(mysql_num_rows($result)==0) $ret .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e</td></tr>';
	$i = 0;
	$j=6;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		($row['STATUS']=='0' ? $status = 'Non cl&ocirc;turé' : $status ='Cl&ocirc;turé');
		($row['FIN']>= date('Y-m-d') ? $tps = '(jj-'.date_diff2(date('Y-m-d'),$row['FIN']).')' : $tps ='');
		($row['STATUS']=='0' ? $imgCl = '' : $imgCl ='<img src="../images/fermer.png" width="16" height="16">');
		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_EXERCICE'].'" onClick="go('.$row['ID_EXERCICE'].','.$j.');"></td>
                    <td width="3%" align="center">'.$imgCl.'</</td>
                    <td width="6%" height="22" class="text" align="center">'.$row['ID_EXERCICE'].'</td>
                    <td width="30%" class="text" >'.(stripslashes($row['LIBELLE_EXERCICE'])).'</td>
					<td width="20%" class="text" align="center">'.frFormat($row['DEBUT']).'</td>
					<td width="20%" class="text" align="center">'.frFormat($row['FIN']).'</td>
					<td width="20%" class="text" align="center">'.$status.' <font color="#066">'.$tps.'</font></td>
                 </tr>';
				 $i++; $j++;
	}
	//mysql_close);
	return $ret;
}

function lignClotExercices($wh='', $ord='', $sens='ASC'){
	$ret = '';
	$table1 = "stocks_exercice";
	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");

	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY $table1.ID_EXERCICE ASC");
	$SQL = "SELECT * FROM $table1 $where $order;";
	$result = mysql_query($SQL);
	if(mysql_num_rows($result)==0) $ret .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e</td></tr>';
	$i = 0;
	$j=6;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		($row['STATUS']=='0' ? $status = 'Non cl&ocirc;turé' : $status ='Cl&ocirc;turé');
		($row['FIN']>= date('Y-m-d') ? $tps = '(jj-'.date_diff2(date('Y-m-d'),$row['FIN']).')' : $tps ='');
		($row['STATUS']=='0' ? $imgCl = '' : $imgCl ='<img src="../images/fermer.png" width="16" height="16">');
		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_EXERCICE'].'" onClick="go('.$row['ID_EXERCICE'].','.$j.');"></td>
                    <td width="3%" align="center">'.$imgCl.'</</td>
                    <td width="6%" height="22" class="text" align="center">'.$row['ID_EXERCICE'].'</td>
                    <td width="30%" class="text" >'.(stripslashes($row['LIBELLE_EXERCICE'])).'</td>
					<td width="15%" class="text" align="center">'.frFormat($row['DEBUT']).'</td>
					<td width="15%" class="text" align="center">'.frFormat($row['FIN']).'</td>
					<td width="15%" class="text" align="center">'.$status.' <font color="#066">'.$tps.'</font></td>
					<!-- <td width="12%" class="text" align="center"><input name="detail" type="button" class="button" id="detail"  value="Cl&ecirc;turer &gt;&gt;" onClick="javascript:window.location.href=\'cloture2.php?selectedTab=parameters&id='.$row['ID_EXERCICE'].'\';"></td> -->
                 </tr>';
				 $i++; $j++;
	}
	//mysql_close);
	return $ret;
}

function lignSearchExercices($cr1,$cr2,$cr3,$cr4){
	$ret = '';
	$table1 = "stocks_exercice";
	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($cr1) and $cr1!='' ? $where .= " ID_EXERCICE = '$cr1' AND " : $where .= "");
	(isset($cr2) and $cr2!='' ? $where .= " LIBELLE_EXERCICE LIKE '%$cr2%' AND " : $where .= "");
	(isset($cr3) and $cr3!='' ? $where .= " DEBUT = '".mysqlFormat($cr3)."' AND " : $where .= "");
	(isset($cr4) and $cr4!='' ? $where .= " FIN = '".mysqlFormat($cr4)."' AND " : $where .= "");

	if($where != '') $where = substr(" WHERE $where",0,strlen(" WHERE $where")-4);

	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY $table1.ID_EXERCICE ASC");
	$SQL = "SELECT * FROM $table1 $where $order;";
	$result = mysql_query($SQL);
	$i = 0; $j=5;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		($row['STATUS']=='0' ? $status = 'Non cl&ocirc;turé' : $status ='Cl&ocirc;turé');
		($row['FIN']>= date('Y-m-d') ? $tps = '(jj-'.date_diff2(date('Y-m-d'),$row['FIN']).')' : $tps ='');
		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_EXERCICE'].'" onClick="go('.$row['ID_EXERCICE'].','.$j.');"></td>
                    <td width="6%" height="22" class="text" align="center">'.$row['ID_EXERCICE'].'</td>
                    <td width="30%" class="text" >'.(stripslashes($row['LIBELLE_EXERCICE'])).'</td>
					<td width="20%" class="text" align="center">'.frFormat($row['DEBUT']).'</td>
					<td width="20%" class="text" align="center">'.frFormat($row['FIN']).'</td>
					<td width="20%" class="text" align="center">'.$status.' <font color="#066">'.$tps.'</font></td>
                 </tr>';
				 $i++; $j++;
	}
	//mysql_close);
	return $ret;
}

//--------- CATEGORIES -----------------------------

function lignConCategories($wh='', $ord='', $sens='ASC',$page=1,$nelt){
	$ret = '';
	$t = array();
	$table1 = "stocks_categorie";
	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");

	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY LIBELLE_CATEGORIE ASC");
	$SQL = "SELECT * FROM $table1 $where $order;";
	$result = mysql_query($SQL);
	$t['NE'] = mysql_num_rows($result);

	$i = ($page-1)*$nelt;
	$SQL = "SELECT * FROM $table1 $where $order LIMIT $i, $nelt;";
	$result = mysql_query($SQL);
	if(mysql_num_rows($result)==0) $ret .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e</td></tr>';
	$i = 0;
	$j=6;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_CATEGORIE'].'" onClick="go(\''.$row['ID_CATEGORIE'].'\','.$j.');"></td>
                    <td width="6%" height="22" class="text" align="center">'.$row['ID_CATEGORIE'].'</td>
                    <td width="80%" class="text" >'.(stripslashes($row['LIBELLE_CATEGORIE'])).'</td>
                 </tr>';
				 $i++;$j++;
	}
	$t['L']=$ret;
	//mysql_close);
	return $t;
}


function lignSearchCategories($cr1,$cr2,$page=1,$nelt){
	$ret = '';
	$t = array();
	$table1 = "stocks_categorie";
	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($cr1) and $cr1!='' ? $where .= " ID_CATEGORIE = '$cr1' AND " : $where .= "");
	(isset($cr2) and $cr2!='' ? $where .= " LIBELLE_CATEGORIE LIKE '%$cr2%' AND " : $where .= "");

	if($where != '') $where = substr(" WHERE $where",0,strlen(" WHERE $where")-4);

	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY LIBELLE_CATEGORIE ASC");
	$SQL = "SELECT * FROM $table1 $where $order;";
	$result = mysql_query($SQL);
	$t['NE'] = mysql_num_rows($result);

	$i = ($page-1)*$nelt;
	$SQL = "SELECT * FROM $table1 $where $order LIMIT $i, $nelt;";
	$result = mysql_query($SQL);
	$i = 0;
	$j=6;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_CATEGORIE'].'" onClick="go(\''.$row['ID_CATEGORIE'].'\','.$j.');"></td>
                    <td width="6%" height="22" class="text" align="center">'.$row['ID_CATEGORIE'].'</td>
                     <td width="80%" class="text" >'.(stripslashes($row['LIBELLE_CATEGORIE'])).'</td>
                 </tr>';
				 $i++;$j++;
	}
	$t['L']=$ret;
	//mysql_close);
	return $t;
}




//--------- ARTICLES -----------------------------

function nomUnite($id){
	$table1 = "stocks_unite";
	//Save data
	$SQL1 ="SELECT LIBELLE_UNITE FROM $table1 WHERE ID_UNITE=$id;";

		//Connection to Database server
		mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

		//Select Database
		mysql_select_db(DB) or header('location:errorPage.php&code=');
		$result = mysql_query($SQL1) or header('location:errorPage.php&code=');
		$row = mysql_fetch_array($result);
		return $row[0];
}

function lignConArticles($wh='', $ord='', $sens='ASC',$page=1,$nelt){
	$ret = '';
	$t = array();
	$table1 = "stocks_article";
	$table2 = "stocks_categorie";
	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	//(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");

	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY LIBELLE_ARTICLE ASC");
	$SQL = "SELECT * FROM $table1, $table2 WHERE $table1.ID_CATEGORIE=$table2.ID_CATEGORIE $order;";
	$result = mysql_query($SQL);
	$t['NE'] = mysql_num_rows($result);

	$i = ($page-1)*$nelt;
	$SQL = "SELECT * FROM $table1, $table2 WHERE $table1.ID_CATEGORIE=$table2.ID_CATEGORIE $order LIMIT $i, $nelt;";
	$result = mysql_query($SQL);
	if(mysql_num_rows($result)==0) $ret .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e</td></tr>';
	$i = 0;
	$j=6;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_ARTICLE'].'" onClick="go(\''.$row['ID_ARTICLE'].'\','.$j.');"></td>
                    <td width="5%" height="22" class="text" align="center">'.$row['ID_ARTICLE'].'</td>
					<td width="15%" height="22" class="text" align="left">'.(stripslashes( $row['LIBELLE_ARTICLE'])).'</td>
					<td width="8%" height="22" class="text" align="right" nowrap>'.number_format($row['PRIX_UNITAIRE'],0,',',' ').'</td>
					<td width="5%" height="22" class="text" align="right">'.$row['SEUIL_APPRO'].'</td>
                    <td width="5%" class="text" align="right">'.number_format($row['POIDS'],2,',',' ').'</td>
					<td width="10%" class="text" align="left">'.(stripslashes(nomUnite($row['ID_UNITE']))).'</td>
					<td width="20%" height="22" class="text" align="left">'.(stripslashes($row['DESCRIPTION'])).'</td>
					<td width="15%" height="22" class="text" align="left">'.(stripslashes($row['LIBELLE_CATEGORIE'])).'</td>
                 </tr>';
				 $i++;$j++;
	}
	$t['L']=$ret;
	//mysql_close);
	return $t;
}

function lignSearchArticles($cr1, $cr2, $cr3, $cr4, $cr5, $cr6, $cr7,$page=1,$nelt){
	//$xcodeCategorie,$xcodeArticle,$xnomArticle,$xdescription,$xprixUnitaire,$xseuil,$xpoids
	$ret = '';
	$t = array();
	$table1 = "stocks_article";
	$table2 = "stocks_categorie";
	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($cr1) and $cr1!='00' ? $where .= " $table1.ID_CATEGORIE='$cr1' AND " : $where .= "");
	(isset($cr2) and $cr2!='' ? $where .= " ID_ARTICLE='$cr2' AND " : $where .= "");
	(isset($cr3) and $cr3!='' ? $where .= " LIBELLE_ARTICLE LIKE '%$cr3%' AND " : $where .= "");
	(isset($cr3) and $cr3!='' ? $where .= " DESCRIPTION='$cr4' AND " : $where .= "");
	(isset($cr3) and $cr3!='' ? $where .= " PRIX_UNITAIRE='$cr5' AND " : $where .= "");
	(isset($cr3) and $cr3!='' ? $where .= " SEUIL_APPRO='$cr6' AND " : $where .= "");
	(isset($cr3) and $cr3!='' ? $where .= " POIDS='$cr7' AND " : $where .= "");

	if($where != '') $where = substr(" AND $where",0,strlen(" AND $where")-4);

	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY LIBELLE_ARTICLE ASC");
	$SQL = "SELECT * FROM $table1, $table2 WHERE $table1.ID_CATEGORIE=$table2.ID_CATEGORIE $where $order;";
	$result = mysql_query($SQL);
	$t['NE'] = mysql_num_rows($result);

	$i = ($page-1)*$nelt;
	$SQL = "SELECT * FROM $table1, $table2 WHERE $table1.ID_CATEGORIE=$table2.ID_CATEGORIE $where $order LIMIT $i, $nelt;";
	$result = mysql_query($SQL);
	$i = 0;$j=6;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_ARTICLE'].'" onClick="go(\''.$row['ID_ARTICLE'].'\','.$j.');"></td>
                    <td width="10%" height="22" class="text" align="center">'.$row['ID_ARTICLE'].'</td>
					<td width="15%" height="22" class="text" align="left">'.(stripslashes($row['LIBELLE_ARTICLE'])).'</td>
					<td width="8%" height="22" class="text" align="right" nowrap>'.number_format($row['PRIX_UNITAIRE'],0,',',' ').'</td>
					<td width="8%" height="22" class="text" align="right">'.$row['SEUIL_APPRO'].'</td>
                    <td width="8%" class="text" align="right">'.number_format($row['POIDS'],2,',',' ').'</td>
					<td width="10%" class="text" align="left">'.(stripslashes(nomUnite($row['ID_UNITE']))).'</td>
					<td width="15%" height="22" class="text" align="left">'.(stripslashes($row['DESCRIPTION'])).'</td>
					<td width="15%" height="22" class="text" align="left">'.(stripslashes($row['LIBELLE_CATEGORIE'])).'</td>
                 </tr>';
				 $i++;$j++;
	}
	$t['L']=$ret;
	//mysql_close);
	return $t;
}
//--------- PERSONNES -----------------------------

//Fill the consultation array() of personnes
function setConsPersonnes($id){
	$table1 = "stocks_personnel";
	$table2 = "stocks_compte";
	$table3 = "stocks_groupe";

	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($id) ? $where = " WHERE NUM_MATRICULE LIKE '$id' " : $where = "");
	$SQL = "SELECT $table1.* FROM $table1 $where;";
	$result = mysql_query($SQL);
	$row = mysql_fetch_array($result);

	//Fill session vars
	$_SESSION['CONS_PERS']= array(
	'numMatricule' 	=>$row['NUM_MATRICULE'],
	'nomPrenoms' 	=>$row['NOM_PRENOMS'],
	'fonction' 		=>$row['FONCTION'],
	'service' 		=>$row['SERVICE'],
	'adresse'	 	=>$row['ADRESSE'],
	'ville' 		=>$row['VILLE'],
	'telephone' 	=>$row['TEL'],
	'email' 		=>$row['EMAIL']
	);
	//SQL
	$where ='';
	(isset($id) ? $where = " WHERE $table2.NUM_MATRICULE LIKE '$id' AND $table2.ID_GROUPE=$table3.ID_GROUPE" : $where = "");
	$SQL = "SELECT $table2.*, $table3.NOM_GROUPE FROM $table2,$table3 $where ORDER BY LOGIN ASC;";
	$result = mysql_query($SQL);

	//Fill session vars
	$_SESSION['CONS_PERS']['compte'] =array();
	while($row = mysql_fetch_array($result)){
		array_push($_SESSION['CONS_PERS']['compte'], array('login'=>$row['LOGIN'],'groupe'=>$row['ID_GROUPE'].' - '.$row['NOM_GROUPE']));
	}
}

//Deplay consultation ligne
function lignDetPersonnes($arr){
	$ret = '';
	foreach ($arr as $key=>$row){
		$ret .='<tr align="left" valign="top">
                  <td width="200" align=right valign="middle" class="text">Type de compte (Groupe)&nbsp;:&nbsp;</td>
                  <td align="left" class="text"><div class="ligneAll" nowrap>'.$row['groupe'].'</div></td>
                </tr>
                <tr align="left" valign="top">
                  <td width="200" align=right valign="middle" class="text">Nom d\'utilisateur&nbsp;:&nbsp;</td>
                  <td align="left" class="text"><div class="ligneAll" nowrap>'.$row['login'].'</div></td>
                </tr>
                <tr align="left" valign="top">
                  <td width="200" align=right valign="middle" class="text">Mot de passe&nbsp;:&nbsp;</td>
                  <td class="text"><div class="ligneAll" nowrap>*********</div></td>
                </tr>
                <tr align="left" valign="top">
                  <td width="200" align=right valign="middle" class="text">Confirmer&nbsp;:&nbsp;</td>
                  <td class="text"><div class="ligneAll" nowrap>********</div></td>
               </tr>
			   <tr align="left" valign="top">
                  <td class="text" colspan="2">&nbsp;</td>
                </tr>
			';
	}
	return $ret;
}

function lignConPersonnes($wh='', $ord='', $sens='ASC',$page=1,$nelt){
	$ret = '';
	$t = array();
	$table1 = "stocks_personnel";
	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");

	$order ='';
	(isset($ord) and $wh!=''  ? $order = " ORDER BY $ord $sens" : $order = "");
	$SQL = "SELECT * FROM $table1 $where $order;";
	$result = mysql_query($SQL);
	$t['NE'] = mysql_num_rows($result);

	$i = ($page-1)*$nelt;
	$SQL = "SELECT * FROM $table1 $where $order LIMIT $i, $nelt;";
	$result = mysql_query($SQL);
	if(mysql_num_rows($result)==0) $ret .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e</td></tr>';
	$i = 0;
	$j=6;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['NUM_MATRICULE'].'" onClick="go(\''.$row['NUM_MATRICULE'].'\','.$j.');"></td>
                    <td width="10%" height="22" class="text" align="center">'.$row['NUM_MATRICULE'].'</td>
                    <td width="20%" height="22" class="text" align="left">'.(stripslashes($row['NOM_PRENOMS'])).'</td>
                    <td width="15%" class="text" align="left">'.(stripslashes($row['FONCTION'])).'</td>
					<td width="15%" height="22" class="text" align="left">'.(stripslashes($row['SERVICE'])).'</td>
					<td width="15%" height="22" class="text" align="left">'.$row['TEL'].'</td>
					<td width="15%" height="22" class="text" align="left">'.$row['EMAIL'].'</td>
                    <td width="15%" class="text" align="center"><input name="detail" type="button" class="button" id="detail"  value="D&eacute;tails &gt;&gt;" onClick="javascript:window.location.href=\'personnes2.php?selectedTab=parameters&displayName=parameters&id='.$row['NUM_MATRICULE'].'\';"></td>
                 </tr>';
				 $i++;$j++;
	}
	$t['L']=$ret;
	//mysql_close);
	return $t;
}

function lignSearchPersonnes($cr1,$cr2,$cr3,$cr4,$cr5,$cr6,$cr7,$cr8,$page=1,$nelt){
	//($xnumMatricule,$xnomPrenoms,$xfonction,$xservice,$xadresse,$xville,$xtelephone,$xemail);
	$ret = '';
	$t = array();
	$table1 = "stocks_personnel";
	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($cr1) and $cr1!='' ? $where .= " NUM_MATRICULE LIKE '$cr1' AND " : $where .= "");
	(isset($cr2) and $cr2!='' ? $where .= " NOM_PRENOMS LIKE '%$cr2%' AND " : $where .= "");
	(isset($cr3) and $cr3!='' ? $where .= " FONCTION LIKE '%$cr3%' AND " : $where .= "");
	(isset($cr4) and $cr4!='' ? $where .= " SERVICE LIKE '%$cr4%' AND " : $where .= "");
	(isset($cr5) and $cr5!='' ? $where .= " ADRESSE LIKE '%$cr5%' AND " : $where .= "");
	(isset($cr6) and $cr6!='' ? $where .= " VILLE LIKE '%$cr6%' AND " : $where .= "");
	(isset($cr7) and $cr7!='' ? $where .= " TEL LIKE '%$cr7%' AND " : $where .= "");
	(isset($cr8) and $cr8!='' ? $where .= " EMAIL LIKE '%$cr8%' AND " : $where .= "");

	if($where != '') $where = substr(" WHERE $where",0,strlen(" WHERE $where")-4);


	$order ='';
	(isset($ord) and $wh!=''  ? $order = " ORDER BY $ord $sens" : $order = "");
	$SQL = "SELECT * FROM $table1 $where $order;";
	$result = mysql_query($SQL);
	$t['NE'] = mysql_num_rows($result);

	$i = ($page-1)*$nelt;
	$SQL = "SELECT * FROM $table1 $where $order LIMIT $i, $nelt;";
	$result = mysql_query($SQL);
	$i = 0;
	$j=6;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['NUM_MATRICULE'].'" onClick="go(\''.$row['NUM_MATRICULE'].'\','.$j.');"></td>
                    <td width="10%" height="22" class="text" align="center">'.$row['NUM_MATRICULE'].'</td>
                    <td width="20%" height="22" class="text" align="left">'.(stripslashes($row['NOM_PRENOMS'])).'</td>
                    <td width="15%" class="text" align="left">'.(stripslashes($row['FONCTION'])).'</td>
					<td width="15%" height="22" class="text" align="left">'.(stripslashes($row['SERVICE'])).'</td>
					<td width="15%" height="22" class="text" align="left">'.$row['TEL'].'</td>
					<td width="15%" height="22" class="text" align="left">'.$row['EMAIL'].'</td>
                    <td width="15%" class="text" align="center"><input name="detail" type="button" class="button" id="detail"  value="D&eacute;tails &gt;&gt;" onClick="javascript:window.location.href=\'personnes2.php?selectedTab=parameters&id='.$row['NUM_MATRICULE'].'\';"></td>
                 </tr>';
				 $i++;$j++;
	}
	$t['L']=$ret;
	//mysql_close);
	return $t;
}

function lignConComptes($wh='', $ord='', $sens='ASC', $page=1, $nelt){
	$ret = '';
	$t = array();
	$table1 = "stocks_compte";
	$table2 = "stocks_groupe";
	$table3 = "stocks_personnel";

	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($wh) and $wh!='' ? $where = " AND $wh " : $where = "");

	if($where != '') $where = substr(" WHERE $where",0,strlen(" WHERE $where")-4);

	$order ='';
	(isset($ord) and $wh!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY LOGIN ASC ");
	$SQL = "SELECT $table1.*, $table2.NOM_GROUPE, $table3.NOM_PRENOMS FROM $table1, $table2, $table3
	WHERE $table1.ID_GROUPE=$table2.ID_GROUPE AND $table1.NUM_MATRICULE=$table3.NUM_MATRICULE $where $order;";
	$result = mysql_query($SQL);
	$t['NE'] = mysql_num_rows($result);

	$i = ($page-1)*$nelt;
	$SQL = "SELECT $table1.*, $table2.NOM_GROUPE, $table3.NOM_PRENOMS FROM $table1, $table2, $table3
	WHERE $table1.ID_GROUPE=$table2.ID_GROUPE AND $table1.NUM_MATRICULE=$table3.NUM_MATRICULE $where $order LIMIT $i, $nelt;";
	$result = mysql_query($SQL);
	if(mysql_num_rows($result)==0) $ret .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e</td></tr>';
	$i = 0;
	$j=6;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		($row['STATUS']== 1 ? $status='Activ&eacute;' : $status='D&eacute;sactiv&eacute;');
		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['LOGIN'].'" onClick="go(\''.$row['LOGIN'].'\','.$j.');"></td>
                    <td width="15%" height="22" class="text" align="left">'.$row['LOGIN'].'</td>
                    <td width="15%" class="text" align="left">'.$row['NUM_MATRICULE'].'</td>
					<td width="15%" height="22" class="text" align="left">'.(stripcslashes($row['NOM_PRENOMS'])).'</td>
					<td width="15%" height="22" class="text" align="left">'.$status.'</td>
					<td width="15%" height="22" class="text" align="left">'.$row['NOM_GROUPE'].'</td>
                 </tr>';
				 $i++;$j++;
	}
	$t['L']=$ret;
	//mysql_close);
	return $t;
}

function lignSearchComptes($cr1,$cr2,$cr3,$page=1,$nelt){
	//($xnumMatricule,$xgroupe,$xnomUtilisateur);
	$ret = '';
	$t = array();
	$t = array();
	$table1 = "stocks_compte";
	$table2 = "stocks_groupe";
	$table3 = "stocks_personnel";

	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($cr1) and $cr1!='00' ? $where .= " $table1.NUM_MATRICULE LIKE '$cr1' AND " : $where .= "");
	(isset($cr2) and $cr2!='00' ? $where .= " $table1.ID_GROUPE = '$cr2' AND " : $where .= "");
	(isset($cr3) and $cr3!='' ? $where .= " LOGIN LIKE '$cr3' AND " : $where .= "");

	if($where != '') $where = substr(" AND $where",0,strlen(" AND $where")-4);

	$order ='';
	(isset($ord) and $wh!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY LOGIN ASC ");
	$SQL = "SELECT $table1.*, $table2.NOM_GROUPE, $table3.NOM_PRENOMS FROM $table1, $table2, $table3
	WHERE $table1.ID_GROUPE=$table2.ID_GROUPE AND $table1.NUM_MATRICULE=$table3.NUM_MATRICULE $where $order;";
	$result = mysql_query($SQL);
	$t['NE'] = mysql_num_rows($result);

	$i = ($page-1)*$nelt;
	$SQL = "SELECT $table1.*, $table2.NOM_GROUPE, $table3.NOM_PRENOMS FROM $table1, $table2, $table3
	WHERE $table1.ID_GROUPE=$table2.ID_GROUPE AND $table1.NUM_MATRICULE=$table3.NUM_MATRICULE $where $order LIMIT $i, $nelt;";
	$result = mysql_query($SQL);
	$i = 0;
	$j=6;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		($row['STATUS']== 1 ? $status='Activ&eacute;' : $status='D&eacute;sactiv&eacute;');
		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['LOGIN'].'" onClick="go(\''.$row['LOGIN'].'\','.$j.');"></td>
                    <td width="15%" height="22" class="text" align="left">'.$row['LOGIN'].'</td>
                    <td width="15%" class="text" align="left">'.$row['NUM_MATRICULE'].'</td>
					<td width="15%" height="22" class="text" align="left">'.(stripslashes($row['NOM_PRENOMS'])).'</td>
					<td width="15%" height="22" class="text" align="left">'.$status.'</td>
					<td width="15%" height="22" class="text" align="left">'.$row['NOM_GROUPE'].'</td>
                 </tr>';
				 $i++;$j++;
	}
	$t['L']=$ret;
	//mysql_close);
	return $t;
}

//--------- GROUPES -----------------------------

//Fill the consultation array() of personnes
function setConsGroupes($id){
	$table1 = "stocks_groupe";

	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($id) ? $where = " WHERE ID_GROUPE = '$id' " : $where = "");
	$SQL = "SELECT $table1.* FROM $table1 $where";
	$result = mysql_query($SQL);
	$row = mysql_fetch_array($result);

	//Fill session vars
	$_SESSION['CONS_GRPE']= array(
	'codeGroupe' 	=>$row['ID_GROUPE'],
	'nomGroupe' 	=>$row['NOM_GROUPE'],
	'statusGroupe' 	=>$row['GRPE_STATUS'],
	);

	$B = preg_split('/ /',$row['BESOINS']);
	$AO = preg_split('/ /',$row['APPELOFFRE']);
	$BE= preg_split('/ /',$row['BONENTREE']);
	$BS = preg_split('/ /',$row['BONSORTIE']);
	$P = preg_split('/ /',$row['PARAMETRE']);
	$I = preg_split('/ /',$row['INVENTAIRE']);

	//Fill session vars
	$_SESSION['CONS_GRPE']['droit'] =array(
				'B' =>array($B[0],$B[1],$B[2],$B[3]),
				'AO'=>array($AO[0],$AO[1],$AO[2],$AO[3]),
				'BE'=>array($BE[0],$BE[1],$BE[2],$BE[3]),
				'BS'=>array($BS[0],$BS[1],$BS[2],$BS[3]),
				'P'=>array($P[0],$P[1],$P[2]),
				'I'=>array($I[0],$I[1],$I[2])
				);
}

function lignConGroupe($wh='', $ord='', $sens='ASC',$page=1,$nelt){
	$ret = '';
	$t = array();
	$table1 = "stocks_groupe";

	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");

	$order ='';
	(isset($ord) and $wh!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY ID_GROUPE ASC ");
	$SQL = "SELECT $table1.* FROM $table1 $where $order;";
	$result = mysql_query($SQL);
	$t['NE'] = mysql_num_rows($result);

	$i = ($page-1)*$nelt;
	$SQL = "SELECT $table1.* FROM $table1 $where $order LIMIT $i, $nelt;";
	$result = mysql_query($SQL);
	if(mysql_num_rows($result)==0) $ret .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e</td></tr>';
	$i = 0;
	$j=6;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		($row['GRPE_STATUS']== 1 ? $status='Activ&eacute;' : $status='D&eacute;sactiv&eacute;');
		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_GROUPE'].'" onClick="go('.$row['ID_GROUPE'].','.$j.');"></td>
                    <td width="10%" height="22" class="text" align="center">'.$row['ID_GROUPE'].'</td>
                    <td width="10%" class="text" align="left">'.$status.'</td>
					<td width="65%" height="22" class="text" align="left">'.(stripslashes($row['NOM_GROUPE'])).'</td>
					<td width="10%" class="text" align="left"><input name="detail" type="button" class="button" id="detail"  value="D&eacute;tails &gt;&gt;" onClick="javascript:window.location.href=\'groups2.php?selectedTab=parameters&id='.$row['ID_GROUPE'].'\';"></td>
                 </tr>';
				 $i++;
				 $j++;
	}
	$t['L']=$ret;
	//mysql_close);
	return $t;
}

function lignSearchGroupe($cr1,$cr2,$page=1,$nelt){
	$ret = '';
	$t = array();
	$table1 = "stocks_groupe";

	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($cr1) and $cr1!='' ? $where .= " $table1.ID_GROUPE = '$cr1' AND " : $where .= "");
	(isset($cr2) and $cr2!='' ? $where .= " $table1.NOM_GROUPE = '%$cr2%' AND " : $where .= "");

	if($where != '') $where = substr(" WHERE $where",0,strlen(" WHERE $where")-4);

	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY ID_GROUPE ASC ");
	$SQL = "SELECT $table1.* FROM $table1 $where $order;";
	$result = mysql_query($SQL);
	$t['NE'] = mysql_num_rows($result);

	$i = ($page-1)*$nelt;
	$SQL = "SELECT $table1.* FROM $table1 $where $order LIMIT $i, $nelt;";
	$result = mysql_query($SQL);
	$i = 0;
	$j=6;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		($row['GRPE_STATUS']== 1 ? $status='Activ&eacute;' : $status='D&eacute;sactiv&eacute;');
		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_GROUPE'].'" onClick="go('.$row['ID_GROUPE'].','.$j.');"></td>
                    <td width="10%" height="22" class="text" align="center">'.$row['ID_GROUPE'].'</td>
                    <td width="10%" class="text" align="left">'.$status.'</td>
					<td width="65%" height="22" class="text" align="left">'.(stripslashes($row['NOM_GROUPE'])).'</td>
					<td width="10%" class="text" align="left"><input name="detail" type="button" class="button" id="detail"  value="D&eacute;tails &gt;&gt;" onClick="javascript:window.location.href=\'groups2.php?selectedTab=parameters&id='.$row['ID_GROUPE'].'\';"></td>
                 </tr>';
				 $i++;
				 $j++;
	}
	$t['L']=$ret;
	//mysql_close);
	return $t;
}

//--------- LOGS -----------------------------

function nomUser($id){
	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	$table = "stocks_personnel";
	//SQL
	$SQL = "SELECT NOM_PRENOMS FROM $table WHERE NUM_MATRICULE LIKE '$id'";
	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');
	$result = mysql_query($SQL) or header('location:errorPage.php&code=');
	$row = mysql_fetch_array($result);
	return $row['NOM_PRENOMS'];
}

function numMatricule($id){
	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	$table = "stocks_compte";
	//SQL
	$SQL = "SELECT NUM_MATRICULE FROM $table WHERE LOGIN LIKE '$id'";
	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');
	$result = mysql_query($SQL) or header('location:errorPage.php&code=');
	$row = mysql_fetch_array($result);
	return $row['NUM_MATRICULE'];
}

function lignConLog($wh='', $ord='', $sens='ASC', $page=1, $nelt){
	$ret = '';
	$t = array();
	$table1 = "stocks_logs";

	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");

	$order ='';
	(isset($ord) and $wh!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY DATE_LOG DESC ");
	$SQL = "SELECT $table1.* FROM $table1 $where $order;";
	$result = mysql_query($SQL);
	$t['NE'] = mysql_num_rows($result);

	$i = ($page-1)*$nelt;
	$SQL = "SELECT $table1.* FROM $table1 $where $order LIMIT $i, $nelt;";
	$result = mysql_query($SQL);

	if(mysql_num_rows($result)==0) $ret .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e</td></tr>';
	$i = 0;
	$j=4;

	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		$matricule = numMatricule($row['LOGIN']);
		$d = preg_split('/ /',$row['DATE_LOG']);
		$date = frFormat($d[0]).' '.$d[1];
		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_LOG'].'" onClick="go('.$row['ID_LOG'].','.$j.');"></td>
                    <td width="5%" height="22" class="text" align="center">'.(($i+1)+($page-1)*$nelt).'</td>
					<td width="20%" class="text" align="left" nowrap>'.$row['LOGIN'].': '.(stripslashes(nomUser($matricule))).'</td>
                    <td width="15%" class="text" align="center">'.$date.'</td>
					<td width="60%" height="22" class="text" align="left">'.(stripslashes($row['DESCRIPTION'])).'</td>
                 </tr>';
				 $i++;
				 $j++;
	}
	$t['L']=$ret;
	//mysql_close);
	return $t;
}

function lignSearchLog($cr1,$cr2,$cr3,$page=1, $nelt){
	$ret = '';
	$t = array();
	$table1 = "stocks_logs";

	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($cr1) and $cr1!='' ? $where .= " $table1.LOGIN LIKE '$cr1' AND " : $where .= "");
	if (isset($cr2) && $cr2 !='' && isset($cr2) && $cr3 !='') $where .= " ($table1.DATE_LOG >='".mysqlFormat($cr2)."' AND $table1.DATE_LOG <='".mysqlFormat($cr3)."') OR "; //Date fin
	if (isset($cr2) && $cr2 !='' && $cr3 =='') $where .= " $table1.DATE_LOG ='".mysqlFormat($cr2)."' OR "; //Date début
	if (isset($cr3) && $cr3 !='' && $cr2 =='') $where .= " $table1.DATE_LOG ='".mysqlFormat($cr3)."' OR "; //Date fin

	if($where != '') $where = substr(" WHERE $where",0,strlen(" WHERE $where")-4);

	$order ='';
	(isset($ord) and $wh!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY DATE_LOG ASC ");
	$SQL = "SELECT $table1.* FROM $table1 $where $order;";
	$result = mysql_query($SQL);
	$t['NE'] = mysql_num_rows($result);

	$i = ($page-1)*$nelt;
	$SQL = "SELECT $table1.* FROM $table1 $where $order LIMIT $i, $nelt;";
	$result = mysql_query($SQL);
	$i = 0;
	$j=4;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		$matricule = numMatricule($row['LOGIN']);
		$d = preg_split('/[-\.\/ ]/',$row['DATE_LOG']);
		$date = frFormat($d[0]).' '.$d[1];
		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_LOG'].'" onClick="go('.$row['ID_LOG'].','.$j.');"></td>
                    <td width="5%" height="22" class="text" align="center">'.(($i+1)+($page-1)*$nelt).'</td>
					<td width="20%" class="text" align="left" nowrap>'.$row['LOGIN'].': '.(stripslashes(nomUser($matricule))).'</td>
                    <td width="15%" class="text" align="center">'.$date.'</td>
					<td width="60%" height="22" class="text" align="left">'.(stripslashes($row['DESCRIPTION'])).'</td>
                 </tr>';
				 $i++;
				 $j++;
	}
	$t['L']=$ret;
	//mysql_close);
	return $t;
}


//================ PHP TO EXCEL ================================//

function xlsBOF() {
	echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
	return;
}
function xlsEOF() {
	echo pack("ss", 0x0A, 0x00);
	return;
}
function xlsWriteNumber($Row, $Col, $Value) {
	echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
	echo pack("d", $Value);
	return;
}
function xlsWriteLabel($Row, $Col, $Value ) {
	$L = strlen($Value);
	echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
	echo $Value;
	return;
}



?>
