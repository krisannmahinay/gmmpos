<?php
echo "<table width='100%'>";
echo "<tr>";
	echo "<th class='wtitle' with='1%' align=center>Delete</th>";
	echo "<th class='wtitle'>DESCRIPTION</th>";
	echo "<th class='wtitle' with='1%' align=right>Amount</th>";
	echo "<th class='wtitle' with='1%' align=right>Charge</th>";
echo "</tr>";		
	
$mSql = "SELECT * FROM `possales_details`
		WHERE EntryType=2 
			AND TranRID='$sesnTranRID' 
			ORDER BY OrderDetailRID;"; #order_charges
		#AND DisLineCanceled=0
$mQry = mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql".mysqli_error($db_wgfinance));
$x=0;
$mGrandT=0;

while ($tblOrders=$mQry->fetch_object())
{
	$mxRID = $tblOrders->OrderDetailRID;

	$bcl=($x%2==0)? "#EEEEEE":"#FFFFFF";
	$x++;
	echo "<tr>";

	$mx = $x;
	if ($tblOrders->DisLineCanceled==1)	
		$mx = "<span style='color: red'>$x</span>";
	else
		if ($W_disabled != 'disabled') 
			$mx = "<a class='button red small' style='display:block;'
				onclick=\"return decision('Are you sure to delete the entry #$x?')\"
				href='charges.entry.del.php?delRowId=$mxRID'>$x</a>";
	echo "<th align=center>$mx</th>";
				
	echo "<td class='wpadd' nowrap>";
		$xDesc = GetChargesInfo($tblOrders->ProductRID); 
		if ($tblOrders->DisLineCanceled==1) 
			echo "<s>$xDesc</s>";
		else
			echo $xDesc;
	echo "</td>";

	echo "<td align=right>";
		$xSRP = $tblOrders->SoldPrice;
		if ($tblOrders->DisLineCanceled==1)
			echo "<s>".number_format($xSRP,2)."</s>";
		else
			echo number_format($xSRP,2);
	echo "</td>";
	
	echo "<td class='extamount' align=right>";
		$xDiscount = $tblOrders->SoldPrice * $tblOrders->SoldQty;
		echo number_format($xDiscount,2);
		$mGrandT += $xDiscount;
	echo "</td>";	
	echo "</tr>";
}

#$sesnSALESTotalDiscounts = GetTransInfo($sesnTranRID, 9); #na compute nas diri $mGrandT

echo "<tr>";
echo "<th class='extamount' colspan=3 align=right>Total CHARGES:</th>";	
echo "<th class='total' align=right colspan=1>".number_format($mGrandT,2)."</th>";
echo "</tr>";

#echo "<tr valign=center  bgcolor='yellow'>";
#echo "<th colspan=2 align=right>Total Charges</th>";
#echo "<th align=right colspan=1>".number_format($sesnSALESTotalCharges,2)."</th>";
#echo "</tr>";

echo "<tr>";
echo "<th class='extamount' align=right colspan=3>Net Amount Due:</th>";
echo "<th class='total' align=right colspan=1>".
	number_format($sesnSALESNetAmountDue, 2)."</th>";			
echo "</tr>";

echo "</table>";
?>