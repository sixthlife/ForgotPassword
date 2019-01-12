<?php
  // Stop page from being loaded directly.
  
  
if (preg_match("/rconfig.php/i", $_SERVER['PHP_SELF'])){
echo "Please do not load this page directly. Thanks!";
exit;
}
define('DB_HOST', 'Your Database name here'); 		/*Enter the database hostname. ex: localhost */
define('DB_NAME', 'Your Database Name here');				/*Enter the database name. */
define('DB_USER','kamas3_forgotpas');			/*Enter the database user name. */
define('DB_PASS','Database User password here.');		/*Enter the database user password. */
define('DB_TABLEPREFIX',''); 		/*Enter the database table prefix. Leave blank if no prefix. */

define('DB_USERTABLE','users'); 		/*Enter the database user table name. */

define('DB_USERIDCOL', 'id'); 	/*Enter the id(primary key) column name in your users table in database. */
define('DB_USERNAMECOL', 'username'); 		/*Enter the username column name in  your users table in database. */
define('DB_USERPASSCOL', 'password'); 			/*Enter the password column name in  your users table in database. */
define('DB_USEREMAILCOL', 'email'); 		/*Enter the email column name in your users table in database. It can be same as username column if your username is an email. */
define('DB_FNAMECOL', ''); 		/*Enter the First Name column name in  your users table in database(optional). */
define('DB_NAMECOL', ''); 		/*Enter the  Name column name in  your users table in database(optional). */

define('FROMEMAIL', 'Your From Email here'); 		/*Enter the Email from which a password reset email will be sent. */
define('COMPANYNAME', 'Your Company Name here'); 		/*Enter the Company Name. */
define('COMPANY_SHORTBANE', 'Your Company Short Name here');			/*Enter the Company Short Name. */

define('MINPASSLENGTH', '6'); 		/*Enter the Password Encryption md5, sha1, or none. */
define('PASSVALIDATION', 'none'); 		/*Enter the Password Encryption md5, sha1, or none. */
define('PASSENCRYPT', 'none'); 		/*Enter the Password Validation alpha, alphanumeric or alphanumeric + special. */
?>