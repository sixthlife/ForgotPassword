<?php session_start();

include ('../includes/rconfig.php');
include ('../includes/rfunctions.php');
include ('functions.php');

dbConnect(DB_HOST, DB_USER,DB_PASS, DB_NAME);

if(isset($_POST['haveuserstable'])){
 $_SESSION['haveuserstable'] = $_POST['haveuserstable'];
 if(isset($_POST['tableprefix'])){
 $_SESSION['tableprefix'] = $_POST['tableprefix'];	
 }
 if(isset($_POST['passencypt'])){
 $_SESSION['passencypt'] = $_POST['passencypt'];	
 }
 if(isset($_POST['passlength'])){
 $_SESSION['passlength'] = $_POST['passlength'];	
 }
 if(isset($_POST['passvalidate'])){
 $_SESSION['passvalidate'] = $_POST['passvalidate'];	
 }
 if(isset($_POST['username1'])){
 $_SESSION['username1'] = $_POST['username1'];	
 }
 if(isset($_POST['email1'])){
 $_SESSION['email1'] = $_POST['email1'];	
 }
 if(isset($_POST['password1'])){
 $_SESSION['password1'] = $_POST['password1'];	
 }
 if(isset($_POST['username2'])){
 $_SESSION['username2'] = $_POST['username2'];	
 }
 if(isset($_POST['email2'])){
 $_SESSION['email2'] = $_POST['email2'];	
 }
 if(isset($_POST['password2'])){
 $_SESSION['password2'] = $_POST['password2'];	
 }
}

if(isset($_SESSION['haveuserstable'])&& $_SESSION['haveuserstable']==1){

$table_arr = array();
$showtablequery = "
	SHOW TABLES
	FROM
	
	".DB_NAME;
 
$showtablequery_result	= mysql_query($showtablequery);
while($showtablerow = mysql_fetch_array($showtablequery_result))
{	//echo $showtablerow[0]."<br />";
	$result_cols = mysql_query("SHOW COLUMNS FROM ".$showtablerow[0]);
	$possibility = 0;
 if(has_usertblstr($showtablerow[0])){
          	 $possibility= $possibility+2;
          }
	$rowct = mysql_num_rows($result_cols);
	$counter = 0;
	while ($row = mysql_fetch_assoc($result_cols)) {
          
		 // echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$row['Field']."<br />";
          if(strpos(strtolower($row['Field']), 'username')!== FALSE){
          	 $possibility++;
          }
          if(strtolower($row['Field'])=='username'){
          	          	 $possibility=  $possibility+2;
          }
          if(strpos(strtolower($row['Field']), 'password')!== FALSE){
          	 $possibility++;
          }
          if(strtolower($row['Field'])=='password'){
 		  $possibility=  $possibility+2;	
          }
          if(strpos(strtolower($row['Field']), 'pass')!== FALSE ||strtolower($row['Field'])=='pass'){
          	 $possibility++;
          }

          $counter++;
         // echo $possibility."<br />";
   		if($counter>=$rowct){
			  $table_arr[$showtablerow[0]] =  $possibility; 
 
		}
		
        }
          	 
	
}
arsort($table_arr);

} 
if(isset($_SESSION['haveuserstable'])){
$prefix = $_SESSION['tableprefix'];
 $query_activeresets = "CREATE TABLE IF NOT EXISTS `{$prefix}activeresets` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `resetcode` varchar(225) COLLATE utf8_unicode_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `resetcode` (`resetcode`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";

 mysql_query($query_activeresets);

 if( $_SESSION['haveuserstable']==0){ 

if(table_exists($prefix.'users')){
	$_SESSION['tempusert'] = $prefix.'users';
	header('location:step1.php?error=texists');
	exit;
}
	$query_users = "CREATE TABLE IF NOT EXISTS `{$prefix}users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";
 $successok = mysql_query($query_users);

 $query_chkdata = "select * from `{$prefix}users`";
 $result_chk = mysql_query($query_chkdata);	
 if(mysql_num_rows($result_chk)>0){
 	$query_del = "delete from `{$prefix}users`";
 	$result_del = mysql_query($query_del);
 }
 
 $_SESSION['tablename'] = "{$prefix}users";
$_SESSION['idcol'] = "id";
$_SESSION['unamecol'] = "username";
$_SESSION['passwordcol'] = "password";
$_SESSION['emailcol'] = "email";

 $username1 = $_SESSION['username1'];
$username1 = mysql_escape_string($username1);
 $email1 = $_SESSION['email1'];
 $email1 = mysql_escape_string($email1);
 $password1 = $_SESSION['password1'];
 $username2 = $_SESSION['username2'];
$username2 = mysql_escape_string($username2);
 $email2 = $_SESSION['email2'];
 $email2 = mysql_escape_string($email2);
 $password2 = $_SESSION['password2'];
 
$encryption = $_SESSION['passencypt'];
if($encryption=='md5'){
 $password1 = md5($password1);
 $password2 = md5($password1);
}
if($encryption=='sha1'){
 $password1 = sha1($password1);
 $password2 = sha1($password1);
}
if($email1!="" && $email2!="" && $username1!="" && $username2!=""&& $password1!="" && $password2!=""){

$query_chk1 = "select id from {$prefix}users where username = '{$username1}' limit 1";
$result_chk1 = mysql_query($query_chk1);
if(mysql_num_rows($result_chk1)>0){
	$query_del1 = "delete from {$prefix}users where username = '{$username1}' limit 1";
	$result_del1 = mysql_query($query_del1);
}
$query_chk2 = "select id from {$prefix}users where username = '{$username2}' limit 1";
$result_chk2 = mysql_query($query_chk2);

if(mysql_num_rows($result_chk2)>0){
	$query_del2 = "delete from {$prefix}users where username = '{$username2}' limit 1";
	$result_del2 = mysql_query($query_del2);
}

 $query_testdata1= "insert into {$prefix}users (username, email, password) 
 values('{$username1}','{$email1}','{$password1}')";
  $result_testdata1 = mysql_query($query_testdata1) or die(mysql_error());
 
 $query_testdata2= "insert into {$prefix}users (username, email, password) 
 values('{$username2}','{$email2}','{$password2}')";
 $result_testdata2 = mysql_query($query_testdata2) or die(mysql_error());

if(!isset($_GET['flag'])&& ($_GET['flag']!='fromfour'))
{
header('location: step4.php');
}



 }
 }

 } ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Forgot Password Installtion - Step 2</title>

<link href="css/style.css" rel="stylesheet" type="text/css" />
</head>


<body>
<div id="outer_container">
	<div id="inner_container">
		<div id="upper_container">
        	<div id="cross"><a href="#"><img src="images/close.png" alt="" /></a></div>
        </div>
        <div id="middle_container">
        <div id="table1">
          <table width="200" border="0">
  <tr>
    <td height="36"><h1>Forgot Password Installtion - Step 2</h1></td>
  </tr>
  <tr>
    <td height="35"><div class="blue_left"><img src="images/blue_up.png" alt="" /></div>
    <div class="blue_middle"><img src="images/exclamation.jpg" alt="" /><strong>Information:</strong><p>What you can do here?</p>

<ul style="color:#3760B5; margin-left:50px;  line-height:18px; padding-top:10px;">
<li style="list-style-image: url(images/next1.jpg); padding-bottom:2px; margin-bottom:3px;">
<b>Select your users table</b> and <b>Click on next</b> to proceed. Suggestions for <b>users table highlighted</b> in red.
 </li>
 <li style="list-style-image: url(images/prev1.jpg); margin-bottom:3px;" >
<b>Click on previous</b> to <b>create a users table</b> if you think you do <b>not have a user's table.</b> 
</li>
<li style="list-style-image: url(images/help1.jpg);">
Contact us to <b>help with installtion.</b></li></ul></div>
    <div class="blue_right"><img src="images/blue_down.png" alt="" /></div>
    </td>
  </tr>
 <?php if(isset($error)){ ?>
  <tr>
    <td><div class="red_left"><img src="images/blue_up-red.png" alt="" /></div>
    <div class="red_middle"><img src="images/cross.jpg" alt="" /><strong>Error:</strong><p><?php  echo $error;  ?></p></div>
    <div class="red_right"><img src="images/blue_down-red.png" alt="" /></div></td>
  </tr> <?php } ?>
</table>
<?php if(isset($_SESSION['haveuserstable'])&& $_SESSION['haveuserstable']==1){ ?>
<form method="post" action="step3.php">


<table class="radio_list" cellspacing="3">

<?php

$tempct = 1;
foreach($table_arr as $table=>$possibility){
	if($possibility>0){
		$tname =  "<span style=\"color: red;\">$table</span><br />";
		
	}
	else{
$tname = "<span style=\"\">$table</span><br />";
	}

?>

  <tr>
    <td width="187"><?php echo $tname; ?></td>
	<td width="155"><input type="radio" name="tabname" value="<?php echo $table; ?>" <?php 
if($tempct==1){
	echo "checked=\"true\""; 

}

?> /></td>
  </tr>
<?php $tempct++; }


?>
</table>
</div>		<!--table1 ends -->





<?php } ?>

<div id="navigator">
<div class="left_botton"><a href="step1.php"><img src="images/prev-red.jpg" alt="" /></a></div>
<div class="right_botton"><input type="hidden"  name="selecttable" value="Proceed"/><input type="image" src="images/next-red.gif" name="selecttable" value="Proceed"/></form></div>
</div>

        </div>
        <div id="bottom_container"><img src="images/body_round_corner_down.png" alt="" /></div>
	</div>
</div>

</body>

</html>

