<?php
@session_start();

include_once 'wfslib/WalnetFunctionsPOS.php';

include_once "wfslib/xcls.sales.php";
$clsSALES = new SALES();

require_once 'wchensPOS.php';

include_once 'sys.inc.php';

$upc = trim($_REQUEST['upc']);
$jid = $_REQUEST['jid']; 
$mxTranRID = $_REQUEST['trnid'] * 1; 

// $wfp = fopen("zzz_janelsA.zzz", "w");
// fwrite($wfp, "TranRID: " . $mxTranRID . ", UPC: " . $upc);
// fclose($wfp);

if ($mxTranRID == 0) {
	$mxTranRID = wfs_CreateNewTrans($sesnLOGGEDPxRID);
	$_SESSION['sesnTranRID'] = $mxTranRID;

	$clsSALES->SalesRow($mxTranRID);
}
$sesnTranRID = $_SESSION['sesnTranRID'];

// $wfp = fopen("zzz_janelsB.zzz", "w");
// fwrite($wfp, "TranRID: " . $sesnTranRID);
// fclose($wfp);

if ($sesnLOGGEDPxRID<=0)
{
	# kay wala mong
	$jid = NULL;
}

if ($jid=="txtPOSID" || $jid=="idPLU")
{
	$mQ = 0;

	if (strstr($upc, '*'))
	{
		$x = $upc;
		$a = strstr($upc, '*');
		$upc = substr($a, 1);

		$mQ = substr($x, 0, strpos($x, "*"));
		$mQ *= 1;	
		$mQ = ($mQ<0)? 0 : $mQ;
	}
	
	$mQty = ($mQ<=0 || $mQ==NULL)? 1 : $mQ;

	#search item
	$ItemUPC = $upc;
	
	// if (GetOrgSetUp(13)==1)
	// {
	// 	#multiUPC of MedSUp
	// 	$sss = "SELECT * FROM product WHERE UPC LIKE '%$ItemUPC%';";
	// 	$qryItem = mysqli_query($db_wgfinance,$sss) or die("$sss<br>".mysqli_error($db_wgfinance));
	// 	if ($qryItem->num_rows > 1)
	// 	{
	// 		$_SESSION['sesnMultiUPC'] = "$ItemUPC";
	// 		#echo "<script>alert(' sesnMultiUPC ');</script>";
	// 	}
	// 	else {
	// 		include_once 'ajaxpos.sales.B.php';
	// 	}
	// }
	// else {
		include 'ajaxpos.sales.B.php';
	// }
}
// echo "<script>location='sales.php';</script>";
?>