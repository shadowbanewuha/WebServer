<?php

function get_real_ip() {
    $ip = FALSE;
    //客户端IP 或 NONE
    if(!empty($_SERVER["HTTP_CLIENT_IP"])){
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    }
    //多重代理服务器下的客户端真实IP地址（可能伪造）,如果没有使用代理，此字段为空
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
        if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
        for ($i = 0; $i < count($ips); $i++) {
            if (!eregi ("^(10│172.16│192.168).", $ips[$i])) {
                $ip = $ips[$i];
                break;
            }
        }
    }
    //客户端IP 或 (最后一个)代理服务器 IP
    return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}

function get_city($ip = '') {
    $url = "http://ip.taobao.com/service/getIpInfo.php?ip=".$ip;
    $ip = json_decode(file_get_contents($url));
    if ((string)$ip->code == '1') {
        return false;
    }
    $data = (array)$ip->data;
    return $data;
}

function generate_order_no($time) {
    return date('ymd').substr($time,-5).substr(microtime(),2,5);
}

/**
 * php post跳转到其他php
 * 建立跳转请求表单
 * @param string $url 数据提交跳转到的URL
 * @param array $data 请求参数数组
 * @param string $method 提交方式：post或get 默认post
 * @return string 提交表单的HTML文本
 */
function build_request_form($url, $data, $method = 'post') {
    $sHtml = "<form id='requestForm' name='requestForm' action='".$url."' method='".$method."'>";
    while (list ($key, $val) = inner_each ($data)) {
        $sHtml.= "<input type='hidden' name='".$key."' value='".$val."' />";
    }
    $sHtml = $sHtml."<input type='submit' value='confirm' style='display:none;'></form>";
    $sHtml = $sHtml."<script>document.forms['requestForm'].submit();</script>";
    return $sHtml;
}


function inner_each(&$array){
    $res = array();
    $key = key($array);
    if($key !== null){
        next($array);
        $res[1] = $res['value'] = $array[$key];
        $res[0] = $res['key'] = $key;
    }else{
        $res = false;
    }
    return $res;
}



function curl_post_request($url, $paraArray) {
    $curl = curl_init();
    $timeout = 100;
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);    //对HTTPS时 不校验证书
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $paraArray);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-type: text/plain',    //传递格式
        'Content-length: 100'
    ));

    $output = curl_exec($curl);
    $error = curl_error($curl);
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if($code == '200') {
        $ret = json_decode($output, true);
    } else {
        $ret = $error;
    }
    return $ret;
}