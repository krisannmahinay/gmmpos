<?php
ini_set("session.bug_compat_warn","off");
@session_start();

include_once('wchensPOS.php');

@$sesnIDept = $_SESSION['sesnIDept'];
@$sesnIDeptButtonScheme	= $_SESSION['sesnIDeptButtonScheme'];

echo "<div class='grid_7' style='border:2px solid #000'>";
	echo "<div class='box'>";
	
		echo "<h2>";
			echo "<a href='#' id='toggle-list-items'>Settings</a>";
		echo "</h2>";
		
		#echo "<div>";# class='block' id='SALES'>";
			echo "<form method='get' action='' class='search'>";
			#echo "<p>";
			echo "<input class='search text' name='value' type='text'
				style='width:180px'/>";
			echo "<input class='search button orange' value='Search' type='submit' />";
			#echo "</p>";
			echo "</form>";
		#echo "</div>";

		#echo "<div class='block' id='list-items' 
		echo "<div style='text-align:center; height:408px;
				overflow: auto;'>";

			$mSql = "SELECT * FROM fnbproduct 
				WHERE DeptCode='$sesnIDept' AND Deleted=0 
				ORDER BY ButtonLabel;";
			$mQry = mysqli_query($db_wgfinance, $mSql) OR DIE(mysqli_error($db_wgfinance));
			
			echo "<table border=0 width='100%'>";
			echo "<tr>";
			
			$mxMaxCols = 2; #number of cols
			$mxCol=0; 
			
			while ($row = $mQry->fetch_object())
			{
				echo "<th class='wpadd' width='33%'>";
					echo "<a class='buttoni $sesnIDeptButtonScheme' 
						style='display:block;' href='#'>
						$row->ButtonLabel
						</a>";
				echo "</th>";
				
				if ($mxCol < $mxMaxCols)
				{
					/*echo "<th class='wpadd' width='33%'>";
					echo "<a class='buttoni $sesnIDeptButtonScheme' 
						style='display:block;' href='#'>
						
						$row->ButtonLabel
						</a>";
						
					echo "</th>";*/
					
					$mxCol++;
				}
				else
				{
					echo "</tr>";
					
					echo "<tr>";
						
					$mxCol=0;
				}
				
				
			}	
			echo "</tr>";
			echo "</table>";	
		echo "</div>";
		
	echo "</div>";
	
echo "</div>";


?>	