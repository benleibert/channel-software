<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */

//--------- UNITES -----------------------------
require_once('../lib/phpfuncLib.php');

//Nombre de ligne retourner
function nombreCommande($where=''){
	$sql = "SELECT * FROM commande INNER JOIN fournisseur ON (commande.CODE_FOUR = fournisseur.CODE_FOUR) $where;";
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

//CONSULT ORDER
function ligneConCommande($wh='', $ord='', $sens='ASC', $page=1, $nelt){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	$returnHTML = '';
	$returnTble = array();//HTML, nbreTotal,

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY commande.CDE_DATE DESC");
	//Nombre d'éléments
	$returnTble['NE'] = nombreCommande($where);
	if($returnTble['NE']>0){
		//Calcule des limites
		$i = ($page-1)*$nelt;
		$sql = "SELECT * FROM commande INNER JOIN fournisseur ON (commande.CODE_FOUR LIKE fournisseur.CODE_FOUR) $where $order LIMIT $i, $nelt;";
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
		$j=6; //Nbre d'élément dans le formulaire
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
			$d1 = frFormat($row['CDE_DATE']); //Return  $ret = array(); // DFR, TFR

			if($row['CDE_STATUT']=='0' ) {
				$imgCl = '<img src="../images/encours.gif" title="En cours" width="16" height="16">' ;
			}
			elseif ($row['CDE_STATUT']=='2' ){
				$imgCl = '<img src="../images/cancel.png" title="Annulée" width="16" height="16">' ;
			}
			else {
				$imgCl ='<img src="../images/valider.gif" title="Validée" width="16" height="16">';
			}

			$i++;  //Color and row n°
			$returnHTML .= '
			<tr align="left" valign="middle" class="'.$col.'">
	            <td class="text" align="center" >'.$i.'</td>
				<td><input type="checkbox" name="rowSelection[]" value="'.$row['CODE_COMMANDE'].'@'.$row['CDE_STATUT'].'@'.$j.'" onClick="IsValider('.$row['CDE_STATUT'].', '.$j.');"></td>
                <td class="text" align="center" >'.$imgCl.'</td>
                <td class="text" align="center">'.(stripslashes($row['REF_COMMANDE'])).'&nbsp;</td>
                <td class="text" >'.(stripslashes($row['CDE_LIBELLE'])).'&nbsp;</td>
                <td class="text" >'.(stripslashes($d1['DFR'])).'&nbsp;</td>
                <td class="text" >'.(stripslashes($row['FOUR_NOM'])).'&nbsp;</td>
                <td class="text" align="center" ><a href="dbcommande.php?do=detail&xid='.$row['CODE_COMMANDE'].'" class="morelink">'.(stripslashes('Détails')).'</a></td>
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

//ADD ORDER
function ligneaddCommande($nbre=1, $data=array()){
	$ret = '';
	$verif = '';
	$fonction ="";
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])	? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['oldcodeproduit'])? $oldcodeproduit 	= $data[$i-1]['oldcodeproduit'] 	: $oldcodeproduit	='');
		(isset($data[$i-1]['produit']) 		? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['qte'])			? $qte 			= $data[$i-1]['qte'] 			: $qte			='');
		(isset($data[$i-1]['unite']) 		? $unite 		= $data[$i-1]['unite'] 			: $unite		='');
		(isset($data[$i-1]['prix']) 		? $prix 		= $data[$i-1]['prix'] 			: $prix		='');

		($total = $prix*$qte>0 ? $total=number_format($prix*$qte,2,'.','') : $total = '');
		($prix>0 ? $prix=number_format($prix,2,'.','') : $prix = '');

		$verif .= "trimAll(document.formadd.qte$i.value)=='' && ";

		$ret .= '
		<tr align="left" valign="middle">
            <td class="botBorderTd" nowrap>'.$i.' - </td>
			<td class="botBorderTd"><input name="delete'.$i.'" type="button" class="button" title="Supprimer" value=" X " onClick="msgSupprLigne(\''.$codeproduit.'\','.$i.');"></td>
			<td class="botBorderTd"><input name="openf'.$i.'" type="button" class="button"  title="Produit" value="..." onClick="OpenWin(\'listeproduitcde.php?lg='.$i.'\',\'Liste\');"></td>
			<td class="botBorderTd"><input name="codeproduit'.$i.'" type="text" readonly="readonly" class="formStyleFree" id="codeproduit'.$i.'" size="10" value="'.$codeproduit.'" onblur="if(parseFloat(document.getElementById(\'qte'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)>0) {document.getElementById(\'total'.$i.'\').value =parseFloat(document.getElementById(\'qte'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)}" />
			<input name="oldcodeproduit'.$i.'" type="hidden" id="oldcodeproduit'.$i.'" value="'.$oldcodeproduit.'"></td>
            <td class="botBorderTd"><input name="produit'.$i.'" type="text" readonly="readonly" class="formStyle" id="produit'.$i.'" value="'.(stripslashes($produit)).'" onblur="if(parseFloat(document.getElementById(\'qte'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)>0) {document.getElementById(\'total'.$i.'\').value =parseFloat(document.getElementById(\'qte'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)}" /></td>
            <td class="botBorderTd"><input name="qte'.$i.'" type="text" class="formStyleFree" id="qte'.$i.'" size="10" value="'.$qte.'" onblur="if(!parseFloat(document.getElementById(\'qte'.$i.'\').value)){alert(\'Entrez un nombre pour la quantité\');document.getElementById(\'qte'.$i.'\').value=\'\';};if(parseFloat(document.getElementById(\'qte'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)>0) {document.getElementById(\'total'.$i.'\').value =parseFloat(document.getElementById(\'qte'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)}" /></td>
			<td class="botBorderTd"><input name="unite'.$i.'" type="text" readonly="readonly" class="formStyleFree" id="unite'.$i.'" size="10" value="'.$unite.'" onblur="if(parseFloat(document.getElementById(\'qte'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)>0) {document.getElementById(\'total'.$i.'\').value =parseFloat(document.getElementById(\'qte'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)}" /></td>
			<td class="botBorderTd"><input name="prix'.$i.'" type="text" class="formStyleFree" id="prix'.$i.'" value="'.$prix.'" size="10" value=""  onblur="if(!parseFloat(document.getElementById(\'prix'.$i.'\').value)){alert(\'Entrez un nombre pour le prix\');document.getElementById(\'prix'.$i.'\').value=\'\';};if(parseFloat(document.getElementById(\'qte'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)>0) {document.getElementById(\'total'.$i.'\').value =parseFloat(document.getElementById(\'qte'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)}" /></td>
			<td class="botBorderTd"><input name="total'.$i.'" type="text" readonly="readonly" class="formStyleFree" id="total'.$i.'" value="'.$total.'" size="10" value="" ></td>
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

//DETAIL ORDER
function lignedetailCommande($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])	? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 		? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['qte'])			? $qte 			= $data[$i-1]['qte'] 			: $qte			='');
		(isset($data[$i-1]['unite']) 		? $unite 		= $data[$i-1]['unite'] 			: $unite		='');
		(isset($data[$i-1]['prix'])			? $prix		 	= $data[$i-1]['prix'] 			: $prix			='');

		($prix>0 ? $Aprix=number_format($prix,2,',',' ') : $Aprix = '');
		($Atotal = $prix*$qte>0 ? $Atotal=number_format($prix*$qte,2,',',' ') : $total = '');
		$ret .= '
		<tr align="left" valign="middle">
            <td class="botBorderTd" nowrap>'.$i.' - </td>
			<td class="botBorderTd"><input name="codeproduit'.$i.'" type="text" readonly="readonly" class="formStyleFree" id="codeproduit'.$i.'" size="10" value="'.$codeproduit.'">
			<input name="oldcodeproduit'.$i.'" type="hidden" id="oldcodeproduit'.$i.'" value="'.$codeproduit.'"></td>
            <td class="botBorderTd"><input name="produit'.$i.'" type="text" readonly="readonly" class="formStyle" id="produit'.$i.'" value="'.(stripslashes($produit)).'"></td>
            <td class="botBorderTd"><input name="qte'.$i.'" type="text" readonly="readonly"  class="formStyleFree" id="qte'.$i.'" size="10" value="'.$qte.'"></td>
			<td class="botBorderTd"><input name="unite'.$i.'" type="text" readonly="readonly" class="formStyleFree" id="unite'.$i.'" size="10" value="'.(stripslashes($unite)).'"></td>
			<td class="botBorderTd"><input name="prix'.$i.'" type="text" readonly="readonly" class="formStyleFree" id="prix'.$i.'" size="10" value="'.stripslashes($Aprix).'"></td>
			<td class="botBorderTd"><input name="total'.$i.'" type="text" readonly="readonly" class="formStyleFree" id="total'.$i.'" size="10" value="'.$Atotal.'"></td>
        </tr>';
	}
	return $ret;
}

//DETAIL ORDER
function ligneEtatCommande($nbre=1, $data=array()){
	$ret = '';
	$somme=0;
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 	? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['qte'])			? $qte 			= $data[$i-1]['qte'] 			: $qte			='');
		(isset($data[$i-1]['unite']) 		? $unite 		= $data[$i-1]['unite'] 			: $unite		='');
		(isset($data[$i-1]['prix'])			? $prix		 	= $data[$i-1]['prix'] 		: $prix			='');

		($prix>0 ? $Aprix=number_format($prix,2,',',' ') : $Aprix = '');
		($total = $prix*$qte>0 ? $Atotal=number_format($prix*$qte,2,',',' ') : $Atotal = '');
		$ret .= '
		<tr align="left" valign="middle">
            <td class="botBorderTdall" nowrap align="center">'.$i.' - </td>
			<!-- <td class="botBorderTdall" align="center">'.$codeproduit.'&nbsp;</td> -->
            <td class="botBorderTdall">'.(stripslashes($produit)).'&nbsp;</td>
			<td class="botBorderTdall" align="center">'.(stripslashes($unite)).'&nbsp;</td>
            <td class="botBorderTdall" align="right">'.$qte.'&nbsp;</td>
			<td class="botBorderTdall" align="right">'.$Aprix.'&nbsp;</td>
			<td class="botBorderTdall" align="right">'.$Atotal.'&nbsp;</td>
        </tr>';
		$somme +=$prix*$qte;
	}
	($somme>0 ? $somme=number_format($somme,2,',',' ') : $somme = '');
	$ret .='<tr align="left" valign="middle">
            <td class="botBorderTdall" align="right" nowrap colspan="5"><strong>TOTAL GENERAL :&nbsp;&nbsp;</strong></td>
		<td class="botBorderTdall" align="right" nowrap="nowrap"><strong>'.$somme.'&nbsp;</strong></td>
        </tr>';
	return $ret;
}

//CONSULT ligne Etat Liste Order
function ligneEtatListeCommande($wh='', $ord='', $sens='ASC'){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	$returnHTML = '';
	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY commande.CDE_DATE DESC");
	//Nombre d'éléments
	$nbre = nombreCommande($where);
	if($nbre>0){
		//Calcule des limites
		//$i = ($page-1)*$nelt;  LIMIT $i, $nelt;
		$sql = "SELECT * FROM commande INNER JOIN fournisseur ON (commande.CODE_FOUR = fournisseur.CODE_FOUR) $where $order";
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

			$d1 = frFormat($row['CDE_DATE']); //Return  $ret = array(); // DFR, TFR

			if($row['CDE_STATUT']=='0' ) {
				$imgCl = '<img src="../images/encours.gif" title="En cours" width="16" height="16">' ;
			}
			elseif ($row['CDE_STATUT']=='2' ){
				$imgCl = '<img src="../images/cancel.png" title="annulée" width="16" height="16">' ;
			}
			else {
				$imgCl ='<img src="../images/valider.gif" title="Validée" width="16" height="16">';
			}

			$returnHTML .= '
			<tr align="left" valign="middle">
	            <td class="botBorderTdall" align="center">'.$i++.'</td>
                <td class="botBorderTdall" align="center" >'.$imgCl.'</td>
                <td class="botBorderTdall" align="center">'.(stripslashes($row['CODE_COMMANDE'])).'&nbsp;</td>
                <td class="botBorderTdall" >'.(stripslashes($row['CDE_LIBELLE'])).'&nbsp;</td>
                <td class="botBorderTdall" align="center">'.(stripslashes($d1['DFR'])).'&nbsp;</td>
                <td class="botBorderTdall" >'.(stripslashes($row['FOUR_NOM'])).'&nbsp;</td>
            </tr>';
			$i++;
		}

	}
	else {
	if($ilang=='1' && $ilang!='') {$returnHTML .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e...</td></tr>';}
	if($ilang=='2' && $ilang!='') {$returnHTML .= '<tr><td colspan="4" class="text">No data...</td></tr>';}
	if($ilang=='3' && $ilang!='') {$returnHTML .= '<tr><td colspan="4" class="text">Nenhum dado...</td></tr>';}
	}

	//$returnTble['L']=$returnHTML;
	return $returnHTML;
}


?>