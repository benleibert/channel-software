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

function ligneConAffectation($wh='', $ord='', $sens='ASC', $page=1, $nelt){
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
			//(($compte) > 0 ? $Use = 1 : $Use = 0);
			$Use = 0;
			$returnHTML .= '
			<tr align="left" valign="middle" class="'.$col.'">
	            <td><input type="checkbox" name="rowSelection[]" value="'.$row['LOGIN'].'@'.$Use.'"></td>
                <td class="text" >'.(stripslashes($row['LOGIN'])).'</td>
				<td class="text" >'.(stripslashes($row['NUM_MLLE'])).'</td>
				<td class="text" >'.(stripslashes(getPersonnelName($row['NUM_MLLE']))).'</td>
				<td class="text" >'.(stripslashes(getUsermagasin($row['LOGIN']))).'</td>
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
			($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
			($row['ACTIVATED']==1 ? $etat = 'Activé' : $etat = 'Déactivé');

			$returnHTML .= '
			<tr align="left" valign="middle" class="'.$col.'">
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


function listemagasin($magasin=array()){
	$returnHTML = '';

	//Calcule des limites
	$sql1 = "SELECT * FROM region ORDER BY REGION ASC ;";
	//Exécution
	try {
		$cnx1 = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
	}
	$query1 =  $cnx1->prepare($sql1); //Prepare the SQL
	$query1->execute(); //Execute prepared SQL => $query

	$i = 1;
	$returnHTML .= '<table width="100%">';
	while($row1 = 	$query1->fetch(PDO::FETCH_ASSOC)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		$returnHTML .= '
		<tr align="left" valign="middle" class="'.$col.'">
	        <td class="botBorderTdall" align="center" width="5%">'.$i.'</td>
            <td class="botBorderTdall" colspan="3">'.(stripslashes($row1['REGION'])).'&nbsp;</td>
        </tr>';
		$i++;

		//PROVINCE
		$sql2 = "SELECT * FROM province WHERE IDREGION='".$row1['IDREGION']."' ORDER BY PROVINCE ASC ;";
		//Exécution
		try {
			$cnx2 = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query2 =  $cnx2->prepare($sql2); //Prepare the SQL
		$query2->execute(); //Execute prepared SQL => $query//Exécution

		$j=1;
		while($row2 = 	$query2->fetch(PDO::FETCH_ASSOC)){
			$returnHTML .= '
			<tr align="left" valign="middle" class="'.$col.'">
				<td class="botBorderTdall" align="center" width="5%"> + </td>
	            <td class="botBorderTdall" colspan="3">'.(stripslashes($row2['PROVINCE'])).'&nbsp;</td>
	        </tr>';
			$j++;

			//PROVINCE
			$sql2 = "SELECT * FROM magasin WHERE IDPROVINCE='".$row2['IDPROVINCE']."' ORDER BY SER_NOM ASC ;";
			//Exécution
			try {
				$cnx3 = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}
			$query3 =  $cnx3->prepare($sql2); //Prepare the SQL
			$query3->execute(); //Execute prepared SQL => $query//Exécution

			$returnHTML .= '<tr align="left" valign="middle">
							</td>
							<table width="100%">';
			$Nbre =0;
			while($row3 = 	$query3->fetch(PDO::FETCH_ASSOC)){
				if($Nbre%3==0){
					$returnHTML .= '<tr align="left" valign="middle">';
				}
				$returnHTML .= '<td class="botBorderTdall" align="center" width="5%"> -> </td>';
				$id =$row3['CODE_MAGASIN'];

				if(isset($magasin["$id"]) && $magasin["$id"]!=''){
					$returnHTML .= '<td class="botBorderTdall" nowrap="nowrap"><input name="mag[]" type="checkbox" id="mag[]" checked value="'.(stripslashes($row3['CODE_MAGASIN'])).'"  />'.(stripslashes($row3['SER_NOM'])).'&nbsp;</td>';
				}
				else{
					$returnHTML .= '<td class="botBorderTdall" nowrap="nowrap"><input name="mag[]" type="checkbox" id="mag[]" value="'.(stripslashes($row3['CODE_MAGASIN'])).'"  />'.(stripslashes($row3['SER_NOM'])).'&nbsp;</td>';
				}

				$Nbre++;

				if($Nbre==3){
					$returnHTML .= '</tr>';
					$Nbre =0;
				}

			}
			$returnHTML .= '</td></tr><table>';
		}
	}
	$returnHTML .= '</table>';
	return $returnHTML;
}



?>