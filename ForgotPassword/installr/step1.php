<?php session_start();
include ('../includes/rconfig.php');
include ('../includes/rfunctions.php');
include ('functions.php');

dbConnect(DB_HOST, DB_USER,DB_PASS, DB_NAME);

if(isset($_GET['error'])&&($_GET['error']=='texists')){
	$error  = "A users table named {$_SESSION['tempusert']} already exists in your database.Select <b>\"No\"</b> to select users table from existing  tables in the next step.";
}

$prefix = get_tableprefix(DB_NAME);
$possible_usertables = possible_usertables(DB_NAME);


if(count($possible_usertables)!=0){
		$tabs = implode(', ', $possible_usertables);
		
	$info = "Existing user table suggestions: ";
	$info .= "<b>".$tabs."</b>";
	$info .= "<br /><br />Select <b>\"No\"</b> to select users table from existing  tables in your database. <br />Select <b>\"Yes\"</b> to create a new users table right here.";
}
else{
	$info = "We could not find a users table in your database.<br /> Select <b>\"Yes\"</b> to create a new users table <br /> Select <b>\"No\"</b> to select users table from existing database tables in the next step.";
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Forgot Password Installtion - Step 1</title>

<link href="css/style.css" rel="stylesheet" type="text/css" />


<script type="text/javascript">


function validatePassLength(element){
	var textval = element.value;

	 if(textval == "" ){
		document.getElementById('jserror').innerHTML = "<div class=\"red_left\"><img src=\"images/red_text_field_left.png\" alt=\"\" /></div><div class=\"red_middle\"><img src=\"images/cross.jpg\" alt=\"\" /><strong>Error:</strong><span class=\"error\"  >Password Length cannot be blank.</span></div><div class=\"red_right\"><img src=\"images/red_text_field_right.png\" alt=\"\" /></div>";
		return false;
	}


	else if(/[^0-9]/.test( textval )){
		document.getElementById('jserror').innerHTML =  "<div class=\"red_left\"><img src=\"images/red_text_field_left.png\" alt=\"\" /></div><div class=\"red_middle\"><img src=\"images/cross.jpg\" alt=\"\" /><strong>Error:</strong><span class=\"error\"  >Password Length can be numeric only.</span></div><div class=\"red_right\"><img src=\"images/red_text_field_right.png\" alt=\"\" /></div>";
		return false;
	}

else{
	document.getElementById('jserror').innerHTML = "";
	return true;
}
}

function validateTablePrefix(element){
	var textval = element.value;

		 if(/[^A-Za-z0-9_]/.test( textval )){
		document.getElementById('jserror').innerHTML = "<div class=\"red_left\"><img src=\"images/red_text_field_left.png\" alt=\"\" /></div><div class=\"red_middle\"><img src=\"images/cross.jpg\" alt=\"\" /><strong>Error:</strong><span class=\"error\"  >Table Prefix can be alphanumeric or underscore.</span></div><div class=\"red_right\"><img src=\"images/red_text_field_right.png\" alt=\"\" /></div>";
		return false;
	}
	else{
			document.getElementById('jserror').innerHTML = "";
				return true;
	}	
}

function validateEmail(element){
	var textval = element.value;	
	var emailPattern =/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
	var email1= document.getElementsByName('email1')[0].value;
	var email2 = document.getElementsByName('email2')[0].value;
//	alert(emailPattern.test(textval));

 	if(textval == "" ){
	document.getElementById('jserror').innerHTML = "<div class=\"red_left\"><img src=\"images/red_text_field_left.png\" alt=\"\" /></div><div class=\"red_middle\"><img src=\"images/cross.jpg\" alt=\"\" /><strong>Error:</strong><span class=\"error\"  >Test email cannot be blank.</span></div><div class=\"red_right\"><img src=\"images/red_text_field_right.png\" alt=\"\" /></div>";
	return false;
	}
	else if( emailPattern.test(textval)==false){
	document.getElementById('jserror').innerHTML = "<div class=\"red_left\"><img src=\"images/red_text_field_left.png\" alt=\"\" /></div><div class=\"red_middle\"><img src=\"images/cross.jpg\" alt=\"\" /><strong>Error:</strong><span class=\"error\"  >Test email is not valid.</span></div><div class=\"red_right\"><img src=\"images/red_text_field_right.png\" alt=\"\" /></div>";
	return false;	
	}
	else if(email1==email2){
		document.getElementById('jserror').innerHTML = "<div class=\"red_left\"><img src=\"images/red_text_field_left.png\" alt=\"\" /></div><div class=\"red_middle\"><img src=\"images/cross.jpg\" alt=\"\" /><strong>Error:</strong><span class=\"error\"  >Test emails cannot be same for two users.</span></div><div class=\"red_right\"><img src=\"images/red_text_field_right.png\" alt=\"\" /></div>";
		return false;		
	}
	else{
	document.getElementById('jserror').innerHTML = "";
	 return true;
	}
}

function validateUsername(element){
	var textval = element.value;
	 if(textval == "" ){
		document.getElementById('jserror').innerHTML = "<div class=\"red_left\"><img src=\"images/red_text_field_left.png\" alt=\"\" /></div><div class=\"red_middle\"><img src=\"images/cross.jpg\" alt=\"\" /><strong>Error:</strong><span class=\"error\"  >Test username cannot be blank.</span></div><div class=\"red_right\"><img src=\"images/red_text_field_right.png\" alt=\"\" /></div>";
		return false;
	}
	
	else if(textval.length<4){
		document.getElementById('jserror').innerHTML = "<div class=\"red_left\"><img src=\"images/red_text_field_left.png\" alt=\"\" /></div><div class=\"red_middle\"><img src=\"images/cross.jpg\" alt=\"\" /><strong>Error:</strong><span class=\"error\"  >Test username should be at least 4 characters long.</span></div><div class=\"red_right\"><img src=\"images/red_text_field_right.png\" alt=\"\" /></div>";
		return false;
	}
	
	else if(/[^a-zA-Z0-9]/.test( textval )){
		document.getElementById('jserror').innerHTML = "<div class=\"red_left\"><img src=\"images/red_text_field_left.png\" alt=\"\" /></div><div class=\"red_middle\"><img src=\"images/cross.jpg\" alt=\"\" /><strong>Error:</strong><span class=\"error\"  >Test username can be alphanumeric only.</span></div><div class=\"red_right\"><img src=\"images/red_text_field_right.png\" alt=\"\" /></div>";
		return false;
	}

else{
	document.getElementById('jserror').innerHTML = "";
	return true;
}
}

function validatePassword(element){
	var textval = element.value;

	 if(textval == "" ){
		document.getElementById('jserror').innerHTML = "<div class=\"red_left\"><img src=\"images/red_text_field_left.png\" alt=\"\" /></div><div class=\"red_middle\"><img src=\"images/cross.jpg\" alt=\"\" /><strong>Error:</strong><span class=\"error\"  >Test password cannot be blank.</span></div><div class=\"red_right\"><img src=\"images/red_text_field_right.png\" alt=\"\" /></div>";
		return false;
	}
	
	else if(textval.length<6){
		document.getElementById('jserror').innerHTML = "<div class=\"red_left\"><img src=\"images/red_text_field_left.png\" alt=\"\" /></div><div class=\"red_middle\"><img src=\"images/cross.jpg\" alt=\"\" /><strong>Error:</strong><span class=\"error\"  >Test password should be at least 4 characters long.</span></div><div class=\"red_right\"><img src=\"images/red_text_field_right.png\" alt=\"\" /></div>";
		return false;
	}
	else{
			document.getElementById('jserror').innerHTML = "";
				return true;
	}	
}
function toggleForm(){

var radios = document.getElementsByName('haveuserstable');
var value;
for (var i = 0; i < radios.length; i++) {
    if (radios[i].type === 'radio' && radios[i].checked) {

        value = radios[i].value;       
    }
}

if(value=='1'){
	document.getElementById('sampleusers').style.display = "none";
	document.getElementById('testuserhead').style.display = "none";	
}

else{
	document.getElementById('sampleusers').style.display = "inline";
	document.getElementById('testuserhead').style.display = "inline";
	document.getElementsByName('username1')[0].value = 'demouser1';	
	document.getElementsByName('username2')[0].value = 'demouser2';	
	document.getElementsByName('password1')[0].value = '123456';	
	document.getElementsByName('password2')[0].value = '123456';		
}
}

function Validate(){
	var tableprefix = document.getElementsByName('tableprefix')[0];
	var minpasslength = document.getElementsByName('passlength')[0];
	var username1 = document.getElementsByName('username1')[0];
	var email1 = document.getElementsByName('email1')[0];
	var password1 = document.getElementsByName('password1')[0];
	var username2 = document.getElementsByName('username2')[0];
	var email2 = document.getElementsByName('email2')[0];
	var password2 = document.getElementsByName('password2')[0];
//alert(tableprefix.value);
var radios = document.getElementsByName('haveuserstable');
var value;
for (var i = 0; i < radios.length; i++) {
    if (radios[i].type === 'radio' && radios[i].checked) {

        value = radios[i].value;       
    }
}

if(validateTablePrefix(tableprefix)==false||
validatePassLength(minpasslength)==false  ){
 	return false;
}
else if((value=='0') &&( validateUsername(username1)==false || validateEmail(email1)==false  || validatePassword(password1)==false|| validateUsername(username2)==false || validateEmail(email2)==false || validatePassword(password2)==false )){
	return false;
}

else{
	document.getElementById('jserror').innerHTML = "";
	usertableform.submit();
	return true;
	
}


}
</script>
<style>

#prefixrow, #sampleusers, #testuserhead{

}

</style>

</head>
<body onload="toggleForm();">
<div id="outer_container">
	<div id="inner_container">
		<div id="upper_container">
        	<div id="cross"><a href="#"><img src="images/close.png" alt="" /></a></div>
        </div>
        <div id="middle_container">
        <div id="table1">
       <form method="post" action="step2.php" name="usertableform"  onsubmit="return Validate(); " >
          <table width="200" border="0">
  <tr>
    <td height="36"><h1>Forgot Password Installtion - Step 1</h1></td>
  </tr>
  <?php if(isset($info)){ ?>
  <tr>
    <td height="35"><div class="blue_up"><img src="images/blue_up.png" alt="" /></div>
    <div class="blue_middle"><img src="images/exclamation.jpg" alt="" /><strong>Information:</strong><p><?php  echo $info;  ?></p></div>
    <div class="blue_dwon"><img src="images/blue_down.png" alt="" /></div>
    </td>
  </tr>
   <?php } ?> 
  
    <tr >
    <td id="jserror"><?php  if(isset($error)){ ?><div class="red_up"><img src="images/blue_up-red.png" alt="" /></div>
    <div class="red_middle"><img src="images/cross.jpg" alt="" /><strong>Error:</strong><p class="error"><?php echo $error;  ?></p></div>
    <div class="red_dwon"><img src="images/blue_down-red.png" alt="" /></div> <?php } ?></td>
  </tr>

  
   <tr>
    <td height="27"><h3>Users Table</h3></td>
  </tr>
  <tr>
    <td height="25">
      <div class="text1">I already have a user table in my database.</div>
    <div class="step2_radio"><ul><li>Yes</li><li><input type="radio" name="haveuserstable"   value="1" <?php if(isset( $_SESSION['haveuserstable'])&&( $_SESSION['haveuserstable']=='1')){echo "checked=\"true\"";}else if(!isset( $_SESSION['haveuserstable'])){echo "checked=\"true\"";} ?> onclick="toggleForm();"/></li><li>No</li><li><input type="radio" name="haveuserstable" <?php if(isset( $_SESSION['haveuserstable'])&&( $_SESSION['haveuserstable']=='0')){echo "checked=\"true\"";} ?> value="0" onclick="toggleForm();" /></li></ul></div>

    </td>
  </tr>
  <tr>
    <td height="27">Table Prefix</td>
  </tr>
  <tr id="prefixrow">
    <td><div class="gray_left"><img src="images/gray_text_field_left.png" alt="" /></div>
    <div class="gray_middle"><div class="step1_input"><input type="text" name="tableprefix"  value="<?php if(isset($_SESSION['tableprefix'])){echo $_SESSION['tableprefix']; } else{ echo  $prefix;} ?>" <?php if($prefix!="" ){echo "readonly=\"true\"";} ?> onchange="validateTablePrefix(this);"/></div></div>
    <div class="gray_right"><img src="images/gray_text_field_right.png" alt="" /></div></td>
  </tr>
  

  <tr>
    <td height="27"><span>Minimum Password Length </span></td>
  </tr>
  <tr id="prefixrow">
    <td><div class="gray_left"><img src="images/gray_text_field_left.png" alt="" /></div>
    <div class="gray_middle"><div class="step1_input"><input type="text" name="passlength" value="<?php if(isset($_SESSION['passlength'])){echo $_SESSION['passlength']; } else{ echo  '6';} ?>"  onchange="validatePassLength(this);"/></div></div>
    <div class="gray_right"><img src="images/gray_text_field_right.png" alt="" /></div></td>
  </tr>
      <tr>
    <td height="27"><span>Password Validation</span></td>
  </tr>
  <tr>
    <td><div class="select_left"><img src="images/select_gradient_left.png" alt="" /></div>
    <div class="select_middle">
    	<select class="select1" name="passvalidate" ><option value="none"  <?php if(isset( $_SESSION['passvalidate'])&&( $_SESSION['passvalidate']=='none')){echo "selected=\"true\"";} ?>>None </option>
<option value="alpha" <?php if(isset( $_SESSION['passvalidate'])&&( $_SESSION['passvalidate']=='alpha')){echo "selected=\"true\"";} ?>>Alphabets Only </option>
<option value="alphanumeric" <?php if(isset( $_SESSION['passvalidate'])&&( $_SESSION['passvalidate']=='alphanumeric')){echo "selected=\"true\"";} ?>>Alphanumeric Only</option>
<option value="alpha+special" <?php if(isset( $_SESSION['passvalidate'])&&( $_SESSION['passvalidate']=='alpha+special')){echo "selected=\"true\"";} ?>>Alpha and Special</option>
<option value="alphanumeric+special" <?php if(isset( $_SESSION['passvalidate'])&&( $_SESSION['passvalidate']=='alphanumeric+special')){echo "selected=\"true\"";} ?>>Alphanumeric and Special</option></select>
    </div>
    <div class="select_right"><img src="images/select_gradient_right.png" alt="" /></div></td>
  </tr>
    <tr>
    <td height="27"><span>Password Encryption</span></td>
  </tr>
  <tr>
    <td><div class="select_left"><img src="images/select_gradient_left.png" alt="" /></div>
    <div class="select_middle">
    	<select class="select1" name="passencypt" ><option value="none" <?php if(isset( $_SESSION['passencypt'])&&( $_SESSION['passencypt']=='none')){echo "selected=\"true\"";} ?>>None</option>
<option value="md5" <?php if(isset( $_SESSION['passencypt'])&&( $_SESSION['passencypt']=='md5')){echo "selected=\"true\"";} ?>>MD5</option>
<option value="sha1" <?php if(isset( $_SESSION['passencypt'])&&( $_SESSION['passencypt']=='sha1')){echo "selected=\"true\"";} ?>>SHA1</option></select>
    </div>
    <div class="select_right"><img src="images/select_gradient_right.png" alt="" /></div></td>
  </tr>
  </table>
</div>		<!--table1 ends -->

<div id="table2">
<div id="add_heading"><h2>Add Test Users</h2><img src="images/add_button.jpg" alt="" /></div>
<table width="200" border="0" id="sampleusers">
  <tr>
    <td width="170">Username </td>
    <td width="170">Email</td>
    <td width="170">Password</td>
    <td width="19">&nbsp;</td>
  </tr>


 <tr>
    <td><div class="table2_gray_left"><img src="images/gray_text_field_left.png" alt="" /></div>
    <div class="table2_gray_middle"><div class="table2_step1_input"><input type="text" name="username1" value="<?php if(isset($_SESSION['username1'])){echo $_SESSION['username1']; } else{ echo  'demouser1';} ?>" onchange="validateUsername(this);"  /></div></div>
    <div class="table2_gray_right"><img src="images/gray_text_field_right.png" alt="" /></div></td>
    <td><div class="table2_gray_left"><img src="images/gray_text_field_left.png" alt="" /></div>
    <div class="table2_gray_middle"><div class="table2_step1_input"><input type="text" value="<?php if(isset($_SESSION['email1'])){echo $_SESSION['email1']; } ?>" name="email1" onchange="validateEmail(this);"  /></div></div>
    <div class="table2_gray_right"><img src="images/gray_text_field_right.png" alt="" /></div></td>
    <td><div class="table2_gray_left"><img src="images/gray_text_field_left.png" alt="" /></div>
    <div class="table2_gray_middle"><div class="table2_step1_input"><input type="text" name="password1" value="<?php if(isset($_SESSION['password1'])){echo $_SESSION['password1']; } else{ echo  '123456';} ?>" onchange="validatePassword(this);" /></div></div>
    <div class="table2_gray_right"><img src="images/gray_text_field_right.png" alt="" /></div></td>
    <td><a href="#"><img src="images/minus_button.gif" alt="" /></a></td>
  </tr>


 <tr>
    <td>Username </td>
    <td>Email</td>
    <td>Password</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div class="table2_gray_left"><img src="images/gray_text_field_left.png" alt="" /></div>
    <div class="table2_gray_middle"><div class="table2_step1_input"><input type="text" name="username2" value="<?php if(isset($_SESSION['username2'])){echo $_SESSION['username2']; } else{ echo  'demouser2';} ?>" onchange="validateUsername(this);" /></div></div>
    <div class="table2_gray_right"><img src="images/gray_text_field_right.png" alt="" /></div></td>
    <td><div class="table2_gray_left"><img src="images/gray_text_field_left.png" alt="" /></div>
    <div class="table2_gray_middle"><div class="table2_step1_input"><input type="text" name="email2" value="<?php if(isset($_SESSION['email2'])){echo $_SESSION['email2']; } ?>" onchange="validateEmail(this);"/></div></div>
    <div class="table2_gray_right"><img src="images/gray_text_field_right.png" alt="" /></div></td>
    <td><div class="table2_gray_left"><img src="images/gray_text_field_left.png" alt="" /></div>
    <div class="table2_gray_middle"><div class="table2_step1_input"><input type="text" name="password2"  value="<?php if(isset($_SESSION['password2'])){echo $_SESSION['password2']; } else{ echo  '123456';} ?>" onchange="validatePassword(this);"  /></div></div>
    <div class="table2_gray_right"><img src="images/gray_text_field_right.png" alt="" /></div></td>
    <td><a href="#"><img src="images/minus_button.gif" alt="" /></a></td>
  </tr>
</table>

</div>

<div id="navigator">
<div class="left_botton"><a href="step-1.html"><a href="index.php"><img src="images/prev-red.jpg" alt="" /></a></div>
<div class="right_botton"><input type="hidden" name="selecttable" value="Proceed"  /><input type="image" name="selecttable" value="Proceed" src="images/next-red.gif" /></div>
</form>
</div>

        </div>
        <div id="bottom_container"><img src="images/body_round_corner_down.png" alt="" /></div>
	</div>
</div>

</body>

</html>
