<?php

class RecoverPas{
	/******Basic Settings*******/
private $dbuserstable = 'users'; /*This is the users table in the database. It contains userid/id, username, password, firstname, lastname etc columns */
private $dbprimaryidcol = 'id'; /*This is the userid or id column in the above users table in database. Most of the times it is the first column in a table. */
private $dbusernamecol = 'username'; /*This is the username column in users table in database. */
private $dbemailcol = 'email';		 /**/
private $dbpasswordecol = 'password';/*This is the password column in users table where password is stored in encrypted format or otherwise. */
private $dbfnamecol = ''; /*This is the first name column in users table in database. If filled password reset wil address the user by firstname */
private $dbnamecol = ''; /*This is the name column in users table in database. If filled password reset wil address the user by name */

public $companyname = 'Satyam Technologies';  /*This company name displays in the signature part of Password reset email  sent to the user.*/
public $companyshortname = 'SatyamTech'; /*This company name displays in the signature part of Password reset email  sent to the user. */

public $resetemailfrom = 'support@satyamtechnologies.net'; /* The email address from which a password reset email is sent. */

/******Optional Settings*******/

public $resetmailsubj = '';  /* Reset Email Subject (optional) */
public $resetmailmesshtml = '';	/* Reset Email in HTML format (optional) */
public $resetmailmesstext = '';	/* Reset Email in HTML format (optional) */

private $salt = "";		/*for future development when we work for password with salts. */
private $hashmethod = 'md5'; /* Hashing type for Password. It can be md5 or sha1. */ 
private $deloldcodeint = 1; //delte older codes used or unused interval in days. 

/******For Internal Use*******/
private $validations;
private $error;	
private $messages = array();
private $success;
private $userid;
private $userdata;
private $maxresets = 100; /*This is the maximum possible number of password requests that may be active at any point of time.*/
public $reseturl;

/*******************************Constructor Method**************************************/

public function __construct()	
{
	$this->newMessage();		//Allow new object access to default Error Messages.
	$this->deleteOldCodes();	//Delete reset codes older that a certain interval.

	if(defined('DB_USERTABLE')){
		$this->dbuserstable = DB_USERTABLE;
	}
	if(defined('DB_USERIDCOL')){
		$this->dbprimaryidcol = DB_USERIDCOL;
	}
	if(defined('DB_USERNAMECOL')){
		$this->dbusernamecol = DB_USERNAMECOL;
	}
	if(defined('DB_USEREMAILCOL')){
		$this->dbemailcol = DB_USEREMAILCOL;
	}
	if(defined('DB_USERPASSCOL')){
		$this->dbpasswordecol = DB_USERPASSCOL;
	}
	if(defined('DB_FNAMECOL')){
		$this->dbfnamecol = DB_FNAMECOL;
	}
	if(defined('DB_NAMECOL')){
		$this->dbnamecol = DB_NAMECOL;
	}
	if(defined('FROMEMAIL')){
		$this->resetemailfrom = FROMEMAIL;
	}
	
	if(defined('COMPANYNAME')){
		$this->companyname = COMPANYNAME;
	}
	
	if(defined('COMPANY_SHORTBANE')){
		$this->companyshortname = COMPANY_SHORTBANE;
	}
		
	if(defined('MINPASSLENGTH')&& is_numeric(MINPASSLENGTH)&& MINPASSLENGTH>0){
		$this->validations = 'len='.MINPASSLENGTH;
	}
	if(defined('PASSVALIDATION')&& PASSVALIDATION!=""){
		if($this->validations!=""){
			$this->validations .= '&'.PASSVALIDATION;
		}
		else{
			$this->validations = PASSVALIDATION;	
		}
	
	}
	if(defined('PASSENCRYPT')){
		$this->hashmethod = PASSENCRYPT;
	}

	 
}

/*******************************Basic Methods**************************************/

/*Initiates sending of reset email once user 
fills email/ username in the forgot passwod 
form.*/
public function initiateReset($emailunameinput = 'emailuname') 
{
	if(isset($_POST)&& !empty($_POST))		// When user submits the forgot password form.
	{
	$useremail = "";
	$emailuname = $this->cleanforDb(trim($_POST[$emailunameinput]));
        if($emailuname=="")					//If  email/ username is blank.
		{ 
			$this->error = $this->messages['noblank'];
	 	}
	 	else									
	 	{
			$validemail = $this->isValidEmail($emailuname);	 //check if user input is an email.  
        	if($this->dbusernamecol == $this->dbemailcol)   //If username and email columns are same.
        	{
                 if($validemail)							// If  user input  is a valid email.
                 {
                    if($this->emailExists($emailuname)!= false) //If user input is an email and exists in users table.  
                    {
                        $userid = $this->emailExists($emailuname);
                        $useremail = $emailuname;
                    }
                else								//If the user input which is an email does not exist in  users table. 
                    {
                    	$this->error = $this->messages['noexist'];                
                    }
                }
                else											// If the user input is not a valid email.
                {
                    	$this->error = $this->messages['invalid'];     
                }
        	}
       		else									//If username and email columns are NOT same.
        	{
            	if($validemail)						// if user input is an email. 			
           	 	{
	                 if($this->usernameExists($emailuname)!= false) // if user input exists in username column of users table.  
	                 {
	                    $userid = $this->usernameExists($emailuname);
	                    $useremail = $emailuname;
	                 }
	                 else if($this->emailExists($emailuname) != false) // if user input exists in email column of users table. 
	                 {
	                    $userid = $this->emailExists($emailuname); 
	                    $useremail = $emailuname;                   
	                 }
	                 else												// if user input does not exist in username or email column of users table.
	                 {
	                    $this->error = $this->messages['noexist'];              
	                 }   
	            }
	            else													// if user input is not a valid email.
	            {
	                if($this->usernameExists($emailuname)!= false)		//if user input  exists in username column of users table.
	                {
	                    $userid = $this->usernameExists($emailuname);                    
	                }
	                else												//if user input which is not an email does not exist in username column of users table.
	                {
	                $this->error = $this->messages['noexist'];               
	                }  
	            }
        	}
	        if(isset($userid)&& $userid!="")							//If a userid based on userinput was found then send a password reset email. 
			{
	        	$this->userid = $userid;
	        	$this->reseturl = $this->resetUrl();
	        	$this->sendResetMail($userid,$useremail);
	  		}
   		}   	 		
	}//End isset Post 
}

/*Once Reset Password Email is clicked and password
 is entered twice this function vlaidates data and 
 changes password. */
public function resetPassword($passinput='password', $rpassinput='rpassword', $validations ='len=6')
{ 														//len = 6, alpha, alphanumeric, alpha+special, alphanumeric+special

	if($this->validations!="" && $validations == 'len=6'){
		$validations = $this->validations;
	}

	if(!isset($_GET['regen'])|| $_GET['regen']==""||empty($_GET['regen'])) 
	{ 															//When reset code is not present redirect.
		header('location:resetinvalid.php');	
	}
	if(isset($_GET['regen'])&& $_GET['regen']!="") //If reset code exists replace invalid chars and set userid if exists
	{ 
		$resetcode= preg_replace("/[^a-zA-Z0-9\s]/", "", $_GET['regen']);
		if($this->setUseridByResetcode($resetcode)!=true)
		{
			header('location:resetinvalid.php');
		}
	}
	if(isset($_POST)&& !empty($_POST))			//If form was submitted	
	{	
	//	$validationarray = explode('&',$validations);	
		if($_POST[$passinput]==""||$_POST[$rpassinput]=="") // If entered password is blank.
		{
			$this->error = $this->messages['noblank'];
		}
		else if(trim($_POST[$passinput])!= trim($_POST[$rpassinput])) //If the two passwords entered match.
			{
				$this->error = $this->messages['nomatch'];
			}
		else{
		$temppass  = trim($_POST[$passinput]);	

		$this->applyValidationrule($temppass, $validations);	//Check if the format of password is correct length etc.			
		}	
	
		if($this->error=="") 									//If error is blank till here then
		{
				$newpass =$this->securePass(trim($_POST[$passinput])); //hash password if applicable
				if(isset($newpass) && $newpass != "") //if the password is set and not blank update for rspective user.
				{
					$this->updatePassword($newpass);
				}

		}
	}//end isset post
}

/* Prints Errors if they occur */
public function printError()
{
  echo $this->error;
}

/* Prints Success messages for example when
 a reset mail is sent or when password is 
 changed */
public function printSuccess()
{
  echo $this->success;
}

/*Option method to set a custom subject for the reset email*/
public function setResetSubject($emailsubject="")
{
	if($emailsubject=="")
	{
		$emailsubject = $this->companyshortname." Password Reset Mail";
	}
	$this->resetmailsubj = $emailsubject;
}

/*Optional method to set a custom message in text 
format for the reset email. Use {username} and 
{reseturl} to insert respective variables. */
public function setResetMessage($textmessage=""){
    if($textmessage=="")
	{
    	$textmessage= "Dear {username},\r\nTo reset your password please follow the link below:\n"; 

    	$textmessage .= "You can use the password reset link only once, after which you'll have to request to reset your password again.\r\n ";
    	$textmessage .= "{reseturl}\n";
    	$textmessage .= "Regards,\n{$this->companyname}\r\n";
    }
    $this->resetmailmesstext = $this->replaceText($textmessage);
}

/*Optional method to set a custom message in html 
format for the reset email. Use {username} and 
{reseturl} to insert respective variables. */
public function setResetMessageHTML($htmlmessage="")
{
    if($htmlmessage=="")
	{
    	$htmlmessage= "Dear {username}<br />To reset your password please follow the link below:<br />"; 
 	   $htmlmessage .= "You can use the password reset link only once, after which you'll have to request to reset your password again.<br />";
 	   $htmlmessage .="{reseturl}<br />";
	   $htmlmessage .= "Regards,<br />".$this->companyname."  ";
    }
    $this->resetmailmesshtml =$this->replaceText($htmlmessage,'html');
}

/*method to set password encryption method to md5 
or sha1 or blank. */
 function setEncryption($encryption)
 {
 	$this->$hashmethod = trim($encryption);
 }


/*******************************Internal Methods**************************************/

/*This method checks if a user exists for corresponding
 reset code and assigns userid or redirects*/
private function setUseridByResetcode($resetcode)
{
	$query_getid = "select user_id from activeresets where resetcode = '{$resetcode}' limit 1";
	$result_getid = mysql_query($query_getid) or die(mysql_error());
	if(mysql_num_rows($result_getid)==0)
	{ 
		return false;
	}
	else
	{
		$this->userid = mysql_result($result_getid, 0, 'user_id');
		return true;	
	}
}

/*This method checks the supplied password format*/		
private function applyValidationrule($temppass, $validations)
{

	$validationarray = explode('&',$validations);
	foreach($validationarray as $validation)
	{
		if(!(strpos($validation, '=')===FALSE))		//if validation string contains an '=' sign validate for minimum length condition
		{
			$check = explode('=',$validation);
			if(strlen($temppass)<$check[1])
			{
				$this->error = $this->messages['minlength'];
				break; 
			}

		}
		else if(!(strpos($validations, 'alphanumeric+special')===FALSE))	//if validation string contains 'alphanumeric+special' validate to allow alphabetical, numeric and special characters characters.
		{
			if(!( preg_match('/[a-zA-Z]/', $temppass) &&  preg_match('/\d/',$temppass) && preg_match('/[^a-zA-Z\d]/', $temppass)))
			{
			  $this->error = $this->messages['alphanumsp']; 
			  break; 
			}
		}
		else if(!(strpos($validations, 'alpha+special')===FALSE))	//if validation string contains 'alpha+special' validate to allow alphabetical and special characters characters.
		{						
			 if(!( preg_match('/[a-zA-Z]/', $temppass) && preg_match('/[^a-zA-Z\d]/', $temppass)))
			{
				$this->error = $this->messages['alphasp'];
				break; 
			}

		}
		else if(!(strpos($validations, 'alphanumeric')===FALSE))	//if validation string contains 'alphanumeric' validate to allow alphabetical and numeric characters only.
		{
			if(!( preg_match('/[a-zA-Z]/', $temppass) &&  preg_match('/\d/',$temppass)))
			{
				$this->error = $this->messages['alphanum'];
				break; 

			}

		}
		else if(!(strpos($validations, 'alpha')===FALSE ))	//if validation string contains 'alpha' validate to allow alphabetical characters only.
		{
			if(!( preg_match('/[a-zA-Z]/', $temppass)))
			{
				$this->error = $this->messages['alphaonly'];
				break; 
			}
 
		}
		else
		{
			   $this->error	= "";
		}
	 
	}
}

/*Method to hash password as per the hashing
 method set. */
public function securePass($newpass)
{
	if($this->hashmethod == 'md5')
	{
		$hapass = md5($this->salt.$newpass); 
	}
	else if($this->hashmethod == 'sha1')
	{
		$hapass = sha1($this->salt.$newpass);
	}
	else
	{
		$hapass = 	$newpass;
	}
	return $hapass;
}

/*Method to change Password in the users
 table. */
public function updatePassword($newpass)
{				
	$query_ch = "update ".$this->dbuserstable. " set ".$this->dbpasswordecol." = '".$newpass."' where ".$this->dbprimaryidcol." = ".$this->userid." limit 1";
	$result_ch = mysql_query($query_ch);				
	if($result_ch)
	{ 
		$this->success = $this->messages['passchanged'];
		$this->deleteCodebyUserid($this->userid);
		return true;
	}
	else
	{
		$this->error = $this->error = $this->messages['tryagain'];
		return false;
	}		
}

/*method to create the reset password url.*/		
private function resetUrl()
{
	return $this->currentUrl()."resetpassword.php?regen=".$this->resetCode();
}
	
	
/*method to send password reset email 
for a userid and useremail. */
private function sendResetMail($userid,$useremail="" )
{	
	$userdata = $this->getUserData($userid);
 	$this->userdata = $userdata;
  	if(!isset($useremail)||$useremail=="")
   	{
    	$useremail =   $userdata[$this->dbemailcol];
    }
    if(isset($this->dbfnamecol))
    {
    	$addressname =   $this->dbfnamecol; 
    }
    elseif(isset($this->dbname))
    {
    	$addressname = $this->dbname;
     	$addressname = explode(" ",$addressname); 
      	$addressname = $addressname[0];               
    }
    else
    {
    	$addressname = $userdata[$this->dbusernamecol];
    }
    if($this->resetmailmesstext=="")
    {
    	$this->setResetMessage();
    }
    if($this->resetmailmesshtml=="")
    {
    	$this->setResetMessageHTML();
    }
    if($this->resetmailsubj=="")
    {
    	$this->setResetSubject();
    }                      
    	$sendmail = $this->sendMail($useremail, $this->resetemailfrom, $this->resetmailsubj, $this->resetmailmesshtml, $this->resetmailmesstext);
    if(!$sendmail)
    {
    	$this->error = $this->messages['tryagain'];
     	return false;
    }
    else
    {
    	$this->success = $this->messages['resetsent'];
     	return true;
    }
}	

/*method to replace the shorttags with variables
 in the email messages */
private function replaceText($message, $type='plain')
{   
	$firstname = "user";
 	if(isset($this->dbfnamecol)&& $this->dbfnamecol != "")
	{
 		$firstname = $this->userdata[$this->dbfnamecol];		
   	}
    elseif(isset($this->dbnamecol)&& $this->dbnamecol != "")
    {
   		$firstname = $this->userdata[$this->dbnamecol];
	}
	if($firstname=="")
	{
		$firstname = "user";	
	}
 	$message = str_replace('{username}',$firstname, $message );	
  	if($type=='html')
	{	
 		$message = str_replace('{reseturl}',"<a href=\"".$this->reseturl."\">".$this->reseturl."</a>", $message );		   	
    }
   else
	{
		$message = str_replace('{reseturl}',$this->reseturl, $message );	
 	}
 	return $message;
}

/*Method to generate password reset code.*/
private function resetCode()
{                                
	$randcode = $this->randomCode();
	$count = 1;
 	while($count>0)
	 {
  		$query_unq = "SELECT id FROM activeresets WHERE resetcode = '{$randcode}' LIMIT 1";	
    	$result_unq = @mysql_query($query_unq);
   		$count = @mysql_num_rows($result_unq);
	    $randcode = $this->randomCode();	
    }
		$this->deleteCodebyUserid($this->userid);
		$this->addCode($this->userid, $randcode); 		  
  		return $randcode;
}

/*Method to insert the resetcode generated in 
activeresets table. */		
private function addCode($userid, $randomcode)
{
	$query_newcode = "insert into activeresets (user_id,resetcode) values({$userid},'{$randomcode}')";
	$result_newcode = mysql_query($query_newcode) or die(mysql_error());
}

/*Method to delete a reset code from database
 after it has been used by the concerned user. */		
public function deleteCodebyUserid($userid)
{
	$query_deloldcodes = "delete from activeresets where user_id = {$this->userid}";
	$result_deloldcodes = mysql_query($query_deloldcodes);
}

/*Method to delete old codes */	
public function deleteOldCodes(){
	$query_deloldcodes = "delete from activeresets where date  < DATE_SUB(CURDATE(), INTERVAL {$this->deloldcodeint} DAY)";
	$result_deloldcodes = mysql_query($query_deloldcodes);
	}
	
/*Method to generate a unique random code */
private function randomCode()
{	                                 
	if($this->maxresets < 10000)
	{ 
		$this->maxresets = 10000;
	} 
	else
	{ 
		$this->maxresets = $this->maxresets*100;
	}
	for($i=0; $i< strlen($this->maxresets)*2; $i++)
	{
		$randchars[] = chr(rand(97,122));
	 }
	$timestring = (string)time();
	$code = '';	
	$i = 0;
	foreach($randchars as $randchar)
	{
		$code .= $randchar;
  		if($i<strlen($timestring))
  		{
	    	$code .= $timestring{$i};
	    }
	$i++;	
	}
	return $code;
}

/*Method to check is valid*/
private function resetcodeExists($resetcode)
{
	$query = "select user_id from activeresets where resetcode ='{$resetcode}'";
	$result = mysql_query($query);
	if(mysql_num_rows($result)==1)
	{
		return mysql_result($result,0, 'user_id');
	}
	else 
	{ 
		return false;
	}
}

/*Method checks if the supplied email address exists in
 the users table column defined as .dbemailcol*/
private function emailExists($email)
{  
    $query_chkemail = "select ".$this->dbprimaryidcol." from ".$this->dbuserstable." where ".$this->dbemailcol." = '{$email}' limit 1";
    $result_chkemail = mysql_query($query_chkemail)	or die(mysql_error());
    if(mysql_num_rows($result_chkemail)>0)
    {
      return mysql_result($result_chkemail, 0, $this->dbprimaryidcol); 
    }
    {
      return false;  
    } 
}

/*Method checks if the supplied username exists */
private function usernameExists($username)
{
    $query_chkuname = "select ".$this->dbprimaryidcol." from ".$this->dbuserstable." where ".$this->dbusernamecol." = '{$username}' limit 1";
    $result_chkuname = mysql_query($query_chkuname)	or die(mysql_error());
    if(mysql_num_rows($result_chkuname)>0)
    {
      return mysql_result($result_chkuname, 0, $this->dbprimaryidcol); 
    }
    else
    {
      return false;  
    } 
}

/*Method gets user data for a particular user id. */
private function getUserData($id)
{
    $query_userdata = "select * from ".$this->dbuserstable." where ".$this->dbprimaryidcol." = {$id} limit 1";
    $result_userdata = mysql_query($query_userdata)	or die(mysql_error());
    if(mysql_num_rows($result_userdata)>0)
    {
      return mysql_fetch_array($result_userdata); 
    }
    else
    {
      return false;  
    } 
}

/*Method which allows setting custom error
 and success messages */
 function newMessage($messagekey='',$message='')
{
	$this->messages['invalid']='Invalid Username/ Email Address.';
	$this->messages['noexist']='This Username/ Email does not exist.';
	$this->messages['noblank']='Username/ Email cannot be blank.';
	$this->messages['minlength']='Password does not match the minimum length  critera.';
	$this->messages['alphaonly']='Password should contain alphabets.';
	$this->messages['alphanum']='Password can contain alphabets and numbers.';
	$this->messages['alphasp']='Password should have alphabets and special characters.';
	$this->messages['alphanumsp']='Password should contain alphabets, numbers and special characters.';
	$this->messages['spchar']='Password should have atleast one special character.';
	$this->messages['nomatch'] = 'Passwords do not match.';
	$this->messages['error']='There was an error.';
	$this->messages['tryagain'] = 'There was an Error. Please try again.';
	$this->messages['resetsent']='A Password reset email was sent sucessfully.';
	$this->messages['passchanged'] = 'Your Password was changed sucessfully.';
	if($messagekey!="" && array_key_exists($messagekey,$this->messages))
	{
		$this->messages[$messagekey] = $message;
	}
}

/*******************************Internal Utility Methods**************************************/

/*Method to clean data for database.*/
function cleanforDb($value)
{
   	$mq_active = get_magic_quotes_gpc();
	$new_php = function_exists("mysql_real_escape_string"); 
	if($new_php)
	{ 
		if ($mq_active) 
		{ 
			$value = stripslashes($value);
	 	}
		$value = mysql_real_escape_string($value);
		$value = addslashes($value);
 	}
	else
    { 
		if(!$mq_active)
		{
			$value = addslashes($value);
		}
	}			
	return $value; 
}

/*Method to send an email in both html and text format.*/
function sendMail($to='', $from='', $subject='', $html_content='', $text_content='') 
{ 
	$mime_boundary = 'Multipart_Boundary_x'.md5(time()).'x';
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\r\n";
	$headers .= "Content-Transfer-Encoding: 7bit\r\n";
	$message	 = "--$mime_boundary\n";
	$message	.= "Content-Type: text/plain; charset=\"charset=iso-8859-1\"\n";
	$message	.= "Content-Transfer-Encoding: 7bit\n\n";
	$message	.= $text_content;
	$message	.= "\n\n";
	$message	.= "--$mime_boundary\n";
	$message	.= "Content-Type: text/html; charset=\"UTF-8\"\n";
	$message	.= "Content-Transfer-Encoding: 7bit\n\n";
	$message	.= $html_content;  
//	$message	.= "--$mime_boundary--\n"; 
	$headers .= "From: $from\r\n";
	$headers .= "X-Sender-IP: $_SERVER[SERVER_ADDR]\r\n";
	$headers .= 'Date: '.date('n/d/Y g:i A')."\r\n";
	return mail($to, $subject, $message, $headers);
}

/*Method to check if an email is valid. */
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

/*Method to get current page url.*/
private function currentUrl()
{                                                     
	$url =  (!empty($_SERVER['HTTPS']) ? 'https://': 'http://').$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];	
	$url = explode('/',$url);
	unset($url[count($url)-1]);
	return implode('/',$url).'/';
}
}
?>