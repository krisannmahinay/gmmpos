<?php
echo "<table align=center border='0'>";
echo "<tr>";
echo "<td width='1%'>";
	echo "<input class='gray' type=button name='buttPRODControl' value='Inventory'
		onclick=\"window.open('products.php','popup',
			'width=960,height=600,scrollbars=yes,resizable=yes,toolbar=no,directories=no,location=no,menubar=no,status=no,left=10,top=10');
			return false\"
		>";		
echo "</td>";
echo "<td width='1%'>";		
	#echo "<input class='gray' type='submit' name='buttReceiving' value='Stock Receive' title='Stock Receive from Source'>";
	echo "<input class='gray' type=button name='buttReceiving' value='Stock Receive' title='Stock Receive from Source'
		onclick=\"window.open('receiving.php','popupRECE',
			'width=960,height=630,scrollbars=yes,resizable=yes,toolbar=no,directories=no,location=no,menubar=no,status=no,left=10,top=10');
			return false\"
		>";	
echo "</td>";

echo "<td width='1%'>";			
	echo "<input class='gray' type='submit' name='buttReceiving' value='Stock Returns' title='Stock Returns'>";
echo "</td>";

echo "</tr>";
echo "<tr>";

echo "<td width='1%'>";				
	echo "<input class='gray' type='submit' name='buttXXX' value='Customer' title='Customer Control'>";
echo "</td>";

echo "<td width='1%'>";					
	echo "<input class='gray' type=button name='buttREPS' value='Reports' title='Reports Options'
			onclick=\"window.open('reports.php','popupREPS',
			'width=300,height=500,scrollbars=yes,resizable=yes,toolbar=no,directories=no,location=no,menubar=no,status=no,left=10,top=10');
			return false\"
		>";	
echo "</td>";

echo "<td width='1%'>";					
	echo "<input class='gray' type='submit' name='buttShiftStart' value='Start of Day' title='Start of Day'>";
echo "</td>";

echo "</tr>";
echo "<tr>";

echo "<td width='1%'>";					
	echo "<input class='gray' type='submit' name='buttShiftEnd' value='End of Shift' title='End of Day'>";
echo "</td>";
echo "<td width='1%'>";				
	echo "<input class='gray' type='submit' name='buttXXX' value='Pay In/Out' title='Cash in/out'>";		
echo "</td>";
echo "<td width='1%'>";					
	echo "<input class='gray' type=submit name='buttADMIN' value='ADMIN' title='admin functions'>";		
echo "</td>";
	
	
echo "</tr>";
echo "</table>";
?>