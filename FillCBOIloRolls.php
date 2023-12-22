<?php
#$i = 0;

echo "<select REQUIRED name='cboIloRolls[]' $W_disabled>";
echo "<option value='0'>&nbsp</option>";

$sql = "SELECT * FROM users WHERE IloRolls=1 Order by UserName";
$qryU = mysqli_query($db_ipadrbg,$sql) or die("<b>$_SERVER[PHP_SELF]</b><br>".mysqli_error($db_ipadrbg));
while ($rowU = $qryU->fetch_object())
{
	$selected = ($cboIloRollsDummy == $rowU->PxRID)? "SELECTED" : "";
	echo "<option value='$rowU->PxRID' $selected>
		$rowU->UserName</option>";
	#$i ++;
}
echo "</select>";
?>
