<?php
echo "<div style='background-color:#11eeee; 
		height:$touchpanHT; width:375px; 
		margin:0 10px 0 10px; 
		top:80px; left:150px;
		border: solid 3px #FFFF00;
		position: absolute;'>";
		
		echo "<div id='content-slider'></div>";
			echo "<div id='content-scroll'>";
				echo "<div id='content-holder'>";
					echo "<div class='content-item'>";
						@$sessnDepartment = $_SESSION['sessnDepartment'];
						$DeptCode = GetDeptInfo($sessnDepartment, 1);

						echo "<table border=0>";
						echo "<tr>";

						$sqlItems = "SELECT * FROM product WHERE DeptCode='$DeptCode';";
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
			echo "</div>";
		echo "	</div>";
	echo "	</div>";
?>	