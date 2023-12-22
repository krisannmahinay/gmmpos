<?php
$x=0;
$mSlip = "";
$mTQty = 0;
$mGrandT = 0;

$orgline = str_repeat("-", 36);

$mSql = "SELECT 
		OrderDetailRID, SoldPrice, EntryType, DisLineCanceled,
		Served, SoldQty, OrderSlipNo, ProductRID, TranRID,
		CancelledQty, 
		SUM(SoldQty) AS SumQTY,
		SUM(ExtendAmount) AS SumExtendAmount 
		FROM `possales_details_all` 
			WHERE TranRID='$mxiTranRID' 
				AND EntryType = 3 
				AND ProductRID = 3333333
			GROUP BY OrderSlipNo, ProductRID, DisLineCanceled
			ORDER BY OrderSlipNo DESC, OrderDetailRID DESC;"; 
		#OR TranRID>1000 
$mQry = mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql".mysqli_error($db_wgfinance));
if ($mQry->num_rows)
{
	$eco = number_format($sesnSALESTotalSCPWDDiscounts * -1, 2);
	$ifoot = str_pad(" ", $a, $pad, STR_PAD_RIGHT).
		 str_pad("SENIOR C Disc.", $b, $pad, STR_PAD_LEFT) .
		 str_pad($eco, $c, $pad, STR_PAD_LEFT);
	fwrite($wfp, $ifoot);
	fwrite($wfp, chr(13).chr(10));
}
?>