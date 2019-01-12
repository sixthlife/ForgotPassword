<?php session_start();
include ('../includes/rconfig.php');
include ('../includes/rfunctions.php');
include ('functions.php');

dbConnect(DB_HOST, DB_USER,DB_PASS, DB_NAME);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Forgot Password Installtion - Finish</title>

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
    <td height="36"><h1>Forgot Password Installtion - Finish</h1></td>
  </tr>
  
 
<?php 


if(isset($_POST['csubmit'])){
	$tablename = $_POST['tablename'];
	$idcolm = $_POST['idcolm'];
	$unamecolm = $_POST['unamecolm'];
	$pwordcolm = $_POST['pwordcolm'];
	$emailcolm = $_POST['emailcolm'];
	$fnamecolm = $_POST['fnamecolm'];
	$lnamecolm = $_POST['lnamecolm'];
	$emailfrom = $_POST['emailfrom'];
	$companyname = $_POST['companyname'];
	$cshortname = $_POST['cshortname'];
	$minpasslen = $_POST['passlength'];
	$passecypt = $_POST['passencypt'];
	$passvalidate = $_POST['passvalidate'];
	$tableprefix = $_POST['tableprefix'];

	
	$dbhost = 		$_SESSION['dbhost'];
	$dbname = 		$_SESSION['dbname'];
	$dbuser = 		$_SESSION['dbuser'];	
	$dbpass =		 $_SESSION['dbpass'];
	$configstr = "";
$configstr = '<?php
  // Stop page from being loaded directly.
  
  
if (preg_match("/rconfig.php/i", $_SERVER[\'PHP_SELF\'])){
echo "Please do not load this page directly. Thanks!";
exit;
}';

$configstr .='
define(\'DB_HOST\', \'';

$configstr .= $dbhost.'\'); 		/*Enter the database hostname. */
define(\'DB_NAME\', \'';

$configstr .= $dbname.'\');				/*Enter the database name. */
define(\'DB_USER\',\'';

$configstr .= $dbuser.'\');			/*Enter the database user name. */
define(\'DB_PASS\',\'';
$configstr .= $dbpass.'\');		/*Enter the database user password. */';


$configstr .= '
define(\'DB_TABLEPREFIX\',\'';

$configstr .= $tableprefix.'\'); 		/*Enter the database table prefix. */';

$configstr .= '

define(\'DB_USERTABLE\',\'';

$configstr .= $tablename.'\'); 		/*Enter the database user table name. */';

$configstr .= '

define(\'DB_USERIDCOL\', \'';

$configstr .= $idcolm.'\'); 	/*Enter the id(primary key) column name in your users table in database. */
define(\'DB_USERNAMECOL\', \'';

$configstr .= $unamecolm.'\'); 		/*Enter the username column name in  your users table in database. */
define(\'DB_USERPASSCOL\', \'';

$configstr .= $pwordcolm.'\'); 			/*Enter the password column name in  your users table in database. */';

$configstr .='
define(\'DB_USEREMAILCOL\', \'';

$configstr .= $emailcolm.'\'); 		/*Enter the email column name in your users table in database. It can be same as username column if your username is an email. */';

$configstr .= '
define(\'DB_FNAMECOL\', \'';

$configstr .= $fnamecolm.'\'); 		/*Enter the First Name column name in  your users table in database(optional). */
define(\'DB_NAMECOL\', \'';

$configstr .= $lnamecolm.'\'); 		/*Enter the  Name column name in  your users table in database(optional). */';


$configstr .= '

define(\'FROMEMAIL\', \'';

$configstr .= $emailfrom.'\'); 		/*Enter the Email from which a password reset email will be sent. */';

$configstr .= '
define(\'COMPANYNAME\', \'';

$configstr .= $companyname.'\'); 		/*Enter the Company Name. */
define(\'COMPANY_SHORTNAME\', \'';

$configstr .= $cshortname.'\');			/*Enter the Company Short Name. */

';
$configstr .= 'define(\'MINPASSLENGTH\', \'';

$configstr .= $minpasslen.'\'); 		/*Enter the Password Encryption md5, sha1, or none. */';
$configstr .= '
define(\'PASSVALIDATION\', \'';

$configstr .= $passvalidate.'\'); 		/*Enter the Password Encryption md5, sha1, or none. */
define(\'PASSENCRYPT\', \'';

$configstr .= $passecypt.'\'); 		/*Enter the Password Validation alpha, alphanumeric or alphanumeric + special. */
?>';

$file = "../includes/rconfig.php"; 
 $handle = fopen($file, 'w');
 
$status = fwrite($handle, $configstr);
chmod($file, 0644);
fclose($handle);

	$haveuserstable = $_SESSION['haveuserstable'];

 if(!$status){
$error = "There was an error writing the configuration file. Please make sure the rconfig.php file has 0777 permission and is writable.";

 }
 else{
 
 	$_SESSION = "";
unset($_SESSION);
 }

}
	


else{
	header('location: index.php');	
}

?>
  <tr> <?php if(isset($error)){ ?>
    <td><div class="red_left"><img src="images/blue_up-red.png" alt="" /></div>
    <div class="red_middle"><img src="images/cross.jpg" alt="" /><strong>Error:</strong><p><?php echo $error; ?></p></div>
    <div class="red_right"><img src="images/blue_down-red.png" alt="" /></div></td> <?php  } ?>
  </tr>
 <tr>
 
  <tr><?php if($status){ ?>
    <td><div class="green_left"><img src="images/green_text_field_left.png" alt="" /></div>
    <div class="green_middle"><img src="images/tick_mark.gif" alt="" /><strong>Yeah! Success!</strong></div>
    <div class="green_right"><img src="images/green_text_field_right.png" alt="" /></div>  <?php  } ?>
    </td>
  </tr>
    <td height="40"><span class="span1">Please copy and paste this code in your login page to create a
"<a href="#">forgot password</a>" hyperlink.<br />
<br />
    </span></td>
  </tr>
  <tr>
    <td><div class="sky_left"><img src="images/sky_text_field_left.png" alt="" /></div>
    <div class="sky_middle"><span>&lt;a href=&quot;ForgotPassword/resetlink.php&quot;&gt;forgot password&lt;/a&gt;</span></div>
    <div class="sky_right"><img src="images/sky_text_field_right.png" alt="" /></div></td>
  </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</div>
<div id="navigator">
<div class="left_botton"><?php if($haveuserstable==0 && !$status){

 ?>
<a href="step1.php?flag=fromfour"><img src="images/prev-red.jpg" alt="" /></a> <?php } 

 else if($haveuserstable==1 && !$status){

 ?><a href="step3.php"><img src="images/prev-red.jpg" alt="" /></a> <?php } ?></div>
<div class="right_botton"><img src="images/next_grey.gif" alt="" /></div>
</div>

        </div>
        <div id="bottom_container"><img src="images/body_round_corner_down.png" alt="" /></div>
	</div>
</div>

</body>

</html>
