<?php
/**
 * Created by PhpStorm.
 * User: amu
 * Date: 15-5-16
 * Time: 上午9:20
 */

namespace Biye\PageBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller{

    /**
     * 用户注册
     * @return Response
     */
    public function registerAction(){

        $username = isset($_REQUEST['username'])?$_REQUEST['username']:'';
        $password = isset($_REQUEST['password'])?$_REQUEST['password']:'';
        $email = isset($_REQUEST['email'])?$_REQUEST['email']:'';
        $sex = $_REQUEST['sex'];


        $conn = $this->get('database_connection');
        $user = $conn->insert('imooc_user',array('username'=>$username,'password'=>$password,'email'=>$email,'sex'=>$sex));

        return $this->render('BiyePageBundle:User:register_succ.html.twig',array('username'=>$username));
    }

    public function loginAction(Request $request){

        $username = isset($_REQUEST['username'])?$_REQUEST['username']:'';
        $password = isset($_REQUEST['password'])?$_REQUEST['password']:'';

        $conn = $this->get('database_connection');
        $sql = "select * from imooc_user where username = '".$username."' and password = '".$password."'";
        $query = $conn->query($sql);
        $result = $query->fetchAll();
        $count1 = count($result);
        if($count1>0){
            $session = $request->getSession();
            $session->set('username',$username);

            //查找所有分类
            $conn = $this->get('database_connection');
            $sqlCate = "select id , cName from imooc_cate";
            $query = $conn->query($sqlCate);
            $resultcate = $query->fetchAll();

            //查找所有的产品
            $sqlPro = "select id, pName, pSn, pNum, mPrice, iPrice, pDesc, pubTime, isShow, isHot, cId from imooc_pro limit 12";
            $query1 = $conn->query($sqlPro);
            $resultpro = $query1->fetchAll();

            //查找所有商品的图片
            $sqlalbum = "select id, pid, albumPath from imooc_album";
            $query2 = $conn->query($sqlalbum);
            $resultalbum = $query2->fetchAll();

            $session = $request->getSession();
            $car = session_id();

            $sqlcar = "select * from imooc_car where iden = '".$car."' ";
            $query3 = $conn->query($sqlcar);
            $resultcar = $query3->fetchAll();
            $count=count($resultcar);


            return $this->render('BiyePageBundle:Page:index.html.twig',array('resultcate'=>$resultcate,'resultpro'=>$resultpro,'resultalbum'=>$resultalbum,'username'=>$username,'count'=>$count));
        }else{
            header("Content-type:text/html;charset=utf-8");
            echo "<script>alert('用户名或者密码错误，请重新登入');history.back()</script>";
            exit;
        }

    }

} 