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
 * @package    Functions
 * @version    $Id$
 */

/**
 * Create or modify the AWStats configuration file for the given domain.
 * Modified by Berend Dekens to allow custom configurations.
 *
 * @param logFile
 * @param siteDomain
 * @param hostAliases
 * @return null
 */

function createAWStatsConf($logFile, $siteDomain, $hostAliases, $customerDocroot)
{
	global $pathtophpfiles, $settings;

	// Generation header

	$header = "## GENERATED BY FROXLOR\n";
	$header2 = "## Do not remove the line above! This tells Froxlor to update this configuration\n## If you wish to manually change this configuration file, remove the first line to make sure Froxlor won't rebuild this file\n## Generated for domain {SITE_DOMAIN} on " . date('l dS \of F Y h:i:s A') . "\n";

	$awstats_dir = makeCorrectDir($customerDocroot.'/awstats/'.$siteDomain.'/');
	if(!is_dir($awstats_dir))
	{
		safe_exec('mkdir -p '.escapeshellarg($awstats_dir));
	}

	// These are the variables we will replace

	$regex = array(
		'/\{LOG_FILE\}/',
		'/\{SITE_DOMAIN\}/',
		'/\{HOST_ALIASES\}/',
		'/\{CUSTOMER_DOCROOT\}/'
	);
	$replace = array(
		makeCorrectFile($logFile),
		$siteDomain,
		$hostAliases,
		$awstats_dir
	);

	// File names

	$domain_file = '/etc/awstats/awstats.' . $siteDomain . '.conf';
	$model_file = dirname(dirname(dirname(dirname(__FILE__))));
	$model_file.= '/templates/misc/awstatsmodel/';
	
	if($settings['system']['mod_log_sql'] == '1')
	{
		$model_file.= 'awstats.froxlor.model_log_sql.conf';
	} else {
		$model_file.= 'awstats.froxlor.model.conf';
	}

	$model_file = makeCorrectFile($model_file);
	
	// Test if the file exists

	if(file_exists($domain_file))
	{
		// Check for the generated header - if this is a manual modification we won't update

		$awstats_domain_conf = fopen($domain_file, 'r');

		if(fgets($awstats_domain_conf, strlen($header)) != $header)
		{
			fclose($awstats_domain_conf);
			return;
		}

		// Close the file

		fclose($awstats_domain_conf);
	}

	$awstats_domain_conf = fopen($domain_file, 'w');
	$awstats_model_conf = fopen($model_file, 'r');

	// Write the header

	fwrite($awstats_domain_conf, $header);
	fwrite($awstats_domain_conf, preg_replace($regex, $replace, $header2));

	// Write the configuration file

	while(($line = fgets($awstats_model_conf, 4096)) !== false)
	{
		if(!preg_match('/^#/', $line)
		   && trim($line) != '')
		{
			fwrite($awstats_domain_conf, preg_replace($regex, $replace, $line));
		}
	}

	fclose($awstats_domain_conf);
	fclose($awstats_model_conf);
}
