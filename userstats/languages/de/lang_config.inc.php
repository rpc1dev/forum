<?php

/*
 **************************************
 *
 * languages/de/lang_config.inc.php
 * -------------
 *
 * last modified:	2005-02-26
 * -------------
 *
 * project:	chCounter
 * version:	3.1.1
 * copyright:	© 2005 Christoph Bachner
 * license:	GPL vs2.0 [ see docs/license.txt ]
 * contact:	www.christoph-bachner.net
 *
 **************************************
 *
*/


$_CHC_LANG = !isset( $_CHC_LANG ) ? array() : $_CHC_LANG;
$_CHC_LANG = array_merge( $_CHC_LANG, array(


'CONFIG' => array(

	/* Translator: */
	'translator' => '',


	/* special settings for this language */
	'lang_code' => 'de',
	'decimal_separator' => ',',	// number formatting
	'thousands_separator' => '.',	// number formatting

	// see http://php.net/date
	'DATE_FORMATS' => array(

		'common_date_format:complete' => 'd.m.Y, H:i:s',
		'common_date_format:date_only' => 'd.m.Y',

		'access_statistics_month:short' => 'M.',
		'access_statistics_month:long' => 'F Y',
		'access_statistics_day:short' => 'd.m.',
		'access_statistics_day:long' => 'l, d.m.Y',
		'access_statistics_calendar_week:beginning,end' => 'd.m.Y',
		'access_statistics_weekday:long' => 'l',
		'access_statistics_year:short' => 'y',
		'access_statistics_year:long' => 'Y',

		'all_lists' => 'd.m.Y, H:i:s',

		'online_users' => 'd.m.Y, H:i:s'
	)

) ) );

?>