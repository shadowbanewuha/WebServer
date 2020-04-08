<?php

require_once './sdk/service/AlipayTradeService.php';
require_once './sdk/model/AlipayTradePrecreateContentBuilder.php';

do {
    if (!isset($_POST['out_trade_no'])) break;
    if (!isset($_POST['total_amount'])) break;
    if (!isset($_POST['usr'])) break;
    if (!isset($_POST['level'])) break;
    if (empty($_POST['out_trade_no']) || trim($_POST['out_trade_no']) === "") break;
    if (empty($_POST['total_amount']) || trim($_POST['total_amount']) === "") break;
    if (empty($_POST['usr']) || trim($_POST['usr']) === "") break;

    // 创建请求builder，设置请求参数
    $qrPayRequestBuilder = new AlipayTradePrecreateContentBuilder();
    $qrPayRequestBuilder->setOutTradeNo($_POST['out_trade_no']);//订单号
    $qrPayRequestBuilder->setTotalAmount($_POST['total_amount']); //总金额
    $qrPayRequestBuilder->setTimeExpress('5m'); //超时时间
    if (isset($_POST['level'])) {
        $qrPayRequestBuilder->setSubject('迅聊平台扫码充值会员，时长'.$_POST['level'].'天'); // 订单主题
    } else {
        $qrPayRequestBuilder->setSubject('迅聊平台扫码充值会员'); // 订单主题
    }

    // 调用qrPay方法获取当面付应答
    $qrPay = new AlipayTradeService($alt_alipay_config);
    $qrPayResult = $qrPay->qrPay($qrPayRequestBuilder);

    //	根据状态值进行业务处理
    switch ($qrPayResult->getTradeStatus()) {
        case "SUCCESS":
            // 如果用户已经生成了二维码订单，但是没有付款，先在数据库中清理之前未付款的订单
            require_once './../../if/DBO.php';
            $usr = $_POST['usr'];
            $sqldelete = "DELETE FROM orderlist WHERE username='$usr' and state!=0";
            if (mysqli_query($conn, $sqldelete)) {
                $response = $qrPayResult->getResponse();
                $orderno = $_POST['out_trade_no'];
                $amount = $_POST['total_amount'];
                $level = $_POST['level'];
                $curdate = date("Y-m-d H:i:s", time());
                // 将订单与用户绑定，插入到订单数据库
                $sqlinsert = "insert into orderlist (orderno, amount, days, time, username) values ('$orderno', '$amount', '$level', '$curdate', '$usr')";
                if (mysqli_query($conn, $sqlinsert)) {
                    $qrcode = urlencode($response->qr_code);
                    // 输出二维码
                    echo  '
                        <!DOCTYPE html>
                        <html lang="en">
                        <head>
                            <meta charset="UTF-8">
                            <link rel="stylesheet" type="text/css" href="../../css/base.css"/>
                            <title>会员充值</title>
                        </head>
                        <body>
                            <div id="login">
                                <p style="width: 240px; margin-left: 100px">
                                    <span class="spcontent" style="margin-left: 12px;">使用手机支付宝扫码支付</span><br>
                                    <span class="spcontent" style="margin-left: 12px;">时长 ：'.$level.'天 </span><br>
                                    <span class="spcontent" style="margin-left: 12px;">金额 ：'.$amount.'元 </span>
                                    <span class="spcontent" style="margin-left: 12px;margin-bottom: 20px">支付成功后点击<a style="color: yellow; margin-left: 2px; margin-right: 2px" href="./../../authuser.php">这里</a>返回</span><br>
                                    <img src="http://qr.liantu.com/api.php?text='. $qrcode .'" style=width:200px;/>
                                </p>
                            </div>
                        </body>
                        </html>
                    ';
                } else {
                    echo '服务器繁忙，请稍后再试';
                }
            } else {
                echo '服务器繁忙，请稍后再试';
            }
            $conn->close();
            break;
        case "FAILED":
            echo "支付宝创建订单二维码失败!!!"."<br>--------------------------<br>";
            if(!empty($qrPayResult->getResponse())){
                print_r($qrPayResult->getResponse());
            }
            break;
        case "UNKNOWN":
            echo "系统异常，状态未知!!!"."<br>--------------------------<br>";
            if(!empty($qrPayResult->getResponse())){
                print_r($qrPayResult->getResponse());
            }
            break;
        default:
            echo "不支持的返回状态，创建订单二维码返回异常!!!";
            break;
    }

    return ;
} while(0);

echo '参数错误，将自动跳转到登录页面';
header("refresh:2; url=../../authuser.php");