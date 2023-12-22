<?php
@session_start();
//turn off PHP compatibility warnings
ini_set("session.bug_compat_warn","off");

@$sesnProcessClnxRID = $_SESSION['sesnProcessClnxRID'];

		require 'wchensMDX.php';
		$mSql = "UPDATE clinix SET 
			POSTranStatus = 9
			WHERE RID = '$sesnProcessClnxRID';";
		#echo $mSql;
		@mysqli_query($db_wgfinance,$mSql) OR die("$mSql<br>".mysqli_error($db_wgfinance));

		require 'wchensPOS.php';
		
		#echo "<script>alert('Processed GRAB to close!');</script>";
?>