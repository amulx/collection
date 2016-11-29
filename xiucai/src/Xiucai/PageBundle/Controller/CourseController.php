<?php

namespace Xiucai\PageBundle\Controller;

use Proxies\__CG__\Xiucai\StoreBundle\Entity\XcInstructor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Xiucai\StoreBundle\Entity\XcPost;
use Xiucai\StoreBundle\Entity\XcFocusImage;
use Xiucai\StoreBundle\Entity\XcCategory;

class CourseController extends Controller
{
    /**
     * 录播课程列表页
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $level = $request->get('level'); //课程等级搜索条件
        $level = empty($level) ? "" : $level;
        $param['level'] = $level;
        $courseData = $this->_getCourseData($param);
        unset($courseData['course_count']);
        //$courseData = $emt->getRepository('StoreBundle:XcCourse')->findBy($where, array('id' => 'DESC'), 5);
        $emt = $this->getDoctrine()->getManager();
        $focusImageData = $emt->getRepository('StoreBundle:XcFocusImage')
            ->findBy(array('areaId' => 1,'isActive'=>1),array('order' => 'DESC'));
        return $this->render('PageBundle:Course:index.html.twig',
            array(
                'level' => $level,
                'courseData' => $courseData,
                'focusImageData' => $focusImageData
            )
        );
    }

    /**
     * ajax加载更多录播课程
     * @param Request $request
     * @return Response
     */
    public function ajaxLoadCourseAction(Request $request){
        $data['code'] = 300; //初始化返回状态
        if($request->getMethod() === "POST"){
            $level = $request->get('level');
            $level = empty($level) ? "" : $level;
            $param['level'] = $level;  //等级限制条件
            $param['page'] = $request->get('page'); //分页页码

            $courseData = $this->_getCourseData($param);
            $data['course_count'] = $courseData['course_count'];
            unset($courseData['course_count']);
            $data['data'] = $courseData;
            $data['code'] = 200;
        }

        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * 录播课程详情页
     * @param Request $request
     * @return Response
     */
    public function detailAction(Request $request){
        $contentId = 1;
        $postData = "";
        $courseChapterData = "";
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

        $courseData = $emt->getRepository('StoreBundle:XcCourse')
            ->findOneBy(array('id' => $courseId)); //获取课程详细信息，状态status：1
        if(empty($courseData)){
            $this->location($this->generateUrl('PageBundle_course_video'),'该课程不存在或未审核。');
            exit;
        }else{
            if($courseData->getStatus() == 7){
                $this->location($this->generateUrl('PageBundle_course_video'),'该课程不存在或已被删除。');
                exit;
            }
        }
        $categoryId = $courseData->getCategoryId();
        if(!empty($categoryId)){
            $categoryData = $emt->getRepository('StoreBundle:XcCategory')
                ->findOneBy(array('categoryId' => $categoryId));
            $categoryName = $categoryData->getName();
        }else{
            $categoryName = '无';
        }
        //课程状态
        $courseStatusData = $this->_setCourseStatusBtn($courseId, $memberId);
        $courseStatus = $courseStatusData['roomStatus'];
        $showBgStatus = $courseStatusData['showBgStatus'];

        //录播课程页面主讲人信息
        $teacherData = $emt->getRepository('StoreBundle:XcInstructor')
            ->findOneBy(array('id' => $courseData->getInstructorId())); //获取课程主讲人信息

        if($type == 'chapter'){
            //获取录播课程所有分节信息
            $courseChapterData = $emt->getRepository('StoreBundle:XcVideo')
                ->findBy(array('contentId' => $courseId, 'status' => 4), array('zindex' => 'ASC'));
        }elseif($type == 'comment'){
            //获取课程讨论信息
            $postResult = $this->_getCoursePost($courseId, 1);
            $postData = $postResult['post_data'];
        }
        if($type == 'about'){
            $courseChapterFirst = $emt->getRepository('StoreBundle:XcVideo')
                ->findOneBy(array('contentId' => $courseId, 'status' => 4,'isFree'=>1), array('zindex' => 'ASC'));
        }else{
            $courseChapterFirst = '';
        }

        //主讲人开设的课程
        /*$teacherCourseData = $emt->getRepository('StoreBundle:XcCourse')
            ->findBy(array('instructorId' => $courseData->getInstructorId(),
                'status' => 4), array('id' => 'DESC'), 5); //获取课程主讲人信息*/

        $sql = 'SELECT id,title FROM xc_course WHERE
            status = 4 AND  instructor_id = :instructorId AND id <> :courseId ORDER BY id DESC limit 5';
        $where[':courseId'] = $courseId;
        $where[':instructorId'] = $courseData->getInstructorId(); //获取课程主讲人信息

        $conn = $this->get('database_connection'); //获取数据库连接对象
        $ready = $conn->prepare($sql);
        $ready->execute($where); //搜索参数执行匹配
        $teacherCourseData = $ready->fetchAll();

        //TODO:喜欢本课的人也喜欢
        $favoriteCourse = $emt->getRepository('StoreBundle:XcCourse')
            ->findBy(array('status' => 4), array('reserveNum' => 'DESC'), 3); //喜欢本课的人也喜欢

        $videoId = $request->get('vid');
        if(!empty($videoId)){
            $emt = $this->getDoctrine()->getManager();
            $courseVideo = $emt->getRepository('StoreBundle:XcVideo')
                ->findOneBy(array('id' => $videoId, 'status' => 4));
        }else{
            $courseVideo = "";
            if($type == 'chapter'){
                $courseVideo = $emt->getRepository('StoreBundle:XcVideo')
                    ->findOneBy(array('contentId' => $courseId, 'status' => 4, 'isFree' => 1), array('zindex' => 'ASC'));
            }
        }

        if(!empty($courseVideo)){
            if($courseVideo->getIsFree() == 0){
                if(empty($memberId)){
                    $this->location($this->generateUrl('PageBundle_course_detail', array('id' => $courseId)),'请先登录再观看课程。');
                }else{
                    //登录用户判断用户是否购买该课程
                    $payment = $this->get('payment_service');
                    $isPayment = $payment->existCourseInventory($memberId, $courseId, 1); //content_type 1:录播 2：直播
                    if(!$isPayment){
                        $this->location($this->generateUrl('PageBundle_course_detail', array('id' => $courseId)),'您没有购买该课程，暂无权限观看。');
                        exit;
                    }
                }
            }
            if(!empty($memberId)){
                $courseService = $this->get('course_service');
                $courseService->setCoursePlayPersonCount($memberId, $courseId); //录播课程播放人数列表,每个人只算一次
                $courseService->setCoursePlayCount($courseId, $memberId); //单个录播课程点击数列表
                $courseService->setPlayCount($courseId); //更新录播课程播放次数
            }
        }

        //录播课程播放人数
        $redis = $this->container->get('snc_redis.default');
        $redisKeyList = 'course_play_person_count';
        $personPlayCount = $redis->zscore($redisKeyList, $courseId);
        $personPlayCount = (empty($personPlayCount)) ? 0 : $personPlayCount;

        return $this->render('PageBundle:Course:detail.html.twig',
            array(
                'user' => $user,
                'type' => $type,
                'postData' => $postData,
                'contentId' => $contentId,
                'courseData' => $courseData,
                'teacherData' => $teacherData,
                'courseVideo' => $courseVideo,
                'courseStatus' => $courseStatus,
                'showBgStatus' => $showBgStatus,
                'favoriteCourse' => $favoriteCourse,
                'personPlayCount' => $personPlayCount,
                'teacherCourseData' => $teacherCourseData,
                'courseChapterData' => $courseChapterData,
                'categoryName' => $categoryName,
                'courseChapterFirst' => $courseChapterFirst,
            )
        );
    }

    /**
     * 录播课程按钮状态事件处理
     * @param Request $request
     * @return Response
     */
    public function courseStatusAction(Request $request){
        $loginInfo = $this->getUser();
        $emt = $this->getDoctrine()->getManager();
        $session = $this->get('request')->getSession();
        $memberId = empty($loginInfo) ? $session->get('member_id') : $loginInfo->getMemberId();
        if(empty($memberId)){
            $result['code'] = 404;
        }else{
            $courseId = $request->get('course_id');
            $videoCourseData = $emt->getRepository('StoreBundle:XcCourse')
                ->findOneBy(array('id' => $courseId, 'status' => 4)); //获取课程详细信息，状态status：4
            if(empty($videoCourseData)){
                $result['code'] = 300;
                $result['msg'] = "该课程不存在或为审核显示。";
            }else{
                $payment = $this->get('payment_service');
                $isFree = $payment->ifAllowAccess($memberId, $courseId, 1);
                if($isFree == 1){
                    //免费，判断用户是否参加过该课程
                    $isAttend = $payment->existCourseInventory($memberId, $courseId, 1);
                    if($isAttend){
                        $result['code'] = 200;
                        $result['msg'] = '观看课程';
                    }else{
                        $payment->insertCourseInventory(array('member_id' => $memberId,
                            'content_id' => $courseId ,'content_type' => 1));

                        //添加用户录播课程列表
                        $createTime = (array)$videoCourseData->getCreateTime();
                        $createTime = strtotime($createTime['date']);
                        $courseService = $this->get('course_service');
                        $courseService->setMemberCourse($createTime, $memberId, $courseId);

                        $result['code'] = 500;
                        $result['msg'] = '参加成功，请在课程列表中观看视频。';
                    }
                }else if($isFree == 2){
                    //已支付
                    $result['code'] = 200;
                    $result['msg'] = '已参加';
                }else if($isFree == 3){
                    //未支付
                    $result['code'] = 301;
                }
            }
        }

        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * 录播详情页录播状态
     * @param $courseId 点播课程ID
     * @param $memberId 登录用户ID
     * @return string
     */
    private function _setCourseStatusBtn($courseId, $memberId){
        if(!empty($memberId)){
            //已登录判断课程是否免费
            $payment = $this->get('payment_service');
            $isFree = $payment->ifAllowAccess($memberId, $courseId, 1);
            if($isFree == 1){
                //免费，判断用户是否参加过该课程
                $isAttend = $payment->existCourseInventory($memberId, $courseId, 1);
                if($isAttend){
                    $showBgStatus = 2;
                    $roomStatus = '观看课程';
                }else{
                    $showBgStatus = 1;
                    $roomStatus = '参加课程';
                }
            }else if($isFree == 2){
                //已支付
                $showBgStatus = 2;
                $roomStatus = '观看课程';
            }else if($isFree == 3){
                //未支付
                $showBgStatus = 1;
                $roomStatus = '参加课程';
            }
        }else{
            $showBgStatus = 1;
            $roomStatus = '参加课程';
        }

        return array('showBgStatus'=>$showBgStatus,'roomStatus'=>$roomStatus);;
    }

    /**
     * ajax给老师点赞
     * @param Request $request
     * @return Response
     */
    public function ajaxFavoriteTeachAction(Request $request){
        $result['code'] = 300;
        $result['msg'] = '操作失败。';
        if($request->getMethod() === "POST"){
            $teacherId = $request->get('teacher_id');
            if(!empty($teacherId)){
                $emt = $this->getDoctrine()->getManager();
                $instructor = $emt->getRepository('StoreBundle:XcInstructor')
                    ->findOneBy(array('id' => $teacherId));
                if(!empty($instructor)){
                    $favoriteCount = $instructor->getFavoriteCount();
                    $favoriteCount = empty($favoriteCount) ? 0 : $favoriteCount;
                    $favoriteCount = $favoriteCount + 1;
                    $instructor->setFavoriteCount($favoriteCount);
                    $emt->persist($instructor); //更新老师点赞数量
                    $emt->flush();

                    $result['code'] = 200;
                    $result['favorite_count'] = $favoriteCount;
                }else{
                    $result['msg'] = "该老师不存在或未审核。";
                }
            }
        }

        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * ajax点击加载更多课程讨论
     * @param Request $request
     * @return Response
     */
    public function ajaxLoadPostAction(Request $request){
        $result['code'] = 300;
        if($request->getMethod() === "POST"){
            $page = $request->get('page'); //当前页
            $contentId = $request->get('content_id'); //课程id
            $contentType = $request->get('content_type');
            if(empty($contentId)){
                $result['code'] = 300;
                $result['code'] = "请求参数错误。";
            }else{
                $postResult = $this->_getCoursePost($contentId, $contentType, $page);
                $postData = $postResult['post_data'];
                $postCount = count($postResult['post_data']);

                if(!empty($postData)){
                    $result['code'] = 200;
                    $result['data'] = $postData;
                    $result['total_count'] = $postCount; //统计评论数
                }else{
                    $result['code'] = 404;
                }
            }
        }

        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * 录播课程页面用户课程讨论
     * @param Request $request
     * @return Response
     */
    public function ajaxCoursePostAction(Request $request){
        $data['code'] = 300;
        $params = $request->get('params'); //获取参数
        $content = $request->get('post_content'); //获取讨论内容
        if($request->getMethod() === "POST" && !empty($content)){
            if(empty($params['u_id']) || $params['u_id'] == 0){
                $data['code'] = 404;
                $data['msg'] = '您还未登录，请先登录。';

                $response = new Response(json_encode($data));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }

            $xcPost = new XcPost();
            $xcPost->setStatus(4); //评论状态1有效
            $xcPost->setCommentNum(0); //默认评论数量为0
            $xcPost->setContent($content); //用户发表内容
            $xcPost->setMemberId($params['u_id']); //发表用户Id
            $xcPost->setContentType($params['c_type']); //发表类型
            $xcPost->setContentId($params['course_id']); //发表对象Id：课程模块课程id
            $xcPost->setMemberLogo($params['u_logo']);  //发表讨论用户头像
            $xcPost->setMemberName($params['u_name']); //发表讨论用户名臣
            $xcPost->setMemberIp($_SERVER["REMOTE_ADDR"]); //发表讨论用户ip
            $xcPost->setCreateTime(new \DateTime(date("Y-m-d H:i:s"))); //评论发布时间
            $xcPost->setModifyTime(new \DateTime(date("Y-m-d H:i:s"))); //评论修改时间

            $emt = $this->getDoctrine()->getManager();
            $emt->persist($xcPost); //添加用户讨论操作
            $emt->flush();

            $data['code'] = 200;
            $data['id'] = $xcPost->getId();
            $data['date'] = date("Y-m-d H:i:s", time());
        }else{
            $data['msg'] = '错误的请求';
        }

        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * 录播课程视频段
     * @param Request $request
     * @return Response
     */
    public function ajaxLoadChapterAction(Request $request){
        $data['code'] = 404;
        $chapterId = $request->get('chapter_id');
        $courseId = $request->get('course_id');
        if($request->getMethod() === "POST" && !empty($chapterId)){
            $session = $this->get('request')->getSession();
            //用户是否登录
            $loginInfo = $this->getUser();
            $memberId = empty($loginInfo) ? $session->get('member_id') : $loginInfo->getMemberId();
            if(empty($memberId)){
                $data['code'] = 300;
                $data['msg'] = "您还没登录，请先登录再观看课程。";
            }else{
                //获取录播课程所有分节信息
                $emt = $this->getDoctrine()->getManager();
                $courseChapter = $emt->getRepository('StoreBundle:XcVideo')
                    ->findOneBy(array('id' => $chapterId, 'status' => 4));
                if(empty($courseChapter)){
                    $data['code'] = 300;
                    $data['msg'] = "该课题不存在或未审核。";
                }else if($courseChapter->getIsFree() == 0){
                    //登录用户判断用户是否购买该课程
                    $payment = $this->get('payment_service');
                    $isPayment = $payment->existCourseInventory($memberId, $courseId, 1); //content_type 1:录播 2：直播
                    if($isPayment){
                        $data['code'] = 200;
                        $data['data'] = $courseChapter->getUrl();
                    }else{
                        $data['code'] = 300;
                        $data['msg'] = "您没有购买该课程，暂无权限观看。";
                    }
                }else{
                    $data['code'] = 200;
                    $data['data'] = $courseChapter->getUrl();
                }
            }
        }

        //更新课程播放次数，播放人数，课程点击次数
        if($data['code'] == 200){
            $courseService = $this->get('course_service');
            $courseService->setCoursePlayPersonCount($memberId, $courseId); //录播课程播放人数列表,每个人只算一次
            $courseService->setCoursePlayCount($courseId, $memberId); //单个录播课程点击数列表
            $courseService->setPlayCount($courseId); //更新录播课程播放次数
        }

        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * 获取录播课程信息处理
     * @param $param 条件参数 默认五条
     * @param int $limit 限制条数
     * @return mixed
     */
    private function _getCourseData($param, $limit = 3){
        $page = empty($param['page']) ? 1 : $param['page']; //当前页数初始化
        $offset = ($page - 1)*$limit; //计算分页偏移量
        $conn = $this->get('database_connection'); //获取数据库连接对象

        //获取课程列表信息sql语句
        $sql = "SELECT `id`,`title`,`brief`,`reserve_init`,`reserve_num`,`current_price`,
        `course_level`,`comment_star`,`create_time`,`modify_time`,`img_url`,`video_num`
        FROM `xc_course`
        WHERE `status` = 4 ";

        //搜索课程等级条件处理
        if(!empty($param['level'])){
            $sql .= " AND course_level = :course_level ";
            $where[':course_level'] = $param['level']; //录播课程等级限制条件
        }
        $sql .= " ORDER BY `modify_time` DESC LIMIT $offset, $limit "; //按修改时间倒序排列

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
                if($course['course_level'] == 1){
                    $courseData[$key]['course_level'] = '初级';
                }else if($course['course_level'] == 2){
                    $courseData[$key]['course_level'] = '中级';
                }else{
                    $courseData[$key]['course_level'] = '高级';
                }
                $courseData[$key]['img_url'] = "http://" . $_SERVER ['HTTP_HOST'].'/'.$course['img_url'];
                $courseData[$key]['modify_time'] = date('y年m月d日', strtotime($course['modify_time']));

                //录播课程播放人数
                $redisKeyList = 'course_play_person_count';
                $personPlayCount = $redis->zscore($redisKeyList, $course['id']);
                $personPlayCount = (empty($personPlayCount)) ? 0 : $personPlayCount;
                $courseData[$key]['play_count'] = $personPlayCount;
            }

            $courseData['course_count'] = count($courseData);
        }else{
            $courseData['course_count'] = 0;
        }

        return $courseData;
    }

    /**
     * 获取课程讨论信息
     * @param $contentId
     * @param $contentType
     * @param int $page
     * @param int $limit
     * @return mixed
     */
    private function _getCoursePost($contentId, $contentType, $page = 1, $limit = 5){
        $offset = ($page - 1)*$limit; //计算分页偏移量
        $conn = $this->get('database_connection'); //获取数据库连接对象

        $sql = "SELECT `id`,`content_id`,`content_type`,`member_id`,
            `member_name`,`member_logo`,`member_ip`,`create_time`,`content`,`comment_num`
            FROM `xc_post`
            WHERE `status` = 4 AND content_id = :content_id AND content_type = :content_type ";
        $sql .= " ORDER BY `create_time` DESC LIMIT $offset, $limit "; //按讨论时间倒序排列

        $where[':content_id'] = $contentId; //评论类型id限制条件
        $where[':content_type'] = $contentType; //评论类型id限制条件
        $ready = $conn->prepare($sql);
        $ready->execute($where); //搜索参数执行匹配
        $result['post_data'] = $ready->fetchAll();

        return $result;
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