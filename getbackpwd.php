<?php

require_once "./if/errcode.php";
$code = -1;
$msg = $error_invalid_param;

if (isset($_POST["email"])) {
    require_once "./if/DBO.php";

    $email = $_POST["email"];
    $sqlsearch = "select * from userinfo where useremail='$email'";
    $result = mysqli_query($conn, $sqlsearch);
    if ($result->num_rows > 0) {
        $arr = array();
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $arr[] = $row;
        }
        $row = $arr[0];

        require_once "./if/config.php";
        $token = md5($row['username'].$row['userpassword'].$md5infostr);
        $getpasstime = time();
        $md5getpasstime = md5($md5timestr.$getpasstime);

        $innercontent = 'http://'.$_SERVER['HTTP_HOST']."/verifyresetpwd.php?email=".$email."&token=".$token."&code=".$md5getpasstime."&time=".$getpasstime;
        $result = sendmail($email, '迅聊平台 - 找回密码', $innercontent);
        if ($result == 1) {
            $code = $success_send_email;
        }else{
            $msg = $error_recv_email_failed;
        }
    } else {
        $msg = $error_not_found_email;
    }
    $conn->close();
}

header('Location: ' . "./error.php?code=".$code);

function sendmail($to, $title, $content) {
    // 这个PHPMailer
    require_once './other/PHPMailer/PHPMailerAutoload.php';

    $mail = new PHPMailer;
    //使用smtp鉴权方式发送邮件
    $mail->isSMTP();
    //smtp需要鉴权 这个必须是true
    $mail->SMTPAuth = true;
    // 163 邮箱的 smtp服务器地址，这里当然也可以写其他的 smtp服务器地址
    $mail->Host = "smtp.163.com";
    //smtp登录的账号 这里填入字符串格式的qq号即可
    $mail->Username = "yh5253331@163.com";
    // 这个就是之前得到的授权码，一共16位
    $mail->Password = "yhpop21";
    $mail->setFrom("yh5253331@163.com", '迅聊平台管理员');
    // $to 为收件人的邮箱地址，如果想一次性发送向多个邮箱地址，则只需要将下面这个方法多次调用即可
    $mail->addAddress($to);
    // 该邮件的主题
    $mail->Subject = $title;
    // 该邮件的正文内容
    $mail->IsHTML(true);
    $mail->Body = "亲爱的".$to."：<br/>您在迅聊平台提交了找回密码请求。请点击下面的链接重置密码 （链接15分钟内有效）。<br/><a href='".$content."'target='_blank'>".$content."</a>";;
    // 开启调试信息
    //$mail->SMTPDebug = 1;
    // 使用 send() 方法发送邮件
    return $mail->send();
}
