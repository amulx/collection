<?php

namespace Xiucai\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Config\Definition\Exception\Exception;
use Xiucai\StoreBundle\Entity\XcOrder;
use Xiucai\StoreBundle\Entity\XcOrderItem;

class OrderController extends Controller
{
    public function listAction()
    {
        $contents   = isset($_REQUEST['contents'])?trim($_REQUEST['contents']):'';
        $condition  = isset($_REQUEST['condition'])?trim($_REQUEST['condition']):'';
        $timeSelect = isset($_REQUEST['timeSelect'])?trim($_REQUEST['timeSelect']):'';
        $startTime  = isset($_REQUEST['startTime'])?trim($_REQUEST['startTime']):'';
        $endTime    = isset($_REQUEST['endTime'])?trim($_REQUEST['endTime']):'';
        $type    = isset($_REQUEST['type'])?trim($_REQUEST['type']):'-1';
        $order_status    = isset($_REQUEST['order_status'])?trim($_REQUEST['order_status']):'-1';

        $conn   = $this->get('database_connection'); //获取数据库连接对象
        $sql    = "SELECT o.id, o.member_id, o.order_code, o.amount, o.order_status, o.create_time, o.type, m.nickname FROM xc_order o left join xc_member as m on o.member_id = m.id where 1 ";
        //全局搜索条件
        if($condition == ''){
            $sql .= " and (o.order_code like '%".$contents."%' or o.member_id = '$contents' or m.nickname like '%".$contents."%')";
        }elseif($condition == '1'){
            $sql .= " and o.order_code like '%".$contents."%'";
        }elseif($condition == '2'){
            $sql .= " and o.member_id = '$contents'";
        }elseif($condition == '3'){
            $sql .= " and m.nickname like '%".$contents."%'";
        }

        //时间收索条件
        if($timeSelect == '1'){
            if($startTime != '' && $endTime != ''){
                $sql .= " and o.create_time between '".$startTime."' and '".$endTime."'";
            }
            else
                if($startTime != ''){
                    $sql .= " and o.create_time >= '".$startTime."'";
                }
                else
                    if($endTime != ''){
                        $sql .= " and o.create_time <= '".$endTime."'";
                    }
        }
        //交易类型搜索条件
        if($type != '-1'){
            $sql .= " and o.type =".$type;
        }
        //交易类型搜索条件
        if($order_status != '-1'){
            $sql .= " and o.order_status =".$order_status;
        }
        $sql .= " order by o.id desc";
        //print_r($sql);
        $query  = $conn->query($sql);
        $orderList = $query->fetchAll();
        //print_r($orderList);
        $count=count($orderList);
        $request = Request::createFromGlobals()->query;
        $pageSize = 10;
        $params['page'] = $request->get('page');  //当前页码数
        $limit = $pageSize; //页面限制条数
        $page = empty($params['page']) ? 1 : $params['page']; //当前页数初始化
        $offset = ($page - 1)*$limit; //计算分页偏移量
        $orderList  =  array_slice($orderList,$offset,$pageSize);
        $pagination = $this->container->get('PagePaginationServices'); //获取分页services
        $pager = $pagination->getNavigation($count, $limit, $page, ' list', $request); //分页处理：总数，每页条数，页码，action名称，搜索参数
        $pages = ($count == 0 || $count <= $pageSize) ? '' : $pager; //每10条记录作为一页
        $parameter =array(
            'page'        => $page,
            'contents'   => $contents,
            'condition'  => $condition,
            'nums'        => $count,
            'timeSelect' => $timeSelect,
            'startTime'  => $startTime,
            'endTime'    => $endTime,
            'type'    => $type,
            'order_status'    => $order_status,
        );
        return $this->render('AdminBundle:Order:list.html.twig', array('pages' => $pages,'orderList' =>$orderList,'parameter' =>$parameter));
    }

    public function detailAction()
    {
        $id = isset($_GET['id'])?trim($_GET['id']):'';
        $conn   = $this->get('database_connection'); //获取数据库连接对象
        $sql_order    = "SELECT o.id, o.member_id, o.order_code, o.amount, o.order_status, o.create_time, o.update_time, o.operator_id, o.ip_address, o.transaction_id, o.transaction_status, o.type, m.nickname, m.avatar FROM xc_order o left join xc_member as m on o.member_id = m.id where o.id=$id ";
        $query  = $conn->query($sql_order);
        $orderDetail = $query->fetchAll();
        $sql_item    = "SELECT * FROM xc_order_item where order_id=$id";
        $query  = $conn->query($sql_item);
        $itemList = $query->fetchAll();
        foreach($itemList as $k1=>$v1){
            if($v1['content_type']==1){
                $table_name = 'xc_course';
                $sql_order_item = "SELECT id, title FROM $table_name where (id = :content_id)";
            }else if($v1['content_type']==2){
                $table_name = 'xc_live_course';
                $sql_order_item = "SELECT id, title FROM $table_name where (id = :content_id)";
            }
            $ready = $this->container->get('database_connection')->prepare($sql_order_item);
            $where = array(':content_id' => $v1['content_id']);
            $ready->execute($where);
            $lession = $ready->fetchAll();
            $itemList[$k1]['cid'] = $lession[0]['id'];
            $itemList[$k1]['title'] = $lession[0]['title'];
        }
        return $this->render('AdminBundle:Order:detail.html.twig', array('orderDetail' =>$orderDetail[0], 'itemList'=>$itemList));
    }

    public function cancelAction()
    {
        $id = isset($_POST['id'])?trim($_POST['id']):'';
        try{
            $em = $this->getDoctrine()->getManager();
            $item = $em->getRepository('StoreBundle:XcOrder')->findOneBy(array('id'=>$id));
            $item->setOrderStatus('3');
            $em->flush();
            $result['code'] = 1;
            $result['msg'] = '取消订单成功！';
        }catch(Exception $e){
            $result['msg'] = "Failed: ".$e->getMessage();
            $result['code'] = 0;
        }
        $response =  new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
