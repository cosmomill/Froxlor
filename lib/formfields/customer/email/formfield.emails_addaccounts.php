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
	'emails_addaccount' => array(
		'title' => $lng['emails']['account_add'],
		'image' => 'icons/email_add.png',
		'sections' => array(
			'section_a' => array(
				'title' => $lng['emails']['account_add'],
				'image' => 'icons/email_add.png',
				'fields' => array(
					'email_full' => array(
						'label' => $lng['emails']['emailaddress'],
						'type' => 'label',
						'value' => $result['email_full']
					),
					'destination' => array(
						'label' => $lng['emails']['forward_to_account'],
						'type' => 'select',
						'select_var' => $account_options
					)
				)
			)
		)
	)
);
