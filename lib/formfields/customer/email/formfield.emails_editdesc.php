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
	'emails_editdesc' => array(
		'title' => $lng['emails']['edit_desc'],
		'image' => 'icons/email_add.png',
		'sections' => array(
			'section_a' => array(
				'title' => $lng['emails']['edit_desc'],
				'image' => 'icons/email_add.png',
				'fields' => array(
					'account_username' => array(
						'label' => $lng['login']['username'],
						'type' => 'label',
						'value' => $result['username']
					),
					'account_description' => array(
						'label' => $lng['emails']['account_description'],
						'type' => 'text',
						'value' => '',
					)
				)
			)
		)
	)
);
