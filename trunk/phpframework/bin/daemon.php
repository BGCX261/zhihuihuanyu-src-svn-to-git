<?
/*
 * Copyright (c) 2013, shinetek.
 * All rights reserved.
 * 
 * file	name		daemon.php
 * 
 * description		守护进程
 * 流程如下：
		monitor the all day run progarms by ps, pid.
		if no program pid found by ps, rm pid.file, and run it.
		if ps.pid is different with pid.file, kill pid, and re-run it.
		check date time in alive.file.
		kill program every day for recource re-allocation.
 * 					
 * [注意]本程序可以手动执行:
 * /usr/bin/php -q /home1/cosrun/gumm/RCMResourcecollector/bin/daemon.php hp-mgmt-1 get_local_infos 2>&1 
 *  					
 * date			author		changes
 * 2013-05-11	gumeng		create.
 */
	
require_once '/home1/cosrun/gumm/RCMResourcecollector/etc/RCMResourcecollector.conf.php';
global $cluster_list;
global $etc_path;
global $log_path;
global $pid_path;
global $lib_path;
global $bin_path;
global $ps;
global $awk;
global $xargs;
global $kill;
global $touch;
global $server;
global $default_sleep_time;

require_once $lib_path.'/common.php';

if( $argc != 3 && $argc != 4) {
	echo "cmd err: $argv[0] target_machine_name target_php_program [kill]\n";
	echo "eg: $argv[0] hp-compute-10 get_local_infos\n";
	echo "where target_machine_name is defined in $etc_path/RCMResourcecollector.conf.php\n";
	echo "and, target_php_program is get_local_infos, info_to_db or get_job_stat\n";
	echo "and, kill means kill the target_php_program.\n";
	exit();
}

// make sure we are the right local ip.
$target_name = $argv[1];
$target_program = $argv[2];
$kill_flag = '';
if(isset($argv[3])) {
	$kill_flag = strtoupper($argv[3]);
}
$target_info = $cluster_list[$target_name];
$target_ip = $target_info['ip'];
$local_ip_list = get_local_ip();
if( !in_array($target_ip, $local_ip_list) ) {
	echo "your input host name is: $target_name\n";
	echo "$target_name's pre-defined ip in $etc_path/RCMResourcecollector.conf.php is ".$target_ip. "\n";
	echo "which is NOT find locally by ifconfig:\n";
	print_r($local_ip_list);
	exit();
}

$target_tag = $target_name.'.'.$target_program;
$target_pidfile = $pid_path.'/'.$target_tag.'.pid';
$target_alivefile = $pid_path.'/'.$target_tag.'.alive';

$my_log = $log_path.'/'.$target_name.'.'.date("Ymd").'.log';
$log_tag = 'daemon.'.$target_program;

$cmd = $cat." $target_pidfile 2>/dev/null";
$target_pid = exec($cmd) + 0;

if( $kill_flag == 'KILL') {
	phplog($my_log, $log_tag, 'cronly, i will kill '.$target_name.': '.$target_program."[$target_pid]");
	restart();
	exit;
}

$pid_list = array();
$cmd = $ps.' -elf | '.$grep.' '.$target_program.' | '.$grep. ' -v grep | '.$grep. ' -v daemon.php | '.$awk." '{print $4}' ";
exec($cmd, $pid_list);

// Make sure ONLY one target_php_program alive.
if(count($pid_list) == 1 && in_array($target_pid, $pid_list) ) {
	// check alive.time
	$cmd = $cat." $target_alivefile 2>/dev/null";
	$last_alive_time = exec($cmd); // 2013-05-14 22:53:04
	$cur_time = time();
	if( abs($cur_time - strtotime($last_alive_time) ) < 2 * $default_sleep_time ) {
		$msg = $target_name.': '.$target_program."[$target_pid] is alive at $last_alive_time";
		phplog($my_log, $log_tag, $msg);
		
		// send health ok info to server.
		
		exit;
	}else {
		$msg = $target_name.': '.$target_program."[$target_pid] will be killed. cuz exceed 2*defalut_sleep_time. cur_time=".date("Y-m-d H:i:s", $cur_time).", last_alive_time=$last_alive_time";
		phplog($my_log, $log_tag, $msg);	
	}
}else {
	$msg = $target_name.': '.$target_program."[$target_pid] will be killed. cuz ps.ret.count=".count($pid_list).", ps.ret=". implode(',', $pid_list);
	phplog($my_log, $log_tag, $msg);	
}

// if we come here, we need restart the target_php_program
restart();

// send health is NOT ok info to server.

exit;

function restart()
{
	global $ps;
	global $awk;
	global $xargs;
	global $kill;
	global $php;
	global $rm;
	global $grep;
	
	global $bin_path;
	global $log_path;
	
	global $target_name;
	global $target_program;
	global $target_pidfile;
	global $target_alivefile;
	
	global $my_log;
	global $log_tag;

	$exec_ret_arr = array();
	$cmd = $ps.' -elf | '.$grep.' '.$target_program.' | '.$grep. ' -v grep | '.$grep. ' -v daemon.php | '
			.$awk." '{print $4}' | ".$xargs. " -I {} ".$kill. ' -term {}';
	exec($cmd, $exec_ret_arr);
	echo date("Ymd H:i:s").': '.$cmd."\n";
	print_r($exec_ret_arr);
	phplog($my_log, $log_tag, $cmd);
	
	$cmd = $rm. " -rf $target_pidfile 2>/dev/null";
	exec($cmd, $exec_ret_arr);
	echo date("Ymd H:i:s").': '.$cmd."\n";
	print_r($exec_ret_arr);
	phplog($my_log, $log_tag, $cmd);

	$cmd = $rm. " -rf $target_alivefile 2>/dev/null";
	exec($cmd, $exec_ret_arr);
	echo date("Ymd H:i:s").': '.$cmd."\n";
	print_r($exec_ret_arr);
	phplog($my_log, $log_tag, $cmd);

	sleep(1);
	
	// restart.
	$cmd = $php." -q $bin_path/$target_program.php $target_name >> $log_path/$target_name.$target_program.php.log 2>&1 &";
	exec($cmd, $exec_ret_arr);
	echo date("Ymd H:i:s").': '.$cmd."\n";	
	print_r($exec_ret_arr);
	phplog($my_log, $log_tag, $cmd);
}

?>