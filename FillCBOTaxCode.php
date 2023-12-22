<?php

echo "<select style='color:black' size=1 name='CBOTaxCode' id='idCBOTaxCode'

onchange='SalesDRestSave(this.value, this.id, ". $mxOrderDetailRID . ")'

>";

echo "<option value=''></option>";

$sql = "SELECT * FROM lkup_salestax WHERE deleted=0 ORDER BY Description;";
$qry = mysqli_query($db_wgfinance,$sql) or die("$sql<br>".mysqli_error($db_wgfinance));
while ($row = $qry->fetch_object())
{
    if ($CBOTaxCode == $row->SalesTaxRID)

        echo "<option value='$row->SalesTaxRID' Selected>$row->Description</option>";
    else
        echo "<option value='$row->SalesTaxRID'> $row->Description</option>";
}
echo "</select>";
?>