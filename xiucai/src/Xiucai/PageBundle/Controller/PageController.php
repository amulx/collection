<?php
namespace Xiucai\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpKernel\Exception\FlattenException;
use Xiucai\ServiceBundle\CornMeeting\CornMeeting;
use Xiucai\StoreBundle\Entity\XcFeedback;
use \DateTime;

class PageController extends Controller
{
    /**
     * 秀财网首页模块
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $conn = $this->get('database_connection'); //获取数据库连接对象
        $loginInfo = $this->getUser();
        $session = $this->get('request')->getSession();
        $memberId = empty($loginInfo) ? $session->get('member_id') : $loginInfo->getId();

        //获取课程列表信息sql语句
        $sql = "SELECT `id`,`title`,`brief`,`reserve_init`,`reserve_num`,`current_price`,
        `course_level`,`comment_star`,`create_time`,`modify_time`,`img_url`,`video_num`
        FROM `xc_course`
        WHERE `status` = 4 ORDER BY `modify_time` DESC LIMIT 6 "; //按修改时间倒序排列
        $courseData = $conn->fetchAll($sql);
        if(!empty($courseData)){
            $redis = $this->container->get('snc_redis.default');
            foreach($courseData as $key => $course){
                //录播课程播放人数
                $redisKeyList = 'course_play_person_count';
                $personPlayCount = $redis->zscore($redisKeyList, $course['id']);
                $personPlayCount = (empty($personPlayCount)) ? 0 : $personPlayCount;
                $courseData[$key]['play_count'] = $personPlayCount;
            }
        }


        //获取课程列表信息sql语句
        $sql = "SELECT l.`id`,l.`conf_id`,l.`title`,l.`brief`,l.`reserve_num`,l.`current_price`,l.duration,
        l.`course_level`,l.`img_url`,l.`schedule_time`,con.room_token
        FROM `xc_live_course` AS l
        LEFT JOIN  `xc_conference` AS con ON con.id = l.conf_id
        WHERE l.`status` = 4 AND con.conference_status = 3 ORDER BY `schedule_time` ASC LIMIT 3";
        $liveCourseData = $conn->fetchAll($sql);
        if(!empty($liveCourseData)){
            foreach($liveCourseData as $key => $live){
                $startTime = strtotime($live['schedule_time']);
                $endTime = $startTime + ($live['duration']*60); //直播时长
                if($endTime < time()){
                    //判断是否直播是否结束
                    $this->endMeeting($live['room_token'], $live['id']);
                    unset($liveCourseData[$key]);
                }else{
                    $timeLeft = $startTime - time();
                    $timeLeft2 = ($startTime-900);
                    if($timeLeft < 0 || (time() > $timeLeft2))
                        $timeLeft = 0;

                    $liveCourseData[$key]['time_left'] = $timeLeft;
                    $liveCourseData[$key]['today_t'] = date("Ymd", $startTime);
                    $liveCourseData[$key]['time_length'] = date('m月d日 H:i',$startTime).'-'.date('H:i', $endTime);
                }
            }
        }

        return $this->render('PageBundle:Page:index.html.twig',
            array(
                'memberId' => $memberId,
                'todayDate' => date('Ymd'),
                'courseData' => $courseData,
                'liveCourseData' => $liveCourseData
            )
        );
    }

    /**
     * 关闭直播间
     * @param $roomToken
     * @param $liveId
     */
    public function endMeeting($roomToken, $liveId){
        $emt = $this->getDoctrine()->getManager();
        $conference = $emt->getRepository('StoreBundle:XcConference')->findOneBy(array('roomToken' => $roomToken));
        if($conference && $conference->getConferenceStatus() != '7'){
            $conference->setConferenceStatus(6);
            $emt->flush(); //直播间状态更新为6: 关闭

            //获取直播间状态
            $cm = new CornMeeting(
                $this->container->getParameter("app_type"),
                $this->container->getParameter("app_ui_type"),
                $this->container->getParameter("web_service_key"),
                $this->container->getParameter("web_service_url")
            );
            $users = $cm->wbGetRoomUsers($roomToken, 'onlineList'); //实际参与用户
            if(isset($users['online'])){
                $users = json_encode($users['online']);
                $courseService = $this->get('course_service');
                $courseService->setLiveCourseAttend($liveId, $users); //redis记录直播实际参与人数
            }
        }
    }

    public function aboutAction()
    {
        return $this->render('PageBundle:Page:about.html.twig');
    }

    public function contactAction()
    {
        return $this->render('PageBundle:Page:contact.html.twig');
    }

    public function termsAction()
    {
        return $this->render('PageBundle:Page:terms.html.twig');
    }

    public function feedbackAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        $memberId = $session->get('member_id');
        if(!empty($memberId)){
            $conn = $this->get('database_connection');
            $sql = "SELECT `email` FROM `xc_member` WHERE `id` = $memberId LIMIT 1";
            $memberData = $conn->fetchAll($sql);
            $email = $memberData[0]['email'];
        }else{
            $email = '';
        }

        return $this->render('PageBundle:Page:feedback.html.twig',array('email'=>$email));
    }

    public function feedbacksubmitAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        $memberId = $session->get('member_id');
        $content = $request->get('content');
        $email = $request->get('email');
        if($request->getMethod() === "POST" && !empty($content)){
            $feedback = new XcFeedback();
            if(!empty($memberId)){
                $feedback->setMemberId($memberId);
            }
            $feedback->setEmail($email);
            $feedback->setContent($content);
            $feedback->setCreateTime(new \DateTime(date("Y-m-d H:i:s")));
            $feedback->setStatus(1);
            $em = $this->getDoctrine()->getManager();
            $em->persist($feedback);
            $em->flush();
            $data['code'] = 200;
            $data['msg'] = '留言成功，非常感谢您的意见！';
        }

        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function warnAction()
    {
        return $this->render('PageBundle:Page:warn.html.twig');
    }

    public function errorAction(FlattenException $exception){
        $code = $exception->getStatusCode();
        if($code == '404'){
            return $this->render('PageBundle:Page:error404.html.twig');
        }else{
            return $this->render('PageBundle:Page:error.html.twig');
        }
    }
}
