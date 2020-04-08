<?php

$success_signup = 1;
$success_send_email = 2;
$success_change_pwd = 3;
$success_addcredit = 4;
$success_admin_login = 5;

$error_none = 0;

$error_invalid_param = -1;
$error_server_busy = -2;
$error_invalid_usr_or_pwd = -3;
$error_usr_exist = -4;
$error_email_exist = -5;
$error_usr_not_match_pwd = -6;
$error_session_expire = -7;
$error_request_expire = -8;
$error_token_invalid = -9;
$error_email_tamper = -10;
$error_recv_email_failed = -11;
$error_not_found_email = -12;
$error_please_signin_usr = -13;
$error_old_pwd_error = -14;
$error_verify_code_error = -15;
$error_user_had_suspend = -16;

$error_code_table = array(
    $success_signup => "注册成功",
    $success_send_email => "邮件发送成功",
    $success_change_pwd => "修改密码成功",
    $success_addcredit => "充值成功",
    $success_admin_login => "管理员登录",

    $error_none => "success",

    $error_invalid_param => "参数错误",
    $error_server_busy => "服务器繁忙，请稍后再试",
    $error_invalid_usr_or_pwd => "无效的用户名或密码",
    $error_usr_exist => "注册失败，用户名已存在",
    $error_email_exist => "注册失败，邮箱已经绑定其他账号",
    $error_usr_not_match_pwd => "用户名或密码错误",
    $error_session_expire => "Session已过期，请重新登录",
    $error_request_expire => "请求已过期，请重试",
    $error_token_invalid => "Token已失效，请重试",
    $error_email_tamper => "邮箱已被篡改，请重试",
    $error_recv_email_failed => "获取邮件失败，请重试",
    $error_not_found_email => "未能找到此邮账号箱绑定的账号",
    $error_please_signin_usr => "请先登录账号",
    $error_old_pwd_error => "旧密码错误",
    $error_verify_code_error => '验证码错误',
    $error_user_had_suspend => '用户已被封禁',
);