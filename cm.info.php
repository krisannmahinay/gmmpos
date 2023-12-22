<?php
@session_start();

include_once 'sys.inc.php';

@$sesnTranRID = $_SESSION['sesnTranRID'] * 1; 

$mSql = "SELECT * FROM credit_memo WHERE TranRID = '$sesnTranRID' AND Deleted=0;";
#echo $mSql;
$mQry = mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql.<br>".mysqli_error($db_wgfinance));
if ($tblQ = $mQry->fetch_object())
{
	$sesnCMRID		= $tblQ->CMRID;
	$sesnCMDate		= $tblQ->CMDate;
	$sesnCMDateTime	= $tblQ->CMDateTime;
	$sesnCMType		= $tblQ->CMType;
	$sesnCMUserRID	= $tblQ->UserRID;
	$sesnCMDiscountApplied	= $tblQ->DiscountApplied;
	$sesnCMNetAmountDue	= $tblQ->NetAmountDue;
	$sesnCMPrinted	= $tblQ->Printed;
}	
else
{
	$_SESSION['sesnTranRID'] = NULL;
}	

@$sesnTranRID = $_SESSION['sesnTranRID'] * 1; 

#echo "<script>alert('$sesnTranRID heyhey!');</script>";
?>