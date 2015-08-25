<?
/*
 * Copyright (c) 2013, shinetek.
 * All rights reserved.
 * 
 * file	name		cluster_ping.php
 * 
 * description		ping all of cluster ip, 并汇报给主节点。
 * 流程如下：ping cluster list one-by-one, then log infos.
 * 	cmd = ping -c 5 -i 0.2 -q -w 2 127.0.0.1 // to verify that the local network interface  is  up
 * then, cmd = ping -c 5 -i 0.2 -q -w 2 192.168.8.10				
 * 	If  ping  does  not  receive any reply packets at all it will exit with code 1. If a packet count and deadline are both specified, and
    fewer than count packets are received by the time the deadline has arrived, it will also exit with code 1.  On other  error  it  exits
    with code 2. Otherwise it exits with code 0. This makes it possible to use the exit code to see if a host is alive or not.
    
 * [注意] 本程序可以手动执行:
 * /usr/bin/php -q /home1/cosrun/gumm/RCMResourcecollector/bin/cluster_ping.php 2>&1 
 * 
 * date			author		changes
 * 2013-05-16	gumeng		create.
 */

set_time_limit(50);  
	
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

require_once $lib_path.'/common.php';

if( $argc != 2) {
	echo "cmd err: $argv[0] the_local_machine_name\n";
	echo "eg: $argv[0] hp-compute-10\n";
	echo "where the_local_machine_name is defined in $etc_path/RCMResourcecollector.conf.php\n";
	exit();
}

// make sure we are the right local ip.
$my_name = $argv[1];
$my_info = $cluster_list[$my_name];
$my_ip = $my_info['ip'];
$local_ip_list = get_local_ip();
if( !in_array($my_ip, $local_ip_list) ) {
	echo "your input host name is: $my_name\n";
	echo "$my_name's pre-defined ip in $etc_path/RCMResourcecollector.conf.php is ".$my_ip. "\n";
	echo "which is NOT find locally by ifconfig:\n";
	print_r($local_ip_list);
	exit();
}

$log_tag = 'get_local_infos';
$my_tag = $my_name.'.'.$log_tag;
$my_pidfile = $pid_path.'/'.$my_tag.'.pid'; // like: hp-mgmt-1.get_local_infos.pid
$my_alivefile = $pid_path.'/'.$my_tag.'.alive';

$pid = getmypid();
$cmd = "$echo '$pid' > $my_pidfile 2>/dev/null";
exec($cmd);
$my_log = $log_path.'/'.$my_name.'.'.date("Ymd").'.log'; // like: hp-mgmt-1.20130511.log
phplog($my_log, $log_tag, 'program start. pid='.$pid);

declare(ticks = 1);
pcntl_signal(SIGTERM, "sig_handler");
pcntl_signal(SIGINT, "sig_handler");

while (1) {
	$my_sleep_time = get_sleep_time();
	
	// tell daemon that i am alive at current time.
	$cur_time = date("Y-m-d H:i:s");
	$cmd = $echo. " '$cur_time' > $my_alivefile 2>/dev/null";
	exec($cmd);

	// get all of infos by cmd-line
	$cmd = $df . ' -h 2>/dev/null | head -n 1';
	exec($cmd, $exec_ret_arr);

	$data = array();
	$data['type'] = $msg_type_info;
	$data['time'] = time(); // unix time stemp.
	$data['df'] = $exec_ret_arr[0];


	$url = $server['url_recv_info'].'?'.http_build_query($data);
	$ret = send_data_by_get($url);
	$my_log = $log_path.'/'.$my_name.'.'.date("Ymd").'.log'; // like: hp-mgmt-1.20130511.log
	phplog($my_log, $log_tag, $url.'. ret='.$ret);	
	
	sleep($my_sleep_time);
}

function sig_handler($signo) 
{
	global $log_path;
	global $my_log;
	global $my_name;
	global $log_tag;
	global $my_tag;
	global $my_pidfile;
	global $pid;
	global $rm;
	
	// release resource such as db connection
	$my_log = $log_path.'/'.$my_name.'.'.date("Ymd").'.log';
	$msg = 'recv signal '.$signo.'. exit now. pid='.$pid;
	echo date("Ymd H:i:s").': '.$my_tag.': '.$msg."\n";
	phplog($my_log, $log_tag, $msg);
	exec("$rm -rf $my_pidfile 2>/dev/null");
	
	exit;
}
?>