<?php
// *** include require setting files ***
include_once("lib/ini.setting.php");
include_once("ini.config.php");
include_once("ini.dbstring.php");
include_once("ini.functions.php");

sec_session_start();

include_once("mod.order.php");
include_once("mod.login.php");
include_once("mod.optional.php");
include_once("ctrl.order.php");
include_once("ctrl.client.php");
include_once("ctrl.login.php");


// check user authentication
checkSession($_SESSION['sess_user_id']);
checkSession($_SESSION['sess_order_type']);
checkSession($_SESSION['sess_client']);

$sta = getStatus($db);
$rf = getRf($db);
$os = getOS($db);
$app = getApp($db);
$cli = getClient($db);
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
<div class="ctnLeft marginBtm20">
    <?php include_once("inc.search.php"); ?>
</div>
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
                        echo '<option value="' . $cli[$d]['client_id'] . '">' . $cli[$d]['company_name'] . '</option>';
                    }
                    echo '</select> ';
                    ?>
                </td>
                <td>
                    <label class="lbl"><?php echo $odtypelbl; ?>ID</label>
                    <label>
                        <?php
                            $id = autoGen();
                            echo $id;
                        ?>
                    </label>
                </td>
            </tr>
            <tr>
                <td>
                    <label class="lbl">部署</label>
                    <input type="text" name="txtDept" class="txt" placeholder="部署名" />
                </td>
                <td>
                    <label class="lbl">登録者</label>
                    <input type="text" name="txtResPerson" class="txt" value="<?php echo $_SESSION['sess_username']; ?>"/>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <label class="lbl"><?php echo $odtypelbl; ?>者</label>
                    <input type="text" name="txtOrdPerson" class="txt halfWidth" value="<?php echo $_SESSION['sess_username']; ?>"/>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <label class="lbl">得意先名</label>
                    <input type="text" name="txtCAddress" class="txt halfWidth" value=""/>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <label class="lbl">品名</label>
                    <input type="text" name="txtPdName" class="txt halfWidth" value=""/>
                </td>
            </tr>
            <tr>
                <td>
                    <label class="lbl">開始時間・終了時間</label>
                    <input type="text" name="txtStartTimeD" id="txtStartTimeD" class="txt date" value="<?php echo date("Y-m-d"); ?>"/>
                    <input type="text" name="txtStartTimeT" class="txt time" value="<?php echo date("H:i"); ?>">
                    ⇒
                    <input type="text" name="txtEndTimeD" id="txtEndTimeD" class="txt date" value="<?php echo date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 5, date('Y'))); ?>"/>
                    <input type="text" name="txtEndTimeT" class="txt time" value="00:00"/>
                </td>
                <td>
                    <label class="lbl">納期予定</label>
                    <input type="text" name="txtDDateD" id="txtDDateD" class="txt date" value="<?php echo date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 6, date('Y'))); ?>"/>
                    <input type="text" name="txtDDateT" class="txt time" value="<?php echo date('H:i', mktime(0, 0, 0, date('m'), date('d') + 6, date('Y'))); ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label class="lbl">登録日時</label>
                    <label><?php echo date("Y-m-d H:i"); ?></label>
                </td>

                <td>
                    <label class="lbl"><?php echo $odtypelbl; ?>予定日</label>
                    <input type="text" name="txtOrdDateD" id="txtOrdDateD" class="txt date" value="<?php echo date("Y-m-d"); ?>"/>
                    <input type="text" name="txtOrdDateT" class="txt time" value="<?php echo date("H:i"); ?>"/>
                </td>
            </tr>
            <tr>
                <?php
                if ($_SESSION['sess_order_type'] == 1) {
                    echo "<td colspan='2'>";
                    echo "<label class='lbl'>".$odtypelbl."金額</label>";
                    echo "<input type='text' name='txtOdrAmt' class='txt' value='0'/>";
                    echo "<input type='hidden' name='txtRecAmt' value=0 />";
                    echo "</td>";
                } elseif ($_SESSION['sess_order_type'] == 2) {
                    echo "<td colspan='2'>";
                    echo "<label class='lbl'>".$odtypelbl."金額</label>";
                    echo "<input type='text' name='txtRecAmt' class='txt' value='0'/>";
                    echo "<input type='hidden' name='txtOdrAmt' value=0 />";
                    echo "</td>";
                }
                ?>
            </tr>
            <tr>
                <td>
                    <label class="lbl">社内売価</label>
                    <input type="text" name="txtCompAmt" class="txt" value="0"/>
                </td>

                <td>
                    <label class="lbl">差益額</label>
                    <input type="text" name="txtMrgAmt" class="txt" value="0"/>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <label class="lbl"><?php echo $odtypelbl; ?>内容</label>
                    <?php
                    for ($i = 0; $i < 4; $i++) {
                        echo '<select name="selRf[]" class="txt">';
                        echo '<option value=0 >-</option>';
                        for ($d = 0; $d < count($rf); $d++) {
                            echo '<option value="' . $rf[$d]['rf_id'] . '">' . $rf[$d]['rf_descp'] . '</option>';
                        }
                        echo '</select> ';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <label class="lbl">OS (Operating System)</label>
                    <select name="selOS" class="txt">
                        <?php
                        for ($d = 0; $d < count($os); $d++) {
                            echo '<option value="' . $os[$d]['os_id'] . '">' . $os[$d]['os_descp'] . '</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <label class="lbl">アプリケーション</label>
                    <?php
                    for ($i = 0; $i < 3; $i++) {
                        echo '<select name="selApp[]" class="txt">';
                        echo '<option value=0>-</option>';
                        for ($d = 0; $d < count($app); $d++) {
                            echo '<option value="' . $app[$d]['app_id'] . '">' . $app[$d]['app_descp'] . '</option>';
                        }
                        echo '</select> ';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <label class="lbl">レイアウト原稿</label>
                    <input type="text" name="txtLayoutPt" class="txt" value="0"/> 点
                </td>
                <td>
                    <label class="lbl">テキスト原稿</label>
                    <input type="text" name="txtTextPt" class="txt" value="0"/> 点
                </td>
            </tr>
            <tr>
                <td>
                    <label class="lbl">フォーマット</label>
                    <input type="text" name="txtFormat" class="txt" value="0"/>
                </td>
                <td>
                    <label class="lbl">流用データ</label>
                    <input type="text" name="txtDiv" class="txt" value="0"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label class="lbl">画像データ</label>
                    <select name="selImg" class="txt">
                        <option value="1">あり</option>
                        <option value="0">なし</option>
                    </select>
                    <input type="text" name="txtImgPt" class="txt" style="width: 30px;" value="0"/>
                </td>
                <td>
                    <label class="lbl">出稿データ</label>
                    <input type="text" name="txtAdPt" class="txt" value="0"/> 点
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <textarea name="txtComment" class="txt fullWidth" rows="10" placeholder="Comments"></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="hidden" value="<?php echo $id; ?>" name="hidOrdID"/>
                    <input type="hidden" value="<?php echo $_SESSION['sess_order_type']; ?>" name="hidOrdType"/>
                    <input type="submit" value="登録" name="orderAdd"/>
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