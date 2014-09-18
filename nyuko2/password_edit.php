<?php
include_once("lib/ini.setting.php");
include_once("ini.config.php");
include_once("ini.dbstring.php");
include_once("mod.admin.php");
include_once("ctrl.admin.php");

?>
<html lang="en-US">
<head>
	<meta charset="utf-8">
   <link href="styles/import.css" rel="stylesheet" type="text/css">
    <script src="<?php echo JS; ?>jquery.min.js"></script>
	<title>Password edit</title>
</head>
<body>
<div id="editform">
	<form action="#" method="post" id="form1">
		<h1 class="p_edit">Get a New Password</h1>
        Enter the information below and we'll send you an email<br>
        with the next steps to get a new password.<br><br>
        Type your email address :
		<br>
		<input type="text" id="email" name="email" class="txt" placeholder="email">
		<br><br>

		<input type="submit" name="add" id="add" value="Submit" class="button"/>

	</form>
</div>
</body>
</html>