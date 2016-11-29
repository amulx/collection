<?php
/**
 * Created by PhpStorm.
 * User: amu
 * Date: 15-5-19
 * Time: 下午6:24
 */

namespace Biye\AdminBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
class OrderFromController extends Controller{

    public function orderlistAction(){

        //        页面输入框输入的信息
        $contents   = isset($_REQUEST['contents'])?trim($_REQUEST['contents']):'';
        //        选择的条件
        $condition  = isset($_REQUEST['condition'])?trim($_REQUEST['condition']):'';
        $timeSelect = isset($_REQUEST['timeSelect'])?trim($_REQUEST['timeSelect']):'';
        $createTime  = isset($_REQUEST['createTime'])?trim($_REQUEST['createTime']):'';
        $lastLogin    = isset($_REQUEST['lastLogin'])?trim($_REQUEST['lastLogin']):'';
        //        用户角色
        $userType   = isset($_REQUEST['userType'])?trim($_REQUEST['userType']):'';
        //        用户状态
        $vendorStatus = isset($_REQUEST['vendorStatus'])?trim($_REQUEST['vendorStatus']):'';

        $conn   = $this->get('database_connection'); //获取数据库连接对象
        $sqlcar    = "select * from imooc_car";

        $query  = $conn->query($sqlcar);
        $resultcar = $query->fetchAll();


        $sqlpro ="select * from imooc_pro";
        $query1 = $conn->query($sqlpro);
        $resultpro = $query1->fetchAll();

        $sqlalbum = "select * from imooc_album";
        $query2 = $conn->query($sqlalbum);
        $resultalbum = $query2->fetchAll();



        $count=count($resultcar);
        $request = Request::createFromGlobals()->query;

        $params['page'] = $request->get('page');  //当前页码数
        $limit = 10; //页面限制条数
        $page = empty($params['page']) ? 1 : $params['page']; //当前页数初始化
        $offset = ($page - 1)*$limit; //计算分页偏移量
        $tdata  =  array_slice($resultcar,$offset,10);
        $pagination = $this->container->get('PagePaginationServicesAdmin'); //获取分页services
        $pager = $pagination->getNavigation($count, $limit, $page, 'orderlist', $request); //分页处理：总数，每页条数，页码，action名称，搜索参数
        $pages = ($count == 0 || $count <= 10) ? '' : $pager; //每10条记录作为一页
        $parameter =array(
            'page'        => $page,
            'contents'   => $contents,
            'condition'  => $condition,
            'nums'        => $count,
            'timeSelect' => $timeSelect,
            'createTime'  => $createTime,
            'lastLogin'    => $lastLogin,
            //用户角色
            'userType'   => $userType,
            //用户状态
            'vendorStatus' => $vendorStatus
        );
        return $this->render('BiyeAdminBundle:OrderFrom:orderlist.html.twig',array('pages' => $pages,'parameter'=>$parameter,'tdata'=>$tdata,'resultpro'=>$resultpro,'resultalbum'=>$resultalbum));


    }

} 