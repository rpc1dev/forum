<?php

/*
 **************************************
 *
 * install/set_new_login_data.php
 * -------------
 *
 * last modified:	2005-04-21
 * -------------
 *
 * project:	chCounter
 * version:	3.0.0
 * copyright:	© 2005 Christoph Bachner
 * license:	GPL vs2.0 [ see docs/license.txt ]
 * contact:	www.christoph-bachner.net
 *
 **************************************
*/


error_reporting( E_ALL );
set_magic_quotes_runtime(0);
header( 'Content-Type: text/html; charset=UTF-8' ); 


if( basename( getcwd() ) != 'install' )
{
	die( 'This script must be executed within the "install" directory!<br />Script aborted.' );
}


require( '../includes/config.inc.php' );
require( '../includes/common.inc.php' );
require( '../includes/mysql.class.php' );
require( '../includes/functions.inc.php' );

$_CHC_DB = new chC_mysql( $_CHC_DBCONFIG['server'], $_CHC_DBCONFIG['user'], $_CHC_DBCONFIG['password'], $_CHC_DBCONFIG['database'] );

$_CHC_CONFIG = chC_get_config();

print "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
 <head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <meta http-equiv="content-language" content="en" />
  <meta name="author" content="Christoph Bachner" />
  <title>chCounter <?php print $_CHC_CONFIG['script_version']; ?> - Set a new administrator login name/password</title>
 </head>
 <body>
  <b>chCounter <?php print $_CHC_CONFIG['script_version']; ?> - Set a new administrator login name/password</b><br /><br />
<?php
if(
	isset( $_POST['name'] )
	&& isset( $_POST['pw1'] )
	&& isset( $_POST['pw2'] )
  )
{
	if(
		empty( $_POST['name'] )
		|| empty( $_POST['pw1'] )
		|| empty( $_POST['pw2'] )
	)
	{
		print "<span style=\"color: #FF0000;\">Please fill out every field!</span><br />\n<br />\n";
	}
	elseif( $_POST['pw1'] != $_POST['pw2'] )
	{
	 	print "<span style=\"color: #FF0000;\">The passwords you entered did not match.</span><br />\n<br />\n";
	}
	else
	{
	 	$config_set = TRUE;
		chC_set_config( 'admin_name', $_POST['name'] );
		chC_set_config( 'admin_passwort', md5( $_POST['pw1'] ) );
		print "Update successful!<br />\n You may login now to the <a href=\"../administration/index.php\">Administration Area</a> using the new login name/password.";
	}
}


if( !isset( $config_set ) )
{
	?>
  If you have lost your login name and your password, this script will help you as it writes a new name and a new password into the database.<br />
  Simply fill out the following form:<br />
  <br />
  <form method="post" action="set_new_login_data.php">
  <div style="float: left; margin-right: 20px;">
   New login name:<br />
   New password:<br />
   Confirm password:<br />
  </div>
  <div>
   <input type="text" name="name" value="<?php print isset( $_POST['name'] ) ? $_POST['name'] : ''; ?>" /><br />
   <input type="password" name="pw1" value="" /><br />
   <input type="password" name="pw2" value="" /><br />
  </div>
  <input type="submit" value="Set new login data" />
  </form>
	<?php
}

?>
  <br />
  <br />
  <span style="font-size: smaller; line-height: 100%;">
  Powered by <a href="http://www.christoph-bachner.net/" target="_blank">chCounter <?php print $_CHC_CONFIG['script_version']; ?></a>.<br />
  </span>
 </body>
</html>