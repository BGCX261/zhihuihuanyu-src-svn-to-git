<?
/*
说明：本程序
第一个参数是kill 本程序实现kill进程功能
第一个参数非kill 本程序实现监控进程功能。
**
*/

if ($argv[1] === "kill")
{
	if ($argv[2] === "insertDB.php")
	{
		exec("echo '1' > /data0/apache/gateway/etc/insertDB.conf") ;
	}
	else if ($argv[2] === "mtlog2buf.php")
	{
		exec("echo '1' > /data0/apache/gateway/etc/mtlog2buf.conf") ;
	}
	else if ($argv[2] === "plog2buf.php")
	{
		exec("echo '1' > /data0/apache/gateway/etc/plog2buf.conf") ;
	}
	else if ($argv[2] === "all")
	{
		exec("echo '1' > /data0/apache/gateway/etc/insertDB.conf") ;
		exec("echo '1' > /data0/apache/gateway/etc/mtlog2buf.conf") ;
		exec("echo '1' > /data0/apache/gateway/etc/plog2buf.conf") ;
	}
	else 
	{
		echo "参数错误：ex：php control.php kill insertDB.php\n";
	}

}
else 
{
	$proce = array("insertDB.php","mtlog2buf.php","plog2buf.php");
	foreach ($proce as $key)
	{
		echo "$key\n";  
		
		$procnt = exec ("ps -axww | grep $key | grep -v grep | grep -v '/bin/sh' | wc -l") + 0;
		print $procnt."\n";
		
		if ($procnt == 0)
		{
			echo "start $key\n";
			exec("/data0/php/bin/php /data0/apache/gateway/withdb/log2db/$key > /dev/null &");
		}
		
	}
	
}
?>