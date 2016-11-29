<?php

namespace Xiucai\ServiceBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
//use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpFoundation\Response;
use Xiucai\ServiceBundle\CornMeeting\CornMeeting;
use Xiucai\ServiceBundle\CornMeeting\object;
use Xiucai\ServiceBundle\CornMeeting\RoomVVType;
use Xiucai\ServiceBundle\CornMeeting\ConfTest;

/** 描述：被动接口服务类<br/>
 * 提供外部webService服务
 */
class ServiceController extends Controller
{
    /**
     * 描述：日志对象<br/>
     * Symfony\Bridge\Monolog\Logger
    */
    private $logger = null;
    /** 描述：webService主动接口服务*/
    private $cm = null;

    public function setContainer(ContainerInterface $container=null)
    {
        parent::setContainer($container);
        //初始化日志
        $this->logger = $this->get("logger");
        //初始化主动接口服务
        $this->cm = new CornMeeting(
            $this->container->getParameter("app_type"),
            $this->container->getParameter("app_ui_type"),
            $this->container->getParameter("web_service_key"),
            $this->container->getParameter("web_service_url")
        );
        //
    }

    /** 描述：仅仅做测试之用....*/
    public function testAction()
    {
        //Symfony\Bridge\Monolog\Logger

//        $logger = $this->get("logger");
//        var_dump($this->logger);
//        $result = "dtsola";
//        $result .= $this->cm->getAppType();
        $result = "";
//        $result .= RoomVVType::$ONLY_P2P;

//        $moderator = array(
//            array("uid" => "2234567890", "logo" => "asset/imgs/logo.jpg", "name" => "tom"),
//            array("uid" => "3234567890", "logo" => "asset/imgs/logo.jpg", "name" => "tim")
//        );
//        var_dump($moderator);

        $tester = new ConfTest($this->cm);
//        var_dump($this->cm->getAppType(). '='. $this->cm->getUIType());
//        $tester->testwbCreateRoom();
//        $tester->testwbGetRoomStatus();
//        $tester->testwbGetRoomProperty();
//        $tester->testwbGetRoomUsers();
//        $tester->testwbUpdateRoomProperty();
//        $tester->testwbUpdateRoomViewers();
//        $tester->testwbGetFSListByUid();
//        $tester->testwbResDel();
//        $tester->testwbResEx();
//        $tester->testwbGetFile4RoomSpace();
//        $tester->testwbRooms();

        //
//        var_dump($this->cm->getWebServiceUrl());
        return $this->render('ServiceBundle:Default:index.html.twig', array('name' => $result));
    }


    /** 描述：提供会议室端，事件回调服务*/
    public function eventAction($data)
    {
        $r = "eventAction";
        $this->logger->info("会议室端事件回调：");
        try{
            $ret = new object();
            $data = str_replace('@','%',$data);
            // echo $data;
            $data = urldecode($data);
            //echo $data;
            $data = $this->cm->encrypt($data, $this->cm->keys());
            $data = json_decode($data,true);

            if(!$data){
                $ret->ret="fault";
                $ret->exp=urlencode("JSON不能解析");
                $ret->sn=$this->cm->sn();
                $ret=json_encode($ret);
                $ret=urldecode($ret);

                return new Response ($ret);
            }

            $ret = array();
            switch($data["type"])
            {
                case("Room_Timeout_Event"):
                {
                    $ret = $this->endMeeting($data["data"]["roomtoken"], $data["data"]["endTime"]);
                    $generateUrl = $this->generateUrl('PageBundle_live_detail',array("id" => $ret['live_id']));
                    echo "<script>window.top.location.href='".$generateUrl."'</script>";
                    exit;
                    break;
                }
                case("Room_Close_Event"):
                {
                    $ret = $this->endMeeting( $data["data"]["roomtoken"],$data["data"]["endTime"]);
                    $generateUrl = $this->generateUrl('PageBundle_live_detail',array("id" => $ret['live_id']));
                    echo "<script>window.top.location.href='".$generateUrl."'</script>";
                    exit;
                    break;
                }
            }


            $ret = json_encode($ret);
            $ret = urldecode($ret);

            return new Response ($ret);
        }
        catch(\Exception $e){
            $this->logger->error("发生错误 : " . $e->getMessage());
        }
        return new Response ($r);
    }
    //

    /** 描述：提供会议室端，跳转服务*/
    public function gotoAction($data)
    {
        $r = "gotoAction";
        $this->logger->info("会议室端调用跳转服务：");
        try{
            $ret = new object();
            $data = str_replace('@','%',$data);
            // echo $data;
            $data = urldecode($data);
            //echo $data;
            $data = $this->cm->encrypt($data, $this->cm->keys());
            $data = json_decode($data,true);
            //print_r($data);
            if(!$data){
                $ret->ret = "fault";
                $ret->exp = urlencode("JSON不能解析");
                $ret->sn = $this->cm->sn();
                $ret = json_encode($ret);
                $ret = urldecode($ret);
                return new Response ($ret);
                //throw new \Exception('JSON不能解析');
                exit;
            }

            $confid = $this->getRoomId($data['roomtoken'], 'liveId');
            $generateUrl = $this->generateUrl('PageBundle_live_detail',array("id" => $confid));
            switch($data["type"])
            {
                case("THE_USER_LEAVE"):
                {
                    echo "<script>window.top.location=".$generateUrl."</script>";
                    exit;
                    break;
                }
                case("KICKED_OUT"):
                {
                    if($data["roomtoken"]=="mypublicRoom")
                    {
                        $url = $this->generateUrl('PageBundle_course_live');
                        echo "<script>window.top.location.href='".$url."'</script>";
                        exit;
                        break;
                    }
                    echo "<script>window.top.location.href='".$generateUrl."'</script>";
                    exit;
                    break;
                }
                case("ILLEGAL_ACCESS"):
                {
                    if($data["roomtoken"]=="mypublicRoom")
                    {
                        $url = $this->generateUrl('PageBundle_course_live');
                        echo "<script>window.top.location.href='".$url."'</script>";
                        exit;
                        break;
                    }
                    echo "<script>window.top.location.href='".$generateUrl."'</script>";
                    exit;
                    break;
                }
                case("PUBLIC_ROOM_LEAVE"):
                {
                    try{
                        $url = $this->generateUrl('PageBundle_course_live');
                        echo "<script>window.top.location.href='".$url."'</script>";
                        exit;
                        break;
                    }catch (\Symfony\Component\Config\Definition\Exception\Exception $e){
                        throw new \Exception($e->getMessage());
                    }
                }
            }

            $ret = json_encode($ret);
            $ret = urldecode($ret);
            $ret = $this->cm->encrypt($ret,$this->cm->keys());

            return new Response (null);
        }catch(\Exception $e){
            $this->logger->error("发生错误 : " . $e->getMessage());
        }

        return new Response ($r);
    }

    /** 描述：提供会议室端，获取房间信息服务*/
    public function getRoomInfoAction($data)
    {
        $r = "getRoomInfoAction";
        $this->logger->info("会议室端获取房间信息：");
        try{}
        catch(\Exception $e){
            $this->logger->error("发生错误 : " . $e->getMessage());
        }
        return new Response ($r);
    }

    /**
     * 关闭直播间
     * @param $roomToken
     * @param $endTime
     * @param null $u
     * @return array
     */
    public function endMeeting($roomToken, $endTime, $u = null){
        $emt = $this->getDoctrine()->getManager();
        $conference = $emt->getRepository('StoreBundle:XcConference')->findOneBy(array('roomToken' => $roomToken));
        $liveCourse = $emt->getRepository('StoreBundle:XcLiveCourse')->findOneBy(array('confId' => $conference->getId()));
        if($conference && $conference->getConferenceStatus() != '7'){
            $liveId = $liveCourse->getId();
            date_default_timezone_set('Asia/Shanghai');
            $t = (int)($endTime/1000);
            $timer = new \DateTime('@'.$t);
            $timer->setTimezone(new \DateTimeZone('Asia/Shanghai'));
            $conference->setEndTime($timer);
            $conference->setConferenceStatus(6);
            $emt->flush(); //直播间状态更新为6: 关闭

            $users = $this->cm->wbGetRoomUsers($roomToken, 'onlineList'); //实际参与用户
            if(isset($users['online'])){
                $users = json_encode($users['online']);
                $courseService = $this->get('course_service');
                $courseService->setLiveCourseAttend($liveId, $users); //redis记录直播实际参与人数

                $ret = array('ret'=>'success', 'live_id' => $liveCourse->getId());
            }else{
                $ret = array('ret'=>'success', 'live_id' => $liveCourse->getId());
            }
        }else{
            $ret = array('ret'=>'fault','exp'=>'roomToken can not find', 'live_id' => $liveCourse->getId());
        }

        return $ret;
    }

    /**
     * 获取房间属性
     * @param $roomtoken
     * @return string
     */
    public function getRoomId($roomtoken, $type = 'roomId'){
        $emt = $this->getDoctrine()->getManager();
        $conference = $emt->getRepository('StoreBundle:XcConference')->findOneBy(array('roomToken'=>$roomtoken));
        if(!empty($conference)){
            if($type == 'liveId'){
                $live = $emt->getRepository('StoreBundle:XcLiveCourse')->findOneBy(array('confId' => $conference->getId()));
                if(!empty($live)){
                    return $live->getId();
                }else{
                    return 'false';
                }
            }else{
                return $conference->getId();
            }
        }else{
            return 'false';
        }
    }
}






