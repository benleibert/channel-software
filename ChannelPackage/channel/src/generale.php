<?php
//Session
session_start();
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:dbuser.php?do=logout");

require_once('../lib/phpfuncLib.php');		//All commun functions
require_once('menus.php');

//Top Menu
$selectedTab = $_GET['selectedTab'];
$menu = topMenus($selectedTab,$_SESSION['GL_USER']['DROIT']);

//Left Menu
$leftMenu = parametersMenus($selectedTab , $_SESSION['GL_USER']['DROIT']);

//DOIT MAJ
$droitMAJ = $_SESSION['GL_USER']['DROIT']['par_gen'];

$INFO = getInfoGenerale($_SESSION['GL_USER']['MAGASIN']);
$nommag = getField('CODE_MAGASIN',$_SESSION['GL_USER']['MAGASIN'],'SER_NOM','magasin');

(isset($INFO['CODE_INFGLE']) ? $oldcodeinfogle = $INFO['CODE_INFGLE']: $oldcodeinfogle ='');
(isset($INFO['ID']) ? $id = $INFO['ID']: $id ='');
(isset($INFO['INF_CLIENT']) ? $client = $INFO['INF_CLIENT']: $client ='');
(isset($INFO['INF_DATEACQ']) ? $dateacq = $INFO['INF_DATEACQ']: $dateacq  ='');
(isset($INFO['INF_LICENCE']) ? $licence = $INFO['INF_LICENCE']: $licence ='');
(isset($INFO['INF_MINISTERE']) && $INFO['INF_MINISTERE']!='' ? $ministere = $INFO['INF_MINISTERE']: $ministere ='');
(isset($INFO['INF_SECRETARIAT']) && $INFO['INF_SECRETARIAT']!='' ? $secretariat = $INFO['INF_SECRETARIAT']: $secretariat ='');
(isset($INFO['INF_DIRECTION']) && $INFO['INF_DIRECTION']!='' ? $direction = $INFO['INF_DIRECTION']: $direction ='');
(isset($INFO['INF_SERVICE']) && $INFO['INF_SERVICE']!='' ? $service = $INFO['INF_SERVICE']: $service ='');
(isset($INFO['INF_CSPS']) && $INFO['INF_CSPS']!='' ? $csps = $INFO['INF_CSPS']: $csps ='');
(isset($INFO['INF_CSPS']) ? $csps = $INFO['INF_CSPS']: $csps ='');
(isset($INFO['INF_PAYS']) ? $pays = $INFO['INF_PAYS']: $pays ='');
(isset($INFO['INF_VILLE']) ? $ville = $INFO['INF_VILLE']: $ville ='');
(isset($INFO['INF_DEVISE']) ? $devise = $INFO['INF_DEVISE']: $devise ='');
(isset($INFO['LOGO']) ? $logo = $INFO['LOGO']: $logo ='');
(isset($INFO['INF_SIGNATEUR1']) ? $signateur1 = $INFO['INF_SIGNATEUR1']: $signateur1 ='');
(isset($INFO['INF_NOMSIGNATEUR1']) ? $nomsignateur1 = $INFO['INF_NOMSIGNATEUR1']: $nomsignateur1 ='');

(isset($INFO['INF_SIGNATEUR2']) ? $signateur2 = $INFO['INF_SIGNATEUR2']: $signateur2 ='');
(isset($INFO['INF_NOMSIGNATEUR2']) ? $nomsignateur2 = $INFO['INF_NOMSIGNATEUR2']: $nomsignateur2 ='');

(isset($INFO['INF_SIGNATEUR3']) ? $signateur3 = $INFO['INF_SIGNATEUR3']: $signateur3 ='');
(isset($INFO['INF_NOMSIGNATEUR3']) ? $nomsignateur3 = $INFO['INF_NOMSIGNATEUR3']: $nomsignateur3 ='');

(isset($INFO['INF_SIGNATEUR4']) ? $signateur4 = $INFO['INF_SIGNATEUR4']: $signateur4 ='');
(isset($INFO['INF_NOMSIGNATEUR4']) ? $nomsignateur4 = $INFO['INF_NOMSIGNATEUR4']: $nomsignateur4 ='');

(isset($INFO['INF_VALIDAUTO']) ? $validauto = $INFO['INF_VALIDAUTO']: $validauto ='');
(isset($INFO['INF_LIBELLE_PROG']) ? $libprog = $INFO['INF_LIBELLE_PROG']: $libprog ='');
(isset($INFO['INF_LIBELLE_DOT']) ? $libdot = $INFO['INF_LIBELLE_PROG']: $libdot ='');
(isset($INFO['INF_LIBELLE_PROGBAC']) ? $libprogBAC = $INFO['INF_LIBELLE_PROGBAC']: $libprogBAC ='');
(isset($INFO['INF_LIBELLE_REVER']) ? $librevers = $INFO['INF_LIBELLE_REVER']: $librevers ='');
(isset($INFO['INF_LIBELLE_DECL']) ? $libdecl = $INFO['INF_LIBELLE_DECL']: $libdecl ='');
(isset($INFO['INF_LIBELLE_REC']) ? $librecond= $INFO['INF_LIBELLE_REC']: $librecond ='');
(isset($INFO['INF_LIBELLE_REP']) ? $libreport= $INFO['INF_LIBELLE_REP']: $libreport ='');
(isset($INFO['INF_LIBELLE_DOTUST']) ? $libdotUst= $INFO['INF_LIBELLE_DOTUST']: $libdotUst ='');
(isset($INFO['INF_LIBELLE_DOTBAC']) ? $libdotBAC= $INFO['INF_LIBELLE_DOTBAC']: $libdotBAC ='');
(isset($INFO['INF_LIBELLE_TRF']) ? $libtransf= $INFO['INF_LIBELLE_TRF']: $libtransf ='');
(isset($INFO['LOGO']) ? $afflogo = "<a href=\"#\" onClick=\"OpenWin('afficherlogo.php?id=$oldcodeinfogle','Logo');\">Afficher logo</a>": $afflogo ='');
(isset($INFO['INF_MAGASIN']) ? $magasin = $INFO['INF_MAGASIN']: $magasin ='');
($validauto == 1? $checked='checked="checked"' : $checked='');
($dateacq =='0000-00-00'  ? $dateacq='' : $dateacq =frFormat2($dateacq));
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


<script type="text/javascript">
function validateForm(){
        var client = trimAll(document.formadd.client.value);
		var msg = '';

		if(client == '') {
        	msg += '- Veuillez saisir le client.\n';
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
			if(retour !='') document.getElementById('codeCategorie').focus();
		}
	}

	// Ici on va voir comment faire du post
	xhr.open("POST","phpfunccategories.php?test=CODECATEGORIE",true);
	// ne pas oublier éa pour le post
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
</head><body class="bodyBg">
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
            
            <td class="leftHeader">
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
                 <td height="20"  bgcolor="#FFCC66" class="leftHeader"><?php echo getlang(106); ?> -> <?php echo getlang(107); ?></td>
              </tr>
              <tr>
                <td colspan="2" align="left" valign="top"><form action="dbgenerale.php?do=add" method="post" enctype="multipart/form-data" name="formadd" id="formadd">
                  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
                    <tr class="header2Bg">
                      <td align="left" valign="top" class="boldText" >&nbsp;<?php echo getlang(340); ?></td>
                    </tr>
                    <tr>
                      <td class="text" align="center"><table width="800" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                        <tbody>
                          <tr align="left" valign="top">
                            <td width="251" align="right" valign="middle" class="text"><?php echo getlang(343); ?> :&nbsp;</td>
                            <td align="left" class="text"><input name="client" type="text" class="formStyle" id="client" value="<?php echo $client; ?>" onblur="go();" /></td>
                          </tr>
                          
                          
                          <tr class="header2Bg">
                      <td align="left" colspan="2" valign="top" class="boldText" >&nbsp;<?php echo getlang(341); ?></td>
                    </tr>
                          <tr align="left" valign="top">
                            <td width="251" align="right" valign="middle" class="text"><?php echo getlang(214); ?> 1 (Ex. Minist&egrave;re de ...)&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="ministere" type="text" class="formStyle" id="ministere" value="<?php echo $ministere; ?>" /></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td width="251" align="right" valign="middle" class="text"><?php echo getlang(214); ?> 2 (Ex. Sec&eacute;tariat de ...)&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="secretariat" type="text" class="formStyle" id="secretariat" value="<?php echo $secretariat; ?>" /></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td width="251" align="right" valign="middle" class="text"><?php echo getlang(214); ?> 3 (Ex. Direction de ...)&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="direction" type="text" class="formStyle" id="direction" value="<?php echo $direction; ?>" /></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td width="251" align="right" valign="middle" class="text"><?php echo getlang(214); ?> 4 (Ex. Service de ...)&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="service" type="text" class="formStyle" id="service" value="<?php echo $service; ?>" /></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(214); ?> 5 (Ex. Consommation/CSPS ...)&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="csps" type="text" class="formStyle" id="csps" value="<?php echo $csps; ?>" /></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td width="251" align="right" valign="middle" class="text"><?php echo getlang(343); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="pays" type="text" class="formStyle" id="pays" value="<?php echo $pays; ?>" /></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td width="251" align="right" valign="middle" class="text"><?php echo getlang(344); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="ville" type="text" class="formStyle" id="ville" value="<?php echo $ville; ?>" /></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td width="251" align="right" valign="middle" class="text"><?php echo getlang(345); ?> &nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="devise" type="text" class="formStyle" id="devise" value="<?php echo $devise; ?>" /></td>
                          </tr>
                           <tr class="header2Bg">
                      <td align="left" colspan="2" valign="top" class="boldText" >&nbsp;<?php echo getlang(342); ?></td>
                    </tr>
                          <tr align="left" valign="top">
                            <td width="251" align="right" valign="middle" class="text"><?php echo getlang(459); ?> 1 (Ex. magasinier)&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="signateur1" type="text" class="formStyle" id="signateur1" value="<?php echo $signateur1; ?>" /></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td width="251" align="right" valign="middle" class="text" nowrap="nowrap"><?php echo getlang(103); ?> <?php echo getlang(115); ?> <?php echo getlang(459); ?> 1 (Ex. Paul Adama) :&nbsp;</td>
                            <td align="left" class="text"><input name="nomsignateur1" type="text" class="formStyle" id="nomsignateur1" value="<?php echo $nomsignateur1; ?>" /></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td width="251" align="right" valign="middle" class="text"><?php echo getlang(459); ?> 2 (Ex. Chef de service)&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="signateur2" type="text" class="formStyle" id="signateur2" value="<?php echo $signateur2; ?>" /></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td width="251" align="right" valign="middle" class="text"><?php echo getlang(103); ?> <?php echo getlang(115); ?> <?php echo getlang(459); ?> 2&nbsp;(Ex. M XXX) :&nbsp;</td>
                            <td align="left" class="text"><input name="nomsignateur2" type="text" class="formStyle" id="nomsignateur2" value="<?php echo $nomsignateur2; ?>" /></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(459); ?> 3 (Ex. Gestionnaire)&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="signateur3" type="text" class="formStyle" id="signateur3" value="<?php echo $signateur3; ?>" /></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td width="251" align="right" valign="middle" class="text"><?php echo getlang(103); ?> <?php echo getlang(115); ?> <?php echo getlang(459); ?> 3&nbsp;(Mme YYYY) :&nbsp;</td>
                            <td align="left" class="text"><input name="nomsignateur3" type="text" class="formStyle" id="nomsignateur3" value="<?php echo $nomsignateur3; ?>" /></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(459); ?> 4 (DAF)&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="signateur4" type="text" class="formStyle" id="signateur4" value="<?php echo $signateur4; ?>" /></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td width="251" align="right" valign="middle" class="text"><?php echo getlang(103); ?> <?php echo getlang(115); ?> <?php echo getlang(459); ?> 4&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="nomsignateur4" type="text" class="formStyle" id="nomsignateur4" value="<?php echo $nomsignateur4; ?>" /></td>
                          </tr>
                        <!--
                          <tr class="header2Bg">
                      <td align="left" colspan="2" valign="top" class="boldText" >&nbsp;Automatisation de la validation</td>
                    </tr>
                    
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Automatiser la validation&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="validauto" type="checkbox" id="validauto" value="1"  <?php echo  $checked; ?> /></td>
                          </tr>
                          -->
                          <!--
                          <tr class="header2Bg">
                      <td align="left" colspan="2" valign="top" class="boldText" >&nbsp;Etats et imprimables (Libellé)</td>
                    </tr>
                          <tr>
                            <td align="right">Libellé état Programmations&nbsp;:&nbsp;</td>
                            <td><input name="libprog" type="text" class="formStyle" id="libprog" value="<?php echo $libprog; ?>" /></td>
                          </tr>
                          <tr>
                            <td align="right">Libellé état Réversement&nbsp;:&nbsp;</td>
                            <td><input name="librevers" type="text" class="formStyle" id="librevers" value="<?php echo $librevers; ?>" /></td>
                          </tr>
                          <tr>
                            <td align="right">Libellé état Programmations BAC&nbsp;:&nbsp;</td>
                            <td><input name="librevers2" type="text" class="formStyle" id="librevers2" value="<?php echo $librevers; ?>" /></td>
                          </tr>
                          <tr>
                            <td align="right">Libellé état Dotations&nbsp;:&nbsp;</td>
                            <td><input name="libdot" type="text" class="formStyle" id="libdot" value="<?php echo $libdot; ?>" /></td>
                          </tr>
                          <tr>
                            <td align="right">Libellé état Déclassements&nbsp;:&nbsp;</td>
                            <td><input name="libdecl" type="text" class="formStyle" id="libdecl" value="<?php echo $libdecl; ?>" /></td>
                          </tr>
                          <tr>
                            <td align="right">Libellé état Transferts&nbsp;:&nbsp;</td>
                            <td><input name="libtransf" type="text" class="formStyle" id="libtransf" value="<?php echo $libtransf; ?>" /></td>
                          </tr>
                          <tr>
                            <td align="right">Libellé état Reconditionnements&nbsp;:&nbsp;</td>
                            <td><input name="librecond" type="text" class="formStyle" id="librecond" value="<?php echo $librecond; ?>" /></td>
                          </tr>
                          <tr>
                            <td align="right">Libellé état Reports&nbsp;:&nbsp;</td>
                            <td><input name="libreport" type="text" class="formStyle" id="libreport" value="<?php echo $libreport; ?>" /></td>
                          </tr>
                          <tr>
                            <td align="right">Libellé état Dotations ustensiles&nbsp;:&nbsp;</td>
                            <td><input name="libdotUst" type="text" class="formStyle" id="libdotUst" value="<?php echo $libdotUst; ?>" /></td>
                          </tr>
                          <tr>
                            <td align="right">Libellé état Dotations BAC&nbsp;:&nbsp;</td>
                            <td><input name="libdotBAC" type="text" class="formStyle" id="libdotBAC" value="<?php echo $libdotBAC; ?>" /></td>
                          </tr>
                          -->
                          <tr>
                            <td><input name="exercicecourant" type="hidden" id="exercicecourant" value="<?php echo $_SESSION['GL_USER']['EXERCICE']; ?>" />
                              <input name="statusexercice" type="hidden" id="statusexercice" value="<?php echo $_SESSION['GL_USER']['STATUT_EXERCICE']; ?>" />
                              <input name='myaction' type='hidden' id="myaction" value="" />
                              <input name='oldcodeinfogle' type='hidden' id="oldcodeinfogle" value="<?php echo $oldcodeinfogle; ?>" />
                              <input name='id' type='hidden' id="id" value="<?php echo $id; ?>" /></td>
                            <td><span class="mandatory">*</span> <?php echo getlang(215); ?></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td width="251">&nbsp;</td>
                            <td><input name="Suivant" type="button" class="button" id="Suivant"  value="<?php echo getlang(190); ?>" onclick="validateForm();" />
                              <input name="Retablir" type="reset" class="button" id="Retablir"  value='<?php echo getlang(193); ?>'
 /></td>
                          </tr>
                        </tbody>
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
