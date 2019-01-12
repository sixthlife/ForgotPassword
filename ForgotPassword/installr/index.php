<?php 

$installed = false;
session_start();
if(isset($_POST['submitcon'])){
	
	if(empty($_POST['hostname'])||empty($_POST['dbname'])||empty($_POST['dbuser'])){
		$error = "Incomplete Information.";
	}
	else{
		$dbhost = mysql_escape_string($_POST['hostname']);
		$_SESSION['dbhost'] = $dbhost;
		$dbname = mysql_escape_string($_POST['dbname']);
		$_SESSION['dbname'] = $dbname;
		$dbuser = mysql_escape_string($_POST['dbuser']);
		$_SESSION['dbuser'] = $dbuser;
		$dbpass = mysql_escape_string($_POST['dbpass']);
		$_SESSION['dbpass'] = $dbpass;
		$con = @mysql_connect($dbhost, $dbuser,$dbpass);
		if(!$con){$error = "Connection failed: Incorrect database host, user or password.";}
		else if($con){
		$success = @mysql_select_db($dbname);
		if(!$success){$error = "Connection failed: Incorrect Database Name.";
		}
		else if($success){ 
		
		$configstr = '<?php
  // Stop page from being loaded directly.
  
  
if (preg_match("/rconfig.php/i", $_SERVER[\'PHP_SELF\'])){
echo "Please do not load this page directly. Thanks!";
exit;
}

define(\'DB_HOST\', \'';

$configstr .= $dbhost.'\'); 		/*Enter the database hostname. */
define(\'DB_NAME\', \'';

$configstr .= $dbname.'\');				/*Enter the database name. */
define(\'DB_USER\',\'';

$configstr .= $dbuser.'\');			/*Enter the database user name. */
define(\'DB_PASS\',\'';
$configstr .= $dbpass.'\');		/*Enter the database user password. */


?>';
	
			
			
$file = "../includes/rconfig.php"; 
chmod($file, 0755);
 $handle = fopen($file, 'w');
 
$status = fwrite($handle, $configstr);
 fclose($handle);
 if($status){

 	header("Location: step1.php");
 }
 else{
 	$error = "There was an error writing the configuration file. Please make sure the config.php file has 0777 permission and is writable.";
 }
		}
		}
	}
	
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Forgot Password Installtion - Start</title>

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
        <form method="post" action="">
          <table width="200" border="0">
  <tr>
    <td height="36"><h1>Forgot Password Installtion - Start</h1></td>
  </tr>
  <tr>
    <td height="35"><div class="blue_up"><img src="images/blue_up.png" alt="" /></div>
    <div class="blue_middle"><img src="images/exclamation.jpg" alt="" /><strong>Information:</strong><p>Please enter information about your database here. </p></div>
    <div class="blue_dwon"><img src="images/blue_down.png" alt="" /></div>
    </td>
    
  </tr>
  <?php if(isset($error)){ ?>
   <tr>
    <td><div class="red_up"><img src="images/blue_up-red.png" alt="" /></div>
    <div class="red_middle"><img src="images/cross.jpg" alt="" /><strong>Error:</strong><p><?php echo $error; ?></p></div>
    <div class="red_dwon"><img src="images/blue_down-red.png" alt="" /></div></td>
  </tr><?php } ?>



  <tr>
    <td height="27">Database HostName</td>
  </tr>


  <tr>
    <td><div class="gray_left"><img src="images/gray_text_field_left.png" alt="" /></div>
    <div class="gray_middle"><div class="step1_input"><input type="text" name="hostname" value="<?php if(isset($_SESSION['dbhost'])){echo $_SESSION['dbhost'];} ?>" /></div></div>
    <div class="gray_right"><img src="images/gray_text_field_right.png" alt="" /></div></td>
  </tr>

 <tr>
    <td height="27">Database Name</td>
  </tr>
  <tr>
    <td><div class="gray_left"><img src="images/gray_text_field_left.png" alt="" /></div>
    <div class="gray_middle"><div class="step1_input"><input type="text" name="dbname" value="<?php if(isset($_SESSION['dbname'])){echo $_SESSION['dbname'];} ?>"/></div></div>
    <div class="gray_right"><img src="images/gray_text_field_right.png" alt="" /></div></td>
  </tr>
  <tr>
    <td height="27">Database User</td>
  </tr>
  <tr>
    <td><div class="gray_left"><img src="images/gray_text_field_left.png" alt="" /></div>
    <div class="gray_middle"><div class="step1_input"><input type="text" name="dbuser" value="<?php if(isset($_SESSION['dbuser'])){echo $_SESSION['dbuser'];} ?>" /></div></div>
    <div class="gray_right"><img src="images/gray_text_field_right.png" alt="" /></div></td>
  </tr>


  <tr>
    <td height="27">Database Password</td>
  </tr>
  <tr>
    <td><div class="gray_left"><img src="images/gray_text_field_left.png" alt="" /></div>
    <div class="gray_middle"><div class="step1_input"><input type="text" name="dbpass" value="<?php if(isset($_SESSION['dbpass'])){echo $_SESSION['dbpass'];} ?>" /></div></div>
    <div class="gray_right"><img src="images/gray_text_field_right.png" alt="" /></div></td>
  </tr>


</table>
</div>
<div id="navigator">
<div class="left_botton"><img src="images/prev_grey.gif" alt="" /></div>
<div class="right_botton"><input type="image" name="submitcon" value="Proceed" src="images/next-red.gif"/>
<input type="hidden" name="submitcon" value="Proceed" />
</div>
</div>

        </div>
        <div id="bottom_container"><img src="images/body_round_corner_down.png" alt="" /></div>
	</div>
</div>

</body>

</html>


