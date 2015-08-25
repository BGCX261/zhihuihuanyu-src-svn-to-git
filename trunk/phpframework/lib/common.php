<?php
/*
 * Copyright (c) 2013, shinetek.
 * All rights reserved.
 * 
 * file	name		common.php
 * 
 * description		通用函数
 * 
 * date			author		changes
 * 2013-05-11	gumeng		create.
 */

require_once '/data0/apache/gateway/etc/conf.php';


/**
 * @desc	get_client_ip
 * @param	_SERVER
 * @return	string. one ip.
 */
function get_client_ip($server_info)
{
	if ($server_info['HTTP_CLIENT_IP'] ) {
		$ip = $server_info['HTTP_CLIENT_IP'];
	}elseif ($server_info['HTTP_X_FORWARDED_FOR'] ) {
		$ip = $server_info['HTTP_X_FORWARDED_FOR'];
	}elseif ($server_info['REMOTE_ADDR'] ) {
		$ip = $server_info['REMOTE_ADDR'];
	}else {
		$ip = 'Unknown';
	}
	return $ip;
}

/**
 * @desc	get_local_ip
 * @param	null
 * @return	array of ip list in localhost.
 */
function get_local_ip()
{
	global $ifconfig;
	global $grep;
	global $awk;
	
	// ifconfig -a 2>/dev/null | grep "inet addr:" | awk '{print $2}' | awk -F':' '{print $2}'
	$cmd = $ifconfig. ' -a 2>/dev/null | '. $grep ." \"inet addr:\" | ". 
		$awk ." '{print $2}' | ". $awk . " -F':' '{print $2}' ";
	$ret = `$cmd`;
	
	$arr = explode("\n", $ret);
	return $arr;
}

/**
 * @desc	get_sleep_time
 * @param	null
 * @return	int, sleep time.
 */
function get_sleep_time()
{
	global $sleep_time_conf;
	global $cat;
	global $default_sleep_time;
	
	$cmd = $cat. " $sleep_time_conf 2>/dev/null";
	$sleep_time = exec($cmd) + 0;
	
	if($sleep_time < 1 ) {
		$sleep_time = $default_sleep_time;
	}
	
	return $sleep_time;
}

/**
 * @desc	set_sleep_time
 * @param	$sleep_time
 * @return	null
 */
function set_sleep_time($sleep_time)
{
	global $sleep_time_conf;
	global $echo;
	global $default_sleep_time;

	if($sleep_time < 1) {
		$sleep_time = $default_sleep_time;
	}
	$cmd = $echo. " '$sleep_time' > $sleep_time_conf 2>/dev/null";
	exec($cmd);
}

/**
 * @desc	log
 * @param	$data: infos without \n
 * @return	null
 */
function phplog($logfile, $tag, $data)
{
	$fp = fopen($logfile,'a');
	if($fp===false) {
		return;
	}
	if (!flock($fp, LOCK_EX)) { // do an exclusive lock
		fclose($fp);		
		return;
	}else {
		$data = '['.date("Y-m-d H:i:s").']`'.$tag.'`'.$data."\n";
		fwrite($fp, $data);
	    flock($fp, LOCK_UN); // release the lock
		fclose($fp);
	}
}

/**
 * @desc	send_sms
 * @param	$data: infos without \n
 * @return	null
 */
function send_sms($msg, $mobile)
{
	global $alarm_url;
	
	$msg = rawurlencode($msg);
	$url = $alarm_url."?msg=$msg&count=1&from=98399&longnum=10690090&usernumber=$mobile";
	$ret = send_data_by_get($url);
	return $ret;
}

/**
 * @desc	send_data_by_get. send data to $url by GET method.
 * @param	$url.
 * @return	the $url return.
 */
function send_data_by_get($url)
{
	$ret = trim(file_get_contents($url));
	return $ret;
}

/**
 * @desc	send_data_by_post. send data to $url by POST method.
 * @param	$url.
 * @return	the $url return.
 */
function send_data_by_post($url)
{
	$ret = trim(file_get_contents($url));
	return $ret;
}


function get_microtime()
{
	list($usec, $sec) = explode(" ",microtime());
	return ((float)$usec + (float)$sec);
}

/**
 * @desc	列出path路径下的文件。不含. &　..
 * @param	$path: 路径。$time_order: 排序规则，默认是以文件修改时间升序[由早到晚]
 * @return	array of file list in $path.
 */
function get_files($path, $time_order='asc')
{
	$files = array();
	
	//path末尾若无/，则添加一个
	if(substr($path, strlen($path)-1)!='/') {
		$path .= '/';
	}
	
	if(!is_dir($path)) {
		return $files;
	}else {
		if (($dh = @opendir($path)) === false) {
			return $files;
		}else {
			while (($file = readdir($dh)) !== false) {								
				if($file=="." || $file==".." || is_dir($path.$file))continue;
				array_push($files, $path.$file); // 添加元素于数组的末尾
			}
			usort($files, "file_sort_query"); 
			if($time_order=='desc') {
				$files = array_reverse($files, false);
			}
			return $files;
		}
	}
}

// 默认以修改时间递增排序
function file_sort_query($file1,$file2)
{
	$time1 = filemtime($file1);
	$time2 = filemtime($file2);      
    if($time1==$time2) return 0;
    return ($time1<$time2) ? -1 : 1;
}


?>