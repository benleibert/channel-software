<?php
//Session
session_start();
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:dbuser.php?do=logout");

if($_SESSION['GL_USER']['DROIT']['int_int']['AJOUT']!=1) header("location:accessinterdit.php?selectedTab=home");

require_once('../lib/phpfuncLib.php');		//All commun functions
require_once('menus.php');
require_once('funcinventaire.php');

//Top Menu
$selectedTab = $_GET['selectedTab'];
$menu = topMenus($selectedTab,$_SESSION['GL_USER']['DROIT']);

//Left Menu
$leftMenu = inventaireMenus($selectedTab , $_SESSION['GL_USER']['DROIT']);

//DOIT MAJ
$droitMAJ = $_SESSION['GL_USER']['DROIT']['int_int'];
//Data
(isset($_SESSION['DATA_INV']['exercice']) 		? $exercice 	= $_SESSION['DATA_INV']['exercice']: $exercice ='');
(isset($_SESSION['DATA_INV']['dateinventaire']) 	? $dateinventaire = $_SESSION['DATA_INV']['dateinventaire']: $dateinventaire ='');
(isset($_SESSION['DATA_INV']['inventaire']) 	? $inventaire = $_SESSION['DATA_INV']['inventaire']: $inventaire ='');
(isset($_SESSION['DATA_INV']['refinventaire']) 	? $refinventaire = $_SESSION['DATA_INV']['refinventaire']: $refinventaire ='');
(isset($_SESSION['DATA_INV']['categorie']) 		? $categorie 	= $_SESSION['DATA_INV']['categorie']: $categorie ='');
(isset($_SESSION['DATA_INV']['nbreLigne']) 		? $nbreLigne 		= $_SESSION['DATA_INV']['nbreLigne']: $nbreLigne ='');

(isset($_SESSION['DATA_INV']['ligne']) ? $data= $_SESSION['DATA_INV']['ligne'] : $data=array());

//Ligne
$ligne = ligneaddInventaire($nbreLigne, $data);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<META HTTP-EQUIV="refresh" CONTENT="" >
<title><?php echo TITLE; ?></title>
<LINK REL="SHORTCUT ICON" HREF="../images/favicon0.ico">
<link href="../css/neutralcss.css" rel="stylesheet" type="text/css">
<link href="../lib/JQuerySpinBtn.css" rel="stylesheet" type="text/css">
<link href="../lib/jquery.alerts.css" rel="stylesheet" type="text/css">
<!-- Begin of JS code  -->
<script type="text/javascript" src="../lib/jquery.js"></script>
<script type="text/javascript" src="../lib/jslib.js"></script>
<script type="text/javascript" src="../lib/jsfuncLib.js"></script>


<!-- Pickdate -->
<link type="text/css" href="../lib/themes/base/ui.all.css" rel="stylesheet" />
<link type="text/css" href="../lib/demos.css" rel="stylesheet" />
<script type="text/javascript" src="../lib/ui/ui.core.js"></script>
<script type="text/javascript" src="../lib/ui/ui.datepicker.js"></script>

<!-- Begin of JS code  -->
<script type="text/javascript" src="../lib/JQuerySpinBtn.js"></script>

<script type="text/javascript">	$(function() {
		$('#datepicker1').datepicker({
			showButtonPanel: true,
			dateFormat: "dd/mm/yy" });
	});

</script>

<script type="text/javascript">
	$(function() {
		$('#datepicker2').datepicker({
			showButtonPanel: true
		});
	});
</script>

<script type="text/javascript">
	$(function() {
		$('#datepicker3').datepicker({
			showButtonPanel: true
		});
	});
</script>
<script type="text/javascript" language="javascript">

		// Apply the SpinButton code to the appropriate INPUT elements:
		$(function(){

			$("INPUT.spin-button").SpinButton({min:1});

		});

</script>
<script type="text/javascript">

function validateForm(){
	document.formadd.submit();

	//window.location.reload() recharger une page
}

//Add ligne to
function msgAddLign(){
	document.formadd.myaction.value ="addline";
	document.formadd.submit();

	//window.location.reload() recharger une page
}

function validateForm(){
	var dateinventaire 	= trimAll(document.formadd.dateinventaire.value);
	var inventaire = trimAll(document.formadd.inventaire.value);
	var msg = '';

	if( dateinventaire == "") {
      	msg += '- Veuillez saisir le date de l\'inventaire.\n';
    }
	else {
		if (!checkDate(document.formadd.dateinventaire)) {
			msg += '- Date incorrect. Revoir le(Format: jj/mm/aaaa).\n';
		}
		else {
			if (!CompareDate(dateinventaire, document.formadd.debutexercice.value, '>=') || !CompareDate(dateinventaire, document.formadd.finexercice.value, '<=')) {msg += '- Date commande hors exercice budegétaire ['+document.formadd.debutexercice.value+' - '+document.formadd.finexercice.value+'].\n';}
			
		}
	}
	if( inventaire == "") {
      	msg += '- Veuillez saisir le libellé de l\'inventaire.\n';
    }
	if(msg !=''){
		alert(msg);
	}
	else {
		document.formadd.submit();
    }
}


function go(){
	var xhr = getXhr();
	// On défini ce qu'on va faire quand on aura la réponse
	xhr.onreadystatechange = function(){
		// On ne fait quelque chose que si on a tout reéu et que le serveur est ok
		if(xhr.readyState == 4 && xhr.status == 200){
			retour = xhr.responseText;
			// On se sert de innerHTML pour rajouter les options a la liste
			document.getElementById('msg').innerHTML = retour;
			if(retour !='') document.getElementById('refinventaire').focus();
		}
	}

	// Ici on va voir comment faire du post
	xhr.open("POST","dbinveentaire.php?do=check",true);
	// ne pas oublier éa pour le post
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	// ne pas oublier de poster les arguments
	// ici, l'id de l'auteur
	id = document.getElementById('refinventaire').value;
	xhr.send("refinventaire="+id);
}


</script>
<script>
window.focus();
</script>
<script>
//Duplicated in MainLayout.jsp
function writeTableStartTagBasedOnResolution()
{
	var winW = 1024;
	if (parseInt(navigator.appVersion)>3)
	{
		if (navigator.appName=="Netscape")
		{
			winW = window.innerWidth;
		}
		if (navigator.appName.indexOf("Microsoft")!=-1)
		{
			winW = document.body.offsetWidth;
		}
	}
	if(winW < '1024')
	{
		document.write("<table width=1024  border=0 cellspacing=0 cellpadding=0>");
	}
	else
	{
		document.write("<table id=maintable width=100%  border=0 cellspacing=0 cellpadding=0>");
	}
}
</script>
<style type="text/css">
<!--
.Style2 {
	font-size: x-large;
	font-family: "Times New Roman", Times, serif;
}
-->
</style>
</head>
<body class="bodyBg">
<script> writeTableStartTagBasedOnResolution(); </script>
  <tr>
    <td class="tabsBg">
	<script language="JavaScript" type="text/JavaScript">
	<!--
	function clearText(){
		document.searchForm.searchTerm.value="";
	}
	function validateValues(){
		if(this.document.searchForm.searchTerm.value == ''){
			alert("Please enter the device name to search")
			 return false;
		}
		return true;
	}

	//-->
	</script>

<!-- End of JS code  -->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" rowspan="2"><img src="../images/unfpa.png" alt="" width="95" height="45" hspace=2 vspace=0 /></td>
    <td height="24" colspan="2" align="right" valign="top"><span class="wtext"><?php echo RIGHT_MENU; ?></td>
  </tr>
  <tr>
    <td height="20" valign="top"><?php echo $menu['Top']; ?></td>
    <td align="right"><?php echo LOGOUT; ?>&nbsp;</td>
  </tr>
</table>
    </td>
</tr>
    <tr class="searchBg">
      <td height="21" align="center">

	 <table border="0"cellspacing="0" cellpadding="0">
          <tr>

            <td align="left" class="leftHeader">
            <?php echo EXBG_MAG; ?></td>
            <td align="right">&nbsp;
            	</td>
            <td></td>

	  </tr>
	</table>

	 </td>
    </tr>
    <tr class=bodyBg>
      <td height="8"></td>
    </tr>
  </table>
</td>
  </tr>
</table>
<script> writeTableStartTagBasedOnResolution(); </script>
  		 <tr>
		 <td width="200" valign="top">
         </td>
		 <td width="10"></td>
    <td width="*%" valign="top"><script>
window.focus();
</script>

  </td>
  </tr>
</table>
<table width="100%" height="80%"  border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="80%" align="left" valign="top"><table width="200" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="5" height="48">&nbsp;</td>
        <td width="180" align="left" valign="top"><table width="200" border="0" cellpadding="1" cellspacing="0">
          <tr>
            <td width="180"><?php echo $leftMenu; ?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
    <td width="85%" height="80%" align="left" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td><table width="100%"  border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td><table width="100%" border="0" align="left" cellpadding="1" cellspacing="1">
              <tr>
                <td height="20" bgcolor="#FFCC66" class="leftHeader"><?php echo getlang(298); ?> -><?php echo getlang(73); ?></td>
              </tr>
              <tr>
                <td colspan="2" align="left" valign="top"><form action="dbinventaire.php?do=add" method="post" name="formadd" id="formadd">
                  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
                    <tr class="header2Bg">
                      <td align="left" valign="top" class="boldText" >&nbsp;Faire un inventaire (Etape n&deg;2)</td>
                    </tr>
                    <tr>
                      <td class="text" align="center"><table width="600" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                        <tbody>
                          <tr align="left" valign="top">
                            <td width="200" align="right" valign="middle" class="text"><?php echo getlang(62); ?> :</td>
                            <td width="358" align="left" class="text"><select name="xexercice" id="xexercice" class="formStyle" readonly="readonly"  disabled="disabled">
                              <option value="0"></option>
                              <?php echo selectExercice($_SESSION['GL_USER']['EXERCICE']); ?>
                            </select>
                              <input name="exercice" type="hidden" id="exercice" value="<?php echo $_SESSION['GL_USER']['EXERCICE']; ?>" />
                              <span class="mandatory" >*</span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(291); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="dateinventaire" type="text" class="formStyle" id="datepicker1" value="<?php echo $dateinventaire; ?>" />
                              <span class="mandatory">*</span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(286); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="refinventaire" type="text" class="formStyle" id="refinventaire" value="<?php echo $refinventaire; ?>" onblur="go();" />
                              <span class="mandatory" id="msg"></span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(284); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="inventaire" type="text" class="formStyle" id="inventaire" value="<?php echo $inventaire; ?>" />
                              <span class="mandatory">*</span></td>
                          </tr>
                          <!--
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(20); ?> :</td>
                            <td align="left" class="text"><select name="categorie" id="categorie" class="formStyle"  onchange="fillMe();">
                              <option value="0"></option>
                              <option value="TOUS">Toutes les cat&eacute;rories</option>
                              <?php echo selectCategorie($categorie); ?>
                            </select></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="top" class="text"><?php echo getlang(118); ?> :</td>
                            <td align="left" class="text"><div id="listearticle">
                              <select name="produit[]" class="formStyle" id="produit[]" multiple="multiple">
                              </select>
                            </div>
                              <br />
                              <i><?php echo getlang(227); ?></i></td>
                          </tr>
                          -->
                          <tr>
                            <td><input name="exercicecourant2" type="hidden" id="exercicecourant2" value="<?php echo $_SESSION['GL_USER']['EXERCICE']; ?>" />
                              <input name="statusexercice2" type="hidden" id="statusexercice2" value="<?php echo $_SESSION['GL_USER']['STATUT_EXERCICE']; ?>" />
                              <input name='myaction2' type='hidden' id="myaction2" value="" />
                              <input name='debutexercice' type='hidden' id="debutexercice" value="<?php echo $_SESSION['GL_USER']['DEBUT_EXERCICE']; ?>" />
                              <input name='finexercice' type='hidden' id="finexercice"  value="<?php echo $_SESSION['GL_USER']['FIN_EXERCICE']; ?>" /></td>
                            <td><span class="mandatory">*</span> <?php echo getlang(215); ?></td>
                          </tr>
                        </tbody>
                      </table></td>
                    </tr>
                  </table><br />
<table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
                        <tr class="header2Bg">
                          <td align="left" valign="top" class="boldText" ><?php echo getlang(287); ?></td>
                        </tr>
                        <tr>
                          <td class="text" align="center"><table width="500" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                              <tbody>
                                <tr align="left" valign="top" nowrap>
                                  <td align=right valign="middle" class="text">&nbsp;</td>
                                 <td width="85" align=right valign="middle" nowrap class="text"><div align="center"><?php echo getlang(257); ?> </div></td>
                                  <td width="275" align=right valign="middle" class="text"><div align="left"><?php echo getlang(199); ?></div></td>
                                  <td width="139" align=center valign="middle" class="text"><div align="center">Réf. Lot</div></td>
                                  <td width="139" align=center valign="middle" class="text"><div align="center"><?php echo getlang(226); ?></div></td>
                                  <td width="139" align=center valign="middle" class="text"><div align="center"><?php echo getlang(288); ?></div></td>

                                  <td width="139" align=center valign="middle" class="text"><div align="center"><?php echo getlang(289); ?></div></td>
                                  <td width="66" align=center valign="middle" class="text"><div align="center"><?php echo getlang(221); ?></div></td>
                                  <td width="66" align=center valign="middle" class="text"><div align="center">Total</div></td>
                                  <td width="66" align=center valign="middle" class="text"><div align="center"><?php echo getlang(204); ?></div></td>
                                  </tr>
                                <tr align="left" valign="middle">
                                  <td colspan="11" class="text" nowrap="nowrap"><?php echo $ligne ; ?>
								  </td>
                                  </tr>
                              	<tr>
                                <td colspan="11"><input name='myaction' type='hidden' id="myaction">
								<input name="exercicecourant" type="hidden" id="exercicecourant" value="<?php echo $_SESSION['GL_USER']['EXERCICE']; ?>">
						<input name="statusexercice" type="hidden" id="statusexercice" value="<?php echo $_SESSION['GL_USER']['STATUT_EXERCICE']; ?>">

								<input name="nbreLigne" type="hidden" id="nbreLigne" value="<?php echo $nbreLigne; ?>" /></td>
                              </tr>
							  <tr align="left" valign="top">
							    <td width="8">&nbsp;</td>
							    <td colspan="10">
							      <!-- <input name="Precedent" type="button" class="button" id="Precedent"  value='&lt;&lt; Pr&eacute;c&eacute;dent' onClick="javascript:window.location.href='addBonentree.php?selectedTab=inputs';"> -->
							      <input name="Enregistrer" type="button" class="button" id="Enregistrer"  value='<?php echo getlang(189); ?>'  onClick="validateForm();"></td>
  								</tr>
                          </table></td>
                        </tr>
                      </table>
                </form></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table><br>


</td>
  </tr>
  <tr>
    <td height="10%">&nbsp;</td>
    <td height="10%">&nbsp;</td>
  </tr>
</table>
</body>
</html>
