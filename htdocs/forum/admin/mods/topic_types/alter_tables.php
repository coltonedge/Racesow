<?php
/***************************************************************************
 *                             alter_tables.php
 *                            -------------------
 *   begin                : Thursday, July 31, 2003
 *   email                : florian@developpez.biz
 *
 *   $Id: alter_tables.php,v 1.0.0 
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

define('IN_PHPBB', 1);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'config.'.$phpEx);
include($phpbb_root_path . 'includes/constants.'.$phpEx);
include($phpbb_root_path.'common.'.$phpEx);	
include($phpbb_root_path . 'includes/db.'.$phpEx);

$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);

if ( $userdata['user_level'] != ADMIN )
{
	message_die(GENERAL_ERROR, "You must be an Administrator to use this page.");
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<title>Alter table for Add Topic Types Mod</title>
<head>
<meta http-equiv="Content-Type" content="text/html;">
<meta http-equiv="Content-Style-Type" content="text/css">
<style type="text/css">
<!--

font,th,td,p,body { font-family: "Courier New", courier; font-size: 11pt }

a:link,a:active,a:visited { color : #006699; }
a:hover		{ text-decoration: underline; color : #DD6900;}

hr	{ height: 0px; border: solid #D1D7DC 0px; border-top-width: 1px;}

.maintitle,h1,h2	{font-weight: bold; font-size: 22px; font-family: "Trebuchet MS",Verdana, Arial, Helvetica, sans-serif; text-decoration: none; line-height : 120%; color : #000000;}

.ok {color:green}

.error {color:red}

/* Import the fancy styles for IE only (NS4.x doesn't use the @import function) */
@import url("./templates/subSilver/formIE.css"); 
-->
</style>
</head>
<body bgcolor="#FFFFFF" text="#000000" link="#006699" vlink="#5584AA">

<table width="100%" border="0" cellspacing="0" cellpadding="10" align="center"> 
	<tr>
		<td><table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td><a href="./index.php"><img src="./templates/subSilver/images/logo_phpBB.gif" border="0" alt="Forum Home" vspace="1" /></a></td>
				<td align="center" width="100%" valign="middle"><span class="maintitle">Installing Add Topic Types MOD</span></td>
			</tr>
		</table></td>
	</tr>
</table>

<br clear="all" />

<h2>Informations</h2>

<?php

// get the phpBB version
$sql = "SELECT config_value  
	FROM " . CONFIG_TABLE . " 
	WHERE config_name = 'version'";
if ( !($result = $db->sql_query($sql)) )
{
	die("Couldn't obtain version info");
}
$row = $db->sql_fetchrow($result);
$phpBB_version = $row['config_value'] ;
$sql = array();

// output some info
echo '<p>Database type &nbsp;  :: <b>' . SQL_LAYER . '</b><br />';

echo 'phpBB version &nbsp;  :: <b>2' . $phpBB_version . '</b><br />';

echo 'Add Topic Types :: <b>1.0.0</b></p>' ."\n";

?>

<br clear="all" />

<h3>What are you going to do ?</h3>
This file is used to do the changes to your database (modifying a table) to make the MOD working properly.
If you have any problem during this part, you can contact me to get support. Now, if you are ready, click on the button.<br>
The process can be long, in particular if you have a large board.

<br clear="all" />

<center>
	<form action="alter_tables.php" method=POST>
		<input type="submit" name="submit" value="submit" class="liteoption" />
	</form>
</center>

<?php

$submit = ( isset($HTTP_POST_VARS['submit']) ) ? $HTTP_POST_VARS['submit'] : 0;

if ( $submit )
{
	switch ( SQL_LAYER )
	{
		case 'mysql':
		case 'mysql4':
			$sql[] = "ALTER TABLE " . TOPICS_TABLE . "
				ADD COLUMN topic_solved char(1) default 'N'";
			$sql[] = "ALTER TABLE " . TOPICS_TABLE . "
				ADD COLUMN topic_totrash char(1) default 'N'";
			$sql[] = "ALTER TABLE " . TOPICS_TABLE . "
				ADD COLUMN topic_ext_status tinyint default 0";
			break;
		default:
			die("/!\ No Database Abstraction Layer (DBAL) found /!\\");
			break;
	}

	echo("<h2>Adding/modifying tables to your database</h2>\n");
	for ($i=0; $i < count($sql); $i++)
	{
		echo("Running query :: " . $sql[$i]);
		flush();

		if ( !($result = $db->sql_query($sql[$i])) )
		{
			$error_code = TRUE;
			$error = $db->sql_error();

			echo(" -> <b><span class=\"error\">ERROR - QUERY FAILED</span></b> ----> <u>" . $error['message'] . "</u><br /><br />\n\n");
		}
		else
		{
			echo(" -> <b><span class=\"ok\">GOOD - QUERY OK</span></b><br /><br />\n\n");
		}
	}

		$error_message = "";

		if ( $error_code )
		{
			$error_message .= "<br />At least one query failed : check the error message and contact me if you need help to resolve the problem. <br />";
		}
		else
		{
			$error_message .= "<br />All the queries have been successfully done - Enjoy. <br />";
		}

		echo("\n<br />\n<b>COMPLETE - INSTALLATION IS ENDED</b><br />\n");
		echo($error_message . "<br />");
		echo("<br /><b>NOW, DELETE THIS FILE FROM YOUR SERVER</b><br />\n");
}

?>
</body>
</html>