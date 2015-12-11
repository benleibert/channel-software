<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */

//--------- UNITES -----------------------------

//Nombre de ligne retourner
function nombreExercice($where=''){
	$sql = "SELECT * FROM exercice $where;";
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

function ligneConExercice($wh='', $ord='', $sens='ASC', $page=1, $nelt){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	$returnHTML = '';
	$returnTble = array();//HTML, nbreTotal,

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY ID_EXERCICE DESC");
	//Nombre d'éléments
	$returnTble['NE'] = nombreExercice($where);
	if($returnTble['NE']>0){
		//Calcule des limites
		$i = ($page-1)*$nelt;
		$sql = "SELECT * FROM exercice $where $order LIMIT $i, $nelt;";
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
			$d1 = frFormat($row['EX_DATEDEBUT']); //Return  $ret = array(); // DFR, TFR
			$d2 = frFormat($row['EX_DATEFIN']);
			$d3 = frFormat($row['EX_DATECLOTURE']);

			$nbre = NbJours(date('Y-m-d'), $row['EX_DATEFIN']);
			if($nbre==1) {$nbre = $nbre.' jour';}
			elseif($nbre>1) {$nbre = $nbre.' jours';}
			else {$nbre ='-';}

			//Is use
			$order = isUseNow('ID_EXERCICE', 'commande', "WHERE ID_EXERCICE = ".$row['ID_EXERCICE']);
			$livr = isUseNow($row['ID_EXERCICE'], 'livraison', "WHERE ID_EXERCICE =".$row['ID_EXERCICE']);
			$autrelivr = isUseNow($row['ID_EXERCICE'], 'conditionmt', "WHERE ID_EXERCICE =".$row['ID_EXERCICE']);
			$mouvement = isUseNow($row['ID_EXERCICE'], 'mouvement', "WHERE ID_EXERCICE =".$row['ID_EXERCICE']);
			$declass = isUseNow($row['ID_EXERCICE'], 'declass', "WHERE ID_EXERCICE =".$row['ID_EXERCICE']);
			$report = isUseNow($row['ID_EXERCICE'], 'report', "WHERE ID_EXERCICE =".$row['ID_EXERCICE']);
			$dotation = isUseNow($row['ID_EXERCICE'], 'dotation', "WHERE ID_EXERCICE =".$row['ID_EXERCICE']);
			$transfert = isUseNow($row['ID_EXERCICE'], 'transfert', "WHERE ID_EXERCICE =".$row['ID_EXERCICE']);
			$inventaire = isUseNow($row['ID_EXERCICE'], 'inventaire', "WHERE ID_EXERCICE =".$row['ID_EXERCICE']);
			$programm = isUseNow($row['ID_EXERCICE'], 'programmation', "WHERE ID_EXERCICE =".$row['ID_EXERCICE']);
			(($order+$livr+$autrelivr+$mouvement+$declass+$report+$dotation+$transfert+$inventaire+$programm) > 0 ? $Use = 1 : $Use = 0);


			($row['EX_CLOTURE']=='0' ? $imgCl = '<img src="../images/encours.gif" width="16" height="16">' : $imgCl ='<img src="../images/fermer.png" width="16" height="16">');

			$returnHTML .= '
			<tr align="left" valign="middle" class="'.$col.'">
	            <td><input type="checkbox" name="rowSelection[]" value="'.$row['ID_EXERCICE'].'@'.$Use.'@'.$row['EX_CLOTURE'].'" onClick="IsCloturer('.$row['EX_CLOTURE'].', '.$j.');">	</td>
                <td class="text" align="center">'.$row['ID_EXERCICE'].'</td>
                <td class="text" >'.(stripslashes($row['EX_LIBELLE'])).'</td>
				<td class="text" align="center">'.(stripslashes($d1['DFR'])).'</td>
				<td class="text" align="center">'.(stripslashes($d2['DFR'])).'</td>
				<td class="text" align="center">'.$imgCl.'</td>
				<td class="text" align="center">'.(stripslashes($d3['DFR'])).'</td>
				<td class="text" align="center">'.(stripslashes($nbre)).'</td>
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


function ligneEtatExercice($wh='', $ord='', $sens='ASC'){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	$returnHTML = '';
	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY ID_EXERCICE DESC");
	//Nombre d'éléments
	$nbre = nombreExercice($where);
	if($nbre>0){
		//Calcule des limites
		$sql = "SELECT * FROM exercice $where $order;";
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
			$d1 = frFormat($row['EX_DATEDEBUT']); //Return  $ret = array(); // DFR, TFR
			$d2 = frFormat($row['EX_DATEFIN']);
			$d3 = frFormat($row['EX_DATECLOTURE']);

			$nbre = NbJours(date('Y-m-d'), $row['EX_DATEFIN']);
			if($nbre==1) {$nbre = $nbre.' jour';}
			elseif($nbre>1) {$nbre = $nbre.' jours';}
			else {$nbre ='-';}

			($row['EX_CLOTURE']=='0' ? $imgCl = '<img src="../images/encours.gif" width="16" height="16">' : $imgCl ='<img src="../images/fermer.png" width="16" height="16">');

			$returnHTML .= '
			<tr align="left" valign="middle">
	            <td class="botBorderTdall" align="center">'.$row['ID_EXERCICE'].'&nbsp;</td>
                <td class="botBorderTdall" >'.(stripslashes($row['EX_LIBELLE'])).'&nbsp;</td>
				<td class="botBorderTdall" align="center">'.(stripslashes($d1['DFR'])).'&nbsp;</td>
				<td class="botBorderTdall" align="center">'.(stripslashes($d2['DFR'])).'&nbsp;</td>
				<td class="botBorderTdall" align="center">'.$imgCl.'</td>
				<td class="botBorderTdall" align="center">'.(stripslashes($d3['DFR'])).'&nbsp;</td>
				<td class="botBorderTdall" align="center">'.(stripslashes($nbre)).'&nbsp;</td>
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