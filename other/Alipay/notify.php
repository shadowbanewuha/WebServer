<?php

require_once dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'config.php';

if (verifyNotifyResult($_POST, $alt_alipay_config['alipay_public_key'])) { // 5
    $orderNo = $_POST['out_trade_no'];
    $amount = $_POST['total_amount'];
    $sellerEmail = $_POST['seller_email'];
    $appID = $_POST['app_id'];

    require_once '../../if/DBO.php';
    $sqlsearch = "select * from orderlist where orderno='$orderNo'";
    $result = mysqli_query($conn, $sqlsearch);
    if ($result->num_rows > 0) { // A
        $arr = array();
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $arr[] = $row;
        }
        $row = $arr[0];
        $db_amount = $row['amount'];
        $db_state = $row['state'];
        $db_usr = $row['usr'];
        $local_sellerEmail = $alt_alipay_config['seller_email']; //从配置读取 seller_email
        $local_appID = $alt_alipay_config['app_id']; //从配置读取 app_id

        if ($db_amount === $amount // B
            && $sellerEmail === $local_sellerEmail // C
            && $local_appID === $appID) {  // D

            //付款成功
            echo success;
            if ($db_state == 0) {
                // 已经处理了延长会员
                return;
            }

            $sqlupdate = "update orderlist set state=0 where orderno='$orderNo'";
            mysqli_query($conn, $sqlupdate); // 将state置为0, 表示数据库已经处理

            $db_level = $row['days']; // 获取订单号对应的天数
            db_addcredit($conn, $db_usr, $db_level);
        }
        $conn->close();
    } else {
        echo failed;
    }
} else {
    echo failed;
}

function verifyNotifyResult($para_temp, $public_key) {
    if (empty($para_temp)) return false;

    //除去待签名参数数组中的空值和签名参数
    $para_filter = paraFilter($para_temp); // 1

    //对待签名参数数组排序
    $para_sort = argSort($para_filter); // 2

    //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
    $prestr = createLinkstringUrlencode($para_sort); // 3

    return veifySign($prestr, $para_temp['sign'], $public_key, $para_temp['sign_type']); //4
}

/**
 * 除去数组中的空值和签名参数
 * @param $para 签名参数组
 * return 去掉空值与签名参数后的新签名参数组
 */
function paraFilter($para) {
    $para_filter = array();
    while (list ($key, $val) = each ($para)) {
        if ($key == "sign" || $key == "sign_type" || $val == "") continue;
        else $para_filter[$key] = $para[$key];
    }
    return $para_filter;
}

/**
 * 对数组排序
 * @param $para 排序前的数组
 * return 排序后的数组
 */
function argSort($para) {
    ksort($para);
    reset($para);
    return $para;
}

/**
 * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
 * @param $para 需要拼接的数组
 * return 拼接完成以后的字符串
 */
function createLinkstring($para) {
    $arg  = "";
    while (list ($key, $val) = each ($para)) {
        $arg.=$key."=".$val."&";
    }
    //去掉最后一个&字符
    $arg = substr($arg,0,count($arg)-2);

    //如果存在转义字符，那么去掉转义
    if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}

    return $arg;
}

/**
 * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串，并对字符串做urlencode编码
 * @param $para 需要拼接的数组
 * return 拼接完成以后的字符串
 */
function createLinkstringUrlencode($para) {
    $arg  = "";
    while (list ($key, $val) = each ($para)) {
        $arg.=$key."=".urlencode($val)."&";
    }
    //去掉最后一个&字符
    $arg = substr($arg,0,count($arg)-2);

    //如果存在转义字符，那么去掉转义
    if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}

    return $arg;
}

/**
 *  验证签名
 */
function veifySign($data, $sign, $publicSignKey, $signType = "RSA2") {
    $sign = base64_decode($sign);
    $res = "-----BEGIN RSA PUBLIC KEY-----\n" .
        wordwrap($publicSignKey, 64, "\n", true) .
        "\n-----END RSA PUBLIC KEY-----";
    ($res) or die("public sign key error");
    return openssl_verify($data, $sign, $publicSignKey, OPENSSL_ALGO_SHA256);
}