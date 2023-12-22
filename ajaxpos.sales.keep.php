<?php
@session_start();

include_once('wfslib/WalnetFunctionsPOS.php');

include_once("wfslib/xcls.sales.php");
$clsSALES = new SALES();

require_once('wchensPOS.php');

include_once('sys.inc.php');

$q = $_REQUEST['upc'];
$jid = $_REQUEST['jid']; 
$mxTranRID = $_REQUEST['trnid'] * 1; 


# feb 2016, MGD
$sesnSALESTranDateTime = $_SESSION['sesnSALESTranDateTime'];


if ($jid=="txtPOSID" || $jid=="idPLU")
{
	$mQ = 0;

	if (strstr($q, '*'))
	{
		$x = $q;
		$a = strstr($q, '*');
		$q = substr($a, 1);

		$mQ = substr($x, 0, strpos($x, "*"));
		$mQ *= 1;	
		$mQ = ($mQ<0)? 0 : $mQ;
	}
	
	$mQty = ($mQ<=0 || $mQ==NULL)? 1 : $mQ;

	#search item
	$ItemUPC = $q;
	
	$sss = "SELECT * FROM product WHERE UPC='$ItemUPC';";
	$qryItem = mysqli_query($db_wgfinance,$sss) or die("$sss<br>".mysqli_error($db_wgfinance));
	if ($tblItem = $qryItem->fetch_object())
	{ 
		#create the transaction record first
		if ($mxTranRID<=0)
		{
			$mxTranRID = wfs_CreateNewTrans($sesnLOGGEDPxRID);
			$_SESSION['sesnTranRID'] = $mxTranRID;
		}
		
		$mSRP = $tblItem->SRP * 1;
		$mCost = $tblItem->UnitCost * 1;
		$mIRID = $tblItem->ProductRID * 1;

		$mSql = "SELECT * FROM possales_details 
			WHERE ProductRID = '$mIRID' 
				AND TranRID = '$mxTranRID' 
				AND DisLineCanceled=0;";
		$mQry = mysqli_query($db_wgfinance,$mSql) or die("$sss<br>".mysqli_error($db_wgfinance));
		if ($tblFound = $mQry->fetch_object())
		{ 
			$mxiCount = $tblFound->SoldQty + $mQty; #DUGANG
			
			$mExtAmnt = $mSRP * $mxiCount; #just a formality a
			
			$sqlIn = "UPDATE possales_details SET 
				SoldQty = '$mxiCount',
				ExtendAmount = '$mExtAmnt'
				WHERE ProductRID = '$mIRID' AND TranRID = '$mxTranRID'
					AND DisLineCanceled=0;";
		}
		else
		{
			$mExtAmnt = $mSRP * $mQty; #just a formality a
			
			$sqlIn = "INSERT INTO `possales_details` SET 
				ProductRID = '$mIRID',
				SoldQty = '$mQty',
				UnitCost = '$mCost',
				SRP = '$mSRP',
				SoldPrice = '$mSRP',
				ExtendAmount = '$mExtAmnt',

				Stamped = '$sesnSALESTranDateTime',

				TranRID = '$mxTranRID';";	
		}
		@mysqli_query($db_wgfinance, $sqlIn) or die(mysqli_error($db_wgfinance));	

		CalcSalesTotal($mxTranRID);
		#$clsSALES->SalesRow($mxTranRID); 
	}
	else
	{	
		$sqlIn = "INSERT INTO possales_details SET 
			ProductRID = '".$clsSALES->NOTPLU."',
		  	TranRID = '$mxTranRID';";		
		@mysqli_query($db_wgfinance,$sqlIn) or die(mysqli_error($db_wgfinance));	
	}
}
echo "<script>location='sales.php';</script>";
?>