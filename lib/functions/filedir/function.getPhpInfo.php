<?php

/**
 * convert the output of phpinfo into an array
 *
 * @return array the phpinfo values
 */

function getPhpInfo()
{
	ob_start();
	phpinfo();
	$info_arr = array();
	$info_lines = explode("\n", ob_get_clean());
	
	foreach($info_lines as $line)
	{
		if(preg_match('/(.+)=>(.+)/', $line, $val))
		{
			$info_arr[trim($val[1])] = trim($val[2]);
		}
	}
	
	return $info_arr;
}
