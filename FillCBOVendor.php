<?php
echo "<select size=1 name='cboVendor' $W_disabled>";
echo "<option value='0'>(all)</option>";

$sql = "Select Distinct VendorCode, VendorRID  
	From vendors Order by VendorCode;";
$qry = mysqli_query($db_wgfinance,$sql) or die("$sql<br>".mysqli_error($db_wgfinance));
while ($row = $qry->fetch_object())
{
    if ($cboVendor == $row->VendorRID)
        echo "<option value='$row->VendorRID' Selected>$row->VendorCode</option>";
    else
        echo "<option value='$row->VendorRID'>$row->VendorCode</option>";
}
echo "</select>";
?>
