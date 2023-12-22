<?php

# feb 2016, MGD
$sesnSALESTranDateTime = $_SESSION['sesnSALESTranDateTime'];

@$buttNumbers = $_REQUEST['buttNumbers'];
@$txtAmount = $_REQUEST['txtAmount'];
if (isset($buttNumbers))
{
    for ($i=0; $i<count($buttNumbers); $i++)
    {
        #echo $buttNumbers[$i];
		$x = $_REQUEST['txtAmount'];
        $txtAmount = $x . $buttNumbers[$i];
    }
}

@$buttCharge = $_REQUEST['buttCharge'];
if (isset($buttCharge))
{
    for ($i=0; $i<count($buttCharge); $i++)
    {
        $ChargeDesc = $buttCharge[$i];
    }
	$ChargeAmount = $_REQUEST['txtAmount'] * 1;
	$ChargeRID = GetChargesInfoName($ChargeDesc);
	
	$mExtAmnt = $ChargeAmount;
	
	
	$mSql = "INSERT INTO `possales_details` SET 
		ProductRID = $ChargeRID, 
		SoldPrice = $ChargeAmount,
		SoldQty = 1,
		EntryType = 2,
		ExtendAmount = '$mExtAmnt',

		Stamped = '$sesnSALESTranDateTime',
			
		TranRID = $sesnTranRID;";
	@mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql.<br>".mysqli_error($db_wgfinance));
	
	CalcTransChargesTotal($sesnTranRID, 'possales_details');
	$txtAmount = "";
	
	$_SESSION['sesnREDIRECTOR']="charges.php";
	echo "<script>location='redir.php';</script>";
}

@$buttClearNumb = $_REQUEST['buttClearNumb'];
if (isset($buttClearNumb))
{
	$txtAmount = "";
}

@$buttBackSpace = $_REQUEST['buttBackSpace'];
if (isset($buttBackSpace))
{
	$x = $_REQUEST['txtAmount'];
	$txtAmount = substr($x, 0, -1);
}
/*
@$buttCancel = $_REQUEST['buttCancel'];
if (isset($buttCancel))
{
    for ($i=0; $i<count($buttCancel); $i++)
    {
        $ODetRID=$buttCancel[$i];
		#echo "<script>alert('$buttCancel[$i]');</script>";
    }
	
	@$voidRID = $ODetRID;
	$user_RID = wfs_WhoIsLogged();
	
	$mAuthorThis = "'VOID SALES CHARGES'?$voidRID?$sesnTranRID?$user_RID";
	#echo $mAuthorThis;
	
	$clsWalnet->CreateAuthorizeGrab($mAuthorThis);
	echo "<script>window.open('authorizelog.php','popAuthLog',
		'width=380, height=230,scrollbars=no,resizable=no,toolbar=no,directories=no,location=no,menubar=no,status=no,left=10,top=10');</script>";
}*/
?>