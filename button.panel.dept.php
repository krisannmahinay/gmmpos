<?php
#************** department
@session_start();

echo "<div id='scroll-dept'>";
	echo "<div id='scroll-content-dept'>";
				
		echo "<table border=0>";
				$sqlCats = "SELECT * FROM department ORDER BY ButtonLabel;";
					$qryCats = mysql_query($sqlCats) OR DIE ("$sqlCats<br>".mysql_error());
					$x=0;
					while ($tblCats=mysql_fetch_object($qryCats))
					{
						$cat = $tblCats->ButtonLabel;
						$desc= $tblCats->DeptDesc;
						$icon = $tblCats->Icon;
						$bgc = (($x%2)==0)? "":"";
						$x++;
						echo "<tr bgcolor=$bgc>";
						echo "<td align=center valign='top' width='1%'>";

						/* preserve me HA?
						echo "<input class='touchCats' type=submit name=buttDEPT[]
							style='background: url(img/$icon) no-repeat center; background-color: #11FFCC;' value='$cat'>";
						echo "$cat</td>";
						*/

						echo "<input class='touchCats' type=submit name='buttDEPT[]' value='$cat' title='$desc'>";
						

						echo "</tr>";
					}
		echo "</table>";
				
	echo "</div>";
echo "</div>";
	
?>