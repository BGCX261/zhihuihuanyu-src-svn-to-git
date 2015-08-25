<?
/*
 * Copyright (c) 2013, shinetek.
 * All rights reserved.
 * 
 * file	name		get_sleep_time.php
 * 
 * description		获取DB配置的睡眠时间，写入 etc/sleep_time.conf。
 * 流程如下：get client sleep time for collection loop, then wt to share-disk.
 * 					
 * [注意] 本程序可以手动执行:
 * /usr/bin/php -q /home1/cosrun/gumm/RCMResourcecollector/bin/get_sleep_time.php 2>&1 
 * 
 * date			author		changes
 * 2013-05-16	gumeng		create.
 */

set_time_limit(20);  
	
require_once '/home1/cosrun/gumm/RCMResourcecollector/etc/RCMResourcecollector.conf.php';
global $cluster_list;
global $etc_path;
global $log_path;
global $pid_path;
global $lib_path;
global $echo;
global $df;
global $touch;
global $server;
global $msg_type_info;

global $max_db_retry;
global $default_sleep_time;

require_once $lib_path.'/common.php';

// connect to db.
$retry = 0;
$sleep_time = $default_sleep_time;

set_sleep_time($sleep_time);

?>