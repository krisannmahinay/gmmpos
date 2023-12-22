<?php
echo "<table class='table table-condensed table-bordefed'>";
echo "<tr class='info'>";
	echo "<th class ='text-center' style='color:#000' with='1%'>Delete</th>";
	echo "<th class ='text-center' style='color:#000' with='1%'>DESCRIPTION</th>";
	echo "<th style='color:#000' with='1%' align=right></th>";
	echo "<th class ='text-center' style='color:#000' with='1%'>Amount</th>";		
	
echo "</tr>";		
	
$mSql = "SELECT * FROM  `possales_details`
		WHERE EntryType=3 
			AND TranRID='$sesnTranRID' 
			ORDER BY OrderDetailRID;"; #order_charges
			#AND DisLineCanceled=0
$mQry = mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql".mysqli_error($db_wgfinance));
$x=0;
$mGrandT=0;

while ($tblOrders=$mQry->fetch_object())
{
	$mxRID = $tblOrders->OrderDetailRID;
	$mxSeniorID = $tblOrders->SeniorID;
	$mxSeniorName = $tblOrders->SeniorName;
	$mxSeniorIDExpire = $tblOrders->SeniorIDExpire;
	$mxDiscountPromoNote = $tblOrders->DiscountPromoNote;

	$bcl=($x%2==0)? "#EEEEEE":"#FFFFFF";
	$x++;
	echo "<tr class='active'>";

	$mx = $x;
	if ($tblOrders->DisLineCanceled==1)	
		$mx = "<span style='color: red'>$x</span>";
	else
		if ($W_disabled != 'disabled') 
			$mx = "<a class='btn btn-danger' style='display:block;'
				onclick=\"return decision('Are you sure to delete the entry #$x?')\"
				href='discounts.entry.del.php?delRowId=$mxRID'>$x</a>";
	echo "<th align=center>$mx</th>";
				
	echo "<td class='wpadd' nowrap>";
		$xDesc = GetDiscountsInfo($tblOrders->ProductRID, 1); 
		if ($tblOrders->DisLineCanceled==1) 
			echo "<s>$xDesc</s>";
		else
			echo $xDesc;
	echo "</td>";

	echo "<td class='text-right'>";
		$xSRP = $tblOrders->SoldPrice;
		if ($tblOrders->DisLineCanceled==1)
			echo "<s>".number_format($xSRP,2)."</s>";
		else
			echo number_format($xSRP,2);
	echo "</td>";
	
	echo "<td class='extamount text-right'>";
		$xDiscount = $tblOrders->SoldPrice * $tblOrders->SoldQty;
		echo number_format($xDiscount,2);
		$mGrandT += $xDiscount;
	echo "</td>";	
	echo "</tr>";
	
	
	if (($mxSeniorID == NULL) &&
		($mxSeniorName == NULL) &&
		($mxSeniorIDExpire == NULL) &&
		($mxDiscountPromoNote == NULL))
	{
		#do nothing
	}	
	else
	{
		$mxSeniorID = ($mxSeniorID == NULL)? NULL : "ID: $mxSeniorID<br>";
		$mxSeniorName = ($mxSeniorName == NULL)? NULL : "NAME: $mxSeniorName<br>";
		$mxSeniorIDExpire = ($mxSeniorIDExpire == "0000-00-00")? NULL : "EXPIRY: $mxSeniorIDExpire<br>";
		$mxDiscountPromoNote = ($mxDiscountPromoNote == NULL)? NULL : "PROMO: $mxDiscountPromoNote<br>";
	
		echo "<tr>";
			echo "<td align=left>&nbsp;</td>";
			echo "<td align=left nowrap>
				$mxSeniorID
				$mxSeniorName
				$mxSeniorIDExpire
				$mxDiscountPromoNote
				</td>";
		echo "</tr>";
	}
}

#$sesnSALESTotalDiscounts = GetTransInfo($sesnTranRID, 9); #na compute nas diri $mGrandT
echo "<tr>";
echo "<th class='extamount text-right' colspan=3>Senior Citizen/PWD Discounts:</th>";	
echo "<th class='total text-right'>".number_format($sesnSALESTotalSCPWDDiscounts,2)."</th>";
echo "</tr>";


// echo "<tr>";
// echo "<th class='extamount text-right' colspan=3>PWD Discounts:</th>";	
// echo "<th class='total text-right'>".number_format($sesnSALESTotalSCPWDDiscounts,2)."</th>";
// echo "</tr>";

echo "<tr>";
echo "<th class='extamount text-right' colspan=3>Total Discounts:</th>";	
echo "<th class='total text-right'>".number_format($mGrandT,2)."</th>";
echo "</tr>";

#echo "<tr valign=center  bgcolor='yellow'>";
#echo "<th colspan=2 align=right>Total Charges</th>";
#echo "<th align=right colspan=1>".number_format($sesnSALESTotalCharges,2)."</th>";
#echo "</tr>";


echo "<tr>";
echo "<th class='extamount text-right' colspan=3>Net Amount Due:</th>";
echo "<th class='total text-right' colspan=1>".
	number_format($sesnSALESNetAmountDue, 2)."</th>";
echo "</tr>";

echo "</table>";

?>