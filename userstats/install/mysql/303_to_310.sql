CREATE TABLE `{CHC_TABLE_DOWNLOADS_AND_HYPERLINKS}` (
  `id` INT( 14 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `typ` enum('download','hyperlink') NOT NULL default 'download',
  `wert` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `timestamp_eintrag` int(14) NOT NULL DEFAULT 0,
  `timestamp` int(14) NOT NULL default 0,
  `anzahl` int(14) NOT NULL default 0,
  `in_statistik_verbergen` int(1) NOT NULL default 0,
   INDEX (`typ`)
) TYPE=MyISAM;

CREATE TABLE `{CHC_TABLE_DOWNLOADS_AND_HYPERLINKS_LOGS}` (
  `id` INT( 14 ) NOT NULL default 0,
  `typ` enum('download','hyperlink') NOT NULL default 'download',
  `timestamp` int(14) NOT NULL default 0,
  `monat` int(6) DEFAULT 0,
  `anzahl` int(14) NOT NULL default 0,
   INDEX (`id`),
   INDEX (`monat`),
   INDEX (`typ`)
) TYPE=MyISAM;





ALTER TABLE `{CHC_TABLE_ACCESS}`
	CHANGE `typ` `typ` ENUM('tag','kw', 'monat', 'jahr', 'tageszeit_wochentag_start', 'tageszeit', 'wochentag_1_Mon', 'wochentag_2_Tue', 'wochentag_3_Wed', 'wochentag_4_Thu', 'wochentag_5_Fri', 'wochentag_6_Sat', 'wochentag_7_Sun') NOT NULL default 'tag';

ALTER TABLE `{CHC_TABLE_LOCALE_INFORMATION}`
	CHANGE `typ` `typ` ENUM('host_tld','country', 'language') NOT NULL default 'country',
	ADD `monat` INT(6) DEFAULT 0 AFTER `timestamp`,
	ADD INDEX (`monat`);
UPDATE `{CHC_TABLE_LOCALE_INFORMATION}`
	SET monat = -1;


ALTER TABLE `{CHC_TABLE_SCREEN_RESOLUTIONS}`
	ADD `monat` INT(6) DEFAULT 0 AFTER `timestamp`;
UPDATE `{CHC_TABLE_SCREEN_RESOLUTIONS}`
	SET monat = -1;


ALTER TABLE `{CHC_TABLE_REFERRERS}`
	CHANGE `typ` `typ` ENUM('referrer','domain') NOT NULL default 'referrer',
	ADD `id` INT( 14 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST,
	ADD `monat` INT(6) DEFAULT 0 AFTER `timestamp`,
	ADD INDEX (`monat`),
	ADD INDEX ( `homepage_id` );
UPDATE `{CHC_TABLE_REFERRERS}`
	SET monat = -1;

ALTER TABLE `{CHC_TABLE_SEARCH_ENGINES}`
	CHANGE `typ` `typ` ENUM('suchmaschine','suchphrase', 'suchwort') NOT NULL default 'suchmaschine',
	ADD `monat` INT(6) DEFAULT 0 AFTER `timestamp`,
	ADD INDEX (`monat`);
UPDATE `{CHC_TABLE_SEARCH_ENGINES}`
	SET monat = -1;

ALTER TABLE `{CHC_TABLE_USER_AGENTS}`
	CHANGE `typ` `typ` ENUM('user_agent','browser', 'os', 'robot', 'version~browser', 'version~os', 'version~robot') NOT NULL default 'user_agent',
	ADD `monat` INT(6) DEFAULT 0 AFTER `timestamp`,
	ADD INDEX (`monat`);
UPDATE `{CHC_TABLE_USER_AGENTS}`
	SET monat = -1;


ALTER TABLE `{CHC_TABLE_COUNTED_USERS}`
	ADD `letzte_seite` INT( 14 ) NOT NULL default 0 AFTER `seitenaufrufe`;


ALTER TABLE `{CHC_TABLE_IGNORED_USERS}`
	ADD INDEX (`ip`(11),`user_agent`(20));


ALTER TABLE `{CHC_TABLE_LOG_DATA}`
	ADD `downloads` text NOT NULL AFTER `seiten`,
	ADD `hyperlinks` text NOT NULL AFTER `downloads`;

ALTER TABLE `{CHC_TABLE_DATA}`
	ADD `js_robots` INT(14) NOT NULL AFTER `js_alle`;


ALTER TABLE `{CHC_TABLE_PAGES}`
	ADD `id` INT( 14 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	ADD `anzahl_einstiegsseite` INT( 14 ) NOT NULL default 0 AFTER `anzahl`,
	ADD `anzahl_ausgangsseite` INT( 14 ) NOT NULL default 0 AFTER `anzahl_einstiegsseite`,
	ADD `monat` INT(6) DEFAULT 0 AFTER `timestamp`,
	ADD INDEX (`monat`);
UPDATE `{CHC_TABLE_PAGES}`
	SET monat = -1;


ALTER TABLE `{CHC_TABLE_ONLINE_USERS}`
	ADD `timestamp_erster_aufruf` INT( 14 ) NOT NULL default 0 AFTER `is_robot`,
	CHANGE `timestamp` `timestamp_letzter_aufruf` INT( 14 ) NOT NULL default 0;




INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('status_einstiegs_ausgangsseiten', '1');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('wordwrap_downloads', '30');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('statistiken_anzahl_downloads', '10');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('statistiken_anzahl_hyperlinks', '10');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('robots_von_js_stats_ausschliessen', '1');
INSERT INTO `{CHC_TABLE_CONFIG}` VALUES ('php_self_oder_request_uri', 'REQUEST_URI');

UPDATE `{CHC_TABLE_CONFIG}`
	SET value = CONCAT( value, 'p; month; p_month; d_month; h_month; ref_month; refdom_month; kp_month; se_month; b_month; os_month; r_month; clh_month; res_month; ')
	WHERE setting = 'seiten_query_string_bereinigung_variablen';