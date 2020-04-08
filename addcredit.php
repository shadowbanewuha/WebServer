<?php

require_once "./if/errcode.php";
$code = $error_invalid_param;

if (isset($_GET['level'])) {
    session_start();
    if (isset($_SESSION["admin"]) && $_SESSION["admin"] === true) {
        $level = intval($_GET['level']); // 防止sql注入
        require_once "./if/config.php";
        $amount = $config_amounts[$level];
        if (!empty($amount)) {
            $usr = $_SESSION["user"];
            require_once "./if/DBO.php";
            $sqlsearch = "select * from userinfo where username='$usr'";
            $result = mysqli_query($conn, $sqlsearch);
            if ($result->num_rows > 0) {
                $code = $error_none;
                $arr = array();
                while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                    $arr[] = $row;
                }
                $row = $arr[0];
                $conn->close();
                //提交到支付宝
                require_once './if/function.php';
                $curtimestamp = time();
                $orderno = generate_order_no($curtimestamp);
                $url = './other/Alipay/index.php';
                $param = array(
                    'out_trade_no' => $orderno,
                    'total_amount' => $amount,
                    'level' => $level,
                    'usr' => $usr
                );
                echo build_request_form($url, $param);
            } else {
                $code = $error_server_busy;
                $conn->close();
            }
        }
    } else {
        $code = $error_please_signin_usr;
    }
}

if ($code != $error_none) {
   header('Location: ' . "./error.php?code=".$code);
}