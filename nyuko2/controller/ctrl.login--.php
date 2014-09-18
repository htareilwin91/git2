<?php
include_once("../lib/ini.setting.php");
include_once("ini.config.php");
include_once("ini.dbstring.php");
include_once("ini.functions.php");

sec_session_start();

include_once("mod.login.php");

if (isset($_GET['cmd']) && $_GET['cmd'] == "logout") {
    logout();
    header("location: " . ROOT . "index.php");
    exit;
} else {
    $login_id = $_POST['login_id'];
    $password = $_POST['password'];
    $password = hash("sha256", $password);
    $stmt = login($db, $login_id);
    if (sizeof($stmt) == 0) {
        header('Location: ' . ROOT . 'index.php?err=1');
        exit;
    } else {
        while ($result = $stmt) {
            define("MAX_LENGTH", 6);
            $intermediateSalt = md5(uniqid(rand(), true));
            $salt = substr($intermediateSalt, 0, MAX_LENGTH);
            $hash = hash("sha256", $password . $result['salt']);
            if ($hash != $result['password']) // Incorrect password. So, redirect to login_form again.
            {
                header('Location: ' . ROOT . 'index.php?err=1');
                exit;
            } else {
                // Redirect to home page after successful login.
                $_SESSION['sess_user_id'] = $result['user_id'];
                $_SESSION['sess_username'] = $result['user_name'];
                header('Location: ' . ROOT . "order_select.php");
                session_write_close();
            }
        }
    }
}

