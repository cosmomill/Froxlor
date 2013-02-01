<?php

/**
 * This file is part of the Froxlor project.
 * Copyright (c) 2010 the Froxlor Team (see authors).
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.froxlor.org/misc/COPYING.txt
 *
 * @copyright  (c) the authors
 * @author     Michael Kaufmann <mkaufmann@nutime.de>
 * @author     Froxlor team <team@froxlor.org> (2010-)
 * @license    GPLv2 http://files.froxlor.org/misc/COPYING.txt
 * @package    Cron
 *
 * @link       http://www.nutime.de/
 * @since      0.9.16
 *
 */

class phpinterface_fpm
{
	/**
	 * Database handler
	 * @var object
	 */
	private $_db = false;

	/**
	 * Settings array
	 * @var array
	 */
	private $_settings = array();

	/**
	 * Domain-Data array
	 * @var array
	 */
	private $_domain = array();

	/**
	 * main constructor
	 */
	public function __construct($db, $settings, $domain)
	{
		$this->_db = $db;
		$this->_settings = $settings;
		$this->_domain = $domain;
	}

	public function createConfig($phpconfig)
	{
		$fh = @fopen($this->getConfigFile(), 'w');
		if($fh)
		{
			$fpm_pm = $this->_settings['phpfpm']['pm'];
			$fpm_children = (int)$this->_settings['phpfpm']['max_children'];
			$fpm_start_servers = (int)$this->_settings['phpfpm']['start_servers'];
			$fpm_min_spare_servers = (int)$this->_settings['phpfpm']['min_spare_servers'];
			$fpm_max_spare_servers = (int)$this->_settings['phpfpm']['max_spare_servers'];
			$fpm_requests = (int)$this->_settings['phpfpm']['max_requests'];

			if($fpm_children == 0) {
				$fpm_children = 1;
			}

			$fpm_config = ';PHP-FPM configuration for "'.$this->_domain['domain'].'" created on ' . date("Y.m.d H:i:s") . "\n";
			$fpm_config.= '['.$this->_domain['domain'].']'."\n";
			$fpm_config.= 'listen = '.$this->getSocketFile()."\n";
			
			// try to find out if libnss-mysql is installed
			// PHP's stat does not work with libnss-mysql
			$dir_owner = safe_exec('stat -c %U ' . makeCorrectDir($this->_domain['documentroot']));
			
			if($dir_owner[0] == 'UNKNOWN')
			{
				$fpm_config.= 'listen.mode = 0666'."\n";
				$fpm_config.= 'user = '.$this->_domain['guid']."\n";
				$fpm_config.= 'group = '.$this->_domain['guid']."\n";
			}
			else
			{
				if($this->_domain['loginname'] == 'froxlor.panel')
				{
					$fpm_config.= 'listen.owner = '.$this->_domain['guid']."\n";
					$fpm_config.= 'listen.group = '.$this->_domain['guid']."\n";
				}
				else
				{
					$fpm_config.= 'listen.owner = '.$this->_domain['loginname']."\n";
					$fpm_config.= 'listen.group = '.$this->_domain['loginname']."\n";
				}
				$fpm_config.= 'listen.mode = 0666'."\n";
	
				if($this->_domain['loginname'] == 'froxlor.panel')
				{
					$fpm_config.= 'user = '.$this->_domain['guid']."\n";
					$fpm_config.= 'group = '.$this->_domain['guid']."\n";
				}
				else
				{
					$fpm_config.= 'user = '.$this->_domain['loginname']."\n";
					$fpm_config.= 'group = '.$this->_domain['loginname']."\n";
				}
			}

			$fpm_config.= 'pm = '.$fpm_pm."\n";
			$fpm_config.= 'pm.max_children = '.$fpm_children."\n";
			if($fpm_pm == 'dynamic') {
				$fpm_config.= 'pm.start_servers = '.$fpm_start_servers."\n";
				$fpm_config.= 'pm.min_spare_servers = '.$fpm_min_spare_servers."\n";
				$fpm_config.= 'pm.max_spare_servers = '.$fpm_max_spare_servers."\n";
			}
			$fpm_config.= 'pm.max_requests = '.$fpm_requests."\n";

			$fpm_config.= ';chroot = '.makeCorrectDir($this->_domain['documentroot'])."\n";

			$tmpdir = makeCorrectDir($this->_settings['phpfpm']['tmpdir'] . '/' . $this->_domain['loginname'] . '/');
			if(!is_dir($tmpdir))
			{
				$this->getTempDir();
			}
			//$slowlog = makeCorrectFile($this->_settings['system']['logfiles_directory'] . $this->_domain['loginname'] . '/php-fpm_slow.log');

			$fpm_config.= 'env[TMP] = '.$tmpdir."\n";
			$fpm_config.= 'env[TMPDIR] = '.$tmpdir."\n";
			$fpm_config.= 'env[TEMP] = '.$tmpdir."\n";

			$fpm_config.= 'php_admin_value[sendmail_path] = /usr/sbin/sendmail -t -i -f '.$this->_domain['email']."\n";
			if($this->_domain['loginname'] != 'froxlor.panel')
			{
				if($this->_domain['openbasedir'] == '1')
				{
					$openbasedir = '';
					$_phpappendopenbasedir = '';
					$_custom_openbasedir = explode(':', $this->_settings['phpfpm']['peardir']);
					foreach($_custom_openbasedir as $cobd)
					{
						$_phpappendopenbasedir .= appendOpenBasedirPath($cobd);
					}

					$_custom_openbasedir = explode(':', $this->_settings['system']['phpappendopenbasedir']);
					foreach($_custom_openbasedir as $cobd)
					{
						$_phpappendopenbasedir .= appendOpenBasedirPath($cobd);
					}

					if($this->_domain['openbasedir_path'] == '0' && strstr($this->_domain['documentroot'], ":") === false)
					{
						$openbasedir = appendOpenBasedirPath($this->_domain['documentroot'], true);
					}
					else
					{
						$openbasedir = appendOpenBasedirPath($this->_domain['customerroot'], true);
					}

					$openbasedir .= appendOpenBasedirPath($this->getTempDir());
					$openbasedir .= $_phpappendopenbasedir;

					$openbasedir = explode(':', $openbasedir);
					$clean_openbasedir = array();
					foreach($openbasedir as $number => $path)
					{
						if(trim($path) != '/')
						{
							$clean_openbasedir[] = makeCorrectDir($path);
						}
					}
					$openbasedir = implode(':', $clean_openbasedir);

					$fpm_config.= 'php_admin_value[open_basedir] = ' . $openbasedir . "\n";
				}
			}
			$fpm_config.= 'php_admin_value[session.save_path] = ' . makeCorrectDir($this->_settings['phpfpm']['tmpdir'] . '/' . $this->_domain['loginname'] . '/') . "\n";
			$fpm_config.= 'php_admin_value[upload_tmp_dir] = ' . makeCorrectDir($this->_settings['phpfpm']['tmpdir'] . '/' . $this->_domain['loginname'] . '/') . "\n";

			fwrite($fh, $fpm_config, strlen($fpm_config));
			fclose($fh);
		}
	}

	public function createIniFile($phpconfig)
	{
		if(PHP_VERSION_ID < 50300)
		{
			return;
		}
		
		$openbasedir = '';
		$openbasedirc = ';';

		if($this->_domain['openbasedir'] == '1')
		{
			$openbasedirc = '';
			$_phpappendopenbasedir = '';

			$_custom_openbasedir = explode(':', $this->_settings['system']['mod_fcgid_peardir']);
			foreach($_custom_openbasedir as $cobd)
			{
				$_phpappendopenbasedir .= appendOpenBasedirPath($cobd);
			}

			$_custom_openbasedir = explode(':', $this->_settings['system']['phpappendopenbasedir']);
			foreach($_custom_openbasedir as $cobd)
			{
				$_phpappendopenbasedir .= appendOpenBasedirPath($cobd);
			}

			if($this->_domain['openbasedir_path'] == '0' && strstr($this->_domain['documentroot'], ":") === false)
			{
				$openbasedir = appendOpenBasedirPath($this->_domain['documentroot'], true);
			}
			else
			{
				$openbasedir = appendOpenBasedirPath($this->_domain['customerroot'], true);
			}

			$openbasedir .= appendOpenBasedirPath($this->getTempDir());
			$openbasedir .= $_phpappendopenbasedir;

			$openbasedir = explode(':', $openbasedir);
			$clean_openbasedir = array();
			foreach($openbasedir as $number => $path)
			{
				if(trim($path) != '/')
				{
					$clean_openbasedir[] = makeCorrectDir($path);
				}
			}
			$openbasedir = implode(':', $clean_openbasedir);
		}
		else
		{
			$openbasedir = 'none';
			$openbasedirc = ';';
		}

		$admin = $this->_getAdminData($this->_domain['adminid']);
		$php_ini_variables = array(
			'SAFE_MODE' => ($this->_domain['safemode'] == '0' ? 'Off' : 'On'),
			'PEAR_DIR' => $this->_settings['system']['mod_fcgid_peardir'],
			'OPEN_BASEDIR' => $openbasedir,
			'OPEN_BASEDIR_C' => $openbasedirc,
			'OPEN_BASEDIR_GLOBAL' => $this->_settings['system']['phpappendopenbasedir'],
			'TMP_DIR' => $this->getTempDir(),
			'CUSTOMER_EMAIL' => $this->_domain['email'],
			'ADMIN_EMAIL' => $admin['email'],
			'DOMAIN' => $this->_domain['domain'],
			'CUSTOMER' => $this->_domain['loginname'],
			'ADMIN' => $admin['loginname']
		);

		//insert a small header for the file

		$phpini_file = ";\n";
		$phpini_file.= "; php.ini created/changed on " . date("Y.m.d H:i:s") . " for domain '" . $this->_domain['domain'] . "' with id #" . $this->_domain['id'] . " from php template '" . $phpconfig['description'] . "' with id #" . $phpconfig['id'] . "\n";
		$phpini_file.= "; Do not change anything in this file, it will be overwritten by the Froxlor Cronjob!\n";
		$phpini_file.= ";\n\n";
		$phpini_file.= "[PATH=" . makeCorrectDir($this->_domain['documentroot']) . "]\n\n";
		$phpini_file.= replace_variables($phpconfig['phpsettings'], $php_ini_variables);
		$phpini_file.= "\n[PHP]\n";
		
		// remove deprecated INI directives
		$deprecated = array('safe_mode = On', 'safe_mode = Off');
		$phpini_file = str_replace($deprecated, '', $phpini_file);
		
		$phpini_file = str_replace('"none"', 'none', $phpini_file);
		$phpini_file = preg_replace('/\"+/', '"', $phpini_file);
		$phpini_file_handler = fopen($this->getIniFile(), 'w');
		fwrite($phpini_file_handler, $phpini_file);
		fclose($phpini_file_handler);
		safe_exec('chown root:0 ' . escapeshellarg($this->getIniFile()));
		safe_exec('chmod 0644 ' . escapeshellarg($this->getIniFile()));
	}

	/**
	 * fpm-config file
	 *
	 * @param boolean $createifnotexists create the directory if it does not exist
	 *
	 * @return string the full path to the file
	 */
	public function getConfigFile($createifnotexists = true)
	{
		$configdir = makeCorrectDir($this->_settings['phpfpm']['configdir']);
		$config = makeCorrectFile($configdir.'/'.$this->_domain['domain'].'.conf');

		if(!is_dir($configdir) && $createifnotexists)
		{
			safe_exec('mkdir -p ' . escapeshellarg($configdir));
		}

		return $config;
	}

	/**
	 * return path of fpm-socket file
	 *
	 * @param boolean $createifnotexists create the directory if it does not exist
	 *
	 * @return string the full path to the socket
	 */
	public function getSocketFile($createifnotexists = true)
	{
		$socketdir = makeCorrectDir('/var/run/'.$this->_settings['system']['webserver'].'/');
		$socket = makeCorrectFile($socketdir.'/'.$this->_domain['loginname'].'-'.$this->_domain['domain'].'-php-fpm.socket');

		if(!is_dir($socketdir) && $createifnotexists)
		{
			safe_exec('mkdir -p '.escapeshellarg($socketdir));
			safe_exec('chown -R '.$this->_settings['system']['httpuser'].':'.$this->_settings['system']['httpgroup'].' '.escapeshellarg($socketdir));
		}

		return $socket;
	}

	/**
	 * fpm-temp directory
	 *
	 * @param boolean $createifnotexists create the directory if it does not exist
	 *
	 * @return string the directory
	 */
	public function getTempDir($createifnotexists = true)
	{
		$tmpdir = makeCorrectDir($this->_settings['phpfpm']['tmpdir'] . '/' . $this->_domain['loginname'] . '/');

		if(!is_dir($tmpdir) && $createifnotexists)
		{
			safe_exec('mkdir -p ' . escapeshellarg($tmpdir));
			safe_exec('chown -R ' . $this->_domain['guid'] . ':' . $this->_domain['guid'] . ' ' . escapeshellarg($tmpdir));
			safe_exec('chmod 0750 ' . escapeshellarg($tmpdir));
		}

		return $tmpdir;
	}

	/**
 	 * fastcgi-fakedirectory directory
 	 *
 	 * @param boolean $createifnotexists create the directory if it does not exist
 	 *
 	 * @return string the directory
 	 */
 	public function getAliasConfigDir($createifnotexists = true)
 	{
    // ensure default...
    if (!isset($this->_settings['phpfpm']['aliasconfigdir'])) {
      $this->_settings['phpfpm']['aliasconfigdir'] = '/var/www/php-fpm';
    }

 		$configdir = makeCorrectDir($this->_settings['phpfpm']['aliasconfigdir'] . '/' . $this->_domain['loginname'] . '/' . $this->_domain['domain'] . '/');
 		if(!is_dir($configdir) && $createifnotexists)
 		{
 			safe_exec('mkdir -p ' . escapeshellarg($configdir));
 			safe_exec('chown ' . $this->_domain['guid'] . ':' . $this->_domain['guid'] . ' ' . escapeshellarg($configdir));
 		}

 		return $configdir;
 	}
	
	/**
	 * return path of php.ini file
	 * 
	 * @return string full with path file-name
	 */
	public function getIniFile()
	{
		$php_info = getPhpInfo();
		$php_ini_scanned_dir = $php_info['Scan this dir for additional .ini files'];
		$phpini_filename = makeCorrectFile($php_ini_scanned_dir.'/'.$this->_domain['loginname'].'-'.$this->_domain['domain'].'-froxlor-php.ini');

		return $phpini_filename;
	}
	
	/**
	 * return the admin-data of a specific admin
	 * 
	 * @param int $adminid id of the admin-user
	 * 
	 * @return array
	 */
	private function _getAdminData($adminid)
	{
		$adminid = intval($adminid);

		if(!isset($this->_admin_cache[$adminid]))
		{
			$this->_admin_cache[$adminid] = $this->_db->query_first(
				"SELECT `email`, `loginname` FROM `" . TABLE_PANEL_ADMINS . "` 
				WHERE `adminid` = " . (int)$adminid
			);
		}

		return $this->_admin_cache[$adminid];
	}
}
