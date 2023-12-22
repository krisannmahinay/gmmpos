<?php
@$buttTBLS = $_REQUEST['buttTBLS'];
if (isset($buttTBLS))
{
    for ($i=0; $i<count($buttTBLS); $i++)
    {
        #echo $buttTBLS[$i];
		#$_SESSION['SESNTableSELECTED'] = $buttTBLS[$i];
		#$_SESSION['SESNTableSELECTEDRID'] = GetTableInfoNAME($_SESSION['SESNTableSELECTED'], 1);
		
		$clsWalnet->CreateTableGrab($buttTBLS[$i]);
		
		#$clsWalnet->SETTableSelected = $buttTBLS[$i];
		#$clsWalnet->GETTableSelectedRID = GetTableInfoNAME($buttTBLS[$i] , 1);
    }
}

@$buttTblOK = $_REQUEST['buttTblOK'];
if (isset($buttTblOK))
{
	#echo "<script>alert('Ma OK BA!');</script>";
	$txtTblGuests 	= $_REQUEST['txtTblGuests'];
	$cboUsers		= ($_REQUEST['cboUsers']==0)? "" : $_REQUEST['cboUsers'];
	
	$x = $clsWalnet->ReturnTableNumber();
	$TableNo = GetTableInfoNAME($x, 1) * 1;
	if ($TableNo==0)
		echo "<script>alert('NO TABLE Selected!');</script>";
	else
	{
		if (strlen(check_input($txtTblGuests, 'Please specify the number of guests.')))
		{
			$txtTblGuests = $txtTblGuests * 1;
			if (strlen(check_input($cboUsers, 'Please specify the person who serves this table!')))
			{	
				#create order_master record for this table
				$mSql = "INSERT INTO order_master SET 
					tableno=$TableNo,
					TranDate=NOW(),
					UserRid = $cboUsers,
					ServedBy = $cboUsers,
					guests = $txtTblGuests,
					TranStatus = 1,
					SalesType = ".$clsWalnet->SalesTypeTABLE."
					;";
				@mysql_query($mSql) OR DIE("$mSql<br>".mysql_error());
			
				#GETs RID
				$mSql = "SELECT MAX(TranRID) AS MaxRID FROM order_master 
					WHERE tableno=$TableNo AND UserRid =$cboUsers
					LIMIT 1;";
				$mQry = mysql_query($mSql) OR DIE("$mSql<br>".mysql_error());	
				$tblO = mysql_fetch_object($mQry);
				$NewOrderRid = $tblO->MaxRID;
			
				#update table record
				$mSql = "UPDATE tables SET 
					Occupied = 1,
					TranRID = $NewOrderRid,
					ServedBy = $cboUsers,
					guests = $txtTblGuests
					WHERE tableno=$TableNo;";
				@mysql_query($mSql) OR DIE("$mSql<br>".mysql_error());
			}
		}
	}
}

@$buttTblMove = $_REQUEST['buttTblMove'];
if ($buttTblMove)
{
	$MoveTo = $_REQUEST['cboTablesAvail'];
	#echo "<script>alert('Ma Move ba! $MoveTo');</script>";
	
	$MoveFrom = GetTableInfoName($SESNTableSELECTED , 1); #Get TableNo
	
	#get orders_master, change its tableno
	$TranRID = GetTableInfoRID($MoveFrom, 5); #get TranRID
	$mSql = "UPDATE order_master SET tableno=$MoveTo WHERE TranRID = $TranRID";
	@mysql_query($mSql) OR DIE("$mSql<br>".mysql_error());

	#insert a record in order_detail for this movement
	$niners = 11111111;
	$mSql = "INSERT INTO order_details SET 
		TranRID = $TranRID,
		ProductRID = $niners,
		EntryType=1, 
		Remarks='Transfered from table#: $MoveFrom to $MoveTo'"; #EntryType=1 so as to print only remarks
	@mysql_query($mSql) OR DIE("$mSql<br>".mysql_error());
	
	#update the moved table
	$xOccupied 	= GetTableInfoRID($MoveFrom, 2);
	$xPaidOut 	= GetTableInfoRID($MoveFrom, 3);
	$xServedBy	= GetTableInfoRID($MoveFrom, 4);
	$xGuests 	= GetTableInfoRID($MoveFrom, 6);
	$xOrderMode	= GetTableInfoRID($MoveFrom, 7);
		
	$mSql = "UPDATE tables SET 
		Occupied=$xOccupied , 
		PaidOut=$xPaidOut, 
		TranRID=$TranRID, 
		ServedBy=$xServedBy, 
		guests=$xGuests,
		OrderMode = $xOrderMode
		WHERE tableno = $MoveTo";
	@mysql_query($mSql) OR DIE("$mSql<br>".mysql_error());
	
	#change the text file table name
	$MoveTableName = GetTableInfoRID($MoveTo, 1); #TableName
	$clsWalnet->CreateTableGrab($MoveTableName);
	
	#release this table
	$mSql = "UPDATE tables SET Occupied=0 , 
								PaidOut=0, 
								TranRID=0, 
								ServedBy=0, 
								guests=0,
								OrderMode=0
			WHERE tableno = $MoveFrom";
	@mysql_query($mSql) OR DIE("$mSql<br>".mysql_error());
	
	#just uniqalize the ProductRID in EntryType
	$mSql = "UPDATE order_details SET ProductRID=$niners+OrderDetailRID
		WHERE TranRID=$TranRID AND EntryType=1;";
	@mysql_query($mSql) OR DIE("$mSql<br>".mysql_error());
}
#just get again please
#@$SESNTableSELECTED = $clsWalnet->ReturnTableNumber();

?>