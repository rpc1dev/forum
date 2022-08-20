<?php
/*
*
* @package phpBB3 Portal  a.k.a canverPortal  ( www.phpbb3portal.net )
* @version $Id: portal.php,v 1.11 2008/02/09 08:18:13 angelside Exp $
* @copyright (c) Canver Software - www.canversoft.net
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

// Note: If you would like to have the portal in a different location than in the main phpBB3 directory
// You must change the following variable, and change the 'U_PORTAL' template variable in functions.php
define('IN_PHPBB', true);
define('IN_PORTAL', true);
define('PHPBB_ROOT_PATH', './');
define('PORTAL_ROOT_PATH','./portal/');

$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$portal_root_path = (defined('PORTAL_ROOT_PATH')) ? PORTAL_ROOT_PATH : './portal/';

$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($portal_root_path . '/includes/functions.'.$phpEx);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup('portal');

// show login box and user menu

//  acp de aç/kapa yok, dil dosyasýna ek: hiç doðumgünü yoksa evet seçili olsa bile blok görünmez.
// SQL bilgisi yok -  - SQL eklenirse dil deðiþkeni de eklenmeli
//if ($config['portal_user_menu'])
//{
	// only registered user see user menu
	if ($user->data['is_registered'])
	{
		include($portal_root_path . '/block/user_menu.'.$phpEx);
	}
	else
	{
		include($portal_root_path . '/block/login_box.'.$phpEx);
	}
//}

if ($config['portal_attachments'])
{
	include($portal_root_path . '/block/attachments.'.$phpEx);
}

if ($config['portal_recent']) 
{ 
	include($portal_root_path . '/block/recent.'.$phpEx);
}

if ($config['portal_advanced_stat'])
{
	include($portal_root_path . '/block/statistics.'.$phpEx);
}

if ($config['portal_minicalendar'])
{
	include($portal_root_path . '/block/mini_cal.'.$phpEx);
}

if ($config['portal_link_us'])
{
	include($portal_root_path . '/block/link_us.'.$phpEx);
}

if ($config['portal_leaders'])
{
	include($portal_root_path . '/block/leaders.'.$phpEx);
}

if ($config['portal_wordgraph'])
{
	include($portal_root_path . '/block/wordgraph.'.$phpEx);
}

if ( $config['portal_poll_topic'] && $config['portal_poll_topic'] != "" )
{
	include($portal_root_path . '/block/poll.'.$phpEx);
}

if ($config['portal_load_last_visited_bots'])
{
	include($portal_root_path . '/block/latest_bots.'.$phpEx);
}

if ($config['portal_top_posters'])
{
	include($portal_root_path . '/block/top_posters.'.$phpEx);
}

if ($config['portal_latest_members'])
{
	include($portal_root_path . '/block/latest_members.'.$phpEx);
}

if ($config['portal_random_member'])
{
	include($portal_root_path . '/block/random_member.'.$phpEx);
}

if ($config['portal_clock'])
{
	$template->assign_vars(array(
		'S_DISPLAY_CLOCK' => true,
	));
}

if ($config['portal_links'])
{
//	include($portal_root_path . '/block/links.'.$phpEx);
	$template->assign_vars(array(
		'S_DISPLAY_LINKS' => true,
	));
}

if ($config['portal_welcome'])
{
	$template->assign_vars(array(
		'S_DISPLAY_WELCOME' 	=> true,
		'PORTAL_WELCOME_INTRO'	=> $config['portal_welcome_intro'],
	));
}

if ($config['portal_announcements'])
{
	include($portal_root_path . '/block/announcements.'.$phpEx);
	$template->assign_vars(array(
		'S_ANNOUNCE_COMPACT' => ($config['portal_announcements_style']) ? true : false,
	));
}

if ($config['portal_news'])
{
	include($portal_root_path . '/block/news.'.$phpEx);
	$template->assign_vars(array(
		'S_NEWS_COMPACT' => ($config['portal_news_style']) ? true : false,
	));
}

if ($config['portal_pay_s_block'] or $config['portal_pay_c_block'])
{
	include($portal_root_path . '/block/donate.'.$phpEx);
}


if ($config['portal_ads_small'])
{
	$template->assign_vars(array(
		'S_ADS_SMALL' 	=> ($config['portal_ads_small_box']) ? true : false,
		'ADS_SMALL_BOX'	=> $config['portal_ads_small_box'],
	));
}

if ($config['portal_ads_center'])
{
	$template->assign_vars(array(
		'S_ADS_CENTER' 		=> ($config['portal_ads_center_box']) ? true : false,
		'ADS_CENTER_BOX'	=> $config['portal_ads_center_box'],
	));
}


// acp de aç/kapa yok - SQL bilgisi yok - SQL eklenirse dil deðiþkeni de eklenmeli
if ($user->data['is_registered']/* and $config['portal_friends']*/)
{
	include($portal_root_path . '/block/friends.'.$phpEx);
}

// acp de aç/kapa yok - SQL bilgisi yok - SQL eklenirse dil deðiþkeni de eklenmeli
// dil dosyasýna ek: hiç doðumgünü yoksa evet seçili olsa bile blok görünmez.
//if ($config['show_birthdays'])
//{
	include($portal_root_path . '/block/birthday_list.'.$phpEx);
//}

// acp de aç/kapa yok - SQL bilgisi yok - SQL eklenirse dil deðiþkeni de eklenmeli
//if ($config['show_whois_online'])
//{
	include($portal_root_path . '/block/whois_online.'.$phpEx);
//}

// acp de aç/kapa yok - SQL bilgisi yok - SQL eklenirse dil deðiþkeni de eklenmeli
//if ($config['show_search'])
//{
	include($portal_root_path . '/block/search.'.$phpEx);
//}

// acp de aç/kapa yok - SQL bilgisi yok - SQL eklenirse dil deðiþkeni de eklenmeli
//if ($config['change_style'])
//{
//	include($portal_root_path . '/block/change_style.'.$phpEx); // stil seçince hata veriyor
//}

$template->assign_vars(array(
	'S_DISPLAY_JUMPBOX' 	=> true, // SQL + ACP eklenecek
	'PORTAL_LEFT_COLLUMN' 	=> $config['portal_left_collumn_width'],
	'PORTAL_RIGHT_COLLUMN' 	=> $config['portal_right_collumn_width'],
));

// output page
page_header($user->lang['PORTAL']);
//page_header($config['sitename']);

$template->set_filenames(array(
	'body' => 'portal/portal_body.html'
));

// SQL + ACP eklenecek
make_jumpbox(append_sid("{$phpbb_root_path}viewforum.$phpEx"));

page_footer();

?>