<?php

namespace Xiucai\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TransactionController extends Controller
{
    public function listAction()
    {
        $payment_service = $this->get('payment_service');
        $list = $payment_service->getTransactionList();
        $count = count($list);
        $request = Request::createFromGlobals()->query;
        $pageSize = 10;
        $params['page'] = $request->get('page');  //当前页码数
        $limit = $pageSize; //页面限制条数
        $page = empty($params['page']) ? 1 : $params['page']; //当前页数初始化
        $offset = ($page - 1)*$limit; //计算分页偏移量
        $list =  array_slice($list,$offset,$pageSize);
        $pagination = $this->container->get('PagePaginationServices'); //获取分页services
        $pager = $pagination->getNavigation($count, $limit, $page, 'list', $request); //分页处理：总数，每页条数，页码，action名称，搜索参数
        $pages = ($count == 0 || $count <= $pageSize) ? '' : $pager; //每10条记录作为一页
        $parameter =array(
            'page'       =>  $page,
            'nums'        => $count
        );
        return $this->render('AdminBundle:Transaction:list.html.twig', array('pages' => $pages,'list' =>$list,'parameter' =>$parameter));
    }
}
