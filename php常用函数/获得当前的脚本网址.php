<?php 

function get_php_url(){ 
        if(!empty($_server["REQUEST_URI"])){ 
                $scriptName = $_SERVER["REQUEST_URI"]; 
                $nowurl = $scriptName; 
        }else{ 
                $scriptName = $_SERVER["PHP_SELF"]; 
                if(empty($_SERVER["QUERY_STRING"])) $nowurl = $scriptName; 
                else $nowurl = $scriptName."?".$_SERVER["QUERY_STRING"]; 
        } 
        return $nowurl; 
} 

?>