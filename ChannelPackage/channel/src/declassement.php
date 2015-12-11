<?php
//Session
session_start();
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:dbuser.php?do=logout");

require_once('../lib/phpfuncLib.php');		//All commun functions
require_once('menus.php');
require_once('funcdeclassement.php');

//Top Menu
$selectedTab = $_GET['selectedTab'];
$menu = topMenus($selectedTab,$_SESSION['GL_USER']['DROIT']);

//Left Menu
$leftMenu = bonsortieMenus($selectedTab , $_SESSION['GL_USER']['DROIT']);

//DOIT MAJ
$droitMAJ = $_SESSION['GL_USER']['DROIT']['bds_dec'];

//Message
$msg = '';
if(isset($_SESSION['GL_USER']['MAGASIN']) && $_SESSION['GL_USER']['MAGASIN']==''){
	header('location:home.php?selectedTab=home');
}
elseif(isset($_GET['rs']) && $_GET['rs']==1) { //Ajout
	$text =getlang(373);
	$msg = '<div class="okMsg">'.(stripslashes($text)).'</div>';}
elseif(isset($_GET['rs']) && $_GET['rs']==2){ //Modif
	$text =getlang(372);
	$msg = '<div class="okMsg">'.(stripslashes($text)).'</div>';
}
elseif(isset($_GET['rs']) && $_GET['rs']==3){
	$text =getlang(371);
	$msg = '<div class="okMsg">'.(stripslashes($text)).'</div>';
}
elseif(isset($_GET['rs']) && $_GET['rs']==4){
	$text =getlang(370);
	$msg = '<div class="okMsg">'.(stripslashes($text)).'</div>';
}

elseif(isset($_GET['rs']) && $_GET['rs']==0){
	$text =getlang(374);
	$msg = '<div class="errorMsg">'.(stripslashes($text)).'</div>';
}

//Numuro page
(isset($_GET['page']) ? $page = $_GET['page'] : $page=1);
//Nbre d'élément par page
(isset($_POST['viewLength'])  ? $_SESSION['GL_USER']['ELEMENT']= $_POST['viewLength']: '');

//
if(isset($_GET['do']) && $_GET['do']=="search") {
		//Date
	(isset($_POST['datedeclassement']) && $_POST['datedeclassement']!='' ? 	$date1 = $_POST['datedeclassement']	: $date1='');
	(isset($_POST['datedeclassement1']) && $_POST['datedeclassement1']!='' ? 	$date2 = $_POST['datedeclassement1']	: $date2='');

	$where ="  CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND ";

	(isset($_POST['exercice']) && $_POST['exercice']!='' 	? 	$where .="declass.ID_EXERCICE = '".addslashes(trim($_POST['exercice']))."' AND " 	: $where .="");

	(isset($_POST['codedeclassement']) && $_POST['codedeclassement']!='' 	? 	$where .="declass.CODE_DECLAS LIKE '".addslashes(trim($_POST['codedeclassement']))."' AND " 	: $where .="");

	if($date1!='' && $date2!='') {$where .="declass.DCL_DATE >= '".addslashes(mysqlFormat(trim($date1)))."' AND declass.DCL_DATE <= '".addslashes(mysqlFormat(trim($date2)))."' AND ";}
	elseif($date1=='' && $date2!='') {$where .="declass.DCL_DATE >= '".addslashes(mysqlFormat(trim($date2)))."' AND declass.DCL_DATE <= '".addslashes(mysqlFormat(trim($date2)))."' AND ";}
	elseif($date1!='' && $date2=='') {$where .="declass.DCL_DATE >= '".addslashes(mysqlFormat(trim($date1)))."' AND declass.DCL_DATE <= '".addslashes(mysqlFormat(trim($date1)))."' AND ";}

	(isset($_POST['raison']) && $_POST['raison']!='' 			? 	$where .="declass.DCL_RAISON LIKE '".addslashes(trim($_POST['raison']))."%' AND " 	: $where .="");
	(isset($_POST['statut']) && $_POST['statut']=='1' 	? 	$where .="declass.DCL_VALIDE = '".addslashes(trim($_POST['statut']))."' AND " 	: $where .="");

	if($where != '')  {$where = substr($where,0, strlen($where)-4);
		$_SESSION['WHERE'] = $where;
	}
	elseif($_SESSION['WHERE'] !='') {$where = $_SESSION['WHERE'];}
	$link ='declassement.php?selectedTab=cde&do=search';
	$retour = ligneConDeclassement($where,'','', $page, $_SESSION['GL_USER']['ELEMENT']); //$where, $order, $sens, $page=1, $nelt
}
else {
	$link ='declassement.php?selectedTab=cde';
	$retour = ligneConDeclassement('declass.ID_EXERCICE='.$_SESSION['GL_USER']['EXERCICE']." AND CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."'",'','', $page, $_SESSION['GL_USER']['ELEMENT']); //$where, $order, $sens, $page=1, $nelt
}

$pageLengh = pageLengh($_SESSION['GL_USER']['ELEMENT']);
$affichage = $retour['L'];
//Page
$barre= page($retour['NE'], $_SESSION['GL_USER']['ELEMENT'], $page, $link);

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
<script langage="javascript">

function doMyAction(myform){
	var mylist = '';
	if(document.ListingForm.toggleAll.checked == true){
	//alert(document.ListingForm.elements.length);
		for (i = 0; i < document.ListingForm.elements.length; i++) {
       		document.ListingForm.elements[i].checked=true;
			var myString = document.ListingForm.elements[i].value;
			var mySplit = myString.split("@");
			if(mySplit[1]==1){
				var k = mySplit[2]*1-5;
				mylist +=' ligne '+k+', ';
				document.ListingForm.elements[i].checked=false;
			}
    	}
		if(mylist!='') alert('Impossible de modifier ou supprimer '+mylist+ 'déjà validée');
	}
	if(document.ListingForm.toggleAll.checked == false){
	//alert(document.ListingForm.elements.length);
		for (i = 0; i < document.ListingForm.elements.length; i++) {
       		document.ListingForm.elements[i].checked=false;
    	}
	}
    return false;
}

function IsValider(ok,i){
	if(ok==1) {
		alert('Cette perte est validée, impossible de la supprimer, modifier ou valider');
		document.ListingForm.elements[i].checked=false;
	}
	return false;
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
		if(j==1){
			document.ListingForm.myaction.value="edit";
			document.ListingForm.submit();
		}
		else  { ret = confirm('Vous ne pouvez modifier qu\'une seule donnée à la fois.');}

	}
	else alert('Aucun élément sélectionné');
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
		if(j==1){ ret = confirm('Voulez-vous supprimer cette donnée?');}
		else    { ret = confirm('Voulez-vous supprimer ces données?');}
		if(ret==true) {
			document.ListingForm.myaction.value="delete";
			document.ListingForm.submit();
		}
	}
	else alert('Aucun élément sélectionné');
}

 function msgValid(){
	var ret;
	var j=0;
	for (i = 0; i < document.ListingForm.elements.length; i++) {
       		if(document.ListingForm.elements[i].checked==true){
				j++;
			}
    }
	if(j>0){
		if(j==1){
			document.ListingForm.myaction.value="validate";
			document.ListingForm.submit();
		}
		else  { ret = confirm('Vous ne pouvez valider qu\'une seule donnée à la fois.');}

	}
	else alert('Aucun élément sélectionné');
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
            
            <td align="left" class="leftHeader">
            <?php echo EXBG_MAG; ?> </td>
            <td align="right">&nbsp;</td>
            <td>&nbsp;</td>
				<td>&nbsp;</td>
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
        <td><table width="100%" border="0" align="left" cellpadding="1" cellspacing="1">
          <tr>
            <td width="43" bgcolor="#FFCC66" class="leftHeader"><?php echo getlang(271); ?> </td>
          </tr>
          <tr>
            <td align="left" valign="top" height="3"></td>
          </tr>

          <tr>
            <td align="left" valign="top" class="text">
            <!--  Debut affichage -->
            <table width="100%"  border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td><table width="100%" border="0" align="left" cellpadding="1" cellspacing="1">

          <tr>
             <form name="pageLengthform" method="POST" action=''><td align="left"><?php echo $msg; ?></td>
            <td align="right"><span class="text"><?php echo getlang(7); ?></span>
              <select name="viewLength"class="selectVerySmall" onchange="document.pageLengthform.submit();">
                <?php echo $pageLengh; ?>
              </select></td>
           
              </form>
          </tr>
		  <form name="ListingForm" action="../src/dbdeclassement.php" method="POST">
          <tr>
            <td width="43%"><?php echo  sousMenuDroit($page='declassement', $tab='bds', $droitMAJ); ?></td>
            <td nowrap align="right" class="text"><?php echo  $barre; ?></td>
          </tr>
          <!-- Data Tbale contener -->
          <tr>
            <td colspan=3 align="left" valign="top">
			      <!-- Data Table  -->
                <table width="100%" border="0" cellpadding="2" cellspacing="1" class="tableBorder">
                  <!-- Begin Table Header -->
                  <tr class="header2">
                    <td width="2%" align="center" valign="middle" class="header2Bg">#</td>
                    <td width="2%" class="header2Bg"><input type="checkbox" name="toggleAll" value="checkbox" onClick="doMyAction();"></td>
                    <td width="5%" height="25" align="center" valign="middle" class="header2Bg" nowrap>...</td>
                    <td width="10%" height="25" align="center" valign="middle" class="header2Bg"><?php echo getlang(31); ?> </td>
                    <td width="10%" height="25" align="left" valign="middle" class="header2Bg"><?php echo getlang(42); ?> </td>
                    <td width="35%" height="25" align="left" valign="middle" class="header2Bg"><?php echo getlang(99); ?> </td>
                    <td width="10%" height="25" align="center" valign="middle" class="header2Bg">...</td>
                   </tr>
                  <!-- Begin Table row -->
                  <?php echo $affichage;?>
                  <!-- End Table row -->
                </table>
                <!-- End Data Table -->
				<input name="exercicecourant" type="hidden" id="exercicecourant" value="<?php echo $_SESSION['GL_USER']['EXERCICE']; ?>">
									  <input name="statusexercice" type="hidden" id="statusexercice" value="<?php echo $_SESSION['GL_USER']['STATUT_EXERCICE']; ?>">

                              <input name='myaction' type='hidden' id="myaction" value='' /></td>
          </tr></form>
        </table>
          <table border="0" align="right" cellpadding="5" cellspacing="0" class="botBorder">
            <tbody>
              <tr align="left" valign="top">
                <td><input name="Enregistrer2" type="button" class="button" id="Enregistrer2"  value='<?php echo getlang(187); ?>'   onclick="OpenBigWin('printlistdeclassement.php','');" /></td>
              </tr>
            </tbody>
          </table></td>
      </tr>
    </table>
          <!-- Fin Affichage -->
          </tr>
          <!-- Data Tbale contener -->
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
