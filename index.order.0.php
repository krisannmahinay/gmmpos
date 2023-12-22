<?php
@$buttCancelTran = $_REQUEST['buttCancelTran'];
if (isset($buttCancelTran))
{
	$mAuthorThis = "'CANCEL TRAN'?0?$TranRID?$SYSUserLogged";
	#echo $mAuthorThis;
	$clsWalnet->CreateAuthorizeGrab($mAuthorThis);

	echo "<script>window.open('authorizelog.php','popAuthLog',
		'width=380, height=230,scrollbars=no,resizable=no,toolbar=no,directories=no,location=no,menubar=no,status=no,left=10,top=10');</script>";
	
	echo "<script>location='plu.main.php';</script>";
}

@$buttAbortCancel = $_REQUEST['buttAbortCancel'];
if (isset($buttAbortCancel))
{
	@mysql_query("DELETE FROM order_master_current;") OR DIE(mysql_error());
	@mysql_query("DELETE FROM order_details_current;") OR DIE(mysql_error());
	@mysql_query("DELETE FROM approval;") OR DIE(mysql_error());	
}

@$buttSERVE = $_REQUEST['buttSERVE'];
if (isset($buttSERVE))
{
	for ($i=0; $i<count($buttSERVE); $i++)
    {
        $ODetRID=$buttSERVE[$i];
		#echo "<script>alert('$buttSERVE[$i]');</script>";
    }
	
	$mSql = "UPDATE order_details_current SET Served=1
		WHERE OrderDetailRID=$ODetRID AND DisLineCanceled=0;";
	@mysql_query($mSql) OR DIE("$mSql<br>".mysql_error());		
	
	#echo $mSql;
	#echo "<script>window.close();</script>";
}

#deprecate na ni, gin LINK
#@$buttVoidEntry = $_REQUEST['buttVoidEntry'];
@$buttVoidEntry = $_POST['buttVoidEntry'];
if (isset($buttVoidEntry))
{
    for ($i=0; $i<count($buttVoidEntry); $i++)
    {
        $ODetRID=$buttVoidEntry[$i];
		#	echo "<script>alert('$buttVoidEntry[$i]');</script>";
    }
	
	$user_RID = wfs_WhoIsLogged();
	
	$mAuthorThis = "'VOID ENTRY'?$ODetRID?$TranRID?$user_RID";
	$clsWalnet->CreateAuthorizeGrab($mAuthorThis);
	
	#unset($buttVoidEntry);
	
	echo "<script>window.open('authorizelog.php','popAuthLog',
		'width=380, height=230,scrollbars=no,resizable=no,toolbar=no,directories=no,location=no,menubar=no,status=no,left=10,top=10');</script>";
}




?>