<STYLE TYPE="text/css">
<!--
.headtbl {background-image:url('img/blue2.jpg'); }
-->
</STYLE>
<?php
echo "<table class='headtbl' style='width:100%; border:0;'>";
echo "<tr>";
	if (isset($_SESSION['sesnORGBranch']))
		$mxHead = $_SESSION['sesnORGBranch'] . "&nbsp;|";
	else	
		$mxHead=NULL;
		
		$mxHead=NULL; #wfs 1019 balik anay sa dati kay budlay ang sync
		
	$headertext="$mxHead &nbsp;".$headertext;
	
	echo "<td height='30' colspan=1 valign=top nowrap>";
		echo "<span style='color: white; font-weight: bold; font-size: 20px; padding: 0 2px 0 2px;'>";
		echo "<i>$headertext</i>";
		echo "</span>";
	echo "</td>";
	
	if (isset($headertextB))
	{
		echo "<th style='align:center;width:1%;white-space:nowrap;'>";
			echo "<span style='color: white; font-weight: bold; font-size: 20px; padding: 0 10px 0 10px;'>";		
			echo "<i>$headertextB</i>";
			echo "</span>";			
		echo "&nbsp;&nbsp;</th>";
	}
	
	echo "<th style='align:right;width:1%;background:img/blue2.jpg'>
		<img src='img/Medix5.jpg' height=55px ></th>";
echo "</tr>"; #height=53px
echo "</table>";
?>
