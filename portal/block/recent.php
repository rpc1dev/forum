<?php
/*
*
* @package phpBB3 Portal  a.k.a canverPortal  ( www.phpbb3portal.net )
* @version $Id: recent.php,v 1.5 2008/02/09 08:18:14 angelside Exp $
* @copyright (c) Canver Software - www.canversoft.net
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
if (!defined('IN_PHPBB') or !defined('IN_PORTAL'))
{
	die('Hacking attempt');
	exit;
}

/**
*/

//
// Exclude forums
//
$sql_where = '';
if ($config['portal_exclude_forums'])
{
	$exclude_forums = explode(',', $config['portal_exclude_forums']);
	foreach ($exclude_forums as $i => $id)
	{
		if ($id > 0)
		{
			$sql_where .= ' AND forum_id <> ' . trim($id);
			$recent_where .= ' AND phpbb_topics.forum_id != ' . trim($id);
		}
	}
}

//
// Recent announcements
//
$sql = 'SELECT topic_title, forum_id, topic_id
	FROM ' . TOPICS_TABLE . '
	WHERE topic_status <> ' . FORUM_LINK . '
		AND topic_approved = 1 
		AND ( topic_type = ' . POST_ANNOUNCE . ' OR topic_type = ' . POST_GLOBAL . ' )
		' . $sql_where . '
	ORDER BY topic_time DESC';

$result = $db->sql_query_limit($sql, $config['portal_max_topics']);

while( ($row = $db->sql_fetchrow($result)) && ($row['topic_title']) )
{
	// auto auth
	if ( ($auth->acl_get('f_read', $row['forum_id'])) || ($row['forum_id'] == '0') )
	{
		$template->assign_block_vars('latest_announcements', array(
			'TITLE'	 		=> character_limit($row['topic_title'], $config['portal_recent_title_limit']),
			'FULL_TITLE'	=> censor_text($row['topic_title']),
			'U_VIEW_TOPIC'	=> append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'f=' . $row['forum_id'] . '&amp;t=' . $row['topic_id'])
		));
	}
}
$db->sql_freeresult($result);

//
// Recent hot topics
//
$sql = 'SELECT topic_title, forum_id, topic_id
	FROM ' . TOPICS_TABLE . '
	WHERE topic_approved = 1 
		AND topic_replies >=' . $config['hot_threshold'] . '
		' . $sql_where . '
	ORDER BY topic_time DESC';

$result = $db->sql_query_limit($sql, $config['portal_max_topics']);

while( ($row = $db->sql_fetchrow($result)) && ($row['topic_title']) )
{
	// auto auth
	if ( ($auth->acl_get('f_read', $row['forum_id'])) || ($row['forum_id'] == '0') )
	{
		$template->assign_block_vars('latest_hot_topics', array(
			'TITLE'	 		=> character_limit($row['topic_title'], $config['portal_recent_title_limit']),
			'FULL_TITLE'	=> censor_text($row['topic_title']),
			'U_VIEW_TOPIC'	=> append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'f=' . $row['forum_id'] . '&amp;t=' . $row['topic_id'])
		));
	}
}
$db->sql_freeresult($result);

//
// Recent posts (only show normal posts)
//
$sql = "SELECT topic_title, phpbb_topics.forum_id, topic_id, topic_first_poster_name, topic_last_poster_name, topic_last_post_time, 
		forum_name, topic_views, topic_replies, REPLACE(icons_url,'misc/','misc/r') as icon_url 
	FROM " . TOPICS_TABLE . "
	LEFT JOIN phpbb_forums ON phpbb_topics.forum_id = phpbb_forums.forum_id 
	LEFT JOIN phpbb_icons ON phpbb_topics.icon_id = phpbb_icons.icons_id 
	WHERE topic_status <> 2 
		AND topic_approved = 1 
		" . $recent_where . "
	ORDER BY topic_last_post_time DESC";

$result = $db->sql_query_limit($sql, $config['portal_max_topics']);

while( ($row = $db->sql_fetchrow($result)) && ($row['topic_title']) )
{
	// auto auth
	if ( ($auth->acl_get('f_read', $row['forum_id'])) || ($row['forum_id'] == '0') )
	{
		$template->assign_block_vars('latest_topics', array(
			'TITLE'	 		=> character_limit($row['topic_title'], $config['portal_recent_title_limit']),
			'FULL_TITLE'	=> censor_text($row['topic_title']),
			'U_VIEW_TOPIC'	=> append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'f=' . $row['forum_id'] . '&amp;t=' . $row['topic_id'])
		));
	}	
}
$db->sql_freeresult($result);


/*
//
// Recent active topic
//
$last_post_time_sql = ($sort_days) ? ' AND t.topic_last_post_time > ' . (time() - ($sort_days * 24 * 3600)) : '';

$sql = 'SELECT topic_title, forum_id, topic_id, topic_time, topic_first_poster_name
	FROM ' . TOPICS_TABLE . '
	WHERE topic_status <> ' . FORUM_LINK . "
		$last_post_time_sql
		AND topic_moved_id = 0
		AND topic_approved = 1 
		AND topic_type = " . POST_NORMAL . '
		' . $sql_where . '
	ORDER BY topic_time DESC';

$result = $db->sql_query_limit($sql, $config['portal_max_topics']);

while( ($row = $db->sql_fetchrow($result)) && ($row['topic_title']) )
{
	// auto auth
	if ( ($auth->acl_get('f_read', $row['forum_id'])) || ($row['forum_id'] == '0') )
	{
		$template->assign_block_vars('active_topics', array(
			'TITLE'	 		=> character_limit($row['topic_title'], $config['portal_recent_title_limit']),
			'FULL_TITLE'	=> censor_text($row['topic_title']),
			'DATE'			=> $user->format_date($row['topic_time']),
			'POSTER'		=> $row['topic_first_poster_name'],
			'U_VIEW_TOPIC'	=> append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'f=' . $row['forum_id'] . '&amp;t=' . $row['topic_id'])
		));
	}	
}
$db->sql_freeresult($result);
*/

$template->assign_vars(array(
	'S_DISPLAY_RECENT'			=> true,
));

?>