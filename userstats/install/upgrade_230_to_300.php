<?php

/*
 **************************************
 *
 * install/upgrade_230_to_300.php
 * -------------
 *
 * last modified:	2005-04-21
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


if( !is_file( './mysql/230_to_300.sql' ) )
{
	die( 'File install/mysql/230_to_300.sql does not exist!' );
}


$script_version = '3.0.0';

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
require_once( '../languages/'. $lang .'/lang_config.inc.php' );
require_once( '../languages/'. $lang .'/install.lang.php' );
ob_end_clean();

$title = $_CHC_LANG['upgrade_from_230_to_300'];

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
    <div class="centered_message_box">
<?php
if( !isset( $_POST['lng'] ) )
{
	?>
     <div style="text-align: center; width: 500px;">
	<?php
	print $_CHC_LANG['please_choose_install_language'];
	?>
     <br />
     <br />
     <form method="post" action="upgrade_230_to_300.php">
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
    </div>
	<?php
}
elseif( !isset( $_POST['upgrade_now'] ) )
{
	print sprintf( $_CHC_LANG['upgrade_welcome_message'], $script_version ) ."<br />\n<br />\n";

	$string = @implode( '', @file( 'http://www.christoph-bachner.net/php-scripts/chCounter/get_current_chcounter_version.php' ) );
	if( $string == TRUE && is_string( $string ) && !empty( $string )  )
	{
		if( version_compare( $script_version, $string, '<' ) == 1 )
		{
			print $_CHC_LANG['higher_chcounter_version_available'] ."<br />\n<br />\n";
		}
	}

	if( version_compare( phpversion(), '4.1.0' ) == -1 )
	{
		print sprintf( $_CHC_LANG['error:higher_php_version_needed'], phpversion() );
	}
	else
	{
		print $_CHC_LANG['message_old_config_file_needed'] ."<br />\n<br />\n";
		print $_CHC_LANG['backup_message'] ."\n";
		?>
    <br />
    <br />
    <br />
    <form method="post" action="upgrade_230_to_300.php">
    <input type="checkbox" name="backup_tabellen" value="1" checked /> <?php print $_CHC_LANG['create_temporary_backup_tables']; ?><br />
    <?php print $_CHC_LANG['description_backup_tables']; ?><br />
    <br />
    <br />
    <?php
		if( count( $_CHC_DB->get_errors() ) > 0 )
		{
			print $_CHC_LANG['error:could_not_connect_to_the_database'] . '<br /><br />';
		}
		else
		{
			?>
     <input type="hidden" name="upgrade_now" value="1" />
     <input type="hidden" name="lng" value="<?php print $_POST['lng']; ?>" />
     <input type="submit" value="<?php print $_CHC_LANG['continue']; ?>" />
    </form>
<?php
		}
	}
}
else
{
	if( !file_exists( './old_config.inc.php' ) )
	{
		print $_CHC_LANG['error:old_config_file_not_available'];
	}
	else
	{
		require( './old_config.inc.php' );

		$alte_tabellen = array(
			$GLOBALS['chC']['dbconfig']['table_config'],
			$GLOBALS['chC']['dbconfig']['table_data'],
			$GLOBALS['chC']['dbconfig']['table_log_data'],
			$GLOBALS['chC']['dbconfig']['table_onlineusers'],
			$GLOBALS['chC']['dbconfig']['table_blockedusers'],
			$GLOBALS['chC']['dbconfig']['table_referers'],
			$GLOBALS['chC']['dbconfig']['table_useragents'],
			$GLOBALS['chC']['dbconfig']['table_pages'],
			$GLOBALS['chC']['dbconfig']['table_access'],
			$GLOBALS['chC']['dbconfig']['table_countries'],
			$GLOBALS['chC']['dbconfig']['table_resolutions'],
			$GLOBALS['chC']['dbconfig']['table_searchwords']
		);

		$result = $_CHC_DB->query( 'SHOW TABLES;' );
		$vorhandene_tabellen = array();
		while( $row = $_CHC_DB->fetch_assoc( $result ) )
		{
			$vorhandene_tabellen[] = strtolower( $row['Tables_in_'. $_CHC_DBCONFIG['database']] );
		}
		foreach( $alte_tabellen as $tabelle )
		{
			if( !in_array( strtolower( $tabelle ), $vorhandene_tabellen ) )
			{
				$fehler_tabelle_nicht_vorhanden = TRUE;
			}
		}

		if( isset( $fehler_tabelle_nicht_vorhanden ) )
		{
			print $_CHC_LANG['error:could_not_find_db_tables'];
		}
		else
		{
			if( isset( $_POST['backup_tabellen'] ) )
			{
				chC_upgrade_backup_copy_tables( '', 'chc_backup_' );

				$backupfehler = $_CHC_DB->get_errors();
				if( count( $backupfehler ) > 0 )
				{
					print '- <span class="error_msg">'. $_CHC_LANG['could_not_create_backup'] ."</span><br />\n"
						.'&nbsp;&nbsp;&nbsp;<i>'. $_CHC_LANG['db_error_messages:'] ."</i><br />\n";
					foreach( $backupfehler as $array )
					{
						print '<span class="db_error_message">&nbsp;&nbsp;'. $array['error'] ."</span><br />\n";
					}

					$_CHC_DB->query(
						'DROP TABLE IF EXISTS
							`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_config'] .'`,
							`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_data'] .'`,
							`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_log_data'] .'`,
							`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_onlineusers'] .'`,
							`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_blockedusers'] .'`,
							`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_referers'] .'`,
							`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_useragents'] .'`,
							`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_pages'] .'`,
							`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_access'] .'`,
							`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_countries'] .'`,
							`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_resolutions'] .'`,
							`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_searchwords'] .'`;'
					);
					?>
   </div>
   </div>
   <div class="footer">&nbsp;</div>
  </div>
 </body>
</html>
<?php
					exit;
				}
			}


			$result = $_CHC_DB->query( 'SELECT * FROM `'. $GLOBALS['chC']['dbconfig']['table_config'] .'`' );
			$alte_config = $_CHC_DB->fetch_assoc( $result );

			$result = $_CHC_DB->query( 'SELECT counterstart FROM `'. $GLOBALS['chC']['dbconfig']['table_data'] .'`' );
			$counterstart_alt = $_CHC_DB->fetch_assoc( $result );
			$counterstart_alt = $counterstart_alt['counterstart'];

			if( !isset( $alte_config['script_version'] ) )
			{
				print $_CHC_LANG['error:update_first_to_230'];
			}
			else
			{
				$sql_string = implode( '', file( './mysql/230_to_300.sql') );
				$sql_string = preg_replace( '/\{(CHC_TABLE_[_A-Z]+)\}/e', '\\1', $sql_string  );
				$sql_string = str_replace(
					array(
						'{CHC_OLD_TABLE_CONFIG}',
						'{CHC_OLD_TABLE_DATA}',
						'{CHC_OLD_TABLE_LOG_DATA}',
						'{CHC_OLD_TABLE_ONLINEUSERS}',
						'{CHC_OLD_TABLE_BLOCKEDUSERS}',
						'{CHC_OLD_TABLE_REFERERS}',
						'{CHC_OLD_TABLE_USERAGENTS}',
						'{CHC_OLD_TABLE_PAGES}',
						'{CHC_OLD_TABLE_ACCESS}',
						'{CHC_OLD_TABLE_COUNTRIES}',
						'{CHC_OLD_TABLE_RESOLUTIONS}',
						'{CHC_OLD_TABLE_SEARCHWORDS}'
					),
					$alte_tabellen,
					$sql_string
				);

				$array_search = array(
					'{DEFAULT_COUNTER_URL}',
					'{EXCLUSION_LIST_REFERRERS}',
					'{COUNTER_START_OLD}',
					'{TIMESTAMP}',
					'{TIMESTAMP_TODAY}',
					'{SESSION_NAME}',
					'{TIMESTAMP_THIS_DAY}',
					'{TIMESTAMP_THIS_WEEK}',
					'{TIMESTAMP_THIS_MONTH}',
					'{TIMESTAMP_THIS_YEAR}',
					'{DST}'
				);
				$array_replace = array(
					'http://'. $_SERVER['SERVER_NAME'] .dirname( dirname( $_SERVER['PHP_SELF'] ) ),
					preg_replace( '#http(s?)://(www\.)?#', 'http\\1://%', $alte_config['url_hp'] ) .'%; http://www.example.tld; %blocked by Outpost%',
					$counterstart_alt,
					time(),
					gmmktime(
						0,
						0,
						0,
						chC_format_date( 'n', CHC_TIMESTAMP, FALSE ),
						chC_format_date( 'j', CHC_TIMESTAMP, FALSE ),
						chC_format_date( 'Y', CHC_TIMESTAMP, FALSE )
					),
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
				     
				chC_set_config( array(
						'blockzeit' => $alte_config['blockzeit'],
						'admin_name' => $alte_config['admin_name'],
						'admin_passwort' => $alte_config['admin_passwort'],
						'user_online_fuer' => $alte_config['user_online_fuer'],
						'anzahl_pro_logseite' => $alte_config['anzahl_pro_logseite'],
						'statistiken_anzahl_referrer' => $alte_config['anzahl_referer'],
						'statistiken_anzahl_refdomains' => $alte_config['anzahl_refdomains'],
						'show_online_users_ip' => ( $alte_config['show_onlineuser_ip'] == '1' ) ? 4 : 0,
						'statistiken_anzahl_seiten' => $alte_config['anzahl_stats_seiten'],
						'statistiken_anzahl_user_agents' => $alte_config['anzahl_useragents'],
						'statistiken_anzahl_laender' => $alte_config['anzahl_stats_countries'],
						'admin_blocking_cookie' => $alte_config['cookie'],
						'default_homepage_url' => $alte_config['url_hp'],
						'statistiken_anzahl_aufloesungen' => $alte_config['anzahl_aufloesungen'],
						'statistiken_anzahl_suchwoerter' => $alte_config['anzahl_suchwoerter'],
						'zeitzone' => $alte_config['zeitzone'],
						'lang' => $alte_config['lang'],
						'lang_administration' => $alte_config['lang'],
						'seitenstatistik_titel_suche' => $alte_config['stats_seiten_search_title'],
						'status_logs' => $alte_config['activate_besucherdaten'],
						'status_referrer' => $alte_config['activate_referer'],
						'status_user_agents' => $alte_config['activate_useragents'],
						'status_clh' => $alte_config['activate_countries'],
						'status_seiten' => $alte_config['activate_seiten'],
						'status_aufloesungen' => $alte_config['activate_aufloesung'],
						'status_access' => $alte_config['activate_tage'],
						'status_suchwoerter' => $alte_config['activate_suchwoerter'],
						'status_js' => $alte_config['activate_js'],
						'script_version' => $script_version,
						'block_robots' => $alte_config['block_bots'],
						'log_eintraege_loeschen_nach:wert_in_sek' => $alte_config['eintraege_loeschen_nach'],
						'log_eintraege_loeschen_nach:einheit_administration' => $alte_config['einheit_eintraege_loeschen_nach'],
						'counterstatus_statistikseiten' => ( $alte_config['stats_ignore_counterfiles'] == '0' ? 1 : 0 )
					)
				);

				$_CHC_CONFIG = chC_get_config();
				$parse_url = parse_url( $_CHC_CONFIG['default_counter_url'] );
				if( isset( $parse_url['path'] ) && $parse_url['path'] != '/' )
				{
					$_CHC_DB->query(
						'UPDATE `'. CHC_TABLE_PAGES ."`
						SET counter_verzeichnis = 1
						WHERE wert LIKE '". $parse_url['path'] ."/%';"
					);
				}

				/*
				$result = $_CHC_DB->query(
					'SELECT timestamp, was, besucher, hits
					FROM `'. $GLOBALS['chC']['dbconfig']['table_access'] ."_tmp`
					WHERE
						was LIKE 'tageszeit%'
						OR was like 'tag\_%'
						OR was = 'tageszeit_wochentag_start'"
				);
				$_CHC_DB->query( 'DROP TABLE `'. $GLOBALS['chC']['dbconfig']['table_access'] .'_tmp`;' );
				while( $row = $_CHC_DB->fetch_assoc( $result ) )
				{ 
					if( preg_match( '/tageszeit_(\d+)/', $row['was'], $match ) )
					{
						$_CHC_DB->query(
							'UPDATE `'. CHC_TABLE_ACCESS .'`
							SET
								besucher_'. str_pad( $match[1], 2, '0', STR_PAD_LEFT ) .' = '. $row['besucher'] .',
								seitenaufrufe_'. str_pad( $match[1], 2, '0', STR_PAD_LEFT ) .' = '. $row['hits'] ."
							WHERE typ = 'tageszeit'"
						);
					}
					elseif( preg_match( '/tag_(\w+)/', $row['was'], $match ) )
					{
						$_CHC_DB->query(
							'UPDATE `'. CHC_TABLE_ACCESS .'`
							SET
								besucher_00 = '. $row['besucher'] .',
								seitenaufrufe_00 = '. $row['hits'] ."
							WHERE typ LIKE 'wochentag\_%\_". $match[1] ."'"
						);
					}
					elseif( $row['was'] == 'tageszeit_tag_start' )
					{
						$_CHC_DB->query(
							'UPDATE `'. CHC_TABLE_ACCESS .'`
							SET
								timestamp = '. $row['timestamp'] ."
							WHERE typ = 'tageszeit_wochentag_start'"
						);
					}
					else
					{
						die( 'Error: '. __FILE__. ', '. __LINE__ );
					}
				}
				*/


				$db_upgradefehler = $_CHC_DB->get_errors();
				if( count( $db_upgradefehler ) > 0 )
				{
					print '- <span class="error_msg">'. $_CHC_LANG['error(s)_occurred'] ."</span><br />\n"
						.'&nbsp;&nbsp;&nbsp;<i>'. $_CHC_LANG['db_error_messages:'] ."</i><br />\n";
					foreach( $db_upgradefehler as $array )
					{
						print '<span class="db_error_message">&nbsp;&nbsp;'. $array['error'] ."</span><br />\n";
					}

					if( isset( $_POST['backup_tabellen'] ) )
					{
						$_CHC_DB->query(
							'DROP TABLE IF EXISTS
								`'. $GLOBALS['chC']['dbconfig']['table_config'] .'`,
								`'. $GLOBALS['chC']['dbconfig']['table_data'] .'`,
								`'. $GLOBALS['chC']['dbconfig']['table_log_data'] .'`,
								`'. $GLOBALS['chC']['dbconfig']['table_onlineusers'] .'`,
								`'. $GLOBALS['chC']['dbconfig']['table_blockedusers'] .'`,
								`'. $GLOBALS['chC']['dbconfig']['table_referers'] .'`,
								`'. $GLOBALS['chC']['dbconfig']['table_useragents'] .'`,
								`'. $GLOBALS['chC']['dbconfig']['table_pages'] .'`,
								`'. $GLOBALS['chC']['dbconfig']['table_access'] .'`,
								`'. $GLOBALS['chC']['dbconfig']['table_countries'] .'`,
								`'. $GLOBALS['chC']['dbconfig']['table_resolutions'] .'`,
								`'. $GLOBALS['chC']['dbconfig']['table_searchwords'] .'`;'
						);
						$_CHC_DB->query(
							'DROP TABLE IF EXISTS
								`'. CHC_TABLE_CONFIG .'`,
								`'. CHC_TABLE_DATA .'`,
								`'. CHC_TABLE_COUNTED_USERS .'`,
								`'. CHC_TABLE_LOG_DATA .'`,
								`'. CHC_TABLE_ONLINE_USERS .'`,
								`'. CHC_TABLE_ACCESS .'`,
								`'. CHC_TABLE_SCREEN_RESOLUTIONS .'`,
								`'. CHC_TABLE_USER_AGENTS .'`,
								`'. CHC_TABLE_SEARCH_ENGINES .'`,
								`'. CHC_TABLE_REFERRERS .'`,
								`'. CHC_TABLE_LOCALE_INFORMATION .'`,
								`'. CHC_TABLE_PAGES .'`;'
						);
						chC_upgrade_backup_copy_tables( 'chc_backup_', '' );
						$_CHC_DB->query(
							'DROP TABLE
								`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_config'] .'`,
								`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_data'] .'`,
								`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_log_data'] .'`,
								`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_onlineusers'] .'`,
								`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_blockedusers'] .'`,
								`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_referers'] .'`,
								`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_useragents'] .'`,
								`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_pages'] .'`,
								`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_access'] .'`,
								`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_countries'] .'`,
								`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_resolutions'] .'`,
								`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_searchwords'] .'`;'
						);
					}
				}
				else
				{
					print '<span class="success">'. sprintf( $_CHC_LANG['upgrade_finished'], $script_version ) ."</span><br />\n"
					."<br />\n". $_CHC_LANG['remove_install_directory']."<br />\n<br />\n";

					if( isset( $_POST['backup_tabellen'] ) )
					{
						$_CHC_DB->query(
							'DROP TABLE
								`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_config'] .'`,
								`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_data'] .'`,
								`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_log_data'] .'`,
								`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_onlineusers'] .'`,
								`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_blockedusers'] .'`,
								`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_referers'] .'`,
								`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_useragents'] .'`,
								`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_pages'] .'`,
								`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_access'] .'`,
								`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_countries'] .'`,
								`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_resolutions'] .'`,
								`chc_backup_'. $GLOBALS['chC']['dbconfig']['table_searchwords'] .'`;'
						);
					}
				}
			}
		}
	}
}
?>
   </div>
   </div>
   <div class="footer">&nbsp;</div>
  </div>
 </body>
</html>
