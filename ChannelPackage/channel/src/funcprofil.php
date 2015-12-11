<?php

/**
 * @author KG
 *
 * @version $Id$
 * @copyright 2012
 * @date 08/06/2012
 *
 * What is it about?
 * Here you will find all functions used in profil
 */

//Consult Groupes ou Profils
function ligneConProfil($wh='', $ord='', $sens='ASC', $page=1, $nelt){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	$returnHTML = '';
	$returnTble = array();//HTML, nbreTotal,

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY profil.LIBPROFIL ASC");

	//Nombre d'éléments
	$returnTble['NE'] = nombreElement($where, 'profil');

	if($returnTble['NE']>0){

		//Calcule des limites
		$i = ($page-1)*$nelt;
		//SQL
		 $sql = "SELECT * FROM profil  $where $order LIMIT $i, $nelt;";

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
			$compte = isUseNow('IDPROFIL', 'COMPTE', "WHERE IDPROFIL =".$row['IDPROFIL']);
			(($compte) > 0 ? $Use = 1 : $Use = 0);

			$returnHTML .= '
			<tr align="left" valign="middle" class="'.$col.'">
	            <td><input type="checkbox" name="rowSelection[]" value="'.$row['IDPROFIL'].'@'.$Use.'"></td>
                <td class="text" >'.(stripslashes($row['LIBPROFIL'])).'</td>
				<td class="text" >'.(stripslashes(getDroitSrting($row['IDPROFIL']))).'</td>
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


function ligneDroitProfil($data=array()){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	//SQL
	$sql ="SELECT * FROM menu ORDER BY IDMENU ASC;";
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

	$ligneRet="";
	$i = 0;
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		$key = $row['IDMENU'];

//if($ilang=='3' && $ilang!='') { 
//switch($key ){
//case 'aid':		
//$erow="Ajuda";
//case 'bde':		
//$erow="Bon d'entréePort";
// code

//}


//}
		
		
		
		
		
		(isset($data[$key]['VISIBLE']) && $data[$key]['VISIBLE'] == 1 ? $visible='checked="checked"' : $visible='');
		(isset($data[$key]['AJOUT']) && $data[$key]['AJOUT'] == 1 ? $ajout='checked="checked"' : $ajout='');
		(isset($data[$key]['MODIF']) && $data[$key]['MODIF'] == 1 ? $modif='checked="checked"' : $modif='');
		(isset($data[$key]['SUPPR']) && $data[$key]['SUPPR'] == 1 ? $suppr='checked="checked"' : $suppr='');
		(isset($data[$key]['ANNUL']) && $data[$key]['ANNUL'] == 1 ? $annul='checked="checked"' : $annul='');
		(isset($data[$key]['VALID']) && $data[$key]['VALID'] == 1 ? $valid='checked="checked"' : $valid='');

		//:Generate the line of right
		$ligneRet .='
		<tr align="left" valign="top" class="'.$col.'">
		<td width="400" align="left" valign="middle" class="text">'.stripslashes($row['LIBMENU']).' :&nbsp;</td>
		<td width="358" align="left" class="text">
		<label>Visible<input name="'.$row['IDMENU'].'visible" type="checkbox" id="'.$row['IDMENU'].'visible" value="1" '.$visible.' /></label>
		<label>Ajout.<input name="'.$row['IDMENU'].'ajout" type="checkbox" id="'.$row['IDMENU'].'ajout" value="1" '.$ajout.'  /></label>
        <label>Modif.<input name="'.$row['IDMENU'].'modif" type="checkbox" id="'.$row['IDMENU'].'modif" value="1" '.$modif.'  /></label>
        <label>Suppr.<input name="'.$row['IDMENU'].'suppr" type="checkbox" id="'.$row['IDMENU'].'suppr" value="1" '.$suppr.'  /></label>
        <label>Valid.<input name="'.$row['IDMENU'].'valid" type="checkbox" id="'.$row['IDMENU'].'valid" value="1" '.$valid.'  /></label>
        <label>Annul.<input name="'.$row['IDMENU'].'annul" type="checkbox" id="'.$row['IDMENU'].'annul" value="1" '.$annul.'  /></label>
		</td>
		</tr>';

		$i++;
	}
	return $ligneRet;
}


function ligneEtatListeGroupe($wh=''){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	$returnHTML = '';
	$returnTble = array();//HTML, nbreTotal,

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY ID_GROUPE  ASC");

	//Nombre d'éléments
	$nbre = nombreGroupe($where);
	if($nbre>0){
		//Calcule des limites
		$sql = "SELECT * FROM groupe $where $order;";
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

			$returnHTML .= '
			<tr align="left" valign="middle" class="'.$col.'">
	            <td class="botBorderTdall" align="center">'.$i.'</td>
                <td class="botBorderTdall" >'.(stripslashes($row['GRPE_LIBELLE'])).'</td>
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