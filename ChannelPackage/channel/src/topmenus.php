<?php
/**
 *
 * @version $Id$
 * @copyright 2011
 * KG
 */

/*
$TOPMENUS =array( //Libellé , Lien
	'HOME'=> array('Accueil','home.php?selectedTab=home',0),
	'CDE'=> array('Commandes/Livraisons','order.php?selectedTab=cde',0),
	'PRG'=> array('Programmation','programme.php?selectedTab=prg'),
	'MVT'=> array('Mouvement sur stock','mouvement.php?selectedTab=mvt',0),
	'INV'=> array('Inventaire','inventaire.php?selectedTab=invt',0),
	'REV'=> array('Reversement','reversement.php?selectedTab=revmt',0),
	'ETAT'=> array('Etats & imprimables','etat.php?selectedTab=etat',0),
	'PAR'=> array('Paramétrage','parametrage.php?selectedTab=etatedab=revmt',0)
);
*/


function topMenus($tab ='', $droit=''){
	$TOPMENUS =array( //Libellé , Lien, Droit, Tab
	'0'=> array('Accueil','home.php?selectedTab=home','1', 'home'),
	'1'=> array('Commandes/Livraisons','orderdelivery.php?selectedTab=cde','0', 'order'),
	'2'=> array('Programmation','progreversement.php?selectedTab=prg','0', 'prog'),
	'3'=> array('Mouvement sur stock','mouvement.php?selectedTab=mvt','0', 'mouvmt'),
	'4'=> array('Inventaire','inventaire.php?selectedTab=invt','0', 'invt'),
	'5'=> array('Etats & imprimables','etat.php?selectedTab=etat','0', 'etat'),
	'6'=> array('Paramétrage','parametrage.php?selectedTab=par','0', 'param'),
	'7'=> array('Aide','aide.php?selectedTab=aide','1', 'aide')
	);

	$t_droit = preg_split('/ /',$droit);
	foreach ($t_droit as $key => $val){$TOPMENUS[$key][2] =$val;} //Fill Right

	$menuHTML1= '<table cellpadding=0 cellspacing=0 border=0 id="tab">
              		<tr>';

	//Display menu
	foreach ($TOPMENUS as $key => $val){
		if($val[2]==1) {
			//Allow to see it
   			if($tab == $TOPMENUS[$key][3]){
    			$menuHTML1 .= '<td nowrap class="menuOnBg"><a id="homelink" href="'.$TOPMENUS[$key][1].'" class="menuOff">'.($TOPMENUS[$key][0]).'</a></td>';
			}
			else {
				$menuHTML1 .= '<td nowrap class="menuOffBg"><a id="homelink" href="'.$TOPMENUS[$key][1].'" class="menuOff">'.($TOPMENUS[$key][0]).'</a></td>';
			}
		}
	}
	$menuHTML1 .= '</tr></table>';

	return $menuHTML1;
}



define('RIGHT_MENU','

          <a href="aide.php?selectedTab=aide"><img src="../images/help0000.gif" border="0" hspace="3" align="middle" />Aide</a> <span class=white> </span>
          <a href="about.php?selectedTab=aide"><img src="../images/about000.gif" border="0" hspace="3" align="middle" />A propos</a>&nbsp;&nbsp;');

define('LICENCE','
          <a href="http://www.econsulting.bf/forums/index.php?id=STOCKS" target="_blank"><img src="../images/forums00.gif" border="0" hspace="3" align="middle" />Forums</a> <span class=white> </span>
          <a href="" onClick=""><img src="../images/talkback.gif" border="0" hspace="3" align="middle" />R&eacute;agir</a><span class=white> </span>            <span class=white> </span>
          <a href="" onClick="javascript:window.open(\'aide/index.html\',\'\',\'\')"><img src="../images/help0000.gif" border="0" hspace="3" align="middle" />Aide</a> <span class=white> </span>
          <a href="" onClick="JavaScript:window.open(\'/about.php?selectedTab=aide\',\'A propos\',\'left=500,top=100,width=350,height=300\')" ><img src="../images/about000.gif" border="0" hspace="3" align="middle" />A propos</a>&nbsp;&nbsp;');


define('DECONNEXION', 'dbuser.php?do=logout');
?>