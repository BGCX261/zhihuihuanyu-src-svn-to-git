<?
/*
 * Copyright (c) 2013, shinetek.
 * All rights reserved.
 * 
 * file	name		info_to_db.php
 * 
 * description		把log中的info信息，导入sybase. 若发现err信息，则直接sms报警。
 * 					
 * [注意] 本程序可以手动执行:
 * /usr/bin/php -q /home1/cosrun/gumm/RCMResourcecollector/bin/info_to_db.php hp-mgmt-1 2>&1 
 * 
 * date			author		changes
 * 2013-05-13	gumeng		create.
 */

set_time_limit(0);

require_once '/home1/cosrun/gumm/RCMResourcecollector/etc/RCMResourcecollector.conf.php';
global $cluster_list;
global $etc_path;
global $log_path;
global $pid_path;
global $lib_path;
global $echo;

require_once $lib_path.'/common.php';

if( $argc != 2) {
	echo "cmd err: $argv[0] the_local_machine_name\n";
	echo "eg: $argv[0] hp-compute-10\n";
	echo "where the_local_machine_name is defined in $etc_path/RCMResourcecollector.conf.php\n";
	exit();
}

// make sure we are the right local ip.
$my_name = $argv[1]; // local host name.
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

$info_program = 'recv_collection_info';

$log_tag = 'info_to_db';
$my_tag = $my_name.'.'.$log_tag;
$my_pidfile = $pid_path.'/'.$my_tag.'.pid';
$my_alivefile = $pid_path.'/'.$my_tag.'.alive';

$pid = getmypid();
$cmd = "$echo '$pid' > $my_pidfile 2>/dev/null";
exec($cmd);
$my_log = $log_path.'/'.$my_name.'.'.date("Ymd").'.log';
phplog($my_log, $log_tag, 'program start. pid='.$pid);

declare(ticks = 1);
pcntl_signal(SIGTERM, "sig_handler");
pcntl_signal(SIGINT, "sig_handler");

$conn = 0; // handler of db-connection.

while (1) {
	// tell daemon that i am alive at current time.
	$cur_time = date("Y-m-d H:i:s");
	$cmd = $echo. " '$cur_time' > $my_alivefile 2>/dev/null";
	exec($cmd);

	$info_log = $log_path.'/'.$info_program.'.'.date("Ymd").'.log';
	$info_posfile = $log_path.'/'.$info_program.'.'.date("Ymd").'.pos';
	$my_log = $log_path.'/'.$my_name.'.'.date("Ymd").'.log';
	
	// read log, parse and insert to db.
	if (file_exists($info_posfile)) {
		$cmd = $cat.' '.$info_posfile.' 2>/dev/null';
		$pos = exec($cmd) +0;

		parse_log($info_posfile, $pos, $info_log, $my_log);
	}else {
		//建立当前日志pos文件，行数初始为0
		$cmd = $echo." '0' > $info_posfile 2>/dev/null";
		exec($cmd);
		
		//跑昨天的日志, 防止23:59分之后，且本进程被kill后，23:59分之后的log无法入库。
		$yesterday = date("Ymd",strtotime("-1 day") );
		$info_log = $log_path.'/'.$info_program.'.'.$yesterday.'.log';
		$info_posfile = $log_path.'/'.$info_program.'.'.$yesterday.'.pos';
		$cmd = $cat.' '.$info_posfile.' 2>/dev/null';
		$pos = exec($cmd) +0;

		parse_log($info_posfile, $pos, $info_log, $my_log);
	}	

	sleep(2);
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
	
	global $pos;
	global $info_posfile;
	global $conn;
	
	$my_log = $log_path.'/'.$my_name.'.'.date("Ymd").'.log';
	$msg = 'recv signal '.$signo.'. exit now. pid='.$pid;
	echo date("Ymd H:i:s").': '.$my_tag.': '.$msg."\n";
	phplog($my_log, $log_tag, $msg);
	
	// do NOT save $pos. cuz the right $info_posfile is hard to get in signal_hander.
	// if we get the wrong $info_posfile and save $pos to it. all of log data will no order.
	
	// release resource such as db connection
	
	exec("$rm -rf $my_pidfile 2>/dev/null");
	exit;
}

function parse_log($info_posfile, $pos, $info_log, $my_log)
{
	return;
	
	global $log_tag;
	global $my_tag;
	global $rd_lines_each_time;
	global $msg_type_info;
	global $msg_type_err;
	global $conn;
	
	$fd = fopen($info_log,'r');
	if( !$fd) {
		$msg = "$info_log can not read";
		echo date("Ymd H:i:s").': '.$my_tag.': '.$msg."\n";
		phplog($my_log, $log_tag, $msg);
		return;
	}else {
		fseek($fd,$pos);
	}

	// connect to db by ODBC.
	// $conn = db_connect();
	
	$rd_cnt = 0;
	while( $data = fgets($fd, 2048) ) {
		$parse_ok = true;
		
		$data = chop(urldecode($data) );	
		if((substr($data,0,1)!=="[") or (substr($data,9,1)!=="]") ) {
			echo date("Ymd H:i:s").': '.$my_tag.": log fmt error: $data\n";
		    $rd_cnt++;
		    continue;
		}
	
		$data = addslashes($data);
		parse_str($data, $log_arr);
		
		if($log_arr['type'] == $msg_type_err) {
			send_sms('error: ', '15801564398');
		}
		
		
		$timetmp1 = explode('[',$logarr[0]);
		$timetmp2 = explode(']',$timetmp1[1]);
		$logtimestr = date("Y-m-d"). " $timetmp2[0]";
		$logsec = strtotime("2012-02-08 10:00:00");

		$gatewaytmp = explode(' ',$logarr[0]);
		$gateway = $gatewaytmp[1];
		
		//分析日志，日志各个字段分隔符为","  短信内容中可能会有逗号
		$count =  count($logarr)."\n";
		$content = "";
		if ($count == 9)
		{
			$content = $logarr[$count-2];
			$linkid = $logarr[$count-1];
		}
		else
		{
			$linkid = $logarr[$count-1];
			for ($j = 7 ;$j < $count-1 ;$j++)
			{	
				$content .= $logarr[$j];
			}
		}
		if (substr($content,0,4) == "0500" )
		{

			$len = strlen($content) - 12;
			$tmpcontent = pack("H$len", substr($content,12));

			$content = substr($content,0,12).$tmpcontent ;
	
		}		

		//获取tablename
		echo "tablename $tablename\n";
		
		$premobile = substr($logarr[4], 0, 7);
		$mobile_reg = $Hregion[$premobile];
		$mobile_city = $Hcity[$premobile];
		
		echo "$Hregion\n";	

		$sql = "insert into $tablename (msgtime,gateway,mobile,region,city,longnum,msgfeecode,msglength,msg,linkid) values ('$timetmp2[0]','$gateway','$logarr[4]','$mobile_reg','$mobile_city','$logarr[2]','$logarr[3]','$logarr[6]','$content','$linkid')";
		echo $sql."\n";
						
		$result=mysql_query($sql);
		if(0!=mysql_errno())
        {
          //mysql 1146错误，表示没这个表，建立之
          if (mysql_errno() == 1146)
          {
          	trans_w_log('/data0/apache/gateway/withdb/logdb3/insertDB_mo.log',"create table $tablename\n" );
          	$sql="CREATE TABLE `$tablename` (
					`id` int(9) unsigned NOT NULL AUTO_INCREMENT,
					`msgtime` time NOT NULL DEFAULT '00:00:00',
					`gateway` varchar(6) NOT NULL ,
					`mobile` varchar(32) NOT NULL,
					`region` varchar(6) NOT NULL,
					`city` varchar(6) NOT NULL,
					`longnum` varchar(32) NOT NULL,
					`msgfeecode` varchar(21) NOT NULL DEFAULT '',
					`msglength` varchar(4) DEFAULT NULL,
					`msg` text,
					`linkid` varchar(255) DEFAULT NULL,
					PRIMARY KEY (`id`),
					KEY `mobile` (`mobile`),
					KEY `linkid` (`linkid`),
					KEY `region` (`region`),
					KEY `city` (`city`),
					KEY `longnum` (`longnum`),
					KEY `gateway` (`gateway`)
					)";
            mysql_query($sql);            
            continue;
          }
          else
          {
          	//数据库访问错误，直接退出本函数
          	$parse_ok = false;
          	break;
          }
        }		
                		
		$rd_cnt++;
		if( $rd_cnt > $rd_lines_each_time) {
			break;
		}
	}
	
	//mysql_close($conn);

	if($parse_ok) {
		$parse_to_line = ftell($fd);
		fclose($fd);
		
		$cmd = $echo." '$parse_to_line' > $info_posfile 2>/dev/null";
		exec($cmd);
	}else {
		// do nothing. return to main for re-try.
	}
}


?>