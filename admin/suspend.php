<?php
require_once "./../if/DBO.php";
session_start();
if (isset($_SESSION["admin"]) && $_SESSION["admin"] === true && $_SESSION["user"] === $administrator) {
    if (isset($_POST)) {
        $action = intval($_POST['action']);
        while (list ($key, $usr) = each ($_POST)) {
            if ($key === "action") continue;
            db_suspend($conn, $usr, $action);
        }
        $conn->close();
    }
} else {
    die("没有访问权限");
}