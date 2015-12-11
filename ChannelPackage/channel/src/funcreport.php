<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */

//--------- UNITES -----------------------------

//Nombre de ligne retourner
function nombreReport($where=''){
	$sql = "SELECT ID_REPORT  FROM report INNER JOIN exercice ON (report.ID_EXERCICE = exercice.ID_EXERCICE)  $where;";
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

function ligneConReport($wh='', $ord='', $sens='ASC', $page=1, $nelt){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	$returnHTML = '';
	$returnTble = array();//HTML, nbreTotal,

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY REP_DATE DESC");
	//Nombre d'éléments
	$returnTble['NE'] = nombreReport($where);
	if($returnTble['NE']>0){
		//Calcule des limites
		$i = ($page-1)*$nelt;
	 	$sql = "SELECT * FROM report INNER JOIN exercice ON (report.ID_EXERCICE = exercice.ID_EXERCICE) $where $order LIMIT $i, $nelt;";
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
			$d1 = frFormat($row['REP_DATE']); //Return  $ret = array(); // DFR, TFR
			($row['REP_VALIDE']=='0' ? $imgCl = '<img src="../images/encours.gif" title="En cours" width="16" height="16">' : $imgCl ='<img src="../images/valider.gif" title="Validée" width="16" height="16">');
			$i++;
			$returnHTML .= '
			<tr align="left" valign="middle" class="'.$col.'">
	            <td class="text" align="center" nowrap>'.$i.'</td>
				<td><input type="checkbox" name="rowSelection[]" value="'.$row['CODE_REPORT'].'@'.$row['REP_VALIDE'].'@'.$j.'" onClick="IsValider('.$row['REP_VALIDE'].','.$j.');"></td>
                <td class="text" align="center" >'.$imgCl.'<input type="hidden" name="rowValid[]" id="rowValid[]"value="'.$row['REP_VALIDE'].'"></td>
                <td height="22" class="text" align="left">'.(stripslashes($row['CODE_REPORT'])).'</td>
                <td class="text" >'.(stripslashes($d1['DFR'])).'</td>
                <td class="text" >'.(stripslashes($row['EX_LIBELLE'])).'</td>
                <td class="text" align="center" nowrap="nowrap" ><a href="dbreport.php?do=detail&xid='.$row['CODE_REPORT'].'"  class="morelink">'.(stripslashes('Détails report')).'</a>
				| <a href="dbreport.php?do=journal&xid='.$row['CODE_REPORT'].'" class="morelink">'.(stripslashes('Journal')).'</a></td>
            </tr>';
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

function ligneEtatListeReport($wh='', $ord='', $sens='ASC'){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	$returnHTML = '';

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY REP_DATE DESC");
	//Nombre d'éléments
	$nbre = nombreReport($where);
	if($nbre>0){
		//Calcule des limites
		$sql = "SELECT * FROM report INNER JOIN exercice ON (report.ID_EXERCICE = exercice.ID_EXERCICE) $where $order ;";
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
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			//($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
			$d1 = frFormat($row['REP_DATE']); //Return  $ret = array(); // DFR, TFR
			($row['REP_VALIDE']=='0' ? $imgCl = '<img src="../images/encours.gif" title="En cours" width="16" height="16">' : $imgCl ='<img src="../images/valider.gif" title="Validée" width="16" height="16">');

			$returnHTML .= '
			<tr align="left" valign="middle" >
	            <td class="botBorderTdall" align="center" >'.$i.'</td>
                <td class="botBorderTdall" align="center" >'.$imgCl.'&nbsp;</td>
                <td height="22" class="botBorderTdall" align="center">'.(stripslashes($row['ID_REPORT'])).'&nbsp;</td>
				<td class="botBorderTdall" >'.(stripslashes($d1['DFR'])).'&nbsp;</td>
                 <td class="botBorderTdall" >'.(stripslashes($row['EX_LIBELLE'])).'&nbsp;</td>
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

function ligneaddReport($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['code_detreport'])	? $code_detreport 	= $data[$i-1]['code_detreport'] : $code_detreport	='');
		(isset($data[$i-1]['monlot'])			? $monlot 		= $data[$i-1]['monlot'] 		: $monlot	='');
		(isset($data[$i-1]['codeproduit'])		? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 			? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['stocks']) 			? $stocks 			= $data[$i-1]['stocks'] 			: $stocks			=0);
		(isset($data[$i-1]['unite']) 			? $unite 		= $data[$i-1]['unite'] 			: $unite		='');
		(isset($data[$i-1]['prix']) 			? $prix 		= $data[$i-1]['prix'] 			: $prix		    ='');
		(isset($data[$i-1]['reflot']) 			? $reflot 		= $data[$i-1]['reflot'] 		: $reflot		='');
		(isset($data[$i-1]['dateperemp']) 		? $dateperemp 	= $data[$i-1]['dateperemp'] 	: $dateperemp	='');


		$total = $prix*$stocks;
		($total>0 ?	$Atotal = number_format($total,2,',', ' ') : $Atotal='');

		$ret .= '
		<tr align="left" valign="middle">
            <td class="botBorderTd" nowrap>'.$i.' -
			<input name="code_detreport'.$i.'" type="hidden"   id="code_detreport'.$i.'" size="10" value="'.$code_detreport.'" />
			<input name="monlot'.$i.'" type="hidden"   id="monlot'.$i.'" size="10" value="'.$monlot.'" /></td>
			<!-- <td class="botBorderTd"><input name="openf'.$i.'" type="button" class="button"  value="..." onClick="OpenWin(\'listearticlescde.php?lg='.$i.'\',\'Liste\');"></td> -->
			<td class="botBorderTd"><input name="codeproduit'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="codeproduit'.$i.'" size="10" value="'.$codeproduit.'">
			<input name="oldcodeproduit'.$i.'" type="hidden"   id="oldcodeproduit'.$i.'" size="10" value="'.$codeproduit.'"></td>
            <td class="botBorderTd"><input name="produit'.$i.'" type="text"  readonly="readonly" class="formStyle" id="produit'.$i.'" value="'.(stripslashes($produit)).'"></td>
            <td class="botBorderTd"><input name="qte'.$i.'" type="text" readonly="readonly" class="formStyleFree" id="qte'.$i.'" size="10" value="'.$stocks.'"></td>
			<td class="botBorderTd"><input name="unite'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="unite'.$i.'" size="10" value="'.$unite.'"></td>
			<td class="botBorderTd"><input name="prix'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="prix'.$i.'" value="'.$prix.'" size="10"></td>
			<td class="botBorderTd"><input name="total'.$i.'" type="text" readonly="readonly" class="formStyleFree" id="total'.$i.'" value="'.$Atotal.'" size="10"></td>
			<td class="botBorderTd"><input name="reflot'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="reflot'.$i.'" value="'.$reflot.'" size="10"></td>
			<td class="botBorderTd"><input name="dateperemp'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="dateperemp'.$i.'" value="'.$dateperemp.'" size="10" />
        </tr>';
	}
	return $ret;
}

function lignedetailReport($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['code_detreport'])	? $code_detreport 	= $data[$i-1]['code_detreport'] : $code_detreport	='');
		(isset($data[$i-1]['monlot'])			? $monlot 		= $data[$i-1]['monlot'] 		: $monlot	='');
		(isset($data[$i-1]['codeproduit'])		? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 			? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['qte'])				? $qte	 		= $data[$i-1]['qte'] 			: $qte			='');
		(isset($data[$i-1]['unite']) 			? $unite 		= $data[$i-1]['unite'] 			: $unite		='');
		(isset($data[$i-1]['prix'])				? $prix		 	= $data[$i-1]['prix'] 			: $prix			='');
		(isset($data[$i-1]['reflot'])			? $reflot		= $data[$i-1]['reflot'] 		: $reflot			='');
		(isset($data[$i-1]['dateperemp'])		? $dateperemp	= $data[$i-1]['dateperemp'] 	: $dateperemp			='');

		$dateperemp = substr($dateperemp,0,strlen($dateperemp)-3 );
		($prix*$qte>0 ? $total=number_format($prix*$qte,0,',',' ') : $total = '');

		$ret .= '
		<tr align="left" valign="middle">
            <td class="botBorderTd" nowrap>'.$i.' - </td>
			<td class="botBorderTd">
			<input name="code_detreport'.$i.'" type="hidden"   id="code_detreport'.$i.'" size="10" value="'.$code_detreport.'">
			<input name="monlot'.$i.'" type="hidden"   id="monlot'.$i.'" size="10" value="'.$monlot.'">
			<input name="codeproduit'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="codeproduit'.$i.'" size="10" value="'.$codeproduit.'"></td>
            <td class="botBorderTd"><input name="produit'.$i.'" type="text"  readonly="readonly" class="formStyle" id="produit'.$i.'" value="'.(stripslashes($produit)).'"></td>
            <td class="botBorderTd"><input name="qte'.$i.'" type="text" readonly="readonly" class="formStyleFree" id="qte'.$i.'" size="10" value="'.$qte.'" ></td>
			<td class="botBorderTd"><input name="unite'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="unite'.$i.'" size="10" value="'.$unite.'"></td>
			<td class="botBorderTd"><input name="prix'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="prix'.$i.'" size="10" value="'.$prix.'" ></td>
			<td class="botBorderTd"><input name="total'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="total'.$i.'" size="10" value="'.$total.'" ></td>
			<td class="botBorderTd"><input name="reflot'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="reflot'.$i.'" size="10" value="'.$reflot.'" ></td>
			<td class="botBorderTd"><input name="dateperemp'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="dateperemp'.$i.'" size="10" value="'.$dateperemp.'"></td>
        </tr>';
	}
	return $ret;
}

function lignejournalReport($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])? $codeproduit = $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 	? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['qte'])		? $qte	 		= $data[$i-1]['qte'] 			: $qte			='');
		(isset($data[$i-1]['unite']) 	? $unite 		= $data[$i-1]['unite'] 			: $unite		='');

		(isset($data[$i-1]['valide']) && $data[$i-1]['valide']==1	? $imgCl ='<img src="../images/valider.gif" title="Validé" width="16" height="16">'	: $imgCl = '<img src="../images/encours.gif" title="En cours" width="16" height="16">');

		$ret .= '
		<tr align="left" valign="middle">
            <td class="botBorderTd" nowrap>'.$i.' - </td>
			<td class="botBorderTd"><input name="jcodeproduit'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="jcodeproduit'.$i.'" size="10" value="'.$codeproduit.'"></td>
            <td class="botBorderTd"><input name="jqte'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="jqte'.$i.'" size="10" value="'.$qte.'" ></td>
			<td class="botBorderTd"><input name="junite'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="junite'.$i.'" size="10" value="'.$unite.'"></td>
			<td class="botBorderTd">'.$imgCl.'</td>
        </tr>';
	}
	return $ret;
}

function ligneEtatReport($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 	? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['stocks'])			? $stocks 			= $data[$i-1]['stocks'] 			: $stocks			='');
		(isset($data[$i-1]['unite']) 		? $unite 		= $data[$i-1]['unite'] 			: $unite		='');

		$ret .= '
		<tr align="left" valign="middle">
            <td class="botBorderTdall" align="center" nowrap>'.$i.' - </td>
			<!-- <td class="botBorderTdall" align="center">'.$codeproduit.'&nbsp;</td> -->
            <td class="botBorderTdall">'.(stripslashes($produit)).'&nbsp;</td>
            <td class="botBorderTdall" align="center">'.$stocks.'&nbsp;</td>
			<td class="botBorderTdall" align="center">'.(stripslashes($unite)).'&nbsp;</td>
        </tr>';
	}
	return $ret;
}

?>