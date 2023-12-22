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

include_once 'sys.inc.php';

require_once "wfslib/xcls.sales.php";
$clsSALES = new SALES();

include_once 'wfslib/WalnetFunctionsPOS.php';
include_once 'wchensPOS.php';

@$sesnTranRID = $_SESSION['sesnTranRID'] * 1;

	$mSql = "UPDATE possales SET 
		Printed = Printed + 1
		WHERE TranRID=$sesnTranRID;";
	#echo "$mSql<br>";
	@mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql<br>".mysqli_error($db_wgfinance));

#no printing times is updated , reload sessions
require 'trans.info.php';

if ($sesnTranRID<=0)
{
	echo "<script>alert('Please specify a transaction!');</script>";
	echo "<script>window.close();</script>";
}

@$sesnPRNJournalType = $_SESSION['sesnPRNJournalType'];
@$sesnSALESSrCitPWDLine = $_SESSION['sesnSALESSrCitPWDLine'] * 1; # ang sa class ni ya ha?, reprint issue
			
#exec("mode COM3 BAUD=9600 PARITY=N data=8 stop=1 xon=off");

/*
$wfp = fopen ("COM3", "w");
if (!$wfp) {
   echo "Not open";
} else {
   echo "Open";
}

if ($wfp = fopen("COM3", "r+") === FALSE)
{ 
  // wfp error 
  echo "PRINTER ERROR DETECTED!";
  exit; 
} 
*/

//error wfpr function
function customError($errno, $errstr)
{
	echo "<b>Error:</b> [$errno] $errstr";
	#echo "<br><h2>PRINTER ERROR DETECTED!</h2>";
	exit();
}

//set error handler
set_error_handler("customError");

$PHARMA_OR = GetOrgSetUp(26) * 1;

$PrintOR = GetOrgSetUp(20);
if ($PrintOR == 1)
{	
	$printer = "\\\\".$sesnLOGGEDUserIPX."\\".GetOrgSetUp(12);
	include_once('kick.php');
}
else
{
	$printer = "zzz.OR.txt";
}
$wfp = fopen($printer, "w");

$a = 5;
$b = 20;
$c = 10;
$pad = " ";
$orgline = str_repeat("=", 36);

require('or.head.onj.php');

fwrite($wfp, $orgline);
fwrite($wfp, chr(13).chr(10));

#$hbar = str_pad("QTY", $a, $pad, STR_PAD_RIGHT) . 
$hbar = str_pad(" ", $a, $pad, STR_PAD_RIGHT) . 
$hbar = str_pad("DESCRIPTION", $b, $pad, STR_PAD_RIGHT) . 
		str_pad("AMOUNT", $c, $pad, STR_PAD_LEFT);

fwrite($wfp, $hbar);
fwrite($wfp, chr(13).chr(10));

fwrite($wfp, $orgline);
fwrite($wfp, chr(13).chr(10));

$mSql = "SELECT ExtendAmount
	, OrderDetailRID
	, SoldPrice
	, EntryType
	, DisLineCanceled
	, Served
	, SoldQty
	, OrderSlipNo
	, ProductRID
	, TranRID
	, SUM(CancelledQty) AS SumCancQTY
	, SUM(SoldQty) AS SumQTY
	, SUM(ExtendAmount) AS SumExtendAmount 
	, DiscountApplied
		
	FROM `possales_details` 
		
	WHERE TranRID='$sesnTranRID' AND EntryType = 0 AND DisLineCanceled = 0
	GROUP BY ProductRID, DisLineCanceled
	ORDER BY OrderDetailRID;"; 
		#GroupBy OrderSlipNo,
	
$mQry = mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql<br>".mysqli_error($db_wgfinance));
$x=0;
$mSlip = "";
$mTtlQty = 0;
$mGTtl = 0;

while ($tblOrders=$mQry->fetch_object())
{	
	$xQty 	= $tblOrders->SumQTY;
		
	$xDesc 	= trim(GetItemInfoRID($tblOrders->ProductRID, 1)); #button label
	$xDesc 	= substr($xDesc, 0, 30); #button label
	
	$xLineDisc = $tblOrders->DiscountApplied;
	$xSRP 	= $tblOrders->SoldPrice;
	
	$mExtend = $tblOrders->SumExtendAmount;
	$mExtendFormatted = number_format($mExtend, 2);
	
	$mTtlQty += $xQty;
	$mGTtl += $mExtend;
	
	#$irw = 	str_pad($xDesc, $b, $pad, STR_PAD_RIGHT).
		#		str_pad($mExtend, $c, $pad, STR_PAD_LEFT);	
	$irw = 	str_pad($xQty, $a, $pad, STR_PAD_RIGHT).
		str_pad($xDesc, 30, $pad, STR_PAD_RIGHT);
		
	fwrite($wfp, $irw);
	
	fwrite($wfp, chr(13).chr(10));
	
	if ($PHARMA_OR == 1)
		include "bill.onj.or.pharma.php";
	else
		include "bill.onj.or.standard.php";
		
	fwrite($wfp, chr(13).chr(10));
}

fwrite($wfp, $orgline);
fwrite($wfp, chr(13).chr(10));

$eco = number_format($sesnSALESTotalAmount, 2);
$ifoot = str_pad($mTtlQty, $a, $pad, STR_PAD_RIGHT).
		 str_pad("SUB TOTAL", $b, $pad, STR_PAD_LEFT) .
		 str_pad($eco, $c, $pad, STR_PAD_LEFT);
fwrite($wfp, $ifoot);
fwrite($wfp, chr(13).chr(10));


$discnt = number_format($sesnSALESTotalDiscounts, 2);
$ifoot = str_pad(" ", $a, $pad, STR_PAD_RIGHT).
		 str_pad("DISCOUNT:", $b, $pad, STR_PAD_LEFT) .
		 str_pad($discnt, $c, $pad, STR_PAD_LEFT);
fwrite($wfp, $ifoot);
fwrite($wfp, chr(13).chr(10));


include_once 'bill.onj.B.php';

include_once 'bill.onj.C.php';

#	KON may SENIOR CITIZEN

$mSql = "SELECT * FROM `possales_details` 
	WHERE TranRID='$sesnTranRID' 
		AND EntryType = 3 
		AND (ProductRID = 3333333 OR ProductRID = 3333335)
		;";
$mQrySC = mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql".mysqli_error($db_wgfinance));
if ($mQrySC->num_rows)
{
	#get the VAT first
	# 	VATABLE AMOUNT DUE
	fwrite($wfp, chr(13).chr(10));
	$netovat = number_format($sesnSALESNetOfVAT, 2);
	$ivat = str_pad(" ", $a, $pad, STR_PAD_RIGHT).
		 str_pad("NET OF VAT", $b, $pad, STR_PAD_LEFT) .
		 str_pad($netovat, $c, $pad, STR_PAD_LEFT);
	fwrite($wfp, $ivat);
	fwrite($wfp, chr(13).chr(10));
	
}	
	include_once 'bill.onj.C.SC.php';
	include_once 'bill.onj.C.PWD.php';

	#fwrite($wfp, chr(13).chr(10));
	#$eDUE = number_format($sesnSALESTotalAmount, 2);
	$eDUE = number_format($sesnSALESNetAmountDue, 2);
	$iDUE = str_pad(" ", $a, $pad, STR_PAD_RIGHT).
		 str_pad("AMOUNT DUE", $b, $pad, STR_PAD_LEFT) .
		 str_pad($eDUE, $c, $pad, STR_PAD_LEFT);
	fwrite($wfp, $iDUE);
	fwrite($wfp, chr(13).chr(10));

	
	#	AMOUNT TENDERED 
	$eTEND = number_format($sesnSALESTotalTendered, 2);
	$iTend = str_pad(" ", $a, $pad, STR_PAD_RIGHT).
		 str_pad("AMOUNT TENDERED", $b, $pad, STR_PAD_LEFT) .
		 str_pad($eTEND, $c, $pad, STR_PAD_LEFT);
	fwrite($wfp, $iTend);
	fwrite($wfp, chr(13).chr(10));

	# 	CHANGE DUE 
	$eChens = number_format($sesnSALESChange, 2);
	$echens = str_pad(" ", $a, $pad, STR_PAD_RIGHT).
		 str_pad("CHANGE", $b, $pad, STR_PAD_LEFT) .
		 str_pad($eChens, $c, $pad, STR_PAD_LEFT);
	fwrite($wfp, $echens);

	fwrite($wfp, chr(13).chr(10));	


/*
if (GetOrgSetUp(11)<>1) #don't show for ilo	
{
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
}*/


#if (GetOrgSetUp(11)<>1) #don't show for ilo	
#{
if ($orgn9 <> 14301) // Ryan A switch off
{
	fwrite($wfp, chr(13).chr(10));
	
	$mxTaxBase = $sesnSALESNetAmountDue / 1.12;
	$mxVAT = $mxTaxBase * 0.12;
	$eVAT = number_format($mxTaxBase, 2);
	
	#$eVAT = number_format($mxTaxBase, 2);  #sesnSALESVatableSales sesnSALESNetOfVAT
	$eVAT = number_format($sesnSALESVatableSales, 2);  #sesnSALESVatableSales sesnSALESNetOfVAT
	$ivat = str_pad("VATABLE SALES: ", $b, $pad, STR_PAD_LEFT) .
		str_pad($eVAT, $c, $pad, STR_PAD_LEFT);
		#str_pad(" ", $a, $pad, STR_PAD_RIGHT).
	fwrite($wfp, $ivat);
	fwrite($wfp, chr(13).chr(10));

	$eVAT = number_format(0, 2);
	$ivat = str_pad("ZERO RATED: ", $b, $pad, STR_PAD_LEFT) .
		str_pad($eVAT, $c, $pad, STR_PAD_LEFT);
		#str_pad(" ", $a, $pad, STR_PAD_RIGHT).
	fwrite($wfp, $ivat);
	fwrite($wfp, chr(13).chr(10));
	
	$eVAT = number_format($sesnSALESExemptSales, 2);
	$ivat = str_pad("EXEMPT SALES: ", $b, $pad, STR_PAD_LEFT) .
		str_pad($eVAT, $c, $pad, STR_PAD_LEFT);
		#str_pad(" ", $a, $pad, STR_PAD_RIGHT).
	fwrite($wfp, $ivat);
	fwrite($wfp, chr(13).chr(10));
	
	$eVAT = number_format($sesnSALESTotalVat, 2);
	$ivat = str_pad("12% VAT: ", $b, $pad, STR_PAD_LEFT) .
		str_pad($eVAT, $c, $pad, STR_PAD_LEFT);
		#str_pad(" ", $a, $pad, STR_PAD_RIGHT).
	fwrite($wfp, $ivat);
	#fwrite($wfp, chr(13).chr(10));
}
#}

fwrite($wfp, chr(13).chr(10));

require 'bill.onj.srzit.php';


#if (($sesnPRNJournalType == "OFFICIAL RECEIPT") ||
#	($sesnPRNJournalType == "SALES INVOICE"))
#{
	$jrnlT = GetOrgSetUp(15);

	// wfs Feb 8, off danay a
	// $jrnlt = "This serves as your $jrnlT";
	$jrnlt = "";
#}
#else
#{
#	$jrnlT = NULL;
#	$jrnlt = "O R D E R   S L I P";
#}


$jrnlt = str_pad($jrnlt, 36, " ", STR_PAD_BOTH);
fwrite($wfp, $jrnlt);
	
fwrite($wfp, chr(13).chr(10));

require 'or.foot.onj.php';

for ($x=0; $x<10; $x++) fwrite($wfp, chr(13).chr(10)); 

$cutsheet = "\x00\x1Bi\x00";
fwrite($wfp, $cutsheet);

fclose($wfp);

#set tran status
$mSql = "UPDATE possales SET 
	ForcePrint = 0
	WHERE TranRID=$sesnTranRID;";
@mysqli_query($db_wgfinance, $mSql) OR DIE("$mSql<br>".mysqli_error($db_wgfinance));

echo "<script>window.close();</script>";
?>