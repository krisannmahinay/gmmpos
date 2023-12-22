<?php
@session_start();

include_once('wfslib/WalnetFunctionsPOS.php');

#require_once("wfslib/xcls.sales.php");
#$clsSALES = new SALES();

require_once('wchensPOS.php');

include_once('sys.inc.php');

$q = $_REQUEST['amnt'] * 1;
#$jid = $_REQUEST['jid']; 

#$mxTranRID = $_REQUEST['trnid'];
$mxTranRID = $_SESSION['sesnTranRID'] * 1;
#$mUserRID = $_REQUEST['userid'];
$mUserRID = $sesnLOGGEDPxRID;

@$sesnSALESTotalAmount= $_SESSION['sesnSALESTotalAmount'];

$sqlIn = "INSERT INTO payment SET 
	PayTypeRID = 0,
	TranRID = '$mxTranRID',
	PayDate = NOW(),
	Tendered = '$q',
	AmountDue = '$sesnSALESTotalAmount',
	UserRID = '$mUserRID';";
@mysqli_query($db_wgfinance,$sqlIn) or die(mysqli_error($db_wgfinance));

CalcPaymentsTotal($mxTranRID);
CalcSalesTotal($mxTranRID);

$txtAmount = "";
$txtTendered = "";

$_SESSION['sesnREDIRECTOR']="payment.php";
echo "<script>location='redir.php';</script>";
?>