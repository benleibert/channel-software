<?php
//MySQL Parameters
require_once('../lib/global.inc');

//Application
define('TITLE', 'Cantine Pro v1.0');
define('DEFAULTVIEWLENGTH', 20);

//------------------------------------ Stanadrd functions -----------------------------------
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
		if($str != '0000-00-00') {$ret = $split[2].'-'.$split[1].'-'.$split[0];}
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
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query
	$row = $query->fetch(PDO::FETCH_ASSOC);
	return $row['NBRE'];
}



//QTE
function stockQte($exercice, $nature, $prd, $date, $valid){
	//ID_MOUVEMENT 	ID_CONDIT 	ID_EXERCICE 	CODE_MAGASIN 	ID_SOURCE 	MVT_DATE 	MVT_QUANTITE 	MVT_UNITE 	MVT_NATURE 	MVT_VALID
	$where ="";
	(isset($exercice) && $exercice!='' 	? 	$where .="mouvement.ID_EXERCICE = '".addslashes($exercice)."' AND " 	: $where .="");
	(isset($nature) && $nature!='' 		? 	$where .="mouvement.MVT_NATURE LIKE '".addslashes($nature)."' AND " 	: $where .="");
	(isset($prd) && $prd!='' 			? 	$where .="mouvement.ID_CONDIT = '".addslashes($prd)."' AND " 			: $where .="");
	(isset($date) && $date!='' 			? 	$where .="mouvement.ID_CONDIT = '".addslashes(mysqlFormat($date))."' AND " 	: $where .="");
	(isset($valid) && $valid!='' 		? 	$where .="mouvement.MVT_VALID = '".addslashes($valid)."' AND " 			: $where .="");

	if($where != '')  {$where = 'WHERE '.substr($where,0, strlen($where)-4);}


	$sql = "SELECT SUM(MVT_QUANTITE), ID_CONDIT, ID_EXERCICE, MVT_UNITE, MVT_NATURE, MVT_VALID
	FROM `mouvement` $where; ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row;
	}
	else {return array();}
}

//QTE PRODUITS COMMANDES
function getQteCde($idcde, $prd){
	$sql = "SELECT CDEPRD_QUANTITE FROM `cde_prd` WHERE ID_COMMANDE='$idcde' AND ID_CONDIT '$prd'; ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row['CDEPRD_QUANTITE'];
	}
	else {return 0;}
}

//QTE PRODUITS LIVRES
function getQteLivr($idcde, $prd){
	$sql = "SELECT SUM(LVRPRD_QUANTITE) AS NBRE FROM `lvr_prd` INNER JOIN `livraison` ON (lvr_prd.ID_LIVRAISON  = livraison.ID_LIVRAISON ) WHERE ID_COMMANDE='$idcde' AND ID_CONDIT '$prd'; ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row['NBRE'];
	}
	else {return 0;}
}
//ETAT STOCK
function StockCourantQte($id, $nature, $where){
 	$sql = "SELECT `ID_CONDIT`,`MVT_NATURE`, `MVT_VALID`, `MVT_UNITE`, sum(`MVT_QUANTITE`) as QTE FROM mouvement
	 WHERE MVT_VALID=1 AND `MVT_NATURE` LIKE '$nature' $where  group by `ID_CONDIT` having `ID_CONDIT`='$id'; ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
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
		header('location:errorPage.php');
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
		header('location:errorPage.php');
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

function getStatutExtercice($exercice){
	//SQL
	$sql = "SELECT * FROM exercice WHERE ID_EXERCICE=$exercice;";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
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
	$sql = "SELECT * FROM localite WHERE ID_LOCALITE=$id;";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query
	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row['LOC_NOM'];
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
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query
	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row['LOC_NOM'];
	}
	else return '';
}

//<?php echo getlang(385); ?>function listeDesArticles($defaut=''){
	//SQL
	(isset($defaut) ? $where = " WHERE PRD_LIBELLE LIKE '$defaut%'" : $where = "");
	$sql = "SELECT * FROM conditionmt INNER JOIN produit ON (produit.CODE_PRODUIT LIKE conditionmt.CODE_PRODUIT) $where ORDER BY CND_LIBELLE ASC;";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$list = '';
	$i = 0;
	$categorie = "";
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		// Treat categorie
		//if($categorie != $row['CODE_CATEGORIE']){
		//	$list .= '<tr>
            //<td align="center" valign="middle" class="text"> >> '.$row['CODE_CATEGORIE'].'</td>
          //  <td colspan="4" class="text" >'.($row['CAT_LIBELLE']).'</td>
        //  </tr>';
	//	}
	//	$categorie = $row['CODE_CATEGORIE'];
		$in='';$where='';
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		$Livr = StockCourantQte($row['ID_CONDIT'], 'LIVRAISON',$in, $where);
		$AutreLivr = StockCourantQte($row['ID_CONDIT'], 'AUTRE LIVRAISON',$in, $where);
		$Dotation = StockCourantQte($row['ID_CONDIT'], 'DOTATION',$in, $where);
		//print_r($Dotation);
		$entree  = ($Livr['QTE']+$AutreLivr['QTE']);
		$sortie = $Dotation['QTE'];
		$rest = $entree - $sortie;

		//$sE = 0;//etatArticleEntree($row['ID_ARTICLE'],$exercice);
		//$sB = 0;//etatArticleBonus($row['ID_ARTICLE'],$exercice);
		//$sS = 0;//etatArticleSortie($row['ID_ARTICLE'],$exercice);
		//$sM = 0;//etatArticleMalus($row['ID_ARTICLE'],$exercice);
		//$stock = 0;//($sE + $sB) - ($sM + $sS);
		if($rest <= 0) $col = "tableFINIRow";

		$list .= '<tr class="'.$col.'">
			<td align="center" valign="middle" class="text"><a href="#" onClick="pickUp(\''.$row['ID_CONDIT'].'\',\''.addslashes($row['CND_LIBELLE']).'\',\''.$rest.'\',\''.$row['PRD_PRIX'].'\',\''.addslashes($row['ID_UNITE']).'\');">'.$row['ID_CONDIT'].'</a></td>
			<td class="text" ><a href="#" onClick="pickUp(\''.$row['ID_CONDIT'].'\',\''.addslashes($row['CND_LIBELLE']).'\',\''.$rest.'\',\''.$row['PRD_PRIX'].'\',\''.addslashes($row['ID_UNITE']).'\');">'.($row['CND_LIBELLE']).'</a></td>
            <td align="center" valign="middle" class="text" ><a href="#" onClick="pickUp(\''.$row['ID_CONDIT'].'\',\''.addslashes($row['CND_LIBELLE']).'\',\''.$rest.'\',\''.$row['PRD_PRIX'].'\',\''.addslashes($row['ID_UNITE']).'\');">'.$rest.'</a></td>
			<td width="10%" align="left" valign="middle" class="text" ><a href="#" onClick="pickUp(\''.$row['ID_CONDIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.$rest.'\',\''.$row['PRD_PRIX'].'\',\''.addslashes($row['ID_UNITE']).'\');">'.($row['ID_UNITE']).'</a></td>
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
  	$sql = "SELECT * FROM benefmag INNER JOIN beneficiaire ON (benefmag.ID_BENEF=beneficiaire.ID_BENEF) $where ORDER BY BENEF_NOM ASC;";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$list = '';
	$i = 0;
	$categorie = "";
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		$list .= '<tr class="'.$col.'">
			<td align="center" valign="middle" class="text"><a href="#" onClick="pickUp(\''.$row['ID_BENEF'].'\',\''.addslashes($row['BENEF_NOM']).'\');">'.$row['CODE_BENEF'].'</a></td>
			<td class="text" ><a href="#" onClick="pickUp(\''.$row['ID_BENEF'].'\',\''.addslashes($row['BENEF_NOM']).'\');">'.(stripslashes($row['BENEF_NOM'])).'</a></td>
			<td class="text" ><a href="#" onClick="pickUp(\''.$row['ID_BENEF'].'\',\''.addslashes($row['BENEF_NOM']).'\');">'.(stripslashes($row['CODE_NOMBENF'])).'</a></td>
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
function listeTousBeneficaires($where=''){
	//SQL
	$sql = "SELECT * FROM beneficiaire $where ORDER BY BENEF_NOM ASC;";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$list = '';
	$i = 0;
	$categorie = "";
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		$list .= '<tr class="'.$col.'">
			<td align="center" valign="middle" class="text"><a href="#" onClick="pickUp(\''.$row['ID_BENEF'].'\',\''.addslashes($row['BENEF_NOM']).'\');">'.$row['CODE_BENEF'].'</a></td>
			<td class="text" ><a href="#" onClick="pickUp(\''.$row['ID_BENEF'].'\',\''.addslashes($row['BENEF_NOM']).'\');">'.(stripslashes($row['BENEF_NOM'])).'</a></td>
			<td class="text" ><a href="#" onClick="pickUp(\''.$row['ID_BENEF'].'\',\''.addslashes($row['BENEF_NOM']).'\');">'.(stripslashes($row['CODE_NOMBENF'])).'</a></td>
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


//<?php echo getlang(385); ?>function listeDesArticlesPrg($defaut=''){
	//SQL
	(isset($defaut) ? $where = " WHERE PRD_LIBELLE LIKE '$defaut%'" : $where = "");
	$sql = "SELECT * FROM bareme INNER JOIN produit ON (produit.CODE_PRODUIT LIKE bareme.CODE_PRODUIT) $where ORDER BY CND_LIBELLE ASC;";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$list = '';
	$i = 0;
	$categorie = "";
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		$list .= '<tr class="'.$col.'">
			<td align="center" valign="middle" class="text"><a href="#" onClick="pickUp(\''.$row['ID_CONDIT'].'\',\''.addslashes($row['CND_LIBELLE']).'\',\''.$rest.'\',\''.$row['PRD_PRIX'].'\',\''.addslashes($row['ID_UNITE']).'\');">'.$row['ID_CONDIT'].'</a></td>
			<td class="text" ><a href="#" onClick="pickUp(\''.$row['ID_CONDIT'].'\',\''.addslashes($row['CND_LIBELLE']).'\',\''.$rest.'\',\''.$row['PRD_PRIX'].'\',\''.addslashes($row['ID_UNITE']).'\');">'.($row['CND_LIBELLE']).'</a></td>
            <td align="center" valign="middle" class="text" ><a href="#" onClick="pickUp(\''.$row['ID_CONDIT'].'\',\''.addslashes($row['CND_LIBELLE']).'\',\''.$rest.'\',\''.$row['PRD_PRIX'].'\',\''.addslashes($row['ID_UNITE']).'\');"></a></td>
			<td width="10%" align="left" valign="middle" class="text" ><a href="#" onClick="pickUp(\''.$row['ID_CONDIT'].'\',\''.addslashes($row['PRD_LIBELLE']).'\',\''.$rest.'\',\''.$row['PRD_PRIX'].'\',\''.addslashes($row['ID_UNITE']).'\');">'.($row['ID_UNITE']).'</a></td>
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

//<?php echo getlang(385); ?>function listeProgramme($where=''){
	//SQL
	$sql = "SELECT * FROM programmation INNER JOIN beneficiaire ON (programmation.ID_BENEF = beneficiaire.ID_BENEF) $where ORDER BY programmation.CODE_NDOTATION, BENEF_NOM ASC;";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$list = '';
	$i = 0;
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		$list .= '<tr class="'.$col.'">
			<td align="center" valign="middle" class="text"><a href="#" onClick="pickUp(\''.$row['ID_PROGR'].'\',\''.addslashes($row['CODE_NDOTATION'].' - '.$row['BENEF_NOM']).'\');">'.(stripslashes($row['CODE_NDOTATION'])).'</a></td>
			<td class="text" ><a href="#" onClick="pickUp(\''.$row['ID_PROGR'].'\',\''.addslashes($row['CODE_NDOTATION'].' - '.$row['BENEF_NOM']).'\');">'.(stripslashes($row['CODE_NDOTATION'].' - '.$row['BENEF_NOM'])).'</a></td>
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

function getInfoGenerale(){
	$sql ="SELECT * FROM `infogenerale`";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
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
function updateLog($username, $idcust, $action='' ){
	$sql = "INSERT INTO `logs` (`LOGIN` ,`MLLE`,`LOG_DATE` ,`LOG_DESCRIP` ) ";
	$sql .= "VALUES ( '".addslashes($username)."', '".addslashes($idcust)."', '".date("Y-m-d H:i:s")."', '".addslashes($action)."') ";

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

//This function updates the log table
function getPersonnelName($mlle){
	$sql = "SELECT PERS_NOM, PERS_PRENOMS FROM `personnel` WHERE NUM_MLLE LIKE '$mlle'; ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row['PERS_PRENOMS'].' '.$row['PERS_NOM'];
	}
	else {return '';}
}

//CONDITIONNEMENT
function getConditionnement($id){
	$sql = "SELECT CND_LIBELLE FROM `conditionmt` WHERE ID_CONDIT ='$id'; ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row['CND_LIBELLE'];
	}
	else {return '';}
}

function getBeneficiaire($id){
	$sql = "SELECT BENEF_NOM,BENEF_EBREVIATION,CODE_NOMBENF FROM `beneficiaire` WHERE ID_BENEF =$id ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
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
 	$sql = "SELECT BENEF_NOM,BENEF_EBREVIATION FROM programmation INNER JOIN `beneficiaire` ON (programmation.ID_BENEF=beneficiaire.ID_BENEF) WHERE ID_PROGR =$id ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row['BENEF_NOM'];
	}
	else {return '';}
}


function getNomBeneficiaire($id){
	$sql = "SELECT BENEF_NOM FROM `beneficiaire` WHERE ID_BENEF =$id ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
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
		header('location:errorPage.php');
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
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row['PRD_LIBELLE'];
	}
	else {return '';}
}

//GET magasin
function getmagasin($id){
	$sql = "SELECT SER_NOM FROM `magasin` WHERE CODE_MAGASIN LIKE '$id'; ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
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


//GET SERVICE
function getService($id){
	$sql = "SELECT SER_NOM FROM `service` WHERE CODE_MAGASIN LIKE '$id'; ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row['SER_NOM'];
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
		header('location:errorPage.php');
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
	$sql = "SELECT NBENEF_LIBELLE FROM `nombeneficiaire` WHERE CODE_NOMBENF LIKE '$id'; ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row['NBENEF_LIBELLE'];
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
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row['GRPLOC_LIBELLE'];
	}
	else {return '';}
}

//magasin
function getmagasinName($id){
	$sql = "SELECT SER_NOM FROM `magasin` WHERE CODE_MAGASIN ='$id'; ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
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
	$sql = "SELECT SER_NOM FROM `service` WHERE CODE_MAGASIN ='$id'; ";

	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
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
		header('location:errorPage.php');
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
	$sql = "SELECT ID_EXERCICE, EX_CLOTURE FROM `exercice` ORDER BY ID_EXERCICE DESC'; ";
	$exercice=array('EXERCICE'=>'', 'STATUT_EXERCICE'=>0);
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	if($query->rowCount()) {
		$row = $query->fetch(PDO::FETCH_ASSOC);
		$exercice['EXERCICE'] = $row['ID_EXERCICE'];
		$exercice['STATUT_EXERCICE'] = $row['EX_CLOTURE'];
	}
	return $exercice;
}



function sousMenuSansAj($page='', $tab='', $droit=''){
	$return ='
	<table border="0" align="left" cellpadding="0" cellspacing="4">
        <tr>
        	<td><input name="AddButton" type="button" class="button" value="Ajouter" onClick="openPage(\'add'.$page.'.php?selectedTab='.$tab.'\');"></td>
        	<td><input name="DeleteButton" type="button" class="button" value="Supprimer" onClick="msgSuppress();"></td>
			<td><input name="EditButton" type="button" class="button" value="Editer" onClick="msgModif();"></td>
            <td><input name="SearchButton" type="button" class="button" value="<?php echo getlang(139); ?>" onClick="openPage(\'search'.$page.'.php?selectedTab='.$tab.'\');"></td>
        </tr>
    </table>';
	return $return;

}

function sousMenuAvecValide($page='', $tab='', $droit=array()){
	$return ='
	<table border="0" align="left" cellpadding="0" cellspacing="4">
        <tr>
        	<td><input name="AddButton" type="button" class="button" value="Ajouter" onClick="openPage(\'add'.$page.'.php?selectedTab='.$tab.'\');"></td>
        	<td><input name="DeleteButton" type="button" class="button" value="Supprimer" onClick="msgSuppress();"></td>
			<td><input name="EditButton" type="button" class="button" value="Editer" onClick="msgModif();"></td>
			<td><input name="ValidateButton" type="button" class="button" value="Valider" onClick="msgValid();"></td>
            <td><input name="SearchButton" type="button" class="button" value="<?php echo getlang(139); ?>" onClick="openPage(\'search'.$page.'.php?selectedTab='.$tab.'\');"></td>
        </tr>
    </table>';
	return $return;
}


function sousMenuDroit($page='', $tab='', $droit=array()){
	$d = preg_split('/ /',$droit );
	$return ='
	<table border="0" align="left" cellpadding="0" cellspacing="4">
        <tr>';

        if($d[1]==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="AddButton" type="button" class="button" value="Ajouter" onClick="openPage(\'add'.$page.'.php?selectedTab='.$tab.'\');"></td>';}
        else {$return .='<td><input name="AddButton" type="button" class="buttonDisabled" disabled="disabled" value="Ajouter" onClick="openPage(\'add'.$page.'.php?selectedTab='.$tab.'\');"></td>';}

        if($d[2]==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="EditButton" type="button" class="button" value="Editer" onClick="msgModif();"></td>';}
        else {$return .='<td><input name="EditButton" type="button" class="buttonDisabled" disabled="disabled" value="Editer" onClick="msgModif();"></td>';}

        if($d[3]==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="DeleteButton" type="button" class="button" value="Supprimer" onClick="msgSuppress();"></td>';}
        else {$return .='<td><input name="DeleteButton" type="button" class="buttonDisabled" disabled="disabled" value="Supprimer" onClick="msgSuppress();"></td>';}

		if($d[4]==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="ValidateButton" type="button" class="button" value="Valider" onClick="msgValid();"></td>';}
		else {$return .='<td><input name="ValidateButton" type="button" class="buttonDisabled" disabled="disabled" value="Valider" onClick="msgValid();"></td>';}

		$return .='<td><input name="SearchButton" type="button" class="button" value="<?php echo getlang(139); ?>" onClick="openPage(\'search'.$page.'.php?selectedTab='.$tab.'\');"></td>
        </tr>
    </table>';
	return $return;
/*

	$return ='<table border="0" align="left" cellpadding="0" cellspacing="4">
        <tr>';
	(isset($droit['ADD']) && $droit['ADD']==1 ? $return .='<td><input name="AddButton" type="button" class="button" value="Ajouter" onClick="openPage(\'add'.$page.'.php?selectedTab='.$tab.'\');"></td>' : $return .='');
    (isset($droit['DEL']) && $droit['DEL']==1 ? $return .='<td><input name="DeleteButton" type="button" class="button" value="Supprimer" onClick="msgSuppress();"></td>' : $return .='');
	(isset($droit['EDI']) && $droit['EDI']==1 ? $return .='<td><input name="EditButton" type="button" class="button" value="Editer" onClick="msgModif();"></td>' : $return .='');
	(isset($droit['VAL']) && $droit['VAL']==1 ? $return .='<td><input name="ValidateButton" type="button" class="button" value="Valider" onClick="msgValid();"></td>' : $return .='');
	(isset($droit['SEA']) && $droit['SEA']==1 ? $return .='<td><input name="SearchButton" type="button" class="button" value="<?php echo getlang(139); ?>" onClick="openPage(\'search'.$page.'.php?selectedTab='.$tab.'\');"></td>'  : $return .='');
	$return .='</tr></table>';
	return $return;
*/
}

function sousMenuDroitSansVlider($page='', $tab='', $droit=array()){
	$d = preg_split('/ /',$droit );
	$return ='
	<table border="0" align="left" cellpadding="0" cellspacing="4">
        <tr>';

        if($d[1]==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="AddButton" type="button" class="button" value="Ajouter" onClick="openPage(\'add'.$page.'.php?selectedTab='.$tab.'\');"></td>';}
        else {$return .='<td><input name="AddButton" type="button" class="buttonDisabled" disabled="disabled" value="Ajouter" onClick="openPage(\'add'.$page.'.php?selectedTab='.$tab.'\');"></td>';}

        if($d[2]==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="EditButton" type="button" class="button" value="Editer" onClick="msgModif();"></td>';}
        else {$return .='<td><input name="EditButton" type="button" class="buttonDisabled" disabled="disabled" value="Editer" onClick="msgModif();"></td>';}

        if($d[3]==1 && $_SESSION['GL_USER']['STATUT_EXERCICE']==0) {$return .='<td><input name="DeleteButton" type="button" class="button" value="Supprimer" onClick="msgSuppress();"></td>';}
        else {$return .='<td><input name="DeleteButton" type="button" class="buttonDisabled" disabled="disabled" value="Supprimer" onClick="msgSuppress();"></td>';}

		$return .='<td><input name="SearchButton" type="button" class="button" value="<?php echo getlang(139); ?>" onClick="openPage(\'search'.$page.'.php?selectedTab='.$tab.'\');"></td>
        </tr>
    </table>';
	return $return;
/*

	$return ='<table border="0" align="left" cellpadding="0" cellspacing="4">
        <tr>';
	(isset($droit['ADD']) && $droit['ADD']==1 ? $return .='<td><input name="AddButton" type="button" class="button" value="Ajouter" onClick="openPage(\'add'.$page.'.php?selectedTab='.$tab.'\');"></td>' : $return .='');
    (isset($droit['DEL']) && $droit['DEL']==1 ? $return .='<td><input name="DeleteButton" type="button" class="button" value="Supprimer" onClick="msgSuppress();"></td>' : $return .='');
	(isset($droit['EDI']) && $droit['EDI']==1 ? $return .='<td><input name="EditButton" type="button" class="button" value="Editer" onClick="msgModif();"></td>' : $return .='');
	(isset($droit['VAL']) && $droit['VAL']==1 ? $return .='<td><input name="ValidateButton" type="button" class="button" value="Valider" onClick="msgValid();"></td>' : $return .='');
	(isset($droit['SEA']) && $droit['SEA']==1 ? $return .='<td><input name="SearchButton" type="button" class="button" value="<?php echo getlang(139); ?>" onClick="openPage(\'search'.$page.'.php?selectedTab='.$tab.'\');"></td>'  : $return .='');
	$return .='</tr></table>';
	return $return;
*/
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

//DROIT
function getDroit($where, $grp){
	$sql = "SELECT $where FROM groupe WHERE ID_GROUPE ='$grp' ;";
	$ret='0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0';
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query
	$row = $query->fetch(PDO::FETCH_ASSOC);
	//print_r($row);
	$t ='';
	foreach ($row as $key => $val){$t.=$val.' ';}
	if($t !='') $ret =$t;
	//echo $ret;
	return $ret;
}

//DROIT MAJ
function getDroitMAJ($where, $grp){
	$sql = "SELECT $where FROM groupe WHERE ID_GROUPE ='$grp' ;";
	$ret='0 0 0 0';
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query
	$row = $query->fetch(PDO::FETCH_ASSOC);

	$t ='';
	foreach ($row as $key => $val){$t.=$val.' ';}
	if($t !='')  $ret =$t;
	return $ret;
}

//DROIT MAJ
function getDroitTOPMENUS($grp){
	$sql = "SELECT MENU_CPMIEP FROM groupe WHERE ID_GROUPE ='$grp' ;";
	$ret='0 0 0 0 0 0 0';
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
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
	$sql = "SELECT * FROM beneficiaire WHERE beneficiaire.CODE_NOMBENF LIKE 'ETB' ;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
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
	$sql = "SELECT SUM(LVRPRD_RECU) as TOTAL FROM lvr_prd WHERE ID_CONDIT='$idproduit' AND LVR_IDCOMMANDE='$cde';";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$row = $query->fetch(PDO::FETCH_ASSOC);
	return $row['TOTAL'];
}

function entreePourProduitRec($recSortie, $idproduit){
	$sql = "SELECT SUM(CNDREC_QTEE) as TOTAL FROM recond_entre WHERE ID_CONDIT='$idproduit' AND ID_RECONDIT='$recSortie';";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
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
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['ID_EXERCICE'] ? $select .='<option value="'.$row['ID_EXERCICE'].'"  selected="selected">'.(stripslashes($row['EX_LIBELLE'])).'</option>' : $select .='<option value="'.$row['ID_EXERCICE'].'">'.(stripslashes($row['EX_LIBELLE'])).'</option>');
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
	$sql = "SELECT * FROM beneficiaire WHERE CODE_NOMBENF LIKE 'ETB' ORDER BY BENEF_NOM ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		(isset($row['BENEF_EBREVIATION']) ? $abbr = '('.$row['BENEF_EBREVIATION'].')' : $abbr='');
		($default == $row['ID_BENEF'] ? $select .='<option value="'.$row['ID_BENEF'].'"  selected="selected">'.(stripslashes($row['BENEF_NOM'])).'</option>' : $select .='<option value="'.$row['ID_BENEF'].'">'.(stripslashes($row['BENEF_NOM'].$abbr)).'</option>');
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
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['NUM_MLLE'] ? $select .='<option value="'.$row['NUM_MLLE'].'"  selected="selected">'.(stripslashes($row['PERS_NOM'].' '.$row['PERS_PRENOMS'])).'</option>' : $select .='<option value="'.$row['NUM_MLLE'].'">'.(stripslashes($row['PERS_NOM'].' '.$row['PERS_PRENOMS'])).'</option>');
	} // while
	return $select;
}

//GROUPE
function selectGroupe($default=''){
	$sql = "SELECT * FROM groupe  ORDER BY GRPE_LIBELLE ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['ID_GROUPE'] ? $select .='<option value="'.$row['ID_GROUPE'].'"  selected="selected">'.(stripslashes($row['GRPE_LIBELLE'])).'</option>' : $select .='<option value="'.$row['ID_GROUPE'].'">'.(stripslashes($row['GRPE_LIBELLE'])).'</option>');
	} // while
	return $select;
}


//BENEFICIAIRE
function selectBeneficiaire($default=''){
	$sql = "SELECT * FROM beneficiaire ORDER BY BENEF_NOM ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		(isset($row['BENEF_EBREVIATION']) ? $abbr = '('.$row['BENEF_EBREVIATION'].')' : $abbr='');
		($default == $row['ID_BENEF'] ? $select .='<option value="'.$row['ID_BENEF'].'"  selected="selected">'.(stripslashes($row['BENEF_NOM'])).'</option>' : $select .='<option value="'.$row['ID_BENEF'].'">'.(stripslashes($row['BENEF_NOM'].$abbr)).'</option>');
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
		header('location:errorPage.php');
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
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['CODE_CATEGORIE'] ? $select .='<option value="'.$row['CODE_CATEGORIE'].'"  selected="selected">'.(stripslashes($row['CAT_LIBELLE'])).'</option>' : $select .='<option value="'.$row['CODE_CATEGORIE'].'">'.(stripslashes($row['CAT_LIBELLE'])).'</option>');
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
		header('location:errorPage.php');
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
		header('location:errorPage.php');
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
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['ID_LOCALITE'] ? $select .='<option value="'.$row['ID_LOCALITE'].'"  selected="selected">'.(stripslashes($row['LOC_NOM'])).'</option>' : $select .='<option value="'.$row['ID_LOCALITE'].'">'.(stripslashes($row['LOC_NOM'])).'</option>');
	} // while
	return $select;
}

//magasin
function selectmagasin($default=''){
	$sql = "SELECT * FROM magasin ORDER BY SER_NOM ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query
	$select='';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['CODE_MAGASIN'] ? $select .='<option value="'.$row['CODE_MAGASIN'].'"  selected="selected">'.(stripslashes($row['SER_NOM'])).'</option>' : $select .='<option value="'.$row['CODE_MAGASIN'].'">'.(stripslashes($row['SER_NOM'])).'</option>');
	} // while
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
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['ID_GRPSERVICE'] ? $select .='<option value="'.$row['ID_GRPSERVICE'].'"  selected="selected">'.(stripslashes($row['GRPSER_LIBELLE'])).'</option>' : $select .='<option value="'.$row['ID_GRPSERVICE'].'">'.(stripslashes($row['GRPSER_LIBELLE'])).'</option>');
	} // while
	return $select;
}


function selectTypeBeneficiaire($default=''){
	$sql = "SELECT * FROM nombeneficiaire ORDER BY NBENEF_LIBELLE ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['CODE_NOMBENF'] ? $select .='<option value="'.$row['CODE_NOMBENF'].'"  selected="selected">'.(stripslashes($row['NBENEF_LIBELLE'])).'</option>' : $select .='<option value="'.$row['CODE_NOMBENF'].'">'.(stripslashes($row['NBENEF_LIBELLE'])).'</option>');
	} // while
	return $select;
}

//SERVICE
function selectService($default=''){
	$sql = "SELECT * FROM service ORDER BY SER_NOM ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['CODE_MAGASIN'] ? $select .='<option value="'.$row['CODE_MAGASIN'].'"  selected="selected">'.(stripslashes($row['SER_NOM'])).'</option>' : $select .='<option value="'.$row['CODE_MAGASIN'].'">'.(stripslashes($row['SER_NOM'])).'</option>');
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
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['ID_COMMANDE'] ? $select .='<option value="'.$row['ID_COMMANDE'].'"  selected="selected">'.(stripslashes($row['CODE_COMMANDE'].' - '.$row['CDE_LIBELLE'])).'</option>' : $select .='<option value="'.$row['ID_COMMANDE'].'">'.(stripslashes($row['CODE_COMMANDE'].' - '.$row['CDE_LIBELLE'])).'</option>');
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
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['ID_PROGR'] ? $select .='<option value="'.$row['ID_PROGR'].'"  selected="selected">'.(stripslashes($row['CODE_NDOTATION'].' - '.getBeneficiaire($row['ID_BENEF']))).'</option>' : $select .='<option value="'.$row['ID_PROGR'].'">'.(stripslashes($row['CODE_NDOTATION'].' - '.getBeneficiaire($row['ID_BENEF']))).'</option>');
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
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		//$ok = getQteCde($row['ID_COMMANDE'],) - getQteLivr($row['ID_COMMANDE'],);
		($default == $row['ID_COMMANDE'] ? $select .='<option value="'.$row['ID_COMMANDE'].'"  selected="selected">'.(stripslashes($row['CODE_COMMANDE'].' - '.$row['CDE_LIBELLE'])).'</option>' : $select .='<option value="'.$row['ID_COMMANDE'].'">'.(stripslashes($row['CODE_COMMANDE'].' - '.$row['CDE_LIBELLE'])).'</option>');
	} // while
	return $select;
}

//SELCT DOTATION
function selectDotation($default=''){
	$sql = "SELECT * FROM nomdotation  ORDER BY CODE_NDOTATION ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
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
	INNER JOIN beneficiaire ON (programmation.ID_BENEF=beneficiaire.ID_BENEF) $where ORDER BY PGR_DATE DESC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['ID_PROGR'] ? $select .='<option value="'.$row['ID_PROGR'].'"  selected="selected">'.(stripslashes($row['CODE_NDOTATION']. ' - '.$row['BENEF_NOM'])).'</option>' : $select .='<option value="'.$row['ID_PROGR'].'">'.(stripslashes($row['NDOT_LIBELLE']. ' '.$row['BENEF_EBREVIATION'])).'</option>');
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
		header('location:errorPage.php');
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
		header('location:errorPage.php');
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
		header('location:errorPage.php');
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
		header('location:errorPage.php');
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
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['LOGIN'] ? $select .='<option value="'.$row['LOGIN'].'"  selected="selected">'.(stripslashes($row['PERS_NOM'].' '.$row['PERS_PRENOMS'].' ('.$row['NUM_MLLE'].')')).'</option>' : $select .='<option value="'.$row['LOGIN'].'">'.(stripslashes($row['PERS_NOM'].' '.$row['PERS_PRENOMS'].' ('.$row['NUM_MLLE'].')')).'</option>');
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
		header('location:errorPage.php');
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
		header('location:errorPage.php');
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
	$sql = "SELECT * FROM conditionmt ORDER BY CND_LIBELLE ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$select = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($default == $row['ID_CONDIT'] ? $select .='<option value="'.$row['ID_CONDIT'].'"  selected="selected">'.(stripslashes($row['CND_LIBELLE'])).'</option>' : $select .='<option value="'.$row['ID_CONDIT'].'">'.(stripslashes($row['CND_LIBELLE'])).'</option>');
	} // while
	return $select;
}

function getLibConditionne($default){
	$sql = "SELECT CND_LIBELLE  FROM conditionmt WHERE ID_CONDIT =$default;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query
	$row = $query->fetch(PDO::FETCH_ASSOC);
	return $row['CND_LIBELLE'];
}


//Return the number of day between two dates
function NbJours($debut, $fin) {

  $tDeb = explode("-", $debut);
  $tFin = explode("-", $fin);

  $diff = mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) -
          mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]);

  return(($diff / 86400)+1);
}

//Generate le pageLength list
function pageLengh($defaut =20){
	$list = '';
	for($i=10; $i< 50; $i+=10){
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

function getUsermagasin($login){
	//Save data
	$sql ="SELECT * from mag_compte WHERE LOGIN LIKE '$login';";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$mag = '';
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		$mag .= $row['CODE_MAGASIN'].' ';
	}

	return trim($mag);
}

function getUsermagasinList($login, $default){
	//Save data
	$sql ="SELECT mag_compte.*, magasin.SER_NOM from mag_compte INNER JOIN magasin ON (mag_compte.CODE_MAGASIN LIKE magasin.CODE_MAGASIN) WHERE LOGIN LIKE '$login' ORDER BY magasin.SER_NOM ASC;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
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
		header('location:errorPage.php');
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

	if($page == 1){ //Première page  Affichage de 1 &agrave; 25 of 50 | Premier | Pr&eacute;c&eacute;dente | <a href=''>Derni&egrave;re</a></
		$i = ($page-1)*$engPage+1;
		$j = $page*$engPage;
		($page < $nbrePage ? $k = $page+1 : $k = $page);
		$Premier .='Affichage de '.$i.' &agrave; '.$j.' sur '.$nbreEng.' | Premi&egrave;re | Pr&eacute;c&eacute;dent | ';
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
	$SQL1 ="SELECT LIBELLE_BENEFICIAIRE   FROM $table1 WHERE ID_BENEFICIAIRE ='$id';";

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
		header('location:errorPage.php');
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
		header('location:errorPage.php');
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

//--------- BESOINS -----------------------------
//Deplay besoins lignes
//Who enter the needs  - Besoins
function whoBesoins($idb, $nMlle='', $autre =''){
	$ret = '';
	$table1 = "stocks_beneficiaire";
	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$SQL = "SELECT  ID_BENEFICIAIRE, LIBELLE_BENEFICIAIRE, ID_PROVINCE FROM $table1 WHERE ID_BENEFICIAIRE='$idb';";
	$result = mysql_query($SQL);
	$row = mysql_fetch_array($result);
	$ret = $row['LIBELLE_BENEFICIAIRE'];
	if($autre!='') $ret = $ret.'/'.$autre;
	//if($row['ID_BENEFICIAIRE'] == 7 && $row['ID_PROVINCE'] ==0){$ret = $autre;}	//Autre
	//if($row['ID_BENEFICIAIRE'] == 8 && $row['ID_PROVINCE'] ==0){$ret = $_SESSION['GL_USER']['NOM'];} //Moi-même
	return $ret;
}

//Fill the consultation array() of Besoin
function setConsBesoins($id){
	$table1 = "stocks_besoin";
	$table2 = "stocks_beneficiaire";
	$table3 = "stocks_ligne_besoin";
	$table4 = "stocks_article";
	$exercice = $_SESSION['GL_USER']['EXERCICE'];

	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($id) ? $where = " AND ID_BESOIN=$id " : $where = "");
	$where .="AND $table1.ID_BENEFICIAIRE=$table2.ID_BENEFICIAIRE";
	$SQL = "SELECT $table1.*, LIBELLE_BENEFICIAIRE FROM $table1, $table2 WHERE $table1.ID_EXERCICE ='$exercice' $where;";
	$result = mysql_query($SQL);
	$row = mysql_fetch_array($result);

	//Fill session vars
	$_SESSION['CONS_BESOIN']= array(
	'idBesoin'	=> $row['ID_BESOIN'],
	'exercice'	=> $row['ID_EXERCICE'],
	'beneficiaire'	=> $row['ID_BENEFICIAIRE'].' - '.$row['LIBELLE_BENEFICIAIRE'],
	'autre'		=>  $row['AUTRE'],
	'dateAjout'	=> frFormat($row['DATE_BESOIN']),
	'libelle'	=> $row['LIBELLE_BESOIN'],
	);

	//SQL
	$where ='';
	(isset($id) ? $where = " AND ID_BESOIN=$id " : $where = "");
	$where .="AND $table3.ID_ARTICLE=$table4.ID_ARTICLE ";
	$SQL = "SELECT $table3.*, $table4.* FROM $table3, $table4 WHERE $table3.ID_EXERCICE ='$exercice' $where ORDER BY LIBELLE_ARTICLE ASC;";
	$result = mysql_query($SQL);

	//Fill session vars
	$_SESSION['CONS_BESOIN']['ligne'] =array();
	while($row = mysql_fetch_array($result)){
		array_push($_SESSION['CONS_BESOIN']['ligne'], array('idArticle'=>$row['ID_ARTICLE'], 'designat'=>$row['LIBELLE_ARTICLE'],'prixUnit'=>$row['PU_BESION'], 'qte'=>$row['QTE_DDE'], 'unite'=>$row['UNITE']));
	}
}

//Deplay ligne consultation besoins
function lignConBesoins($wh='', $ord='', $sens='ASC',$page=1,$nelt){
	$ret = '';
	$t = array();
	$table1 = "stocks_besoin";
	$exercice = $_SESSION['GL_USER']['EXERCICE'];

	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($wh) and $wh!='' ? $where = " AND $wh " : $where = "");

	//(isset($ord) and $wh!=''  ? $order = "$ord $sens" : $order = "");
	$SQL = "SELECT * FROM $table1 WHERE $table1.ID_EXERCICE ='$exercice' $where  ORDER BY DATE_BESOIN DESC ;";
	$result = mysql_query($SQL);
	$t['NE'] = mysql_num_rows($result);

	$i = ($page-1)*$nelt;
	$SQL = "SELECT * FROM $table1 WHERE $table1.ID_EXERCICE ='$exercice' $where ORDER BY DATE_BESOIN DESC LIMIT $i, $nelt;";
	$result = mysql_query($SQL);
	if(mysql_num_rows($result)==0) $ret .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e</td></tr>';
	$i = 0;
	$j=6;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		($row['VALIDER']==1 ? $valid = '<img src="../images/valider.gif" width="16" height="16">' : $valid = '');
		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_BESOIN'].'" onClick="go('.$row['ID_BESOIN'].','.$j.');"></td>
					<td width="3%" align="center">'.$valid.'</td>
                    <td width="6%" height="22" class="text" align="center">'.$row['ID_BESOIN'].'</td>
                    <td width="12%" height="22" class="text" align="center">'.frFormat($row['DATE_BESOIN']).'</td>
                    <td width="25%" class="text" >'.(stripslashes(whoBesoins($row['ID_BENEFICIAIRE'], $row['NUM_MATRICULE'], $row['AUTRE']))).'</td>
                    <td width="42%" class="text" >'. (stripslashes($row['LIBELLE_BESOIN'])).'</td>
                    <td width="12%" class="text" align="center"><input name="detail" type="button" class="button" id="detail"  value="D&eacute;tails &gt;&gt;" onClick="javascript:window.location.href=\'besoins2.php?selectedTab=needs&id='.$row['ID_BESOIN'].'\';"></td>
                 </tr>';
				 $i++;
				 $j++;$j++;
	}
	$t['L']=$ret;
	//mysql_close);
	return $t;
}

function lignConsolidBesoins($wh=''){
	$ret = '';
	$t = array();
	$table1 = "stocks_besoin";
	$table2 = "stocks_ligne_besoin";
	$table3 = "stocks_article";

	$exercice = $_SESSION['GL_USER']['EXERCICE'];

	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($wh) and $wh!='' ? $where = " AND $wh " : $where = "");

	//$order =''; AND $table2.CONSOLIDER=0
	//(isset($ord) and $wh!=''  ? $order = " ORDER BY $ord $sens" : $order = "");
	$SQL  = "SELECT * FROM $table1, $table2, $table3 WHERE $table1.ID_EXERCICE ='$exercice' ";
	$SQL .= "AND $table1.ID_BESOIN=$table2.ID_BESOIN AND $table2.ID_ARTICLE=$table3.ID_ARTICLE ";
	$SQL .= "AND VALIDER=0  $where  ORDER BY DATE_BESOIN DESC ;";
	$result = mysql_query($SQL);

	if(mysql_num_rows($result)==0) $ret .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e</td></tr>';
	$i = 0;

	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		($row['VALIDER']==1 ? $valid = '<img src="../images/valider.gif" width="16" height="16">' : $valid = '');

		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_BESOIN'].'"></td>
					<td width="6%" height="22" class="text" align="center">'.$row['ID_BESOIN'].'</td>
                    <td width="12%" height="22" class="text" align="center">'.frFormat($row['DATE_BESOIN']).'</td>
                    <td width="25%" class="text" >'.(stripslashes(whoBesoins($row['ID_BENEFICIAIRE'], $row['NUM_MATRICULE'], $row['AUTRE']))).'</td>
                    <td width="42%" class="text" >'. (stripslashes($row['LIBELLE_ARTICLE'])).'</td>
                    <td width="42%" class="text" align="center" >'. (stripslashes($row['QTE_DDE'])).'</td>
                </tr>';
				 $i++;
	}

	//mysql_close);
	return $ret;
}


function lignSearchBesoins($cr1, $cr2, $cr3,$page=1,$nelt){
	$ret = '';
	$t = array();
	$table1 = "stocks_besoin";
	$exercice = $_SESSION['GL_USER']['EXERCICE'];
	(isset($cr2) && $cr2 !='' ? $cr2 = mysqlFormat($cr2) : $cr2='');

	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($cr1) and $cr1!='00' ? $where .= " ID_BENEFICIAIRE ='$cr1' AND " : $where .= "");
	(isset($cr2) and $cr2 !='' ? $where .= " $table1.DATE_BESOIN = '$cr2' AND " : $where .= "");
	(isset($cr3)  and $cr3 !='' ? $where .= " $table1.LIBELLE_BESOIN LIKE '%$cr3%' AND " : $where .= "");

	if($where != '') $where = substr(" AND $where",0,strlen(" AND $where")-4);

	//$order ='';
	//(isset($ord) and $wh!=''  ? $order = " ORDER BY $ord $sens" : $order = "");
	$SQL = "SELECT * FROM $table1 WHERE $table1.ID_EXERCICE ='$exercice' $where  ORDER BY DATE_BESOIN DESC ;";
	$result = mysql_query($SQL);
	$t['NE'] = mysql_num_rows($result);

	$i = ($page-1)*$nelt;
	$SQL = "SELECT * FROM $table1 WHERE $table1.ID_EXERCICE ='$exercice' $where  ORDER BY DATE_BESOIN DESC  LIMIT $i, $nelt;";
	$result = mysql_query($SQL);
	if(mysql_num_rows($result)==0) $ret .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e</td></tr>';
	$i = 0;
	$j = 6;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		($row['VALIDER']==1 ? $valid = '<img src="../images/valider.gif" width="16" height="16">' : $valid = '');
		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_BESOIN'].'" onClick="go('.$row['ID_BESOIN'].','.$j.');"></td>
                    <td width="3%" align="center">'.$valid.'</td>
					<td width="6%" height="22" class="text" align="center">'.$row['ID_BESOIN'].'</td>
                    <td width="12%" height="22" class="text" align="center">'.frFormat($row['DATE_BESOIN']).'</td>
                    <td width="25%" class="text" >'.(stripslashes(whoBesoins($row['ID_BENEFICIAIRE'], $row['NUM_MATRICULE'], $row['AUTRE']))).'</td>
                    <td width="42%" class="text" >'.(stripslashes($row['LIBELLE_BESOIN'])).'</td>
                    <td width="12%" class="text" align="center"><input name="detail" type="button" class="button" id="detail"  value="D&eacute;tails &gt;&gt;" onClick="javascript:window.location.href=\'besoins2.php?selectedTab=needs&id='.$row['ID_BESOIN'].'\';"></td>
                 </tr>';
				 $i++;
				 $j++; $j++;
	}
	$t['L']=$ret;
	//mysql_close);
	return $t;
}

//Deplay besoins lignes
function lignBesoins($nbre, $ligne){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($ligne[$i]['idArticle']) ? $idArticle 	= $ligne[$i]['idArticle'] 	: $idArticle	='');
		(isset($ligne[$i]['designat']) 	? $designat 	= $ligne[$i]['designat'] 	: $designat		='');
		(isset($ligne[$i]['qte']) 		? $qte 			= $ligne[$i]['qte'] 		: $qte			='');
		(isset($ligne[$i]['unite']) 	? $unite 		= $ligne[$i]['unite'] 		: $unite		='');
		(isset($ligne[$i]['prixUnit'])	? $prixUnit 	= $ligne[$i]['prixUnit'] 	: $prixUnit		='');
		(isset($ligne[$i]['mntTotal'])	? $mntTotal 	= $ligne[$i]['mntTotal'] 	: $mntTotal		='');

		$ret .= '<tr align="left" valign="middle">
                    <td class="botBorderTd" nowrap>'.$i.' - </td>
					<td class="botBorderTd"><input name="openf'.$i.'" type="button" class="button"  value="..." onClick="OpenWin(\'listearticlesb.php?lg='.$i.'\',\'Liste\');"></td>
					<td class="botBorderTd"><input name="idArticle'.$i.'" type="text" readonly class="formStyleFree" id="idArticle'.$i.'" size="10" value="'.$idArticle.'"></td>
                    <td class="botBorderTd"><input name="designat'.$i.'" type="text" readonly class="formStyle" id="designat'.$i.'" value="'.$designat.'"></td>
                    <td class="botBorderTd"><input name="prixUnit'.$i.'" type="text" class="formStyleFree" id="prixUnit'.$i.'" size="10" value="'.$prixUnit.'" onBlur="javascript:if(document.FormBesoins.qte'.$i.'.value!=\'\' && document.FormBesoins.prixUnit'.$i.'.value !=\'\'){document.FormBesoins.mntTotal'.$i.'.value =document.FormBesoins.qte'.$i.'.value * document.FormBesoins.prixUnit'.$i.'.value;}"></td>
                    <td class="botBorderTd"><input name="qte'.$i.'" type="text" class="formStyleFree" id="qte'.$i.'" size="10" value="'.$qte.'" onBlur="javascript:if(document.FormBesoins.qte'.$i.'.value!=\'\' && document.FormBesoins.prixUnit'.$i.'.value !=\'\'){document.FormBesoins.mntTotal'.$i.'.value =document.FormBesoins.qte'.$i.'.value * document.FormBesoins.prixUnit'.$i.'.value;}"></td>
					<td class="botBorderTd"><input name="unite'.$i.'" type="text" readonly class="formStyleFree" id="unite'.$i.'" size="10" value="'.$unite.'"></td>
                    <td class="botBorderTd"><input name="mntTotal'.$i.'" readonly type="text" class="formStyleFree" id="mntTotal'.$i.'" size="10" value="'.$mntTotal.'"></td>
					<td class="botBorderTd"><input name="qteDispo'.$i.'" type="hidden" class="formStyleFree" id="qteDispo'.$i.'" size="10" value=""></td>
		         </tr>';
	}
	return $ret;
}

function lignEditBesoins($data, $ligne){
	$ret = '';
	$i=1;
	foreach ($data as $key=>$val){

		$ret .= '<tr align="left" valign="middle">
                    <td class="botBorderTd" nowrap>'.$i.' - </td>
					<td class="botBorderTd"><input name="delete'.$i.'" type="button" class="button" title="Supprimer" value=" X " onClick="msgSupprLigne(\''.$val['idArticle'].'\','.$i.');"></td>
					<td class="botBorderTd"><input name="openf'.$i.'" type="button" class="button"  value="..." onClick="OpenWin(\'listearticlesb1.php?lg='.$i.'\',\'Liste\');"></td>
					<td class="botBorderTd"><input name="idArticle'.$i.'" type="text" readonly class="formStyleFree" id="idArticle'.$i.'" size="10" value="'.$val['idArticle'].'">
					<input name="oldArticle'.$i.'" type="hidden"  class="formStyleFree" id="oldArticle'.$i.'" value="'.$val['idArticle'].'"></td>
                    <td class="botBorderTd"><input name="designat'.$i.'" type="text" readonly class="formStyle" id="designat'.$i.'" value="'.stripslashes($val['designat']).'"></td>
                    <td class="botBorderTd"><input name="prixUnit'.$i.'" type="text" class="formStyleFree" id="prixUnit'.$i.'" size="10" value="'.$val['prixUnit'].'" onBlur="javascript:if(document.AddBesoinsForm.qte'.$i.'.value!=\'\' && document.AddBesoinsForm.prixUnit'.$i.'.value !=\'\'){document.AddBesoinsForm.mntTotal'.$i.'.value =document.AddBesoinsForm.qte'.$i.'.value * document.AddBesoinsForm.prixUnit'.$i.'.value;}"></td>
                    <td class="botBorderTd"><input name="qte'.$i.'" type="text" class="formStyleFree" id="qte'.$i.'" size="10" value="'.$val['qte'].'" onBlur="javascript:if(document.AddBesoinsForm.qte'.$i.'.value!=\'\' && document.AddBesoinsForm.prixUnit'.$i.'.value !=\'\'){document.AddBesoinsForm.mntTotal'.$i.'.value =document.AddBesoinsForm.qte'.$i.'.value * document.AddBesoinsForm.prixUnit'.$i.'.value;}"></td>
					<td class="botBorderTd"><input name="unite'.$i.'" type="text" readonly class="formStyleFree" id="unite'.$i.'" size="10" value="'.$val['unite'].'"></td>
                    <td class="botBorderTd"><input name="mntTotal'.$i.'" readonly type="text" class="formStyleFree" id="mntTotal'.$i.'" size="10" value="'.$val['qte']*$val['prixUnit'].'"></td>
					<td class="botBorderTd"><input name="qteDispo'.$i.'" type="hidden" class="formStyleFree" id="qteDispo'.$i.'" size="10" value=""></td>
		         </tr>';
				 $i++;
	}
	return $ret;
}

//Deplay consultation ligne
function lignDetBesoins($arr){
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

//--------- APPELS OFFRES -----------------------------

//Fill the consultation array() of Besoin
function setConsAppels($id){
	$table1 = "stocks_appel_offre";
	$table2 = "stocks_ligne_appeloffre";
	$table3 = "stocks_article";
	$exercice = $_SESSION['GL_USER']['EXERCICE'];

	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($id) ? $where = " AND ID_APPELOFFRE='$id' " : $where = "");
	$SQL = "SELECT $table1.* FROM $table1 WHERE $table1.ID_EXERCICE ='$exercice' $where;";
	$result = mysql_query($SQL);
	$row = mysql_fetch_array($result);

	//Fill session vars
	$_SESSION['CONS_APPEL']= array(
	'idBesoin'	=> $row['ID_APPELOFFRE'],
	'exercice'	=> $row['ID_EXERCICE'],
	'dateAjout'	=> $row['DATE_APPELOFFRE'],
	'libelle'	=> $row['LIBELLE_APPELOFFRE'],
	);

	//SQL
	$where ='';
	(isset($id) ? $where = " AND ID_APPELOFFRE='$id' " : $where = "");
	$where .="AND $table2.ID_ARTICLE=$table3.ID_ARTICLE ";
	$SQL = "SELECT $table2.*, $table3.* FROM $table2, $table3 WHERE $table2.ID_EXERCICE ='$exercice'  $where ORDER BY LIBELLE_ARTICLE ASC;";
	$result = mysql_query($SQL);

	//Fill session vars
	$_SESSION['CONS_APPEL']['ligne'] =array();
	while($row = mysql_fetch_array($result)){
		array_push($_SESSION['CONS_APPEL']['ligne'], array('idArticle'=>$row['ID_ARTICLE'], 'designat'=>$row['LIBELLE_ARTICLE'],'prixUnit'=>$row['PU_APPELOFFRE'], 'qte'=>$row['QTE_CDE'], 'unite'=>$row['UNITE']));
	}
}

//Deplay Appels offre lignes
function lignConAppels($wh='', $ord='', $sens='ASC',$page=1,$nelt){
	$ret = '';
	$table1 = "stocks_appel_offre";
	$exercice = $_SESSION['GL_USER']['EXERCICE'];

	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($wh) and $wh!='' ? $where = " AND $wh " : $where = "");

	//$order ='';
	//(isset($ord) and $wh!=''  ? $order = " ORDER BY $ord $sens" : $order = "");
	$SQL = "SELECT * FROM $table1 WHERE $table1.ID_EXERCICE ='$exercice' $where ORDER BY DATE_APPELOFFRE DESC;";
	$result = mysql_query($SQL);
	$t['NE'] = mysql_num_rows($result);

	$i = ($page-1)*$nelt;
	$SQL = "SELECT * FROM $table1 WHERE $table1.ID_EXERCICE ='$exercice'  $where ORDER BY DATE_APPELOFFRE DESC LIMIT $i, $nelt;";
	$result = mysql_query($SQL);
	if(mysql_num_rows($result)==0) $ret .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e</td></tr>';
	$i = 0;
	$j= 6;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		($row['VALIDER']==1 ? $valid = '<img src="../images/valider.gif" width="16" height="16">' : $valid = '');
		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_APPELOFFRE'].'" onClick="go(\''.$row['ID_APPELOFFRE'].'\','.$j.');"></td>
                    <td width="1%" height="22" class="text" align="center">'.$valid.'</td>
					<td width="6%" height="22" class="text" align="center">'.$row['ID_APPELOFFRE'].'</td>
                    <td width="12%" height="22" class="text" align="center">'.frFormat($row['DATE_APPELOFFRE']).'</td>
                    <td width="50%" class="text" >'.(stripslashes($row['LIBELLE_APPELOFFRE'])).'</td>
                    <td width="5%" class="text" align="center"><input name="detail" type="button" class="button" id="detail"  value="D&eacute;tails &gt;&gt;" onClick="javascript:window.location.href=\'appels2.php?selectedTab=demands&id='.$row['ID_APPELOFFRE'].'\';"></td>
                 </tr>';
				 $i++;
				 $j++; $j++;
	}
	$t['L']=$ret;
	//mysql_close);
	return $t;
}

function lignSearchAppels($cr1,$cr2,$cr3, $sens='ASC',$page=1,$nelt){
//$xreference,$xdateAjout,$xlibelle
	$ret = '';
	$t = array();
	$table1 = "stocks_appel_offre";
	$exercice = $_SESSION['GL_USER']['EXERCICE'];

	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($cr1) and $cr1!='' ? $where .= " ID_APPELOFFRE LIKE '$cr1' AND " : $where .= "");
	(isset($cr2) and $cr2!='' ? $where .= " DATE_APPELOFFRE = '".mysqlFormat($cr2)."' AND " : $where .= "");
	(isset($cr3) and $cr3!='' ? $where .= " LIBELLE_APPELOFFRE LIKE '%$cr3%' AND " : $where .= "");

	if($where != '') $where = substr(" AND $where",0,strlen(" AND $where")-4);

	//$order ='';
	//(isset($ord) and $wh!=''  ? $order = " ORDER BY $ord $sens" : $order = "");
	$SQL = "SELECT * FROM $table1 WHERE $table1.ID_EXERCICE ='$exercice'  $where ORDER BY DATE_APPELOFFRE DESC;";
	$result = mysql_query($SQL);
	$t['NE'] = mysql_num_rows($result);

	$i = ($page-1)*$nelt;
	$SQL = "SELECT * FROM $table1 WHERE $table1.ID_EXERCICE ='$exercice'  $where ORDER BY DATE_APPELOFFRE DESC LIMIT $i, $nelt;";
	$result = mysql_query($SQL);
	if(mysql_num_rows($result)==0) $ret .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e</td></tr>';
	$i = 0;
	$j=6;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		($row['VALIDER']==1 ? $valid = '<img src="../images/valider.gif" width="16" height="16">' : $valid = '');
		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_APPELOFFRE'].'" onClick="go(\''.$row['ID_APPELOFFRE'].'\','.$j.');"></td>
                    <td width="1%" height="22" class="text" align="center">'.$valid.'</td>
			        <td width="6%" height="22" class="text" align="center">'.$row['ID_APPELOFFRE'].'</td>
                    <td width="12%" height="22" class="text" align="center">'.frFormat($row['DATE_APPELOFFRE']).'</td>
                    <td width="50%" class="text" >'.(stripslashes($row['LIBELLE_APPELOFFRE'])).'</td>
                    <td width="5%" class="text" align="center"><input name="detail" type="button" class="button" id="detail"  value="D&eacute;tails &gt;&gt;" onClick="javascript:window.location.href=\'appels2.php?selectedTab=demands&displayName=demands&selectedLink=demands&id='.$row['ID_APPELOFFRE'].'\';"></td>
                 </tr>';
				 $i++;
				 $j++;$j++;
	}
	$t['L']=$ret;
	//mysql_close);
	return $t;
}

//Deplay appels lignes
function lignAppels($nbre, $ligne){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($ligne[$i]['idArticle']) ? $idArticle 	= $ligne[$i]['idArticle'] 	: $idArticle	='');
		(isset($ligne[$i]['designat']) 	? $designat 	= stripslashes($ligne[$i]['designat']) 	: $designat		='');
		(isset($ligne[$i]['qte']) 		? $qte 			= $ligne[$i]['qte'] 		: $qte			='');
		(isset($ligne[$i]['unite']) 	? $unite		= stripslashes($ligne[$i]['unite']) 		: $unite		='');
		(isset($ligne[$i]['prixUnit'])	? $prixUnit 	= $ligne[$i]['prixUnit'] 	: $prixUnit		='');
		(isset($ligne[$i]['mntTotal'])	? $mntTotal 	= $ligne[$i]['mntTotal'] 	: $mntTotal		='');

		$ret .= '<tr align="left" valign="middle">
                    <td class="botBorderTd" nowrap>'.$i.' - </td>
					<td class="botBorderTd"><input name="openf'.$i.'" type="button" class="button"  value="..." onClick="OpenWin(\'listearticlesaf.php?lg='.$i.'\',\'Liste\');"></td>
					<td class="botBorderTd"><input name="idArticle'.$i.'" type="text" readonly class="formStyleFree" id="idArticle'.$i.'" size="10" value="'.$idArticle.'"></td>
                    <td class="botBorderTd"><input name="designat'.$i.'" type="text" readonly class="formStyle" id="designat'.$i.'" value="'.$designat.'"></td>
                    <td class="botBorderTd"><input name="prixUnit'.$i.'" type="text" class="formStyleFree" id="prixUnit'.$i.'" size="10" value="'.$prixUnit.'" onBlur="javascript:if(document.FormAppels.qte'.$i.'.value!=\'\' && document.FormAppels.prixUnit'.$i.'.value !=\'\'){document.FormAppels.mntTotal'.$i.'.value =document.FormAppels.qte'.$i.'.value * document.FormAppels.prixUnit'.$i.'.value;}"></td>
                    <td class="botBorderTd"><input name="qte'.$i.'" type="text" class="formStyleFree" id="qte'.$i.'" size="10" value="'.$qte.'" onBlur="javascript:if(document.FormAppels.qte'.$i.'.value!=\'\' && document.FormAppels.prixUnit'.$i.'.value !=\'\'){document.FormAppels.mntTotal'.$i.'.value =document.FormAppels.qte'.$i.'.value * document.FormAppels.prixUnit'.$i.'.value;}"></td>
					<td class="botBorderTd"><input name="unite'.$i.'" type="text" readonly class="formStyleFree" id="unite'.$i.'" size="10" value="'.$unite.'"></td>
                    <td class="botBorderTd"><input name="mntTotal'.$i.'" readonly type="text" class="formStyleFree" id="mntTotal'.$i.'" size="10" value="'.$mntTotal.'"></td>
					<td class="botBorderTd"><input name="qteDispo'.$i.'" type="hidden" class="formStyleFree" id="qteDispo'.$i.'" size="10" value=""></td>
                  </tr>';
	}
	return $ret;
}

function lignEditAppels($data, $ligne){
	$ret = '';
	$i=1;
	foreach ($data as $key=>$val){

		$ret .= '<tr align="left" valign="middle">
                    <td class="botBorderTd" nowrap>'.$i.' - </td>
					<td class="botBorderTd"><input name="delete'.$i.'" type="button" class="button" title="Supprimer" value=" X " onClick="msgSupprLigne(\''.$val['idArticle'].'\','.$i.');"></td>
					<td class="botBorderTd"><input name="openf'.$i.'" type="button" class="button"  value="..." onClick="OpenWin(\'listearticlesaf1.php?lg='.$i.'\',\'Liste\');"></td>
					<td class="botBorderTd"><input name="idArticle'.$i.'" type="text" readonly class="formStyleFree" id="idArticle'.$i.'" size="10" value="'.$val['idArticle'].'">
					<input name="oldArticle'.$i.'" type="hidden"  class="formStyleFree" id="oldArticle'.$i.'" value="'.$val['idArticle'].'"></td>
                    <td class="botBorderTd"><input name="designat'.$i.'" type="text" readonly class="formStyle" id="designat'.$i.'" value="'.stripslashes($val['designat']).'"></td>
                    <td class="botBorderTd"><input name="prixUnit'.$i.'" type="text" class="formStyleFree" id="prixUnit'.$i.'" size="10" value="'.$val['prixUnit'].'" onBlur="javascript:if(document.AddAppelsForm.qte'.$i.'.value!=\'\' && document.AddAppelsForm.prixUnit'.$i.'.value !=\'\'){document.AddAppelsForm.mntTotal'.$i.'.value =document.AddAppelsForm.qte'.$i.'.value * document.AddAppelsForm.prixUnit'.$i.'.value;}"></td>
                    <td class="botBorderTd"><input name="qte'.$i.'" type="text" class="formStyleFree" id="qte'.$i.'" size="10" value="'.$val['qte'].'" onBlur="javascript:if(document.AddAppelsForm.qte'.$i.'.value!=\'\' && document.AddAppelsForm.prixUnit'.$i.'.value !=\'\'){document.AddAppelsForm.mntTotal'.$i.'.value =document.AddAppelsForm.qte'.$i.'.value * document.AddAppelsForm.prixUnit'.$i.'.value;}"></td>
					<td class="botBorderTd"><input name="unite'.$i.'" type="text" readonly class="formStyleFree" id="unite'.$i.'" size="10" value="'.$val['unite'].'"></td>
                    <td class="botBorderTd"><input name="mntTotal'.$i.'" readonly type="text" class="formStyleFree" id="mntTotal'.$i.'" size="10" value="'.$val['qte']*$val['prixUnit'].'"></td>
					<td class="botBorderTd"><input name="qteDispo'.$i.'" type="hidden" class="formStyleFree" id="qteDispo'.$i.'" size="10" value=""></td>
		         </tr>';
				 $i++;
	}
	return $ret;
}

// Deplay consultation ligne Appels d'offre
function lignDetAppels($arr){
	$ret = '';
	$i=1;
	foreach ($arr as $key=>$row){
		$ret .='<tr align="left" valign="middle">
				<td class="botBorderTd" nowrap>'.$i.' - </td>
				<td class="botBorderTd" nowrap><div class="ligneAll1">'.$row['idArticle'].'</div></td>
            	<td class="botBorderTd" nowrap><div class="ligneAll">'.(stripslashes($row['designat'])).'</div></td>
			    <td class="botBorderTd" nowrap><div class="ligneAll1" align="right">'.number_format($row['prixUnit'],0,',',' ').'</div></td>
            	<td class="botBorderTd" nowrap><div class="ligneAll1" align="right">'.$row['qte'].'</div></td>
				<td class="botBorderTd" nowrap><div class="ligneAll1" align="left">'.(stripslashes($row['unite'])).'</div></td>
			    <td class="botBorderTd" nowrap><div class="ligneAll1" align="right">'.number_format($row['qte']*$row['prixUnit'],0,',',' ').'</div></td>
			 </tr>';
			$i++;
	}
	return $ret;
}
function lignEtat($arr){
	$ret = '';
	$i=1;
	foreach ($arr as $key=>$row){
		$ret .='<tr align="left" valign="middle" class="botBorderTdallEtat">
				<td class="botBorderTdallEtat" nowrap>'.$i.' - </td>
				<td class="botBorderTdallEtat" class="EtatText" nowrap><div class="ligneANO">'.$row['idArticle'].'</div></td>
            	<td class="botBorderTdallEtat" class="EtatText" nowrap><div class="ligneANO">'.(stripslashes($row['designat'])).'</div></td>
			    <!-- <td class="botBorderTdallEtat" class="EtatText" nowrap><div class="ligneANO" align="right">'.number_format($row['prixUnit'],0,',',' ').'</div></td> -->
            	<td class="botBorderTdallEtat" class="EtatText" nowrap><div class="ligneANO" align="center">'.$row['qte'].'</div></td>
				<td class="botBorderTdallEtat" class="EtatText" nowrap><div class="ligneANO" align="left">'.stripslashes($row['unite']).'</div></td>
			    <!-- <td class="botBorderTdallEtat" class="EtatText" nowrap><div class="ligneANO" align="right">'.number_format($row['qte']*$row['prixUnit'],0,',',' ').'</div></td> -->
			 </tr>';
			$i++;
	}
	return $ret;
}


function lignEtatCat($arr){
	$ret = '';
	$i=1;
	foreach ($arr as $key=>$row){
		$ret .='<tr align="left" valign="middle" class="botBorderTdallEtat">
				<td class="botBorderTdallEtat" nowrap>'.$i.' - </td>
				<td class="botBorderTdallEtat" class="EtatText" nowrap><div class="ligneANO">'.$row['id'].'</div></td>
				<td class="botBorderTdallEtat" class="EtatText" nowrap><div class="ligneANO">'.$row['d'].'</div></td>
				<td class="botBorderTdallEtat" class="EtatText" nowrap><div class="ligneANO">'.$row['benef'].'</div></td>
				<td class="botBorderTdallEtat" class="EtatText" nowrap><div class="ligneANO">'.$row['idArticle'].'</div></td>
            	<td class="botBorderTdallEtat" nowrap class="EtatText" ><div class="ligneANO">'.(stripslashes($row['designat'])).'</div></td>
			    <!-- <td class="botBorderTdallEtat" class="EtatText" nowrap><div class="ligneANO" align="right">'.number_format($row['prixUnit'],0,',',' ').'</div></td> -->
            	<td class="botBorderTdallEtat" class="EtatText" nowrap><div class="ligneANO" align="center">'.$row['qte'].'</div></td>
				<td class="botBorderTdallEtat" class="EtatText" nowrap><div class="ligneANO" align="left">'.stripslashes($row['unite']).'</div></td>
			    <!-- <td class="botBorderTdallEtat" class="EtatText" nowrap><div class="ligneANO" align="right">'.number_format($row['qte']*$row['prixUnit'],0,',',' ').'</div></td> -->
			 </tr>';
			$i++;
	}
	return $ret;
}

function lignEtatCat1($arr){
	$ret = '';
	$i=1;
	foreach ($arr as $key=>$row){
		$ret .='<tr align="left" valign="middle" class="botBorderTdallEtat">
				<td class="botBorderTdallEtat" nowrap>'.$i.' - </td>
				<td class="botBorderTdallEtat" class="EtatText" nowrap><div class="ligneANO">'.$row['id'].'</div></td>
				<td class="botBorderTdallEtat" class="EtatText" nowrap><div class="ligneANO">'.$row['d'].'</div></td>
				<td class="botBorderTdallEtat" class="EtatText" nowrap><div class="ligneANO">'.$row['idArticle'].'</div></td>
            	<td class="botBorderTdallEtat" class="EtatText" nowrap><div class="ligneANO">'.(stripslashes($row['designat'])).'</div></td>
			    <!-- <td class="botBorderTdallEtat" class="EtatText" nowrap><div class="ligneANO" align="right">'.number_format($row['prixUnit'],0,',',' ').'</div></td> -->
            	<td class="botBorderTdallEtat" class="EtatText" nowrap><div class="ligneANO" align="center">'.$row['qte'].'</div></td>
				<td class="botBorderTdallEtat" class="EtatText" nowrap><div class="ligneANO" align="left">'.stripslashes($row['unite']).'</div></td>
			    <!-- <td class="botBorderTdallEtat" class="EtatText" nowrap><div class="ligneANO" align="right">'.number_format($row['qte']*$row['prixUnit'],0,',',' ').'</div></td> -->
			 </tr>';
			$i++;
	}
	return $ret;
}

//--------- BONS ENTREE -----------------------------

//Fill the consultation array() of Bon entrée
function setConsBentree($id){
	$table1 = "stocks_bon_entre";
	$table2 = "stocks_ligne_bon_entre";
	$table3 = "stocks_article";
	$exercice = $_SESSION['GL_USER']['EXERCICE'];

	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($id) ? $where = " WHERE ID_BONENTRE='$id' " : $where = "");
	$SQL = "SELECT $table1.* FROM $table1 $where;";
	$result = mysql_query($SQL);
	$row = mysql_fetch_array($result);

	//Fill session vars
	$_SESSION['CONS_BENTRE']= array(
	'idBesoin'	=> $row['ID_BONENTRE'],
	'exercice'	=> $row['ID_EXERCICE'],
	'dateAjout'	=> $row['DATE_BONENTRE'],
	'libelle'	=> $row['LIBELLE_BONENTRE'],
	);

	//SQL
	$where ='';
	(isset($id) ? $where = " WHERE ID_BONENTRE='$id' " : $where = "");
	$where .="AND $table2.ID_ARTICLE=$table3.ID_ARTICLE ";
	$SQL = "SELECT $table2.*, $table3.* FROM $table2, $table3 $where ORDER BY NUM ASC;";
	$result = mysql_query($SQL);

	//Fill session vars
	$_SESSION['CONS_BENTRE']['ligne'] =array();
	while($row = mysql_fetch_array($result)){
		array_push($_SESSION['CONS_BENTRE']['ligne'], array('idArticle'=>$row['ID_ARTICLE'], 'designat'=>$row['LIBELLE_ARTICLE'],'prixUnit'=>$row['PU_BONENTRE'], 'qte'=>$row['QTE_ENTREE'], 'unite'=>$row['UNITE']));
	}
}

//Deplay bon d'entrée lignes
function lignConBentree($wh='', $ord='', $sens='ASC',$valider='',$page=1,$nelt){
	$ret = '';
	$t = array();
	$table1 = "stocks_bon_entre";
	$exercice = $_SESSION['GL_USER']['EXERCICE'];

	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($wh) and $wh!='' ? $where = " AND $wh " : $where = "");
	(isset($valider) and $valider!='' ? $where = " AND VALIDER=$valider " : $where = "");

	$order ='';
	(isset($ord) and $ord!=''  ? $order = " $ord $sens" : $order = "");
	$SQL = "SELECT * FROM $table1 WHERE $table1.ID_EXERCICE ='$exercice' $where ORDER BY DATE_BONENTRE $order;";
	$result = mysql_query($SQL);
	$t['NE'] = mysql_num_rows($result);

	$i = ($page-1)*$nelt;
	$SQL = "SELECT * FROM $table1 WHERE $table1.ID_EXERCICE ='$exercice' $where ORDER BY DATE_BONENTRE DESC $order LIMIT $i, $nelt;";
	$result = mysql_query($SQL);
	if(mysql_num_rows($result)==0) $ret .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e</td></tr>';
	$i = 0;
	$j = 6;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		($row['VALIDER']==1 ? $valid = '<img src="../images/valider.gif" width="16" height="16">' : $valid = '');
		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_BONENTRE'].'" onClick="go(\''.$row['ID_BONENTRE'].'\','.$j.');"></td>
					<td width="2%" align="center">'.$valid.'</td>
                    <td width="6%" height="22" class="text" align="center">'.$row['ID_BONENTRE'].'</td>
                    <td width="12%" height="22" class="text" align="center">'.frFormat($row['DATE_BONENTRE']).'</td>
                    <td width="50%" class="text" >'.(stripslashes($row['LIBELLE_BONENTRE'])).'</td>
                    <td width="5%" class="text" align="center"><input name="detail" type="button" class="button" id="detail"  value="D&eacute;tails &gt;&gt;" onClick="javascript:window.location.href=\'bonentree2.php?selectedTab=inputs&displayName=inputs&selectedLink=inputs&id='.$row['ID_BONENTRE'].'\';"></td>
                 </tr>';
				 $i++;
				 $j++;$j++;
	}
	$t['L']=$ret;
	//mysql_close);
	return $t;
}

function lignSearchBentree($cr1,$cr2,$cr3,$valider,$page=1,$nelt){
//$xreference,$xdateAjout,$libelle);
	$ret = '';
	$t = array();
	$table1 = "stocks_bon_entre";
	$exercice = $_SESSION['GL_USER']['EXERCICE'];

	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($cr1) and $cr1!='' ? $where .= " ID_BONENTRE LIKE '$cr1' AND " : $where .= "");
	(isset($cr2) and $cr2!='' ? $where .= " DATE_BONENTRE = '".mysqlFormat($cr2)."' AND " : $where .= "");
	(isset($cr3) and $cr3!='' ? $where .= " LIBELLE_BONENTRE LIKE '%$cr3%' AND " : $where .= "");

	if($where != '') $where = substr(" AND $where",0,strlen(" AND $where")-4);

	$order ='';
	(isset($ord) and $ord!=''  ? $order = " $ord $sens" : $order = "");
	$SQL = "SELECT * FROM $table1 WHERE $table1.ID_EXERCICE ='$exercice' $where ORDER BY DATE_BONENTRE $order;";
	$result = mysql_query($SQL);
	$t['NE'] = mysql_num_rows($result);

	$i = ($page-1)*$nelt;
	$SQL = "SELECT * FROM $table1 WHERE $table1.ID_EXERCICE ='$exercice' $where ORDER BY DATE_BONENTRE $order LIMIT $i, $nelt;";
	$result = mysql_query($SQL);
	if(mysql_num_rows($result)==0) $ret .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e</td></tr>';
	$i = 0;
	$j=6;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		($row['VALIDER']==1 ? $valid = '<img src="../images/valider.gif" width="16" height="16">' : $valid = '');
		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_BONENTRE'].'" onClick="go(\''.$row['ID_BONENTRE'].'\','.$j.');"></td>
                    <td width="2%" align="center">'.$valid.'</td>
					<td width="6%" height="22" class="text" align="center">'.$row['ID_BONENTRE'].'</td>
                    <td width="12%" height="22" class="text" align="center">'.frFormat($row['DATE_BONENTRE']).'</td>
                    <td width="50%" class="text" >'.(stripslashes($row['LIBELLE_BONENTRE'])).'</td>
                    <td width="5%" class="text" align="center"><input name="detail" type="button" class="button" id="detail"  value="D&eacute;tails &gt;&gt;" onClick="javascript:window.location.href=\'bonentree2.php?selectedTab=inputs&displayName=inputs&selectedLink=inputs&id='.$row['ID_BONENTRE'].'\';"></td>
                 </tr>';
				 $i++;
				 $j++;$j++;
	}
	$t['L']=$ret;
	//mysql_close);
	return $t;
}

//Deplay bon entrée lignes
function lignBonentree($nbre, $ligne){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($ligne[$i]['idArticle']) ? $idArticle 	= $ligne[$i]['idArticle'] 	: $idArticle	='');
		(isset($ligne[$i]['designat']) 	? $designat 	= $ligne[$i]['designat'] 	: $designat		='');
		(isset($ligne[$i]['qte']) 		? $qte 			= $ligne[$i]['qte'] 		: $qte			='');
		(isset($ligne[$i]['unite']) 	? $unite 		= $ligne[$i]['unite'] 		: $unite		='');
		(isset($ligne[$i]['prixUnit'])	? $prixUnit 	= $ligne[$i]['prixUnit'] 	: $prixUnit		='');
		(isset($ligne[$i]['mntTotal'])	? $mntTotal 	= $ligne[$i]['mntTotal'] 	: $mntTotal		='');

		$ret .= '<tr align="left" valign="middle">
                    <td class="botBorderTd" nowrap>'.$i.' - </td>
					<td class="botBorderTd"><input name="openf'.$i.'" type="button" class="button"  value="..." onClick="OpenWin(\'listearticlesbe.php?lg='.$i.'\',\'Liste\');"></td>
					<td class="botBorderTd"><input name="idArticle'.$i.'" type="text" readonly class="formStyleFree" id="idArticle'.$i.'" size="10" value="'.$idArticle.'"></td>
                    <td class="botBorderTd"><input name="designat'.$i.'" type="text" readonly class="formStyle" id="designat'.$i.'" value="'.stripslashes($designat).'"></td>
                    <td class="botBorderTd"><input name="prixUnit'.$i.'" type="text" class="formStyleFree" id="prixUnit'.$i.'" size="10" value="'.$prixUnit.'" onBlur="javascript:if(document.FormBonentree.qte'.$i.'.value!=\'\' && document.FormBonentree.prixUnit'.$i.'.value !=\'\'){document.FormBonentree.mntTotal'.$i.'.value =document.FormBonentree.qte'.$i.'.value * document.FormBonentree.prixUnit'.$i.'.value;}"></td>
                    <td class="botBorderTd"><input name="qte'.$i.'" type="text" class="formStyleFree" id="qte'.$i.'" size="10" value="'.$qte.'" onBlur="javascript:if(document.FormBonentree.qte'.$i.'.value!=\'\' && document.FormBonentree.prixUnit'.$i.'.value !=\'\'){document.FormBonentree.mntTotal'.$i.'.value =document.FormBonentree.qte'.$i.'.value * document.FormBonentree.prixUnit'.$i.'.value;}"></td>
					<td class="botBorderTd"><input name="unite'.$i.'" type="text" readonly class="formStyleFree" id="unite'.$i.'" size="10" value="'.$unite.'"></td>
                    <td class="botBorderTd"><input name="mntTotal'.$i.'" readonly type="text" class="formStyleFree" id="mntTotal'.$i.'" size="10" value="'.$mntTotal.'"></td>
					<td class="botBorderTd"><input name="qteDispo'.$i.'" type="hidden" class="formStyleFree" id="qteDispo'.$i.'" size="10" value=""></td>
                 </tr>';
	}
	return $ret;
}


function lignEditBentree($data, $ligne){
	$ret = '';
	$i=1;
	foreach ($data as $key=>$val){

		$ret .= '<tr align="left" valign="middle">
                    <td class="botBorderTd" nowrap>'.$i.' - </td>
					<td class="botBorderTd"><input name="delete'.$i.'" type="button" class="button" title="Supprimer" value=" X " onClick="msgSupprLigne(\''.$val['idArticle'].'\','.$i.');"></td>
					<td class="botBorderTd"><input name="openf'.$i.'" type="button" class="button"  value="..." onClick="OpenWin(\'listearticlesbe1.php?lg='.$i.'\',\'Liste\');"></td>
					<td class="botBorderTd"><input name="idArticle'.$i.'" type="text" readonly class="formStyleFree" id="idArticle'.$i.'" size="10" value="'.$val['idArticle'].'">
					<input name="oldArticle'.$i.'" type="hidden" class="formStyleFree" id="oldArticle'.$i.'" size="10" value="'.$val['oldidArticle'].'"></td>
                    <td class="botBorderTd"><input name="designat'.$i.'" type="text" readonly class="formStyle" id="designat'.$i.'" value="'.stripslashes($val['designat']).'"></td>
                    <td class="botBorderTd"><input name="prixUnit'.$i.'" type="text" class="formStyleFree" id="prixUnit'.$i.'" size="10" value="'.$val['prixUnit'].'" onBlur="javascript:if(document.AddBentreForm.qte'.$i.'.value!=\'\' && document.AddBentreForm.prixUnit'.$i.'.value !=\'\'){document.AddBentreForm.mntTotal'.$i.'.value =document.AddBentreForm.qte'.$i.'.value * document.AddBentreForm.prixUnit'.$i.'.value;}"></td>
                    <td class="botBorderTd"><input name="qte'.$i.'" type="text" class="formStyleFree" id="qte'.$i.'" size="10" value="'.$val['qte'].'" onBlur="javascript:if(document.AddBentreForm.qte'.$i.'.value!=\'\' && document.AddBentreForm.prixUnit'.$i.'.value !=\'\'){document.AddBentreForm.mntTotal'.$i.'.value =document.AddBentreForm.qte'.$i.'.value * document.AddBentreForm.prixUnit'.$i.'.value;}"></td>
					<td class="botBorderTd"><input name="unite'.$i.'" type="text" readonly class="formStyleFree" id="unite'.$i.'" size="10" value="'.stripslashes($val['unite']).'"></td>
                    <td class="botBorderTd"><input name="mntTotal'.$i.'" readonly type="text" class="formStyleFree" id="mntTotal'.$i.'" size="10" value="'.$val['qte']*$val['prixUnit'].'"></td>
					<td class="botBorderTd"><input name="qteDispo'.$i.'" type="hidden" class="formStyleFree" id="qteDispo'.$i.'" size="10" value=""></td>
                 </tr>';
				 $i++;
	}
	return $ret;
}

function lignConValBentre($wh='', $ord='', $sens='ASC',$page=1,$nelt){
	$ret = '';
	$t = array();
	$table1 = "stocks_bon_entre";
	$exercice = $_SESSION['GL_USER']['EXERCICE'];

	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($wh) and $wh!='' ? $where = " AND $wh " : $where = "");

	$order ='';
	(isset($ord) and $ord!=''  ? $order = " $ord $sens" : $order = "");
	$SQL = "SELECT * FROM $table1 WHERE $table1.ID_EXERCICE ='$exercice' AND $table1.VALIDER=0 $where ORDER BY DATE_BONENTRE $order;";
	$result = mysql_query($SQL);
	$t['NE'] = mysql_num_rows($result);

	$i = ($page-1)*$nelt;
	$SQL = "SELECT * FROM $table1 WHERE $table1.ID_EXERCICE ='$exercice' AND $table1.VALIDER=0 $where ORDER BY DATE_BONENTRE $order LIMIT $i, $nelt;";
	$result = mysql_query($SQL);
	if(mysql_num_rows($result)==0) $ret .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e</td></tr>';
	$i = 0;
	$j = 4;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		($row['VALIDER']==1 ? $valid = '<img src="../images/valider.gif" width="16" height="16">' : $valid = '');
		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_BONENTRE'].'" onClick="go(\''.$row['ID_BONENTRE'].'\','.$j.');"></td>
					<td width="2%" align="center">'.$valid.'</td>
                    <td width="6%" height="22" class="text" align="center">'.$row['ID_BONENTRE'].'</td>
                    <td width="12%" height="22" class="text" align="center">'.frFormat($row['DATE_BONENTRE']).'</td>
                    <td width="50%" class="text" >'.(stripcslashes($row['LIBELLE_BONENTRE'])).'</td>
					<td width="5%" class="text" align="center"><input name="detail" type="button" class="button" id="detail"  value="D&eacute;tails &gt;&gt;" onClick="javascript:window.location.href=\'validbonentree1.php?selectedTab=inputs&displayName=inputs&selectedLink=inputs&id='.$row['ID_BONENTRE'].'\';"></td>

                 </tr>';
				 $i++;
				 $j++;$j++;
	}
	$t['L']=$ret;
	//mysql_close);
	return $t;
}

function lignSearchValBentre($cr1,$cr2,$cr3,$page=1,$nelt){
//$xreference,$xdateAjout,$libelle);
	$ret = '';
	$t = array();
	$table1 = "stocks_bon_entre";
	$exercice = $_SESSION['GL_USER']['EXERCICE'];

	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($cr1) and $cr1!='' ? $where .= " ID_BONENTRE LIKE '$cr1' AND " : $where .= "");
	(isset($cr2) and $cr2!='' ? $where .= " DATE_BONENTRE = '$cr2' AND " : $where .= "");
	(isset($cr3) and $cr3!='' ? $where .= " LIBELLE_BONENTRE LIKE '%$cr3%' AND " : $where .= "");

	if($where != '') $where = substr(" AND $where",0,strlen(" AND $where")-4);

	$order ='';
	(isset($ord) and $wh!=''  ? $ord = " $ord $sens" : $order = "");
	$SQL = "SELECT * FROM $table1 WHERE $table1.ID_EXERCICE ='$exercice' AND $table1.VALIDER=0 $where ORDER BY DATE_BONENTRE $order;";
	$result = mysql_query($SQL);
	$t['NE'] = mysql_num_rows($result);

	$i = ($page-1)*$nelt;
	$SQL = "SELECT * FROM $table1 WHERE $table1.ID_EXERCICE ='$exercice' AND $table1.VALIDER=0 $where ORDER BY DATE_BONENTRE $order LIMIT $i, $nelt;";
	$result = mysql_query($SQL);
	if(mysql_num_rows($result)==0) $ret .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e</td></tr>';
	$i = 0;
	$j=5;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		($row['VALIDER']==1 ? $valid = '<img src="../images/valider.gif" width="16" height="16">' : $valid = '');
		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_BONENTRE'].'" onClick="go(\''.$row['ID_BONENTRE'].'\','.$j.');"></td>
                    <td width="2%" align="center">'.$valid.'</td>
					<td width="6%" height="22" class="text" align="center">'.$row['ID_BONENTRE'].'</td>
                    <td width="12%" height="22" class="text" align="center">'.frFormat($row['DATE_BONENTRE']).'</td>
                    <td width="50%" class="text" >'.($row['LIBELLE_BONENTRE']).'</td>
                    </tr>';
				 $i++;
				 $j++;$j++;
	}
	$t['L']=$ret;
	//mysql_close);
	return $t;
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
					<td class="botBorderTd"><input name="openf'.$i.'" type="button" class="button" title="<?php echo getlang(385); ?>" value="..." onClick="OpenWin(\'listearticlesinv1.php?lg='.$i.'\',\'Liste\');"></td>
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
	'service'	=> $row['ID_BENEFICIAIRE'].' - '.nomBeneficiaire($row['ID_BENEFICIAIRE']),
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
		$benef = nomBeneficiaire($row['ID_BENEFICIAIRE']);
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
	(isset($cr4) and $cr4!='' ? $where .= " AND ID_BENEFICIAIRE = '$cr4'" : $where .= "");
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
		$benef = nomBeneficiaire($row['ID_BENEFICIAIRE']);
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
		$benef = nomBeneficiaire($row['ID_BENEFICIAIRE']);
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
	(isset($cr4) and $cr4!='' ? $where .= " AND ID_BENEFICIAIRE = '$cr4'" : $where .= "");
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
		$benef = nomBeneficiaire($row['ID_BENEFICIAIRE']);
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
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY $table1.ID_BENEFICIAIRE ASC");
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
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_BENEFICIAIRE'].'" onClick="go('.$row['ID_BENEFICIAIRE'].','.$j.');"></td>
                    <td width="6%" height="22" class="text" align="center">'.$row['ID_BENEFICIAIRE'].'</td>
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
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY $table1.ID_BENEFICIAIRE ASC");
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
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_BENEFICIAIRE'].'" onClick="go('.$row['ID_BENEFICIAIRE'].','.$j.');"></td>
                    <td width="6%" height="22" class="text" align="center">'.$row['ID_BENEFICIAIRE'].'</td>
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
					<!-- <td width="12%" class="text" align="center"><input name="detail" type="button" class="button" id="detail"  value="Cl&ecirc;turer &gt;&gt;" onClick="javascript:window.location.href=\'cloture2.php?selectedTab=pareters&id='.$row['ID_EXERCICE'].'\';"></td> -->
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
                  <td width="200" align=right valign="middle" class="text"><?php echo getlang(171); ?>&nbsp;:&nbsp;</td>
                  <td align="left" class="text"><div class="ligneAll" nowrap>'.$row['groupe'].'</div></td>
                </tr>
                <tr align="left" valign="top">
                  <td width="200" align=right valign="middle" class="text"><?php echo getlang(103); ?> d\'utilisateur&nbsp;:&nbsp;</td>
                  <td align="left" class="text"><div class="ligneAll" nowrap>'.$row['login'].'</div></td>
                </tr>
                <tr align="left" valign="top">
                  <td width="200" align=right valign="middle" class="text"><?php echo getlang(97); ?>&nbsp;:&nbsp;</td>
                  <td class="text"><div class="ligneAll" nowrap>*********</div></td>
                </tr>
                <tr align="left" valign="top">
                  <td width="200" align=right valign="middle" class="text"><?php echo getlang(39); ?>&nbsp;:&nbsp;</td>
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
                    <td width="15%" class="text" align="center"><input name="detail" type="button" class="button" id="detail"  value="D&eacute;tails &gt;&gt;" onClick="javascript:window.location.href=\'personnes2.php?selectedTab=pareters&displayName=parameters&id='.$row['NUM_MATRICULE'].'\';"></td>
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
                    <td width="15%" class="text" align="center"><input name="detail" type="button" class="button" id="detail"  value="D&eacute;tails &gt;&gt;" onClick="javascript:window.location.href=\'personnes2.php?selectedTab=pareters&id='.$row['NUM_MATRICULE'].'\';"></td>
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
					<td width="10%" class="text" align="left"><input name="detail" type="button" class="button" id="detail"  value="D&eacute;tails &gt;&gt;" onClick="javascript:window.location.href=\'groups2.php?selectedTab=pareters&id='.$row['ID_GROUPE'].'\';"></td>
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
					<td width="10%" class="text" align="left"><input name="detail" type="button" class="button" id="detail"  value="D&eacute;tails &gt;&gt;" onClick="javascript:window.location.href=\'groups2.php?selectedTab=pareters&id='.$row['ID_GROUPE'].'\';"></td>
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



//--------- LES ETATS ET IMPRIMABLES------

function setEtatAppels($id){
		$table1 = "stocks_appel_offre";
		$table2 = "stocks_ligne_appeloffre";
		$table3 = "stocks_article";
		$exercice = $_SESSION['GL_USER']['EXERCICE'];

		//Connection to Database server
		mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

		//Select Database
		mysql_select_db(DB) or header('location:errorPage.php&code=');

		//SQL
		$SQL = "SELECT * FROM $table1 LEFT JOIN $table2 ON ($table1.ID_APPELOFFRE=$table2.ID_APPELOFFRE) ";
		$SQL .="LEFT JOIN $table3 ON ($table2.ID_ARTICLE=$table3.ID_ARTICLE) WHERE $table1.ID_APPELOFFRE='$id'";
		$result = mysql_query($SQL) or header('location:errorPage.php&code=');

		$SQL = "SELECT * FROM $table1 WHERE $table1.ID_APPELOFFRE='$id';";
		$ptr = mysql_query($SQL) or header('location:errorPage.php&code=');

		$_SESSION['ETAT_APPEL']['libelle'] = "APPEL D'OFFRE";
		$_SESSION['ETAT_APPEL']['critere'] = '';
		$_SESSION['ETAT_APPEL']['data'] = array();

		while($row = mysql_fetch_array($ptr)){
			$fils = array();
			while($row1 = mysql_fetch_array($result)){
				if($row['ID_APPELOFFRE']==$row1['ID_APPELOFFRE']){
					array_push($fils,
					array('id'=>$row1['ID_APPELOFFRE'],
					'idArticle'=>$row1['ID_ARTICLE'],
					'designat'=>stripslashes($row1['LIBELLE_ARTICLE']),
					'qte'=>$row1['QTE_CDE'],
					'prixUnit'=>$row1['PU_APPELOFFRE'],
					'unite'=>$row1['UNITE']));
				}
			}
			$pere = array('id'=>$row['ID_APPELOFFRE'],'exercice'=>libelleExercice($row['ID_EXERCICE']), 'd'=>frFormat($row['DATE_APPELOFFRE']), 'lib'=>stripslashes($row['LIBELLE_APPELOFFRE']), 'fils'=>$fils);
			array_push($_SESSION['ETAT_APPEL']['data'],$pere);
			mysql_data_seek($result,0);
		}

		//mysql_close);
}

function setEtatBonSorties($id){
		$table1 = "stocks_bon_sortie";
		$table2 = "stocks_ligne_bon_sortie";
		$table3 = "stocks_article";
		$table4 = "stocks_beneficiaire";
		$exercice = $_SESSION['GL_USER']['EXERCICE'];

		//Connection to Database server
		mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

		//Select Database
		mysql_select_db(DB) or header('location:errorPage.php&code=');

		//WHERE
		$Where = " WHERE $table1.ID_EXERCICE=$exercice AND $table1.ID_BONSORTIE=$table2.ID_BONSORTIE AND
		$table2.ID_ARTICLE=$table3.ID_ARTICLE AND
		$table1.ID_BENEFICIAIRE=$table4.ID_BENEFICIAIRE AND $table1.ID_BONSORTIE='$id'";

		//if($Where != '') $Where = substr($Where,0,strlen($Where)-4);
		//SQL
		$SQL = "SELECT * FROM $table1, $table2, $table3, $table4 $Where;";
		$result = mysql_query($SQL) or header('location:errorPage.php&code=');

		$SQL = "SELECT * FROM $table1, $table2, $table3, $table4 $Where GROUP BY $table1.ID_BONSORTIE;";
		$ptr = mysql_query($SQL) or header('location:errorPage.php&code=');


		$_SESSION['ETAT_BSORTIE']['libelle'] = ("Bon de sortie n° $id");
		$_SESSION['ETAT_BSORTIE']['critere'] = '';
		$_SESSION['ETAT_BSORTIE']['data'] = array();

		while($row = mysql_fetch_array($ptr)){
			$fils = array();
			while($row1 = mysql_fetch_array($result)){
				if($row['ID_BONSORTIE']==$row1['ID_BONSORTIE']){
					array_push($fils,
					array('id'=>$row1['ID_BONSORTIE'],
					'idArticle'=>$row1['ID_ARTICLE'],
					'designat'=>stripslashes($row1['LIBELLE_ARTICLE']),
					'qte'=>$row1['QTE_SORTIE'],
					'prixUnit'=>$row1['PU_SORTIE'],
					'unite'=>stripslashes($row1['UNITE'])));
				}
			}
			$benefic = stripslashes(nomBeneficiaire($row['ID_BENEFICIAIRE']));
			if($row['AUTRE']!='') $benefic .="(".$row['AUTRE'].")";
			$_SESSION['ETAT_BSORTIE']['benef'] = $benefic;
			$pere = array('id'=>$row['ID_BONSORTIE'], 'd'=>frFormat($row['DATE_BONSORTIE']), 'lib'=>stripslashes($row['LIBELLE_BONSORTIE']), 'benef'=>stripslashes($benefic), 'fils'=>$fils);
			array_push($_SESSION['ETAT_BSORTIE']['data'],$pere);
			mysql_data_seek($result,0);
		}

		//mysql_close);
}

function setEtatBonEntrees($id){
		$table1 = "stocks_bon_entre";
		$table2 = "stocks_ligne_bon_entre";
		$table3 = "stocks_article";
		$exercice = $_SESSION['GL_USER']['EXERCICE'];
		//Connection to Database server
		mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

		//Select Database
		mysql_select_db(DB) or header('location:errorPage.php&code=');

		//WHERE
		$Where = " WHERE $table1.ID_EXERCICE=$exercice AND $table1.ID_BONENTRE=$table2.ID_BONENTRE AND $table2.ID_ARTICLE=$table3.ID_ARTICLE AND $table1.ID_BONENTRE='$id'";

		//SQL
		$SQL = "SELECT * FROM $table1, $table2, $table3 $Where;";
		$result = mysql_query($SQL) or header('location:errorPage.php&code=');

		$SQL = "SELECT * FROM $table1, $table2, $table3 $Where GROUP BY $table1.ID_BONENTRE;";
		$ptr = mysql_query($SQL) or header('location:errorPage.php&code=');

		//LIBELLE

		$_SESSION['ETAT_BENTRE']['libelle'] = "Bon d'entrée n° $id";
		$_SESSION['ETAT_BENTRE']['critere'] = '';
		$_SESSION['ETAT_BENTRE']['data'] = array();

		while($row = mysql_fetch_array($ptr)){
			$fils = array();
			while($row1 = mysql_fetch_array($result)){
				if($row['ID_BONENTRE']==$row1['ID_BONENTRE']){
					array_push($fils,
					array('id'=>$row1['ID_BONENTRE'],
					'idArticle'=>$row1['ID_ARTICLE'],
					'designat'=>$row1['LIBELLE_ARTICLE'],
					'qte'=>$row1['QTE_ENTREE'],
					'prixUnit'=>$row1['PU_BONENTRE'],
					'unite'=>$row1['UNITE']));
				}
			}
			$pere = array('id'=>$row['ID_BONENTRE'], 'd'=>frFormat($row['DATE_BONENTRE']), 'lib'=>$row['LIBELLE_BONENTRE'], 'fils'=>$fils);
			array_push($_SESSION['ETAT_BENTRE']['data'],$pere);
			mysql_data_seek($result,0);
		}

		//mysql_close);
}

function setEtatBesoins($id){

		$table1 = "stocks_besoin";
		$table2 = "stocks_ligne_besoin";
		$table3 = "stocks_article";
		$exercice = $_SESSION['GL_USER']['EXERCICE'];

		//Connection to Database server
		mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

		//Select Database
		mysql_select_db(DB) or header('location:errorPage.php&code=');

		//SQL
		$SQL  = "SELECT * FROM $table1 LEFT JOIN $table2 ON ($table1.ID_BESOIN=$table2.ID_BESOIN) ";
		$SQL .= "LEFT JOIN $table3 ON ($table2.ID_ARTICLE=$table3.ID_ARTICLE) WHERE $table1.ID_BESOIN='$id' ORDER BY $table3.LIBELLE_ARTICLE ASC;";
		$result = mysql_query($SQL) or header('location:errorPage.php&code=');

		$SQL  = "SELECT * FROM $table1 WHERE $table1.ID_BESOIN='$id';";
		$ptr = mysql_query($SQL) or header('location:errorPage.php&code=');

		//LIBELLE

		$_SESSION['ETAT_BESOIN']['libelle'] = "BESOIN"; // n° $id
		$_SESSION['ETAT_BESOIN']['critere'] = '';
		$_SESSION['ETAT_BESOIN']['data'] = array();

		while($row = mysql_fetch_array($ptr)){
			$fils = array();
			while($row1 = mysql_fetch_array($result)){
				if($row['ID_BESOIN']==$row1['ID_BESOIN']){
					array_push($fils,
					array('id'=>$row1['ID_BESOIN'],
					'idArticle'=>$row1['ID_ARTICLE'],
					'designat'=>stripslashes($row1['LIBELLE_ARTICLE']),
					'qte'=>$row1['QTE_DDE'],
					'prixUnit'=>$row1['PU_BESION'],
					'unite'=>stripslashes($row1['UNITE'])));
				}
			}
			$pere = array('id'=>$row['ID_BESOIN'],'exercice'=>libelleExercice($row['ID_EXERCICE']), 'd'=>$row['DATE_BESOIN'], 'benef'=>stripslashes(nomBeneficiaire($row['ID_BENEFICIAIRE'])), 'lib'=>stripslashes($row['LIBELLE_BESOIN']), 'fils'=>$fils);
			array_push($_SESSION['ETAT_BESOIN']['data'],$pere);
			mysql_data_seek($result,0);
		}

		//mysql_close);
}

function setEtatInventaires($id){
		$table1 = "stocks_inventaire";
		$table2 = "stocks_ligne_inventaire";
		$table3 = "stocks_article";
		$exercice = $_SESSION['GL_USER']['EXERCICE'];


		//Connection to Database server
		$idCon = mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

		//Select Database
		mysql_select_db(DB,$idCon) or header('location:errorPage.php&code=');

		//WHERE
		$Where = " WHERE $table1.ID_EXERCICE=$exercice AND $table1.ID_INVENTAIRE=$table2.ID_INVENTAIRE AND $table2.ID_ARTICLE=$table3.ID_ARTICLE AND $table1.ID_INVENTAIRE='$id'";
		//SQL
		$SQL = "SELECT * FROM $table1, $table2, $table3 $Where;";
		$result = mysql_query($SQL,$idCon) or header('location:errorPage.php&code=');

		$SQL = "SELECT * FROM $table1, $table2, $table3 $Where GROUP BY $table1.ID_INVENTAIRE;";
		$ptr = mysql_query($SQL,$idCon) or header('location:errorPage.php&code=');

		//LIBELLE
		$_SESSION['ETAT_INVENTAIRE']['libelle'] = "Appel d'offre n° $id";
		$_SESSION['ETAT_INVENTAIRE']['critere'] = '';
		$_SESSION['ETAT_INVENTAIRE']['data'] = array();

		while($row = mysql_fetch_array($ptr)){
			$fils = array();
			while($row1 = mysql_fetch_array($result)){
				if($row['ID_INVENTAIRE']==$row1['ID_INVENTAIRE']){
					array_push($fils,
					array('id'=>$row1['ID_INVENTAIRE'],
					'idArticle'=>$row1['ID_ARTICLE'],
					'designat'=>$row1['LIBELLE_ARTICLE'],
					'qte'=>$row1['TYPE_INVENTAIRE'].$row1['QTE_INVENTAIRE'],
					'prixUnit'=>$row1['PU_INVENTAIRE'],
					'unite'=>$row1['UNITE']));
				}
			}
			$pere = array('id'=>$row['ID_INVENTAIRE'], 'd'=>frFormat($row['DATE_INVENTAIRE']), 'lib'=>$row['LIBELLE_INVENTAIRE'], 'fils'=>$fils);
			array_push($_SESSION['ETAT_INVENTAIRE']['data'],$pere);
			mysql_data_seek($result,0);
		}

	//mysql_close($idCon);
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