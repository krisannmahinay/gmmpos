<?php
echo "<select size=1 name='cboDeptCode'>";
echo "<option value='0'></option>";

#bring back to connection, wfs 1104
wfsDBfnb();

$sql = "Select Distinct * From department Order by DeptDesc;";
$qry = mysql_query($sql) or die("$sql<br>".mysql_error());
while ($row = mysql_fetch_object($qry))
{
    if ($cboDeptCode == $row->DeptCode)
    {
        echo "<option value='$row->DeptCode' Selected>$row->DeptDesc</option>";
    }
    else
    {
        echo "<option value='$row->DeptCode'>$row->DeptDesc</option>";
    }
}
echo "</select>";
?>
