<?php
@session_start();

include_once('wfslib/WalnetFunctionsPOS.php');
include_once('sys.inc.php');

if ($sesnLOGGEDPxRID<=0)
{
	echo "<script>alert('Please Log-in!');</script>";
	echo "<script>window.close();</script>";
}
include_once('kick.php');
?>