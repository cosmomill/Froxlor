$header
	<form method="post" action="{$linker->getLink(array('section' => 'ftp'))}">
		<input type="hidden" name="s" value="$s" />
		<input type="hidden" name="page" value="$page" />
		<input type="hidden" name="action" value="$action" />
		<input type="hidden" name="send" value="send" />
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable_60">
			<tr>
				<td class="maintitle" colspan="2"><b><img src="images/Minimalist/title.gif" alt="" />&nbsp;{$title}</b></td>
			</tr>
			{$ftp_add_form}
		</table>
	</form>
	<br />
	<br />
$footer
