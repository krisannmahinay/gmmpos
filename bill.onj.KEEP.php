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

require_once("wfslib/xcls.sales.php");
$clsSALES = new SALES();

include_once('wfslib/WalnetFunctionsPOS.php');
include_once('wchensPOS.php');

require('trans.info.php');

if ($sesnTranRID<=0)
{
	echo "<script>alert('Please specify a transaction!');</script>";
	echo "<script>window.close();</script>";
}

@$sesnPRNJournalType = $_SESSION['sesnPRNJournalType'];

//set error handler
set_error_handler("customError");

#include_once('kick.php');

#$printer = "\\\\127.0.0.1\BIXOLON SAMSUNG SRP-275";
#$printer = "\\\\".GetOrgSetUp(12);

#$printer = "x.txt";

$printer = "\\\\".$sesnLOGGEDUserIPX."\\".GetOrgSetUp(12);

$wfp = fopen($printer, "w");

$a = 5;
$b = 20;
$c = 10;
$pad = " ";
$orgline = str_repeat("=", 36);

include_once('or.head.onj.php');

fwrite($wfp, $orgline);
fwrite($wfp, chr(13).chr(10));

$hbar = str_pad("QTY", $a, $pad, STR_PAD_RIGHT) . 
		str_pad("DESCRIPTION", $b, $pad, STR_PAD_RIGHT) . 
		str_pad("PRICE", $c, $pad, STR_PAD_LEFT);

fwrite($wfp, $hbar);
fwrite($wfp, chr(13).chr(10));

fwrite($wfp, $orgline);
fwrite($wfp, chr(13).chr(10));

$mSql = "SELECT ExtendAmount,
	OrderDetailRID, SoldPrice, EntryType, DisLineCanceled,
	Served, SoldQty, OrderSlipNo, ProductRID, TranRID,
	SUM(CancelledQty) AS SumCancQTY, 
	SUM(SoldQty) AS SumQTY, 
	SUM(ExtendAmount) AS SumExtendAmount 
		
	FROM possales_details 
		
	WHERE TranRID='$sesnTranRID' AND EntryType = 0 AND DisLineCanceled = 0
	GROUP BY OrderSlipNo, ProductRID, DisLineCanceled
	ORDER BY OrderDetailRID,ProductRID, OrderDetailRID DESC;"; 
	
$mQry = mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql<br>".mysqli_error($db_wgfinance));
$x=0;
$mSlip = "";
$mTtlQty = 0;
$mGTtl = 0;

#echo "<br>$mSql<br>";

while ($tblOrders=$mQry->fetch_object())
{	
	$xQty 	= $tblOrders->SumQTY;
	
	$xDesc 	= trim(GetItemInfoRID($tblOrders->ProductRID, 3)); #button label
	$xDesc 	= substr($xDesc, 0, 20); #button label
	
	$xSRP 	= $tblOrders->SoldPrice;
	
	$mExtend = number_format($tblOrders->SumExtendAmount, 2);
	
	$mTtlQty += $xQty;
	$mGTtl += $mExtend;
	
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
}

fwrite($wfp, $orgline);
fwrite($wfp, chr(13).chr(10));

$eco = number_format($sesnSALESTotalAmount, 2);
$ifoot = str_pad($mTtlQty, $a, $pad, STR_PAD_RIGHT).
		 str_pad("TOTAL", $b, $pad, STR_PAD_LEFT) .
		 str_pad($eco, $c, $pad, STR_PAD_LEFT);
fwrite($wfp, $ifoot);
fwrite($wfp, chr(13).chr(10));

include_once('bill.onj.B.php');

include_once('bill.onj.C.php');

$mxTaxBase = $sesnSALESNetAmountDue / 1.12;
$mxVAT = $mxTaxBase * 0.12;

$eVAT = number_format($mxTaxBase, 2);
$ivat = str_pad("TAX Base", $c, $pad, STR_PAD_LEFT) .
		str_pad($eVAT, $c, $pad, STR_PAD_LEFT);
		#str_pad(" ", $a, $pad, STR_PAD_RIGHT).
fwrite($wfp, $ivat);
fwrite($wfp, chr(13).chr(10));

$eVAT = number_format($mxVAT, 2);
$ivat = str_pad("12% VAT", $c, $pad, STR_PAD_LEFT) .
		str_pad($eVAT, $c, $pad, STR_PAD_LEFT);
		#str_pad(" ", $a, $pad, STR_PAD_RIGHT).
fwrite($wfp, $ivat);
fwrite($wfp, chr(13).chr(10));
fwrite($wfp, chr(13).chr(10));

$eTEND = number_format($sesnSALESTotalTendered, 2);
$iTend = str_pad(" ", $a, $pad, STR_PAD_RIGHT).
		 str_pad("TENDERED", $b, $pad, STR_PAD_LEFT) .
		 str_pad($eTEND, $c, $pad, STR_PAD_LEFT);
fwrite($wfp, $iTend);
fwrite($wfp, chr(13).chr(10));

$eChens = number_format($sesnSALESChange, 2);
$echens = str_pad(" ", $a, $pad, STR_PAD_RIGHT).
		 str_pad("CHANGE", $b, $pad, STR_PAD_LEFT) .
		 str_pad($eChens, $c, $pad, STR_PAD_LEFT);
fwrite($wfp, $echens);

fwrite($wfp, chr(13).chr(10));
fwrite($wfp, chr(13).chr(10));

$orgfooter = GetOrgSetUp(6);
$orgfooter = str_pad($orgfooter, 36, " ", STR_PAD_BOTH);
fwrite($wfp, $orgfooter);
		 
for ($x=0; $x<10; $x++) fwrite($wfp, chr(13).chr(10)); 

$cutsheet = "\x00\x1Bi\x00";
fwrite($wfp, $cutsheet);

fclose($wfp);

#set tran status
$mSql = "UPDATE possales SET ForcePrint = 0
	WHERE TranRID='$sesnTranRID';";
@mysqli_query($db_wgfinance, $mSql) OR DIE("$mSql<br>".mysqli_error($db_wgfinance));

$_SESSION['sesnTranRID'] = NULL;
$clsSALES->SalesRow(0);
require('trans.info.php');

#echo "<script>window.close();</script>";

//error wfpr function
function customError($errno, $errstr)
{
	echo "<b>Error:</b> [$errno] $errstr";
	#echo "<br><h2>PRINTER ERROR DETECTED!</h2>";
	exit();
}
?>