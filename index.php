<html>
<?php
include_once 'htmlhead.php';
?>
<script language="JavaScript">
	document.domain='<?=$_SERVER['SERVER_NAME']?>';
	function popUpClosed()
	{
		window.location.reload();
	}
</script>

<body>

<?php
@session_start();
//turn off PHP compatibility warnings
ini_set("session.bug_compat_warn","off");

include_once 'wfslib/WalnetFunctionsPOS.php';

$today = wfsGetSysDate(0);

require_once('sys.off.php');

$_SESSION['sesnHelperBar'] = 1;

include_once 'sys.inc.php';

include_once 'mxi.top.php';
if ($sesnLOGGEDPxRID<=0)
{
	#echo "<script>window.open('login.php','prnchkPDF',
	#	'menubar=0,resizable=0,width=400,height=250');</script>";
	include_once 'face.pos.php';
}
else
{
	$mSql = "SELECT * FROM branches WHERE BranchRID = '$sesnLOGGEDUserLocation';";
	$mQry = mysqli_query($db_ipadrbg,$mSql) or die(mysqli_error($db_ipadrbg));
	if ($tblBranch = $mQry->fetch_object())
	{
		$mSql = "SELECT * FROM orgparms WHERE OrgRID = '$sesnLOGGEDUserLocation';";
		$mQry = mysqli_query($db_ipadrbg,$mSql) or die(mysqli_error($db_ipadrbg));
		if ($tblBranch = $mQry->fetch_object())
			$mOK = 1;
		else
		{
			$mOK = 0;
			$ErrMess = "ORGANIZATIONAL ID INFO not defined! System halted!";
		}
	}
	else
	{
		$mOK = 0;
		$ErrMess = "Table BRANCH INFO not defined! System halted!";
	}
	$mOK = 1;
		
	if ($mOK == 0)
	{
		echo "<script>alert('$ErrMess');</script>";
		@session_destroy();
		die();
	}
	#echo "<script>alert('HIT!!!');</script>";
	echo "<script>location='sales.php';</script>";

	#echo "<script>location='plu.main.php';</script>";
}
#echo "<div class='container_16'>";
#echo "</div>";
?>
<?php include "footer.php";?>	
</body>
</html>