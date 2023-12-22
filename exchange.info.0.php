<?php
$ExchangeRID = wfs_GetCurrentExchangeRID();
#echo "<script>alert('$ExchangeRID');</script>";

$txtORNo 			= GetSalesExchangeInfo($ExchangeRID, 1);
$ExchangeDate		= GetSalesExchangeInfo($ExchangeRID, 2);
$ExchangeStatus  	= GetSalesExchangeInfo($ExchangeRID, 3);
$ExchangeEnteredBy	= GetSalesExchangeInfo($ExchangeRID, 4);
$ExchangedQty		= GetSalesExchangeInfo($ExchangeRID, 5);
$ExchangeAmount		= GetSalesExchangeInfo($ExchangeRID, 6);
$txtRemarks 		= GetSalesExchangeInfo($ExchangeRID, 7);



$ORDate			= GetOLDTransInfo($txtORNo, 1); #TranID table, from ARCHIVE
$TranStatus 	= GetOLDTransInfo($txtORNo, 8);
$TranEnteredBy 	= GetOLDTransInfo($txtORNo, 5); #ang nag pa sulod


@$W_readonly = "";
@$W_disabled = "";
if ($ExchangeStatus>0)
{
    $W_readonly = "readonly";
    $W_disabled = "disabled";
}
?>