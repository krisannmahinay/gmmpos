<?php
$mxSelected0 = ($cboSIType == 0) ? NULL : "SELECTED";
$mxSelected1 = ($cboSIType == 1) ? "SELECTED" : NULL;

echo "<select size=1 name='cboSIType' $W_disabled>";
echo "<option value='0' $mxSelected0>CASH</option>";
echo "<option value='1' $mxSelected1>CHARGE</option>";
echo "</select>";
?>