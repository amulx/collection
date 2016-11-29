<?php

namespace Xiucai\ServiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Xiucai\StoreBundle\Entity\XcComment;

class CommentController extends Controller
{
    /**
     * 评论功能模块-评论列表
     * @param Request $request  | 评论条件参数
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $param['c_id'] = $request->get('c_id'); //评论类型id
        $param['u_id'] = $request->get('u_id'); //评论人的user_id
        $param['c_type'] = $request->get('c_type'); //评论类型
        $param['u_name'] = $request->get('u_name'); //评论用户的名称
        $param['u_logo'] = $request->get('u_logo'); //评论用户的头像
        $limit = $request->get('limit'); //每页限制条数
        $param['limit'] = empty($limit) ? 5 : $limit;

        if(empty($param['c_id']) || empty($param['c_type'])){
            echo "<script>alert('参数缺省错误。')</script>";
            exit;
        }

        $commentData = $this->_getCommentData($param, $limit);
        $role = $commentData['role']; //评论者角色
        $commentInfo = $commentData['data']; //评论信息
        $commentCount = $commentData['count']; //评论数

        return $this->render('ServiceBundle:comment:index.html.twig',
            array('commentData' => $commentInfo, 'params' => $param, 'role' => $role, 'commentCount' => $commentCount ));
    }

    /**
     * ajax发表评论内容
     * @param Request $request
     * @return Response
     */
    public function ajaxSendCommentAction(Request $request){
        $params = $request->get('params'); //获取基本参数
        if(empty($params['u_id']) || $params['u_id'] == 0){
            $result['code'] = 404;
            $result['msg'] = '您还没有登录，请先登录。';
        }else{
            //发布评论信息操作
            if($request->getMethod() === "POST"){
                $comment = new XcComment();
                $emt = $this->getDoctrine()->getManager();
                if(!empty($params['comment_id'])){
                    $comment->setParent($params['comment_id']); //回复评论

                    //评论头衔
                    $parentInfo = $emt->getRepository('StoreBundle:XcComment')
                        ->findOneBy(array('id' => $params['comment_id'])); //有父级评论回复
                    if(!empty($parentInfo)){
                        $commentResult['comment_title'] = "<code>".$params['u_name']."</code> 回复 ".$parentInfo->getMemberName();
                    }else{
                        $commentResult['comment_title'] = "<code>".$params['u_name']."</code>";
                    }
                }else{
                    $commentResult['comment_title'] = "<code>".$params['u_name']."</code>";
                }

                $comment->setStatus(4); //评论有效状态
                $comment->setMemberId($params['u_id']); //评论用户ID
                $comment->setContentId($params['c_id']); //评论类型ID
                $comment->setMemberIp($_SERVER["REMOTE_ADDR"]); //评论用户IP
                $comment->setMemberLogo($params['u_logo']); //评论用户头像
                $comment->setMemberName($params['u_name']); //评论用户名字
                $comment->setContentType($params['c_type']); //评论类型
                $comment->setContent($request->get('comment_content')); //评论内容
                $comment->setCreateTime(new \DateTime(date("Y-m-d H:i:s"))); //评论发表时间
                $comment->setModifyTime(new \DateTime(date("Y-m-d H:i:s"))); //评论修改时间

                $emt->persist($comment); //添加评论

                //更新讨论的评论数量
                $postInfo = $emt->getRepository('StoreBundle:XcPost')
                    ->findOneBy(array('id' => $params['c_id'])); //获取讨论信息
                $commentNum = $postInfo->getCommentNum() + 1;
                $postInfo->setCommentNum($commentNum);
                $emt->persist($postInfo); //更新评论数量
                $emt->flush();

                //评论成功返回相应信息
                $commentResult['id'] = $comment->getId();
                $commentResult['comment_count'] = $commentNum;
                $commentResult['member_id'] = $comment->getMemberId();
                $commentResult['member_logo'] = $comment->getMemberLogo();
                $commentResult['member_name'] = $comment->getMemberName();
                $commentResult['create_time'] = date("Y-m-d H:i:s");
                $result['data'] = $commentResult;
                $result['code'] = 200;
            }
        }

        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * 评论用户编辑评论信息
     * @param Request $request
     * @return Response
     */
    public function ajaxEditCommentAction(Request $request){
        $params = $request->get('params'); //获取基本参数
        if(empty($params['u_id'])){
            $result['code'] = 404;
            $result['msg'] = '您还没有登录，请先登录。';
        }else{
            //编辑评论信息操作
            if($request->getMethod() === "POST"){
                if(!empty($params['comment_id']) && $request->get('type') == "editor"){
                    $emt = $this->getDoctrine()->getManager();
                    $commentInfo = $emt->getRepository('StoreBundle:XcComment')
                        ->findOneBy(array('id' => $params['comment_id'])); //获取编辑评论信息
                    if(empty($commentInfo)){
                        $result['code'] = 404;
                        $result['msg'] = '您编辑的该评论不存在。';
                    }else{
                        if($commentInfo->getAuthorId() == $params['u_id']){
                            $commentInfo->setContent($request->get('comment_content')); //评论内容
                            $commentInfo->setModifyTime(new \DateTime(date("Y-m-d H:i:s"))); //评论修改时间
                            $emt->persist($commentInfo);
                            $emt->flush(); //更新评论

                            $result['data'] = 'editor';
                            $result['code'] = 200;
                        }else{
                            $result['code'] = 404;
                            $result['msg'] = '您没有权限编辑该评论。';
                        }
                    }
                }else{
                    $result['code'] = 404;
                    $result['msg'] = '编辑的评论失败。';
                }
            }
        }

        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * 删除评论信息
     * @param Request $request
     * @return Response
     */
    public function ajaxDeleteCommentAction(Request $request){
        $params = $request->get('params'); //获取基本参数
        $postId = $request->get('post_id'); //获取该评论的讨论ID
        if(empty($params['u_id'])){
            $result['code'] = 404;
            $result['msg'] = '您还没有登录，请先登录。';
        }else{
            //编辑评论信息操作
            if($request->getMethod() === "POST"){
                if(!empty($params['comment_id'])){
                    $emt = $this->getDoctrine()->getManager();
                    $commentInfo = $emt->getRepository('StoreBundle:XcComment')
                        ->findOneBy(array('id' => $params['comment_id'])); //获取编辑评论信息
                    if(empty($commentInfo)){
                        $result['code'] = 404;
                        $result['msg'] = '该评论不存在或已被删除。';
                    }else{
                        //$role = $this->_getRole($params['u_id']); //获取用户角色
                        if($commentInfo->getMemberId() == $params['u_id']){
                            $emt->remove($commentInfo);
                            $emt->flush(); //删除该评论

                            //更新评论数量
                            $commentNum = 0;
                            $postInfo = $emt->getRepository('StoreBundle:XcPost')
                                ->findOneBy(array('id' => $postId));
                            if(!empty($postInfo)){
                                if($postInfo->getCommentNum() > 0){
                                    $commentNum = $postInfo->getCommentNum() - 1;
                                    $postInfo->setCommentNum($commentNum);
                                    $emt->persist($postInfo); //更新评论数量
                                    $emt->flush();
                                }
                            }

                            $result['code'] = 200;
                            $result['comment_num'] = $commentNum;
                        }else{
                            $result['code'] = 404;
                            $result['msg'] = '您没有权限删除该评论。';
                        }
                    }
                }else{
                    $result['code'] = 404;
                    $result['msg'] = '编辑的评论失败。';
                }
            }
        }

        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * 加载更多评论
     * @param Request $request
     * @return Response
     */
    public function ajaxLoadCommentAction(Request $request){
        $param = $request->get('params'); //获取基本参数

        if(empty($param['c_id']) || empty($param['c_type'])){
            $result['code'] = 300;
        }else{
            $commentData = $this->_getCommentData($param, $param['limit']);
            $role = $commentData['role'];
            $totalCount = $commentData['total_count'];
            $commentCount = $commentData['comment_count']; //评论数统计
            $commentData = $commentData['data'];
            if(!empty($commentData)){
                $result['code'] = 200;
                $result['role'] = $role;
                $result['data'] = $commentData;
                $result['total_count'] = $totalCount;
                $result['comment_count'] = $commentCount; //评论数统计
            }else{
                $result['code'] = 404;
            }
        }

        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * 评论信息详情处理
     * @param $param 条件参数
     * @param $limit 限制条数
     * @return mixed
     */
    private function _getCommentData($param, $limit){
        $role = 'common';
        $emt = $this->getDoctrine()->getManager();
        if(empty($param['u_id'])){
            $param['u_id'] = 0; //评论人的user_id
            $param['u_name'] = '游客'; //默认评论用户的名称
            $param['u_logo'] = '/asset/images/male.gif'; //默认评论用户的头像
        }else{
            $role = $this->_getRole($param['u_id']); //获取用户角色
        }

        $param['limit'] = $limit = empty($limit) ? 5 : $limit; //评论限制条数
        $page = empty($param['page']) ? 1 : $param['page']; //当前页数初始化
        $offset = ($page - 1)*$param['limit']; //计算分页偏移量
        $conn = $this->get('database_connection'); //获取数据库连接对象

        //根据type 分清是哪一个表
        //$contentType = $emt->getRepository('StoreBundle:XcContentType')->findOneBy(array('id' => $param['c_type']));

        //获取课程列表信息sql语句
        $sql = "SELECT SQL_CALC_FOUND_ROWS xc_comment.`id`,`content_id`,`content_type`,`parent`,`member_id`,
        `member_name`,`member_logo`,`member_ip`,`create_time`,`content`
        FROM `xc_comment`
        WHERE `status` = 4 AND content_id = :content_id AND content_type = :content_type ";

        $sql .= " ORDER BY `create_time` DESC LIMIT $offset, $limit "; //按评论时间倒序排列

        $where[':content_id'] = $param['c_id']; //评论类型id限制条件
        $where[':content_type'] = $param['c_type']; //评论类型type限制条件

        $ready = $conn->prepare($sql);
        $ready->execute($where); //搜索参数执行匹配
        $commentData = $ready->fetchAll();
        $commentCount = $conn->fetchAll("SELECT FOUND_ROWS() AS total"); //获取评论数
        if(!empty($commentData)){
            foreach($commentData as $key => $comment){
                if(!empty($comment['parent'])){
                    $parentInfo = $emt->getRepository('StoreBundle:XcComment')
                        ->findOneBy(array('id' => $comment['parent'])); //有父级评论回复
                    if(!empty($parentInfo)){
                        $commentData[$key]['comment_title'] = "<code>".$comment['member_name']."</code> 回复 ".$parentInfo->getMemberName();
                    }else{
                        $commentData[$key]['comment_title'] = "<code>".$comment['member_name']."</code>";
                    }
                }else{
                    $commentData[$key]['comment_title'] = "<code>".$comment['member_name']."</code>";
                }
            }
        }

        $result['role'] = $role; //评论人角色判断
        $result['data'] = $commentData; //评论信息
        $result['comment_count'] = count($commentData); //评论信息
        $result['total_count'] = empty($commentCount[0]['total']) ? 0 : $commentCount[0]['total']; //评论数统计

        return $result;
    }

    /**
     * 判断用户是否是超级管理用户
     * @param $uId  用户ID
     * @return string
     */
    private function _getRole($uId){
        $emt = $this->getDoctrine()->getManager();

        $session = $this->get('request')->getSession();
        $loginInfo = $this->getUser();
        $memberId = empty($loginInfo) ? $session->get('member_id') : $loginInfo->getMemberId();
        if($memberId == $uId){
            $memberData = $emt->getRepository('StoreBundle:XcMember')
                ->findOneBy(array('id' => $memberId)); //获取用户信息
            if(!empty($memberData)){
                $role = 'super';
                //TODO:
                /*if($memberData->getRole() == 3){
                    $role = 'super';
                }*/
            }
        }
        $role = (empty($role)) ? 'common' : $role;

        return $role;
    }
}