<?php
// *** include require setting files ***
include_once("lib/ini.setting.php");
include_once("ini.config.php");
include_once("ini.dbstring.php");
include_once("ini.functions.php");

sec_session_start();

include_once("mod.login.php");
include_once("mod.order.php");
include_once("mod.client.php");
include_once("mod.optional.php");
include_once("ctrl.order.php");
include_once("ctrl.client.php");
include_once("ctrl.login.php");

// check user authentication
checkSession($_SESSION['sess_user_id']);
checkOrderSession($_SESSION['sess_order_type'], $_SESSION['sess_client']);

$d['sess_order_type'] = $_SESSION['sess_order_type'];
$d['sess_client'] = $_SESSION['sess_client'];

$ordlist = getOrder($d, $db);

$sta = getStatus($db);
$rf = getRf($db);
$os = getOS($db);
$app = getApp($db);

$reload = $_SERVER['PHP_SELF'] . "?tpages=" . $tpages;
$per_page = PERPAGE; // number of results to show per page
$total_results = count($ordlist);
$total_pages = ceil($total_results / $per_page); //total pages we going to have

//-------------if page is setcheck------------------//
$show_page = 1;
if (isset($_GET['page'])) {

    $show_page = $_GET['page']; //it will telles the current page
    if ($show_page == "") {
        $show_page = 1;
    }
    if ($show_page > 0 && $show_page <= $total_pages) {
        $start = ($show_page - 1) * $per_page;
        $end = $start + $per_page;
    } else {
        $start = 1;
        $end = $per_page;
    }
} else {
    // if page isn't set, show first set of results
    $start = 0;
    $end = $per_page;
}
// display pagination
$page = intval($_GET['page']);

$tpages = $total_pages;
if ($page <= 0)
    $page = 1;
?>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <link href="<?php echo CSS; ?>import.css" type="text/css" rel="stylesheet"/>
    <script src="<?php echo JS; ?>jquery.min.js"></script>
    <title>Order List</title>
</head>
<body>
<div class="bodyWrapper">
    <?php include_once("inc.header.php"); ?>
    <div class="ctnLeft">
        <?php include_once("inc.search.php"); ?>
    </div>
    <div class="ctnRight">
        <table class="tblList">
            <colgroup></colgroup>
            <colgroup></colgroup>
            <colgroup></colgroup>
            <colgroup></colgroup>
            <colgroup></colgroup>
            <colgroup></colgroup>
            <colgroup></colgroup>
            <colgroup></colgroup>
            <colgroup></colgroup>
            <colgroup></colgroup>
            <colgroup></colgroup>
            <colgroup></colgroup>
            <colgroup></colgroup>
            <colgroup></colgroup>
            <tr>
                <th><?php echo $odtypelbl; ?>番号</th>
                <th><?php echo $odtypelbl; ?>日時</th>
                <th>部署</th>
                <th>登録者</th>
                <th>クライアント名</th>
                <th>名前</th>
                <th><?php echo $odtypelbl; ?>内容</th>
                <th><?php echo $odtypelbl; ?>金額</th>
                <th>社内売価</th>
                <th>差益額</th>
                <th>納期予定</th>
                <th>開始時間</th>
                <th>終了時間</th>
                <th>状況</th>
            </tr>
            <?php
            /********************************* check and display the search order list*****************************/
            if (isset($_POST['search'])) {
                $stardate = $_POST['startdate'];
                $enddate = $_POST['enddate'];
                $received_name = $_POST['received_name'];
                $received_facts = $_POST['facts'];
                $received_no = $_POST['received_no'];
                $selStatus = $_POST['selStatus'];
                // ............put into array
                $array['stardate'] = $stardate;
                $array['enddate'] = $enddate;
                $array['received_no'] = $received_no;
                $array['received_name'] = $received_name;
                $array['received_facts'] = $received_facts;
                $array['selStatus'] = $selStatus;
                $array['order_type'] = $_SESSION['sess_order_type'];
                $search_result = searchOrder($array, $db);

                if (!empty($search_result)) {
                    for ($i = 0; $i < count($search_result); $i++) {
                        echo "<tr>";
                        echo "<td><a href='" . ROOT . "order_detail.php?ordid=" . $search_result[$i]['order_id'] . "'>" . $search_result[$i]['order_uid'] . "</a></td>";
                        echo "<td>" . $search_result[$i]['order_date'] . "</td>";
                        echo "<td>" . $search_result[$i]['order_dept'] . "</td>";
                        echo "<td>" . $search_result[$i]['responsible_person'] . "</td>";
                        echo "<td>" . $search_result[$i]['company_name'] . "</td>";
                        echo "<td>" . $search_result[$i]['client_user_name'] . "</td>";

                        $rflist = getRfById($search_result[$i]['order_id'], $db);
                        echo "<td>";
                        if (empty($rflist)) {
                            echo "-";
                        } else {
                            for ($a = 0; $a < count($rflist); $a++) {
                                echo $rflist[$a]['rf_descp'] . "<br/>";
                            }
                        }
                        echo "</td>";

                        echo "<td>" . $search_result[$i]['client_price'] . "</td>";
                        echo "<td>" . $search_result[$i]['company_sell_price'] . "</td>";
                        echo "<td>" . $search_result[$i]['margin_price'] . "</td>";

                        $applist = getAppById($search_result[$i]['order_id'], $db);
                        echo "<td>";
                        if (empty($applist)) {
                            echo "-";
                        } else {
                            for ($b = 0; $b < count($applist); $b++) {
                                echo $applist[$b]['app_descp'] . "<br/>";
                            }
                        }
                        echo "</td>";

                        echo "<td>" . $search_result[$i]['start_timed'] . "</td>";
                        echo "<td>" . $search_result[$i]['end_timed'] . "</td>";
                        echo "<td>" . $search_result[$i]['sta_descp'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='13'>No data</td></tr>";
                }
            } //....................Show the order list..........................//
            else {
                if (empty($ordlist)) {
                    echo "<tr><td colspan='13'>No data</td></tr>";
                } elseif (count($ordlist) >= 1) {
                    for ($i = $start; $i < $end; $i++) {
                        if ($i == $total_results) {
                            break;
                        }
                        echo "<tr>";
                        echo "<td><a href='" . ROOT . "order_detail.php?ordid=" . $ordlist[$i]['order_id'] . "'>" . $ordlist[$i]['order_uid'] . "</a></td>";
                        echo "<td>" . $ordlist[$i]['order_date'] . "</td>";
                        echo "<td>" . $ordlist[$i]['order_dept'] . "</td>";
                        echo "<td>" . $ordlist[$i]['responsible_person'] . "</td>";
                        echo "<td>" . $ordlist[$i]['company_name'] . "</td>";
                        echo "<td>" . $ordlist[$i]['client_user_name'] . "</td>";

                        $rflist = getRfById($ordlist[$i]['order_id'], $db);
                        echo "<td>";
                        if (empty($rflist)) {
                            echo "-";
                        } else {
                            for ($a = 0; $a < count($rflist); $a++) {
                                echo $rflist[$a]['rf_descp'] . "<br/>";
                            }
                        }
                        echo "</td>";

                        echo "<td>" . $ordlist[$i]['client_price'] . "</td>";
                        echo "<td>" . $ordlist[$i]['company_sell_price'] . "</td>";
                        echo "<td>" . $ordlist[$i]['margin_price'] . "</td>";

                        $applist = getAppById($ordlist[$i]['order_id'], $db);
                        echo "<td>";
                        if (empty($applist)) {
                            echo "-";
                        } else {
                            for ($b = 0; $b < count($applist); $b++) {
                                echo $applist[$b]['app_descp'] . "<br/>";
                            }
                        }
                        echo "</td>";
                        echo "<td>" . $ordlist[$i]['start_timed'] . "</td>";
                        echo "<td>" . $ordlist[$i]['end_timed'] . "</td>";
                        echo "<td>" . $ordlist[$i]['sta_descp'] . "</td>";
                        echo "</tr>";
                    }
                    echo "<tr><th colspan='13' class='alignCenter'>";
                    echo '<div class="pagination"><ul class="paginate">';
                    if ($total_pages > 1) {
                        echo paginate($reload, $show_page, $total_pages);
                    }
                    echo "</ul></div>";
                    echo "</th></tr>";
                }
            }
            ?>
        </table>
        <?php
        ?>
    </div>
</div>
<script>
    $(document).ready(function () {
        $(".tblList").delegate('td', 'mouseover mouseleave', function (e) {
            if (e.type == 'mouseover') {
                $(this).parent().addClass("hover");
                $("colgroup").eq($(this).index()).addClass("hover");
            }
            else {
                $(this).parent().removeClass("hover");
                $("colgroup").eq($(this).index()).removeClass("hover");
            }
        });
    });
</script>
</body>
</html>