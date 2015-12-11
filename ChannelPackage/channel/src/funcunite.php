<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */

//--------- UNITES -----------------------------

//Nombre de ligne retourner
function nombreUnite($where=''){
	$sql = "SELECT * FROM unite $where;";
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

function ligneConUnites($wh='', $ord='', $sens='ASC', $page=1, $nelt){
	$returnHTML = '';
	$returnTble = array();//HTML, nbreTotal,

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY ID_UNITE ASC");
	//Nombre d'éléments
	$returnTble['NE'] = nombreUnite($where);
	if($returnTble['NE']>0){
		//Calcule des limites
		$i = ($page-1)*$nelt;
		$sql = "SELECT * FROM unite $where $order LIMIT $i, $nelt;";
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
			$cde_prd = isUseNow('CDE_UNITE', 'cde_prd', "WHERE CDE_UNITE ='".$row['ID_UNITE']."'");
			$cnd_autreliv = isUseNow('CNDAUL_UNITE', 'cnd_autreliv', "WHERE CNDAUL_UNITE ='".$row['ID_UNITE']."'");
			$cnd_invt = isUseNow('INV_UNITE', 'cnd_invt', "WHERE INV_UNITE ='".$row['ID_UNITE']."'");
			$condit = isUseNow('ID_UNITE', 'conditionmt', "WHERE ID_UNITE ='".$row['ID_UNITE']."'");
			$convert = isUseNow('ID_UNITE', 'conversion', "WHERE ID_UNITE ='".$row['ID_UNITE']."'");
			$declass_cnd = isUseNow('DEC_UNITE', 'declass_cnd', "WHERE DEC_UNITE ='".$row['ID_UNITE']."'");
			$dot_cnd = isUseNow('DOT_UNITE', 'dot_cnd', "WHERE DOT_UNITE ='".$row['ID_UNITE']."'");
			$lvr_prd = isUseNow('LIV_UNITE', 'lvr_prd', "WHERE LIV_UNITE ='".$row['ID_UNITE']."'");
			$mouvement = isUseNow('MVT_UNITE', 'mouvement', "WHERE MVT_UNITE ='".$row['ID_UNITE']."'");
			$produit = isUseNow('ID_UNITE', 'produit', "WHERE ID_UNITE ='".$row['ID_UNITE']."'");
			$recond_cnd = isUseNow('CNDREC_UNITEE', 'recond_cnd', "WHERE CNDREC_UNITEE ='".$row['ID_UNITE']."'");
			$trs_cnd = isUseNow('TRS_UNITE', 'trs_cnd', "WHERE TRS_UNITE ='".$row['ID_UNITE']."'");

			(($cde_prd+$cnd_autreliv+$cnd_invt+$cnd_invt+$condit+$convert+$declass_cnd+$dot_cnd+$lvr_prd+$produit+$mouvement+$recond_cnd+$trs_cnd) > 0 ? $Use = 1 : $Use = 0);

			$returnHTML .= '
			<tr align="left" valign="middle" class="'.$col.'">
	            <td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_UNITE'].'@'.$Use.'"></td>
                <td width="10%" height="22" class="text" align="center">'.$row['ID_UNITE'].'</td>
                <td width="30%" class="text" >'.(stripslashes($row['UT_LIBELLE'])).'</td>
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

function ligneEtatListeUnite($wh=''){
	$returnHTML = '';

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY CODESOUSGROUP ASC");
	//Nombre d'éléments
	$nbre = nombreUnite($where);
	if($nbre>0){
		//Calcule des limites
		$sql = "SELECT * FROM unite $where $order ;";
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

		$i =1;
		while($row = $query->fetch(PDO::FETCH_ASSOC)){

			$returnHTML .= '
			<tr align="left" valign="middle">
	            <td class="botBorderTdall">'.$i.'</td>
                <td class="botBorderTdall"  align="center">'.(stripslashes($row['ID_UNITE'])).'&nbsp;</td>
                <td class="botBorderTdall">'.(stripslashes($row['UT_LIBELLE'])).'&nbsp;</td>
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