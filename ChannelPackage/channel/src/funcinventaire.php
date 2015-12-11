<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */

//--------- UNITES -----------------------------

//Nombre de ligne retourner
function nombreInventaire($where=''){
	$sql = "SELECT * FROM inventaire  $where;";
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

function ligneConInventarie($wh='', $ord='', $sens='ASC', $page=1, $nelt){
	$returnHTML = '';
	$returnTble = array();//HTML, nbreTotal,

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY INV_DATE DESC");
	//Nombre d'éléments
	$returnTble['NE'] = nombreInventaire($where);
	if($returnTble['NE']>0){
		//Calcule des limites
		$i = ($page-1)*$nelt;
		$sql = "SELECT * FROM inventaire  $where $order LIMIT $i, $nelt;";
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
			if($row['INV_VALID']=='0' ) {
				$imgCl = '<img src="../images/encours.gif" title="En cours" width="16" height="16">' ;
			}
			elseif ($row['INV_VALID']=='2' ){
				$imgCl = '<img src="../images/cancel.png" title="Annulée" width="16" height="16">' ;
			}
			else {
				$imgCl ='<img src="../images/valider.gif" title="Validée" width="16" height="16">';
			}

			$returnHTML .= '
			<tr align="left" valign="middle" class="'.$col.'">
	            <td><input type="checkbox" name="rowSelection[]" value="'.$row['CODE_INVENTAIRE'].'@'.$row['INV_VALID'].'" onClick="IsValider('.$row['INV_VALID'].','.$j.');"></td>
                <td class="text" align="center">'.$imgCl.'</td>
				<td height="22" class="text">'.(stripslashes($row['CODE_INVENTAIRE'])).'</td>
                <td class="text" >'.(stripslashes(frFormat2($row['INV_DATE']))).'</td>
                <td class="text" >'.(stripslashes($row['INV_LIBELLE'])).'</td>
                <td class="text" align="center" nowrap="nowrap"><a href="dbinventaire.php?do=detail&xid='.$row['CODE_INVENTAIRE'].'" class="morelink">'.(stripslashes('Détails inventaire')).'</a>
            </tr>';
			$i++;
			$j++;
		}

	}
	else {$returnHTML .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e</td></tr>';}

	$returnTble['L']=$returnHTML;
	return $returnTble;
}

function ligneEtatListeInventarie($wh='', $ord='', $sens='ASC'){
	$returnHTML = '';
	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY INV_DATE DESC");
	//Nombre d'éléments
	$nbre = nombreInventaire($where);
	if($nbre>0){

		$sql = "SELECT * FROM inventaire  $where $order;";
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
			($row['INV_VALID']=='0' ? $imgCl = '<img src="../images/encours.gif" title="En cours" width="16" height="16">' : $imgCl ='<img src="../images/valider.gif" title="Validée" width="16" height="16">');

			$returnHTML .= '
			<tr align="left" valign="middle">
	            <td class="botBorderTdall" align="center">'.$i.'</td>
                <td class="botBorderTdall" align="center">'.$imgCl.'</td>
				<td height="22" class="botBorderTdall">'.(stripslashes($row['CODE_INVENTAIRE'])).'&nbsp;</td>
                <td class="botBorderTdall" >'.(stripslashes(frFormat2($row['INV_DATE']))).'&nbsp;</td>
                <td class="botBorderTdall" >'.(stripslashes($row['INV_LIBELLE'])).'&nbsp;</td>
            </tr>';
			$i++;
		}

	}
	else {$returnHTML .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e</td></tr>';}

	return $returnHTML;
}

function ligneaddInventaire($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['code_detinventaire'])	? $code_detinventaire 	= $data[$i-1]['code_detinventaire'] : $code_detinventaire	='');
		(isset($data[$i-1]['reflot'])		? $reflot 		= $data[$i-1]['reflot'] 	: $reflot		='');
		(isset($data[$i-1]['monlot'])		? $monlot 		= $data[$i-1]['monlot'] 	: $monlot		='');
		(isset($data[$i-1]['dateperemp'])	? $dateperemp 	= $data[$i-1]['dateperemp']	: $dateperemp	='');
		(isset($data[$i-1]['codeproduit'])	? $codeproduit 	= $data[$i-1]['codeproduit']: $codeproduit	='');
		(isset($data[$i-1]['produit']) 		? $produit 		= $data[$i-1]['produit'] 	: $produit		='');
		(isset($data[$i-1]['stockst'])		? $stockst 		= $data[$i-1]['stockst'] 	: $stockst		='');
		(isset($data[$i-1]['stocksp'])		? $stocksp 		= $data[$i-1]['stocksp'] 	: $stocksp		='');
		(isset($data[$i-1]['unite']) 		? $unite 		= $data[$i-1]['unite'] 		: $unite		='');
		(isset($data[$i-1]['prix']) 		? $prix 		= $data[$i-1]['prix'] 		: $prix			='');

		($prix*$stocksp>0 ? $total=number_format($prix*$stocksp,0,',',' ') : $total = '');

		$ret .= '
		<tr align="left" valign="middle">
            <td class="botBorderTd" nowrap>'.$i.' - </td>
			<td class="botBorderTd"><input name="codeproduit'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="codeproduit'.$i.'" size="10" value="'.$codeproduit.'">
			<input name="code_detinventaire'.$i.'" type="hidden"   id="code_detinventaire'.$i.'" size="10" value="'.$code_detinventaire.'">
			<input name="oldcodeproduit'.$i.'" type="hidden"   id="oldcodeproduit'.$i.'" size="10" value="'.$codeproduit.'"></td>
            <td class="botBorderTd"><input name="produit'.$i.'" type="text"  readonly="readonly" class="formStyle" id="produit'.$i.'" value="'.(stripslashes($produit)).'"></td>
            <td class="botBorderTd"><input name="reflot'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="reflot'.$i.'" size="10" value="'.$reflot.'">
			<input name="monlot'.$i.'" type="hidden"  readonly="readonly" class="formStyleFree" id="monlot'.$i.'" size="10" value="'.$monlot.'"></td>
			<td class="botBorderTd"><input name="dateperemp'.$i.'" readonly="readonly"  type="text" class="formStyleFree" id="dateperemp'.$i.'" size="10" value="'.$dateperemp.'"></td>
			<td class="botBorderTd"><input name="stockstheorique'.$i.'" readonly="readonly"  type="text" class="formStyleFree" id="stockstheorique'.$i.'" size="10" value="'.$stockst.'"></td>
			<td class="botBorderTd"><input name="stocksphysique'.$i.'" type="text" class="formStyleFree" id="stocksphysique'.$i.'" size="10" value="'.$stocksp.'" onblur="if(parseFloat(document.getElementById(\'stocksphysique'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)>0) {document.getElementById(\'total'.$i.'\').value =parseFloat(document.getElementById(\'stocksphysique'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)}" /></td>
			<td class="botBorderTd"><input name="prix'.$i.'" type="text" class="formStyleFree" id="prix'.$i.'" size="10" value="'.$prix.'"></td>
			<td class="botBorderTd"><input name="total'.$i.'" type="text" readonly="readonly" class="formStyleFree" id="total'.$i.'" value="'.$total.'" size="10" value="" ></td>
			<td class="botBorderTd"><input name="unite'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="unite'.$i.'" size="10" value="'.$unite.'">
			<input name="stockstheorique'.$i.'" type="hidden"  class="formStyleFree" id="stockstheorique'.$i.'" size="10" value="'.$stockst.'"></td>
        </tr>';
		//<td class="botBorderTd"><input name="stockstheorique'.$i.'" type="text" readonly="readonly" class="formStyleFree" id="stockstheorique'.$i.'" size="10" value="'.$stockst.'"></td>

	}
	return $ret;
}

function lignejournalInventaire($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])? $codeproduit = $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 	? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['qte'])		? $qte	 		= $data[$i-1]['qte'] 			: $qte			='');
		(isset($data[$i-1]['unite']) 	? $unite 		= $data[$i-1]['unite'] 			: $unite		='');
		(isset($data[$i-1]['MAGASIN'])	? $magasin		 = $data[$i-1]['MAGASIN'] 		: $magasin			='');

		(isset($data[$i-1]['valide']) && $data[$i-1]['valide']==1	? $imgCl ='<img src="../images/valider.gif" title="Validé" width="16" height="16">'	: $imgCl = '<img src="../images/encours.gif" title="En cours" width="16" height="16">');

		$ret .= '
		<tr align="left" valign="middle">
            <td class="botBorderTd" nowrap>'.$i.' - </td>
			<td class="botBorderTd"><input name="jcodeproduit'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="jcodeproduit'.$i.'" size="10" value="'.$codeproduit.'"></td>
            <td class="botBorderTd"><input name="qte'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="qte'.$i.'" size="10" value="'.$qte.'" ></td>
			<td class="botBorderTd"><input name="junite'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="junite'.$i.'" size="10" value="'.$unite.'"></td>
			<td class="botBorderTd">'.$imgCl.'</td>
        </tr>';
		//<td class="botBorderTd"><select name="magasin'.$i.'"  readonly="readonly" id="magasin'.$i.'" class="formStyle"><option value="0"></option>'.selectmagasin($magasin).'</select></td>
	}
	return $ret;
}

function lignedetailInventaire($nbre=1, $statut, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['monlot'])			? $monlot 			= $data[$i-1]['monlot'] 		: $monlot	='');
		(isset($data[$i-1]['reflot'])		? $reflot 		= $data[$i-1]['reflot'] 	: $reflot		='');
		(isset($data[$i-1]['produit']) 	? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['stockst'])	? $stockst 		= $data[$i-1]['stockst'] 		: $stockst		='');
		(isset($data[$i-1]['stocksp'])	? $stocksp 		= $data[$i-1]['stocksp'] 		: $stocksp		='');
		(isset($data[$i-1]['unite']) 	? $unite 		= $data[$i-1]['unite'] 			: $unite		='');
		$ecart =  $stocksp - $stockst;

		$ret .= '
		<tr align="left" valign="middle">
            <td class="botBorderTd" nowrap>'.$i.' - </td>
			<!-- <td class="botBorderTd"><input name="openf'.$i.'" type="button" class="button"  value="..." onClick="OpenWin(\'listearticlescde.php?lg='.$i.'\',\'Liste\');"></td> -->
			<td class="botBorderTd"><input name="codeproduit'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="codeproduit'.$i.'" size="10" value="'.$codeproduit.'">
			<input name="oldcodeproduit'.$i.'" type="hidden"   id="oldcodeproduit'.$i.'" size="10" value="'.$codeproduit.'"></td>
            <td class="botBorderTd"><input name="reflot'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="reflot'.$i.'" size="10" value="'.$reflot.'">
			<td class="botBorderTd"><input name="produit'.$i.'" type="text"  readonly="readonly" class="formStyle" id="produit'.$i.'" value="'.(stripslashes($produit)).'"></td>';

			$ret .= '<td class="botBorderTd"><input name="stockstheorique'.$i.'" type="text" readonly="readonly" class="formStyleFree" id="stockstheorique'.$i.'" size="10" value="'.$stockst.'"></td>';
			//($statut==1 ? $ret .= '<td class="botBorderTd"><input name="stockstheorique'.$i.'" type="text" readonly="readonly" class="formStyleFree" id="stockstheorique'.$i.'" size="10" value="'.$stockst.'"></td>' : $ret .= '<td class="botBorderTd">&nbsp;</td>');
			$ret .= '<td class="botBorderTd"><input name="stocksphysique'.$i.'" type="text" readonly="readonly" class="formStyleFree" id="stocksphysique'.$i.'" size="10" value="'.$stocksp.'"></td>';
			($statut==1 ? $ret .= '<td class="botBorderTd"><input name="stockstheorique'.$i.'" type="text" readonly="readonly" class="formStyleFree" id="stockstheorique'.$i.'" size="10" value="'.$ecart.'"></td>' : $ret .= '<td class="botBorderTd">&nbsp;</td>');
			$ret .= '<td class="botBorderTd"><input name="unite'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="unite'.$i.'" size="10" value="'.$unite.'"></td>
        </tr>';
	}
	return $ret;
}

function ligneFicheInventaire($nbre=1, $statut, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 	? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['reflot']) 	? $reflot 		= $data[$i-1]['reflot'] 		: $reflot		='');
		(isset($data[$i-1]['stockst'])	? $stockst 		= $data[$i-1]['stockst'] 		: $stockst		='');
		(isset($data[$i-1]['stocksp'])	? $stocksp 		= $data[$i-1]['stocksp'] 		: $stocksp		='');
		(isset($data[$i-1]['unite']) 	? $unite 		= $data[$i-1]['unite'] 			: $unite		='');
		$ecart = $stocksp - $stockst;

		$ret .= '
		<tr align="left" valign="middle">
            <td class="botBorderTd" nowrap>'.$i.' - </td>
			<!-- <td class="botBorderTd"><input name="openf'.$i.'" type="button" class="button"  value="..." onClick="OpenWin(\'listearticlescde.php?lg='.$i.'\',\'Liste\');"></td> -->
			<td class="botBorderTd"><input name="codeproduit'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="codeproduit'.$i.'" size="10" value="'.$codeproduit.'">
			<td class="botBorderTd"><input name="reflot'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="reflot'.$i.'" size="10" value="'.$reflot.'">
			<input name="oldcodeproduit'.$i.'" type="hidden"   id="oldcodeproduit'.$i.'" size="10" value="'.$codeproduit.'"></td>
            <td class="botBorderTd"><input name="produit'.$i.'" type="text"  readonly="readonly" class="formStyle" id="produit'.$i.'" value="'.(stripslashes($produit)).'"></td>';

		($statut==1 ? $ret .= '<td class="botBorderTd"><input name="stockstheorique'.$i.'" type="text" readonly="readonly" class="formStyleFree" id="stockstheorique'.$i.'" size="10" value="'.$stockst.'"></td>' : $ret .= '<td class="botBorderTd">&nbsp;</td>');

		$ret .= '<td class="botBorderTd"><input name="stocksphysique'.$i.'" type="text" readonly="readonly" class="formStyleFree" id="stocksphysique'.$i.'" size="10" value="'.$stocksp.'"></td>';

		($statut==1 ? $ret .= '<td class="botBorderTd"><input name="stockstheorique'.$i.'" type="text" readonly="readonly" class="formStyleFree" id="stockstheorique'.$i.'" size="10" value="'.$ecart.'"></td>' : $ret .= '<td class="botBorderTd">&nbsp;</td>');

		$ret .= '<td class="botBorderTd"><input name="unite'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="unite'.$i.'" size="10" value="'.$unite.'"></td>
        </tr>';
	}
	return $ret;
}

//DETAIL ORDER
function ligneEtatInventaire($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['monlot'])			? $monlot 			= $data[$i-1]['monlot'] 		: $monlot	='');
		(isset($data[$i-1]['codeproduit'])? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 	? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['stockst'])			? $stockst 			= $data[$i-1]['stockst'] 			: $stockst			='');
		(isset($data[$i-1]['stockst'])			? $stockst 			= $data[$i-1]['stockst'] 			: $stockst			='');
		(isset($data[$i-1]['stocksp'])			? $stocksp 			= $data[$i-1]['stocksp'] 			: $stocksp			='');
		(isset($data[$i-1]['unite']) 		? $unite 		= $data[$i-1]['unite'] 			: $unite		='');
		(isset($data[$i-1]['prixUnit'])		? $prix		 	= $data[$i-1]['prixUnit'] 		: $prix			='');
		(isset($data[$i-1]['reflot']) 		? $reflot 		= $data[$i-1]['reflot'] 			: $reflot		='');
		$ecart = $stocksp- $stockst;

		$ret .= '
		<tr align="left" valign="middle">
            <td class="botBorderTdall" nowrap align="center">'.$i.' - </td>
			<!-- <td class="botBorderTdall" align="center">'.$codeproduit.'&nbsp;</td> -->
            <td class="botBorderTdall">'.(stripslashes($produit)).'&nbsp;</td>
            <td class="botBorderTdall">'.(stripslashes($reflot)).'&nbsp;</td>
            <td class="botBorderTdall" align="right">'.$stockst.'&nbsp;</td>
			<td class="botBorderTdall" align="right">'.$stocksp.'&nbsp;</td>
			<td class="botBorderTdall" align="right">'.$ecart.'&nbsp;</td>
			<td class="botBorderTdall" align="center">'.(stripslashes($unite)).'&nbsp;</td>
 			<td class="botBorderTdall" align="center"></td>
       </tr>';
	}
	return $ret;
}

function ligneEtatFicheInventaire($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['reflot'])			? $reflot 		= $data[$i-1]['reflot'] 	: $reflot		='');
		(isset($data[$i-1]['codeproduit'])		? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 			? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['stockst'])			? $stockst 			= $data[$i-1]['stockst'] 			: $stockst			='');
		(isset($data[$i-1]['stockst'])			? $stockst 			= $data[$i-1]['stockst'] 			: $stockst			='');
		(isset($data[$i-1]['stocksp'])			? $stocksp 			= $data[$i-1]['stocksp'] 			: $stocksp			='');
		(isset($data[$i-1]['unite']) 			? $unite 		= $data[$i-1]['unite'] 			: $unite		='');
		(isset($data[$i-1]['prixUnit'])			? $prix		 	= $data[$i-1]['prixUnit'] 		: $prix			='');


		$ret .= '
		<tr align="left" valign="middle">
            <td class="botBorderTdall" nowrap align="center">'.$i.' - </td>
			<td class="botBorderTdall" align="left">'.$codeproduit.'&nbsp;</td>
            <td class="botBorderTdall">'.stripslashes($produit).'&nbsp;</td>
			<td class="botBorderTdall" align="left">'.stripslashes($reflot).'&nbsp;</td>
			<td class="botBorderTdall" align="center">'.stripslashes($unite).'&nbsp;</td>
			<td class="botBorderTdall" align="left">&nbsp;</td>
			<td class="botBorderTdall" align="left">&nbsp;</td>
 			<td class="botBorderTdall" align="center"></td>
        </tr>';
	}
	return $ret;
}

?>
