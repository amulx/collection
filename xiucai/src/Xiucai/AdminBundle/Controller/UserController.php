<?php

namespace Xiucai\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
class UserController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AdminBundle:Default:index.html.twig', array('name' => $name));
    }

    public function teacherAction(){
        $contents  = isset($_REQUEST['contents'])?trim($_REQUEST['contents']):'';
        $condition = isset($_REQUEST['condition'])?trim($_REQUEST['condition']):'';

        $conn   = $this->get('database_connection'); //获取数据库连接对象
        $sql    = "select i.id, i.name, i.title, i.brief, i.avatar, i.favorite_count, i.cellphone, i.status from xc_instructor i where i.status<>7";

        if($condition == ''){
            $sql .= " and (i.name like '%".$contents ."%' or i.id like '%".$contents ."%' or i.cellphone like '%".$contents ."%' or i.title like '%".$contents ."%')";
        }elseif($condition == '1'){
            $sql .= " and i.name like '%".$contents ."%'";
        }elseif($condition == '2'){
            $sql .= " and i.id like '%".$contents ."%'";
        }elseif($condition == '3'){
            $sql .= " and i.cellphone like '%".$contents ."%'";
        }elseif($condition == '4'){
            $sql .= " and i.title like '%".$contents ."%'";
        }
        $query  = $conn->query($sql);
        $result = $query->fetchAll();
        $count=count($result);
        $request = Request::createFromGlobals()->query;

        $params['page'] = $request->get('page');  //当前页码数
        $limit = 10; //页面限制条数
        $page = empty($params['page']) ? 1 : $params['page']; //当前页数初始化
        $offset = ($page - 1)*$limit; //计算分页偏移量
        $tdata  =  array_slice($result,$offset,10);
        $pagination = $this->container->get('PagePaginationServices'); //获取分页services
        $pager = $pagination->getNavigation($count, $limit, $page, 'teacher', $request); //分页处理：总数，每页条数，页码，action名称，搜索参数
        $pages = ($count == 0 || $count <= 10) ? '' : $pager; //每10条记录作为一页
        $parameter =array(
            'page'       => $page,
            'contents'  => $contents,
            'condition' => $condition,
            'nums'       => $count
        );
        return $this->render('AdminBundle:User:teacherIndex.html.twig',array('pages' => $pages,'parameter'=>$parameter,'tdata'=>$tdata));
    }
    /**
     *描述：单个删除老师信息
     */
    public function teacherDeleteAction(){
        if(isset($_POST['id'])){
            $id = $_POST['id'];
        }
        $conn = $this->get('database_connection');
        $category = $conn->update('xc_instructor',array('status'=>7),array('id'=>$id));
        return new Response('ok');
    }

    /**
     * @return Response
     * 描述：批量删除老师信息
     */
    public function teacherRemoveAction(){
        try{
            $Ids    = $_POST['Ids'];
            $idList = urldecode($Ids);
            $datas  = explode(',',$idList);
            $conn   = $this -> get('database_connection');
            for($i=0;$i < count($datas)-1;$i++){
                $category = $conn->update('xc_instructor',array('status'=>7),array('id'=>$datas[$i]));
            }
            return new Response('ok');
        }
        catch(Exception $e){
            return new Response('no');
        }
    }

    /**
     * @return Response
     */
    public function teacherAddPageAction(){
        return $this->render('AdminBundle:User:teacherAddPage.html.twig');
    }
    public function teacherAddAction(){
        $name       = isset($_REQUEST['name'])?$_REQUEST['name']:' ';
        $title      = isset($_REQUEST['title'])?$_REQUEST['title']:' ';
        $brief      = isset($_REQUEST['brief'])?$_REQUEST['brief']:' ';
        $cellphone  = isset($_REQUEST['cellphone'])?$_REQUEST['cellphone']:' ';
        $avatar     = isset($_REQUEST['avatar_large'])?$_REQUEST['avatar_large']:'';
        if($avatar == ''){
            $avatar='assets/img/people_heand.jpg';
        }
        $conn       = $this->get('database_connection');
        $instructor = $conn->insert('xc_instructor', array('name'=>$name, 'title'=>$title,'brief'=>$brief,'cellphone'=>$cellphone,'avatar'=>$avatar,'status'=>4));
        return $this->redirect($this->generateUrl('AdminBundle_User_teacher'));
    }
    public function teacherUpdatePageAction(){
        $iid     = isset($_GET['id'])?$_GET['id']:' ';
        $conn    = $this -> get('database_connection');
        $sql     = "select i.`id`, i.`name`, i.`title`, i.`brief`, i.`avatar`, i.`cellphone` from xc_instructor i
        where i.`id`='".$iid."' limit 0,1";
        $result  = $conn->query($sql)->fetchAll();
        return $this->render('AdminBundle:User:teacherUpdatePage.html.twig',array('tdata'=>$result[0]));
    }
    public function teacherUpdateAction(){
        $id        = isset($_REQUEST['tid'])?$_REQUEST['tid']:' ';
        $name       = isset($_REQUEST['name'])?$_REQUEST['name']:' ';
        $title      = isset($_REQUEST['title'])?$_REQUEST['title']:' ';
        $brief      = isset($_REQUEST['brief'])?$_REQUEST['brief']:' ';
        $cellphone  = isset($_REQUEST['cellphone'])?$_REQUEST['cellphone']:' ';
        $avatar     = isset($_REQUEST['avatar_large'])?$_REQUEST['avatar_large']:'assets/img/people_heand.jpg';
        if($avatar == ''){
            $avatar = 'assets/img/people_heand.jpg';
        }

        $conn       = $this -> get('database_connection');
        $instructor = $conn->update('xc_instructor',array('name'=>$name, 'title'=>$title,'brief'=>$brief,'cellphone'=>$cellphone,'avatar'=>$avatar),array('id'=>$id));
        return $this->redirect($this->generateUrl('AdminBundle_User_teacher'));
    }
    /**
     * 描述：会员类表显示
     */
    public function memberAction(){
        $contents   = isset($_REQUEST['contents'])?trim($_REQUEST['contents']):'';
        $condition  = isset($_REQUEST['condition'])?trim($_REQUEST['condition']):'';
        $timeSelect = isset($_REQUEST['timeSelect'])?trim($_REQUEST['timeSelect']):'';
        $startTime  = isset($_REQUEST['startTime'])?trim($_REQUEST['startTime']):'';
        $endTime    = isset($_REQUEST['endTime'])?trim($_REQUEST['endTime']):'';
        $userType   = isset($_REQUEST['userType'])?trim($_REQUEST['userType']):'';

        $conn   = $this->get('database_connection'); //获取数据库连接对象
        $sql    = "select m.id, m.avatar_large, m.source_id, m.third_user_id, m.nickname, m.email, m.cellphone, m.create_time, m.last_login,  m.status from xc_member m where m.status<>4 or m.status is null ";
        //全局搜索条件
        if($condition == ''){
            $sql .= " and (m.id like '%".$contents ."%' or  m.third_user_id like '%".$contents ."%'  or m.nickname like '%".$contents ."%' or m.email like '%".$contents ."%' or m.cellphone like '%".$contents ."%')";
        }elseif($condition == '1'){
            $sql .= " and m.id like '%".$contents ."%'";
        }elseif($condition == '2'){
            $sql .= " and m.third_user_id like '%".$contents ."%'";
        }elseif($condition == '3'){
            $sql .= " and m.nickname like '%".$contents ."%'";
        }elseif($condition == '4'){
            $sql .= " and m.email like '%".$contents ."%'";
        }elseif($condition == '5'){
            $sql .= " and m.cellphone like '%".$contents ."%'";
        }
        //时间收索条件
        if($timeSelect == '1'){
            if($startTime != '' && $endTime != ''){
                $sql = $sql." and m.create_time between '".$startTime."' and '".$endTime."'";
            }
            else
                if($startTime != ''){
                    $sql=$sql." and m.create_time >= '".$startTime."'";
                }
                else
                    if($endTime != ''){
                        $sql=$sql." and m.create_time <= '".$endTime."'";
                    }
        }elseif($timeSelect == '2'){
            if($startTime != '' && $endTime != ''){
                $sql = $sql." and m.last_login between '".$startTime."' and '".$endTime."'";
            }
            else
                if($startTime != ''){
                    $sql=$sql." and m.last_login >= '".$startTime."'";
                }
                else
                    if($endTime != ''){
                        $sql=$sql." and m.last_login <= '".$endTime."'";
                    }
        }
        //用户类型搜索条件
        if($userType == '1'){
            $sql=$sql." and m.source_id is NULL";
        }elseif($userType == '2'){
            $sql=$sql." and m.source_id = 2";
        }elseif($userType == '3'){
            $sql=$sql." and m.source_id = 1";
        }
        $query  = $conn->query($sql);
        $result = $query->fetchAll();
        $count=count($result);
        $request = Request::createFromGlobals()->query;

        $params['page'] = $request->get('page');  //当前页码数
        $limit = 10; //页面限制条数
        $page = empty($params['page']) ? 1 : $params['page']; //当前页数初始化
        $offset = ($page - 1)*$limit; //计算分页偏移量
        $mdata  =  array_slice($result,$offset,10);
        $pagination = $this->container->get('PagePaginationServices'); //获取分页services
        $pager = $pagination->getNavigation($count, $limit, $page, 'member', $request); //分页处理：总数，每页条数，页码，action名称，搜索参数
        $pages = ($count == 0 || $count <= 10) ? '' : $pager; //每10条记录作为一页
        $parameter =array(
            'page'        => $page,
            'contents'   => $contents,
            'condition'  => $condition,
            'nums'        => $count,
            'timeSelect' => $timeSelect,
            'startTime'  => $startTime,
            'endTime'    => $endTime,
            'userType'   => $userType
        );
        return $this->render('AdminBundle:User:memberIndex.html.twig',array('pages' => $pages,'parameter'=>$parameter,'mdata'=>$mdata));
    }

    /**
     *描述：单个删除会员信息
     */
    public function memberDeleteAction(){
        if(isset($_POST['id'])){
            $id = $_POST['id'];
        }
        $conn = $this->get('database_connection');
        $category = $conn->update('xc_member',array('status'=>7),array('id'=>$id));
        return new Response('ok');
    }
    /**
     * @return Response
     * 描述：批量删除老师信息
     */
    public function memberRemoveAction(){
        try{
            $Ids    = $_POST['Ids'];
            $idList = urldecode($Ids);
            $datas  = explode(',',$idList);
            $conn   = $this -> get('database_connection');
            for($i=0;$i < count($datas)-1;$i++){
                $category = $conn->update('xc_member',array('status'=>7),array('id'=>$datas[$i]));
            }
            return new Response('ok');
        }
        catch(Exception $e){
            return new Response('no');
        }
    }

    /**
     * 描述：用户反馈管理列表
     * 时间：2014-11-03
     * author：wzl
     */
    public function feedbackAction(){
        $conn   = $this->get('database_connection'); //获取数据库连接对象
        $sql    = "select f.id, f.email, f.content, f.create_time, f.status from xc_feedback f where f.status order by f.create_time desc";
        //全局搜索条件
        $query  = $conn->query($sql);
        $result = $query->fetchAll();

        $count   = count($result);
        $request = Request::createFromGlobals()->query;

        $params['page'] = $request->get('page');  //当前页码数
        $limit  = 10; //页面限制条数
        $page   = empty($params['page']) ? 1 : $params['page']; //当前页数初始化
        $offset = ($page - 1)*$limit; //计算分页偏移量
        $fdata  =  array_slice($result,$offset,$limit);
        $pagination = $this->container->get('PagePaginationServices'); //获取分页services
        $pager = $pagination->getNavigation($count, $limit, $page, 'feedback', $request); //分页处理：总数，每页条数，页码，action名称，搜索参数
        $pages = ($count == 0 || $count <= $limit) ? '' : $pager; //每10条记录作为一页
        $parameter =array(
            'page'        => $page,
        );
        return $this->render('AdminBundle:User:feedbackIndex.html.twig',array('pages' => $pages,'parameter'=>$parameter,'fdata'=>$fdata));
    }



}



