<?php
			@mysql_query("DELETE FROM exchange_current;") OR DIE(mysql_error());
			@mysql_query("DELETE FROM exchange_details_current;") OR DIE(mysql_error());
	
			$TranAmount 	= GetOLDTransInfo($TranRID, 6);
			$TranTtlQty		= GetOLDTransInfo($TranRID, 11);
			$TranTtlTendered = GetOLDTransInfo($TranRID, 12);
	
			$mSql = "INSERT INTO exchange_current SET 
				TranRID = $TranRID,
				ExchangeDate = NOW(),
				ExchangeEnteredBy = $SYSUserLogged
				;";
			@mysql_query($mSql) OR DIE("$mSql<br>".mysql_error());
			
			$ExchangeRID = wfs_GetCurrentExchangeRID();

			$mSql = "SELECT * FROM order_details WHERE TranRID=$TranRID;";
			$mQry = mysql_query($mSql) OR DIE("$mSql<br>".mysql_error());
			while ($tblItems=mysql_fetch_object($mQry))
			{
				$mItemRID = $tblItems->ProductRID;
				$mCost = $tblItems->UnitCost;
				$mSRP  = $tblItems->SoldPrice;
				$mQty  = $tblItems->SoldQty;
				
				$mSql = "INSERT INTO exchange_details_current SET
					ExchangeRID = $ExchangeRID,
					TranRID = $TranRID,				
					ProductRID = $mItemRID,
					UnitCost = $mCost,
					SoldPrice = $mSRP,
					SoldQty = $mQty
					;";
				@mysql_query($mSql) OR DIE(mysql_error());
			}
?>