<!DOCTYPE html>
<html>
<head>
<title>GMMR POS</title>
<?php
include_once('htmlhead.php');
?>
<script language="JavaScript">
	document.domain='<?=$_SERVER['SERVER_NAME']?>';
	function popUpClosed() 
	{
		window.location.reload();
	}
	function funcDetectKey(evt)
	{
		switch(evt.keyCode)
		{
			case 27:    //ESCped
				window.close();
		      break;
		}//end of switch	
	}
	function decision(message)
	{
		if(confirm(message) )
		{
			return true;
		}
		else
		{
			return false;
		}
	}
</script>
</head>
<body onunload="window.opener.popUpClosed();" onKeyDown='funcDetectKey(event);'>
<?php
@session_start();
//turn off PHP compatibility warnings
ini_set("session.bug_compat_warn","off");

require('wchensPOS.php');

require_once("wfslib/xcls.sales.php");
$clsSALES = new SALES();

include_once('wfslib/WalnetFunctionsPOS.php');

$today = wfsGetSysDate(0);

include_once('sys.inc.php');

if ($sesnLOGGEDPxRID<=0)
{
	echo "<script>window.close();</script>";
}

require('trans.info.php');

if ($sesnTranRID==0)
{
	echo "<script>alert('TABLE DATA IS NOT COMPLETE, select a table first!');</script>";
	echo "<script>window.close();</script>";
}

include_once('discounts.0.php');

$W_readonly = "";
$W_disabled = "";
if ($sesnSALESTranStatus>5)
{
    $W_readonly = "readonly";
    $W_disabled = "disabled";
}

echo "<div class='container_16'>";

echo "<form action='discounts.php' method='POST'>";

echo "<table class='table table-bordered' width='1%'>";
#echo "<tr>";
	#echo "<th class='wpaddPOSlbl' colspan=9>MANAGE DISCOUNTS";
#echo "</th>";
/*
echo "<th class='nosides' align=center colspan=1>
	<span style='color: blue'>Table</span></th>";
echo "<th class='nosides' align=right colspan=3>
		<span style='font-size:14pt; font: impact; color: blue'>".
		str_pad($sesnTranRID, 8, "0", STR_PAD_LEFT)."
		</span>
	</th>"; */
	
echo "</tr>";

echo "<tr>";
	echo "<th class='wtitle text-left' colspan=1 width='1%' nowrap>Tran No: </th>";
	
	echo "<th class='wtitle text-left' width='1%' nowrap>";
		echo str_pad($sesnTranRID, 8, "0", STR_PAD_LEFT);
	echo "</th>";

	echo "<td></td>";

	echo "<th class='wtitle text-right' width='1%' nowrap>Sales Total</th>";
		
	echo "<th class='wtitle text-right' width='1%'>".
		number_format($sesnSALESTotalAmount, 2)."</th>";
echo "</tr>";	

echo "<tr>";
	echo "<th class='wtitle text-center' nowrap> </th>";

	echo "<th class='wtitle text-center' nowrap>".
		wfs_Date_from_DATE($sesnSALESTranDate, 4)."</th>";

	echo "<td></td>";

	echo "<th class='wtitle text-right'>Charges</th>";
	
	echo "<th class='wtitle text-right'>".
		number_format($sesnSALESTotalCharges, 2)."</th>";
echo "</tr>";	

echo "<tr>";
	echo "<th class='wtitle text-center'>&nbsp;</th>";

	echo "<td class='wtitle text-center'></td>";
	echo "<td></td>";

	echo "<th class='wPOSChens text-right'>Discounts</th>";
		
	echo "<th class='wPOSChens text-right'>".
		number_format($sesnSALESTotalSCPWDDiscounts, 2)." </th>";
echo "</tr>";	

echo "<tr bgcolor='#000066'>";
	#echo "<th>".GetTranStatus($sesnSALESTranStatus)."</th>";
	echo "<th class='wtitle text-left'>&nbsp</th>";

	echo "<td class='wtitle text-center'></td>";
	echo "<td></td>";

	echo "<th class='wtitle text-right'>Amount Due</th>";
	echo "<th class='wtitle text-right'>".
		number_format($sesnSALESNetAmountDue, 2)."</th>";
echo "</tr>";	

echo "</table>";


if ($W_disabled != 'disabled')
{
	echo "<table class='table table-bordered'  border=0 bgcolor='#AAFF00'>";	
	echo "<tr>";
	echo "<th width='50%'>";
		echo "<table class='table table-condensed'>";
		
		$mSql = "SELECT * FROM lookupdiscounts WHERE deleted=0;";
		$mQry = mysqli_query($db_wgfinance,$mSql) OR DIE("CHARGES LOOK up<br>".mysqli_error($db_wgfinance));
		
		/*echo "<tr>";
			echo "<th class='wpaddPOSlbl'>Discount</th>";
			echo "<th class='wpaddPOSlbl' width='1%'>%</th>";
			echo "<th class='wpaddPOSlbl' width='1%'>Deduct</th>";			
			#echo "<th width='1%'>Avail</th>"; 
		echo "</tr>";*/		
		
		while ($tblDiscounts=$mQry->fetch_object())
		{
			echo "<tr>";

			echo "<td></td>";
			
			echo "<td width='1%' class='active text-right' nowrap>
				$tblDiscounts->DiscDesc %
				</td>";

			echo "<th width='1%' class='active' nowrap>
				<input class='btn btn-warning' 
				style='display:block; width:100%'
				type='submit' name='buttDiscount[]' 
				value='$tblDiscounts->DiscPercent'>
			</th>";  // DiscDesc
			

			/*
			$mPerc = ($tblDiscounts->DiscPercent*1)/100;
			$mDiscount = $mGrossy * $mPerc;
			echo "<td class='nosides' align='right'>".
				number_format($mDiscount, 2)."</td>";
				
			$mDiscounted = $mGrossy * (1-$mPerc);
			*/
			#echo "<td class='nosides' align='right'>".number_format($mDiscounted, 2)."</td>";			
			
			

			echo "</tr>";
		}
		echo "</table>";	
	echo "</th>";
		
	echo "<th align='left' style='color:black;' class='warning' width='1%' nowrap>
		<br>CARD Deatils<br>";
		
		echo "<table class='table table-condensed info'>";
			echo "<tr>
				<td>ID Number<div id='EntriesArea'>
						<input type='text' name='txtIDNo' size=10></div>
				</td></tr>";
			echo "<tr><td class=''>Name<div id='EntriesArea'>
				<input type='text' name='txtName' size=10></div></td></tr>";
			echo "<tr><td class=''>Expiry:<div id='EntriesArea'>
				<input type='date' name='txtExpiry' size=10></div></td></tr>";
			//echo "<tr><td class=''><br>PROMO:<div id='EntriesArea'>
			//	<input type='text' name='txtPromoNote' size=10></div></td></tr>";
		echo "</table>";
	echo "</th>";
}

if ($W_disabled != 'disabled')
{
	//echo "<th class='text-center' width='50%' nowrap>";
	//echo "<input type='text' name='txtAmount' value='$txtAmount' 
	//	size=11 style='text-align: right; font-size:30px;'
	//	style='text-align: right;'>";
	//echo "<br>";
	
	//include_once('kpadblue.php');
	
	echo "<br><br>
		<input class='btn btn-danger' type=button 
			name='buttCLOSE' value='close window' 
			onclick='window.close();'";			
	echo "</th>";
}


	echo "<td>";
	include_once('discounts.list.panel.php');
	echo "</td>";
echo "</tr>";
echo "</table>";

echo "</form>";
echo "</div>";
?>
<?php include "footer.php";?>