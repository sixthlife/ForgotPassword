<?php session_start();
include ('../includes/rconfig.php');
include ('../includes/rfunctions.php');
include ('functions.php');

dbConnect(DB_HOST, DB_USER,DB_PASS, DB_NAME);

if(isset($_POST['submitselcol'])){
	$idcol = "";
	$unamecol = ""; 
	$emailcol = "";
	$passwordcol = "";
	$fnamecol="";
	$lnamecol="";
	
$tablename = mysql_escape_string($_POST['tablename']);
$_SESSION['tablename']= $tablename;
$columns = get_columns($tablename);

//print_r($_SESSION);

foreach($columns as $column){
	foreach($_POST as $keyvar => $colvar){
	//	echo str_replace($keyvar.'_', '',$colvar )."<br />";;
	//	echo $colvar."<br />";
		if($column==$keyvar){
			if((str_replace($keyvar.'_', '',$colvar )=='userid')!==false){
			$idcol = $keyvar;	
			}
			else if((str_replace($keyvar.'_', '',$colvar )=='username')!==false){
			$unamecol = $keyvar;				
			}
			else if((str_replace($keyvar.'_', '',$colvar )=='password')!==false)
			{
				$passwordcol = $keyvar;	
			}
			else if((str_replace($keyvar.'_', '',$colvar )=='email')!==false)
			{
				$emailcol = $keyvar;	
			}
			else if((str_replace($keyvar.'_', '',$colvar )=='fname')!==false){
				$fnamecol = $keyvar;					
			}
			else if((str_replace($keyvar.'_', '',$colvar )=='lname')!==false){
				$lnamecol = $keyvar;					
			}
		}
	}


}

//echo 	$_SESSION['idcol'];

	if(isset($idcol)){
	$_SESSION['idcol'] = $idcol;}
	if(isset($unamecol)){
	$_SESSION['unamecol'] = $unamecol;
	}
	if(isset($passwordcol)){
	$_SESSION['passwordcol'] = $passwordcol;}
	if(isset($emailcol)){
	$_SESSION['emailcol'] = $emailcol;}
	else{
	$firsttenarr = 	getfirstten($tablename, $_SESSION['unamecol']);
//	print_r($firsttenarr);
	$emailarray = array();
		foreach($firsttenarr as $firsttenitem){
		if(isValidEmail($firsttenitem)) {
		$emailarray []= $firsttenitem;	
		}	
		}
		$emailarray = array_unique($emailarray);
	//	print_r($emailarray);

		if(count($firsttenarr)==count($emailarray)){
			$_SESSION['emailcol'] = $unamecol;	
		}
	}
	if(isset($fname)){
	$_SESSION['fname'] = $fname;}

	if(isset($lnamecol)){

	$_SESSION['lname'] = $lnamecol;}

} 

if(!isset($_SESSION['haveuserstable'])){
		header("location:step1.php?error=mistablename");	
}

if(!isset($_SESSION['tablename'])|| ($_SESSION['tablename']=="")){
		header("location:step1.php?error=mistablename");
}



 if(!isset($_SESSION['idcol'])|| !isset($_SESSION['unamecol'])||!isset($_SESSION['passwordcol'])||(!isset($_SESSION['emailcol']))||$_SESSION['idcol']==""|| $_SESSION['unamecol']==""||$_SESSION['passwordcol']==""||$_SESSION['emailcol']==""){
	
if($_SESSION['haveuserstable']==0){
	header("location:step1.php?error=miscols"); 
}
	else{
		header("location:step3.php?error=miscols"); }	
	}


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Forgot Password Installation - Step 4</title>

<link href="css/style.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">


function validateFromEmail(){
	var emailPattern =/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
	var emailfrom= document.getElementsByName('emailfrom')[0].value;

	
		if(emailfrom == "" ){
	document.getElementById('jserror').innerHTML = "<div class=\"red_left\"><img src=\"images/red_text_field_left.png\" alt=\"\" /></div><div class=\"red_middle\"><img src=\"images/cross.jpg\" alt=\"\" /><strong>Error:</strong><span class=\"error\"  >From Email cannot be blank.</span></div><div class=\"red_right\"><img src=\"images/red_text_field_right.png\" alt=\"\" /></div>";
	return false;
	}
	else if( emailPattern.test(emailfrom)==false){
	document.getElementById('jserror').innerHTML = "<div class=\"red_left\"><img src=\"images/red_text_field_left.png\" alt=\"\" /></div><div class=\"red_middle\"><img src=\"images/cross.jpg\" alt=\"\" /><strong>Error:</strong><span class=\"error\"  >From Email is not valid.</span></div><div class=\"red_right\"><img src=\"images/red_text_field_right.png\" alt=\"\" /></div>";
	return false;	
	}
	
}

function validateCompanyName(){
	var companyname= document.getElementsByName('companyname')[0].value;
	
	if(companyname == "" ){
	document.getElementById('jserror').innerHTML = "<div class=\"red_left\"><img src=\"images/red_text_field_left.png\" alt=\"\" /></div><div class=\"red_middle\"><img src=\"images/cross.jpg\" alt=\"\" /><strong>Error:</strong><span class=\"error\"  >Company Name cannot be blank.</span></div><div class=\"red_right\"><img src=\"images/red_text_field_right.png\" alt=\"\" /></div>";
	return false;
	}
}

function validateCompanyShortName(){
	var companyshortname = document.getElementsByName('cshortname')[0].value;
	
		if(companyshortname == "" ){
	document.getElementById('jserror').innerHTML = "<div class=\"red_left\"><img src=\"images/red_text_field_left.png\" alt=\"\" /></div><div class=\"red_middle\"><img src=\"images/cross.jpg\" alt=\"\" /><strong>Error:</strong><span class=\"error\"  >Company Short Name cannot be blank.</span></div><div class=\"red_right\"><img src=\"images/red_text_field_right.png\" alt=\"\" /></div>";
	return false;
	}

}

	function FinalValidate(){

 if(validateFromEmail()!= false && validateCompanyName()!=false && validateCompanyShortName()!=false){
	document.getElementById('jserror').innerHTML = "";
	finalform.submit();
	return true;
	}

	else{
	return false;	
	}
}
</script>
</head>


<body>
<div id="outer_container">
	<div id="inner_container">
		<div id="upper_container">
        	<div id="cross"><a href="#"><img src="images/close.png" alt="" /></a></div>
        </div>
        <div id="middle_container">
        <div id="table1">
           <form action="final.php" method="post" name="finalform" onsubmit="return FinalValidate(); ">
          <table width="200" border="0">
       
  <tr>
    <td height="36"><h1>Forgot Password Installation - Step 4</h1></td>
  </tr>
  <tr>
    <td height="35"><div class="blue_up"><img src="images/blue_up.png" alt="" /></div>
    <div class="blue_middle"><img src="images/exclamation.jpg" alt="" /><strong>Information:</strong><p>In most cases you need to enter three fields only on this page -<b> Email From, Company Name and Company Short Name.</b><br /> Fields in yellow is information entered in previous steps. You can modify these in case needed by going to previous steps before configuration file is written. </p></div>
    <div class="blue_dwon"><img src="images/blue_down.png" alt="" /></div>
    </td>
  </tr>
<?php if(isset($error)&& $error!=""){?>
  <tr>
    <td><div class="red_up"><img src="images/blue_up-red.png" alt="" /></div>
    <div class="red_middle"><img src="images/cross.jpg" alt="" /><strong>Error:</strong><p><?php echo $error; ?></p></div>
    <div class="red_dwon"><img src="images/blue_down-red.png" alt="" /></div></td>
  </tr>
<?php } ?>
<tr><td id="jserror">

</td> 
</tr>
    <td height="27">Table Name</td>
  </tr>
<tr>
    <td><div class="gray_left"><img src="images/gray_text_field_left.png" alt="" /></div>
    <div class="gray_middle"><div class="step1_input"><input type="text" name="tablename" readonly="true" style="background:yellow;" value="<?php if(isset($_SESSION['tablename'])){echo $_SESSION['tablename'];} ?>"/></div></div>
    <div class="gray_right"><img src="images/gray_text_field_right.png" alt="" /></div></td>
  </tr>

 <tr>
    <td height="27">Table Prefix</td>
  </tr>
<tr>
    <td><div class="gray_left"><img src="images/gray_text_field_left.png" alt="" /></div>
    <div class="gray_middle"><div class="step1_input"><input type="text" name="tableprefix" readonly="true" style="background:yellow;" value="<?php if(isset($_SESSION['tableprefix'])){echo $_SESSION['tableprefix'];} ?>"/></div></div>
    <div class="gray_right"><img src="images/gray_text_field_right.png" alt="" /></div></td>
  </tr>

 <tr>
    <td height="27">Userid Column</td>
  </tr>
  <tr>
    <td><div class="gray_left"><img src="images/gray_text_field_left.png" alt="" /></div>
    <div class="gray_middle"><div class="step1_input"><input type="text" name="idcolm" readonly="true" style="background:yellow;" value="<?php if(isset($_SESSION['idcol'])){echo $_SESSION['idcol'];} ?>"/></div></div>
    <div class="gray_right"><img src="images/gray_text_field_right.png" alt="" /></div></td>
  </tr>

<tr>
    <td height="27">Username Column</td>
  </tr>
  <tr>
    <td><div class="gray_left"><img src="images/gray_text_field_left.png" alt="" /></div>
    <div class="gray_middle"><div class="step1_input"><input type="text" name="unamecolm" readonly="true" style="background:yellow;" value="<?php if(isset($_SESSION['unamecol'])){echo $_SESSION['unamecol'];} ?>"/></div></div>
    <div class="gray_right"><img src="images/gray_text_field_right.png" alt="" /></div></td>
  </tr>
<tr>
    <td height="27">Password Column</td>
  </tr>
  <tr>
    <td><div class="gray_left"><img src="images/gray_text_field_left.png" alt="" /></div>
    <div class="gray_middle"><div class="step1_input"><input type="text" name="pwordcolm" readonly="true" style="background:yellow;" value="<?php if(isset($_SESSION['passwordcol'])){echo $_SESSION['passwordcol'];} ?>"/></div></div>
    <div class="gray_right"><img src="images/gray_text_field_right.png" alt="" /></div></td>
  </tr>


<tr>
    <td height="27">Email Column</td>
  </tr>
  <tr>
    <td><div class="gray_left"><img src="images/gray_text_field_left.png" alt="" /></div>
    <div class="gray_middle"><div class="step1_input"><input type="text" readonly="true" style="background:yellow;"  name="emailcolm" value="<?php if(isset($emailcol)){echo $emailcol;} ?>"/></div></div>
    <div class="gray_right"><img src="images/gray_text_field_right.png" alt="" /></div></td>
  </tr>


<tr>
    <td height="27">First Name Column</td>
  </tr>
  <tr>
    <td><div class="gray_left"><img src="images/gray_text_field_left.png" alt="" /></div>
    <div class="gray_middle"><div class="step1_input"><input type="text" name="fnamecolm" readonly="true" style="background:yellow;" value="<?php if(isset($_SESSION['fname'])){echo $_SESSION['fname'];} ?>"/></div></div>
    <div class="gray_right"><img src="images/gray_text_field_right.png" alt="" /></div></td>
  </tr>

<tr>
    <td height="27">Full Name Column</td>
  </tr>
  <tr>
    <td><div class="gray_left"><img src="images/gray_text_field_left.png" alt="" /></div>
    <div class="gray_middle"><div class="step1_input"><input type="text" name="lnamecolm" readonly="true" style="background:yellow;" value="<?php if(isset($_SESSION['lname'])){echo $_SESSION['lname'];} ?>"/></div></div>
    <div class="gray_right"><img src="images/gray_text_field_right.png" alt="" /></div></td>
  </tr>
 <tr>
    <td height="27">Password Encryption</td>
  </tr>
<tr>
    <td><div class="gray_left"><img src="images/gray_text_field_left.png" alt="" /></div>
    <div class="gray_middle"><div class="step1_input"><input type="text" name="passencypt" readonly="true" style="background:yellow;" value="<?php if(isset($_SESSION['passencypt'])){echo $_SESSION['passencypt'];} ?>"/></div></div>
    <div class="gray_right"><img src="images/gray_text_field_right.png" alt="" /></div></td>
  </tr>
 <tr>
    <td height="27">Password Length</td>
  </tr>
<tr>
    <td><div class="gray_left"><img src="images/gray_text_field_left.png" alt="" /></div>
    <div class="gray_middle"><div class="step1_input"><input type="text" name="passlength" readonly="true" style="background:yellow;" value="<?php if(isset($_SESSION['passlength'])){echo $_SESSION['passlength'];} ?>"/></div></div>
    <div class="gray_right"><img src="images/gray_text_field_right.png" alt="" /></div></td>
  </tr>
 <tr>
    <td height="27">Password Validation</td>
  </tr>
<tr>
    <td><div class="gray_left"><img src="images/gray_text_field_left.png" alt="" /></div>
    <div class="gray_middle"><div class="step1_input"><input type="text" name="passvalidate" readonly="true" style="background:yellow;" value="<?php if(isset($_SESSION['passvalidate'])){echo $_SESSION['passvalidate'];} ?>"/></div></div>
    <div class="gray_right"><img src="images/gray_text_field_right.png" alt="" /></div></td>
  </tr>
<tr>
    <td height="27">Email From</td>
  </tr>
  <tr>
    <td><div class="gray_left"><img src="images/gray_text_field_left.png" alt="" /></div>
    <div class="gray_middle"><div class="step1_input"><input type="text" name="emailfrom" onchange="validateFromEmail();" value="<?php if(isset($emailfrom)){echo $emailfrom;} ?>"/></div></div>
    <div class="gray_right"><img src="images/gray_text_field_right.png" alt="" /></div></td>
  </tr>
  
<tr>
    <td height="27">Company Name</td>
  </tr>
  <tr>
    <td><div class="gray_left"><img src="images/gray_text_field_left.png" alt="" /></div>
    <div class="gray_middle"><div class="step1_input"><input type="text" name="companyname" value="<?php if(isset($companyname)){echo $companyname;} ?>"/></div></div>
    <div class="gray_right"><img src="images/gray_text_field_right.png" alt="" /></div></td>
  </tr>

<tr>
    <td height="27">Company  Short Name</td> 
  </tr>
  <tr>
    <td><div class="gray_left"><img src="images/gray_text_field_left.png" alt="" /></div>
    <div class="gray_middle"><div class="step1_input"><input type="text" name="cshortname" value="<?php if(isset($ccshortname)){echo $ccshortname;} ?>"/></div></div>
    <div class="gray_right"><img src="images/gray_text_field_right.png" alt="" /></div>
	

	
	
	</td>
  </tr>

  
</table>
</div>

<div id="navigator">
<div class="left_botton"><?php if($_SESSION['haveuserstable']==0){

 ?>
<a href="step1.php?flag=fromfour"><img src="images/prev-red.jpg" alt="" /></a> <?php } 

 else if($_SESSION['haveuserstable']==1){

 ?>
<a href="step3.php"><img src="images/prev-red.jpg" alt="" /></a> <?php } ?></div>
<div class="right_botton"><input type="hidden" name="csubmit" value="Next"/><input type="image" src="images/next-red.gif" name="csubmit" value="Next"/></div>
</div>

 </div>
        <div id="bottom_container"><img src="images/body_round_corner_down.png" alt="" /></div>
	</div>
</div>

</body>

</html>


</body>
</html>