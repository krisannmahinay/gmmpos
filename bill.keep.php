<head>
<link href="css/print.css" rel="stylesheet" type="text/css"  media="print"/>  
</head>
<?php
include_once('wfslib/WalnetFunctionsPOS.php');
wfsDBfnb();

$mSql = "SELECT SUM(SoldQty*SoldPrice) AS TotalSold FROM order_details;";
$mQry = mysql_query($mSql) OR DIE("$mSql".mysql_query());
$tblSold=mysql_fetch_object($mQry);



$handle = fopen("COM3", "w"); // note 1 

echo "<table width=10% border=0 bgcolor='#FFFFFF'>";
	echo "<table width=100% border=0 bgcolor='#FFFFFF'>";
		
	echo "<tr bgcolor='#FFFFFF'>";
	echo "<th  colspan=2>".wfsGetSysDate(4)."</th>";
	echo "<th  colspan=2 align=right>999999999</th>";
	echo "</tr>";
echo "</table>";

/*
	
	echo "<tr>";
	echo "<th  align=left colspan=2 align=left>Server: Piolo</th>";
	echo "<th  align=right colspan=2>tabel: 45</th>";
	echo "</tr>";


	echo "<tr>";
    echo "<th with=1>QTY</th>";
    echo "<th with=1>DESCRIPTION</th>";
    echo "<th>Price</th>";
    echo "<th>Amount</th>";
    echo "</tr>";		
	
    $mSql = "SELECT SUM(SoldQty) AS SumQty, SoldPrice, ProductRID FROM order_details 
		GROUP BY ProductRID
		ORDER BY OrDetRID DESC;";
    $mQry = mysql_query($mSql) OR DIE("$mSql".mysql_query());
	$x=0;
    while ($tblOrders=mysql_fetch_object($mQry))
    {
		echo "<tr>";
        echo "<td align=center valign=top>";
			$xQty = $tblOrders->SumQty;
			echo $xQty;
        echo "</td>";
        echo "<td>";
			$xDesc = GetItemInfoRID($tblOrders->ProductRID, 3); #button label
            echo $xDesc;
        echo "</td>";

        echo "<td align=right>";
            $xSRP = $tblOrders->SoldPrice;
            echo number_format($xSRP,2);
        echo "</td>";
			
        echo "<td align=right>";
            echo number_format($xQty * $xSRP, 2);
        echo "</td>";
			
        echo "</tr>";
    }
	
	echo "<tr>";

		echo "<th class='total' align=right colspan=2>Total</th>";
		echo "<th class='total' align=right colspan=2>".number_format($tblSold->TotalSold, 2)."</th>";			
		echo "</tr>";	

    echo "</table>";
	
	echo "</td>";
    echo "</tr>";
	
echo "</table>";	
*/
?>