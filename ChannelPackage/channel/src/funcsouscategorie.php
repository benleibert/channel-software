<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */

//--------- UNITES -----------------------------

//Nombre de ligne retourner
function nombresousCategorie($where=''){
	$sql = "SELECT * FROM souscategorie INNER JOIN categorie ON (categorie.CODE_CATEGORIE LIKE souscategorie.CODE_CATEGORIE)  $where;";
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

function ligneConsousCategorie($wh='', $ord='', $sens='ASC', $page=1, $nelt){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	$returnHTML = '';
	$returnTble = array();//HTML, nbreTotal,

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY CODE_SOUSCATEGORIE ASC");
	//Nombre d'éléments
	$returnTble['NE'] = nombresousCategorie($where);
	if($returnTble['NE']>0){
		//Calcule des limites
		$i = ($page-1)*$nelt;
		$sql = "SELECT * FROM souscategorie INNER JOIN categorie ON (categorie.CODE_CATEGORIE LIKE souscategorie.CODE_CATEGORIE) $where $order LIMIT $i, $nelt;";
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
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

			//Is use
			$produit = isUseNow('CODE_CATEGORIE', 'produit', "WHERE CODE_CATEGORIE LIKE '".$row['CODE_CATEGORIE']."'");
			(($produit) > 0 ? $Use = 1 : $Use = 0);

			$returnHTML .= '
			<tr align="left" valign="middle" class="'.$col.'">
	            <td><input type="checkbox" name="rowSelection[]" value="'.$row['CODE_SOUSCATEGORIE'].'@'.$Use.'"></td>
                <td class="text" align="center">'.$row['CODE_CATEGORIE'].'&nbsp;</td>
                <td class="text" >'.(stripslashes($row['CAT_LIBELLE'])).'&nbsp;</td>
				<td class="text" align="center">'.$row['CODE_SOUSCATEGORIE'].'&nbsp;</td>
                <td class="text" >'.(stripslashes($row['SOUSCAT_LIBELLE'])).'&nbsp;</td>
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

function ligneEtatListeSousCategorie($wh=''){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	$returnHTML = '';

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY CODE_SOUSCATEGORIE ASC");
	//Nombre d'éléments
	$nbre = nombresousCategorie($where);
	if($nbre>0){
		//Calcule des limites
		$sql = "SELECT * FROM souscategorie INNER JOIN categorie ON (categorie.CODE_CATEGORIE LIKE souscategorie.CODE_CATEGORIE) $where $order;";
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
			<tr align="left" valign="middle" >
	            <td class="botBorderTdall">'.$i.'</td>
                <td class="botBorderTdall"  align="let">'.(stripslashes($row['CODE_CATEGORIE'])).'&nbsp;</td>
                <td class="botBorderTdall">'.(stripslashes($row['CAT_LIBELLE'])).'&nbsp;</td>
				<td class="botBorderTdall" align="left">'.$row['CODE_SOUSCATEGORIE'].'&nbsp;</td>
                <td class="botBorderTdall" >'.(stripslashes($row['SOUSCAT_LIBELLE'])).'&nbsp;</td>
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