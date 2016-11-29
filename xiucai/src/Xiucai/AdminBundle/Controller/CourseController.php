<?php
namespace Xiucai\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Xiucai\StoreBundle\Entity\XcCourse;
use Xiucai\StoreBundle\Entity\XcVideo;

class CourseController extends Controller
{
    public function indexAction(){
        $priority  = isset($_REQUEST['priority'])?trim($_REQUEST['priority']):'';
        $contents  = isset($_REQUEST['contents'])?trim($_REQUEST['contents']):'';
        $condition = isset($_REQUEST['condition'])?trim($_REQUEST['condition']):'';

        $conn   = $this->get('database_connection'); //获取数据库连接对象
        $sql    = "select c.id, c.category_id, c.name, c.tags, c.status, c.parent_category_id, c.zindex from xc_category c where c.status<>7";

        if($condition == ''){
            $sql .= " and (c.category_id like '%".$contents ."%' or c.name like '%".$contents ."%')";
        }elseif($condition == '1'){
            $sql .= " and c.category_id like '%".$contents ."%'";
        }elseif($condition == '2'){
            $sql .= " and c.name like '%".$contents ."%'";
        }else{

        }
        if($priority){
            if($priority == 1){
                $sql .= " order by c.zindex DESC";
            }else{
                $sql .= " order by c.zindex ASC";
            }

        }
        $query  = $conn->query($sql);
        $result = $query->fetchAll();
        $cdata  = array();
        foreach($result as $r){
            if($condition == '3'){
                $sqlp    = "select p.name from xc_category p where p.category_id = '".$r['parent_category_id']."' and p.name like '%".$contents ."%' limit 0,1";
                $queryp  = $conn->query($sqlp);
                $resultp = $queryp->fetchAll();
                if($resultp){
                    $cdata[] = array(
                        'id'          => $r['id'],
                        'categoryId' => $r['category_id'],
                        'cname'       => $r['name'],
                        'pname'       => $resultp[0]["name"],
                        'tags'        => $r['tags'],
                        'rank'        => $this->tiering($r['category_id']),
                        'zindex'      => $r['zindex']
                    );
                }

            }else{
                $sqlp    = "select p.name from xc_category p where p.category_id = '".$r['parent_category_id']."' limit 0,1";
                $queryp  = $conn->query($sqlp);
                $resultp = $queryp->fetchAll();
                $cdata[] = array(
                    'id'          => $r['id'],
                    'categoryId' => $r['category_id'],
                    'cname'       => $r['name'],
                    'pname'       => $resultp?$resultp[0]["name"]: "无父级",
                    'tags'        => $r['tags'],
                    'rank'        => $this->tiering($r['category_id']),
                    'zindex'      => $r['zindex']
                );
            }

        }
        $count=count($cdata);
        $request = Request::createFromGlobals()->query;

        $params['page'] = $request->get('page');  //当前页码数
        $limit = 10; //页面限制条数
        $page = empty($params['page']) ? 1 : $params['page']; //当前页数初始化
        $offset = ($page - 1)*$limit; //计算分页偏移量
        $cdata  =  array_slice($cdata,$offset,10);
        $pagination = $this->container->get('PagePaginationServices'); //获取分页services
        $pager = $pagination->getNavigation($count, $limit, $page, 'index', $request); //分页处理：总数，每页条数，页码，action名称，搜索参数
        $pages = ($count == 0 || $count <= 10) ? '' : $pager; //每10条记录作为一页
        $parameter =array(
            'priority'  => $priority,
            'page'       =>  $page,
            'contents'  => $contents,
            'condition' => $condition,
            'nums'        => $count
        );
        return $this->render('AdminBundle:Course:index.html.twig',array('pages' => $pages,'cdata' =>$cdata,'parameter' =>$parameter));
    }

    public function categoryDeleteAction(){
        if(isset($_POST['id'])){
            $id = $_POST['id'];
        }
        $conn = $this->get('database_connection');
        $category = $conn->update('xc_category',array('status'=>7),array('id'=>$id));
        return new Response('ok');
    }

    public function categoryAddAction(){
        $cid       = isset($_REQUEST['categoryId'])?$_REQUEST['categoryId']:' ';
        $name      = isset($_REQUEST['name'])?$_REQUEST['name']:' ';
        $tags      = isset($_REQUEST['tags'])?$_REQUEST['tags']:' ';
        $zindex    = isset($_REQUEST['zindex'])?$_REQUEST['zindex']:'10000';
        $pid       = isset($_REQUEST['pid'])?$_REQUEST['pid']:' ';
        $two_cid   = isset($_REQUEST['twocid'])?$_REQUEST['twocid']:' ';

        if($zindex == ''){
            $zindex = 10000;
        }
       // $three_cid = isset($_REQUEST['threecid '])?$_REQUEST['threecid ']:' ';
//        if($three_cid){
//            $uid = $three_cid;
//        }else
        if($two_cid){
            $uid = $two_cid;
        }else{
            $uid = $pid;
        }
        $conn      = $this->get('database_connection');
        $category  = $conn->insert('xc_category', array('category_id'=>$cid, 'name'=>$name,'tags'=>$tags,'zindex'=>$zindex,'parent_category_id'=>$uid,'status'=>4));
        return $this->redirect($this->generateUrl('AdminBundle_Course_index'));
    }

    public function categoryAddPageAction(){
        $conn   = $this->get('database_connection');
        $sql    = "select distinct c.category_id, c.name from xc_category c where c.parent_category_id=0";
        $result = $conn->query($sql)->fetchAll();
        return $this->render('AdminBundle:Course:categoryAddPage.html.twig',array('pdata'=>$result));
    }

    public function categoryUpdatePageAction(){
        $cid     = isset($_GET['id'])?$_GET['id']:' ';
        $twodata = array();
        $pdata   = array();
        $flag    = '';
        $two_pid = '';
        $two_cid = '';
        $conn    = $this -> get('database_connection');
        $sql     = "select c.`id`, c.`category_id`, c.`name`, c.`tags`, c.`status`, c.`parent_category_id`, c.`zindex` from xc_category c
        where c.`id`='".$cid."' limit 0,1";
        $result  = $conn->query($sql)->fetchAll();
       // $two_id  =
        $r_cid   =$result[0]['category_id'];
        $r_pid   =$result[0]['parent_category_id'];
        $rank = $this->tiering($r_cid);

        if($rank == '二级类'){
          //  $two_pid = $r_cid;
            $two_pid = $r_pid;
            $flag    = 1;
        }
        if($rank == '三级类'){
            $sqlt    = "select  c.category_id,c.parent_category_id from xc_category c where c.category_id='".$r_pid."' limit 0,1";
            $re      = $conn->query($sqlt)->fetchAll();
            if($re){
                $two_cid  = $re[0]['category_id'];
                $two_pid  = $re[0]['parent_category_id'];
                $sqltwo  = "select distinct c.category_id, c.name from xc_category c where c.parent_category_id='".$two_pid."'";
                $twodata = $conn->query($sqltwo)->fetchAll();
            }
        }
        if($rank == '四级类'){

        }
        $rank_id = array(
            'r_cid'   => $r_cid,
            'r_pid'   => $r_pid,
            'two_pid' => $two_pid,
            'two_cid' => $two_cid
        );
        $sqlp  = "select distinct c.category_id, c.name from xc_category c where c.parent_category_id=0";
        $pdata = $conn->query($sqlp)->fetchAll();
        return $this->render('AdminBundle:Course:categoryUpdatePage.html.twig',array('cdata'=>$result[0],'pdata'=>$pdata,'twodata'=>$twodata, 'flag'=>$flag,'rank_id'=>$rank_id));

    }
    public function categoryUpdateAction(){
        $id        = isset($_REQUEST['id'])?$_REQUEST['id']:' ';
        $cid       = isset($_REQUEST['categoryId'])?$_REQUEST['categoryId']:' ';
        $name      = isset($_REQUEST['name'])?$_REQUEST['name']:' ';
        $tags      = isset($_REQUEST['tags'])?$_REQUEST['tags']:' ';
        $zindex    = isset($_REQUEST['zindex'])?$_REQUEST['zindex']:'10000';
        $pid       = isset($_REQUEST['pid'])?$_REQUEST['pid']:' ';
        $two_cid   = isset($_REQUEST['twocid'])?$_REQUEST['twocid']:' ';
//        $three_cid = isset($_REQUEST['threecid '])?$_REQUEST['three_cid ']:' ';
//        if($three_cid != ' '){
//            $uid = $three_cid;
//        }else
        if($zindex == ''){
            $zindex = 10000;
        }
        if($two_cid){
            $uid = $two_cid;
        }else{
            $uid = $pid;
        }
        $conn     = $this -> get('database_connection');
        $category = $conn->update('xc_category',array('category_id'=>$cid, 'name'=>$name,'tags'=>$tags,'zindex'=>$zindex,'parent_category_id'=>$uid),array('id'=>$id));
        return $this->redirect($this->generateUrl('AdminBundle_Course_index'));
    }

    public function categoryRemoveAction(){
        try{
            $Ids    = $_POST['Ids'];
            $idList = urldecode($Ids);
            $datas  = explode(',',$idList);
            $conn   = $this -> get('database_connection');
            for($i=0;$i < count($datas)-1;$i++){
                $category = $conn->update('xc_category',array('status'=>7),array('id'=>$datas[$i]));
            }
            return new Response('ok');
        }
        catch(Exception $e){
            return new Response('no');
        }
    }

    /**
     * 描述：判断重复分类ID
     */
    public function categoryRedoAction(){
        try{
            if(isset($_POST['categoryId'])){
                $id = $_POST['categoryId'];
            }
            if(isset($_POST['dataId'])){
                $data_id = $_POST['dataId'];
            }else{
                $data_id = '';
            }

            $conn = $this->get('database_connection');
            if($data_id){
                $sql    = "select c.id from xc_category c where c.category_id='".$id."' and c.id <> '".$data_id."'";
            }else{
                $sql    = "select c.id from xc_category c where c.category_id='".$id."'";
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
     * 描述：返回二级类
     */
    function categoryTwoAction(){
        $first_cid    = $_POST['first_cid'];
        $conn   = $this->get('database_connection');
        $sql    = "select c.category_id, c.name from xc_category c where c.parent_category_id='".$first_cid."'";
        $result = $conn->query($sql)->fetchAll();
        $html = '';

        if($first_cid >0){
            if($result){
                $html .='<option value=""></option>';
                foreach($result as $r){
                    $html .='<option value="'.$r['category_id'].'">'.$r['name'].'</option>';
                }
            }else{
                $html .='<option value=""></option>';
            }

        }else{
            $html .='<option value=""></option>';
        }

        return new Response($html);
    }
    /**
     * 描述：返回三级类
     */
    function categoryThreeAction(){
        $two_cid = $_POST['two_cid'];
        $conn    = $this->get('database_connection');
        $sql     = "select c.category_id, c.name from xc_category c where c.parent_category_id='".$two_cid."'";
        $result  = $conn->query($sql)->fetchAll();
        $html = '';
        if($result){
            $html .= '<select name="threecid" id="three_cid" onchange="three_level()">';
            foreach($result as $r){
                $html .='<option value="'.$r['category_id'].'">'.$r['name'].'</option>';
            }
            $html .='</select>';
        }

        return new Response($html);
    }
    /**
     * 描述：返回四级类
     */
    function categoryFourAction(){
        $four_cid    = $_POST['four_cid'];
        $conn   = $this->get('database_connection');
        $sql    = "select c.category_id, c.name from xc_category c where c.parent_category_id='".$four_cid."'";
        $result = $conn->query($sql)->fetchAll();
        $html = '';
        if($result){
            $html .= '<select name="fourcid" id="four_cid">';
            foreach($result as $r){
                $html .='<option value="'.$r['category_id'].'">'.$r['name'].'</option>';
            }
            $html .='</select>';
        }
        return new Response($html);
    }
    /**
     * 描述：级别划分
     * 时间：2014-9-30
     */
    public function tiering($cid){
        if(strlen($cid) == 8){
            $word_one   = substr($cid,0,2);
            $word_two   = substr($cid,2,2);
            $word_three = substr($cid,4,2);
            $word_four  = substr($cid,6,2);
            if($word_one > 0 && $word_two == 0 && $word_three == 0 && $word_four == 0){
                return "一级类";
            }
            if($word_one > 0 && $word_two > 0 && $word_three == 0 && $word_four == 0){
                return "二级类";
            }
            if($word_one > 0 && $word_two > 0 && $word_three > 0 && $word_four == 0){
                return "三级类";
            }
            if($word_one > 0 && $word_two > 0 && $word_three > 0 && $word_four > 0){
                return "四级类";
            }
            return null;
        }else{
            return null;
        }

    }

    /**
     * @return Response
     * 描述：点播列表
     */
    public function vodAction(){
        $contents    = isset($_REQUEST['contents'])?trim($_REQUEST['contents']):'';
        $condition   = isset($_REQUEST['condition'])?trim($_REQUEST['condition']):'';
        $timeSelect  = isset($_REQUEST['timeSelect'])?trim($_REQUEST['timeSelect']):'';
        $startTime   = isset($_REQUEST['startTime'])?trim($_REQUEST['startTime']):'';
        $endTime     = isset($_REQUEST['endTime'])?trim($_REQUEST['endTime']):'';
        $oneCategory = isset($_REQUEST['oneCategory'])?trim($_REQUEST['oneCategory']):'';
        $twocid      = isset($_REQUEST['twocid'])?trim($_REQUEST['twocid']):'';
        $vodStatus   = isset($_REQUEST['vodStatus'])?trim($_REQUEST['vodStatus']):'';
        $courseLevel = isset($_REQUEST['courseLevel'])?trim($_REQUEST['courseLevel']):'';


        $conn   = $this->get('database_connection'); //获取数据库连接对象
        $sql    = "select v.id, v.title, v.category_id, v.instructor_id, v.current_price, v.create_time, v.modify_time, v.course_level,
        v.reserve_init, v.reserve_num, v.duration, v.status,c.name cname,i.name iname
        from xc_course v left join xc_category c on v.category_id=c.category_id
         left join xc_instructor i on v.instructor_id=i.id where v.id>0";

//        全局搜索条件
        if($condition == ''){
            $sql .= " and (v.id like '%".$contents ."%' or v.title like '%".$contents ."%'  or i.name like '%".$contents ."%')";
        }elseif($condition == '1'){
            $sql .= " and v.id like '%".$contents ."%'";
        }elseif($condition == '2'){
            $sql .= " and v.title like '%".$contents ."%'";
        }elseif($condition == '3'){
            $sql .= " and i.name like '%".$contents ."%'";
        }
        //时间收索条件
        if($timeSelect == '1'){
            if($startTime != '' && $endTime != ''){
                $sql = $sql." and v.modify_time between '".$startTime."' and '".$endTime."'";
            }
            else
                if($startTime != ''){
                    $sql=$sql." and v.modify_time >= '".$startTime."'";
                }
                else
                    if($endTime != ''){
                        $sql=$sql." and v.modify_time <= '".$endTime."'";
                    }
        }

        //课程级别搜索条件
        if($courseLevel == '1'){
            $sql=$sql." and v.course_level = 1";
        }elseif($courseLevel == '2'){
            $sql=$sql." and v.course_level = 2";
        }elseif($courseLevel == '3'){
            $sql=$sql." and v.course_level = 3";
        }
        //课程状态搜索条件
        if($vodStatus == ''){
            $sql=$sql." and v.status <> 7";
        }elseif($vodStatus == '1'){
            $sql=$sql." and v.status = 2";
        }elseif($vodStatus == '2'){
            $sql=$sql." and v.status = 4";
        }elseif($vodStatus == '3'){
            $sql=$sql." and v.status = 7";
        }
        //课程类别搜索条件
        if($twocid){
            $b_twocid = $twocid + 10000;
            $sql=$sql." and v.category_id >= '".$twocid."' and v.category_id < '".$b_twocid."'";
        }elseif($oneCategory){
            $b_oneCategory = $oneCategory + 1000000;
            $sql=$sql." and v.category_id >= '".$oneCategory."' and v.category_id < '".$b_oneCategory."'";
        }
        $sql=$sql." order by v.create_time desc";
        $query  = $conn->query($sql);
        $result = $query->fetchAll();

        $count=count($result);
        $request = Request::createFromGlobals()->query;

        $params['page'] = $request->get('page');  //当前页码数
        $limit = 10; //页面限制条数
        $page = empty($params['page']) ? 1 : $params['page']; //当前页数初始化
        $offset = ($page - 1)*$limit; //计算分页偏移量
        $vdata  =  array_slice($result,$offset,10);
        $pagination = $this->container->get('PagePaginationServices'); //获取分页services
        $pager = $pagination->getNavigation($count, $limit, $page, 'vod', $request); //分页处理：总数，每页条数，页码，action名称，搜索参数
        $pages = ($count == 0 || $count <= 10) ? '' : $pager; //每10条记录作为一页
        $parameter =array(
            'page'         => $page,
            'contents'    => $contents,
            'condition'   => $condition,
            'nums'         => $count,
            'timeSelect'  => $timeSelect,
            'startTime'   => $startTime,
            'endTime'      => $endTime,
            'courseLevel' => $courseLevel,
            'oneCategory' => $oneCategory,
            'twocid'       => $twocid,
            'vodStatus'   => $vodStatus

        );
        $sqlc    = "select distinct c.category_id, c.name from xc_category c where c.parent_category_id=0";
        $cdata   = $conn->query($sqlc)->fetchAll();
        if($oneCategory){
            $sql_two    = "select distinct c.category_id, c.name from xc_category c where c.parent_category_id='".$oneCategory."'";
            $two_data   = $conn->query($sql_two)->fetchAll();
        }else{
            $two_data = array();
        }
        return $this->render('AdminBundle:Course:vodIndex.html.twig',array('pages' => $pages,'parameter'=>$parameter,'vdata'=>$vdata,'cdata'=>$cdata,'twodata'=>$two_data));
    }
    function updateVodStatusAction(){
        try{
            if(isset($_POST['id'])){
                $id = $_POST['id'];
            }
            if(isset($_POST['flag'])){
                $flag = $_POST['flag'];
            }
            $conn = $this->get('database_connection');
            if($flag == 1){
                $category = $conn->update('xc_course',array('status'=>2),array('id'=>$id));
            }elseif($flag == 2){
                $category = $conn->update('xc_course',array('status'=>4),array('id'=>$id));
            }
            return new Response('ok');
        }catch (Exception $e){
            return new Response('no');
        }
    }
    /**
     * 描述：点播返回二级类
     */
    function categoryTwoVodAction(){
        $first_cid    = $_POST['first_cid'];
        $conn   = $this->get('database_connection');
        $sql    = "select c.category_id, c.name from xc_category c where c.parent_category_id='".$first_cid."'";
        $result = $conn->query($sql)->fetchAll();
        $html = '';

        $html .= '<select name="twocid" id="two_cid" onchange="two_level()">';
        $html .='<option value="">全部</option>';
        if($first_cid >0){
            if($result){

                foreach($result as $r){
                    $html .='<option value="'.$r['category_id'].'">'.$r['name'].'</option>';
                }
            }else{
                $html .='<option value=""></option>';
            }

        }else{
            $html .='<option value=""></option>';
        }

        $html .='</select>';
        $html .='<input type="text" name="val" id="valid" value="111">';
        return new Response($html);
    }
    public function vodDeleteAction(){
        if(isset($_POST['id'])){
            $id = $_POST['id'];
        }
        $conn = $this->get('database_connection');
        $category = $conn->update('xc_course',array('status'=>7),array('id'=>$id));
        return new Response('ok');

    }
    public function vodRemoveAction(){
        try{
            $Ids    = $_POST['Ids'];
            $idList = urldecode($Ids);
            $datas  = explode(',',$idList);
            $conn   = $this -> get('database_connection');
            for($i=0;$i < count($datas)-1;$i++){
                $category = $conn->update('xc_course',array('status'=>7),array('id'=>$datas[$i]));
            }
            return new Response('ok');
        }
        catch(Exception $e){
            return new Response('no');
        }
    }
    public function liveAction(){
        $contents    = isset($_REQUEST['contents'])?trim($_REQUEST['contents']):'';
        $condition   = isset($_REQUEST['condition'])?trim($_REQUEST['condition']):'';
        $timeSelect  = isset($_REQUEST['timeSelect'])?trim($_REQUEST['timeSelect']):'';
        $startTime   = isset($_REQUEST['startTime'])?trim($_REQUEST['startTime']):'';
        $endTime     = isset($_REQUEST['endTime'])?trim($_REQUEST['endTime']):'';
        $oneCategory = isset($_REQUEST['oneCategory'])?trim($_REQUEST['oneCategory']):'';
        $twocid      = isset($_REQUEST['twocid'])?trim($_REQUEST['twocid']):'';
        $liveStatus  = isset($_REQUEST['liveStatus'])?trim($_REQUEST['liveStatus']):'';
        $courseLevel = isset($_REQUEST['courseLevel'])?trim($_REQUEST['courseLevel']):'';


        $conn   = $this->get('database_connection'); //获取数据库连接对象
        $sql    = "select l.id, l.title, l.category_id, l.instructor_id, l.create_time, l.current_price, l.schedule_time, l.course_level,
        l.reserve_init, l.reserve_num, l.duration, l.status, l.conf_id, c.name cname,i.name iname,f.conference_status conferenceStatus
        from xc_live_course l left join xc_category c on l.category_id=c.category_id
         left join xc_instructor i on l.instructor_id=i.id left join xc_conference f on l.conf_id= f.id where l.status <> 7";

//        全局搜索条件
        if($condition == ''){
            $sql .= " and (l.id like '%".$contents ."%' or l.title like '%".$contents ."%'  or i.name like '%".$contents ."%')";
        }elseif($condition == '1'){
            $sql .= " and l.id like '%".$contents ."%'";
        }elseif($condition == '2'){
            $sql .= " and l.title like '%".$contents ."%'";
        }elseif($condition == '3'){
            $sql .= " and i.name like '%".$contents ."%'";
        }
        //时间收索条件
        if($timeSelect == '1'){
            if($startTime != '' && $endTime != ''){
                $sql = $sql." and l.schedule_time between '".$startTime."' and '".$endTime."'";
            }
            else
                if($startTime != ''){
                    $sql=$sql." and l.schedule_time >= '".$startTime."'";
                }
                else
                    if($endTime != ''){
                        $sql=$sql." and l.schedule_time <= '".$endTime."'";
                    }
        }elseif($timeSelect == '2'){
            if($startTime != '' && $endTime != ''){
                $sql = $sql." and l.modify_time between '".$startTime."' and '".$endTime."'";
            }
            else
                if($startTime != ''){
                    $sql=$sql." and l.modify_time >= '".$startTime."'";
                }
                else
                    if($endTime != ''){
                        $sql=$sql." and l.modify_time <= '".$endTime."'";
                    }
        }

        //课程级别搜索条件
        if($courseLevel == '1'){
            $sql=$sql." and l.course_level = 1";
        }elseif($courseLevel == '2'){
            $sql=$sql." and l.course_level = 2";
        }elseif($courseLevel == '3'){
            $sql=$sql." and l.course_level = 3";
        }

        //会议状态搜索条件
        if($liveStatus == '1'){
            $sql=$sql." and f.conference_status = 1";
        }elseif($liveStatus == '3'){
            $sql=$sql." and f.conference_status = 3";
        }elseif($liveStatus == '6'){
            $sql=$sql." and f.conference_status = 6";
        }
        //课程类别搜索条件
        if($twocid){
            $b_twocid = $twocid + 10000;
            $sql=$sql." and l.category_id >= '".$twocid."' and l.category_id < '".$b_twocid."'";
        }elseif($oneCategory){
            $b_oneCategory = $oneCategory + 1000000;
            $sql=$sql." and l.category_id >= '".$oneCategory."' and l.category_id < '".$b_oneCategory."'";
        }
        $sql=$sql." order by l.create_time desc";
        $query  = $conn->query($sql);
        $result = $query->fetchAll();

        $count=count($result);
        $request = Request::createFromGlobals()->query;

        $params['page'] = $request->get('page');  //当前页码数
        $limit = 10; //页面限制条数
        $page = empty($params['page']) ? 1 : $params['page']; //当前页数初始化
        $offset = ($page - 1)*$limit; //计算分页偏移量
        $ldata  =  array_slice($result,$offset,10);
        $pagination = $this->container->get('PagePaginationServices'); //获取分页services
        $pager = $pagination->getNavigation($count, $limit, $page, 'live', $request); //分页处理：总数，每页条数，页码，action名称，搜索参数
        $pages = ($count == 0 || $count <= 10) ? '' : $pager; //每10条记录作为一页
        $parameter =array(
            'page'         => $page,
            'contents'    => $contents,
            'condition'   => $condition,
            'nums'         => $count,
            'timeSelect'  => $timeSelect,
            'startTime'   => $startTime,
            'endTime'      => $endTime,
            'courseLevel' => $courseLevel,
            'oneCategory' => $oneCategory,
            'twocid'       => $twocid,
            'liveStatus'  => $liveStatus

        );
        $sqlc    = "select distinct c.category_id, c.name from xc_category c where c.parent_category_id=0";
        $cdata   = $conn->query($sqlc)->fetchAll();
        if($oneCategory){
            $sql_two    = "select distinct c.category_id, c.name from xc_category c where c.parent_category_id='".$oneCategory."'";
            $two_data   = $conn->query($sql_two)->fetchAll();
        }else{
            $two_data = array();
        }
        return $this->render('AdminBundle:Course:liveIndex.html.twig',array('pages' => $pages,'parameter'=>$parameter,'ldata'=>$ldata,'cdata'=>$cdata,'twodata'=>$two_data));
    }

    /**
     * @return Response
     * 描述：编辑直播状态
     */
    function updateLiveStatusAction(){
        try{
            if(isset($_POST['id'])){
                $id = $_POST['id'];
            }
            if(isset($_POST['flag'])){
                $flag = $_POST['flag'];
            }
            $conn = $this->get('database_connection');
            if($flag == 1){
                $category = $conn->update('xc_live_course',array('status'=>2),array('id'=>$id));
            }elseif($flag == 2){
                $category = $conn->update('xc_live_course',array('status'=>4),array('id'=>$id));
            }
            return new Response('ok');
        }catch (Exception $e){
            return new Response('no');
        }
    }
    public function liveDeleteAction(){
        if(isset($_POST['id'])){
            $id = $_POST['id'];
        }
        $conn = $this->get('database_connection');
        $category = $conn->update('xc_live_course',array('status'=>7),array('id'=>$id));
        return new Response('ok');

    }
    public function liveRemoveAction(){
        try{
            $Ids    = $_POST['Ids'];
            $idList = urldecode($Ids);
            $datas  = explode(',',$idList);
            $conn   = $this -> get('database_connection');
            for($i=0;$i < count($datas)-1;$i++){
                $category = $conn->update('xc_live_course',array('status'=>7),array('id'=>$datas[$i]));
            }
            return new Response('ok');
        }
        catch(Exception $e){
            return new Response('no');
        }
    }


    /**
     * 添加发布录播课程
     * @param Request $request
     * @return Response
     */
    public function addCourseAction(Request $request){
        $emt = $this->getDoctrine()->getManager();
        $imgToken = substr(md5(time()+rand(100,30000)), 16).rand(100,999); //生成imgToken，上传图片生成的随机参数

        //表单数据处理操作
        if($request->getMethod() === "POST"){
            //上传课程图片处理
            $year = date("Y");
            $mDay = date('md');
            $uploadPath = $this->get('kernel')->getRootDir().'/../web/assets/uploads/'.$year.'/'.$mDay.'/';
            if(!is_dir($uploadPath) ){
                mkdir($uploadPath,0777,true);
            }

            //课程图片-小图
            if(!empty($_FILES["img_url"]["name"])){
                $imgToken = substr(md5(time()+rand(100,30000)), 16).rand(100,999);
                $lastPosition = strrpos($_FILES["img_url"]["name"], '.');
                $imgType = substr($_FILES["img_url"]["name"], $lastPosition);
                $imgName = $imgToken.$imgType;

                $targetUrl = $uploadPath.$imgName;
                move_uploaded_file($_FILES["img_url"]["tmp_name"], $targetUrl);
                $imgUrl = '/assets/uploads/'.$year.'/'.$mDay.'/'.$imgName;
            }else{
                $imgUrl = "";
            }

            //课程图片-大图
            if(!empty($_FILES["banner_url"]["name"])){
                $imgToken = substr(md5(time()+rand(100,30000)), 16).rand(100,999);
                $lastPosition = strrpos($_FILES["banner_url"]["name"], '.');
                $imgType = substr($_FILES["banner_url"]["name"], $lastPosition);
                $imgName = $imgToken.$imgType;

                $targetUrl = $uploadPath.$imgName;
                move_uploaded_file($_FILES["banner_url"]["tmp_name"], $targetUrl);
                $bannerUrl = '/assets/uploads/'.$year.'/'.$mDay.'/'.$imgName;
            }else{
                $bannerUrl = "";
            }

            $courseId = $request->get('course_id');
            if(!empty($courseId)){
                $course = $emt->getRepository('StoreBundle:XcCourse')->findOneBy(array('id' => $courseId));
            }else{
                $course = new XcCourse();
                $course->setVideoNum(0); //初始化点播课程视频数量
                $course->setReserveNum(0); //初始化点播课程预约数量
                $course->setCreateTime(new \DateTime(date("Y-m-d H:i:s")));

                //添加默认课程小图
                if(empty($imgUrl)){
                    $course->setImgUrl('/assets/img/zhibo_desc.jpg'); //课程图片
                }

                //添加默认课程大图
                if(empty($bannerUrl)){
                    $course->setBannerUrl('/assets/img/introduce_index.jpg'); //课程banner图片
                }
            }

            $reserveInit = $request->get('reserve_init'); //初始化播放人数
            $reserveInit = empty($reserveInit) ? 0 : $reserveInit;

            $categoryId = $request->get('course_kind'); //课程级别
            $categoryId = empty($categoryId) ? $request->get('parent_course_kind') : $categoryId;

            //设置课程小图
            if(!empty($imgUrl)){
                $course->setImgUrl($imgUrl); //课程图片
            }

            //设置课程banner图片
            if(!empty($bannerUrl)){
                $course->setBannerUrl($bannerUrl); //课程banner图片
            }
            $duration = $request->get('duration');
            $duration = empty($duration) ? 0 : $duration;
            $course->setDuration($duration); //课程时长
            $course->setTags($request->get('tags')); //课程标签
            $course->setCategoryId($categoryId); //课程类别分类
            $course->setReserveInit($reserveInit); //课程初始化播放人数
            $course->setStatus($request->get('status')); //课程状态
            $course->setContent($request->get('content')); //课程详细内容
            $course->setTitle($request->get('course_title')); //课程名称
            $course->setBrief($request->get('course_brief')); //课程简介
            $course->setCourseLevel($request->get('course_level')); //课程级别
            $course->setCurrentPrice($request->get('current_price')); //课程现价
            $course->setOriginalPrice($request->get('original_price')); //课程原价
            $course->setModifyTime(new \DateTime(date("Y-m-d H:i:s"))); //课程更新时间
            $course->setInstructorId($request->get('course_instructor')); //课程主讲
            $emt->persist($course); //添加点播课程
            $emt->flush();

            $url = $this->generateUrl('AdminBundle_Course_vod');
            echo "<script>location.href='".$url."'</script>";
            exit;
        }

        //获取课程信息
        $courseData = ""; //课程信息
        $cCategory = ""; //课程子当前二级分类
        $videoData = ""; //课程点播视频信息
        $categoryChild = ""; //课程所有二级分类
        $categoryParent = ""; //课程父级分类
        $courseInstructor = ""; //课程主讲

        $courseId = $request->get('id');
        if(!empty($courseId)){
            $courseData = $emt->getRepository('StoreBundle:XcCourse')->findOneBy(array('id' => $courseId));
            if(empty($courseData)){
                $url = $this->generateUrl('AdminBundle_Course_index');
                echo "<script>alert('该课程ID不存在');location.href='".$url."'</script>";
                exit;
            }

            //获取课程视频
            $videoData = $emt->getRepository('StoreBundle:XcVideo')
                ->findBy(array('contentId' => $courseId, 'contentType' => 1, 'status' => 4), array('zindex' => 'ASC'));

            //统计播放次数
            $playCount = 0;
            foreach($videoData as $video){
                $playCount += $video->getPlayCount();
            }
            $courseData->playCount = $playCount;

            //获取该主讲信息
            $courseInstructor = $emt->getRepository('StoreBundle:XcInstructor')
                ->findOneBy(array('id' => $courseData->getInstructorId()));

            //课程子集分类
            $categoryId = $courseData->getCategoryId();
            if(!empty($categoryId)){
                $categoryParent = $emt->getRepository('StoreBundle:XcCategory')
                    ->findOneBy(array('categoryId' => $courseData->getCategoryId(), 'parentCategoryId' => 0)); //父级分类
                if(empty($categoryParent)){
                    $cCategory = $emt->getRepository('StoreBundle:XcCategory')->findOneBy(array('categoryId' => $courseData->getCategoryId()));
                    if(!empty($cCategory)){
                        $categoryChild = $emt->getRepository('StoreBundle:XcCategory')
                            ->findBy(array('parentCategoryId' => $cCategory->getParentCategoryId())); //相同父级下的所有子集分类
                    }
                }else{
                    $categoryChild = $emt->getRepository('StoreBundle:XcCategory')
                        ->findBy(array('parentCategoryId' => $categoryParent->getCategoryId())); //相同父级下的所有子集分类
                }
            }
        }

        //获取所有主讲信息
        $courseTeacher = $emt->getRepository('StoreBundle:XcInstructor')->findAll();

        //课程父级分类
        $courseCategory = $emt->getRepository('StoreBundle:XcCategory')->findBy(array('parentCategoryId' => 0));

        return $this->render('AdminBundle:Course:add.html.twig',
            array(
                'imgToken' => $imgToken,
                'cCategory' => $cCategory,
                'videoData' => $videoData,
                'courseData' => $courseData,
                'courseTeacher' => $courseTeacher,
                'categoryChild' => $categoryChild,
                'categoryParent' => $categoryParent,
                'courseCategory' => $courseCategory,
                'courseInstructor' => $courseInstructor,
            )
        );
    }

    /**
     * ajax添加或编辑录播课程视频
     * @param Request $request
     * @return Response
     */
    public function ajaxCourseVideoAction(Request $request){
        $result['code'] = 300;
        $params = $request->get('params');
        if($request->getMethod() === "POST"){
            $emt = $this->getDoctrine()->getManager();
            if(!empty($params['course_id'])){
                if(!empty($params['video_id'])){
                    $video = $emt->getRepository('StoreBundle:XcVideo')->findOneBy(array('id' => $params['video_id']));
                }else{
                    $video = new XcVideo();
                    $video->setStatus(4);
                    $video->setPlayCount(0);
                    $video->setContentType(1);
                }
                $isFree = empty($params['is_free']) ? 0 : $params['is_free'];
                $video->setTags($params['tags']); //标签
                $video->setLength($params['length']); //时长
                $video->setIsFree($isFree); //是否免费
                $video->setUrl($params['video_path']); //视频路径
                $video->setContentId($params['course_id']); //相关课程ID
                $video->setTitle($params['chapter_title']); //视频名称
                $video->setThirdPartyId($params['third_party_id']); //第三方视频ID
                $emt->persist($video); //添加点播课程
                $emt->flush();

                if(empty($params['video_id'])){
                    //更新video_zIndex
                    $video->setZindex($video->getId());
                    $emt->persist($video); //更新zIndex
                    $emt->flush();
                }

                //更新课程视频数量
                $courseData = $emt->getRepository('StoreBundle:XcCourse')->findOneBy(array('id' => $params['course_id']));
                if(!empty($courseData)){
                    $courseVideoData = $emt->getRepository('StoreBundle:XcVideo')
                        ->findBy(array('contentId' => $params['course_id'], 'status' => 4));
                    if(empty($courseVideoData)){
                        $videoNum = 0;
                    }else{
                        $videoNum = count($courseVideoData);
                    }

                    $courseData->setVideoNum($videoNum);
                    $emt->persist($courseData); //更新点播课程视频数量
                    $emt->flush();
                }

                $result['code'] = 200;
                $result['id'] = $video->getId();
                $result['zIndex'] = $video->getZindex();
            }

            $videoId = $request->get('video_id');
            if(!empty($videoId)){
                //删除点播视频
                $videoInfo = $emt->getRepository('StoreBundle:XcVideo')->findOneBy(array('id' => $videoId));
                if(!empty($videoInfo)){
                    $videoInfo->setStatus(7);
                    $emt->persist($videoInfo); //更新视频删除状态
                    $emt->flush();

                    //更新课程视频数量
                    $courseData = $emt->getRepository('StoreBundle:XcCourse')->findOneBy(array('id' => $videoInfo->getContentId()));
                    if(!empty($courseData)){
                        $courseVideoData = $emt->getRepository('StoreBundle:XcVideo')
                            ->findBy(array('contentId' => $videoInfo->getContentId(), 'status' => 4));
                        if(empty($courseVideoData)){
                            $videoNum = 0;
                        }else{
                            $videoNum = count($courseVideoData) - 1;
                        }
                        $courseData->setVideoNum($videoNum);
                        $emt->persist($courseData); //更新点播课程视频数量
                        $emt->flush();
                    }

                    $result['code'] = 200;
                }else{
                    $result['code'] = 404;
                    $result['msg'] = "该视频不存在";
                }
            }
        }

        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * ajax获取课程级别分类
     * @param Request $request
     * @return Response
     */
    public function ajaxGetCategoryAction(Request $request){
        $result['code'] = 300;
        if($request->getMethod() === "POST"){
            $emt = $this->getDoctrine()->getManager();

            //获取课程分类
            $categoryId = $request->get('id');
            if(!empty($categoryId)){
                $category = $emt->getRepository('StoreBundle:XcCategory')->findBy(array('parentCategoryId' => $categoryId));
                if(!empty($category)){
                    foreach($category as $key => $cate){
                        $tempCate[$key]['name'] = $cate->getName();
                        $tempCate[$key]['category_id'] = $cate->getCategoryId();
                    }
                    $result['data'] = $tempCate;
                    $result['code'] = 200;
                }else{
                    $result['code'] = 404;
                }
            }

            //获取老师信息
            $instructorId = $request->get('instructor_id');
            if(!empty($instructorId)){
                $instructor = $emt->getRepository('StoreBundle:XcInstructor')->findOneBy(array('id' => $instructorId));
                if(!empty($instructor)){
                    $result['name'] = $instructor->getName();
                    $result['avatar'] = $instructor->getAvatar();
                    $result['code'] = 200;
                }else{
                    $result['code'] = 404;
                }
            }

            //获取课程视频
            $videoId = $request->get('video_id');
            if(!empty($videoId)){
                $video = $emt->getRepository('StoreBundle:XcVideo')->findOneBy(array('id' => $videoId));
                if(!empty($video)){
                    $result['id'] = $video->getId();
                    $result['url'] = $video->getUrl();
                    $result['tags'] = $video->getTags();
                    $result['title'] = $video->getTitle();
                    $result['length'] = $video->getLength();
                    $result['is_free'] = $video->getIsFree();
                    $result['third_party_id'] = $video->getThirdPartyId();
                    $result['code'] = 200;
                }else{
                    $result['code'] = 404;
                    $result['msg'] = "该视频不存在";
                }
            }
        }

        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * ajax排序点播课程
     * @param Request $request
     * @return Response
     */
    public function ajaxSortVideoAction(Request $request){
        $result['code'] = 300;
        if($request->getMethod() === "POST"){
            $conn = $this->get('database_connection'); //获取数据库连接对象
            $sortType = $request->get('sort_type');
            $sql = "SELECT id,zindex
              FROM `xc_video`
              WHERE status = 4 AND content_id = :content_id And content_type = 1 ";
            if($sortType == 'up_sort'){
                $sql .= 'AND zindex < :zIndex ORDER BY zindex DESC limit 1 ';
            }else if($sortType == "down_sort"){
                $sql .= 'AND zindex > :zIndex ORDER BY zindex ASC limit 1 ';
            }

            $where = array(':zIndex' => $request->get('zIndex'), ':content_id' => $request->get('content_id'));
            $ready = $conn->prepare($sql);
            $ready->execute($where); //搜索参数执行匹配
            $videoInfo = $ready->fetchAll();
            if(!empty($videoInfo)){
                $conn->update('xc_video',array('zindex'=> $request->get('zIndex')),array('id' => $videoInfo[0]['id']));
                $conn->update('xc_video',array('zindex' => $videoInfo[0]['zindex']),array('id'=> $request->get('video_id')));
                $result['new_zIndex'] = $videoInfo[0]['zindex'];
                $result['old_zIndex'] = $request->get('zIndex');

                $result['code'] = 200;
            }else{
                $result['code'] = 201;
            }

        }else{
            $result['msg'] = "错误的请求";
        }

        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
