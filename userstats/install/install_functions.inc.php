<?php

/*
 **************************************
 *
 * install/install_functions.php
 * -------------
 *
 * last modified:	2005-05-26
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



function chC_split_sql_queries( $string )
{
	$sql_queries = array();
	$in_string = FALSE;
	$found_query_end = FALSE;
	$query = '';


	// jedes Zeichen durchgehen
	$strlen = strlen( $string );
	for( $i = 0; $i < $strlen; $i++ )
	{
		// Wir befinden uns momentan in einem String eines SQL-Statements
		// (begrenzt von entweder jeweils zwei '`', '"' oder Hochkommata)
		if( $in_string == TRUE )
		{
			$i_aktuell = $i; // <- wird gleich noch gebraucht, da $i selber sich ändert, allerdings die (noch) aktuelle Stelle bekannt sein muss)

			// Nach Stringende suche, ab jetziger Position
			$i = strpos( $string, $in_string, $i );

			// wenn Stringende gar nicht im SQL-String vorhanden:
			// Restliche Zeichen zur temporären Query hinzufügen und Suche abschließen.
			if( !is_int( $i) )
			{
				$query .= substr( $string, $i_aktuell );
				break;
			}

			// ansonsten: mögliches Stringende gefunden
			// nun auf die Anzahl eventuell vorhandener vorhergehender aufeinander folgender Backslashes davor prüfen
			$number_of_backslashes = 0;
			for( $j = $i; $j > $i_aktuell; $j-- )
			{
				if( $string[$j] == '\\' )
				{
					$number_of_backslashes++;
				}
				else // kein Backslash mehr
				{
					break;
				}
			}

			// Anzahl gerade: -> Backslashes escapen sich alle nur selber
			if( ( $number_of_backslashes % 2 ) == 0 )
			{
				// wir haben tatsächlich das Stringende gefunden;
				// alle Zeichen bis einschließlich Stringende zum SQL-Statement hinzufügen.
				$query .= substr(
					$string,
					$i_aktuell,
					$i - $i_aktuell +1
				);
				// Der String ist vollständig erfasst, also:
				$in_string = FALSE;

				// Falls Stringende nicht gefunden wird, steht $in_string weiterhin auf TRUE,
				// durch das geänderte $i wird nach dem nächsten möglichen Stringende gesucht

			}

		} // elseif( $in_string == TRUE )

		// alles andere: Zeichen innerhalb einer Query und nicht in einem String dieser Query
		else
		{
			if( $string[$i] == '`' )
			{
				$in_string = '`';
			}
			elseif( $string[$i] == '"' )
			{
				$in_string = '"';
			}
			elseif( $string[$i] == '\'' )
			{
				$in_string = '\'';
			}

			elseif( $string[$i] == ';' )
			{
				// in Semikolon ausserhalb eines Kommentares oder String: Jo, da ist es hoffentlich, das Statement-Ende :-)
				$found_query_end = TRUE;
			}


			// Zeichen zur gerade im Zusammenbau befindlichen Query hinzufügen
			$query .= $string[$i];

			// Ende des SQL-Statements gefunden...?
			if( $found_query_end == TRUE )
			{
				// Zusammenbau des aktuellen Statements fertig, ab damit in die "Lagerhalle" ;-), temporäres Statement dann wieder auf null
				$sql_queries[] = trim( $query );
				$query = '';
				$found_query_end = FALSE;
			}

		} // ! ( $in_string == TRUE )
	} // for( $i = 0; $i < $strlen; $i++ )

	// Alle Zeichen durchlaufen. Wenn die letzte Query nicht abgeschlossen wurde, jetzt abschließen
	if( trim( $query ) != '' )
	{
		$sql_queries[] = trim( $query );
	}

	// ermittelten SQL-Statements zurückgeben
	return $sql_queries;

} // function chC_split_sql_queries()







function chC_execute_sql_file( $file )
{
	global $_CHC_DB;
	
	$sql_string = @implode( '', @file( $file) );
	if( $sql_string == FALSE )
	{
		die( 'Error: Could not open file '. $file .'.' );
	}
	$sql_string = preg_replace( '/\{(CHC_TABLE_[_A-Z]+)\}/e', '\\1', $sql_string  );
	$sql_queries = chC_split_sql_queries( $sql_string );
	foreach( $sql_queries as $query )
	{
		$_CHC_DB->query( $query );
	}
}




function chC_upgrade_backup_copy_tables( $from_prefix, $to_prefix )
{
	global $_CHC_DB;

	$_CHC_DB->query(
		'CREATE TABLE `'. $to_prefix . $GLOBALS['chC']['dbconfig']['table_config'] ."` (
			blockzeit int(14) NOT NULL default 0,
			eintraege_loeschen_nach int(14) NOT NULL default 0,
			einheit_eintraege_loeschen_nach varchar(255) NOT NULL default '',
			user_online_fuer int(14) NOT NULL default 0,
			admin_name varchar(255) NOT NULL default '',
			admin_email varchar(255) NOT NULL default '',
			admin_passwort varchar(255) NOT NULL default '',
			url_hp varchar(255) NOT NULL default '',
			verzeichnis varchar(255) NOT NULL default '',
			anzahl_pro_logseite int(11) NOT NULL default 0,
			anzahl_referer int(14) NOT NULL default 0,
			anzahl_refdomains int(14) NOT NULL default 0,
			show_onlineuser_ip int(14) NOT NULL default 0,
			anzahl_stats_seiten int(14) NOT NULL default 0,
			anzahl_useragents int(14) NOT NULL default 0,
			anzahl_stats_countries int(14) NOT NULL default 0,
			anzahl_aufloesungen int(14) NOT NULL default 0,
			anzahl_suchwoerter int(14) NOT NULL default 0,
			cookie int(14) NOT NULL default 0,
			stats_ignore_counterfile int(1) NOT NULL default 0,
			stats_ignore_counterfiles int(1) NOT NULL default 0,
			datum_format varchar(255) NOT NULL default '',
			zeitzone varchar(255) NOT NULL default '',
			lang varchar(255) NOT NULL default '',
			stats_seiten_search_title int(1) NOT NULL default 0,
			activate_besucherdaten int(1) NOT NULL default 0,
			activate_referer int(1) NOT NULL default 0,
			activate_useragents int(1) NOT NULL default 0,
			activate_countries int(1) NOT NULL default 0,
			activate_seiten int(1) NOT NULL default 0,
			activate_aufloesung int(1) NOT NULL default '0',
			activate_tage int(1) NOT NULL default 0,
			activate_monate int(1) NOT NULL default 0,
			activate_suchwoerter int(1) NOT NULL default 0,
			activate_js int(1) NOT NULL default 0,
			activate_tageszeit_tag int(1) NOT NULL default 0,
			ignored_referers MEDIUMTEXT NOT NULL default '',
			script_version int(3) NOT NULL default 0,
			block_bots int(1) NOT NULL default 0
		) TYPE=MyISAM;"
	);
	$_CHC_DB->query(
		'INSERT INTO `'. $to_prefix . $GLOBALS['chC']['dbconfig']['table_config'] .'`
			SELECT
				blockzeit, eintraege_loeschen_nach, einheit_eintraege_loeschen_nach, user_online_fuer, admin_name,
				admin_email, admin_passwort, url_hp, verzeichnis, anzahl_pro_logseite, anzahl_referer, anzahl_refdomains,
				show_onlineuser_ip, anzahl_stats_seiten, anzahl_useragents, anzahl_stats_countries, anzahl_aufloesungen,
				anzahl_suchwoerter, cookie, stats_ignore_counterfile, stats_ignore_counterfiles, datum_format, zeitzone,
				lang, stats_seiten_search_title, activate_besucherdaten, activate_referer, activate_useragents,
				activate_countries, activate_seiten, activate_aufloesung, activate_tage, activate_monate, activate_suchwoerter,
				activate_js, activate_tageszeit_tag, ignored_referers, script_version, block_bots
			FROM `'. $from_prefix . $GLOBALS['chC']['dbconfig']['table_config'] .'`;'
	);

	$_CHC_DB->query(
		'CREATE TABLE `'. $to_prefix . $GLOBALS['chC']['dbconfig']['table_data'] .'` (
			besucher_gesamt int(14) NOT NULL default 0,
			besucher_heute int(14) NOT NULL default 0,
			heute_timestamp int(14) NOT NULL default 0,
			besucher_gestern int(14) NOT NULL default 0,
			max_online int(14) NOT NULL default 0,
			max_online_datum int(14) NOT NULL default 0,
			max_per_day int(14) NOT NULL default 0,
			max_per_day_datum int(14) NOT NULL default 0,
			hits_gesamt int(14) NOT NULL default 0,
			hits_heute int(14) NOT NULL default 0,
			hits_gestern int(14) NOT NULL default 0,
			besucher_hits_pro_besucher int(14) NOT NULL default 0,
			hits_hits_pro_besucher int(14) NOT NULL default 0,
			besucher_seit_counterstart int(14) NOT NULL default 0,
			hits_seit_counterstart int(14) NOT NULL default 0,
			counterstart int(1) NOT NULL default 0,
			js_alle int(14) NOT NULL default 0,
			js_aktiv int(14) NOT NULL default 0
		) TYPE=MyISAM;'
	);
	$_CHC_DB->query(
		'INSERT INTO `'. $to_prefix . $GLOBALS['chC']['dbconfig']['table_data'] .'`
			SELECT
				besucher_gesamt, besucher_heute, heute_timestamp, besucher_gestern, max_online, max_online_datum, max_per_day,
				max_per_day_datum, hits_gesamt, hits_heute, hits_gestern, besucher_hits_pro_besucher, hits_hits_pro_besucher,
				besucher_seit_counterstart, hits_seit_counterstart, counterstart, js_alle, js_aktiv
			FROM `'. $from_prefix . $GLOBALS['chC']['dbconfig']['table_data'] .'`'
	);

	$_CHC_DB->query(
		'CREATE TABLE `'. $to_prefix . $GLOBALS['chC']['dbconfig']['table_log_data'] ."` (
			nr int(14) NOT NULL default '0',
			ip varchar(255) NOT NULL default '',
			host varchar(255) NOT NULL default '',
			useragent varchar(255) NOT NULL default '',
			zeit varchar(255) NOT NULL default '',
			referer varchar(255) NOT NULL default '',
			hits int(14) NOT NULL default 0,
			seiten TEXT NOT NULL
		) TYPE=MyISAM;"
	);
	$_CHC_DB->query(
		'INSERT INTO `'. $to_prefix . $GLOBALS['chC']['dbconfig']['table_log_data'] .'`
			SELECT
				nr, ip, host, useragent, zeit, referer, hits, seiten
			FROM `'. $from_prefix . $GLOBALS['chC']['dbconfig']['table_log_data'] .'`'
	);

	$_CHC_DB->query(
		'CREATE TABLE `'. $to_prefix . $GLOBALS['chC']['dbconfig']['table_onlineusers'] ."` (
			nr int(14) NOT NULL default 0,
			ip varchar(255) NOT NULL default '',
			useragent varchar(255) NOT NULL default '',
			zeit varchar(255) NOT NULL default '',
			seite varchar(255) NOT NULL default '',
			hits int(14) NOT NULL default 0
		) TYPE=MyISAM;"
	);
	$_CHC_DB->query(
		'INSERT INTO `'. $to_prefix . $GLOBALS['chC']['dbconfig']['table_onlineusers'] .'`
			SELECT
				nr, ip, useragent, zeit, seite, hits
			FROM `'. $from_prefix . $GLOBALS['chC']['dbconfig']['table_onlineusers'] .'`'
	);

	$_CHC_DB->query(
		'CREATE TABLE `'. $to_prefix . $GLOBALS['chC']['dbconfig']['table_blockedusers'] ."` (
			nr int(14) NOT NULL default 0,
			ip varchar(255) NOT NULL default '',
			useragent varchar(255) NOT NULL default '',
			zeit varchar(255) NOT NULL default '',
			hits int(14) NOT NULL default 0
		) TYPE=MyISAM;"
	);
	$_CHC_DB->query(
		'INSERT INTO `'. $to_prefix . $GLOBALS['chC']['dbconfig']['table_blockedusers'] .'`
			SELECT
				nr, ip, useragent, zeit, hits
			FROM `'. $from_prefix . $GLOBALS['chC']['dbconfig']['table_blockedusers'] .'`'
	);

	$_CHC_DB->query(
		'CREATE TABLE `'. $to_prefix . $GLOBALS['chC']['dbconfig']['table_useragents'] ."` (
			was varchar(255) NOT NULL default '',
			typ varchar(255) NOT NULL default '',
			datum int(14) NOT NULL default '',
			anzahl int(14) NOT NULL default 0
		) TYPE=MyISAM;"
	);
	$_CHC_DB->query(
		'INSERT INTO `'. $to_prefix . $GLOBALS['chC']['dbconfig']['table_useragents'] .'`
			SELECT
				was, typ, datum, anzahl
			FROM `'. $from_prefix . $GLOBALS['chC']['dbconfig']['table_useragents'] .'`'
	);

	$_CHC_DB->query(
		'CREATE TABLE `'. $to_prefix . $GLOBALS['chC']['dbconfig']['table_access'] ."` (
			was varchar(255) NOT NULL default '',
			timestamp int(14) NOT NULL default 0,
			besucher int(14) NOT NULL default 0,
			hits int(14) NOT NULL default 0
		) TYPE=MyISAM;"
	);
	$_CHC_DB->query(
		'INSERT INTO `'. $to_prefix . $GLOBALS['chC']['dbconfig']['table_access'] .'`
			SELECT
				was, timestamp, besucher, hits
			FROM `'. $from_prefix . $GLOBALS['chC']['dbconfig']['table_access'] .'`'
	);

	$_CHC_DB->query(
		'CREATE TABLE `'. $to_prefix . $GLOBALS['chC']['dbconfig']['table_pages']."` (
			seite varchar(255) NOT NULL default '',
			titel varchar(255) NOT NULL default '',
			datum int(14) NOT NULL default 0,
			anzahl int(14) NOT NULL default 0,
			UNIQUE KEY `". $GLOBALS['chC']['dbconfig']['table_pages'] ."` (`seite`)
		) TYPE=MyISAM;"
	);
	$_CHC_DB->query(
		'INSERT INTO `'. $to_prefix . $GLOBALS['chC']['dbconfig']['table_pages'] .'`
			SELECT
				seite, titel, datum, anzahl
			FROM `'. $from_prefix . $GLOBALS['chC']['dbconfig']['table_pages'] .'`'
	);

	$_CHC_DB->query(
		'CREATE TABLE `'. $to_prefix . $GLOBALS['chC']['dbconfig']['table_referers']."` (
			was varchar (255) NOT NULL default '',
			referer varchar(255) NOT NULL default '',
			datum int(14) NOT NULL default 0,
			anzahl int(14) NOT NULL default 0,
			UNIQUE KEY `". $GLOBALS['chC']['dbconfig']['table_referers'] ."` (`referer`)
		) TYPE=MyISAM;"
	);
	$_CHC_DB->query(
		'INSERT INTO `'. $to_prefix . $GLOBALS['chC']['dbconfig']['table_referers'] .'`
			SELECT
				was, referer, datum, anzahl
			FROM `'. $from_prefix . $GLOBALS['chC']['dbconfig']['table_referers'] .'`'
	);

	$_CHC_DB->query(
		'CREATE TABLE `'. $to_prefix . $GLOBALS['chC']['dbconfig']['table_countries'] ."` (
			land varchar(255) NOT NULL default '',
			anzahl int(14) NOT NULL default 0
		) TYPE=MyISAM;"
	);
	$_CHC_DB->query(
		'INSERT INTO `'. $to_prefix . $GLOBALS['chC']['dbconfig']['table_countries'] .'`
			SELECT
				land, anzahl
			FROM `'. $from_prefix . $GLOBALS['chC']['dbconfig']['table_countries'] .'`'
	);

	$_CHC_DB->query(
		'CREATE TABLE `'. $to_prefix . $GLOBALS['chC']['dbconfig']['table_resolutions'] ."` (
			`aufloesung` varchar(255) NOT NULL default '',
			`anzahl` int(14) NOT NULL default 0,
			UNIQUE KEY `".$GLOBALS['chC']['dbconfig']['table_resolutions']."` (`aufloesung`)
		) TYPE=MyISAM;"
	);
	$_CHC_DB->query(
		'INSERT INTO `'. $to_prefix . $GLOBALS['chC']['dbconfig']['table_resolutions'] .'`
			SELECT
				aufloesung, anzahl
			FROM `'. $from_prefix . $GLOBALS['chC']['dbconfig']['table_resolutions'] .'`'
	);

	$_CHC_DB->query(
		'CREATE TABLE `'. $to_prefix . $GLOBALS['chC']['dbconfig']['table_searchwords'] ."` (
			suchwoerter varchar(255) NOT NULL default '',
			anzahl int(14) NOT NULL default 0,
			datum int(14) NOT NULL default 0,
			UNIQUE KEY `".$GLOBALS['chC']['dbconfig']['table_searchwords']."` (`suchwoerter`)
		) TYPE=MyISAM;"
	);
	$_CHC_DB->query(
		'INSERT INTO `'. $to_prefix . $GLOBALS['chC']['dbconfig']['table_searchwords'] .'`
			SELECT
				suchwoerter, anzahl, datum
			FROM `'. $from_prefix . $GLOBALS['chC']['dbconfig']['table_searchwords'] .'`'
	);
}



function chC_update_user_agent_version_entries( $typ, $search, $replace )
{
	global $_CHC_DB;

	$result = $_CHC_DB->query(
		'SELECT typ, wert as alter_wert, anzahl, timestamp
		FROM `'. CHC_TABLE_USER_AGENTS ."`
		WHERE typ LIKE 'version~". $typ ."' AND wert LIKE '". $search ."~%'"
	);
	while( $row = $_CHC_DB->fetch_assoc( $result ) )
	{
		$row['neuer_wert'] = str_replace( $search, $replace, $row['alter_wert'] );
		$_CHC_DB->query(
			'UPDATE `'. CHC_TABLE_USER_AGENTS ."`
			SET wert = '". $row['neuer_wert'] ."'
			WHERE typ = '". $row['typ'] ."' AND wert = '". $row['alter_wert'] ."';"
		);
	}
}


?>
