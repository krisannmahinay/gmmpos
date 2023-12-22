<?php
@session_start();
$_SESSION['sesnIDept'] = $_REQUEST['idept'];
$_SESSION['sesnIDeptButtonScheme'] = $_REQUEST['ischm'];
echo "<script>location='fnbindex.php';</script>";
?>