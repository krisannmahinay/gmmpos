<?php
	#************** department

	$touchpanHT="500px"; 
	echo "<div style='height: $touchpanHT; width:100px; 
		margin:0 10px 0 10px; 
		top:80px; left:10px;
		border: solid 3px #FFFF00;
		position: absolute;
		'>";

		echo "<div id='content-slider-categ'></div>";
		echo "<div id='content-scroll-categ'>";
			echo "<div id='content-holder-categ'>";
				echo "<div class='content-item-categ'>";
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
		echo "</div>";
	echo "</div>";
	
	#************** department END
?>