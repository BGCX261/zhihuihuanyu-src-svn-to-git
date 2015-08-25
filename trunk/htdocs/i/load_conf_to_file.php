<?php
/*
 * Copyright (c) 2013, zhihuihuanyu.
 * All rights reserved.
 * 
 * file	name		load_conf_to_file.php?user=&pwd=
 * 		从DB中读取conf信息，写到本地project.conf文件，以供 send_sms.php使用。
 * 		在写入conf之前，先把conf备份一下，以保证万一有错的话，可以有恢复之处。
 * 
 *	return_val:
 * 	成功： 0
 * 	
 *	失败：
 * 		-1: ip非法
<<<<<<< .mine
 * 		-2: 用户名非法
 * 		-3：密码非法
 * 		-4：备份之前的conf文件为conf.bk失败
 * 		-5：读取db失败
 * 		-6: db中的信息转化为json_encode()失败
 * 		-7: json_decode后的信息写入 conf 文件失败。作为补救措施，系统将备份的conf.bk文件重命名为conf文件
=======
 * 		-2: 用户名非法
 * 		-3：密码非法
 * 		-4：备份之前的conf文件为conf.bk失败
 * 		-5：db相关错误。
 * 		-6: db中的信息转化为json_encode()失败
 * 		-7: json_decode后的信息写入 conf 文件失败。作为补救措施，系统将备份的conf.bk文件重命名为conf文件
>>>>>>> .r25
 * 
<<<<<<< .mine
 * description		用户在web页面更改了配置，比如新了通道、项目后，都将调用本接口，以更新conf文件。
 * 		注意：用户新加了群发后，并不更新本文件，因为凡是使用 send_sms.php 的外部客户，都是
 * 		后付费用户，并无gsend-id。因此不需要将群发相关信息更新到conf文件。
 * 
 * $projects = array(
 * 		project_no => array(
 * 			'stauts' => 2, // 0 => '待审核', 1 => '驳回', 2 => '开通', 3 => '暂停', 4=>'删除'
 * 			'gsend_list' => array(), // select gsend_id, gs_status from group_send_list;
 * 			'pj_fee' => 70, // select pj_fee from project_list;
 * 			'isp_type' => 'CMCUCT', // select isp_type from project_list;
 * 			'channel_list' => array(
 * 				'cm' => array('gateway'=>'101001', 'longcode'=>'10690090', 'restrict_city'=>'010,021,025,'),
 * 				'cu' => array('gateway'=>'201001', 'longcode'=>'10690090', 'restrict_city'=>'010,021,025,'),
 * 				'ct' => array('gateway'=>'301001', 'longcode'=>'10690090', 'restrict_city'=>'010,021,025,'),
 * 				), 
 * 		),
 * );
=======
 * description		用户在web页面更改了配置，比如新了通道、项目后，都将调用本接口，以更新conf文件。
>>>>>>> .r25
 * 
 * date			author		changes
 * 2013-05-28	gumeng		create.
 */
/*
require_once '/data0/apache/gateway/lib/common.php';

global $log_path;
$logfile = $log_path.'/load_conf_to_file.'.date('Ymd').'.log';

$user = $_GET['user'];
$pwd = $_GET['pwd'];

if($user != 'zhihuihuanyu') {
	phplog($logfile, 'error', 'user wrong: '.$user);
	echo '-2';
	exit;
}
if($pwd != '6LK3Fh7z') {
	phplog($logfile, 'error', 'pwd wrong: '.$pwd);
	echo '-3';
	exit;
}
$ip = get_client_ip($_SERVER);
if($ip != '127.0.0.1') {
	phplog($logfile, 'error', 'ip wrong: '.$ip);
	echo '-1';
	exit;
}
*/
//$conn=@mysql_connect("127.0.0.1", "gummmysql", "7QZ1n0u9GtWw1MQG3dl3");
$conn=@mysql_connect("127.0.0.1", "root", "123");
if(false===$conn) {
	phplog($logfile, 'error', 'msql_connect failed:'.mysql_error());
	echo '-5';
	exit;
}
$ret = @mysql_select_db("sms_plat", $conn);
if(false === $ret) {
	phplog($logfile, 'error', 'mysql_select_db failed:'.mysql_error());
	echo '-5';
	exit;
}

$sql="select project_no, status, pj_fee, isp_type from project_list";
$result=@mysql_query($sql); 
if(false===$result) {
	mylog($logfile, 'error', 'msql_query failed:'.mysql_error());
	echo '-5';
	exit;
}
/* $projects = array(
 * 		project_no => array(
 * 			'stauts' => 2, // 0 => '待审核', 1 => '驳回', 2 => '开通', 3 => '暂停', 4=>'删除'
 * 			'gsend_list' => array(gsend_id=>sg_status, ...), // select gsend_id, gs_status from group_send_list;
 * 			'pj_fee' => 70, // select pj_fee from project_list;
 * 			'isp_type' => 'CMCUCT', // select isp_type from project_list;
 * 			'channel_list' => array(
 * 				'CM' => array('gateway'=>'101001', 'longcode'=>'10690090', 'restrict_city'=>'010,021,025,'),
 * 				'CU' => array('gateway'=>'201001', 'longcode'=>'10690090', 'restrict_city'=>'010,021,025,'),
 * 				'CT' => array('gateway'=>'301001', 'longcode'=>'10690090', 'restrict_city'=>'010,021,025,'),
 * 				), 
 * 		),
 * );
 */
$projects = array();
while( $row = @mysql_fetch_array($result, MYSQL_ASSOC)) {
	$one_project = array();

	$one_project['status'] = $row['status']; // 0 => '待审核', 1 => '驳回', 2 => '开通', 3 => '暂停', 4=>'删除'
	$one_project['pj_fee'] = $row['pj_fee']; // 厘
	$one_project['isp_type'] = $row['isp_type'];
	$one_project['gsend_list'] = array();
	$one_project['channel_list'] = array();

<<<<<<< .mine
function get_client_ip($server_info)
{
	if (isset($server_info['HTTP_CLIENT_IP']) ) {
		$ip = $server_info['HTTP_CLIENT_IP'];
	}elseif (isset($server_info['HTTP_X_FORWARDED_FOR']) ) {
		$ip = $server_info['HTTP_X_FORWARDED_FOR'];
	}elseif (isset($server_info['REMOTE_ADDR']) ) {
		$ip = $server_info['REMOTE_ADDR'];
	}else {
		$ip = 'Unknown';
	}
	return $ip;
}
=======
	$projects[$row['project_no']] = $one_project;
}
@mysql_free_result($result);

>>>>>>> .r25
$sql="select gsend_id, project_no, gs_status from group_send_list";
$result=@mysql_query($sql); 
if(false===$result) {
	mylog($logfile, 'error', 'msql_query failed:'.mysql_error());
	echo '-5';
	exit;
}
while( $row = @mysql_fetch_array($result, MYSQL_ASSOC)) {
	$projects[$row['project_no']]['gsend_list'][$row['gsend_id']] = $row['gs_status'];  
}
@mysql_free_result($result);

$sql="select project_no, gateway, isp_type, longcode from map_project_channel";
$result=@mysql_query($sql); 
if(false===$result) {
	mylog($logfile, 'error', 'msql_query failed:'.mysql_error());
	echo '-5';
	exit;
}
while( $row = @mysql_fetch_array($result, MYSQL_ASSOC)) {
	$one_channel = array();
	
	$one_channel['gateway'] = $row['gateway'];
	$one_channel['longcode'] = $row['longcode'];
	$one_channel['restrict_city'] = '';

	if( false !== stripos($row['isp_type'], 'CM') ) {
		$projects[$row['project_no']]['channel_list']['CM'] = $one_channel;  
	}
	if( false !== stripos($row['isp_type'], 'CU') ) {
		$projects[$row['project_no']]['channel_list']['CU'] = $one_channel;  
	}
	if( false !== stripos($row['isp_type'], 'CT') ) {
		$projects[$row['project_no']]['channel_list']['CT'] = $one_channel;  
	}
}
@mysql_free_result($result);







print_r($projects);









