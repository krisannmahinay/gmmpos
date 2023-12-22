<?php
echo "<html lang='en'>";
echo "<head>";

require_once('wtabber.inc.php'); #disable danay ang cookies cookies a 0904

$PageHeadTitle="POSEIDON-9561";
require('htmlhead.php');
echo "</head>";

echo "<body'>";

@session_start();

include_once('sys.inc.php');
if ($sesnLOGGEDPxRID==0 || $sesnLOGGEDUserType!="ADMIN") DIE("login first!");

#require_once("wfslib/wfsclass.php");
#$clsWalnet = new clsWalnet();

include_once('wfslib/WalnetFunctionsPOS.php');
include_once('wchensPOS.php');

#include_once('mxi.top.php');

if (! isset($_SESSION['sesnLOGGEDPxRID']))
{
	echo "<script>alert('Hey!');</script>";
}
else
{
	/*echo "<div class='tabber' id='examine'>";
		echo "<div class='tabbertab'>";
			echo "<h2>Doctor</h2>";
			include('9561.1.php');
		echo "</div>";

		echo "<div class='tabbertab'>";
			echo "<h2>Access</h2>";*/
			include('9561.2.php');
		/*echo "</div>";

		
		echo "<div class='tabbertab'>";
			echo "<h2>SysOrg</h2>";
			include('9561.8.php');
		echo "</div>";
		
	
		echo "<div class='tabbertab'>";
			echo "<h2>PRIORITI</h2>";
			include('9561.9.php');
		echo "</div>";
	echo "</div>";*/
}
?>
<?php include "footer.php";?>