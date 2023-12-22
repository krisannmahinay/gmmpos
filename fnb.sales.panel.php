<?php
@session_start();
echo "<table width='100%' border=0 bgcolor='#afa'>";

echo "<tr>";
	echo "<th class='wtitle' width='1%' align=center>VOID</th>";		
	echo "<th class='wtitle' width='1%'>QTY</th>";
	echo "<th class='wtitle' >DESCRIPTION</th>";
	echo "<th class='wtitle' width='1%' align=right>Price</th>";
	echo "<th class='wtitle' width='1%' align=right>Extend</th>";
	echo "<th class='wtitle' width='1%' align=right>&nbsp;</th>";
echo "</tr>";		
	
$mSql = "SELECT 
		OrderDetailRID, SoldPrice, EntryType, DisLineCanceled,
		Served, SoldQty, OrderSlipNo, ProductRID, TranRID,
		SUM(CancelledQty) AS SumCancQTY, 
		SUM(SoldQty) AS SumQTY 
		
		FROM possales_details 
		
		WHERE TranRID='$sesnTranRID' AND EntryType = 0 
		GROUP BY OrderSlipNo, ProductRID, DisLineCanceled
		ORDER BY ProductRID, OrderDetailRID DESC;"; 
		#OR TranRID>1000 
$mQry = mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql<br>".mysqli_error($db_wgfinance));
$x=0;
$mSlip = "";
$mTQty = 0;
$mTAmnt = 0;
$mGrandT = 0;
$osbg = "#CCDDCC";
while ($tblOrders=$mQry->fetch_object())
{	
	$bcl=($x%2==0)? "#EEEEEE":"#FFFFFF";
	$x++;
	echo "<tr bgcolor='$bcl'>";
		
	$xcanc = "&nbsp;";
	if ($tblOrders->DisLineCanceled==1)
		$xcanc = "<span style='color: red'>$x</span>";
	else
	{
		if ($tblOrders->EntryType>0)
		{
			$mEntry = array("","transfer","<span style='color:red'>
				charges</span>","<span style='color:red'>discounts</span>");
			$xcanc="<span style='color:blue;'>".$mEntry[$tblOrders->EntryType]."</span>";
		}
		else
		{
			#
			if ($W_disabled != 'disabled') 
			{
				/*
				$xcanc = "<input class='redcancel' type='button' 
					name='buttVoidEntry[]' 
					value='$tblOrders->OrderDetailRID' />";
				*/
				$xcanc = "<a class='button red small' style='display:block'
				href='void.entry.prep.php?voidRID=$tblOrders->OrderDetailRID&TranRID=$sesnTranRID'>
				$x</a>"; #$tblOrders->OrderDetailRID
			}
		}
	}
	echo "<th align=center valign=center>$xcanc</th>";
			
	echo "<td class='extamount' align=center valign=center>";
		if ($tblOrders->DisLineCanceled==1 && $tblOrders->EntryType==0)
			echo "<s>$tblOrders->SumCancQTY</s>";
		else
		{
			if ($tblOrders->EntryType==0)
				echo $tblOrders->SumQTY;	
			else
				echo "&nbsp;";
		}	
	echo "</td>";
	
	#EntryType==0     -IS PLU
	if ($tblOrders->EntryType==0)
	{
		$mTQty += $tblOrders->SumQTY; #$tblOrders->SoldQty;
		$mTAmnt += $tblOrders->SumQTY * $tblOrders->SoldPrice;	
		$mGrandT += $tblOrders->SumQTY * $tblOrders->SoldPrice;	
	}
	if ($tblOrders->EntryType==2) $mTAmnt += $tblOrders->SumQTY * $tblOrders->SoldPrice;
	if ($tblOrders->EntryType==3) $mTAmnt -= $tblOrders->SumQTY * $tblOrders->SoldPrice;
	echo "<td class='wpadd'>";
		if ($tblOrders->EntryType==0) $xDesc=GetItemInfoRID($tblOrders->ProductRID, 1); #1-deswc, 3-use Button Label
		
		if ($tblOrders->EntryType==1) $xDesc=$tblOrders->Remarks;
		if ($tblOrders->EntryType==2) $xDesc=GetChargesInfo($tblOrders->ProductRID); #actually ChargeRID
		if ($tblOrders->EntryType==3) $xDesc=GetDiscountsInfo($tblOrders->ProductRID,1); #actually DiscRID		
		
		if ($tblOrders->DisLineCanceled==1)	
			echo "<s>$xDesc</s>";
		else
			echo $xDesc;
			
	echo "</td>";

	echo "<td class='extamount' align=right>";
		$xSRP = $tblOrders->SoldPrice;
		if ($tblOrders->DisLineCanceled==1)	
			echo "<s>".number_format($xSRP,2)."</s>";
		else
			echo number_format($xSRP,2);
	echo "</td>";
			
	echo "<td class='extamount' align=right>";
		if (! $tblOrders->DisLineCanceled==1)	
		{
			if ($tblOrders->EntryType==3)
				echo number_format(($tblOrders->SumQTY * $tblOrders->SoldPrice)*-1, 2);
			else
				echo number_format(($tblOrders->SumQTY * $tblOrders->SoldPrice), 2);
		}
		else
		{
			echo "&nbsp;";
		}
	echo "</td>";
			
	echo "<td class='ordpan'>&nbsp;&nbsp;&nbsp;</td>"; #scroller space
echo "</tr>";
}

#Effect the last row loop color
$bcl=($x%2==0)? "#EEEEEE":"#FFFFFF";
		
#$TranTtlQty = 0;
#$TranAmount = 0;
		
echo "<tr valign=center bgcolor='$bcl'>";
	echo "<th class='ordpan'>&nbsp;</th>";
	echo "<th class='total' colspan=1 align=center><u>
		$sesnSALESTotalQty</u></th>";
	echo "<td class='wpadd' align='left'><i>items</i></td>";
	echo "<th class='wpadd' colspan=1 align=right> Total</th>";
	#echo "<th class='ordpan' align=center>&nbsp;</th>";
	#echo "<th class='ordpan' align=center>&nbsp;</th>";
	#echo "<th class='ordpan' align=center>&nbsp;</th>";	
	echo "<th class='total' align=right><u>".
		number_format($sesnSALESTotalAmount,2)."</u></th>";
	
	echo "<td class='ordpan'>&nbsp;</td>"; #scroller space
	
	/* #RESERVE FOR TOUCH SCREEN 
	echo "<th align=center>
		<input class='black' type=button name='buttPrnAll' value='print all'>
		</th>";
	echo "</tr>";
	*/
		
		
	#include_once('order.panel.blockB.php'); #charges
	/*
	#echo "<tr valign=bottom>";
	echo "<tr valign=bottom >";
	echo "<td class='wtitle'>&nbsp;</td>";
	echo "<td class='wtitle'>&nbsp;</td>";
	echo "<th class='wtitle' colspan=1 align=right><br>Grand Total:&nbsp;&nbsp;</th>";
	echo "<th class='wtitle' align=right><u></u></th>";
	echo "<td class='wtitle'>&nbsp;</td>"; #scroller space
	echo "<td class='wtitle'>&nbsp;</td>"; #scroller space
	echo "</tr>";		
	
	echo "<tr valign=bottom>";
	echo "<td class='wtitle'>&nbsp;</td>";
	echo "<td class='wtitle'>&nbsp;</td>";		
	echo "<th class='wtitle' colspan=1 align=right>VAT:&nbsp;&nbsp;</th>";
	echo "<th class='wtitle' align=right></th>";
	echo "<td class='wtitle'>&nbsp;</td>"; #scroller space
	echo "<td class='wtitle'>&nbsp;</td>"; #scroller space
	echo "</tr>";		
	*/
	#include_once('order.panel.blockC.php'); #discounts
	/*
	echo "<tr valign=bottom bgcolor='$bcl'>";
	echo "<td class='wtitle'>&nbsp;</td>";
	echo "<td class='wtitle'>&nbsp;</td>";		
	echo "<th class='wtitle' colspan=1 align=right><br>AMOUNT DUE:&nbsp;&nbsp;</th>";
	echo "<th class='wtitle' align=right><u>".number_format($sesnSALESGrossAmountDue,2)."</u></th>";
	echo "<td class='wtitle'>&nbsp;</td>"; #scroller space
	echo "<td class='wtitle'>&nbsp;</td>"; #scroller space
	echo "</tr>";			
	*/
echo "</table>";
?>