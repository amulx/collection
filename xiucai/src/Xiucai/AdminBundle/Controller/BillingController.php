 <?php

namespace Xiucai\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Config\Definition\Exception\Exception;


class BillingController extends Controller
{
    public function listAction()
    {
        $contents   = isset($_REQUEST['contents'])?trim($_REQUEST['contents']):'';
        $condition  = isset($_REQUEST['condition'])?trim($_REQUEST['condition']):'';

        $conn   = $this->get('database_connection'); //获取数据库连接对象
        $sql    = "SELECT b.*, m.nickname FROM xc_billing as b left join xc_member as m on b.member_id = m.id where 1 ";
        //全局搜索条件
        //print_r($contents);
        if($condition == ''){
            $sql .= " and (b.member_id = '$contents' or m.nickname like '%".$contents."%'  or b.contact_name like '%".$contents."%' or b.contact_number like '%".$contents."%' or b.company_name like '%".$contents."%')";
        }elseif($condition == '1'){
            $sql .= " and b.member_id = $contents";
        }elseif($condition == '2'){
            $sql .= " and m.nickname like '%".$contents."%'";
        }elseif($condition == '3'){
            $sql .= " and b.contact_name like '%".$contents."%'";
        }elseif($condition == '4'){
            $sql .= " and b.contact_number like '%".$contents."%'";
        }elseif($condition == '5'){
            $sql .= " and b.company_name like '%".$contents."%'";
        }
        $sql .= " order by b.id desc";
        $query  = $conn->query($sql);
        $list = $query->fetchAll();
        $count=count($list);
        $request = Request::createFromGlobals()->query;
        $pageSize = 10;
        $params['page'] = $request->get('page');  //当前页码数
        $limit = $pageSize; //页面限制条数
        $page = empty($params['page']) ? 1 : $params['page']; //当前页数初始化
        $offset = ($page - 1)*$limit; //计算分页偏移量
        $orderList  =  array_slice($list,$offset,$pageSize);
        $pagination = $this->container->get('PagePaginationServices'); //获取分页services
        $pager = $pagination->getNavigation($count, $limit, $page, ' list', $request); //分页处理：总数，每页条数，页码，action名称，搜索参数
        $pages = ($count == 0 || $count <= $pageSize) ? '' : $pager; //每10条记录作为一页
        $parameter =array(
            'page'        => $page,
            'contents'   => $contents,
            'condition'  => $condition,
            'nums'        => $count,
        );
        return $this->render('AdminBundle:Billing:list.html.twig', array('pages' => $pages,'list' =>$list,'parameter' =>$parameter));
    }

    public function editAction()
    {
        $id = isset($_GET['id'])?trim($_GET['id']):'';
        $conn   = $this->get('database_connection');
        $sql    = "SELECT b.*, m.nickname, m.avatar FROM xc_billing as b left join xc_member as m on b.member_id = m.id where b.id=$id ";
        $query  = $conn->query($sql);
        $detail = $query->fetchAll();
        //print_r($detail);
        return $this->render('AdminBundle:Billing:edit.html.twig', array('detail' =>$detail[0]));
    }

    public function updateAction()
    {
        $request = $this->getRequest();
        $id = $request->get('id');
        $company_name = $request->get('company_name');
        $contact_name = $request->get('contact_name');
        $contact_number = $request->get('contact_number');
        $address = $request->get('address');
        $postcode = $request->get('postcode');
        $conn = $this -> get('database_connection');
        try{
            $conn->update('xc_billing',array('company_name'=>$company_name, 'contact_name'=>$contact_name,'contact_number'=>$contact_number,'address'=>$address,'postcode'=>$postcode),array('id'=>$id));
        }
        catch(Exception $e){
            return new Response($e->getMessage());;
        }
        return $this->redirect($this->generateUrl('AdminBundle_Billing_list'));
    }

    public function infoAction()
    {
        $request = $this->getRequest();
        $id = $request->get('id');

        $conn   = $this->get('database_connection');
        $sql_billing    = "SELECT b.*, m.nickname, m.avatar FROM xc_billing as b left join xc_member as m on b.member_id = m.id where b.member_id='$id'";
        $query_billing  = $conn->query($sql_billing);
        $detail = $query_billing->fetchAll();
        if(empty($detail)){
            header('content-type:text/html;charset=utf-8;');
            echo "<script>alert('无支付账户信息！');history.go(-1)</script>";
            exit;
        }
        $sql_invoice    = "SELECT * FROM xc_invoice where member_id='$id' order by id desc";
        $query_invoice  = $conn->query($sql_invoice);
        $invoiceList = $query_invoice->fetchAll();
        return $this->render('AdminBundle:Billing:info.html.twig', array('detail' =>$detail[0],'invoiceList'=>$invoiceList));
    }
}
