<?php 

function checkEmail($inAddress) 
{ 
return (ereg("^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+",$inAddress)); 
} 

?>