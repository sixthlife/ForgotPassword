<?php

 include ('../../includes/rconfig.php');
include ('../../includes/rfunctions.php');
include("../functions.php");
session_start();
dbConnect(DB_HOST, DB_USER,DB_PASS, DB_NAME);

if(isset($_GET['varName']) && isset($_GET['dbColumn']) && isset($_SESSION['tablename']) ){

$varName =  str_replace($_GET['dbColumn'].'_', '', $_GET['varName']);
$dbColumn =  $_GET['dbColumn'];
$tablename =  $_SESSION['tablename'];

echo  checkVar($varName, $dbColumn, $tablename);

/*echo  "<td><div class=\"red_up\"><img src=\"images/blue_up-red.png\" alt=\"\" /></div>
    <div class=\"red_middle\"><img src=\"images/cross.jpg\" alt=\"\" /><strong>Error:</strong><p>{$errortext}</p></div>
    <div class=\"red_dwon\"><img src=\"images/blue_down-red.png\" alt=\"\" /></div></td>"; */

}
?>