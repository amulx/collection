<?php
/**
 * Created by PhpStorm.
 * User: amu
 * Date: 15-5-17
 * Time: 下午4:13
 */

namespace Biye\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
class ShopCarController extends Controller{

    public function addgouwucheAction(Request $request){

        $session = $request->getSession();
        $username = $session->get('username');

        if($username==""){
            header("Content-type:text/html;charset=utf-8");
            echo "<script>alert('请先登录后购物');history.back()</script>";
            exit;
        }else{
            $id = isset($_GET['id'])?$_GET['id']:'';
            $session->set('shopcar',$id);
            $car = $session->get('shopcar');
            $purchaser = $session->get('username');
            $identity = session_id();

            $conn = $this->get('database_connection');
            $user = $conn->insert('imooc_car',array('car'=>$car,'iden'=>$identity,'purchaser'=>$purchaser));

            header("Content-type:text/html;charset=utf-8");
//            echo "<script>alert('购物成功');history.back()</script>";  //只返回上一页不刷新
            echo "<script>alert('购物成功');self.location=document.referrer;</script>"; //返回上一页并刷新
            exit;
        }





    }

} 