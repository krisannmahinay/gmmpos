<?php
@session_start();

// @$mx = $_REQUEST['jt'] * 1;

// if ($mx==0)
	$_SESSION['sesnPRNJournalType'] = "O R D E R    S L I P";
// else
// 	$_SESSION['sesnPRNJournalType'] = "OFFICIAL RECEIPT";

echo "<script>location='bill.onj.slip.php';</script>";
?>		