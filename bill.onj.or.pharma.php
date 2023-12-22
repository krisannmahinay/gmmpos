<?php
if ($sesnSALESSrCitPWDLine == 0)
{
	$xxx = GetItemInfoRID($tblOrders->ProductRID, 17); #SeniorStandardSRP
	$xSRPj = number_format($xxx, 2);
	
	$isrp = str_pad(" ", $a, $pad, STR_PAD_RIGHT).
	str_pad("reg.price: ".$xSRPj, $b, $pad, STR_PAD_RIGHT); #.str_pad($mExtend, $c, $pad, STR_PAD_LEFT);	
	fwrite($wfp, $isrp);

	fwrite($wfp, chr(13).chr(10));
	$xSRPjd = number_format($xSRP, 2);
	$isrp =	str_pad(" ", $a, $pad, STR_PAD_RIGHT).
		str_pad("disc.price:".$xSRPjd, $b, $pad, STR_PAD_RIGHT).
		str_pad($mExtend, $c, $pad, STR_PAD_LEFT);	
			fwrite($wfp, $isrp);
}
else if ($sesnSALESSrCitPWDLine == 1)
{
	$PharmaORStr = "reg.price: ";
	
	$xSRPj = number_format($xSRP, 2);
	$isrp = str_pad(" ", $a, $pad, STR_PAD_RIGHT).
	str_pad($PharmaORStr.$xSRPj, $b, $pad, STR_PAD_RIGHT).
	str_pad($mExtend, $c, $pad, STR_PAD_LEFT);	
	fwrite($wfp, $isrp);

	/* # DON'T SHOW NA LANG KUNO A!
	$xSRPHi = GetItemInfoRID($tblOrders->ProductRID, 16); # SRP3
	
	#kon prehos man lang, don't print na
	if ($xSRPHi <> $xSRP)
	{
		fwrite($wfp, chr(13).chr(10));
		$xSRP_hi = number_format($xSRPHi, 2);
		
		$isrp =	str_pad(" ", $a, $pad, STR_PAD_RIGHT).
			str_pad("dis. price: ".$xSRP_hi, $b, $pad, STR_PAD_RIGHT).
		str_pad(" ", $c, $pad, STR_PAD_LEFT);	
			fwrite($wfp, $isrp);
	}*/
}
?>