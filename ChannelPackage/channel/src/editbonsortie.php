<?php
//Session
session_start();
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:dbuser.php?do=logout");

if($_SESSION['GL_USER']['DROIT']['bds_bds']['MODIF']!=1) header("location:accessinterdit.php?selectedTab=home");

require_once('../lib/phpfuncLib.php');		//All commun functions
require_once('menus.php');
require_once('funcbonsortie.php');

//Top Menu
$selectedTab = $_GET['selectedTab'];
$menu = topMenus($selectedTab,$_SESSION['GL_USER']['DROIT']);

//Left Menu
$leftMenu = bonsortieMenus($selectedTab , $_SESSION['GL_USER']['DROIT']);

//DOIT MAJ
$droitMAJ = $_SESSION['GL_USER']['DROIT']['bds_bds'];

//Reset WHERE
if(isset($_GET['rst']) && $_GET['rst']==1 ) $_SESSION['WHERE']='';

//Data
(isset($_SESSION['DATA_BDS']['xid']) 		? $xid 	= $_SESSION['DATA_BDS']['xid']: $xid ='');
(isset($_SESSION['DATA_BDS']['exercice']) 		? $exercice 	= $_SESSION['DATA_BDS']['exercice']: $exercice ='');
(isset($_SESSION['DATA_BDS']['datebonsortie']) 	? $datebonsortie = $_SESSION['DATA_BDS']['datebonsortie']: $datebonsortie ='');
(isset($_SESSION['DATA_BDS']['libelle']) 	? $libelle 	= $_SESSION['DATA_BDS']['libelle']: $libelle ='');
(isset($_SESSION['DATA_BDS']['refbonsortie']) 	? $refbonsortie 	= $_SESSION['DATA_BDS']['refbonsortie']: $refbonsortie ='');
(isset($_SESSION['DATA_BDS']['idbeneficiaire']) 	? $idbeneficiaire 	= $_SESSION['DATA_BDS']['idbeneficiaire']: $idbeneficiaire ='');
(isset($_SESSION['DATA_BDS']['beneficiaire']) 	? $beneficiaire 	= $_SESSION['DATA_BDS']['beneficiaire']: $beneficiaire ='');
(isset($_SESSION['DATA_BDS']['nbreLigne']) 		? $nbreLigne 	= $_SESSION['DATA_BDS']['nbreLigne']: $nbreLigne ='');

(isset($_SESSION['DATA_BDS']['ligne']) ? $data= $_SESSION['DATA_BDS']['ligne'] : $data=array());
//Ligne
$t= ligneaddBonsortie($nbreLigne, $data);
$ligne = $t[1];
$verif = $t[0];
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

<script type="text/javascript">	
$(function() {
		$('#datepicker1').datepicker({
			showButtonPanel: true,
			dateFormat: "dd-mm-yy" });
	});

</script>
<script type="text/javascript">
	$(document).ready(function(){
	  $('.placeholder').mask("00/00/0000", {placeholder: "__/__/____"});
	});
</script>


<script type="text/javascript" language="javascript">

		// Apply the SpinButton code to the appropriate INPUT elements:
		$(function(){

			$("INPUT.spin-button").SpinButton({min:1});

		});

</script>
<script type="text/javascript">
<?php echo $verif; ?>
</script>
<script type="text/javascript">

function checkSommeQte(){
	var msg ='';
	for (var i=1; i<= parseInt(document.formadd.nbreLigne.value); i++) {
		for (var j=i+1; j<= parseInt(document.formadd.nbreLigne.value); j++) {
			var produiti = 'codeproduit'+i.toString(); 
			var refloti = 'reflot'+i.toString(); 
			var produitj = 'codeproduit'+j.toString(); 
			var reflotj = 'reflot'+j.toString();
			
			var qtei = 'qte'+i.toString(); 
			var qtej = 'qte'+j.toString();
			
			if(document.getElementById(produiti).value == document.getElementById(produitj).value && document.getElementById(refloti).value == document.getElementById(reflotj).value){
				var sommeQte = parseInt(document.getElementById(qtei).value)+ parseInt(document.getElementById(qtej).value);				
				var dispoi = 'dispo'+i.toString(); 			
				if(sommeQte>document.getElementById(dispoi).value){
					msg += ' * ligne '+i.toString()+' (Qté:'+document.getElementById(qtei).value+') et ligne '+j.toString()+' (Qté:'+document.getElementById(qtej).value+') sont identiques, la quantité totale est supérieure au stock disponible ('+document.getElementById(dispoi).value+')\n';	//alert('Somme: '+sommeQte.toString()+ ' Disp' +document.getElementById(dispoi).value);
				}
			}
		}
	}
	return msg;
}


function msgSupprLigne(id,num){
	var ret = false;
	if(document.formadd.statusexercice.value ==1) {alert('Cet exercice est clôturé. Aucune suppression n\'est possible');}
	else {

		ret = confirm('Voulez-vous supprimer la ligne '+num+'?');
		if(ret) {
			document.formadd.myaction.value="delline";
			document.formadd.rowSelection.value=id;
			document.formadd.submit();
		}
	}
}


//Add ligne to
function msgAddLign(){
	document.formadd.myaction.value ="addline";
	document.formadd.submit();

	//window.location.reload() recharger une page
}

function validateForm(){
	var datebonsortie	= trimAll(document.formadd.datebonsortie.value);
	var idbeneficiaire	= trimAll(document.formadd.idbeneficiaire.value);
	var libelle 	= trimAll(document.formadd.libelle.value);
	var msg = '';

	var checkSumQte = checkSommeQte();
	
	if( datebonsortie == "") {
      	msg += '- Veuillez saisir le date du bon de sortie.\n';
    }
	else {
		if (!checkDate(document.formadd.datebonsortie)) {
			msg += '- Date incorrect. Revoir le format: jj/mm/aaaa.\n';
		}
		else {
			if (!CompareDate(datebonsortie, document.formadd.debutexercice.value, '>=') || !CompareDate(datebonsortie, document.formadd.finexercice.value, '<=')) {msg += '- Date bon de sortie hors exercice budegétaire ['+document.formadd.debutexercice.value+' - '+document.formadd.finexercice.value+'].\n';}
				
		}
	}
	if(idbeneficiaire == "") {
      	msg += '- Veuillez sélectionner le fournisseur.\n';
    }
	if( libelle == "") {
      	msg += '- Veuillez saisir le libellé.\n';
	}
	if(tousVide()){
      	msg += '- Veuillez saisir les quantités des produits.\n';
    }
	if(checkSumQte!='') {
		msg += '- Veuillez revoir les lignes suivantes.\n'+checkSumQte;
	}
	if(msg !=''){
		alert(msg);
	}
	else {
		document.formadd.submit();
    }
}


function checkQte(qte,dispo){
	if(parseInt(document.getElementById(qte).value) > parseInt(document.getElementById(dispo).value)) {
		alert('Le stock disponible ('+ document.getElementById(dispo).value +' produit(s)) est insuffisant');
		document.getElementById(qte).value='';
		document.getElementById(qte).focus();
	}
	return true;
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
			if(retour !='') document.getElementById('refbonsortie').focus();
		}
	}

	// Ici on va voir comment faire du post
	xhr.open("POST","dbbonsortie.php?do=check",true);
	// ne pas oublier ça pour le post
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	// ne pas oublier de poster les arguments
	// ici, l'id de l'auteur
	id = document.getElementById('refbonsortie').value;
	xhr.send("code="+id);
}

function sendDate(){
	var xhr = getXhr();
	// On défini ce qu'on va faire quand on aura la réponse
	xhr.onreadystatechange = function(){
		// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
		if(xhr.readyState == 4 && xhr.status == 200){
			retour = xhr.responseText;
			// On se sert de innerHTML pour rajouter les options a la liste
			//document.getElementById('msg').innerHTML = retour;
			//if(retour !='') document.getElementById('refbonsortie').focus();
		}
	}

	// Ici on va voir comment faire du post
	xhr.open("POST","dbbonsortie.php?do=sendDate",true);
	// ne pas oublier ça pour le post
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	// ne pas oublier de poster les arguments
	// ici, l'id de l'auteur
	id = document.formadd.datebonsortie.value;
	xhr.send("code="+id);
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
    <td height="24" colspan="2" align="right" valign="top"><span class="wtext"><?php echo RIGHT_MENU; ?></span></td>
  </tr>
  <tr>
    <td height="20" valign="top"><?php echo $menu['Top']; ?></td>
    <td align="right"><?php echo LOGOUT; ?>&nbsp;</td>
  </tr>
</table></td>
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
                <td height="20" bgcolor="#FFCC66" class="leftHeader"><?php echo getlang(79); ?> -> <?php echo getlang(40); ?></td>
              </tr>
              <tr>
                <td colspan="2" align="left" valign="top"><form action="dbbonsortie.php?do=update" method="post" name="formadd" id="formadd">
                  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
                    <tr class="header2Bg">
                      <td align="left" valign="top" class="boldText" > <?php echo getlang(40); ?></td>
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
                              <span class="mandatory">*</span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(203); ?> :</td>
                            <td align="left" class="text"><input name="datebonsortie" type="text" class="formStyle" id="datepicker1" value="<?php echo $datebonsortie ?>" />
                              <span class="mandatory">*</span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(28); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="refbonsortie" type="text" class="formStyle" id="refbonsortie" value="<?php echo $refbonsortie ?>" onblur="go();"  />
                              <span class="mandatory" id="msg"> *</span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(357); ?> :</td>
                            <td align="left" class="text"><input name="libelle" type="text" class="formStyle" id="libelle" value="<?php echo $libelle ?>"  />
                              <span class="mandatory" id="msg3"> *</span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(16); ?> :</td>
                            <td align="left" class="text"><input name="beneficiaire" type="text" class="formStyle" id="beneficiaire" readonly="readonly" value="<?php echo $beneficiaire ?>" />
                              <input name="idbeneficiaire" type="hidden" class="formStyle" id="idbeneficiaire" value="<?php echo $idbeneficiaire ?>" />
                              <span class="mandatory">*</span>
                              <input name="openf" type="button" class="button"  title="Bénéficiaire" value="..." onclick="OpenWin('listebeneficiaire.php','Liste');" /></td>
                          </tr>
                          <tr>
                            <td align="right" valign="middle" class="text"><?php echo getlang(53); ?> :</td>
                            <td align="left" class="text"><input name="nbreLigne" type="text" class="spin-button formStyleFree" id="txtSpin" value="<?php echo $nbreLigne; ?>" size="5" /></td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td><span class="mandatory">*</span> <?php echo getlang(215); ?></td>
                          </tr>
                        </tbody>
                      </table></td>
                    </tr>
                  </table>
                  <br />
<table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
                        <tr class="header2Bg">
                          <td align="left" valign="top" class="boldText" ><?php echo getlang(214); ?> <?php echo getlang(40); ?></td>
                        </tr>
                        <tr>
                          <td class="text" align="center"><table width="623" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                              <tbody>
                                <tr align="left" valign="top" nowrap>
                                  <td align=right valign="middle" class="text">&nbsp;</td>
                                  <td width="4" align=right valign="middle" class="text">&nbsp;</td>
                                  <td width="4" align=right valign="middle" class="text">&nbsp;</td>
                                  <td width="64" align=right valign="middle" nowrap class="text"><div align="center"><?php echo getlang(257); ?> </div></td>
                                  <td width="264" align=right valign="middle" class="text"><div align="left"><?php echo getlang(199); ?></div></td>
                                  <!-- <td width="60" align="center" valign="middle" nowrap class="text"><div align="center">P. unitaire</div></td> -->
                                  <td width="48" align=center valign="middle" class="text"><div align="center"><?php echo getlang(200); ?></div></td>
                                  <td width="48" align=center valign="middle" class="text"><div align="center"><?php echo getlang(204); ?></div></td>
                                  <td width="48" align=center valign="middle" class="text"><div align="center"><?php echo getlang(220); ?></div></td>
								  <td width="48" align=center valign="middle" class="text"><div align="center">Total</div></td>
								  <td width="48" align=center valign="middle" class="text"><div align="center">Réf. Lot</div></td>
                                  <td width="48" align=center valign="middle" class="text"><div align="center"><?php echo getlang(226); ?></div></td>
                                  </tr>
                                <tr align="left" valign="middle">
                                  <td colspan="14" class="text" nowrap="nowrap"><?php echo $ligne ; ?>								  </td>
                                  </tr>
                              	<tr>
                                <td colspan="14"><input name='myaction' type='hidden' id="myaction" />
                                  <input name="exercicecourant" type="hidden" id="exercicecourant" value="<?php echo $_SESSION['GL_USER']['EXERCICE']; ?>" />
                                  <input name="statusexercice" type="hidden" id="statusexercice" value="<?php echo $_SESSION['GL_USER']['STATUT_EXERCICE']; ?>" />
                                  <input name="oldcode" type='hidden' id="oldcode" value="<?php echo  $refbonsortie; ?>" />
                                  <input name="xid" type='hidden' id="xid" value="<?php echo $xid; ?>" />
                                  <input name="rowSelection" type="hidden" id="rowSelection" value="" />
                                  <input name='debutexercice' type='hidden' id="debutexercice" value="<?php echo $_SESSION['GL_USER']['DEBUT_EXERCICE']; ?>" />
                                  <input name='finexercice' type='hidden' id="finexercice"  value="<?php echo $_SESSION['GL_USER']['FIN_EXERCICE']; ?>" /></td>
                              </tr>
							  <tr align="left" valign="top">
							    <td width="4">&nbsp;</td>
							    <td colspan="13">
							      <!-- <input name="Precedent" type="button" class="button" id="Precedent"  value='&lt;&lt; Pr&eacute;c&eacute;dent' onClick="javascript:window.location.href='addBonentree.php?selectedTab=inputs';"> -->
							      <input name="AjouterLign" type="button" class="button" id="AjouterLign"  value='<?php echo getlang(191); ?>' onClick="msgAddLign();" />
							      <input name="Enregistrer" type="button" class="button" id="Enregistrer"  value='<?php echo getlang(189); ?>'  onClick="validateForm();" /></td>
							    </tr>
                          </tbody></table></td>
                        </tr>
                      </table>
                </form></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table><br />


</td>
  </tr>
  <tr>
    <td height="10%">&nbsp;</td>
    <td height="10%">&nbsp;</td>
  </tr>
</table>
</body>
</html>
