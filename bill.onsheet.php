<head>
<?php
include_once('htmlhead.php');
?>

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

$today = wfsGetSysDate(0);

@$sesnTranRID = $_SESSION['sesnTranRID'] * 1;

require('trans.info.php');



$mSql = "UPDATE possales SET 
	Printed = Printed + 1
	WHERE TranRID=$sesnTranRID;";
#echo "$mSql<br>";
@mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql<br>".mysqli_error($db_wgfinance));

			
@$sesnPRNJournalType = $_SESSION['sesnPRNJournalType'];
@$sesnSALESSrCitPWDLine = $_SESSION['sesnSALESSrCitPWDLine'] * 1; # ang sa class ni ya ha?, reprint issue

$a = 5;
$b = 20;
$c = 10;
$pad = " ";
$orgline = str_repeat("=", 36);

require('or.head.onsheet.php');
?>

<table width='100%' class='table table-condensed table-bordered table-striped table-hover'>
	

	<?php
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
		ORDER BY OrderDetailRID DESC;"; 
			#GroupBy OrderSlipNo,
		
	$mQry = mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql<br>".mysqli_error($db_wgfinance));
	$x=0;
	$mSlip = "";
	$mTtlQty = 0;
	$mGTtl = 0;

	?>
	<thead>
		<tr class='info'>
			<td width='1%' class='text-center'>QTY</td>
			<td width='1%' class='text-center'>UNIT</td>
			<td class='text-center'>DESCRIPTION</td>
			<td width='1%' class='text-center'>SRP</td>
			<td width='1%' class='text-center'>AMOUNT</td>
			<td width='1%' class='text-center'>LINE DISCOUNT</td>
			<td width='1%' class='text-center'>NET AMOUNT</td>
		</tr>
	</thead>

	<tbody class='success'>
	<?php
	$x=0;
	$mSlip = "";
	$mTtlQty = 0;
	$mGTtl = 0;

	while ($tblOrders=$mQry->fetch_object())
	{	
		$xLineDisc = $tblOrders->DiscountApplied;
		$xSRP 	= $tblOrders->SoldPrice;

		echo "<tr>";
			echo "<td class='text-right'>";
				echo number_format($tblOrders->SumQTY, 2);
				$mTtlQty += $tblOrders->SumQTY;
			echo "</td>";

			echo "<td class='text-center'>";
				$mxUnit = GetItemInfoRID($tblOrders->ProductRID, 9);
				$mxUnit = GetUOMInfo($mxUnit, 2); // minor unit
				echo $mxUnit; 
			echo "</td>";

			echo "<td>";
				$xDesc 	= GetItemInfoRID($tblOrders->ProductRID, 1);
				echo $xDesc;
			echo "</td>";

			echo "<td class='text-right'>";
				echo number_format($tblOrders->SoldPrice, 2);
			echo "</td>";

			echo "<td class='text-right'>";
				echo number_format($tblOrders->SumExtendAmount, 2);
			echo "</td>";

			echo "<td class='text-right'>";
				echo number_format($tblOrders->DiscountApplied, 2);
			echo "</td>";

			echo "<td class='text-right'>";
				echo number_format($tblOrders->SumExtendAmount - $tblOrders->DiscountApplied, 2);
				$mGTtl += $tblOrders->SumExtendAmount - $tblOrders->DiscountApplied;
			echo "</td>";
		echo "</tr>";
	}

	// echo "<tr class='info'>";
	// 	echo "<th colspan='9'> ".str_repeat("=",50)." </th>";
	// echo "</tr>";

	echo "<tr class='info'>";
		echo "<th class='text-right'>";
			echo number_format($mTtlQty, 2);
		echo "</th>";

		echo "<th class='text-right' colspan='5'>TOTAL</th>";

		echo "<th class='text-right'>";
			echo number_format($mGTtl, 2);
		echo "</th>";
	echo "</tr>";	
	?>

	<tr class="DontPrint">
		<th id='DontPrint' colspan='6' class='ext-center' nowrap></th>
		<th id='DontPrint' class='text-center' nowrap>
			<a id='DontPrint' class='btn btn-sm btn-warning btn-block' style='text-decoration:none' onclick='window.print()' />PRINT</a>
		</th>
	</tr>
</table>

<table width='100%' class='table table-condensed table-bordered table-striped table-hover'>
	<tr>
		<th class='text-left'><br><br><br><br><br>Prepared by: 
			<?php 
			echo GetPXInfo($sesnLOGGEDPxRID, 1); 
			?> 
		</th>			
		<th class='text-right'><br><br><br><br><br>Received by: _________________________________________</th>			
	</tr>
	</tbody>
</table>

<?php
#set tran status
$mSql = "UPDATE possales SET 
	ForcePrint = 0
	WHERE TranRID='$sesnTranRID';";
@mysqli_query($db_wgfinance, $mSql) OR DIE("$mSql<br>".mysqli_error($db_wgfinance));
?>