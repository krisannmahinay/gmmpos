<?php
echo "<select style='color:black' size=1 name='cboPayType'>";

$sql = "SELECT * FROM lookuppaytype WHERE deleted=0 Order by PayType;";
$qry = mysqli_query($db_wgfinance,$sql) or die("$sql<br>".mysqli_error($db_wgfinance));
while ($row = $qry->fetch_object())
{
    if ($cboPayType == $row->PayTypeRID)
        echo "<option value='$row->PayTypeRID' Selected>$row->PayType</option>";
    else
        echo "<option value='$row->PayTypeRID'>$row->PayType</option>";
}
echo "</select>";
?>