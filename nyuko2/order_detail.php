<?php
// *** include require setting files ***
include_once("lib/ini.setting.php");
include_once("ini.config.php");
include_once("ini.dbstring.php");
include_once("ini.functions.php");

sec_session_start();

include_once("mod.login.php");
include_once("mod.order.php");
include_once("mod.optional.php");
include_once("ctrl.order.php");
include_once("ctrl.client.php");
include_once("ctrl.login.php");

// check user  authentication
checkSession($_SESSION['sess_user_id']);

if (isset($_GET['ordid'])) {
    $oid = $_GET['ordid'];
    $orddetail = getOrderById($oid, $db);
    $rf = getRf($db);
    $odrf = getRfById($oid, $db);
    $os = getOS($db);
    $app = getApp($db);
    $odapp = getAppById($oid, $db);
    $cli = getClient($db);
    $sta = getStatus($db);
} else {
    header("location: " . ROOT . "order_list.php");
    exit;
}
?>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <link href="<?php echo CSS; ?>import.css" type="text/css" rel="stylesheet"/>
    <script src="<?php echo JS; ?>jquery.min.js"></script>
    <script src="<?php echo JS; ?>datepickr.js"></script>
    <script src="<?php echo JS; ?>jquery.timepicker.min.js"></script>
    <title>Order List</title>
</head>
<body>
<div class="bodyWrapper">
    <?php include_once("inc.header.php"); ?>
    <div class="ctnRight">
        <form action="#" method="post">
            <table class="tblForm">
                <tr>
                    <td>
                        <label class="lbl">クライアント</label>
                        <?php
                            echo '<select name="selClient" class="txt">';
                            echo '<option value=0 >-</option>';
                            for ($d = 0; $d < count($cli); $d++) {
                                if ($cli[$d]['client_id'] == $orddetail['client_id']) {
                                    $selected = "selected";
                                }
                                echo '<option value="' . $cli[$d]['client_id'] . '" ' . $selected . '>' . $cli[$d]['company_name'] . '</option>';
                                $selected = "";
                            }
                            echo '</select> ';
                        ?>
                    </td>
                    <td>
                        <label class="lbl"><?php echo $odtypelbl; ?>ID</label>
                        <label><?php echo $orddetail['order_uid']; ?></label>
                    </td>
                    <td>
                        <label class="lbl">状況</label>
                        <select name="selStatus">
                            <?php
                                for($i = 0; $i < count($sta); $i++) {
                                    if($orddetail['sta_id'] == $sta[$i]['sta_id']) {
                                        $selected = "selected";
                                    }
                                    echo "<option value='".$sta[$i]['sta_id']."' ".$selected.">".$sta[$i]['sta_descp']."</option>";
                                    $selected = "";
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class="lbl">部署</label>
                        <input type="text" name="txtDept" class="txt" value="<?php echo $orddetail['order_dept']; ?>" />
                    </td>
                    <td colspan="2">
                        <label class="lbl">登録者</label>
                        <input type="text" name="txtResPerson" class="txt"
                               value="<?php echo $orddetail['responsible_person']; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <label class="lbl"><?php echo $odtypelbl; ?>者</label>
                        <input type="text" name="txtOrdPerson" class="txt halfWidth" value="<?php echo $orddetail['order_person']; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <label class="lbl">得意先名</label>
                        <input type="text" name="txtCAddress" class="txt halfWidth"
                               value="<?php echo $orddetail['address']; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <label class="lbl">品名</label>
                        <input type="text" name="txtPdName" class="txt halfWidth"
                               value="<?php echo $orddetail['product_name']; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class="lbl">開始時間・終了時間</label>
                        <input type="text" name="txtStartTimeD" id="txtStartTimeD" class="txt date"
                               value="<?php echo $orddetail['start_timed']; ?>"/>
                        <input type="text" name="txtStartTimeT" class="txt time"
                               value="<?php echo $orddetail['start_timet']; ?>">
                        ⇒
                        <input type="text" name="txtEndTimeD" id="txtEndTimeD" class="txt date"
                               value="<?php echo $orddetail['end_timed']; ?>"/>
                        <input type="text" name="txtEndTimeT" class="txt time"
                               value="<?php echo $orddetail['end_timet']; ?>"/>
                    </td>
                    <td colspan="2">
                        <label class="lbl">納期予定</label>
                        <input type="txt" name="txtDDateD" id="txtDDateD" class="txt date"
                               value="<?php echo $orddetail['delivery_dated']; ?>"/>
                        <input type="txt" name="txtDDateT" class="txt time"
                               value="<?php echo $orddetail['delivery_datet']; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class="lbl">登録日時</label>
                        <label><?php echo $orddetail['order_date']; ?></label>
                    </td>

                    <td colspan="2">
                        <label class="lbl"><?php echo $odtypelbl; ?>予定日</label>
                        <input type="txt" name="txtOrdDateD" id="txtOrdDateD" class="txt date"
                               value="<?php echo $orddetail['receipt_dated']; ?>"/>
                        <input type="txt" name="txtOrdDateT" class="txt time"
                               value="<?php echo $orddetail['receipt_datet']; ?>"/>
                    </td>
                </tr>
                <tr>
                    <?php
                    if ($_SESSION['sess_order_type'] == 1) {
                        echo "<td colspan='3'>";
                        echo "<label class='lbl'>".$odtypelbl."金額</label>";
                        echo "<input type='text' name='txtOdrAmt' class='txt' value='".$orddetail['order_price']."'/>";
                        echo "<input type='hidden' name='txtRecAmt' value=0 />";
                        echo "</td>";
                    } elseif ($_SESSION['sess_order_type'] == 2) {
                        echo "<td colspan='3'>";
                        echo "<label class='lbl'>".$odtypelbl."金額</label>";
                        echo "<input type='text' name='txtRecAmt' class='txt' value='".$orddetail['client_price']."'/>";
                        echo "<input type='hidden' name='txtOdrAmt' value=0 />";
                        echo "</td>";
                    }
                    ?>
                </tr>
                <tr>
                    <td>
                        <label class="lbl">社内売価</label>
                        <input type="txt" name="txtCompAmt" class="txt"
                               value="<?php echo $orddetail['company_sell_price']; ?>"/>
                    </td>

                    <td colspan="2">
                        <label class="lbl">差益額</label>
                        <input type="txt" name="txtMrgAmt" class="txt"
                               value="<?php echo $orddetail['margin_price']; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <label class="lbl"><?php echo $odtypelbl; ?>内容</label>
                        <?php
                        for ($i = 0; $i < 4; $i++) {
                            echo '<select name="selRf[]" class="txt">';
                            echo '<option value=0 >-</option>';
                            for($e = 0; $e < count($rf); $e++) {
                                if(!array_key_exists($i, $odrf)) {
                                    $selected = "";
                                }

                                if($rf[$e]['rf_id'] == $odrf[$i]['rf_id']) {
                                    $selected = "selected";
                                }else {
                                    $selected = "";
                                }

                                echo '<option value="' . $rf[$e]['rf_id'] . '" '.$selected.'>' . $rf[$e]['rf_descp'] . '</option>';
                                $selected = "";
                            }
                            echo '</select> ';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <label class="lbl">OS (Operating System)</label>
                        <select name="selOS" class="txt">
                            <?php
                            for ($d = 0; $d < count($os); $d++) {
                                if($orddetail['os_id'] == $os[$d]['os_id']) {
                                    $selected = "selected";
                                }
                                echo '<option value="' . $os[$d]['os_id'] . '" '.$selected.'>' . $os[$d]['os_descp'] . '</option>';
                                $selected = "";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <label class="lbl">アプリケーション</label>
                        <?php
                        for ($i = 0; $i < 3; $i++) {
                            echo '<select name="selApp[]" class="txt">';
                            echo '<option value=0>-</option>';
                            for ($d = 0; $d < count($app); $d++) {
                                if(!array_key_exists($i, $odapp)) {
                                    $selected = "";
                                }

                                if($app[$d]['app_id'] == $odapp[$i]['app_id']) {
                                    $selected = "selected";
                                }else {
                                    $selected = "";
                                }

                                echo '<option value="' . $app[$d]['app_id'] . '" '.$selected.'>' . $app[$d]['app_descp'] . '</option>';
                                $selected = "";
                            }
                            echo '</select> ';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class="lbl">レイアウト原稿</label>
                        <input type="txt" name="txtLayoutPt" class="txt" value="<?php echo $orddetail['layout']; ?>"/> 点
                    </td>
                    <td colspan="2">
                        <label class="lbl">テキスト原稿</label>
                        <input type="txt" name="txtTextPt" class="txt" value="<?php echo $orddetail['text_size']; ?>"/> 点
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class="lbl">フォーマット</label>
                        <input type="txt" name="txtFormat" class="txt" value="<?php echo $orddetail['format']; ?>"/>
                    </td>
                    <td colspan="2">
                        <label class="lbl">流用データ</label>
                        <input type="txt" name="txtDiv" class="txt" value="<?php echo $orddetail['divs']; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class="lbl">画像データ</label>
                        <select name="selImg" class="txt">
                            <option value="1" <?php echo ($orddetail['display_data']==1)?"selected":""; ?>>あり</option>
                            <option value="0" <?php echo ($orddetail['display_data']==0)?"selected":""; ?>>なし</option>
                        </select>
                        <input type="text" name="txtImgPt" class="txt" style="width: 30px;" value="<?php echo $orddetail['display_data_qty']; ?>"/>
                    </td>
                    <td colspan="2">
                        <label class="lbl">出稿データ</label>
                        <input type="txt" name="txtAdPt" class="txt" value="<?php echo $orddetail['ad']; ?>"/> 点
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <textarea name="txtComment" class="txt fullWidth" rows="10" placeholder="Comments"><?php echo $orddetail['comment']; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <input type="hidden" value="<?php echo $orddetail['order_id']; ?>" name="hidOrdID"/>
                        <input type="hidden" value="<?php echo $_SESSION['sess_order_type']; ?>" name="hidOrdType"/>
                        <input type="submit" value="登録" name="orderUpdate"/>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<script>
    $(document).ready(function () {
        new datepickr('txtOrdDateD', { dateFormat: "Y-m-d" });
        new datepickr('txtStartTimeD', { dateFormat: "Y-m-d" });
        new datepickr('txtEndTimeD', { dateFormat: "Y-m-d" });
        new datepickr('txtDDateD', { dateFormat: "Y-m-d" });

        $('input[name=txtStartTimeT], input[name=txtEndTimeT], input[name=txtDDateT], input[name=txtOrdDateT]').timepicker({ 'timeFormat': 'H:i' });

        $('select[name=selImg]').on('change', function () {
            if ($(this).val() == 0) {
                $('input[name=txtImgPt]').val(0);
            }
        });

        $('input[name=txtImgPt]').keyup(function () {
            if ($('select[name=selImg]').val() == 0) {
                $(this).val(0);
                return false;
            }
        });
    });
</script>
</body>
</html>