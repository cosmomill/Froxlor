$header
	<form action="{$linker->getLink(array('section' => 'email_users', 'page' => 'accounts'))}" method="post">
		<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
			<tr>
				<td  class="maintitle_search_left"><b><img src="images/Classic/title.gif" alt="" />&nbsp;{$lng['menue']['email']['accounts']}</b>&nbsp;({$emails_count})</td>
				<td class="maintitle_search_right" colspan="6">{$searchcode}</td>
			</tr>
			<if ($userinfo['email_accounts_used'] < $userinfo['email_accounts'] || $userinfo['email_accounts'] == '-1') && 15 < $emails_count >
			<tr>
				<td class="field_display_border_left" colspan="5"><a href="{$linker->getLink(array('section' => 'email_users', 'page' => $page, 'action' => 'add'))}">{$lng['ftp']['account_add']}</a></td>
			</tr>
			</if>
			<tr>
				<td class="field_display_border_left">{$lng['login']['username']}&nbsp;&nbsp;{$arrowcode['username']}</td>
				<td class="field_display">{$lng['emails']['account_description']}&nbsp;&nbsp;{$arrowcode['description']}</td>
				<td class="field_display">&nbsp;</td>
				<td class="field_display" colspan="2">{$sortcode}</td>
			</tr>
			$accounts
			<if $pagingcode != ''>
			<tr>
				<td class="field_display_border_left" colspan="5" style=" text-align: center; ">{$pagingcode}</td>
			</tr>
			</if>
			<if ($userinfo['email_accounts_used'] < $userinfo['email_accounts'] || $userinfo['email_accounts'] == '-1') >
			<tr>
				<td class="field_display_border_left" colspan="5"><a href="{$linker->getLink(array('section' => 'email_users', 'page' => $page, 'action' => 'add'))}">{$lng['ftp']['account_add']}</a></td>
			</tr>
			</if>
		</table>
	</form>
	<br />
	<br />
$footer