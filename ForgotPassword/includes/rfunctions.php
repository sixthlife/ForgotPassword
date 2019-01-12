<?php

  // Stop page from being loaded directly. 
if (preg_match("/rfunctions.php/i", $_SERVER['PHP_SELF'])){
echo "Please do not load this page directly. Thanks!";
exit;
}

function dbConnect($hostname, $username,$password, $database)
{
	$con = mysql_connect($hostname, $username,$password) or die(mysql_error());
	mysql_select_db($database) or die(mysql_error());	
	return $con;
}






?>