<?php
//for input page
if (isset($_POST['input_var'])) {

    $input_var = $_POST['input_var'];

    //validation
    if (isset($_POST['rece_facts'])) {
        $rece_facts = $_POST['rece_facts'];
        if (strlen($rece_facts) == 0) {
            $rece_error = "Please enter receipt facts description";
            return false;
        }


    } elseif (isset($_POST['application'])) {
        $application = $_POST['application'];
        if (strlen($application) == 0) {
            $application_error = "Please enter application description";
            return false;
        }
    } else {
        $status = $_POST['status'];
        if (strlen($status) == 0) {
            $status_error = "Please specify status description";
            return false;
        }
    }


    if (isset($rece_facts) || isset($application) || isset($status)) {
        switch ($input_var) {

            case "app":
                if (input_desc("application_tbl", $application, $db)) {
                    echo "<script>alert('Successfully inserted')</script>";
                    echo '<script>window.location.href= "input_desc.php";</script>';
                    break;
                }

            case "status":
                if (input_desc("status_tbl", $status, $db)) {
                    echo "<script>alert('Successfully inserted')</script>";
                    echo '<script>window.location.href= "input_desc.php";</script>';
                    break;
                }

            case "receipt":
                if (input_desc("receiptfacts_tbl", $rece_facts, $db)) {
                    echo "<script>alert('Successfully inserted')</script>";
                    echo '<script>window.location.href= "input_desc.php";</script>';
                    break;
                }

            default:

        }
    }
}