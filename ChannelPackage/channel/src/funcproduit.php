<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */

//--------- UNITES -----------------------------

//Nombre de ligne retourner
function nombreProduit($where=''){
	$sql = "SELECT * FROM produit INNER JOIN souscategorie ON (produit.CODE_SOUSCATEGORIE LIKE souscategorie.CODE_SOUSCATEGORIE)
	INNER JOIN categorie ON (categorie.CODE_CATEGORIE LIKE souscategorie.CODE_CATEGORIE)  $where;";
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

function ligneConProduit($wh='', $ord='', $sens='ASC', $page=1, $nelt){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	$returnHTML = '';
	$returnTble = array();//HTML, nbreTotal,

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY produit.CODE_SOUSCATEGORIE, CODE_PRODUIT ASC");
	//Nombre d'éléments
	$returnTble['NE'] = nombreProduit($where);
	if($returnTble['NE']>0){
		//Calcule des limites
		$i = ($page-1)*$nelt;
		$sql = "SELECT * FROM produit INNER JOIN souscategorie ON (produit.CODE_SOUSCATEGORIE LIKE souscategorie.CODE_SOUSCATEGORIE)
		INNER JOIN categorie ON (categorie.CODE_CATEGORIE LIKE souscategorie.CODE_CATEGORIE)   $where $order LIMIT $i, $nelt;";
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
		$j=5;
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
			($row['PRD_TRACEUR'] == 'TRACEUR' ? $cdt = "Produit traceur" : $cdt = "Produit non traceur");

			//Is use
			$livraison = isUseNow('CODE_PRODUIT', 'prd_livraison', "WHERE CODE_PRODUIT LIKE '".$row['CODE_PRODUIT']."'");
			$commande = isUseNow('CODE_PRODUIT', 'prd_commande', "WHERE CODE_PRODUIT LIKE '".$row['CODE_PRODUIT']."'");
			$sortie = isUseNow('CODE_PRODUIT', 'prd_bonsortie', "WHERE CODE_PRODUIT LIKE '".$row['CODE_PRODUIT']."'");
			(($livraison+$commande+$sortie) > 0 ? $Use = 1 : $Use = 0);

			($row['PRD_PRIXACHAT'] >0 ? $prixachat = number_format($row['PRD_PRIXACHAT'],2,',',' ') : $prixachat ='');
			($row['PRD_PRIXACHAT'] >0 ? $prixrevient = number_format($row['PRD_PRIXREVIENT'],2,',',' ') : $prixrevient ='');
			($row['PRD_PRIXACHAT'] >0 ? $prixvente = number_format($row['PRD_PRIXVENTE'],2,',',' ') : $prixvente ='');

			$returnHTML .= '
			<tr align="left" valign="middle" class="'.$col.'">
	            <td><input type="checkbox" name="rowSelection[]" value="'.$row['CODE_PRODUIT'].'@'.$Use.'"></td>
                <td class="text" align="center">'.$row['CODE_PRODUIT'].'</td>
                <td class="text" >'.(stripslashes($row['PRD_LIBELLE'])).'</td>
				<td class="text" >'.(stripslashes($row['CAT_LIBELLE'])).'</td>
				<td class="text" >'.(stripslashes($row['SOUSCAT_LIBELLE'])).'</td>
				<td class="text" >'.(stripslashes($row['ID_UNITE'])).'</td>
				<td class="text"align="right" >'.stripslashes($prixachat).'</td>
				<td class="text" align="right">'.stripslashes($prixrevient).'</td>
				<td class="text" align="right">'.stripslashes($prixvente).'</td>
				<td class="text" align="left">'.(stripslashes($cdt)).'</td>
				<!-- <td class="text" align="left" ><a href="conditionnement.php?selectedTab=par&codeproduit='.$row['CODE_PRODUIT'].'">'.(stripslashes('Conditionnement')).'</a></td> -->
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


function ligneEtatListeProduit($wh=''){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	$returnHTML = '';

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY produit.CODE_SOUSCATEGORIE, CODE_PRODUIT ASC");
	//Nombre d'éléments
	$nbre = nombreProduit($where);
	if($nbre>0){
		//Calcule des limites
		$sql = "SELECT * FROM produit INNER JOIN souscategorie ON (produit.CODE_SOUSCATEGORIE LIKE souscategorie.CODE_SOUSCATEGORIE)
		INNER JOIN categorie ON (categorie.CODE_CATEGORIE LIKE souscategorie.CODE_CATEGORIE)   $where $order ";
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
		$j=5;
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			($row['PRD_TRACEUR'] !='' ? $cdt = "Produit traceur" : $cdt = "Produit non traceur");

			//Is use
			$bareme = isUseNow('CODE_PRODUIT', 'bareme', "WHERE CODE_PRODUIT LIKE '".$row['CODE_PRODUIT']."'");
			$conditionnement = isUseNow('CODE_PRODUIT', 'conditionmt', "WHERE CODE_PRODUIT LIKE '".$row['CODE_PRODUIT']."'");
			$returnHTML .= '
			<tr align="left" valign="middle" >
	            <td class="botBorderTdall" align="center">'.$row['CODE_PRODUIT'].'</td>
                <td class="botBorderTdall" >'.(stripslashes($row['PRD_LIBELLE'])).'</td>
				<td class="botBorderTdall" >'.(stripslashes($row['CAT_LIBELLE'])).'</td>
				<td class="botBorderTdall" align="center">'.(stripslashes($row['ID_UNITE'])).'</td>
				<td class="botBorderTdall" >'.(stripslashes($cdt)).'</td>
				<!-- <td class="botBorderTdall" align="center" ><a href="conditionnement.php?selectedTab=par&codeproduit='.$row['CODE_PRODUIT'].'">'.(stripslashes('Conditionnement')).'</a></td> -->
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
	return $returnHTML;
}


function ligneMajPrixProduit(){
	$returnHTML = '';

	//Calcule des limites
	$sql = "SELECT * FROM produit INNER JOIN souscategorie ON (produit.CODE_SOUSCATEGORIE LIKE souscategorie.CODE_SOUSCATEGORIE)
	INNER JOIN categorie ON (categorie.CODE_CATEGORIE LIKE souscategorie.CODE_CATEGORIE) ORDER BY CODE_PRODUIT, PRD_LIBELLE ASC;";
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

	$i = 0; $k=1;
	$j=5;
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
			($row['PRD_TRACEUR'] == 'TRACEUR' ? $cdt = "Produit traceur" : $cdt = "Produit non traceur");

			($row['PRD_PRIXACHAT'] >0 ? $prixachat = number_format($row['PRD_PRIXACHAT'],2,',','') : $prixachat ='');
			($row['PRD_PRIXREVIENT'] >0 ? $prixrevient = number_format($row['PRD_PRIXREVIENT'],2,',','') : $prixrevient ='');
			($row['PRD_PRIXVENTE'] >0 ? $prixvente = number_format($row['PRD_PRIXVENTE'],2,',','') : $prixvente ='');

			($row['PRD_PRIXACHATN2'] >0 ? $prixachatn2 = number_format($row['PRD_PRIXACHATN2'],2,',','') : $prixachatn2 ='');
			($row['PRD_PRIXREVIENTN2'] >0 ? $prixrevientn2 = number_format($row['PRD_PRIXREVIENTN2'],2,',','') : $prixrevientn2 ='');
			($row['PRD_PRIXVENTEN2'] >0 ? $prixventen2 = number_format($row['PRD_PRIXVENTEN2'],2,',','') : $prixventen2 ='');

			$returnHTML .= '
			<tr align="left" valign="middle" class="'.$col.'">
				<td  align="center">'.$k.'-<input type="hidden" name="rowSelection[]" value="'.$row['CODE_PRODUIT'].'"></td>
				<td class="text" align="center">'.$row['CODE_PRODUIT'].'</td>
                <td class="text" >'.(stripslashes($row['PRD_LIBELLE'])).'</td>
				<td class="text" align="center">'.(stripslashes($row['ID_UNITE'])).'</td>
				<td class="text"align="right" >'.stripslashes($prixachat).'</td>
				<td class="botBorderTd"><input name="prixachat'.$i.'" type="text" class="formStyleFree" id="prix'.$i.'" value="'.$prixachat.'" size="5" /></td>
				<td class="text" align="right">'.stripslashes($prixrevient).'</td>
				<td class="botBorderTd"><input name="prixrevient'.$i.'" type="text" class="formStyleFree" id="prixrevient'.$i.'" value="'.$prixrevient.'" size="5" /></td>
				<td class="text" align="right">'.stripslashes($prixvente).'</td>
				<td class="botBorderTd"><input name="prixvente'.$i.'" type="text" class="formStyleFree" id="prixvente'.$i.'" value="'.$prixvente.'" size="5" /></td>

				<td class="text"align="right" >'.stripslashes($prixachatn2).'</td>
				<td class="botBorderTd"><input name="prixachatn2'.$i.'" type="text" class="formStyleFree" id="prixachatn2'.$i.'" value="'.$prixachatn2.'" size="5" /></td>
				<td class="text" align="right">'.stripslashes($prixrevientn2).'</td>
				<td class="botBorderTd"><input name="prixrevientn2'.$i.'" type="text" class="formStyleFree" id="prixrevientn2'.$i.'" value="'.$prixrevientn2.'" size="5" /></td>
				<td class="text" align="right">'.stripslashes($prixventen2).'</td>
				<td class="botBorderTd"><input name="prixventen2'.$i.'" type="text" class="formStyleFree" id="prixventen2'.$i.'" value="'.$prixventen2.'" size="5" /></td>

				<td class="text" align="left">'.(stripslashes($cdt)).'</td>
				<!-- <td class="text" align="left" ><a href="conditionnement.php?selectedTab=par&codeproduit='.$row['CODE_PRODUIT'].'">'.(stripslashes('Conditionnement')).'</a></td> -->
            </tr>';
			$i++;
			$j++;
			$k++;
		}

	return $returnHTML;
}
?>