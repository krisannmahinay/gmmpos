<?php
echo "<select size=1 name='cboLocation' $W_disabled>";
echo "<option value='0'>(all)</option>";

#bring back to connection, wfs 
require('wchensii.php');

$sql = "Select Distinct * From locations Order by Location;";
$qry = mysql_query($sql) or die("$sql<br>".mysql_error());
while ($row = mysql_fetch_object($qry))
{
    if ($cboLocation == $row->LocaRID)
        echo "<option value='$row->LocaRID' Selected>$row->Location</option>";
    else
        echo "<option value='$row->LocaRID'>$row->Location</option>";
}
echo "</select>";
?>
