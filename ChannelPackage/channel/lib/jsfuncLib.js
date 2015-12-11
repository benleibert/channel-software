/* $Id: validation.js,v 1.5 2005/05/11 11:05:33 sureshbabu Exp $ */

function trimAll(str)
{
        /*************************************************************
        Input Parameter :str
        Purpose         : remove all white spaces in front and back of string
        Return          : str without white spaces
        ***************************************************************/

        //check for all spaces
        var objRegExp =/^(\s*)$/;
        if (objRegExp.test(str))
        {
                str = str.replace(objRegExp,'');
                if (str.length == 0)
                return str;
        }

        // check for leading and trailling spaces
        objRegExp = /^(\s*)([\W\w]*)(\b\s*$)/;
        if(objRegExp.test(str))
        {
                str = str.replace(objRegExp, '$2');
        }
        return str;
}

function isEmailId(str)
{
   var objRegExp  = /^[a-z0-9]([a-z0-9_\-\.]*)@([a-z0-9_\-\.]*)(\.[a-z]{2,3}(\.[a-z]{2}){0,2})$/i;
   return objRegExp.test(str); 
}

function isPositiveInteger(str)
{
        /*************************************************************
        Input Parameter :str
        Purpose         : to check whether given str is a positive integer
        Return          : true / false
        ***************************************************************/

	number = trimAll(str);
    if(number=="") return false;
	if (isNaN(number))
	{
		return false;
	}
	else
	{
		if (number < 0)
		{
			return false;
		}
	}
	return true;
}

function ValidateMAC(mac){

       /*************************************************************
        Input Parameter : MAC addr string : FF-FF-FF-FF-FF-FF
        Purpose         : 1. to check whether given str is confirm to the abv format.
                          2. Each character is a hexa value.      
                          3. check length is 12+5.
        Return          : true / false
        ***************************************************************/

  if(mac.value =="") {
      return true; 
  }
  //check the valid digit
  var validchars = "0123456789ABCDEFabcdef-";
  var seperator = "-";
  
  var i,cc,y,cy;
  
  if (mac.value.length != 17)
  {
        alert("Invalid MAC Address specified. Format = FF-FF-FF-FF-FF-FF");
        mac.value = "";
        mac.focus();
        document.body.style.cursor = "hand";            
        return false;  
  }
  
  y = 2;
  for (i=0; i < mac.value.length; i++) {
    cc = mac.value.charAt(i);
    cy = mac.value.charAt(y);
    if (seperator.indexOf(cy) == -1)
    {
          alert("Invalid MAC Address specified. Format = FF-FF-FF-FF-FF-FF");
          mac.value = "";
          mac.focus();
          document.body.style.cursor = "hand";          
          return false;
    }
    if (validchars.indexOf(cc) != -1)
      continue;
    else {
      alert("Invalid MAC Address specified. Format = FF-FF-FF-FF-FF-FF");
      mac.value = "";
      mac.focus();
      document.body.style.cursor = "hand";              
      return false;
    }
    y +=2;
  }

  return true;    
}

//Determine if a string is blank
function isEmpty(str)
{
  if (str.search(/\S/) == -1)
    return true
  return false
}

//Determine, string is a number
function isNumber(str)
{
  if (str.search(/^\s*\d+\s*$/) != -1) {
    //non-zero numbers starting with 0 may be interpreted by IOS as octal.
    //Disallow them to avoid surprises one way or the other.
    if (str.search(/^\s*0+[1-9]/) != -1)
      return false
    else
      return true
  }
  return false
}

//Determine, string is an oct value
function isOctStr(str)
{
  if (str.search(/[^0-7]/) == -1)
    return true
  return false
}

//Determine, string is a hexa value
function isHexStr(str)
{
  if (str.search(/[^0-9a-fA-F]/) == -1)
    return true
  return false
}

function isValidIP(str)
{

   if (str.search(/^(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])$/) == -1)
            return false; 
        
  return true
}

//Determine, string is a hexa value
function isASCIIStr(str)
{
  if (str.search(/[^0-9a-zA-Z]/) == -1)
    return true
  return false
}

function OpenWin(url, titre){
	var width1=450;
	var height1=350;
	var left1 = Math.floor( (screen.width - width1) / 2);
    var top1 = Math.floor( (screen.height - height1) / 2);
	var c = 'width='+width1+',height='+height1+',top='+top1+',left='+left1+',';
	window.open(url,titre,c+'scrollbars=yes,location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=no');
}

function OpenBigWin(url, titre){
	var width1=screen.width -300;
	var height1=screen.height-300;
	var left1 = Math.floor( (screen.width - width1) / 2);
    var top1 = Math.floor( (screen.height - height1) / 2);
	var c = 'width='+width1+',height='+height1+',top='+top1+',left='+left1+',';
	window.open(url,titre,c+'scrollbars=yes,location=no,directories=no,status=no,menubar=yes,toolbar=no,resizable=no');
}


function getXhr(){
	var xhr = null; 
	if(window.XMLHttpRequest) // Firefox et autres
		xhr = new XMLHttpRequest(); 
	else if(window.ActiveXObject){ // Internet Explorer 
		try {
			xhr = new ActiveXObject("Msxml2.XMLHTTP");
		} 
		catch (e) {
			xhr = new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	else { // XMLHttpRequest non supporté par le navigateur 
	   alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest..."); 
	   xhr = false; 
	} 
    return xhr;
}

function openPage(str){
	window.location.href=str
}

function checkDate(field)
  {
    var allowBlank = true;
    var minYear = 1900;
    var maxYear = (new Date()).getFullYear();
	var errorMsg = "";

    // regular expression to match required date format
    re = /^(\d{1,2})\/(\d{1,2})\/(\d{4})$/;

    if(field.value != '') {
      if(regs = field.value.match(re)) {
        if(regs[1] < 1 || regs[1] > 31) {
          errorMsg = "Invalid value for day: " + regs[1];
        } else if(regs[2] < 1 || regs[2] > 12) {
          errorMsg = "Invalid value for month: " + regs[2];
        } else if(regs[3] < minYear ) {//|| regs[3] > maxYear
          errorMsg = "Invalid value for year: " + regs[3] + " - must be between " + minYear + " and " + maxYear;
        }
      } else {
        errorMsg = "Invalid date format: " + field.value;
      }
    } else if(!allowBlank) {
      errorMsg = "Empty date not allowed!";
    }

    if(errorMsg != "") {
      //alert(errorMsg);
      //field.focus();
      return false;
    }

    return true;
  }
  
  function checkTime(field)
  {
    var errorMsg = "";

    // regular expression to match required time format
    re = /^(\d{1,2}):(\d{2})(:00)?([ap]m)?$/;

    if(field.value != '') {
      if(regs = field.value.match(re)) {
        if(regs[4]) {
          // 12-hour time format with am/pm
          if(regs[1] < 1 || regs[1] > 12) {
            errorMsg = "Invalid value for hours: " + regs[1];
          }
        } else {
          // 24-hour time format
          if(regs[1] > 23) {
            errorMsg = "Invalid value for hours: " + regs[1];
          }
        }
        if(!errorMsg && regs[2] > 59) {
          errorMsg = "Invalid value for minutes: " + regs[2];
        }
      } else {
        errorMsg = "Invalid time format: " + field.value;
      }
    }

    if(errorMsg != "") {
      //alert(errorMsg);
      //field.focus();
      return false;
    }

    return true;
  }
  
   function CompareDate(date1, date2, comp) { //Champ formulaire

       // new Date(Year, Month, Date, Hr, Min, Sec);
	   var tdate1 = date1.split('/');
	   var tdate2 = date2.split('/');

       var dateOne = new Date(tdate1[2], tdate1[1], tdate1[0], 0, 0, 0);
       var dateTwo = new Date(tdate2[2], tdate2[1], tdate2[0], 0, 0, 0);

       switch (comp) {
    		case '=':
        		if (dateOne == dateTwo) {return true;}
				else {return false;}
        		break;
				
    		case '>':
        		if (dateOne > dateTwo) {return true;}
				else {return false;}
        		break;
				
    		case '<':
       		 	if (dateOne < dateTwo) {return true;}
				else {return false;}
        		break;
			
			case '<=':
       		 	if (dateOne <= dateTwo) {return true;}
				else {return false;}
        		break;
				
			case '>=':				
       		 	if (dateOne >= dateTwo) {return true;}
				else {return false;}
        		break;
				
			default: return false;
		}	
		
    }