<?php
/**
 * Created by PhpStorm.
 * User: amu
 * Date: 15-3-18
 * Time: 下午1:43
 */

namespace Biye\AdminBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller {
    /**
     * 后台页面
     * @return Response
     */
    public function indexAction(){
        return $this->render('BiyeAdminBundle:Admin:index.html.twig');
    }

    public function mainAction(){
        return $this->render('BiyeAdminBundle:Admin:main.html.twig');
    }

    /**
     * 管理员添加页面
     * @return Response
     */
    public function addAdminAction(){
        return $this->render('BiyeAdminBundle:Admin:addAdmin.html.twig');
    }

    /**
     * 管理员添加操作
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function adminAddAction(){
        $username = isset($_REQUEST['username'])?$_REQUEST['username']:'';
        $password = isset($_REQUEST['password'])?$_REQUEST['password']:'';
        $email = isset($_REQUEST['email'])?$_REQUEST['email']:'';

        $conn = $this->get('database_connection');
        $admin = $conn->insert('imooc_admin',array('username'=>$username,'password'=>$password,'email'=>$email,'is_active'=>0));

        return $this->redirect($this->generateUrl('AdminBundle_Admin_addAdmin'));

    }

    /**
     * 管理员显示列表
     * @return Response
     */
    public function listAdminAction(){
        $conn = $this->get('database_connection');
        $sql = "select m.id, m.username, m.password, m.email from imooc_admin m where m.status<>2";
        $query = $conn->query($sql);
        $result = $query->fetchAll();

        return $this->render('BiyeAdminBundle:Admin:listAdmin.html.twig',array('result'=>$result));

    }

    /**
     * 跳转到管理员的编辑页面
     * @return Response
     */
    public function editAdminAction(){

        $id = isset($_GET['id'])?$_GET['id']:'';

        $conn = $this ->get('database_connection');

        $sql = "select m.id, m.username,  m.password, m.email from imooc_admin m where m.id='".$id."'";
        $result = $conn->query($sql)->fetchAll();

        return $this->render('BiyeAdminBundle:Admin:editAdmin.html.twig',array('result'=>$result[0],'id'=>$id));
    }

    /**
     * 修改管理员
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAdminAction(){
        $id = isset($_POST['id'])?$_POST['id']:'';
        $username = isset($_REQUEST['username'])?$_REQUEST['username']:'';
        $password = isset($_REQUEST['password'])?$_REQUEST['password']:'';
        $email = isset($_REQUEST['email'])?$_REQUEST['email']:'';

        $conn = $this->get('database_connection');
        $admin = $conn->update('imooc_admin',array('username'=>$username, 'password'=>$password,'email'=>$email),array('id'=>$id));

        return $this->redirect($this->generateUrl('AdminBundle_Admin_listAdmin'));
    }

    /**
     * 删除管理员
     * @return Response
     */
    public function delAdminAction(){
            if(isset($_POST['id'])){
                $id = $_POST['id'];
            }
            $conn = $this->get('database_connection');
            $category = $conn->update('imooc_admin',array('status'=>1),array('id'=>$id));
            return new Response('ok');

    }
















    }