<?php
echo "<select size=1 name='cboUsers' $W_disabled>";
echo "<option value='0'></option>";
wfsDBfnb();

$sql = "SELECT * FROM users ORDER BY UserName;";
$qry = mysql_query($sql) or die("$sql<br>".mysql_error());
while ($row = mysql_fetch_object($qry))
{
    if ($cboUsers == $row->UserRID)
    {
        echo "<option value='$row->UserRID' Selected>$row->UserName</option>";
    }
    else
    {
        echo "<option value='$row->UserRID'>$row->UserName</option>";
    }
}
echo "</select>";
?>
