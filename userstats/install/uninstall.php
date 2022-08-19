<?php

/*
 **************************************
 *
 * install/uninstall.php
 * -------------
 *
 * last modified:	2005-06-13
 * -------------
 *
 * project:	chCounter
 * version:	3.1.1
 * copyright:	© 2005 Christoph Bachner
 * license:	GPL vs2.0 or higher [ see docs/license.txt ]
 * contact:	www.christoph-bachner.net
 *
 **************************************
*/


error_reporting(E_ALL);
set_magic_quotes_runtime(0);
header( 'Content-Type: text/html; charset=UTF-8' ); 


define( 'CHC_ROOT', dirname( dirname( __FILE__ ) ) );
require_once( '../includes/config.inc.php' );
require_once( '../includes/common.inc.php' );
require_once( '../includes/mysql.class.php' );
require_once( '../includes/functions.inc.php' );

$_CHC_DB = new chC_mysql( $_CHC_DBCONFIG['server'], $_CHC_DBCONFIG['user'], $_CHC_DBCONFIG['password'], $_CHC_DBCONFIG['database'], 'DEBUG_OFF' );

$_CHC_CONFIG = chC_get_config();
ob_start();
require_once( '../languages/'. $_CHC_CONFIG['lang_administration'] .'/lang_config.inc.php' );
require_once( '../languages/'. $_CHC_CONFIG['lang_administration'] .'/install.lang.php' );
ob_end_clean();

$title = 'chCounter '. $_CHC_CONFIG['script_version'] .' - '. $_CHC_LANG['uninstallation'];

print '<?xml version="1.0" encoding="UTF-8"?>'."\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title><?php print $title; ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="author" content="Christoph Bachner" />
  <link rel="stylesheet" type="text/css" href="../administration/style.css" />
  <style type="text/css">
   .db_error_message
   {
	font-family: Courier;
	font-size: 8pt;
   }
  .success
   {
	color: #009900;
	font-weight: bold;
   }   
  </style>
 </head>
 <body>
  <div class="main_box">
   <div class="header"><b><?php print $title; ?></b></div>
   <div class="content">
    <?php
if( isset( $_POST['uninstall_now'] ) )
{
	$_CHC_DB->query(
		'DROP TABLE `'. CHC_TABLE_CONFIG .'`,
			`'. CHC_TABLE_DATA .'`,
			`'. CHC_TABLE_DOWNLOADS_AND_HYPERLINKS .'`,
			`'. CHC_TABLE_DOWNLOADS_AND_HYPERLINKS_LOGS .'`,
			`'. CHC_TABLE_LOG_DATA .'`,
			`'. CHC_TABLE_COUNTED_USERS .'`,
			`'. CHC_TABLE_ONLINE_USERS .'`,
			`'. CHC_TABLE_IGNORED_USERS .'`,
			`'. CHC_TABLE_ACCESS .'`,
			`'. CHC_TABLE_SCREEN_RESOLUTIONS .'`,
			`'. CHC_TABLE_USER_AGENTS .'`,
			`'. CHC_TABLE_SEARCH_ENGINES .'`,
			`'. CHC_TABLE_REFERRERS .'`,
			`'. CHC_TABLE_LOCALE_INFORMATION .'`,
			`'. CHC_TABLE_PAGES .'`;'
	);
	$fehler = $_CHC_DB->get_errors();
	if( count( $fehler ) > 0 )
	{
		print $_CHC_LANG['the_following_error_occurred:'] ."<br />\n";
		print "<ul>\n";
		print ' <li><span class="db_error_message">'. $fehler[0]['error'] ."</span></li>\n";
		print "</ul>\n<br />\n<br />\n";
		print '<b>' .$_CHC_LANG['could_not_delete_database_tables'] .'</b>';
	}
	else
	{
		print '<span class="success">'. $_CHC_LANG['database_tables_successfully_deleted'] .'</span>';
	}
}
else
{
	print $_CHC_LANG['uninstall_information'];
	?><br />
	<br />
	<form action="uninstall.php" method="post">
	 <input type="checkbox" name="uninstall_now" value="1" id="uninstall" /><label for="uninstall"> <?php print $_CHC_LANG['uninstall_now']; ?></label><br />
	 <br />
	 <input type="submit" value="<?php print $_CHC_LANG['continue']; ?>" />
	</form>
	<?php
}
    ?>
   </div>
   <div class="footer">&nbsp;</div>
  </div>
 </body>
</html>
