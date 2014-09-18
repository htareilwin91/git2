<?php
if ($_POST['orderAdd']) {
    $data["selClient"] = $_POST['selClient'];
    $data["hidOrdID"] = $_POST['hidOrdID'];
    $data["hidOrdType"] = $_POST['hidOrdType'];
    $data['txtDept'] = $_POST['txtDept'];
    $data["txtResPerson"] = $_POST['txtResPerson'];
    $data["txtOrdPerson"] = $_POST['txtOrdPerson'];
    $data["txtCAddress"] = $_POST['txtCAddress'];
    $data["txtPdName"] = $_POST['txtPdName'];
    $data["txtOrdDate"] = implode(" ", array($_POST['txtOrdDateD'], $_POST['txtOrdDateT']));
    $data["txtRecAmt"] = $_POST['txtRecAmt'];
    $data["txtOdrAmt"] = $_POST['txtOdrAmt'];
    $data["txtCompAmt"] = $_POST['txtCompAmt'];
    $data["txtMrgAmt"] = $_POST['txtMrgAmt'];
    $data["txtStartTime"] = implode(" ", array($_POST['txtStartTimeD'], $_POST['txtStartTimeT']));
    $data["txtEndTime"] = implode(" ", array($_POST['txtEndTimeD'], $_POST['txtEndTimeT']));
    $data["selRf"] = $_POST['selRf'];
    $data["selOS"] = $_POST['selOS'];
    $data["selApp"] = $_POST['selApp'];
    $data["txtLayoutPt"] = $_POST['txtLayoutPt'];
    $data["txtTextPt"] = $_POST['txtTextPt'];
    $data["txtFormat"] = $_POST['txtFormat'];
    $data["txtDiv"] = $_POST['txtDiv'];
    $data["selImg"] = $_POST['selImg'];
    $data["txtImgPt"] = $_POST['txtImgPt'];
    $data["txtAdPt"] = $_POST['txtAdPt'];
    $data["txtComment"] = $_POST['txtComment'];
    $data["txtDDate"] = implode(" ", array($_POST['txtDDateD'], $_POST['txtDDateT']));

    ## get inserted order id
    $data["orid"] = insertOrder($data, $db);

    ## loop through receiptfacts
    for ($i = 0; $i < count($data['selRf']); $i++) {

        if ($data['selRf'][$i] == 0) {
            continue;
        }

        if (!insertRf($data["orid"], $data['selRf'][$i], $db)) {
            header("location: " . ROOT . "error.html");
            exit;
        }
    }

    ## loop through applications
    for ($i = 0; $i < count($data['selApp']); $i++) {

        if ($data['selApp'][$i] == 0) {
            continue;
        }

        if (!insertApp($data["orid"], $data['selApp'][$i], $db)) {
            header("location: " . ROOT . "error.html");
            exit;
        }
    }

    header("location: " . ROOT . "order_list.php");
    exit;
}

if ($_POST['orderUpdate']) {
    $data["selClient"] = $_POST['selClient'];
    $data["hidOrdID"] = $_POST['hidOrdID'];
    $data["hidOrdType"] = $_POST['hidOrdType'];
    $data['txtDept'] = $_POST['txtDept'];
    $data["txtResPerson"] = $_POST['txtResPerson'];
    $data["txtOrdPerson"] = $_POST['txtOrdPerson'];
    $data["txtCAddress"] = $_POST['txtCAddress'];
    $data["txtPdName"] = $_POST['txtPdName'];
    $data["txtOrdDate"] = implode(" ", array($_POST['txtOrdDateD'], $_POST['txtOrdDateT']));
    $data["txtRecAmt"] = $_POST['txtRecAmt'];
    $data["txtOdrAmt"] = $_POST['txtOdrAmt'];
    $data["txtCompAmt"] = $_POST['txtCompAmt'];
    $data["txtMrgAmt"] = $_POST['txtMrgAmt'];
    $data["txtStartTime"] = implode(" ", array($_POST['txtStartTimeD'], $_POST['txtStartTimeT']));
    $data["txtEndTime"] = implode(" ", array($_POST['txtEndTimeD'], $_POST['txtEndTimeT']));
    $data["selRf"] = $_POST['selRf'];
    $data["selOS"] = $_POST['selOS'];
    $data["selApp"] = $_POST['selApp'];
    $data["txtLayoutPt"] = $_POST['txtLayoutPt'];
    $data["txtTextPt"] = $_POST['txtTextPt'];
    $data["txtFormat"] = $_POST['txtFormat'];
    $data["txtDiv"] = $_POST['txtDiv'];
    $data["selImg"] = $_POST['selImg'];
    $data["txtImgPt"] = $_POST['txtImgPt'];
    $data["txtAdPt"] = $_POST['txtAdPt'];
    $data["txtComment"] = $_POST['txtComment'];
    $data["txtDDate"] = implode(" ", array($_POST['txtDDateD'], $_POST['txtDDateT']));
    $data['selStatus'] = $_POST['selStatus'];

    if(!updateOrder($data, $db)) {
        header("location: " . ROOT . "order_list.php");
        exit;
    }

    ## clear Rf and App data first
    deleteRf($data["hidOrdID"], $db);
    deleteApp($data["hidOrdID"], $db);

    ## loop through receiptfacts
    for ($i = 0; $i < count($data['selRf']); $i++) {

        if ($data['selRf'][$i] == 0) {
            continue;
        }

        if (!insertRf($data["hidOrdID"], $data['selRf'][$i], $db)) {
            header("location: " . ROOT . "error.html");
            exit;
        }
    }

    ## loop through applications
    for ($i = 0; $i < count($data['selApp']); $i++) {

        if ($data['selApp'][$i] == 0) {
            continue;
        }

        if (!insertApp($data["hidOrdID"], $data['selApp'][$i], $db)) {
            header("location: " . ROOT . "error.html");
            exit;
        }
    }

    header("location: " . ROOT . "order_list.php");
    exit;
}

if (isset($_POST['orderUpdate'])) {
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
}

if (isset($_POST["changemonth"])) {
    $d['sess_order_type'] = $_SESSION['sess_order_type'];
    $d['sess_client'] = $_SESSION['sess_client'];

    $month = $_POST['month'];
    $year = $_POST['year'];
    $ordlist = getOrderByMonth($month, $year, $d, $db);
}