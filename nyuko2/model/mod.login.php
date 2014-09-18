<?php
function login($login_id, $password, $mysqli)
{
    $user_id = "";
    $user_name = "";
    $user_password = "";
    $user_salt = "";

    $query = "SELECT user_id, user_name,password, salt FROM user_tbl WHERE user_id = '" . $login_id . "' LIMIT 1";

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($user_id, $user_name, $user_password, $user_salt);
        $stmt->fetch();
    }
    // hash the password with the unique salt.
    $hashed = hash('sha512', $password);
    $password = hash('sha512', $hashed . $user_salt);

    if ($stmt->num_rows == 1) { // If the user exists
        if ($user_password == $password) { // Check if the password in the database matches the password the user submitted.
            $_SESSION['sess_user_id'] = $user_id;
            $_SESSION['sess_username'] = $user_name;
            return true;
        } else {
            return false;
        }
    } else {
        // No user exists.
        return false;
    }
}

function logout()
{
    $_SESSION = array();
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    session_destroy();
    return true;
}

function checkSession($session)
{
    if (!isset($session) || $session == "" || $session == NULL) {
        header("location: " . ROOT . "index.php");
        exit;
    }
}

function checkOrderSession($session, $session2)
{
    if ((!isset($session) || $session == "" || $session == NULL) || (!isset($session2) || $session2 == "" || $session2 == NULL)) {
        header("location: " . ROOT . "order_select.php");
        exit;
    }
}