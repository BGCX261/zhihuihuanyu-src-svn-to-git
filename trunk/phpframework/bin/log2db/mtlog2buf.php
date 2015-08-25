<?
/*
####################################################################################
#	Copyright (c) 2012, 新浪无线研发中心
#	All rights reserved.
#
#	文件名称: mtlog2buf.php
#	摘    要: 网关日志入库程序，本程序负责将SLOG写入INSERT缓冲区----只适合移动
#	作    者:
#	版    本:1.0
#	日    期:2012-03-01
#	程序地址:
####################################################################################
*/

while (1)
{
	//trans_w_log('/data0/apache/gateway/withdb/logdb/mtlog2buf.log',"mtlog2buf begion!!!!".date("Y-m-d H:i:s")."\n");
	
	/*查看配置文件是否需要停止程序*/
	$stopflag =  exec("cat /data0/apache/gateway/etc/mtlog2buf.conf") + 0;
	if ($stopflag == 1)
	{
		//恢复标志位
		exec("echo '0' > /data0/apache/gateway/etc/mtlog2buf.conf") ;
		
		trans_w_log('/data0/apache/gateway/withdb/logdb/mtlog2buf.log',"kill myself!\n");
		exit;
	}
	
	/*监视INSERT下 文件数是否大于5万。大于5万停止下发，sleep（180） 发出报警短信*/
	$filenum = exec("find /data0/apache/gateway/print/INSERT -type f | wc -l") + 0;
	if($filenum > 50000)
	{
		echo "INSERT下文件数量过大 $filenum\n";
		$sendsms = "86.69的INSERT下文件数量过大：${filenum}\n";
		POSTDATA(13488694500,$sendsms);
		POSTDATA(18601357810,$sendsms);
		trans_w_log('/data0/apache/gateway/withdb/logdb/mtlog2buf.log',"86.69的INSERT下文件数量过大!\n");
		exec("/data0/php/bin/php /data0/apache/gateway/withdb/logdb/bufmonitor_insert.php > /dev/null &");
		sleep(60);
		continue;
	}
	
	$basepath = "/data0/apache/gateway/log";
	$rcpath = "/data0/apache/gateway/withdb/logdb/";
	
	
	$file = "S".date("Ymd").".log";
	$seekfile = $rcpath."/var/".$file.".seek";
	$slogfile = $basepath."/S".date("Ymd").".log";
	
	$seeknum=0;
	if (file_exists($seekfile)) 
	{
		$seeknum = exec("cat $seekfile") +0;
		
		echo "line num: $seeknum\n";
	
		parse_log($seekfile,$slogfile,$seeknum);
	
	}else
	{
		//建立当前日志seek文件，行数初始为0
		$fd = fopen($seekfile,'w');
		fwrite($fd,"0");
		fclose($fd);
		
		//先跑晚昨天的日志
		$file = "S".date("Ymd",strtotime("-1 day")).".log";
		$seekfile = $rcpath."/var/".$file.".seek";
		$slogfile = $basepath."/S".date("Ymd",strtotime("-1 day")).".log";
		$seeknum = exec("cat $seekfile") +0;
		
	
	
	
		parse_log($seekfile,$slogfile,$seeknum);
	}
	echo "sleep 5 sec.\n";
	sleep(2);
}



function parse_log($seekfile,$slogfile,$seeknum)
{
	$bufpath = "/data0/apache/gateway/print/INSERT/";

	$fd = fopen($slogfile,'r');
	echo $file."\n";
	if(!$fd)
	{
		trans_w_log('/data0/apache/gateway/withdb/logdb/mtlog2buf.log',"Content file open error!!!!\n");
		return;
	}
	$i=0;
	$nowsec = time();
	
	/*-----fseek到文件传输开始位置-----*/
    fseek($fd,$seeknum);
	
	while( $data = fgets($fd, 1024) )
	{

		$data = chop($data);
		$data = addslashes($data);
		
		
		
		if ( (substr($data,0,1) !== "[") or (substr($data,9,1) !== "]" ))
		{
			echo "日志格式错误：$i行 $data\n";
		    $i++;
		    continue;
		}
	
		//循环控制
		//1.获取单条日志时间;
		//2.当日志时间($logsec)大于程序执行时间($nowsec) 循环结束
		$logarr = explode(',',$data);
		$timetmp1 = explode('[',$logarr[0]);
		$timetmp2 = explode(']',$timetmp1[1]);
		$logtimestr = date("Y-m-d"). " $timetmp2[0]";
		$logsec = strtotime($logtimestr);

		$gatewaytmp = explode(' ',$logarr[0]);
		$gateway = $gatewaytmp[1];
		
		//分析日志，日志各个字段分隔符为","  短信内容中可能会有逗号
		$count =  count($logarr)."\n";
		$content = "";
		if ($count == 16)
		{
			$linkid = $logarr[15];
			$content = $logarr[14];
	
		}
		else
		{
			$linkid = $logarr[$count-1];
			for ($j = 14 ;$j < $count-1 ;$j++)
			{	
				$content .= $logarr[$j];
			}
			
		}
		
		if (substr($content,0,6) == "050003" )
		{

			$len = strlen($content) - 12;
			$tmpcontent = pack("H$len", substr($content,12));
			$tmpcontent = iconv( "UCS-2", "GBK" , $tmpcontent);
			$content = substr($content,0,12).$tmpcontent ;
			
		}
		
		$buf = "$timetmp2[0]|||$gateway|||$logarr[1]|||$logarr[2]|||$logarr[3]|||$logarr[4]|||$logarr[5]|||$logarr[6]|||$logarr[7]|||$logarr[8]|||$logarr[9]|||$logarr[10]|||$logarr[11]|||$logarr[12]|||$logarr[13]|||$content|||$linkid\n";
		
		genSMSbuf($bufpath,$gateway,$logarr[2],$buf);
		//echo $buf;
		$i++;
		
		if ( $i  > 10000)
		{

			break;
		}
	
	}

	
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


function genSMSbuf($bufpath,$gateway,$msgid,$buf)
{
	if(!is_dir("$bufpath$gateway"))
	{	
  		mkdirs("$bufpath$gateway");
	}

	$buffile = $bufpath.$gateway."/$msgid" ;
	
	$fd = fopen($buffile,"w");
	
	if (flock($fd, LOCK_EX))
	{
		fwrite($fd,$buf);
		flock($fd, LOCK_UN); // 释放锁定
	}
	else 
	{
		//锁失败
		trans_w_log('/data0/apache/gateway/log/lockerr.log',"<pho>".$mobile."</pho><message>".$a."</message>\n");
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

function POSTDATA($mobile,$sendsms)
{
	//sock连接地址写死，在fsockopen函数中，POSTDATA函数的第一个参数无效。
	$rs = "usernumber=$mobile&count=1&from=98399&longnum=10690090&msg=$sendsms";
	$sock = fsockopen("qxt.intra.mobile.sina.cn", 80, $errno, $errstr, 30);

    if (!$sock) 
    	return -1;

	fwrite($sock, "POST /cgi-bin/qxt/sendSMS.cgi HTTP/1.1\r\n");
	fwrite($sock, "Host: localhost\r\n");
    fwrite($sock, "Content-type: application/x-www-form-urlencoded\r\n");
    fwrite($sock, "Content-length: " . strlen($rs) . "\r\n");
    fwrite($sock, "Accept: */*\r\n");
    fwrite($sock, "\r\n");
    fwrite($sock, "$rs\r\n");
    fwrite($sock, "\r\n");
        
	fclose($sock);
    
	return 1;
}

?>