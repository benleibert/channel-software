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


function ligneConMenu($wh='', $ord='', $sens='ASC', $page=1, $nelt){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	$returnHTML = '';
	$returnTble = array();//HTML, nbreTotal,

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " WHERE $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY menu.IDMENU  ASC");

	//Nombre d'éléments
	$returnTble['NE'] = nombreElement($where, 'menu');

	if($returnTble['NE']>0){

		//Calcule des limites
		$i = ($page-1)*$nelt;
		//SQL
		$sql = "SELECT * FROM menu $where $order LIMIT $i, $nelt;";

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
			//Is use
			$compte = isUseNow('IDMENU', 'profil_menu', "WHERE IDMENU LIKE '".$row['IDMENU']."'");
			(($compte) > 0 ? $Use = 1 : $Use = 0);

			$returnHTML .= '
			<tr align="left" valign="middle" class="'.$col.'">
	            <td><input type="checkbox" name="rowSelection[]" value="'.$row['IDMENU'].'@'.$Use.'"></td>
                <td class="text" >'.(stripslashes($row['IDMENU'])).'</td>
                <td class="text" >'.(stripslashes($row['LIBMENU'])).'</td>
           </tr>';
			$i++;
			$j++;
		}
		//				<td class="text" ><a href="detailfonctionnalite.php?do=detail&xid='.$row['IDMENU'].'"><img src="../images/b_browse1.png" title="Afficher" align="absmiddle"></a></td>

	}
	else {
	if($ilang=='1' && $ilang!='') {$returnHTML .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e...</td></tr>';}
	if($ilang=='2' && $ilang!='') {$returnHTML .= '<tr><td colspan="4" class="text">No data...</td></tr>';}
	if($ilang=='3' && $ilang!='') {$returnHTML .= '<tr><td colspan="4" class="text">Nenhum dado...</td></tr>';}
	}

	$returnTble['L']=$returnHTML;
	return $returnTble;
}



?>