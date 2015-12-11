<?php
require_once('../channel/lib/phpfuncLib.php');
$msg ='';
if(isset($_GET['rs']) && $_GET['rs']==1) {$msg = '<div >Compte et mot de passe invalide.</div>';}
//echo $_POST['pword'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo TITLE; ?></title>

<script type="text/javascript" src="../channel/lib/jquery.js"></script>
<script type="text/javascript" src="../channel/lib/jsfuncLib.js"></script>

<link href="../channel/css/neutralcss.css" rel="stylesheet" type="text/css">
<!-- BEGIN Validate form js code -->
<script language="javascript">
function validateForm(){
        var userName = trimAll(document.stocksForm.userName.value);
        var pword = trimAll(document.stocksForm.pword.value);
		var msg ='';
        if(userName == "") {
                msg +=' - Veuillez saisir votre nom d\'utilisateur s\'il vous plaît.\n';
        }
        if(pword == "") {
                msg +=' - Veuillez saisir votre mot de passe s\'il vous plaît.\n';
        }
       	if(msg !=''){
			alert(msg);
		}
		else {
 			document.stocksForm.submit();
        }
}
</script>
<!-- END Validate form js code -->

<style type="text/css">
<!--
.Style13 {
	color: #FFFFFF;
	font-size: 0.70pc;
	font-weight: bold;
	text-decoration: none;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
-->
</style>
</head>

<body class="bgcol">
<table width="100%" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="middle"><div class="homeLoginPage">
    <div id="formPosition">
    <form name="stocksForm" id="stocksForm" action="../channel/src/dbuser.php?do=login" method="post">
      <div align="center"></div>
      <table width="629"  height="338" border="0" cellspacing="2" cellpadding="0">
        <tr>
          <td width="227" height="55" class="homtTextBold">&nbsp;</td>
          <td colspan="2" class="homtTextBold">&nbsp;</td>
          <td width="81" class="homtTextBold">&nbsp;</td>
          <td width="11" class="homtTextBold">&nbsp;</td>
          <td width="2" class="homtTextBold">&nbsp;</td>
          <td width="42">&nbsp;</td>
        </tr>
        <tr>
          <td height="60" class="homtTextBold">&nbsp;</td>
          <td colspan="3" class="homtTextBold"><div align="left"><img src="images/compte.jpg" width="297" height="59" /></div></td>
          <td class="homtTextBold">&nbsp;</td>
          <td class="homtTextBold">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="37" class="homtTextBold">&nbsp;</td>
          <td width="120" class="homtTextBold"><input name="userName" type="text" id="userName" size="20" maxlength="10" class="onText" value="" placeholder="" title="Compte utilisateur / user Account  / Conta do utilizador"/></td>
          <td width="108" class="homtTextBold"><input name="pword" type="password" id="pword" size="18" maxlength="20" class="onText" placeholder="" title="Mot de passe / Password / Palavra passe " /></td>
          <td class="homtTextBold"><div align="center"><img src="images/connecter.jpg" width="80" height="28"  onclick="validateForm();" title="Connecter / Login / Entrar "/></div></td>
          <td class="homtTextBold">&nbsp;</td>
          <td class="homtTextBold">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="19" class="homtTextBold">&nbsp;</td>
          <td class="homtTextBold">&nbsp;</td>
          <td class="homtTextBold">&nbsp;</td>
          <td class="homtTextBold">&nbsp;</td>
          <td class="homtTextBold">&nbsp;</td>
          <td class="homtTextBold">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="19" class="homtTextBold">&nbsp;</td>
          <td colspan="2" rowspan="2" class="homtTextBold">&nbsp;</td>
          <td class="homtTextBold">&nbsp;</td>
          <td class="homtTextBold">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="37" class="homtTextBold">&nbsp;</td>
          <td class="homtTextBold">&nbsp;</td>
          <td class="homtTextBold">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="27" colspan="7" class="homtTextBold"><div align="center"><?php echo $msg; ?></div></td>
          </tr>

        <tr>
          <td height="19" class="homtTextBold">&nbsp;</td>
          <td colspan="2" class="homtTextBold">&nbsp;</td>
          <td class="homtTextBold">&nbsp;</td>
          <td class="homtTextBold">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="7" class="homtTextBold"><div align="center"></div></td>
        </tr>
      </table>
      <div align="center"></div>
    </form>
	</div>
    </div></td>
  </tr>
</table>
</body>
</html>
