<script language="JavaScript">
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
<form action='digol.php'>
<?php

@$cmdButton = $_REQUEST['cmdButton'];
if (isset($cmdButton))
{
	for ($i=0; $i<count($cmdButton); $i++)
	{
		#echo "<script>alert('$cmdButton');</script>";
		echo $cmdButton[$i];
	}
} 

for ($i=0; $i<=5; $i++)
{
	echo "<p><input type='button' name='cmdButton[]' value='save$i'
		onclick=\"return decision('Are you sure? button $i')\"></p>";
}
#ssssssonclick=\"return decision('Are you sure to delete the entry?')\"
?>
</form>