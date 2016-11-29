<?php 

function readfromfile($file_name) { 
	if (file_exists($file_name)) { 
	$filenum=fopen($file_name,"r"); 
	flock($filenum,LOCK_EX); 
	$file_data=fread($filenum, filesize($file_name)); 
	rewind($filenum); 
	fclose($filenum); 
	return $file_data; 
	} 
} 

?>