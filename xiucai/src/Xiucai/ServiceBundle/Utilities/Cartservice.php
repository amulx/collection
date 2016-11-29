<?php

namespace Xiucai\ServiceBundle\Utilities;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Bundle\DoctrineBundle;
use Symfony\Component\HttpFoundation\Request;
use Predis;
use Xiucai\StoreBundle\Entity\XcCourse;
use Xiucai\StoreBundle\Entity\XcLiveCourse;
use Symfony\Component\Config\Definition\Exception\Exception;
use Xiucai\StoreBundle\Entity\XcOrder;
use Xiucai\StoreBundle\Entity\XcOrderItem;
use \DateTime;


class Cartservice {
    protected $doctrine;
    protected $redis;
    protected $conn;
    protected $_memberId	= -1;	//用户id，未登录时为-1
    protected $_cart		= array();	//购物车array

    public function __construct($doctrine, $redis, $session ,$conn){
        $this->doctrine = $doctrine;
        $this->redis = $redis;
        $this->conn = $conn;
        $member_id = $session->get('member_id');
        if(!empty($member_id)){
            $this->_memberId = $member_id;
            $cartInfo = $this->redis->hget("course_order_cart",$member_id);
            if(!empty($cartInfo)){
                $this->_cart = self::obj_to_array($cartInfo);
            }
        }
    }
    /**
     * 判断cart中是否有item
     * @param	item	array	item数据数组，必需包含
     * order_item_type，video_course_id | course_package_id
     */
    protected function _hasItem(array $item){
        if(!empty($this->_cart)){
            foreach ($this->_cart as $key => $value) {
                if ($item['content_type'] == $value['content_type']){
                    if (!empty($item['content_id']) && ($item['content_id']==$value['content_id'])) return true;
                }
            }
        }
        return false;
    }

    /**
     * 获取item信息
     * @param	item	array item数据数组，结构同上
     */
    protected function _getItemInfo(array $item){
        $data = array();
        if ($item['content_type'] == 1){
            $table_name = 'xc_course';
            $content_type_name = '录播课程';
            /*$CartItem = $this->doctrine
                ->getRepository('StoreBundle:'.$table_name)
                ->findOneBy(array('id'=>$item['content_id']));*/
            $sql    = "SELECT title, original_price, current_price, create_time FROM $table_name  WHERE id = ".$item['content_id']." order by id desc";
            $query  = $this->conn->query($sql);
            $CartItem = $query->fetchAll();
            if (!empty($CartItem)){
                $data['content_id'] = $item['content_id'];
                $data['content_type'] = $item['content_type'];
                $data['content_type_name']= $content_type_name;
                $data['title']	= $CartItem[0]['title'];
                $data['original_price'] = $CartItem[0]['original_price'];
                $data['current_price'] = $CartItem[0]['current_price'];
                $data['lession_time'] = $CartItem[0]['create_time'];
                $data['create_time'] = $CartItem[0]['create_time'];
            }
        }else if($item['content_type'] == 2){
            $table_name = 'xc_live_course';
            $content_type_name = '直播课程';
            $sql    = "SELECT title, original_price, current_price, schedule_time, create_time FROM $table_name  WHERE id = ".$item['content_id']." order by id desc";
            $query  = $this->conn->query($sql);
            $CartItem = $query->fetchAll();
            if (!empty($CartItem)){
                $data['content_id'] = $item['content_id'];
                $data['content_type'] = $item['content_type'];
                $data['content_type_name']= $content_type_name;
                $data['title']	= $CartItem[0]['title'];
                $data['original_price'] = $CartItem[0]['original_price'];
                $data['current_price'] = $CartItem[0]['current_price'];
                $data['lession_time'] = $CartItem[0]['schedule_time'];
                $data['create_time'] = $CartItem[0]['create_time'];
            }
        }else{
            throw new Exception("Save item failed:Invalid content_type ".$item['content_type']);
        }

        return $data;
    }

    /**
     * 检查item是否合规
     * @param	item	array item数据数组，结构同上
     */
    protected function _checkItem(array $item){
        if (!isset($item['content_type']) || (!isset($item['content_id']))) throw new Exception("Parameter error: Invalid item");
    }

    /**
     * 添加产品
     * @param	item	array item数据数组，结构同上
     */
    public function add($item){
        $this->_checkItem($item);
        if (!$this->_hasItem($item)){
            //如果购物车中没有此产品，则添加到购物车里
            $info = $this->_getItemInfo($item);
            if(!array_key_exists($info['content_type'].'-'.$info['content_id'],$this->_cart)){
                $dataCart = array($info['content_type'].'-'.$info['content_id']=>$info) + $this->_cart;
                $this->redis->hset("course_order_cart",$this->_memberId,json_encode($dataCart));
            }
        }
    }

    /**
     * 移除产品
     * @param	item	array item数据数组，结构同上
     */
    public function remove($course_id){
        unset($this->_cart[$course_id]);
        $this->redis->hset("course_order_cart",$this->_memberId,json_encode($this->_cart));
    }

    public function obj_to_array($item){
        $item2 = json_decode($item);
        $data = array();
        foreach($item2 as $key=>$value){
            $data[$key] = (array)($value);
        }
        return $data;
    }

    /**
     * 获取购物车中的产品总数
     */
    public function getCount(){
        if (!empty($this->_cart))
            return count($this->_cart);
        else
            return 0;
    }

    /**
     * 获取购物车中的产品总价
     */
    public function getTotalPrice(){
        $pay_amount = 0;
        if(!empty($this->_cart)){
            foreach ($this->_cart as $key => $value) {
                $pay_amount += $value['current_price'];
            }
        }
        return $pay_amount;
    }

    /**
     * 获取购物车中的原价总价
     */
    public function getOriginalPrice(){
        $original_amount = 0;
        if(!empty($this->_cart)){
            foreach ($this->_cart as $key => $value) {
                $original_amount += $value['original_price'];
            }
        }
        return $original_amount;
    }

    /**
     * 获取购物车中的产品列表
     */
    public function getItemList(){
        return $this->_cart;
    }

    /**
     * 清空购物车
     */
    public function clear(){
        $this->redis->hdel("course_order_cart",$this->_memberId);
        $this->_cart = array();
    }

        /**
     * 生成订单，必需登录才能进行
     * @return	orderId
     */
    public function makeOrder(){
        set_time_limit(0);
        if ($this->_memberId == -1)
            throw new Exception("MakeOrder failed: need log in");
        if ($this->getCount() == 0)
            throw new Exception("MakeOrder failed: total count is zero");

        //根据items生成order
        $data['order_code']	= self::getOrderCode();
        $data['member_id']	 = $this->_memberId;
        $data['amount']	= $this->getTotalPrice();
        $data['pay_amount']	= $this->getTotalPrice();
        $data['payment_gateway']	= 0; // internet
        $data['order_status']	= 0;
        $data['operator_id']	= 0; //默认为系统
        $data['ip_address']	= self::getIP();
        $data['create_time']	= new \DateTime(date("Y-m-d H:i:s"));
        $data['update_time']	= new \DateTime(date("Y-m-d H:i:s"));
        $data['type']	= 0;
        $data['status']	= 4;
        $orderRes	= self::addOrder($data);

        if(empty($orderRes['orderId'])) throw new Exception("MakeOrder failed: ".$orderRes['msg']);
        $orderId = $orderRes['orderId'];

        //添加orderItem
        foreach ($this->_cart as $key => $value) {
            $data['order_id']	= $orderId;
            $data['content_id'] = $value['content_id'];
            $data['content_type'] = $value['content_type'];
            $data['original_price'] = $value['original_price'];
            $data['current_price'] = $value['current_price'];
            self::addItem($orderId,$data);
        }

        //清空购物车
        $this->clear();

        return $orderId;
    }

    /**
     * 描述：得到订单号
     * @param $member_id：会员ID
     * @return 订单号
     */
    public function getOrderCode()
    {
        $previewOrderCode = $this->redis->get('xiucai_order_code_'.date("Ymd"));
        if($previewOrderCode){
            $currentOrderCode = $this->orderCodeIncr($previewOrderCode+1);
            $this->redis->setex('xiucai_order_code_'.date("Ymd"), 3600*24 , $previewOrderCode+1);
        }else{
            $em = $this->doctrine->getManager();
            $query = $em->createQuery('SELECT o.orderCode FROM StoreBundle:XcOrder o ORDER BY o.id DESC');
            $result = $query->getResult(1);
            if(empty($result)){
                $currentOrderCode = date("Ymd").'0001';
                $this->redis->setex('xiucai_order_code_'.date("Ymd"), 3600*24 , '1' );
            }else{
                if(substr($result[0]['orderCode'],8) == date("Ymd")){
                    $currentOrderCode = $this->orderCodeIncr(substr($result[0]['orderCode'],-4)+1);
                    $this->redis->setex('xiucai_order_code_'.date("Ymd"), 3600*24 , $currentOrderCode);
                }else{
                    $currentOrderCode = date("Ymd").'0001';
                    $this->redis->setex('xiucai_order_code_'.date("Ymd"), 3600*24 , '1');
                }
            }
        }
        return $currentOrderCode;
    }

    public function orderCodeIncr($currentOrderCode)
    {
        $strlen = strlen($currentOrderCode);
        switch($strlen){
            case 1:$currentOrderCodeStr = '000'.$currentOrderCode;break;
            case 2:$currentOrderCodeStr = '00'.$currentOrderCode;break;
            case 3:$currentOrderCodeStr = '0'.$currentOrderCode;break;
            case 4:$currentOrderCodeStr = $currentOrderCode;break;
        }
        $newOrderCode = date("Ymd").$currentOrderCodeStr;
        return $newOrderCode;
    }

    private function addOrder($item)
    {
        if(!empty($item)){
            try{
                $data = new XcOrder();
                $data->setOrderCode($item['order_code']);
                $data->setMemberId($item['member_id']);
                $data->setAmount($item['amount']);
                $data->setPayAmount($item['pay_amount']);
                $data->setPaymentGateway($item['payment_gateway']);
                $data->setOrderStatus($item['order_status']);
                $data->setOperatorId($item['operator_id']);
                $data->setIpAddress($item['ip_address']);
                $data->setCreateTime($item['create_time']);
                $data->setUpdateTime($item['update_time']);
                $data->setStatus($item['status']);
                $em = $this->doctrine->getManager();
                $em->persist($data);
                $em->flush();
                $result['orderId'] = $data->getId();;
                $result['msg'] = "Successful";
                $result['code'] = 1;
            }catch(Exception $e){
                $result['msg'] = "Failed: ".$e->getMessage();
                $result['code'] = 0;
            }
        }
        return $result;
    }

    private function addItem($orderId,$item)
    {
        if(!empty($item) && !empty($orderId)){
            try{
                $data = new XcOrderItem();
                $data->setOrderId($orderId);
                $data->setContentId($item['content_id']);
                $data->setContentType($item['content_type']);
                $data->setOriginalPrice($item['original_price']);
                $data->setCurrentPrice($item['current_price']);
                $data->setNum(1);
                $data->setStatus(1);
                $em = $this->doctrine->getManager();
                $em->persist($data);
                $em->flush();
            }catch(Exception $e){
                $result['msg'] = "Failed: ".$e->getMessage();
                $result['code'] = 0;
            }
        }
    }

    public function getIP() {
        if (@$_SERVER["HTTP_X_FORWARDED_FOR"])
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        else if (@$_SERVER["HTTP_CLIENT_IP"])
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        else if (@$_SERVER["REMOTE_ADDR"])
            $ip = $_SERVER["REMOTE_ADDR"];
        else if (@getenv("HTTP_X_FORWARDED_FOR"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (@getenv("HTTP_CLIENT_IP"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (@getenv("REMOTE_ADDR"))
            $ip = getenv("REMOTE_ADDR");
        else
            $ip = "Unknown";
        return $ip;
    }
} 