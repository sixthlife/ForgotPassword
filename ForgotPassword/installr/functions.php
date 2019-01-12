<?php


function get_tableprefix($dbname){
	$tablearr = get_tablenames($dbname);
	if(count($tablearr)>1){
	return common_str($tablearr);}
}

function has_usertblstr($tablename){
	 if(strpos(strtolower($tablename), 'user')!== FALSE||strtolower($tablename)=='user'||strpos(strtolower($tablename), 'member')!== FALSE||strtolower($tablename)=='member'||strpos(strtolower($tablename), 'register')!== FALSE||strtolower($tablename)=='register'||strpos(strtolower($tablename), 'login')!== FALSE||strtolower($tablename)=='login'||strtolower($tablename)=='users'){
	 	return true;
	 }
	 else{
	 	return false;
	 }
}

function get_tablenames($dbname){
	$table_arr = array();
	$showtablequery = "
	SHOW TABLES
	FROM
	
	".$dbname;
	$showtablequery_result	= mysql_query($showtablequery);
	
	while($showtablerow = mysql_fetch_array($showtablequery_result))
{
	$table_arr[] = $showtablerow[0];
}

return $table_arr;
}

function possible_usertables($dbname){
	$table_array = get_tablenames($dbname);
	$possible_tables = array();
	foreach($table_array as $table){
if(strpos(strtolower($table), 'user')!== FALSE||strtolower($table)=='user'||strpos(strtolower($table), 'member')!== FALSE||strtolower($table)=='member'||strpos(strtolower($table), 'register')!== FALSE||strtolower($table)=='register'||strpos(strtolower($table), 'login')!== FALSE||strtolower($table)=='login'||strtolower($table)=='users'){
	$possible_tables[] = $table;		
}
}
return $possible_tables;
}

function common_str($strarray)
{	
	$smallest = $strarray[0];

foreach($strarray as $str){
	$nextstr = next($strarray);
//	echo $nextstr."<br />";
if(strlen($nextstr)<strlen($smallest)&& $nextstr!=""){
 	$smallest = $nextstr;
	// $smallest." c <br />";
}
}

$prefix = $smallest;

$presize = strlen($prefix);
$pref_arr = array();
for ($i = $presize; $i >0;  $i--){
	foreach($strarray as $str){
		$pref_arr[] = substr($str, 0, $i);
	}

	$dedup = array_unique($pref_arr);
//		print_r($dedup);
	if(count($dedup)==1){
		$prefix = $pref_arr[0];
		break;
	}
	else{
	$prefix = "";	
	}
	$pref_arr = "";
}
return $prefix;
}

function raw_columns($table){
$myQuery = "show columns from $table";
$result = mysql_query($myQuery);
return $result;
}

function get_columncount($table){
$result =raw_columns($table);
return mysql_num_rows($result);
}

function get_columns($table){
$result = $result =raw_columns($table);
$colarray = array();

while($row = mysql_fetch_assoc($result)){
 $colarray[] = $row['Field'];
}
return $colarray;
}


function id_column($table){
$result = raw_columns($table);
	$idcol = array();
while($rawcol =mysql_fetch_assoc($result)){
//	print_r($rawcol);

	
	if($rawcol['Key']=="PRI"){
	$idcol[] = $rawcol['Field'];	
	}
}
if(isset($idcol[0])){
return $idcol[0];	}
}

function email_column($table){
	$result = raw_columns($table);
	$emailcol = array();
while($rawcol =mysql_fetch_assoc($result)){
	if((strtolower($rawcol['Field'])=="email")||(strpos(strtolower($rawcol['Field']), "email")!==false)){
	$emailcol[] = $rawcol['Field'];	
	}

	}
if(isset($emailcol[0])){
return $emailcol[0];	}	
}

function username_column($table){
$result = raw_columns($table);
	$unamecol = array();
while($rawcol =mysql_fetch_assoc($result)){
//	print_r($unamecol);

	if((strtolower($rawcol['Field'])=="username")||(strpos(strtolower($rawcol['Field']), "username")!==false)){
	$unamecol[] = $rawcol['Field'];	
	}
	if((strtolower($rawcol['Field'])=="uname")||(strpos(strtolower($rawcol['Field']), "uname")!==false)){
			$unamecol[] = $rawcol['Field'];	
		}
	if((strtolower($rawcol['Field'])=="u_name")||(strpos(strtolower($rawcol['Field']), "u_name")!==false)){
	$unamecol[] = $rawcol['Field'];	
	}
	if((strtolower($rawcol['Field'])=="user_name")||(strpos(strtolower($rawcol['Field']), "user_name")!==false)){
	$unamecol[] = $rawcol['Field'];		
	}
	if(((strtolower($rawcol['Field'])=="user")||(strpos(strtolower($rawcol['Field']), "user")!==false))&&(strpos(strtolower($rawcol['Field']), "password")==false &&(strpos(strtolower($rawcol['Field']), "pass")==false && strpos(strtolower($rawcol['Field']), "add")==false && strpos(strtolower($rawcol['Field']), "info")==false))){

	$unamecol[] = $rawcol['Field'];		
	}
	if(((strtolower($rawcol['Field']=="users"))||(strpos(strtolower($rawcol['Field']), "users")!==false))&&(strpos(strtolower($rawcol['Field']), "password")==false &&(strpos(strtolower($rawcol['Field']), "pass")==false))&& strpos(strtolower($rawcol['Field']), "add")==false && strpos(strtolower($rawcol['Field']), "info")==false){
	$unamecol[] = $rawcol['Field'];		
	}

}
//	print_r($unamecol);

if(isset($unamecol[0])){
return $unamecol[0];	}
}

function userpass_column($table){
$result = raw_columns($table);
	$passcol = array();
while($rawcol =mysql_fetch_assoc($result)){
//	print_r($unamecol);

	if((strtolower($rawcol['Field'])=="password")||(strpos(strtolower($rawcol['Field']), "password")!==false)){
	$passcol[] = $rawcol['Field'];	
	}
	if((strtolower($rawcol['Field'])=="pass_word")||(strpos(strtolower($rawcol['Field']), "pass_word")!==false)){
			$passcol[] = $rawcol['Field'];	
		}
	if((strtolower($rawcol['Field'])=="pass")||(strpos(strtolower($rawcol['Field']), "pass")!==false)){
	$passcol[] = $rawcol['Field'];	
	}
	if((strtolower($rawcol['Field'])=="pas")||(strpos(strtolower($rawcol['Field']), "pas")!==false)){
	$passcol[] = $rawcol['Field'];	
	}
}
//	print_r($unamecol);

if(isset($passcol[0])){
return $passcol[0];	}
}

function useremail_column($table){
	$result = raw_columns($table);
	$emailcol = array();
while($rawcol =mysql_fetch_assoc($result)){
//	print_r($unamecol);

	if((strtolower($rawcol['Field'])=="email")||(strpos(strtolower($rawcol['Field']), "email")!==false)){
	$emailcol[] = $rawcol['Field'];
	}
	else{
	$query_chk = "select {$rawcol['Field']} from {$table} limit 10";
	$result_chk	= mysql_query($query_chk);
	$countemail = mysql_num_rows($result_chk);
	}
	

}


if(isset($emailcol[0])){
return $emailcol[0];	}
}

function fname_column($table){
$result = raw_columns($table);
	$fnamecol = array();
while($rawcol =mysql_fetch_assoc($result)){
//	print_r($unamecol);

	if((strtolower($rawcol['Field'])=="firstname")||(strpos(strtolower($rawcol['Field']), "firstname")!==false)){
	$fnamecol[] = $rawcol['Field'];	
	}
	if((strtolower($rawcol['Field'])=="first_name")||(strpos(strtolower($rawcol['Field']), "first_name")!==false)){
			$fnamecol[] = $rawcol['Field'];	
		}
	if((strtolower($rawcol['Field'])=="fname")||(strpos(strtolower($rawcol['Field']), "fname")!==false)){
	$fnamecol[] = $rawcol['Field'];	
	}
	if((strtolower($rawcol['Field'])=="f_name")||(strpos(strtolower($rawcol['Field']), "f_name")!==false)){
	$fnamecol[] = $rawcol['Field'];	
	}
	if(((strtolower($rawcol['Field'])=="name")||(strpos(strtolower($rawcol['Field']), "name")!==false))&&(strpos(strtolower($rawcol['Field']), "password")==false &&(strpos(strtolower($rawcol['Field']), "pass")==false))&& strpos(strtolower($rawcol['Field']), "add")==false && strpos(strtolower($rawcol['Field']), "info")==false){
	$fnamecol[] = $rawcol['Field'];	
	}
}
//	print_r($unamecol);

if(isset($fnamecol[0])){
return $fnamecol[0];	}
}

function lname_column($table){
$result = raw_columns($table);
	$lnamecol = array();
while($rawcol =mysql_fetch_assoc($result)){
//	print_r($unamecol);

	if((strtolower($rawcol['Field'])=="lastname")||(strpos(strtolower($rawcol['Field']), "lastname")!==false)){
	$lnamecol[] = $rawcol['Field'];	
	}
	if((strtolower($rawcol['Field'])=="last_name")||(strpos(strtolower($rawcol['Field']), "last_name")!==false)){
			$lnamecol[] = $rawcol['Field'];	
		}
	if((strtolower($rawcol['Field'])=="lname")||(strpos(strtolower($rawcol['Field']), "lname")!==false)){
	$lnamecol[] = $rawcol['Field'];	
	}
	if((strtolower($rawcol['Field'])=="l_name")||(strpos(strtolower($rawcol['Field']), "l_name")!==false)){
	$lnamecol[] = $rawcol['Field'];	
	}
}
//	print_r($unamecol);

if(isset($lnamecol[0])){
return $lnamecol[0];	}
}

function is_selected($option, $tablename){
	$useridcol=id_column($tablename);
	$usernamecol=username_column($tablename);	
	$passwordcol =userpass_column($tablename);
	$fnamecol=fname_column($tablename);
	$lnamecol=lname_column($tablename);
	
	if($option==$useridcol||$option==$usernamecol||$option==$passwordcol||$option==$fnamecol||$option==$lnamecol){
		return "style=\"background:red;\"";
	}
}


function checkVar($varName, $dbColumn, $tablename){
	$error = "";
	$firstten = getfirstten($tablename, $dbColumn);
	$rawcols = raw_columns($tablename);
	//print_r($rawcols);
	$columndata = array();
	while($column = mysql_fetch_assoc($rawcols)){
		if($column['Field']==$dbColumn){
			$columndata  = $column;
		}
	}
//	print_r($columndata);
//	print_r($firstten);
	$isunique = isUnique($firstten);
		//	echo $columndata['Key'];
	if($varName=='username'){
	if(isstrDatatype($column['Field'])==false){ $error = $dbColumn. " cannot be the {$varName} column as it is not a string.";}	
	else if(!$isunique){$error = $dbColumn. " cannot be the {$varName} column as it has duplicates."; }	
	}
	else if($varName=='password'){
	if(isstrDatatype($column['Field'])==false){ $error = $dbColumn. " cannot be the {$varName} column as it it is not a string..";}	
	else if(!$isunique){$error = $dbColumn. " cannot be the {$varName} column as it has duplicates."; }		
	}
	else if($varName=='userid'){
	if($columndata['Key']!='PRI'){ $error = $dbColumn. " cannot be the {$varName} column as it is not a primary key.";}		
	}
	else if($varName=='email'){
	if(!isValidEmail($firstten[0])){$error = $dbColumn. " cannot be the {$varName} column as its values are not emails.";}
	else if(isstrDatatype($column['Field'])==false){ $error = $dbColumn. " cannot be the {$varName} column as it is not a string.";}	
	else if(!$isunique){$error = $dbColumn. " cannot be the {$varName} column as it has duplicates."; }			

	}
	else if($varName=='fname'){
	if(isstrDatatype($column['Field'])==false){ $error = $dbColumn. " cannot be the {$varName} column as it is not a string.";}	
	else if(!$isunique){$error = $dbColumn. " cannot be the {$varName} column as it has duplicates."; }			
	}
	else if($varName=='lname'){
	if(isstrDatatype($column['Field'])==false){ $error = $dbColumn. " cannot be the {$varName} column as it is not a string.";}	
	else if(!$isunique){$error = $dbColumn. " cannot be the {$varName} column as it has duplicates."; }			
	}
//echo $error;
return $error;
	
}


function getfirstten($tablename, $dbColumn){
	$query = "select {$dbColumn} from {$tablename} limit 10";
	$result = mysql_query($query);
	$columndata = array();
	while($row = mysql_fetch_row($result)){
		$columndata[] = $row[0];
	}
	return $columndata;
}

function isUnique($array){
	$array1 = array_unique($array);
	if(count($array)==count($array1)){
		return true;
	}
	else{
		return false;
	}
}


function isstrDatatype($datatype){
		$flag = true;
	switch ($datatype){
		case 'date':
		$flag = false;
		break;
		case 'datetime':
		$flag = false;
		break;
		case 'time':
		$flag = false;
		break;
		case 'timestamp':
		$flag = false;
		break;
		case 'year':
		$flag = false;
		break;
		
		case 'blob':
		$flag = false;
		break;
		case 'tinyblob':
		$flag = false;
		break;
		case 'mediumblob':
		$flag = false;
		break;
		case 'longblob':
		$flag = false;
		break;
		case 'enum':
		$flag = false;
		break;
		case 'set':
		$flag = false;
		break;
	}
return $flag;	
}

function table_exists($tablename, $database = false) {

    if(!$database) {
        $res = mysql_query("SELECT DATABASE()");
        $database = mysql_result($res, 0);
    }

    $res = mysql_query("
        SELECT COUNT(*) AS count 
        FROM information_schema.tables 
        WHERE table_schema = '$database' 
        AND table_name = '$tablename'
    ");

    return mysql_result($res, 0) == 1;

}

function isValidEmail($email)
{
    if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
    return false;
    }
    else
    {
    return true;   
    }
}




?>
