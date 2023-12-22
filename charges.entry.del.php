<?php
@session_start();
include_once('wfslib/WalnetFunctionsPOS.php');
include_once('wchensPOS.php');
require('sys.inc.php');	
$delRowId = $_REQUEST['delRowId'];

$mSql = "UPDATE `possales_details` SET DisLineCanceled=1,
		CancelledQty = SoldQty,
		SoldQty = 0
		WHERE OrderDetailRID=$delRowId;";
@mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql.<br>".mysqli_error($db_wgfinance));

@$sesnTranRID = $_SESSION['sesnTranRID'] * 1;

CalcSalesTotal($sesnTranRID);
CalcTransChargesTotal($sesnTranRID, 'possales_details');
//this discount routine was moved to CalsSalesTotal in MGD in Feb 2016
//CalcTransDiscountsTotal($sesnTranRID, 'possales_details');

echo "<script>location='charges.php';</script>";
?>