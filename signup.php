<?php

require_once "./if/errcode.php";
$code = $error_invalid_param;

do {
    if (!isset($_POST["usr"])) break;
    if (!isset($_POST["pwd"])) break;
    if (!isset($_POST["email"])) break;

    require_once "./if/DBO.php";
    require_once "./if/function.php";

    $username = $_POST["usr"];
    $password = $_POST["pwd"];

    $useremail = $_POST["email"];
    $loginip = get_real_ip();
    $logintime = date("Y-m-d H:i:s");

    $sqlsearch = "select * from userinfo where username='$username'";
    $result = mysqli_query($conn, $sqlsearch);

    if ($result->num_rows > 0) {
        $code = $error_usr_exist;
    } else if (!empty($username) && !empty($password) && !empty($useremail)) {
        $sqlsearch = "select * from userinfo where useremail='$useremail'";
        $result = mysqli_query($conn, $sqlsearch);
        if ($result->num_rows > 0) {
            $code = $error_email_exist;
        } else {
            $password = password_hash($password, PASSWORD_DEFAULT); // hash 加密
            $sqlinsert = "insert into userinfo (username, userpassword, regtime, logintime, regip, loginip, useremail, authlevel, vipenddate, userstatus) values ('$username', '$password', '$logintime', '$logintime', '$loginip', '$loginip', '$useremail', 9, '$logintime', 1)";
            if (mysqli_query($conn, $sqlinsert)) {
                $code = $success_signup;
                session_start();
                setcookie(session_name(), session_id(), time() + $session_lift_time, "/");
                $_SESSION['admin'] = true;
                $_SESSION["user"] = $username;
            } else {
                $code = $error_server_busy;
            }
        }
    } else {
        $code = $error_invalid_usr_or_pwd;
    }
    $conn->close();
} while(0);

header('Location: ' . "./error.php?code=".$code);
?>
