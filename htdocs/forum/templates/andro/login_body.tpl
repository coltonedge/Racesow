<div class="racenet_headrow">
    Racenet Board
    <span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></span>
</div>


<form action="{S_LOGIN_ACTION}" method="post">

<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
  <tr> 
    <th height="25" class="thHead" nowrap="nowrap"><h2 style="padding: 0; margin: 0 0 4px 0; color: #FF7800; font-size: 20px; font-weight: bold; color: #FF7800;">Login <!--using <span style="cursor: help; color: #fff;" title="warsow.net forum login">warsowID</span> or <span style="cursor: help; color: #fff;" title="warsow-race.net forum login">racenetID</span>--></h2></th>
  </tr>
  <tr> 
        <td class="">
            <table border="0" cellpadding="3" cellspacing="1" width="100%">
              <tr> 
                    <td width="45%" align="right" style="text-align:right;"><span class="gen">{L_USERNAME}:</span></td>
                    <td style="text-align:left;"> 
                      <input type="text" name="username" size="25" maxlength="40" value="{USERNAME}" />
                    </td>
              </tr>
              <tr> 
                    <td align="right" style="text-align:right;"><span class="gen">{L_PASSWORD}:</span></td>
                    <td style="text-align:left;"> 
                      <input type="password" name="password" size="25" maxlength="25" />
                    </td>
              </tr>
              <tr> 
                    <td style="text-align:center;" colspan="2"><label for="asdf"><span class="gen">{L_AUTO_LOGIN}:</label> <input id="asdf" type="checkbox" name="autologin" /></span></td>
              </tr>
              <tr align="center"> 
                    <td style="text-align:center;" colspan="2">{S_HIDDEN_FIELDS}<input type="submit" name="login" class="mainoption" value="{L_LOGIN}" /></td>
              </tr>
              <tr align="center"> 
                    <td style="text-align:center;"  colspan="2">
                        <br/><br/><br/>
                        <a href="{U_SEND_PASSWORD}">I forgot my racenet password</a>
                        <br/>
                        <a href="/forum/profile.php?mode=register&agreed=true">I want to register a racenetID</a>
                        <!--
						<br/><br/>
                        <a href="http://www.warsow.net/forum/login.php">I forgot my warsow.net password</a>
                        <br/>
                        <a href="http://www.warsow.net/forum/login.php">I want to register a warsowID</a>
						-->
                    </td>
              </tr>
            </table>
        </td>
  </tr>
</table>

</form>
