<script type="text/javascript" >
	function funcDetectKey(evt)
	{
		switch(evt.keyCode)
		{
			case 27:    //ESCped
				window.close();
		      break;
		}//end of switch	
	}
</script>	
<?php
echo "<body onKeyDown='funcDetectKey(event);'>";
echo "<center>";
echo "<table border=1 width=1% cellpadding=1 cellspacing=0>";
echo "<tr>";
echo "<th width=50% nowrap>COMMAND</th>";
echo "<th width=50% nowrap>PROCESS</th>";
echo "</tr>";

echo "<tr>";
echo "<td nowrap>*trn, *trn</td>";
echo "<td nowrap>Transaction Listing</td>";
echo "</tr>";

echo "<tr>";
echo "<td nowrap>*jbc# + barcode number</td>";
echo "<td nowrap>to enter a barcode manually</td>";
echo "</tr>";

echo "<tr>";
echo "<td nowrap>*999</td>";
echo "<td nowrap>new transaction</td>";
echo "</tr>";

echo "</table>";


echo "press ESC to close window";
echo "<center>";
?>
