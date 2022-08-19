-- TABLE_ACCESS --
-- CREATE TABLE `{CHC_OLD_TABLE_ACCESS}_tmp` (
--	was varchar(255) NOT NULL default '',
--	timestamp int(14) NOT NULL default 0,
--	besucher int(14) NOT NULL default 0,
--	hits int(14) NOT NULL default 0
-- ) TYPE=MyISAM;

-- INSERT INTO `{CHC_OLD_TABLE_ACCESS}_tmp`
--	SELECT was, timestamp, besucher, hits
--	FROM `{CHC_OLD_TABLE_ACCESS}`;

DROP TABLE `{CHC_OLD_TABLE_ACCESS}`;

CREATE TABLE `{CHC_TABLE_ACCESS}` (
  `timestamp` int(14) NOT NULL default '0',
  `typ` varchar(255) NOT NULL default '',
  `erster_eintrag` INT( 1 ) NOT NULL DEFAULT '0',
  `besucher_00` int(14) NOT NULL default '0',
  `besucher_01` int(14) NOT NULL default '0',
  `besucher_02` int(14) NOT NULL default '0',
  `besucher_03` int(14) NOT NULL default '0',
  `besucher_04` int(14) NOT NULL default '0',
  `besucher_05` int(14) NOT NULL default '0',
  `besucher_06` int(14) NOT NULL default '0',
  `besucher_07` int(14) NOT NULL default '0',
  `besucher_08` int(14) NOT NULL default '0',
  `besucher_09` int(14) NOT NULL default '0',
  `besucher_10` int(14) NOT NULL default '0',
  `besucher_11` int(14) NOT NULL default '0',
  `besucher_12` int(14) NOT NULL default '0',
  `besucher_13` int(14) NOT NULL default '0',
  `besucher_14` int(14) NOT NULL default '0',
  `besucher_15` int(14) NOT NULL default '0',
  `besucher_16` int(14) NOT NULL default '0',
  `besucher_17` int(14) NOT NULL default '0',
  `besucher_18` int(14) NOT NULL default '0',
  `besucher_19` int(14) NOT NULL default '0',
  `besucher_20` int(14) NOT NULL default '0',
  `besucher_21` int(14) NOT NULL default '0',
  `besucher_22` int(14) NOT NULL default '0',
  `besucher_23` int(14) NOT NULL default '0',
  `seitenaufrufe_00` int(14) NOT NULL default '0',
  `seitenaufrufe_01` int(14) NOT NULL default '0',
  `seitenaufrufe_02` int(14) NOT NULL default '0',
  `seitenaufrufe_03` int(14) NOT NULL default '0',
  `seitenaufrufe_04` int(14) NOT NULL default '0',
  `seitenaufrufe_05` int(14) NOT NULL default '0',
  `seitenaufrufe_06` int(14) NOT NULL default '0',
  `seitenaufrufe_07` int(14) NOT NULL default '0',
  `seitenaufrufe_08` int(14) NOT NULL default '0',
  `seitenaufrufe_09` int(14) NOT NULL default '0',
  `seitenaufrufe_10` int(14) NOT NULL default '0',
  `seitenaufrufe_11` int(14) NOT NULL default '0',
  `seitenaufrufe_12` int(14) NOT NULL default '0',
  `seitenaufrufe_13` int(14) NOT NULL default '0',
  `seitenaufrufe_14` int(14) NOT NULL default '0',
  `seitenaufrufe_15` int(14) NOT NULL default '0',
  `seitenaufrufe_16` int(14) NOT NULL default '0',
  `seitenaufrufe_17` int(14) NOT NULL default '0',
  `seitenaufrufe_18` int(14) NOT NULL default '0',
  `seitenaufrufe_19` int(14) NOT NULL default '0',
  `seitenaufrufe_20` int(14) NOT NULL default '0',
  `seitenaufrufe_21` int(14) NOT NULL default '0',
  `seitenaufrufe_22` int(14) NOT NULL default '0',
  `seitenaufrufe_23` int(14) NOT NULL default '0',
  KEY `typ` (`typ`),
  KEY `timestamp` (`typ`,`timestamp`)
) TYPE=MyISAM;

INSERT INTO `{CHC_TABLE_ACCESS}` ( timestamp, typ ) VALUES ( {TIMESTAMP}, 'tageszeit_wochentag_start' );
INSERT INTO `{CHC_TABLE_ACCESS}` ( typ ) VALUES ( 'tageszeit' );
INSERT INTO `{CHC_TABLE_ACCESS}` ( typ ) VALUES ( 'wochentag_1_Mon' );
INSERT INTO `{CHC_TABLE_ACCESS}` ( typ ) VALUES ( 'wochentag_2_Tue' );
INSERT INTO `{CHC_TABLE_ACCESS}` ( typ ) VALUES ( 'wochentag_3_Wed' );
INSERT INTO `{CHC_TABLE_ACCESS}` ( typ ) VALUES ( 'wochentag_4_Thu' );
INSERT INTO `{CHC_TABLE_ACCESS}` ( typ ) VALUES ( 'wochentag_5_Fri' );
INSERT INTO `{CHC_TABLE_ACCESS}` ( typ ) VALUES ( 'wochentag_6_Sat' );
INSERT INTO `{CHC_TABLE_ACCESS}` ( typ ) VALUES ( 'wochentag_7_Sun' );
INSERT INTO `{CHC_TABLE_ACCESS}` ( timestamp, typ, erster_eintrag ) VALUES ( {TIMESTAMP_THIS_DAY}, 'tag', 1 );
INSERT INTO `{CHC_TABLE_ACCESS}` ( timestamp, typ, erster_eintrag ) VALUES ( {TIMESTAMP_THIS_WEEK}, 'kw', 1 );
INSERT INTO `{CHC_TABLE_ACCESS}` ( timestamp, typ, erster_eintrag ) VALUES ( {TIMESTAMP_THIS_MONTH}, 'monat', 1 );
INSERT INTO `{CHC_TABLE_ACCESS}` ( timestamp, typ, erster_eintrag ) VALUES ( {TIMESTAMP_THIS_YEAR}, 'jahr', 1 );





-- TABLE_CONFIG --
DROP TABLE `{CHC_OLD_TABLE_CONFIG}`;

CREATE TABLE `{CHC_TABLE_CONFIG}` (
  `setting` varchar(255) NOT NULL default '',
  `value` text NOT NULL,
  PRIMARY KEY  (`setting`)
) TYPE=MyISAM;

INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('statistiken_anzahl_aufloesungen', '10');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('admin_name', '');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('admin_passwort', '');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('modus_zaehlsperre', 'intervall');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('blockzeit', '86400');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('user_online_fuer', '300');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('log_eintraege_loeschen_nach:wert_in_sek', '2421972');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('log_eintraege_loeschen_nach:einheit_administration', 'd');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('anordnung_log_eintraege', 'ASC');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('anzahl_pro_logseite', '100');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('statistiken_anzahl_referrer', '10');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('statistiken_anzahl_refdomains', '10');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('statistiken_anzahl_all_lists', '1000');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('show_online_users_ip', '2');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('statistiken_anzahl_seiten', '20');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('statistiken_anzahl_user_agents', '10');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('statistiken_anzahl_sprachen', '10');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('statistiken_anzahl_hosts_tlds', '10');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('statistiken_anzahl_laender', '10');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('db_aufraeumen_nach', '86400');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('statistiken_anzahl_suchwoerter', '10');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('statistiken_anzahl_suchphrasen', '10');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('zeitzone', '1');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('dst', '{DST}');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('lang', 'de');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('seitenstatistik_titel_suche', '1');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('status_logs', '1');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('status_referrer', '1');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('status_user_agents', '1');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('status_clh', '1');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('status_seiten', '1');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('status_aufloesungen', '1');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('status_access', '1');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('status_suchmaschinen_und_suchwoerter', '1');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('status_suchphrasen', '1');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('status_js', '1');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('script_version', '{SCRIPT_VERSION}');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('block_robots', '0');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('statistiken_login_erforderlich', 'index:all_lists;');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('statistiken_anzahl_latest', '10');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('statistiken_anzahl_top', '10');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('blacklist_user_agents', '');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('blacklist_referrers', '%q=www.%+sex%.%');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('blacklist_pages', '');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('blacklist_ips', '');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('blacklist_hosts', '');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('exclusion_list_user_agents', '');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('exclusion_list_referrers', '{EXCLUSION_LIST_REFERRERS}');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('exclusion_list_keywords', '');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('exclusion_list_search_phrases', '');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('exclusion_list_pages', '');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('exclusion_list_screen_resolutions', '');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('hideout_list_pages', '');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('hideout_list_screen_resolutions', '');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('hideout_list_keywords', '');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('hideout_list_search_phrases', '');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('hideout_list_referrers', '');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('hideout_list_user_agents', '');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('wordwrap_latest_x', '30');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('wordwrap_top_x', '30');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('referrer_kuerzen_nach', '27');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('referrer_kuerzungszeichen', '...');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('wordwrap_referrer', '30');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('wordwrap_refdomains', '30');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('wordwrap_suchwoerter', '30');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('wordwrap_suchphrasen', '30');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('wordwrap_seite_online_users', '30');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('wordwrap_seiten', '30');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('wordwrap_browser', '0');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('wordwrap_user_agents', '30');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('wordwrap_aufloesungen', '0');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('statistiken_anzahl_host_tlds', '20');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('statistiken_anzahl_suchmaschinen', '10');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('hideout_list_browsers', '');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('hideout_list_os', '');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('hideout_list_robots', '');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('user_agents_kuerzen_nach', '0');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('user_agents_kuerzungszeichen', '...');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('fremde_URLs_verlinken', '0');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('hideout_list_referring_domains', '');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('wordwrap_all_lists', '50');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('wordwrap_suchmaschinen', '30');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('gast_name', '');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('gast_passwort', '');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('lang_administration', 'de');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('admin_blocking_cookie', '0');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('exclusion_list_search_engines', '');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('statistiken_anzahl_browser', '20');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('hideout_list_search_engines', '');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('default_counter_visibility', '1');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('statistiken_anzahl_os', '20');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('statistiken_anzahl_robots', '20');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('wordwrap_os', '0');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('wordwrap_robots', '0');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('wordwrap_laender', '0');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('wordwrap_sprachen', '0');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('wordwrap_hosts_tlds', '0');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('referrer_query_string_entfernen', '0');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('seiten_query_string_bereinigung_variablen', 'latest; top; sort_by; sort_order; distr_type; distr_of; d_day; d_month; d_year; d_type; m_month; m_year; m_type; w_month; w_year; w_type; y_year; y_type; l_last; l_type; lang; type; clh; homepage_id; kp; {SESSION_NAME}; ');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('seiten_query_string_bereinigung_modus', 'ohne');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('timestamp_start_pseudo', '{COUNTER_START_OLD}');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('timestamp_start_access', '{TIMESTAMP}');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('timestamp_start_referrer', '{COUNTER_START_OLD}');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('timestamp_start_user_agents', '{COUNTER_START_OLD}');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('timestamp_start_browser', '{TIMESTAMP}');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('timestamp_start_os', '{TIMESTAMP}');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('timestamp_start_robots', '{TIMESTAMP}');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('timestamp_start_seiten', '{COUNTER_START_OLD}');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('timestamp_start_laender', '{TIMESTAMP}');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('timestamp_start_sprachen', '{TIMESTAMP}');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('timestamp_start_hosts_tlds', '{TIMESTAMP}');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('timestamp_start_suchmaschinen', '{TIMESTAMP}');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('timestamp_start_suchwoerter_suchphrasen', '{TIMESTAMP}');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('timestamp_start_aufloesungen', '{COUNTER_START_OLD}');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('timestamp_start_javascript', '{COUNTER_START_OLD}');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('timestamp_start_verweisende_domains', '{COUNTER_START_OLD}');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('zeige_seitentitel', '1');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('logdaten_zeige_seitentitel', '1');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('default_homepage_url', '{DEFAULT_HOMEPAGE_URL}');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('homepages_urls', '1 => default_homepage_url; ');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('default_counter_url', '{DEFAULT_COUNTER_URL}');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('counter_urls', '1 => default_counter_url; ');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('aktiviere_seitenverwaltung_von_mehreren_domains', '0');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('seitenstatistik:_zeige_homepages_getrennt', '0');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('regelmaessiges_loeschen:user_agents:werte', '1;8640000');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('regelmaessiges_loeschen:user_agents:aktiviert', '0');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('regelmaessiges_loeschen:referrer:werte', '1;8640000');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('regelmaessiges_loeschen:referrer:aktiviert', '0');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('regelmaessiges_loeschen:verweisende_domains:werte', '1;8640000');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('regelmaessiges_loeschen:verweisende_domains:aktiviert', '0');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('darstellungsart_balkendiagramme_zugriffsstatistiken', 'relativ');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('homepage_charset', 'ISO-8859-1');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('js_gleichwertige_homepage_urls', '');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('min_jscode_version', '3.0.0Beta8');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('min_zeichenlaenge_suchwoerter_suchphrasen', '3');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('counterstatus_statistikseiten', '1');




-- TABLE_COUNTED_USERS --
ALTER TABLE `{CHC_OLD_TABLE_BLOCKEDUSERS}`
	RENAME `{CHC_TABLE_COUNTED_USERS}`,
	CHANGE `useragent` `user_agent` VARCHAR( 255 ) NOT NULL DEFAULT '',
	CHANGE `zeit` `timestamp` int(14) NOT NULL default '0',
	CHANGE `hits` `seitenaufrufe` INT( 14 ) NOT NULL DEFAULT 0,
	ADD `js` int(11) NOT NULL default '0',
	ADD `aufloesung` varchar(255) NOT NULL default '',
	ADD PRIMARY KEY  (`nr`),
	ADD KEY `schluessel` (`ip`,`user_agent`(20));





-- TABLE_DATA --
ALTER TABLE `{CHC_OLD_TABLE_DATA}`
	RENAME `{CHC_TABLE_DATA}`,
	CHANGE `max_online` `max_online:anzahl` INT( 14 ) NOT NULL DEFAULT 0,
	CHANGE `max_online_datum` `max_online:timestamp` INT( 14 ) NOT NULL DEFAULT 0,
	CHANGE `max_per_day` `max_besucher_pro_tag:anzahl` INT( 14 ) NOT NULL DEFAULT 0,
	CHANGE `hits_gesamt` `seitenaufrufe_gesamt` INT( 14 ) NOT NULL DEFAULT 0,
	CHANGE `hits_heute` `seitenaufrufe_heute` INT( 14 ) NOT NULL DEFAULT 0,
	CHANGE `hits_gestern` `seitenaufrufe_gestern` INT( 14 ) NOT NULL DEFAULT 0,
	CHANGE `besucher_hits_pro_besucher` `seitenaufrufe_pro_besucher:besucher` INT( 14 ) NOT NULL DEFAULT 0,
	CHANGE `hits_hits_pro_besucher` `seitenaufrufe_pro_besucher:seitenaufrufe` INT( 14 ) NOT NULL DEFAULT 0,
	CHANGE `max_per_day_datum` `max_besucher_pro_tag:timestamp` INT( 14 ) NOT NULL DEFAULT 0,
	CHANGE `besucher_seit_counterstart` `durchschnittlich_pro_tag:besucher` INT( 14 ) NOT NULL DEFAULT 0,
	CHANGE `counterstart` `durchschnittlich_pro_tag:timestamp` INT( 14 ) NOT NULL DEFAULT 0,
	CHANGE `hits_seit_counterstart` `durchschnittlich_pro_tag:seitenaufrufe` INT( 14 ) NOT NULL DEFAULT 0,
	ADD `timestamp_letztes_db_aufraeumen` int(14) NOT NULL default 0,
	ADD `max_seitenaufrufe_pro_tag:anzahl` int(14) NOT NULL default 0,
	ADD `max_seitenaufrufe_pro_tag:timestamp` int(14) NOT NULL default 0;

UPDATE `{CHC_TABLE_DATA}`
	SET
	`heute_timestamp` = {TIMESTAMP_TODAY},
	`durchschnittlich_pro_tag:timestamp` = {COUNTER_START_OLD},
	`max_seitenaufrufe_pro_tag:timestamp` = {TIMESTAMP};





-- TABLE LOCALE_INFORMATION --
DROP TABLE `{CHC_OLD_TABLE_COUNTRIES}`;
CREATE TABLE `{CHC_TABLE_LOCALE_INFORMATION}` (
  `typ` varchar(255) NOT NULL default '',
  `wert` varchar(255) NOT NULL default '',
  `anzahl` int(14) NOT NULL default '0',
  `timestamp` int(14) NOT NULL default '0',
  KEY `typ` (`typ`)
) TYPE=MyISAM;





-- TABLE_LOG_DATA --
DROP TABLE `{CHC_OLD_TABLE_LOG_DATA}`;
CREATE TABLE `{CHC_TABLE_LOG_DATA}` (
  `nr` int(14) NOT NULL default '0',
  `ip` varchar(255) NOT NULL default '',
  `host` varchar(255) NOT NULL default '',
  `user_agent` varchar(255) NOT NULL default '',
  `http_accept_language` VARCHAR( 255 ) NOT NULL default '',
  `timestamp` int(14) NOT NULL default '0',
  `referrer` varchar(255) NOT NULL default '',
  `seitenaufrufe` int(14) NOT NULL default '0',
  `seiten` text NOT NULL,
  `js` int(1) NOT NULL default '0',
  `aufloesung` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`nr`),
  KEY `timestamp` (`timestamp`)
) TYPE=MyISAM;





-- TABLE_ONLINE_USERS --
ALTER TABLE `{CHC_OLD_TABLE_ONLINEUSERS}`
	RENAME `{CHC_TABLE_ONLINE_USERS}`,
	CHANGE `useragent` `user_agent` VARCHAR( 255 ) NOT NULL DEFAULT '',
	CHANGE `zeit` `timestamp` int(14) NOT NULL default '0',
	CHANGE `hits` `seitenaufrufe` INT( 14 ) NOT NULL DEFAULT 0,
	ADD `homepage_id` int(2) NOT NULL default '0' AFTER `seite`,
	ADD KEY `nr` (`nr`);

UPDATE `{CHC_TABLE_ONLINE_USERS}`
	SET homepage_id = 1;





-- TABLE_PAGES --
ALTER TABLE `{CHC_OLD_TABLE_PAGES}`
	RENAME `{CHC_TABLE_PAGES}`,
	CHANGE `seite` `wert` VARCHAR( 255 ) NOT NULL DEFAULT '',
	CHANGE `datum` `timestamp` int(14) NOT NULL default '0',
	ADD `homepage_id` int(2) NOT NULL default '0' AFTER `wert`,
	ADD `counter_verzeichnis` int(1) NOT NULL default '0' AFTER `homepage_id`,
	DROP INDEX `{CHC_OLD_TABLE_PAGES}`,
	ADD INDEX `wert` ( `wert` ),
	ADD KEY `homepage_id` (`homepage_id`);

UPDATE `{CHC_TABLE_PAGES}`
	SET homepage_id = 1;





-- TABLE_REFERRERS --
ALTER TABLE `{CHC_OLD_TABLE_REFERERS}`
	RENAME `{CHC_TABLE_REFERRERS}`,
	CHANGE `was` `typ` VARCHAR( 255 ) NOT NULL DEFAULT '',
	CHANGE `referer` `wert` VARCHAR( 255 ) NOT NULL DEFAULT '',
	CHANGE `datum` `timestamp` int(14) NOT NULL default '0',
	ADD `homepage_id` int(2) NOT NULL default '0' AFTER `wert`, 
	DROP INDEX `{CHC_OLD_TABLE_REFERERS}`,
	ADD KEY `typ` (`typ`),
	ADD KEY `typ_2` (`typ`,`wert`(20));

UPDATE `{CHC_TABLE_REFERRERS}`
	SET typ = 'referrer'
	WHERE typ = 'referer';





-- TABLE_SCREEN_RESOLUTIONS --
ALTER TABLE `{CHC_OLD_TABLE_RESOLUTIONS}`
	RENAME `{CHC_TABLE_SCREEN_RESOLUTIONS}`,
	CHANGE `aufloesung` `wert` VARCHAR( 255 ) NOT NULL DEFAULT '',
	ADD `timestamp` int(14) NOT NULL DEFAULT '0';





-- TABLE_SEARCH_ENGINES --
ALTER TABLE `{CHC_OLD_TABLE_SEARCHWORDS}`
	RENAME `{CHC_TABLE_SEARCH_ENGINES}`,
	ADD `typ` VARCHAR( 255 ) NOT NULL DEFAULT '' FIRST,
	CHANGE `suchwoerter` `wert` VARCHAR( 255 ) NOT NULL DEFAULT '',
	CHANGE `datum` `timestamp` VARCHAR( 255 ) NOT NULL DEFAULT '',
	DROP INDEX `{CHC_OLD_TABLE_SEARCHWORDS}` ,
	ADD KEY `typ` (`typ`),
	ADD KEY `typ_2` (`typ`,`wert`(10));

UPDATE `{CHC_TABLE_SEARCH_ENGINES}`
	SET typ = 'suchphrase';





-- TABLE_USER_AGENTS --
ALTER TABLE `{CHC_OLD_TABLE_USERAGENTS}`
	RENAME `{CHC_TABLE_USER_AGENTS}`,
	CHANGE `was` `typ` VARCHAR( 255 ) NOT NULL DEFAULT '',
	CHANGE `typ` `wert` VARCHAR( 255 ) NOT NULL DEFAULT '',
	CHANGE `datum` `timestamp` VARCHAR( 255 ) NOT NULL DEFAULT '',
	ADD KEY `typ` (`typ`),
	ADD KEY `typ_2` (`typ`,`wert`(20));

UPDATE `{CHC_TABLE_USER_AGENTS}`
	SET typ = 'user_agent'
	WHERE typ = 'useragent';

DELETE FROM `{CHC_TABLE_USER_AGENTS}`
WHERE typ != 'user_agent';