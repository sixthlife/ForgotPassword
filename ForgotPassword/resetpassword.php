<?php
include ('includes/rconfig.php');
include ('includes/rfunctions.php');
include ('includes/class.recoverpas.php');

dbConnect(DB_HOST, DB_USER,DB_PASS, DB_NAME);


 $forgotpass = new RecoverPas(); 
 $forgotpass->resetPassword();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Change Password</title>

<link rel="stylesheet" title="default" type="text/css" href="admin.css"/>
</head>

<body>
<div id="demo"></div>
<!--end of demo-->
<div>
<div id="information" align="center">
<div id="resetbutton">
</div>
<p class="error"><?php $forgotpass->printError(); ?></p>
<p class="success"><?php $forgotpass->printSuccess(); ?></p> 

</div>
 <div id="info" >
<p>Enter new password two times. Minimum lenth: 6 digits</p>
</div>

 
<form action="" method="post">

<fieldset >
<legend>Change Password </legend>
<div id="x"></div>

<input type="password"  class="mycontent" name="password" value="" />

<td><input type="password" class="mycontent"  name="rpassword" value="" /></td>


<p id="mybutton"><input type="submit"  name="submit" value="SUBMIT" />


</p>
</fieldset>
</form>
</div>




</body>
</html>