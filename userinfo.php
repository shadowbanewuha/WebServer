<?php

require_once "./if/errcode.php";
$code = $error_invalid_param;
$msg = array('action' => $_GET["action"], 'des' => $error_code_table[$code]);

if (isset($_GET["action"])) {
    session_start();
    switch ($_GET["action"]) {
        case 'getusername':
            if (isset($_SESSION["admin"]) && $_SESSION["admin"] === true) {
                $code = $error_none;
                $msg = array('action' => $_GET["action"], 'user' =>  $_SESSION['user']);
            } else {
                $code = $error_session_expire;
                $msg = array('action' => $_GET["action"], 'des' => $error_code_table[$code]);
            }
            break;
        case 'getusercenterinfo':
            if (isset($_SESSION["admin"]) && $_SESSION["admin"] === true) {
                $usr = $_SESSION['user'];
                require_once "./if/DBO.php";
                $sqlsearch = "select * from userinfo where username='$usr'";
                $result = mysqli_query($conn, $sqlsearch);
                if ($result->num_rows > 0) {
                    $code = $error_none;
                    $arr = array();
                    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                        $arr[] = $row;
                    }
                    $usr = $arr[0]['username'];
                    $vipenddate = $arr[0]['vipenddate'];
                    $lastloginip = $arr[0]['loginip'];
                    $lastlogintime = $arr[0]['logintime'];
                    $msg = array('action' => $_GET["action"], 'usr' =>  $usr, 'lastloginip' => $lastloginip, 'lastlogintime' =>  $lastlogintime, 'vipenddate' =>  $vipenddate);
                } else {
                    $code = $error_server_busy;
                    $msg = array('action' => $_GET["action"], 'des' => $error_code_table[$code]);
                }
                $conn->close();
            } else {
                $code = $error_session_expire;
                $msg = array('action' => $_GET["action"], 'des' => $error_code_table[$code]);
            }
            break;
        default:
            break;
    }
}

$array = array('code' => $code, 'msg' => $msg);
echo json_encode($array, JSON_UNESCAPED_UNICODE);