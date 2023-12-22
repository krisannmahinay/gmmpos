<head>
<title>GMMR POS</title>
<script>
function confirmDeleteBagnos(recordId, recordEqui) {
		//alert(recordId);
		if (confirm("Are you sure you want to delete " + recordEqui + "?") == true)
			window.location.href = "9561.iban.php?delrid=" + recordId;
		return false;
	}
</script>	
<style>
.ColHeadsWdBorder
{
	font-size: 	12px;
	background: #660099; /*99FFFF*/
	color: #FFFFFF;
	
	padding-left: 0.5em;
    padding-right: 0.5em;
    padding-top: 0.2em;
    padding-bottom: 0.2em;	
	border-style: solid;
    border-width: 1px 1px 1px 1px;
    border-color: #FFFFFF;
}
.wpadd
{
	font-size: 	12px;
	padding-left: 0.5em;
    padding-right: 0.5em;
    padding-top: 2px;
    padding-bottom: 2px;
}
</style>

</head>
<?php
@session_start();

include_once('sys.inc.php');

@$sesn9561PxRID = $_SESSION['sesn9561PxRID'];

@$cmdSave = $_REQUEST['cmdSave'];
if ($cmdSave=="save")
{
	@$doorax = $_REQUEST['doorax'];

	$mSql = "UPDATE sys_doorkeys SET Deleted=1 WHERE (PxRID='$sesn9561PxRID');";
	@mysqli_query($db_ipadrbg,$mSql) OR DIE(mysqli_error($db_ipadrbg));

	
	for ($j=0; $j<count($doorax);$j++)
	{
		$mxDoor = $doorax[$j];
		$mSql = "INSERT INTO sys_doorkeys SET PxRID='$sesn9561PxRID', DoorKnob='$mxDoor';";
		@mysqli_query($db_ipadrbg,$mSql) OR DIE(mysqli_error($db_ipadrbg));
	}
	$mSql = "DELETE FROM sys_doorkeys WHERE Deleted=1;";
	@mysqli_query($db_ipadrbg,$mSql) OR DIE(mysqli_error($db_ipadrbg));
	
	#echo "<script>alert('HI');</script>";
}

echo "<div class='container_16'>";

echo "<form name='frmAX' action='9561.php'>";

echo "<table border=1 width='100%'>";
echo "<tr>";
echo "<td class='wpadd' width='1%' valign='top'>";

	echo "<table border=0 width='100%'>";
	echo "<tr>";
		echo "<th class='ColHeadsWdBorder' width='1%' align=left nowrap>PxRID</th>";
		echo "<th class='ColHeadsWdBorder' width='1%' align=center nowrap>UserName</th>";
		echo "<th class='ColHeadsWdBorder' align=left nowrap>Name</th>";
		echo "<th class='ColHeadsWdBorder' align=left nowrap>Position</th>";
	echo "</tr>";
	
	$mSql = "SELECT * FROM users INNER JOIN px_data 
		ON users.PxRID = px_data.PxRID
		WHERE users.Deleted=0 
			ORDER BY UserType, UserName;"; #AND UserLevel<$sesnLOGGEDUserLevel
			
	$mQry = mysqli_query($db_ipadrbg,$mSql) OR DIE("$mSql.<br>".mysqli_error($db_ipadrbg));
	$x=0;
	while ($tblDoors=$mQry->fetch_object())
	{
		$bgc = (($x%2)==0)? "#CCCCCC":"#DDDDDD";
		$x++;
		echo "<tr style='background:$bgc'>";

		echo "<td class='wpadd' align=center nowrap>$tblDoors->PxRID</td>";
		echo "<td class='wpadd' align=center nowrap>
			<a class='button blue small' style='display:block;' href='9561.2.prep.php?pxu=$tblDoors->PxRID'>$tblDoors->UserName</a></td>";
		echo "<td class='wpadd' align=left nowrap>$tblDoors->LastName, $tblDoors->FirstName</td>";
		echo "<td class='wpadd' align=left nowrap>$tblDoors->UserType</td>";
	
		echo "</tr>";
	}
	echo "</table>";
echo "</td>";



echo "<td class='wpadd'>";

	echo "<table border=0 width='100%'>";
	echo "<tr>";
		echo "<th class='wtitle' colspan=9 nowrap>&nbsp;".GetPatientInfo($sesn9561PxRID, 2)."</th>";
	echo "</tr>";
	
	echo "<tr>";
		echo "<th class='ColHeadsWdBorder' width='1%' align=left nowrap>DoorKnob</th>";
		echo "<th class='ColHeadsWdBorder' width='1%' align=center nowrap>ACCESS</th>";
		echo "<th class='ColHeadsWdBorder' width='1%' align=center nowrap>DoorSign</th>";
		echo "<th class='ColHeadsWdBorder' align=left nowrap>Location</th>";
	echo "</tr>";

	echo "<tr>";
		echo "<th class='wtitle' colspan=9 nowrap>
			<input class='button orange small' type=submit name='cmdSave' value='save'></th>";
	echo "</tr>";

	$mSql = "SELECT * FROM sys_doors WHERE Deleted=0 AND 
		DoorKnob<9500 ORDER BY DoorKnob,DoorSign;";
		
	$mQry = mysqli_query($db_ipadrbg,$mSql) OR DIE("$mSql.<br>".mysqli_error($db_ipadrbg));
	$x=0;
	while ($tblUsers = $mQry->fetch_object())
	{
		$bgc = (($x%2)==0)? "#CCCCCC":"#DDDDDD";
		$x++;
		echo "<tr style='background:$bgc'>";
	
		$mxDoorKnob = $tblUsers->DoorKnob;

		$mSql = "SELECT * FROM sys_doorkeys WHERE (DoorKnob='$mxDoorKnob' AND PxRID='$sesn9561PxRID') AND Deleted=0;";
		$mQryAx = mysqli_query($db_ipadrbg,$mSql) OR DIE(mysqli_error($db_ipadrbg));
		$tblAx=$mQryAx->fetch_object();
		$mAxed = ($tblAx)? "checked" : "";
	
		echo "<td class='wpadd' align=center nowrap>$tblUsers->DoorKnob</td>";
		echo "<th class='wpadd' nowrap><input type='checkbox' name='doorax[]' value='$tblUsers->DoorKnob' $mAxed></th>";
		echo "<td class='wpadd' align=left nowrap>$tblUsers->DoorSign</td>";
		echo "<td class='wpadd' align=left nowrap>$tblUsers->Location</td>";
	
		echo "</tr>";
	}
	echo "</table>";

echo "</td>";
echo "</tr>";
echo "</table>";

echo "</form>";

echo "</div>";
?>