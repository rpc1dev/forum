<?php


/*
 **************************************
 *
 * index.php
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


require( 'includes/config.inc.php' );
require( 'includes/common.inc.php' );
require( 'includes/functions.inc.php' );
require( 'includes/mysql.class.php' );

$_CHC_DB = new chC_mysql( $_CHC_DBCONFIG['server'], $_CHC_DBCONFIG['user'], $_CHC_DBCONFIG['password'], $_CHC_DBCONFIG['database'] );

$_CHC_CONFIG = chc_get_config();

header( 'Location: '. $_CHC_CONFIG['default_counter_url'] .'/stats/index.php' );

?>
