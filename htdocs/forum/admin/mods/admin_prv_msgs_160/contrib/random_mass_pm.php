<?php

/* This script might be usefull to someone, I use it for testing */

/* It will randomly generate the specified # of PMs below in the
define('SEND_PMS', xxx); area where xxx is the number.  It just
gets a random user for from and another random user to send to
and pumps out a PM into the database directly.  Use it for
testing only :)  Your users probably don't want a bunch of
random PMs.  It will not notify by e-mail anyone you send them
to though, so that's a plus to keep annoyance down. */

/* To use it, just pop it into your main directory and login
as an admin account, then call it like http://www.nivisec.com/phpBB2/random_mass_pm.php */

define('SEND_PMS', 500);

/* Uncomment this line to get it working */
//define('WORK', true);

if (!defined('WORK'))
{
	die('Please open this file and read it, you need to enable the script from inside.');
}

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management
//

if ($userdata['user_level'] != ADMIN)
{
	die('NOT AN ADMIN!!!');
}


$sql = 'SELECT user_id FROM ' . USERS_TABLE;
$result = $db->sql_query($sql);
while ($row = $db->sql_fetchrow($result))
{
	$users[] = $row['user_id'];
}

for ($i=0; $i < SEND_PMS; $i++)
{
	$msg_time = time();
	$user_ids = array_rand($users, 2);
	$user_from_id = $users[$user_ids[0]];
	$user_to_id = $users[$user_ids[1]];
	

	$privmsg_subject = "A randomly generated message from user id # $user_from_id!";
	$privmsg_message = "This is just a random generated message for testing sent from user id $user_from_id to $user_to_id, which is probably you :)  It was not actually sent by any user, but instead by a small randomizer script to help with board testing.";
	
	$sql = "INSERT INTO " . PRIVMSGS_TABLE . " (privmsgs_type, privmsgs_subject, privmsgs_from_userid, privmsgs_to_userid, privmsgs_date, privmsgs_ip, privmsgs_enable_html, privmsgs_enable_bbcode, privmsgs_enable_smilies, privmsgs_attach_sig)
				VALUES (" . PRIVMSGS_NEW_MAIL . ", '" . str_replace("\'", "''", $privmsg_subject) . "', " . $user_from_id . ", " . $user_to_id . ", $msg_time, '0000000', 1, 1, 1, 0)";
	
	if (!$result = $db->sql_query($sql))
	{
		die ("Error: $sql");
	}
	
	$privmsg_sent_id = $db->sql_nextid();
	
	$sql = "INSERT INTO " . PRIVMSGS_TEXT_TABLE . " (privmsgs_text_id, privmsgs_bbcode_uid, privmsgs_text)
				VALUES ($privmsg_sent_id, '" . $bbcode_uid . "', '" . str_replace("\'", "''", $privmsg_message) . "')";
	if (!$db->sql_query($sql))
	{
		die( "Error: $sql");
	}

			//
			// Add to the users new pm counter
			//
			$sql = "UPDATE " . USERS_TABLE . "
				SET user_new_privmsg = user_new_privmsg + 1, user_last_privmsg = " . time() . "  
				WHERE user_id = " . $user_to_id; 
			if ( !$status = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not update private message new/read status for user', '', __LINE__, __FILE__, $sql);
			}
}

?>