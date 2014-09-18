<?php
// *** include require setting files ***
include_once("lib/ini.setting.php");
include_once("ini.config.php");
include_once("ini.dbstring.php");
include_once("ini.functions.php");

sec_session_start();

include_once("mod.login.php");
include_once("mod.client.php");
include_once("mod.order.php");
include_once("mod.optional.php");
include_once("ctrl.client.php");
include_once("ctrl.order.php");

// check user  authentication
checkSession($_SESSION['sess_user_id']);
checkOrderSession($_SESSION['sess_order_type'],$_SESSION['sess_client']);

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
    <title>Order system</title>
    <style>
        .clientlist_tbl {
            width: 700px;

        }

        .clientlist_tbl th {
            background-color: #cccbcb;
            height: 30px;
        }

        .clientlist_tbl td {
            text-align: center;
            border-bottom: 1px dotted gray;
        }
    </style>
    <script>
        function ConfirmDelete() {
            var x = confirm("Are you sure you want to delete?");
            if (x)
                return true;
            else
                return false;
        }
    </script>
</head>
<body>
<div class="bodyWrapper">
    <?php include_once("inc.header.php"); ?>
    <div class="ctnLeft">
        <?php include_once("inc.search.php"); ?>
    </div>
    <div class="ctnRight">


    </div>
</div>
</body>
</html>