<?php
//session_start();
if($_SESSION['GL_USER']['SESSIONID'] != session_id())header("location:dbuser.php?do=logout");
require_once('../lib/phpfuncLib.php');	//All commun functions
 $userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
 $ilang=getCodelangue($userName);
$vaide=getlang(8);
$vprop=getlang(3);

//TOP MENU
function topMenus($tab ='', $droit=''){

	(isset($droit['bes']['VISIBLE']) ? $bes = $droit['bes']['VISIBLE'] : $bes= 0);
	(isset($droit['bde']['VISIBLE']) ? $bde = $droit['bde']['VISIBLE'] : $bde= 0);
	(isset($droit['bds']['VISIBLE']) ? $bds = $droit['bds']['VISIBLE'] : $bds= 0);
	(isset($droit['bds']['VISIBLE']) ? $bds = $droit['bds']['VISIBLE'] : $bds= 0);
	(isset($droit['int']['VISIBLE']) ? $int = $droit['int']['VISIBLE'] : $int= 0);
	(isset($droit['eta']['VISIBLE']) ? $eta = $droit['eta']['VISIBLE'] : $eta= 0);
	(isset($droit['par']['VISIBLE']) ? $par = $droit['par']['VISIBLE'] : $par= 0);
	(isset($droit['ann']['VISIBLE']) ? $ann = $droit['ann']['VISIBLE'] : $ann= 0);
	(isset($droit['aid']['VISIBLE']) ? $aid = $droit['aid']['VISIBLE'] : $aid= 0);
	(isset($droit['data']['VISIBLE']) ? $data = $droit['data']['VISIBLE'] : $data= 0);
	(isset($droit['rap']['VISIBLE']) ? $rap = $droit['rap']['VISIBLE'] : $rap= 0);

	//Top menus Data
	//Libellé , Lien, Droit, Tab
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	if($ilang=='1' && $ilang!='') { 
	$TOPMENUS =array( 
	'0'=> array('Accueil','home.php?selectedTab=home','1', 'home',array()),
	'1'=> array('Entrées de produits','entree.php?selectedTab=bde',$bde, 'bde',array()),
	'2'=> array('Sorties de produits','sortie.php?selectedTab=bds',$bds, 'bds',array()),
	'3'=> array('Inventaire & Etats','inventstock.php?selectedTab=int',$int, 'int',array()),
	'4'=> array('Rapports','rapport.php?selectedTab=rap',$rap, 'rap',array()),
	'5'=> array('Base de données','basededonnees.php?selectedTab=data',$data, 'data',array()),
	'6'=> array('Paramétrage ','parametrage.php?selectedTab=par',$par, 'par',array()),
	'7'=> array('Aide ','aide.php?selectedTab=aid',$aid, 'aid',array()),
	);
	}
	if($ilang=='2' && $ilang!='') { 
	$TOPMENUS =array( 
	'0'=> array('Welcome','home.php?selectedTab=home','1', 'home',array()),
	'1'=> array('Product entries','entree.php?selectedTab=bde',$bde, 'bde',array()),
	'2'=> array('Product output','sortie.php?selectedTab=bds',$bds, 'bds',array()),
	'3'=> array('Inventory & States','inventstock.php?selectedTab=int',$int, 'int',array()),
	'4'=> array('Reports','rapport.php?selectedTab=rap',$rap, 'rap',array()),
	'5'=> array('Database','basededonnees.php?selectedTab=data',$data, 'data',array()),
	'6'=> array('Setting ','parametrage.php?selectedTab=par',$par, 'par',array()),
	'7'=> array('Help ','aide.php?selectedTab=aid',$aid, 'aid',array()),
	);
	}
	if($ilang=='3' && $ilang!='') { 
	$TOPMENUS =array( 
	'0'=> array('Bem-vindo','home.php?selectedTab=home','1', 'home',array()),
	'1'=> array('Entradas de produtos','entree.php?selectedTab=bde',$bde, 'bde',array()),
	'2'=> array('Saída do produto','sortie.php?selectedTab=bds',$bds, 'bds',array()),
	'3'=> array('Inventário e Estados','inventstock.php?selectedTab=int',$int, 'int',array()),
	'4'=> array('Relações','rapport.php?selectedTab=rap',$rap, 'rap',array()),
	'5'=> array('Base de dados','basededonnees.php?selectedTab=data',$data, 'data',array()),
	'6'=> array('Cenário ','parametrage.php?selectedTab=par',$par, 'par',array()),
	'7'=> array('Socorro ','aide.php?selectedTab=aid',$aid, 'aid',array()),
	);
	}
	$Active = ''; //

	//$t_droit = preg_split('/ /',$droit);
	//foreach ($t_droit as $key => $val){$TOPMENUS[$key][2] =$val;} //Fill Right

	$menuHTML1= '<table cellpadding=0 cellspacing=0 border=0 id="tab">
              		<tr>';
	$menuHTML2 = '';

	//Display menu
	foreach ($TOPMENUS as $key => $val){
		if($val[2]==1) {
			//Allow to see it
			if(isset($TOPMENUS[$key][3]) && $tab == $TOPMENUS[$key][3]){
				$menuHTML1 .= '<td nowrap class="menuOnBg"><a id="homelink" href="'.$TOPMENUS[$key][1].'" class="menuOff">'.stripslashes($TOPMENUS[$key][0]).'</a></td>';
				$Active = $key;
			}
			else {
				$menuHTML1 .= '<td nowrap class="menuOffBg"><a id="homelink" href="'.$TOPMENUS[$key][1].'" class="menuOff">'.stripslashes($TOPMENUS[$key][0]).'</a></td>';
			}
		}
	}
	$menuHTML1 .= '</tr></table>';
	//SubMenu
	$SUBMENUS = $TOPMENUS[$Active][4];
	foreach ($SUBMENUS as $key => $val){
		$menuHTML2 .='<span class="swtext"><a href="'.$SUBMENUS[$key][1].'">'.stripslashes($SUBMENUS[$key][0]).'</a></span><span class="swtext">|</span>';
	}
	if(	$menuHTML2!='') 	$menuHTML2 = '<span class="arrow"> &rarr;	</span>'.substr($menuHTML2,0, strlen($menuHTML2)-29);
	return array('Top'=>$menuHTML1, 'Sub'=>$menuHTML2);
}

//HOME
function homeMenus($tab ='', $droit=''){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);

	if($ilang=='1' && $ilang!='') { 
	$TOPMENUS =array( //Libellé , Lien, Droit, Tab
	'0'=> array('0'=>'Paramètres personnels',
				'1'=> array('Mettre à jour identité', 'moncompte.php?selectedTab=home',1, 'home'),
				'2'=> array('Changer mot de passe', 'changepwd.php?selectedTab=home&rst=1',1, 'home'),
			),
	'1'=> array('0'=>'Etat du stock',
				'1'=> array('Etat de stock/lot', 'etatdustocklot.php?selectedTab=home',1, 'home'),
				'2'=> array('Etat de stock/produit', 'etatdustockproduit.php?selectedTab=home',1, 'home'),
				'3'=> array('Alerte péremption', 'hrapproduitaperime.php?selectedTab=home',1, 'home'),
//				'4'=> array('Stock sup. seuil max.', 'hrapstocksupseuilmax.php?selectedTab=home',1, 'home'),
			)
		);
	}
	if($ilang=='2' && $ilang!='') { 
	$TOPMENUS =array( //Libellé , Lien, Droit, Tab
	'0'=> array('0'=>'Personal settings',
				'1'=> array('Update identity', 'moncompte.php?selectedTab=home',1, 'home'),
				'2'=> array('Change Password', 'changepwd.php?selectedTab=home&rst=1',1, 'home'),
			),
	'1'=> array('0'=>'Stock status',
				'1'=> array('State of stock / lot', 'etatdustocklot.php?selectedTab=home',1, 'home'),
				'2'=> array('State stock / product', 'etatdustockproduit.php?selectedTab=home',1, 'home'),
				'3'=> array('Expiration alert', 'hrapproduitaperime.php?selectedTab=home',1, 'home'),
//				'4'=> array('Stock sup. seuil max.', 'hrapstocksupseuilmax.php?selectedTab=home',1, 'home'),
			)
		);
	}
	if($ilang=='3' && $ilang!='') { 
	$TOPMENUS =array( //Libellé , Lien, Droit, Tab
	'0'=> array('0'=>'Definições pessoais',
				'1'=> array('Identidade Atualização', 'moncompte.php?selectedTab=home',1, 'home'),
				'2'=> array('Alterar A Senha', 'changepwd.php?selectedTab=home&rst=1',1, 'home'),
			),
	'1'=> array('0'=>'Da estatuto',
				'1'=> array('Estado de estoque / lot', 'etatdustocklot.php?selectedTab=home',1, 'home'),
				'2'=> array('Estoque Estado / produto', 'etatdustockproduit.php?selectedTab=home',1, 'home'),
				'3'=> array('Alerta de validade', 'hrapproduitaperime.php?selectedTab=home',1, 'home'),
//				'4'=> array('Stock sup. seuil max.', 'hrapstocksupseuilmax.php?selectedTab=home',1, 'home'),
			)
		);
	}



	$menuHTML1= '';
	foreach ($TOPMENUS as $key => $val){
		$menuHTML1 .= '<tr><td><table width="185" border="0" align="left" cellpadding="1" cellspacing="1">';
		foreach ($val as $key1 => $val1){ //B 1
			if(!is_array($val1)){
				$menuHTML1 .= '
				<tr>
            		<td width="175" height="20" colspan="2" class="leftHeader"><nobr>'.stripslashes($val[0]).'</nobr></td>
          		</tr>
          		';
			}
			if(is_array($val1) && $val1[2]==1){ //B 2
				$menuHTML1 .= '
				<tr>
            		<td class="leftLink" width="2">&nbsp;</td>
					<td class="leftLink" height="20" width="175" ><a href="'.stripslashes($val1[1]).'" />'.stripslashes($val1[0]).'</a></td>
				</tr>
				';
			}//B 2
		}
		$menuHTML1 .= '
			<tr>
            	<td class="leftLink" width="2"></td>
				<td class="leftLink" height="4" width="175" ></td>
			</tr>
			</table> </td></tr>';
	}
	return $menuHTML1;
}

//COMMANDES LIVRAISONS
function commandesMenus($tab ='', $droit=''){

	(isset($droit['bde_cde']['VISIBLE']) ? $bde_cde = $droit['bde_cde']['VISIBLE'] : $bde_cde= 0);
	(isset($droit['bde_liv']['VISIBLE']) ? $bde_liv = $droit['bde_liv']['VISIBLE'] : $bde_liv= 0);
	(isset($droit['bde_ali']['VISIBLE']) ? $bde_ali = $droit['bde_ali']['VISIBLE'] : $bde_ali= 0);
	(isset($droit['bde_lot']['VISIBLE']) ? $bde_lot = $droit['bde_lot']['VISIBLE'] : $bde_lot= 0);

$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	if($ilang=='1' && $ilang!='') { 
	$TOPMENUS =array( //Libellé , Lien, Droit, Tab

	'0'=> array('0'=>'Entrées de produits',
				'1'=> array('Commandes', 'commande.php?selectedTab=bde&rst=1',$bde_cde, 'bde'),
				'2'=> array('Livraisons', 'livraison.php?selectedTab=bde&rst=1',$bde_liv, 'bde')
				//'3'=> array('Gestion des lots et dates', 'lots.php?selectedTab=bde&rst=1',$bde_lot, 'bde')
				)
	);
	}
	if($ilang=='2' && $ilang!='') { 
	$TOPMENUS =array( //Libellé , Lien, Droit, Tab

	'0'=> array('0'=>'Product entries',
				'1'=> array('Commands', 'commande.php?selectedTab=bde&rst=1',$bde_cde, 'bde'),
				'2'=> array('Shipments', 'livraison.php?selectedTab=bde&rst=1',$bde_liv, 'bde')
				//'3'=> array('Gestion des lots et dates', 'lots.php?selectedTab=bde&rst=1',$bde_lot, 'bde')
				)
	);
	}
	if($ilang=='3' && $ilang!='') { 
	$TOPMENUS =array( //Libellé , Lien, Droit, Tab

	'0'=> array('0'=>'Entradas de produtos',
				'1'=> array('Comandos', 'commande.php?selectedTab=bde&rst=1',$bde_cde, 'bde'),
				'2'=> array('Os embarques', 'livraison.php?selectedTab=bde&rst=1',$bde_liv, 'bde')
				//'3'=> array('Gestion des lots et dates', 'lots.php?selectedTab=bde&rst=1',$bde_lot, 'bde')
				)
	);
	}

	$menuHTML1= '';
	foreach ($TOPMENUS as $key => $val){
		$menuHTML1 .= '<tr><td><table width="185" border="0" align="left" cellpadding="1" cellspacing="1">';
		foreach ($val as $key1 => $val1){ //B 1
			if(!is_array($val1)){
				$menuHTML1 .= '
				<tr>
            		<td width="175" height="20" colspan="2" class="leftHeader"><nobr>'.stripslashes($val[0]).'</nobr></td>
          		</tr>
          		';
			}
			if(is_array($val1) && $val1[2]==1){ //B 2
				$menuHTML1 .= '
				<tr>
            		<td class="leftLink" width="2">&nbsp;</td>
					<td class="leftLink" height="20" width="175" ><a href="'.stripslashes($val1[1]).'" />'.stripslashes($val1[0]).'</a></td>
				</tr>
				';
			}//B 2
		}
		$menuHTML1 .= '
			<tr>
            	<td class="leftLink" width="2"></td>
				<td class="leftLink" height="10" width="175" ></td>
			</tr>
			</table> </td></tr>';
	}
	return $menuHTML1;
}

//MOUVEMENT
function bonsortieMenus($tab ='', $droit=''){

	(isset($droit['bds_bds']['VISIBLE']) ? $bds_bds = $droit['bds_bds']['VISIBLE'] : $bds_bds= 0);
	(isset($droit['bds_dec']['VISIBLE']) ? $bds_dec = $droit['bds_dec']['VISIBLE'] : $bds_dec= 0);
	(isset($droit['bds_trf']['VISIBLE']) ? $bds_trf = $droit['bds_trf']['VISIBLE'] : $bds_trf= 0);
	(isset($droit['bds_rec']['VISIBLE']) ? $bds_rec = $droit['bds_rec']['VISIBLE'] : $bds_rec= 0);
	(isset($droit['bds_rep']['VISIBLE']) ? $bds_rep = $droit['bds_rep']['VISIBLE'] : $bds_rep= 0);

$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	if($ilang=='1' && $ilang!='') { 
	$TOPMENUS =array( //Libellé , Lien, Droit, Tab

	'0'=> array('0'=>'Sorties de produits',
				'3'=> array('Transferts', 'transfert.php?selectedTab=bds&rst=1',$bds_trf, 'bds'),
				'1'=> array('Consommations', 'bonsortie.php?selectedTab=bds&rst=1',$bds_bds, 'bds'),
				'2'=> array('Pertes', 'declassement.php?selectedTab=bds&rst=1',$bds_dec, 'bds'),
				'4'=> array('Reports', 'report.php?selectedTab=bds&rst=1',$bds_rep,'bds')
				)

		);
	}
	if($ilang=='2' && $ilang!='') { 
	$TOPMENUS =array( //Libellé , Lien, Droit, Tab

	'0'=> array('0'=>'Product output',
				'3'=> array('Transfers', 'transfert.php?selectedTab=bds&rst=1',$bds_trf, 'bds'),
				'1'=> array('Consumptions', 'bonsortie.php?selectedTab=bds&rst=1',$bds_bds, 'bds'),
				'2'=> array('Losses', 'declassement.php?selectedTab=bds&rst=1',$bds_dec, 'bds'),
				'4'=> array('Reports', 'report.php?selectedTab=bds&rst=1',$bds_rep,'bds')
				)

		);
	}
	if($ilang=='3' && $ilang!='') { 
	$TOPMENUS =array( //Libellé , Lien, Droit, Tab

	'0'=> array('0'=>'Saída do produto',
				'3'=> array('Transferências', 'transfert.php?selectedTab=bds&rst=1',$bds_trf, 'bds'),
				'1'=> array('Consumos', 'bonsortie.php?selectedTab=bds&rst=1',$bds_bds, 'bds'),
				'2'=> array('Perdas', 'declassement.php?selectedTab=bds&rst=1',$bds_dec, 'bds'),
				'4'=> array('Relatórios', 'report.php?selectedTab=bds&rst=1',$bds_rep,'bds')
				)

		);
	}


	$menuHTML1= '';
	foreach ($TOPMENUS as $key => $val){
		$menuHTML1 .= '<tr><td><table width="185" border="0" align="left" cellpadding="1" cellspacing="1">';
		foreach ($val as $key1 => $val1){ //B 1
			if(!is_array($val1)){
				$menuHTML1 .= '
				<tr>
            		<td width="175" height="20" colspan="2" class="leftHeader"><nobr>'.stripslashes($val[0]).'</nobr></td>
          		</tr>
          		';
			}
			if(is_array($val1) && $val1[2]==1){ //B 2
				$menuHTML1 .= '
				<tr>
            		<td class="leftLink" width="2">&nbsp;</td>
					<td class="leftLink" height="20" width="175" ><a href="'.stripslashes($val1[1]).'" />'.stripslashes($val1[0]).'</a></td>
				</tr>
				';
			}//B 2
		}
		$menuHTML1 .= '
			<tr>
            	<td class="leftLink" width="2"></td>
				<td class="leftLink" height="10" width="175" ></td>
			</tr>
			</table> </td></tr>';
	}
	return $menuHTML1;
}

//INVENTAIRE
function inventaireMenus($tab ='', $droit=''){

	(isset($droit['int_int']['VISIBLE']) ? $int_int = $droit['int_int']['VISIBLE'] : $int_int= 0);
	(isset($droit['int_sto']['VISIBLE']) ? $int_sto = $droit['int_sto']['VISIBLE'] : $int_sto= 0);
	(isset($droit['int_jou']['VISIBLE']) ? $int_jou = $droit['int_jou']['VISIBLE'] : $int_jou= 0);
	(isset($droit['int_stl']['VISIBLE']) ? $int_stl = $droit['int_stl']['VISIBLE'] : $int_stl= 0);
	(isset($droit['int_sta']['VISIBLE']) ? $int_sta = $droit['int_sta']['VISIBLE'] : $int_sta= 0);
	(isset($droit['int_sin']['VISIBLE']) ? $int_sin = $droit['int_sin']['VISIBLE'] : $int_sin= 0);
	(isset($droit['int_din']['VISIBLE']) ? $int_din = $droit['int_din']['VISIBLE'] : $int_din= 0);
	(isset($droit['int_mst']['VISIBLE']) ? $int_mst = $droit['int_mst']['VISIBLE'] : $int_mst= 0);
	(isset($droit['int_ppe']['VISIBLE']) ? $int_ppe = $droit['int_ppe']['VISIBLE'] : $int_ppe= 0);
	(isset($droit['int_sme']['VISIBLE']) ? $int_sme = $droit['int_sme']['VISIBLE'] : $int_sme= 0);
	(isset($droit['int_pac']['VISIBLE']) ? $int_pac = $droit['int_pac']['VISIBLE'] : $int_pac= 0);
	(isset($droit['int_mfr']['VISIBLE']) ? $int_mfr = $droit['int_mfr']['VISIBLE'] : $int_mfr= 0);
	(isset($droit['int_mde']['VISIBLE']) ? $int_mde = $droit['int_mde']['VISIBLE'] : $int_mde= 0);
	(isset($droit['int_rst']['VISIBLE']) ? $int_rst = $droit['int_rst']['VISIBLE'] : $int_rst= 0);
	(isset($droit['int_pcd']['VISIBLE']) ? $int_pcd = $droit['int_pcd']['VISIBLE'] : $int_pcd= 0);
	(isset($droit['int_rds']['VISIBLE']) ? $int_rds = $droit['int_rds']['VISIBLE'] : $int_rds= 0);

$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	if($ilang=='1' && $ilang!='') { 
	$TOPMENUS =array( //Libellé , Lien, Droit, Tab

	'0'=>  array('0'=>'Inventaires',
				'1'=> array('Imprimer fiche d\'inventaire', 'ficheinventaire.php?selectedTab=int',$int_pcd, 'int'),
				'2'=> array('Saisie d\'inventaire', 'inventaire.php?selectedTab=int&rst=1',$int_int, 'int')
				),
	'1'=>  array('0'=>'Etat du stock',
				'1'=> array('Etat du stock/Produits', 'etatstock.php?selectedTab=int',$int_sto, 'int'),
				'2'=> array('Etat du stock/Lots', 'etatstocklots.php?selectedTab=int', $int_stl, 'int'),
				'3'=> array('Journal des mouvements', 'journal.php?selectedTab=int', $int_jou, 'int')
				)

	);

	}
	if($ilang=='2' && $ilang!='') { 
	$TOPMENUS =array( //Libellé , Lien, Droit, Tab
	'0'=>  array('0'=>'Inventory',
				'1'=> array('Print inventory sheet', 'ficheinventaire.php?selectedTab=int',$int_pcd, 'int'),
				'2'=> array('Entering Inventory', 'inventaire.php?selectedTab=int&rst=1',$int_int, 'int')
				),
	'1'=>  array('0'=>'Stock status',
				'1'=> array('State stock / product', 'etatstock.php?selectedTab=int',$int_sto, 'int'),
				'2'=> array('State of stock / lot', 'etatstocklots.php?selectedTab=int', $int_stl, 'int'),
				'3'=> array('Journal of stock movements', 'journal.php?selectedTab=int', $int_jou, 'int')
				)
	);
	}
	if($ilang=='3' && $ilang!='') { 
	$TOPMENUS =array( //Libellé , Lien, Droit, Tab
	'0'=>  array('0'=>'Inventário',
				'1'=> array('Folha de inventário Imprimir', 'ficheinventaire.php?selectedTab=int',$int_pcd, 'int'),
				'2'=> array('Entrando Inventory', 'inventaire.php?selectedTab=int&rst=1',$int_int, 'int')
				),
	'1'=>  array('0'=>'Da estatuto',
				'1'=> array('Estoque Estado / produto', 'etatstock.php?selectedTab=int',$int_sto, 'int'),
				'2'=> array('Estado de estoque / lot', 'etatstocklots.php?selectedTab=int', $int_stl, 'int'),
				'3'=> array('Jornal de movimentos de stock', 'journal.php?selectedTab=int', $int_jou, 'int')
				)

	);

	}


	//$t_droit = preg_split('/ /',$droit);

	$menuHTML1= '';
	foreach ($TOPMENUS as $key => $val){
		$menuHTML1 .= '<tr><td><table width="185" border="0" align="left" cellpadding="1" cellspacing="1">';
		foreach ($val as $key1 => $val1){ //B 1
			if(!is_array($val1)){
				$menuHTML1 .= '
				<tr>
            		<td width="175" height="20" colspan="2" class="leftHeader"><nobr>'.stripslashes($val[0]).'</nobr></td>
          		</tr>
          		';
			}
			if(is_array($val1) && $val1[2]==1){ //B 2
				$menuHTML1 .= '
				<tr>
            		<td class="leftLink" width="2">&nbsp;</td>
					<td class="leftLink" height="20" width="175" ><a href="'.stripslashes($val1[1]).'" />'.stripslashes($val1[0]).'</a></td>
				</tr>
				';
			}//B 2
		}
		$menuHTML1 .= '
			<tr>
            	<td class="leftLink" width="2"></td>
				<td class="leftLink" height="10" width="175" ></td>
			</tr>
			</table> </td></tr>';
	}
	return $menuHTML1;
}

//INVENTAIRE
function rapportMenus($tab ='', $droit=''){

	(isset($droit['rap_sta']['VISIBLE']) ? $rap_sta = $droit['rap_sta']['VISIBLE'] : $rap_sta= 0);
	(isset($droit['rap_sin']['VISIBLE']) ? $rap_sin = $droit['rap_sin']['VISIBLE'] : $rap_sin= 0);
	(isset($droit['rap_din']['VISIBLE']) ? $rap_din = $droit['rap_din']['VISIBLE'] : $rap_din= 0);
	(isset($droit['rap_mst']['VISIBLE']) ? $rap_mst = $droit['rap_mst']['VISIBLE'] : $rap_mst= 0);
	(isset($droit['rap_ppe']['VISIBLE']) ? $rap_ppe = $droit['rap_ppe']['VISIBLE'] : $rap_ppe= 0);
	(isset($droit['rap_sme']['VISIBLE']) ? $rap_sme = $droit['rap_sme']['VISIBLE'] : $rap_sme= 0);
	(isset($droit['rap_pac']['VISIBLE']) ? $rap_pac = $droit['rap_pac']['VISIBLE'] : $rap_pac= 0);
	(isset($droit['rap_mfr']['VISIBLE']) ? $rap_mfr = $droit['rap_mfr']['VISIBLE'] : $rap_mfr= 0);
	(isset($droit['rap_mde']['VISIBLE']) ? $rap_mde = $droit['rap_mde']['VISIBLE'] : $rap_mde= 0);
	(isset($droit['rap_rst']['VISIBLE']) ? $rap_rst = $droit['rap_rst']['VISIBLE'] : $rap_rst= 0);
	(isset($droit['rap_pcd']['VISIBLE']) ? $rap_pcd = $droit['rap_pcd']['VISIBLE'] : $rap_pcd= 0);
	(isset($droit['rap_rds']['VISIBLE']) ? $rap_rds = $droit['rap_rds']['VISIBLE'] : $rap_rds= 0);
	(isset($droit['rap_men']['VISIBLE']) ? $rap_men = $droit['rap_men']['VISIBLE'] : $rap_men= 0);
	(isset($droit['rap_tri']['VISIBLE']) ? $rap_tri = $droit['rap_tri']['VISIBLE'] : $rap_tri= 0);
	(isset($droit['rap_cons']['VISIBLE']) ? $rap_cons = $droit['rap_cons']['VISIBLE'] : $rap_cons= 0);
	(isset($droit['rap_dec']['VISIBLE']) ? $rap_dec = $droit['rap_dec']['VISIBLE'] : $rap_dec= 0);
	(isset($droit['rap_fprd']['VISIBLE']) ? $rap_fprd = $droit['rap_fprd']['VISIBLE'] : $rap_fprd= 0);
	(isset($droit['rap_prdp']['VISIBLE']) ? $rap_prdp = $droit['rap_prdp']['VISIBLE'] : $rap_prdp= 0);
	(isset($droit['rap_sssm']['VISIBLE']) ? $rap_sssm = $droit['rap_sssm']['VISIBLE'] : $rap_sssm= 0);

$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	if($ilang=='1' && $ilang!='') { 
	$TOPMENUS =array( //Libellé , Lien, Droit, Tab

	'0'=>  array('0'=>'Rapports divers',
				'1'=> array('Rapport détaillé des entrées', 'rapdetailentree.php?selectedTab=rap',$rap_din, 'rap'),
				'2'=> array('Rapport détaillé des sorties', 'rapdetsortie.php?selectedTab=rap', $rap_rds, 'rap'),
				'3'=> array('Rapport produits à commander', 'rapprdcommande.php?selectedTab=rap', $rap_pac, 'rap'),
				//'2'=> array('Rapport synthèse inventaire', 'rapsyntheseinventaire.php?selectedTab=rap', $rap_sin, 'rap'),
				//'3'=> array('Rapport détaillé inventaire', 'rapinventairedetaille.php?selectedTab=rap', $rap_din, 'rap'),
				'4'=> array('Rapport mouvement de Stocks', 'rapmvtstock.php?selectedTab=rap', $rap_mst, 'rap'),
				'5'=> array('Rapport péremption (Produits périmés)', 'rapprdperime.php?selectedTab=rap', $rap_ppe, 'rap'),
				'6'=> array('Sorties mensuelles', 'rapsortiemensuelle.php?selectedTab=rap', $rap_sme, 'rap'),
				'7'=> array('Sorties  moyennes mensuelles', 'rapsortiemoymensuelle.php?selectedTab=rap', $rap_sme, 'rap'),
				'9'=> array('Rapport mouvement  fournisseurs', 'rapmvtfournisseur.php?selectedTab=rap', $rap_mfr, 'rap'),
				'10'=> array('Rapport mouvement  destinataires', 'rapmvtdestinaire.php?selectedTab=rap', $rap_mde, 'rap'),
				'11'=> array('Rapport de rupture de stock', 'raprupture.php?selectedTab=rap', $rap_rst, 'rap'),
				'12'=> array('Rapport périodique simple', 'rapmensuel.php?selectedTab=rap', $rap_men, 'rap'),
				'13'=> array('Rapport périodique consolidé', 'raptrimestriel.php?selectedTab=rap', $rap_tri, 'rap'),
				'14'=> array('Rapport de consommation', 'rapconsommation.php?selectedTab=rap', $rap_cons, 'rap'),
				'15'=> array('Rapport pertes', 'rapdeclassement.php?selectedTab=rap', $rap_dec, 'rap'),
				'16'=> array('Fiche de stock produit', 'rapficheproduit.php?selectedTab=rap', $rap_fprd, 'rap'),
//				'17'=> array('Produit au seuil de péremption', 'rapproduitaperime.php?selectedTab=rap', $rap_prdp, 'rap'),
				'18'=> array('Rapport de surstockage des sites', 'rapstocksupseuilmax.php?selectedTab=rap', $rap_sssm, 'rap'),
			)
	);
	}
	if($ilang=='2' && $ilang!='') { 
	$TOPMENUS =array( //Libellé , Lien, Droit, Tab

	'0'=>  array('0'=>'Various reports',
				'1'=> array('Detailed Report of entries', 'rapdetailentree.php?selectedTab=rap',$rap_din, 'rap'),
				'2'=> array('Detailed Report of Issued', 'rapdetsortie.php?selectedTab=rap', $rap_rds, 'rap'),
				'3'=> array('Report of the Products to order', 'rapprdcommande.php?selectedTab=rap', $rap_pac, 'rap'),
				//'2'=> array('Rapport synthèse inventaire', 'rapsyntheseinventaire.php?selectedTab=rap', $rap_sin, 'rap'),
				//'3'=> array('Rapport détaillé inventaire', 'rapinventairedetaille.php?selectedTab=rap', $rap_din, 'rap'),
				'4'=> array('Stock activity report', 'rapmvtstock.php?selectedTab=rap', $rap_mst, 'rap'),
				'5'=> array('Expiration date report (Expired products)', 'rapprdperime.php?selectedTab=rap', $rap_ppe, 'rap'),
				'6'=> array('Monthly outings', 'rapsortiemensuelle.php?selectedTab=rap', $rap_sme, 'rap'),
				'7'=> array('Sorties  moyennes mensuelles', 'rapsortiemoymensuelle.php?selectedTab=rap', $rap_sme, 'rap'),
				'9'=> array('Suppliers activity report', 'rapmvtfournisseur.php?selectedTab=rap', $rap_mfr, 'rap'),
				'10'=> array('Recipients activity report', 'rapmvtdestinaire.php?selectedTab=rap', $rap_mde, 'rap'),
				'11'=> array('Report of out of stock / Stock out Report', 'raprupture.php?selectedTab=rap', $rap_rst, 'rap'),
				'12'=> array('Simple periodic reports', 'rapmensuel.php?selectedTab=rap', $rap_men, 'rap'),
				'13'=> array('Consolidated periodic reports', 'raptrimestriel.php?selectedTab=rap', $rap_tri, 'rap'),
				'14'=> array('Consumption report', 'rapconsommation.php?selectedTab=rap', $rap_cons, 'rap'),
				'15'=> array('Report of the losses', 'rapdeclassement.php?selectedTab=rap', $rap_dec, 'rap'),
				'16'=> array('Product stock card', 'rapficheproduit.php?selectedTab=rap', $rap_fprd, 'rap'),
//				'17'=> array('Produit au seuil de péremption', 'rapproduitaperime.php?selectedTab=rap', $rap_prdp, 'rap'),
				'18'=> array('Overstocking Report website', 'rapstocksupseuilmax.php?selectedTab=rap', $rap_sssm, 'rap'),
			)
	);
	}
	if($ilang=='3' && $ilang!='') { 
	$TOPMENUS =array( //Libellé , Lien, Droit, Tab

	'0'=>  array('0'=>'Vários relatórios',
				'1'=> array('Relatório detalhado das entradas', 'rapdetailentree.php?selectedTab=rap',$rap_din, 'rap'),
				'2'=> array('Relatório detalhado das saídas', 'rapdetsortie.php?selectedTab=rap', $rap_rds, 'rap'),
				'3'=> array('Relatório Produtos à requisitar', 'rapprdcommande.php?selectedTab=rap', $rap_pac, 'rap'),
				//'2'=> array('Rapport synthèse inventaire', 'rapsyntheseinventaire.php?selectedTab=rap', $rap_sin, 'rap'),
				//'3'=> array('Rapport détaillé inventaire', 'rapinventairedetaille.php?selectedTab=rap', $rap_din, 'rap'),
				'4'=> array('Relatório movimento stock', 'rapmvtstock.php?selectedTab=rap', $rap_mst, 'rap'),
				'5'=> array('Relatório de expiração (Produtos expirados)', 'rapprdperime.php?selectedTab=rap', $rap_ppe, 'rap'),
				'6'=> array('Passeios mensais', 'rapsortiemensuelle.php?selectedTab=rap', $rap_sme, 'rap'),
				'7'=> array('Sorties  moyennes mensuelles', 'rapsortiemoymensuelle.php?selectedTab=rap', $rap_sme, 'rap'),
				'9'=> array('Relatório movimento fornecedores', 'rapmvtfournisseur.php?selectedTab=rap', $rap_mfr, 'rap'),
				'10'=> array('Relatório movimento destinatários', 'rapmvtdestinaire.php?selectedTab=rap', $rap_mde, 'rap'),
				'11'=> array('Relatório de rotura de stock', 'raprupture.php?selectedTab=rap', $rap_rst, 'rap'),
				'12'=> array('Relatórios periódicos simples', 'rapmensuel.php?selectedTab=rap', $rap_men, 'rap'),
				'13'=> array('Relatórios periódicos consolidadas', 'raptrimestriel.php?selectedTab=rap', $rap_tri, 'rap'),
				'14'=> array('Relatório de consumo', 'rapconsommation.php?selectedTab=rap', $rap_cons, 'rap'),
				'15'=> array('Relatório de perdas', 'rapdeclassement.php?selectedTab=rap', $rap_dec, 'rap'),
				'16'=> array('Cartão de estoque do produto', 'rapficheproduit.php?selectedTab=rap', $rap_fprd, 'rap'),
//				'17'=> array('Produit au seuil de péremption', 'rapproduitaperime.php?selectedTab=rap', $rap_prdp, 'rap'),
				'18'=> array('Overstocking relatório de site.', 'rapstocksupseuilmax.php?selectedTab=rap', $rap_sssm, 'rap'),
			)
	);
	}



	//$t_droit = preg_split('/ /',$droit);

	$menuHTML1= '';
	foreach ($TOPMENUS as $key => $val){
		$menuHTML1 .= '<tr><td><table width="185" border="0" align="left" cellpadding="1" cellspacing="1">';
		foreach ($val as $key1 => $val1){ //B 1
			if(!is_array($val1)){
				$menuHTML1 .= '
				<tr>
            		<td width="175" height="20" colspan="2" class="leftHeader"><nobr>'.stripslashes($val[0]).'</nobr></td>
          		</tr>
          		';
			}
			if(is_array($val1) && $val1[2]==1){ //B 2
				$menuHTML1 .= '
				<tr>
            		<td class="leftLink" width="2">&nbsp;</td>
					<td class="leftLink" height="20" width="175" ><a href="'.stripslashes($val1[1]).'" />'.stripslashes($val1[0]).'</a></td>
				</tr>
				';
			}//B 2
		}
		$menuHTML1 .= '
			<tr>
            	<td class="leftLink" width="2"></td>
				<td class="leftLink" height="10" width="175" ></td>
			</tr>
			</table> </td></tr>';
	}
	return $menuHTML1;
}

//ANNULATION
function annulationMenus($tab ='', $droit=''){

	(isset($droit['ann_cde']['VISIBLE']) ? $ann_cde = $droit['ann_cde']['VISIBLE'] : $ann_cde= 0);
	(isset($droit['ann_liv']['VISIBLE']) ? $ann_liv = $droit['ann_liv']['VISIBLE'] : $ann_liv= 0);
	(isset($droit['ann_aut']['VISIBLE']) ? $ann_aut = $droit['ann_aut']['VISIBLE'] : $ann_aut= 0);
	(isset($droit['ann_prg']['VISIBLE']) ? $ann_prg = $droit['ann_prg']['VISIBLE'] : $ann_prg= 0);
	(isset($droit['ann_rev']['VISIBLE']) ? $ann_rev = $droit['ann_rev']['VISIBLE'] : $ann_rev= 0);
	(isset($droit['ann_bac']['VISIBLE']) ? $ann_bac = $droit['ann_bac']['VISIBLE'] : $ann_bac= 0);

	(isset($droit['ann_dot']['VISIBLE']) ? $ann_dot = $droit['ann_dot']['VISIBLE'] : $ann_dot= 0);
	(isset($droit['ann_dec']['VISIBLE']) ? $ann_dec = $droit['ann_dec']['VISIBLE'] : $ann_dec= 0);
	(isset($droit['ann_trf']['VISIBLE']) ? $ann_trf = $droit['ann_trf']['VISIBLE'] : $ann_trf= 0);
	(isset($droit['ann_rec']['VISIBLE']) ? $ann_rec = $droit['ann_rec']['VISIBLE'] : $ann_rec= 0);

	(isset($droit['ann_rep']['VISIBLE']) ? $ann_rep = $droit['ann_rep']['VISIBLE'] : $ann_rep= 0);
	(isset($droit['ann_ust']['VISIBLE']) ? $ann_ust = $droit['ann_ust']['VISIBLE'] : $ann_ust= 0);
	(isset($droit['ann_dbac']['VISIBLE']) ? $ann_dbac = $droit['ann_dbac']['VISIBLE'] : $ann_dbac= 0);
	(isset($droit['ann_daut']['VISIBLE']) ? $ann_daut = $droit['ann_daut']['VISIBLE'] : $ann_daut= 0);

	$TOPMENUS =array( //Libellé , Lien, Droit, Tab

	'0'=> array('0'=>'Commandes/Livraisons',
				'1'=> array('Commandes', 'annorder.php?selectedTab=ann&rst=1',$ann_cde, 'ann'),
				'2'=> array('Livraisons', 'anndelivery.php?selectedTab=ann&rst=1',$ann_liv, 'ann'),
				'3'=> array('Autres livraisons', 'annotherdelivery.php?selectedTab=ann&rst=1',$ann_aut, 'ann')
				),

	'1'=> array('0'=>'Programmations/Reversements',
				'1'=> array('Programmations', 'annprogramme.php?selectedTab=ann&rst=1',$ann_prg, 'ann'),
				'2'=> array('Reversements', 'annreversement.php?selectedTab=ann&rst=1',$ann_rev, 'ann'),
				),

	'2'=> array('0'=>'Programmations BAC',
				'1'=> array('Programmations BAC', 'annprogrammebac.php?selectedTab=ann&rst=1',$ann_bac, 'ann')
				),

	'3'=> array('0'=>'Mouvement de stock',
				'1'=> array('Dotations', 'anndotation.php?selectedTab=ann&rst=1',$ann_dot, 'ann'),
				'2'=> array('Déclassements', 'anndeclassement.php?selectedTab=ann&rst=1',$ann_dec, 'ann'),
				'3'=> array('Transferts', 'anntransfert.php?selectedTab=ann&rst=1',$ann_trf, 'ann'),
				'4'=> array('Reconditionnements', 'annreconditionnement.php?selectedTab=ann&rst=1',$ann_rec, 'ann'),
				'5'=> array('Reports', 'annreport.php?selectedTab=ann&rst=1',$ann_rep,'bds'),
				),

	'4'=> array('0'=>'Dotations BAC',
				'1'=> array('Dotations BAC', 'anndotationbac.php?selectedTab=ann&rst=1',$ann_dbac, 'ann'),
				),

	'5'=> array('0'=>'Autres dotations',
				'1'=> array('Autres dotations', 'annautredotation.php?selectedTab=ann&rst=1',$ann_daut, 'bds'),
				)

	);

	//$t_droit = preg_split('/ /',$droit);

	$menuHTML1= '';
	foreach ($TOPMENUS as $key => $val){
		$menuHTML1 .= '<tr><td><table width="185" border="0" align="left" cellpadding="1" cellspacing="1">';
		foreach ($val as $key1 => $val1){ //B 1
			if(!is_array($val1)){
				$menuHTML1 .= '
				<tr>
            		<td width="175" height="20" colspan="2" class="leftHeader"><nobr>'.stripslashes($val[0]).'</nobr></td>
          		</tr>
          		';
			}
			if(is_array($val1) && $val1[2]==1){ //B 2
				$menuHTML1 .= '
				<tr>
            		<td class="leftLink" width="2">&nbsp;</td>
					<td class="leftLink" height="20" width="175" ><a href="'.stripslashes($val1[1]).'" />'.stripslashes($val1[0]).'</a></td>
				</tr>
				';
			}//B 2
		}
		$menuHTML1 .= '
			<tr>
            	<td class="leftLink" width="2"></td>
				<td class="leftLink" height="10" width="175" ></td>
			</tr>
			</table> </td></tr>';
	}
	return $menuHTML1;
}

function parametersMenus($tab ='', $droit=''){

	(isset($droit['par_per']['VISIBLE']) ? $par_per = $droit['par_per']['VISIBLE'] : $par_per= 0);
	(isset($droit['par_uti']['VISIBLE']) ? $par_uti = $droit['par_uti']['VISIBLE'] : $par_uti= 0);
	(isset($droit['par_grp']['VISIBLE']) ? $par_grp = $droit['par_grp']['VISIBLE'] : $par_grp= 0);
	(isset($droit['par_log']['VISIBLE']) ? $par_log = $droit['par_log']['VISIBLE'] : $par_log= 0);
	(isset($droit['par_men']['VISIBLE']) ? $par_men = $droit['par_men']['VISIBLE'] : $par_men= 0);

	(isset($droit['par_cat']['VISIBLE']) ? $par_cat = $droit['par_cat']['VISIBLE'] : $par_cat= 0);
	(isset($droit['par_sscat']['VISIBLE']) ? $par_sscat = $droit['par_sscat']['VISIBLE'] : $par_sscat= 0);
	(isset($droit['par_ssg']['VISIBLE']) ? $par_ssg = $droit['par_ssg']['VISIBLE'] : $par_ssg= 0);
	(isset($droit['par_prd']['VISIBLE']) ? $par_prd = $droit['par_prd']['VISIBLE'] : $par_prd= 0);
	(isset($droit['par_con']['VISIBLE']) ? $par_con = $droit['par_con']['VISIBLE'] : $par_con= 0);
	(isset($droit['par_uni']['VISIBLE']) ? $par_uni = $droit['par_uni']['VISIBLE'] : $par_uni= 0);
	(isset($droit['par_bar']['VISIBLE']) ? $par_bar = $droit['par_bar']['VISIBLE'] : $par_bar= 0);
	(isset($droit['par_bac']['VISIBLE']) ? $par_bac = $droit['par_bac']['VISIBLE'] : $par_bac= 0);

	(isset($droit['par_reg']['VISIBLE']) ? $par_reg = $droit['par_reg']['VISIBLE'] : $par_reg= 0);
	(isset($droit['par_prv']['VISIBLE']) ? $par_prv = $droit['par_prv']['VISIBLE'] : $par_prv= 0);
	(isset($droit['par_ser']['VISIBLE']) ? $par_ser = $droit['par_ser']['VISIBLE'] : $par_ser= 0);
	(isset($droit['par_mag']['VISIBLE']) ? $par_mag = $droit['par_mag']['VISIBLE'] : $par_mag= 0);
	(isset($droit['par_res']['VISIBLE']) ? $par_res = $droit['par_res']['VISIBLE'] : $par_res= 0);

	(isset($droit['par_bud']['VISIBLE']) ? $par_bud = $droit['par_bud']['VISIBLE'] : $par_bud= 0);
	(isset($droit['par_dot']['VISIBLE']) ? $par_dot = $droit['par_dot']['VISIBLE'] : $par_dot= 0);
	(isset($droit['par_gen']['VISIBLE']) ? $par_gen = $droit['par_gen']['VISIBLE'] : $par_gen= 0);

	(isset($droit['par_fou']['VISIBLE']) ? $par_fou = $droit['par_fou']['VISIBLE'] : $par_fou= 0);
	(isset($droit['par_tfr']['VISIBLE']) ? $par_tfr = $droit['par_tfr']['VISIBLE'] : $par_tfr= 0);
	(isset($droit['par_ben']['VISIBLE']) ? $par_ben = $droit['par_ben']['VISIBLE'] : $par_ben= 0);
	(isset($droit['par_tbe']['VISIBLE']) ? $par_tbe = $droit['par_tbe']['VISIBLE'] : $par_tbe= 0);
	(isset($droit['par_aff']['VISIBLE']) ? $par_aff = $droit['par_aff']['VISIBLE'] : $par_aff= 0);
	(isset($droit['par_inf']['VISIBLE']) ? $par_inf = $droit['par_inf']['VISIBLE'] : $par_inf= 0);
	(isset($droit['par_ctr']['VISIBLE']) ? $par_ctr = $droit['par_ctr']['VISIBLE'] : $par_ctr= 0);
	(isset($droit['par_ndcl']['VISIBLE']) ? $par_ndcl = $droit['par_ndcl']['VISIBLE'] : $par_ndcl= 0);
	(isset($droit['par_aff']['VISIBLE']) ? $par_aff = $droit['par_aff']['VISIBLE'] : $par_aff= 0);
	(isset($droit['par_tse']['VISIBLE']) ? $par_tse = $droit['par_tse']['VISIBLE'] : $par_tse= 0);

$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	if($ilang=='1' && $ilang!='') { 
	$TOPMENUS =array( //Libellé , Lien, Droit, Tab
	'0'=> array('0'=>'Utilisateurs & groupes',
				'3'=> array('Groupe d\'utilisateurs', 'profil.php?selectedTab=par&rst=1',$par_grp, 'par'),
				'1'=> array('Personnel', 'personnel.php?selectedTab=par&rst=1',$par_per, 'par'),
				'2'=> array('Utilisateur', 'user.php?selectedTab=par&rst=1',$par_uti, 'par'),
				'4'=> array('Logs', 'log.php?selectedTab=par&rst=1',$par_log, 'par'),
				//'5'=> array('Menu logiciel', 'menu.php?selectedTab=par&rst=1',$par_men, 'par')
			),

	'1'=> array('0'=>'Catégories & produits',
				'1'=> array('Catégorie', 'categorie.php?selectedTab=par&rst=1',$par_cat, 'par'),
				'2'=> array('Sous-Catégorie', 'souscategorie.php?selectedTab=par&rst=1',$par_sscat, 'par'),
				'3'=> array('Sous-groupe', 'sousgroupe.php?selectedTab=par&rst=1',$par_ssg, 'par'),
				'4'=> array('Produit', 'produit.php?selectedTab=par&rst=1',$par_prd, 'par'),
				'5'=> array('Mise à jour des prix', 'majprix.php?selectedTab=par&rst=1',$par_prd, 'par'),
				'6'=> array('Unité de mesure', 'unite.php?selectedTab=par&rst=1',$par_uni, 'par'),
//				'7'=> array('Mise Langue', 'langue.php?selectedTab=par&rst=1',$par_lan, 'par'),
				),

	'2'=> array('0'=>'Circuit de distribution',
				'1'=> array('Niveau central', 'region.php?selectedTab=par&rst=1',$par_reg, 'par'),
				'2'=> array('Site fournisseur', 'province.php?selectedTab=par&rst=1',$par_prv, 'par'),
				'3'=> array('Site bénéficiaire', 'service.php?selectedTab=par&rst=1',$par_mag, 'par'),
				'4'=> array('Affectation des sites par utilisateur', 'affectation.php?selectedTab=par&rst=1',$par_aff, 'par'),
				),

	'3'=> array('0'=>'Fournisseurs & Bénéficiaires',
				'2'=> array('Type fournisseur', 'typefournisseur.php?selectedTab=par&rst=1',$par_tfr, 'par'),
				'1'=> array('Fournisseur', 'fournisseur.php?selectedTab=par&rst=1',$par_fou, 'par'),
				'4'=> array('Type bénéficiaire', 'typebeneficiaire.php?selectedTab=par&rst=1',$par_tse, 'par'),
				'3'=> array('Bénéficiaire', 'beneficiaire.php?selectedTab=par&rst=1',$par_ben, 'par'),
				'5'=> array('Nature de pertes', 'natdeclassement.php?selectedTab=par&rst=1',$par_ndcl, 'par')
				),

	'4'=> array('0'=>'Autres paramètres',
				'1'=> array('Exercice budgétaire', 'exercice.php?selectedTab=par&rst=1',$par_bud, 'par'),
				'2'=> array('Paramètres généraux', 'generale.php?selectedTab=par&rst=1',$par_gen, 'par'),
				)
	);
	}
	if($ilang=='2' && $ilang!='') { 
	$TOPMENUS =array( //Libellé , Lien, Droit, Tab
	'0'=> array('0'=>'Users & Groups',
				'3'=> array('User Group', 'profil.php?selectedTab=par&rst=1',$par_grp, 'par'),
				'1'=> array('Staff', 'personnel.php?selectedTab=par&rst=1',$par_per, 'par'),
				'2'=> array('User', 'user.php?selectedTab=par&rst=1',$par_uti, 'par'),
				'4'=> array('Logs', 'log.php?selectedTab=par&rst=1',$par_log, 'par'),
				//'5'=> array('Menu logiciel', 'menu.php?selectedTab=par&rst=1',$par_men, 'par')
			),

	'1'=> array('0'=>'Categories and Products',
				'1'=> array('Category', 'categorie.php?selectedTab=par&rst=1',$par_cat, 'par'),
				'2'=> array('Sub-Category', 'souscategorie.php?selectedTab=par&rst=1',$par_sscat, 'par'),
				'3'=> array('Subgroup', 'sousgroupe.php?selectedTab=par&rst=1',$par_ssg, 'par'),
				'4'=> array('Product', 'produit.php?selectedTab=par&rst=1',$par_prd, 'par'),
				'5'=> array('Update prices', 'majprix.php?selectedTab=par&rst=1',$par_prd, 'par'),
				'6'=> array('Unit of measure', 'unite.php?selectedTab=par&rst=1',$par_uni, 'par'),
//				'7'=> array('Mise Langue', 'langue.php?selectedTab=par&rst=1',$par_lan, 'par'),
				),

	'2'=> array('0'=>'Distribution circuit',
				'1'=> array('Central level', 'region.php?selectedTab=par&rst=1',$par_reg, 'par'),
				'2'=> array('Site Provider', 'province.php?selectedTab=par&rst=1',$par_prv, 'par'),
				'3'=> array('Site beneficiary', 'service.php?selectedTab=par&rst=1',$par_mag, 'par'),
				'4'=> array('Affectation site by User  ', 'affectation.php?selectedTab=par&rst=1',$par_aff, 'par'),
				),

	'3'=> array('0'=>'Suppliers & Beneficiaries',
				'2'=> array('Type supplier', 'typefournisseur.php?selectedTab=par&rst=1',$par_tfr, 'par'),
				'1'=> array('Provider', 'fournisseur.php?selectedTab=par&rst=1',$par_fou, 'par'),
				'4'=> array('Type beneficiary', 'typebeneficiaire.php?selectedTab=par&rst=1',$par_tse, 'par'),
				'3'=> array('Beneficiary', 'beneficiaire.php?selectedTab=par&rst=1',$par_ben, 'par'),
				'5'=> array('Nature losses', 'natdeclassement.php?selectedTab=par&rst=1',$par_ndcl, 'par'),
				),

	'4'=> array('0'=>'Other parameters',
				'1'=> array('Fiscal year', 'exercice.php?selectedTab=par&rst=1',$par_bud, 'par'),
				'2'=> array('General Settings', 'generale.php?selectedTab=par&rst=1',$par_gen, 'par'),
				)
	);
	}
	if($ilang=='3' && $ilang!='') { 
	$TOPMENUS =array( //Libellé , Lien, Droit, Tab
	'0'=> array('0'=>'Usuários e Grupos',
				'3'=> array('Grupo de Usuários', 'profil.php?selectedTab=par&rst=1',$par_grp, 'par'),
				'1'=> array('Pessoal', 'personnel.php?selectedTab=par&rst=1',$par_per, 'par'),
				'2'=> array('Usuário', 'user.php?selectedTab=par&rst=1',$par_uti, 'par'),
				'4'=> array('Logs', 'log.php?selectedTab=par&rst=1',$par_log, 'par'),
				//'5'=> array('Menu logiciel', 'menu.php?selectedTab=par&rst=1',$par_men, 'par')
			),

	'1'=> array('0'=>'Categorias e Produtos',
				'1'=> array('Categoria', 'categorie.php?selectedTab=par&rst=1',$par_cat, 'par'),
				'2'=> array('Sub-Category', 'souscategorie.php?selectedTab=par&rst=1',$par_sscat, 'par'),
				'3'=> array('Subgrupo', 'sousgroupe.php?selectedTab=par&rst=1',$par_ssg, 'par'),
				'4'=> array('Produto', 'produit.php?selectedTab=par&rst=1',$par_prd, 'par'),
				'5'=> array('Atualizar preços', 'majprix.php?selectedTab=par&rst=1',$par_prd, 'par'),
				'6'=> array('Unidade de medida', 'unite.php?selectedTab=par&rst=1',$par_uni, 'par'),
//				'7'=> array('Mise Langue', 'langue.php?selectedTab=par&rst=1',$par_lan, 'par'),
				),

	'2'=> array('0'=>'Circuito de distribuição',
				'1'=> array('Nível Central', 'region.php?selectedTab=par&rst=1',$par_reg, 'par'),
				'2'=> array('Provedor do Site', 'province.php?selectedTab=par&rst=1',$par_prv, 'par'),
				'3'=> array('Beneficiário do Site', 'service.php?selectedTab=par&rst=1',$par_mag, 'par'),
				'4'=> array('Affectation site/Usuário', 'affectation.php?selectedTab=par&rst=1',$par_aff, 'par'),
				),

	'3'=> array('0'=>'Fornecedores e Beneficiários',
				'2'=> array('Tipo de fornecedor', 'typefournisseur.php?selectedTab=par&rst=1',$par_tfr, 'par'),
				'1'=> array('Provedor', 'fournisseur.php?selectedTab=par&rst=1',$par_fou, 'par'),
				'4'=> array('Tipo de beneficiário', 'typebeneficiaire.php?selectedTab=par&rst=1',$par_tse, 'par'),
				'3'=> array('Beneficiário', 'beneficiaire.php?selectedTab=par&rst=1',$par_ben, 'par'),
				'5'=> array('Perdas Natureza', 'natdeclassement.php?selectedTab=par&rst=1',$par_ndcl, 'par'),
				),

	'4'=> array('0'=>'Outros parâmetros',
				'1'=> array('Ano fiscal', 'exercice.php?selectedTab=par&rst=1',$par_bud, 'par'),
				'2'=> array('Configurações Gerais', 'generale.php?selectedTab=par&rst=1',$par_gen, 'par'),
				)
	);
	}

	//,				'4'=> array('Sauvegarde', 'sauvegarde.php?selectedTab=par&rst=1',$par_sau, 'par')
	$menuHTML1= '';
	foreach ($TOPMENUS as $key => $val){
		$menuHTML1 .= '<tr><td><table width="185" border="0" align="left" cellpadding="1" cellspacing="1">';
		foreach ($val as $key1 => $val1){ //B 1
			if(!is_array($val1)){
				$menuHTML1 .= '
				<tr>
            		<td width="175" height="20" colspan="2" class="leftHeader"><nobr>'.stripslashes($val[0]).'</nobr></td>
          		</tr>
          		';
			}
			if(is_array($val1) && $val1[2]==1){ //B 2
				$menuHTML1 .= '
				<tr>
            		<td class="leftLink" width="2">&nbsp;</td>
					<td class="leftLink" height="20" width="175" ><a href="'.stripslashes($val1[1]).'" />'.stripslashes($val1[0]).'</a></td>
				</tr>
				';
			}//B 2
		}
		$menuHTML1 .= '
			<tr>
            	<td class="leftLink" width="2"></td>
				<td class="leftLink" height="10" width="175" ></td>
			</tr>
			</table> </td></tr>';
	}
	return $menuHTML1;
}


//PARAMETRES
function basededonneesMenus($tab ='', $droit=''){

	(isset($droit['data_vid']['VISIBLE']) ? $data_vid = $droit['data_vid']['VISIBLE'] : $data_vid= 0);
	(isset($droit['data_exp']['VISIBLE']) ? $data_exp = $droit['data_exp']['VISIBLE'] : $data_exp= 0);
	(isset($droit['data_imp']['VISIBLE']) ? $data_imp = $droit['data_imp']['VISIBLE'] : $data_imp= 0);

$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	if($ilang=='1' && $ilang!='') { 

	$TOPMENUS =array( //Libellé , Lien, Droit, Tab
	'0'=> array('0'=>'Base de données',
				'1'=> array('Vider la base de données', 'vider.php?selectedTab=data&rst=1',$data_vid, 'data'),
				'2'=> array('Sauvegarde de la base', 'sauvegarde.php?selectedTab=data&rst=1',$data_exp, 'data'),
				'3'=> array('Importer de la base', 'import.php?selectedTab=data&rst=1',$data_imp, 'data')
				),
//	'1'=> array('0'=>'Historique',
//				'1'=> array('Fichier base de données', 'fichierdb.php?selectedTab=data&rst=1',$data_exp, 'data'),
//				'2'=> array('Fichier Excel exporté', 'fichierexcel.php?selectedTab=data&rst=1',$data_exp, 'data')
//				)
	);
	}
	if($ilang=='2' && $ilang!='') { 

	$TOPMENUS =array( //Libellé , Lien, Droit, Tab
	'0'=> array('0'=>'Database',
				'1'=> array('Empty database', 'vider.php?selectedTab=data&rst=1',$data_vid, 'data'),
				'2'=> array('Database backup', 'sauvegarde.php?selectedTab=data&rst=1',$data_exp, 'data'),
				'3'=> array('Import the base', 'import.php?selectedTab=data&rst=1',$data_imp, 'data')
				),
//	'1'=> array('0'=>'Historique',
//				'1'=> array('Database file', 'fichierdb.php?selectedTab=data&rst=1',$data_exp, 'data'),
//				'2'=> array('Excel file exported', 'fichierexcel.php?selectedTab=data&rst=1',$data_exp, 'data')
//				)
	);
	}
	if($ilang=='3' && $ilang!='') { 

	$TOPMENUS =array( //Libellé , Lien, Droit, Tab
	'0'=> array('0'=>'Base de dados',
				'1'=> array('Esvaziar a base de dados', 'vider.php?selectedTab=data&rst=1',$data_vid, 'data'),
				'2'=> array('Guardar a base de dados', 'sauvegarde.php?selectedTab=data&rst=1',$data_exp, 'data'),
				'3'=> array('Importar da basse', 'import.php?selectedTab=data&rst=1',$data_imp, 'data')
				),
//	'1'=> array('0'=>'Historique',
//				'1'=> array('Fichier base de données', 'fichierdb.php?selectedTab=data&rst=1',$data_exp, 'data'),
//				'2'=> array('Ficheiro Excel exportar', 'fichierexcel.php?selectedTab=data&rst=1',$data_exp, 'data')
//				)
	);
	}

	$menuHTML1= '';
	foreach ($TOPMENUS as $key => $val){
		$menuHTML1 .= '<tr><td><table width="185" border="0" align="left" cellpadding="1" cellspacing="1">';
		foreach ($val as $key1 => $val1){ //B 1
			if(!is_array($val1)){
				$menuHTML1 .= '
				<tr>
            		<td width="175" height="20" colspan="2" class="leftHeader"><nobr>'.stripslashes($val[0]).'</nobr></td>
          		</tr>
          		';
			}
			if(is_array($val1) && $val1[2]==1){ //B 2
				$menuHTML1 .= '
				<tr>
            		<td class="leftLink" width="2">&nbsp;</td>
					<td class="leftLink" height="20" width="175" ><a href="'.stripslashes($val1[1]).'" />'.stripslashes($val1[0]).'</a></td>
				</tr>
				';
			}//B 2
		}
		$menuHTML1 .= '
			<tr>
            	<td class="leftLink" width="2"></td>
				<td class="leftLink" height="10" width="175" ></td>
			</tr>
			</table> </td></tr>';
	}
	return $menuHTML1;
}

//AIDE
function aideMenus($tab ='', $droit=''){

	$TOPMENUS =array( //Libellé , Lien, Droit, Tab

	'0'=>  array('0'=>'Le logiciel',
//				'1'=> array('Introdustion', 'aide.php?selectedTab=aid','0', 'aid'),
//				'2'=> array('Prérequis', 'prerequis.php?selectedTab=aid','0', 'aid'),
//				'3'=> array('Installation', 'installation.php?selectedTab=aid','0', 'aid'),
//				'4'=> array('Authentification', 'authentification.php?selectedTab=aid','0', 'aid'),
//				'5'=> array('Généralités', 'generalite.php?selectedTab=aid','0', 'aid'),
//				'6'=> array('Fenêtre ajout', 'fenetre.php?selectedTab=aid','0', 'aid'),
//				'7'=> array('Fenêtre édition', 'fedition.php?selectedTab=aid','0', 'aid'),
//				'8'=> array('Fenêtre validation', 'fvalidation.php?selectedTab=aid','0', 'aid'),
//				'9'=> array('Fenêtre recherche', 'frecherche.php?selectedTab=aid','0', 'aid'),
//				),
//	'1'=>  array('0'=>'Paramétrage',
//				'1'=> array('Paramétrage', 'fparametre.php?selectedTab=aid','0', 'aid'),
//				'2'=> array('Utilisateurs & groupes', 'futilisateur.php?selectedTab=aid','0', 'aid'),
//				'3'=> array('Installation', 'installation.php?selectedTab=aid','0', 'aid'),
//			//	'4'=> array('Etats & imprimables', 'printtransfert.php?selectedTab=aide','0', 'aide')
				),
//	'2'=>  array('0'=>'Commandes/livraisons',
//				'1'=> array('Introdustion', 'presentation.php?selectedTab=aide','0', 'aide'),
//				'2'=> array('Prérequis', 'addtransfert.php?selectedTab=aide','0', 'aide'),
//				'3'=> array('Installation', 'searchtransfert.php?selectedTab=aide','0', 'aide'),
//				'4'=> array('Etats & imprimables', 'printtransfert.php?selectedTab=aide','0', 'aide')
//				),
//	'3'=>  array('0'=>'Programmetion',
//				'1'=> array('Introdustion', 'presentation.php?selectedTab=aide','0', 'aide'),
//				'2'=> array('Prérequis', 'addtransfert.php?selectedTab=aide','0', 'aide'),
//				'3'=> array('Installation', 'searchtransfert.php?selectedTab=aide','0', 'aide'),
//				'4'=> array('Etats & imprimables', 'printtransfert.php?selectedTab=aide','0', 'aide')
//				),
//
//	'4'=>  array('0'=>'Mouvement de stocks',
//				'1'=> array('Introdustion', 'presentation.php?selectedTab=aide','0', 'aide'),
//				'2'=> array('Prérequis', 'addtransfert.php?selectedTab=aide','0', 'aide'),
//				'3'=> array('Installation', 'searchtransfert.php?selectedTab=aide','0', 'aide'),
//				'4'=> array('Etats & imprimables', 'printtransfert.php?selectedTab=aide','0', 'aide')
//				),
//
//	'5'=>  array('0'=>'Inventaires',
//				'1'=> array('Introdustion', 'presentation.php?selectedTab=aide','0', 'aide'),
//				'2'=> array('Prérequis', 'addtransfert.php?selectedTab=aide','0', 'aide'),
//				'3'=> array('Installation', 'searchtransfert.php?selectedTab=aide','0', 'aide'),
//				'4'=> array('Etats & imprimables', 'printtransfert.php?selectedTab=aide','0', 'aide')
//				),
//
//	'6'=>  array('0'=>'Etats & imprimables',
//				'1'=> array('Introdustion', 'presentation.php?selectedTab=aide','0', 'aide'),
//				'2'=> array('Prérequis', 'addtransfert.php?selectedTab=aide','0', 'aide'),
//				'3'=> array('Installation', 'searchtransfert.php?selectedTab=aide','0', 'aide'),
//				'4'=> array('Etats & imprimables', 'printtransfert.php?selectedTab=aide','0', 'aide')
//				),

	);

	$menuHTML1= '';
	foreach ($TOPMENUS as $key => $val){
		$menuHTML1 .= '<tr><td><table width="185" border="0" align="left" cellpadding="1" cellspacing="1">';
		foreach ($val as $key1 => $val1){ //B 1
			if(!is_array($val1)){
				$menuHTML1 .= '
				<tr>
            		<td width="175" height="20" colspan="2" class="leftHeader"><nobr>'.($val[0]).'</nobr></td>
          		</tr>
          		';
			}
			if(is_array($val1)){ //B 2
				$menuHTML1 .= '
				<tr>
            		<td class="leftLink" width="2">&nbsp;</td>
					<td class="leftLink" height="20" width="175" ><a href="'.($val1[1]).'" />'.($val1[0]).'</a></td>
				</tr>
				';
			}//B 2
		}
		$menuHTML1 .= '
			<tr>
            	<td class="leftLink" width="2"></td>
				<td class="leftLink" height="4" width="175" ></td>
			</tr>
			</table> </td></tr>';
	}
	return $menuHTML1;
}

define('INFO', '<span id="rightInfo">'.$_SESSION['GL_USER']['NAME'].'</span>');

define('RIGHT_MENU','<a href="aide.php?selectedTab=aid"><img src="../images/help0000.gif" border="0" hspace="3" align="middle" />'.$vaide.'</a> <span class=white> </span>
          <a href="about.php?selectedTab=aid"><img src="../images/about000.gif" border="0" hspace="3" align="middle" />'.$vprop.'</a>&nbsp;&nbsp;');

define('LICENCE','
          <a href="http://www.econsulting.bf/forums/index.php?id=STOCKS" target="_blank"><img src="../images/forums00.gif" border="0" hspace="3" align="middle" />Forums</a> <span class=white> </span>
          <a href="" onClick=""><img src="../images/talkback.gif" border="0" hspace="3" align="middle" />R&eacute;agir</a><span class=white> </span>            <span class=white> </span>
          <a href="" onClick="javascript:window.open(\'aide/index.html\',\'\',\'\')"><img src="../images/help0000.gif" border="0" hspace="3" align="middle" />'.$vaide.'</a> <span class=white> </span>
          <a href="" onClick="JavaScript:window.open(\'/about.php?selectedTab=aide\',\'A propos\',\'left=500,top=100,width=350,height=300\')" ><img src="../images/about000.gif" border="0" hspace="3" align="middle" />'.$vprop.'</a>&nbsp;&nbsp;');

define('LOGOUT','<span class="wtext"><a href="dbuser.php?do=logout" title="'.$_SESSION['GL_USER']['NAME'].'">'.$_SESSION['GL_USER']['LOGIN'].' [D&eacute;connexion]</a></span>');

define('EXBG_MAG', '<span class="Style2" ><font color="#066">'.$_SESSION['GL_USER']['EX_LIBELLE'].' ['.$_SESSION['GL_USER']['DEBUT_EXERCICE'].' - '.$_SESSION['GL_USER']['FIN_EXERCICE'].']
		&gt;&gt;&nbsp;'.(isset($_SESSION['GL_USER']['STATUT_EXERCICE']) && $_SESSION['GL_USER']['STATUT_EXERCICE']==1 ? '[Cl&ocirc;tur&eacute;]' : '').'&nbsp;
		&nbsp;'.stripslashes(getField('IDPROVINCE',$_SESSION['GL_USER']['PROVINCE'],'PROVINCE','province')).'&nbsp;&gt;&gt;&nbsp;
		&nbsp;'.stripslashes(getField('CODE_MAGASIN',$_SESSION['GL_USER']['MAGASIN'],'SER_NOM','magasin')).'&nbsp;'
		);
//<span title="'.stripslashes(getField('CODE_MAGASIN',$_SESSION['GL_USER']['MAGASIN'],'SER_NOM','magasin')).'">'.$_SESSION['GL_USER']['MAGASIN'].'</span></font> </span>'
?>

