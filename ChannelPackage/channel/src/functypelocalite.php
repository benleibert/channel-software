<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */

//--------- UNITES -----------------------------

//Nombre de ligne retourner
function nombreTypeLocalite($where=''){
	$sql = "SELECT * FROM groupelocalite $where;";
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

function ligneConTypeLocalite($wh='', $ord='', $sens='ASC', $page=1, $nelt){
	$returnHTML = '';
	$returnTble = array();//HTML, nbreTotal,

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY ID_GRPLOC ASC");
	//Nombre d'éléments
	$returnTble['NE'] = nombreTypeLocalite($where);
	if($returnTble['NE']>0){
		//Calcule des limites
		$i = ($page-1)*$nelt;
		$sql = "SELECT * FROM groupelocalite $where $order LIMIT $i, $nelt;";
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
			$localite = isUseNow('ID_GRPLOC', 'localite', "WHERE ID_GRPLOC LIKE '".$row['ID_GRPLOC']."'");
			(($localite) > 0 ? $Use = 1 : $Use = 0);

			$returnHTML .= '
			<tr align="left" valign="middle" class="'.$col.'">
	            <td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['ID_GRPLOC'].' '.$Use.'"></td>
                <td width="10%" height="22" class="text" align="center">'.$row['ID_GRPLOC'].'</td>
                <td width="30%" class="text" >'.(stripslashes($row['GRPLOC_LIBELLE'])).'</td>
                <td width="30%" class="text" >'.(stripslashes(getTypelocalite($row['GRPLOC_LIEN']))).'</td>
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

?>