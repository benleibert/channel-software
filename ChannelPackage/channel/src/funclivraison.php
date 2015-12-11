<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */

//--------- UNITES -----------------------------

//Nombre de ligne retourner
function nombreLivraison($where=''){
	$sql = "SELECT * FROM livraison LEFT JOIN commande ON (livraison.CODE_COMMANDE=commande.CODE_COMMANDE) $where;";
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

function ligneConLivraison($wh='', $ord='', $sens='ASC', $page=1, $nelt){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	$returnHTML = '';
	$returnTble = array();//HTML, nbreTotal,

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY livraison.LVR_DATE DESC");
	//Nombre d'éléments
	$returnTble['NE'] = nombreLivraison($where);
	if($returnTble['NE']>0){
		//Calcule des limites
		$i = ($page-1)*$nelt;
		$sql = "SELECT livraison.*, commande.CDE_DATE, commande.CODE_COMMANDE, fournisseur.FOUR_NOM  FROM livraison INNER JOIN fournisseur ON (fournisseur.CODE_FOUR=livraison.CODE_FOUR)
		LEFT JOIN commande ON (livraison.CODE_COMMANDE=commande.CODE_COMMANDE) $where $order LIMIT $i, $nelt;";
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

			(!is_null($row['CODE_COMMANDE']) ? $ref =$row['CODE_COMMANDE'].' du '.$d1['DFR'] : $ref='');

			$i++;
			$returnHTML .= '
			<tr align="left" valign="middle" class="'.$col.'">
	            <td class="text" align="center">'.$i.'</td>
				<td><input type="checkbox" name="rowSelection[]" value="'.$row['CODE_LIVRAISON'].'@'.$row['LVR_VALIDE'].'@'.$j.'" onClick="IsValider('.$row['LVR_VALIDE'].','.$j.');"></td>
                <td class="text" align="center">'.$imgCl.'</td>
				<td class="text">'.stripslashes($ref).'&nbsp;</td>
                <td class="text" >'.stripslashes($row['LVR_LIBELLE']).'&nbsp;</td>
				<td class="text" >'.stripslashes($row['FOUR_NOM']).'&nbsp;</td>
				<td class="text" >'.stripslashes($row['CODE_LIVRAISON']).'&nbsp;</td>
                <td class="text" >'.stripslashes($d2['DFR']).'&nbsp;</td>
                <td class="text" align="center" nowrap="nowrap">
                <a href="dblivraison.php?do=detail&xid='.$row['CODE_LIVRAISON'].'" class="morelink">'.(stripslashes('Détails')).'</a>
				| <a href="dblivraison.php?do=journal&xid='.$row['CODE_LIVRAISON'].'" class="morelink">'.(stripslashes('Journal')).'</a></td>
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

function ligneEtatListeLivraison($wh='', $ord='', $sens='ASC'){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	$returnHTML = '';

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY CDE_DATE DESC");
	//Nombre d'éléments
	$nbre = nombreLivraison($where);
	if($nbre>0){
		//Calcule des limites
		$sql = "SELECT livraison.*, commande.CDE_DATE, commande.CODE_COMMANDE, fournisseur.FOUR_NOM  FROM livraison INNER JOIN fournisseur ON (fournisseur.CODE_FOUR=livraison.CODE_FOUR)
		LEFT JOIN commande ON (livraison.CODE_COMMANDE=commande.CODE_COMMANDE)  $where $order;";
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

			(isset($row['CODE_COMMANDE']) && $row['CODE_COMMANDE']!= '' ? $cde = 'Cde n°'.$row['CODE_COMMANDE']. ' du '.$d1['DFR'] : $cde = "");

			$returnHTML .= '
			<tr align="left" valign="middle" class="'.$col.'">
	            <td class="botBorderTdall" align="center">'.$i.'</td>
                <td class="botBorderTdall" align="center">'.$imgCl.'</td>
				<td class="botBorderTdall" >'.stripslashes($cde).'&nbsp;</td>
                <td class="botBorderTdall" >'.stripslashes($row['LVR_LIBELLE']).'&nbsp;</td>
				<td class="botBorderTdall" align="center">'.stripslashes($row['CODE_LIVRAISON']).'&nbsp;</td>
                <td class="botBorderTdall" align="center">'.stripslashes($d2['DFR']).'&nbsp;</td>
				<td class="botBorderTdall" >'.stripslashes(getFournisseur($row['CODE_FOUR'])).'&nbsp;</td>
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

function ligneaddLivraison1($nbre=1, $data=array()){
	$ret = '';
	$verif = '';
	$lotvide ='';
	$fonction ='';


	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['code_detlivraison'])	? $code_detlivraison= $data[$i-1]['code_detlivraison'] : $code_detlivraison	='');
		(isset($data[$i-1]['monlot'])				? $monlot 			= $data[$i-1]['monlot'] 		: $monlot	='');
		(isset($data[$i-1]['codeproduit'])			? $codeproduit 		= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['oldcodeproduit'])		? $oldcodeproduit 	= $data[$i-1]['oldcodeproduit'] : $oldcodeproduit	='');
		(isset($data[$i-1]['produit']) 				? $produit 			= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['qte'])					? $qte 				= $data[$i-1]['qte'] 			: $qte			='');
		(isset($data[$i-1]['qtelvr'])				? $qtelvr 			= $data[$i-1]['qtelvr'] 		: $qtelvr		='');
		(isset($data[$i-1]['unite']) 				? $unite 			= $data[$i-1]['unite'] 			: $unite		='');
		(isset($data[$i-1]['prix']) 				? $prix 			= $data[$i-1]['prix'] 			: $prix		    ='');
		(isset($data[$i-1]['reflot']) 				? $reflot 			= $data[$i-1]['reflot'] 		: $reflot		='');
		(isset($data[$i-1]['dateperemp']) 			? $dateperemp 		= $data[$i-1]['dateperemp'] 	: $dateperemp	='');

		($prix*$qtelvr>0 ? $total=number_format($prix*$qtelvr,2,'.','') : $total = '');
		($prix>0 ? $prix=number_format($prix,2,'.','') : $prix = '');
		$verif .= "trimAll(document.formadd.qtelvr$i.value)=='' && ";
		//$dateperemp = substr($dateperemp,0,strlen($dateperemp)-3 );
		$lotvide .= "if (trimAll(document.formadd.codeproduit$i.value)!=''  && trimAll(document.formadd.reflot$i.value)=='') { msg +='- Veuillez saisir la référence du lot (ligne $i).\\n';}\n";
		$lotvide .= "if (trimAll(document.formadd.codeproduit$i.value)!=''  && trimAll(document.formadd.dateperemp$i.value)=='') { msg +='- Veuillez saisir la date de péremption du lot (ligne $i).\\n';}\n";

		$ret .= '
		<tr align="left" valign="middle">
            <td class="botBorderTd" nowrap>'.$i.' - </td>
			<td class="botBorderTd"><input name="delete'.$i.'" type="button" class="button" title="Supprimer" value=" X " onClick="msgSupprLigne(\''.$codeproduit.'\','.$i.');" onblur="if(parseFloat(document.getElementById(\'qtelvr'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)>0) {document.getElementById(\'total'.$i.'\').value =parseFloat(document.getElementById(\'qtelvr'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)}" /></td>
			<td class="botBorderTd"><input name="openf'.$i.'" type="button" class="button"  title="Produit" value="..." onClick="OpenWin(\'listeproduitlivr.php?lg='.$i.'\',\'Liste\');">
			<input name="code_detlivraison'.$i.'" type="hidden"   id="code_detlivraison'.$i.'" size="10" value="'.$code_detlivraison.'">
			<input name="monlot'.$i.'" type="hidden"   id="monlot'.$i.'" size="10" value="'.$monlot.'"></td>
			<td class="botBorderTd"><input name="codeproduit'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="codeproduit'.$i.'" size="10" value="'.$codeproduit.'" onblur="if(parseFloat(document.getElementById(\'qtelvr'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)>0) {document.getElementById(\'total'.$i.'\').value =parseFloat(document.getElementById(\'qtelvr'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)}" />
			<input name="oldcodeproduit'.$i.'" type="hidden"   id="oldcodeproduit'.$i.'" size="10" value="'.$oldcodeproduit.'"></td>
            <td class="botBorderTd"><input name="produit'.$i.'" type="text"  readonly="readonly" class="formStyle" id="produit'.$i.'" value="'.(stripslashes($produit)).'" onblur="if(parseFloat(document.getElementById(\'qtelvr'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)>0) {document.getElementById(\'total'.$i.'\').value =parseFloat(document.getElementById(\'qtelvr'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)}" /></td>
            <td class="botBorderTd"><input name="qte'.$i.'" type="text" readonly="readonly" class="formStyleFree" id="qte'.$i.'" size="10" value="'.$qte.'"  onblur="if(parseFloat(document.getElementById(\'qtelvr'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)>0) {document.getElementById(\'total'.$i.'\').value =parseFloat(document.getElementById(\'qtelvr'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)}" /></td>
			<td class="botBorderTd"><input name="qtelvr'.$i.'" type="text" class="formStyleFree" id="qtelvr'.$i.'" value="'.$qtelvr.'" size="10" onBlur="if(!parseFloat(document.getElementById(\'qtelvr'.$i.'\').value)){alert(\'Entrez un nombre la quantité\');document.getElementById(\'qtelvr'.$i.'\').value=\'\';};checkQte(\'qte'.$i.'\',\'qtelvr'.$i.'\'); if(parseFloat(document.getElementById(\'qtelvr'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)>0) {document.getElementById(\'total'.$i.'\').value =parseFloat(document.getElementById(\'qtelvr'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)}" /></td>
			<td class="botBorderTd"><input name="unite'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="unite'.$i.'" size="10" value="'.$unite.'">
			<td class="botBorderTd"><input name="prix'.$i.'" type="text" class="formStyleFree" id="prix'.$i.'" value="'.$prix.'" size="10" onBlur="if(!parseFloat(document.getElementById(\'prix'.$i.'\').value)){alert(\'Entrez un nombre pour le prix\');document.getElementById(\'prix'.$i.'\').value=\'\';};if(parseFloat(document.getElementById(\'qtelvr'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)>0) {document.getElementById(\'total'.$i.'\').value =parseFloat(document.getElementById(\'qtelvr'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)}" /></td>
			<td class="botBorderTd"><input name="total'.$i.'" type="text" readonly="readonly" class="formStyleFree" id="total'.$i.'" value="'.$total.'" size="10" /></td>
			<td class="botBorderTd"><input name="reflot'.$i.'" type="text" class="formStyleFree" id="reflot'.$i.'" value="'.$reflot.'" size="10" /></td>
			<td class="botBorderTd"><input name="dateperemp'.$i.'" type="text" class="placeholder formStyleFree" id="dateperemp'.$i.'" value="'.$dateperemp.'"  size="10" />
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
	return array($fonction,$ret, $lotvide);
}

function ligneaddLivraison2($nbre=1, $data=array()){
	$ret = '';
	$verif = '';
	$lotvide ='';
	$fonction ='';


	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['code_detlivraison'])	? $code_detlivraison 	= $data[$i-1]['code_detlivraison'] : $code_detlivraison	='');
		(isset($data[$i-1]['monlot'])			? $monlot 		= $data[$i-1]['monlot'] 		: $monlot	='');
		(isset($data[$i-1]['codeproduit'])		? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['oldcodeproduit'])		? $oldcodeproduit 	= $data[$i-1]['oldcodeproduit'] 	: $oldcodeproduit	='');
		(isset($data[$i-1]['produit']) 			? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['qte'])				? $qte 			= $data[$i-1]['qte'] 			: $qte			='');
		(isset($data[$i-1]['qtelvr'])			? $qtelvr 		= $data[$i-1]['qtelvr'] 		: $qtelvr		='');
		(isset($data[$i-1]['unite']) 			? $unite 		= $data[$i-1]['unite'] 			: $unite		='');
		(isset($data[$i-1]['prix']) 			? $prix 		= $data[$i-1]['prix'] 			: $prix		    ='');
		(isset($data[$i-1]['reflot']) 			? $reflot 		= $data[$i-1]['reflot'] 		: $reflot		='');
		(isset($data[$i-1]['dateperemp']) 		? $dateperemp 	= $data[$i-1]['dateperemp'] 	: $dateperemp	='');

		($prix*$qtelvr>0 ? $total=number_format($prix*$qtelvr,2,'.','') : $total = '');
		($prix>0 ? $prix=number_format($prix,2,'.','') : $prix = '');
		$verif .= "trimAll(document.formadd.qtelvr$i.value)=='' && ";
		//$dateperemp = substr($dateperemp,0,strlen($dateperemp)-3 );
		$lotvide .= "if (trimAll(document.formadd.codeproduit$i.value)!=''  && trimAll(document.formadd.reflot$i.value)=='') { msg +='- Veuillez saisir la référence du lot (ligne $i).\\n';}\n";
		$lotvide .= "if (trimAll(document.formadd.codeproduit$i.value)!=''  && trimAll(document.formadd.dateperemp$i.value)=='') { msg +='- Veuillez saisir la date de péremption du lot (ligne $i).\\n';}\n";

		$ret .= '
		<tr align="left" valign="middle">
            <td class="botBorderTd" nowrap>'.$i.' - </td>
			<td class="botBorderTd"><input name="delete'.$i.'" type="button" class="button" title="Supprimer" value=" X " onClick="msgSupprLigne(\''.$codeproduit.'\','.$i.');" onblur="if(parseFloat(document.getElementById(\'qtelvr'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)>0) {document.getElementById(\'total'.$i.'\').value =parseFloat(document.getElementById(\'qtelvr'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)}" /></td>
			<td class="botBorderTd"><input name="openf'.$i.'" type="button" class="button"  title="Produit" value="..." onClick="OpenWin(\'listeproduitlivr.php?lg='.$i.'\',\'Liste\');">
			<input name="code_detlivraison'.$i.'" type="hidden"   id="code_detlivraison'.$i.'" size="10" value="'.$code_detlivraison.'">
			<input name="monlot'.$i.'" type="hidden"   id="monlot'.$i.'" size="10" value="'.$monlot.'"></td>
			<td class="botBorderTd"><input name="codeproduit'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="codeproduit'.$i.'" size="10" value="'.$codeproduit.'" onchange="if(parseFloat(document.getElementById(\'qtelvr'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)>0) {document.getElementById(\'total'.$i.'\').value =parseFloat(document.getElementById(\'qtelvr'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)}" />
			<input name="oldcodeproduit'.$i.'" type="hidden"   id="oldcodeproduit'.$i.'" size="10" value="'.$oldcodeproduit.'"></td>
            <td class="botBorderTd"><input name="produit'.$i.'" type="text"  readonly="readonly" class="formStyle" id="produit'.$i.'" value="'.(stripslashes($produit)).'" onblur="if(parseFloat(document.getElementById(\'qtelvr'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)>0) {document.getElementById(\'total'.$i.'\').value =parseFloat(document.getElementById(\'qtelvr'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)}" /></td>
            <td class="botBorderTd"><input name="qtelvr'.$i.'" type="text" class="formStyleFree" id="qtelvr'.$i.'" value="'.$qtelvr.'" size="10" onBlur="if(!parseFloat(document.getElementById(\'qtelvr'.$i.'\').value)); if(parseFloat(document.getElementById(\'qtelvr'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)>0) {document.getElementById(\'total'.$i.'\').value =parseFloat(document.getElementById(\'qtelvr'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)}" /></td>
			<td class="botBorderTd"><input name="unite'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="unite'.$i.'" size="10" value="'.$unite.'">
			<td class="botBorderTd"><input name="prix'.$i.'" type="text" class="formStyleFree" id="prix'.$i.'" value="'.$prix.'" size="10" onBlur="if(!parseFloat(document.getElementById(\'prix'.$i.'\').value));if(parseFloat(document.getElementById(\'qtelvr'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)>0) {document.getElementById(\'total'.$i.'\').value =parseFloat(document.getElementById(\'qtelvr'.$i.'\').value)*parseFloat(document.getElementById(\'prix'.$i.'\').value)}" />
			<td class="botBorderTd"><input name="total'.$i.'" type="text" readonly="readonly" class="formStyleFree" id="total'.$i.'" value="'.$total.'" size="10" /></td>
			<td class="botBorderTd"><input name="reflot'.$i.'" type="text" class="formStyleFree" id="reflot'.$i.'" value="'.$reflot.'" size="10" /></td>
			<td class="botBorderTd"><input name="dateperemp'.$i.'" type="text" class="placeholder formStyleFree" id="dateperemp'.$i.'" value="'.$dateperemp.'"  size="10" />
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
	return array($fonction,$ret, $lotvide);
}

function lignedetailLivraison1($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['code_detlivraison'])	? $code_detlivraison 	= $data[$i-1]['code_detlivraison'] : $code_detlivraison	='');
		(isset($data[$i-1]['monlot'])			? $monlot 		= $data[$i-1]['monlot'] 		: $monlot	='');
		(isset($data[$i-1]['codeproduit'])		? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 			? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['qte'])				? $qte	 		= $data[$i-1]['qte'] 			: $qte			='');
		(isset($data[$i-1]['qtelvr'])			? $qtelvr 		= $data[$i-1]['qtelvr'] 		: $qtelvr			='');
		(isset($data[$i-1]['unite']) 			? $unite 		= $data[$i-1]['unite'] 			: $unite		='');
		(isset($data[$i-1]['prix'])				? $prix		 	= $data[$i-1]['prix'] 			: $prix			='');
		(isset($data[$i-1]['reflot'])			? $reflot		= $data[$i-1]['reflot'] 		: $reflot			='');
		(isset($data[$i-1]['dateperemp'])		? $dateperemp	= $data[$i-1]['dateperemp'] 	: $dateperemp			='');

		($prix*$qtelvr>0 ? $Atotal=number_format($prix*$qtelvr,2,',',' ') : $Atotal = '');
		($prix>0 ? $Aprix=number_format($prix,2,',',' ') : $Aprix = '');

		$ret .= '
		<tr align="left" valign="middle">
            <td class="botBorderTd" nowrap>'.$i.' - </td>
			<td class="botBorderTd">
			<input name="code_detlivraison'.$i.'" type="hidden"   id="code_detlivraison'.$i.'" size="10" value="'.$code_detlivraison.'">
			<input name="monlot'.$i.'" type="hidden"   id="monlot'.$i.'" size="10" value="'.$monlot.'">
			<input name="codeproduit'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="codeproduit'.$i.'" size="10" value="'.$codeproduit.'"></td>
            <td class="botBorderTd"><input name="produit'.$i.'" type="text"  readonly="readonly" class="formStyle" id="produit'.$i.'" value="'.(stripslashes($produit)).'"></td>
            <td class="botBorderTd"><input name="qtecde'.$i.'" type="text" readonly="readonly" class="formStyleFree" id="qtecde'.$i.'" size="10" value="'.$qte.'" ></td>
			<td class="botBorderTd"><input name="qtelvr'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="qtelvr'.$i.'" size="10" value="'.$qtelvr.'" ></td>
			<td class="botBorderTd"><input name="unite'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="unite'.$i.'" size="10" value="'.$unite.'"></td>
			<td class="botBorderTd"><input name="prix'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="prix'.$i.'" size="10" value="'.$Aprix.'" ></td>
			<td class="botBorderTd"><input name="total'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="total'.$i.'" size="10" value="'.$Atotal.'" ></td>
			<td class="botBorderTd"><input name="reflot'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="reflot'.$i.'" size="10" value="'.$reflot.'" ></td>
			<td class="botBorderTd"><input name="dateperemp'.$i.'" type="text"  readonly="readonly" class="placeholder formStyleFree" id="dateperemp'.$i.'" size="10" value="'.$dateperemp.'"></td>

        </tr>';
		//<td class="botBorderTd"><select name="magasin'.$i.'"  readonly="readonly" id="magasin'.$i.'" class="formStyle"><option value="0"></option>'.selectmagasin($magasin).'</select></td>
	}
	return $ret;
}

function lignedetailLivraison2($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['code_detlivraison'])	? $code_detlivraison 	= $data[$i-1]['code_detlivraison'] : $code_detlivraison	='');
		(isset($data[$i-1]['monlot'])			? $monlot 		= $data[$i-1]['monlot'] 		: $monlot	='');
		(isset($data[$i-1]['codeproduit'])		? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 			? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['qte'])				? $qte	 		= $data[$i-1]['qte'] 			: $qte			='');
		(isset($data[$i-1]['qtelvr'])			? $qtelvr 		= $data[$i-1]['qtelvr'] 		: $qtelvr			='');
		(isset($data[$i-1]['unite']) 			? $unite 		= $data[$i-1]['unite'] 			: $unite		='');
		(isset($data[$i-1]['prix'])				? $prix		 	= $data[$i-1]['prix'] 			: $prix			='');
		(isset($data[$i-1]['reflot'])			? $reflot		= $data[$i-1]['reflot'] 		: $reflot			='');
		(isset($data[$i-1]['dateperemp'])		? $dateperemp	= $data[$i-1]['dateperemp'] 	: $dateperemp			='');

		//$dateperemp = frFormat2($dateperemp);
		($prix*$qtelvr>0 ? $Atotal=number_format($prix*$qtelvr,2,',',' ') : $Atotal = '');
		($prix>0 ? $Aprix=number_format($prix,2,',',' ') : $Aprix = '');

		$ret .= '
		<tr align="left" valign="middle">
            <td class="botBorderTd" nowrap>'.$i.' - </td>
			<td class="botBorderTd">
			<input name="code_detlivraison'.$i.'" type="hidden"   id="code_detlivraison'.$i.'" size="10" value="'.$code_detlivraison.'">
			<input name="monlot'.$i.'" type="hidden"   id="monlot'.$i.'" size="10" value="'.$monlot.'">
			<input name="codeproduit'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="codeproduit'.$i.'" size="10" value="'.$codeproduit.'"></td>
            <td class="botBorderTd"><input name="produit'.$i.'" type="text"  readonly="readonly" class="formStyle" id="produit'.$i.'" value="'.(stripslashes($produit)).'"></td>
           	<td class="botBorderTd"><input name="qtelvr'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="qtelvr'.$i.'" size="10" value="'.$qtelvr.'" ></td>
			<td class="botBorderTd"><input name="unite'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="unite'.$i.'" size="10" value="'.$unite.'"></td>
			<td class="botBorderTd"><input name="prix'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="prix'.$i.'" size="10" value="'.$Aprix.'" ></td>
			<td class="botBorderTd"><input name="total'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="total'.$i.'" size="10" value="'.$Atotal.'" ></td>
			<td class="botBorderTd"><input name="reflot'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="reflot'.$i.'" size="10" value="'.$reflot.'" ></td>
			<td class="botBorderTd"><input name="dateperemp'.$i.'" type="text"  readonly="readonly" class="placeholder formStyleFree" id="dateperemp'.$i.'" size="10" value="'.$dateperemp.'"></td>

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

		(isset($data[$i-1]['valide']) && $data[$i-1]['valide']==1	? $imgCl ='<img src="../images/valider.gif" title="Validé" width="16" height="16">'	: $imgCl = '<img src="../images/encours.gif" title="En cours" width="16" height="16">');

		$ret .= '
		<tr align="left" valign="middle">
            <td class="botBorderTd" nowrap>'.$i.' - </td>
			<td class="botBorderTd"><input name="jcodeproduit'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="jcodeproduit'.$i.'" size="10" value="'.$codeproduit.'"></td>
            <td class="botBorderTd"><input name="jqtelvr'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="jqtelvr'.$i.'" size="10" value="'.$qtelvr.'" ></td>
			<td class="botBorderTd"><input name="junite'.$i.'" type="text"  readonly="readonly" class="formStyleFree" id="junite'.$i.'" size="10" value="'.$unite.'"></td>
			<td class="botBorderTd">'.$imgCl.'</td>
        </tr>';
	}
	return $ret;
}

function ligneEtatLivraison($nbre=1, $data=array()){
	$ret = '';
	$somme = 0;
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['reflot'])		? $reflot = $data[$i-1]['reflot'] 	: $reflot	='');
		(isset($data[$i-1]['codeproduit'])	? $codeproduit = $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 		? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['qte']) && $data[$i-1]['qte']!=0		? $qte	 		= $data[$i-1]['qte'] 			: $qte			='');
		(isset($data[$i-1]['qtelvr']) && $data[$i-1]['qtelvr']!=0	? $qtelvr 		= $data[$i-1]['qtelvr'] 		: $qtelvr			='');
		(isset($data[$i-1]['unite']) 	? $unite 		= $data[$i-1]['unite'] 			: $unite		='');
		(isset($data[$i-1]['prix'])	? $prix		 = $data[$i-1]['prix'] 		: $prix			='');
		(isset($data[$i-1]['dateperemp'])	? $dateperemp		 = $data[$i-1]['dateperemp'] 		: $dateperemp			='');

		$total = $prix*$qtelvr;
		($prix*$qtelvr>0 ? $Atotal=number_format($total,2,',',' ') : $Atotal = '');
		($prix>0 ? $Aprix=number_format($prix,2,',',' ') : $Aprix = '');
		$ret .= '
		<tr align="left" valign="middle">
            <td class="botBorderTdall" align="center" nowrap>'.$i.' - </td>
			<td class="botBorderTdall" align="center">'.$reflot.'&nbsp;</td>
            <td class="botBorderTdall">'.(stripslashes($produit)).'&nbsp;</td>
            <td class="botBorderTdall" align="center">'.$qte.'&nbsp;</td>
			<td class="botBorderTdall" align="center">'.$qtelvr.'&nbsp;</td>
			<td class="botBorderTdall" align="center">'.(stripslashes($unite)).'&nbsp;</td>
			<td class="botBorderTdall" align="right">'.$Aprix.'&nbsp;</td>
			<td class="botBorderTdall" align="right">'.$Atotal.'&nbsp;</td>
			<td class="botBorderTdall" align="right">'.$dateperemp.'&nbsp;</td>
        </tr>';
		$somme +=$total;
	}
	($somme>0 ? $Asomme=number_format($somme,2,',',' ') : $Asomme = '');
	$ret .='<tr align="left" valign="middle">
            <td class="botBorderTdall" align="right" nowrap colspan="7"><strong>TOTAL GENERAL :&nbsp;&nbsp;</strong></td>
			<td class="botBorderTdall" align="right" nowrap="nowrap"><strong>'.$Asomme.'&nbsp;</strong></td>
        </tr>';
	return $ret;
}

?>