<!DOCTYPE html>
<html>
<head>
<title>GMMR POS</title>
<?php
include_once 'htmlhead.php';
?>
<script type="text/javascript" >
	document.domain='<?=$_SERVER['SERVER_NAME']?>';
	function funcDetectKey(evt)
	{
		switch(evt.keyCode)
		{
			case 27:    //ESCped
				window.close();
		      break;
		}//end of switch	
	}
</script>
</head>
<body onKeyDown='funcDetectKey(event);'>
<?php
@session_start();
include_once 'wfslib/WalnetFunctionsPOS.php';
ini_set("session.bug_compat_warn","off");

require 'wchensPOS.php';
include_once 'sys.inc.php';

if ($sesnLOGGEDPxRID<=0)
{
	echo "<script>alert('Please Log-in!');</script>";
	echo "<script>window.close();</script>";
}

@$cmdSave = $_REQUEST['cmdSave'];
if ($cmdSave == "Compute")
{
	$hidBill= $_REQUEST['hidBill'];
	$txtQty= $_REQUEST['txtQty'];

    for ($i=0; $i<count($hidBill); $i++)
    {
        $bill=$hidBill[$i];
		$qty =$txtQty[$i];
		
		/*#echo "<script>alert('$buttCancel[$i]');</script>";
		echo "<br>";
		echo $bill;
		echo "&nbsp;&nbsp;";
		echo $qty;
		*/
		$mSql = "UPDATE denominations SET qty='$qty'
			WHERE bill=$bill;";
		@mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql<br>".mysqli_error($db_wgfinance));
    }
	
	require 'cash.count.inc.php';
}
elseif ($cmdSave == "Reset")
{
	$mSql = "UPDATE denominations SET qty=0;";
	@mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql<br>".mysqli_error($db_wgfinance));
}
elseif ($cmdSave == "Turn-Over")
{
	require('cash.count.inc.php');

	#GET THE CONCENRED SHITRID
	#UPDATE SHIFTRID OF POSSALES
	#UPDATE THE SHIFT eoshift=1

	$mSql = "SELECT * FROM eoshift 
		WHERE TurnOver=0 
			AND Deleted = 0 
			AND UserRID='$sesnLOGGEDPxRID'
			ORDER BY ShiftRID DESC LIMIT 1;
		;";
	$mQry = mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql<br>".mysqli_error($db_wgfinance));
	if ($tblTU = $mQry->fetch_object())
	{
		$mxShiftRID = $tblTU->ShiftRID;
	
		$mSql = "UPDATE eoshift SET 
			TurnOver = 1 
			WHERE UserRID='$sesnLOGGEDPxRID'
				AND ShiftRID = '$mxShiftRID'
				AND TurnOver=0 
				AND Deleted=0;";
		@mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql<br>".mysqli_error($db_wgfinance));
	}	
	
	echo "<script>alert('CASH TURN-OVER SUCCESSFUL!');</script>";
	echo "<script>window.close();</script>";
}

$mSql = "SELECT * FROM eoshift 
		WHERE 
			UserRID		 = '$sesnLOGGEDPxRID'
			AND TurnOver = 0
			AND Deleted	 = 0
		ORDER BY ShiftRID DESC LIMIT 1;";
$mQry = mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql<br>".mysqli_error($db_wgfinance));
if ($tbl = $mQry->fetch_object())
	$txtCashB = $tbl->CashBeginning;
else
	$txtCashB = 0;

$today = wfsGetSysDate(0);
$orgn = GetOrgSetUp(1);
$mxUserName = GetUserInfo($sesnLOGGEDPxRID, 1);

?>
<div class='container_16' style='text-align: center'>

<form name='frmDenom' method='POST' action='cash.count.php' AUTOCOMPLETE=OFF>

<table border=0 width=100%>

<tr bgcolor='#00000066'>
<th colspan='99' bgcolor='white' nowrap><span style='color:blue; font-size:x-large'>
	TURN-OVER 
	DENOMINATIONS</span>
	<br>
	<?php 
	echo wfs_Date_from_DATE($today, 14) ; 
	echo "<br>$mxUserName";
	?>
	</th>
</tr>

<tr bgcolor='#00000066'>
<th class='wtitle' width='1%' nowrap><span = style='color: white;'>BILL</span></th>
<th class='wtitle' width='1%' nowrap><span = style='color: white;'>QTY</span></th>
<th class='wtitle' nowrap><span = style='color: white;'>AMOUNT</span></th>
</tr>

<?php
$mSql = "SELECT * FROM denominations ORDER BY bill DESC;";
$mqry = mysqli_query($db_wgfinance,$mSql) OR DIE ("$mSql<br>".mysqli_error($db_wgfinance));
$x=0;
$mTtlQty = 0;
$mTtl = 0;
while ($tblDenom=$mqry->fetch_object())
{
	$bgc = (($x%2)==0)? "#FFCCFF":"#FFFFCC";
	$x++;
	
	echo "<trbgcolor=$bgc>";
	
	echo "<td class='extamount' align=right nowrap>".number_format($tblDenom->bill,2)."</td>";
	$mQty = ($tblDenom->qty == 0)? NULL : $tblDenom->qty;
	echo "<td class='wpadd' align=center nowrap>
		<input type='hidden' name='hidBill[]' value='$tblDenom->bill' size=3 readonly>
		<input name='txtQty[]' value='$mQty' size=3
		style='text-align: center:font-size'
		>
		</td>";
	
	echo "<td class='extamount' align=right>".number_format($tblDenom->qty * $tblDenom->bill, 2)."</td>";
	
	$mTtlQty += $tblDenom->qty;
	$mTtl += $tblDenom->qty * $tblDenom->bill;
	
	echo "</tr>";
}
?>

<tr>
	<th class='wpadd' nowrap>TOTAL</th>
	<th class='wpadd' align=dept>
		<?php echo number_format($mTtlQty);?>
	</th>
	<th class='total' align=right>
		<?php 
		echo "<input type='hidden' name='hidTotal' value='$mTtl'>";
		echo number_format($mTtl, 2);
		?>
	</th>
</tr>

<tr>
	<th class='wpadd'>&nbsp;</th>
	<th class='wpadd' nowrap>Cash Beginning</th>
	<th class='total' align=right>
		<?php
		echo "<input type=text name='txtCashB' value='$txtCashB' style='text-align:right' size=10>";
		?>
	</th>
</tr>

<tr>
	<td colspan='19'>
		<table width='50%'>
		<tr>
			<!-- th class='wpadd WithBorders' colspan=9 nowrap>PAYMENTS RUNDOWN</th -->
			<?php
				include_once 'rep.XREAD.0.php';
			?>
			</th>
		</tr>
		
		<tr>
			<td class='wpadd WithBorders' align='right'>CHEQUE PAYMENTS</td>
			<td class='wpadd WithBorders' align='right'>
				<?php
				$sesnXR_CR_CHQUE_COUNT = $_SESSION['sesnXR_CR_CHQUE_COUNT'];
				echo number_format($sesnXR_CR_CHQUE_COUNT,0);
				?>
			</td>
			
			<td class='wpadd WithBorders' align='right'>
				<?php
				$sesnXR_CR_CHQUE = $_SESSION['sesnXR_CR_CHQUE'];
				echo number_format($sesnXR_CR_CHQUE,0);
				?>
			</td>
		</tr>
		
		<tr>
			<td class='wpadd WithBorders' align='right'>CHARGE</td>
			<td class='wpadd WithBorders' align='right'>
				<?php
				$sesnXR_CHARGE_COUNT = $_SESSION['sesnXR_CHARGE_COUNT'];
				echo number_format($sesnXR_CHARGE_COUNT,0);
				?>
			</td>
			
			<td class='wpadd WithBorders' align='right'>
				<?php
				$sesnXR_CHARGE = $_SESSION['sesnXR_CHARGE'];
				echo number_format($sesnXR_CHARGE,0);
				?>
			</td>
		</tr>
		
		<tr>
			<td class='wpadd WithBorders' align='right'>CREDIT CARD</td>
			<td class='wpadd WithBorders' align='right'>
				<?php
				$sesnXR_CR_CARD_COUNT = $_SESSION['sesnXR_CR_CARD_COUNT'];
				echo number_format($sesnXR_CR_CARD_COUNT,0);
				$sesnXR_CR_CARD = $_SESSION['sesnXR_CR_CARD'];
				?>
			</td>
	
			<td class='wpadd WithBorders' align='right'>
				<?php 
				echo number_format($sesnXR_CR_CARD,2);
				?>
			</td>
		</tr>

		<tr>
			<td class='wpadd WithBorders' align='right'>CREDIT MEMO</td>
			<td class='wpadd WithBorders' align='right'>
				<?php
				$sesnXR_CM_COUNT = $_SESSION['sesnXR_CM_COUNT'];
				echo number_format($sesnXR_CM_COUNT,0);
				?>
			</td>
			
			<td class='wpadd WithBorders' align='right'>
				<?php
				$sesnXR_CM = $_SESSION['sesnXR_CM'];
				echo number_format($sesnXR_CM,2);
				?>
			</td>
		</tr>
  
		<tr>
			<td class='wpadd WithBorders' align='right'>CASH</td>
			<td class='wpadd WithBorders' align='right'>
				<?php
				$sesnXR_CASH_COUNT = $_SESSION['sesnXR_CASH_COUNT'];
				echo number_format($sesnXR_CASH_COUNT,0);
				?>
			</td>
			<td class='wpadd WithBorders' align='right'>
				<?php
				$sesnXR_CASH = $_SESSION['sesnXR_CASH'];
				echo number_format($sesnXR_CASH,2);
				?>
			</td>
		</tr>
 
		<tr>
			<td class='wpadd WithBorders' align='right'>DEBIT CARD</td>
			<td class='wpadd WithBorders' align='right'>
				<?php
				$sesnXR_DR_CARD_COUNT = $_SESSION['sesnXR_DR_CARD_COUNT'];
				echo number_format($sesnXR_DR_CARD_COUNT,0)
				?>
			</td>
			
			<td class='wpadd WithBorders' align='right'>
				<?php
				$sesnXR_DR_CARD = $_SESSION['sesnXR_DR_CARD'];
				echo number_format($sesnXR_DR_CARD,2);
				?>
			</td>
		</tr>

		<tr>
			<td class='wpadd WithBorders' align='right'>GIFT CERTI</td>
			<td class='wpadd WithBorders' align='right'>
				<?php
				$sesnXR_GIFT_CERT_COUNT = $_SESSION['sesnXR_GIFT_CERT_COUNT'];
				echo number_format($sesnXR_GIFT_CERT_COUNT,0);
				?>
			</td>
			<td class='wpadd WithBorders' align='right'>
				<?php
				$sesnXR_GIFT_CERT = $_SESSION['sesnXR_GIFT_CERT'];
				echo number_format($sesnXR_GIFT_CERT,2);
				?>
			</td>
		</tr>
		</table>
	</td>	
</tr>

<!-- ********************************************************* -->



<tr>
	<th nowrap colspan=3>	
		<input class='button orange' type='submit' name='cmdSave' value='Reset'>
		<input class='button blue' type='submit' name='cmdSave' value='Compute'>
	</th>
</tr>

<tr>
	<th nowrap colspan=9>
		<input class='button red' type='submit' name='cmdSave' value='Turn-Over'>
	</th>
</tr>

</table>
</form>
</div>
<?php include "footer.php";?>
</body>