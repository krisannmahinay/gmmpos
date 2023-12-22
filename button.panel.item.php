<?php
@session_start();
echo "<div id='scroll-dept-products'>";
	echo "<div id='scroll-content-products'>";
		$DeptGrab = $clsWalnet->ReturnDEPTGrab();
		@$sessnDepartment = $_SESSION['sessnDepartment'];
		$DeptCode = GetDeptInfo($DeptGrab, 1);
		
		echo "<table border=0>";
		echo "<tr>";
		$sqlItems = "SELECT * FROM product WHERE DeptCode='$DeptCode';";
		#	echo $sqlItems;
		
		$qryItems = mysql_query($sqlItems) OR DIE("$sqlItems<br>".mysql_error());
		@$colm = 0;
		while ($tblProducts=mysql_fetch_object($qryItems))
		{
			if ($colm>3)
			{
				echo "</tr>";
				echo "<tr>";
				$colm=0;
			}
			echo "<td><input class='orange' type='submit' name='buttITEMS[]' 
				value='$tblProducts->ButtonLabel' title='$tblProducts->Description'  /></td>";  #style='width:100px; height:130px;'
				$colm++;
		}
		echo "</tr>";
		echo "</table>";
	echo "</div>";
echo "</div>";
?>	