<?php
//Session
session_start();
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:dbuser.php?do=logout");

if($_SESSION['GL_USER']['DROIT']['bde_liv']['VALID']!=1) header("location:accessinterdit.php?selectedTab=home");

require_once('../lib/phpfuncLib.php');		//All commun functions
require_once('menus.php');
require_once('funclivraison.php');

//Top Menu
$selectedTab = $_GET['selectedTab'];
$menu = topMenus($selectedTab,$_SESSION['GL_USER']['DROIT']);

//Left Menu
$leftMenu = commandesMenus($selectedTab , $_SESSION['GL_USER']['DROIT']);
//Rsest
if(isset($_GET['rst']) && $_GET['rst']==1) {$_SESSION['WHERE']='';}

//DOIT MAJ
$droitMAJ = $_SESSION['GL_USER']['DROIT']['bde_liv'];


//Data
(isset($_SESSION['DATA_LVR']['xid']) 		? $xid 	= $_SESSION['DATA_LVR']['xid']: $xid ='');
(isset($_SESSION['DATA_LVR']['exercice']) 		? $exercice 	= $_SESSION['DATA_LVR']['exercice']: $exercice ='');
(isset($_SESSION['DATA_LVR']['datelivraison']) 	? $datelivraison = $_SESSION['DATA_LVR']['datelivraison']: $datelivraison ='');
(isset($_SESSION['DATA_LVR']['commande']) 	? $commande = $_SESSION['DATA_LVR']['commande']: $commande ='');
(isset($_SESSION['DATA_LVR']['libcommande']) 	? $libcommande = $_SESSION['DATA_LVR']['libcommande']: $libcommande ='');
(isset($_SESSION['DATA_LVR']['fournisseur']) 	? $fournisseur = $_SESSION['DATA_LVR']['fournisseur']: $fournisseur ='');
(isset($_SESSION['DATA_LVR']['idfournisseur']) 	? $idfournisseur = $_SESSION['DATA_LVR']['idfournisseur']: $idfournisseur ='');
(isset($_SESSION['DATA_LVR']['reflivraison']) 	? $reflivraison = $_SESSION['DATA_LVR']['reflivraison']: $reflivraison ='');
(isset($_SESSION['DATA_LVR']['libelle']) 	? $libelle = $_SESSION['DATA_LVR']['libelle']: $libelle ='');
(isset($_SESSION['DATA_LVR']['nbreLigne']) 		? $nbreLigne 	= $_SESSION['DATA_LVR']['nbreLigne']: $nbreLigne ='');
(isset($_SESSION['DATA_LVR']['statut']) 		? $statut 	= $_SESSION['DATA_LVR']['statut']: $statut ='');
(isset($_SESSION['DATA_LVR']['ligne']) ? $data= $_SESSION['DATA_LVR']['ligne'] : $data=array());
($statut==1 ? $checked = 'checked="checked"' : $checked ='');

//Ligne
$ligne= lignedetailLivraison1($nbreLigne, $data);
//$ligne = $t[1];
//$verif = $t[0];

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
	$(document).ready(function(){
	  $('.placeholder').mask("00/00/0000", {placeholder: "__/__/____"});
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
function checkQte(ch1, ch2){
	if(trimAll(document.getElementById(ch1).value)!='' && document.getElementById(ch2).value !='' && parseInt(document.getElementById(ch2).value)>parseInt(document.getElementById(ch1).value)){
		alert('La qunatité reçue est supérieure à la quantité commandée\n'+document.getElementById(ch2).value+'>'+document.getElementById(ch1).value);
		document.getElementById(ch2).value='';
		document.getElementById(ch2).focus();
	}
	return false;
}
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
	var datelivraison 	= trimAll(document.formadd.datelivraison.value);
	var msg = '';

	if( datelivraison == "") {
      	msg += '- Veuillez saisir le date de la livraison.\n';
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
		// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
		if(xhr.readyState == 4 && xhr.status == 200){
			retour = xhr.responseText;
			// On se sert de innerHTML pour rajouter les options a la liste
			document.getElementById('msg').innerHTML = retour;
			if(retour !='') document.getElementById('codeCategorie').focus();
		}
	}

	// Ici on va voir comment faire du post
	xhr.open("POST","phpfunccategories.php?test=CODECATEGORIE",true);
	// ne pas oublier ça pour le post
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	// ne pas oublier de poster les arguments
	// ici, l'id de l'auteur
	id = document.getElementById('codeCategorie').value;
	xhr.send("codeCategorie="+id);
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
                <td width="43" bgcolor="#FFCC66" class="leftHeader"><?php echo getlang(62); ?> -><?php echo getlang(244); ?></td>
              </tr>
              <tr>
                <td colspan="2" align="left" valign="top"><form action="dblivraison.php?do=validate" method="post" name="formadd" id="formadd">
                  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
                    <tr class="header2Bg">
                      <td align="left" valign="top" class="boldText" ><?php echo getlang(180); ?> r une livraison </td>
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
                              <span class="mandatory" id="msg">*
                                <input name="exercice" type="hidden" id="exercice" value="<?php echo $_SESSION['GL_USER']['EXERCICE']; ?>" />
                              </span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(45); ?> :</td>
                            <td align="left" class="text"><input name="datelivraison" type="text" class="formStyle" id="datepicker1" value="<?php echo $datelivraison; ?>" />
                              <span class="mandatory">*</span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(140); ?> :</td>
                            <td align="left" class="text"><input name="refcommande" type="text" class="formStyle" id="refcommande" readonly="readonly" value="<?php echo  $commande; ?>"/>
                              <input name="commande" type="hidden" class="formStyle"  id="commande" value="<?php echo  $commande; ?>"/>
                              &nbsp;</td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(82); ?> :&nbsp;</td>
                            <td align="left" class="text"><input name="libcommande" type="text" class="formStyle" id="libcommande" readonly="readonly" value="<?php echo  $libcommande; ?>"/>
                              &nbsp;</td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(66); ?> :</td>
                            <td align="left" class="text"><input name="fournisseur" type="text" class="formStyle" id="fournisseur" readonly="readonly" value="<?php echo  $fournisseur; ?>"/>
                              <input name="idfournisseur" type="hidden" class="formStyle"  id="idfournisseur" value="<?php echo  $idfournisseur; ?>"/></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(141); ?> :</td>
                            <td align="left" class="text"><input name="reflivraison"  id="reflivraison" type="text" class="formStyle" onblur="go();" value="<?php echo  $reflivraison; ?>" />
                              <span class="mandatory" id="msg2"></span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(83); ?> :</td>
                            <td align="left" class="text"><input name="libelle"  id="libelle" type="text" class="formStyle" value="<?php echo  $libelle; ?>" />
                              <span class="mandatory">*</span></td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td><span class="mandatory">*</span> <?php echo getlang(215); ?></td>
                          </tr>
                        </tbody>
                      </table></td>
                    </tr>
                  </table><br />
<table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
                        <tr class="header2Bg">
                          <td align="left" valign="top" class="boldText" ><?php echo getlang(214); ?> <?php echo getlang(86); ?></td>
                        </tr>
                        <tr>
                          <td class="text" align="center"><table width="623" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                              <tbody>
                                <tr align="left" valign="top" nowrap>
                                  <td align=right valign="middle" class="text">&nbsp;</td>
                                 <td width="64" align=right valign="middle" nowrap class="text"><div align="center"><?php echo getlang(257); ?> </div></td>
                                  <td width="264" align=right valign="middle" class="text"><div align="left"><?php echo getlang(199); ?></div></td>

                                  <td width="48" align=center valign="middle" class="text"><div align="center"><?php echo getlang(200); ?> cd&eacute;e</div></td>
								  <td width="48" align=center valign="middle" class="text"><div align="center"><?php echo getlang(200); ?> <?php echo getlang(249); ?></div></td>
								  <td width="48" align=center valign="middle" class="text"><div align="center"><?php echo getlang(204); ?></div></td>
								  <td width="48" align=center valign="middle" class="text"><div align="center"><?php echo getlang(221); ?></div></td>
								  <td width="48" align=center valign="middle" class="text"><div align="center">Réf. Lot</div></td>
                                  <td width="48" align=center valign="middle" class="text"><div align="center"><?php echo getlang(226); ?></div></td>
                                  </tr>
                                <tr align="left" valign="middle">
                                  <td colspan="10" class="text" nowrap="nowrap"><?php echo $ligne ; ?>
								  </td>
                                  </tr>
                              	<tr>
                                <td colspan="10"><input name="exercicecourant" type="hidden" id="exercicecourant" value="<?php echo $_SESSION['GL_USER']['EXERCICE']; ?>">
						<input name="statusexercice" type="hidden" id="statusexercice" value="<?php echo $_SESSION['GL_USER']['STATUT_EXERCICE']; ?>">

								<input name="xid" type='hidden' id="xid" value="<?php echo  $xid; ?>" />
                                <input name="statut" type='hidden' id="statut" value="1" /></td>
                              </tr>
							  <tr align="left" valign="top">
							    <td width="4">&nbsp;</td>
							    <td colspan="9">
							      <!-- <input name="Precedent" type="button" class="button" id="Precedent"  value='&lt;&lt; Pr&eacute;c&eacute;dent' onClick="javascript:window.location.href='addBonentree.php?selectedTab=inputs';"> -->
							      <input name="Enregistrer" type="button" class="button" id="Enregistrer"  value='<?php echo getlang(180); ?>'  onClick="validateForm();"></td>
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
