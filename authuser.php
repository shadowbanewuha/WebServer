<?php

$admin = false;
session_start();
if (isset($_SESSION["admin"]) && $_SESSION["admin"] === true) {
    require_once "./if/config.php";
    if ($_SESSION["user"] === $administrator) {
 //       header('Location: ' . "./admin/");
  //  } else {
        header('Location: ' . "/html/usercenter.html");
    }
} else {
    $_SESSION["admin"] = false;
    header('Location: ' . "/html/signin.html");
}