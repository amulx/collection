<?php

namespace Xiucai\PageBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Xiucai\ServiceBundle\Utilities\Cartservice;

class PublicController extends Controller
{
    /**
     * 秀财网页面头部信息
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function headerAction()
    {
        $session = $this->get('request')->getSession();
        $loginInfo = $this->getUser();
        $memberId = empty($loginInfo) ? $session->get('member_id') : $loginInfo->getId();
        $username =  $session->get('username');
        /*$cart = $this->get('cart_service');
        $cartCount = $cart->getCount();*/
        /*$commonFunction = $this->get('common_function_service');
        $browser = $commonFunction->getBrowser();
        if($browser == 'Internet Explorer 7.0' || $browser == 'Internet Explorer 6.0'){
            echo "<script>window.location.href='".$this->generateUrl('PageBundle_page_warn')."'</script>";exit;
        }*/
        return $this->render('PageBundle:Public:header.html.twig',
        array('memberId' => $memberId,'username'=>$username));
    }

    /**
     * 公共弹层
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function windowAction()
    {
        $getMemberByIp = "";
        $session = $this->get('request')->getSession();
        $loginInfo = $this->getUser();
        $username = empty($_COOKIE['username']) ? "" : $_COOKIE['username'];
        $memberId = empty($loginInfo) ? $session->get('member_id') : $loginInfo->getId();
        /*if(empty($memberId)){
            $emt = $this->getDoctrine()->getManager();
            $getMemberByIp = $emt->getRepository('StoreBundle:XcMember')
                ->findOneBy(array('registerIp' => $this->getMemberIp())); //判断该ip是否已注册过
        }*/

        return $this->render('PageBundle:Public:window.html.twig',
            array('memberId' => $memberId, 'username' => $username, 'member_ip' => $getMemberByIp));
    }

    /**
     * 获取客户端IP
     */
    public function getMemberIp(){
        if (getenv("HTTP_CLIENT_IP"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if(getenv("HTTP_X_FORWARDED_FOR"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if(getenv("REMOTE_ADDR"))
            $ip = getenv("REMOTE_ADDR");
        else $ip = "Unknow";

        return $ip;
    }
}
