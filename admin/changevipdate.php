<?php

require_once "./../if/DBO.php";

session_start();
if (isset($_SESSION["admin"]) && $_SESSION["admin"] === true && $_SESSION["user"] === $administrator) {
    if (isset($_POST['days'])) {
        $days = intval($_POST['days']);
        while (list ($key, $usr) = each ($_POST)) {
            if ($key === "days") continue;
            db_addcredit($conn, $usr, $days);
        }
    }
    $conn->close();
} else {
    $conn->close();
    die("没有访问权限");
}