$header
	<article>
		<header>
			<h2>
				{$lng['menue']['email']['accounts']}&nbsp;({$emails_count})
			</h2>
		</header>
		
		<section>

			<form action="{$linker->getLink(array('section' => 'email_users', 'page' => 'accounts'))}" method="post" enctype="application/x-www-form-urlencoded">
	
			<div class="form-horizontal search">
				{$searchcode}
			</div>

			<if ($userinfo['email_accounts_used'] < $userinfo['email_accounts'] || $userinfo['email_accounts'] == '-1') && 15 < $emails_count >
				<div class="overviewadd">
					<a class="btn btn-inverse" href="{$linker->getLink(array('section' => 'email_users', 'page' => $page, 'action' => 'add'))}"><i class="icon-plus icon-white"></i>{$lng['ftp']['account_add']}</a>
				</div>
			</if>
			
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>{$lng['login']['username']}{$arrowcode['username']}</th>
						<th>{$lng['emails']['account_description']}{$arrowcode['description']}</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<if $pagingcode != ''>
					<tfoot>
						<tr>
							<td colspan="5"><div class="pagination">{$pagingcode}</div></td>
						</tr>
					</tfoot>
				</if>
				<tbody>
					{$accounts}
				</tbody>
			</table>
			
			<p style="display:none;">
				<input type="hidden" name="s" value="$s" />
				<input type="hidden" name="page" value="$page" />
			</p>

			</form>

			<if ($userinfo['email_accounts_used'] < $userinfo['email_accounts'] || $userinfo['email_accounts'] == '-1') >
				<div class="overviewadd">
					<a class="btn btn-inverse" href="{$linker->getLink(array('section' => 'email_users', 'page' => $page, 'action' => 'add'))}"><i class="icon-plus icon-white"></i>{$lng['ftp']['account_add']}</a>
				</div>
			</if>

		</section>
	</article>
$footer
