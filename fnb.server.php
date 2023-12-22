<!DOCTYPE html>
<html>
<head>
<title>SOFTMO TOUCH SYSTEMS</title>
<?php
include_once('htmlhead.php');
echo "<link rel='stylesheet' type='text/css' href='css/fnbtables.css' media='screen' />";
?>
<head>
 
<script type="text/javascript" >
	document.domain='<?=$_SERVER['SERVER_NAME']?>';
	function popUpClosed()
	{
		window.location.reload();
	}
	
	function funcDetectKey(evt)
	{
		switch(evt.keyCode)
		{
			case 27:    //ESCped
				window.close();
		      break;
		}//end of switch	
	}
</script>
</head>
<body onKeyDown='funcDetectKey(event);' onunload="window.opener.popUpClosed();" 
	onload="document.frmORF.txtORNo.focus();">
<?php
@session_start();
//turn off PHP compatibility warnings
ini_set("session.bug_compat_warn","off");

require_once("wfslib/xcls.sales.php");
$clsSALES = new SALES();

include_once('wfslib/WalnetFunctionsPOS.php');
require 'wchensPOS.php';

include_once('sys.inc.php');
if ($sesnLOGGEDPxRID<=0)
{
	echo "<script>window.open('login.php','popLogin');</script>";
}

@$cmdSearch = $_REQUEST['cmdSearch'];
if (isset($cmdSearch))
{
	@$txtORNo = $_REQUEST['txtORNo'];
	$TranRID = $_REQUEST['txtORNo'];
	
	$mSql = "SELECT * FROM possales WHERE TranRID='$txtORNo';";
	$mQry = mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql<br>".mysqli_error($db_wgfinance));
	
	if ($tblOR = $mQry->fetch_object())
	{
		/*
		@mysql_query("DELETE FROM possales;") OR DIE(mysqli_error($db_wgfinance));
		@mysql_query("DELETE FROM order_details_current;") OR DIE(mysqli_error($db_wgfinance));
	
		$mSql = "INSERT INTO possales 
			SELECT * FROM order_master WHERE TranRID=$TranRID;";
		@mysql_query($mSql) OR DIE("$mSql<br>".mysqli_error($db_wgfinance));

		$mSql = "INSERT INTO order_details_current 
			SELECT * FROM order_details WHERE TranRID=$TranRID;";
		@mysql_query($mSql) OR DIE("$mSql<br>".mysqli_error($db_wgfinance));
		
		$clsWalnet->CreateCancelTRANGrab($TranRID);
		*/
		$_SESSION['sesnTranRID'] = $tblOR->TranRID;
		$_SESSION['sesnORViewMode'] = TRUE;
		
		
		echo "<script>window.close();</script>";
	}
	else
	{
		$txtMess = "OR number $txtORNo is not in file!";
		echo "<script>alert('$txtMess');</script>";
	}
}

#require current transasciton to be completed first
include_once('trans.info.fnb.php');

if ($sesnTranRID<=0)
{
	echo "<br><br><h1 align=center>No Transaction Started!<br><br></h1>";
	#echo "<script>window.close();</script>";
}
else
{
$headertext = "Select SERVER";
include_once('head.php');

@$W_readonly = "";
@$W_disabled = "";
if (($sesnSALESTranStatus>0) && ($sesnSALESTranStatus<$clsSALES->TranStatusCANCELLED))
{
    $W_readonly = "readonly";
    $W_disabled = "disabled";
}

#echo "<script>alert('$sesnSALESTableRID');</script>";

echo "<div class='container_16'>";
echo "<div clas='grid_16'>";

$mSql = "SELECT * FROM users WHERE UserType='CLERK' AND Deleted=0
		Order BY UserName";
$mQry = mysqli_query($db_ipadrbg,$mSql) OR DIE("$mSql<br>".mysqli_error($db_ipadrbg));

echo "<table class='table table-condensed'>";
	echo "<tr>";
			
	$mxMaxCols = 3; #number of cols
	$mxCol=0; 
			
	while ($tblUsers = $mQry->fetch_object())
	{
		$mURID = $tblUsers->PxRID;
		
		echo "<th class='wpadd' width='25%' nowrap>";
		echo "<a class='btn btn-primary btn-lg btn-block' style='text-decoration:none'
			href='w8r.assign.php?t8l=$sesnSALESTableRID&w8r=$mURID'>
			$tblUsers->UserName</a>";
		echo "</th>";
	
		if ($mxCol < $mxMaxCols)
		{
			$mxCol++;
		}
		else
		{
			echo "</tr>";
			echo "<tr>";
			$mxCol=0;
		}	
	}
	echo "</tr>";
	
	echo "<tr>";
		echo "<th width='1%' class='text-center' colspan=9>";
		echo "<input class='btn btn-danger btn-lg btn-block' type=button 
			name='buttCLOSE' value='exit' 
			onclick='window.close();'>";
		echo "</th>";
	echo "</tr>";
	
	
echo "</table>";

echo "</div>";
echo "</div>";
}
?>
</body>