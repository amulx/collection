<?php

namespace Xiucai\ServiceBundle\Utilities;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Bundle\DoctrineBundle;
use Symfony\Component\HttpFoundation\Request;
use Predis;
use Symfony\Component\Config\Definition\Exception\Exception;
use \DateTime;


class CourseService {
    protected $redis;
    protected $doctrine;
    protected $memberId	= -1;	//用户id，未登录时为-1

    public function __construct($doctrine, $redis, $session){
        $this->redis = $redis;
        $this->doctrine = $doctrine;
        $memberId = $session->get('member_id');
        if(!empty($memberId)){
            $this->memberId = $memberId;
        }
    }

    /**
     * 获取用户直播列表
     * @param $memberId
     * @return int
     */
    public function getMemberLive($memberId){
        if($this->memberId == -1){
            return $this->memberId;
        }else{
            $redisKeyList = "user_live_course_list:".$memberId;
            $list = $this->redis->zrange($redisKeyList, 0, -1, array('withscores'=>true));

            return $list;
        }
    }

    /**
     * 更新用户直播列表
     * @param $scheduleTime 直播开始时间,时间戳
     * @param $liveId 直播课程Id
     * @param $memberId
     */
    public function setMemberLive($scheduleTime, $liveId, $memberId){
        $redisKeyList = "user_live_course_list:".$memberId;
        $this->redis->zadd($redisKeyList,$scheduleTime,$liveId);
    }

    /**
     * 获取用户录播课程列表
     * @param $memberId
     * @return int
     */
    public function getMemberCourse($memberId){
        if($this->memberId == -1){
            return $this->memberId;
        }else{
            $redisKeyList = "user_course_list:".$memberId;
            $list = $this->redis->zrange($redisKeyList, 0, -1, array('withscores'=>true));

            return $list;
        }
    }

    /**
     * 更新用户录播课程列表
     * @param $createTime 录播课程的创建时间,时间戳
     * @param $courseId 录播课程的ID
     * @param $memberId
     */
    public function setMemberCourse($createTime, $memberId, $courseId){
        $redisKeyList = "user_course_list:".$memberId;
        $this->redis->zadd($redisKeyList, $createTime, $courseId);
    }

    /**
     * 统计每个直播课程实际参与的用户列表
     * @param $courseId 直播课程ID
     * @param $user 实际参与人数的用户组
     * @return bool
     * @throws \Symfony\Component\Config\Definition\Exception\Exception
     */
    public function setLiveCourseAttend($courseId, $user){
        $redisKey = "live_course_attend:".$courseId;
        $this->redis->sadd($redisKey, $user);
    }

    /**
     * 统计每个直播课程预约的用户列表
     * @param $courseId 课程ID
     * @param $user 用户对象 {用户组对象} = {uid,name,avatar_url}
     */
    public function setLiveCourseReserve($courseId, $user){
        $redisKey = "live_course_reserve:".$courseId;
        $this->redis->sadd($redisKey, $user);
    }

    /**
     * 为单个录播课程的点击数（包括每个成员对这个课程的点击数）做统计和排序
     * @param $courseId
     * @param $memberId
     */
    public function setCoursePlayCount($courseId, $memberId){
        $redisKeyList = "course_play_count:".$courseId;

        $playCount = $this->redis->zscore($redisKeyList, $memberId);
        if(empty($playCount))
            $playCount = 1;//不存在key
        else{
            $playCount++;
        }

        $this->redis->zadd($redisKeyList, $playCount, $memberId);
    }

    /**
     * 为录播课程的播放次数做统计和排序
     * @param $courseId
     */
    public function setPlayCount($courseId){
        $redisKeyList = "course_play_count";

        $playCount = $this->redis->zscore($redisKeyList, $courseId);
        if(empty($playCount))
            $playCount = 1;//不存在key
        else{
            $playCount++;
        }

        $this->redis->zadd($redisKeyList, $playCount, $courseId);
    }

    /**
     * 为直播课程的预约人数（每个人只算一次）做统计和排序
     * @param $courseId
     */
    public function setLiveCourseReserveCount($courseId){
        $redisKeyList = "live_course_reserve_count";

        $personCount = $this->redis->zscore($redisKeyList, $courseId);
        if(empty($personCount))
            $personCount = 1;//不存在key
        else{
            $personCount++;
        }

        $this->redis->zadd($redisKeyList, $personCount, $courseId);
    }

    /**
     * 为录播课程的购买人数（每个人只算一次）做统计和排序
     * @param $courseId
     */
    public function setCourseReserveCount($courseId){
        $redisKeyList = "course_reserve_count";

        $personCount = $this->redis->zscore($redisKeyList, $courseId);
        if(empty($personCount))
            $personCount = 1;//不存在key
        else{
            $personCount++;
        }

        $this->redis->zadd($redisKeyList, $personCount, $courseId);
    }


    /**
     * 为单个录播课程的点击数（包括每个成员对这个课程的点击数）做统计和排序
     * 如果存在单个录播课程点击纪录则更新录播课程的播放人数（即每个人只算一次）做统计和排序
     * @param $memberId
     * @param $courseId
     */
    public function setCoursePlayPersonCount($memberId, $courseId){
        $redisKeyList = "course_play_count:".$courseId;

        $playCount = $this->redis->zscore($redisKeyList, $memberId);
        if(empty($playCount)){
            $redisKeyList = "course_play_person_count";

            $playPersonCount = $this->redis->zscore($redisKeyList, $courseId);
            if(empty($playPersonCount))
                $playPersonCount = 1;
            else
                $playPersonCount++;

            $this->redis->zadd($redisKeyList, $playPersonCount, $courseId);
        }
    }
} 