<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html dir="{S_CONTENT_DIRECTION}">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={S_CONTENT_ENCODING}">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta name="Author" content="http://www.phpbbstyles.com" />
{META}
{NAV_LINKS}
<title>{SITENAME} :: {PAGE_TITLE}</title>
<link rel="stylesheet" href="{T_TEMPLATE_PATH}/{T_HEAD_STYLESHEET}" type="text/css">
<style type="text/css">
<!--
th, td.th, td.spacerow	{ background-image: url({T_TEMPLATE_PATH}/images/bg_cat.gif); }
td.th2,th.cat2,td.cat,td.catHead,td.catSides,td.catLeft,td.catRight,td.catBottom	{ background-image: url({T_TEMPLATE_PATH}/images/bg_cat2.gif); }
table.toptable { background-image: url({T_TEMPLATE_PATH}/images/top_bg.gif); height: 26px; }
table.bottable { background-image: url({T_TEMPLATE_PATH}/images/bottom_bg.gif); height: 9px; }
table.bottable2 { background-image: url({T_TEMPLATE_PATH}/images/bottom_bg2.gif); height: 20px; }
table.maintable { background-image: url({T_TEMPLATE_PATH}/images/top_bg.jpg); height: 100px; }

/* Import the fancy styles for IE only (NS4.x doesn't use the @import function) */
@import url("{T_TEMPLATE_PATH}/formIE.css"); 
-->
</style>
<!-- BEGIN switch_enable_pm_popup -->
<script language="Javascript" type="text/javascript">
<!--
	if ( {PRIVATE_MESSAGE_NEW_FLAG} )
	{
		window.open('{U_PRIVATEMSGS_POPUP}', '_phpbbprivmsg', 'HEIGHT=225,resizable=yes,WIDTH=400');;
	}
//-->
</script>
<!-- END switch_enable_pm_popup -->
<script language="javascript" type="text/javascript">
<!--

var PreloadFlag = false;
var expDays = 90;
var exp = new Date(); 
var tmp = '';
var tmp_counter = 0;
var tmp_open = 0;

exp.setTime(exp.getTime() + (expDays*24*60*60*1000));

function changeImages()
{
	if (document.images)
	{
		for (var i=0; i<changeImages.arguments.length; i+=2)
		{
			document[changeImages.arguments[i]].src = changeImages.arguments[i+1];
		}
	}
}

function newImage(arg)
{
	if (document.images)
	{
		rslt = new Image();
		rslt.src = arg;
		return rslt;
	}
}

function PreloadImages()
{
	if (document.images)
	{
		// preload all rollover images
		<!-- BEGIN switch_user_logged_out -->
		img0 = newImage('{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_login_on.gif');
		img1 = newImage('{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_register_on.gif');
		<!-- END switch_user_logged_out -->
		<!-- BEGIN switch_user_logged_in -->
		img2 = newImage('{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_pm_on.gif');
		img3 = newImage('{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_profile_on.gif');
		img4 = newImage('{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_groups_on.gif');
		img5 = newImage('{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_logout_on.gif');
		<!-- END switch_user_logged_in -->
		img6 = newImage('{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_faq_on.gif');
		img7 = newImage('{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_search_on.gif');
		img8 = newImage('{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_users_on.gif');
		img9 = newImage('{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_index_on.gif');
		PreloadFlag = true;
	}
	return true;
}


function SetCookie(name, value) 
{
	var argv = SetCookie.arguments;
	var argc = SetCookie.arguments.length;
	var expires = (argc > 2) ? argv[2] : null;
	var path = (argc > 3) ? argv[3] : null;
	var domain = (argc > 4) ? argv[4] : null;
	var secure = (argc > 5) ? argv[5] : false;
	document.cookie = name + "=" + escape(value) +
		((expires == null) ? "" : ("; expires=" + expires.toGMTString())) +
		((path == null) ? "" : ("; path=" + path)) +
		((domain == null) ? "" : ("; domain=" + domain)) +
		((secure == true) ? "; secure" : "");
}

function getCookieVal(offset) 
{
	var endstr = document.cookie.indexOf(";",offset);
	if (endstr == -1)
	{
		endstr = document.cookie.length;
	}
	return unescape(document.cookie.substring(offset, endstr));
}

function GetCookie(name) 
{
	var arg = name + "=";
	var alen = arg.length;
	var clen = document.cookie.length;
	var i = 0;
	while (i < clen) 
	{
		var j = i + alen;
		if (document.cookie.substring(i, j) == arg)
			return getCookieVal(j);
		i = document.cookie.indexOf(" ", i) + 1;
		if (i == 0)
			break;
	} 
	return null;
}

function ShowHide(id1, id2, id3) 
{
	var res = expMenu(id1);
	if (id2 != '') expMenu(id2);
	if (id3 != '') SetCookie(id3, res, exp);
}
	
function expMenu(id) 
{
	var itm = null;
	if (document.getElementById) 
	{
		itm = document.getElementById(id);
	}
	else if (document.all)
	{
		itm = document.all[id];
	} 
	else if (document.layers)
	{
		itm = document.layers[id];
	}
	if (!itm) 
	{
		// do nothing
	}
	else if (itm.style) 
	{
		if (itm.style.display == "none")
		{ 
			itm.style.display = ""; 
			return 1;
		}
		else
		{
			itm.style.display = "none"; 
			return 2;
		}
	}
	else 
	{
		itm.visibility = "show"; 
		return 1;
	}
}

//-->
</script>
</head>
<body bgcolor="#{T_BODY_BGCOLOR}" text="#000000" link="#{T_BODY_LINK}" vlink="#{T_BODY_VLINK}" marginwidth="0" marginheight="0" leftmargin="0" topmargin="0" onload="PreloadImages();" />
<a name="top"></a>
<table border="0" cellspacing="0" cellpadding="0" width="{T_BODY_BACKGROUND}" height="100%" class="firsttable" align="center">
<tr>
	<td width="{T_BODY_BACKGROUND}" height="100%" align="center" valign="top"><table border="0" cellspacing="0" cellpadding="0" width="100%" height="100" class="maintable">
	<tr height="61">
		<td align="center" valign="middle" width="100%" height="61"><a href="{U_INDEX}"><img src="{T_TEMPLATE_PATH}/images/logo_phpBB.gif" border="0" onload="if(this.height > 61) { this.height = 61; }" /></a></td>
	</tr>
	<tr height="19">
		<td align="center" valign="middle" width="100%" height="19"><table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td align="center" valign="middle"><a title="{L_INDEX}" href="{U_INDEX}" class="mainmenu" onmouseover="changeImages('btn_top_index', '{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_index_on.gif'); return true;" onmouseout="changeImages('btn_top_index', '{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_index.gif'); return true;"><img name="btn_top_index" src="{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_index.gif" height="19" border="0" alt="{L_INDEX}" /></a></td>
			<!-- BEGIN switch_user_logged_out -->
			<td align="center" valign="middle"><a title="{L_LOGIN_LOGOUT}" href="{U_LOGIN_LOGOUT}" class="mainmenu" onmouseover="changeImages('btn_top_login', '{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_login_on.gif'); return true;" onmouseout="changeImages('btn_top_login', '{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_login.gif'); return true;"><img name="btn_top_login" src="{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_login.gif" height="19" border="0" alt="{L_LOGIN_LOGOUT}" /></a></td>
			<td align="center" valign="middle"><a title="{L_REGISTER}" href="{U_REGISTER}" class="mainmenu" onmouseover="changeImages('btn_top_register', '{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_register_on.gif'); return true;" onmouseout="changeImages('btn_top_register', '{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_register.gif'); return true;"><img name="btn_top_register" src="{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_register.gif" height="19" border="0" alt="{L_REGISTER}" /></a></td>
			<!-- END switch_user_logged_out -->
			<!-- BEGIN switch_user_logged_in -->
			<td align="center" valign="middle"><a title="{L_PROFILE}" href="{U_PROFILE}" class="mainmenu" onmouseover="changeImages('btn_top_profile', '{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_profile_on.gif'); return true;" onmouseout="changeImages('btn_top_profile', '{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_profile.gif'); return true;"><img name="btn_top_profile" src="{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_profile.gif" height="19" border="0" alt="{L_PROFILE}" /></a></td>
			<td align="center" valign="middle"><a title="{PRIVATE_MESSAGE_INFO}" href="{U_PRIVATEMSGS}" class="mainmenu" onmouseover="changeImages('btn_top_pm', '{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_pm_on.gif'); return true;" onmouseout="changeImages('btn_top_pm', '{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_pm.gif'); return true;"><img name="btn_top_pm" src="{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_pm.gif" height="19" border="0" alt="{PRIVATE_MESSAGE_INFO}" /></a></td>
			<!-- END switch_user_logged_in -->
			<td align="center" valign="middle"><a title="{L_FAQ}" href="{U_FAQ}" class="mainmenu" onmouseover="changeImages('btn_top_faq', '{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_faq_on.gif'); return true;" onmouseout="changeImages('btn_top_faq', '{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_faq.gif'); return true;"><img name="btn_top_faq" src="{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_faq.gif" height="19" border="0" alt="{L_FAQ}" /></a></td>
			<td align="center" valign="middle"><a title="{L_MEMBERLIST}" href="{U_MEMBERLIST}" class="mainmenu" onmouseover="changeImages('btn_top_users', '{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_users_on.gif'); return true;" onmouseout="changeImages('btn_top_users', '{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_users.gif'); return true;"><img name="btn_top_users" src="{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_users.gif" height="19" border="0" alt="{L_MEMBERLIST}" /></a></td>
			<td align="center" valign="middle"><a title="{L_SEARCH}" href="{U_SEARCH}" class="mainmenu" onmouseover="changeImages('btn_top_search', '{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_search_on.gif'); return true;" onmouseout="changeImages('btn_top_search', '{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_search.gif'); return true;"><img name="btn_top_search" src="{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_search.gif" height="19" border="0" alt="{L_SEARCH}" /></a></td>
			<!-- BEGIN switch_user_logged_in -->
			<td align="center" valign="middle"><a title="{L_USERGROUPS}" href="{U_GROUP_CP}" class="mainmenu" onmouseover="changeImages('btn_top_groups', '{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_groups_on.gif'); return true;" onmouseout="changeImages('btn_top_groups', '{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_groups.gif'); return true;"><img name="btn_top_groups" src="{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_groups.gif" height="19" border="0" alt="{L_USERGROUPS}" /></a></td>
			<td align="center" valign="middle"><a title="{L_LOGIN_LOGOUT}" href="{U_LOGIN_LOGOUT}" class="mainmenu" onmouseover="changeImages('btn_top_logout', '{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_logout_on.gif'); return true;" onmouseout="changeImages('btn_top_logout', '{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_logout.gif'); return true;"><img name="btn_top_logout" src="{T_TEMPLATE_PATH}/images/lang_{LANG}/btn_logout.gif" height="19" border="0" alt="{L_LOGIN_LOGOUT}" /></a></td>
			<!-- END switch_user_logged_in -->
		</tr>
		</table></td>
	</tr>
	<tr height="20">
		<td align="center" valign="middle" width="100%" height="20"><img src="{T_TEMPLATE_PATH}/images/spacer.gif" width="1" height="18" border="0" alt="" /></td>
	</tr>
</table>

<br />

<table border="0" cellspacing="0" cellpadding="10" width="100%">
<tr>
	<td align="center" valign="top">
