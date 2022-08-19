<?php

/*
 **************************************
 *
 * install/update.php
 * -------------
 *
 * last modified:	2005-07-13
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


if( @ini_get( 'register_globals' ) )
{
	foreach ( $_REQUEST as $var_name => $void )
	{
		unset( ${$var_name} );
	}
}


$script_version = '3.1.1';

define( 'CHC_ROOT', dirname( dirname( __FILE__ ) ) );
require_once( '../includes/config.inc.php' );
require_once( '../includes/common.inc.php' );
require_once( '../includes/mysql.class.php' );
require_once( '../includes/functions.inc.php' );
require_once( './install_functions.inc.php' );

$_CHC_DB = new chC_mysql( $_CHC_DBCONFIG['server'], $_CHC_DBCONFIG['user'], $_CHC_DBCONFIG['password'], $_CHC_DBCONFIG['database'] );
$_CHC_CONFIG = chC_get_config();


ob_start();
require_once( '../languages/'. $_CHC_CONFIG['lang_administration'] .'/lang_config.inc.php' );
require_once( '../languages/'. $_CHC_CONFIG['lang_administration'] .'/install.lang.php' );
ob_end_clean();

$title = sprintf( $_CHC_LANG['title_of_update_script'], $script_version );

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
  .success
   {
	color: #009900;
	font-weight: bold;
   }
   .error_msg
   {
	color: #FF0000;
   }

   .db_error_message
   {
	font-family: Courier;
	font-size: 8pt;
   }
  </style>
 </head>
 <body>
  <div class="main_box">
   <div class="header"><b><?php print $title; ?></b></div>
   <div class="content">
<?php
if( !isset( $_POST['update_now'] ) )
{
	print sprintf( $_CHC_LANG['update_welcome_message'], $script_version ) ."<br />\n<br />\n";
	if( !isset( $_CHC_CONFIG['script_version'] ) || version_compare( '3.0.0', $_CHC_CONFIG['script_version'], '>' ) == 1 ) // version_compare eigentlich gar nicht notwendig, da chC_get_config etc erst seit 3.0.0, sieht aber schöner aus^^
	{
		print "<br />\n". sprintf( $_CHC_LANG['message_update_from_versions_lower_than_3.0.0'], $script_version ) ."<br />\n<br />\n";
	}
	else
	{
		$string = @implode( '', @file( 'http://www.christoph-bachner.net/php-scripts/chCounter/get_current_chcounter_version.php' ) );
		if( $string == TRUE && is_string( $string ) && !empty( $string )  )
		{
			if( version_compare( $script_version, $string, '<' ) == 1 )
			{
				print $_CHC_LANG['higher_chcounter_version_available'] ."<br />\n<br />\n";
			}
		}
		print $_CHC_LANG['backup_message'] ."\n";
		?>
    <br />
    <br />
    <form method="post" action="update.php">
     <input type="hidden" name="update_now" value="1" />
     <input type="submit" value="<?php print $_CHC_LANG['continue']; ?>" />
    </form>
<?php
	}
}
else
{
	$_CHC_DB->set_debug_mode( 'OFF' );
	
	if( version_compare( $_CHC_CONFIG['script_version'], '3.0.1', '<' ) == 1 )
	{
		chC_execute_sql_file( './mysql/300_to_301.sql' );

		$result = $_CHC_DB->query(
			'SELECT
				SUM(anzahl) as anzahl_robots
			FROM `'. CHC_TABLE_USER_AGENTS ."`
			WHERE typ = 'robot'"
		);
		$row = $_CHC_DB->fetch_assoc( $result );
		if( intval( $row['anzahl_robots'] ) > 0 )
		{
			$_CHC_DB->query(
				'UPDATE `'. CHC_TABLE_USER_AGENTS .'`
				SET anzahl = IF(anzahl > '. $row['anzahl_robots'] .', anzahl - '. $row['anzahl_robots'] .", anzahl)
				WHERE typ = 'os' AND wert = 'unknown';"
			);
		}

		chC_set_config( 'script_version', '3.0.1pl1' );
	}
	
	if( version_compare( $_CHC_CONFIG['script_version'], '3.0.2', '<' ) == 1 )
	{
		$result = $_CHC_DB->query(
			'SELECT timestamp
			FROM `'. CHC_TABLE_ACCESS ."`
			WHERE typ = 'tageszeit_wochentag_start';"
		);
		$row = $_CHC_DB->fetch_assoc( $result );
		$_CHC_DB->query(
			'UPDATE `'. CHC_TABLE_ACCESS .'`
			SET timestamp = '. mktime(
				0,
				0,
				0,
				date( 'n', $row['timestamp'] ),
				date( 'j', $row['timestamp'] ),
				date( 'Y', $row['timestamp'] )
			) ."
			WHERE typ = 'tageszeit_wochentag_start';"
		);
		
		$result = $_CHC_DB->query(
			'SELECT typ, wert, anzahl, timestamp
			FROM `'. CHC_TABLE_USER_AGENTS ."`
			WHERE
				`typ` LIKE 'version~robot'
				AND wert LIKE '%others%';"
		);
		while( $row = $_CHC_DB->fetch_assoc( $result ) )
		{
			$row['wert'] = str_replace( 'others', 'other', $row['wert'] );
			$_CHC_DB->query(			
				'UPDATE `'. CHC_TABLE_USER_AGENTS .'`
				SET anzahl = anzahl + '. $row['anzahl'] ."
				WHERE typ = '". $row['typ'] ."' and wert = '". $row['wert'] ."'
				LIMIT 1;"
			);
			if( $_CHC_DB->affected_rows() == 0 )
			{
				$_CHC_DB->query(			
					'INSERT INTO `'. CHC_TABLE_USER_AGENTS ."`
					( typ, wert, anzahl, timestamp )
					VALUES ( '". $row['typ'] ."', '". $row['wert'] ."', ". $row['anzahl'] .', '. $row['timestamp'] .' );'
				);
			}
		}
		$_CHC_DB->query(
			'DELETE FROM `'. CHC_TABLE_USER_AGENTS ."`
			WHERE
				( `typ` LIKE 'version~robot' AND wert LIKE '%others%' )
				OR ( typ = 'robot' AND wert = 'others');"
		);

		chC_set_config( 'script_version', '3.0.2' );
	}
	
	
	if( version_compare( $_CHC_CONFIG['script_version'], '3.0.3', '<' ) == 1 )
	{
		chC_set_config( 'script_version', '3.0.3' );
	}

	if( version_compare( $_CHC_CONFIG['script_version'], '3.1.0', '<' ) == 1 )
	{
        	$tabellen = array(
			CHC_TABLE_SCREEN_RESOLUTIONS,
			CHC_TABLE_USER_AGENTS,
			CHC_TABLE_SEARCH_ENGINES,
			CHC_TABLE_REFERRERS,
			CHC_TABLE_LOCALE_INFORMATION,
			CHC_TABLE_PAGES
		);
		foreach( $tabellen as $tabelle )
		{
		 	$_CHC_DB->query(
		 		'ALTER TABLE `'. $tabelle .'`
		 		DROP PRIMARY KEY;'
		 	);
		}
		$_CHC_DB->reset_errors();
		chC_execute_sql_file( './mysql/303_to_310.sql' );
		chC_set_config( 'script_version', '3.1.0' );

		chC_update_user_agent_version_entries( 'robot', 'Mediapartners-Google', 'Google Adsense' );
	}


	if( version_compare( $_CHC_CONFIG['script_version'], '3.1.1', '<' ) == 1 )
	{
		chC_set_config( 'script_version', '3.1.1' );
	}




	$db_updatefehler = $_CHC_DB->get_errors();
	
	if( count( $db_updatefehler ) > 0 )
	{
		print '- <span class=error_msg">'. $_CHC_LANG['error(s)_occurred'] ."</span><br />\n"
			.'&nbsp;&nbsp;&nbsp;<i>'. $_CHC_LANG['db_error_messages:'] ."</i><br />\n";
		foreach( $db_updatefehler as $array )
		{
			print "<span class=\"db_error_message\">&nbsp;&nbsp;".$array['error']."</span><br />\n";
		}
	}
	else
	{
		print '<span class="success">'. sprintf( $_CHC_LANG['update_finished'], $script_version ) ."</span><br />\n<br />\n"
		."<br />\n". $_CHC_LANG['remove_install_directory'];
	}
}
?>
   </div>
   <div class="footer">&nbsp;</div>
  </div>
 </body>
</html>
