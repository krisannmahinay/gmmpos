<input list="browsers">

<datalist id="browsers">
  <option value="Internet Explorer">
  <option value="Firefox">
  <option value="Chrome">
  <option value="Opera">
  <option value="Safari">


<?php
echo "<input name='cboPxRID' list='Customer' $W_disabled>";

$sql = "SELECT * FROM px_data 
	WHERE (LastName<>'' AND FirstName<>'') OR (BusinessName<>'')
	ORDER BY LastName, FirstName;";
$qry = mysqli_query($db_ipadrbg,$sql) or die("$sql<br>".mysqli_error($db_ipadrbg));

echo "<datalist id='Customer'>";
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
echo "</datalist>";
?>