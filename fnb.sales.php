<html>
<?php



DEPREACTE 


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
//turn off PHP compatibility warnings
ini_set("session.bug_compat_warn","off");
@session_start();

include_once('wfslib/WalnetFunctionsPOS.php');

$today = wfsGetSysDate(0);

require_once("wfslib/xcls.sales.php");
$clsSALES = new SALES();

require_once('sys.off.php');

include_once('sys.inc.php');
include_once('wchensPOS.php');

echo "<div class='container_16'>";

include_once('mxi.top.php');
	
require('trans.info.php');
	
@$W_readonly = "";
@$W_disabled = "";

#if ($sesnSALESTranStatus > 0) && ($sesnSALESTranStatus < $clsSALES->TranStatusCANCELLED))
if ($sesnSALESTranStatus > 0)
{
	#echo "<script>alert('Hello!');</script>";
    $W_readonly = "readonly";
    $W_disabled = "disabled";
}

if ($sesnSALESTranStatus == $clsSALES->TranStatusSUSPENDED)
{
    $W_readonly = "";
    $W_disabled = "";
}


include('fnb.idept.php');
	
include('fnb.iplu.php');



echo "<div style='width:270px; height:478px;
	float:right;border:2px solid #000;overflow:auto'>";
#echo "<div class='grid_4' style='float:left;border:2px solid #000'>";
#	echo "<div class='box'>";
		echo "<h2>";
			echo "<a href='#'>DUE</a>";
		echo "</h2>";
		include_once('fnb.sales.panel.php');
#	echo "</div>";
echo "</div>";

	
echo "</div>"; #main div
?>
<?php include "footer.php";?>
<?php
echo "</body>";
echo "</html>";
?>