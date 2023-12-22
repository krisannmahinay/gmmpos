<?php
echo "<select size=1 name='cboPxRID' $W_disabled>";

$sql = "SELECT * FROM px_data 
	WHERE (LastName<>'' AND FirstName<>'') OR (BusinessName<>'')
	ORDER BY LastName, FirstName;";
$qry = mysqli_query($db_ipadrbg,$sql) or die("$sql<br>".mysqli_error($db_ipadrbg));

echo "<option value='0'>(all)</option>";

while ($row = $qry->fetch_object())
{
    if ($cboPxRID == $row->PxRID)
    {
        echo "<option value='$row->PxRID' Selected>$row->LastName $row->FirstName</option>";
    }
    else
    {
        echo "<option value='$row->PxRID'>$row->LastName $row->FirstName</option>";
    }
}
echo "</select>";
?>