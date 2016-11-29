<?php
/**
 * Created by PhpStorm.
 * User: amu
 * Date: 15-3-20
 * Time: 下午9:39
 */

namespace Biye\AdminBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class CateController extends Controller{
    /**
     * 跳转到分类添加页面
     * @return Response
     */
    public function addCateAction(){
        return $this->render('BiyeAdminBundle:Cate:addCate.html.twig');
    }

    /**
     * 分类添加操作
     * @return Response
     */
    public function cateAddAction(){
        $cName = isset($_REQUEST['cName'])?$_REQUEST['cName']:'';
        $conn = $this->get('database_connection');      //连接数据库
        $cate = $conn->insert('imooc_cate',array('cName'=>$cName));  //插入数据

//        return $this->render('BiyeAdminBundle:Cate:addCate.html.twig');
        header("Content-type:text/html;charset=utf-8");
//            echo "<script>alert('购物成功');history.back()</script>";  //只返回上一页不刷新
        echo "<script>alert('添加成功');self.location=document.referrer;</script>"; //返回上一页并刷新
        exit;
    }

    /**
     * 分类显示页面
     * @return Response
     */
    public function listCateAction(){
        $conn = $this->get('database_connection');
        $sql = "select m.id, m.cName from imooc_cate m ";
        $query = $conn->query($sql);
        $result = $query->fetchAll();

        return $this->render('BiyeAdminBundle:Cate:listCate.html.twig',array('result'=>$result));
    }

    public function editCateAction(){

        $id = isset($_GET['id'])?$_GET['id']:'';

        $conn = $this ->get('database_connection');

        $sql = "select m.id, m.cName from imooc_cate m where m.id='".$id."'";
        $result = $conn->query($sql)->fetchAll();

        return $this->render('BiyeAdminBundle:Cate:editCate.html.twig',array('result'=>$result[0],'id'=>$id));

    }

    public function updateCateAction(){
        $id = isset($_POST['id'])?$_POST['id']:'';
        $cName = isset($_REQUEST['cName'])?$_REQUEST['cName']:'';

        $conn = $this->get('database_connection');
        $admin = $conn->update('imooc_cate',array('cName'=>$cName),array('id'=>$id));

        return $this->redirect($this->generateUrl('AdminBundle_Cate_listCate'));
    }

    public function delCateAction(){
        if(isset($_POST['id'])){
            $id = $_POST['id'];
        }


        print_r($id);
        exit;
        $conn = $this->get('database_connection');
        $category = $conn->update('imooc_cate',array('status'=>1),array('id'=>$id));
        return new Response('ok');

    }


} 