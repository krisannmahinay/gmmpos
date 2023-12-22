<?php
require_once("wfslib/wfsclass.php");
$clsWalnet = new clsWalnet();
include_once('wfslib/WalnetFunctionsPOS.php');
wfsDBfnb();

@$mExchangeRID = $_REQUEST['ExchangeRID'];

@mysql_query("DELETE FROM exchange_current;") OR DIE(mysql_error());
@mysql_query("DELETE FROM exchange_details_current;") OR DIE(mysql_error());

$mSql = "INSERT INTO exchange_current SELECT * FROM exchange 
	WHERE ExchangeRID=$mExchangeRID;";
#echo $mSql;
mysql_query($mSql) OR DIE("$mSql<br>".mysql_error());	

$mSql = "INSERT INTO exchange_details_current SELECT * FROM exchange_details
	WHERE exchange_details.ExchangeRID=$mExchangeRID;";
#echo $mSql;
mysql_query($mSql) OR DIE("$mSql<br>".mysql_error());	

echo "<script>location='exchange.php';</script>";		
?>		