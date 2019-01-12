<?php include ('../includes/rconfig.php');
include ('../includes/rfunctions.php');
include ('functions.php');
session_start();
dbConnect(DB_HOST, DB_USER,DB_PASS, DB_NAME);

$prefix = get_tableprefix(DB_NAME);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="Anupam Rekha" />

	<title>Untitled 1</title>

<script type="text/javascript">

function addRow(tablename){
var tbody = document.getElementById(tablename).getElementsByTagName("tbody")[0];
var row = document.createElement("tr");
var cell1 = document.createElement("td");
var field1 = document.createElement("input");
    field1.setAttribute("type", "text");
    field1.setAttribute("value", "Username");
    field1.setAttribute("name", "Username[]");
cell1.appendChild(field1);
var cell2 = document.createElement("td");
var field2 = document.createElement("input");
    field2.setAttribute("type", "text");
    field2.setAttribute("value", "Email");
    field2.setAttribute("name", "Email[]");
cell2.appendChild (field2);
var cell3= document.createElement("td");
var field3 = document.createElement("input");
    field3.setAttribute("type", "text");
    field3.setAttribute("value", "Password");
    field3.setAttribute("name", "Password[]");
cell3.appendChild(field3);
row.appendChild(cell1);
row.appendChild(cell2);
row.appendChild(cell3);
tbody.appendChild(row);
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
	document.getElementById('prefixrow').style.display = "none";
	document.getElementById('sampleusers').style.display = "none";
	document.getElementById('testuserhead').style.display = "none";	
}

else{
	document.getElementById('prefixrow').style.display = "inline";
	document.getElementById('sampleusers').style.display = "inline";
	document.getElementById('testuserhead').style.display = "inline";		
}
}

function validateUsername(element){
	var textval = element.value;
	 if(textval == "" ){
		document.getElementById('jserror').innerHTML = "Test username cannot be blank.";
		return false;
	}
	
	else if(textval.length<4){
		document.getElementById('jserror').innerHTML = "Test username should be at least 4 characters long.";
		return false;
	}
	
	else if(/[^a-zA-Z0-9]/.test( textval )){
		document.getElementById('jserror').innerHTML = "Test username can be alphanumeric only.";
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
		document.getElementById('jserror').innerHTML = "Test password cannot be blank.";
		return false;
	}
	
	else if(textval.length<6){
		document.getElementById('jserror').innerHTML = "Test password should be at least 4 characters long.";
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
//	alert(emailPattern.test(textval));

 	if(textval == "" ){
	document.getElementById('jserror').innerHTML = "Test email cannot be blank.";
	return false;
	}
	else if( emailPattern.test(textval)==false){
	document.getElementById('jserror').innerHTML = "Test email is not valid.";
	return false;	
	}
	else{
	document.getElementById('jserror').innerHTML = "";
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


<span class="error" id="jserror" ><?php if(isset($error)){ echo $error; } ?></span>

<form method="post" action="step2.php">
<table>

<tr>
<td colspan="2">I already have a user table in my database. <input type="radio" name="haveuserstable" onclick="toggleForm()"  value="1" checked="true"/> Yes<input type="radio" name="haveuserstable" value="0" onclick="toggleForm()"   /> No</td>

</tr>

<tr>
<td>Password Encryption</td>
<td><select name="passencypt" >
<option value="none" selected="true">None</option>
<option value="md5">MD5</option>
<option value="sha1">SHA1</option>
</select>


</td>
</tr>


<tr id="prefixrow">
<td>Table Prefix</td>
<td><input type="text" name="tableprefix"  value="<?php echo $prefix; ?>" <?php if($prefix!="" ){echo "readonly=\"true\"";} ?>/>


</td>
</tr>
</table>



<h3 id="testuserhead"> Test Users <span onclick="addRow('sampleusers');return false;"><a href="">Add</a></span></span> <h3>



<table id="sampleusers">
<tbody>
<tr>

<td><input type="text" name="username[]" value="Username" onchange="validateUsername(this);"  /></td>
<td><input type="text" name="email[]" value="Email" onchange="validateEmail(this);" /></td>
<td><input type="text" name="password[]" value="Password" onchange="validatePassword(this);" /></td>

</tr>
</tbody>
</table>






<a href="index.php">back</a></a>
<input type="submit" name="selecttable" value="Proceed" />



</form>

</body>
</html>