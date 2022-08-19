<?php

/*
 **************************************
 *
 * administration/news.inc.php
 * -------------
 *
 * last modified:	2005-01-23
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


if( !defined( 'CHC_ACP' ) )
{
	header( 'Location: http://'. $_SERVER['SERVER_NAME'] . dirname( $_SERVER['PHP_SELF'] ) .'/index.php' ); 
	exit;
}

$url = 'http://' . $_SERVER['SERVER_NAME'] . dirname( $_SERVER['PHP_SELF'] ) . '/';
$file = @file( 'http://www.christoph-bachner.net/php-scripts/chCounter/news.php?url='. rawurlencode( $url ) .'&version='. rawurlencode( $_CHC_CONFIG['script_version'] ). '&lang='. $_CHC_CONFIG['lang_administration'] );
if( $file === false )
{
	 ?>
<iframe src="<?php print 'http://www.christoph-bachner.net/php-scripts/chCounter/news.php?url=' . rawurlencode( $url ) .'&amp;version='. rawurlencode( $_CHC_CONFIG['script_version'] ) .'&amp;lang='. $_CHC_CONFIG['lang_administration'] .'&amp;frame=true'; ?>" style="width:95%; height:100% border: 0px;" frameborder="0" name="news">
<?php print $_CHC_LANG['error_contacting_news_file']; ?>
</iframe>
	 <?php
}
else
{
	$news = trim( implode( '', $file ) );
	print empty( $news ) ? '<div style="text-align:center;">'.$_CHC_LANG['no_news_available_at_present'].'</div>' : $news;
}
?>
<br />
<br />
<br />
<br />
