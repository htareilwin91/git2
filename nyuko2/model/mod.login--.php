<?php
function login($mysqli, $login_id)
{
    $query = "SELECT user_id, user_name,password, salt FROM user_tbl WHERE user_id = '$login_id' LIMIT 1";

    if ($stmt = $mysqli->query($query)) {
        if ($stmt->num_rows > 0) {
            while ($result = $stmt->fetch_assoc()) {
                return $result;
            }
        }
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


function checkOrderSession($session,$session2)
{
    if ((!isset($session) || $session == "" || $session == NULL) || (!isset($session2) || $session2 == "" || $session2 == NULL)){
        header("location: " . ROOT . "order_select.php");
        exit;
    }
}


