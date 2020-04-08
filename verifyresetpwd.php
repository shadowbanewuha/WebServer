<?php

require_once "./if/errcode.php";
$code = $error_invalid_param;

do {
    if (!isset($_GET["email"])) break;
    if (!isset($_GET["token"])) break;
    if (!isset($_GET["code"])) break;
    if (!isset($_GET["time"])) break;

    require_once "./if/config.php";
    $getpasstime = $_GET["time"];
    $code = $_GET["code"];
    $md5time = md5($md5timestr.$getpasstime);
    $now = time();
    if ($code != $md5time) {
        $code = $error_request_expire;
        break;
    }

    if ( $now-$getpasstime > 15 * 60) {
        $code = $error_request_expire;
        break;
    }

    require_once "./if/DBO.php";
    $email = $_GET["email"];
    $token = $_GET["token"];

    $sqlsearch = "select username,userpassword from userinfo where useremail='$email'";
    $result = mysqli_query($conn, $sqlsearch);
    if ($result->num_rows > 0) {
        $arr = array();
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $arr[] = $row;
        }
        $username = $arr[0]['username'];
        $userpassword = $arr[0]['userpassword'];
        $dbtoken = md5($username.$userpassword.$md5infostr);
        if ($dbtoken != $token) {
            $code = $error_token_invalid;
        } else {
            $code = $error_none;
            $resettoken = md5($username.$email.$md5infostr);
            include "./html/resetpwd.html";
        }
    } else {
        $code = $error_email_tamper;
    }
    $conn->close();
} while(0);

if ($code != $error_none) {
    header('Location: ' . "./error.php?code=".$code);
}

?>
