<?php

require_once "./../if/DBO.php";

session_start();
if (isset($_SESSION["admin"]) && $_SESSION["admin"] === true && $_SESSION["user"] === $administrator) {
    $totalcounts = getUserCounts($conn);
    $totalpage = intval($totalcounts / $adminshowuserperpage) + ($totalcounts % $adminshowuserperpage != 0 ? 1 : 0);
    if (isset($_GET['search'])) {
        $usr = $_GET['search'];
        $arr = searchUserInfo($conn, $usr);
    } else {
        $curpage = $_GET['page'] ? : 0;
        if ($curpage == -1 || $curpage >= $totalpage) $curpage = $totalpage-1;
        $arr = loadUserInfo($conn, $curpage, $adminshowuserperpage);
    }
    $conn->close();
} else {
    $conn->close();
    die("没有访问权限");
}

function searchUserInfo($db, $usr) {
    $sqlsearch = "select * from userinfo where username='$usr' or useremail='$usr'";
    $result = mysqli_query($db, $sqlsearch);
    $arr = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $arr[] = $row;
        }
    }
    return $arr;
}

function getUserCounts($db) {
    $sqlsearch = "select * from userinfo";
    $result = mysqli_query($db, $sqlsearch);
    return $result->num_rows;
}

function loadUserInfo($db, $page = 0, $perpagecount=10) {
    $start = $page * $perpagecount;
    $sqlsearch = "select * from userinfo limit $start,$perpagecount";
    $result = mysqli_query($db, $sqlsearch);
    $user_counts = $result->num_rows;
    $arr = array();
    if ($user_counts > 0) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $arr[] = $row;
        }
    }
    return $arr;
}

?>

<!DOCTYPE html>
<html lang="en">
<link>
<meta charset="UTF-8">
<title>管理平台</title>
<link rel="stylesheet" type="text/css" href="../css/base.css"/>
<script src="./../js/ajax.js"></script>
<script>
    function addcredit() {
        var checkedUserList = document.getElementsByName("users")
        days = document.getElementById('addcredittf').value
        if (days.length == 0) return
        var userLists = ""
        for(var i=0; i<checkedUserList.length; i++){
            if (!checkedUserList[i].checked) continue
            userLists += '&'
            userLists = userLists+i+'='+checkedUserList[i].id
        }
        if (userLists.length == 0) return
        ajax_post_request('./changevipdate.php',
            "days="+days+userLists,
            function (xhr) {
                window.location.href = '?page='+'<?php echo $curpage; ?>'
            },
            function (xhr) {

            }
        )
    }
    
    function suspenduser (op) {
        var checkedUserList = document.getElementsByName("users")
        var userLists = ""
        for(var i=0; i<checkedUserList.length; i++){
            if (!checkedUserList[i].checked) continue
            userLists += '&'
            userLists = userLists+i+'='+checkedUserList[i].id
        }
        if (userLists.length == 0) return
        ajax_post_request('./suspend.php',
            "action="+op+userLists,
            function (xhr) {
                window.location.href = '?page='+'<?php echo $curpage; ?>'
            },
            function (xhr) {

            }
        )
    }

    function searchUser() {
        usr = document.getElementById('searchtf').value
        if (usr.length < 6) return
        window.location.href = '?search='+usr
    }

    function nextpage(cur, total) {
        if (cur < total-1) cur++
        else return
        window.location.href = '?page='+cur
    }

    function prepage(cur) {
        if (cur > 0) cur--
        else  return
        window.location.href = '?page='+cur
    }

    function firstpage() {
        window.location.href = '?page=0'
    }

    function lastpage() {
        window.location.href = '?page=-1'
    }

    function gotopage() {
        page = document.getElementById('inputpage').value
        if (page >= 1) --page;
        else  return
        window.location.href = '?page='+page
    }
</script>
</head>
<body>

<div>
    <table width="100%" bgcolor="#e9faff" cellpadding="2">
        <caption style="background-color: #e9f0ce">用户列表</caption>
        <thead>
            <tr align="center">
                <th></th>
                <th>用户名</th>
                <th>注册时间</th>
                <th>VIP到期时间</th>
                <th>上次登录时间</th>
                <th>账号状态</th>
            </tr>
        </thead>
        <tbody>
            <?php
                for ($i=0; $i<count($arr); $i++) {
                    echo '
                         <tr align="center">
                            <td> <input style="width:40px; margin-top: 4px" name="users" type="checkbox" id='.$arr[$i]['username'].' /> </td>
                            <td>'.$arr[$i]['username'].'</td>
                            <td>'.$arr[$i]['regtime'].'</td>
                            <td>'.$arr[$i]['vipenddate'].'</td>
                            <td>'.$arr[$i]['logintime']. '</td> ';
                            if ($arr[$i]['userstatus'] == 1) {
                                echo '<td style="color: #03ff0e">正常</td>';
                            } else {
                                echo '<td style="color: #ff2e19">封禁</td>';
                            }
                            echo '</tr>';
                }
            ?>
        </tbody>
    </table>
    <div style="margin-top:18px; margin-left: 20px;">
        <button style="margin-left: 20px; width: 88px; height: 32px" onclick="searchUser()">搜索用户：</button>
        <input type="text" class="input_search_box" style="margin-left: 8px" id="searchtf">
        <button style="margin-left: 30px; width: 50px; height: 32px" onclick="addcredit()">充值</button>
        <input type="text" class="input_search_box" style="margin-left: 8px" id="addcredittf">
        <button style="margin-left: 30px; width: 60px; height: 32px" onclick="suspenduser(1)">解封</button>
        <button style="margin-left: 8px; width: 60px; height: 32px" onclick="suspenduser(0)">封禁</button>
        <br>
        <p style="margin-top: 12px">
            <span class="spcontent" style="margin-left: 18px">第</span>
            <?php echo '<span class="spcontent" style="margin-left: 2px">'.($curpage+1).' / '.$totalpage.'</span>' ?>
            <span class="spcontent" style="margin-left: 2px">页</span>
            <button style="margin-left: 8px" onclick="firstpage()">首页</button>
            <?php echo '<button style="margin-left: 8px" onclick="nextpage('.$curpage.', '.$totalpage.')">下一页</button>' ?>
            <?php echo '<button style="margin-left: 8px" onclick="prepage('.$curpage.')">上一页</button>' ?>
            <button style="margin-left: 8px" onclick="lastpage()">尾页</button>
            <button style="margin-left: 18px" onclick="gotopage()">跳转至</button>
            <input type="text" style="width: 32px; height: 4px;  vertical-align: 1px;" id="inputpage">
            <span class="spcontent" style="margin-left: 4px">页</span>
        </p>
    </div>
</div>


</body>
</html>