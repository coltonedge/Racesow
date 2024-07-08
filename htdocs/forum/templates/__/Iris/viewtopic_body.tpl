<!-- BEGIN switch_xs_enabled -->
<?php

// This code will be visible only with eXtreme Styles mod

$postrow_count = ( isset($this->_tpldata['postrow.']) ) ?  sizeof($this->_tpldata['postrow.']) : 0;
for ($postrow_i = 0; $postrow_i < $postrow_count; $postrow_i++)
{
	$postrow_item = &$this->_tpldata['postrow.'][$postrow_i];
	// replace username with link to user profile
	if(!empty($postrow_item['PROFILE']))
	{
		$postrow_item['SEARCH_IMG2'] = $postrow_item['SEARCH_IMG'];
		$search = array($lang['Read_profile'], '<a ');
		$replace = array($postrow_item['POSTER_NAME'], '<a class="name" ');
		$postrow_item['POSTER_NAME'] = str_replace($search, $replace, $postrow_item['PROFILE']);
	}
}

?>
<!-- END switch_xs_enabled -->
<table width="100%" cellspacing="2" cellpadding="2" border="0">
  <tr> 
	<td align="left" valign="middle"><span class="nav">
	  <a href="{U_INDEX}" class="nav">{L_INDEX}</a> 
	  &raquo; <a href="{U_VIEW_FORUM}" class="nav">{FORUM_NAME}</a>
	  &raquo; <a class="nav" href="{U_VIEW_TOPIC}">{TOPIC_TITLE}</a></span></td>
	 <td align="right" valign="middle"><span class="nav"><b>{PAGINATION}</b></span></td>
  </tr>
  <tr>
    <td align="left" valign="middle"><a href="{U_POST_NEW_TOPIC}"><img src="{POST_IMG}" border="0" alt="{L_POST_NEW_TOPIC}" align="middle" /></a>&nbsp;&nbsp;<a href="{U_POST_REPLY_TOPIC}"><img src="{REPLY_IMG}" border="0" alt="{L_POST_REPLY_TOPIC}" align="middle" /></a></td>
	<td align="right" valign="middle"><span class="nav">
	<a href="{U_VIEW_OLDER_TOPIC}" class="nav">{L_VIEW_PREVIOUS_TOPIC}</a> :: <a href="{U_VIEW_NEWER_TOPIC}" class="nav">{L_VIEW_NEXT_TOPIC}</a>&nbsp;
	</span></td>
  </tr>
</table>

{POLL_DISPLAY} 

<!-- BEGIN postrow -->
<a name="{postrow.U_POST_ID}"></a>
{TPL_HDR1}<span class="cattitle">{postrow.POST_SUBJECT}</span>{TPL_HDR2}<table border="0" cellpadding="0" cellspacing="1" width="100%" class="forumline">
<tr>
	<td class="th" align="center" valign="middle"><table border="0" cellspacing="0" cellpadding="2" width="100%">
	<tr height="26">
		<td align="left" valign="middle" nowrap="nowrap"><a href="{postrow.U_MINI_POST}"><img src="{postrow.MINI_POST_IMG}" width="12" height="9" alt="{postrow.L_MINI_POST_ALT}" title="{postrow.L_MINI_POST_ALT}" border="0" /></a><span class="genmed"><span class="th">{L_POSTED}: {postrow.POST_DATE}</span></span></td>
		<td align="right" valign="middle" nowrap="nowrap">{postrow.QUOTE_IMG} {postrow.EDIT_IMG} {postrow.DELETE_IMG} {postrow.IP_IMG} </td>
	</tr></table></td>
</tr>
<tr>
	<td class="row1" align="left" valign="top" width="100%"><table border="0" cellspacing="0" cellpadding="0" width="100%"><!-- main table start -->
	<tr>
		<td width="150" align="left" valign="top" rowspan="2"><table border="0" cellspacing="0" cellpadding="0" width="100%"><!-- left row table start -->
		<tr>
			<td width="100%" align="left" valign="top" background="{T_TEMPLATE_PATH}/images/post_bg.gif"><table border="0" cellspacing="0" cellpadding="4">
			<tr>
				<td align="left" valign="top"><table border="0" cellspacing="0" cellpadding="0">
				<tr><td nowrap="nowrap"><span class="name"><b>{postrow.POSTER_NAME}</b></span></td></tr>
				<tr><td nowrap="nowrap"><span class="postdetails">{postrow.POSTER_RANK}</span></td></tr>
				<tr><td nowrap="nowrap"><span class="postdetails">{postrow.RANK_IMAGE}{postrow.POSTER_AVATAR}</span></tr>
				<tr><td><img src="{T_TEMPLATE_PATH}/images/spacer.gif" width="110" height="5" border="0" alt="" /></td></tr>
				<tr><td><span class="postdetails">{postrow.POSTER_JOINED}</span></td></tr>
				<tr><td><span class="postdetails">{postrow.POSTER_POSTS}</span></td></tr>
				<tr><td><span class="postdetails">{postrow.POSTER_FROM}</span></td></tr>
				</table></td>
			</tr>
			</table><br /><br /></td>
			<td width="10" background="{T_TEMPLATE_PATH}/images/post_right.gif"><img src="{T_TEMPLATE_PATH}/images/spacer.gif" width="10" height="1" border="0" /></td>
		</tr>
		<tr>
			<td height="10" background="{T_TEMPLATE_PATH}/images/post_bottom.gif"><img src="{T_TEMPLATE_PATH}/images/spacer.gif" width="1" height="10" border="0" /></td>
			<td width="10" height="10"><img src="{T_TEMPLATE_PATH}/images/post_corner.gif" width="10" height="10" border="0" /></td>
		</tr>
		<!-- left row table end --></table><br /><br /></td>
		<td class="row1" align="left" valign="top" width="100%"><table border="0" cellspacing="0" cellpadding="5" width="100%"><!-- right top row table start -->
		<tr>
			<td width="100%"><span class="postbody">{postrow.MESSAGE}</span></td>
		</tr>
		<!-- right top row table end --></table></td>
	</tr>
	<tr>
		<td class="row1" align="left" valign="bottom" nowrap="nowrap"><table border="0" cellspacing="0" cellpadding="5" width="100%"><!-- right bottom row start -->
		<tr>
			<td width="100%"><span class="postbody"><span class="gensmall">{postrow.EDITED_MESSAGE}</span>{postrow.SIGNATURE}</span></td>
		</tr>
		<!-- right bottom row end --></table></td>
	</tr>
	</table></td>
</tr>
<tr>
	<td height="28" valign="middle" class="cat2"><table border="0" cellspacing="0" cellpadding="3">
	<tr>
		<td width="120"><img src="{T_TEMPLATE_PATH}/images/spacer.gif" width="120" height="1" border="0" /></td>
		<td width="100%" align="left" valign="middle" nowrap="nowrap">{postrow.PROFILE_IMG} {postrow.SEARCH_IMG2} {postrow.PM_IMG} {postrow.EMAIL_IMG} {postrow.WWW_IMG} {postrow.AIM_IMG} {postrow.YIM_IMG} {postrow.MSN_IMG} {postrow.ICQ_IMG}</td>
	</tr></table></td>
</tr>
</table>{TPL_FTR}
<!-- END postrow -->

{TPL_HDR1}<span class="cattitle">&nbsp;<a class="cattitle" href="{U_VIEW_TOPIC}">{TOPIC_TITLE}</a>&nbsp;</span>{TPL_HDR2}<table class="forumline" width="100%" cellspacing="1" cellpadding="" border="0">
<tr>
	<th align="left">&nbsp;<a class="th" href="{U_INDEX}">{L_INDEX}</a> &raquo; <a class="th" href="{U_VIEW_FORUM}">{FORUM_NAME}</a></th>
</tr>
<tr>
	<td class="row1" align="left" valign="top">
	<table border="0" cellspacing="0" cellpadding="5" width="100%">
	<tr>
		<td align="left" valign="top">
			<span class="gensmall">{S_AUTH_LIST}</span>
		</td>
		<td align="right" valign="top">
			<span class="gensmall">{S_TIMEZONE}&nbsp;&nbsp;<br />
			{PAGE_NUMBER}&nbsp;&nbsp;</span>
			<span class="nav"><b>{PAGINATION}</b></span><br />
			<span class="gensmall">{S_WATCH_TOPIC}</span>
		</td>
	</tr>
	</table>
	</td>
</tr>
<tr>
	<td class="cat2" align="center" valign="middle" nowrap="nowrap"><table border="0" cellspacing="0" cellpadding="2" width="100%">
	<tr>
		<form method="post" action="{S_POST_DAYS_ACTION}"><td align="left" valign="middle" nowrap="nowrap"><span class="cat2">{S_SELECT_POST_DAYS}&nbsp;{S_SELECT_POST_ORDER}&nbsp;<input type="submit" value="{L_GO}" class="liteoption" name="submit" /></span></td></form>
		<td align="right" valign="middle" nowrap="nowrap">{JUMPBOX}</td>
	</tr>
	</table>
	</td>
</tr>
</table>{TPL_FTR}

<table border="0" cellspacing="0" cellpadding="5" width="100%">
<tr>
	<td align="left" valign="top">&nbsp;<a href="{U_POST_NEW_TOPIC}"><img src="{POST_IMG}" border="0" alt="{L_POST_NEW_TOPIC}" align="middle" /></a>&nbsp;&nbsp;<a href="{U_POST_REPLY_TOPIC}"><img src="{REPLY_IMG}" border="0" alt="{L_POST_REPLY_TOPIC}" align="middle" /></a></td>
	<td align="right" valign="top">{S_TOPIC_ADMIN}&nbsp;</td>
</tr>
</table>
