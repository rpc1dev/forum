<?php
/*
*
* @package phpBB3 Portal  a.k.a canverPortal  ( www.phpbb3portal.net )
* @version $Id: statistics.php,v 1.4 2008/02/09 08:18:14 angelside Exp $
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

// switch idea from phpBB2 :p
function get_db_stat($mode)
{
	global $db, $user;

	switch( $mode )
	{
	/*
		case 'newposttotal':
			$sql = 'SELECT COUNT(distinct post_id) AS newpost_total
				FROM ' . POSTS_TABLE . '
				WHERE post_time > ' . $user->data['session_last_visit'];
		break;

		case 'newtopictotal':
			$sql = 'SELECT COUNT(distinct p.post_id) AS newtopic_total
				FROM ' . POSTS_TABLE . ' p, ' . TOPICS_TABLE . ' t
				WHERE p.post_time > ' . $user->data['session_last_visit'] . '
				AND p.post_id = t.topic_first_post_id';
		break;
			
		case 'newannouncmenttotal':
			$sql = 'SELECT COUNT(distinct t.topic_id) AS newannouncment_total
				FROM ' . TOPICS_TABLE . ' t, ' . POSTS_TABLE . ' p
				WHERE t.topic_type = ' . POST_ANNOUNCE . '
					AND p.post_time > ' . $user->data['session_last_visit'] . '
					AND p.post_id = t.topic_first_post_id';
		break;

		case 'newstickytotal':
			$sql = 'SELECT COUNT(distinct t.topic_id) AS newsticky_total
				FROM ' . TOPICS_TABLE . ' t, ' . POSTS_TABLE . ' p
				WHERE t.topic_type = ' . POST_STICKY . '
					AND p.post_time > ' . $user->data['session_last_visit'] . '
					AND p.post_id = t.topic_first_post_id';
		break;	
	*/
		case 'announcmenttotal':
			$sql = 'SELECT COUNT(distinct t.topic_id) AS announcment_total
				FROM ' . TOPICS_TABLE . ' t, ' . POSTS_TABLE . ' p
				WHERE t.topic_type = ' . POST_ANNOUNCE . '
					AND p.post_id = t.topic_first_post_id';
		break;

		case 'stickytotal':
			$sql = 'SELECT COUNT(distinct t.topic_id) AS sticky_total
				FROM ' . TOPICS_TABLE . ' t, ' . POSTS_TABLE . ' p
				WHERE t.topic_type = ' . POST_STICKY . '
					AND p.post_id = t.topic_first_post_id';
		break;

		case 'attachmentstotal':
			$sql = 'SELECT COUNT(attach_id) AS attachments_total
					FROM ' . ATTACHMENTS_TABLE;
		break;
	}
	
	if ( !($result = $db->sql_query($sql)) )
	{
		return false;
	}

	$row = $db->sql_fetchrow($result);
 
	switch ( $mode )
	{
	/*
		case 'newposttotal':
			return $row['newpost_total'];
		break;

		case 'newtopictotal':
			return $row['newtopic_total'];
		break;

		case 'newannouncmenttotal':
			return $row['newannouncment_total'];
		break;
	
		case 'newstickytotal':
			return $row['newsticky_total'];
		break;
	*/

		case 'announcmenttotal':
			return $row['announcment_total'];
		break;
			

		case 'stickytotal':
			return $row['sticky_total'];
		break;

		case 'attachmentstotal':
			return $row['attachments_total'];
		break;
	}
	return false;
}

// Set some stats, get posts count from forums data if we... hum... retrieve all forums data
$total_posts		= $config['num_posts'];
$total_topics		= $config['num_topics'];
$total_users		= $config['num_users'];

// no last user color, no more SQL codes ;)
$newest_user		= $config['newest_username'];
$newest_uid			= $config['newest_user_id'];

$l_total_user_s 	= ($total_users == 0) ? 'TOTAL_USERS_ZERO' : 'TOTAL_USERS_OTHER';
$l_total_post_s 	= ($total_posts == 0) ? 'TOTAL_POSTS_ZERO' : 'TOTAL_POSTS_OTHER';
$l_total_topic_s	= ($total_topics == 0) ? 'TOTAL_TOPICS_ZERO' : 'TOTAL_TOPICS_OTHER';

// avarage stat
$board_days = ( time() - $config['board_startdate'] ) / 86400;

$topics_per_day		= round($total_topics / $board_days, 0);
$posts_per_day 		= round($total_posts / $board_days, 0);
$users_per_day 		= round($total_users / $board_days, 0);
$topics_per_user	= round($total_topics / $total_users, 0);
$posts_per_user 	= round($total_posts / $total_users, 0);
$posts_per_topic 	= ( $total_topics > 0 ) ? round($total_posts / $total_topics, 0) : 0 ;
	

if ($topics_per_day > $total_topics)
{
	$topics_per_day = $total_topics;
}

if ($posts_per_day > $total_posts)
{
	$posts_per_day = $total_posts;
}

if ($users_per_day > $total_users)
{
	$users_per_day = $total_users;
}

if ($topics_per_user > $total_topics)
{
	$topics_per_user = $total_topics;
}

if ($posts_per_user > $total_posts)
{
	$posts_per_user = $total_posts;
}

if ($posts_per_topic > $total_posts)
{
	$posts_per_topic = $total_posts;
}

$l_topics_per_day_s = ($total_topics == 0) ? 'TOPICS_PER_DAY_ZERO' : 'TOPICS_PER_DAY_OTHER';
$l_posts_per_day_s = ($total_posts == 0) ? 'POSTS_PER_DAY_ZERO' : 'POSTS_PER_DAY_OTHER';
$l_users_per_day_s = ($total_users == 0) ? 'USERS_PER_DAY_ZERO' : 'USERS_PER_DAY_OTHER';
$l_topics_per_user_s = ($total_topics == 0) ? 'TOPICS_PER_USER_ZERO' : 'TOPICS_PER_USER_OTHER';
$l_posts_per_user_s = ($total_posts == 0) ? 'POSTS_PER_USER_ZERO' : 'POSTS_PER_USER_OTHER';
$l_posts_per_topic_s = ($total_posts == 0) ? 'POSTS_PER_TOPIC_ZERO' : 'POSTS_PER_TOPIC_OTHER';

// Assign specific vars
$template->assign_vars(array(
	'S_DISPLAY_ADVANCED_STAT' => true,
	'TOTAL_POSTS'	=> sprintf($user->lang[$l_total_post_s], $total_posts),
	'TOTAL_TOPICS'	=> sprintf($user->lang[$l_total_topic_s], $total_topics),
	'TOTAL_USERS'	=> sprintf($user->lang[$l_total_user_s], $total_users),
	'NEWEST_USER'	=> sprintf($user->lang['NEWEST_USER'], '<a href="' . append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=viewprofile&amp;u=' . $newest_uid) . '">' . $newest_user . '</a>'),
/*
	'S_NEW_POSTS'	=> get_db_stat('newposttotal'),
	'S_NEW_TOPIC'	=> get_db_stat('newtopictotal'),
	'S_NEW_ANN'		=> get_db_stat('newannouncmenttotal'),
	'S_NEW_SCT'		=> get_db_stat('newstickytotal'),
*/
	'S_ANN'			=> get_db_stat('announcmenttotal'),
	'S_SCT'			=> get_db_stat('stickytotal'),
	'S_TOT_ATTACH'	=> get_db_stat('attachmentstotal'),
	
	// avarage stat
	'TOPICS_PER_DAY'	=> sprintf($user->lang[$l_topics_per_day_s], $topics_per_day),	
	'POSTS_PER_DAY'		=> sprintf($user->lang[$l_posts_per_day_s], $posts_per_day),	
	'USERS_PER_DAY'		=> sprintf($user->lang[$l_users_per_day_s], $users_per_day),	
	'TOPICS_PER_USER'	=> sprintf($user->lang[$l_topics_per_user_s], $topics_per_user),	
	'POSTS_PER_USER'	=> sprintf($user->lang[$l_posts_per_user_s], $posts_per_user),	
	'POSTS_PER_TOPIC'	=> sprintf($user->lang[$l_posts_per_topic_s], $posts_per_topic),
));

?>