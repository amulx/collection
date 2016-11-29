<?php

namespace Xiucai\PageBundle\Extension;

use Xiucai\PageBundle\Extension;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MyTwigExtension extends \Twig_Extension {
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container =$container;
    }

    public function getName()
    {
        return 'my_twig_extension';
    }

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFilters(){
        return array(
            'login_translate'=>new \Twig_Filter_Method($this,'login_translate'),
            'room_status'=>new \Twig_Filter_Method($this,'room_status'),
        );
    }


    /**
     * 登录信息错误提示
     * @param $string
     * @return string
     */
    public function login_translate($string){
        $string = trim($string);

        if($string == "Bad credentials"){
            return "账号密码有误，请重新输入";
        }else if($string == "User account is disabled."){
            return "账号还未激活，请先激活";
        }
    }

    /**
     * 直播间状态
     * @param $status
     * @return string
     */
    public function room_status($status){
        if($status == "NONEXIST"){
            $roomStatus = "不存在";
        }else if($status == "READY"){
            $roomStatus = "就绪";
        }else if($status == "START"){
            $roomStatus = "已开始";
        }else if($status == "RUNNING"){
            $roomStatus = "进行中";
        }else if($status == "TIMEOUT"){
            $roomStatus = "超时中";
        }else if($status == "CLOSED"){
            $roomStatus = "已结束";
        }else if($status == "LOCKED"){
            $roomStatus = "锁定中";
        }else{
            $roomStatus = "--";
        }

        return $roomStatus;
    }
}



