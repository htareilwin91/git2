<?php

// logout
if (isset($_GET['cmd']) && $_GET['cmd'] == "logout") {
    if (logout()) {
        header("location: " . ROOT . "index.php");
        exit;
    }
}

// login
if (isset($_POST['cmd']) && $_POST['cmd'] == "login") {
    $loginid = $_POST['login_id'];
    $password = $_POST['password'];
    if (login($loginid, $password, $db)) {
        header('Location: ' . ROOT . "order_select.php");
        exit;
    } else {
        header('Location: ' . ROOT . 'index.php?err=1');
        exit;
    }
}