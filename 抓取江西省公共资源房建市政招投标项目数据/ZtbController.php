<?php

namespace App\Http\Controllers\Am;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ZtbController extends Controller
{
    function index(){
    	$url = 'http://www.yijiahx.com/common/ListHandler.ashx';
    	$postData['mod'] = 'tenderopen';
    	$postData['p'] = 0;
    	$postData['m'] = 1;	//0==》当前这个星期   1==》 代指下一个星期
    	$postData['city'] = 4;
    	$postData['t'] = date("Y/m/d H:i:s");
    	$postData['q'] = '';



        echo '<!DOCTYPE html><html><head><meta http-equiv="content-type" content="text/html;charset=utf-8"><title>本周招标情况</title><style type="text/css">body{ font-size:12px;}.w_time{width:64px}</style></head><body>';

		echo '<table border="1" cellspacing="0" cellpadding="0">';
		
        for ($j=0; $j < 3; $j++) {
            $postData['m'] = $j+1; //0==》当前这个星期   1==》 代指下一个星期 
            $k = $postData['m'] + 1;
            echo "<td colspan='5' align='center' bgcolor='#FF0000'>二月份第{$k}周</td>";
        // ===================================================================
            for ($i=0; $i < 30; $i++) { 

                $postData['p'] = $i;//页数

                $result = json_decode($this->postbbb($url,$postData),true);

                if (empty($result['datas'])) {
                    break;
                }       
            // ==================================
                foreach ($result['datas'] as $key => $value) {
                    // echo '<pre>';
                    // var_dump($value['tender']['tendertype']['id']);exit();
                    // echo $value['tender'];
                    if (empty($value['note']) || $value['tender']['tendertype']['id'] !== 1) {
                        continue;
                    }
                    echo '<tr>';
                        echo '<td bgcolor="#7AFEC6">'.$value['items'].'</td>';
                        echo '<td>'.$value['note'].'</td>';
                        echo '<td bgcolor="#7AFEC6">'.$value['req'].'</td>';
                        echo '<td class="w_time">'.$value['opendate'].'</td>';
                        echo '<td bgcolor="#7AFEC6">'.$value['btime'].'</td>';
                    echo '</tr>';
                }
            // ======================================
            }

        // =============================================================

        }

    	echo '</table>';
    	echo '</body></html>';

    }


    public function httpGet($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
        // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        $res = json_decode(curl_exec($curl),true);
        curl_close($curl);
        return $res;
    }
    /**
     * curl 模拟post请求  edit@by cg
     *
     * @param      <type>  $url    需要访问的url
     * @param      <type>  $param  参数
     */
     public function postbbb($url,$param)
     {  
        // $header[]="bbb:ddd";//自增请求头信息    没有实际意义  可删除
            $ch = curl_init();  
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  //使用自动跳转
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  //获取的信息已文件流的形式返回
            curl_setopt($ch, CURLOPT_TIMEOUT, 5000);  //设置超时时间  防止死循环
            curl_setopt($ch, CURLOPT_POST, 1 );   //发送一个常规的post请求
            curl_setopt($ch, CURLOPT_POSTFIELDS, $param); //post提交的数据包   
            // curl_setopt($ch, CURLOPT_HTTPHEADER, $header);  
            curl_setopt($ch, CURLOPT_URL,$url );         //要访问的地址
            $res = curl_exec($ch);  //执行操作
            if (curl_errno($ch)) {
                echo 'Errno '.curl_error($ch);
            }
            curl_close($ch);
            return $res;
     }
}
