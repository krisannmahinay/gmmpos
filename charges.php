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

include_once('charges.0.php');

$W_readonly = "";
$W_disabled = "";
if ($sesnSALESTranStatus>5)
{
    $W_readonly = "readonly";
    $W_disabled = "disabled";
}

echo "<div class='container_16'>";

echo "<form action='charges.php' method='POST'>";

echo "<table width='100%'>";
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
	echo "<th class='wtitle' colspan=1 align=left width='1%' nowrap>
		Tran No: ".
		str_pad($sesnTranRID, 8, "0", STR_PAD_LEFT);
	echo "</th>";
	
	echo "<th class='wpaddPOSlbl' align=right colspan=1>Sales Total</th>";
		
	echo "<th class='wpaddPOSlbl' align=right colspan=1>".
		number_format($sesnSALESTotalAmount, 2)."</th>";
echo "</tr>";	

echo "<tr>";
	echo "<th class='wtitle' colspan=1 align=center nowrap>".
		wfs_Date_from_DATE($sesnSALESTranDate, 4)."</th>";

	echo "<th class='wPOSChens' align=center colspan=1>Charges</th>";
	
	echo "<th class='wPOSChens' align=right colspan=1>".
		number_format($sesnSALESTotalCharges, 2)."</th>";
echo "</tr>";	

echo "<tr>";
	echo "<th class='wtitle' colspan=1 align=center>&nbsp;</th>";

	echo "<th class='wpaddPOSlbl' align=right colspan=1>Discounts</th>";
		
	echo "<th class='wpaddPOSlbl' align=right colspan=1>".
		number_format($sesnSALESTotalDiscounts, 2)."</th>";
echo "</tr>";	

echo "<tr bgcolor='#000066'>";
	#echo "<th>".GetTranStatus($sesnSALESTranStatus)."</th>";
	echo "<th class='wpaddPOSlbl' align=left colspan=1>&nbsp</th>";

	echo "<th class='wpaddPOSlbl' align=right colspan=1>Amount Due</th>";
	echo "<th class='wpaddPOSlbl' align=right colspan=1>".
		number_format($sesnSALESNetAmountDue, 2)."</th>";
echo "</tr>";	

echo "</table>";


	
if ($W_disabled != 'disabled')
{
	echo "<table width=100% border=0 bgcolor='#AAFF00'>";	
	echo "<tr>";
	echo "<th width='50%'>";
		echo "<table width='100%'>";
		
		$mSql = "SELECT * FROM lookupcharges WHERE deleted=0;";
		$mQry = mysqli_query($db_wgfinance,$mSql) OR DIE("CHARGES LOOK up<br>".mysqli_error($db_wgfinance));
		
		/*echo "<tr>";
			echo "<th class='wpaddPOSlbl'>Discount</th>";
			echo "<th class='wpaddPOSlbl' width='1%'>%</th>";
			echo "<th class='wpaddPOSlbl' width='1%'>Deduct</th>";			
			#echo "<th width='1%'>Avail</th>"; 
		echo "</tr>";*/		
		
		while ($tblCharges=$mQry->fetch_object())
		{
			echo "<tr>";
			echo "<th nowrap><input class='button orange' 
				style='display:block; width:100%'
				type=submit name='buttCharge[]' 
				value='$tblCharges->ChargeDesc'></th>";
			echo "</tr>";
		}
		echo "</table>";	
	echo "</th>";
}

if ($W_disabled != 'disabled')
{
	echo "<th valign=top align=center nowrap width='50%'>";
	#$txtAmount = $TranGranTotal;
	#$txtAmount = number_format($txtAmount, 2);
	#echo "<div id='inputArea'>
	echo "<input type='text' name='txtAmount' value='$txtAmount' 
		size=11 style='text-align: right; font-size:30px;'
		style='text-align: right;'>";
	#echo "</div>";
	echo "<br>";
	
	include_once('kpadblue.php');
	
	echo "<br><br>
		<input class='button red' type=button 
			name='buttCLOSE' value='close window' 
			onclick='window.close();'";			
	echo "</th>";
}

echo "<td>"; #don't put any class on me
	include_once('charges.list.panel.php');
echo "</td>";

echo "</tr>";
echo "</table>";

echo "</form>";

echo "</div>";

?>
<?php include "footer.php";?>