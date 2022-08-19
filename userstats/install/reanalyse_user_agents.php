<?php

/*
 **************************************
 *
 * install/reanalyse_user_agents.php
 * -------------
 *
 * last modified:	2005-04-21
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


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title></title>
 </head>
 <body>
<?php

error_reporting(E_ALL);
set_magic_quotes_runtime(0);

if( isset( $_POST['submit'] ) )
{
	require_once( '../includes/config.inc.php' );
	require_once( '../includes/common.inc.php' );
	require_once( '../includes/functions.inc.php' );
	require_once( '../includes/mysql.class.php' );
	require_once( '../includes/user_agents.lib.php' );

	$_CHC_DB = new chC_mysql( $_CHC_DBCONFIG['server'], $_CHC_DBCONFIG['user'], $_CHC_DBCONFIG['password'], $_CHC_DBCONFIG['database'] );

	$_CHC_DB->query(
		"DELETE FROM `".CHC_TABLE_USER_AGENTS."`
		WHERE typ != 'user_agent'"
	);


	$useragents = array(
		'browser' => array(),
		'os' => array(),
		'robot' => array()
	);
	$user_agents_versionen = array();

	$result = $_CHC_DB->query(
		"SELECT wert as useragent, timestamp, anzahl
		FROM `".CHC_TABLE_USER_AGENTS."`
		WHERE typ = 'user_agent'"
	);

	if( $_CHC_DB->num_rows( $result ) == 0 )
	{
		die( 'Done.' );
	}

	while( $row = $_CHC_DB->fetch_assoc( $result ) )
	{
		$useragents_details = chC_analyse_user_agent( $row['useragent'] );

                if( $useragents_details['browser'] == TRUE )
                {
			# browser
			if( isset( $useragents['browser'][$useragents_details['browser']] ) )
			{
				$useragents['browser'][$useragents_details['browser']]['anzahl'] += $row['anzahl'];
				if( $useragents['browser'][$useragents_details['browser']]['timestamp'] < $row['timestamp'] )
				{
					$useragents['browser'][$useragents_details['browser']]['timestamp'] = $row['timestamp'];
				}
			}
			else
			{
				$useragents['browser'][$useragents_details['browser']] = array(
					'anzahl'	=> $row['anzahl'],
					'timestamp'		=> $row['timestamp']
				);
			}

			# browser version
			if( $useragents_details['browser_version'] == TRUE )
			{
				if( isset( $useragents_versionen['browser'][$useragents_details['browser']][$useragents_details['browser_version']] ) )
				{
		         		$useragents_versionen['browser'][$useragents_details['browser']][$useragents_details['browser_version']]['anzahl'] += $row['anzahl'];
					if( $useragents_versionen['browser'][$useragents_details['browser']][$useragents_details['browser_version']]['timestamp'] < $row['timestamp'] )
					{
						$useragents_versionen['browser'][$useragents_details['browser']][$useragents_details['browser_version']]['timestamp'] = $row['timestamp'];
					}

					$useragents_versionen['browser'][$useragents_details['browser']]['versionen_gesamt']['anzahl'] += $row['anzahl'];
					if( $useragents_versionen['browser'][$useragents_details['browser']]['versionen_gesamt']['timestamp'] < $row['timestamp'] )
					{
						$useragents_versionen['browser'][$useragents_details['browser']]['versionen_gesamt']['timestamp'] = $row['timestamp'];
					}
				}
				else
				{
					$useragents_versionen['browser'][$useragents_details['browser']][$useragents_details['browser_version']] = array(
						'anzahl'	=> $row['anzahl'],
						'timestamp'		=> $row['timestamp']
					);
					if( !isset( $useragents_versionen['browser'][$useragents_details['browser']]['versionen_gesamt'] ) )
					{
						$useragents_versionen['browser'][$useragents_details['browser']]['versionen_gesamt'] = array(
							'anzahl'	=> $row['anzahl'],
							'timestamp'		=> $row['timestamp']
						);
					}
					else
					{
						$useragents_versionen['browser'][$useragents_details['browser']]['versionen_gesamt']['anzahl'] += $row['anzahl'];
						if( $useragents_versionen['browser'][$useragents_details['browser']]['versionen_gesamt']['timestamp'] < $row['timestamp'] )
						{
							$useragents_versionen['browser'][$useragents_details['browser']]['versionen_gesamt']['timestamp'] = $row['timestamp'];
						}
					}
				}
			}
		}
		else
		{
			# robot
			if( isset( $useragents['robot'][$useragents_details['robot']] ) )
			{
				$useragents['robot'][$useragents_details['robot']]['anzahl'] += $row['anzahl'];
				if( $useragents['robot'][$useragents_details['robot']]['timestamp'] < $row['timestamp'] )
				{
					$useragents['robot'][$useragents_details['robot']]['timestamp'] = $row['timestamp'];
				}
			}
			else
			{
				$useragents['robot'][$useragents_details['robot']] = array(
					'anzahl'	=> $row['anzahl'],
					'timestamp'		=> $row['timestamp']
				);
			}

			# robot version
			if( $useragents_details['robot_version'] == true)
			{
				if( isset( $useragents_versionen['robot'][$useragents_details['robot']][$useragents_details['robot_version']] ) )
				{
		         		$useragents_versionen['robot'][$useragents_details['robot']][$useragents_details['robot_version']]['anzahl'] += $row['anzahl'];
					if( $useragents_versionen['robot'][$useragents_details['robot']][$useragents_details['robot_version']]['timestamp'] < $row['timestamp'] )
					{
						$useragents_versionen['robot'][$useragents_details['robot']][$useragents_details['robot_version']]['timestamp'] = $row['timestamp'];
					}

					$useragents_versionen['robot'][$useragents_details['robot']]['versionen_gesamt']['anzahl'] += $row['anzahl'];
					if( $useragents_versionen['robot'][$useragents_details['robot']]['versionen_gesamt']['timestamp'] < $row['timestamp'] )
					{
						$useragents_versionen['robot'][$useragents_details['robot']]['versionen_gesamt']['timestamp'] = $row['timestamp'];
					}
				}
				else
				{
					$useragents_versionen['robot'][$useragents_details['robot']][$useragents_details['robot_version']] = array(
						'anzahl'	=> $row['anzahl'],
						'timestamp'		=> $row['timestamp']
					);

					if( !isset( $useragents_versionen['robot'][$useragents_details['robot']]['versionen_gesamt'] ) )
					{
						$useragents_versionen['robot'][$useragents_details['robot']]['versionen_gesamt'] = array(
							'anzahl'	=> $row['anzahl'],
							'timestamp'		=> $row['timestamp']
						);
					}
					else
					{
						$useragents_versionen['robot'][$useragents_details['robot']]['versionen_gesamt']['anzahl'] += $row['anzahl'];
						if( $useragents_versionen['robot'][$useragents_details['robot']]['versionen_gesamt']['timestamp'] < $row['timestamp'] )
						{
							$useragents_versionen['robot'][$useragents_details['robot']]['versionen_gesamt']['timestamp'] = $row['timestamp'];
						}
					}
				}
			}
		}


		# os
		if( isset( $useragents['os'][$useragents_details['os']] ) )
		{
			$useragents['os'][$useragents_details['os']]['anzahl'] += $row['anzahl'];
			if( $useragents['os'][$useragents_details['os']]['timestamp'] < $row['timestamp'] )
			{
				$useragents['os'][$useragents_details['os']]['timestamp'] = $row['timestamp'];
			}
		}
		else
		{
			$useragents['os'][$useragents_details['os']] = array(
				'anzahl'	=> $row['anzahl'],
				'timestamp'		=> $row['timestamp']
			);
		}

		# os version
		if( $useragents_details['os_version'] == TRUE )
		{
			if( isset($useragents_versionen['os'][$useragents_details['os']][$useragents_details['os_version']] ) )
			{
				$useragents_versionen['os'][$useragents_details['os']][$useragents_details['os_version']]['anzahl'] += $row['anzahl'];
				if( $useragents_versionen['os'][$useragents_details['os']][$useragents_details['os_version']]['timestamp'] < $row['timestamp'] )
				{
					$useragents_versionen['os'][$useragents_details['os']][$useragents_details['os_version']]['timestamp'] = $row['timestamp'];
				}

				$useragents_versionen['os'][$useragents_details['os']]['versionen_gesamt']['anzahl'] += $row['anzahl'];
				if( $useragents_versionen['os'][$useragents_details['os']]['versionen_gesamt']['timestamp'] < $row['timestamp'] )
				{
					$useragents_versionen['os'][$useragents_details['os']]['versionen_gesamt']['timestamp'] = $row['timestamp'];
				}
			}
			else
			{
				$useragents_versionen['os'][$useragents_details['os']][$useragents_details['os_version']] = array(
					'anzahl'	=> $row['anzahl'],
					'timestamp'		=> $row['timestamp']
				);

				if( !isset( $useragents_versionen['os'][$useragents_details['os']]['versionen_gesamt'] ) )
				{
					$useragents_versionen['os'][$useragents_details['os']]['versionen_gesamt'] = array(
						'anzahl'	=> $row['anzahl'],
						'timestamp'		=> $row['timestamp']
					);
				}
				else
				{
					$useragents_versionen['os'][$useragents_details['os']]['versionen_gesamt']['anzahl'] += $row['anzahl'];
					if( $useragents_versionen['os'][$useragents_details['os']]['versionen_gesamt']['timestamp'] < $row['timestamp'] )
					{
						$useragents_versionen['os'][$useragents_details['os']]['versionen_gesamt']['timestamp'] = $row['timestamp'];
					}
				}
			}
		}

	}

	$insert_values = '';
	   # exit;
	foreach( $useragents['browser'] as $browser => $array )
	{
		if( !empty( $insert_values ) )
		{
			$insert_values .= ', ';
		}
		$insert_values .= "('browser', '".$browser."', ".$array['timestamp'].", ".$array['anzahl'].")\n";
	}

	foreach( $useragents['robot'] as $robot => $array )
	{
		if( !empty( $insert_values ) )
		{
			$insert_values .= ', ';
		}
		$insert_values .= "('robot', '".$robot."', ".$array['timestamp'].", ".$array['anzahl'].")\n";
	}

	foreach( $useragents['os'] as $os => $array )
	{
		if( !empty( $insert_values ) )
		{
			$insert_values .= ', ';
		}
		$insert_values .= "('os', '".$os."', ".$array['timestamp'].", ".$array['anzahl'].")\n";
	}

	foreach( $useragents_versionen as $kategorie => $array )
        {
		foreach( $array as $typ => $versionen )
		{
			foreach( $versionen as $version => $array2 )
			{
				if( !empty( $insert_values ) )
				{
					$insert_values .= ', ';
				}
				$insert_values .= "('version~".$kategorie."', '".$typ."~".$version."', ".$array2['timestamp'].", ".$array2['anzahl'].")\n";
			}
		}
	}

	if( !empty( $insert_values ) )
	{
		$_CHC_DB->query( "INSERT INTO `".CHC_TABLE_USER_AGENTS."` ( typ, wert, timestamp, anzahl ) VALUES ". $insert_values );
	}

	print "Done.";
}
else
{
?>
Bitte nur fortfahren, wenn du noch nie die Useragents "teilweise" gel&ouml;scht hast (in der Administration).<br />
<br />
Please continue only if you have never partially erased the stored user agents.<br />
<br />
<form action="reanalyse_user_agents.php" method="post">
<input type="submit" name="submit" value="ok" />
</form>
<?php
}
?>
 </body>
</html>
