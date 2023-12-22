<head>
<?php
include_once('htmlhead.php');
?>
<script type="text/javascript" >
	document.domain='<?=$_SERVER['SERVER_NAME']?>';
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
<body onload="moveTo(10,100); resizeTo(800,350); document.frmALog.txtUser.focus()"
 onunload="window.opener.popUpClosed();" autocomplete='off'
 onKeyDown='funcDetectKey(event);'
 >
<?php
@session_start();

include_once('sys.inc.php');
require('wchensPOS.php');

@$sesnAUTHORIZEMasterRID 	= $_SESSION['sesnAUTHORIZEMasterRID'];
@$sesnAUTHORIZEChildRID		= $_SESSION['sesnAUTHORIZEChildRID'];
@$sesnAUTHORIZETrnTYP		= $_SESSION['sesnAUTHORIZETrnTYP'];

@$cmdApprove = $_REQUEST['cmdApprove'];
if (isset($cmdApprove))
{
	$txtUser = $_REQUEST['txtUser'];
	$txtPWD = md5($_REQUEST['txtPWD']);
	
	$mSql = "SELECT * FROM users WHERE 
		UserName='$txtUser' AND PassWD='$txtPWD';";
	$mQry = mysqli_query($db_ipadrbg,$mSql) OR DIE("Auth Log<br>".mysqli_query($db_ipadrbg));
	if ($tblUser = $mQry->fetch_object())
	{
		#$TranStatus = GetTransInfo($ApprvTranRID, 8);
		#$mUserRID = $tblUser->UserRID;
		
		#$#mSql = "UPDATE approval SET ApprovedBy = '$mUserRID', Approved=1;";
		#@mysql_query($mSql) OR DIE("UPDATE Auth Log<br>".mysql_query());
		
		#if ($TranStatus<9) $mVC = 7; #not yet closed, cancelled
		#if ($TranStatus>=9) $mVC = 8; #closed, void
		#echo "<script>alert('$sesnAUTHORIZETrnTYP');</script>";

		if ($sesnAUTHORIZETrnTYP == "VOID DISCOUNT") 	VoidOrderDetail($ApprvWhatRID, $mUserRID, $ApprvTranRID);
		if ($sesnAUTHORIZETrnTYP == "VOID SALES CHARGES") 	VoidOrderDetail($ApprvWhatRID, $mUserRID, $ApprvTranRID);		
		if ($sesnAUTHORIZETrnTYP == "VOID PAYMENT") 	VoidPaymentDetail($ApprvWhatRID, $mUserRID, $ApprvTranRID);		
		if ($sesnAUTHORIZETrnTYP == "CANCEL RECEIVING") CancelizeReceivingReport($ApprvWhatRID, $mUserRID);
		if ($sesnAUTHORIZETrnTYP == "VOID RECEIVE ITEM") VoidReceivingRecord($ApprvWhatRID, $mUserRID, $ApprvTranRID);
		if ($sesnAUTHORIZETrnTYP == "EDIT RECEIVING") AllowEditReceiving($ApprvWhatRID, $mUserRID);
		
		# depre: if ($sesnAUTHORIZETrnTYP == "VOID TRANSFER ITEM") VoidTransferRecord($ApprvWhatRID, $mUserRID, $ApprvTranRID);
		if ($sesnAUTHORIZETrnTYP == "APPROVAL for CANCEL/VOID TRAN#") CancellizeOrder($sesnAUTHORIZEMasterRID, $tblUser->PxRID);				
		if ($sesnAUTHORIZETrnTYP == "APPROVAL for Entry Cancellation") VoidOrderDetail($sesnAUTHORIZEMasterRID, $tblUser->PxRID);		
		if ($sesnAUTHORIZETrnTYP == "APPROVAL to Cancel Receiving Report") CancelizeRECREP($sesnAUTHORIZEMasterRID, $tblUser->PxRID);		
		if ($sesnAUTHORIZETrnTYP == "APPROVAL to Cancel RMA") CancelizeRMA($sesnAUTHORIZEMasterRID, $tblUser->PxRID);		
		if ($sesnAUTHORIZETrnTYP == "APPROVAL to Cancel STOCK Transfer") CancelizeStockTransfer($sesnAUTHORIZEMasterRID, $tblUser->PxRID);		
		if ($sesnAUTHORIZETrnTYP == "CANCEL PO") CancelPO($sesnAUTHORIZEMasterRID, $tblUser->PxRID);
		if ($sesnAUTHORIZETrnTYP == "APPROVAL of Purchase Order") ApprovePO($sesnAUTHORIZEMasterRID, $tblUser->PxRID);
		if ($sesnAUTHORIZETrnTYP == "SEND PO") SendPO($sesnAUTHORIZEMasterRID, $tblUser->PxRID);
		
		echo "<script>window.close();</script>";
	}	
	else
	{
		echo "<script>alert('INVALID APPROVAL AUTHENTICATION, Please try again.');</script>";
	}
}

echo "<div class='container_16'>";
echo "<div clas='grid_16'>";

echo "<form name='frmALog' method='POST' action='authorizelog.php' autocomplete=off>";

echo "<table width='80%' border=0>";
echo "<tr>";
	$headertext="AUTHORIZATION LOG";
	echo "<td colspan=2>";
	include_once('head.php');
	echo "</td>";
echo "</tr>";

echo "<tr>";
echo "<th class='wtitle' colspan=1>Approval Type: </th>";
echo "<th class='total' colspan=9>". $sesnAUTHORIZETrnTYP ." ". $sesnAUTHORIZEMasterRID ."</th>";
echo "</tr>";

echo "<tr>";
echo "<td class='text-right'>User Id &nbsp;&nbsp; </td><td><input type='text' name='txtUser' value=''></td>";
echo "</tr>";

echo "</tr>";
echo "<td class='text-right'>Password &nbsp;&nbsp; </td><td><input type='password' name='txtPWD' value=''></td>";
echo "</tr>";
echo "<tr>";
echo "<th class='text-center' colspan='2'>
<input class='btn btn-info' type='submit' name='cmdApprove' value='approve'>
<input class='btn btn-danger' type='button' name='cmdCancel' value='exit' onclick='window.close();'>
</th>";

echo "</tr>";
echo "</table>";
echo "</form>";

echo "</div>";
echo "</div>";


function CancellizeOrder($ApprvTranRID, $ABy)
{
	include_once('wfslib/WalnetFunctionsPOS.php');
	require('wchensPOS.php');
	// June 18, 2016
	// Charged Transaction Cannot be Cancelled
	// off-set it using payment to cancell
	// Button Cancel was disabled form view

	// FIRST, Get Original Status before Cancellation

	$_SESSION['sesnTranRID'] = $ApprvTranRID;
	@$sesnTranRIDReversal = $_SESSION['sesnTranRIDReversal'] * 1;

	// 2016 Juy 10
	// Process REVERSE only on PAID transactions 
	// Process REVERSE only on PAID transactions 
	# Update product->CSMinor moves

	// Process Reversals first, before Cancelling it
	$mGetStatus = GetTransInfo ($ApprvTranRID, 8);
	// if ($mGetStatus > 7) // Good Completed Transactions Only
	// {
		// 1.0 Cancel It
		$mSql = "UPDATE possales SET 
			TranStatus=7
			, ApprovedBy = '$ABy'
			, ZREAD=0
			, FlagSales = 0
			, FlagCancelledSales = 0
		WHERE TranRID='". $ApprvTranRID ."';";
		#wfs 09072013 ZREAD reset to 0, for inventory update at pistons.php
		mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql.<br>".mysqli_error($db_wgfinance));
		

		# 2. reverse all items, for completed transactions only
		if ($mGetStatus > 7) // Good Completed Transactions Only, reverses
		{
			require('piston.sales.07.php');
		}
	// } 
}


function VoidOrderDetail($ODetRID, $ABy)
{
	include_once 'wfslib/WalnetFunctionsPOS.php';
	require 'wchensPOS.php';
	require 'sys.inc.php';	
	
	$sesnAUTHORIZEOrigOrderDetailRID = $_SESSION['sesnAUTHORIZEOrigOrderDetailRID']; // 2016 Oct 24

	$mSql = "UPDATE `possales_details` SET 
		DisLineCanceled=1
		, ApprovedBy = '$ABy'
		, CancelledQty = SoldQty
		, SoldQty = 0
		WHERE OrderDetailRID=$ODetRID;";
	@mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql.<br>".mysqli_error($db_wgfinance));

	## capture the TranRID from details temp table since OrderDetailRID=$ODetRID is unique anyways
	$mSql = "SELECT TranRID FROM `possales_details` 
		WHERE OrderDetailRID=$ODetRID;";
	$mQrySusVoid = mysqli_query($db_wgfinance,$mSql) OR DIE("SUSPEND VOID!<br>".mysqli_query($db_wgfinance));
	if ($tblSusVoid = $mQrySusVoid->fetch_object())
	{
		$SusVoidTranRID = $tblSusVoid->TranRID;

		$mSql = "UPDATE `possales_details_all` SET 
			DisLineCanceled=1
			, ApprovedBy = '$ABy'
			, CancelledQty = SoldQty
			, SoldQty = 0
			WHERE OrderDetailRID=" . $sesnAUTHORIZEOrigOrderDetailRID . " 
				AND TranRID = " . $SusVoidTranRID .";";
			// 2016 Oct 24
			// WHERE OrderDetailRID=$ODetRID and TranRID = $SusVoidTranRID;";
		mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql.<br>".mysqli_error($db_wgfinance));
	}

	// $wfp = fopen("zzz.SUSPEND.VOID.txt", "w");
	// fwrite($wfp, $mSql);
	// fclose($wfp);

	#???????????????????????$TranRID=GetTranDetailInfo($ODetRID, 1);
	
	#$mSql = "INSERT INTO approvalhistory SELECT * FROM approval;";
	#@mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql.<br>".mysqli_error($db_wgfinance));
	
	#echo "<script>alert('u!');</script>";
	
	@$sesnTranRID = $_SESSION['sesnTranRID'] * 1; 
	
	@CalcSalesTotal($sesnTranRID);
	#@CalcTransChargesTotal($TranRID);
	#@CalcTransDiscountsTotal($TranRID);
}


function CancelPO($PORowID, $ABy)
{
	require('wchensPOS.php');
	
	$mSql = "UPDATE po SET POStatus=4,
		CancelledBy = '$ABy', CancelledDate=NOW()
		WHERE PORowID=$PORowID;";
	@mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql.<br>".mysqli_error($db_wgfinance));
}


function ApprovePO($PORowID, $ABy)
{
	require('wchensPOS.php');
	
	$mSql = "UPDATE po SET POStatus=3,
		ApprovedBy = '$ABy', DateApproved=NOW()
		WHERE PORowID=$PORowID;";
	@mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql.<br>".mysqli_error($db_wgfinance));
}


function SendPO($PORowID, $ABy)
{
	require('wchensPOS.php');
	
	$mSql = "UPDATE po SET POStatus=7 WHERE PORowID=$PORowID;";
	@mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql.<br>".mysqli_error($db_wgfinance));
}


function CancelizeStockTransfer($TranRID, $ABy)
{
	require('wchensPOS.php');
	
	$mSql = "UPDATE transfer SET TransferStatus=8, Deleted=1, 
		AuthorizedBy = '$ABy'
		WHERE STransferRID=$TranRID;";
	@mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql.<br>".mysqli_error($db_wgfinance));
	#CalcTransferTotal($RecRID);
}


function CancelizeRECREP($RRID, $ABy)
{
	require('wchensPOS.php');
	
	$mSql = "UPDATE receiving SET DRStatus=8, Deleted=1, 
		AuthorizedBy = '$ABy'
		WHERE RecRID=$RRID;";
	@mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql.<br>".mysqli_error($db_wgfinance));
	#CalcTransferTotal($RecRID);
}


function CancelizeRMA($RRID, $ABy)
{
	require('wchensPOS.php');
	
	$mSql = "UPDATE rma SET RMAStatus=8, Deleted=1, 
		AuthorizedBy = '$ABy'
		WHERE RMARID=$RRID;";
	@mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql.<br>".mysqli_error($db_wgfinance));
	#CalcTransferTotal($RecRID);
}
?>
<?php include "footer.php";?>