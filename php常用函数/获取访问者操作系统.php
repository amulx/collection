<?php 

function osinfo() { 
$os=""; 
$Agent = $GLOBALS["HTTP_USER_AGENT"]; 
if (eregi('win',$Agent) && strpos($Agent, '95')) { 
$os="Windows 95"; 
} 
elseif (eregi('win 9x',$Agent) && strpos($Agent, '4.90')) { 
$os="Windows ME"; 
} 
elseif (eregi('win',$Agent) && ereg('98',$Agent)) { 
$os="Windows 98"; 
} 
elseif (eregi('win',$Agent) && eregi('nt 5.0',$Agent)) { 
$os="Windows 2000"; 
} 
elseif (eregi('win',$Agent) && eregi('nt',$Agent)) { //开源代码OSPhP.COm.CN 
$os="Windows NT"; 
} 
elseif (eregi('win',$Agent) && eregi('nt 5.1',$Agent)) { 
$os="Windows XP"; 
} 
elseif (eregi('win',$Agent) && ereg('32',$Agent)) { 
$os="Windows 32"; 
} 
elseif (eregi('linux',$Agent)) { 
$os="Linux"; 
} 
elseif (eregi('unix',$Agent)) { 
$os="Unix"; //开源OSPhP.COM.CN 
} 
elseif (eregi('sun',$Agent) && eregi('os',$Agent)) { 
$os="SunOS"; 
} 
elseif (eregi('ibm',$Agent) && eregi('os',$Agent)) { 
$os="IBM OS/2"; 
} 
elseif (eregi('Mac',$Agent) && eregi('PC',$Agent)) { 
$os="Macintosh"; 
} 
elseif (eregi('PowerPC',$Agent)) { //PHP开源代码 
$os="PowerPC"; 
} 
elseif (eregi('AIX',$Agent)) { 
$os="AIX"; 
} 
elseif (eregi('HPUX',$Agent)) { 
$os="HPUX"; 
} 
elseif (eregi('NetBSD',$Agent)) { 
$os="NetBSD"; 
} 
elseif (eregi('BSD',$Agent)) { 
$os="BSD"; 
} 
elseif (ereg('OSF1',$Agent)) { 
$os="OSF1"; 
} 
elseif (ereg('IRIX',$Agent)) { 
$os="IRIX"; 
} 
elseif (eregi('FreeBSD',$Agent)) { 
$os="FreeBSD"; 
} 
if ($os=='') $os = "Unknown"; 
return $os; 
} 
//调用方法$os=os_infor() ; 

?>