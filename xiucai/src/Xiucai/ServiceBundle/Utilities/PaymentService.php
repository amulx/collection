<?php

namespace Xiucai\ServiceBundle\Utilities;
use Xiucai\ServiceBundle\Utilities\Alipay\Alipay;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller;
use Doctrine\Bundle\DoctrineBundle;
use Symfony\Component\Config\Definition\Exception\Exception;
use Xiucai\StoreBundle\Entity\XcCourseInventory;
use Xiucai\StoreBundle\Entity\XcTransaction;
use Xiucai\StoreBundle\Entity\XcBilling;
use Xiucai\StoreBundle\Entity\XcOrder;
use Xiucai\StoreBundle\Entity\XcCourse;
use Xiucai\StoreBundle\Entity\XcLiveCourse;
use \DateTime;


class PaymentService {
    protected $doctrine;
    protected $conn;
    protected $redis;
    public static $TaoBaoOrderStatus = array('WAIT_BUYER_PAY'=>0,'TRADE_CLOSED'=>3,'TRADE_SUCCESS'=>1,'TRADE_FINISHED'=>1);

    public function __construct($doctrine,$conn,$redis)
    {
        $this->doctrine = $doctrine;
        $this->conn = $conn;
        $this->redis = $redis;
    }

    ///////////////////////////////////////////////内部函数///////////////////////////////////////
    /**
     * 描述：获取支付配置参数
     * @return null
     */
    public function getPayConfig(){
        $payConfig = null;
        $payConfig = Alipay::getAlipayConfig();
        return $payConfig;
    }

    /**
     * 描述：获取 支付 提交接口
     * @param null $payConfig：支付配置
     * @return null
     */
    public function getSumbiter($payConfig = null){
        if(!$payConfig) $payConfig = $this->getPayConfig();
        if(!$payConfig) return null;
        $sumbiter = null;
        $sumbiter = Alipay::getAlipaySubmit($payConfig);
        return $sumbiter;
    }

    /**
     * 描述：获取 支付 通知接口
     * @param null $payConfig：支付配置
     * @return null
     */
    public function getNotifyer($payConfig = null){
        if(!$payConfig) $payConfig = $this->getPayConfig();
        if(!$payConfig) return null;
        $notifyer = null;
        $notifyer = Alipay::getAlipayNotify($payConfig);
        return $notifyer;
    }

    /**
     * 描述：增加用户的课程库存
     * @param $params 参数数组 { 'member_id' => '会员ID',
     *                                   'content_id' => 'content_type',
     *                                   'content_type' => 'content_type'}
     */
    public function insertCourseInventory($params){
        try{
            $item = new XcCourseInventory();
            $item->setMemberId($params['member_id']);
            $item->setContentId($params['content_id']);
            $item->setContentType($params['content_type']);
            $item->setQuantity('1');
            $item->setCreateTime(new \DateTime());
            $em = $this->doctrine->getManager();
            $em->persist($item);
            $em->flush();
            $result['code'] = 1;
        }
        catch(Exception $e){
            $result['msg'] = $e->getMessage();
            $result['code'] = 0;
        }
        return $result;
    }

    /**
     * 描述：根据订单号得到订单信息
     * @param $out_trade_no：订单号
     * @return 对象数组
     */
    public function getOrderByOrderCode($out_trade_no){
        $data = $this->doctrine
            ->getRepository('StoreBundle:XcOrder')
            ->findOneBy(array('orderCode'=>$out_trade_no));
        return $data;
    }

    /**
     * 描述：根据out_trade_no得到课程列表
     * @param $out_trade_no：订单号
     * @return 对象数组
     */
    public function getOrderItemByOrderCode($out_trade_no)
    {
        $sql = 'SELECT a.id,a.member_id,a.amount,b.content_id,b.content_type
                FROM `xc_order` AS a
                LEFT JOIN `xc_order_item` AS b ON a.id = b.order_id
                WHERE (a.order_code = :out_trade_no)';

        $where = array(':out_trade_no' => $out_trade_no);
        $ready = $this->conn->prepare($sql);
        $ready->execute($where); //搜索参数执行匹配
        $itemList = $ready->fetchAll();
        return $itemList;
    }

    /**
     * 描述：根据会员ID得到课程库存列表
     * @param $member_id：会员ID
     * @return 对象数组
     */
    public function getCourseInventoryList($member_id){
        $data = $this->doctrine
            ->getRepository('StoreBundle:XcCourseInventory')
            ->findBy(array('memberId'=>$member_id));
        return $data;
    }

    /**
     * 描述：判断会员是否买过某课程
     * @param $member_id：会员ID，$content_id,$content_type
     * @return Boolean
     */
    public function existCourseInventory($member_id,$content_id,$content_type){
        $data = $this->doctrine
            ->getRepository('StoreBundle:XcCourseInventory')
            ->findBy(array('memberId'=>$member_id, 'contentId'=>$content_id, 'contentType'=>$content_type));
        if(!empty($data)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 描述：判断会员是否可以进入直播间
     * @param $member_id：会员ID，$content_id,$content_type
     * @return 1->免费，2->已支付，3->未支付
     */
    public function ifAllowAccess($member_id,$content_id,$content_type){
        if($content_type == 1){
            $table = 'XcCourse';
        }else if($content_type == 2){
            $table = 'XcLiveCourse';
        }
        $dataLession =  $this->doctrine
            ->getRepository('StoreBundle:'.$table)
            ->findOneBy(array( 'id'=>$content_id));
        if($dataLession->getCurrentPrice() == 0 || $dataLession->getCurrentPrice() == ''){
            return '1';
        }else{
            if($this->existCourseInventory($member_id,$content_id,$content_type)==true){
                return '2';
            }else{
                return '3';
            }
        }
    }

    /**
     * 描述：根据CourseInventory ID得到课程库存详情
     * @param $id：ID
     * @return 对象数组
     */
    public function getCourseInventoryDetail($id)
    {
        $data = $this->doctrine
            ->getRepository('StoreBundle:XcCourseInventory')
            ->find(array('id'=>$id));
        return $data;
    }

    /**
     * 描述：增加支付交易流水信息
     * @param $params：参数数组 { 'member_id' => '订单的会员ID',
     *                                   'amount' => '订单amount',
     *                                   'total_amount' => '订单pay_amount',
     *                                   'content_id' => '订单ID',
     *                                   'content_type' => 'content_type'}  ,$type交易类型，$status第三方支付状态
     * @return array 数组 {'code' => '结果，失败：0，成功：1',
     *                      'msg' => '结果信息'}
     */
    public function insertTransaction($params,$type=0,$status){
        try{
            $item = new XcTransaction();
            $item->setType($type);
            $item->setMemberId($params['member_id']);
            $item->setStatus($status);
            $item->setCreateTime(new \DateTime());
            $item->setAmount($params['amount']);
            $item->setTotalAmount($params['total_amount']);
            $item->setContentId($params['content_id']);
            $item->setContentType($params['content_type']);
            $em = $this->doctrine->getManager();
            $em->persist($item);
            $em->flush();
            $result['code'] = 1;
        }
        catch(Exception $e){
            $result['msg'] = $e->getMessage();
            $result['code'] = 0;
        }
        return $result;
    }

    /**
     * 描述：根据会员ID得到支付交易流水
     * @param $member_id：会员ID
     * @return 对象数组
     */
    public function getTransactionList($member_id = ''){
        $sql = 'SELECT *
                FROM `xc_transaction`';
        if(!empty($member_id)){
            $sql .= ' where member_id='.$member_id;
        }
        $query  = $this->conn->query($sql);
        $data = $query->fetchAll();
        /*$data = $query->fetchAll();
        $queryArray = array();
        if(empty($member_id)){
            $data = $this->doctrine
                ->getRepository('StoreBundle:XcTransaction')
                ->findAll();
        }else{
            $queryArray += array('memberId'=>$member_id);
            $data = $this->doctrine
                ->getRepository('StoreBundle:XcTransaction')
                ->findBy($queryArray);
        }*/

        return $data;
    }

    /**
     * 描述：根据Transaction ID得到支付交易流水详情
     * @param $id：ID
     * @return 对象数组
     */
    public function getTransactionDetail($id){
        $data = $this->doctrine
            ->getRepository('StoreBundle:XcTransaction')
            ->find(array('id'=>$id));
        return $data;
    }

    /**
     * 描述：增加客户计费信息
     * @param $params：参数数组 { 'member_id' => '订单的会员ID',
     *                                   'current_balance' => '收支平衡',
     *                                   'amount' => '订单amount',
     *                                   'pay_amount' => '订单pay_amount',
     *                                   'total_invoice' => '总发票数额'}
     * @return array 数组 {'code' => '结果，失败：0，成功：1',
     *                      'msg' => '结果信息'}
     */
    public function operateBilling($params){
        try{
            $ExistBilling = $this->doctrine
                ->getRepository('StoreBundle:XcBilling')
                ->findOneBy(array('memberId'=>$params['member_id']));
            $item = new XcBilling();
            if(empty($ExistBilling)){
                $item->setMemberId($params['member_id']);
                $item->setCreateTime(new \DateTime());
                $item->setCurrentBalance($params['current_balance']);
                $item->setTotalAmount($params['amount']);
                $item->setVirtualAmount($params['pay_amount']);
                $item->setTotalInvoice($params['total_invoice']);
                $em = $this->doctrine->getManager();
                $em->persist($item);
                $em->flush();
            }else{
                $em = $this->doctrine->getManager();
                $item = $em->getRepository('StoreBundle:XcBilling')->find(array('id'=>$ExistBilling->getId()));
                $item->setTotalAmount($ExistBilling->getTotalAmount()+$params['amount']);
                $item->setVirtualAmount($ExistBilling->getVirtualAmount()+$params['pay_amount']);
                $em->flush();
            }
            $result['code'] = 1;
        }
        catch(Exception $e){
            $result['msg'] = $e->getMessage();
            $result['code'] = 0;
        }
        return $result;
    }

    /**
     * 描述：根据会员ID得到计费信息详情
     * @param $member_id：会员ID
     * @return 对象数组
     */
    public function getBillingDetail($member_id){
        $data = $this->doctrine
            ->getRepository('StoreBundle:XcBilling')
            ->findOneBy(array('memberId'=>$member_id));
        return $data;
    }

    /**
     * 描述：根据会员ID得到计费信息列表
     * @param $member_id：会员ID
     * @return 对象数组
     */
    public function getBillingList($member_id = ''){
        $sql = 'SELECT *
                FROM `xc_billing`';
        if(!empty($member_id)){
            $sql .= ' where member_id='.$member_id;
        }
        $query  = $this->conn->query($sql);
        $data = $query->fetchAll();
        /*$queryArray = array();
        if(empty($member_id)){
            $data = $this->doctrine
                ->getRepository('StoreBundle:XcBilling')
                ->findAll();
        }else{
            $queryArray += array('memberId'=>$member_id);
            $data = $this->doctrine
            ->getRepository('StoreBundle:XcBilling')
            ->findOneBy($queryArray);
        }*/
        return $data;
    }

    /**
     * 描述：订单支付回调
     * @param $out_trade_no：订单号,$trade_no: 支付宝交易号,$trade_status:第三方交易状态，$order_status：订单状态，
     * $params：参数数组 { 'notify_time' => '通知时间',
     *                                   'notify_type' => '通知类型',
     *                                   'notify_id' => '通知校验ID'，
     *                                   'sign_type' => '签名方式'
     *                                   'sign' => '签名}
     * @return array 数组 {'code' => '结果，失败：0，成功：1',
     *                      'msg' => '结果信息'}
     */
    public function orderCallBack($out_trade_no,$trade_no,$trade_status,$order_status,$params){
        try{
            $datetime = new DateTime($params['notify_time']);
            $em = $this->doctrine->getManager();
            $item = $em->getRepository('StoreBundle:XcOrder')->findOneBy(array('orderCode'=>$out_trade_no));
            $item->setTransactionId($trade_no);
            $item->setTransactionStatus(PaymentService::$TaoBaoOrderStatus[$trade_status]);
            $item->setOrderStatus($order_status);
            $item->setNotifyTime($datetime);
            $item->setNotifyType($params['notify_type']);
            $item->setNotifyId($params['notify_id']);
            $item->setSignType($params['sign_type']);
            $item->setSign($params['sign']);
            $em->flush();
            $result['code'] = 1;
        }
        catch(Exception $e){
            $result['msg'] = $e->getMessage();
            $result['code'] = 0;
        }
        return $result;
    }

    /**
     * 描述：每个用户播放过的录播课程列表
     * @param $member_id：会员ID,$timestamp: 为该课程最近的播放时间,$course_id:课程ID
     */
    public function setPlayedCourse($member_id,$timestamp,$course_id)
    {
        $this->redis->zadd('user_course_list:'.$member_id, $timestamp, $course_id);
    }

    /**
     * 描述：每个用户预约的直播课程列表
     * @param $member_id：会员ID,$timestamp: 为预约的直播课程的直播时间,$course_id:课程ID
     */
    public function setAppointCourse($member_id,$timestamp,$course_id)
    {
        $this->redis->zadd('user_live_course_list:'.$member_id, $timestamp, $course_id);
    }

    /**
     * 描述：获取某个用户预约的直播课程列表
     * @param $member_id：会员ID
     */
    public function getAppointCourseList($member_id)
    {
        $data = $this->redis->zrange('user_live_course_list:'.$member_id,0,-1);
        return $data;
    }

    /**
     * 描述：增加录播购买人数,为录播课程的购买人数（每个人只算一次）做统计
     * @param $course_id:课程ID
     */
    public function setCourseReserveCount($course_id)
    {
        $this->redis->zincrby('course_reserve_count', 1 ,$course_id);
    }

    /**
     * 描述：获取某个录播课程的购买人数
     * @param $member_id：课程ID
     */
    public function getCourseReserveCount($course_id)
    {
        $data = $this->redis->zscore('course_reserve_count',$course_id);
        return $data;
    }

    /**
     * 描述：增加直播课程预约人数,为直播课程的预约人数（每个人只算一次）做统计
     * @param $course_id:课程ID
     */
    public function setLiveCourseReserveCount($course_id)
    {
        $this->redis->zincrby('live_course_reserve_count', 1 ,$course_id);
    }

    /**
     * 描述：获取直播课程预约人数
     * @param $member_id：课程ID
     */
    public function getLiveCourseReserveCount($course_id)
    {
        $data = $this->redis->zscore('live_course_reserve_count',$course_id);
        return $data;
    }
}