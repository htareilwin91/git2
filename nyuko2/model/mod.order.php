<?php
function insertOrder($data, $mysqli)
{
    $query = "INSERT INTO order_tbl(order_uid, client_id, address, responsible_person, order_person, order_dept, order_date, receipt_date, product_name, client_price, order_price, company_sell_price, margin_price, os_id, layout, text_size, format, divs, display_data, display_data_qty, ad, comment, start_time, end_time, delivery_date, status_id, order_type, create_date, delete_flag) ";
    $query .= "VALUES('" . $data['hidOrdID'] . "', " . $data['selClient'] . ", '" . $data['txtCAddress'] . "','" . $data['txtResPerson'] . "', '" . $data['txtOrdPerson'] . "', '" . $data['txtDept'] . "', NOW(), '" . $data['txtOrdDate'] . "', '" . $data['txtPdName'] . "', ";
    $query .= $data['txtRecAmt'] . ", " . $data['txtOdrAmt'] . ", " . $data['txtCompAmt'] . ", " . $data['txtMrgAmt'] . "," . $data['selOS'] . ", '" . $data['txtLayoutPt'] . "', " . $data['txtTextPt'] . ", ";
    $query .= "'" . $data['txtFormat'] . "', '" . $data['txtDiv'] . "', " . $data['selImg'] . ", " . $data['txtImgPt'] . ", '" . $data['txtAdPt'] . "', '" . $data['txtComment'] . "', ";
    $query .= "'" . $data['txtStartTime'] . "' ,'" . $data['txtEndTime'] . "', '" . $data['txtDDate'] . "', ";
    $query .= "1, " . $data['hidOrdType'] . ", NOW(), 0";
    $query .= ")";

    $stmt = $mysqli->prepare($query);
    if ($stmt->execute()) {
        $orid = $mysqli->insert_id;
        return $orid;
    }
    return false;
}

function getOrder($d, $mysqli)
{
//    $query = "SELECT order_id, CAST(order_date AS DATE) AS order_date, c.company_name, c.client_user_name, ";
//    $query .= "s.sta_descp, s.sta_id, o.order_dept, o.responsible_person, client_price, order_price, company_sell_price, ";
//    $query .= "margin_price, CAST(start_time AS DATE) AS start_time, CAST(end_time AS DATE) AS end_time, CAST(delivery_date AS DATE) AS delivery_date ";
//    $query .= "FROM order_tbl o LEFT JOIN client_tbl c ON c.client_id = o.client_id ";
//    $query .= "LEFT JOIN status_tbl s ON s.sta_id = o.status_id ";
    $query = "SELECT o.order_id, o.order_uid, o.product_name, CAST(order_date AS DATE) AS order_date, c.company_name, c.client_user_name, o.order_person, o.order_dept, o.order_dept, ";
    $query .= "CAST(receipt_date AS DATE) AS receipt_dated, DATE_FORMAT(receipt_date, '%H:%i') AS receipt_datet, os.os_id, ";
    $query .= "o.layout, o.text_size, o.format, o.divs, o.display_data, o.display_data_qty, o.ad, o.comment, ";
    $query .= "s.sta_descp, s.sta_id, c.client_id, c.address, responsible_person, client_price, order_price, company_sell_price, ";
    $query .= "margin_price, CAST(start_time AS DATE) AS start_timed, DATE_FORMAT(start_time, '%H:%i') AS start_timet, CAST(end_time AS DATE) AS end_timed, DATE_FORMAT(end_time, '%H:%i') AS end_timet, CAST(delivery_date AS DATE) AS delivery_dated, DATE_FORMAT(delivery_date, '%H:%i') AS delivery_datet ";
    $query .= "FROM order_tbl o LEFT JOIN client_tbl c ON c.client_id = o.client_id ";
    $query .= "LEFT JOIN status_tbl s ON s.sta_id = o.status_id ";
    $query .= "LEFT JOIN os_tbl os ON os.os_id = o.os_id ";
    $query .= "WHERE c.client_id = " . $d['sess_client'] . " AND o.order_type = " . $d['sess_order_type'] . " AND order_date BETWEEN order_date AND DATE_ADD(order_date, INTERVAL 1 MONTH)";

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

function getOrderById($ordid, $mysqli)
{
    $query = "SELECT o.order_id, o.order_uid, o.product_name, CAST(order_date AS DATE) AS order_date, c.company_name, c.client_user_name, o.order_person, o.order_dept, o.order_dept, ";
    $query .= "CAST(receipt_date AS DATE) AS receipt_dated, DATE_FORMAT(receipt_date, '%H:%i') AS receipt_datet, os.os_id, ";
    $query .= "o.layout, o.text_size, o.format, o.divs, o.display_data, o.display_data_qty, o.ad, o.comment, ";
    $query .= "s.sta_descp, s.sta_id, c.client_id, c.address, responsible_person, client_price, order_price, company_sell_price, ";
    $query .= "margin_price, CAST(start_time AS DATE) AS start_timed, DATE_FORMAT(start_time, '%H:%i') AS start_timet, CAST(end_time AS DATE) AS end_timed, DATE_FORMAT(end_time, '%H:%i') AS end_timet, CAST(delivery_date AS DATE) AS delivery_dated, DATE_FORMAT(delivery_date, '%H:%i') AS delivery_datet ";
    $query .= "FROM order_tbl o LEFT JOIN client_tbl c ON c.client_id = o.client_id ";
    $query .= "LEFT JOIN status_tbl s ON s.sta_id = o.status_id ";
    $query .= "LEFT JOIN os_tbl os ON os.os_id = o.os_id ";
    $query .= "WHERE order_id = " . $ordid . " LIMIT 0, 1";

    if ($stmt = $mysqli->query($query)) {
        if ($stmt->num_rows > 0) {
            $data = $stmt->fetch_assoc();
        } else {
            $data = "";
        }
    }
    return $data;
}

function getOrderByMonth($month, $year, $d, $mysqli)
{
    $query = "SELECT o.order_id, o.order_uid, o.product_name, CAST(order_date AS DATE) AS order_date, c.company_name, c.client_user_name, o .order_person, o.order_dept, o.order_dept, ";
    $query .= "CAST(receipt_date AS DATE) AS receipt_dated, DATE_FORMAT(receipt_date, '%H:%i') AS receipt_datet, os.os_id, ";
    $query .= "o.layout, o.text_size, o.format, o.divs, o.display_data, o.display_data_qty, o.ad, o.comment, ";
    $query .= "s.sta_descp, s.sta_id, c.client_id, c.address, responsible_person, client_price, order_price, company_sell_price, ";
    $query .= "margin_price, CAST(start_time AS DATE) AS start_timed, DATE_FORMAT(start_time, '%H:%i') AS start_timet, CAST(end_time AS DATE) AS end_timed, DATE_FORMAT(end_time, '%H:%i') AS end_timet, CAST(delivery_date AS DATE) AS delivery_dated, DATE_FORMAT(delivery_date, '%H:%i') AS delivery_datet ";
    $query .= "FROM order_tbl o LEFT JOIN client_tbl c ON c.client_id = o.client_id ";
    $query .= "LEFT JOIN status_tbl s ON s.sta_id = o.status_id ";
    $query .= "LEFT JOIN os_tbl os ON os.os_id = o.os_id ";
    $query .= "WHERE MONTH(order_date) = $month AND YEAR(order_date) = $year AND o.order_type = " . $d['sess_order_type'] . " AND c.client_id = " . $d['sess_client'];

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

function searchOrder($searcharray, $mysqli)
{
    if ($searcharray['received_facts'] != 0 )
    {
        $query = "select o.order_id, o.order_uid, o.product_name, CAST(order_date AS DATE) AS order_date, c.company_name, c.client_user_name, o .order_person, o.order_dept, o.order_dept,";
        $query.=" CAST(receipt_date AS DATE) AS receipt_dated, DATE_FORMAT(receipt_date, '%H:%i') AS receipt_datet, os.os_id,";
        $query.=" o.layout, o.text_size, o.format, o.divs, o.display_data, o.display_data_qty, o.ad, o.comment, ";
        $query .= "s.sta_descp, s.sta_id, c.client_id, c.address, responsible_person, client_price, order_price, company_sell_price, ";
        $query .= "margin_price, CAST(start_time AS DATE) AS start_timed, DATE_FORMAT(start_time, '%H:%i') AS start_timet, CAST(end_time AS DATE) AS end_timed, DATE_FORMAT(end_time, '%H:%i') AS end_timet, CAST(delivery_date AS DATE) AS delivery_dated, DATE_FORMAT(delivery_date, '%H:%i') AS delivery_datet, rf_descp";
        $query .= " from order_tbl o join client_tbl c on o . client_id = c . client_id join status_tbl s on o.status_id=sta_id join os_tbl os on os.os_id=o.os_id join(select OT.order_id,OT.rf_id,RF.rf_descp  from ord_rf_tbl OT join
(select rf_id,rf_descp from receiptfacts_tbl where rf_id = '" . $searcharray['received_facts'] . "') as RF on OT . rf_id = RF . rf_id) as b on o. order_id = b . order_id where";
    if($searcharray['selStatus']!="" && $searcharray['stardate']=="" && $searcharray['enddate'] == "" && $searcharray['received_name']=="" && $searcharray['received_no']=="" &&  $searcharray['$received_facts']=="")
     {
         $query .= " status_id = '".$searcharray['selStatus']."'";
     }
 else{
        /*.....................start for start date.......................................*/
        if($searcharray['stardate']!="" && $searcharray['enddate'] == "")
        {
            $query .= " CAST(o.order_date AS DATE)='" . $searcharray['stardate'] . "' and o.status_id = '".$searcharray['selStatus']."'";
        }
        if($searcharray['stardate']!="" && $searcharray['enddate'] == "" && $searcharray['received_name']!="")
        {
            $query .= " CAST(o.order_date AS DATE)='" . $searcharray['stardate'] . "' and o.responsible_person='".$searcharray['received_name']."' and o.status_id = '".$searcharray['selStatus']."'";
        }
        if($searcharray['stardate']!=""  && $searcharray['enddate'] == "" && $searcharray['received_name']!="" && $searcharray['received_no']!="" )
        {
            $query .= " CAST(o.order_date AS DATE)='" . $searcharray['stardate'] . "' and o.responsible_person='".$searcharray['received_name']."' and o.order_uid = '".$searcharray['received_no']."' and o.status_id = '".$searcharray['selStatus']."'";
        }


        /*.....................end for start date.......................................*/

        if($searcharray['stardate']!="" && $searcharray['enddate'] != "")
        {

            $query .= " order_date >= '" . $searcharray['stardate'] . "' AND order_date <= '" . $searcharray['enddate'] . "' and o.status_id = '".$searcharray['selStatus']."'";
        }
        if($searcharray['stardate']!="" && $searcharray['enddate'] != "" && $searcharray['received_name']!="")
        {
            $query .= " order_date >= '" . $searcharray['stardate'] . "' AND order_date <= '" . $searcharray['enddate'] . "' and o.responsible_person='".$searcharray['received_name']."' and o.status_id = '".$searcharray['selStatus']."'";
        }
        if($searcharray['stardate']!="" && $searcharray['enddate'] != ""&& $searcharray['received_name']!="" && $searcharray['received_no']!="" )
        {
            $query .= " order_date >= '" . $searcharray['stardate'] . "' AND order_date <= '" . $searcharray['enddate'] . "' and o.responsible_person='".$searcharray['received_name']."' and o.order_uid = '".$searcharray['received_no']."' and o.status_id = '".$searcharray['selStatus']."'";
        }



        /*.....................end for start date and end date.......................................*/

        /*.....................start received_name...................................................*/
        if($searcharray['received_name']!="")
        {
            $query .= " o.responsible_person='".$searcharray['received_name']."' and o.status_id = '".$searcharray['selStatus']."'";
        }
        if($searcharray['received_name']!="" && $searcharray['received_no']!="")
        {
            $query .= " o.responsible_person='".$searcharray['received_name']."' and o.order_uid = '".$searcharray['received_no']."' and o.status_id = '".$searcharray['selStatus']."'";
        }


        /*.............................................end received_name...........................*/
        /*.............................................start received_no...........................*/
        if($searcharray['received_no']!="")
        {
            $query .= " o.order_uid = '".$searcharray['received_no']."' and o.status_id = '".$searcharray['selStatus']."'";
        }

 }
        /*.............................................end received_no...........................*/
        /*.............................................start selStatus...........................*/

    }
    elseif($searcharray['selStatus']!="" && $searcharray['stardate']=="" && $searcharray['enddate'] == "" && $searcharray['received_name']=="" && $searcharray['received_no']=="" &&  $searcharray['$received_facts']=="")
    {
        $query = "SELECT o.order_id, o.order_uid, o.product_name, CAST(order_date AS DATE) AS order_date, c.company_name, c.client_user_name, o.order_person, o.order_dept, ";
        $query .= "o.order_dept, CAST(receipt_date AS DATE) AS receipt_dated, DATE_FORMAT(receipt_date, '%H:%i') AS receipt_datet, os.os_id, ";
        $query .= "o.layout, o.text_size, o.format, o.divs, o.display_data, o.display_data_qty, o.ad, o.comment, s.sta_descp, s.sta_id, c.client_id, ";
        $query .= "c.address, responsible_person, client_price, order_price, company_sell_price, margin_price, CAST(start_time AS DATE) AS start_timed, ";
        $query .= "DATE_FORMAT(start_time, '%H:%i') AS start_timet, CAST(end_time AS DATE) AS end_timed, DATE_FoRMAT(end_time, '%H:%i') AS end_timet, ";
        $query .= "CAST(delivery_date AS DATE) AS delivery_dated, DATE_FORMAT(delivery_date, '%H:%i') AS delivery_datet ";
        $query .= "FROM order_tbl o ";
        $query .= "JOIN client_tbl c ON o.client_id = c.client_id ";
        $query .= "JOIN status_tbl s ON o.status_id=sta_id ";
        $query .= "JOIN os_tbl os ON os.os_id=o.os_id ";
        $query .= "WHERE ";
        $query .= "o.status_id = '".$searcharray['selStatus']."'";
    }


    else{

        $query = "SELECT o.order_id, o.order_uid, o.product_name, CAST(order_date AS DATE) AS order_date, c.company_name, c.client_user_name, o.order_person, o.order_dept, ";
        $query .= "o.order_dept, CAST(receipt_date AS DATE) AS receipt_dated, DATE_FORMAT(receipt_date, '%H:%i') AS receipt_datet, os.os_id, ";
        $query .= "o.layout, o.text_size, o.format, o.divs, o.display_data, o.display_data_qty, o.ad, o.comment, s.sta_descp, s.sta_id, c.client_id, ";
        $query .= "c.address, responsible_person, client_price, order_price, company_sell_price, margin_price, CAST(start_time AS DATE) AS start_timed, ";
        $query .= "DATE_FORMAT(start_time, '%H:%i') AS start_timet, CAST(end_time AS DATE) AS end_timed, DATE_FoRMAT(end_time, '%H:%i') AS end_timet, ";
        $query .= "CAST(delivery_date AS DATE) AS delivery_dated, DATE_FORMAT(delivery_date, '%H:%i') AS delivery_datet ";
        $query .= "FROM order_tbl o ";
        $query .= "JOIN client_tbl c ON o.client_id = c.client_id ";
        $query .= "JOIN status_tbl s ON o.status_id=sta_id ";
        $query .= "JOIN os_tbl os ON os.os_id=o.os_id ";
        $query .= "WHERE ";
        /*.....................start for start date.......................................*/
        if($searcharray['stardate']!="" && $searcharray['enddate'] == "")
        {
            $query .= " CAST(o.order_date AS DATE)='" . $searcharray['stardate'] . "' and o.status_id = '".$searcharray['selStatus']."'";
        }
        if($searcharray['stardate']!="" && $searcharray['enddate'] == "" && $searcharray['received_name']!="")
        {
            $query .= " CAST(o.order_date AS DATE)='" . $searcharray['stardate'] . "' and o.responsible_person='".$searcharray['received_name']."' and o.status_id = '".$searcharray['selStatus']."'";
        }
        if($searcharray['stardate']!=""  && $searcharray['enddate'] == "" && $searcharray['received_name']!="" && $searcharray['received_no']!="" )
        {
            $query .= " CAST(o.order_date AS DATE)='" . $searcharray['stardate'] . "' and o.responsible_person='".$searcharray['received_name']."' and o.order_uid = '".$searcharray['received_no']."' and o.status_id = '".$searcharray['selStatus']."'";
        }


        /*.....................end for start date.......................................*/

        if($searcharray['stardate']!="" && $searcharray['enddate'] != "")
        {

            $query .= " order_date >= '" . $searcharray['stardate'] . "' AND order_date <= '" . $searcharray['enddate'] . "' and o.status_id = '".$searcharray['selStatus']."'";
        }
        if($searcharray['stardate']!="" && $searcharray['enddate'] != "" && $searcharray['received_name']!="")
        {
            $query .= " order_date >= '" . $searcharray['stardate'] . "' AND order_date <= '" . $searcharray['enddate'] . "' and o.responsible_person='".$searcharray['received_name']."' and o.status_id = '".$searcharray['selStatus']."'";
        }
        if($searcharray['stardate']!="" && $searcharray['enddate'] != ""&& $searcharray['received_name']!="" && $searcharray['received_no']!="" )
        {
            $query .= " order_date >= '" . $searcharray['stardate'] . "' AND order_date <= '" . $searcharray['enddate'] . "' and o.responsible_person='".$searcharray['received_name']."' and o.order_uid = '".$searcharray['received_no']."' and o.status_id = '".$searcharray['selStatus']."'";
        }


        /*.....................end for start date and end date.......................................*/

        /*.....................start received_name...................................................*/
        if($searcharray['received_name']!="")
        {
            $query .= " o.responsible_person='".$searcharray['received_name']."' and o.status_id = '".$searcharray['selStatus']."'";
        }
        if($searcharray['received_name']!="" && $searcharray['received_no']!="")
        {
            $query .= " o.responsible_person='".$searcharray['received_name']."' and o.order_uid = '".$searcharray['received_no']."' and o.status_id = '".$searcharray['selStatus']."'";
        }


        /*.............................................end received_name...........................*/
        /*.............................................start received_no...........................*/
        if($searcharray['received_no']!="")
        {
            $query .= " o.order_uid = '".$searcharray['received_no']."' and o.status_id = '".$searcharray['selStatus']."'";
        }


        /*.............................................end received_no...........................*/
        /*.............................................start selStatus...........................*/

    }


   // echo $query;
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

function updateOrder($data, $mysqli)
{
    $query = "UPDATE order_tbl SET ";
    $query .= "client_id = " . $data['selClient'] . ", address = '" . $data['txtCAddress'] . "', responsible_person = '" . $data['txtResPerson'] . "', order_person = '" . $data['txtOrdPerson'] . "', order_dept = '" . $data['txtDept'] . "', ";
    $query .= "receipt_date = '" . $data['txtOrdDate'] . "', product_name = '" . $data['txtPdName'] . "', client_price = " . $data['txtRecAmt'] . ", order_price = " . $data['txtOdrAmt'] . ", company_sell_price = " . $data['txtCompAmt'] . ", ";
    $query .= "margin_price = " . $data['txtMrgAmt'] . ", os_id = " . $data['selOS'] . ", layout = " . $data['txtLayoutPt'] . ", text_size = " . $data['txtTextPt'] . ", format = " . $data['txtFormat'] . ", divs = " . $data['txtDiv'] . ", display_data = " . $data['selImg'] . ", ";
    $query .= "display_data_qty = " . $data['txtImgPt'] . ", ad = " . $data['txtAdPt'] . ", comment = '" . $data['txtComment'] . "', start_time = '" . $data['txtStartTime'] . "', end_time = '" . $data['txtEndTime'] . "', delivery_date = '" . $data['txtDDate'] . "', status_id = " . $data['selStatus'] . " ";
    $query .= "WHERE order_id = " . $data['hidOrdID'];

    if ($mysqli->query($query)) {
        return true;
    }
    return false;
}