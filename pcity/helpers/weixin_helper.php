<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

function wx_curl_https_post($url, $data=array(), $header=array(), $timeout=30){

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);  // 从证书中检查SSL加密算法是否存在
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

    $response = curl_exec($ch);

    if($error=curl_error($ch)){
        log_message('error','wx_curl_https_post:'.$url.' --- '.$error);
        return NULL;
    }
    
    curl_close($ch);
    return $response;
}

function wx_curl_http_get_media($url = '', $timeout = 30)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:15.0)");

    curl_setopt($ch, CURLOPT_TIMEOUT, intval($timeout));
    
    $tmpInfo = curl_exec($ch);

    if($error=curl_error($ch)){
        log_message('error','wx_curl_http_get_media:'.$url.' --- '.$error);
        return NULL;
    }

    list($tmpheader, $body) = explode("\r\n\r\n", $tmpInfo, 2);
    $header_array = explode("\n", $tmpheader);
    foreach($header_array as $header_value) {
        $header_pieces = explode(':', $header_value);
        if(count($header_pieces) == 2) {
            $headers[$header_pieces[0]] = trim($header_pieces[1]);
        }
    }
   
    curl_close($ch);

    $ret = array();
    $ret['header'] =  $headers;
    $ret['body'] = empty($body) ? null : $body;
    return $ret;
}