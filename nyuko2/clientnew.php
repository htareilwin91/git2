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

// check user  authentication
checkSession($_SESSION['sess_user_id']);
checkOrderSession($_SESSION['sess_order_type'], $_SESSION['sess_client']);

$rf = getRf($db);
$os = getOS($db);
$app = getApp($db);

$showclient = getClient($db);
?>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <link href="<?php echo CSS; ?>import.css" type="text/css" rel="stylesheet"/>
    <link href="<?php echo CSS; ?>style.css" type="text/css" rel="stylesheet"/>
    <script src="<?php echo JS; ?>jquery.min.js"></script>
    <title>Order List</title>
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
        <form action="#" method="post">
            <table class="tblForm">
                <tr>
                    <td>
                        <span class="lbl">会社名</span>
                        <input type="text" id="comname" name="comname" class="txt_style"><br/>
                        <span class="error"><?php echo $name_error; ?></span>
                    </td>
                    <td>
                        <span class="lbl">担当者名</span>
                        <input type="text" id="c_username" name="c_username" class="txt_style"><br/>
                        <span class="error"><?php echo $user_name_error; ?></span>
                    </td>
                    <td>
                        <span class="lbl">住所（会社）</span>
                        <textarea id="add" name="add" class="txt_style"></textarea><br>
                        <span class="error"><?php echo $add_error; ?></span>
                    </td>
                </tr>
                <tr>

                    <td colspan="3"><input type="submit" id="submit" name="submit" value="保存する"></td>
                </tr>
            </table>
        </form>
    </div>
    <div class="ctnRight">
        <table class="tblList">
            <tr>
                <th>クライアントID</th>
                <th>会社名</th>
                <th>担当者名</th>
                <th>住所</th>
                <th>削除</th>
            </tr>
            <?php
            if (empty($showclient)) {
                echo "<tr><td colspan='5'>No data</td></tr>";
            } else {
                for ($r = 0; $r < count($showclient); $r++) {
                    echo "<tr>";
                    echo "<td>" . $showclient[$r]['client_id'] . "</td>";
                    echo "<td>" . $showclient[$r]["company_name"] . "</td>";
                    echo "<td>" . $showclient[$r]["client_user_name"] . "</td>";
                    echo "<td>" . $showclient[$r]["address"] . "</td>";
                    echo "<td><a href=" . ROOT . "clientnew.php?cid=" . $showclient[$r]['client_id'] . " onclick='javascript:confirm('Are you sure you want to delete this record ?');'>削除</a></td>";
                    echo "</tr>";
                }
            }
            ?>
        </table>
    </div>
</div>
</body>
</html>