<?php
// mgd
// please consider sa sales.0.php because this might not be loaded/required there
//require('sys.inc.php');

# feb 2016, MGD
$sesnSALESTranDateTime = $_SESSION['sesnSALESTranDateTime'];

$sss = "SELECT * FROM product WHERE UPC='" . $ItemUPC . "';";
$qryItem = mysqli_query($db_wgfinance, $sss) 
	OR DIE($sss . "<br>".mysqli_error($db_wgfinance));
	
	// $wfp = fopen("zzz_janelsY.zzz", "w");
	// fwrite($wfp, $sss);
	// fclose($wfp);
	
if ($tblItem = $qryItem->fetch_object())
{ 
	#SAYLO SA sales.php
	// SA AJAX sa MGD dito sa ajazpos.sales.php
	/*#create the transaction record first
	if ($mxTranRID<=0)
	{
		$mxTranRID = wfs_CreateNewTrans($sesnLOGGEDPxRID);
		$_SESSION['sesnTranRID'] = $mxTranRID;
	}*/
	$mxTranRID = $_SESSION['sesnTranRID'] * 1; 

	#echo "<script>alert('possales_details!');</script>";
	
	$mCSMinor = $tblItem->CSMinor * 1;
	// disable lang anay sa janels', June 19, 2015


	// re-activated, Dec 3, 2019
	if ($mCSMinor<=0)
		echo "<script>alert('Warning! This item is out of stock!');</script>";
	elseif ($mCSMinor<11 AND $mCSMinor>0)
		echo "<script>alert('Warning! This item is almost depleted: $mCSMinor on stock!');</script>";
		
	// $wfp = fopen("zzz_MarsY.txt", "w");
	// fwrite($wfp, "mCSMinor: ".$mCSMinor);
	// fclose($wfp);


	$mSRP = $tblItem->SRP * 1;
	$mTrigQTY = $tblItem->TriggerQTY * 1;
	$mTrigSRP = $tblItem->TriggerSRP * 1;
	$mCost = $tblItem->UnitCost * 1;

	$mIRID = $tblItem->ProductRID * 1;
	
	#$mSRZitizen = $tblItem->SeniorStandardSRP * 1;
	$mSRZitizen = $tblItem->SRP3 * 1; #not that 1
	
	$mSETCombo = $tblItem->SetCombo * 1;
	$mComboSRP = 0;
	$mComboUnitCost = 0;
	
	if ($mSETCombo == 1)
	{
		$mSRP = 0;
		$mCost = 0;
	}
	
	#if triggerSRP not defined, restore $tblItem->SRP
	if ($mTrigSRP == 0) $mTrigSRP = $mSRP;

	$mSql = "SELECT * FROM `possales_details` 
		WHERE ProductRID = '$mIRID' 
		AND TranRID = '$mxTranRID' 
		
		AND DisLineCanceled=0;";

		//AND Stamped = '$sesnSALESTranDateTime' 

	$mQry = mysqli_query($db_wgfinance,$mSql) or die("$mSql<br>".mysqli_error($db_wgfinance));
	if ($tblFound = $mQry->fetch_object())
	{ 
		#DUGANG ang Quantity sa old record found
		$mxiCount = $tblFound->SoldQty + $mQty; 

		// line disabled at MGD 20160302
		// if (GetOrgSetUp(13)==1) if ($mxiCount >= $mTrigQTY) $mSRP = $mTrigSRP;
		
		$mExtAmnt = $mSRP * $mxiCount; #just a formality a

		# Feb 2016, potential error mag update, should check the Stamped 
		# field that matches TranDateTime

		$sqlIn = "UPDATE `possales_details` SET 
			SRP = '$mSRP'
			, SoldPrice = '$mSRP'
			, ComboSRP = '$mComboSRP'
			, ComboUnitCost = '$mComboUnitCost'
			, SoldPriceSRCitz = '$mSRZitizen'
			, SoldQty = '$mxiCount'
			, GrossLine = '$mExtAmnt'
			, ExtendAmount = '$mExtAmnt'
			WHERE ProductRID = '$mIRID' 
				AND TranRID = '$mxTranRID'
				AND Stamped = '$sesnSALESTranDateTime'
				AND DisLineCanceled=0;";
	}
	else
	{
		// line disabled at MGD 20160302
		// if (GetOrgSetUp(13)==1) if ($mQty >= $mTrigQTY) $mSRP = $mTrigSRP;
		
		$mExtAmnt = $mSRP * $mQty; #just a formality a
		
		$sqlIn = "INSERT INTO `possales_details` SET 
			ProductRID = '$mIRID',
			
			UnitCost = '$mCost',
			SRP = '$mSRP',
			SoldPrice = '$mSRP',
			
			SoldPriceSRCitz = '$mSRZitizen',
			
			ComboSRP = '$mComboSRP',
			ComboUnitCost = '$mComboUnitCost',
			
			SoldQty = '$mQty',
			GrossLine = '$mExtAmnt',
			ExtendAmount = '$mExtAmnt',

			Stamped = '$sesnSALESTranDateTime',
			TranRID = '$mxTranRID';";	
	}
	
	// $wfp = fopen("zzz_errors".time().".zzz", "w");
	// fwrite($wfp, $sqlIn);
	// fclose($wfp);
		
	mysqli_query($db_wgfinance, $sqlIn) OR DIE(mysqli_error($db_wgfinance));	

	if ($mSETCombo == 1) {
		$_SESSION['sesnPartOfComboRID'] = $mIRID * 1; 	
		include_once('set.combo.addmembers.php');
	}	
	
	CalcSalesTotal($mxTranRID, "possales_details");
	#$clsSALES->SalesRow($mxTranRID); 
}
else
{	
	$sqlIn = "INSERT INTO `possales_details` SET 
		ProductRID = '".$clsSALES->NOTPLU."',
		Stamped = '".$sesnSALESTranDateTime."',
		TranRID = '".$mxTranRID."';";		
	mysqli_query($db_wgfinance,$sqlIn) OR DIE(mysqli_error($db_wgfinance));	
}
$_SESSION['sesnMultiUPC'] = NULL;

// for each item scanned, kill this session HERE!
// in case Discount was editted, it will be saved, also in product picker F4
$_SESSION['sesnEditLineDiscount_ORID'] = NULL;
?>
