<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */

//--------- UNITES -----------------------------

//Nombre de ligne retourner
function nombreTypeFournisseur($where=''){
	$sql = "SELECT * FROM typefournisseur $where;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query
	return $query->rowCount();
}

function ligneConTypeFournisseur($wh='', $ord='', $sens='ASC', $page=1, $nelt){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	$returnHTML = '';
	$returnTble = array();//HTML, nbreTotal,

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY TYPEFOUR_NOM ASC");
	//Nombre d'éléments
	$returnTble['NE'] = nombreTypeFournisseur($where);
	if($returnTble['NE']>0){
		//Calcule des limites
		$i = ($page-1)*$nelt;
		$sql = "SELECT * FROM typefournisseur $where $order LIMIT $i, $nelt;";
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

		$i = 0;
		$j=6;
		while($row = 	$query->fetch(PDO::FETCH_ASSOC)){
			($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
			//Is use
			$Fournisseur = isUseNow('CODE_TYPEFOUR', 'fournisseur', "WHERE CODE_TYPEFOUR LIKE '".$row['CODE_TYPEFOUR']."'");
			(($Fournisseur) > 0 ? $Use = 1 : $Use = 0);

			$returnHTML .= '
			<tr align="left" valign="middle" class="'.$col.'">
	            <td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['CODE_TYPEFOUR'].'@'.$Use.'"></td>
                <td width="10%" height="22" class="text" align="left">'.$row['CODE_TYPEFOUR'].'</td>
                <td width="30%" class="text" >'.(stripslashes($row['TYPEFOUR_NOM'])).'</td>
            </tr>';
			$i++;
			$j++;
		}
	}
	else {
	if($ilang=='1' && $ilang!='') {$returnHTML .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e...</td></tr>';}
	if($ilang=='2' && $ilang!='') {$returnHTML .= '<tr><td colspan="4" class="text">No data...</td></tr>';}
	if($ilang=='3' && $ilang!='') {$returnHTML .= '<tr><td colspan="4" class="text">Nenhum dado...</td></tr>';}
	}

	$returnTble['L']=$returnHTML;
	return $returnTble;
}

function ligneEtatTypeFournisseur($wh='', $ord='', $sens='ASC'){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	$returnHTML = '';

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Order condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY TYPEFOUR_NOM ASC");

	//Nombre d'éléments
	$nbre = nombreTypeFournisseur($where);
	if($nbre>0){
		$sql = "SELECT * FROM typefournisseur $where $order;";
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

		$i = 0;
		while($row = 	$query->fetch(PDO::FETCH_ASSOC)){

			$returnHTML .= '
			<tr align="left" valign="middle">
	            <td height="22" class="botBorderTdall" align="left">'.$row['CODE_TYPEFOUR'].'&nbsp;</td>
                <td class="botBorderTdall" >'.(stripslashes($row['TYPEFOUR_NOM'])).'&nbsp;</td>
            </tr>';
			$i++;
		}
	}
	else {
	if($ilang=='1' && $ilang!='') {$returnHTML .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e...</td></tr>';}
	if($ilang=='2' && $ilang!='') {$returnHTML .= '<tr><td colspan="4" class="text">No data...</td></tr>';}
	if($ilang=='3' && $ilang!='') {$returnHTML .= '<tr><td colspan="4" class="text">Nenhum dado...</td></tr>';}
	}
	return $returnHTML;
}

?>