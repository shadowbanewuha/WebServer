<?php

require_once "./if/errcode.php";
$code = $error_invalid_param;

do {
    if (!isset($_POST["email"])) break;
    if (!isset($_POST["pwd"])) break;
    if (!isset($_POST["token"])) break;

    require_once "./if/DBO.php";

    $email = $_POST["email"];
    $password = $_POST["pwd"];
    $token = $_POST["token"];

    $sqlsearch = "select username from userinfo where useremail='$email'";
    $result = mysqli_query($conn, $sqlsearch);
    if ($result->num_rows > 0) {
        $arr = array();
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $arr[] = $row;
        }
        $username = $arr[0]['username'];
        $dbtoken = md5($username.$email.$md5infostr);
        if ($dbtoken != $token) {
            $code = $error_token_invalid;
        } else {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $sqlupdate = "update userinfo set userpassword='$password' where username='$username'";
            if (mysqli_query($conn, $sqlupdate)) {
                $code = $error_none;
            } else {
                $code = $error_server_busy;
            }
        }
    } else {
        $code = $error_request_expire;
    }
    $conn->close();
} while(0);

if ($code == $error_none) {
    header("refresh:2;url=./html/signin.html");
    echo '修改成功！<br>即将自动跳转到登录界面...';
} else {
    header('Location: ' . "./error.php?code=".$code);
}
