<?php

namespace Xiucai\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
class WebController extends Controller
{
    /**
     * @return Response
     * 描述：轮播图列表页面
     */
    public function focusImageAction()
    {
        $focusSelect  = isset($_REQUEST['focusSelect'])?trim($_REQUEST['focusSelect']):'1';
        $areaid       = isset($_GET['areaid'])?trim($_GET['areaid']):'';
        if($areaid){
            $focusSelect = $areaid;
        }
        $conn   = $this->get('database_connection'); //获取数据库连接对象
        $sql    = "select f.id, f.name, f.url, f.target_url, f.create_time, f.area_id, f.order, f.is_active, p.name paname from xc_focus_image f left join xc_page_area p on f.area_id=p.id where f.id>0";
        //用户状态搜索条件
        if($focusSelect == '1'){
            $sql = $sql." and f.area_id = 1";
        }elseif($focusSelect == '2'){
            $sql = $sql." and f.area_id = 2";
        }
        //order排序
        $sql    = $sql." order by f.order";
        $query  = $conn->query($sql);
        $result = $query->fetchAll();
        $parameter =array(
            'focusSelect'  => $focusSelect,
        );
        return $this->render('AdminBundle:Web:focusImageIndex.html.twig',array('parameter'=>$parameter,'fdata'=>$result));
    }
    /**
     * @return Response
     * 描述：改变用户账号状态
     */
    public function activeUpdateAction(){
        try{
            if(isset($_POST['id'])){
                $id = $_POST['id'];
            }
            if(isset($_POST['flag'])){
                $flag = $_POST['flag'];
            }
            $conn = $this->get('database_connection');
            if($flag == 1){
                $admin = $conn->update('xc_admin',array('is_active'=>0),array('admin_id'=>$id));
            }elseif($flag == 2){
                $admin = $conn->update('xc_admin',array('is_active'=>1),array('admin_id'=>$id));
            }
            return new Response('ok');
        }catch (Exception $e){
            return new Response('no');
        }
    }

    /**
     * 描述：跳转套轮播图添加页面
     */
    public function focusAddPageAction(){
        $areaid = isset($_GET['areaid'])?$_GET['areaid']:' ';
        return $this->render('AdminBundle:Web:focusAddPage.html.twig',array('areaid'=>$areaid));
    }
    /**
     * 描述：添加轮播图功能
     */
    public function focusAddAction(){
        date_default_timezone_set("PRC");
        $c_time  = date("Y-m-d H:i:s");
        $name    = isset($_REQUEST['name'])?trim($_REQUEST['name']):'';
        $t_url   = isset($_REQUEST['t_url'])?trim($_REQUEST['t_url']):'';
        $areaid  = isset($_REQUEST['areaid'])?trim($_REQUEST['areaid']):'';
        $img     = isset($_REQUEST['img'])?trim($_REQUEST['img']):'';

        $conn         = $this->get('database_connection');
        $focus_image  = $conn->insert('xc_focus_image', array('name'=>$name, 'url'=>$img,'target_url'=>$t_url,'create_time'=>$c_time,'area_id'=>$areaid, 'is_active'=>1));
        $sql          = "select id from xc_focus_image order by id desc limit 0,1";
        $query        = $conn->query($sql);
        $result       = $query->fetchAll();
        $f_order      = $conn->update('xc_focus_image f', array('f.order'=>$result[0]['id']),array('id'=>$result[0]['id']));
        return $this->redirect($this->generateUrl('AdminBundle_Web_focusImage',array('areaid' => $areaid)));
    }


    /**
     * 描述：跳转轮播图编辑页面
     */
    public function focusUpdatePageAction(){
        $id     = isset($_GET['id'])?$_GET['id']:' ';
        $conn   = $this->get('database_connection');
        $sql    = "select f.id, f.name, f.url, f.target_url, f.create_time, f.area_id, f.order, f.is_active from xc_focus_image f where f.id='".$id."'";
        $query  = $conn->query($sql);
        $result = $query->fetchAll();
        return $this->render('AdminBundle:Web:focusUpdatePage.html.twig',array('fdata'=>$result[0]));
    }
    /**
     * 描述：编辑轮播图功能
     */
    public function focusUpdateAction(){
        date_default_timezone_set("PRC");
        $c_time  = date("Y-m-d H:i:s");
        $id      = isset($_REQUEST['fid'])?trim($_REQUEST['fid']):'';
        $name    = isset($_REQUEST['name'])?trim($_REQUEST['name']):'';
        $t_url   = isset($_REQUEST['t_url'])?trim($_REQUEST['t_url']):'';
        $areaid  = isset($_REQUEST['areaid'])?trim($_REQUEST['areaid']):'';
        $img     = isset($_REQUEST['img'])?trim($_REQUEST['img']):'';

        $conn         = $this->get('database_connection');
        $focus_image  = $conn->update('xc_focus_image', array('name'=>$name, 'url'=>$img,'target_url'=>$t_url,'create_time'=>$c_time),array('id'=>$id));

        return $this->redirect($this->generateUrl('AdminBundle_Web_focusImage',array('areaid' => $areaid)));
    }

    /**
     * @return Response
     * 描述：向上向下功能
     */
    public function focusUpDownAction(){
        try{
            $order     = isset($_REQUEST['order'])?trim($_REQUEST['order']):'';
            $flag      = isset($_REQUEST['flag'])?trim($_REQUEST['flag']):'';
            $focusType = isset($_REQUEST['focusType'])?trim($_REQUEST['focusType']):'';
            $conn    = $this->get('database_connection');
            if($flag == 1){
                $sql = "select f.id, f.order from xc_focus_image f where f.area_id='".$focusType."' and f.order <= '".$order."' order by f.order desc";
                $query  = $conn->query($sql);
                $result = $query->fetchAll();
                if(count($result) == 1){
                    return new Response('up');
                }elseif(count($result) > 1){
                    $up_one_order = $conn->update('xc_focus_image f', array('f.order'=>$result[1]['order']),array('id'=>$result[0]['id']));
                    $up_two_order = $conn->update('xc_focus_image f', array('f.order'=>$result[0]['order']),array('id'=>$result[1]['id']));
                    $up_value=$result[1]['order'].','.$result[0]['order'];
                    return new Response($up_value);
                }else{
                    return new Response('no');
                }
            }elseif($flag == 2){
                $sql = "select f.id, f.order from xc_focus_image f where f.area_id='".$focusType."' and f.order >= '".$order."' order by f.order";
                $query  = $conn->query($sql);
                $result = $query->fetchAll();
                if(count($result) == 1){
                    return new Response('down');
                }elseif(count($result) > 1){
                    $down_one_order = $conn->update('xc_focus_image f', array('f.order'=>$result[1]['order']),array('id'=>$result[0]['id']));
                    $down_two_order = $conn->update('xc_focus_image f', array('f.order'=>$result[0]['order']),array('id'=>$result[1]['id']));
                    $down_value=$result[0]['order'].','.$result[1]['order'];
                    return new Response($down_value);
                }else{
                    return new Response('no');
                }
            }else{
                return new Response('no');
            }
        }catch (Exception $e){
            return new Response('no');
        }
    }

    /**
     * @return Response
     * 描述：删除轮播图
     */
    public function focusDeleteAction(){
        try{
            if(isset($_POST['id'])){
                $id = $_POST['id'];
            }
            $conn = $this->get('database_connection');
            $category = $conn->delete('xc_focus_image',array('id'=>$id));
            return new Response('ok');
        }catch (Exception $e){
            return new Response('no');
        }

    }
    /**
     * @return Response
     * 描述：删除轮播图
     */
    public function focusOfflineAction(){
        try{
            if(isset($_POST['id'])){
                $id = $_POST['id'];
            }
            if(isset($_POST['flag'])){
                $flag = $_POST['flag'];
            }
            $conn     = $this->get('database_connection');
            $category = $conn->update('xc_focus_image',array('is_active'=>$flag),array('id'=>$id));
            return new Response('ok');
        }catch (Exception $e){
            return new Response('no');
        }

    }
}
