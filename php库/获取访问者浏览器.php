<?php 

function browse_infor() 
{ 
$browser="";$browserver=""; //OSPHP.com.CN 
$Browsers =array("Lynx","MOSAIC","AOL","Opera","JAVA","MacWeb","WebExplorer","OmniWeb"); 
$Agent = $GLOBALS["HTTP_USER_AGENT"]; 
for ($i=0; $i<=7; $i++) 
{ 
if (strpos($Agent,$Browsers[$i])) 
{ 
$browser = $Browsers[$i]; 
$browserver =""; 
} 
} 
if (ereg("Mozilla",$Agent) && !ereg("MSIE",$Agent)) 
{ 
$temp =explode("(", $Agent); $Part=$temp[0]; 
$temp =explode("/", $Part); $browserver=$temp[1]; 
$temp =explode(" ",$browserver); $browserver=$temp[0]; 
$browserver =preg_replace("/([d.]+)/","1",$browserver); 
$browserver = " $browserver"; 
$browser = "Netscape Navigator"; 
} 
if (ereg("Mozilla",$Agent) && ereg("Opera",$Agent)) 
{ 
$temp =explode("(", $Agent); $Part=$temp[1]; //oSPHP.COM.CN 
$temp =explode(")", $Part); $browserver=$temp[1]; 
$temp =explode(" ",$browserver);$browserver=$temp[2]; 
$browserver =preg_replace("/([d.]+)/","1",$browserver); 
$browserver = " $browserver"; 
$browser = "Opera"; 
} 
if (ereg("Mozilla",$Agent) && ereg("MSIE",$Agent)) 
{ 
$temp = explode("(", $Agent); $Part=$temp[1]; 
$temp = explode(";",$Part); $Part=$temp[1]; 
$temp = explode(" ",$Part);$browserver=$temp[2]; 
$browserver =preg_replace("/([d.]+)/","1",$browserver); 
$browserver = " $browserver"; 
$browser = "Internet Explorer"; 
} 
if ($browser!="") 
{ 
$browseinfo = "$browser$browserver"; 
} 
else 
{ 
$browseinfo = "Unknown"; 
} 
return $browseinfo; 
} 
//调用方法$browser=browseinfo() ;直接返回结果 /

?>