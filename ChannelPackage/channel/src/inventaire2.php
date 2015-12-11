<?php
session_start();
require_once('../src/topmenus.php');				//The menubar
require_once('../lib/phpfuncLib.php');		//All commun functions
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:phpfuncindex.php?myaction=LOGOUT");

//Set default
(isset($_GET['id']) ? $id = $_GET['id'] : header("location:inventaires.php?selectedTab=inputs"));
$ok = setConsInventaire($id);
(isset($_SESSION['CONS_INVENT']['idInvent'])? $reference = (stripslashes($_SESSION['CONS_INVENT']['idInvent'])) : $reference ='');
(isset($_SESSION['CONS_INVENT']['dateAjout'])? $dateAjout = $_SESSION['CONS_INVENT']['dateAjout'] : $dateAjout ='');
(isset($_SESSION['CONS_INVENT']['libelle'])? $libelle = (stripslashes($_SESSION['CONS_INVENT']['libelle'])) : $libelle ='');

//Call lignBesoins($nbre) to deplay lignes
$lignDetInventaire = lignDetInventaire($_SESSION['CONS_INVENT']['ligne']);
session_unregister('CONS_INVENT');
		
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
<!-- Begin of JS code  -->
<script type="text/javascript" src="../lib/jquery.js"></script>
<script type="text/javascript" src="../lib/jsfuncLib.js"></script>
<script langage="javascript">
function goSupp(valeur){
	var ret = confirm('Voulez-vous supprimer cette donnée?')
	if(ret == true)
	{
		var xhr = getXhr();
		// On défini ce qu'on va faire quand on aura la réponse
		xhr.onreadystatechange = function(){
			// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
			if(xhr.readyState == 4 && xhr.status == 200)
			{
				retour = xhr.responseText;
				// On se sert de innerHTML pour rajouter les options a la liste
				//document.getElementById('msg').innerHTML = retour;
				if(retour==1) 
				{
					alert('Impossible de supprimer cet inventaire, il a été validé.');
				}
				else 
				{
					document.FormInventaire.myaction.value="DEL";
					document.FormInventaire.submit();
				}
			}
		}

		// Ici on va voir comment faire du post
		xhr.open("POST","phpfuncinventaires.php?test=VALIDER",true);
		// ne pas oublier ça pour le post
		xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		// ne pas oublier de poster les arguments
		// ici, l'id de l'auteur
		//id = document.getElementById('codeRegion').value;
		xhr.send("code="+valeur);
	}
}

function goEdit(valeur){
	var ret = confirm('Voulez-vous modifier cette donnée?')
	if(ret == true)
	{
		var xhr = getXhr();
		// On défini ce qu'on va faire quand on aura la réponse
		xhr.onreadystatechange = function(){
			// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
			if(xhr.readyState == 4 && xhr.status == 200)
			{
				retour = xhr.responseText;
				// On se sert de innerHTML pour rajouter les options a la liste
				//document.getElementById('msg').innerHTML = retour;
				if(retour==1)
				{
					alert('Impossible de modifier cet inventaire, il a été validé.');
				}
				else 
				{
					document.FormInventaire.myaction.value="EDIT";
					document.FormInventaire.submit();
				}
			}
		}

		// Ici on va voir comment faire du post
		xhr.open("POST","phpfuncinventaires.php?test=VALIDER",true);
		// ne pas oublier ça pour le post
		xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		// ne pas oublier de poster les arguments
		// ici, l'id de l'auteur
		//id = document.getElementById('codeRegion').value;
		xhr.send("code="+valeur);
	}
}

function goPrint(valeur){
	//var ret = confirm('Voulez-vous supprimer cette donnée?')
	//if(ret == true)
	//{
		var xhr = getXhr();
		// On défini ce qu'on va faire quand on aura la réponse
		xhr.onreadystatechange = function(){
			// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
			if(xhr.readyState == 4 && xhr.status == 200)
			{
				retour = xhr.responseText;
				// On se sert de innerHTML pour rajouter les options a la liste
				//document.getElementById('msg').innerHTML = retour;
				if(retour==1) {	OpenBigWin('etatimprimablei.php?id='+valeur,''); }
				else alert('Impossible d\'imprimer cet inventaire, il n\'a pas été validé.');
			}
		}

		// Ici on va voir comment faire du post
		xhr.open("POST","phpfuncinventaires.php?test=VALIDER",true);
		// ne pas oublier ça pour le post
		xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		// ne pas oublier de poster les arguments
		// ici, l'id de l'auteur
		//id = document.getElementById('codeRegion').value;
		xhr.send("code="+valeur);
	//}
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
          <a href="http://www.econsulting.bf/forums/index.php?id=STOCKS" target="_blank"><img src="../images/forums00.gif" border="0" hspace="3" align="middle" />Forums</a> <span class=white> </span>
          <a href="" onClick=""><img src="../images/talkback.gif" border="0" hspace="3" align="middle" />R&eacute;agir</a><span class=white> </span>
          <a href="" onClick="javascript:window.open('perso.php','Personnalisation','left=500,top=100,width=550,height=250')"><img src="../images/personal.gif" border="0" hspace="3" align="middle" />Personnaliser</a>          <span class=white> </span>
          <a href="" onClick="javascript:window.open('aide/index.html','','')"><img src="../images/help0000.gif" border="0" hspace="3" align="middle" />Aide</a> <span class=white> </span>
          <a href="" onClick="JavaScript:window.open('/about.php','A propos','left=500,top=100,width=350,height=300')" ><img src="../images/about000.gif" border="0" hspace="3" align="middle" />A propos</a>&nbsp;&nbsp;
	  </td>
      </tr>
          <tr> 
            <td height="20" valign="top"><?php 
			  $selectedTab = $_GET['selectedTab'];
			  echo topMenus($selectedTab,$droitTOPMENUS);
			  ?>
            <td align="right"><!--<a href="#" onClick="doPersonalize()" >Personalize</a> |-->
           <span class="wtext">
	  <a href="" onClick="JavaScript:window.open('/webclient/common/jsp/registerDialog.jsp?UserType=R','License','left=500,top=100,width=500,height=275')" >License</a>
	   <span class=white> &nbsp;| &nbsp;</span>
	   <a href="file:///C|/wamp/www/sources/disconnect">GL_USER [Déconnexion]</a></span>&nbsp;</td>
        </tr>
      </table>
    </td>
</tr>
    
    <tr class="searchBg">
      <td height="21" align="center">

	 <table border="0"cellspacing="0" cellpadding="0">
          <tr>
            <form name="stockform" action="../sources/search.php" method="post" onsubmit=''>
            <td align="left" class="leftHeader">
            | <span class="Style2">Exercice budg&eacute;taire 2009 &gt;&gt;&nbsp;&nbsp;</span></td>
			  <td align="right">&nbsp;
            
              </td>
            <td><input name="Go" type="submit" class="buttonGo" value="GO"></td>
            	<input type="hidden" name="requestid" value="SNAPSHOT">
				
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
            <td width="180"><?php 
			$selectedTab = $_GET['selectedTab'];
			include '../src/menus.php';
			?></td>
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
                  <td width="43" bgcolor="#FFCC66" class="leftHeader"><?php echo getlang(298); ?></td>
                </tr>
                <tr>
                  <td colspan=2 align="left" valign="top"><form name="FormInventaire" action="../src/phpfuncinventaires.php" method="POST">
                      <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
                        <tr class="header2Bg">
                          <td align="left" valign="top" class="boldText" >&nbsp;R&eacute;f&eacute;rence inventaire</td>
                        </tr>
                        <tr>
                          <td class="text" align="center"><table width="600" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                            <tbody>
							
                              <tr align="left" valign="top">
                                <td width="200" align=right valign="middle" class="text">R&eacute;f&eacute;rence&nbsp;:&nbsp;</td>
                                <td align="left" class="text"><div class="ligneAll" nowrap="nowrap"><?php echo $reference;?>
								<input name="rowSelection[]" type="hidden" class="formStyle" id="rowSelection[]" value="<?php echo $reference;?>"></td>
                              </tr>
                              <tr align="left" valign="top">
                                      <td width="200" align=right valign="middle" class="text"><?php echo getlang(42); ?> :&nbsp;</td>
                                      <td width="358" align="left" class="text"><div class="ligneAll" nowrap="nowrap"><?php echo $dateAjout;?></div>
                                          <span class="mandatory"></span> </td>
                                    </tr>
									<tr align="left" valign="top">
                                      <td width="200" align=right valign="middle" class="text">Libell&eacute;&nbsp;:&nbsp;</td>
                                      <td align="left" class="text"><div class="ligneAll" nowrap="nowrap"><?php echo $libelle;?></div>
                                          <span class="mandatory"></span> </td>
                                    </tr>
									
                          </table></td>
                        </tr>
                      </table>
                          <br>
                      <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
                        <tr class="header2Bg">
                          <td align="left" valign="top" class="boldText" >&nbsp;Lignes inventaire</td>
                        </tr>
                        <tr>
                          <td class="text" align="center"><table width="623" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                              <tbody>
                                <tr align="left" valign="top" nowrap>
                                  <td align=right valign="middle" class="text">&nbsp;</td>
                                  <td width="64" align=right valign="middle" nowrap class="text"><div align="center"><?php echo getlang(257); ?> </div></td>
                                  <td width="264" align=right valign="middle" class="text"><div align="left"><?php echo getlang(199); ?></div></td>
                                  <td width="60" align="center" valign="middle" nowrap class="text"><div align="center">P. unitaire</div></td>
								  <td width="48" align=center valign="middle" class="text"><div align="center">Bonus/Malus</div></td>
                                  <td width="48" align=center valign="middle" class="text"><div align="center"><?php echo getlang(200); ?></div></td>
								  <td width="48" align=center valign="middle" class="text"><div align="center"><?php echo getlang(204); ?></div></td>
                                  <td width="82"  valign="middle" class="text"><div align="center">Mnt total</div></td>
                                  </tr>
                           		  <?php echo $lignDetInventaire;  ?>
					          	<tr>
                                <td colspan="8"><input name='myaction' type='hidden' id="myaction" value=''></td>
                              </tr>
							  <tr align="left" valign="top">
							    <td width="4">&nbsp;</td>
							    <td width="4">&nbsp;</td>
  								<td colspan="6">
								<input name="Precedent" type="button" class="button" id="Precedent"  value='&lt;&lt; Pr&eacute;c&eacute;dent' onClick="javascript:window.location.href='inventaires.php?selectedTab=inputs';">
								<input name="EditerBesoin" type="button" class="button" id="EditerBesoin"  value='Editer les donn&eacute;es' onClick="goEdit('<?php echo $reference;?>');">
								<input name="SupprimerBesoin" type="button" class="button" id="SupprimerBesoin"  value='Supprimer les donn&eacute;es' onClick="goSupp('<?php echo $reference;?>');">
								<input name="EtatBesoin" type="button" class="button" id="EtatBesoin"  value='Etat imprimable' onClick="goPrint('<?php echo $reference;?>');"></td>
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
