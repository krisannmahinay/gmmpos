<?php
@session_start();

include_once 'wfslib/WalnetFunctionsPOS.php';

include_once "wfslib/xcls.sales.php";
$clsSALES = new SALES();

require_once 'wchensPOS.php';

include_once 'sys.inc.php';

$sesnTranRID = $_SESSION['sesnTranRID'] * 1;

$valyu = trim($_REQUEST['valyu']);
$valyu = $valyu * 1;
$jid = $_REQUEST['jid']; 
$orDetlRID = $_REQUEST['orDetlRID'] * 1; 



$mSql="SELECT * FROM `possales_details` 
	WHERE OrderDetailRID='$orDetlRID';";
$detQry = mysqli_query($db_wgfinance, $mSql) OR DIE("$mSql.<br>".mysqli_error($db_wgfinance));

$ProductRID = 0;
$SRP = 0;

if ($productRow = $detQry->fetch_object())
{
	$ProductRID = $productRow->ProductRID;

	$PricingRID = $valyu; // from AJAX

    $mSql = "SELECT * FROM product 
    	WHERE ProductRID='".$ProductRID."' LIMIT 1;";
	// $wfp = fopen("zzz_AJAXkita.zzz", "w");
	// fwrite($wfp, $mSql);
	// fclose($wfp);

    $mQry = mysqli_query($db_wgfinance,$mSql) or die("$mSql<br>".mysqli_error($db_wgfinance));
    if ($tblProX = $mQry->fetch_object())
    {
    	$Description = $tblProX->Description;
    	$UnitCost = $tblProX->UnitCost;

    	// get SRPx from Product based on PricingType used
    	$SRP = $tblProX->SRP1;  // COST - default

		switch ($PricingRID) {
		    case 1:
		        $SRP = $tblProX->SRP1; // Same as UnitCost
		        break;
		    case 2:
		        $SRP = $tblProX->SRP2;
		        break;
		    case 3:
		        $SRP = $tblProX->SRP3;
		        break;
		    case 4:
		        $SRP = $tblProX->SRP4;
		        break;
		    case 5:
		        $SRP = $tblProX->SRP5;
		        break;
		    case 6:
		        $SRP = $tblProX->SRP6;
		        break;
		    case 7:
		        $SRP = $tblProX->SRP7;
		        break;
		    case 8:
		        $SRP = $tblProX->SRP8;
		        break;
		    case 9:
		        $SRP = $tblProX->SRP9;
		        break;
		    case 10:
		        $SRP = $tblProX->SRP10;
		        break;
		    case 11:
		        $SRP = $tblProX->SRP11;
		        break;
		    default:
		    	$SRP = $tblProX->SRP1;
		}
	}
}


// SoldQty
if ($jid=="idtxtQtyUp")
{
	$mSql = "UPDATE `possales_details` SET 
		SoldQty = '". $valyu ."'
		, GrossLine = ". $valyu ." * `SoldPrice`
		, ExtendAmount = ". $valyu ." * `SoldPrice` - `DiscountApplied`
		WHERE OrderDetailRID='". $orDetlRID ."';";

	$wfp = fopen("zzz_qty.zzz", "w");
	fwrite($wfp, $mSql);
	fclose($wfp);

	mysqli_query($db_wgfinance,$mSql) or die($mSql."<br>".mysqli_error($db_wgfinance));

	CalcSalesTotal($sesnTranRID);
	$clsSALES->SalesRow($sesnTranRID);
}

//OpenSRP
if ($jid=="idOpenSRP")
{
	$mSql = "UPDATE `possales_details` SET 
			SoldPrice = '". $valyu ."'
			, SRP = '". $valyu ."'
			, GrossLine = `SoldQty` * ". $valyu ."
			, ExtendAmount = ". $valyu ." * `SoldQty` - `DiscountApplied`
			WHERE OrderDetailRID='". $orDetlRID ."';";

		mysqli_query($db_wgfinance,$mSql) or die($mSql."<br>".mysqli_error($db_wgfinance));
		CalcSalesTotal($sesnTranRID);
		$clsSALES->SalesRow($sesnTranRID);

}



// idCBOPricingRID
if ($jid=="idCBOPricingRID")
{
	$Pricing = null;
	$MUPercentage = 0;
	$markUp = 0;

	$sql = "SELECT * FROM lkup_pricing WHERE deleted=0 AND PricingRID = ". $valyu .";";
	$qry = mysqli_query($db_wgfinance, $sql) or die("$sql<br>".mysqli_error($db_wgfinance));
	if ($row = $qry->fetch_object())
	{
		$Pricing = $row->Description;
		$MUPercentage = $row->MUPercentage;
	}


	$mSql = "UPDATE `possales_details` SET 
		PricingRID = '". $valyu ."'
		, Pricing = '". $Pricing ."'
		, SRP = '". $SRP ."'
		, SoldPrice = '". $SRP ."'
		, GrossLine = `SoldQty` * ". $SRP ."
		, ExtendAmount = ". $SRP ." * `SoldQty` - `DiscountApplied`
		WHERE OrderDetailRID='". $orDetlRID ."';";

	// $wfp = fopen("zzz_idOpenSRP1.zzz", "w");
	// fwrite($wfp, $mSql);
	// fclose($wfp);

	mysqli_query($db_wgfinance,$mSql) or die($mSql."<br>".mysqli_error($db_wgfinance));

	CalcSalesTotal($sesnTranRID);

	$clsSALES->SalesRow($sesnTranRID);

}




// idCBOTaxCode
if ($jid=="idCBOTaxCode")
{
	$TaxRate = 0;

	$sql = "SELECT * FROM lkup_salestax WHERE deleted=0 AND SalesTaxRID = ". $valyu .";";
	$qry = mysqli_query($db_wgfinance, $sql) or die("$sql<br>".mysqli_error($db_wgfinance));
	if ($row = $qry->fetch_object())
	{
		$SalesTaxRate = $row->Rate;
		$SalesTaxCode = $row->Description;
	}

// vat = ((sales / 112) * 100 ) * 12


	$mSql = "UPDATE `possales_details` SET 
		SalesTaxRID = '". $valyu ."'
		, SalesTaxRate = ". $SalesTaxRate ."
		, SalesTaxCode = '". $SalesTaxCode ."'


		, VatAmnt = ExtendAmount * ". $SalesTaxRate / 112 . "


		WHERE OrderDetailRID='". $orDetlRID ."';";
		// , GrossLine = `SoldQty` * ". $valyu ."
		// , ExtendAmount = ". $valyu ." * `SoldQty` - `DiscountApplied`
		$wfp = fopen("zzz_TaxCode.zzz", "w");
		fwrite($wfp, $mSql);
		fclose($wfp);

	mysqli_query($db_wgfinance,$mSql) or die($mSql."<br>".mysqli_error($db_wgfinance));
	
	CalcSalesTotal($sesnTranRID);

	$clsSALES->SalesRow($sesnTranRID);
}


// Line Discount
if ($jid=="idDiscountApplied")
{
	$mSql = "UPDATE `possales_details` SET 
		ExtendAmount = `SoldQty` * `SoldPrice` - " . $valyu ."
		, DiscountApplied = '". $valyu ."'
		WHERE OrderDetailRID='". $orDetlRID ."';";

	// $wfp = fopen("zzz_zzz".time().".zzz", "w");
	// fwrite($wfp, $mSql);
	// fclose($wfp);

	mysqli_query($db_wgfinance,$mSql) or die($mSql."<br>".mysqli_error($db_wgfinance));
}


// wfs: Aug 3/2019, Sales Ref Number
if ($jid=="idRefNo")
{
	$mSql = "UPDATE `possales` SET 
	RefNo = '". $valyu ."'
	WHERE TranRID='". $sesnTranRID ."';";

	mysqli_query($db_wgfinance,$mSql) or die($mSql."<br>".mysqli_error($db_wgfinance));
	
	$wfp = fopen("zzz_zzz".time().".zzz", "w");
	fwrite($wfp, $mSql);
	fclose($wfp);
}

//($sesnTranRID, "possales_details");	
?>