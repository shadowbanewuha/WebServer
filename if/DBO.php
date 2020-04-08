<?php
    require_once dirname ( __FILE__ ).DIRECTORY_SEPARATOR. 'config.php';
    $conn = new mysqli($db_servername, $db_username, $db_password, $db_name);
    if ($conn->connect_error) {
        echo $conn->connect_error;
        die("服务器繁忙，请稍后再试。");
    }

    function db_addcredit($dbhandle, $usr, $days) {
        $sqlsearch = "select vipenddate from userinfo where username='$usr'";
        $result = mysqli_query($dbhandle, $sqlsearch);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $vipenddate = $row['vipenddate'];
        $vipendtime = strtotime($vipenddate);
        $begintime = max(time(), $vipendtime);
        $begintime += $days * 24 * 3600;
        $vipenddate = date("Y-m-d H:i:s", $begintime);
        $sqlupdate = "update userinfo set vipenddate='$vipenddate' where username='$usr'";
        mysqli_query($dbhandle, $sqlupdate);
    }

    function db_suspend($dbhandle, $usr, $status) {
        $sqlupdate = "update userinfo set userstatus='$status' where username='$usr'";
        mysqli_query($dbhandle, $sqlupdate);
    }
?>
