<?php

// 数据库
$db_servername = "localhost";
$db_port = "3306";
$db_username = "altai";
$db_password = "yhpop21";
$db_name = "wxeusers";

// 管理员
$administrator = "yangheng";
$adminshowuserperpage = 10; // 每一页显示10个用户

// session生命周期
$session_lift_time = 3 * 24 * 3600;

// md5加密常量字符串
$md5infostr = "jdwu18971g781h1g6ca651";
$md5timestr = "hdwiaio1511h9daj1hg1hf7h19";

// SMTP邮箱
$smtpserver = "smtp.163.com";
$adminsmtpmail = "yh5253331@163.com";
$adminsmtpauthcode = "yhpop21";

// 充值金额
//$config_amounts = [1=>4, 14=>50, 30=>99, 90=>269, 180=>479, 365=>888];
$config_amounts = [1=>0.01, 14=>0.02, 30=>0.03, 90=>0.04, 180=>0.05, 365=>0.06];