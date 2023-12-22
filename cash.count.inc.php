<?php
	$hidTotal= $_REQUEST['hidTotal'];
	$txtCashB= str_replace(",","",$_REQUEST['txtCashB']);
	$txtCashB= $txtCashB * 1;

	$mSql = "SELECT MAX(TranRID) AS MaxTranRID FROM possales
		WHERE 
			TurnedOverShiftRID = 0 
			AND TranStatus=9
			AND ZREAD=0
			AND UserRID='$sesnLOGGEDPxRID';";
	$mQry = mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql<br>".mysqli_error($db_wgfinance));
	$mxMAXTran = 0;
	if ($tblMAX = $mQry->fetch_object()) $mxMAXTran = $tblMAX->MaxTranRID;

	$mSql = "SELECT MIN(TranRID) AS MinTranRID FROM possales
		WHERE 
			TurnedOverShiftRID = 0 
			AND TranStatus=9
			AND ZREAD=0
			AND UserRID='$sesnLOGGEDPxRID';";
	$mQry = mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql<br>".mysqli_error($db_wgfinance));
	$mxMINTran = 0;
	if ($tblMIN = $mQry->fetch_object()) $mxMINTran = $tblMIN->MinTranRID;

	$mSql = "SELECT * FROM eoshift 
		WHERE TurnOver=0 
			AND Deleted=0 
			AND UserRID='$sesnLOGGEDPxRID'
			ORDER BY ShiftRID DESC LIMIT 1;
		;";
	$mQry = mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql<br>".mysqli_error($db_wgfinance));
	if ($tblTU = $mQry->fetch_object())
	{
		$mSql = "UPDATE eoshift SET 
			EndShiftDate = NOW(),
			EndShiftDateTime = NOW(),
			Amount='$hidTotal',
			FromOR = '$mxMINTran',
			ToOR = '$mxMAXTran',
			CashBeginning='$txtCashB'
			WHERE UserRID='$sesnLOGGEDPxRID'
				AND TurnOver=0
				AND Deleted=0
		;";
	}
	else
	{
		$mSql = "INSERT INTO eoshift SET 
		EndShiftDate = NOW(),
		EndShiftDateTime = NOW(),
		UserRID='$sesnLOGGEDPxRID',
		Amount='$hidTotal',
		FromOR = '$mxMINTran',
		ToOR = '$mxMAXTran',
		CashBeginning='$txtCashB'
		;";
	}
	@mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql<br>".mysqli_error($db_wgfinance));

	
	
	
	# move the bills from denominations
	$mSql = "SELECT * FROM eoshift 
		WHERE UserRID='$sesnLOGGEDPxRID'
			AND TurnOver=0 
			AND Deleted=0 
		ORDER BY ShiftRID DESC LIMIT 1;";
	$mQry = mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql<br>".mysqli_error($db_wgfinance));
	if ($tbl = $mQry->fetch_object())
	{
		$mxShiftRID = $tbl->ShiftRID;
		
		$mSql = "DELETE FROM eoshift_turnover 
				WHERE ShiftRID = '$mxShiftRID';";
		@mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql<br>".mysqli_error($db_wgfinance));
		
		
		$mSql = "INSERT INTO eoshift_turnover (ShiftRID,bill,qty)
			SELECT $mxShiftRID, bill, qty FROM denominations
				WHERE qty > 0;";
		@mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql<br>".mysqli_error($db_wgfinance));
	
	
		$mSql = "UPDATE possales SET
			TurnedOverShiftRID = '$mxShiftRID'
			WHERE UserRID='$sesnLOGGEDPxRID'
				AND TurnedOverShiftRID = 0
				AND ZREAD=0;";
		@mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql<br>".mysqli_error($db_wgfinance));
		
		$mSql = "UPDATE returns SET
			TurnedOverShiftRID = '$mxShiftRID'
			WHERE UserRID='$sesnLOGGEDPxRID'
				AND TurnedOverShiftRID = 0
				AND ZREAD=0;";
		@mysqli_query($db_wgfinance,$mSql) OR DIE("$mSql<br>".mysqli_error($db_wgfinance));
		
	}	
?>