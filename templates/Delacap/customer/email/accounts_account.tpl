<tr>
	<td>{$row['username']}</td>
	<td>{$row['description']}</td>
	<td><a class="btn btn-mini" alt="{$lng['emails']['edit_desc']}" href="{$linker->getLink(array('section' => 'email_users', 'page' => $page, 'action' => 'editdesc', 'id' => $row['id']))}">{$lng['emails']['edit_desc']}</a>
	<td><a class="btn btn-mini" alt="{$lng['menue']['main']['changepassword']}" href="{$linker->getLink(array('section' => 'email_users', 'page' => $page, 'action' => 'edit', 'id' => $row['id']))}">{$lng['menue']['main']['changepassword']}</a>
	<td><a rel="confirm" class="btn btn-mini" alt="{$lng['panel']['delete']}" href="{$linker->getLink(array('section' => 'email_users', 'page' => $page, 'action' => 'delete', 'id' => $row['id']))}"><i class="icon-trash"></i></a>
</tr>
 