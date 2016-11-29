<?php

namespace Xiucai\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
class SystemController extends Controller
{
    public function permissionAction()
    {
        $contents    = isset($_REQUEST['contents'])?trim($_REQUEST['contents']):'';
        $condition   = isset($_REQUEST['condition'])?trim($_REQUEST['condition']):'';
        $timeSelect  = isset($_REQUEST['timeSelect'])?trim($_REQUEST['timeSelect']):'';
        $startTime   = isset($_REQUEST['startTime'])?trim($_REQUEST['startTime']):'';
        $endTime     = isset($_REQUEST['endTime'])?trim($_REQUEST['endTime']):'';
        $adminRole   = isset($_REQUEST['adminRole'])?trim($_REQUEST['adminRole']):'';
        $adminActive = isset($_REQUEST['adminActive'])?trim($_REQUEST['adminActive']):'';

        $conn   = $this->get('database_connection'); //获取数据库连接对象
        $sql    = "select a.admin_id, a.account, a.real_name, a.role_id, a.create_time, a.login_time, a.is_active, r.name from xc_admin a left join xc_admin_role r on a.role_id=r.admin_role_id where a.admin_id>0";

        //全局搜索条件
        if($condition == ''){
            $sql .= " and a.account like '%".$contents ."%'";
        }elseif($condition == '1'){
            $sql .= " and a.account like '%".$contents ."%'";
        }
       //时间收索条件
        if($timeSelect == '1'){
            if($startTime != '' && $endTime != ''){
                $sql = $sql." and a.create_time between '".$startTime."' and '".$endTime."'";
            }
            else
                if($startTime != ''){
                    $sql=$sql." and a.create_time >= '".$startTime."'";
                }
                else
                    if($endTime != ''){
                        $sql=$sql." and a.create_time <= '".$endTime."'";
                    }
        }elseif($timeSelect == '2'){
            if($startTime != '' && $endTime != ''){
                $sql = $sql." and a.login_time between '".$startTime."' and '".$endTime."'";
            }
            else
                if($startTime != ''){
                    $sql=$sql." and a.login_time >= '".$startTime."'";
                }
                else
                    if($endTime != ''){
                        $sql=$sql." and a.login_time <= '".$endTime."'";
                    }
        }
        //用户角色搜索条件
        if($adminRole == '1'){
            $sql=$sql." and a.role_id = 1";
        }elseif($adminRole == '2'){
            $sql=$sql." and a.role_id = 2";
        }elseif($adminRole == '3'){
            $sql=$sql." and a.role_id = 3";
        }elseif($adminRole == '4'){
            $sql=$sql." and a.role_id = 4";
        }
        //用户状态搜索条件
        if($adminActive == '1'){
            $sql=$sql." and a.is_active = 1";
        }elseif($adminActive == '2'){
            $sql=$sql." and a.is_active <> 1";
        }

        $query  = $conn->query($sql);
        $result = $query->fetchAll();
        $count=count($result);
        $request = Request::createFromGlobals()->query;

        $params['page'] = $request->get('page');  //当前页码数
        $limit = 10; //页面限制条数
        $page = empty($params['page']) ? 1 : $params['page']; //当前页数初始化
        $offset = ($page - 1)*$limit; //计算分页偏移量
        $adata  =  array_slice($result,$offset,10);
        $pagination = $this->container->get('PagePaginationServices'); //获取分页services
        $pager = $pagination->getNavigation($count, $limit, $page, 'permission', $request); //分页处理：总数，每页条数，页码，action名称，搜索参数
        $pages = ($count == 0 || $count <= 10) ? '' : $pager; //每10条记录作为一页
        $parameter =array(
            'page'         => $page,
            'contents'    => $contents,
            'condition'   => $condition,
            'nums'         => $count,
            'timeSelect'  => $timeSelect,
            'startTime'   => $startTime,
            'endTime'     => $endTime,
            'adminRole'   => $adminRole,
            'adminActive' => $adminActive
        );
        $sqlr    = "select  r.admin_role_id, r.name from xc_admin_role r";
        $queryr  = $conn->query($sqlr);
        $rdata   = $queryr->fetchAll();
        return $this->render('AdminBundle:System:permissionIndex.html.twig',array('pages' => $pages,'parameter'=>$parameter,'adata'=>$adata,'rdata'=>$rdata));
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
     * @return Response
     * 描述：批量改变用户账号状态
     */
    public function adminRemoveAction(){
        try{
            $Ids    = $_POST['Ids'];
            $idList = urldecode($Ids);
            $datas  = explode(',',$idList);
            $conn   = $this -> get('database_connection');
            for($i=0;$i < count($datas)-1;$i++){
                $admin = $conn->update('xc_admin',array('is_active'=>0),array('admin_id'=>$datas[$i]));
            }
            return new Response('ok');
        }
        catch(Exception $e){
            return new Response('no');
        }
    }

    /**
     * 描述：跳转套管理员添加页面
     */
    public function adminAddPageAction(){
        return $this->render('AdminBundle:System:adminAddPage.html.twig');
    }
    /**
     * 描述：添加管理员功能
     */
    public function adminAddAction(){
        date_default_timezone_set("PRC");
        $account   = isset($_REQUEST['account'])?trim($_REQUEST['account']):'';
        $pwd       = isset($_REQUEST['pwd'])? password_hash(trim($_REQUEST['pwd']), PASSWORD_BCRYPT, array('cost' => 12)):'';
        $role      = isset($_REQUEST['role'])?trim($_REQUEST['role']):'';
        $real_name = isset($_REQUEST['real_name'])?trim($_REQUEST['real_name']):'';
        $c_time    = date("Y-m-d H:i:s");
        $conn      = $this->get('database_connection');
        $admin     = $conn->insert('xc_admin', array('account'=>$account, 'pwd'=>$pwd,'role_id'=>$role,'real_name'=>$real_name,'is_active'=>1, 'create_time'=>$c_time));
        return $this->redirect($this->generateUrl('AdminBundle_System_permission'));
    }

    /**
     * @return Response
     * 描述：ajax是否存在重复账户名
     */
    public function adminRedoAction(){
        try{
            if(isset($_POST['name'])){
                $name = $_POST['name'];
            }
            if(isset($_POST['aid'])){
                $aid = $_POST['aid'];
            }else{
                $aid = '';
            }
            $conn = $this->get('database_connection');
            if($aid){
                $sql    = "select a.admin_id from xc_admin a where a.account='".$name."'";
            }else{
                $sql    = "select a.admin_id from xc_admin a where a.account='".$name."' and a.admin_id <> '".$aid."'";
            }
            $result = $conn->query($sql)->fetchAll();
            if($result){
                return new Response('ok');
            }else{
                return new Response('no');
            }

        }
        catch(Exception $e){
            return new Response('no');
        }
    }
    /**
     * 描述：跳转套管理员编辑页面
     */
    public function adminUpdatePageAction(){
        $id     = isset($_GET['id'])?$_GET['id']:' ';
        $conn   = $this->get('database_connection');
        $sql    = "select a.admin_id, a.account, a.real_name, a.role_id, a.create_time from xc_admin a where a.admin_id='".$id."'";
        $query  = $conn->query($sql);
        $result = $query->fetchAll();
        return $this->render('AdminBundle:System:adminUpdatePage.html.twig',array('adata'=>$result[0]));
    }
    /**
     * 描述：添加管理员功能
     */
    public function adminUpdateAction(){
        date_default_timezone_set("PRC");
        $aid       = isset($_REQUEST['aid'])?trim($_REQUEST['aid']):'';
        $account   = isset($_REQUEST['account'])?trim($_REQUEST['account']):'';
        $pwd       = isset($_REQUEST['pwd'])? password_hash(trim($_REQUEST['pwd']), PASSWORD_BCRYPT, array('cost' => 12)):'';
        $role      = isset($_REQUEST['role'])?trim($_REQUEST['role']):'';
        $real_name = isset($_REQUEST['real_name'])?trim($_REQUEST['real_name']):'';
        $c_time    = date("Y-m-d H:i:s");
        $conn      = $this->get('database_connection');
        $admin     = $conn->update('xc_admin', array('account'=>$account, 'pwd'=>$pwd,'role_id'=>$role,'real_name'=>$real_name, 'create_time'=>$c_time),array('admin_id'=>$aid));
        return $this->redirect($this->generateUrl('AdminBundle_System_permission'));
    }

    /**
     * 描述：系统配置首页
     */
    public function msconfigAction(){
        $conn   = $this->get('database_connection'); //获取数据库连接对象
        $sql    = "select o.id, o.key, o.value from xc_options o";
        $query  = $conn->query($sql);
        $result = $query->fetchAll();
        $count=count($result);
        $request = Request::createFromGlobals()->query;

        $params['page'] = $request->get('page');  //当前页码数
        $limit = 10; //页面限制条数
        $page = empty($params['page']) ? 1 : $params['page']; //当前页数初始化
        $offset = ($page - 1)*$limit; //计算分页偏移量
        $odata  =  array_slice($result,$offset,10);
        $pagination = $this->container->get('PagePaginationServices'); //获取分页services
        $pager = $pagination->getNavigation($count, $limit, $page, 'msconfig', $request); //分页处理：总数，每页条数，页码，action名称，搜索参数
        $pages = ($count == 0 || $count <= 10) ? '' : $pager; //每10条记录作为一页
        $parameter =array(
            'page'         => $page,
            'nums'         => $count
        );
        return $this->render('AdminBundle:System:msconfigIndex.html.twig',array('pages' => $pages,'parameter'=>$parameter,'odata'=>$odata));
    }
    /**
     * 描述：ajax添加系统参数
     */
    public function msconfigAddAction(){
        try{
            $key   = isset($_POST['key'])?trim($_POST['key']):'';
            $val   = isset($_POST['val'])?trim($_POST['val']):'';
            $conn = $this->get('database_connection');
            $sql    = "select o.id, o.key, o.value from xc_options o where o.key='".$key."'";
            $query  = $conn->query($sql);
            $result = $query->fetchAll();
            if($result){
                return new Response('redo');
            }
            $options   = $conn->insert('xc_options', array('`key`'=>$key,'`value`'=>$val));
            return new Response('ok');
        }catch (Exception $e){
            return new Response('no');
        }
    }
    /**
     * 描述：ajax更新系统参数
     */
    public function msconfigUpdateAction(){
        try{
            $key   = isset($_POST['key'])?trim($_POST['key']):'';
            $val   = isset($_POST['val'])?trim($_POST['val']):'';
            $oid   = isset($_POST['oid'])?trim($_POST['oid']):'';
            $conn = $this->get('database_connection');
            $sql    = "select o.id, o.key, o.value from xc_options o where o.key='".$key."' and o.id <> '".$oid."'";
            $query  = $conn->query($sql);
            $result = $query->fetchAll();
            if($result){
                return new Response('redo');
            }
            $options   = $conn->update('xc_options', array('`key`'=>$key,'`value`'=>$val),array('id'=>$oid));
            return new Response('ok');
        }catch (Exception $e){
            return new Response('no');
        }
    }
    public  function msconfigDeleAction(){
        try{
            if(isset($_POST['id'])){
                $id = $_POST['id'];
            }
            $conn    = $this->get('database_connection');
            $options = $conn->delete('xc_options',array('id'=>$id));
            return new Response('ok');
        }catch (Exception $e){
            return new Response('no');
        }
    }
    public function msconfigRemoveAction(){
        try{
            $Ids    = $_POST['Ids'];
            $idList = urldecode($Ids);
            $datas  = explode(',',$idList);
            $conn   = $this -> get('database_connection');
            for($i=0;$i < count($datas)-1;$i++){
                $options = $conn->delete('xc_options',array('id'=>$datas[$i]));
            }
            return new Response('ok');
        }
        catch(Exception $e){
            return new Response('no');
        }
    }
}
