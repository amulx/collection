<?php 

function relative_to_absolute($content, $feed_url) {  
    preg_match('/(http|https|ftp):///', $feed_url, $protocol);  
    $server_url = preg_replace("/(http|https|ftp|news):///", "", $feed_url);  
    $server_url = preg_replace("//.*/", "", $server_url);  

    if ($server_url == '') {  
        return $content;  
    }  

    if (isset($protocol[0])) {  
        $new_content = preg_replace('/href="//', 'href="'.$protocol[0].$server_url.'/', $content);  
        $new_content = preg_replace('/src="//', 'src="'.$protocol[0].$server_url.'/', $new_content);  //开源代码OSPHP.COM.Cn 
    } else {  
        $new_content = $content;  
    }  
    return $new_content;  
}

?>