<?php
// run all of php scripts, crontabs under user gateway!

date_default_timezone_set( 'UTC' );

/* Path defination. */
$root_path = '/data0/apache/gateway';
$cron_path = $root_path.'/cron';
$bin_path = $root_path.'/bin';
$log_path = $root_path.'/log';
$pid_path = $root_path.'/pid';
$etc_path = $root_path.'/etc';
$lib_path = $root_path.'/lib';

$sleep_time_conf = $etc_path.'/sleep_time.conf';

/* default values. */
$max_db_retry = 3;		// max db retry times.
$default_sleep_time = 30; // seconds.
$rd_lines_each_time = 500; // info_to_db.php need.
$msg_type_info = 'INFO';
$msg_type_err = 'ERROR';

$cm_range = array('134', '135', '136', '137', '138', '139', '147', '150', '151', '152', '157', '158', '159', '182', '187', '188');

$cu_range = array('130', '131', '132', '145', '155', '156', '185', '186');
	
$ct_range = array('133', '153', '180', '189');

/* send alarm sms. */
$alarm_url = 'http://qxt.intra.mobile.sina.cn/cgi-bin/qxt/sendSMS.cgi';

/* sys cmd */
$cat = '/bin/cat';
$awk = '/bin/awk';
$head = '/usr/bin/head';
$grep = '/bin/grep';
$echo = '/bin/echo';
$sort = '/bin/sort';
$uniq = '/usr/bin/uniq';
$wc = '/usr/bin/wc';
$ps = '/bin/ps';
$mv = '/bin/mv';
$rm = '/bin/rm';
$cp = '/bin/cp';
$php = '/usr/bin/php';
$ifconfig = '/sbin/ifconfig';
$df = '/bin/df';
$touch = '/bin/touch';
$kill = '/bin/kill';
$xargs = '/usr/bin/xargs';












?>