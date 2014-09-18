<?php
// *** include require setting files ***
include_once("lib/ini.setting.php");
include_once("ini.config.php");
include_once("ini.dbstring.php");
include_once("ini.functions.php");

sec_session_start();

include_once("mod.login.php");
include_once("mod.optional.php");
include_once("mod.order.php");
include_once("mod.client.php");
include_once("ctrl.optional.php");
include_once("ctrl.order.php");
include_once("ctrl.client.php");
include_once("ctrl.login.php");

// check user  authentication
checkSession($_SESSION['sess_user_id']);

$sta = getStatus($db);
$rf = getRf($db);
$os = getOS($db);
$app = getApp($db);
?>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <link href="<?php echo CSS; ?>import.css" type="text/css" rel="stylesheet"/>
    <script src="<?php echo JS; ?>jquery.min.js"></script>
    <title>input page</title>
    <style>
        .error {
            color: red;
        }
    </style>
</head>
<body>
<div class="bodyWrapper">
    <?php include_once("inc.header.php"); ?>
    <div class="ctnLeft">
        <?php include_once("inc.search.php"); ?>
    </div>
    <div class="ctnRight">

        <table class="tblForm">
            <tr>
                <td>
                    <form action="#" method="post">
                        <span class="lbl">状況項目追加</span>
                        <input type="text" name="status" class="txt halfWidth" placeholder="状況項目"/><br/>
                        <span class="error"><?php echo $status_error; ?></span>
                        <input type="hidden" name="input_var" value="status"/>
                        <input type="submit" id="submit" name="submit" name="status_input" value="保存する" class="marginTop20">
                    </form>
                </td>

                <td>
                    <form action="#" method="post">
                        <span class="lbl">発注内容項目追加</span>
                        <input type="text" id="comname" name="rece_facts" class="txt halfWidth"
                               placeholder="発注内容項目"><br/>
                        <span class="error"><?php echo $rece_error; ?></span>
                        <input type="hidden" name="input_var" value="receipt"/>
                        <input type="submit" id="submit" name="submit" name="receipt_input" value="保存する" class="marginTop20">
                    </form>
                </td>
                <td>
                    <form action="#" method="post">
                        <span class="lbl">アプリケーション項目追加</span>
                        <input type="text" id="comname" name="application" class="txt halfWidth"
                               placeholder="アプリケーション項目"><br/>
                        <span class="error"><?php echo $application_error; ?></span>
                        <input type="hidden" name="input_var" value="app"/>
                        <input type="submit" id="submit" name="submit" name="app_input" value="保存する" class="marginTop20">
                    </form>
                </td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>