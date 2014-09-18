<?php
// *** include require setting files ***
include_once("lib/ini.setting.php");
include_once("ini.config.php");
include_once("ini.dbstring.php");
include_once("ctrl.admin.php");

sec_session_start();

include_once("mod.login.php");
include_once("ctrl.login.php");

// check user role and authentication
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
    <link href="<?php echo CSS; ?>style.css" type="text/css" rel="stylesheet"/>
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
        <form action="#" method="post">
            <table class="tblForm">
                <tr>
                    <td width="200">内容</td>
                    <td><textarea id="comname" name="status" class="txt_style" placeholder="status description"></textarea><br>
                        <span class="error"><?php echo $status_error; ?></span>
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                        <input type="hidden" name="input_var" value="status"/>
                        <input type="submit" id="submit" name="submit" name="status_input" value="保存する">
                    </td>
                </tr>
            </table>
        </form>
        <form action="#" method="post">
            <table class="tblForm">
                <tr>
                    <td width="200">内容</td>
                    <td><textarea id="comname" name="rece_facts" class="txt_style" placeholder="receipt facts description"></textarea><br>
                        <span class="error"><?php echo $rece_error; ?></span>
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                        <input type="hidden" name="input_var" value="receipt"/>
                        <input type="submit" id="submit" name="submit" name="receipt_input" value="保存する">
                    </td>
                </tr>
            </table>
        </form>
        <form action="#" method="post">
            <table class="tblForm">
                <tr>
                    <td width="200">内容</td>
                    <td><textarea id="comname" name="application" class="txt_style" placeholder="application description"></textarea><br>
                        <span class="error"><?php echo $application_error; ?></span>
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                        <input type="hidden" name="input_var" value="app"/>
                        <input type="submit" id="submit" name="submit" name="app_input" value="保存する">
                    </td>
                </tr>
            </table>
        </form>

    </div>
</div>
</body>
</html>