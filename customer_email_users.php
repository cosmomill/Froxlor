<?php

/**
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.froxlor.org/misc/COPYING.txt
 *
 * @copyright  (c) the authors
 * @author     Florian Lippert <flo@syscp.org> (2003-2009) started as ftp file
 * @author     Yaser Oulabi <yaser.oulabi@gmail.com> file changed to email
 * @author     Rene Kanzler <rk@cosmomill.de> updated to work with the latest version of Froxlor
 * @license    GPLv2 http://files.froxlor.org/misc/COPYING.txt
 * @package    Panel
 *
 */

define('AREA', 'customer');

/**
 * Include our init.php, which manages Sessions, Language etc.
 */
require("./lib/init.php");

if(isset($_POST['id']))
{
	$id = intval($_POST['id']);
}
elseif(isset($_GET['id']))
{
	$id = intval($_GET['id']);
}

if($page == 'accounts')
{
	if($action == '')
	{
		$log->logAction(USR_ACTION, LOG_NOTICE, "viewed customer_email_users::accounts");
		$fields = array(
			'username' => $lng['login']['username'],
			'description' => $lng['emails']['account_description']
		);
		
		$paging = new paging($userinfo, $db, TABLE_MAIL_VIRTUAL, $fields, $settings['panel']['paging'], $settings['panel']['natsorting']);
		$result = $db->query("SELECT `id`, `username`, `description` FROM `".TABLE_MAIL_USERS."` WHERE `customerid`='".(int)$userinfo['customerid']."' " . $paging->getSqlWhere(true) . " " . $paging->getSqlOrderBy() . " " . $paging->getSqlLimit());
		$paging->setEntries($db->num_rows($result));
		$sortcode = $paging->getHtmlSortCode($lng);
		$arrowcode = $paging->getHtmlArrowCode($filename . '?page=' . $page . '&s=' . $s);
		$pagingcode = $paging->getHtmlPagingCode($filename . '?page=' . $page . '&s=' . $s);
		$searchcode = $paging->getHtmlSearchCode($lng);
		$accounts='';
		
		while($row = $db->fetch_array($result))
		{	
			if($paging->checkDisplay($i))
			{
				eval("\$accounts.=\"" . getTemplate("email/accounts_account") . "\";");
			}
			
			$i++;
		}
		
		if($accounts == '') {
			$colspan = 5;
			eval("\$accounts.=\"" . getTemplate("email/no_accounts") . "\";");
		}
		$emails_count = $db->num_rows($result);

		eval("echo \"" . getTemplate("email/accounts") . "\";");
	}

	elseif($action == 'delete' && $id != 0)
	{
		$result = $db->query_first("SELECT `id`, `username`, `description`, `used_by` FROM `".TABLE_MAIL_USERS."` WHERE `customerid`='".(int)$userinfo['customerid']."' AND `id`='".(int)$id."'");
		if(isset($_POST['send']) && $_POST['send']=='send')
		{
			$db->query("DELETE FROM `".TABLE_MAIL_USERS."` WHERE `customerid`='".(int)$userinfo['customerid']."' AND `id`='".(int)$id."'");
			if($userinfo['email_accounts_used']=='1')
			{
				$resetaccnumber = " , `email_lastaccountnumber`='0'";
			}
			else
			{
				$resetaccnumber = '';
			}
			$used_by = explode(' ', $result['used_by']);
			if(is_array($used_by)) {
				foreach($used_by as $email_full) {
					$resultu=$db->query_first("SELECT `email_full`, `destination` FROM `".TABLE_MAIL_VIRTUAL."` WHERE `customerid`='".(int)$userinfo['customerid']."' AND `email_full`='$email_full'");
					
					$resultu['destination'] = str_replace ( $result['username'] , '' , $resultu['destination'] ) ;
					$db->query("UPDATE `".TABLE_MAIL_VIRTUAL."` SET `destination` = '".$db->escape(makeCorrectDestination($resultu['destination']))."' WHERE `customerid`='".(int)$userinfo['customerid']."' AND `email_full`='$email_full'");
				}
			}
			$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `email_accounts_used`=`email_accounts_used`-1 $resetaccnumber WHERE `customerid`='".(int)$userinfo['customerid']."'");
			redirectTo($filename, Array('page' => $page, 's' => $s));
		}
		else
		{
			if($result['used_by'] != '') {
				$used_by = str_replace(' ', '; ', $result['used_by']);
				$lng['question']['email_reallydelete_account'] = str_replace ('%s', $used_by, $lng['question']['email_reallydelete_account_used']).$lng['question']['email_reallydelete_account'];
			}
			ask_yesno('email_reallydelete_account', $filename, array( 'id' => $id, 'page' => $page, 'action' => $action ), $result['username']);
		}
	}

	elseif($action == 'add')
	{
		if(isset($_POST['email_quota']))
		{
			$quota = validate($_POST['email_quota'], 'email_quota', '/^\d+$/', 'vmailquotawrong');
		}
		
		if($userinfo['email_accounts'] == '-1'
		   || ($userinfo['email_accounts_used'] < $userinfo['email_accounts']))
		{
			if(isset($_POST['send']) && $_POST['send'] == 'send')
			{
				$description = $_POST['account_description'];
				$password = validate($_POST['account_password'], 'password');
				$password = validatePassword($password);
				
				if($settings['panel']['sendalternativemail'] == 1)
				{
					$alternative_email = $idna_convert->encode(validate($_POST['alternative_email'], 'alternative_email'));
				}
				else
				{
					$alternative_email = '';
				}
				
				if($settings['system']['mail_quota_enabled'] == 1)
				{
					if($userinfo['email_quota'] != '-1'
					   && ($quota == 0 || ($quota + $userinfo['email_quota_used']) > $userinfo['email_quota']))
					{
						standard_error('allocatetoomuchquota', $quota);
					}
				}
				else
				{
					$quota = 0;
				}

				if($password == ''
					&& !($settings['panel']['sendalternativemail'] == 1 && validateEmail($alternative_email)))
				{
					standard_error(array('stringisempty', 'mypassword'));
				}
				else
				{
					if($password == '')
					{
						$password = substr(md5(uniqid(microtime(), 1)), 12, 6);
					}
						
					$username = $userinfo['loginname'].$settings['customer']['emailprefix'].(intval($userinfo['email_lastaccountnumber'])+1);
					$db->query("INSERT INTO `" . TABLE_MAIL_USERS . "` (`customerid`, `email`, `username`, " . ($settings['system']['mailpwcleartext'] == '1' ? '`password`, ' : '') . " `password_enc`, `homedir`, `maildir`, `uid`, `gid`, `postfix`, `quota`, `imap`, `pop3`, `description`) VALUES ('" . (int)$userinfo['customerid'] . "', '" . $db->escape($username) . "', '" . $db->escape($username) . "', " . ($settings['system']['mailpwcleartext'] == '1' ? "'" . $db->escape($password) . "'," : '') . " ENCRYPT('" . $db->escape($password) . "'), '" . $db->escape($settings['system']['vmail_homedir']) . "', '" . $db->escape($userinfo['loginname'] . '/' . $username . '/') . "', '" . (int)$settings['system']['vmail_uid'] . "', '" . (int)$settings['system']['vmail_gid'] . "', 'y', '" . (int)$quota . "', '" . (int)$userinfo['imap'] . "', '" . (int)$userinfo['pop3'] . "', '".$db->escape($description)."')");
					$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `email_accounts_used`=`email_accounts_used`+1, `email_lastaccountnumber`=`email_lastaccountnumber`+1 WHERE `customerid`='".(int)$userinfo['customerid']."'");
					$replace_arr = array(
						'EMAIL' => $username,
						'PASSWORD' => $password
					);
					$admin = $db->query_first('SELECT `name`, `email` FROM `' . TABLE_PANEL_ADMINS . '` WHERE `adminid`=\'' . (int)$userinfo['adminid'] . '\'');
					$result = $db->query_first('SELECT `value` FROM `' . TABLE_PANEL_TEMPLATES . '` WHERE `adminid`=\'' . (int)$userinfo['adminid'] . '\' AND `language`=\'' . $db->escape($userinfo['def_language']) . '\' AND `templategroup`=\'mails\' AND `varname`=\'pop_success_subject\'');
					$mail_subject = html_entity_decode(replace_variables((($result['value'] != '') ? $result['value'] : $lng['mails']['pop_success']['subject']), $replace_arr));
					$result = $db->query_first('SELECT `value` FROM `' . TABLE_PANEL_TEMPLATES . '` WHERE `adminid`=\'' . (int)$userinfo['adminid'] . '\' AND `language`=\'' . $db->escape($userinfo['def_language']) . '\' AND `templategroup`=\'mails\' AND `varname`=\'pop_success_mailbody\'');
					$mail_body = html_entity_decode(replace_variables((($result['value'] != '') ? $result['value'] : $lng['mails']['pop_success']['mailbody']), $replace_arr));
					
					$_mailerror = false;
					
					if(!mail("Email account $username <$username>", $mail_subject, $mail_body, 'From: ' . getCorrectUserSalutation($admin) . ' <' . $admin['email'] . '>'))
					{
						$mailerr_msg = 'The message to "' . $username . '" failed';
						$_mailerror = true;
					}
					
					if ($_mailerror) {	
						$log->logAction(USR_ACTION, LOG_ERR, "Error sending mail: " . $mailerr_msg);
						standard_error('errorsendingmail', $username);
					}

					if(validateEmail($alternative_email)
						&& $settings['panel']['sendalternativemail'] == 1)
					{
						$result = $db->query_first('SELECT `value` FROM `' . TABLE_PANEL_TEMPLATES . '` WHERE `adminid`=\'' . (int)$userinfo['adminid'] . '\' AND `language`=\'' . $db->escape($userinfo['def_language']) . '\' AND `templategroup`=\'mails\' AND `varname`=\'pop_success_alternative_subject\'');
						$mail_subject = replace_variables((($result['value'] != '') ? $result['value'] : $lng['mails']['pop_success_alternative']['subject']), $replace_arr);
						$result = $db->query_first('SELECT `value` FROM `' . TABLE_PANEL_TEMPLATES . '` WHERE `adminid`=\'' . (int)$userinfo['adminid'] . '\' AND `language`=\'' . $db->escape($userinfo['def_language']) . '\' AND `templategroup`=\'mails\' AND `varname`=\'pop_success_alternative_mailbody\'');
						$mail_body = replace_variables((($result['value'] != '') ? $result['value'] : $lng['mails']['pop_success_alternative']['mailbody']), $replace_arr);
						
						$_mailerror = false;
						try {
							$mail->SetFrom($admin['email'], getCorrectUserSalutation($admin));
							$mail->Subject = $mail_subject;
							$mail->AltBody = $mail_body;
							$mail->MsgHTML(str_replace("\n", "<br />", $mail_body));
							$mail->AddAddress($idna_convert->encode($alternative_email), 'Email account ' . $username);
							$mail->Send();
						} catch(phpmailerException $e) {
							$mailerr_msg = $e->errorMessage();
							$_mailerror = true;
						} catch (Exception $e) {
							$mailerr_msg = $e->getMessage();
							$_mailerror = true;
						}
	
						if ($_mailerror) {	
							$log->logAction(USR_ACTION, LOG_ERR, "Error sending mail: " . $mailerr_msg);
							standard_error(array('errorsendingmail', $alternative_email));
						}

						$mail->ClearAddresses();
					}
					
					$log->logAction(USR_ACTION, LOG_INFO, "added email account '" . $username . "'");
					redirectTo($filename, Array('page' => $page, 's' => $s));
				}
			}
			else 
			{
				$username = $userinfo['loginname'].$settings['customer']['emailprefix'].(intval($userinfo['email_lastaccountnumber'])+1);;
				
				$account_add_data = include_once dirname(__FILE__).'/lib/formfields/customer/email/formfield.emails_createaccount.php';
				$account_add_form = htmlform::genHTMLForm($account_add_data);

				$title = $account_add_data['emails_add']['title'];
				$image = $account_add_data['emails_add']['image'];

				eval("echo \"" . getTemplate("email/accounts_add") . "\";");
			}
		}
		else
		{
			standard_error(array('allresourcesused', 'allocatetoomuchquota'), $quota);
		}
	}
	
	elseif($action == 'edit' && $id != 0)
	{
		$result = $db->query_first("SELECT `id`, `username`, `description` FROM `".TABLE_MAIL_USERS."` WHERE `customerid`='".(int)$userinfo['customerid']."' AND `id`='".(int)$id."'");
		if(isset($result['username']) && $result['username'] != '')
		{
			if(isset($_POST['send']) && $_POST['send'] == 'send')
			{
				$password = validate($_POST['account_password'], 'password');
				if($password == '')
				{
					standard_error(array('stringisempty', 'mypassword'));
					exit;
				}
				else
				{
					$db->query("UPDATE `".TABLE_MAIL_USERS."` SET `password`='$password', `password_enc`=ENCRYPT('$password') WHERE `customerid`='".(int)$userinfo['customerid']."' AND `id`='".(int)$id."'");
					redirectTo($filename, Array('page' => $page, 's' => $s));
				}
			}
			else
			{
				$result = htmlentities_array($result);
				
				$account_edit_data = include_once dirname(__FILE__).'/lib/formfields/customer/email/formfield.emails_editaccount.php';
				$account_edit_form = htmlform::genHTMLForm($account_edit_data);

				$title = $account_edit_data['emails_add']['title'];
				$image = $account_edit_data['emails_add']['image'];

				eval("echo \"" . getTemplate("email/accounts_edit") . "\";");
			}
		}
	}
	
	elseif($action == 'editdesc' && $id != 0)
	{
		$result = $db->query_first("SELECT `id`, `username`, `description` FROM `".TABLE_MAIL_USERS."` WHERE `customerid`='".(int)$userinfo['customerid']."' AND `id`='".(int)$id."'");
		if(isset($result['username']) && $result['username'] != '')
		{
			if(isset($_POST['send']) && $_POST['send'] == 'send')
			{
				$description = addslashes($_POST['account_description']);
				$db->query("UPDATE `".TABLE_MAIL_USERS."` SET `description`='$description' WHERE `customerid`='".(int)$userinfo['customerid']."' AND `id`='".(int)$id."'");
				redirectTo($filename, Array('page' => $page, 's' => $s));
			}
			else
			{
				$description = htmlentities_array($result['description']);
				
				$account_edit_desc_data = include_once dirname(__FILE__).'/lib/formfields/customer/email/formfield.emails_editdesc.php';
				$account_edit_desc_form = htmlform::genHTMLForm($account_edit_desc_data);

				$title = $account_edit_desc_data['emails_add']['title'];
				$image = $account_edit_desc_data['emails_add']['image'];

				eval("echo \"" . getTemplate("email/accounts_editdesc") . "\";");
			}
		}
	}
}
?>
