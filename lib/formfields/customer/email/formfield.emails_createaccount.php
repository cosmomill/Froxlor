<?php

/**
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.froxlor.org/misc/COPYING.txt
 *
 * @copyright  (c) the authors
 * @author     Rene Kanzler <rk@cosmomill.de>
 * @license    GPLv2 http://files.froxlor.org/misc/COPYING.txt
 * @package    Formfields
 *
 */

return array(
	'emails_createaccount' => array(
		'title' => $lng['emails']['account_add'],
		'image' => 'icons/email_add.png',
		'sections' => array(
			'section_a' => array(
				'title' => $lng['emails']['account_add'],
				'image' => 'icons/email_add.png',
				'fields' => array(
					'account_username' => array(
						'label' => $lng['login']['username'],
						'type' => 'label',
						'value' => $username
					),
					'account_password' => array(
						'label' => $lng['login']['password'],
						'type' => 'password',
						'autocomplete' => 'off'
					),
					'account_password_suggestion' => array(
						'label' => $lng['customer']['generated_pwd'],
						'type' => 'text',
						'value' => generatePassword(),
					),
					'account_description' => array(
						'label' => $lng['emails']['account_description'],
						'type' => 'text',
						'value' => '',
					),
					'account_quota' => array(
						'visible' => ($settings['system']['mail_quota_enabled'] == '1' ? true : false),
						'label' => $lng['emails']['quota'],
						'desc' => $lng['panel']['megabyte'],
						'type' => 'text',
						'value' => $quota
					),
					'alternative_email' => array(
						'visible' => ($settings['panel']['sendalternativemail'] == '1' ? true : false),
						'label' => $lng['emails']['alternative_emailaddress'],
						'type' => 'text'
					)
				)
			)
		)
	)
);
