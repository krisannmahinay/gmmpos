<?php
echo "<select style='color:black' size=1 name='CBOPricingRID' id='idCBOPricingRID'

onchange='SalesDRestSave(this.value, this.id, ". $mxOrderDetailRID . ")'

>";

$sql = "SELECT * FROM lkup_pricing WHERE deleted=0 ORDER BY Description;";
$qry = mysqli_query($db_wgfinance,$sql) or die("$sql<br>".mysqli_error($db_wgfinance));
while ($row = $qry->fetch_object())
{
    if ($CBOPricingRID == $row->PricingRID)
        echo "<option value='$row->PricingRID' Selected>$row->Description</option>";
    else
        echo "<option value='$row->PricingRID'>$row->Description</option>";
}
echo "</select>";
?>