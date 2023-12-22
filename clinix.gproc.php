<?php
@session_start();
//turn off PHP compatibility warnings
ini_set("session.bug_compat_warn","off");

require_once("wfslib/xcls.sales.php");
$clsSALES = new SALES();

include_once('wfslib/WalnetFunctionsPOS.php');
require('wchensPOS.php');

include_once('sys.inc.php');
if ($sesnLOGGEDPxRID<=0)
{
	echo "<script>location='index.php';</script>";
}

$sesnProcessClnxRID = $_SESSION['sesnProcessClnxRID'];
$sesnSALESTranDateTime = $_SESSION['sesnSALESTranDateTime'];

require('wchensMDX.php');

$mSql = "SELECT * FROM clinix WHERE RID='$sesnProcessClnxRID';";
$mQry = mysqli_query($db_wgfinance,$mSql) or die("<b>$_SERVER[PHP_SELF]</b><br>$mSql<br>".mysqli_error($db_wgfinance));
if ($tblClinix=$mQry->fetch_object())
{
	$mxiClinixRID = $tblClinix->RID;
	$mxiPxRID = $tblClinix->PxRID;
	$mxiDokPxRID = $tblClinix->DokPxRID;
	$mxiDateVisit = $tblClinix->DateVisit;
	$mxiDiscount = $tblClinix->Discount;
	$mxiAmountDue = $tblClinix->AmountDue;
	$mxiTranStatus = $tblClinix->TranStatus;
	$mxiPOSTranStatus = $tblClinix->POSTranStatus;
	
	
	
	# NOW CREATE THE TRANSACTION RECORD
	require('wchensPOS.php');
	require('trans.info.php');
	if ($sesnTranRID==0)
	{
		#echo "<script>alert('NO TRANSACTION Started!');</script>";
		#echo "<script>window.close();</script>";
		$sesnTranRID = wfs_CreateNewTrans($sesnLOGGEDPxRID);
		$_SESSION['sesnTranRID'] = $sesnTranRID;
	
		#reload
		include('trans.info.php');
	}

	
	
	#Add the details first
	require('wchensMDX.php');
	$mSql = "SELECT * FROM clinix_charges 
			WHERE clinix_charges.ClinixRID = $mxiClinixRID
				AND clinix_charges.Deleted=0;";
	$mQryCharge = mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql.<br>".mysqli_error($db_wgfinance));	
	while ($tblCharge = $mQryCharge->fetch_object())
	{
		$mFeeRID = $tblCharge->FeeRID;
		$mProductRID = GetItemInfoFEERID($mFeeRID);
		$mAmount = $tblCharge->Amount;

		
		if ($mProductRID > 0)
		{
			require 'wchensPOS.php';
			$mSql = "INSERT INTO `'possales_details'` SET
				TranRID = '$sesnTranRID', 
				ProductRID = '$mProductRID', 
				SoldPrice = '$mAmount',
				SoldQty		= 1,
				ExtendAmount = '$mAmount';";
			@mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql.<br>".mysqli_error($db_wgfinance));	
			#echo "<br>".$mSql ;
		}
	}
	
	require 'wchensPOS.php';
	
	if ($mxiDiscount > 0)
	{
		#use regular DiscountRID 3333338
		$mSql = "INSERT INTO `possales_details` SET 
		ProductRID = '3333338', 
		SoldPrice = '$mxiDiscount',
		SRP = '$mxiDiscount',
		SoldQty = 1,
		EntryType = 3,

		Stamped = '$sesnSALESTranDateTime',
					
		TranRID = $sesnTranRID;";
		#echo $mSql;
		@mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql.<br>".mysql_error());
	}
	
	$mSql = "UPDATE possales SET 
		PxRID='$mxiPxRID',
		ClinixRID = '$mxiClinixRID',
		FlagSales = 0,
		FlagCancelledSales = 0
		WHERE TranRID = '$sesnTranRID';";
	#echo $mSql;
    @mysqli_query($db_wgfinance,$mSql) OR die("$mSql<br>".mysqli_error($db_wgfinance));
	
	#if (GetOrgSetUp(11)==1)	
	#{
		/*$mxiClinixRID = 0;
		
		$mSql = "SELECT * FROM possales  
			WHERE TranRID = '$sesnTranRID' AND Deleted=0;";
		$mQryClnxD = mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql.<br>".mysqli_error($db_wgfinance));	
		if ($tblClxD = $mQryClnxD->fetch_object())
		{
			$mxiClinixRID = $tblClxD->ClinixRID;
		}*/
	
		
		#sa payment ni dapat, pag finishing na
		#include_once('clinix.grab.close.php');
	#}
	
	CalcSalesTotal($sesnTranRID);
	CalcTransChargesTotal($sesnTranRID,'possales_details');

	//this discount routine was moved to CalsSalesTotal in MGD in Feb 2016
	//CalcTransDiscountsTotal($sesnTranRID,'possales_details');
}

echo "<script>location='sales.php';</script>";
?>
</body>