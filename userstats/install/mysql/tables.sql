CREATE TABLE `{CHC_TABLE_ACCESS}` (
  `timestamp` int(14) NOT NULL default '0',
  `typ` ENUM('tag','kw', 'monat', 'jahr', 'tageszeit_wochentag_start', 'tageszeit', 'wochentag_1_Mon', 'wochentag_2_Tue', 'wochentag_3_Wed', 'wochentag_4_Thu', 'wochentag_5_Fri', 'wochentag_6_Sat', 'wochentag_7_Sun') NOT NULL default 'tag',
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



CREATE TABLE `{CHC_TABLE_CONFIG}` (
  `setting` varchar(255) NOT NULL default '',
  `value` text NOT NULL,
  PRIMARY KEY  (`setting`)
) TYPE=MyISAM;



CREATE TABLE `{CHC_TABLE_COUNTED_USERS}` (
  `nr` int(14) NOT NULL default '0',
  `ip` varchar(255) NOT NULL default '',
  `user_agent` varchar(255) NOT NULL default '',
  `is_robot` int(1) NOT NULL default 0,
  `timestamp` int(14) NOT NULL default 0,
  `seitenaufrufe` int(14) NOT NULL default 0,
  `letzte_seite` INT( 14 ) NOT NULL default 0,
  `js` int(1) NOT NULL default 0,
  `aufloesung` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`nr`),
  KEY `schluessel` (`ip`(11),`user_agent`(20))
) TYPE=MyISAM;



CREATE TABLE `{CHC_TABLE_DATA}` (
  `besucher_gesamt` int(14) NOT NULL default 0,
  `besucher_heute` int(14) NOT NULL default 0,
  `heute_timestamp` int(14) NOT NULL default 0,
  `besucher_gestern` int(14) NOT NULL default 0,
  `max_online:anzahl` int(14) NOT NULL default 0,
  `max_online:timestamp` int(14) NOT NULL default 0,
  `max_besucher_pro_tag:anzahl` int(14) NOT NULL default 0,
  `max_besucher_pro_tag:timestamp` int(14) NOT NULL default 0,
  `seitenaufrufe_gesamt` int(14) NOT NULL default 0,
  `seitenaufrufe_heute` int(14) NOT NULL default 0,
  `seitenaufrufe_gestern` int(14) NOT NULL default 0,
  `seitenaufrufe_pro_besucher:besucher` int(14) NOT NULL default 0,
  `seitenaufrufe_pro_besucher:seitenaufrufe` int(14) NOT NULL default 0,
  `durchschnittlich_pro_tag:timestamp` int(14) NOT NULL default 0,
  `durchschnittlich_pro_tag:besucher` int(14) NOT NULL default 0,
  `durchschnittlich_pro_tag:seitenaufrufe` int(14) NOT NULL default 0,
  `max_seitenaufrufe_pro_tag:anzahl` int(14) NOT NULL default 0,
  `max_seitenaufrufe_pro_tag:timestamp` int(14) NOT NULL default 0,
  `js_alle` int(14) NOT NULL default 0,
  `js_robots` INT(14) NOT NULL default 0,
  `js_aktiv` int(14) NOT NULL default 0,
  `timestamp_letztes_db_aufraeumen` int(14) NOT NULL default 0
) TYPE=MyISAM;



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



CREATE TABLE `{CHC_TABLE_IGNORED_USERS}` (
  `id` INT( 14 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `ip` varchar(255) NOT NULL default '',
  `host` varchar(255) NOT NULL default '',
  `grund` varchar(255) NOT NULL default '',
  `tmp_blocked` int(1) NOT NULL default 0,
  `user_agent` varchar(255) NOT NULL default '',
  `is_robot` int(1) NOT NULL default 0,
  `timestamp` int(14) NOT NULL default 0,
  `seitenaufrufe` int(14) NOT NULL default 0,
  INDEX (`ip`(11),`user_agent`(20))
) TYPE=MyISAM;



CREATE TABLE `{CHC_TABLE_LOCALE_INFORMATION}` (
  `typ` ENUM('host_tld','country', 'language') NOT NULL default 'country',
  `wert` varchar(255) NOT NULL default '',
  `anzahl` int(14) NOT NULL default '0',
  `timestamp` int(14) NOT NULL default '0',
  `monat` int(6) DEFAULT 0,
  INDEX (`typ`),
  INDEX (`monat`)
) TYPE=MyISAM;



CREATE TABLE `{CHC_TABLE_LOG_DATA}` (
  `nr` int(14) NOT NULL default '0',
  `ip` varchar(255) NOT NULL default '',
  `host` varchar(255) NOT NULL default '',
  `user_agent` varchar(255) NOT NULL default '',
  `is_robot` int(1) NOT NULL default '0',
  `http_accept_language` VARCHAR( 255 ) NOT NULL default '', 
  `timestamp` int(14) NOT NULL default '0',
  `referrer` varchar(255) NOT NULL default '',
  `seitenaufrufe` int(14) NOT NULL default '0',
  `seiten` text NOT NULL,
  `downloads` text NOT NULL,
  `hyperlinks` text NOT NULL,
  `js` int(1) NOT NULL default '0',
  `aufloesung` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`nr`),
  KEY `timestamp` (`timestamp`)
) TYPE=MyISAM;



CREATE TABLE `{CHC_TABLE_ONLINE_USERS}` (
  `id` INT( 14 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nr` int(14) NOT NULL default '0',
  `ip` varchar(255) NOT NULL default '',
  `user_agent` varchar(255) NOT NULL default '',
  `is_robot` int(1) NOT NULL default '0',
  `timestamp_erster_aufruf` int(14) NOT NULL default 0,
  `timestamp_letzter_aufruf` int(14) NOT NULL default 0,
  `seite` varchar(255) NOT NULL default '',
  `homepage_id` int(2) NOT NULL default '0',
  `seitenaufrufe` int(14) NOT NULL default '0',
  KEY `nr` (`nr`)
) TYPE=MyISAM;



CREATE TABLE `{CHC_TABLE_PAGES}` (
  `id` INT( 14 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `wert` varchar(255) NOT NULL default '',
  `homepage_id` int(2) NOT NULL default '0',
  `counter_verzeichnis` int(1) NOT NULL default '0',
  `anzahl` int(14) NOT NULL default '0',
  `anzahl_einstiegsseite` INT( 14 ) NOT NULL default 0,
  `anzahl_ausgangsseite` INT( 14 ) NOT NULL default 0,
  `titel` varchar(255) NOT NULL default '',
  `timestamp` int(14) NOT NULL default '0',
  `monat` int(6) DEFAULT 0,
  INDEX (`wert`),
  INDEX (`homepage_id`),
  INDEX (`monat`)
) TYPE=MyISAM;



CREATE TABLE `{CHC_TABLE_REFERRERS}` (
  `id` INT( 14 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `typ` ENUM('referrer','domain') NOT NULL default 'referrer',
  `wert` varchar(255) NOT NULL default '',
  `homepage_id` int(2) NOT NULL default '0',
  `anzahl` int(14) NOT NULL default '0',
  `timestamp` int(14) NOT NULL default '0',
  `monat` int(6) DEFAULT 0,
  INDEX (`typ`),
  KEY `typ_2` (`typ`,`wert`(20)),
  INDEX ( `homepage_id` ),
  INDEX (`monat`)
) TYPE=MyISAM;



CREATE TABLE `{CHC_TABLE_SCREEN_RESOLUTIONS}` (
  `wert` varchar(255) NOT NULL default '',
  `anzahl` int(14) NOT NULL default '0',
  `timestamp` int(14) NOT NULL default '0',
  `monat` int(6) DEFAULT 0
) TYPE=MyISAM;



CREATE TABLE `{CHC_TABLE_SEARCH_ENGINES}` (
  `typ` ENUM('suchmaschine','suchphrase', 'suchwort') NOT NULL default 'suchmaschine',
  `wert` varchar(255) NOT NULL default '',
  `anzahl` int(14) NOT NULL default '0',
  `timestamp` int(14) NOT NULL default '0',
  `monat` int(6) DEFAULT 0,
  INDEX (`typ`),
  INDEX (`typ`,`wert`(10)),
  INDEX (`monat`)
) TYPE=MyISAM;



CREATE TABLE `{CHC_TABLE_USER_AGENTS}` (
  `typ` ENUM('user_agent','browser', 'os', 'robot', 'version~browser', 'version~os', 'version~robot') NOT NULL default 'user_agent',
  `wert` varchar(255) NOT NULL default '',
  `anzahl` int(14) NOT NULL default 0,
  `timestamp` int(14) NOT NULL default 0,
  `monat` int(6) DEFAULT 0,
  INDEX (`typ`),
  KEY `typ_2` (`typ`,`wert`(20)),
  INDEX (`monat`)
) TYPE=MyISAM;