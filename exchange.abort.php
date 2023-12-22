<?php
require_once("wfslib/wfsclass.php");
$clsWalnet = new clsWalnet();
include_once('wfslib/WalnetFunctionsPOS.php');
wfsDBfnb();

$user_RID = wfs_WhoIsLogged();

@$mRRID = $_REQUEST['ExchangeRID'];
$TranRID = 0;
$user_RID = wfs_WhoIsLogged();
	
/*
$mAuthorThis = "'CANCEL STOCK TRANSFER'?$STransferRID?$TranRID?$user_RID";
#echo $mAuthorThis;

$clsWalnet->CreateAuthorizeGrab($mAuthorThis);
	
echo "<script>window.open('authorizelog.php','popAuthLog',
		'width=380, height=230,scrollbars=no,resizable=no,toolbar=no,directories=no,location=no,menubar=no,status=no,left=10,top=10');</script>";
*/

$mSql = "UPDATE exchange SET ExchangeStatus=" . $clsWalnet->ExchangeStatusABORT. 
	", 	TranRID=0 WHERE ExchangeRID = $mRRID;";
@mysql_query($mSql) OR DIE("$mSql<br>".mysql_error());	

@mysql_query("DELETE FROM exchange_current;") OR DIE(mysql_error());
@mysql_query("DELETE FROM exchange_details_current;") OR DIE(mysql_error());

echo "<script>location='exchange.php';</script>";		
?>		