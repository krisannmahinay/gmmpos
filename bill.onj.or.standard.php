<?php
$xSRPj = number_format($xSRP, 2);
$isrp = str_pad(" ", $a, $pad, STR_PAD_RIGHT).
str_pad("@ ".$xSRPj, $c, $pad, STR_PAD_RIGHT).
str_pad($mExtendFormatted, $c, $pad, STR_PAD_LEFT);	
fwrite($wfp, $isrp);

fwrite($wfp, chr(13).chr(10));


$xLDisc = number_format($xLineDisc, 2);
$lineNet = number_format($mExtend - $xLineDisc, 2);
$isrp = str_pad(" ", $a, $pad, STR_PAD_RIGHT).
str_pad("disc: ".$xLDisc, $b, $pad, STR_PAD_RIGHT).
str_pad($lineNet, $c, $pad, STR_PAD_LEFT);	
fwrite($wfp, $isrp);

?>