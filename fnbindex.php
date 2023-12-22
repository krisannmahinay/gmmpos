<html>
<?php
DEPRECATE

include_once('htmlhead.php');
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

include_once('wfslib/WalnetFunctionsPOS.php');

$today = wfsGetSysDate(0);

require_once('sys.off.php');

$_SESSION['sesnHelperBar'] = 1;

include_once('sys.inc.php');
if ($sesnLOGGEDPxRID<=0)
{
	echo "<script>window.open('login.php','prnchkPDF',
		'menubar=0,resizable=0,width=400,height=250');</script>";
}
else
{
	echo "<script>location='fnb.sales.php';</script>";
	#echo "<script>location='plu.main.php';</script>";
}

#echo "<div class='container_16'>";


include_once('mxi.top.php');

#echo "</div>";
?>
<?php include "footer.php";?>	
</body>
</html>