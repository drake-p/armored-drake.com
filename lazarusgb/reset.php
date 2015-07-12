<?php
//
// Do not edit beyond this point
//

include "./admin/config.inc.php";
$error = '';

//
// Display any errors in our PHP
//

ini_set ('display_errors', 1);
error_reporting (E_ALL & ~E_NOTICE);

//
// Connect to server and database
//

if ($dbc = @mysql_connect ($GB_DB['host'] , $GB_DB['user'] , $GB_DB['pass'] ))
{
	if (!@mysql_select_db ($GB_DB['dbName']))
	{
		die ('<p>Could not select the database because: <b>'.mysql_error().'</b></p>');
	}
} 
else 
{
	die ('<p>Could not connect to MySQL because: <b>'.mysql_error().'</b></p>');
}
 echo ("<?xml version=\"1.0\"?>\n");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta name="generator" content="HTML Tidy for Windows (vers 12 April 2005), see www.w3.org" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>
      Password Reset
    </title>
      <style type="text/css">
      /*<![CDATA[*/
         <!--
         body { background: #36F; color: #CFF; font-family: arial,tahoma,sans-serif; font-size: 14px; text-align: center; }
         form { width: 340px; border: 1px dashed #039; background: #4AF; color: #245; }
         fieldset { border: 0; }
         h1 { font-size: 22px; letter-spacing: -3px; font-weight: lighter; }
         input { background: #9FF; border: 1px solid #000080; }
         #error { background: #FFC0CB; border: 1px dashed #F00; width: 500px; padding: 3px; color: #F00;}
         #message { text-align: left; width: 500px; margin: 0 auto 0 auto; padding: 3px; background: #039; border: 1px dashed #7BF;}
         -->
      /*]]>*/
      </style>
  </head>
  <body>
    <h1>GUESTBOOK PASSWORD RESET</h1>
<?php 

function show_form($mysqlpass = '', $newuser = '', $newpass = '', $sqlerror = '')
{
echo <<< HEREDOC
    <form action="reset.php" method="post">
      <fieldset>
        <legend>MySQL Password:</legend> <input type="text" name="mysqlpass" size="40" value="$mysqlpass"$sqlerror />
      </fieldset>
      <fieldset>
        <legend>New Username:</legend> <input type="text" name="newuser" size="40" value="$newuser" />
      </fieldset>
      <fieldset>
        <legend>New Password:</legend> <input type="text" name="newpass" size="40" value="$newpass" />
      </fieldset><br />
      <input type="submit" value="Submit Changes" name="submit" /> &nbsp; <input type="reset" value="Clear Form" /><br />
      <br />
    </form>
HEREDOC;
}

if (!isset($_POST['submit']))
{
?>
    <div id="message">
      This script will reset your guestbooks username and password.<br />
      <br />
      To use this script you will need to know your MySQL database password.<br />
      <br />
      Your MySQL password can be found in the file called config.inc.php located in the guestbooks
      admin folder.<br />
      <br />
      If you do not specify a new username or password then they will be set to the defaults of
      test and 123 respectively.
    </div>
<?php
show_form();
}
else
{
//
// Did they supply us with a password?
//

	if (!empty($_POST['mysqlpass']))
	{
	
//
// Is the password they supplied correct?
//
	
		if ($_POST['mysqlpass'] == $GB_DB['pass'])
		{
		
//
// If they didn't supply a username or password then set them to the defaults
//

         $newuser = (empty($_POST['newuser'])) ? 'test' : $_POST['newuser'];
         $newpass = (empty($_POST['newpass'])) ? '123' : $_POST['newpass'];
		
//
//Lets define our MySQL query and then run it
//

			$query = "UPDATE ".$GB_TBL['auth']." SET username='$newuser', password=PASSWORD('$newpass') WHERE id=1";
			if (@mysql_query($query))
			{
?>
    <div id="message">
      <center>
        <strong>Username and password successfully changed</strong><br />
        <br />
        Your new username is:<br />
        <strong><?php echo $newuser; ?></strong><br />
        <br />
        Your new password is:<br />
        <strong><?php echo $newpass; ?></strong><br />
      </center>
    </div>
<?php 
			}
			else
			{
?>
    <div id="error">
      <strong>ERROR!!!</strong><br />
      <br />
      The script encountered an error when it tried to update the login details.<br />
      <br />
      The query the script tried to run is:<br />
      <i><?php echo $query; ?></i><br />
      <br />
      The error encountered is:<br />
      <i><?php echo mysql_error(); ?></i><br />
    </div>
<?php 			
			}
		}
		else
		{
?>
    <div id="error">
      <strong>THE MySQL PASSWORD YOU SUPPLIED IS INCORRECT!</strong>
    </div>
<?php 
show_form($_POST['pass'], $_POST['newuser'], $_POST['newpass'], ' style="background:#FFC0CB;"');
		}
	}
	else
	{
?>
    <div id="error">
      <strong>YOU FORGOT TO SUPPLY YOUR MySQL PASSWORD!</strong>
    </div>
<?php 
show_form($_POST['pass'], $_POST['newuser'], $_POST['newpass'], ' style="background:#FFC0CB;"');	
	}
}
?>

  </body>
</html>