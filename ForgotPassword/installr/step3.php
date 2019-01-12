<?php session_start();
include ('../includes/rconfig.php');
include ('../includes/rfunctions.php');
include ('functions.php');

dbConnect(DB_HOST, DB_USER,DB_PASS, DB_NAME);

if(isset($_GET['error'])&& (($_GET['error']=='miscols'))){
	
	$error = "User Id, Username, Password or User Email column is missing. If the username is an email then it is not necesary to select an email column.";
}
else if(isset($_GET['error'])&& ($_GET['error']=='mistablename')){
	echo "Please select your users tablename.";
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Forgot Password Installtion - Step 3</title>
<script src="ajax/ajax.js" ></script>
<link href="css/style.css" rel="stylesheet" type="text/css" />
 <style type="text/css">
        td { overflow: hidden; }
        table { table-layout: fixed; }
        select{
        	width: 115px;
        }
    </style>

</head>

<body>

<div id="outer_container">
	<div id="inner_container">
		<div id="upper_container">
        	<div id="cross"><a href="#"><img src="images/close.png" alt="" /></a></div>
        </div>
        <div id="middle_container">
        <div id="up_content">
          <div id="left_heading">
 			<h1>Forgot Password Installtion - Step 3</h1></div>
            <div id="right_heading">
<table border="0" width="352">
  <tr>
    <td height="35"><div class="blue_up"><img src="images/blue_up.png" alt="" /></div>
    <div class="blue_middle"><img src="images/exclamation.jpg" alt="" /><strong>Information:</strong><p>Select User Id, Username, Password, Email, First Name(optional), Full Name(optional) columns.<br /> <br /> If your <b>username is an email </b>then selecting the emails column is <b>optional</b>.</p></div>
    <div class="blue_dwon"><img src="images/blue_down.png" alt="" /></div>
    </td>
  </tr>
  <tr  id="errorDiv"> <?php if(isset($error)){ ?>
    <td><div class="red_up"><img src="images/blue_up-red.png" alt="" /></div>
    <div class="red_middle"><img src="images/cross.jpg" alt="" /><strong>Error:</strong><p><?php echo $error; ?></p></div>
    <div class="red_dwon"><img src="images/blue_down-red.png" alt="" /></div></td><?php } ?>
  </tr>
  
  
</table></div>

</div>		<!--up_content ends -->




       <div id="middle_content">
 			<h2>Please choose the correct option.</h2>
 			<form action="step4.php" method="post">
 			<div id="middle_table">
<?php

if(isset($_POST['selecttable'])&& isset($_POST['tabname'])){
	$tablename = mysql_escape_string($_POST['tabname']);
	$_SESSION['tablename'] =$tablename;
	}

if(isset($_SESSION['tablename'])&& ($_SESSION['tablename']!="")){
	//echo $_POST['tabname'];
	$tablename =$_SESSION['tablename'];
	$colcount =  get_columncount($_SESSION['tablename']);
	$columns = get_columns($tablename);
	$noofhtmltables = ceil($colcount/5);
	$useridcol=id_column($tablename);
	$emailcol=email_column($tablename);	
	$usernamecol=username_column($tablename);	
	$passwordcol =userpass_column($tablename);
	$fnamecol=fname_column($tablename);
	$lnamecol=lname_column($tablename);
	$emailcol = email_column($tablename);
	$query_users = "select * from {$tablename} limit 10";
	$result_users = mysql_query($query_users);
	$row_ct = mysql_num_rows($result_users);
	
	echo "<input type=\"hidden\" name=\"tablename\" value=\"{$tablename}\" />";
	for($i = 0; $i <= $noofhtmltables; ){
		echo "<table border=\"0\" style=\"margin-bottom: 50px;\" >";

		$n = 0;
		echo "<tr class=\"tr1\" style=\"width:150px;\">";
		for($n=0;$n<5;$n++ ){
			$newarrid = $i*5+$n;
		if($newarrid<$colcount){
			
			echo "<td height=\"45\"  id=\"sl1\" width=\"20%\" ><div class=\"sl1_select_left\"><img src=\"images/select_gradient_left.png\" alt=\"\" /></div>
    			<div class=\"sl1_select_middle\"><select name=\"{$columns[$newarrid]}\"  onchange=\"checkParam(this, '{$columns[$newarrid]}', 'errorDiv');\">";
			echo "<option value=\"\" >--Select--</option>";	



		if($fnamecol==$columns[$newarrid]){$selected = "selected=\"selected\""; }
			else{$selected="";}	
		echo "<option value=\"{$columns[$newarrid]}_fname\" {$selected}>First Name</option>";
		if($lnamecol==$columns[$newarrid]){$selected = "selected=\"selected\""; }
			else{$selected="";}
	
		echo "<option value=\"{$columns[$newarrid]}_lname\" {$selected}>Full Name</option>";

		if($emailcol==$columns[$newarrid]){$selected = "selected=\"selected\""; }
			else{$selected="";}
			echo "<option value=\"{$columns[$newarrid]}_email\" {$selected}>Email</option>";			
		
		if($usernamecol==$columns[$newarrid]){$selected = "selected=\"selected\""; }
			else{$selected="";}
			echo "<option value=\"{$columns[$newarrid]}_username\" {$selected}>Username</option>";		

		if($passwordcol==$columns[$newarrid]){$selected = "selected=\"selected\""; }
			else{$selected="";}	
		echo "<option value=\"{$columns[$newarrid]}_password\" {$selected}>Password</option>";
				
		if($useridcol==$columns[$newarrid]){$selected = "selected=\"selected\""; }
			else{$selected="";}
			echo "<option value=\"{$columns[$newarrid]}_userid\" {$selected}>User Id</option></select></div>
    			<div class=\"sl1_select_right\"><img src=\"images/select_gradient_right.png\" alt=\"\" /></div></td>";		
		}
		}
				
		echo "</tr>";

		echo "<tr class=\"tr2\">";
		$m = 0;
		for($m=0;$m<5;$m++ ){
			//echo $i;
		$arrid = $i*5+$m;

		if($arrid<$colcount){
		//$style = is_selected($columns[$arrid], $tablename);
			echo "<td class=\"td1\"  width=\"20%\">{$columns[$arrid]}</td>";
		}
		}
		echo "</tr>";
		
		echo"<tr>";
		
	for($j=0; $j<$row_ct;$j++){
//echo $j."<br />";
 	echo "<tr class=\"tr2\">";
 	for($k=0; $k<5; $k++){
 //	echo $i*9+$k." ";
//	echo $k;
	//echo $i;

	$rowid = $i*5+$k;

	if($rowid<$colcount){
			echo"<td  width=\"20%\" \">";
	echo mysql_result($result_users, $j,$columns[$rowid] );
	echo"</td>";
	}

	// echo $k." ";	
 	}
	echo "</tr>";
//	echo "<br />";
	}
			echo "</table>";
	echo "";
	$i++;
}
}


?>
		</div>	</div>
<div id="navigator">
<div class="left_botton"><a href="step2.php"><img src="images/prev-red.jpg" alt="" /></a></div>
<div class="right_botton"><input type="hidden" name="submitselcol" value="Proceed" /><input  src="images/next-red.gif"  type="image" name="submitselcol" value="Proceed" />

</div></form>
</div>

        </div>
        <div id="bottom_container"><img src="images/body_round_corner_down.png" alt="" /></div>
	</div>
</div>

</body>

</html>

