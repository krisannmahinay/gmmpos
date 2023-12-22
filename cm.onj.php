<head>
<link href="css/print.css" rel="stylesheet" type="text/css"  media="print"/>  
<script language="JavaScript">
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

<body onKeyDown='funcDetectKey(event);' autocomplete='off'>

<?php
ini_set("session.bug_compat_warn","off");
@session_start();

include_once('sys.inc.php');

include_once('wfslib/WalnetFunctionsPOS.php');
include_once('wchensPOS.php');

require 'cm.info.php';

$mSql = "UPDATE credit_memo SET 
	Printed = Printed + 1
	WHERE TranRID=$sesnTranRID;";
#echo "$mSql<br>";
@mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql<br>".mysqli_error($db_wgfinance));

if ($sesnTranRID<=0)
{
	echo "<script>location='sales.php';</script>";
}

#$printer = "\\\\127.0.0.1\BIXOLON SAMSUNG SRP-275";
#$printer = "\\\\".GetOrgSetUp(12);

#$printer = "z_crmemo.txt";
#
/*
$printer = "\\\\".$sesnLOGGEDUserIPX."\\".GetOrgSetUp(12);

$wfp = fopen($printer, "w");
*/
$PrintOR = GetOrgSetUp(20);

if ($PrintOR == 1)
{	
	$printer = "\\\\".$sesnLOGGEDUserIPX."\\".GetOrgSetUp(12);
	include_once('kick.php');
}
else
{
	$printer = "zzz_CR_MEMO.txt";
}
// $wfp = fopen($printer, "w");

$a = 5;
$b = 20;
$c = 10;
$pad = " ";
$orgline = str_repeat("=", 36);
$orglineS = str_repeat("-", 36);

require('cm.head.onj.php');

fwrite($wfp, $orgline);
fwrite($wfp, chr(13).chr(10));

	$eTEND = $sesnTranRID;
	#$iTend = str_pad(" ", $a, $pad, STR_PAD_RIGHT).
	$iTend = "OR #: ".str_pad($eTEND, 8, "0", STR_PAD_LEFT).
		" Returned Items: ";
		#str_pad("OR #: ", $b, $pad, STR_PAD_RIGHT) .
	fwrite($wfp, $iTend);
	fwrite($wfp, chr(13).chr(10));
	
fwrite($wfp, $orglineS);
fwrite($wfp, chr(13).chr(10));

$hbar = str_pad("QTY", $a, $pad, STR_PAD_RIGHT) . 
		str_pad("DESCRIPTION", $b, $pad, STR_PAD_RIGHT) . 
		str_pad("PRICE", $c, $pad, STR_PAD_LEFT);

fwrite($wfp, $hbar);
fwrite($wfp, chr(13).chr(10));
fwrite($wfp, $orglineS);
fwrite($wfp, chr(13).chr(10));

$mSql = "SELECT * FROM returns_details 
	WHERE TranRID='$sesnTranRID' AND RETURNED_qty > 0;";
$mQry = mysqli_query($db_wgfinance,$mSql) or die("<b>$_SERVER[PHP_SELF]</b><br>$mSql<br>".mysqli_error($db_wgfinance));
$mTtlQty = 0;
$mGTtl = 0;
$mDisc = 0;
while ($tblRets=$mQry->fetch_object())
{
	$xQty 	= $tblRets->RETURNED_qty * 1;
	
	$xDesc 	= trim(GetItemInfoRID($tblRets->ProductRID, 1)); #button label
	$xDesc 	= substr($xDesc, 0, 20); #button label
	
	$xSRP 	= $tblRets->SoldPrice;
	
	$mDisc = $tblRets->DiscountApplied * 1;
	$mDiscAppld = number_format($tblRets->DiscountApplied, 2);

	$mExtend = number_format($xQty * $xSRP - $mDisc, 2);
	# $mExtend = number_format($tblRets->ExtendAmount, 2);

	$mTtlQty += $xQty;
	$mGTtl += $mExtend - $mDisc;
	
	$irw = 	str_pad($xQty, $a, $pad, STR_PAD_RIGHT).
			str_pad($xDesc, $b, $pad, STR_PAD_RIGHT).
			str_pad($mExtend, $c, $pad, STR_PAD_LEFT);	
	fwrite($wfp, $irw);
	
	fwrite($wfp, chr(13).chr(10));
	
	$xSRPj = number_format($xSRP, 2);
	$isrp =	str_pad(" ", $a, $pad, STR_PAD_RIGHT).
			str_pad("@ ".$xSRPj, $b, $pad, STR_PAD_RIGHT).
			str_pad(" ", $c, $pad, STR_PAD_LEFT);	
	fwrite($wfp, $isrp);
	fwrite($wfp, chr(13).chr(10));

	if ($mDisc > 0) {
		$xLineDisc = number_format($mDiscAppld, 2);
		$idisc =str_pad(" ", $a, $pad, STR_PAD_RIGHT).
			str_pad("disc: ".$xLineDisc, $b, $pad, STR_PAD_RIGHT).
			str_pad(" ", $c, $pad, STR_PAD_LEFT);	
		fwrite($wfp, $idisc);

		fwrite($wfp, chr(13).chr(10));
	}
}

fwrite($wfp, $orgline);
fwrite($wfp, chr(13).chr(10));

	
	#	AMOUNT TENDERED 
	$eTEND = number_format($sesnCMNetAmountDue - $mDisc, 2);
	$iTend = str_pad(" ", $a, $pad, STR_PAD_RIGHT).
		 str_pad("CREDIT BALANCE", $b, $pad, STR_PAD_LEFT) .
		 str_pad($eTEND, $c, $pad, STR_PAD_LEFT);
	fwrite($wfp, $iTend);
	fwrite($wfp, chr(13).chr(10));

fwrite($wfp, $orgline);
fwrite($wfp, chr(13).chr(10));


for ($x=0; $x<10; $x++) fwrite($wfp, chr(13).chr(10)); 

$cutsheet = "\x00\x1Bi\x00";
fwrite($wfp, $cutsheet);

fclose($wfp);
echo "<script>window.close();</script>";
?>