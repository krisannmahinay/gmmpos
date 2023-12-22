<?php
include_once('wfslib/WalnetFunctionsPOS.php');
wfsDBfnb();

$mSql = "SELECT * FROM category;";
$mQry = mysql_query($mSql) or die (mysql_error());
while ($tblCat=mysql_fetch_object($mQry))
{
	$mCatRID=$tblCat->CatRID;
	$mCat=$tblCat->Category;
	
	for ($i = 0; $i<5 ; $i++)
	{
		mysql_query("INSERT INTO items SET CatRID=$mCatRID, Description='$mCat $i';") OR DIE(mysql_error());
	}
}	
?>