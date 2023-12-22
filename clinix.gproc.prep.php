<?php
@session_start();

$_SESSION['sesnProcessClnxRID'] = $_REQUEST['crid'];
echo "<script>location='clinix.gproc.php';</script>";
?>