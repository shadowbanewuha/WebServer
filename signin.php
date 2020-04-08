<?php

require_once "./if/errcode.php";
$code = $error_invalid_param;

do {
    if (!isset($_POST["usr"])) break;
    if (!isset($_POST["pwd"])) break;
 //1   if (!isset($_POST["code"])) break;

    $username = $_POST["usr"];
    $password = $_POST["pwd"];
    $vfcode = $_POST["code"];
    session_start();

    if ($vfcode != $_SESSION['verifycode']) {
        //2   $code = $error_verify_code_error;
        //break;
    }
    unset($_SESSION['verifycode']);

    require_once "./if/DBO.php";
    require_once "./if/function.php";

    $loginip = get_real_ip();
    $logintime = date("Y-m-d H:i:s");

    $sqlsearch = "select * from userinfo where username='$username' or useremail='$username'";
    $result = mysqli_query($conn, $sqlsearch);

    if ($result->num_rows > 0) {
        $code = $error_none;
        $arr = array();
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $arr[] = $row;
        }
        $db_pwd = $arr[0]['userpassword'];
        if (password_verify($password, $db_pwd) === true) {
            $status = $arr[0]['userstatus'];
            if ($status != 1) {
                $code = $error_user_had_suspend;
            } else {
                $username = $arr[0]['username']; // 无论是什么方式登录，始终以显示用户名为准. session中也始终存储的是用户名
                $sqlupdate = "update userinfo set loginip='$loginip',logintime='$logintime' where username='$username'";
                mysqli_query($conn, $sqlupdate);
                $lifeTime = 3 * 24 * 3600;
                setcookie(session_name(), session_id(), time() + $lifeTime, "/");
                $_SESSION['admin'] = true;
                $_SESSION["user"] = $username; // session中保存用户名
                if ($username === $administrator) {
                    $code = $success_admin_login;
                }
            }
        } else {
            $code = $error_usr_not_match_pwd;
        }
    } else {
        $code = $error_usr_not_match_pwd;
    }
    $conn->close();
} while(0);

$array = array('code' => $code, 'msg' => $error_code_table[$code]);
echo json_encode($array, JSON_UNESCAPED_UNICODE);
?>
