<?php
@session_start();
//turn off PHP compatibility warnings
ini_set("session.bug_compat_warn","off");

$DocType = $_REQUEST['DocType'];

@$sesnLOGGEDPxRID	= $_SESSION["sesnLOGGEDPxRID"];


require_once "wfslib/wfsclass.php";
$clsWalnet = new clsWalnet();

$clsWalnet->UserRowData($sesnLOGGEDPxRID);

echo "<script>location='si.main.whatdoc.php?DocType=$DocType';</script>";
?>