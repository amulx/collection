<?php

namespace Xiucai\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Config\Definition\Exception\Exception;
use Xiucai\StoreBundle\Entity\XcInvoice;
use \DateTime;


class InvoiceController extends Controller
{
    public function changeStatusAction()
    {
        $request = $this->getRequest();
        $id = $request->get('id');
        $status = $request->get('status');
        $conn   = $this->get('database_connection');
        $sql   = "SELECT member_id, amount, express_name, express_no, status, send_time FROM xc_invoice where id=$id";
        $query  = $conn->query($sql);
        $detail = $query->fetchAll();
        $handle[0] = array(1,2);//审核中
        $handle[1] = array(3);//已开发票
        $handle[2] = array();//拒绝
        $handle[3] = array();//已发送

        if(!in_array($status,$handle[$detail[0]['status']])){
            $result['code'] = 0;
            $result['msg'] = '，不能修改此状态！';
            $response =  new Response(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        if($status == 3){
            if(empty($detail[0]['express_name']) || empty($detail[0]['express_no']) || empty($detail[0]['send_time'])){
                $result['code'] = 0;
                $result['msg'] = '，请先完善快递信息！';
                $response =  new Response(json_encode($result));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }
        }

        try{
            $em = $this->getDoctrine()->getManager();
            $item = $em->getRepository('StoreBundle:XcInvoice')->findOneBy(array('id'=>$id));
            $item->setStatus($status);
            $em->flush();
            if($status == 1){
                $conn->query("update xc_billing set total_invoice=total_invoice+".$detail[0]['amount']." where member_id=".$detail[0]['member_id']."");
            }
            $result['code'] = 1;
            $result['msg'] = '修改状态成功！';
        }catch(Exception $e){
            $result['msg'] = "Failed: ".$e->getMessage();
            $result['code'] = 0;
        }
        $response =  new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function addAction()
    {
        $billing_id = isset($_GET['billing_id'])?trim($_GET['billing_id']):'';
        $conn   = $this->get('database_connection'); //获取数据库连接对象
        $sql    = "SELECT * FROM xc_billing where id=$billing_id";
        $query  = $conn->query($sql);
        $detail = $query->fetchAll();
        $sql_invoice    = "SELECT * FROM xc_invoice where status!=2 and member_id=".$detail[0]['member_id']."";
        $query_query  = $conn->query($sql_invoice);
        $invoice_list = $query_query->fetchAll();
        $total_invoice = 0;
        foreach($invoice_list as $value)
        {
            $total_invoice += $value['amount'];
        }
        //print_r($total_invoice);
        return $this->render('AdminBundle:Invoice:add.html.twig',array('detail'=>$detail[0],'total_invoice'=>$total_invoice));
    }

    public function insertAction()
    {
        $member_id = isset($_POST['member_id'])?trim($_POST['member_id']):'';
        $amount = isset($_POST['amount'])?trim($_POST['amount']):0;
        $title = isset($_POST['title'])?trim($_POST['title']):'';
        $type = isset($_POST['type'])?trim($_POST['type']):'';
        $contact_name = isset($_POST['contact_name'])?trim($_POST['contact_name']):'';
        $contact_number = isset($_POST['contact_number'])?trim($_POST['contact_number']):'';
        $address = isset($_POST['address'])?trim($_POST['address']):'';
        $postcode = isset($_POST['postcode'])?trim($_POST['postcode']):'';
        $conn = $this -> get('database_connection');
        try{
             if($conn->insert("xc_invoice",array('member_id'=>$member_id,'create_time'=>date('Y-m-d H:i:s',time()),'amount'=>$amount,'title'=>$title,'address'=>$address,'postcode'=>$postcode,'telephone'=>$contact_number,'recipient'=>$contact_name,'type'=>$type,'status'=>0)))
             {
                 //$conn->query("update xc_billing set total_invoice=total_invoice+$amount, address='$address', postcode='$postcode', contact_number='$contact_number', contact_name='$contact_name', company_name='$title' where member_id=$member_id");
                 $conn->query("update xc_billing set address='$address', postcode='$postcode', contact_number='$contact_number', contact_name='$contact_name', company_name='$title' where member_id=$member_id");
             }
        }
        catch(Exception $e){
            return new Response($e->getMessage());;
        }
        return $this->redirect($this->generateUrl('AdminBundle_Billing_info',array('id'=>$member_id)));
    }

    public function updatePageAction()
    {
        $id = isset($_GET['id'])?trim($_GET['id']):'';
        $conn = $this->get('database_connection');
        $sql = "SELECT * FROM xc_invoice where id='$id'";
        $query  = $conn->query($sql);
        $detail = $query->fetchAll();
        return $this->render('AdminBundle:Invoice:updatePage.html.twig',array('detail'=>$detail[0]));
    }

    public function updateAction()
    {
        $id = isset($_POST['id'])?trim($_POST['id']):'';
        $member_id = isset($_POST['member_id'])?trim($_POST['member_id']):'';
        $title = isset($_POST['title'])?trim($_POST['title']):'';
        $type = isset($_POST['type'])?trim($_POST['type']):'';
        $contact_name = isset($_POST['contact_name'])?trim($_POST['contact_name']):'';
        $contact_number = isset($_POST['contact_number'])?trim($_POST['contact_number']):'';
        $address = isset($_POST['address'])?trim($_POST['address']):'';
        $postcode = isset($_POST['postcode'])?trim($_POST['postcode']):'';
        $express_name = isset($_POST['express_name'])?trim($_POST['express_name']):'';
        $express_no = isset($_POST['express_no'])?trim($_POST['express_no']):'';
        $send_time = isset($_POST['send_time'])?trim($_POST['send_time']):'';
        $conn = $this -> get('database_connection');
        $data = array(
            'title'=>$title,
            'address'=>$address,
            'postcode'=>$postcode,
            'telephone'=>$contact_number,
            'recipient'=>$contact_name,
            'type'=>$type,
            'express_name'=>$express_name,
            'express_no'=>$express_no
        );
        if(!empty($send_time)){
            $data += array('send_time'=>$send_time);
        }
        try{
            $conn->update("xc_invoice", $data, array('id'=>$id));
        }
        catch(Exception $e){
            return new Response($e->getMessage());
        }
        return $this->redirect($this->generateUrl('AdminBundle_Billing_info',array('id'=>$member_id)));
    }
}