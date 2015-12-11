<?php
//Session
session_start();
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:dbuser.php?do=logout");

require_once('../lib/phpfuncLib.php');		//All commun functions
require_once('menus.php');
require_once('funcdonneesannuelles.php');

//Rsest
if(isset($_GET['rst']) && $_GET['rst']==1) {$_SESSION['WHERE']='';}


$droit = getDroit('GRP_PERSONNEL ,GRP_UTILISATEUR, GRP_GROUPE, GRP_LOG,	GRP_CATEGORIE, GRP_PRODUIT,	GRP_CONDITIONNEMENT, GRP_UNITE, GRP_BAREME, GRP_REGION, GRP_PROVINCE, GRP_TYPESERVICE, GRP_SERVICE, GRP_MAGASIN, GRP_RESPONSABLE, GRP_FOURNISSEUR, GRP_TYPEBENEFICIAIRE, GRP_BENEFICIAIRE, GRP_AFFECTATION, GRP_DONNANNUELLE, GRP_EXERCICE, GRP_TYPEDOTATION, GRP_PARAMETRE, GRP_DB', $_SESSION['GL_USER']['GROUPE']);


$droitTOPMENUS = getDroitTOPMENUS( $_SESSION['GL_USER']['GROUPE']);

$droitMAJ = getDroitMAJ('GRP_BENEFICIAIRE', $_SESSION['GL_USER']['GROUPE']);
//Numuro page
(isset($_GET['page']) ? $page = $_GET['page'] : $page=1);
//Nbre d'�l�ment par page
(isset($_POST['viewLength'])  ? $_SESSION['GL_USER']['ELEMENT']= $_POST['viewLength']: '');

//
if(isset($_GET['do']) && $_GET['do']=="detail") {
	$where ="";
		//(isset($_GET['xid']) && $_GET['xid']!='' 	? 	$where .="donnee_annuelle.ID_EXERCICE =".addslashes(trim($_GET['xid']))."  AND " 	: $where .="");
		
	if($where != '')  {$where = substr($where,0, strlen($where)-4);
		$_SESSION['WHERE'] = $where;
	}
	elseif($_SESSION['WHERE'] !='') {$where = $_SESSION['WHERE'];}
	$link ='detaildonnees.php?selectedTab=par&do=detail';
	$retour = ligneDetailDonnees($where,'','', $page, $_SESSION['GL_USER']['ELEMENT']); //$where, $order, $sens, $page=1, $nelt
}
elseif(isset($_GET['do']) && $_GET['do']=="search")  {
	$link ='detaildonnees.php?selectedTab=par&do=search';
	$retour = ligneDetailDonnees('donnee_annuelle.ID_EXERCICE='.$_SESSION['GL_USER']['EXERCICE'],'','', $page, $_SESSION['GL_USER']['ELEMENT']); //$where, $order, $sens, $page=1, $nelt
}


$link ='detaildonnees.php?selectedTab=par';
$retour = ligneDetailDonnees('','', '',$page, $_SESSION['GL_USER']['ELEMENT']); //$where, $order, $sens, $page=1, $nelt


$pageLengh = pageLengh($_SESSION['GL_USER']['ELEMENT']);
$affichage = $retour['L'];
//Page
$barre= page($retour['NE'], $_SESSION['GL_USER']['ELEMENT'], $page, $link);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<META HTTP-EQUIV="refresh" CONTENT="" >
<title><?php echo TITLE; ?></title>
<LINK REL="SHORTCUT ICON" HREF="../images/favicon0.ico">
<link href="../css/neutralcss.css" rel="stylesheet" type="text/css">
<link href="../lib/JQuerySpinBtn.css" rel="stylesheet" type="text/css">
<link href="../lib/jquery.alerts.css" rel="stylesheet" type="text/css">
<!-- Begin of JS code  -->
<script type="text/javascript" src="../lib/jquery.js"></script>
<script type="text/javascript" src="../lib/jslib.js"></script>

<!-- Begin of JS code  -->
<script type="text/javascript" src="../lib/jquery.js"></script>
<script type="text/javascript" src="../lib/jslib.js"></script>
<script type="text/javascript" src="../lib/jsfuncLib.js"></script>

<script langage="javascript">
function IsLink(ok,prd){
	if(ok==1) {
		alert('Impossible de supprimer le b�n�ficiaire <'+prd+'>, il est li� � une autre donn�e\n qu\'il faut au pr�alable supprimer');
		document.ListingForm.elements[i].checked=false;
	}
	return false;
}


function doMyAction(myform){
	if(document.ListingForm.toggleAll.checked == true){
	//alert(document.ListingForm.elements.length);
		for (i = 0; i < document.ListingForm.elements.length; i++) {
       		document.ListingForm.elements[i].checked=true;
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
		else  { ret = confirm('Vous ne pouvez modifier qu\'une seule donn�e � la fois.');}

	}
	else alert('Aucun �l�ment s�lectionn�');
}

function msgSuppress(){
	var ret;
	var j=0;
	for (i = 0; i < document.ListingForm.elements.length; i++) {
       		if(document.ListingForm.elements[i].checked==true){
				var myString = document.ListingForm.elements[i].value;
				var mySplit = myString.split("@");
				ok = mySplit[1];
				prd = mySplit[2];
				if(ok==0) j++; //Not use
				IsLink(ok,prd);
			}
    }
	if(j>0){
		if(j==1){ ret = confirm('Voulez-vous supprimer cette donn�e?');}
		else    { ret = confirm('Voulez-vous supprimer ces donn�es?');}
		if(ret==true) {
			document.ListingForm.myaction.value="delete";
			document.ListingForm.submit();
		}
	}
	else alert('Aucun �l�ment s�lectionn�');
}

</script>

<script type="text/javascript">
function updateBasket(etab, effectif, rationnel){ 
	var xhr = getXhr();
	// On d�fini ce qu'on va faire quand on aura la r�ponse
	xhr.onreadystatechange = function(){
		// On ne fait quelque chose que si on a tout re�u et que le serveur est ok
		if(xhr.readyState == 4 && xhr.status == 200){
			retour = xhr.responseText;
			//On se sert de innerHTML pour rajouter les options a la liste
			//document.getElementById('msg').innerHTML = retour;
			if(retour!='' && retour==1) {
				alert('Les donn�es ont �t� enregistr�es avec succ�s');
			}
		}
	}

	// Ici on va voir comment faire du post
	xhr.open("POST","dbdonnees.php?do=save",true);
	// ne pas oublier �a pour le post
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	// ne pas oublier de poster les arguments
	// ici, l'id de l'auteur
	//id = document.getElementById('codecategorie').value;
	xhr.send("etab="+etab+"&effectif="+effectif+"&rationnel="+rationnel);
}


function validateForm(){
	document.ListingForm.submit();
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
            <td width="180"><?php
			$selectedTab = $_GET['selectedTab'];
			echo parametersMenus($selectedTab , $droit)
			//echo menus($selectedTab, $_SESSION['GL_USER']['EXERCICE']);
			?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
    <td width="85%" height="80%" align="left" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td><table width="100%" border="0" align="left" cellpadding="1" cellspacing="1">
          <tr>
            <td height="20" bgcolor="#FFCC66" class="leftHeader"><?php echo getlang(106); ?> &rarr; Les donn&eacute;es annuelles</td>
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
              <td colspan="2" align="right"><span class="text"><?php echo getlang(7); ?></span>
                    <select name="viewLength"class="selectVerySmall" onChange="document.pageLengthform.submit();">
                      <?php echo $pageLengh; ?>
                    </select>
              </td>
			</form>
          </tr>
		  <form name="ListingForm" action="dbbeneficiaire.php" method="POST">
          <tr>
            <td width="43%">&nbsp;</td>
            <td nowrap align="right" class="text"><?php echo  $barre; ?></td>
          </tr>
          <!-- Data Tbale contener -->
          <tr>
            <td colspan=3 align="left" valign="top">
			      <!-- Data Table  -->
                <table width="100%" border="0" cellpadding="2" cellspacing="1" class="tableBorder">
                  <!-- Begin Table Header -->
                  <tr class="header2">
                    <td width="2%" align="center" valign="middle" class="header2Bg">N&deg;</td>
                    <td width="20%" height="25" align="left" valign="middle" class="header2Bg"><?php echo getlang(16); ?></td>
                    <td width="20%" height="25" align="left" valign="middle" class="header2Bg">&nbsp;&nbsp;Type<?php echo getlang(16); ?></td>
                    <td width="15%" align="left" valign="middle" class="header2Bg"><?php echo getlang(152); ?></td>
                    <td width="5%" height="25" align="center" valign="middle" class="header2Bg">Effectif</td>
                    <td width="5%" align="center" valign="middle" class="header2Bg">Rationnels</td>
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
        </table></td>
      </tr>
    </table>
    <table border="0" align="right" cellpadding="5" cellspacing="0" class="botBorder">
                  <tbody>
                    <tr align="left" valign="top">
                      <td><input name="Enregistrer2" type="button" class="button" id="Enregistrer2"  value='<?php echo getlang(187); ?>'   onclick="OpenBigWin('printdetaildonnes.php','');" /></td>
                    </tr>
                  </tbody>
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
