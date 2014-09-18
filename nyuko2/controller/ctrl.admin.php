<?php
include_once("ini.dbstring.php");
include_once("mod.admin.php");

//for password edit
$status_error = '';
$rece_error = '';
$application_error = '';

if (isset($_POST['add'])) {
    $user_email = $_POST['email'];
    $_SESSION['sess_user_email'] = $_POST['email'];

    $result = getusermail($user_email, $db);
    foreach ($result as $row) {
        $psw = $row['password'];
        $seed = str_split($psw);

        shuffle($seed);

        $rand = '';
        foreach (array_rand($seed, 8) as $k)
            $rand .= $seed[$k];
    }
    $to = $user_email;
    $subject = "Security Code";
    $message = "You can access our site by using the following code:" . "<br>";
    $message .= $rand . "<br>";
    $message .= '<a href="http://atu-japan.co.jp/nyuko/password_reset.php?e=' . $_SESSION['sess_user_email'] . '">Click here to change your new password</a>';

    ini_set("SMTP", "localhost");
    ini_set("sendmail_from", "info@saj.ir");
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf8' . "\r\n";
    $headers .= 'From: Rubbersoul' . "\r\n";
    if (mail($to, "password reset", $message, $headers)) {
        echo '<script type="text/javascript">';
        echo 'alert("Your security code has been sent to your email!");';
        echo 'window.location.href= "index.php";';
        echo '</script>';
    }
}

//for password reset
if (isset($_POST['savepsw'])) {
    $hidden = $_POST['hidden'];
    $newpsw = $_POST['newpsw'];
    $password = hash("sha256", $newpsw);
    $intermediateSalt = md5(uniqid(rand(), true));
    $salt = substr($intermediateSalt, 0, 6);
    $new_pass = hash("sha256", $password . $salt);

    if (!update_psw($new_pass, $salt, $hidden, $db)) {
        echo "fail to reset password";
        exit;
    } else {
        echo "<script>alert('Your password successfully updated')</script>";
    }
}
