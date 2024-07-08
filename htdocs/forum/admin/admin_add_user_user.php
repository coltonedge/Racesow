<?php
/***************************************************************************
 *                             admin_add_user.php
 *                            --------------------
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

if($setmodules == 1)
{
	$file = basename(__FILE__);
	$module['User_Add_New_Users']['User_Add_New_Users_2'] = "../profile.php?mode=register&agreed=true";
	return;
}
define('IN_PHPBB',1);
//
// Load default header
//
$phpbb_root_path = "../";
require($phpbb_root_path . 'extension.inc');
require('pagestart.' . $phpEx);
?>
<head>

</head>
