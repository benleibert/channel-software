<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */

//--------- UNITES -----------------------------

//Nombre de ligne retourner
function nombreArticle($where=''){
	$sql = "SELECT * FROM produit INNER JOIN categorie ON (produit.CODE_CATEGORIE LIKE categorie.CODE_CATEGORIE) $where;";
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

function ligneConArticle($wh='', $ord='', $sens='ASC', $page=1, $nelt){
	$returnHTML = '';
	$returnTble = array();//HTML, nbreTotal,

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY produit.CODE_CATEGORIE, ID_PRODUIT ASC");
	//Nombre d'éléments
	$returnTble['NE'] = nombreArticle($where);
	if($returnTble['NE']>0){
		//Calcule des limites
		$i = ($page-1)*$nelt;
		$sql = "SELECT * FROM produit INNER JOIN categorie ON (produit.CODE_CATEGORIE LIKE categorie.CODE_CATEGORIE) $where $order LIMIT $i, $nelt;";
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
			($row['CONDITIONNE'] == 0 ? $cdt = "Non" : $cdt = "Oui");

			$returnHTML .= '
			<tr align="left" valign="middle" class="'.$col.'">
	            <td><input type="checkbox" name="rowSelection[]" value="'.$row['ID_PRODUIT'].'" onClick="go('.$row['ID_PRODUIT'].','.$j.');"></td>
                <td class="text" align="center">'.$row['CODE_PRODUIT'].'</td>
                <td class="text" >'.(stripslashes($row['PRD_LIBELLE'])).'</td>
				<td class="text" >'.(stripslashes($row['CAT_LIBELLE'])).'</td>
				<td class="text" >'.(stripslashes($row['ID_UNITE'])).'</td>
				<td class="text" >'.(stripslashes($cdt)).'</td>
				<td class="text" >'.(stripslashes('Conditionnement')).'</td>
            </tr>';
			$i++;
			$j++;
		}

	}
	else {$returnHTML .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e</td></tr>';}

	$returnTble['L']=$returnHTML;
	return $returnTble;
}

function lignSearchUnites($cr1,$cr2,$page=1,$nelt){
	$ret = '';
	$t = array();
	$table1 = "stocks_unite";
	//Connection to Database server
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errorPage.php&code=');

	//Select Database
	mysql_select_db(DB) or header('location:errorPage.php&code=');

	//SQL
	$where ='';
	(isset($cr1) and $cr1!='' ? $where .= " LIBELLE_UNITE LIKE '%$cr1%' AND " : $where .= "");
	(isset($cr2) and $cr2!='' ? $where .= " LIB_COURT LIKE '%$cr2%' AND " : $where .= "");

	if($where != '') $where = substr(" WHERE $where",0,strlen(" WHERE $where")-4);

	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY CODE_NOMBENF ASC");
	$SQL = "SELECT * FROM $table1 $where $order;";
	$result = mysql_query($SQL);
	$t['NE'] = mysql_num_rows($result);

	$i = ($page-1)*$nelt;
	$SQL = "SELECT * FROM $table1 $where $order LIMIT $i, $nelt;";
	$result = mysql_query($SQL);
	$i = 0;
	$j=6;
	while($row = mysql_fetch_array($result)){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		$ret .= '<tr align="left" valign="middle" class="'.$col.'">
	               	<td width="3%"><input type="checkbox" name="rowSelection[]" value="'.$row['CODE_NOMBENF'].'" onClick="go('.$row['CODE_NOMBENF'].','.$j.');"></td>
                    <td width="10%" height="22" class="text" align="center">'.$row['CODE_NOMBENF'].'</td>
                    <td width="30%" class="text" >'.(stripslashes($row['CODE_NOMBENF'])).'</td>
					<td width="50%" class="text" >'.(stripslashes($row['NBENEF_LIBELLE'])).'</td>
                 </tr>';
				 $i++;$j++;
	}
	$t['L']=$ret;
	//mysql_close);
	return $t;
}



?>