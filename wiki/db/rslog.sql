1. DB - rslog.
	1]. RLOGYYYYMMDD: // MO
		 CREATE TABLE `RLOG20100810` (
		  `id` int(9) unsigned NOT NULL auto_increment,
		  `msgtime` time NOT NULL default '00:00:00',
		  `gateway` int(6) unsigned NOT NULL default '0',
		  `lcode` varchar(21) NOT NULL default '', 
		  `mobile` bigint(32) unsigned NOT NULL default '0', /* 用户手机号 */
		  `msglength` int(4) default NULL,
		  `msg` text,
		  
		  `msgfee` int(4) default NULL, /* 一个gateway可以有多个fee。RMB 1yuan = 100 */
		  `msgid` varchar(24) NOT NULL default '', /* 从运营商、合作方获取到的该条msg的GUID */
		  `projec_no` int(10) unsigned NOT NULL default '99999', /*项目的from值，必填。 */
		  `status` int(1) NOT NULL default '0',
		  `service` int(6) unsigned NOT NULL default '0', /* qxt没用到，但是为了扩展而保留。 */
		  `linkid` varchar(20) default NULL, /* qxt没用到，但是为了扩展而保留。 */
		  PRIMARY KEY  (`id`),
		  INDEX `charge` (`charge`),
		  INDEX `mobile` (`mobile`),
		  INDEX `project_no` (`project_no`)
		) TYPE=INNODB

	2]. SLOGYYYYMMDD: // MT
		/*经常用到的查询语句：
			1- 统计某次群发的成功率
			select count(id) from SLOG where gsendid=XXX;  获取下发总量
			select count(id) from SLOG where gsendid=XXX and stat_rpt = 0; 获取接收成功的总量
			select stat_rpt, count(id) from SLOG where gsendid=XXX group by stat_rpt; 按状态报告的值进行分组
			
			1.1 - 统计某次群发的分省/市的成功率 - 由于该语句导致sql锁表，故需要在凌晨cron执行
			select province, city, stat_rpt, count(id),  from SLOG where gsendid=XXX group by province, city, stat_rpt;
			
			1.2 - 统计某次群发的话单 - 由于会导致sql锁表，故需要在凌晨cron执行
			下发总量、[成功下行，可不写]、成功接收、余额
			
			2- 状态报告入库，需要使用luwei的入库程序！luwei的程序也需要调用smsd，以同步状态报告给下游合作方
			insert into SLOG values(); 该语句只有当状态报告返回后，
			连同省、市、from、gsendid、状态报告等信息，一次性入库。-需要建立号段表，放入上下行db即可。
			入库后，通知状态报告同步smsd，进行同步，并update SLOG set sendflag=1 where mobile=XXX and msgid=XXX;
			若同步失败，置 sendflag=[1, 2, 3]，再由cron.php进行重提交给smsd，进行失败重发，最多尝试三次。
			
			3- 日常查询
			select * from SLOG where mobile=XXX and from=XXX and gsendid=XXX; 查询某次群发的单个手机号
			select * from SLOG where gateway=XXX; 查询某网关的总下发量
			select * from SLOG where gateway=XXX and stat_rpt = 0; 查询某网关的下发成功总量
			select * from SLOG where gateway=XXX group by stat_rpt; 查询某网关的下发，按状态报告的值进行分组
		*/
		
		CREATE TABLE `SLOG20100810` (
		  `id` int(9) unsigned NOT NULL auto_increment,
		  `gsendid` int(10) unsigned NOT NULL default '0', /* 某次群发的群发id。若使用接口进行群发，则无群发id，只有from。 */
		  /* 因此，每天的账单cron，只需要根据 gsendid是否=0，分为后付费接口群发[gsnedid=0], 先付费用户[gsendid!=0].*/
		  /* 跑账单时需要注意的是：是否有这种from值，他不但有gsendid，是先付费，也有gsendid=0，是后付费。这种from是非法的。*/
		  /* 这种from可能是用户使用平台上的短信测试功能下发的，故无法含gsendid。数量很小。*/
		  `gateway` int(6) unsigned NOT NULL default '0',
		  `rpcid` int(10) unsigned NOT NULL default '0', /* 路由的GUID */
		  `sendtime` time NOT NULL default '00:00:00', /* 提交给运营商、合作方的时间*/
		  `msgid` varchar(24) NOT NULL default '', /* 提交给运营商、合作方后，从他们那里获取到的该条msg的GUID。若为空，表示提交失败。 */
		  `stat_rpt` varchar(16) NOT NULL default '', /* 状态报告 */ 
		  `rpt_time` time NOT NULL default '00:00:00', /* 返回状态报告的时间，既用户收到msg的时间*/
		  `mobile` bigint(32) unsigned NOT NULL default '0', /* 用户手机号 */
		  `isp_type` varchar(8) NOT NULL default '', /* 该号码属于cm,cu,ct?*/
		  `province` varchar(4) NOT NULL default '', /* 省份id */
		  `city` varchar(4) NOT NULL default '', /* 城市id */
		  `lcode` varchar(21) NOT NULL default '',
		  `project_no` int(10) unsigned default '99999', /* 下发所使用的项目from值，必填。 */
		  `sendflag` tinyint(1) default 0, /* 同步给合作方的标志位. 0=未同步; 200=ok; 1=同步失败一次; 2=同步失败2次;.... */
		  `msgfee` int(4) default NULL, /* 一个gateway可以有多个fee。RMB 1yuan = 100 */
		  `msglength` int(3) default NULL,
		  `msg` varchar(160) default NULL,
		  `amend` int(10) unsigned default NULL, /* 藏收入 */
		  `service` int(6) unsigned NOT NULL default '0', /* qxt没用到，但是为了扩展而保留。 */
		  `linkid` varchar(20) default NULL,
		  PRIMARY KEY  (`id`),
		  INDEX `rpcid` (`rpcid`),
		  INDEX `msgid` (`msgid`),
		  INDEX `mobile` (`mobile`),
		  INDEX `gsendid` (`gsendid`),
		  INDEX `project_no` (`project_no`)
		) TYPE=MyISAM 

	3] 号段表
		CREATE TABLE `province_city` (
		  `id` int(9) unsigned NOT NULL auto_increment,
		  `mobile` varchar(16) NOT NULL default '', /* 手机号前7位 */
		  `province` varchar(8) NOT NULL default '', /* 省份id */
		  `province_name` varchar(24) NOT NULL default '', /* 省份名称 */
		  `city` varchar(8) NOT NULL default '', /* 城市id */
		  `city_name` varchar(8) NOT NULL default '', /* 城市名称 */
		  `type` varchar(4) NOT NULL default '', /* 运营商cm, cu, ct */
		  PRIMARY KEY  (`id`),
		  INDEX `mobile` (`mobile`)
		)	
	
	4] 账单表 - 属于统计信息，放在 短信平台sms_plat db 里面
	
	