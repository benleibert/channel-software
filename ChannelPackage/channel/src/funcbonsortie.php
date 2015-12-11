<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */

//--------- UNITES -----------------------------

//Nombre de ligne retourner
function nombreBonsortie($where=''){
	$sql = "SELECT * FROM bonsortie INNER JOIN beneficiaire ON (bonsortie.CODE_BENEF=beneficiaire.CODE_BENEF) $where;";
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

function ligneConBonsortie($wh='', $ord='', $sens='ASC', $page=1, $nelt){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	$returnHTML = '';
	$returnTble = array();//HTML, nbreTotal,

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY bonsortie.SOR_VALIDE DESC");
	//Nombre d'éléments
	$returnTble['NE'] = nombreBonsortie($where);
	if($returnTble['NE']>0){
		//Calcule des limites
		$i = ($page-1)*$nelt;
		$sql = "SELECT * FROM bonsortie INNER JOIN beneficiaire ON (bonsortie.CODE_BENEF=beneficiaire.CODE_BENEF)  $where $order LIMIT $i, $nelt;";
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
			$d1 = frFormat($row['SOR_DATE']); //Return  $ret = array(); // DFR, TFR

			if($row['SOR_VALIDE']=='0' ) {
				$imgCl = '<img src="../images/encours.gif" title="En cours" width="16" height="16">' ;
			}
			elseif ($row['SOR_VALIDE']=='2' ){
				$imgCl = '<img src="../images/cancel.png" title="Annulée" width="16" height="16">' ;
			}
			else {
				$imgCl ='<img src="../images/valider.gif" title="Validée" width="16" height="16">';
			}


			$i++;
			$returnHTML .= '
			<tr align="left" valign="middle" class="'.$col.'">
	            <td class="text" align="center">'.$i.'</td>
				<td><input type="checkbox" name="rowSelection[]" value="'.$row['CODE_BONSORTIE'].'@'.$row['SOR_VALIDE'].'@'.$j.'" onClick="IsValider('.$row['SOR_VALIDE'].','.$j.');"></td>
                <td class="text" align="center">'.$imgCl.'</td>
                <td class="text" >'.stripslashes($row['CODE_BONSORTIE']).'&nbsp;</td>
                <td class="text" >'.stripslashes($d1['DFR']).'&nbsp;</td>
                <td class="text" >'.stripslashes($row['BENEF_NOM']).'&nbsp;</td>
				<td class="text" >'.stripslashes($row['SOR_LIBELLE']).'&nbsp;</td>
				<td class="text" align="center" nowrap="nowrap"><a href="dbbonsortie.php?do=detail&xid='.$row['CODE_BONSORTIE'].'" class="morelink">'.(stripslashes('Détails')).'</a>
				| <a href="dbbonsortie.php?do=journal&xid='.$row['CODE_BONSORTIE'].'" class="morelink">'.(stripslashes('Journal')).'</a></td>
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

function ligneEtatListeBonsortie($wh='', $ord='', $sens='ASC'){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	$returnHTML = '';

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY SOR_DATE DESC");
	//Nombre d'éléments
	$nbre = nombreBonsortie($where);
	if($nbre>0){
		//Calcule des limites
		$sql = "SELECT * FROM bonsortie INNER JOIN beneficiaire ON (bonsortie.CODE_BENEF=beneficiaire.CODE_BENEF)  $where $order;";
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
			$col='';
			$d1 = frFormat($row['SOR_DATE']); //Return  $ret = array(); // DFR, TFR

			if($row['SOR_VALIDE']=='0' ) {
				$imgCl = '<img src="../images/encours.gif" title="En cours" width="16" height="16">' ;
			}
			elseif ($row['SOR_VALIDE']=='2' ){
				$imgCl = '<img src="../images/cancel.png" title="Annulée" width="16" height="16">' ;
			}
			else {
				$imgCl ='<img src="../images/valider.gif" title="Validée" width="16" height="16">';
			}
			$returnHTML .= '
			<tr align="left" valign="middle" class="'.$col.'">
	            <td class="botBorderTdall" align="center">'.$i.'</td>
                <td class="botBorderTdall" align="center">'.$imgCl.'</td>
				<td class="botBorderTdall" >'.stripslashes($row['CODE_BONSORTIE']).'&nbsp;</td>
                <td class="botBorderTdall" align="center">'.(stripslashes($d1['DFR'])).'&nbsp;</td>
				<td class="botBorderTdall" >'.(stripslashes($row['SOR_LIBELLE'])).'&nbsp;</td>
               <td class="botBorderTdall" >'.stripslashes($row['BENEF_NOM']).'&nbsp;</td>
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

function ligneaddBonsortie($nbre=1, $data=array()){

	$ret = '';
	$verif = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['code_detbonsortie'])	? $code_detbonsortie 	= $data[$i-1]['code_detbonsortie'] : $code_detbonsortie	='');
		(isset($data[$i-1]['monlot'])			? $monlot 			= $data[$i-1]['monlot'] 		: $monlot	='');
		(isset($data[$i-1]['codeproduit'])	? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['oldcodeproduit'])	? $oldcodeproduit 	= $data[$i-1]['oldcodeproduit'] 	: $oldcodeproduit	='');
		(isset($data[$i-1]['produit']) 		? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['qte'])			? $qte 			= $data[$i-1]['qte'] 			: $qte			='');
		(isset($data[$i-1]['dispo'])		? $dispo 		= $data[$i-1]['dispo'] 			: $dispo			='');
		(isset($data[$i-1]['unite']) 		? $unite 		= $data[$i-1]['unite'] 			: $unite		='');
		(isset($data[$i-1]['prix']) 		? $prix 		= $data[$i-1]['prix'] 			: $prix		='');
		(isset($data[$i-1]['reflot']) 		? $reflot 		= $data[$i-1]['reflot'] 			: $reflot		='');
		(isset($data[$i-1]['dateperemp']) 	? $dateperemp 		= $data[$i-1]['dateperemp'] 			: $dateperemp		='');

		($prix*$qte>0 ? $Atotal=number_format($prix*$qte,2,'.','') : $Atotal = '');
		($prix>0 ? $prix=number_format($prix,2,'.','') : $prix = '');
		$verif .= "trimAll(document.formadd.qte$i.value)=='' && ";

		$ret .= '
		<tr align="left" valign="middle">
            <td class="botBorderTd" nowrap>'.$i.' - </td>
			<td class="botBorderTd"><input name="delete'.$i.'" type="button" class="button" title="Supprimer" value=" X " onClick="msgSupprLigne(\''.$codeproduit.'\','.$i.');"></td>
			<td class="botBorderTd">
			<input name="code_detbonsortie'.$i.'" type="hidden"   id="code_detbonsortie'.$i.'" size="10" value="'.$code_detbonsortie.'">
			<input name="monlot'.$i.'" type="hidden"   id="monlot'.$i.'" size="10" value="'.$monlot.'">
			<input name="openf'.$i.'" type="button" class="button"  title="Produit" value="..." onClick="OpenWin(\'listeproduitqte.php?lg='.$i.'\',\'Liste\');"></td>
			<td class="botBorderTd"><input name="codeproduit'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="codeproduit'.$i.'" size="10" value="'.$codeproduit.'" onblur="if(parseFloat(document.getElementById(\'qte'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)>0) {document.getElementById(\'total'.$i.'\').value =parseFloat(document.getElementById(\'qte'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)}" />
			<input name="oldcodeproduit'.$i.'" type="hidden"   id="oldcodeproduit'.$i.'" size="10" value="'.$oldcodeproduit.'" onblur="if(parseFloat(document.getElementById(\'qte'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)>0) {document.getElementById(\'total'.$i.'\').value =parseFloat(document.getElementById(\'qte'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)}" /></td>
            <td class="botBorderTd"><input name="produit'.$i.'" type="text"  readonly="readonly" class="formStyle" id="produit'.$i.'" value="'.(stripslashes($produit)).'" onblur="if(parseFloat(document.getElementById(\'qte'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)>0) {document.getElementById(\'total'.$i.'\').value =parseFloat(document.getElementById(\'qte'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)}" /></td>
            <td class="botBorderTd"><input name="qte'.$i.'" type="text" class="formStyleFree" id="qte'.$i.'" value="'.$qte.'" size="10" value="" onBlur="if(!parseFloat(document.getElementById(\'qte'.$i.'\').value)){alert(\'Entrez un nombre pour la quantité\');document.getElementById(\'qte'.$i.'\').value=\'\';};checkQte(\'qte'.$i.'\',\'dispo'.$i.'\');if(parseFloat(document.getElementById(\'qte'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)>0) {document.getElementById(\'total'.$i.'\').value =parseFloat(document.getElementById(\'qte'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)} " /></td>
			<td class="botBorderTd"><input name="unite'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="unite'.$i.'" size="10" value="'.$unite.'" onblur="if(parseFloat(document.getElementById(\'qte'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)>0) {document.getElementById(\'total'.$i.'\').value =parseFloat(document.getElementById(\'qte'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)}" />
			<td class="botBorderTd"><input name="prix'.$i.'" type="text" class="formStyleFree" id="prix'.$i.'" value="'.$prix.'" size="10" value=""  onblur="if(!parseFloat(document.getElementById(\'prix'.$i.'\').value)){alert(\'Entrez un nombre pour le prix\');document.getElementById(\'prix'.$i.'\').value=\'\';};if(parseFloat(document.getElementById(\'qte'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)>0) {document.getElementById(\'total'.$i.'\').value =parseFloat(document.getElementById(\'qte'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)}" /></td>
			<td class="botBorderTd"><input name="total'.$i.'" type="text" readonly="readonly" class="formStyleFree" id="total'.$i.'" value="'.$Atotal.'" size="10" value="" ></td>
			<td class="botBorderTd"><input name="reflot'.$i.'" type="text" readonly="readonly" class="formStyleFree" id="reflot'.$i.'" value="'.$reflot.'" size="10" value=""  onblur="if(parseFloat(document.getElementById(\'qte'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)>0) {document.getElementById(\'total'.$i.'\').value =parseFloat(document.getElementById(\'qte'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)}" /></td>
			<td class="botBorderTd"><input name="dateperemp'.$i.'" readonly="readonly" type="text" class="formStyleFree" id="dateperemp'.$i.'" value="'.$dateperemp.'" size="10" value=""  onblur="if(parseFloat(document.getElementById(\'qte'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)>0) {document.getElementById(\'total'.$i.'\').value =parseFloat(document.getElementById(\'qte'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)}" />
			<input name="dispo'.$i.'" type="hidden" id="dispo'.$i.'"  value="'.$dispo.'"></td>
		</tr>';
	}
	if($verif!='') {
		$verif = substr($verif,0,strlen($verif)-3);
		$fonction ="
		function tousVide(){
			if($verif) {return 1;}
			else {return 0;}
		}
		";
	}
	return array($fonction,$ret);
}

function lignedetailBonsortie($nbre=1, $data=array()){
	$ret = '';
	$verif ='';
	$supdipo ='';
	$fonction ='';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['code_detbonsortie'])	? $code_detbonsortie 	= $data[$i-1]['code_detbonsortie'] : $code_detbonsortie	='');
		(isset($data[$i-1]['monlot'])			? $monlot 			= $data[$i-1]['monlot'] 		: $monlot	='');
		(isset($data[$i-1]['codeproduit'])		? $codeproduit 		= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 			? $produit 			= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['qte'])				? $qte	 			= $data[$i-1]['qte'] 			: $qte			='');
		(isset($data[$i-1]['dispo'])			? $dispo 			= $data[$i-1]['dispo'] 			: $dispo		='');
		(isset($data[$i-1]['unite']) 			? $unite 			= $data[$i-1]['unite'] 			: $unite		='');
		(isset($data[$i-1]['prix']) 			? $prix 			= $data[$i-1]['prix'] 			: $prix		='');
		(isset($data[$i-1]['reflot']) 			? $reflot 			= $data[$i-1]['reflot'] 		: $reflot		='');
		(isset($data[$i-1]['dateperemp']) 		? $dateperemp 		= $data[$i-1]['dateperemp'] 	: $dateperemp		='');

		($prix*$qte>0 ? $Atotal=number_format($prix*$qte,2,',',' ') : $Atotal = '');
		($prix>0 ? $Aprix=number_format($prix,2,',',' ') : $Aprix = '');

		$verif .= "trimAll(document.formadd.qte$i.value)=='' && ";
		$supdipo .="if(parseFloat(document.formadd.qte$i.value)>parseFloat(document.formadd.dispo$i.value)){
						msg2 += '- Ligne $i: Quantité ('+document.formadd.dispo$i.value+' produit(s)) insuffisante\\n';
					}";

		$ret .= '
		<tr align="left" valign="middle">
            <td class="botBorderTd" nowrap>'.$i.' - </td>
			<td class="botBorderTd">
			<input name="code_detbonsortie'.$i.'" type="hidden"   id="code_detbonsortie'.$i.'" size="10" value="'.$code_detbonsortie.'">
			<input name="monlot'.$i.'" type="hidden"   id="monlot'.$i.'" size="10" value="'.$monlot.'">
			<input name="codeproduit'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="codeproduit'.$i.'" size="10" value="'.$codeproduit.'"></td>
            <td class="botBorderTd"><input name="produit'.$i.'" type="text"  readonly="readonly" class="formStyle" id="produit'.$i.'" value="'.(stripslashes($produit)).'"></td>
            <td class="botBorderTd"><input name="qte'.$i.'" type="text" readonly="readonly" class="formStyleFree" id="qte'.$i.'" size="10" value="'.$qte.'" >
			<input name="dispo'.$i.'" type="hidden" readonly="readonly" class="formStyleFree" id="dispo'.$i.'" size="10" value="'.$dispo.'" ></td>
			<td class="botBorderTd"><input name="unite'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="unite'.$i.'" size="10" value="'.$unite.'"></td>
			<td class="botBorderTd"><input name="prix'.$i.'" type="text" class="formStyleFree" id="prix'.$i.'" value="'.$Aprix.'" size="10" value="" ></td>
			<td class="botBorderTd"><input name="total'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="total'.$i.'" size="10" value="'.$Atotal.'" ></td>
			<td class="botBorderTd"><input name="reflot'.$i.'" type="text" readonly="readonly" class="formStyleFree" id="reflot'.$i.'" value="'.$reflot.'" size="10" value="" ></td>
			<td class="botBorderTd"><input name="dateperemp'.$i.'" readonly="readonly" type="text" class="formStyleFree" id="dateperemp'.$i.'" value="'.$dateperemp.'" size="10" value="" >
			<input name="dispo'.$i.'" type="hidden" id="dispo'.$i.'"  value=""></td>

        </tr>';
	}

	if($verif!='') {
		$verif = substr($verif,0,strlen($verif)-3);
		$fonction ="
		function tousVide(){
			if($verif) {return 1;}
			else {return 0;}
		}

		";
	}
	return array($fonction,$ret,$supdipo);
}

function lignejournalBonsortie($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])? $codeproduit = $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 	? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['qte'])		? $qte	 		= $data[$i-1]['qte'] 			: $qte			='');
		(isset($data[$i-1]['qtelvr'])	? $qtelvr 		= $data[$i-1]['qtelvr'] 		: $qtelvr			='');
		(isset($data[$i-1]['unite']) 	? $unite 		= $data[$i-1]['unite'] 			: $unite		='');
		(isset($data[$i-1]['MAGASIN'])	? $magasin		 = $data[$i-1]['MAGASIN'] 		: $magasin			='');

		(isset($data[$i-1]['valide']) && $data[$i-1]['valide']==1	? $imgCl ='<img src="../images/valider.gif" title="Validé" width="16" height="16">'	: $imgCl = '<img src="../images/encours.gif" title="En cours" width="16" height="16">');

		$ret .= '
		<tr align="left" valign="middle">
            <td class="botBorderTd" nowrap>'.$i.' - </td>
			<td class="botBorderTd"><input name="jcodeproduit'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="jcodeproduit'.$i.'" size="10" value="'.$codeproduit.'"></td>
            <td class="botBorderTd"><input name="jqtelvr'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="jqtelvr'.$i.'" size="10" value="'.$qtelvr.'" ></td>
			<td class="botBorderTd"><input name="junite'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="junite'.$i.'" size="10" value="'.$unite.'"></td>
			<td class="botBorderTd">'.$imgCl.'</td>
        </tr>';
		//<td class="botBorderTd"><select name="magasin'.$i.'"  readonly="readonly" id="magasin'.$i.'" class="formStyle"><option value="0"></option>'.selectmagasin($magasin).'</select></td>
	}
	return $ret;
}

function ligneEtatBonsortie($nbre=1, $data=array()){
	$ret = '';
	$somme =0;
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])? $codeproduit = $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 	? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['qte']) && $data[$i-1]['qte']!=0		? $qte	 		= $data[$i-1]['qte'] 			: $qte			='');
		(isset($data[$i-1]['unite']) 		? $unite 		= $data[$i-1]['unite'] 			: $unite		='');
		(isset($data[$i-1]['unite']) 		? $unite 		= $data[$i-1]['unite'] 			: $unite		='');
		(isset($data[$i-1]['prix'])			? $prix		 	= $data[$i-1]['prix'] 			: $prix			='');
		(isset($data[$i-1]['reflot'])		? $reflot	 	= $data[$i-1]['reflot'] 		: $reflot		='');

		$total = $prix*$qte;
		($total>0 ? $Atotal=number_format($total,2,',',' ') : $Atotal = '');
		($prix>0 ? $Aprix=number_format($prix,2,',',' ') : $Aprix = '');

		$ret .= '
		<tr align="left" valign="middle">
            <td class="botBorderTdall" align="center" nowrap>'.$i.' - </td>
			<!-- <td class="botBorderTdall" align="center">'.$codeproduit.'&nbsp;</td> -->
            <td class="botBorderTdall">'.stripslashes($produit).'&nbsp;</td>
			<td class="botBorderTdall" align="center">'.stripslashes($reflot).'&nbsp;</td>
            <td class="botBorderTdall" align="right">'.$qte.'&nbsp;</td>
            <td class="botBorderTdall" align="center">'.stripslashes($unite).'&nbsp;</td>
			<td class="botBorderTdall" align="right">'.$Aprix.'&nbsp;</td>
			<td class="botBorderTdall" align="right">'.$Atotal.'&nbsp;</td>
        </tr>';
		$somme +=$total;
	}
	($somme>0 ? $somme=number_format($somme,2,',',' ') : $somme = '');

	$ret .='<tr align="left" valign="middle">
            <td class="botBorderTdall" align="right" nowrap colspan="6"><strong>TOTAL GENERAL :&nbsp;&nbsp;</strong></td>
		<td class="botBorderTdall" align="right" nowrap="nowrap"><strong>'.$somme.'&nbsp;</strong></td>
        </tr>';
	return $ret;
}

?>