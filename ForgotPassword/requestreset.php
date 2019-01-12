<?php
include ('includes/rconfig.php');
include ('includes/rfunctions.php');
include ('includes/class.recoverpas.php');

dbConnect(DB_HOST, DB_USER,DB_PASS, DB_NAME);

 $forgotpass = new RecoverPas(); 
 $forgotpass->initiateReset();
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Email </title>
<link rel="stylesheet" title="default" type="text/css" href="admin.css"/>

</head>

<body>
<div id="demo">

</div><!--end of demo-->

<div id="information" align="center">
<div id="resetbutton">


</div>
<p class="error"><?php $forgotpass->printError(); ?></p>
<p class="success"><?php $forgotpass->printSuccess(); ?></p>

</div>

<div id="info">

</div>

<form action="" method="post" >
<fieldset id="req">
<legend>Username/ E-Mail </legend>
<div id="x"></div>
<input type="text"  name="emailuname" value="" maxlength="150"  class="textfield"/>

<p id="button"><input type="submit"  name="submit" value="SUBMIT" /></p>

</fieldset>
</form>
</div>
</body>
</html>