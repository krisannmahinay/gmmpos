<?php
$UserName = GetUserInfo($sesnCMUserRID, 1);

$wid = 36;

$orgn = GetOrgSetUp(1);
$org = str_pad("".$orgn."", $wid, $pad, STR_PAD_BOTH);
fwrite($wfp, $org);
fwrite($wfp, chr(13).chr(10));

$orgsub = GetOrgSetUp(2);
$orgsub = str_pad($orgsub, $wid, $pad, STR_PAD_BOTH);
fwrite($wfp, $orgsub);
fwrite($wfp, chr(13).chr(10));

$orgadr = GetOrgSetUp(3);
$orgadr = str_pad($orgadr, $wid, $pad, STR_PAD_BOTH);
fwrite($wfp, $orgadr);
fwrite($wfp, chr(13).chr(10));

#fwrite($wfp, chr(13).chr(10));
$orgacc = str_pad("ACC.No. XXX-XXXXXXXXXXX-XXXXXX", $wid, $pad, STR_PAD_BOTH);
fwrite($wfp, $orgacc);
fwrite($wfp, chr(13).chr(10));

$orgtin = str_pad("TIN: XXX-XXX-XXX-XXX", $wid, $pad, STR_PAD_BOTH);
fwrite($wfp, $orgtin);
#fwrite($wfp, chr(13).chr(10));



# 	JOURNAL TYPE HEADER
fwrite($wfp, chr(13).chr(10));
fwrite($wfp, chr(13).chr(10));

$mxjt = str_pad($sesnCMType, $wid, $pad, STR_PAD_BOTH);
fwrite($wfp, $mxjt);

fwrite($wfp, chr(13).chr(10));
fwrite($wfp, chr(13).chr(10));

$mTrj = str_pad("CM #: ", 28, " ", STR_PAD_LEFT).
	str_pad($sesnCMRID, 8, "0", STR_PAD_LEFT);
fwrite($wfp, $mTrj);
fwrite($wfp, chr(13).chr(10));

fwrite($wfp, $orgline);

fwrite($wfp, chr(13).chr(10));

$mDt = wfs_Date_from_DATE($sesnCMDateTime, 4);
$mDt = str_pad($mDt, 26, " ", STR_PAD_RIGHT);
fwrite($wfp, $mDt);



fwrite($wfp, chr(13).chr(10));

if ($sesnCMPrinted > 1)
{
	$mxReprint = "REPRINT #$sesnCMPrinted";
	$mDupli = str_pad($mxReprint, 36, " ", STR_PAD_LEFT);
	fwrite($wfp, $mDupli);

	fwrite($wfp, chr(13).chr(10));
}

$mCashr = "Cashier: $UserName";
$mCashr = str_pad($mCashr, 18, " ", STR_PAD_RIGHT);
fwrite($wfp, $mCashr);

fwrite($wfp, chr(13).chr(10));
?>