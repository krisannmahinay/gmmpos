<!DOCTYPE html>
<html>
<head>
<title>GMMR POS</title>
<link rel='stylesheet' type='text/css' href='css/reset.css' media='screen' />
<link rel='stylesheet' type='text/css' href='css/layout.css' media='screen' />
<link rel='stylesheet' type='text/css' href='css/main.css' media='screen' />
<link rel='stylesheet' type='text/css' href='css/medix5.css' media='screen' />
<style>
div {
    position: fixed;
    top: 45%;
    left: 50%;
    -webkit-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
	font-size:24px;
	text-align:center;
}
</style>

</head>
<body onload="moveTo(150,150); resizeTo(650,450);">

<div> 
<?php
@session_start();

include_once 'sys.inc.php';

if ($sesnLOGYOgid == "nagid")
{
	echo "Your activation key has Expired. Please purchase a new 
		activation key from SOFTMO so you can continue using this system.";
	@session_destroy();
	die();
}	
elseif ($sesnLOGYOgid == "lapit")
{
	echo "Your activation key will Expire in ".$sesnLOGYO.
		" day(s). Please purchase a new activation key from 
		SOFTMO so you can continue using this system.";
}	
/*
.popup {
    position: fixed;
    top: 50%;
    left: 50%;
    -webkit-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
}
div {
	position: absolute;
	margin: auto;
	top: 0;
	right: 0;
	bottom: 0;
	left: 0;
	width: 600px;
	height: 300px;
	font-size:24px;
	text-align:center;
}
*/
?>
</div>