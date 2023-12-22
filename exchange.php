<head>
<link rel='stylesheet' type='text/css' href='receiving.css'>
<script language="JavaScript">
	document.domain='<?=$_SERVER['SERVER_NAME']?>';
	function popUpClosed() 
	{
		window.location.reload();
	}
	function funcDetectKey(evt)
	{
		switch(evt.keyCode)
		{
			case 27:    //ESCped
				window.close();
		      break;
		}//end of switch	
	}
	
	function decision(message)
	{
		if(confirm(message) )
		{
			return true;
		}
		else
		{
			return false;
		}
	}
</script>
<body onunload="window.opener.popUpClosed();" onKeyDown='funcDetectKey(event);'
	onload="document.frmTransfer.txtPLU.focus()">
</head>
<?php
require_once("wfslib/wfsclass.php");
$clsWalnet = new clsWalnet();
#@session_save_path($clsWalnet->zwSessPath);
#@session_start();

include_once('wfslib/WalnetFunctionsPOS.php');
@wfsDBfnb();

$SYSUserLogged = wfs_WhoIsLogged();
if ($SYSUserLogged==0) echo "<script>location='index.php';</script>";

#require current transasciton to be completed first
$TranRID  = wfs_GetCurrentTranRID();
if ($TranRID >0)
{
	echo "<br><br><h1 align=center>CURRENT TRANSACTION IS IN PROGRESS!<br><br>
		Cannot process STOCK RETURNS/EXCHANGE request at this time.
		<br><br><br>press ESC to exit.</h1>";
	#echo "<script>window.close();</script>";
}
else
{
	include('exchange.info.0.php');
	
	@$buttSAVE = $_REQUEST['buttSAVE'];	
	if (isset($buttSAVE))
	{
		$hidRetDetRID 	= $_REQUEST['hidRetDetRID'];
		$txtRetQty		= $_REQUEST['txtRetQty'];
		for ($i=0; $i<count($hidRetDetRID); $i++)
		{
			#echo "<br>$hidRetDetRID[$i] ---- $txtRetQty[$i]";
			$mHid = $hidRetDetRID[$i] * 1;
			$mQty = $txtRetQty[$i] * 1;
			$mSql = "UPDATE returns_details_current SET ExchangedQty=$mQty WHERE ExchangeDetailRID	=$mHid;";
			@mysql_query($mSql) OR DIE("$mSql<br>".mysql_error());
			
			#repair/reset qunatities
			$mSql = "UPDATE returns_details_current SET ExchangedQty=0 WHERE SoldQty < ExchangedQty;";
			@mysql_query($mSql) OR DIE("$mSql<br>".mysql_error());
		}
		@CalcReturnsTotal($ExchangeRID);
		#echo "<script>alert('Hello!');</script>";
	}

	@$buttGO = $_REQUEST['buttGO'];	
	if (isset($buttGO))
	{
		@$txtORNo = $_REQUEST['txtORNo'];
		
		$mSql = "SELECT * FROM order_master WHERE TranRID='$txtORNo';";
		$mQry = mysql_query($mSql) OR DIE("$mSql<br>".mysql_error());
	
		if ($tblOR = mysql_fetch_object($mQry))
		{
			$TranRID = $txtORNo;
			
			#filter TranRID status os completed
			$TranStatus = GetOLDTransInfo($TranRID, 8);
			if ($TranStatus==$clsWalnet->SalesStatusPAID)
			{
				include_once("exchange.orok.php");
			}
			else
			{
				echo "<script>alert('That OR cannot be processed for Exchange due to it`s status!');</script>";
				@mysql_query("DELETE FROM exchange_current;") OR DIE(mysql_error());
				@mysql_query("DELETE FROM exchange_details_current;") OR DIE(mysql_error());
			}
		}
		else
		{
			$txtMess = "OR number $txtORNo is not in file!";
		}
		echo "<script>location('exchange.php');</script>";
	}

	include('exchange.info.0.php');

	echo "<form name='frmExchange' action='exchange.php' method='POST' autocomplete='off'>";

	echo "<table width=100% border=0 bgcolor='#FFFFFF'>";
	echo "<tr bgcolor='#E9E9CC'>";
	echo "<td valign='top' width='1%'>";

	#************************ DATA
	$bgd = "magenta";
	echo "<table width=100% border=0 bgcolor='#FFFFFF'>";

	echo "<tr bgcolor='#E9E9CC'>";
		echo "<th colspan=9>";
		echo "<span style='font-size:20; color: blue;'>RETURNS for EXCHANGE</span>";
		echo "</th>";
	echo "</tr>";

	
	echo "<tr bgcolor='#000066'>";
		echo "<th class='nosides' width=1% align=center nowrap>";
			echo "<span style='font-size:12pt; color:white'>File Num.</span>";
		echo "</th>";
		echo "<th class='nosides' colspan=1 align=center width=1% bgcolor='$bgd' nowrap>$ExchangeRID</th>";
	
		echo "<th class='nosides' width=1% align=center nowrap>";
			echo "<span style='font-size:12pt; color:white'>Exchange Date</span>";
		echo "</th>";

		$mX = ($ExchangeDate==NULL)? "&nbsp;":wfs_Date_from_DATE($ExchangeDate, 1);
		echo "<th class='nosides' align=center colspan=1 bgcolor='$bgd' nowrap>$mX</span></th>";		
	echo "</tr>";	
	
	 echo "<tr bgcolor='#000066'>";
		echo "<th class='nosides' colspan=1 align=center align=right nowrap>
			<span style='font-size:12pt; font: impact; color:white'>Entered By</th>";		
		
		$mX = ($ExchangeEnteredBy ==NULL)? "&nbsp;": GetUserInfo($ExchangeEnteredBy, 1);
		echo "<th class='nosides' colspan=1 align=center bgcolor='$bgd' >
			<span style='font-size:12pt; font: impact; color:white'>$mX</span></th>";	

		$mX = GetSalesExchangeStatus($ExchangeStatus);
		echo "<th class='nosides' colspan=2 align=center bgcolor='$bgd' >
			<span style='font-size:12pt; font: impact; color:white'>$mX</span></th>";	
		
	echo "</tr>";	

	
	
	
	
	#or no input
	
	echo "<tr bgcolor='#000066'>";
		echo "<th class='nosides' width=1% align=center nowrap>";
			echo "<span style='font-size:12pt; color:white'>O.R. Num.</span>";
		echo "</th>";
		echo "<th class='nosides' colspan=1 align=center width=1% bgcolor='$bgd' nowrap>
			<input name=txtORNo value='$txtORNo' size=15 $W_readonly>";
			
			#echo "<input type='submit' value='search'>";
			if (! $TranStatus == $clsWalnet->SalesStatusPAID)
			{
				echo "<input type='submit' class='gray' name='buttGO' value='go' $W_disabled />";
			}
			
		echo "</th>";
	
		echo "<th class='nosides' colspan=1 align=center align=right nowrap>
			<span style='font-size:12pt; font: impact; color:white'>O.R. Date</th>";
		$mX = ($ORDate==NULL)? "&nbsp;":wfs_Date_from_DATE($ORDate, 1);
		echo "<th class='nosides' align=center colspan=1 bgcolor='$bgd' nowrap>
			<span style='font-size:12pt; font: impact; color:white'>$mX</span></span></th>";
	echo "</tr>";	

	
	echo "<tr bgcolor='#000066'>";
		echo "<th class='nosides' width=1% align=center nowrap>";
			echo "<span style='font-size:12pt; color:white'>O.R. BY</span>";
		echo "</th>";
		
		$mX = ($TranEnteredBy ==NULL)? "&nbsp;": GetUserInfo($TranEnteredBy, 1);
		echo "<th class='nosides' colspan=1 align=center bgcolor='$bgd' >
			<span style='font-size:12pt; font: impact; color:white'>$mX</span></th>";
	echo "</tr>";	


	echo "<tr bgcolor='#000066'>";
		echo "<th class='nosides' align=center colspan=1>
			<span style='font-size:12pt; color:white'>Total Qty</span></th>";
		
		$mX = ($ExchangedQty ==NULL)? "&nbsp;":$ExchangedQty;
		echo "<th class='nosides' colspan=1 align=center bgcolor='yellow' >
			<span style='font-size:18pt; font: impact; color: black'>$mX</span></th>";		
		
		echo "<th class='nosides' align=center colspan=1 nowrap>
			<span style='font-size:12pt; color:white'>Total Amount</span></th>";
		
		echo "<th class='nosides' align=right bgcolor='yellow' colspan=1>
			<span style='font-size:24pt; font: impact; color: black'>".
			number_format($ExchangeAmount,2)."</span></th>";
	echo "</tr>";	
	echo "</table>";
	#************************ DATA end

	echo "</td>";		
	echo "<td align='right' valign='top'>";
		
		echo "<table border=0 width=10% align=center cellpadding=2 cellspacing=2>";
		echo "<tr>";
		
		if (($ExchangeRID>0) && ($ExchangeStatus<=0))
		{	
			echo "<th nowrap>"; 
			echo "<a class='blue' target=_self onclick=\"return decision('CONFIRM RETURN Items?')\"
						href='returns.confirm.php?ExchangeRID=$ExchangeRID'>PROCESS</a>";
			echo "<th>";
			echo "<th nowrap><a class='blue' href='exchange.abort.php?ExchangeRID=$ExchangeRID'>ABORT</a><th>";
		}		
		else
		{
			echo "<th nowrap><a class='blue' href='exchange.new.php'>NEW</a><th>";
			echo "<th nowrap><a class='blue' href='exchange.list.php'>LIST</a><th>";
		}
		echo "</tr>";
		echo "</table>";
		
	echo "</td>";
	echo "</tr>";
	echo "</table>";

	echo "<table width=100% border=0 bgcolor='#FFFFFF'>";
	echo "<tr bgcolor='yellow'>";
	echo "<th with='1%' align=center>Qty</th>";		
	
	echo "<th with='1%' align=center>Qty To Exchange</th>";		
	
	echo "<th with='1%' align=center>PLU Code</th>";
	echo "<th>DESCRIPTION</th>";
	echo "<th with='1%' align=right>SRP</th>";
	echo "<th with='1%' align=right>Amount</th>";
	#echo "<th with='1%' align=center>Cancel</th>";
	echo "</tr>";		
	
	$mSql = "SELECT ExchangeDetailRID, ExchangeRID	, ProductRID, SoldPrice, DisLineCanceled,
		SoldQty, ExchangedQty, CancelledQty
		FROM exchange_details_current 
		WHERE ExchangeRID = $ExchangeRID
		ORDER BY ExchangeDetailRID	 DESC, ProductRID;";
		
	$mQry = mysql_query($mSql) OR DIE("$mSql<br>".mysql_query());
	$x=0;
	$mGrandT=0;
	$mTQty=0;
	$mRETQty=0;
	while ($tblOrders=mysql_fetch_object($mQry))
	{
		$bcl=($x%2==0)? "#EEEEEE":"#FFFFFF";
		$x++;
		echo "<tr bgcolor='$bcl'>";
			
		echo "<td align=center>";
			if ($tblOrders->DisLineCanceled==1)	
				echo "<s>$tblOrders->SoldQty</s>";
			else
			{
				echo $tblOrders->SoldQty;
				$mTQty += $tblOrders->SoldQty;
				$mRETQty += $tblOrders->ExchangedQty;
			}
		echo "</td>";
		
		echo "<td align=center>";
			echo "<input type=hidden name='hidExchDetRID[]' value='$tblOrders->ExchangeDetailRID	' size=5/>";
			$mQty = ($tblOrders->ExchangedQty>0)? $tblOrders->ExchangedQty : "";
			echo "<input type=text name='txtRetQty[]' value='$mQty' style='text-align:right;' size=5 $W_readonly/>";
		echo "</td>";
		
		echo "<td align=center>";
		$mUPC = GetItemInfoRID($tblOrders->ProductRID, 4);
		if ($tblOrders->DisLineCanceled==1)	
			echo "<s>$mUPC</s>";
		else
			echo "$mUPC";
		echo "</td>";
				
		echo "<td>";
			if ($tblOrders->ProductRID == $clsWalnet->NOTPLU)
			{
				$xDesc = "<span style='color: red'>ITEM NOT FOUND!</span>"; 
				$mSql = "DELETE FROM returns_detail_current WHERE ProductRID=".$clsWalnet->NOTPLU;
				@mysql_query($mSql) OR DIE(mysql_error());
			}
			else
			{
				$xDesc = GetItemInfoRID($tblOrders->ProductRID, 1); 
				if ($tblOrders->DisLineCanceled==1) $xDesc = "<s>$xDesc</s>";
			}
			echo $xDesc;
		echo "</td>";

		echo "<td align=right>";
			$xSRP = $tblOrders->SoldPrice;
			if ($tblOrders->DisLineCanceled==1)
				echo "<s>".number_format($xSRP,2)."</s>";
			else
				echo number_format($xSRP,2);
		echo "</td>";
	
		echo "<td align=right>";
			$xTend = $tblOrders->SoldPrice * $tblOrders->ExchangedQty;
			if ($tblOrders->DisLineCanceled==0) 
			{
				$mGrandT += $xTend;
				echo number_format($xTend,2);
			}
			else
			{
				#echo "<s>".number_format($xTend,2)."<s>";
				echo "&nbsp;";
			}
		echo "</td>";	
	
		/*
		if ($tblOrders->DisLineCanceled==1)	
			@$x = "<span style='color: red'>cancelled</span>";
		else
		{
			$x = ($W_disabled != 'disabled')? "<a class='redcancelA' 
					href='void.transfer.entry.prep.php?voidRID=$tblOrders->ExchangeDetailRID	&ReturnRID=$ExchangeRID'>
					$tblOrders->ExchangeDetailRID	</a>" : "&nbsp;";
		}
		echo "<th align=center>$x</th>";	
		*/
		
		echo "</tr>";
	}

	/*
	echo "<tr valign=center  bgcolor='yellow'>";
	echo "<th colspan=2 align=right><span style='font-size:14pt; color:#000000'>Total Discounts</span></th>";
	echo "<th align=right colspan=2><span style='font-size:16pt; color:#000000'>".number_format($TranDiscounts,2)."</span></th>";
	echo "</tr>";
	*/
	echo "<tr valign=center  bgcolor='yellow'>";
	echo "<th align=center colspan=1><span style='font-size:14pt; color:#000000'>"
		.number_format($mTQty, 0)."</span></th>";
	
	echo "<th align=center colspan=1><span style='font-size:14pt; color:#000000'>"
		.number_format($mRETQty, 0)."</span></th>";		
		
	echo "<th colspan=2 align=right><span style='font-size:14pt; color:#000000'>Total Exchanged</span></th>";
	echo "<th align=right colspan=2><span style='font-size:14pt; color:#000000'>"
		.number_format($mGrandT, 2)."</span></th>";
	echo "</tr>";
	echo "</table>";

	echo "<table = 10%>";
	echo "<tr bgcolor='#000066'>";
	echo "<th class='nosides' align=right>
		<span style='color:#FFFFFF'>Remarks</span></th>";
	echo "<th class='nosides' align=left>
		<textarea name='txtRemarks' cols=80 rows=3 $W_readonly>$txtRemarks</textarea></th>";			
	echo "</tr>";
	
	echo "<tr>";
		echo "<th class='nosides' colspan=9>";
		echo "<input type='submit' class='gray' name='buttSAVE' value='save' $W_disabled />";	
		echo "</th>";
	echo "</tr>";
	echo "</table>";
	echo "</form>";
}
?>