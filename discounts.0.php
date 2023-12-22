<?php
$mGrossy = 0;

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

@$buttDiscount = $_REQUEST['buttDiscount'];
if (isset($buttDiscount))
{
	$DiscPercent = 0;

    for ($i=0; $i<count($buttDiscount); $i++)
    {
        // $DiscDesc = $buttDiscount[$i];
        $DiscPercent = $buttDiscount[$i];
    }
	
	$txtIDNo	= str_replace("'","`",$_REQUEST['txtIDNo']);
	$txtName	= str_replace("'","`",$_REQUEST['txtName']);
	$txtExpiry	= $_REQUEST['txtExpiry'];
	

	$mPerc = $DiscPercent * 1;

	
	$mSql = "UPDATE `possales` SET 
		SrCitPWD_id 	= '$txtIDNo'
		, SrCitPWD_name	= '$txtName'
		, SrCitPWD_expiry = '$txtExpiry'
		, SrCitPWD_rate = $mPerc
		WHERE TranRID = $sesnTranRID;";

	// $wfp = fopen("zzz_disc0.zzz", "w");
	// fwrite($wfp, $mSql);
	// fclose($wfp);

	@mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql.<br>".mysql_error());

	//CalcTransDiscountsTotal($sesnTranRID, 'possales_details');
	CalcSalesTotal($sesnTranRID);
	$txtAmount = "";
	
	$_SESSION['sesnREDIRECTOR']="discounts.php";


	// echo "<script>location='redir.php';</script>";
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

/*  	DEPRE - used <a> wfs 12/13/2012
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
	
	$mAuthorThis = "'VOID DISCOUNT'?$voidRID?$sesnTranRID?$user_RID";
	#echo $mAuthorThis;

	$clsWalnet->CreateAuthorizeGrab($mAuthorThis);
	echo "<script>window.open('authorizelog.php','popAuthLog',
		'width=380, height=230,scrollbars=no,resizable=no,toolbar=no,directories=no,location=no,menubar=no,status=no,left=10,top=10');</script>";
}
*/
?>