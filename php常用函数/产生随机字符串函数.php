<?php 

function random($length) { 
$hash = @#@#; 
$chars = @#ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz@#; 
$max = strlen($chars) - 1; 
mt_srand((double)microtime() * 1000000); 
for($i = 0; $i < $length; $i++) { //开源代码OSPHP.COM.Cn 
  $hash .= $chars[mt_rand(0, $max)]; 
} 
return $hash; 
} 

?>