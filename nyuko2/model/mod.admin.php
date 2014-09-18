<?php

function getusermail($usermail, $mysqli)
{

    $query = "SELECT * FROM user_tbl WHERE email='" . $usermail . "'";

    if ($stmt = $mysqli->query($query)) {
        if ($stmt->num_rows > 0) {
            while ($result = $stmt->fetch_assoc()) {
                $data[] = $result;
            }
        } else {
            $data = "";
        }
    }
    return $data;
}

function update_psw($newpsw, $salt, $hemail, $mysqli)
{
    $query = "UPDATE `user_tbl` SET `password` = '".$newpsw."', `salt` = '".$salt."' WHERE `user_tbl`.`email` = '".$hemail."'";
    if ($mysqli->query($query)) {
        return true;
    }

    return false;
}