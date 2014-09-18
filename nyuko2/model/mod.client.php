<?php
function insertClient($client_com_name, $client_user_name, $add, $mysqli)
{
    $query = "INSERT INTO client_tbl(company_name,client_user_name,address,created_date)";
    $query .= " VALUES('" . $client_com_name . "', '" . $client_user_name . "', '" . $add . "', NOW())";

    if ($mysqli->query($query)) {
        header("location:clientlist.php");
    }
    return false;
}

function getClient($mysqli)
{
    $query = "SELECT * FROM client_tbl where delete_flag=0";

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

function deleteClient($id, $mysqli)
{
    $query = "UPDATE client_tbl SET delete_flag=1 WHERE client_id =" . $id;
    if ($mysqli->query($query)) {
        header("location:clientlist.php");
        exit;
    }
}

function getClientById($clientid, $mysqli)
{
    $query = "SELECT * FROM client_tbl WHERE client_id=$clientid AND delete_flag=0";
    if ($stmt = $mysqli->query($query)) {
        if ($stmt->num_rows > 0) {
            $data = $stmt->fetch_assoc();
        } else {
            $data = "";
        }
    }
    return $data;
}

