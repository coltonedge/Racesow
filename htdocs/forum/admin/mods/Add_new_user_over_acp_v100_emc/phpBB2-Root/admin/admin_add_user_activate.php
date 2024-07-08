<?php
/***************************************************************************
 *                             admin_add_user_activate.php
 *                            -----------------------------
 *   begin                : 30.05.2003
 *
 *   Note                 : This MOD uses pices of the "Account Activation MOD". 
 *                          I´m afraid i do not know the source of this code.
 *
 * uses phpBB technology (c) 2001 phpBB Group <http://www.phpbb.com/> 
 * 
***************************************************************************/ 

/* ************************************************************************** 
 * 
 *   This program is free software; you can redistribute it and/or modify 
 *   it under the terms of the GNU General Public License as published by 
 *   the Free Software Foundation; either version 2 of the License, or 
 *   (at your option) any later version. 
 * 
***************************************************************************/ 

define('IN_PHPBB', 1);

if($setmodules==1)
{
	$filename = basename(__FILE__);
	$module['User_Add_New_Users']['User_Add_New_Users_3'] = $filename . "?mode=activate";
	return;
}

$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

// Load the appropriate mod language file
if( isset($HTTP_GET_VARS['mode']) )
{
	switch( $HTTP_GET_VARS['mode'] )
	{
default:
			$lang_file = 'lang_admin_add_user';
			$l_title = $lang['admin_add_user'];
			break;
	}
}
else
{
	$lang_file = 'lang_admin_add_user';
	$l_title = $lang['admin_add_user'];
}
include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/' . $lang_file . '.' . $phpEx);
//

			$template->set_filenames(array(
				"body" => "admin/admin_add_user_activate.tpl")
			);

        $sql = "SELECT username, user_active, user_actkey, user_id, user_regdate
                FROM " . USERS_TABLE . " WHERE user_active != '1' AND user_id != '-1' "; 
        if ( !($result = $db->sql_query($sql)) )
        {
                	message_die(GENERAL_ERROR, 'Could not obtain user information', '', __LINE__, __FILE__, $sql);
        }

if ($mode == 'activate')
{
        while($row = $db->sql_fetchrow($results)) 
           { 
               $profile_link = '?mode=viewprofile&' . POST_USERS_URL . '=' . $row[user_id];
               $actkey_link = '?mode=activate&' . POST_USERS_URL . '=' . $row[user_id] . '&act_key=' . $row[user_actkey];
               $reg_date = create_date($board_config['default_dateformat'], $row['user_regdate'], $board_config['board_timezone']);

                        $template->assign_block_vars("admin_activate_user", array(
                                'PROFILE' => '<a href=../profile.php'. $profile_link .'>'. $row[username] .'</a>',
                                'ACTKEY' => '<a href=../profile.php'. $actkey_link .'>' . $lang['admin_add_user4'] . '</a>',
                                'REG_DATE' => $reg_date)
                        );
           }

                        $template->assign_vars(array(
                                'L_TITLE' => $lang['admin_add_user5'],
                                'L_USERNAME' => $lang['Username'],
                                'L_DATE' => $lang['Reg_date'])
                        );
}

$template->pparse('body');
include('./page_footer_admin.'.$phpEx);
?>