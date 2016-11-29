<?php

namespace Xiucai\PageBundle\Controller;

use Proxies\__CG__\Xiucai\StoreBundle\Entity\XcInstructor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Xiucai\ServiceBundle\Utilities\CourseService;
use Xiucai\ServiceBundle\CornMeeting\CornMeeting;
use Xiucai\ServiceBundle\CornMeeting\object;
use Xiucai\StoreBundle\Entity\XcPost;
use Xiucai\StoreBundle\Entity\XcCategory;

class LiveCourseController extends Controller
{
    /**
     * 直播课程列表页
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        date_default_timezone_set("PRC");
        $rType = $request->get('rType');
        $rType = empty($rType) ? "new" : $rType; //最新直播，往期直播type
        $param['rType'] = $rType;

        $level = $request->get('level'); //课程等级搜索条件
        $level = empty($level) ? "" : $level;
        $param['level'] = $level;
        $liveCourseData = $this->_getLiveCourseData($param);
        unset($liveCourseData['course_count']);


        $emt = $this->getDoctrine()->getManager();
        $focusImageData = $emt->getRepository('StoreBundle:XcFocusImage')
            ->findBy(array('areaId' => 2,'isActive'=>1),array('order' => 'DESC'));

        //print_r($liveCourseData); exit;
        return $this->render('PageBundle:Live:index.html.twig',
            array(
                'rType'          => $rType,
                'level'          => $level,
                'liveCourseData' => $liveCourseData,
                'focusImageData' => $focusImageData,
                'today_date'     => date('Ymd')
            )
        );
    }

    /**
     * ajax加载更多直播课程
     * @param Request $request
     * @return Response
     */
    public function ajaxLoadLiveCourseAction(Request $request){
        $data['code'] = 300; //初始化返回状态
        if($request->getMethod() === "POST"){
            $rType = $request->get('rType');
            $rType = empty($rType) ? "new" : $rType; //最新直播，往期直播type
            $param['rType'] = $rType;

            $level = $request->get('level');
            $level = empty($level) ? "" : $level;
            $param['level'] = $level;  //等级限制条件
            $param['page'] = $request->get('page'); //分页页码

            $liveCourseData = $this->_getLiveCourseData($param);
            $data['course_count'] = $liveCourseData['course_count'];
            unset($liveCourseData['course_count']);
            $data['data'] = $liveCourseData;
            $data['code'] = 200;
        }

        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * 直播课程详情页
     * @param Request $request
     * @return Response
     */
    public function detailAction(Request $request){
        $contentId = 2;
        $postData = "";
        $teacherCourseData = "";
        $conferenceStatus = "";
        $courseId = $request->get('id'); //课程ID
        $type = $request->get('type'); //课程tab标签
        $type = (empty($type)) ? "about" : $type;

        $emt = $this->getDoctrine()->getManager();
        $session = $this->get('request')->getSession();

        $loginInfo = $this->getUser();
        $memberId = empty($loginInfo) ? $session->get('member_id') : $loginInfo->getMemberId();
        if(!empty($memberId)){
            $memberInfo = $emt->getRepository('StoreBundle:XcMember')
                ->findOneBy(array('id' => $memberId));

            $user['id'] = $memberId; //评论人的user_id
            $user['name'] = $memberInfo->getNickname(); //评论用户的名称
            $user['logo'] = $memberInfo->getAvatar(); //评论用户的头像
        }else{
            $user['id'] = 0; //评论人的user_id
            $user['name'] = '游客'; //默认评论用户的名称
            $user['logo'] = 'asset/images/male.gif'; //默认评论用户的头像
        }

        $courseData = $emt->getRepository('StoreBundle:XcLiveCourse')
            ->findOneBy(array('id' => $courseId)); //获取课程详细信息，状态status：4
        if(empty($courseData)){
            $this->location($this->generateUrl('PageBundle_course_live'),'该课程不存在或未审核。');
            exit;
        }else{
            if($courseData->getStatus() == 7){
                $this->location($this->generateUrl('PageBundle_course_live'),'该课程不存在或已被删除。');
                exit;
            }
        }

        //直播房间信息
        $conference = $emt->getRepository('StoreBundle:XcConference')
            ->findOneBy(array('id' => $courseData->getConfId())); //获取房间详细信息
        if(!empty($conference)){
            $conferenceStatusData = $this->_setLiveStatusBtn($conference, $courseId, $memberId);
            $conferenceStatus = $conferenceStatusData['roomStatus'];
            $showBgStatus = $conferenceStatusData['showBgStatus'];
        }

        //课程所属分类
        $categoryId = $courseData->getCategoryId();
        if(!empty($categoryId)){
            $categoryData = $emt->getRepository('StoreBundle:XcCategory')
                ->findOneBy(array('categoryId' => $categoryId));
            $categoryName = $categoryData->getName();

            //喜欢本课的人也喜欢 同一类别下的课程
            $sql = 'SELECT l.id,l.title,l.img_url
            FROM xc_live_course AS l
            LEFT JOIN xc_conference as con ON l.conf_id = con.id
            WHERE l.status = 4 AND con.conference_status = 3 AND category_id = :categoryId
            ORDER BY l.schedule_time DESC limit 3';
            $where[':categoryId'] = $categoryId;

            $conn = $this->get('database_connection'); //获取数据库连接对象
            $ready = $conn->prepare($sql);
            $ready->execute($where); //搜索参数执行匹配
            $favoriteCourse = $ready->fetchAll();
        }else{
            $categoryName = '无';
        }

        if(empty($favoriteCourse) || count($favoriteCourse) < 3){
            $lIdStr = "";
            if(!empty($favoriteCourse)){
                foreach($favoriteCourse as $f){
                    $lIdStr .= $f['id'].',';
                }
                $lIdStr = trim($lIdStr, ',');
            }

            $cLimit = 3 - count($favoriteCourse);
            //喜欢本课的人也喜欢 同一类别下的课程
            $sql = 'SELECT l.`id`,l.`conf_id`,l.`title`,l.`img_url`
            FROM `xc_live_course` AS l
            LEFT JOIN  `xc_conference` AS con ON con.id = l.conf_id
            WHERE l.`status` = 4 AND con.conference_status = 3 ';
            if(!empty($lIdStr)){
                $sql .= ' AND l.id not in('.$lIdStr.') ORDER BY l.`schedule_time` ASC LIMIT '.$cLimit;
            }else{
                $sql .= ' ORDER BY l.`schedule_time` ASC LIMIT '.$cLimit;
            }

            $conn = $this->get('database_connection'); //获取数据库连接对象
            $favoriteCourse2 = $conn->fetchAll($sql);
            $favoriteCourse = array_merge($favoriteCourse, $favoriteCourse2);
        }

        //录播课程页面主讲人信息
        $teacherData = $emt->getRepository('StoreBundle:XcInstructor')
            ->findOneBy(array('id' => $courseData->getInstructorId())); //获取课程主讲人信息

        if($type == 'comment'){
            //获取课程讨论信息
            $postResult = $this->_getCoursePost($courseId);
            $postData = $postResult['post_data'];
        }else{
            //主讲人开设的课程
            unset($where);
            $sql = 'SELECT id,title FROM xc_live_course WHERE
            status = 4 AND  instructor_id = :instructorId AND id <> :courseId ORDER BY id DESC limit 5';
            $where[':courseId'] = $courseId;
            $where[':instructorId'] = $courseData->getInstructorId(); //获取课程主讲人信息

            $conn = $this->get('database_connection'); //获取数据库连接对象
            $ready = $conn->prepare($sql);
            $ready->execute($where); //搜索参数执行匹配
            $teacherCourseData = $ready->fetchAll();

            /*$teacherCourseData = $emt->getRepository('StoreBundle:XcLiveCourse')
                ->findBy(array('instructorId' => $courseData->getInstructorId(),
                    'status' => 4), array('id' => 'DESC'), 5); //获取课程主讲人信息*/
        }

        $startTime = (array)$courseData->getScheduleTime();
        $startTime = strtotime($startTime['date']); //直播时间
        $endTime = $startTime + ($courseData->getDuration()*60); //直播时长
        $timeLeft = $startTime - time();
        if($timeLeft < 0)
            $timeLeft = 0;

        //直播课程播放报名人数
        $redisKeyList = 'live_course_reserve_count';
        $redis = $this->container->get('snc_redis.default');
        $reserveCount = $redis->zscore($redisKeyList, $courseId);
        $reserveCount = (empty($reserveCount)) ? 0 : $reserveCount;

        /*$courseService = $this->get('course_service');
        $courseService->setMemberLive($startTime,$courseId);*/
        return $this->render('PageBundle:Live:detail.html.twig',
            array(
                'user' => $user,
                'type' => $type,
                'endTime' => $endTime,
                'timeLeft' => $timeLeft,
                'postData' => $postData,
                'contentId' => $contentId,
                'conference' => $conference,
                'courseData' => $courseData,
                'teacherData' => $teacherData,
                'reserveCount' => $reserveCount,
                'favoriteCourse' => $favoriteCourse,
                'conferenceStatus' => $conferenceStatus,
                'showBgStatus' => $showBgStatus,
                'teacherCourseData' => $teacherCourseData,
                'categoryName' => $categoryName,
                'today_date'     => date('Y-m-d')
            )
        );
    }

    /**
     * 直播详情页直播状态
     * @param $conference 直播间详情
     * @param $courseId 直播课程ID
     * @param $memberId 登录用户
     * @return string
     */
    private function _setLiveStatusBtn($conference, $courseId, $memberId){
        $roomToken = $conference->getRoomToken();
        if(!empty($roomToken)){
            $startTime = (array)$conference->getScheduleTime();
            $endTime = strtotime($startTime['date']) + $conference->getDuration()*60; //直播结束时间
            $startTime = strtotime($startTime['date']) - 900; //直播开始时间

            if($conference->getConferenceStatus() == 6){
                $showBgStatus = 2;
                $roomStatus = '直播已结束';
            }else if($endTime < time()){
                //直播结束
                $showBgStatus = 2;
                $roomStatus = '直播已结束';
                $this->endMeeting($roomToken, $courseId);
            }else if($startTime > time()){
                //开课前
                if(!empty($memberId)){
                    //已登录判断课程是否免费
                    $payment = $this->get('payment_service');
                    $isFree = $payment->ifAllowAccess($memberId, $courseId, 2);

                    //直播间管理员
                    $emt = $this->getDoctrine()->getManager();
                    $owner = $emt->getRepository('StoreBundle:XcOptions')->findOneBy(array('key' => 'owner')); //演讲人-测试管理员用户jason
                    $owner = json_decode($owner->getValue(), true); //拥有者/创建者,一般是企业用户对象
                    $roomAdmin[] = $owner['uid'];

                    //获取直播间成员状态
                    $cm = new CornMeeting(
                        $this->container->getParameter("app_type"),
                        $this->container->getParameter("app_ui_type"),
                        $this->container->getParameter("web_service_key"),
                        $this->container->getParameter("web_service_url")
                    );

                    $roomUser = $cm->wbGetRoomUsers($roomToken, 'all');
                    $moderator = $roomUser['moderator'];
                    /*$moderator = $emt->getRepository('StoreBundle:XcOptions')->findOneBy(array('key' => 'moderator'));
                    $moderator = json_decode($moderator->getValue(), true);*/
                    if(!empty($moderator)){
                        foreach($moderator as $m){
                            $roomAdmin[] = $m['uid'];
                        }
                    }

                    if($isFree == 1){
                        //已购买，判断角色是否可进入直播
                        if(in_array($memberId, $roomAdmin)){
                            $showBgStatus = 500;
                            $roomStatus = '进入教室'; //管理员随时可进入直播
                        }else{
                            //免费，判断用户是否参加过该课程
                            $isAttend = $payment->existCourseInventory($memberId, $courseId, 2);
                            if($isAttend){
                                $showBgStatus = 2;
                                $roomStatus = '直播尚未开始';
                            }else{
                                $showBgStatus = 1;
                                $roomStatus = '我要报名';
                            }
                        }
                    }else if($isFree == 2){
                        //开课前已支付，普通用户状态为直播尚未开始，管理员状态为进入教室
                        if(in_array($memberId, $roomAdmin)){
                            $showBgStatus = 500;
                            $roomStatus = '进入教室'; //管理员随时可进入直播
                        }else{
                            $showBgStatus = 2;
                            $roomStatus = '直播尚未开始';
                        }
                    }else if($isFree == 3){
                        //未支付
                        $showBgStatus = 1;
                        $roomStatus = '我要报名';
                    }
                }else{
                    //未登录
                    $showBgStatus = 1;
                    $roomStatus = '我要报名';
                }
            }else if($startTime <= time()){
                //课程进行中，开始时间前15分钟
                if(empty($memberId) || $memberId == 0){
                    //用户未登录状态显示
                    $showBgStatus = 1;
                    $roomStatus = '进入教室';
                }else{
                    $payment = $this->get('payment_service');
                    $isFree = $payment->ifAllowAccess($memberId, $courseId, 2);
                    if($isFree == 1){
                        //免费课程添加购该权限
                        $showBgStatus = 400;
                        $roomStatus = '进入教室';

                        /*//免费课程添加购该权限
                        $isInventory = $payment->existCourseInventory($memberId, $courseId, 2);
                        if($isInventory){
                            $showBgStatus = 500;
                            $roomStatus = '进入教室';
                        }else{
                            $showBgStatus = 1;
                            $roomStatus = '进入教室';
                        }*/
                    }else if($isFree == 2){
                        //收费课程已支付
                        $showBgStatus = 500;
                        $roomStatus = '进入教室';
                    }else if($isFree == 3){
                        //未支付，跳转到支付页面
                        $showBgStatus = 1;
                        $roomStatus = '进入教室';
                    }
                }
            }else if($endTime < time()){
                //直播结束
                $showBgStatus = 2;
                $roomStatus = '直播已结束';
                $this->endMeeting($roomToken, $courseId);
            }
        }else{
            $showBgStatus = 2;
            $roomStatus = "未创建直播";
        }

        return array('showBgStatus'=>$showBgStatus,'roomStatus'=>$roomStatus);
    }

    /**
     * 直播详情页按钮点击判断处理
     * @param Request $request
     * @return Response
     */
    public function liveStatusAction(Request $request){
        $loginInfo = $this->getUser();
        $emt = $this->getDoctrine()->getManager();
        $session = $this->get('request')->getSession();
        $memberId = empty($loginInfo) ? $session->get('member_id') : $loginInfo->getMemberId();
        if(empty($memberId)){
            $result['code'] = 404;
        }else{
            $courseId = $request->get('course_id');
            $liveCourseData = $emt->getRepository('StoreBundle:XcLiveCourse')
                ->findOneBy(array('id' => $courseId, 'status' => 4)); //获取课程详细信息，状态status：4
            if(empty($liveCourseData)){
                $result['code'] = 300;
                $result['msg'] = "该课程不存在或为审核显示。";
            }else{
                $confId = $liveCourseData->getConfId();
                if(empty($confId)){
                    $result['code'] = 300;
                    $result['msg'] = "该课程未创建直播。";
                }else{
                    $conference = $emt->getRepository('StoreBundle:XcConference')
                        ->findOneBy(array('id' => $confId)); //获取房间详细信息
                    if(empty($conference)){
                        $result['code'] = 300;
                        $result['msg'] = "该课程直播间不存在。";
                    }else{
                        $startTime = (array)$conference->getScheduleTime();
                        $startT = strtotime($startTime['date']);
                        $endTime = $startT + $conference->getDuration()*60; //直播结束时间
                        $startTime = $startT - 900; //直播开始时间

                        $roomToken = $conference->getRoomToken();
                        if(empty($roomToken)){
                            $result['code'] = 300;
                            $result['msg'] = "该课程未创建直播。";
                        }else if($conference->getConferenceStatus() == 6){
                            $result['code'] = 300;
                            $result['msg'] = "直播已结束。";
                        }else if($endTime < time()){
                            //直播结束
                            $result['code'] = 300;
                            $result['msg'] = '直播已结束';
                            $this->endMeeting($roomToken, $courseId);
                        }else{
                            //直播状态处理
                            $payment = $this->get('payment_service');
                            $isFree = $payment->ifAllowAccess($memberId, $courseId, 2);
                            if($startTime > time()){
                                //开课前已登录判断课程是否免费
                                if($isFree == 1 || $isFree == 2){
                                    //已购买，判断角色是否可进入直播
                                    $owner = $emt->getRepository('StoreBundle:XcOptions')->findOneBy(array('key' => 'owner')); //演讲人-测试管理员用户jason
                                    $owner = json_decode($owner->getValue(), true); //拥有者/创建者,一般是企业用户对象
                                    $roomAdmin[] = $owner['uid'];

                                    //获取直播间成员状态
                                    $cm = new CornMeeting(
                                        $this->container->getParameter("app_type"),
                                        $this->container->getParameter("app_ui_type"),
                                        $this->container->getParameter("web_service_key"),
                                        $this->container->getParameter("web_service_url")
                                    );

                                    $roomUser = $cm->wbGetRoomUsers($roomToken, 'all');
                                    $moderator = $roomUser['moderator'];
                                    /*$moderator = $emt->getRepository('StoreBundle:XcOptions')->findOneBy(array('key' => 'moderator'));
                                    $moderator = json_decode($moderator->getValue(), true);*/
                                    if(!empty($moderator)){
                                        foreach($moderator as $m){
                                            $roomAdmin[] = $m['uid'];
                                        }
                                    }
                                    if(in_array($memberId, $roomAdmin)){
                                        $result['code'] = 200; //管理员随时可进入直播
                                    }else{
                                        //免费，判断用户是否参加过该课程
                                        $isAttend = $payment->existCourseInventory($memberId, $courseId, 2);
                                        if($isAttend){
                                            $result['code'] = 300;
                                            $result['msg'] = "直播尚未开始。";
                                        }else{
                                            //添加用户购买权限
                                            $payment->insertCourseInventory(array('member_id' => $memberId,
                                                'content_id' => $courseId ,'content_type' => 2));

                                            //添加用户直播课程列表
                                            $courseService = $this->get('course_service');
                                            $courseService->setMemberLive($startT, $courseId, $memberId);

                                            //直播课程预约人数列表
                                            $courseService->setLiveCourseReserveCount($courseId);

                                            //单个直播课程预约用户列表
                                            $user['uid'] = $memberId;
                                            $user['logo'] = $session->get('avatar');
                                            $user['name'] = $session->get('username');
                                            $user = json_encode($user);
                                            $courseService->setLiveCourseReserve($courseId, $user);

                                            $result['code'] = 500;
                                            $result['msg'] = "恭喜您报名成功，请准时上课。";
                                        }
                                    }
                                }else if($isFree == 3){
                                    //未支付，跳转到支付页面
                                    $result['code'] = 301;
                                }
                            }else if($startTime <= time()){
                                //课程进行中，开始时间前15分钟
                                if($isFree == 1){
                                    //免费课程添加购该权限
                                    $isInventory = $payment->existCourseInventory($memberId, $courseId, 2);
                                    if($isInventory){
                                        $result['code'] = 200;
                                    }else{
                                        $result['code'] = 501;
                                        //添加用户购买权限
                                        $payment->insertCourseInventory(array('member_id' => $memberId,
                                            'content_id' => $courseId ,'content_type' => 2));

                                        //添加用户直播课程列表
                                        $courseService = $this->get('course_service');
                                        $courseService->setMemberLive($startT, $courseId, $memberId);

                                        //直播课程预约人数列表
                                        $courseService->setLiveCourseReserveCount($courseId);

                                        //单个直播课程预约用户列表
                                        $user['uid'] = $memberId;
                                        $user['logo'] = $session->get('avatar');
                                        $user['name'] = $session->get('username');
                                        $user = json_encode($user);
                                        $courseService->setLiveCourseReserve($courseId, $user);
                                    }
                                }else if($isFree == 2){
                                    //收费课程已支付
                                    $result['code'] = 200;
                                }else if($isFree == 3){
                                    //未支付，跳转到支付页面
                                    $result['code'] = 301;
                                }
                            }else if($endTime < time()){
                                //直播结束
                                $result['code'] = 300;
                                $result['msg'] = '直播已结束';
                                $this->endMeeting($roomToken, $courseId);
                            }
                        }
                    }
                }
            }
        }

        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * 进入直播间
     * @param Request $request
     * @return Response
     */
    public function enterLiveRoomAction(Request $request){
        //初始化直播间接口
        $cm = new CornMeeting(
            $this->container->getParameter("app_type"),
            $this->container->getParameter("app_ui_type"),
            $this->container->getParameter("web_service_key"),
            $this->container->getParameter("web_service_url")
        );

        $loginInfo = $this->getUser();
        $emt = $this->getDoctrine()->getManager();
        $session = $this->get('request')->getSession();
        $memberId = empty($loginInfo) ? $session->get('member_id') : $loginInfo->getMemberId();
        $courseId = $request->get('id');
        if(empty($memberId)){
            $this->location($this->generateUrl('PageBundle_live_detail',array('id'=>$courseId)),'您还没有登录，请先登录再进入教室。');
            exit;
        }else{
            $memberInfo = $emt->getRepository('StoreBundle:XcMember')->findOneBy(array('id' => $memberId));
        }


        $courseData = $emt->getRepository('StoreBundle:XcLiveCourse')
            ->findOneBy(array('id' => $courseId, 'status' => 4)); //获取课程详细信息，状态status：4
        if(empty($courseData)){
            $this->location($this->generateUrl('PageBundle_live_detail',array('id'=>$courseId)),'该课程不存在或未审核。');
            exit;
        }else{
            $confId = $courseData->getConfId();
            if(empty($confId)){
                $this->location($this->generateUrl('PageBundle_live_detail',array('id'=>$courseId)),'该课程直播间不存在');
                exit;
            }

            $conference = $emt->getRepository('StoreBundle:XcConference')->findOneBy(array('id' => $confId));
            if(empty($conference)){
                $this->location($this->generateUrl('PageBundle_live_detail',array('id'=>$courseId)),'该课程直播间不存在');
                exit;
            }

            if($conference->getConferenceStatus() == 6){
                $this->location($this->generateUrl('PageBundle_live_detail',array('id'=>$courseId)),'该课程直播已结束。');
                exit;
            }

            $roomToken = $conference->getRoomToken();
            $startTime = (array)$conference->getScheduleTime();
            $startTime = strtotime($startTime['date']) - 900; //直播开始时间
            if($startTime > time()){
                //开课前判断用户是否是管理员
                $roomUser = $cm->wbGetRoomUsers($roomToken, 'all'); //获取直播间所有成员
                if(!empty($roomUser['moderator'])){
                    foreach($roomUser['moderator'] as $m){
                        $roomAdmin[] = $m['uid'];
                    }

                    if(!empty($roomAdmin)){
                        if(!in_array($memberId, $roomAdmin)){
                            $this->location($this->generateUrl('PageBundle_live_detail',array('id'=>$courseId)),'直播尚未开始。');
                            exit;
                        }
                    }
                }
            }

            //已登录，判断是否购买该直播
            $payment = $this->get('payment_service');
            $isFree = $payment->ifAllowAccess($memberId, $courseId, 2);
            if($isFree == 1){
                //免费，判断用户是否参加过该课程
                $isAttend = $payment->existCourseInventory($memberId, $courseId, 2);
                if(!$isAttend){
                    //添加用户购买权限
                    $payment->insertCourseInventory(array('member_id' => $memberId,
                        'content_id' => $courseId ,'content_type' => 2));

                    //添加用户直播课程列表
                    $startTime = (array)$courseData->getScheduleTime();
                    $startTime = strtotime($startTime['date']); //直播时间
                    $courseService = $this->get('course_service');
                    $courseService->setMemberLive($startTime, $courseId, $memberId);

                    //直播课程预约人数列表
                    $courseService->setLiveCourseReserveCount($courseId);

                    //单个直播课程预约用户列表
                    $user['uid'] = $memberId;
                    $user['logo'] = $session->get('avatar');
                    $user['name'] = $session->get('username');
                    $user = json_encode($user);
                    $courseService->setLiveCourseReserve($courseId, $user);
                }
            }elseif($isFree == 3){
                //未支付
                $this->location($this->generateUrl('PageBundle_live_detail',array('id'=>$courseId)),'您没有购买该课程，暂无权限观看。');
                exit;
            }
            /*$isPayment = $payment->existCourseInventory($memberId, $courseId, 2); //content_type 1:录播 2：直播
            if(!$isPayment){
                header('content-type:text/html;charset=utf-8;');
                echo "<script>alert('您没有购买该课程，暂无权限观看。');window.opener=null;window.close(); </script>";
                exit;
            }*/
        }

        if(!empty($roomToken)){
            //获取直播间状态
            $json = $cm->wbGetRoomStatus($roomToken);
            if(!empty($json['exp'])){
                $this->location($this->generateUrl('PageBundle_live_detail',array('id'=>$courseId)),"'".$json['exp']."'");
                exit;
            }else{
                /*print_r($memberInfo);
                $users = $cm->wbGetRoomUsers($roomToken, 'all'); //实际参与用户
                print_r($users); exit;*/
                $avatar = "http://" . $_SERVER ['HTTP_HOST'].$memberInfo->getAvatar();
                $user = array("uid" => (string)$memberInfo->getId(),
                    "logo" => $avatar, "name" => $memberInfo->getNickname());

                $roomUrl = $cm->wbRooms($roomToken, $user, 'RTMP_P2P');
            }
        }else{
            $this->location($this->generateUrl('PageBundle_live_detail',array('id'=>$courseId)),'该课程直播间不存在');
            exit;
        }

        return $this->render('PageBundle:Live:room.html.twig',
            array('roomUrl' => $roomUrl, 'courseData' => $courseData)
        );
    }

    /**
     * 获取录播课程信息处理
     * @param $param 条件参数 默认五条
     * @param int $limit 限制条数
     * @return mixed
     */
    private function _getLiveCourseData($param, $limit = 3){
        $courseNewData = array();
        $page = empty($param['page']) ? 1 : $param['page']; //当前页数初始化
        $offset = ($page - 1)*$limit; //计算分页偏移量
        $conn = $this->get('database_connection'); //获取数据库连接对象

        //获取课程列表信息sql语句
        $sql = "SELECT l.`id`,l.`conf_id`,l.`title`,l.`brief`,l.`reserve_num`,l.`current_price`,l.duration,
        l.`course_level`,l.`img_url`,l.`schedule_time`,con.room_token
        FROM `xc_live_course` AS l
        LEFT JOIN  `xc_conference` AS con ON con.id = l.conf_id
        WHERE l.`status` = 4 ";

        if($param['rType'] == 'new'){
            $sql .= " AND con.conference_status = 3 ";
        }else{
            $sql .= " AND con.conference_status = 6";
        }

        //搜索课程等级条件处理
        if(!empty($param['level'])){
            $sql .= " AND course_level = :course_level ";
            $where[':course_level'] = $param['level']; //录播课程等级限制条件
        }

        if($param['rType'] == 'new'){
            $sql .= " ORDER BY l.`schedule_time` ASC LIMIT $offset, $limit "; //按修改时间倒序排列
        }else{
            $sql .= " ORDER BY l.`schedule_time` DESC LIMIT $offset, $limit "; //按修改时间倒序排列
        }

        if(!empty($where)){
            $ready = $conn->prepare($sql);
            $ready->execute($where); //搜索参数执行匹配
            $courseData = $ready->fetchAll();
        }else{
            $courseData = $conn->fetchAll($sql);
        }

        if(!empty($courseData)){
            $redis = $this->container->get('snc_redis.default');
            foreach($courseData as $key => $course){
                $startTime = strtotime($course['schedule_time']); //直播时间
                $endTime = $startTime + ($course['duration']*60); //直播时长
                if($endTime < time() && $param['rType'] == 'new'){
                    //判断是否直播是否结束
                    $this->endMeeting($course['room_token'], $course['id']);
                    unset($courseData[$key]);
                }else{
                    //直播课程播放报名人数
                    $redisKeyList = 'live_course_reserve_count';
                    $reserveCount = $redis->zscore($redisKeyList, $course['id']);
                    $reserveCount = (empty($reserveCount)) ? 0 : $reserveCount;
                    $courseData[$key]['reserve_count'] = $reserveCount;

                    $timeKey = date('Ymd', strtotime($course['schedule_time']))."_p";
                    $timeKey2 = date('His', strtotime($course['schedule_time']))."_c";
                    if($course['course_level'] == 1){
                        $courseData[$key]['course_level'] = '初级';
                    }else if($course['course_level'] == 2){
                        $courseData[$key]['course_level'] = '中级';
                    }else{
                        $courseData[$key]['course_level'] = '高级';
                    }

                    $currentMd = date("md", time());
                    $startMd = date('md',$startTime);
                    if($currentMd == $startMd){
                        $courseData[$key]['day_box'] = "today";
                    }else{
                        $courseData[$key]['day_box'] = date('md',$startTime);
                    }
                    $timeLeft = $startTime - time();
                    $timeLeft2 = ($startTime-900);
                    if($timeLeft < 0 || (time() > $timeLeft2))
                        $timeLeft = 0;

                    $courseData[$key]['time_left'] = $timeLeft;
                    $courseData[$key]['img_url'] = "http://" . $_SERVER ['HTTP_HOST'].$course['img_url'];
                    $courseData[$key]['time_length'] = date('H:i',$startTime).'-'.date('H:i',$endTime);
                    $courseNewData[$timeKey][$timeKey2] = $courseData[$key];
                }
            }

            $courseNewData['course_count'] = count($courseData);
        }else{
            $courseNewData['course_count'] = 0;
        }

        return $courseNewData;
    }

    /**
     * 获取课程讨论信息
     * @param $contentId
     * @param int $page
     * @param int $limit
     * @return mixed
     */
    private function _getCoursePost($contentId, $page = 1, $limit = 5){
        $offset = ($page - 1)*$limit; //计算分页偏移量
        $conn = $this->get('database_connection'); //获取数据库连接对象

        $sql = "SELECT `id`,`content_id`,`content_type`,`member_id`,
            `member_name`,`member_logo`,`member_ip`,`create_time`,`content`,`comment_num`
            FROM `xc_post`
            WHERE `status` = 4 AND content_id = :content_id AND content_type = 2 ";
        $sql .= " ORDER BY `create_time` DESC LIMIT $offset, $limit "; //按讨论时间倒序排列

        $where[':content_id'] = $contentId; //评论类型id限制条件
        $ready = $conn->prepare($sql);
        $ready->execute($where); //搜索参数执行匹配
        $result['post_data'] = $ready->fetchAll();

        return $result;
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

            //获取直播间状 态
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

    public function location($url,$msg,$ifClose=0){
        header('content-type:text/html;charset=utf-8;');
        $host = $_SERVER['HTTP_HOST'];
        echo "<script src='http://$host/assets/js/jquery-1.7.2.min.js'></script>";
        echo "<script src='http://$host/assets/js/layer/layer.js'></script>";
        echo "<body>";
        if($ifClose == 1){
            echo "<script>layer.alert('$msg','',function(){window.opener=null;window.close();});</script>";
        }else{
            echo "<script>layer.alert('$msg','',function(){location.href='".$url."';});</script>";
        }
        //echo "<script>layer.alert('$msg');location.href='".$url."';</script>";
        //echo "<script>layer.confirm('$msg', function(){location.href='".$url."';});</script>";
        //echo "<script>layer.msg('$msg',3,'',function(){location.href='".$url."'} );</script>";
        echo "</body>";
        exit;
    }
}