<?php
// Generate Salt
function gen_salt()
{
    $rtxt = "abcdefghijklmnopqrstuABCDEFGHIJKLMNOPQRSTU1234567890";
    // Set string to randomize
    $strlength = strlen($rtxt);
    // Get length of the string
    $rs = "";

    for ($i = 0; $i < $strlength; $i++) {
        $rand = round(rand(0, $strlength));
        if ($rand < 0) {
            $rand = 0;
        } else if ($rand > 52) {
            $rand = 52;
        }
        $rs .= $rtxt[$rand];
    }

    $hash = hash("sha512", $rs);
    // Hash the randomly generated string
    return $hash;
}

// Session start
function sec_session_start()
{
    $session_name = 'sec_session_id';
    // Set a custom session name.
    $secure = false;
    //Set to true if using https.
    $httponly = true;
    // This stops javascript being able to access session id.
    ini_set('session.use_only_cookies', 1);
    // Forces sessions to only use cookies.
    $cookieParams = session_get_cookie_params();
    // Gets cookies params.
    session_set_cookie_params($cookieParams['lifetime'], $cookieParams['path'], $cookieParams['domain'], $secure, $httponly);
    session_name($session_name);
    // Sets session name
    session_start();
    // Start session
    session_regenerate_id(true);
    //return true;
}

//Prevent Brute force attack
function checkbrute($user_id, $mysqli)
{
    // Get timestamp of current time
    $now = time();
    // All login attempts are counted from the past 2 hours.
    $valid_attempts = $now - (2 * 60 * 60);

    if ($stmt = $mysqli->prepare("SELECT time FROM login_attempts WHERE usr_id = ? AND time > '$valid_attempts'")) {
        $stmt->bind_param('i', $usr_id);
        // Execute the prepared query.
        $stmt->execute();
        $stmt->store_result();
        // If there has been more than 5 failed logins
        if ($stmt->num_rows > 5) {
            return true;
        } else {
            return false;
        }
    }
}

//Login check
function login_check($mysqli)
{
    // Check if all session variables are set
    if (isset($_SESSION['master_id'], $_SESSION['master_login'], $_SESSION['login_string'])) {
        $master_id = $_SESSION['master_id'];
        $login_string = $_SESSION['login_string'];
        $master_login = $_SESSION['master_login'];

        $master_browser = $_SERVER['HTTP_USER_AGENT'];
        // Get the user-agent string of the user.

        if ($stmt = $mysqli->prepare("SELECT master_password FROM master WHERE master_id = ? LIMIT 1")) {
            $stmt->bind_param('i', $master_id);
            // Bind "$user_id" to parameter.
            $stmt->execute();
            // Execute the prepared query.
            $stmt->store_result();

            if ($stmt->num_rows == 1) { // If the user exists
                $stmt->bind_result($master_password);
                // get variables from result.
                $stmt->fetch();
                $login_check = hash('sha512', $master_password . $master_browser);
                if ($login_check == $login_string) {
                    // Logged In!!!!
                    return true;
                } else {
                    // Not logged in
                    return false;
                }
            } else {
                // Not logged in
                return false;
            }
        } else {
            // Not logged in
            return false;
        }
    } else {
        // Not logged in
        return false;
    }
}


// AutoGenerate Code
function auto_generate($table, $mysqli)
{
    $code = "";
    $id = "";
    switch ($table) {
        case "customer" :
            $code = code(2, $mysqli);
            $id = "customer_id";
            break;
        case "operator" :
            $code = code(3, $mysqli);
            $id = "operator_id";
            break;
        case "order" :
            $code = code(1, $mysqli);
            $id = "order_id";
            break;
        case "type" :
            $code = code(4, $mysqli);
            $id = "type_id";
    }

    $result = $mysqli->query("SELECT $id FROM `$table` ORDER BY $id DESC limit 1");
    $rowcount = $result->num_rows;

    if ($rowcount == "") {
        $last_id = 0;
    } else {
        while ($row = $result->fetch_array(MYSQLI_NUM)) {
            $last_id = $row[0];
        }
    }

    $b = $last_id + 1;
    $b = str_pad($b, 4, '0', STR_PAD_LEFT);
    $code = $code . $b;

    return $code;
}

// Pagination
function paginate($reload, $page, $tpages)
{
    $adjacents = 2;
    $prevlabel = "Prev";
    $nextlabel = "Next";
    $out = "";
    // previous
    if ($page == 1) {
        $out .= "<li><a class='lblPrev'>" . $prevlabel . "</a></li>";
    } elseif ($page == 2) {
        $out .= "<li><a class='lblPrev' href=\"" . $reload . "\">" . $prevlabel . "</a>\n</li>";
    } else {

        $out .= "<li><a class='lblPrev' href=\"" . $reload . "&amp;page=" . ($page - 1) . "\">" . $prevlabel . "</a>\n</li>";
    }

    $pmin = ($page > $adjacents) ? ($page - $adjacents) : 1;
    $pmax = ($page < ($tpages - $adjacents)) ? ($page + $adjacents) : $tpages;
    for ($i = $pmin; $i <= $pmax; $i++) {
        if ($i == $page) {
            $out .= "<li class=\"active\"><a>" . $i . "</a></li>\n";
        } elseif ($i == 1) {
            $out .= "<li><a href=\"" . $reload . "\">" . $i . "</a>\n</li>";
        } else {
            $out .= "<li><a href=\"" . $reload . "&amp;page=" . $i . "\">" . $i . "</a>\n</li>";
        }
    }

    if ($page < ($tpages - $adjacents)) {
        $out .= "<a style='font-size:11px' href=\"" . $reload . "&amp;page=" . $tpages . "\">" . $tpages . "</a>\n";
    }
    // next
    if ($page < $tpages) {
        $out .= "<li><a class='lblNext' href=\"" . $reload . "&amp;page=" . ($page + 1) . "\">" . $nextlabel . "</a>\n</li>";
    } else {
        $out .= "<li><a class='lblNext'>" . $nextlabel . "</a></li>";
    }
    $out .= "";
    return $out;
}

function autoGen()
{
    $generate_id = uniqid(rand());
    $id_generate = substr($generate_id, -6);
    $id_generate = strtoupper($id_generate);
    return "ORD" . $id_generate;
}

function orderType($sta)
{
    if ($sta == 1) {
        return "発注";
    } elseif ($sta == 2) {
        return "受注";
    }
}

function print_f($array) {
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}