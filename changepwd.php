<?php

require_once "./if/errcode.php";
$code = $error_invalid_param;

if (isset($_POST["pwd"]) && !empty($_POST["pwd"])
    && isset($_POST["oldpwd"]) && !empty($_POST["oldpwd"])) {
    session_start();
    if (isset($_SESSION["admin"]) && $_SESSION["admin"] === true) {
        $usr = $_SESSION["user"];
        $oldpwd = $_POST["oldpwd"];
        $password = $_POST["pwd"];

        require_once "./if/DBO.php";
        $sqlsearch = "select userpassword from userinfo where username='$usr'";
        $result = mysqli_query($conn, $sqlsearch);
        if ($result->num_rows > 0) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $db_pwd = $row['userpassword'];
            if (password_verify($oldpwd, $db_pwd) === true) {
                $password = password_hash($password, PASSWORD_DEFAULT);
                $sqlupdate = "update userinfo set userpassword='$password' where username='$usr'";
                if (mysqli_query($conn, $sqlupdate)) {
                    $code = $error_none;
                } else {
                    $code = $error_server_busy;
                }
            } else {
                $code = $error_old_pwd_error;
            }
        } else {
            $code = $error_server_busy;
        }
        $conn->close();
    } else {
        $code = $error_please_signin_usr;
    }
}

if ($code === $error_none) {
    header("refresh:2;url=./html/usercenter.html");
    echo '修改成功！<br>即将自动跳转到用户中心...';
} else {
    header('Location: ' . "./error.php?code=".$code);
}
