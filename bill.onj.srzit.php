<?php
$orgline = str_repeat("-", 36);

$mSql = "SELECT * FROM  `possales_details`
		WHERE EntryType=3 
			AND TranRID='$sesnTranRID' 
			AND DisLineCanceled=0
			ORDER BY OrderDetailRID;"; #order_charges
		#AND DisLineCanceled=0
$mQry = mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql".mysqli_error($db_wgfinance));
$x=0;
$mGrandT=0;

while ($tblOrders=$mQry->fetch_object())
{
	$mxRID = $tblOrders->OrderDetailRID;
	$mxSeniorID = $tblOrders->SeniorID;
	$mxSeniorName = $tblOrders->SeniorName;
	$mxSeniorIDExpire = $tblOrders->SeniorIDExpire;
	$mxDiscountPromoNote = $tblOrders->DiscountPromoNote;

	if (($mxSeniorID == NULL) &&
		($mxSeniorName == NULL) &&
		($mxSeniorIDExpire == NULL) &&
		($mxDiscountPromoNote == NULL))
	{
		#do nothing
	}	
	else
	{
		$mxSeniorID = ($mxSeniorID == NULL)? NULL : "ID: $mxSeniorID";
		$mxSeniorName = ($mxSeniorName == NULL)? NULL : "NAME: $mxSeniorName";
		$mxSeniorIDExpire = ($mxSeniorIDExpire == "0000-00-00")? NULL : "EXPIRY: $mxSeniorIDExpire";
		$mxDiscountPromoNote = ($mxDiscountPromoNote == NULL)? NULL : "PROMO: $mxDiscountPromoNote";
	
		fwrite($wfp, $orgline);
		fwrite($wfp, chr(13).chr(10));
		
		$irw = str_pad(" ", $a, $pad, STR_PAD_RIGHT).
			str_pad($mxSeniorID, $b, $pad, STR_PAD_RIGHT);	
		fwrite($wfp, $irw);
		fwrite($wfp, chr(13).chr(10));
		
		$irw = str_pad(" ", $a, $pad, STR_PAD_RIGHT).
			str_pad($mxSeniorName, $b, $pad, STR_PAD_RIGHT);	
		fwrite($wfp, $irw);
		fwrite($wfp, chr(13).chr(10));
		
		$irw = str_pad(" ", $a, $pad, STR_PAD_RIGHT).
			str_pad($mxSeniorIDExpire, $b, $pad, STR_PAD_RIGHT);	
		fwrite($wfp, $irw);
		fwrite($wfp, chr(13).chr(10));
		
		$irw = str_pad(" ", $a, $pad, STR_PAD_RIGHT).
			str_pad($mxDiscountPromoNote, $b, $pad, STR_PAD_RIGHT);	
		fwrite($wfp, $irw);
		#fwrite($wfp, chr(13).chr(10));
		#fwrite($wfp, $orgline);
		fwrite($wfp, chr(13).chr(10));
	}
}
?>