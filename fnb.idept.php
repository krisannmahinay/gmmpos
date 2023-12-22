<?php
ini_set("session.bug_compat_warn","off");
@session_start();
include_once('wchensPOS.php');

echo "<div class='grid_4' style='border:2px solid #000'>";
	echo "<div class='box'>";
		echo "<h2>";
			echo "<a href='#' id='toggle-list-items'>Menu</a>";
		echo "</h2>";
			
			
			
		echo "<div class='block' id='list-items' 
			style='text-align:center; height:420px;
			overflow: auto;'>";
				
			echo "<table border=0 width='100%'>";
			
			$mSql = "SELECT * FROM fnbdepartment WHERE Deleted=0 
				ORDER BY DeptCode;";
			$mQry = mysqli_query($db_wgfinance, $mSql) OR DIE(mysqli_error($db_wgfinance));
		
			while ($row = $mQry->fetch_object())
			{
				$mxButtonScheme = $row->ButtonScheme;
				$mxDeptCode = $row->DeptCode;
				
				echo "<tr style='height:20px'>";
				echo "<th class='wpadd' width='50%'>";
					echo "<a class='buttoni $mxButtonScheme' 
						style='display:block;' href='index.dept.prep.php?idept=$mxDeptCode&ischm=$mxButtonScheme'>
						$row->DeptCode $row->ButtonLabel</a>";
				echo "</th>";
				
				echo "</tr>";
			}	
			echo "</table>";	
		echo "</div>";
		
		
	echo "</div>";
echo "</div>";
?>