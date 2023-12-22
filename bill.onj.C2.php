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
			WHERE TranRID='$mxiTranRID' AND EntryType = 3 
				AND ProductRID <> '3333333' AND ProductRID <> '3333335'
			GROUP BY OrderSlipNo, ProductRID, DisLineCanceled
			ORDER BY OrderSlipNo DESC, OrderDetailRID DESC;"; 
		#OR TranRID>1000 
$mQry = mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql".mysqli_error($db_wgfinance));
if ($mQry->num_rows)
{
	fwrite($wfp, $orgline);
	fwrite($wfp, chr(13).chr(10));

	fwrite($wfp, "DISCOUNTS");
	fwrite($wfp, chr(13).chr(10));

	while ($tblOrders=$mQry->fetch_object())
	{	
		@$xQty = $tblOrders->SumQTY;
		$mTtlQty += $xQty;
		$xDesc=GetDiscountsInfo($tblOrders->ProductRID,1);
		$xSRP = $tblOrders->SoldPrice;
	
		$mExtend = number_format($tblOrders->SumExtendAmount * -1, 2);
	
		$irw = 	str_pad($xQty, $a, $pad, STR_PAD_RIGHT).
			str_pad($xDesc, $b, $pad, STR_PAD_RIGHT).
			str_pad($mExtend, $c, $pad, STR_PAD_LEFT);	
		fwrite($wfp, $irw);
	
		fwrite($wfp, chr(13).chr(10));

		/*
		$xSRPj = number_format($xSRP, 2);
		$isrp =	str_pad(" ", $a, $pad, STR_PAD_RIGHT).
			str_pad("@ ".$xSRPj, $b, $pad, STR_PAD_RIGHT).
			str_pad(" ", $c, $pad, STR_PAD_LEFT);	
		fwrite($wfp, $isrp);
		fwrite($wfp, chr(13).chr(10));
		*/
	}

	fwrite($wfp, $orgline);
	fwrite($wfp, chr(13).chr(10));

	$eco = number_format($sesnSALESTotalDiscounts * -1, 2);
	$ifoot = str_pad(" ", $a, $pad, STR_PAD_RIGHT).
		 str_pad("TOTAL DISCOUNTS", $b, $pad, STR_PAD_LEFT) .
		 str_pad($eco, $c, $pad, STR_PAD_LEFT);
	fwrite($wfp, $ifoot);
	fwrite($wfp, chr(13).chr(10));

	fwrite($wfp, chr(13).chr(10));
}
?>