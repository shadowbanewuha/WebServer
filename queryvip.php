<?php

    $code = -1;
    $msg = '参数错误';
    
    do {
        if (!isset($_POST["username"])) break;
        if (!isset($_POST["password"])) break;
        
        require "./if/DBO.php";
        
        $username = $_POST["username"];
        $password = $_POST["password"];
        
        $sqlsearch = "select vipenddate from userinfo where username='$username' and userpassword='$password'";
        $result = mysqli_query($conn, $sqlsearch);
        
        if ($result->num_rows > 0) {
            $arr = array();
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $arr[] = $row;
            }
            $vipenddate = $arr[0]['vipenddate'];
            
            $logintime = time();
            $vipendtime = strtotime($vipenddate);
            
            if ($logintime >= $vipendtime) {
                $code = -1;
                $msg = '会员已经过期';
            } else {
                $code = 0;
                $msg = '还是会员';
            }
        } else {
            $code = -2;
            $msg = '用户名或密码错误';
        }
        
        $conn->close();
    } while(0);

    $array = array('code' => $code, 'msg' => $msg);
    echo json_encode($array, JSON_UNESCAPED_UNICODE);
?>
