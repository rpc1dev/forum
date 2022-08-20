<?php

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$portal_root_path = (defined('PORTAL_ROOT_PATH')) ? PORTAL_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);

error_reporting(E_ALL);

@set_time_limit(0);

include($phpbb_root_path . 'common.'.$phpEx);

$user->session_begin();
$auth->acl($user->data);
$user->setup();

// variable
$portal_version = '1.2.2';

$sql = "SELECT config_value FROM {$table_prefix}config WHERE config_name = 'portal_version'"; 
$result = $db->sql_query_limit($sql, 1);
$data = $db->sql_fetchrow($result);

$portal_installed_version = ( $data['config_value'] == "1.1.0.b" ) ? "1.1.0b" : $data['config_value'] ;

// is the administrator logged in?
if (!$user->data['is_registered'])
{
	if ($user->data['is_bot'])
	{
		// the user is a bot, send them back to index...
		redirect(append_sid("{$phpbb_root_path}index.$phpEx"));
	}
	// the administrator is not logged in, let him/her login first here...
	login_box('', 'LOGIN');
}
else if ($user->data['user_type'] != USER_FOUNDER)
{
	trigger_error('NOT_AUTHORIZED');
}

$action = request_var('action', '');
$version = request_var('version', '');

if ( $action == "upgrade" && !isset( $version ) )
{
	redirect(append_sid($_SERVER['SCRIPT_NAME']));
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-gb" xml:lang="en-gb">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="content-language" content="en-gb" />
<meta http-equiv="content-style-type" content="text/css" />
<meta http-equiv="imagetoolbar" content="no" />
<title>phpBB3 Portal installation/update</title>
<link href="adm/style/admin.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body class="ltr">
<div id="wrap">
	<div id="page-header">
		<h1>phpBB3 Portal</h1>
	</div>
	<div id="page-body">
		<div id="acp">
		     <div class="panel">
		     	<span class="corners-top"><span></span></span>
					<div id="content">
						<div id="main">

<?php

switch( $action )
{
	case "upgrade":
?>
							<h1>Introduction</h1>
							<p>Beginning upgrade of phpBB3 Portal to <?php echo $portal_version; ?>.</p>
<?php
			switch( $version )
			{
				case "1.1.0b":
					$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_poll_limit', '3', 0)"; $db->sql_query($sql);
				case "1.2.0":
				case "1.2.1":
					$sql = "DELETE FROM {$table_prefix}config WHERE config_name = 'portal_poll_limit'"; $db->sql_query($sql);
					$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_poll_limit', '3', 0)"; $db->sql_query($sql);
					$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_poll_allow_vote', '1', 0)"; $db->sql_query($sql);

					$sql = "UPDATE {$table_prefix}config SET config_value = '{$portal_version}' WHERE config_name = 'portal_version'"; $db->sql_query_limit($sql, 1);
				break;
			}

?>
							<p>Config values updated!</p><br />
							<p>Upgrade complete, please read the following information.</p>
							<br /><br />

							<h1>Information</h1>
							<p>
								<ul>
									<li><strong>Attention!</strong> Please complete the editing of phpBB files if you have not already done so.</li>
									<li><strong>Attention!</strong> For security reasons, please delete <b>install_portal.php</b> at from your root directory after this install/update.</li>
									<li>After an installtion or update of phpBB3 Portal it is recommend you clear your cache.</li>
								</ul>
							</p>
<?php
	break;
	case "install" :

		if( $portal_installed_version == $portal_version )
		{
?>
							<h1>Introduction</h1>
							<p>You have already installed Portal version <?php echo $portal_version; ?>.</p>
							<p>Please delete this file from your phpBB3 directory and <a href="<?php echo $portal_root_path; ?>portal.php">return to your home page.</a></p>
<?php
		} else {

?>
							<h1>Introduction</h1>
							<p>Beginning installation of phpBB3 Portal.</p>
<?php

		function insert_new_module( &$module_data )
		{
			global $db;

			// no module_id means we're creating a new category/module
			if ($module_data['parent_id'])
			{
				$sql = 'SELECT left_id, right_id
					FROM ' . MODULES_TABLE . "
					WHERE module_class = '" . $db->sql_escape($module_data['module_class']) . "'
						AND module_id = " . (int) $module_data['parent_id'];
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrow($result);
				$db->sql_freeresult($result);

				if (!$row)
				{
				 	echo "Parent does not exist";
					return "Error";
				}

				// Workaround
				$row['left_id'] = (int) $row['left_id'];
				$row['right_id'] = (int) $row['right_id'];

				$sql = 'UPDATE ' . MODULES_TABLE . "
					SET left_id = left_id + 2, right_id = right_id + 2
					WHERE module_class = '" . $db->sql_escape($module_data['module_class']) . "'
						AND left_id > {$row['right_id']}";
				$db->sql_query($sql);

				$sql = 'UPDATE ' . MODULES_TABLE . "
					SET right_id = right_id + 2
					WHERE module_class = '" . $db->sql_escape($module_data['module_class']) . "'
						AND {$row['left_id']} BETWEEN left_id AND right_id";
				$db->sql_query($sql);

				$module_data['left_id'] = (int) $row['right_id'];
				$module_data['right_id'] = (int) $row['right_id'] + 1;
			}
			else
			{
				$sql = 'SELECT MAX(right_id) AS right_id
					FROM ' . MODULES_TABLE . "
					WHERE module_class = '" . $db->sql_escape($module_data['module_class']) . "'";
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrow($result);
				$db->sql_freeresult($result);

				$module_data['left_id'] = (int) $row['right_id'] + 1;
				$module_data['right_id'] = (int) $row['right_id'] + 2;
			}

			$sql = 'INSERT INTO ' . MODULES_TABLE . ' ' . $db->sql_build_array('INSERT', $module_data);
			$db->sql_query($sql);

			return $db->sql_nextid();

			add_log('admin', 'LOG_MODULE_ADD', $this->lang_name($module_data['module_langname']));
		}

		$module_data = array( 'module_enabled' => 1 , 'module_display' => 1 , 'module_basename' => '' , 'module_class' => 'acp' , 'parent_id' => 0 , 'left_id' => null , 'right_id' => null , 'module_langname' => 'ACP_PORTAL_INFO' , 'module_mode' => '' , 'module_auth' => '' );

		$portal_id = insert_new_module($module_data);

		$module_data = array( 'module_enabled' => 1 , 'module_display' => 1 , 'module_basename' => '' , 'module_class' => 'acp' , 'parent_id' => $portal_id , 'left_id' => null , 'right_id' => null , 'module_langname' => 'ACP_PORTAL_INFO' , 'module_mode' => '' , 'module_auth' => '' );

		$portal_id = insert_new_module($module_data);

		$module_data = array( 'module_enabled' => 1 , 'module_display' => 1 , 'module_basename' => 'portal' , 'module_class' => 'acp' , 'parent_id' => $portal_id , 'left_id' => null , 'right_id' => null , 'module_langname' => 'ACP_PORTAL_GENERAL_INFO' , 'module_mode' => 'general' , 'module_auth' => 'acl_a_board' ); insert_new_module($module_data);
		$module_data = array( 'module_enabled' => 1 , 'module_display' => 1 , 'module_basename' => 'portal' , 'module_class' => 'acp' , 'parent_id' => $portal_id , 'left_id' => null , 'right_id' => null , 'module_langname' => 'ACP_PORTAL_WELCOME_INFO' , 'module_mode' => 'welcome' , 'module_auth' => 'acl_a_board' ); insert_new_module($module_data);
		$module_data = array( 'module_enabled' => 1 , 'module_display' => 1 , 'module_basename' => 'portal' , 'module_class' => 'acp' , 'parent_id' => $portal_id , 'left_id' => null , 'right_id' => null , 'module_langname' => 'ACP_PORTAL_ANNOUNCE_INFO' , 'module_mode' => 'announcements' , 'module_auth' => 'acl_a_board' ); insert_new_module($module_data);
		$module_data = array( 'module_enabled' => 1 , 'module_display' => 1 , 'module_basename' => 'portal' , 'module_class' => 'acp' , 'parent_id' => $portal_id , 'left_id' => null , 'right_id' => null , 'module_langname' => 'ACP_PORTAL_NEWS_INFO' , 'module_mode' => 'news' , 'module_auth' => 'acl_a_board' ); insert_new_module($module_data);
		$module_data = array( 'module_enabled' => 1 , 'module_display' => 1 , 'module_basename' => 'portal' , 'module_class' => 'acp' , 'parent_id' => $portal_id , 'left_id' => null , 'right_id' => null , 'module_langname' => 'ACP_PORTAL_RECENT_INFO' , 'module_mode' => 'recent' , 'module_auth' => 'acl_a_board' ); insert_new_module($module_data);
		$module_data = array( 'module_enabled' => 1 , 'module_display' => 1 , 'module_basename' => 'portal' , 'module_class' => 'acp' , 'parent_id' => $portal_id , 'left_id' => null , 'right_id' => null , 'module_langname' => 'ACP_PORTAL_WORDGRAPH_INFO' , 'module_mode' => 'wordgraph' , 'module_auth' => 'acl_a_board' ); insert_new_module($module_data);
		$module_data = array( 'module_enabled' => 1 , 'module_display' => 1 , 'module_basename' => 'portal' , 'module_class' => 'acp' , 'parent_id' => $portal_id , 'left_id' => null , 'right_id' => null , 'module_langname' => 'ACP_PORTAL_PAYPAL_INFO' , 'module_mode' => 'paypal' , 'module_auth' => 'acl_a_board' ); insert_new_module($module_data);
		$module_data = array( 'module_enabled' => 1 , 'module_display' => 1 , 'module_basename' => 'portal' , 'module_class' => 'acp' , 'parent_id' => $portal_id , 'left_id' => null , 'right_id' => null , 'module_langname' => 'ACP_PORTAL_ATTACHMENTS_NUMBER_INFO' , 'module_mode' => 'attachments' , 'module_auth' => 'acl_a_board' ); insert_new_module($module_data);
		$module_data = array( 'module_enabled' => 1 , 'module_display' => 1 , 'module_basename' => 'portal' , 'module_class' => 'acp' , 'parent_id' => $portal_id , 'left_id' => null , 'right_id' => null , 'module_langname' => 'ACP_PORTAL_MEMBERS_INFO' , 'module_mode' => 'members' , 'module_auth' => 'acl_a_board' ); insert_new_module($module_data);
		$module_data = array( 'module_enabled' => 1 , 'module_display' => 1 , 'module_basename' => 'portal' , 'module_class' => 'acp' , 'parent_id' => $portal_id , 'left_id' => null , 'right_id' => null , 'module_langname' => 'ACP_PORTAL_POLLS_INFO' , 'module_mode' => 'polls' , 'module_auth' => 'acl_a_board' ); insert_new_module($module_data);
		$module_data = array( 'module_enabled' => 1 , 'module_display' => 1 , 'module_basename' => 'portal' , 'module_class' => 'acp' , 'parent_id' => $portal_id , 'left_id' => null , 'right_id' => null , 'module_langname' => 'ACP_PORTAL_BOTS_INFO' , 'module_mode' => 'bots' , 'module_auth' => 'acl_a_board' ); insert_new_module($module_data);
		$module_data = array( 'module_enabled' => 1 , 'module_display' => 1 , 'module_basename' => 'portal' , 'module_class' => 'acp' , 'parent_id' => $portal_id , 'left_id' => null , 'right_id' => null , 'module_langname' => 'ACP_PORTAL_MOST_POSTER_INFO' , 'module_mode' => 'poster' , 'module_auth' => 'acl_a_board' ); insert_new_module($module_data);
		$module_data = array( 'module_enabled' => 1 , 'module_display' => 1 , 'module_basename' => 'portal' , 'module_class' => 'acp' , 'parent_id' => $portal_id , 'left_id' => null , 'right_id' => null , 'module_langname' => 'ACP_PORTAL_MINICALENDAR_INFO' , 'module_mode' => 'minicalendar' , 'module_auth' => 'acl_a_board' ); insert_new_module($module_data);
		$module_data = array( 'module_enabled' => 1 , 'module_display' => 1 , 'module_basename' => 'portal' , 'module_class' => 'acp' , 'parent_id' => $portal_id , 'left_id' => null , 'right_id' => null , 'module_langname' => 'ACP_PORTAL_ADS_INFO' , 'module_mode' => 'ads' , 'module_auth' => 'acl_a_board' ); insert_new_module($module_data);

?>

					<p>Admin modules installed, moving on to config values.</p><br />
<?php

		// Delete variables that may have sneaked in.
		$sql = "DELETE FROM {$table_prefix}config WHERE config_name LIKE 'portal_%'"; $db->sql_query($sql);
	
		// alter 255 character to config_value
		$sql = "ALTER TABLE {$table_prefix}config CHANGE config_value config_value TEXT NOT NULL"; $db->sql_query($sql);

		// general
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_welcome_intro', 'Welcome to my community!', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_max_online_friends', '8', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_max_most_poster', '8', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_max_last_member', '8', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_welcome', '1', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_links', '1', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_link_us', '1', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_clock', '1', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_random_member', '1', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_latest_members', '1', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_top_posters', '1', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_leaders', '1', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_advanced_stat', '1', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_version', '{$portal_version}', 0)"; $db->sql_query($sql);

		// collumn_width
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_right_collumn_width', '180', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_left_collumn_width', '180', 0)"; $db->sql_query($sql);

		// poll
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_poll_topic', '1', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_poll_topic_id', '2', 0)"; $db->sql_query($sql);

		// last visit bots
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_last_visited_bots_number', '6', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_load_last_visited_bots', '1', 0)"; $db->sql_query($sql);

		// paypal donation
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_pay_acc', 'your@paypal.com', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_pay_s_block', '0', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_pay_c_block', '0', 0)"; $db->sql_query($sql);

		// recent topic
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_recent', '1', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_recent_title_limit', '100', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_max_topics', '10', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_exclude_forums', '', 0)"; $db->sql_query($sql);
		//$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_active', '1', 0)"; $db->sql_query($sql);
		
		// news
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_news_forum', '2', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_news_length', '250', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_number_of_news', '5', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_show_all_news', '1', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_news', '1', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_news_style', '1', 0)"; $db->sql_query($sql);

		// announcements
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_announcements', '1', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_announcements_style', '0', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_number_of_announcements', '1', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_announcements_day', '0', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_announcements_length', '200', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_global_announcements_forum', '2', 0)"; $db->sql_query($sql);

		// wordgraph
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_wordgraph_word_counts', '0', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_wordgraph_max_words', '30', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_wordgraph', '1', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_wordgraph_ratio', '4', 0)"; $db->sql_query($sql);

		// mini calendar
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_minicalendar', '1', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_minicalendar_today_color', '//FF0000', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_minicalendar_day_link_color', '//006F00', 0)"; $db->sql_query($sql);

		// attachments
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_attachments', '1', 0)"; $db->sql_query($sql);
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_attachments_number', '8', 0)"; $db->sql_query($sql);

		// ads
		//$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_ads_small', '0', 0)"; $db->sql_query($sql);
		//$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_ads_small_box', '-insert ads codes here-', 0)"; $db->sql_query($sql);
		//$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_ads_center', '0', 0)"; $db->sql_query($sql);
		//$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_ads_center_box', '-insert ads codes here-', 0)"; $db->sql_query($sql);

		//1.2.0
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_poll_limit', '3', 0)"; $db->sql_query($sql);

		//1.2.1
		$sql = "INSERT INTO {$table_prefix}config (config_name, config_value, is_dynamic) VALUES ('portal_poll_allow_vote', '1', 0)"; $db->sql_query($sql);

?>

							<p>Config values installed!</p><br />
							<p>Installation complete, please read the following information.</p>
							<br /><br />

							<h1>Information</h1>
							<p>
								<ul>
									<li><strong>Attention!</strong> Please complete the editing of phpBB files if you have not already done so.</li>
									<li><strong>Attention!</strong> For security reasons, please delete <b>install_portal.php</b> at from your root directory after this install/update.</li>
									<li>After an installtion or update of phpBB3 Portal it is recommend you clear your cache.</li>
								</ul>
							</p>

<?php

		}
	break;

	default:

		if( $portal_installed_version == $portal_version)
		{
?>

							<h1>Introduction</h1>
							<p>You have already installed Portal version <?php echo $portal_version; ?>.</p>
							<p>Please delete this file from your phpBB3 directory and <a href="<?php echo $portal_root_path; ?>portal.php">return to your home page.</a></p>

					
<?php
		} elseif( isset($portal_installed_version) && !empty($portal_installed_version) AND $portal_installed_version != $portal_version ) {
?>
							<h1>Introduction</h1>
							<p>Welcome to phpBB3 Portal upgrade utiliy. This will allow you to upgrade to phpBB3 Portal <?php echo $portal_version; ?>.</p>
							<p>For more information please visit phpBB3 Portal project web site: <a href="http://www.phpbb3portal.net">http://www.phpbb3portal.net</a></p>
							<br />
							
							<h1>Action</h1>
							<p>Please select your current version if it is not already selected and then press "Submit".</p>
							
							<h3>Portal version:</h3><br />
							<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="get">
								<input type="hidden" name="action" value="upgrade" />
								<select name="version">
<?php
			$upg_versions_array = array("1.1.0b", '1.2.0', '1.2.1');
			foreach( $upg_versions_array as $upg_version )
			{
				$selected = ( $upg_version == $portal_installed_version ) ? " selected=\"selected\"" : "";
?>
									<option value="<?php echo $upg_version; ?>"<?php echo $selected; ?>><?php echo $upg_version; ?></option>
<?php
			}
?>
								</select><br />
								<input class="button2" type="submit" name="submit" value="Submit" />
							</form>
							
							<br /><br />
							
							<h1>Information</h1>
							<p>
								<ul>
									<li><strong>Attention!</strong> Please complete the editing of phpBB files if you have not already done so.</li>
									<li><strong>Attention!</strong> For security reasons, please delete <b>install_portal.php</b> at from your root directory after this install/update.</li>
									<li>After an installtion or update of phpBB3 Portal it is recommend you clear your cache.</li>
								</ul>
							</p>
<?php
		} else {

?>
							<h1>Introduction</h1>
							<p>Welcome to the phpBB3 Portal installation utility. This will  allow you to install phpbBB3 Portal <?php echo $portal_version; ?>.</p>
							<p>For more information please visit phpBB3 Portal project web site: <a href="http://www.phpbb3portal.net">http://www.phpbb3portal.net</a></p>
							<br />
							
							<h1>Action</h1>
							<p>Please click the install link.</p>
							
							<h3><a href="install_portal.php?action=install">Install <?php echo $portal_version; ?></a></h3>
							
							<br /><br />
							
							<h1>Information</h1>
							<p>
								<ul>
									<li><strong>Attention!</strong> Please complete the editing of phpBB files if you have not already done so.</li>
									<li><strong>Attention!</strong> For security reasons, please delete <b>install_portal.php</b> at from your root directory after this install/update.</li>
									<li>After an installtion or update of phpBB3 Portal it is recommend you clear your cache.</li>
								</ul>
							</p>

<?php
		}
	break;
}


?>
						</div>
					</div>
				<span class="corners-bottom"><span></span></span>
			</div>
		</div>
	</div>
	
	<!--
		We request you retain the full copyright notice below including the link to www.phpbb.com.
		This not only gives respect to the large amount of time given freely by the developers
		but also helps build interest, traffic and use of phpBB. If you (honestly) cannot retain
		the full copyright we ask you at least leave in place the "Powered by phpBB" line, with
		"phpBB" linked to www.phpbb.com. If you refuse to include even this then support on our
		forums may be affected.
	
		The phpBB Group : 2006
	// -->
	
	<div id="page-footer">
		Powered by phpBB &copy; 2000, 2002, 2005, 2007 <a href="http://www.phpbb.com/">phpBB Group</a><br />
		Portal by <a href="http://www.phpbb3portal.net" title="phpBB3 Portal" target="_blank">phpBB3 Portal</a> &copy; <a href="http://www.phpbbturkiye.net" title="phpBB Türkiye" target="_blank">phpBB</a> Türkiye
	</div>
</div>

</body>
</html>