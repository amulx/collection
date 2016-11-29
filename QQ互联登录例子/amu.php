<?php 

require_once 'Connect2.1/function.php';
require_once 'Connect2.1/qqConnectAPI.php';
// echo $_GET['code'];

//qing qiu accesstoken
$oauth = new Oauth();
$accesstoken = $oauth->qq_callback();
$openid = $oauth->get_openid();//可以通过对openid的判断来获知用户是否是第一次登陆平台
debug($accesstoken);

setcookie('qq_accesstoken',$accesstoken,time()+86400);
setcookie('qq_openid',$openid,time()+86400);
header('Location:index.php');


//huo qu  qq avatar
$qc = new QC($_COOKIE['qq_accesstoken'],$_COOKIE['qq_openid']);
$userinfo = $qc->get_user_info();
debug($userinfo);
?>