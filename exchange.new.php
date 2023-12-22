<?php
require_once("wfslib/wfsclass.php");
$clsWalnet = new clsWalnet();
include_once('wfslib/WalnetFunctionsPOS.php');
wfsDBfnb();

$user_RID = wfs_WhoIsLogged();
	
$mSql = "INSERT INTO exchange SET ExchangeDate=NOW(), ExchangeEnteredBy=$user_RID;";
#echo $mSql;
mysql_query($mSql) OR DIE(mysql_error());

$mSql = "SELECT * FROM exchange WHERE ExchangeEnteredBy=$user_RID AND ExchangeStatus=0
	ORDER BY ExchangeRID DESC LIMIT 1;";
@$mQry = mysql_query($mSql) OR DIE(mysql_error());
if ($tblRECE = mysql_fetch_object($mQry))
{
	$RecRID = $tblRECE->ExchangeRID;
	
	#clear working tables
	@mysql_query("DELETE FROM exchange_current;") OR DIE(mysql_error());
	@mysql_query("DELETE FROM exchange_details_current;") OR DIE(mysql_error());

	$mSql = "INSERT INTO exchange_current SELECT * FROM exchange WHERE ExchangeRID=$RecRID;";
	mysql_query($mSql) OR DIE("$mSql<br>".mysql_error());	

	$mSql = "INSERT INTO exchange_details_current SELECT * FROM exchange_details WHERE ExchangeRID=$RecRID;";
	mysql_query($mSql) OR DIE("$mSql<br>".mysql_error());
}
echo "<script>location='exchange.php';</script>";		
?>		