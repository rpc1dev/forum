ALTER TABLE `{CHC_TABLE_COUNTED_USERS}` ADD `is_robot` int(1) NOT NULL default '0' AFTER `user_agent`;
ALTER TABLE `{CHC_TABLE_LOG_DATA}` ADD `is_robot` int(1) NOT NULL default '0' AFTER `user_agent`;
ALTER TABLE `{CHC_TABLE_ONLINE_USERS}`
	ADD `id` INT( 14 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST,
	ADD `is_robot` int(1) NOT NULL default '0' AFTER `user_agent`;


DELETE FROM `{CHC_TABLE_USER_AGENTS}` WHERE wert = '';
DELETE FROM `{CHC_TABLE_LOCALE_INFORMATION}` WHERE wert = '';

CREATE TABLE `{CHC_TABLE_IGNORED_USERS}` (
   `id` INT( 14 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `ip` varchar(255) NOT NULL default '',
  `host` varchar(255) NOT NULL default '',
  `grund` varchar(255) NOT NULL default '',
  `tmp_blocked` int(1) NOT NULL default 0,
  `user_agent` varchar(255) NOT NULL default '',
  `is_robot` int(1) NOT NULL default 0,
  `timestamp` int(14) NOT NULL default 0,
  `seitenaufrufe` int(14) NOT NULL default 0
) TYPE=MyISAM;