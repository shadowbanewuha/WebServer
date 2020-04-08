<?php

session_start();
//unset($_SESSION['admin']);
//unset($_SESSION['user']);
//unset($_SESSION['verifycode']);
session_destroy();

header('Location: ' . "./html/signin.html");