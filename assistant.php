<body onunload="window.opener.popUpClosed();">
<?php
@session_start();
@$sesnHelperBar = $_SESSION['sesnHelperBar'];
$_SESSION['sesnHelperBar'] = ($sesnHelperBar==0)? 1 : 1;
echo "<script>window.close();</script>";
#echo "<script>location='index.php';</script>";
?>
</body>