<?php

/*
 **************************************
 *
 * install/install.php
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


if( !is_file( './mysql/tables.sql' ) )
{
	die( 'File install/mysql/tables.sql does not exist!' );
}
elseif( !is_file( './mysql/data.sql' ) )
{
	die( 'File install/mysql/data.sql does not exist!' );
}


$script_version = '3.1.1';

define( 'CHC_ROOT', dirname( dirname( __FILE__ ) ) );
require_once( '../includes/config.inc.php' );
require_once( '../includes/common.inc.php' );
require_once( '../includes/mysql.class.php' );
require_once( '../includes/functions.inc.php' );
require_once( './install_functions.inc.php' );

$_CHC_DB = new chC_mysql( $_CHC_DBCONFIG['server'], $_CHC_DBCONFIG['user'], $_CHC_DBCONFIG['password'], $_CHC_DBCONFIG['database'], 'DEBUG_OFF' );


// Sprachdateien einbinden
$available_languages = chC_get_available_languages( CHC_ROOT .'/languages' );
if( isset( $_POST['lng'] ) && isset( $available_languages[$_POST['lng']] ) )
{
	$lang = $_POST['lng'];
}
else
{
	$lang = chC_get_language_to_use( $available_languages );
}
ob_start();
require( '../languages/'. $lang .'/lang_config.inc.php' );
require( '../languages/'. $lang .'/install.lang.php' );
ob_end_clean();

$title = 'chCounter '. $script_version .' - '. $_CHC_LANG['installation'];


$installations_schritt = !isset( $_POST['installations_schritt'] ) ? '1' : $_POST['installations_schritt'];

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
   .critical_error
   {
	color: #FF0000;
	font-weight: bold;
   }
   .db_error_message
   {
	font-family: Courier;
	font-size: 8pt;
   }
   .centered_message_box
   {
	width: 500px;
	margin: 10px auto 0px auto;
	text-align: justify;
   }
  </style>
 </head>
 <body>
  <div class="main_box">
   <div class="header"><b><?php print $title; ?></b></div>
   <div class="content">
    <div style="text-align: center;"><?php print sprintf( $_CHC_LANG['installation_step'], $installations_schritt ); ?></div>
    <hr />
    <br />
    <br />
<?php
if( $installations_schritt == '1' )
{
?>
    <div style="text-align: center">
	<?php
	print '<b>'. $_CHC_LANG['welcome_message'] ."</b><br />\n<br />\n";

	if( version_compare( phpversion(), '4.2.0' ) == -1 )
	{
		print '<div class="centered_message_box">'. sprintf( $_CHC_LANG['error:higher_php_version_needed'], phpversion() ) .'</div>';
	}
	else
	{
		print $_CHC_LANG['please_choose_install_language'];
	?>
     <br />
     <br />
     <form method="post" action="install.php">
      <input type="hidden" name="installations_schritt" value="2" />
      <select name="lng">
	<?php
		foreach( $available_languages as $lang_code => $lang_name )
		{
			print '<option value="'. $lang_code .'">'. $lang_name ."</option>\n";
		}
	?>
      </select>
      <input type="submit" value="<?php print $_CHC_LANG['continue']; ?>" />
     </form>
     <?php
	}
	?>
     <br />
    </div>
<?php
}
elseif( $installations_schritt == '2' )
{
	$string = @implode( '', @file( 'http://www.christoph-bachner.net/php-scripts/chCounter/get_current_chcounter_version.php' ) );
	if( $string == TRUE && is_string( $string ) && !empty( $string )  )
	{
		if( version_compare( $script_version, $string, '<' ) == 1 )
		{
			print $_CHC_LANG['higher_chcounter_version_available'] ."<br />\n";
		}
	}

	if( $_CHC_DB->is_connected() == FALSE )
	{
		print '<div class="centered_message_box">'. $_CHC_LANG['error:could_not_connect_to_the_database'] . '</div><br /><br />';
	}
	else
	{
		?>
    <br />
    <br />
    <br />
    <form action="install.php" method="post">
     <input type="hidden" name="installations_schritt" value="3" />
     <input type="hidden" name="lng" value="<?php print $_POST['lng']; ?>" />
     <fieldset style="width: 90%;">
      <legend><?php print $_CHC_LANG['homepage_url']; ?></legend>
      <br />
	<?php
	print $_CHC_LANG['description_homepage_url'];
	?>
      <br />
      <br />
      <input type="text" name="default_homepage_url" size="40" value="<?php
	print !isset( $_POST['default_homepage_url'] ) ? 'http://'. $_SERVER['SERVER_NAME'] : $_POST['default_homepage_url'];
	print '"';
	if( isset( $_POST['fehler_homepage_url'] ) )
	{
		print ' class="error_msg"';
	}
	?> />
     </fieldset>
     <br />
     <br />
     <br />
     <fieldset style="width: 90%;">
      <legend><?php print $_CHC_LANG['admin_account']; ?></legend>
       <br />
	<?php
	print $_CHC_LANG['description_admin_account'];
	?><br />
      <br />
      <div style="margin-bottom: 2px;">
       <div style="float: left; width: 150px;">
	<?php print $_CHC_LANG['login_name:']; ?>
       </div>
       <div>
	<input type="text" name="login_name" value="<?php
	if( isset( $_POST['login_name'] ) )
	{
		print $_POST['login_name'];
	}
	print '"';
	if( isset( $_POST['fehler_login_name'] ) )
	{
		print ' class="error_msg"';
	}
	?> />
       </div>
      </div>
      <div style="margin-bottom: 2px;">
       <div style="float: left; width: 150px;">
	<?php print $_CHC_LANG['password:']; ?>
       </div>
       <div>
	<input type="password" name="passwort" value="<?php
	if( isset( $_POST['passwort'] ) )
	{
		print $_POST['passwort'];
	}
	print '"';
	if( isset( $_POST['fehler_passwort'] ) )
	{
		print ' class="error_msg"';
	}
	?> />
       </div>
      </div>
      <div>
       <div style="float: left; width: 150px;">
	<?php print $_CHC_LANG['confirm_password:']; ?>
       </div>
       <div>
	<input type="password" name="passwort_2" value="<?php
	if( isset( $_POST['passwort_2'] ) )
	{
		print $_POST['passwort_2'];
	}
	print '"';
	if( isset( $_POST['fehler_passwort_2'] ) )
	{
		print ' class="error_msg"';
	}
	?> />
       </div>
      </div>
     </fieldset>
     <br />
     <br />
     <input type="submit" value="<?php print $_CHC_LANG['continue']; ?>" />
    </form>
    <br />
<?php
	}
}
elseif( $installations_schritt == '3' )
{
	$fehler = array();

	if( empty( $_POST['login_name'] ) )
	{
		$feld_leer = TRUE;
		$fehler[] = 'fehler_login_name';
	}
	if( empty( $_POST['passwort'] ) )
	{
		$feld_leer = TRUE;
		$fehler[] = 'fehler_passwort';
	}
	if( empty( $_POST['passwort_2'] ) )
	{
		$feld_leer = TRUE;
		$fehler[] = 'fehler_passwort_2';
	}
	if( empty( $_POST['default_homepage_url'] ) )
	{
		$feld_leer = TRUE;
		$fehler[] = 'fehler_default_homepage_url';
	}

	if( $_POST['passwort'] != $_POST['passwort_2'] )
	{
		$passwoerter_nicht_gleich = TRUE;
		$fehler[] = 'fehler_passwort';
		$fehler[] = 'fehler_passwort_2';
	}

	$tabellen = array(
		strtolower( CHC_TABLE_CONFIG ),
		strtolower( CHC_TABLE_DOWNLOADS_AND_HYPERLINKS ),
		strtolower( CHC_TABLE_DOWNLOADS_AND_HYPERLINKS_LOGS ),
		strtolower( CHC_TABLE_DATA ),
		strtolower( CHC_TABLE_LOG_DATA ),
		strtolower( CHC_TABLE_COUNTED_USERS ),
		strtolower( CHC_TABLE_IGNORED_USERS ),
		strtolower( CHC_TABLE_ONLINE_USERS ),
		strtolower( CHC_TABLE_ACCESS ),
		strtolower( CHC_TABLE_SCREEN_RESOLUTIONS ),
		strtolower( CHC_TABLE_USER_AGENTS ),
		strtolower( CHC_TABLE_SEARCH_ENGINES ),
		strtolower( CHC_TABLE_REFERRERS ),
		strtolower( CHC_TABLE_LOCALE_INFORMATION ),
		strtolower( CHC_TABLE_PAGES )
	);

	$result = $_CHC_DB->query( 'SHOW TABLES;' );
	while( $row = $_CHC_DB->fetch_assoc( $result ) )
	{
		if( in_array( strtolower( $row['Tables_in_'. $_CHC_DBCONFIG['database']] ), $tabellen ) )
		{
			$tabellen_vorhanden = TRUE;
			break;
		}
	}

	if( isset( $tabellen_vorhanden ) )
	{
		print $_CHC_LANG['please_uninstall_first_the_old_installation'] ."<br />\n<br />\n";
	}
	elseif( count( $fehler ) > 0 )
	{
		$fehler = array_unique( $fehler );

		print count( $fehler == 1 ) ? $_CHC_LANG['the_following_error_occurred:'] : $_CHC_LANG['the_following_errors_occurred:'];
		print "<br />\n";
		print "<ul>\n";
		if( isset( $feld_leer ) )
		{
			print '<li>'. $_CHC_LANG['fill_out_all_fields'] ."</li>\n";
		}
		if( isset( $passwoerter_nicht_gleich ) )
		{
			print "<li>".$_CHC_LANG['the_passwords_do_not_match']."</li>\n";
		}
		print "</ul>\n<br />\n";
	}


	if(
		isset( $tabellen_vorhanden )
		|| count( $fehler ) > 0
	  )
	{
		print "    <form action=\"\" method=\"POST\">\n<br />\n";

		if( count( $fehler ) > 0 )
		{
			foreach( $fehler as $name )
			{
				print "     <input type=\"hidden\" name=\"".$name."\" value=\"1\" />\n";
			}
		}

		print "     <input type=\"hidden\" name=\"installations_schritt\" value=\"2\">\n"
			."     <input type=\"hidden\" name=\"login_name\" value=\"".$_POST['login_name']."\">\n"
			."     <input type=\"hidden\" name=\"default_homepage_url\" value=\"".$_POST['default_homepage_url']."\">\n"
			."     <input type=\"hidden\" name=\"lng\" value=\"".$_POST['lng']."\">\n"
			."     <input type=\"submit\" value=\"&lt;&lt; ".$_CHC_LANG['back']."\">\n"
			."    </form>\n";
	}
	else
	{
		print '- '. $_CHC_LANG['db_connection_available'] ."<br />\n";


		$sql_string = implode( '', file( './mysql/tables.sql') );
		$sql_string = preg_replace( '/\{(CHC_TABLE_[_A-Z]+)\}/e', '\\1', $sql_string );

		$sql_queries = chC_split_sql_queries( $sql_string );

		foreach( $sql_queries as $query )
		{
			$_CHC_DB->query( $query );
		}

		$db_installationsfehler = $_CHC_DB->get_errors();

		if( count( $db_installationsfehler ) > 0 )
		{
			print '- <span class=error_msg">'. $_CHC_LANG['error_could_not_create_tables'] ."</span><br />\n"
				.'&nbsp;&nbsp;&nbsp;<i>'. $_CHC_LANG['db_error_messages:'] ."</i><br />\n";
			foreach( $db_installationsfehler as $array )
			{
				print "<span class=\"db_error_message\">&nbsp;&nbsp;".$array['error']."</span><br />\n";
			}
		}
		else
		{
			print '- '. $_CHC_LANG['tables_successfully_created'] ."<br />\n";

			if( !preg_match( '#^(http|https|ftp)://#i', $_POST['default_homepage_url'] ) )
			{
				$_POST['default_homepage_url'] = 'http://'. $_POST['default_homepage_url'];
			}

			$sql_string = implode( '', file( './mysql/data.sql') );
			$sql_string = preg_replace( '/\{(CHC_TABLE_[_A-Z]+)\}/e', '\\1', $sql_string );

			$array_search = array(
				'{ADMIN_NAME}',
				'{ADMIN_PASSWORD}',
				'{LANGUAGE}',
				'{DEFAULT_COUNTER_URL}',
				'{DEFAULT_HOMEPAGE_URL}',
				'{SCRIPT_VERSION}',
				'{EXCLUSION_LIST_REFERRERS}',
				'{TIMESTAMP}',
				'{SESSION_NAME}',
				'{TIMESTAMP_THIS_DAY}',
				'{TIMESTAMP_THIS_WEEK}',
				'{TIMESTAMP_THIS_MONTH}',
				'{TIMESTAMP_THIS_YEAR}',
				'{DST}'
			);
			$array_replace = array(
				$_POST['login_name'],
				md5( $_POST['passwort'] ),
				$lang,
				'http://'. $_SERVER['SERVER_NAME'] .dirname( dirname( $_SERVER['PHP_SELF'] ) ),
				strtolower( $_POST['default_homepage_url'] ),
				$script_version,
				preg_replace( '#http(s?)://(www\.)?#', 'http\\1://', $_POST['default_homepage_url'] ) .'%; '
					. preg_replace( '#http(s?)://(www\.)?#', 'http\\1://www.', $_POST['default_homepage_url'] ) .'%; '
					.'http://www.example.tld%; '
					.'%blocked by Outpost%',
				time(),
				session_name(),
				chC_get_timestamp( 'tag' ),
				chC_get_timestamp( 'kw' ),
				chC_get_timestamp( 'monat' ),
				chC_get_timestamp( 'jahr' ),
				date( 'I' )
			);
			$sql_string = str_replace( $array_search, $array_replace, $sql_string );

			$sql_queries = chC_split_sql_queries( $sql_string );
			foreach( $sql_queries as $query )
			{
				$_CHC_DB->query( $query );
			}

			$db_installationsfehler = $_CHC_DB->get_errors();
			if( count( $db_installationsfehler ) > 0 )
			{
				print '- <span class="error_msg">'. $_CHC_LANG['error_could_not_insert_data'] ."</span><br />\n"
					.'&nbsp;&nbsp;&nbsp;<i>'. $_CHC_LANG['db_error_messages:'] ."</i><br />\n";
				foreach( $db_installationsfehler as $array )
				{
					print '<span style="font-family: Courier">&nbsp;&nbsp;'. $array['error'] ."</span><br />\n";
				}
			}
			else
			{
				print '- '. $_CHC_LANG['data_successfully_inserted'] ."<br />\n<br />\n";
				print $_CHC_LANG['installation_finished'] ."<br />\n<br />\n"
					."<br />\n". $_CHC_LANG['remove_install_directory'];
			}
		}
	}
}
?>
   </div>
   <div class="footer">&nbsp;</div>
  </div>
 </body>
</html>
