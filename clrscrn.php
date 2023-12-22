<?php
#Class written by: Walter Frederick Seballos, Jan 13m 2009
#use without permission is allowed but please give credit to the author by mentioning on your documents
class clsXCAM
{
	function UserXCam()
	{
		@session_start();
		require 'wchensPOS.php';
		
		$today = wfsGetSysDate(0);
		$_SESSION["sesnLOGYO"] = NULL;
		$_SESSION["sesnLOGYOgid"] = NULL;
		
		$mSql = "SELECT * FROM camper WHERE Active = 1;";
		$mQry = mysqli_query($db_xcam, $mSql) OR DIE(mysqli_error($db_xcam));
		if ($tblXCAM = $mQry->fetch_object())
		{
			$mxScamDate = $tblXCAM->ScamDate;
			$mxWarner 	= $tblXCAM->Warner;
			$mxDaysDue = datediff('d', $today, $mxScamDate, FALSE);
			$_SESSION["sesnLOGYO"] = $mxDaysDue;
			$_SESSION["sesnLOGYOgid"] = NULL;

			if ($mxDaysDue <= 0)
			{
				#echo "<script>window.open('clrscrn.clr.php?ms=nagid');</script>";
				$_SESSION["sesnLOGYOgid"] = "nagid";
			}	
			elseif ($mxDaysDue <= $mxWarner)
			{
				#echo "<script>window.open('clrscrn.clr.php?ms=lapit');</script>";
				$_SESSION["sesnLOGYOgid"] = "lapit";
			}	
		}
	}
	
	function UserXCamed()
	{	
		@session_start();
		if (isset($_SESSION["sesnLOGYOgid"]))
		{
			if ($_SESSION["sesnLOGYOgid"] == "nagid")
			echo "<script>window.open('clrscrn.clr.php');</script>";
		}	
	}
}
?>