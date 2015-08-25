<?
/*
####################################################################################
#	Copyright (c) 2012, 新浪无线研发中心
#	All rights reserved.
#
#	文件名称: bufmonitor.php
#	摘    要: 网关日志入库程序，本程序负责将在INSERT缓冲区中超过5分钟的SLOG入库、删除匹配不到下行
#			  的状态报告--只适合移动
#	作    者:
#	版    本:1.0
#	日    期:2012-03-01
#	备	  注: 
####################################################################################
*/


$procnt = exec ("ps -axww | grep bufmonitor | grep -v grep | grep -v '/bin/sh' | wc -l") + 0;
echo "procnt = $procnt\n";
if ($procnt > 1)
{
	exit;	
}


$SLEEP_TIME = 3;
$BASEDIR =  "/data0/apache/gateway/print/PLOGBUF";

$SDIR =  "/data0/apache/gateway/print/INSERT";


$Hcity = array();
$Hregion = array();
$fdreg = fopen("/data0/apache/gateway/etc/PHONE_COUNTY.txt",'r');
if(!$fdreg)
{
	echo "/data0/apache/gateway/etc/PHONE_COUNTY.txt not exist!\n";
	exit;
}
while( $data = fgets($fdreg, 1024) )
{	
	$keys = preg_split("/\s+/", $data); 
	//echo $keys[0]." ".$keys[1]." ".$keys[2]." ".$keys[3]." ".$keys[4]." ";
	
	$Hregion[$keys[0]] = $keys[2];
	$Hcity[$keys[0]] = $keys[4];
	

}
fclose($fdreg);


//1. 清理Slog buf
echo "清理SLOG BUF开始\n";
if ($handle = opendir($SDIR)) 
{	
	//加入报警机制
	//$conn = mysql_connect("mxd1.db.intra.mobile.sina.cn:3311", "syslog_w", "AJfZd9Pn4h3Q710eDu") or die ("111\n");;
	$conn = mysql_connect("insertdb.gateway.sina.com.cn:3306", "gateway", "4d5SqYXTx1BQYLKN") or die("Could not connect");
    mysql_select_db("qxt_smslog");
    
	while (false !== ($file = readdir($handle))) 
	{
		if ($file != "." && $file != ".." )
		{
			$tmppath=$SDIR ."/" .$file;
			echo $tmppath."\n";
			if (is_dir($tmppath))
			{
				if ($handle1 = opendir($tmppath)) 
				{	
					while (false !== ($file1 = readdir($handle1))) 
					{	
						if ($file1 != "." && $file1 != ".." )
						{
							$Filename=$tmppath ."/" .$file1;
							echo $Filename."\n";
							$fd = fopen("$Filename",'r');
							if(!$fd)
							{
								trans_w_log('/data0/apache/gateway/withdb/logdb/insertdb.log',"$Filename open errpr!\n");
								continue;
							}
							if (flock($fd, LOCK_EX|LOCK_NB))
							{
								$data = fgets($fd, 1024) ;
								$data = chop($data);
								if (strlen($data) == 0 )
								{
									trans_w_log('/data0/apache/gateway/withdb/logdb/insertdb.log',"$Filename data is 0!\n");
				        			fclose($fd);
									continue;
								}
								$statflag = stat($Filename);
							
								$nowsec = time() ;
								
								
								//处理5分钟前的日志，入库之
								if ( $nowsec - $statflag['mtime'] > 300)
								{
									echo "$i $Filename $nowsec". $statflag['mtime']."\n";
									$data = addslashes($data);
									$slogarr = explode('|||',$data);
									$tablename = "S".date("Y").substr($slogarr[3],0,4);	
									
									$premobile = substr($slogarr[5], 0, 7);
									$mobile_reg = $Hregion[$premobile];
									$mobile_city = $Hcity[$premobile];
									//入库
									//$slogarr[15] = str_replace("'", "\"", $slogarr[15]);
									//$slogarr[15] = str_replace("\\", "fanxiegang", $slogarr[15]);
									$sql = "insert into $tablename (msgtime,gateway,region,city,rpcid,msgid,status,dest,src,charge,msgfrom,service,msgfeetype,msgfeecode,linkid,msg,sendflag) values ('$slogarr[0]','$slogarr[1]','$mobile_reg','$mobile_city','$slogarr[2]','$slogarr[3]','$slogarr[4]','$slogarr[5]','$slogarr[6]','$slogarr[7]','$slogarr[8]','$slogarr[10]','$slogarr[12]','$slogarr[13]','$slogarr[16]','$slogarr[15]','0')";
				//echo $sql."\n";
									$result=mysql_query($sql);
				            		if(0!=mysql_errno())
				            		{
				            			//mysql 1146错误，表示没这个表，建立之
				            			if (mysql_errno() == 1146)
				            			{
				            				trans_w_log('/data0/apache/gateway/withdb/logdb/insertdb.log',"create table $tablename\n" );
				        					$sql="CREATE TABLE `$tablename` (
													`id` int(9) unsigned NOT NULL auto_increment,
													`msgtime` time NOT NULL default '00:00:00',
													`gateway` char(6) NOT NULL default '',
													`region` char(6) NOT NULL default '',
													`city` char(6) NOT NULL default '',
													`rpcid` char(32) NOT NULL default '',
													`msgid` varchar(24) NOT NULL default '',
													`status` char(6) NOT NULL default '',
													`stat_rpt` char(12) default NULL,
													`dest` varchar(32) default NULL,
													`src` varchar(21) NOT NULL default '',
													`charge` varchar(32) default NULL,
													`msgfrom` char(12) NOT NULL default '',
													`service` char(12) NOT NULL default '',
													`msgfeetype` char(6) default NULL,
													`msgfeecode` char(6) default NULL,
													`linkid` varchar(20) default NULL,
													`msg` varchar(160) default NULL,
													`sendflag` char(6) default NULL,
													PRIMARY KEY  (`id`),
													KEY `msgid` (`msgid`),
													KEY `region` (`region`),
													KEY `city` (`city`),
													KEY `dest` (`dest`),
													KEY `charge` (`charge`),
													KEY `linkid` (`linkid`),
													KEY `service` (`service`),
													KEY `msgfrom` (`msgfrom`),
													KEY `sendflag` (`sendflag`),
													KEY `gateway` (`gateway`)
													) ";
				        					mysql_query($sql);
				        					fclose($fd);
				        					continue;
				            			}
				            			else
				            			{
				            				//数据库访问错误，直接退出
				            				trans_w_log('/data0/apache/gateway/withdb/logdb/insertdb.log',"insert error ". mysql_errno() . ": " . mysql_error(). "\n" );
				        					fclose($fd);
				            				continue;
				            		
				            			}
				            		}
				            		else
				            		{
				            			fclose($fd);
				            			unlink($Filename);
				            		}
								}
							}
						}
					}
				}
				closedir($handle1);
			}	
		}
	}
	closedir($handle);
}

//2.PLOGBUF 清理 超过2个小时没有处理的
echo "清理PLOG BUF开始\n";
if ($handle = opendir($BASEDIR)) 
{	

	while (false !== ($file = readdir($handle))) 
	{
		if ($file != "." && $file != ".." )
		{
			$Filename=$BASEDIR ."/" .$file;
			

			$fd = fopen("$Filename",'r');
			if(!$fd)
			{
				trans_w_log('/data0/apache/gateway/withdb/logdb/insertdb.log',"$Filename open errpr!\n");
				continue;
			}
			if (flock($fd, LOCK_EX|LOCK_NB))
			{
				$data = fgets($fd, 1024) ;
				$data = chop($data);
				if (strlen($data) == 0 )
				{
					trans_w_log('/data0/apache/gateway/withdb/logdb/insertdb.log',"$Filename data is 0!\n");
        			fclose($fd);
					continue;
				}
				$statflag = stat($Filename);
			
				$nowsec = time() ;
				
				
				//处理5分钟前的日志，入库之
				if ( $nowsec - $statflag['mtime'] > 7200)
				{
					
					trans_w_log('/data0/apache/gateway/withdb/logdb/insertdb.log',"error rpt:$data\n");
					
					echo "$i $Filename $nowsec". $statflag['mtime']."\n";
					fclose($fd);
            		unlink($Filename);
				}
				else
					fclose($fd);
			}	
		}
	}
	closedir($handle);
}
exit(1);



function trans_w_log($file,$log)
{
	//$file   =       $file.date("Ymd").".log";
	$fd     =       fopen($file,"a+");
	fwrite($fd,"[".date("Y-m-d H:i:s")."] ".$log);
	fclose($fd);
}


function genSMSbuf($bufpath,$gateway,$msgid,$buf)
{
	if(!is_dir("$bufpath"))
	{	
  		mkdirs("$bufpath");
	}

	$buffile = $bufpath."/".$gateway."_".$msgid ;
	
	$fd = fopen($buffile,"w");
	if (flock($fd, LOCK_EX))
	{
		fwrite($fd,$buf);
		flock($fd, LOCK_UN); // 释放锁定
	}
	else 
	{
		//锁失败
		trans_w_log('/data0/apache/gateway/withdb/logdb/lockerr.log',"<pho>".$mobile."</pho><message>".$a."</message>\n");
	}
	fclose($fd);
	
}

function mkdirs($dir, $mode = 0755)
{
	if (is_dir($dir) || @mkdir($dir, $mode)) 
		return TRUE;
	if (!mkdirs(dirname($dir), $mode)) 
		return FALSE;
	return @mkdir($dir, $mode);
}



?>
