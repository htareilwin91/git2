<?php
// *** include require setting files ***
include_once("lib/ini.setting.php");
include_once("ini.config.php");
include_once("ini.dbstring.php");
include_once("ini.functions.php");

sec_session_start();

include_once("mod.client.php");
include_once("mod.login.php");
include_once("ctrl.client.php");
include_once("ctrl.login.php");

// check user  authentication
checkSession($_SESSION['sess_user_id']);
// get client list
$cl = getClient($db);
?>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <link href="<?php echo CSS; ?>import.css" type="text/css" rel="stylesheet"/>
    <link href="<?php echo CSS; ?>style.css" type="text/css" rel="stylesheet"/>
    <script src="<?php echo JS; ?>jquery.min.js"></script>
    <title>Client</title>
</head>
<body>
<div class="wrapper alignCenter">
    <a class="ctnLogo2 marginBtm20 marginTop20">RubberSoul</a>

    <div class="noti marginBtm20 marginTop20 sizeFontBig bold">
        会社名と行くページを選択してください。
    </div>
    <div style="float:left;width:100%;">
        <form name="order_form" class="" method="post" action="#">
            <table class="fullWidth">
                <tr>
                    <td align="center">
                        <?php
                        echo '<select name="client" class="txt">';
                        for ($d = 0; $d < count($cl); $d++) {
                            echo '<option value="' . $cl[$d]['client_id'] . '">' . $cl[$d]['company_name'] . '</option>';
                        }
                        echo '</select> ';
                        ?>
                        <br/>
                        <select name="order_type" size="" class="txt marginTop20">
                            <option value="1">発注</option>
                            <option value="2">受注</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="center"><input type="submit" name="continue" value="次へ" class="btn marginTop20"></td>
                </tr>
            </table>
        </form>
    </div>
</div>
</body>
</html>