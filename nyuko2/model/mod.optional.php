<?php
function input_desc($table, $data, $mysqli)
{
    switch ($table) {
        case 'application_tbl':
            $query = "INSERT INTO application_tbl(app_descp,create_date,delete_flag)";
            $query .= " VALUES('" . $data . "', NOW(),0)";
            break;

        case 'status_tbl':
            $query = "INSERT INTO status_tbl(sta_descp,create_date,delete_flag)";
            $query .= " VALUES('" . $data . "', NOW(),0)";

            break;

        case 'receiptfacts_tbl':
            $query = "INSERT INTO receiptfacts_tbl(rf_descp,create_date,delete_flag)";
            $query .= " VALUES('" . $data . "', NOW(),0)";
    }

    if ($mysqli->query($query)) {
        return true;
    }
    return false;
}

function insertRf($orderid, $data, $mysqli)
{
    $query = "INSERT INTO ord_rf_tbl(order_id, rf_id, create_date, delete_flag) ";
    $query .= "VALUES(" . $orderid . ", " . $data . ", NOW(), 0)";

    $stmt = $mysqli->prepare($query);
    if (!$stmt->execute()) {
        return false;
    }
    return true;
}

function insertApp($orderid, $data, $mysqli)
{
    $query = "INSERT INTO ord_app_tbl(order_id, app_id, create_date, delete_flag) ";
    $query .= "VALUES(" . $orderid . ", " . $data . ", NOW(), 0)";

    $stmt = $mysqli->prepare($query);
    if (!$stmt->execute()) {
        return false;
    }
    return true;
}

function getRf($mysqli)
{
    $query = "SELECT * FROM receiptfacts_tbl WHERE delete_flag=0";

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

function getRfById($ordid, $mysqli)
{
    $query = "SELECT * FROM ord_rf_tbl o LEFT JOIN receiptfacts_tbl r ON r.rf_id = o.rf_id ";
    $query .= "WHERE o.order_id = $ordid AND r.delete_flag=0";

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

function getOS($mysqli)
{
    $query = "SELECT * FROM os_tbl WHERE delete_flag=0";

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

function getApp($mysqli)
{
    $query = "SELECT * FROM application_tbl WHERE delete_flag=0";

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

function getAppById($ordid, $mysqli)
{
    $query = "SELECT * FROM ord_app_tbl o LEFT JOIN application_tbl a ON a.app_id = o.app_id ";
    $query .= "WHERE o.order_id = $ordid AND a.delete_flag=0";

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

function getStatus($mysqli) {
    $query = "SELECT * FROM status_tbl WHERE delete_flag=0";

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

function deleteRf($ordid, $mysqli) {
    $query = "DELETE FROM ord_rf_tbl WHERE order_id = $ordid";

    if($mysqli ->query($query)) {
        return true;
    }
    return false;
}

function deleteApp($ordid, $mysqli) {
    $query = "DELETE FROM ord_app_tbl WHERE order_id = $ordid";

    if($mysqli ->query($query)) {
        return true;
    }
    return false;
}
