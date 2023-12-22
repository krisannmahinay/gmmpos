<?php
echo "<select size=1 name='cboUOMRID'>";
echo "<option value='0'>(all)</option>";

#bring back to connection, wfs 1104
wfsDBfnb();

$sql = "Select Distinct * From uomplu Order by uom_desc;";
$qry = mysql_query($sql) or die("$sql<br>".mysql_error());
while ($row = mysql_fetch_object($qry))
{
    if ($cboUOMRID == $row->UOMRID)
    {
        echo "<option value='$row->UOMRID' Selected>$row->uom_desc</option>";
    }
    else
    {
        echo "<option value='$row->UOMRID'>$row->uom_desc</option>";
    }
}
echo "</select>";
?>
