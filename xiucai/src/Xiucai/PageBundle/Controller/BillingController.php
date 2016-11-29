<?php

namespace Xiucai\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Config\Definition\Exception\Exception;
use Xiucai\ServiceBundle\Utilities\PaymentService;
/*use Xiucai\ServiceBundle\Command;
use Xiucai\ServiceBundle\Command\GreetCommand;
use Symfony\Component\Console\Application;*/
use Predis;
use Xiucai\ServiceBundle\Utilities\Cartservice;
use Xiucai\StoreBundle\Entity\XcOrder;
use Xiucai\StoreBundle\Entity\XcOrderItem;

class BillingController extends Controller
{
    /**
     * 选课单
     */
    public function cartAction(){
        $request = $this->getRequest();
        $session = $request->getSession();
        $member_id = $session->get('member_id');
        if(empty($member_id)){
            $this->location($this->generateUrl('PageBundle_page_index'),'请先登录！');
        }
        $data = self::_getCartInfo();
        return $this->render('PageBundle:Billing:cart.html.twig',array('cartInfo'=>$data['itemList'],'totalPrice'=>$data['totalPrice'],'count'=>$data['count']));
    }

    /**
     * ajax 购物车相关操作
     */
    public function ajaxCartAction(){

        $request = $this->getRequest();
        $session = $request->getSession();
        if ('POST' === $request->getMethod()) {
            if($session->get('member_id') == ''){
                $result['code'] = 2;
                $result['msg'] = "请先登录";
                $response =  new Response(json_encode($result));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }else{

                $type = $request->get('type');
                $content_type = $request->get('content_type');
                $content_id = $request->get('content_id');
                $result['code'] = 1;
                $result['msg'] = "Successful";
                $cart = $this->get('cart_service');
                switch ($type){
                    case '0':
                        //获取购物车信息
                        $result['data'] = $cart->info();
                        break;

                    case '1':
                        //加入课程
                        if(!empty($content_type)){
                            $item['content_type'] = $content_type;
                            $item['content_id'] = $content_id;

                            $cart->add($item);
                        }
                        break;

                    case '2':
                        //移除课程
                        if(!empty($content_type) && !empty($content_id)){
                            $cart->remove($content_type.'-'.$content_id);
                            $result['data'] = $cart->getTotalPrice();
                            $result['count'] = $cart->getCount();
                        }
                        break;
                    default:
                        //课程数量
                        $result['data'] = $cart->getCount();
                        break;
                }
                $response =  new Response(json_encode($result));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }
        }
    }

    /**
     * 确认订单
     */
    public function confirmorderAction()
    {
        $data = self::_getCartInfo();
        $request = $this->getRequest();
        $session = $request->getSession();
        $member_id = $session->get('member_id');
        if(empty($member_id)){
            $this->location($this->generateUrl('PageBundle_page_index'),'请先登录！');
        }
        if($data['count']==0){
            $this->location($this->generateUrl('PageBundle_billing_cart'),'您的选课单为空！');
        }
        return $this->render('PageBundle:Billing:confirmorder.html.twig',array('cartInfo'=>$data['itemList'],'totalPrice'=>$data['totalPrice'],'count'=>$data['count']));
    }

    /**
     * 我的订单
     */
    public function myorderAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        $member_id = $session->get('member_id');
        if(empty($member_id)){
            $this->location($this->generateUrl('PageBundle_page_index'),'请先登录！');
        }
        /*$em = $this->getDoctrine()->getManager();
        $query = $em->createQuery("SELECT xo.id id, xo.orderCode order_code, xo.amount amount, xo.payAmount pay_amount, xo.orderStatus order_status, xo.createTime create_time FROM StoreBundle:XcOrder xo WHERE xo.memberId = ?1 order by id desc");
        $query->setParameter(1, $member_id);
        $orderList = $query->getResult();*/
        $conn   = $this->get('database_connection'); //获取数据库连接对象
        $sql    = "SELECT  id, order_code, amount,  pay_amount, order_status, create_time FROM xc_order  WHERE member_id = $member_id order by id desc";
        $query  = $conn->query($sql);
        $orderList = $query->fetchAll();
        foreach($orderList as $key=>$value){
            /*$sql = "SELECT b.id, a.content_type, a.current_price, b.title
                FROM xc_order_item AS a
                LEFT JOIN (if a.content_type = '1' then xc_course elseif a.content_type = '2' then xc_live_course end if) AS b ON a.content_id = b.id
                WHERE (a.order_id = :order_id)";*/
            $sql = "SELECT content_id, content_type, current_price
                FROM xc_order_item WHERE (order_id = :order_id)";
            $where = array(':order_id' => $value['id']);
            $ready = $this->container->get('database_connection')->prepare($sql);
            $ready->execute($where);
            $itemList = $ready->fetchAll();
            foreach($itemList as $k1=>$v1){
                if($v1['content_type']==1){
                    $table_name = 'xc_course';
                    $sql_order_item = "SELECT c.id, c.title, t.name  FROM $table_name as c left join xc_instructor as t on c.instructor_id = t.id where (c.id = :content_id)";
                }else if($v1['content_type']==2){
                    $table_name = 'xc_live_course';
                    $sql_order_item = "SELECT c.id, c.title, t.name FROM $table_name as c left join xc_instructor as t on c.instructor_id = t.id where (c.id = :content_id)";
                }
                $ready = $this->container->get('database_connection')->prepare($sql_order_item);
                $where = array(':content_id' => $v1['content_id']);
                $ready->execute($where);
                $lession = $ready->fetchAll();
                $itemList[$k1]['id'] = $lession[0]['id'];
                $itemList[$k1]['title'] = $lession[0]['title'];
                $itemList[$k1]['instructor_name'] = $lession[0]['name'];
            }
            $orderList[$key]['item_list'] = $itemList;
            $orderList[$key]['count'] = count($itemList);
        }
        $count=count($orderList);
        $request = Request::createFromGlobals()->query;
        $pageSize = 10;
        $params['page'] = $request->get('page');  //当前页码数
        $limit = $pageSize; //页面限制条数
        $page = empty($params['page']) ? 1 : $params['page']; //当前页数初始化
        $offset = ($page - 1)*$limit; //计算分页偏移量
        $orderList  =  array_slice($orderList,$offset,$pageSize);
        $pagination = $this->container->get('PagePaginationServices'); //获取分页services
        $pager = $pagination->getNavigation($count, $limit, $page, ' myorder', $request); //分页处理：总数，每页条数，页码，action名称，搜索参数
        $pages = ($count == 0 || $count <= $pageSize) ? '' : $pager; //每10条记录作为一页
        $parameter =array(
            'page'       =>  $page,
            'nums'        => $count
        );
        return $this->render('PageBundle:Billing:myorder.html.twig',array('pages' => $pages,'orderList' =>$orderList,'parameter' =>$parameter));
    }

    public function cancelAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        $member_id = $session->get('member_id');
        $orderId = $request->get('orderId');
        if(empty($member_id)){
            $result['code'] = 0;
            $result['msg'] = '订单ID错误！';
        }else{
            $data = self::getOrder($orderId,$member_id);
            if(empty($data)){
                $result['code'] = 0;
                $result['msg'] = '订单ID错误！';
            }else{
                try{
                    $em = $this->getDoctrine()->getManager();
                    $item = $em->getRepository('StoreBundle:XcOrder')->findOneBy(array('id'=>$orderId));
                    $item->setOrderStatus('3');
                    $em->flush();
                    $result['code'] = 1;
                    $result['msg'] = '取消订单成功！';
                }catch(Exception $e){
                    $result['msg'] = "Failed: ".$e->getMessage();
                    $result['code'] = 0;
                }
            }
        }
        $response =  new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function accountAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        $member_id = $session->get('member_id');
        if(empty($member_id)){
            $this->location($this->generateUrl('PageBundle_page_index'),'请先登录！');
        }
        $conn   = $this->get('database_connection');
        $sql_billing    = "SELECT b.*, m.nickname, m.avatar FROM xc_billing as b left join xc_member as m on b.member_id = m.id where b.member_id='$member_id'";
        $query_billing  = $conn->query($sql_billing);
        $detail = $query_billing->fetchAll();
        if(empty($detail)){
            $detail[0] = array();
        }
        $sql_invoice    = "SELECT * FROM xc_invoice where member_id='$member_id' order by id desc";
        $query_invoice  = $conn->query($sql_invoice);
        $invoiceList = $query_invoice->fetchAll();
        return $this->render('PageBundle:Billing:account.html.twig',array('detail'=>$detail[0],'invoiceList'=>$invoiceList));
    }

    public function requestinvoiceAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        $member_id = $session->get('member_id');
        if(empty($member_id)){
            $this->location($this->generateUrl('PageBundle_page_index'),'请先登录！');
        }
        $conn   = $this->get('database_connection');
        $sql    = "SELECT * FROM xc_billing where member_id=$member_id";
        $query  = $conn->query($sql);
        $detail = $query->fetchAll();
        $sql_invoice    = "SELECT * FROM xc_invoice where status!=2 and member_id=$member_id";
        $query_query  = $conn->query($sql_invoice);
        $invoice_list = $query_query->fetchAll();
        $total_invoice = 0;
        foreach($invoice_list as $value)
        {
            $total_invoice += $value['amount'];
        }
        return $this->render('PageBundle:Billing:requestinvoice.html.twig', array('detail'=>$detail[0],'total_invoice'=>$total_invoice));
    }

    public function addinvoiceAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        $member_id = $session->get('member_id');
        if(empty($member_id)){
            $this->location($this->generateUrl('PageBundle_page_index'),'请先登录！');
        }
        $amount = isset($_POST['invoice_amount'])?trim($_POST['invoice_amount']):0;
        if($amount == 0){
            $this->location($this->generateUrl('PageBundle_Billing_requestinvoice'),'可索取金额为0！');
        }
        $title = isset($_POST['title'])?trim($_POST['title']):'';
        $type = isset($_POST['type'])?trim($_POST['type']):'';
        $contact_name = isset($_POST['contact_name'])?trim($_POST['contact_name']):'';
        $contact_number = isset($_POST['contact_number'])?trim($_POST['contact_number']):'';
        $address = isset($_POST['address'])?trim($_POST['address']):'';
        $postcode = isset($_POST['postcode'])?trim($_POST['postcode']):'';
        $conn = $this -> get('database_connection');
        try{
            if($conn->insert("xc_invoice",array('member_id'=>$member_id,'create_time'=>date('Y-m-d H:i:s',time()),'amount'=>$amount,'title'=>$title,'address'=>$address,'postcode'=>$postcode,'telephone'=>$contact_number,'recipient'=>$contact_name,'type'=>$type,'status'=>0)))
            {
                $conn->query("update xc_billing set address='$address', postcode='$postcode', contact_number='$contact_number', contact_name='$contact_name', company_name='$title' where member_id=$member_id");
            }
        }
        catch(Exception $e){
            return new Response($e->getMessage());;
        }
        return $this->redirect($this->generateUrl('PageBundle_billing_account'));
    }

    /**
     * 获取购物车页面所需信息
     */
    private function _getCartInfo(){
        $cart = $this->get('cart_service');
        $itemList = $cart->getItemList();
        $totalPrice = $cart->getTotalPrice();
        $count = $cart->getCount();
        return array('itemList'=>$itemList,'count'=>$count,'totalPrice'=>$totalPrice);
    }

    /**
     * 支付订单
     */
    public function payAction()
    {
        $request = $this->getRequest();
        $orderId = $request->get('orderId');
        $session = $request->getSession();
        $member_id = $session->get('member_id');
        if(empty($member_id)){
            $this->location($this->generateUrl('PageBundle_page_index'),'请先登录！');
        }
        if(empty($orderId)){
            $cart = $this->get('cart_service');
            $totalPrice = $cart->getTotalPrice();
            $count = $cart->getCount();
            if($count==0){
                $this->location($this->generateUrl('PageBundle_billing_cart'),'您的选课单为空！');
            }
            if($totalPrice == 0){
                $orderId = $cart->makeOrder();
                return $this->render('PageBundle:Billing:paysuccess.html.twig');
                exit;
            }
            $orderId = $cart->makeOrder();
        }else{
            $data = self::getOrder($orderId,$member_id);
            if(!empty($data)){
                if($data->getorderStatus() != 0){
                    $this->location($this->generateUrl('PageBundle_billing_cart'),'订单状态错误！');
                }
                $totalPrice = $data->getAmount();
                $count = count(self::getOrderItem($orderId));
            }else{
                $this->location($this->generateUrl('PageBundle_billing_myorder'),'订单ID错误！');
            }

        }
        return $this->render('PageBundle:Billing:pay.html.twig',array('totalPrice'=>$totalPrice,'count'=>$count,'orderId'=>$orderId));
    }

    public function paysuccessAction()
    {
        /*$request = $this->getRequest();
        $session = $request->getSession();
        $member_id = $session->get('member_id');
        $out_trade_no = $request->get('out_trade_no');
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery("SELECT xo.id id FROM StoreBundle:XcOrder xo WHERE xo.memberId = ?1 and xo.orderCode = ?2 order by id desc");
        $query->setParameter(1, $member_id);
        $query->setParameter(2, $out_trade_no);
        $orderList = $query->getResult();
        foreach($orderList as $key=>$value){
            $sql = "SELECT content_id, content_type, current_price
                FROM xc_order_item WHERE (order_id = :order_id)";
            $where = array(':order_id' => $value['id']);
            $ready = $this->container->get('database_connection')->prepare($sql);
            $ready->execute($where);
            $itemList = $ready->fetchAll();
            foreach($itemList as $k1=>$v1){
                if($v1['content_type']==1){
                    $table_name = 'xc_course';
                }else if($v1['content_type']==2){
                    $table_name = 'xc_live_course';
                }
                $sql_order_item = "SELECT id, title, img_url FROM $table_name where (id = :content_id)";
                $ready = $this->container->get('database_connection')->prepare($sql_order_item);
                $where = array(':content_id' => $v1['content_id']);
                $ready->execute($where);
                $lession = $ready->fetchAll();
                $itemList[$k1]['id'] = $lession[0]['id'];
                $itemList[$k1]['title'] = $lession[0]['title'];
                $itemList[$k1]['img_url'] = $lession[0]['img_url'];
            }
        }*/
        $itemList = array();
        //print_r($itemList);
        return $this->render('PageBundle:Billing:paysuccess.html.twig',array('itemList'=>$itemList));
    }

    public function alipayAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        $member_id = $session->get('member_id');
        $order_id = $request->get('order_id');
        $type = $request->get('type');
        if(empty($member_id)){
            $this->location($this->generateUrl('PageBundle_page_index'),'请先登录！');
        }
        // 订单信息验证
        if(!empty($order_id)){
            $orderRes = self::getOrder($order_id,$member_id);
            if(empty($orderRes)){
                //订单不存在
                $this->location($this->generateUrl('PageBundle_billing_myorder'),'订单ID错误！');
            }
        }else{
            // 参数order_id有误
            header("Location: ".$this->generateUrl('PageBundle_billing_cart'));
            exit();
        }

        $alipay = $this->get('payment_service');
        $alipayConfig = $alipay->getPayConfig();
        $alipaySubmit = $alipay->getSumbiter();

        // 支付类型
        $payment_type = "1";
        // 必填，不能修改
        //商户订单号
        $out_trade_no = $orderRes->getOrderCode();
        //商户网站订单系统中唯一订单号，必填
        //订单名称
        $subject = '秀财网财务课程';
        //必填
        //付款金额
        $total_fee = $orderRes->getAmount();
        //必填
        //订单描述
        $body = '秀财网财务课程订单';
        //商品展示地址
        $show_url = '';
        //需以http://开头的完整路径，例如：http://www.xxx.com/myorder.html
        //防钓鱼时间戳
        $anti_phishing_key = "";
        //若要使用请调用类文件submit中的query_timestamp函数
        //客户端的IP地址
        $exter_invoke_ip = "";
        //非局域网的外网IP地址，如：221.0.0.1

        $alipayConfig['return_url'] = 'http://'.$_SERVER['HTTP_HOST'].$this->generateUrl('PageBundle_billing_return');//同步通知 地址
        $alipayConfig['notify_url'] = 'http://'.$_SERVER['HTTP_HOST'].$this->generateUrl('PageBundle_billing_notify');//异步通知 地址
        $parameter = array(
            "service" => "create_direct_pay_by_user",
            "partner" => trim($alipayConfig['partner']),
            "payment_type" => $payment_type,
            "notify_url" => $alipayConfig['notify_url'],
            "return_url" => $alipayConfig['return_url'],
            "seller_email" => trim($alipayConfig['seller']),
            "out_trade_no" => $out_trade_no,
            "subject" => $subject,
            "total_fee" => $total_fee,
            "body" => $body,
            "show_url" => $show_url,
            "anti_phishing_key" => $anti_phishing_key,
            "exter_invoke_ip" => $exter_invoke_ip,
            "_input_charset" => trim(strtolower($alipayConfig['input_charset']))
        );

        if(!empty($type) && $type != "default"){
            $parameter["paymethod"] = "bankPay"; //默认支付方式
            $parameter["defaultbank"] = $type; //必填，银行简码请参考接口技术文档
        }
        $html_text = $alipaySubmit->buildRequestForm($parameter,"post", "submit");
        echo $html_text;
    }

    /**
     * 支付宝异步通知接口
     */
    public function notifyAction(){
        $request = $this->getRequest();
        $alipay = $this->get('payment_service');
        $alipayNotify = $alipay->getNotifyer();
        $verify_result = $alipayNotify->verifyNotify();
        if($verify_result){
            //验证请求来自于支付宝网站
            self::logPay('Notify verified: from Alipay');
            //商户订单号
            $out_trade_no = $request->get('out_trade_no');
            //支付宝交易号
            $trade_no = $request->get('trade_no');
            //交易状态
            $trade_status = $request->get('trade_status');
            $notify_time = $request->get('notify_time');
            $notify_type = $request->get('notify_type');
            $notify_id = $request->get('notify_id');
            $sign_type = $request->get('sign_type');
            $sign = $request->get('sign');
            self::logPay("out_trade_no: $out_trade_no; trade_no: $trade_no; trade_status: $trade_status\n\r");

            // 交易完成后续处理
            if('TRADE_FINISHED' == $trade_status || 'TRADE_SUCCESS' == $trade_status){
                self::logPay('notify SUCCESSFUL');
                $this->notifySuccess($alipay,$out_trade_no,$trade_no,$trade_status,
                    array('notify_time'=>$notify_time,'notify_type'=>$notify_type,'notify_id'=>$notify_id,'sign_type'=>$sign_type,'sign'=>$sign)
                );
            }else{
                $orderData = $alipay->getOrderByOrderCode($out_trade_no);
                if(!empty($orderData)){
                    $alipay->insertTransaction(array('member_id'=>$orderData->getMemberId(),'amount'=>$orderData->getAmount(),'total_amount'=>$orderData->getPayAmount(),'content_id'=>$orderData->getId(),'content_type'=>16),0,2);
                }else{
                    self::logPay('no this Order Code');
                }
                self::logPay('notify FAILED');
            }

            echo "success";
        }else{
            // 请求来自于其他网站
            self::logPay('Notify not verified: is not from Alipay');
            echo "fail";
        }
    }

    public function notifySuccess($alipay,$out_trade_no,$trade_no,$trade_status,$params)
    {
        $orderData = $alipay->getOrderByOrderCode($out_trade_no);
        if(!empty($orderData)){
            if($orderData->getOrderStatus() != 2){
                $item = $alipay->getOrderItemByOrderCode($out_trade_no);
                foreach($item as $key=>$value){
                    $queryArray = array('memberId'=>$value['member_id'],'contentId'=>$value['content_id'],'contentType'=>$value['content_type']);
                    $ifExistCourseInventory = $this->getDoctrine()
                        ->getRepository('StoreBundle:XcCourseInventory')
                        ->findOneBy($queryArray);
                    if(empty($ifExistCourseInventory))
                    {
                        $alipay->insertCourseInventory(array('member_id'=>$value['member_id'],'content_id'=>$value['content_id'],'content_type'=>$value['content_type']));
                    }
                }
                $alipay->insertTransaction(
                    array('member_id'=>$orderData->getMemberId(),'amount'=>$orderData->getAmount(),'total_amount'=>$orderData->getPayAmount(),'content_id'=>$orderData->getId(),'content_type'=>16) ,
                    0,1);
                $alipay->operateBilling(array('member_id'=>$orderData->getMemberId(),'current_balance'=>0,'amount'=>$orderData->getAmount(),'pay_amount'=>$orderData->getPayAmount(),'total_invoice'=>0));
                $alipay->orderCallBack($out_trade_no,$trade_no,$trade_status,2,array('notify_time'=>$params['notify_time'],'notify_type'=>$params['notify_type'],'notify_id'=>$params['notify_id'],'sign_type'=>$params['sign_type'],'sign'=>$params['sign']));
                self::logPay('operate CourseInventory、Transaction、Billing SUCCESSFUL');
            }
        }else{
            self::logPay('no this Order Code');
        }
    }

    /**
     * 交易成功后返回的链接
     */
    public function returnAction(){
        $request = $this->getRequest();
        // 验证请求是否来自支付宝网站
        $alipay = $this->get('payment_service');
        $alipayNotify = $alipay->getNotifyer();
        $verify_result = $alipayNotify->verifyReturn();
        self::logPay('Receive return info');
        if($verify_result){
            // 请求来自于支付宝
            self::logPay('Return info verified: from Alipay');
            // 商户订单号
            $out_trade_no = $request->get('out_trade_no');
            // 支付宝交易号
            $trade_no = $request->get('trade_no');
            // 交易状态
            $trade_status = $request->get('trade_status');
            self::logPay("out_trade_no: $out_trade_no; trade_no: $trade_no; trade_status: $trade_status\n\r");
            if('TRADE_FINISHED' == $trade_status || 'TRADE_SUCCESS' == $trade_status){
                // 支付成功后续处理
                self::logPay('return SUCCESSFUL');
                return new RedirectResponse($this->generateUrl('PageBundle_billing_pay_success',array('out_trade_no'=>$out_trade_no)));
            }else{
                // 支付失败
                self::logPay('return FAILED');
                return new RedirectResponse($this->generateUrl('PageBundle_billing_cart'));
            }
        }else{
            // 非法请求
            self::logPay('Return info verified error: is not from Alipay');
            return new RedirectResponse($this->generateUrl('PageBundle_billing_cart'));
        }
    }

    private function getOrder($orderId,$member_id)
    {
        $data = $this->getDoctrine()
            ->getRepository('StoreBundle:XcOrder')
            ->findOneBy(array('id'=>$orderId,'memberId'=>$member_id));
        return $data;
    }

    private function getOrderItem($orderId)
    {
        $data = $this->getDoctrine()
            ->getRepository('StoreBundle:XcOrderItem')
            ->findBy(array('orderId'=>$orderId));
        return $data;
    }

    /**
     * 写日志
     * 注意：服务器需要开通fopen配置
     * @param $word 要写入日志里的文本内容 默认值：空值
     */
    public function logPay($word='') {
        $fp = fopen('assets/paylog/'.date("Y-m-d").".txt","a");
        flock($fp, LOCK_EX) ;
        fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time())."\r\n".$word."\r\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    public function location($url,$msg,$ifClose=0){
        header('content-type:text/html;charset=utf-8;');
        $host = $_SERVER['HTTP_HOST'];
        echo "<script src='http://$host/assets/js/jquery-1.7.2.min.js'></script>";
        echo "<script src='http://$host/assets/js/layer/layer.js'></script>";
        echo "<body>";
        if($ifClose == 1){
            echo "<script>layer.alert('$msg','',function(){window.opener=null;window.close();});</script>";
        }else{
            echo "<script>layer.alert('$msg','',function(){window.location.href='".$url."';});</script>";
            //echo "<script>layer.alert('$msg','',['title','aaa'],function(){window.location.href='".$url."';});</script>";
        }
        //echo "<script>layer.alert('$msg');location.href='".$url."';</script>";
        //echo "<script>layer.confirm('$msg', function(){location.href='".$url."';});</script>";
        //echo "<script>layer.msg('$msg',3,'',function(){location.href='".$url."'} );</script>";
        echo "</body>";
        exit;
    }

    public function testemailAction(){
        //exit;
        $message = \Swift_Message::newInstance()
            ->setSubject('秀财网直播课堂')
            ->setFrom('no-reply@xiucai.com')
            ->setTo('tony@xiucai.com','tony@xiucai.com')
            //->attach(\Swift_Attachment::fromPath(__DIR__.'/avatar_ori_0.6568959202587809.jpg'))
            ->setBody("您(预约)的课程将在30分钟后正式开始，请尽快进入直播间!本次直播开始时间：".date("Y-m-d H:i",time()+1800)."【秀财网】");
            //->setBody($this->renderView('PageBundle:Billing:email.html.twig'),'text/html')
        ;
        $this->get('mailer')->send($message);
    }
}