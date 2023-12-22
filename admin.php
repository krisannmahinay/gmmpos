<head>
<link rel='stylesheet' type='text/css' href='admin.css'>  
<link rel='stylesheet' type='text/css' href='alnx.css'>  

<script type="text/javascript" >
	document.domain='<?=$_SERVER['SERVER_NAME']?>';
</script>

</head>
<body onunload="window.opener.popUpClosed();">
<?php
include_once('wfslib/WalnetFunctionsPOS.php');

require_once("wfslib/wfsclass.php");
$clsWalnet = new clsWalnet();

wfsDBfnb();

?>

<div id="buttonA">
    <ul>

        <li><a href="#l">Manage Users</a></li>
        <li><a href="#">Button 3</a></li>
		
        <li><a href="index.php">Sales Panel</a></li>		
    </ul>
</div>


<?php
			
echo "<input class='red' type=button name='buttPRODUCTS' value='Products'
			onclick=\"window.open('products.php','popup',
				'width=960,height=600,scrollbars=yes,resizable=yes,toolbar=no,directories=no,location=no,menubar=no,status=no,left=10,top=10');
				return false\"
			>";

?>	


	
</body>