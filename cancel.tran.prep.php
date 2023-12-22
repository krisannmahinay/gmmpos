<?php
require_once("wfslib/wfsclass.php");
$clsWalnet = new clsWalnet();
include_once('wfslib/WalnetFunctionsPOS.php');
wfsDBfnb();



##DEPRECATED, MOVED TO plu.top.cancel.php 
exit();



$TranRID = $_REQUEST['TranRID'];
$user_RID = wfs_WhoIsLogged();

@mysql_query("DELETE FROM order_master_current;") OR DIE(mysql_error());
@mysql_query("DELETE FROM order_details_current;") OR DIE(mysql_error());
	
$mSql = "INSERT INTO order_master_current 
	SELECT * FROM order_master WHERE TranRID=$TranRID;";
@mysql_query($mSql) OR DIE("$mSql<br>".mysql_error());

$mSql = "INSERT INTO order_details_current 
	SELECT * FROM order_details WHERE TranRID=$TranRID;";
@mysql_query($mSql) OR DIE("$mSql<br>".mysql_error());


$mAuthorThis = "'CANCEL TRAN'?0?$TranRID?$user_RID";
#echo $mAuthorThis;
$clsWalnet->CreateAuthorizeGrab($mAuthorThis);

	
#closing this window goes back to PLU_MAIN
echo "<script>window.close();</script>";

#echo "<script>location='plu.main.php';</script>";		


/*	
$mAuthorThis = "'CANCEL TRAN'?0?$ORID?$user_RID";
#echo $mAuthorThis;

$clsWalnet->CreateAuthorizeGrab($mAuthorThis);
	
echo "<script>window.open('authorizelog.php','popAuthLog',
		'width=380, height=230,scrollbars=no,resizable=no,toolbar=no,directories=no,location=no,menubar=no,status=no,left=10,top=10');</script>";

echo "<script>location='rep.ejournal.php';</script>";		
*/
?>		