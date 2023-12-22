<?php

$oldumask = umask(0);
mkdir("c:/temp/xxx", 0777); // or even 01777 so you get the sticky bit set
umask($oldumask)
			

/*

$myFile = "zgrab.exe";
$fh = fopen($myFile, 'a') or die("can't open file");

$stringData = "a , New Stuff 1\n";
fwrite($fh, $stringData);

$stringData = "b, New Stuff 2\n";
fwrite($fh, $stringData);

fclose($fh);


$file_handle = fopen($myFile, "rb");

while (!feof($file_handle) ) 
{
	$line_of_text = fgets($file_handle);
	$parts = explode('=', $line_of_text);

	print $parts[0] . $parts[1]. "<BR>";
}

fclose($file_handle);
*/
?>
DONE!