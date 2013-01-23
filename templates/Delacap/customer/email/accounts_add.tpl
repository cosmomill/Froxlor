$header
<article>
	<header>
		<h2>
			{$lng['emails']['account_add']}
		</h2>
	</header>

	<section class="fullform bradiusodd">

		<form rel="submit" class="form-inline form-delacap" action="{$linker->getLink(array('section' => 'email_users'))}" method="post" enctype="application/x-www-form-urlencoded">
			<fieldset>

				<table class="table table-bordered table-striped">
					{$account_add_form}
				</table>

				<p style="display: none;">
					<input type="hidden" name="s" value="$s" />
					<input type="hidden" name="page" value="$page" />
					<input type="hidden" name="action" value="$action" />
					<input type="hidden" name="username" value="$username">
					<input type="hidden" name="send" value="send" />
				</p>
			</fieldset>
		</form>
	</section>
</article>
$footer
  