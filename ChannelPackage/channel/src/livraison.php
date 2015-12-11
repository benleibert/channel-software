<?php
//Session
session_start();
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:dbuser.php?do=logout");

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
$droitMAJ = $_SESSION['GL_USER']['DROIT']['bde_cde'];


//Message
$msg = '';
if(isset($_SESSION['GL_USER']['MAGASIN']) && $_SESSION['GL_USER']['MAGASIN']==''){
	header('location:home.php?selectedTab=home');
}
elseif(isset($_GET['rs']) && $_GET['rs']==1) { //Ajout
	$text = "Les données ont été ajoutées avec succès";
	$msg = '<div class="okMsg">'.(stripslashes($text)).'</div>';}
elseif(isset($_GET['rs']) && $_GET['rs']==2){ //Modif
	$text = "Les données ont été modifiées avec succès";
	$msg = '<div class="okMsg">'.(stripslashes($text)).'</div>';
}
elseif(isset($_GET['rs']) && $_GET['rs']==3){
	$text = "Les données ont été validées";
	$msg = '<div class="okMsg">'.(stripslashes($text)).'</div>';
}
elseif(isset($_GET['rs']) && $_GET['rs']==4){
	$text = "Les données ont été supprimées";
	$msg = '<div class="okMsg">'.(stripslashes($text)).'</div>';
}

elseif(isset($_GET['rs']) && $_GET['rs']==0){
	$text = "Une erreur s'est produite";
	$msg = '<div class="errorMsg">'.(stripslashes($text)).'</div>';
}

//Numuro page
(isset($_GET['page']) ? $page = $_GET['page'] : $page=1);
//Nbre d'élément par page
(isset($_POST['viewLength'])  ? $_SESSION['GL_USER']['ELEMENT']= $_POST['viewLength']: '');

//
if(isset($_GET['do']) && $_GET['do']=="search") {
	$where ="  livraison.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND ";

	//Date
	(isset($_POST['datelivraison']) && $_POST['datelivraison']!='' ? 	$date1 = $_POST['datelivraison']	: $date1='');
	(isset($_POST['datelivraison1']) && $_POST['datelivraison1']!='' ? 	$date2 = $_POST['datelivraison1']	: $date2='');


	(isset($_POST['exercice']) && $_POST['exercice']!='' 	? 	$where .="livraison.ID_EXERCICE = '".addslashes(trim($_POST['exercice']))."' AND " 	: $where .="");
	(isset($_POST['commande']) && $_POST['commande']!='0' 	? 	$where .="livraison.CODE_COMMANDE = '".addslashes(trim($_POST['commande']))."' AND " 	: $where .="");
	(isset($_POST['reflivraison']) && $_POST['reflivraison']!='' 	? 	$where .="livraison.CODE_LIVRAISON = '".addslashes(trim($_POST['reflivraison']))."' AND " 	: $where .="");
	(isset($_POST['statut']) && $_POST['statut']=='1' 	? 	$where .="livraison.LVR_VALIDE = '".addslashes(trim($_POST['statut']))."' AND " 	: $where .="");
	(isset($_POST['libelle']) && $_POST['libelle']!='' 	? 	$where .="livraison.LVR_LIBELLE = '".addslashes(trim($_POST['libelle']))."' AND " 	: $where .="");

	if($date1!='' && $date2!='') {$where .="livraison.LVR_DATE >= '".addslashes(mysqlFormat(trim($date1)))."' AND livraison.LVR_DATE <= '".addslashes(mysqlFormat(trim($date2)))."' AND ";}
	elseif($date1=='' && $date2!='') {$where .="livraison.LVR_DATE >= '".addslashes(mysqlFormat(trim($date2)))."' AND livraison.LVR_DATE <= '".addslashes(mysqlFormat(trim($date2)))."' AND ";}
	elseif($date1!='' && $date2=='') {$where .="livraison.LVR_DATE >= '".addslashes(mysqlFormat(trim($date1)))."' AND livraison.LVR_DATE <= '".addslashes(mysqlFormat(trim($date1)))."' AND ";}



	if($where != '')  {$where = substr($where,0, strlen($where)-4);
		$_SESSION['WHERE'] = $where;
	}
	elseif($_SESSION['WHERE'] !='') {$where = $_SESSION['WHERE'];}
	$link ='livraison.php?selectedTab=bde&do=search';
	$retour = ligneConLivraison($where,'','', $page, $_SESSION['GL_USER']['ELEMENT']); //$where, $order, $sens, $page=1, $nelt
}
else {
	$link ='livraison.php?selectedTab=bde';
	$retour = ligneConLivraison('livraison.ID_EXERCICE='.$_SESSION['GL_USER']['EXERCICE']."  AND livraison.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."'",'','', $page, $_SESSION['GL_USER']['ELEMENT']); //$where, $order, $sens, $page=1, $nelt
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

function IsValider(ok,i){
	if(ok==1) {
		alert('Cette livraison est validée, impossible de la supprimer, modifier ou valider');
		document.ListingForm.elements[i].checked=false;
	}
	return false;
}


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
		else  { alert('Vous ne pouvez valider qu\'une seule donnée à la fois.');}

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
          <td align="left" class="leftHeader"><?php echo EXBG_MAG; ?></td>
          <td align="right">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table></td>
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
            <td height="20" bgcolor="#FFCC66" class="leftHeader"><?php echo getlang(86); ?> -> <?php echo getlang(246); ?></td>
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
            <form name="pageLengthform" method="POST" action=''>
              <td align="left"><?php echo $msg; ?></td>
              <td align="right"><span class="text"><?php echo getlang(7); ?></span>
                <select name="viewLength"class="selectVerySmall" onchange="document.pageLengthform.submit();">
                  <?php echo $pageLengh; ?>
                </select></td>
              </form>
          </tr>
		  <form name="ListingForm" action="dblivraison.php" method="POST">
          <tr>
            <td width="43%"><?php echo  sousMenuDroit($page='livraison', $tab='bde', $droitMAJ); ?></td>
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
                    <td width="2%" class="header2Bg"><input type="checkbox" name="toggleAll" value="checkbox"  onClick="doMyAction();"></td>
                    <td width="2%" height="25" align="center" valign="middle" class="header2Bg"><?php echo getlang(54); ?></td>
                    <td width="10%" height="25" align="left" valign="middle" class="header2Bg" nowrap="nowrap"><?php echo getlang(35); ?></td>
                    <td width="20%" height="25" align="left" valign="middle" class="header2Bg"><?php echo getlang(82); ?> :</td>
                    <td width="10%" align="left" valign="middle" class="header2Bg"><?php echo getlang(66); ?> :</td>
                    <td width="10%" height="25" align="left" valign="middle" class="header2Bg"><?php echo getlang(30); ?></td>
                    <td width="10%" height="25" align="left" valign="middle" class="header2Bg"><?php echo getlang(45); ?> :</td>
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
        <br/>

          <table border="0" align="right" cellpadding="5" cellspacing="0" class="botBorder">
            <tbody>
              <tr align="left" valign="top">
                <td><input name="Imprimer" type="button" class="button" id="Imprimer"  value='<?php echo getlang(187); ?>'   onclick="OpenBigWin('printlistelivraison.php','');" /></td>
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