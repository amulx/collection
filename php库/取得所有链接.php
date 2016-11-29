<?php 

function get_all_url($code){  
        preg_match_all('/<as+href=["|']?([^>"' ]+)["|']?s*[^>]*>([^>]+)</a>/i',$code,$arr);  
        return array('name'=>$arr[2],'url'=>$arr[1]);  
} 


?>