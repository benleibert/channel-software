<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */

//--------- UNITES -----------------------------

//Nombre de ligne retourner
function nombreLog($where=''){
	$sql  = "SELECT * FROM logs $where;";
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

function ligneConLog($wh='', $ord='', $sens='ASC', $page=1, $nelt){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	$returnHTML = '';
	$returnTble = array();//HTML, nbreTotal,

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY LOG_DATE DESC");
	//Nombre d'éléments
	$returnTble['NE'] = nombreLog($where);
	if($returnTble['NE']>0){
		//Calcule des limites
		$i = ($page-1)*$nelt;
		$sql  = "SELECT * FROM logs  $where $order LIMIT $i, $nelt;";
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
			$d1 = frFormat($row['LOG_DATE']); //Return  $ret = array(); // DFR, TFR

			$returnHTML .= '
			<tr align="left" valign="middle" class="'.$col.'">
	            <td><input type="checkbox" name="rowSelection[]" value="'.$row['ID_LOG'].'"></td>
                <td class="text" >'.(stripslashes($row['LOGIN'])).'&nbsp;</td>
                <td class="text" >'.(stripslashes(getPersonnelName($row['MLLE']))).'&nbsp;</td>
                <td class="text" >'.(stripslashes($d1['DFR'])).'&nbsp;</td>
                <td class="text" >'.(stripslashes($d1['TFR'])).'&nbsp;</td>
                <td class="text" >'.(stripslashes($row['LOG_DESCRIP'])).'&nbsp;</td>
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


function ligneEtatListeLog($wh=''){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	$returnHTML = '';

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY LOG_DATE DESC");

	//Nombre d'éléments
	$nbre = nombreLog($where);
	if($nbre>0){
		//Calcule des limites
		$sql  = "SELECT * FROM logs  $where $order ";
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

		$i = 1;
		while($row = 	$query->fetch(PDO::FETCH_ASSOC)){
			$d1 = frFormat($row['LOG_DATE']); //Return  $ret = array(); // DFR, TFR

			$returnHTML .= '
			<tr align="left" valign="middle">
	            <td class="botBorderTdall" align="center">'.$i.'</td>
                <td class="botBorderTdall">'.(stripslashes($row['LOGIN'])).'&nbsp;</td>
                <td class="botBorderTdall">'.(stripslashes(getPersonnelName($row['MLLE']))).'&nbsp;</td>
                <td class="botBorderTdall">'.(stripslashes($d1['DFR'])).'&nbsp;</td>
                <td class="botBorderTdall">'.(stripslashes($d1['TFR'])).'&nbsp;</td>
                <td class="botBorderTdall">'.(stripslashes($row['LOG_DESCRIP'])).'&nbsp;</td>
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