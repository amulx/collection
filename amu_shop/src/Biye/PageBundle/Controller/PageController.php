<?php
/**
 * Created by PhpStorm.
 * User: amu
 * Date: 15-4-12
 * Time: 下午10:24
 */

namespace Biye\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

class PageController extends Controller {
    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request){
        $username = "";
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
    }

    public function proDetailsAction(Request $request){

        $session = $request->getSession();
        $username = $session->get('username');

        $request = $this->getRequest();
        $id = isset($_GET['id'])?$_GET['id']:'';

        $conn = $this->get('database_connection');
        $sqlpro = "select id, pName, pSn, pNum, mPrice, iPrice, pDesc, pubTime, isShow, isHot, cId from imooc_pro where id ='".$id."'";
        $query = $conn->query($sqlpro);
        $resultpro = $query->fetchAll();

        $sqlalbum = "select * from imooc_album where pid = '".$id."'";
        $query1 = $conn->query($sqlalbum);
        $resultalbum = $query1->fetchAll();

        $session = $request->getSession();
        $car = session_id();

        $sqlcar = "select * from imooc_car where iden = '".$car."' ";

        $query3 = $conn->query($sqlcar);
        $resultcar = $query3->fetchAll();
        $count=count($resultcar);

        return $this->render('BiyePageBundle:page:proDetails.html.twig',array('resultpro'=>$resultpro[0],'resultalbum'=>$resultalbum[0],'username'=>$username,'count'=>$count));
    }

    public function loginAction(){

        return $this->render('BiyePageBundle:page:login.html.twig');
    }

    public function regAction(){

        return $this->render('BiyePageBundle:page:reg.html.twig');
    }


} 