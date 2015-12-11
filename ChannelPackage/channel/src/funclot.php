<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */

//--------- UNITES -----------------------------

//Nombre de ligne retourner
function nombreDetLivraison($where=''){
	$sql = "SELECT * FROM prd_livraison
		INNER JOIN livraison ON (prd_livraison.ID_LIVRAISON=livraison.ID_LIVRAISON)
		INNER JOIN produit ON (prd_livraison.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
		$where;";

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

function ligneConlot($wh='', $ord='', $sens='ASC', $page=1, $nelt){
	$returnHTML = '';
	$returnTble = array();//HTML, nbreTotal,

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY livraison.LVR_DATE DESC");
	//Nombre d'éléments
	$returnTble['NE'] = nombreDetLivraison($where);
	if($returnTble['NE']>0){
		//Calcule des limites
		$i = ($page-1)*$nelt;
		$sql = "SELECT * FROM prd_livraison
		INNER JOIN livraison ON (prd_livraison.ID_LIVRAISON=livraison.ID_LIVRAISON)
		INNER JOIN produit ON (prd_livraison.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
		$where $order LIMIT $i, $nelt;";
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

			if($row['LVRLOT_VALIDE']=='0' ) {
				$imgCl = '<img src="../images/encours.gif" title="En cours" width="16" height="16">' ;
			}
			elseif ($row['LVRLOT_VALIDE']=='2' ){
				$imgCl = '<img src="../images/cancel.png" title="Annulée" width="16" height="16">' ;
			}
			else {
				$imgCl ='<img src="../images/valider.gif" title="Validée" width="16" height="16">';
			}

			$i++;
			$returnHTML .= '
			<tr align="left" valign="middle" class="'.$col.'">
	            <td class="text" align="center">'.$i.'</td>
				<td><input type="checkbox" name="rowSelection[]" value="'.$row['ID_LIVRAISON'].'@'.$row['LVRLOT_VALIDE'].'@'.$j.'" onClick="IsValider('.$row['LVRLOT_VALIDE'].','.$j.');"></td>
                <td class="text" align="center">'.$imgCl.'</td>
				<td class="text">'.stripslashes($row['CODE_LIVRAISON']).'&nbsp;</td>
				<td class="text" >'.stripslashes($row['CODE_PRODUIT']).'&nbsp;</td>
				<td class="text" >'.stripslashes($row['PRD_LIBELLE']).'&nbsp;</td>
				<td class="text" >&nbsp;</td>
				<td class="text" >&nbsp;</td>
                <td class="text" align="center">'.stripslashes($row['LVR_PRDRECU'].' '.$row['LVR_UNITE']).'&nbsp;</td>
                <td class="text" align="center" nowrap="nowrap"><a href="dlot.php?do=detail&xid='.$row['ID_LIVRAISON'].'&codeprd='.$row['CODE_PRODUIT'].'" class="morelink">'.(stripslashes('Détails lot')).'</a></td>
            </tr>';

			$sql3 = "SELECT * FROM lot
			INNER JOIN livraison ON (livraison.ID_LIVRAISON=lot.ID_LIVRAISON)
			INNER JOIN produit ON (lot.CODE_PRODUIT LIKE produit.CODE_PRODUIT) WHERE lot.ID_LIVRAISON=".$row['ID_LIVRAISON'];
			$query =  $cnx->prepare($sql3); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			$k = 1;
			while($row3 = $query->fetch(PDO::FETCH_ASSOC)){
				($k%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

				$returnHTML .= '
				<tr align="left" valign="middle" class="'.$col.'">
		            <td class="text" align="center">'.$k.'</td>
	                <td class="text" align="center" colspan="3">&nbsp;</td>
					<td class="text" >'.stripslashes($row3['CODE_PRODUIT']).'&nbsp;</td>
					<td class="text" >'.stripslashes($row3['PRD_LIBELLE']).'&nbsp;</td>
					<td class="text" align="center" >'.stripslashes($row3['REF_LOT']).'&nbsp;</td>
					<td class="text" align="center" >'.stripslashes(frFormat2($row3['DATE_PEREMPTION'])).'&nbsp;</td>
	                <td class="text" align="center">'.stripslashes($row3['LOT_PRDQTE'].' '.$row3['LOT_UNITE']).'&nbsp;</td>
					<td class="text" >&nbsp;</td>
	            </tr>';
				$k++;
			}

			$j++;
		}

	}
	else {$returnHTML .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e</td></tr>';}

	$returnTble['L']=$returnHTML;
	return $returnTble;
}

function ligneEtatListeLivraison($wh='', $ord='', $sens='ASC'){
	$returnHTML = '';

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY CDE_DATE DESC");
	//Nombre d'éléments
	$nbre = nombredelivery($where);
	if($nbre>0){
		//Calcule des limites
		$sql = "SELECT * FROM livraison INNER JOIN commande ON (commande.ID_COMMANDE = livraison.ID_COMMANDE) $where $order;";
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
			$d1 = frFormat($row['CDE_DATE']); //Return  $ret = array(); // DFR, TFR
			$d2 = frFormat($row['LVR_DATE']); //Return  $ret = array(); // DFR, TFR

			if($row['LVR_VALIDE']=='0' ) {
				$imgCl = '<img src="../images/encours.gif" title="En cours" width="16" height="16">' ;
			}
			elseif ($row['LVR_VALIDE']=='2' ){
				$imgCl = '<img src="../images/cancel.png" title="Annulée" width="16" height="16">' ;
			}
			else {
				$imgCl ='<img src="../images/valider.gif" title="Validée" width="16" height="16">';
			}
			$returnHTML .= '
			<tr align="left" valign="middle" class="'.$col.'">
	            <td class="botBorderTdall" align="center">'.$i.'</td>
                <td class="botBorderTdall" align="center">'.$imgCl.'</td>
				<td class="botBorderTdall" >'.(stripslashes('Cde n°'.$row['CODE_COMMANDE']. ' du '.$d1['DFR'])).'&nbsp;</td>
                <td class="botBorderTdall" >'.(stripslashes($row['CDE_LIBELLE'])).'&nbsp;</td>
				<td class="botBorderTdall" align="center">'.(stripslashes($row['CODE_LIVRAISON'])).'&nbsp;</td>
                <td class="botBorderTdall" align="center">'.(stripslashes($d2['DFR'])).'&nbsp;</td>
				<td class="botBorderTdall" >'.(stripslashes(getFournisseur($row['CODE_FOUR']))).'&nbsp;</td>
             </tr>';
			$i++;
		}

	}
	else {$returnHTML .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e</td></tr>';}

	return $returnHTML;
}

function ligneaddLot($nbre=1, $data=array()){
	$ret = '';
	$verif ='';
	$fonction='';
	if ($nbre==0) {
		$ret .= '<tr align="left" valign="middle"><td class="botBorderTd" colspan="6">'.(stripslashes('Cette commande a été entièrement livrée, veuillez vérifier dans la liste des livraisons ')).'</td></tr>';
	}
	else
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['reflot'])		? $reflot		= $data[$i-1]['reflot'] 		: $reflot	='');
		(isset($data[$i-1]['codeproduit'])	? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 		? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['qte'])			? $qte 			= $data[$i-1]['qte'] 			: $qte			='');
		(isset($data[$i-1]['dateperempt'])	? $dateperempt 	= $data[$i-1]['dateperempt'] 	: $dateperempt	='');
		(isset($data[$i-1]['unite']) 		? $unite 		= $data[$i-1]['unite'] 			: $unite		='');

		$verif .= "trimAll(document.formadd.qte$i.value)=='' && trimAll(document.formadd.reflot$i.value)=='' && trimAll(document.formadd.dateperempt$i.value)=='' && ";

		$ret .= '
		<tr align="left" valign="middle">
            <td class="botBorderTd" nowrap>'.$i.' - </td>
			<td class="botBorderTd"><input name="delete'.$i.'" type="button" class="button" title="Supprimer" value=" X " onClick="msgSupprLigne(\''.$codeproduit.'\','.$i.');"></td>
			<td class="botBorderTd"><input name="openf'.$i.'" type="button" class="button"  title="Produit" value="..." onClick="OpenWin(\'listeproduitcde.php?lg='.$i.'\',\'Liste\');"></td>
			<input name="oldreflot'.$i.'" type="hidden"   id="oldreflot'.$i.'" size="10" value="'.$reflot.'"></td>
			<td class="botBorderTd"><input name="codeproduit'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="codeproduit'.$i.'" size="10" value="'.$codeproduit.'">
			<input name="oldcodeproduit'.$i.'" type="hidden"   id="oldcodeproduit'.$i.'" size="10" value="'.$codeproduit.'"></td>
            <td class="botBorderTd"><input name="produit'.$i.'" type="text"  readonly="readonly" class="formStyle" id="produit'.$i.'" value="'.(stripslashes($produit)).'"></td>
            <td class="botBorderTd"><input name="reflot'.$i.'" type="text"  class="formStyleFree" id="reflot'.$i.'" size="10" value="'.$reflot.'">
			<td class="botBorderTd"><input name="dateperempt'.$i.'" type="text"  class="formStyleFree" id="dateperempt'.$i.'" size="10" value="'.$dateperempt.'" ></td>
			<td class="botBorderTd"><input name="qte'.$i.'" type="text" class="formStyleFree" id="qte'.$i.'" size="10" value="'.$qte.'" ></td>
			<td class="botBorderTd"><input name="unite'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="unite'.$i.'" size="10" value="'.$unite.'">
			<input name="dispo'.$i.'" type="hidden" id="dispo'.$i.'"  value=""></td>

        </tr>';

		$fonction.= "
			$(function() {
				$('#dateperempt$i').datepicker({
					showButtonPanel: true
				});
			});";
	}

	if($verif){
		$verif = substr($verif,0,strlen($verif)-3);
		$fonction .="


		function tousVide(){
		if($verif) {return 1;}
		else {return 0;}
		}
		";
	}
	return array($fonction,$ret);
}


function ligneaddLivraison2($nbre=1, $data=array()){
	$ret = '';
	$verif = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])	? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 		? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['qte'])			? $qte 			= $data[$i-1]['qte'] 			: $qte			='');
		(isset($data[$i-1]['qtelvr'])		? $qtelvr 		= $data[$i-1]['qtelvr'] 		: $qtelvr			='');
		(isset($data[$i-1]['unite']) 		? $unite 		= $data[$i-1]['unite'] 			: $unite		='');

		$verif .= "trimAll(document.formadd.qterecu$i.value)=='' && ";

		$ret .= '
		<tr align="left" valign="middle">
            <td class="botBorderTd" nowrap>'.$i.' - </td>
			<td class="botBorderTd"><input name="delete'.$i.'" type="button" class="button" title="Supprimer" value=" X " onClick="msgSupprLigne(\''.$codeproduit.'\','.$i.');"></td>
			<td class="botBorderTd"><input name="openf'.$i.'" type="button" class="button"  title="Produit" value="..." onClick="OpenWin(\'listeproduit.php?lg='.$i.'\',\'Liste\');"></td>
			<td class="botBorderTd"><input name="codeproduit'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="codeproduit'.$i.'" size="10" value="'.$codeproduit.'">
			<input name="oldcodeproduit'.$i.'" type="hidden"   id="oldcodeproduit'.$i.'" size="10" value="'.$codeproduit.'"></td>
            <td class="botBorderTd"><input name="produit'.$i.'" type="text"  readonly="readonly" class="formStyle" id="produit'.$i.'" value="'.(stripslashes($produit)).'"></td>
            <td class="botBorderTd"><input name="qterecu'.$i.'" type="text" class="formStyleFree" id="qterecu'.$i.'" value="'.$qtelvr.'" size="10" value="" onBlur="checkQte(\'qte'.$i.'\',\'qterecu'.$i.'\')"></td>
			<td class="botBorderTd"><input name="unite'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="unite'.$i.'" size="10" value="'.$unite.'">
			<input name="dispo'.$i.'" type="hidden" id="dispo'.$i.'"  value=""></td>      </tr>';
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

function lignedetailLivraison($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])? $codeproduit = $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 	? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['qte'])		? $qte	 		= $data[$i-1]['qte'] 			: $qte			='');
		(isset($data[$i-1]['qtelvr'])	? $qtelvr 		= $data[$i-1]['qtelvr'] 		: $qtelvr			='');
		(isset($data[$i-1]['unite']) 	? $unite 		= $data[$i-1]['unite'] 			: $unite		='');
		(isset($data[$i-1]['MAGASIN'])	? $magasin		 = $data[$i-1]['MAGASIN'] 		: $magasin			='');
		//(isset($data[$i-1]['total'])		? $total 		= $data[$i-1]['total'] 			: $total		='');

		$ret .= '
		<tr align="left" valign="middle">
            <td class="botBorderTd" nowrap>'.$i.' - </td>
			<td class="botBorderTd"><input name="codeproduit'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="codeproduit'.$i.'" size="10" value="'.$codeproduit.'"></td>
            <td class="botBorderTd"><input name="produit'.$i.'" type="text"  readonly="readonly" class="formStyle" id="produit'.$i.'" value="'.(stripslashes($produit)).'"></td>
            <td class="botBorderTd"><input name="qtecde'.$i.'" type="text" readonly="readonly" class="formStyleFree" id="qtecde'.$i.'" size="10" value="'.$qte.'" ></td>
			<td class="botBorderTd"><input name="qtelvr'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="qtelvr'.$i.'" size="10" value="'.$qtelvr.'" ></td>
			<td class="botBorderTd"><input name="unite'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="unite'.$i.'" size="10" value="'.$unite.'"></td>

        </tr>';
		//<td class="botBorderTd"><select name="magasin'.$i.'"  readonly="readonly" id="magasin'.$i.'" class="formStyle"><option value="0"></option>'.selectmagasin($magasin).'</select></td>
	}
	return $ret;
}


function lignejournalLivraison($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])? $codeproduit = $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 	? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['qte'])		? $qte	 		= $data[$i-1]['qte'] 			: $qte			='');
		(isset($data[$i-1]['qtelvr'])	? $qtelvr 		= $data[$i-1]['qtelvr'] 		: $qtelvr			='');
		(isset($data[$i-1]['unite']) 	? $unite 		= $data[$i-1]['unite'] 			: $unite		='');
		(isset($data[$i-1]['MAGASIN'])	? $magasin		 = $data[$i-1]['MAGASIN'] 		: $magasin			='');
		//(isset($data[$i-1]['total'])		? $total 		= $data[$i-1]['total'] 			: $total		='');

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

function ligneEtatLivraison($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])? $codeproduit = $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 	? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['qte']) && $data[$i-1]['qte']!=0		? $qte	 		= $data[$i-1]['qte'] 			: $qte			='');
		(isset($data[$i-1]['qtelvr']) && $data[$i-1]['qtelvr']!=0	? $qtelvr 		= $data[$i-1]['qtelvr'] 		: $qtelvr			='');
		(isset($data[$i-1]['unite']) 	? $unite 		= $data[$i-1]['unite'] 			: $unite		='');
		(isset($data[$i-1]['MAGASIN'])	? $magasin		 = $data[$i-1]['MAGASIN'] 		: $magasin			='');
		//(isset($data[$i-1]['total'])		? $total 		= $data[$i-1]['total'] 			: $total		='');

		$ret .= '
		<tr align="left" valign="middle">
            <td class="botBorderTdall" align="center" nowrap>'.$i.' - </td>
			<!-- <td class="botBorderTdall" align="center">'.$codeproduit.'&nbsp;</td> -->
            <td class="botBorderTdall">'.(stripslashes($produit)).'&nbsp;</td>
            <td class="botBorderTdall" align="center">'.$qte.'&nbsp;</td>
			<td class="botBorderTdall" align="center">'.$qtelvr.'&nbsp;</td>
			<td class="botBorderTdall" align="center">'.(stripslashes($unite)).'&nbsp;</td>
			<td class="botBorderTdall">'.(stripslashes(getmagasin($magasin))).'&nbsp;</td>
        </tr>';
		//<td class="botBorderTdall">'.(stripslashes(getmagasin($magasin))).'&nbsp;</td>
	}
	return $ret;
}

?>