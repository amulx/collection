<?php
namespace Xiucai\ServiceBundle\Utilities;

use Symfony\Bundle\FrameworkBundle\Controller;
use Doctrine\Bundle\DoctrineBundle;
class CommonService {
    public function getBrowser(){
        if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE 8.0")){
            $browser = "Internet Explorer 8.0";
        }else if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE 7.0")){
            $browser = "Internet Explorer 7.0";
        }else if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE 6.0")){
            $browser = "Internet Explorer 6.0";
        }else if(strpos($_SERVER["HTTP_USER_AGENT"],"Firefox/3")){
            $browser = "Firefox 3";
        }else if(strpos($_SERVER["HTTP_USER_AGENT"],"Firefox/2")){
            $browser = "Firefox 2";
        }else if(strpos($_SERVER["HTTP_USER_AGENT"],"Chrome")){
            $browser = "Google Chrome";
        }else if(strpos($_SERVER["HTTP_USER_AGENT"],"Safari")){
            $browser = "Safari";
        }else if(strpos($_SERVER["HTTP_USER_AGENT"],"Opera")){
            $browser = "Opera";
            /*}else if(strpos($_SERVER["HTTP_USER_AGENT"],"Maxthon")){
                $browser = "Maxthon";
            }else if(strpos($_SERVER["HTTP_USER_AGENT"],"BIDUBrowser")){
                $browser = "BIDUBrowser";
            }else if(strpos($_SERVER["HTTP_USER_AGENT"],"LBBROWSER")){
                $browser = "LBBROWSER";
            }else if(strpos($_SERVER["HTTP_USER_AGENT"],"SE 2.X MetaSr 1.0"){
                $browser = "搜狗";*/
        }else{
            $browser = $_SERVER["HTTP_USER_AGENT"];
        }
        return $browser;
    }
}
?>