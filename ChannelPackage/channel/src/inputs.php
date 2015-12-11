<?php
//Session
session_start();
require_once('../src/topmenus.php');				//The menubar
require_once('../lib/phpfuncLib.php');		//All commun functions
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:phpfuncindex.php?myaction=LOGOUT");
//Num page
(isset($_GET['page']) ? $page = $_GET['page'] : $page=1);
//Nbre d'élément par page
if(isset($_POST['viewLength']))  $_SESSION['GL_USER']['ELEMENT']= $_POST['viewLength'];//
if(isset($_POST['myaction']) && $_POST['myaction']=="SEARCH") {
	(isset($_POST['reference']) 	? $xreference 		= $_POST['reference'] 	: $xreference 	= '');
	(isset($_POST['dateAjout']) 	? $xdateAjout 		= $_POST['dateAjout'] 	: $xdateAjout 	= '');
	(isset($_POST['libelle']) 	? $xlibelle 		= $_POST['libelle'] 	: $xlibelle 	= '');
	$retour = lignSearchBentree($xreference,$xdateAjout,$xlibelle,'', $page, $_SESSION['GL_USER']['ELEMENT']);
}
else $retour = lignConBentree('', '', '', '',$page, $_SESSION['GL_USER']['ELEMENT']);
//Generateur liste nbre éléments pa page
$pageLengh = pageLengh($_SESSION['GL_USER']['ELEMENT']);
//Affichage standard
$lignConBentree = $retour['L'];
//Barre
$barre= page($retour['NE'], $_SESSION['GL_USER']['ELEMENT'], $page, 'inputs.php?selectedTab=inputs');
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
<!-- Begin of JS code  -->
<script type="text/javascript" src="../lib/jquery.js"></script>
<script type="text/javascript" src="../lib/jsfuncLib.js"></script>
<script langage="javascript">
function go(valeur, ok){
	var xhr = getXhr();
	// On défini ce qu'on va faire quand on aura la réponse
	xhr.onreadystatechange = function(){
		// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
		if(xhr.readyState == 4 && xhr.status == 200){
			retour = xhr.responseText;
			// On se sert de innerHTML pour rajouter les options a la liste
			//document.getElementById('msg').innerHTML = retour;
			if(retour==1 && document.ListingForm.elements[ok].checked==true){
				alert('Impossible de supprimer ou modifier ce bon d\'entrée, il a été validé.');
				document.ListingForm.elements[ok].checked=false;
			}
		}
	}

	// Ici on va voir comment faire du post
	xhr.open("POST","phpfuncbentrees.php?test=VALIDER",true);
	// ne pas oublier ça pour le post
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	// ne pas oublier de poster les arguments
	// ici, l'id de l'auteur
	//id = document.getElementById('codeRegion').value;
	xhr.send("code="+valeur);
}

function openPg(str){
	if(document.ListingForm.statusexercice.value ==1) {alert('Cet exercice est clôturé. Aucune suppression n\'est possible');}
		else window.location.href=str
}

function doMyAction(myform){
	if(document.ListingForm.toggleAll.checked == true){
	//alert(document.ListingForm.elements.length);
		for (i = 0; i < document.ListingForm.elements.length; i++) {
       		document.ListingForm.elements[i].checked=true;
			var x= go(document.ListingForm.elements[i].value, i);
    	}
	}
	if(document.ListingForm.toggleAll.checked == false){
	//alert(document.ListingForm.elements.length);
		for (i = 0; i < document.ListingForm.elements.length; i++) {
       		document.ListingForm.elements[i].checked=false;
    	}
	}	
    return false;
}


function msgSuppress(){
	var ret;
	var j=0; 
	
	for (i = 0; i < document.ListingForm.elements.length; i++) {
       		if(document.ListingForm.elements[i].checked==true){
				j++;
			}
    }
	if(j>0){
		if(document.ListingForm.statusexercice.value ==1) {alert('Cet exercice est clôturé. Aucune suppression n\'est possible');}
		else {
			if(j==1){ ret = confirm('Voulez-vous supprimer cette donnée?');}
			else    { ret = confirm('Voulez-vous supprimer ces données?');}
			if(ret==true) {
				document.ListingForm.myaction.value="DEL";
				document.ListingForm.submit();
			}
		}	
	}
	else alert('Aucun élément sélectionné');
}

function msgModif(){
	var ret;
	var j=0; 
	for (i = 0; i < document.ListingForm.elements.length; i++) {
       		if(document.ListingForm.elements[i].checked==true){
				j++;
			}
    }
	if(j>0){
		if(document.ListingForm.statusexercice.value ==1) {alert('Cet exercice est clôturé. Aucune modification n\'est possible');}
		else {
			if(j==1){ 
				document.ListingForm.myaction.value="EDIT";
				document.ListingForm.submit();
			}
			else  { alert('Vous ne pouvez modifier qu\'une seule donnée à la fois.');}
		}
	}
	else alert('Aucun élément sélectionné');
}

function openPg(str){
	if(document.ListingForm.statusexercice.value ==1) {alert('Cet exercice est clôturé. Aucune suppression n\'est possible');}
	else window.location.href=str
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
      <td width="200" rowspan=2>
              <img src="../images/unfpa.png" alt="" width="95" height="45" hspace=2 vspace=0 />
        </td>
          <td height="24" colspan="2" align="right" valign=top><span class="wtext">
          <a href="http://www.econsulting.bf/forums/index.php?id=STOCKS" target="_blank"><img src="../images/forums00.gif" border="0" hspace="3" align="middle" />Forums</a> <span class=white> </span>
          <a href="" onClick=""><img src="../images/talkback.gif" border="0" hspace="3" align="middle" />R&eacute;agir</a><span class=white> </span>            <span class=white> </span>
          <a href="" onClick="javascript:window.open('aide/index.html','','')"><img src="../images/help0000.gif" border="0" hspace="3" align="middle" />Aide</a> <span class=white> </span>
          <a href="" onClick="JavaScript:window.open('/about.php','A propos','left=500,top=100,width=350,height=300')" ><img src="../images/about000.gif" border="0" hspace="3" align="middle" />A propos</a>&nbsp;&nbsp;
	  </td>
      </tr>
          <tr> 
            <td height="20" valign="top"><?php 
			  $selectedTab = $_GET['selectedTab'];
			  echo topMenus($selectedTab,$droitTOPMENUS);
			  ?>
            <td align="right">
           <span class="wtext">
	  <a href="" onClick="JavaScript:window.open('/webclient/common/jsp/registerDialog.jsp?UserType=R','License','left=500,top=100,width=500,height=275')" >License</a>
	   <span class=white> &nbsp;| &nbsp;</span>
	   <a href="../src/disconnect.php">GL_USER [Déconnexion]</a></span>&nbsp;</td>
        </tr>
      </table>
    </td>
</tr>
    <tr class="searchBg">
      <td height="21" align="center">

	 <table border="0"cellspacing="0" cellpadding="0">
          <tr>
            
            <td align="right">&nbsp;
            	</td>
            <td></td>
				<input type="hidden" name="selectedLink" value="">
				<input type="hidden" name="selectedTab" value="Network Database"></td>
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
        <td><table width="100%" border="0" align="left" cellpadding="1" cellspacing="1">
          <tr>
            <td height="20" colspan="3" class="leftHeader">Bons d'entr&eacute;e</td>
          </tr>
          <tr>
            <td colspan=3 align="left" valign="top" height="3"></td>
          </tr>
          <tr>
            <form name="pageLengthform" method="POST" action=''>
              <td colspan="2" align="right"><span class="text"><?php echo getlang(7); ?></span>
                    <select name="viewLength"class="selectVerySmall" onChange="document.pageLengthform.submit();">
                      <?php echo $pageLengh; ?>
                    </select>
              </td>
			</form>
          </tr>
		  <form name="ListingForm" action="../src/phpfuncbentrees.php" method="POST">
          <tr>
            <td width="43%"><table border="0" align="left" cellpadding="0" cellspacing="4">
                <tr>
                    <td><input name="AddButton" type="button" class="button" value="Ajouter" onClick="openPg('addbonentree.php?selectedTab=inputs');"></td>
                    <td><input name="DeleteButton" type="button" class="button" value="Supprimer" onClick="msgSuppress();"></td>
                    <td><input name="EditButton" type="button" class="button" value="Modifier" onClick="msgModif();"></td>
                    <td><input name="AddButton" type="button" class="button" value="<?php echo getlang(139); ?>" onClick="openPage('searchbonentree.php?selectedTab=inputs');"></td>
                    <td>&nbsp;<input name="myaction" type="hidden" id="myaction" value=''></td>
                </tr>
            </table></td>
            <td nowrap align="right" class="text"><?php echo $barre; ?></td>
          </tr>
          <!-- Data Tbale contener -->
          <tr>
            <td colspan=3 align="left" valign="top">
                <!-- Data Table  -->
                <table width="100%" border="0" cellpadding="0" cellspacing="1" class="tableBorder">
                  <!-- Begin Table Header -->
                  <tr class="header2">
                    <td width="3%" class="header2Bg"><input type="checkbox" name="toggleAll" value="checkbox" onClick="doMyAction();"></td>
                    <td width="2%" class="header2Bg" align="center">...</td>
					<td width="6%" height="25" align="center" valign="middle" class="header2Bg">R&eacute;f&eacute;rence</td>
                    <td width="12%" height="25" align="center" valign="middle" class="header2Bg"><?php echo getlang(42); ?></td>
                    <td width="50%" height="25" align="left" valign="middle" class="header2Bg" nowrap>&nbsp;&nbsp;Libell&eacute;</td>
                    <td width="5%" height="25" align="left" valign="middle" class="header2Bg" nowrap>&nbsp;&nbsp;Articles</td>
                  </tr>
                  <!-- End Table Header -->
                  <!-- Begin Table row -->
                  <?php echo $lignConBentree;?>
                  <!-- End Table row -->
                </table>
                <!-- End Data Table -->
				<input name="exercicecourant" type="hidden" id="exercicecourant" value="<?php echo $_SESSION['GL_USER']['EXERCICE']; ?>">
						<input name="statusexercice" type="hidden" id="statusexercice" value="<?php echo $_SESSION['GL_USER']['STATUT_EXERCICE']; ?>">
            </td>
          </tr></form>
			
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
