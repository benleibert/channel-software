<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */

//--------- UNITES -----------------------------

//Nombre de ligne retourner
function nombreUser($where=''){
	$sql = "SELECT * FROM compte INNER JOIN profil ON (compte.IDPROFIL=profil.IDPROFIL)  $where;";
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

function ligneConUser($wh='', $ord='', $sens='ASC', $page=1, $nelt){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	$returnHTML = '';
	$returnTble = array();//HTML, nbreTotal,

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY LOGIN ASC");
	//Nombre d'éléments
	$returnTble['NE'] = nombreUser($where);
	if($returnTble['NE']>0){
		//Calcule des limites
		$i = ($page-1)*$nelt;
		$sql = "SELECT * FROM compte INNER JOIN profil ON (compte.IDPROFIL=profil.IDPROFIL) $where $order LIMIT $i, $nelt;";
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
			($row['ACTIVATED']==1 ? $etat = 'Activé' : $etat = 'Déactivé');

			//Is use
			$compte = isUseNow('LOGIN', 'logs', "WHERE LOGIN LIKE '".$row['LOGIN']."'");
			(($compte) > 0 ? $Use = 1 : $Use = 0);

			$returnHTML .= '
			<tr align="left" valign="middle" class="'.$col.'">
	            <td><input type="checkbox" name="rowSelection[]" value="'.$row['LOGIN'].'@'.$Use.'"></td>
                <td class="text" >'.(stripslashes($row['LOGIN'])).'</td>
				<td class="text" >'.(stripslashes($row['LIBPROFIL'])).'</td>
				<td class="text" >'.(stripslashes($row['NUM_MLLE'])).'</td>
				<td class="text" >'.(stripslashes(getPersonnelName($row['NUM_MLLE']))).'</td>
				<td class="text" >'.(stripslashes($etat)).'</td>
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

function ligneEtatListeUtilisateur($wh=''){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	$returnHTML = '';

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY LOGIN ASC");
	//Nombre d'éléments
	$nbre = nombreUser($where);
	if($nbre>0){
		//Calcule des limites
		$sql = "SELECT * FROM compte INNER JOIN profil ON (compte.IDPROFIL=profil.IDPROFIL)  $where $order;";
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
			($row['ACTIVATED']==1 ? $etat = 'Activé' : $etat = 'Déactivé');

			$returnHTML .= '
			<tr align="left" valign="middle">
	            <td class="botBorderTdall" align="center">'.$i.'</td>
                <td class="botBorderTdall">'.(stripslashes($row['LOGIN'])).'&nbsp;</td>
				<td class="botBorderTdall">'.(stripslashes($row['LIBPROFIL'])).'&nbsp;</td>
				<td class="botBorderTdall">'.(stripslashes($row['NUM_MLLE'])).'&nbsp;</td>
				<td class="botBorderTdall">'.(stripslashes(getPersonnelName($row['NUM_MLLE']))).'&nbsp;</td>
				<td class="botBorderTdall">'.(stripslashes($etat)).'</td>
				<td class="botBorderTdall">'.(stripslashes(getUsermagasin($row['LOGIN']))).'&nbsp;</td>
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