<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */

//--------- UNITES -----------------------------

//Nombre de ligne retourner
function nombrePersonnel($where=''){
	$sql = "SELECT * FROM personnel INNER JOIN magasin ON (personnel.CODE_MAGASIN=magasin.CODE_MAGASIN) $where;";
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

function ligneConPersonnel($wh='', $ord='', $sens='ASC', $page=1, $nelt){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	$returnHTML = '';
	$returnTble = array();//HTML, nbreTotal,

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY NUM_MLLE  ASC");
	//Nombre d'éléments
	$returnTble['NE'] = nombrePersonnel($where);
	if($returnTble['NE']>0){
		//Calcule des limites
		$i = ($page-1)*$nelt;
		$sql = "SELECT * FROM personnel INNER JOIN magasin ON (personnel.CODE_MAGASIN=magasin.CODE_MAGASIN) $where $order LIMIT $i, $nelt;";
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
			$compte = isUseNow('NUM_MLLE', 'compte', "WHERE NUM_MLLE LIKE '".$row['NUM_MLLE']."'");
			(($compte) > 0 ? $Use = 1 : $Use = 0);

			$returnHTML .= '
			<tr align="left" valign="middle" class="'.$col.'">
	            <td><input type="checkbox" name="rowSelection[]" value="'.$row['NUM_MLLE'].'@'.$Use.'"></td>
                <td class="text" >'.(stripslashes($row['NUM_MLLE'])).'&nbsp;</td>
				<td class="text" >'.(stripslashes($row['PERS_PRENOMS'].' '.$row['PERS_NOM'])).'&nbsp;</td>
				<td class="text" >'.(stripslashes($row['SER_NOM'])).'&nbsp;</td>
				<td class="text" >'.(stripslashes($row['PERS_FONCTION'])).'&nbsp;</td>
				<td class="text" >'.(stripslashes($row['PERS_TEL'])).'&nbsp;</td>
				<td class="text" >'.(stripslashes($row['PERS_EMAIL'])).'&nbsp;</td>
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

function ligneEtatListePersonne($wh=''){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	$returnHTML = '';

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY NUM_MLLE  ASC");
	//Nombre d'éléments
	$nbre = nombrePersonnel($where);

	if($nbre>0){
		//Calcule des limites
		$sql = "SELECT * FROM personnel INNER JOIN magasin ON (personnel.CODE_MAGASIN=magasin.CODE_MAGASIN) $where $order;";
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
			//($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

			$returnHTML .= '
			<tr align="left" valign="middle">
				<td class="botBorderTdall" align="center">'.$i.'</td>
	            <td class="botBorderTdall">'.(stripslashes($row['NUM_MLLE'])).'&nbsp;</td>
				<td class="botBorderTdall">'.(stripslashes($row['PERS_PRENOMS'].' '.$row['PERS_NOM'])).'&nbsp;</td>
				<td class="botBorderTdall">'.(stripslashes($row['SER_NOM'])).'&nbsp;</td>
				<td class="botBorderTdall">'.(stripslashes($row['PERS_FONCTION'])).'&nbsp;</td>
				<td class="botBorderTdall" nowrap="nowrap">'.(stripslashes($row['PERS_TEL'])).'&nbsp;</td>
				<td class="botBorderTdall">'.(stripslashes($row['PERS_EMAIL'])).'&nbsp;</td>
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