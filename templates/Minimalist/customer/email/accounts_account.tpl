<tr>
	<td class="field_name_border_left">{$row['username']}</td>
	<td class="field_name">{$row['description']}</td>
	<td class="field_name"><a href="{$linker->getLink(array('section' => 'email_users', 'page' => $page, 'action' => 'editdesc', 'id' => $row['id']))}">{$lng['emails']['edit_desc']}</a></td>
	<td class="field_name"><a href="{$linker->getLink(array('section' => 'email_users', 'page' => $page, 'action' => 'edit', 'id' => $row['id']))}">{$lng['menue']['main']['changepassword']}</a></td>
	<td class="field_name"><a href="{$linker->getLink(array('section' => 'email_users', 'page' => $page, 'action' => 'delete', 'id' => $row['id']))}">{$lng['panel']['delete']}</a></td>
</tr>