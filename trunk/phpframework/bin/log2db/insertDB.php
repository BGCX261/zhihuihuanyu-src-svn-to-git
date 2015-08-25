<?
/*
####################################################################################
#	Copyright (c) 2012, 新浪无线研发中心
#	All rights reserved.
#
#	文件名称: insertDB.php
#	摘    要: 网关日志入库程序，本程序负责将状态报告匹配下行日志入库--只适合移动
#	作    者:
#	版    本:1.0
#	日    期:2012-03-01
#	备	  注:
####################################################################################
*/
$SLEEP_TIME = 1;
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
	

while(1)
{  
	/*查看配置文件是否需要停止程序*/
	$stopflag =  exec("cat /data0/apache/gateway/etc/insertDB.conf") + 0;
	if ($stopflag == 1)
	{
		//恢复标志位
		exec("echo '0' > /data0/apache/gateway/etc/insertDB.conf") ;
		
		trans_w_log('/data0/apache/gateway/withdb/logdb/insertdb.log',"kill myself!\n");
		exit;
	}
	
	
	
	
	if ($handle = opendir($BASEDIR)) 
	{	
		//加入报警机制
		//$conn = mysql_connect("mxd1.db.intra.mobile.sina.cn:3311", "syslog_w", "AJfZd9Pn4h3Q710eDu") or die("Could not connect");
		$conn = mysql_connect("insertdb.gateway.sina.com.cn:3306", "gateway", "4d5SqYXTx1BQYLKN") or die("Could not connect");  
        mysql_select_db("qxt_smslog");
        
		while (false !== ($file = readdir($handle))) 
		{
			if ($file != "." && $file != ".." )
			{
				$Filename=$BASEDIR ."/" .$file;
				echo "$Filename\n";

				$fd = fopen("$Filename",'r');
				if(!$fd)
				{
					trans_w_log('/data0/apache/gateway/withdb/logdb/insertdb.log',"$Filename open errpr!\n");
					continue;
				}
				
				if (flock($fd, LOCK_EX|LOCK_NB))
				{
					$data = fgets($fd, 1024) ;
					if (strlen($data) == 0 )
					{
						trans_w_log('/data0/apache/gateway/withdb/logdb/insertdb.log',"$Filename data is 0!\n");
            			fclose($fd);
						continue;
					}
					
					$plogarr = explode('|||',$data);
					//10:01:08 101010 02101000580100305177 0 0 0 
					echo "$plogarr[0] $plogarr[1] $plogarr[2] $plogarr[3] $plogarr[4] $plogarr[5] \n";
					
					//获取tablename
					$tablename = "S".date("Y").substr($plogarr[2],0,4);
					//echo "tablename $tablename\n";
					

					//获取 INSERT缓冲区 SLOG日志
					$slogpath = $SDIR."/".$plogarr[1]."/".$plogarr[2] ;
					
					
					if (!file_exists($slogpath)) 
					{
						echo "$slogpath not exists\n";
						
						$sql = "select id from  $tablename where msgid = '$plogarr[2]'";
						$result=@mysql_query($sql);
						if(mysql_errno() == 0 )
						{
							if(@mysql_num_rows($result)==1)
							{
								$row=@mysql_fetch_array($result);
                        		$id=$row['id'];
								
								 $sql="update $tablename set stat_rpt='$plogarr[4]' where id=$id";//发送成功
								 
								//trans_w_log('/data0/apache/gateway/withdb/logdb/update_test.log',"$sql\n");
								 
                                @mysql_query($sql);
                              	unlink("$Filename");
							}
							else
							{
								if (@mysql_num_rows($result) >1)
									echo "msgid 不唯一 $plogarr[2]\n";

							}
							
						}
						else
						{
							echo mysql_error();

						}
						@mysql_free_result($result);
						
						fclose($fd);
						continue;
					}
					
					$pfd = fopen("$slogpath",'r');
					if(!$pfd)
					{
						//缓冲区没有找到文件，再数据库中找
						//trans_w_log('/data0/apache/gateway/withdb/logdb/insertdb.log',"$slogpath open errpr!\n");
						echo "update db\n";
						continue;
					}
					if (flock($pfd, LOCK_EX|LOCK_NB))
					{
						$sdata = fgets($pfd, 1024) ;
						$sdata = chop($sdata);
						
						if (strlen($sdata) == 0 )
						{
							trans_w_log('/data0/apache/gateway/withdb/logdb/insertdb.log',"$slogpath data is 0!\n");
							fclose($pfd);
            				fclose($fd);
							continue;
						}
						$sdata = addslashes($sdata);
						$slogarr = explode('|||',$sdata);
						
						$premobile = substr($slogarr[5], 0, 7);
						$mobile_reg = $Hregion[$premobile];
						$mobile_city = $Hcity[$premobile];
						//替换掉内容中的单引号，以免sql报错
						
						//入库
						$sql = "insert into $tablename (msgtime,gateway,region,city,rpcid,msgid,status,stat_rpt,dest,src,charge,msgfrom,service,msgfeetype,msgfeecode,linkid,msg,sendflag) values ('$slogarr[0]','$slogarr[1]','$mobile_reg','$mobile_city','$slogarr[2]','$slogarr[3]','$slogarr[4]','$plogarr[4]','$slogarr[5]','$slogarr[6]','$slogarr[7]','$slogarr[8]','$slogarr[10]','$slogarr[12]','$slogarr[13]','$slogarr[16]','$slogarr[15]','0')";
						//echo $sql."\n";
						//trans_w_log('/data0/apache/gateway/withdb/logdb/insert_test.log',"$sql\n");
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
            					fclose($pfd);
            					fclose($fd);
            					continue;
                			}
                			else
                			{
                				//数据库访问错误，直接退出
                				trans_w_log('/data0/apache/gateway/withdb/logdb/insertdb.log',"insert error ". mysql_errno() . ": " . mysql_error(). "\n" );
                				fclose($pfd);
            					fclose($fd);
                				continue;
                		
                			}
                		}

					}	
					else
					{	
						trans_w_log('/data0/apache/gateway/withdb/logdb/insertdb.log',"$Filename lock errpr!\n");
						fclose($pfd);
            			fclose($fd);
            			echo "lock err\n";
						continue;
						
					}
				}
				else
				{	
					trans_w_log('/data0/apache/gateway/withdb/logdb/insertdb.log',"$Filename lock errpr!\n");
					fclose($pfd);
            		fclose($fd);
					continue;
				}
				fclose($pfd);
            	fclose($fd);

				
				//删除文件
				unlink("$slogpath");
			
			}
		}
		closedir($handle);
		mysql_close($conn);
	}
	sleep($SLEEP_TIME);
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
