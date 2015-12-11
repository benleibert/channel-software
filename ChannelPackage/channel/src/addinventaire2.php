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

//Set default
(isset($_SESSION['DATA_INVENT']['reference'])? $reference = stripslashes($_SESSION['DATA_INVENT']['reference']) : $reference ='');
(isset($_SESSION['DATA_INVENT']['dateAjout'])? $dateAjout = $_SESSION['DATA_INVENT']['dateAjout'] : $dateAjout ='');
(isset($_SESSION['DATA_INVENT']['libelle'])? $libelle = stripslashes($_SESSION['DATA_INVENT']['libelle']) : $libelle ='');
(isset($_SESSION['DATA_INVENT']['libelle'])? $codeCategorie = stripslashes($_SESSION['DATA_INVENT']['categorie']) : $codeCategorie ='');
$listecategorie =listeCategories($codeCategorie);
$ok = setInventaire($codeCategorie);
$i = $ok['nbreLigne'];
$lignInventaire = lignInventaire($i, $ok['data']);
$_SESSION['DATA_INVENT']['nbreLigne'] = $ok['nbreLigne'];
print_r($_SESSION['DATA_INVENT']);
$valider = '<script type="text/javascript">
	function verifier(i){
		var vide=i;
		var liste=\'\';';
		for ($j=1; $j<=$i; $j++){
			$valider .= 'if(document.FormInventaire.idArticle'.$j.'.value != \'\' && document.FormInventaire.qte'.$j.'.value != \'\' && document.FormInventaire.typeinventaire'.$j.'.value != \'00\'){vide--;}';
		}
		$valider .= 'if(vide !=0) return 1;
		else return 0;
	}

	function validateForm(){
		var vide='.$i.';';
		for ($j=1; $j<=$i; $j++){
			$valider .= 'if(document.FormInventaire.idArticle'.$j.'.value != \'\' && document.FormInventaire.qte'.$j.'.value != \'\'){vide--;}';
		}
		$valider .= 'var lg = '.$i.' - vide;
		if(vide =='.$i.'){alert(\'Veuillez entrer les articles dans lignes besoins\');}
		else if(verifier(lg)== 1) alert(\'Certaines lignes ne sont pas complètes.\');
		else {document.FormInventaire.myaction.value=\'ETAPE3\'; document.FormInventaire.submit();}
	}
</script>';

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<META HTTP-EQUIV="refresh" CONTENT="" >
<title>Gest-Stocks 1.0</title>
<LINK REL="SHORTCUT ICON" HREF="../images/favicon0.ico">
<link href="../css/neutralcss.css" rel="stylesheet" type="text/css">
<link href="../lib/JQuerySpinBtn.css" rel="stylesheet" type="text/css">
<link href="../lib/jquery.alerts.css" rel="stylesheet" type="text/css">
<link href="../css/defaultcss.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../lib/jquery.js"></script>
<script type="text/javascript" src="../lib/jsfuncLib.js"></script>

<!-- Pickdate--------------------------------------  -->
<link type="text/css" href="../lib/themes/base/ui.all.css" rel="stylesheet" />
<link type="text/css" href="../lib/demos.css" rel="stylesheet" />
<script type="text/javascript" src="../lib/ui/ui.core.js"></script>
<script type="text/javascript" src="../lib/ui/ui.datepicker.js"></script>


<!-- Begin of JS code  -->
<script type="text/javascript" src="../lib/JQuerySpinBtn.js"></script>

<?php echo $valider ; ?>
<script type="text/javascript">
function validateForm(){
        var reference = trimAll(document.FormInventaire.reference.value);
		var dateAjout = trimAll(document.FormInventaire.dateAjout.value);
		var libelle = trimAll(document.FormInventaire.libelle.value);
		var categorie = trimAll(document.FormInventaire.categorie.options[document.FormInventaire.categorie.selectedIndex].value);
		var msg = '';
		var j=0;

		if(reference == '') {
        	msg += '- Veuillez préciser la référence de l\'appel d\'offre.\n';
        }
		if(dateAjout == "") {
        	msg += '- Veuillez entrer la date d\'ajout de l\'appel d\'offre.\n';
        }
		else {
			if (!checkDate(document.formadd.dateAjout)) {
				msg += '- Date incorrect. Revoir le(Format: jj/mm/aaaa).\n';
			}
			else {
				if (!CompareDate(dateAjout, document.formadd.debutexercice.value, '>=') || !CompareDate(dateAjout, document.formadd.finexercice.value, '<=')) {msg += '- Date commande hors exercice budegétaire ['+document.formadd.debutexercice.value+' - '+document.formadd.finexercice.value+'].\n';}
				
			}
		}
		if(libelle == "") {
        	msg += '- Veuillez entrer le libellé de l\'appel d\'offre.\n';
        }
        if(categorie == "00") {
        	msg += '- Veuillez sélectionner la catégorie de produit à consolider.\n';
        }
		if(msg !=''){
			alert(msg);
		}
		else {
        	document.FormInventaire.myaction.value='ETAPE3';
			document.FormInventaire.submit();
        }
}

function Deplacer(l1,l2) {
		if (l1.options.selectedIndex>=0) {
			o=new Option(l1.options[l1.options.selectedIndex].text,l1.options[l1.options.selectedIndex].value);
			l2.options[l2.options.length]=o;
			l1.options[l1.options.selectedIndex]=null;
		}else{
			alert("Aucun besoin sélectionné");
		}
	}


function doMyAction(myform){
	if(document.FormInventaire.toggleAll.checked == true){
	//alert(document.ListingForm.elements.length);
		for (i = 0; i < document.FormInventaire.elements.length; i++) {
       		document.FormInventaire.elements[i].checked=true;
			var x= go(document.FormInventaire.elements[i].value, i);
    	}
	}
	if(document.FormInventaire.toggleAll.checked == false){
	//alert(document.ListingForm.elements.length);
		for (i = 0; i < document.FormInventaire.elements.length; i++) {
       		document.FormInventaire.elements[i].checked=false;
    	}
	}
    return false;
}

function ReloadPage(){
	document.FormInventaire.myaction.value='CONSOLID';
	document.FormInventaire.submit();
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
			if(retour !='') document.getElementById('reference').focus();
		}
	}

	// Ici on va voir comment faire du post
	xhr.open("POST","phpfuncinventaires.php?test=DOUBLON",true);
	// ne pas oublier ça pour le post
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	// ne pas oublier de poster les arguments
	// ici, l'id de l'auteur
	id = document.getElementById('reference').value;
	xhr.send("codeInvent="+id);
}


function fillMe(){
	var xhr = getXhr();
	// On défini ce qu'on va faire quand on aura la réponse
	xhr.onreadystatechange = function(){
		// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
		if(xhr.readyState == 4 && xhr.status == 200){
			retour = xhr.responseText;
			// On se sert de innerHTML pour rajouter les options a la liste
			document.getElementById('listearticle').innerHTML = retour;
		}
	}

	// Ici on va voir comment faire du post
	xhr.open("POST","phpfunccategories.php?test=FILL",true);
	// ne pas oublier ça pour le post
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	// ne pas oublier de poster les arguments
	// ici, l'id de l'auteur
	id = document.getElementById('categorie').options[document.getElementById('categorie').selectedIndex].value;
	xhr.send("code="+id);
}


</script>

<script type="text/javascript">
	$(function() {
		$('#datepicker').datepicker({
			showButtonPanel: true
		});
	});

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
</style></head>
<body class="bodyBg">
<script> writeTableStartTagBasedOnResolution(); </script>
  <tr>
    <td class="tabsBg"><script language="JavaScript" type="text/JavaScript">
<!--
function clearText()
{
	document.stocksform.searchTerm.value="";
}
function validateValues()
{
	if(this.document.TabSearchForm.searchTerm.value == '')
	{
		alert("Please enter the device name to search")
			return false;
	}
	return true;
}

//-->
</script>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}


//-->
</script>
<!-- End of JS code  -->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
      <td width="200" rowspan=2>
              <img src="../images/unfpa.png" alt="" width="95" height="45" hspace=2 vspace=0 />
        </td>
          <td height="24" colspan="2" align="right" valign=top><span class="wtext">
          <?php echo RIGHT_MENU; ?>
	  </td>
      </tr>
          <tr>
            <td height="20" valign="top"><?php
			  $selectedTab = $_GET['selectedTab'];
			  echo topMenus($selectedTab,$droitTOPMENUS);
			  ?>
            <td align="right"><!--<a href="#" onClick="doPersonalize()" >Personalize</a> |-->
           <span class="wtext">
	  <a href="../src/licence.php?selectedTab=home" >License</a>
	   <span class=white> &nbsp;| &nbsp;</span>
	   <?php echo '<a href="phpfuncindex.php?myaction=LOGOUT" title="'.$_SESSION['GL_USER']['NOM'].'">'.$_SESSION['GL_USER']['LOGIN'].' [Déconnexion]</a>'; ?></span>&nbsp;</td>
        </tr>
      </table>
    </td>
</tr>

    <tr class="searchBg">
      <td height="21" align="center">

	 <table border="0"cellspacing="0" cellpadding="0">
          <tr>
            <form name="stockform" action="file:///C|/wamp/www/sources/search.php" method="post" onsubmit=''>
			<td align="left" class="leftHeader">
            <?php echo EXBG_MAG; ?></td>
            <td align="right">&nbsp;

              </td>
            <td><input name="Go" type="submit" class="buttonGo" value="GO"></td>
            	<input type="hidden" name="requestid" value="SNAPSHOT">
				<input type="hidden" name="selectedLink" value="">
				<input type="hidden" name="selectedTab" value="Network Database">
	    <td>&nbsp;</td>
	    </form>
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
<script>
function openWindow(theURL,winName,width,height,parms)
{
    var left = Math.floor( (screen.width - width) / 2);
    var top = Math.floor( (screen.height - height) / 2);
    var winParms = "top=" + top + ",left=" + left + ",height=" + height + ",width=" + width +",scrollbars=yes";
    if (parms) { winParms += "," + parms; }
    window.open(theURL, winName, winParms);
}
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
                  <td height="20" bgcolor="#FFCC66" class="leftHeader"><?php echo getlang(298); ?> - Faire un inventaire - | Etape 1 &gt;&gt;  <span class="Style2">Etape 2</span> &gt;&gt; Etape 3 &gt;&gt;</td>
                </tr>
                <tr>
                  <td colspan=2 align="left" valign="top"><form name="FormInventaire" action="../src/phpfuncinventaires.php" method="POST">
                      <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
                        <tr class="header2Bg">
                          <td align="left" valign="top" class="boldText" >&nbsp;Inventaire</td>
                        </tr>
                        <tr>
                          <td class="text" align="center"><table width="600" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                            <tbody>
                              <tr align="left" valign="top">
                                <td width="200" align=right valign="middle" class="text">R&eacute;f&eacute;rence :&nbsp;</td>
                                <td width="358" align="left" class="text"><input name="reference" type="text" class="formStyle" id="reference" value="<?php echo $reference;?>" maxlength="100" onBlur="go();">
								<span class="mandatory" id="msgerror"></span>
								</td>
                              </tr>
							  <tr align="left" valign="top">
                                      <td width="200" align=right valign="middle" class="text"><?php echo getlang(42); ?> :&nbsp;</td>
                                      <td width="358" align="left" class="text"><input name="dateAjout" type="text" class="formStyle" id="datepicker" value="<?php echo $dateAjout;?>">
                                          <span class="mandatory"></span> </td>
                                    </tr>
                                    <tr align="left" valign="top">
                                      <td width="200" align=right valign="middle" class="text">Libell&eacute;&nbsp;:&nbsp;</td>
                                      <td align="left" class="text"><input name="libelle" type="text" class="formStyle" id="libelle" value="<?php echo $libelle;?>">
                                          <span class="mandatory"></span> </td>
                                    </tr>
									 <tr align="left" valign="top">
                                      <td width="200" align=right valign="middle" class="text"><?php echo getlang(19); ?> &nbsp;:&nbsp;</td>
                                      <td align="left" class="text"><select name="categorie" class="formStyle" id="categorie" onChange="fillMe();">
									  <option value="00">[S&eacute;lectionnez la cat&eacute;gorie]</option>
                                  <?php echo $listecategorie; ?>
                                </select> </td>
                               </tr>
							   <!--
                               <tr align="left" valign="top">
                                      <td width="200" align=right valign="middle" class="text">Articles&nbsp;:&nbsp;</td>
                                      <td align="left" class="text"><div id="listearticle"><select name="article[]" class="formStyle" id="article[]" multiple="multiple">
                                 </select> </div><br>
							  <i><?php echo getlang(227); ?></i></td>
                               </tr>
							   -->
                          </table></td>
                        </tr>
                      </table>
                          <br>
                      <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
                        <tr class="header2Bg">
                          <td align="left" valign="top" class="boldText" >&nbsp;Lignes de l'inventaire</td>
                        </tr>
                        <tr>
                          <td class="text" align="center"><table width="756" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                              <tbody>
                                <tr align="left" valign="top" nowrap>
                                  <td align=right valign="middle" class="text">&nbsp;</td>
                                  <td width="64" align=right valign="middle" nowrap class="text"><div align="center"><?php echo getlang(257); ?> </div></td>
                                  <td width="264" align=right valign="middle" class="text"><div align="left"><?php echo getlang(199); ?></div></td>
                                  <td width="60" align="center" valign="middle" nowrap class="text"><div align="center">P. unitaire</div></td>
                                  <td width="48" align=center valign="middle" class="text"><div align="center">Stock T.</div></td>
								  <td width="48" align=center valign="middle" class="text"><div align="center">Stock P.</div></td>
								  <td width="48" align=center valign="middle" class="text"><div align="center">Total</div></td>
								  <td width="48" align=center valign="middle" class="text"><div align="center"><?php echo getlang(204); ?></div></td>
                                  <td width="82"  valign="middle" class="text"><div align="center">Mnt total</div></td>
                                  </tr>
                                <tr align="left" valign="middle">
                                  <td colspan="9" class="text" nowrap="nowrap"><?php echo $lignInventaire ; ?>
								  </td>
                                  </tr>
                              	<tr>
                                <td colspan="9">
								<input name="exercicecourant" type="hidden" id="exercicecourant" value="<?php echo $_SESSION['GL_USER']['EXERCICE']; ?>">
						<input name="statusexercice" type="hidden" id="statusexercice" value="<?php echo $_SESSION['GL_USER']['STATUT_EXERCICE']; ?>">
						<input name="Ajout" type="hidden" id="Ajout" value="<?php echo $_SESSION['GL_USER']['DROIT']['I']['Ajout']; ?>">
				<input name="Modif" type="hidden" id="Modif" value="<?php echo $_SESSION['GL_USER']['DROIT']['I']['Modif']; ?>">
				<input name="Suppr" type="hidden" id="Suppr" value="<?php echo $_SESSION['GL_USER']['DROIT']['I']['Suppr']; ?>">
				<input name="Validatit" type="hidden" id="Validatit" value="<?php echo $_SESSION['GL_USER']['DROIT']['I']['Validatit']; ?>">
						<input name='myaction' type='hidden' id="myaction" value=''>
						<input name='debutexercice' type='hidden' id="debutexercice" value="<?php echo $_SESSION['GL_USER']['DEBUT_EXERCICE']; ?>" />
                        <input name='finexercice' type='hidden' id="finexercice"  value="<?php echo $_SESSION['GL_USER']['FIN_EXERCICE']; ?>" /></td>
                              </tr>
							  <tr align="left" valign="top">
							    <td width="4">&nbsp;</td>
							    <td width="4">&nbsp;</td>
  								<td colspan="7">
								<input name="Precedent" type="button" class="button" id="Precedent"  value='&lt;&lt; Pr&eacute;c&eacute;dent' onClick="javascript:window.location.href='addinventaire.php?selectedTab=inputs';">
								<input name="AjouterBonentre" type="button" class="button" id="AjouterBonentre"  value='<?php echo getlang(189); ?>' onClick="validateForm();"></td>
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
    </table></td>
  </tr>
  <tr>
    <td height="10%">&nbsp;</td>
    <td height="10%">&nbsp;</td>
  </tr>
</table>
</body>
</html>
