<?php
echo "<select size=1 name='cboTablesAvail'>";
echo "<option value='0'></option>";
wfsDBfnb();

$sql = "Select * From tables WHERE Occupied=0 AND Reserved=0 Order by tablename;";
$qry = mysql_query($sql) or die("$sql<br>".mysql_error());
while ($row = mysql_fetch_object($qry))
{
    if ($cboTablesAvail == $row->tableno)
    {
        echo "<option value='$row->tableno' Selected>$row->tablename</option>";
    }
    else
    {
        echo "<option value='$row->tableno'>$row->tablename</option>";
    }
}
echo "</select>";
?>
