<?php

/**
 * This file is part of the Froxlor project.
 * Copyright (c) 2003-2009 the SysCP Team (see authors).
 * Copyright (c) 2010 the Froxlor Team (see authors).
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.froxlor.org/misc/COPYING.txt
 *
 * @copyright  (c) the authors
 * @author     Florian Lippert <flo@syscp.org> (2003-2009)
 * @author     Froxlor team <team@froxlor.org> (2010-)
 * @license    GPLv2 http://files.froxlor.org/misc/COPYING.txt
 * @package    Configfiles
 * @version    $Id$
 */

return Array(
	'suse_linux_10_0' => Array(
		'label' => 'SUSE Linux 10.0',
		'services' => Array(
			'http' => Array(
				'label' => $lng['admin']['configfiles']['http'],
				'daemons' => Array(
					'apache' => Array(
						'label' => 'Apache',
						'commands' => Array(
							$configcommand['vhost'],
							$configcommand['diroptions'],
							$configcommand['include'],
							'mkdir -p ' . $settings['system']['documentroot_prefix'],
							'mkdir -p ' . $settings['system']['logfiles_directory'],
							($settings['system']['deactivateddocroot'] != '') ? 'mkdir -p ' . $settings['system']['deactivateddocroot'] : ''
						),
						'restart' => Array(
							'/etc/init.d/apache2 restart'
						)
					),
				)
			),
			'dns' => Array(
				'label' => $lng['admin']['configfiles']['dns'],
				'daemons' => Array(
					'bind' => Array(
						'label' => 'Bind9',
						'commands' => Array(
							'echo "include \"' . $settings['system']['bindconf_directory'] . 'froxlor_bind.conf\";" >> /etc/named.conf',
							'touch ' . $settings['system']['bindconf_directory'] . 'froxlor_bind.conf'
						),
						'restart' => Array(
							'/etc/init.d/named restart'
						)
					),
				)
			),
			'smtp' => Array(
				'label' => $lng['admin']['configfiles']['smtp'],
				'daemons' => Array(
					'postfix' => Array(
						'label' => 'Postfix',
						'files' => Array(
							'etc_postfix_main.cf' => '/etc/postfix/main.cf',
							'etc_postfix_mysql-virtual_alias_maps.cf' => '/etc/postfix/mysql-virtual_alias_maps.cf',
							'etc_postfix_mysql-virtual_mailbox_domains.cf' => '/etc/postfix/mysql-virtual_mailbox_domains.cf',
							'etc_postfix_mysql-virtual_mailbox_maps.cf' => '/etc/postfix/mysql-virtual_mailbox_maps.cf',
							'usr_lib_sasl2_smtpd.conf' => '/usr/lib/sasl2/smtpd.conf'
						),
						'commands' => Array(
							'mkdir -p /var/spool/postfix/etc/pam.d',
							'groupadd -g ' . $settings['system']['vmail_gid'] . ' vmail',
							'useradd -u ' . $settings['system']['vmail_uid'] . ' -g vmail vmail',
							'mkdir -p ' . $settings['system']['vmail_homedir'],
							'chown -R vmail:vmail ' . $settings['system']['vmail_homedir'],
							'touch /etc/postfix/mysql-virtual_alias_maps.cf',
							'touch /etc/postfix/mysql-virtual_mailbox_domains.cf',
							'touch /etc/postfix/mysql-virtual_mailbox_maps.cf',
							'touch /usr/lib/sasl2/smtpd.conf',
							'chmod 660 /etc/postfix/mysql-virtual_alias_maps.cf',
							'chmod 660 /etc/postfix/mysql-virtual_mailbox_domains.cf',
							'chmod 660 /etc/postfix/mysql-virtual_mailbox_maps.cf',
							'chmod 660 /usr/lib/sasl2/smtpd.conf',
							'chgrp postfix /etc/postfix/mysql-virtual_alias_maps.cf',
							'chgrp postfix /etc/postfix/mysql-virtual_mailbox_domains.cf',
							'chgrp postfix /etc/postfix/mysql-virtual_mailbox_maps.cf',
							'chgrp postfix /usr/lib/sasl2/smtpd.conf'
						),
						'restart' => Array(
							'/etc/init.d/postfix restart'
						)
					)
				)
			),
			'mail' => Array(
				'label' => $lng['admin']['configfiles']['mail'],
				'daemons' => Array(
					'courier' => Array(
						'label' => 'Courier',
						'files' => Array(
							'etc_authlib_authdaemonrc' => '/etc/authlib/authdaemonrc',
							'etc_authlib_authmysqlrc' => '/etc/authlib/authmysqlrc'
						),
						'restart' => Array(
							'/etc/init.d/courier-authdaemon restart',
							'/etc/init.d/courier-pop restart'
						)
					),
				)
			),
			'ftp' => Array(
				'label' => $lng['admin']['configfiles']['ftp'],
				'daemons' => Array(
					'proftpd' => Array(
						'label' => 'ProFTPd',
						'files' => Array(
							'etc_proftpd_modules.conf' => '/etc/proftpd/modules.conf',
							'etc_proftpd_proftpd.conf' => '/etc/proftpd/proftpd.conf'
						),
						'restart' => Array(
							'/etc/init.d/proftpd restart'
						)
					),
				)
			),
			'etc' => Array(
				'label' => $lng['admin']['configfiles']['etc'],
				'daemons' => Array(
					'cron' => Array(
						'label' => 'Crond (cronscript)',
						'files' => Array(
							'etc_cron.d_froxlor' => '/etc/cron.d/froxlor'
						),
						'restart' => Array(
							'/etc/init.d/cron restart'
						)
					),
					'awstats' => Array(
						'label' => 'Awstats',
						'files' => Array(
							'etc_awstats.model.conf' => '/etc/awstats.model.conf'
						)
					)
				)
			)
		)
	)
);

?>
