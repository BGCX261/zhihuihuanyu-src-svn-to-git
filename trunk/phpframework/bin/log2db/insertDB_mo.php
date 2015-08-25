<?
/*
####################################################################################
#	Copyright (c) 2012, 新浪无线研发中心
#	All rights reserved.
#
#	文件名称: insertDB_mo.php
#	摘    要: 网关日志入库程序，本程序负责将RLOG写入数据库
#	作    者:
#	版    本:1.0
#	日    期:2012-04-09
#	程序地址:
####################################################################################
*/

	global $Hcity;
	global $Hregion;
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
	
	
$SLEEP_TIME = 3;

while (1)
{
	//trans_w_log('/data0/apache/gateway/withdb/logdb3/insertDB_mo.log',"insertDB_mo begin!!!!".date("Y-m-d H:i:s")."\n");
	
	/*查看配置文件是否需要停止程序*/
	$stopflag =  exec("cat /data0/apache/gateway/etc/insertDB_mo.conf") + 0;
	if ($stopflag == 1)
	{
		//恢复标志位
		exec("echo '0' > /data0/apache/gateway/etc/insertDB_mo.conf") ;
		
		trans_w_log('/data0/apache/gateway/withdb/logdb3/insertDB_mo.log',"kill myself!\n");
		exit;
	}
	
	
	$basepath = "/data0/apache/gateway/log";
	$rcpath = "/data0/apache/gateway/withdb/logdb3/";
	
	
	$file = "R".date("Ymd").".log";
	$seekfile = $rcpath."/var/".$file.".seek";
	$slogfile = $basepath."/R".date("Ymd").".log";
	$table = "R".date(Ymd);
	
	$seeknum=0;
	if (file_exists($seekfile)) 
	{
		$seeknum = exec("cat $seekfile") +0;
		
		echo "line num: $seeknum\n";
	
		parse_log($seekfile,$slogfile,$seeknum,$table);
	
	}
	else
	{
		//建立当前日志seek文件，行数初始为0
		$fd = fopen($seekfile,'w');
		fwrite($fd,"0");
		fclose($fd);
		
		//先跑晚昨天的日志
		$file = "R".date("Ymd",strtotime("-1 day")).".log";
		$seekfile = $rcpath."/var/".$file.".seek";
		$slogfile = $basepath."/R".date("Ymd",strtotime("-1 day")).".log";
		$seeknum = exec("cat $seekfile") +0;
		$table = "R".date("Ymd",strtotime("-1 day"));
	
		parse_log($seekfile,$slogfile,$seeknum,$table);
	}
	echo "sleep 5 sec.\n";
	sleep(2);
}



function parse_log($seekfile,$slogfile,$seeknum,$tablename)
{


	global $Hcity;
	global $Hregion;
	$fd = fopen($slogfile,'r');
	if(!$fd)
	{
		trans_w_log('/data0/apache/gateway/withdb/logdb3/insertDB_mo.log',"Content file open error!!!!\n");
		return;
	}
	$i=0;
	$nowsec = time();
	
	$conn = mysql_connect("insertdb.gateway.sina.com.cn:3306", "gateway", "4d5SqYXTx1BQYLKN") or die("Could not connect");  
    mysql_select_db("qxt_smslog");

	/*-----fseek到文件传输开始位置-----*/
    fseek($fd,$seeknum);
        
	while( $data = fgets($fd, 1024) )
	{
		
		$data = chop($data);

	
		if ( (substr($data,0,1) !== "[") or (substr($data,9,1) !== "]" ))
		{
			echo "日志格式错误：$i行 $data\n";
		    $i++;
		    continue;
		}
	
		//循环控制
		//1.获取单条日志时间;
		//2.当日志时间($logsec)大于程序执行时间($nowsec) 循环结束
		$data = addslashes($data);
		$logarr = explode(',',$data);
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
          	//数据库访问错误，直接退出
          	trans_w_log('/data0/apache/gateway/withdb/logdb3/insertDB_mo.log',"insert error ". mysql_errno() . ": " . mysql_error(). "\n" );          	
          	continue;
          
          }
        }		
                		
		$i++;
		
		if ( $i > 10000)
		{

			break;
		}
	
	}
	//trans_w_log('/data0/apache/gateway/withdb/logdb3/mtlog2buf.log',"seek is  ". ftell($fd) .  "\n" );
	
	mysql_close($conn);
	/*-----获取seek-----*/
	$seeklast = ftell($fd);
	fclose($fd);
	
	$fdseek = fopen($seekfile,'w');
	fwrite($fdseek,"$seeklast");
	fclose($fdseek);
}



function trans_w_log($file,$log)
{
	//$file   =       $file.date("Ymd").".log";
	$fd     =       fopen($file,"a+");
	fwrite($fd,"[".date("Y-m-d H:i:s")."] ".$log);
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