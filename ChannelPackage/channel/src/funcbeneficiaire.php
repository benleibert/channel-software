<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */

//--------- UNITES -----------------------------

//Nombre de ligne retourner
function nombreBeneficiaire($where=''){
	$sql = "SELECT * FROM beneficiaire INNER JOIN typebeneficiaire ON (beneficiaire.CODE_TYPEBENEF LIKE typebeneficiaire.CODE_TYPEBENEF)
	INNER JOIN province ON (beneficiaire.IDPROVINCE LIKE province.IDPROVINCE) $where;";
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

function ligneConBeneficiaire($wh='', $ord='', $sens='ASC', $page=1, $nelt){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	$returnHTML = '';
	$returnTble = array();//HTML, nbreTotal,

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY  province.IDREGION, beneficiaire.IDPROVINCE, BENEF_NOM  ASC");
	//Nombre d'éléments
	$returnTble['NE'] = nombreBeneficiaire($where);
	if($returnTble['NE']>0){
		//Calcule des limites
		$i = ($page-1)*$nelt;
		$sql = "SELECT * FROM beneficiaire INNER JOIN typebeneficiaire ON (beneficiaire.CODE_TYPEBENEF LIKE typebeneficiaire.CODE_TYPEBENEF)
		INNER JOIN province ON (beneficiaire.IDPROVINCE LIKE province.IDPROVINCE) $where $order LIMIT $i, $nelt;";
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
			// onClick="go('.$row['ID_BENEF'].','.$j.');"

			//Is use
			$bonsortie = isUseNow('CODE_BENEF', 'bonsortie', "WHERE CODE_BENEF LIKE ".$row['CODE_BENEF']);
			($bonsortie > 0 ? $Use = 1 : $Use = 0);


			$returnHTML .= '
			<tr align="left" valign="middle" class="'.$col.'">
	            <td><input type="checkbox" name="rowSelection[]" value="'.$row['CODE_BENEF'].'@'.$Use.'"></td>
                <td class="text" align="left">'.$row['CODE_BENEF'].'</td>
                <td class="text" >'.(stripslashes($row['BENEF_NOM'])).'</td>
				<td class="text" >'.(stripslashes($row['NOM_TYPEBENEF'])).'</td>
				<td class="text" >'.(stripslashes($row['PROVINCE'])).'</td>
				<td class="text" >'.(stripslashes($row['BENEF_TEL'])).'</td>
				<td class="text" >'.(stripslashes($row['BENEF_EMAIL'])).'</td>
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


function ligneEtatBeneficiaire($wh='', $ord='', $sens='ASC'){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	$returnHTML = '';
	$returnTble = array();//HTML, nbreTotal,

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY  province.IDREGION, beneficiaire.IDPROVINCE, BENEF_NOM ASC");

	$sql = "SELECT * FROM beneficiaire INNER JOIN typebeneficiaire ON (beneficiaire.CODE_TYPEBENEF LIKE typebeneficiaire.CODE_TYPEBENEF)
	INNER JOIN province ON (beneficiaire.IDPROVINCE LIKE province.IDPROVINCE)  $where $order;";
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

		$Teff=0;
		$i = 1;
		while($row = 	$query->fetch(PDO::FETCH_ASSOC)){ //($key, $code, $field, $table){

			$returnHTML .= '
			<tr align="left" valign="middle">
	            <td class="botBorderTdall" align="center">'.$i.'-</td>
                <td class="botBorderTdall" align="center">'.$row['CODE_BENEF'].'&nbsp;</td>
                <td class="botBorderTdall">'.(stripslashes($row['BENEF_NOM'])).'&nbsp;</td>
                <td class="botBorderTdall" >'.(stripslashes($row['NOM_TYPEBENEF'])).'&nbsp;</td>
				<td class="botBorderTdall" >'.(stripslashes(getField('IDREGION', $row['IDREGION'],'REGION', 'region'))).'&nbsp;</td>
				<td class="botBorderTdall" >'.(stripslashes($row['PROVINCE'])).'&nbsp;</td>
           </tr>';
			$i++;

	}
	if($returnHTML=='') {
	if($ilang=='1' && $ilang!='') {$returnHTML .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e...</td></tr>';}
	if($ilang=='2' && $ilang!='') {$returnHTML .= '<tr><td colspan="4" class="text">No data...</td></tr>';}
	if($ilang=='3' && $ilang!='') {$returnHTML .= '<tr><td colspan="4" class="text">Nenhum dado...</td></tr>';}
	}
//	$returnTble['L']=$returnHTML;
	return $returnHTML;
}

?>