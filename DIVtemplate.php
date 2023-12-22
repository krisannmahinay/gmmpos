<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script src="jquery-1.3.2.min.js" language="javascript" type="text/javascript"></script>
<script src="jquery-ui-1.7.1.custom.min.js" language="javascript" type="text/javascript"></script>
<script src="slider_test.js" language="javascript" type="text/javascript"></script>
  
<link rel='stylesheet' type='text/css' href='main.css'>
<link rel='stylesheet' type='text/css' href='slider.css'>

  
</head>
<body>


<!-- DIV Scroller Script created with Spider Webmaster Tools (http://spiderwebmastertools.com). -->


<div style="width:600px">
	<div style="background-color:yellow">
		Header
	</div>
	<div style="background-color:#CCFFCC; width:400px;" >
		Lorem ipsum dolor sit amet, ut turpis sagittis, nec placerat, molestie convallis. Mattis et delectus, nullam cras et, faucibus ultrices. Nunc elit, tellus vulputate eros. Leo wisi, luctus pretium. Platea arcu, in natoque non, ipsum eu vivamus.
		<p align=right>Justo dictumst, aliquam metus. Libero sed vivamus, cursus felis etiam. Eu nonummy vestibulum, class excepturi. Nulla tincidunt urna. Phasellus ac lacus, sit eu massa. Velit pretium purus. Rem ac porta.</p>
	</div>
	
	<div style="background-color:#11FFCC; 
			border: solid 3px #21FA3D;
			position: absolute; left: 405px; top: 80px; height: 400px; width: 100px; padding: 0em;">
			
		<?php
		$items = array("V","HG","E","RETG ererg");
		for ($i=0; $i<count($items); $i++)
		{
			echo "<div style='background-color:orange; width:25px; float:left;'>$i</div>";
			echo "<div style='background-color:#FF3344; width:55px; float:left; text-align:right'>
				<span style='color:#FFFFFF;'>$items[$i]</span></div>";
			echo "<br>";
		}
		
		$items = array("V","HG","E","RETG ererg");
		echo "<table border=1>";
		for ($i=0; $i<count($items); $i++)
		{
			echo "<tr>";
			echo "<td>$i</td>";
			echo "<td align=right>$items[$i]</td>";
			echo "</tr>";
		}
		echo "</table>";
		?>
	</div>
	
  
	<div style="background-color:orange; height:250px; width:100px;float:left;">
		Left menu<br />
		Item 1<br />
		Item 2<br />
		Item 3...
	</div>
  
  
	<div style="background-color:#eeeeee; height:240px; width:300px; float:left;">
		Lorem ipsum dolor sit amet, ut turpis sagittis, nec placerat, molestie convallis. Mattis et delectus, nullam cras et, faucibus ultrices. Nunc elit, tellus vulputate eros. Leo wisi, luctus pretium. Platea arcu, in natoque non, ipsum eu vivamus.
		<p align=right>Justo dictumst, aliquam metus. Libero sed vivamus, cursus felis etiam. Eu nonummy vestibulum, class excepturi. Nulla tincidunt urna. Phasellus ac lacus, sit eu massa. Velit pretium purus. Rem ac porta.</p>
	</div>

	<div style="background-color:#22FFAC; height:100px; width:100%: float:left;">
		<table border=1 width=200 >
		<tr><td>ORDER SLIP</td></tr>
		<tr><td>ORDER SLIP</td></tr>
		</table>
	</div>
	
	<div style="background-color:#AAFFAC; height: 135px; width: 100% float: left">
		<table border=1 width=200>
		<tr>
		<td>dfa sdfasdf asdf </td>
		</tr>
		</table>
	</div>
	
  

  
  
  <div style="background-color:yellow; clear:both; ">
    Footer
  </div>
</div>


  
  

  
</body>
</html>