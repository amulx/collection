<?php 
/**
 *自定义API用于Android客户端扫描后访问，将指定的username保存至
 */
$randnumber = $_GET['randnumber'];
$username = $_GET['username'];

require 'conn.php';
mysql_query("update jike set username ='$username' where randnumber='$randnumber'");

?>