<?php
@session_start();
//turn off PHP compatibility warnings
ini_set("session.bug_compat_warn","off");

#require 'wchensMDX.php'; # unified connection www May 14, 2014
require 'wchensPOS.php';

$mSql = "UPDATE clinix SET 
	POSTranStatus = 9;";
	#echo $mSql;
@mysqli_query($db_athena,$mSql) OR die("$mSql<br>".mysqli_error($db_athena));

#no need to reconnect require 'wchensPOS.php';
?>
<script>location='sales.php';</script>