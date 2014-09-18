<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14/09/04
 * Time: 14:46
 */
include_once("mod.client.php");

$client_com_name = '';
$client_user_name = '';
$address = '';
if (isset($_POST['submit'])) {
    $client_com_name = $_POST['comname'];
    $client_user_name = $_POST['c_username'];
    $address = $_POST['add'];

    if (strlen($client_com_name) == 0)
        $name_error = "Please enter your company name";

    if (strlen($client_user_name) == 0) {
        $user_name_error = "Please enter your user name";
    }

    if (strlen($address) == 0) {
        $add_error = "Please specify your address";

    }
    if (strlen($client_com_name) != 0 && strlen($client_user_name) != 0 && strlen($address) != 0) {
        insertClient($client_com_name, $client_user_name, $address, $db);
    }
}

if (isset($_POST['continue'])) {
    $_SESSION['sess_order_type'] = $_POST['order_type'];
    $_SESSION['sess_client'] = $_POST['client'];
    header('Location: ' . ROOT . "order.php");
    session_write_close();
}

if(isset($_GET['cid'])) {
    deleteClient($_GET['cid'], $db);
}