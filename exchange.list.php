<head>
<link rel='stylesheet' type='text/css' href='receiving.css'>
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
<body onKeyDown='funcDetectKey(event);'>
<?php
require_once("wfslib/wfsclass.php");
$clsWalnet = new clsWalnet();
include_once('wfslib/WalnetFunctionsPOS.php');
@wfsDBfnb();

$mSql = "SELECT * FROM exchange ORDER BY ExchangeRID DESC;";
$mQry = mysql_query($mSql) OR DIE("$mSql".mysql_query());

echo "<h2>RETURNS for EXCHANGE</h2>";

echo "<table width=1% border=0 cellpadding=2px cellspacing=3px bgcolor='#FFFFFF'>";
echo "<tr bgcolor='yellow'>";
echo "<th class='nosides' width='1%' nowrap>File No</th>";
echo "<th class='nosides' width='1%' nowrap>O.R. Number</th>";
echo "<th class='nosides' width='1%'>O.R. DATE</th>";
echo "<th class='nosides' width='1%' nowrap>O.R. By</th>";

echo "<th class='nosides' width='1%'>Exchange Date</th>";
echo "<th class='nosides' width='1%'>Exchanged By</th>";
#echo "<th width='1%'>Last Updated</th>";
	
echo "<th class='nosides' width='1%'>Total Qty</th>";
echo "<th class='nosides' width='1%'>Total Amount</th>";
echo "<th class='nosides'>Status</th>";

echo "</tr>";

$x = 0;
while ($tblExchange=mysql_fetch_object($mQry))
{	
	$bcl=($x%2==0)? "#EEEEEE":"#CCCCCC";
	$x++;
	echo "<tr bgcolor='$bcl'>";
	echo "<th class='nosides'>
		<a class='blue' href='exchange.prep.php?ExchangeRID=$tblExchange->ExchangeRID'>".
		str_pad($tblExchange->ExchangeRID, 4, "0", STR_PAD_LEFT)."
		</a></th>";
		
	echo "<th class='nosides' nowrap>".str_pad($tblExchange->TranRID, 4, "0", STR_PAD_LEFT)."</th>";
	
	$xDt = GetOLDTransInfo($tblExchange->TranRID, 1); #OR Date, note the function - get from the ARCHIVE table
	echo "<td class='nosides' nowrap>".wfs_Date_from_DATE($xDt , 1)."</td>";	

	
	$ORBy1 = GetOLDTransInfo($tblExchange->TranRID, 5); # this info is not from the returns table but orders archive table
	$ORBy2 = GetUserInfo($ORBy1, 1); #ang nag OR
	echo "<td class='nosides' align='center' nowrap>$ORBy2</td>";	
	
	
	$mX = ($tblExchange->ExchangeDate==NULL) ? "&nbsp;" : wfs_Date_from_DATE($tblExchange->ExchangeDate, 1);
	echo "<td class='nosides' nowrap>$mX</td>";	
	
	$RetsBy = GetUserInfo($tblExchange->ExchangeEnteredBy, 1); #ang nag Return sang stock
	echo "<td class='nosides' align='center'>&nbsp;$RetsBy</td>";	
	
	echo "<td class='nosides' align='right' nowrap>".number_format($tblExchange->TotalQtyExchanged, 2)."</td>";
	echo "<td class='nosides' align='right' nowrap>".number_format($tblExchange->TotalAmount, 2)."</td>";	
	echo "<td class='nosides' align='center' nowrap>".GetSalesReturnStatus($tblExchange->ExchangeStatus)."</td>";
	echo "</tr>";	
}
echo "</table>";
?>
</body>