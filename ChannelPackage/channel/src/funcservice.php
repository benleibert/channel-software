<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */

//--------- UNITES -----------------------------

//Nombre de ligne retourner
function nombreService($where=''){
	$sql = "SELECT * FROM magasin INNER JOIN province ON (magasin.IDPROVINCE=province.IDPROVINCE)
	INNER JOIN region ON (region.IDREGION=province.IDREGION) $where;";
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

function ligneConService($wh='', $ord='', $sens='ASC', $page=1, $nelt){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	$returnHTML = '';
	$returnTble = array();//HTML, nbreTotal,

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY CODE_MAGASIN ASC");
	//Nombre d'éléments
	$returnTble['NE'] = nombreService($where);
	if($returnTble['NE']>0){
		//Calcule des limites
		$i = ($page-1)*$nelt;
		$sql = "SELECT * FROM magasin INNER JOIN  province ON (magasin.IDPROVINCE=province.IDPROVINCE)
		INNER JOIN region ON (region.IDREGION=province.IDREGION) $where $order LIMIT $i, $nelt;";
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
			$mouvement = isUseNow('CODE_MAGASIN', 'mouvement', "WHERE CODE_MAGASIN LIKE '".$row['CODE_MAGASIN']."'");
			(($mouvement) > 0 ? $Use = 1 : $Use = 0);

			//$resp = getlastmod($row['CODE_MAGASIN']);

			$returnHTML .= '
			<tr align="left" valign="middle" class="'.$col.'">
	            <td><input type="checkbox" name="rowSelection[]" value="'.$row['CODE_MAGASIN'].'@'.$Use.'"></td>
                <td class="text" align="left">'.$row['CODE_MAGASIN'].'</td>
                <td class="text" >'.(stripslashes($row['SER_NOM'])).'</td>
                <td class="text" >'.(stripslashes($row['REGION'])).'</td>
				<td class="text" >'.(stripslashes($row['PROVINCE'])).'</td>
				<td class="text" >'.(stripslashes($row['SER_VILLE'])).'</td>
				<td class="text" >'.(stripslashes($row['SER_TEL'])).'</td>

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

function ligneEtatService($wh='', $ord='', $sens='ASC'){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	$returnHTML = '';

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY CODE_MAGASIN ASC");
	//Nombre d'éléments
	$nbre = nombreService($where);
	if($nbre>0){
		//Calcule des limites
		$sql = "SELECT * FROM magasin INNER JOIN  province ON (magasin.IDPROVINCE=province.IDPROVINCE) $where $order;";
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

			$returnHTML .= '
			<tr align="left" valign="middle" >
	            <td class="botBorderTdall" align="left">'.$row['CODE_MAGASIN'].'&nbsp;</td>
                <td class="botBorderTdall" >'.(stripslashes($row['SER_NOM'])).'&nbsp;</td>
                <td class="botBorderTdall" >'.(stripslashes($row['PROVINCE'])).'&nbsp;</td>
				<td class="botBorderTdall" >'.(stripslashes($row['SER_VILLE'])).'&nbsp;</td>
				<td class="botBorderTdall" >'.(stripslashes($row['SER_NOM'])).'&nbsp;</td>

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
	return $returnHTML;
}

?>