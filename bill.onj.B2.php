<?php
$x=0;
$mSlip = "";
$mTQty = 0;
$mTCharges = 0;
$mGrandT = 0;

@$mTtlCharges = 0;
@$mTtlQty = 0;

$orgline = str_repeat("-", 36);

$mSql = "SELECT 
		OrderDetailRID, SoldPrice, EntryType, DisLineCanceled,
		Served, SoldQty, OrderSlipNo, ProductRID, TranRID,
		CancelledQty, 
		SUM(SoldQty) AS SumQTY, 
		SUM(ExtendAmount) AS SumExtendAmount 
		FROM `possales_details_all` 
		WHERE TranRID='$mxiTranRID' AND EntryType = 2 AND DisLineCanceled=0
		GROUP BY OrderSlipNo, ProductRID, DisLineCanceled
		ORDER BY OrderDetailRID DESC;"; 
		#OR TranRID>1000 

$mQry = mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql".mysqli_error($db_wgfinance));
if ($mQry->num_rows)
{	
	fwrite($wfp, $orgline);
	fwrite($wfp, chr(13).chr(10));

	fwrite($wfp, "CHARGES");
	fwrite($wfp, chr(13).chr(10));

	while ($tblOrders=$mQry->fetch_object())
	{
		@$xQty = $tblOrders->SumQTY;
		$mTtlQty += @$xQty;
		
		$xDesc=GetChargesInfo($tblOrders->ProductRID);
		$xSRP = $tblOrders->SoldPrice;
		
		$mExtend = number_format($tblOrders->SumExtendAmount, 2);
		$mTtlCharges += $mExtend;
		
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

	$eco = number_format($mTtlCharges, 2);
	$ifoot = str_pad(" ", $a, $pad, STR_PAD_RIGHT).
			 str_pad("TOTAL CHARGES", $b, $pad, STR_PAD_LEFT) .
			 str_pad($eco, $c, $pad, STR_PAD_LEFT);
	fwrite($wfp, $ifoot);
	fwrite($wfp, chr(13).chr(10));

	$eco = number_format($sesnSALESGrossAmountDue, 2);
	$ifoot = str_pad(" ", $a, $pad, STR_PAD_RIGHT).
			 str_pad("SUB TOTAL", $b, $pad, STR_PAD_LEFT) .
			 str_pad($eco, $c, $pad, STR_PAD_LEFT);
	fwrite($wfp, $ifoot);
	fwrite($wfp, chr(13).chr(10));
}
?>