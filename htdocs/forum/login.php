<?php
/***************************************************************************
 *                                login.php
 *                            -------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: login.php,v 1.47.2.25 2006/12/16 13:11:24 acydburn Exp $
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

//
// Allow people to reach login page if
// board is shut down
//
define("IN_LOGIN", true);

define('IN_PHPBB', true);
$phpbb_root_path = './';
require_once($phpbb_root_path . 'extension.inc');
require_once($phpbb_root_path . 'common.'.$phpEx);

//
// Set page ID for session management
//
$userdata = session_pagestart($user_ip, PAGE_LOGIN);
init_userprefs($userdata);
//
// End session management
//

// session id check
if (!empty($HTTP_POST_VARS['sid']) || !empty($HTTP_GET_VARS['sid']))
{
	$sid = (!empty($HTTP_POST_VARS['sid'])) ? $HTTP_POST_VARS['sid'] : $HTTP_GET_VARS['sid'];
}
else
{
	$sid = '';
}

if( isset($HTTP_POST_VARS['login']) || isset($HTTP_GET_VARS['login']) || isset($HTTP_POST_VARS['logout']) || isset($HTTP_GET_VARS['logout']) )
{
	$authByWarsowId = false;
	$isConnected = false;
	if (isset($HTTP_POST_VARS['login']) || isset($HTTP_GET_VARS['login'])) {
		
    	$username = isset($HTTP_POST_VARS['username']) ? phpbb_clean_username($HTTP_POST_VARS['username']) : '';
        $password = isset($HTTP_POST_VARS['password']) ? $HTTP_POST_VARS['password'] : '';
		/*
        $test = new Racenet_Auth_Adapter_WarsowForum;
        $test->setIdentity($username);
        $test->setCredential($password);
        $result = $test->authenticate();
        if ($result->isValid()) {
        	
            $authByWarsowId = true;
        	$HTTP_POST_VARS['redirect'] = racenet_addUrlParam($HTTP_POST_VARS['redirect'], 'msgCode', 5);
        	
        	// already is authd in racenet
        	if (isset($userdata['user_id']) && (integer)$userdata['user_id'] > 0) {
            	
        		// check for same warsowID in other racenet users
            	$sql = "SELECT * FROM " . USERS_TABLE . " WHERE user_id != ". (integer)$userdata['user_id'] . " AND warsow_id = '". $username ."'";
            	if ( !($result = $db->sql_query($sql)) ) {

		            message_die(GENERAL_ERROR, 'Error in obtaining warsowID', '', __LINE__, __FILE__, $sql);
		        }
		        
		        if( $row = $db->sql_fetchrow($result) ) {
		        	
		        	// already assigned
		        	redirect(append_sid(racenet_addUrlParam($HTTP_POST_VARS['redirect'], 'msgCode', 1), true));
		        }
            	
		        // update warsowID if logged in with another warsow.net account on the same racenet account
           	    $db->sql_query('UPDATE ' . USERS_TABLE . ' SET warsow_id = "'. $username .'" WHERE user_id = ' . $userdata['user_id']);
            	if($userdata['warsow_id'] != $username) {

            		$HTTP_POST_VARS['redirect'] = racenet_addUrlParam($HTTP_POST_VARS['redirect'], 'msgCode', 2);
            	}
            	
            } else {
            	
                // check for the warsowID in racenet users
                $sql = "SELECT * FROM " . USERS_TABLE . " WHERE warsow_id = '". $username ."'";
                if ( !($result = $db->sql_query($sql)) ) {

                    message_die(GENERAL_ERROR, 'Error in obtaining warsowID', '', __LINE__, __FILE__, $sql);
                }
                
                if( $row = $db->sql_fetchrow($result) ) {
                    
                    $isConnected = true;
                
                } else {
                	
                	//$HTTP_POST_VARS['redirect'] = racenet_addUrlParam($HTTP_POST_VARS['redirect'], 'msgCode', 6);
                	//redirect($HTTP_POST_VARS['redirect']);
                }
            }
            
        } else */if (isset($userdata['user_id']) && (integer)$userdata['user_id'] > 0) {
        	
        	$HTTP_POST_VARS['redirect'] = racenet_addUrlParam($HTTP_POST_VARS['redirect'], 'msgCode', 3);
        }
	}
	
	if( ( isset($HTTP_POST_VARS['login']) || isset($HTTP_GET_VARS['login']) ) && (!$userdata['session_logged_in'] || isset($HTTP_POST_VARS['admin']) || $authByWarsowId) )
	{
        if ($authByWarsowId && $isConnected) {

            $sql = "SELECT user_id, username, user_password, user_active, user_level, user_login_tries, user_last_login_try
                FROM " . USERS_TABLE . "
                WHERE warsow_id = '" . str_replace("\\'", "''", $username) . "'";
            
            
        } else {
        		
			$sql = "SELECT user_id, username, user_password, user_active, user_level, user_login_tries, user_last_login_try
				FROM " . USERS_TABLE . "
				WHERE username = '" . str_replace("\\'", "''", $username) . "'";
        }
		
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Error in obtaining userdata', '', __LINE__, __FILE__, $sql);
		}

		if( $row = $db->sql_fetchrow($result) )
		{
			if( $row['user_level'] != ADMIN && $board_config['board_disable'] )
			{
				redirect(append_sid("index.$phpEx", true));
			}
			else
			{
				// If the last login is more than x minutes ago, then reset the login tries/time
				if ($row['user_last_login_try'] && $board_config['login_reset_time'] && $row['user_last_login_try'] < (time() - ($board_config['login_reset_time'] * 60)))
				{
					$db->sql_query('UPDATE ' . USERS_TABLE . ' SET user_login_tries = 0, user_last_login_try = 0 WHERE user_id = ' . $row['user_id']);
					$row['user_last_login_try'] = $row['user_login_tries'] = 0;
				}
				
				// Check to see if user is allowed to login again... if his tries are exceeded
				if ($row['user_last_login_try'] && $board_config['login_reset_time'] && $board_config['max_login_attempts'] && 
					$row['user_last_login_try'] >= (time() - ($board_config['login_reset_time'] * 60)) && $row['user_login_tries'] >= $board_config['max_login_attempts'] && $userdata['user_level'] != ADMIN)
				{
					message_die(GENERAL_MESSAGE, sprintf($lang['Login_attempts_exceeded'], $board_config['max_login_attempts'], $board_config['login_reset_time']));
				}

				if( ($authByWarsowId || md5($password) == $row['user_password']) && $row['user_active'] )
				{
					$autologin = ( isset($HTTP_POST_VARS['autologin']) ) ? TRUE : 0;

					$admin = (isset($HTTP_POST_VARS['admin'])) ? 1 : 0;
					$session_id = session_begin($row['user_id'], $user_ip, PAGE_INDEX, FALSE, $autologin, $admin);

					// Reset login tries
					$db->sql_query('UPDATE ' . USERS_TABLE . ' SET user_login_tries = 0, user_last_login_try = 0 WHERE user_id = ' . $row['user_id']);

					if( $session_id )
					{
						$url = ( !empty($HTTP_POST_VARS['redirect']) ) ? str_replace('&amp;', '&', htmlspecialchars($HTTP_POST_VARS['redirect'])) : "index.$phpEx";
						$url = racenet_addUrlParam($url, 'msgCode', 4);
		    			redirect(append_sid($url, true));
					}
					else
					{
						message_die(CRITICAL_ERROR, "Couldn't start session : login", "", __LINE__, __FILE__);
					}
				}
				// Only store a failed login attempt for an active user - inactive users can't login even with a correct password
				elseif( !$authByWarsowId && $row['user_active'] )
				{
					// Save login tries and last login
					if ($row['user_id'] != ANONYMOUS)
					{
						$sql = 'UPDATE ' . USERS_TABLE . '
							SET user_login_tries = user_login_tries + 1, user_last_login_try = ' . time() . '
							WHERE user_id = ' . $row['user_id'];
						$db->sql_query($sql);
					}
				}

				$redirect = ( !empty($HTTP_POST_VARS['redirect']) ) ? str_replace('&amp;', '&', htmlspecialchars($HTTP_POST_VARS['redirect'])) : '';
                $redirect = racenet_addUrlParam($redirect, 'msgCode', 5);
				$redirect = str_replace('?', '&', $redirect);
				if (strstr(urldecode($redirect), "\n") || strstr(urldecode($redirect), "\r") || strstr(urldecode($redirect), ';url'))
				{
					message_die(GENERAL_ERROR, 'Tried to redirect to potentially insecure url.');
				}
				
				$template->assign_vars(array(
					'META' => "<meta http-equiv=\"refresh\" content=\"3;url=login.$phpEx?redirect=$redirect\">")
				);

				$message = $lang['Error_login'] . '<br /><br />' . sprintf($lang['Click_return_login'], "<a href=\"login.$phpEx?redirect=$redirect\">", '</a>') . '<br /><br />' .  sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
                
				//redirect($redirect);
			}
		}
		else
		{
			$redirect = ( !empty($HTTP_POST_VARS['redirect']) ) ? str_replace('&amp;', '&', htmlspecialchars($HTTP_POST_VARS['redirect'])) : "";
			//$redirect = str_replace("?", "&", $redirect);

			
			if (strstr(urldecode($redirect), "\n") || strstr(urldecode($redirect), "\r") || strstr(urldecode($redirect), ';url'))
			{
				message_die(GENERAL_ERROR, 'Tried to redirect to potentially insecure url.');
			}

			
			$template->assign_vars(array(
				'META' => "<meta http-equiv=\"refresh\" content=\"3;url=login.$phpEx?redirect=$redirect\">")
			);

			$message = $lang['Error_login'] . '<br /><br />' . sprintf($lang['Click_return_login'], "<a href=\"login.$phpEx?redirect=$redirect\">", '</a>') . '<br /><br />' .  sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');
            message_die(GENERAL_MESSAGE, $message);
            
			
			//redirect($redirect);
		}
	}
	else if( ( isset($HTTP_GET_VARS['logout']) || isset($HTTP_POST_VARS['logout']) ) /* && $userdata['session_logged_in'] */ )
	{
		facebook_logout();
	
		// session id check
		//if ($sid == '' || $sid != $userdata['session_id'])
		//{
		//	message_die(GENERAL_ERROR, 'Invalid_session');
		//}
		

		if( $userdata['session_logged_in'] )
		{
			session_end($userdata['session_id'], $userdata['user_id']);
		}

		if (!empty($HTTP_POST_VARS['redirect']) || !empty($HTTP_GET_VARS['redirect']))
		{
			$url = (!empty($HTTP_POST_VARS['redirect'])) ? htmlspecialchars($HTTP_POST_VARS['redirect']) : htmlspecialchars($HTTP_GET_VARS['redirect']);
			$url = str_replace('&amp;', '&', $url);
			redirect(append_sid($url, true));
		}
		else
		{
			redirect(append_sid("index.$phpEx", true));
		}
	}
	else
	{
		$url = ( !empty($HTTP_POST_VARS['redirect']) ) ? str_replace('&amp;', '&', htmlspecialchars($HTTP_POST_VARS['redirect'])) : "index.$phpEx";
		redirect(append_sid($url, true));
	}
}
else
{
	//
	// Do a full login page dohickey if
	// user not already logged in
	//
	if( !$userdata['session_logged_in'] || (isset($HTTP_GET_VARS['admin']) && $userdata['session_logged_in'] && $userdata['user_level'] == ADMIN))
	{
		$page_title = $lang['Login'];
		require_once($phpbb_root_path . 'includes/page_header.'.$phpEx);

		$template->set_filenames(array(
			'body' => 'login_body.tpl')
		);

		$forward_page = '';

		if( isset($HTTP_POST_VARS['redirect']) || isset($HTTP_GET_VARS['redirect']) )
		{
			$forward_to = $HTTP_SERVER_VARS['QUERY_STRING'];

			if( preg_match("/^redirect=([a-z0-9\.#\/\?&=\+\-_]+)/si", $forward_to, $forward_matches) )
			{
				$forward_to = ( !empty($forward_matches[3]) ) ? $forward_matches[3] : $forward_matches[1];
				$forward_match = explode('&', $forward_to);

				if(count($forward_match) > 1)
				{
					for($i = 1; $i < count($forward_match); $i++)
					{
						if( !ereg("sid=", $forward_match[$i]) )
						{
							if( $forward_page != '' )
							{
								$forward_page .= '&';
							}
							$forward_page .= $forward_match[$i];
						}
					}
					$forward_page = $forward_match[0] . '?' . $forward_page;
				}
				else
				{
					$forward_page = $forward_match[0];
				}
			}
		}

		$username = ( $userdata['user_id'] != ANONYMOUS ) ? $userdata['username'] : '';

		$s_hidden_fields = '<input type="hidden" name="redirect" value="' . $forward_page . '" />';
		$s_hidden_fields .= (isset($HTTP_GET_VARS['admin'])) ? '<input type="hidden" name="admin" value="1" />' : '';

		make_jumpbox('viewforum.'.$phpEx);
		$template->assign_vars(array(
			'USERNAME' => $username,

			'L_ENTER_PASSWORD' => (isset($HTTP_GET_VARS['admin'])) ? $lang['Admin_reauthenticate'] : $lang['Enter_password'],
			'L_SEND_PASSWORD' => $lang['Forgotten_password'],

			'U_SEND_PASSWORD' => append_sid("profile.$phpEx?mode=sendpassword"),

			'S_HIDDEN_FIELDS' => $s_hidden_fields)
		);

		$template->pparse('body');

		require_once($phpbb_root_path . 'includes/page_tail.'.$phpEx);
	}
	else
	{
		redirect(append_sid("index.$phpEx", true));
	}

}

?>