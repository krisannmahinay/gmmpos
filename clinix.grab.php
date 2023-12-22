<?php
@session_start();
include_once('wfslib/WalnetFunctionsPOS.php');

include_once('wchensMDX.php');

require_once("wfslib/xcls.clinix.php");
$clsClinix = new Clinix();

$today = wfsGetSysDate(0);

$mSql = "SELECT clinix.*, LastName, FirstName, Balance
		FROM clinix
		INNER JOIN px_data ON clinix.PxRID=px_data.PxRID
		WHERE DateVisit<='$today' 
			AND POSTranStatus<9 
			AND (TranStatus <> -1 AND TranStatus <> -2)
		ORDER BY RID;";
		#TranStatus DESC, DokPxRID, DateVisit, Priority, RID;"; 
		#(cancelledappt=0) AND 
#echo "<p>$mSql</p>";

$mQryQUEUE = mysqli_query($db_ipadrbg,$mSql) OR DIE("$mSql.<br>".mysqli_error($db_ipadrbg));

$mX = 0;
$mDoki = -1;
$mShowHeads = TRUE;

echo "<table width=100% border='0'>";

while ($tblClinix=$mQryQUEUE->fetch_object())
{
	if ($mShowHeads)
	{
		echo "<tr><th colspan=2><a class='button blue' style='display:block' 
			href='clinix.grab.reset.php'>TEMPORARY RESET</a></th></tr>";
		
		echo "<tr>";
		echo "<th class='ColHeadsWdBorder' width='1%' nowrap>File #</th>";
		echo "<th class='ColHeadsWdBorder'>Name</th>";
		echo "<th class='ColHeadsWdBorder' width='1%' nowrap>Discount</th>";		
		echo "<th class='ColHeadsWdBorder' width='1%' nowrap>Fee</th>";
		
		echo "<th class='ColHeadsWdBorder' width='1%' nowrap>Dok</th>";
		echo "<th class='ColHeadsWdBorder' width='1%' nowrap>Appt Date</th>";
		#echo "<th class='ColHeadsWdBorder' width='1%' >Appt Type</th>";
		echo "<th class='ColHeadsWdBorder' width='1%' >Status</th>";

		echo "</tr>";
		$mShowHeads = FALSE;
	}

	$bcl=($mX%2==0)? "#EEEEEE":"#FFFFFF";
	$mX++;
	
	$mxiClinixRID = $tblClinix->RID;
	
	echo "<tr style='background:$bcl'>";
		echo "<td class='wtitle' align='center' nowrap>";
			if ($tblClinix->TranStatus == 0 ||
			    $tblClinix->TranStatus == 8)
				echo $tblClinix->RID;
			else
			{
				echo "<a class='button orange small' style='display:block;'
				href='clinix.gproc.prep.php?crid=$mxiClinixRID'>
				$tblClinix->RID</a>";
			}
		echo "</td>";

		$mName = $tblClinix->LastName.", ".$tblClinix->FirstName;
		echo "<th class='wtitle' align='left' colspan=1 nowrap>$mName</th>";
		
		echo "<td class='wtitle' align='right' nowrap>";
			echo number_format($tblClinix->Discount,2);
		echo "</td>";
		
		echo "<td class='wtitle' align='right' nowrap>";
			echo number_format($tblClinix->AmountDue,2);
		echo "</td>";
		
		$mDok = $tblClinix->DokPxRID;
		$mxDok = GetPatientInfo($mDok, 5);
		echo "<th class='wtitle' nowrap>$mxDok</th>";

		$z = ($tblClinix->DateVisit==NULL)? wfs_Date_from_DATE($today, 12) : wfs_Date_from_DATE($tblClinix->DateVisit, 12);		

		echo "<td class='wtitle' nowrap>$z</td>";
		
		/*echo "<td class='NoTopBotm' align='center' nowrap>";
			echo $clsClinix->ApptType($tblClinix->ApptType);
		echo "</td>";*/
		
		echo "<td class='wtitle text-center' nowrap>";
			echo $clsClinix->TranStatus($tblClinix->TranStatus);	
		echo "</td>";		
	echo "</tr>";			

	#deails
	$mSql = "SELECT * FROM clinix_charges INNER JOIN lkup_clinixcharges ON 
			clinix_charges.FeeRID = lkup_clinixcharges.FeeRID
			WHERE clinix_charges.ClinixRID = $mxiClinixRID
				AND clinix_charges.Deleted=0;";
	$mQryCharge = mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql.<br>".mysqli_error($db_wgfinance));	
	while ($tblCharge = $mQryCharge->fetch_object())
	{
		#$mFeeRID = $tblCharge->FeeRID;
		echo "<tr>";
			echo "<td>&nbsp;</td>";
			echo "<td class='wpadd' colspan=2 nowrap>";
				echo $tblCharge->Description;
			echo "</td>";
			echo "<td class='wpadd' align='right' nowrap>";
				$mCharge = number_format($tblCharge->Amount, 2);
				echo $mCharge;
			echo "</td>";
		echo "<tr>";
	}
}
echo "</table>";
?>