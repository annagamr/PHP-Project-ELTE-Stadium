<?php
include("userstorage.php");
include("auth.php");
//authentication
function redirect($page) {
    header("Location: ${page}");
    exit();
  }

session_start();
$user_storage = new UserStorage();
$auth = new Auth($user_storage);

$auth->logout();
redirect('login.php');
?>