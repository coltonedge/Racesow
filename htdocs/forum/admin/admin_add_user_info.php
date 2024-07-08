<?php
/***************************************************************************
 *                             admin_add_user_info.php
 *                            -------------------------
 *   begin                : 30.05.2003
 *   copyright            : (C) 2003 AWSW @ www.awsw.de
 *   email                : awsw@awsw.de
 *
 *
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

define('IN_PHPBB',1);

if($setmodules == 1)
{
	$file = basename(__FILE__);
	$module ['User_Add_New_Users']['User_Add_New_Users_1'] = "$file";
	return;
}

//
// Load default header
//
$phpbb_root_path = "../";
require($phpbb_root_path . 'extension.inc');
require('pagestart.' . $phpEx);


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



message_die(GENERAL_MESSAGE, "<br><b>" . $lang['admin_add_user1'] . "</b><br><br>" . $lang['admin_add_user2'] . "<br><br><b>". $lang['admin_add_user3'] . " AWSW @ <a href='http://www.awsw.de' target='_blank'>www.awsw.de</a></b><br><br>");
?>