<!DOCTYPE html>
<html lang="en">
<link>
<meta charset="UTF-8">
<title>迅聊平台</title>
<link rel="stylesheet" type="text/css" href="../css/base.css"/>
</head>
<body>
<div id="login">
    <p>
        <span class="spmiximp" style="font-size: 20px">
            <?php
                if (isset($_GET['code'])) {
                    $code = $_GET['code'];
                    require_once "./if/errcode.php";
                    $des = $error_code_table[$code];
                    if ($code == $success_signup) {
                        $des .= "，即将自动登录....";
                        header("refresh:2; url=./html/usercenter.html");
                    }
                    echo $des;
                } else {
                    echo "非法访问";
                }
            ?>
        </span></br>
    </p>
</div>
</body>
</html>