<?php
$odtypelbl = orderType($_SESSION['sess_order_type']);
?>
<div class="ctnHeader">
    <a class="ctnLogo" href="<?php echo ROOT; ?>order_list.php">RubberSoul</a>
    <ul class="lstMain paddingLft20 marginleft20">
        <li><a href="<?php echo ROOT; ?>clientnew.php">クライアント登録</a></li>
        <li><a href="<?php echo ROOT; ?>order.php">発注新規登録</a></li>
        <li><a href="<?php echo ROOT; ?>order_list.php">発注一覧</a></li>
        <li><a href="<?php echo ROOT; ?>order_history.php">過去発注一覧</a></li>
        <li><a href="<?php echo ROOT; ?>optional.php">オプショナル</a></li>
    </ul>
    <ul class="lstLogout">
        <li><a href="#">Welcome ! <?php echo $_SESSION['sess_username']; ?></a></li>
        <li><a href="controller/ctrl.login.php?cmd=logout" class="btnLogout bold" href="#">Logout</a></li>
    </ul>
</div>
<div class="ctnTitle marginBtm20">
    <?php
    $compName = getClientById($_SESSION['sess_client'], $db);
    echo "<label style='font-size: 130% !important;font-weight:bold;'>" . $compName['company_name'] . " " . orderType($_SESSION['sess_order_type']) . "ページ</label>";
    ?>
</div>
