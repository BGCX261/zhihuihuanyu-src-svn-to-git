/* 一个用户 -- 多个项目。一旦确定了某个项目，则确定了是先付费、后付费。即：提交群发号码txt，还是用接口群发。*/
/* 				一个项目 -- 可以组合使用多个通道，完成对cm、cu、ct用户的下发。参考 map_project_channel。但是目前只支持一个项目使用一个通道。*/
/* 				一个项目 -- 多次群发。*/
/* 							一次群发 -- 一个账单。*/
/* 代发用户 -- 目前，属于管理员权限。也跟普通用户一样，申请项目，申请群发，等等。只不过是管理员自己申请，自己审批。*/


-- MySQL dump 10.9
--
-- Host: localhost    Database: sms_plat
-- ------------------------------------------------------
-- Server version	4.1.21

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

USE `sms_plat`;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(32) NOT NULL default '',
  `password` varchar(40) NOT NULL default '',
  
  `pre_account_fee` int(16) unsigned NOT NULL default '0', /*先付费用户的账户总余额*/
  `post_account_fee` int(16) unsigned NOT NULL default '0', /* 后付费用户累计的消费总额*/
  
  `email` varchar(254) NOT NULL default '',
  `role_id` int(10) unsigned NOT NULL default '0', /* useless! */
  `create_date` datetime default NULL,
  `lastvisit_date` datetime default NULL,
  `status` tinyint(1) unsigned NOT NULL default '0',
  `app_name` varchar(30) default NULL,
  `app_dept` varchar(255) default NULL,
  `app_phone` varchar(20) default NULL,
  `app_ext` varchar(6) default NULL,
  `add_app_name` varchar(255) default NULL,
  `add_app_phone` varchar(12) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`),
  INDEX `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

--
-- Table structure for table `user_charge`. 用户充值记录表
-- 充值流程：先抵消后付费账户消费额，使后付费账户值变成0，再充值到先付费账户中，使先付费账户值变>0.
-- 充值操作员、充值时间、充值金额、充值完成后的先后付费账户数值。

DROP TABLE IF EXISTS `user_charge`;
CREATE TABLE `user_charge` (
  `id` int(10) unsigned NOT NULL auto_increment,
  
  `user_id` int(10) NOT NULL default '0', /* 充值人id */
  `username` varchar(32) NOT NULL default '',

  `operator_id` int(10) NOT NULL default '0', /* 充值操作员id */
  `operator_name` varchar(32) NOT NULL default '',
  
  `charge_date` datetime default NULL,
  `charge_fee` int(16) unsigned NOT NULL default '0', /* 本次充值的金额。精确到厘。*/
  `pre_account_fee` int(16) unsigned NOT NULL default '0', /* 先付费用户的账户总余额。每次群发后，该值变小。*/
  `post_account_fee` int(16) unsigned NOT NULL default '0', /* 后付费用户累计的消费总额。每次群发后，该值变大。*/
  `total_account_fee` int(16) unsigned NOT NULL default '0', /* 用户充值后的账户总额。*/

  PRIMARY KEY  (`id`),
  INDEX `charge_date` (`charge_date`) /* 方便order by*/
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

--
-- Table structure for table `channel_list`
--

DROP TABLE IF EXISTS `channel_list`;
CREATE TABLE `channel_list` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `channel_name` varchar(255) NOT NULL default '',
  `company_name` varchar(255) NOT NULL default '',
  `gateway` int(10) unsigned NOT NULL default '0',
  `longcode` varchar(32) default NULL, /* 根号码。*/
  `mo_mt_type` varchar(10) NOT NULL default 'ALL', /* 本通道属于上行通道+下行通道？ALL=上下行均可。MO=只上行。MT=只下行。 */
  `isp_type` varchar(10) NOT NULL default 'ALL', /* 本通道支持的运营商。ALL=移动联通电信均可。CM=只移动。CU只联通。CT只电信。CMCU=只移动联通 */
  `fin_code` varchar(30) default NULL, /* 计费代码，没用了。*/
  `contract_begin` datetime default NULL,
  `contract_end` datetime default NULL,
  `channel_price` int(16) unsigned NOT NULL default '0', /* 通道价格。精确到厘。1元=1000厘*/
  `app_no` int(10) unsigned default NULL, /* 也就是应用编号service */
  `project_no` int(10) unsigned NOT NULL default '0', /* 项目编号，from，本字段不需要。*/
  `have_contract` tinyint(3) unsigned NOT NULL default '0',
  `memo` varchar(255) default NULL, /* 备注：每条短信的字长，关键词等。*/
  PRIMARY KEY  (`id`),
  UNIQUE KEY `channel_name` (`channel_name`),
  INDEX `gateway` (`gateway`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Table structure for table `project_list`
--
DROP TABLE IF EXISTS `project_list`;
CREATE TABLE `project_list` (
  `id` int(10) unsigned NOT NULL auto_increment,
  
  `project_no` int(10) unsigned NOT NULL default '0', /* 项目编号，from。*/
  `pj_name` varchar(64) NOT NULL default '',
  `pj_bill_type` varchar(16) NOT NULL default 'PRE', /* 本项目每日账单的类型。PRE=先付费，POST=后付费*/
  `pj_fee` int(10) unsigned NOT NULL default '0', /* 我们跟客户谈定的，每条sms的费用。精确到厘。*/
  
  `longcode` varchar(64) default NULL,
  
  `pj_start` datetime default NULL,
  `pj_end` datetime default NULL,
  
  `avg_cnt` int(10) unsigned default NULL,
  `pj_type` tinyint(3) unsigned default NULL, /* 上行还是下行。没用。*/
  `industry_type` varchar(64) NOT NULL default '', /* 行业类型。*/
  `upline_date` datetime default NULL,
  `pj_desc` varchar(255) default NULL,
  `pj_popluar` varchar(255) default NULL,
  `pj_restrict` varchar(255) default NULL,
  `pj_msg` varchar(255) default NULL,
  `pj_memo` varchar(255) default NULL,

  `app_no` int(10) unsigned default NULL,
  `app_name` varchar(30) default NULL,
  `app_dept` varchar(128) default NULL,
  `app_phone` varchar(64) default NULL,
  `app_ext` varchar(6) default NULL,
  `app_email` varchar(64) default NULL,
  `add_app_name` varchar(128) default NULL,
  `add_app_phone` varchar(64) default NULL,
  `app_date` datetime default NULL,

  `apply_user_id` int(10) NOT NULL default '0', /* 项目申请人id */
  `apply_date` datetime default NULL,
  `audit_user_id` int(10) NOT NULL default '0', /* 项目审核人id */
  `audit_date` datetime default NULL,

  `user_id` int(10) unsigned default NULL, /*????????*/
  `apv_user` int(10) unsigned default NULL, /*????????*/

  `status` tinyint(3) unsigned default '0',
  `stop_date` datetime default NULL, /* 项目关闭的时间*/
  
  PRIMARY KEY  (`id`),
  UNIQUE KEY `pj_name` (`pj_name`),
  UNIQUE KEY `project_no` (`project_no`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Table structure for table `map_project_channel`
-- 一个项目

DROP TABLE IF EXISTS `map_project_channel`;
CREATE TABLE `map_project_channel` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pj_id` int(10) unsigned NOT NULL default '0',
  `pj_name` int(10) unsigned NOT NULL default '0',
  `project_no` int(10) unsigned NOT NULL default '0', /* 项目编号，from。*/
  
  `channel_id` int(10) unsigned NOT NULL default '0',
  `channel_name` int(10) unsigned NOT NULL default '0',
  `gateway` int(10) unsigned NOT NULL default '0', /* channel_list.FK: 通道的网关号。*/
  `isp_type` varchar(10) NOT NULL default 'ALL', /* 指明对该项目的该 channel_id，将用于哪个运营商的下发。CM=只移动。CU只联通。CT只电信。*/
  /* 因此，对于一个项目，将至少有3个channel_id，分别表示对cm、cu、ct用哪个channel_id。 */
  
  `channel_percent` int(10) unsigned NOT NULL default '100', /* 该 channel_id 在运营商isp_type下的发送总量中的比率。*/
  /* 当我们有N多通道时，对某个先付费提交群发手机号txt的项目，可以指定：移动号段用通道A和B来发，且A占下发总量的60%，B占40%。-目前不用支持。*/
  
  PRIMARY KEY  (`id`),
  INDEX `project_no` (`project_no`),
  INDEX `gateway` (`gateway`),
  INDEX `channel_id` (`channel_id`)  
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `group_send_list`
--

DROP TABLE IF EXISTS `group_send_list`;
CREATE TABLE `group_send_list` (
  `gsendid` int(10) unsigned NOT NULL auto_increment,
  `gsend_name` varchar(64) NOT NULL default '',
  
  `pj_id` int(10) unsigned NOT NULL default '0',  
  `project_no` int(10) unsigned NOT NULL default '0', /* 项目编号，from。*/
  `pj_name` varchar(64) NOT NULL default '', /* 刻意做的冗余字段。*/
  
  `sms_msg` varchar(255) NOT NULL default '',
  `phone_list` mediumtext NOT NULL,
  `upload_cnt` int(10) unsigned NOT NULL default '0', /* 用户提交给sms_plat平台的号码总量 */
  
  `app_date` datetime default NULL, /* 用户设置的下发时间 */
  
  `apply_user_id` int(10) NOT NULL default '0', /* 群发申请人id */
  `apply_user_name` int(10) NOT NULL default '0', /* 群发申请人id */
  `apply_date` datetime default NULL,
  `audit_user_id` int(10) NOT NULL default '0', /* 群发审核人id */
  `audit_user_name` int(10) NOT NULL default '0', /* 群发审核人id */
  `audit_date` datetime default NULL,
    
  `gs_start` datetime NOT NULL default '0000-00-00 00:00:00',
  `gs_end` datetime NOT NULL default '0000-00-00 00:00:00',
  
  `status` varchar(16) NOT NULL default 'NOT.AUDIT', /* 群发状态。 BEGIN, SENDING, END, NOT.AUDIT, AUDIT, AUDIT.FAILED*/
  PRIMARY KEY  (`id`),
  INDEX `project_no` (`project_no`),
  INDEX `apply_user_id` (`apply_user_id`),
  INDEX `status` (`status`),
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `bill`; 
/* 先付费：用户需要先充值，然后才能使用群发功能的用户的账单。这类用户是最安全的用户。 */
/* 后付费：先使用群发功能,每月统一某天结算账单的用户。这类用户可以平台提交手机号+接口群发。 */
/* 若某个用户不但想先付费，也想后付费，则是个变态用户，不用考虑这类用户。*/
/* 至于到底是先付费还是后付费？由 project_list的 pj_bill_type决定。一个项目，要么先付费，要么后付费。*/
/* 放置了一些冗余信息，以方便显示到web页面。*/
CREATE TABLE `bill` (
  `bill_id` int(10) unsigned NOT NULL auto_increment,
  
  `user_id` int(10) NOT NULL default '0',
  `username` varchar(32) NOT NULL default '',
  
  `gsendid` int(10) unsigned NOT NULL default '0', /* 某次群发的群发id。若使用接口进行群发，则无群发id，只有from */
  `gsend_name` varchar(64) NOT NULL default '',
  
  `pj_id` int(10) unsigned NOT NULL default '0',   
  `project_no` int(10) unsigned NOT NULL default '0', /* from值。对只用接口进行群发的用户，只有from，没有gsendid。必然是后付费用户。*/
  `pj_name` varchar(64) NOT NULL default '',
  `pj_bill_type` varchar(16) NOT NULL default 'PRE', /* 本次账单的类型。PRE=先付费，POST=后付费*/
  `pj_fee` int(16) unsigned NOT NULL default '0', /* project_list.FK: 我们跟客户谈的每条sms的费用。精确到厘。*/
 
  `bill_date` datetime DEFAULT NULL,

  `upload_cnt` int(10) unsigned NOT NULL default '0', /* group_send_list.FK: 用户提交给sms_plat平台的号码总量 */
  `user_cost` int(16) unsigned NOT NULL default '0', /* 本次群发，用户的总花费。精确到厘。upload_cnt*pj_fee. */
  /* 以下三个fee，是截止到账单生成时刻：*/
  `pre_account_fee` int(16) unsigned NOT NULL default '0', /* 先付费用户的账户总余额。每次群发后，该值变小。*/
  `post_account_fee` int(16) unsigned NOT NULL default '0', /* 后付费用户累计的消费总额。每次群发后，该值变大。*/
  `total_account_fee` int(16) unsigned NOT NULL default '0', /* 用户充值后的账户总额。 pre_account_fee - post_account_fee*/

  `our_cost` int(16) unsigned NOT NULL default '0', /* 我们这次群发的总成本。for cm, cu, ct, cost=to_isp_cnt*channel_price */
  `our_profix` int(16) unsigned NOT NULL default '0', /* 我们这次群发的总利润。user_cost - our_cost */

  PRIMARY KEY  (`bill_id`),
  INDEX `user_id` (`user_id`),
  INDEX　`gsendid`　(`gsendid`),
  INDEX `project_no` (`project_no`)
)


DROP TABLE IF EXISTS `bill_detail`; 
/* 由于一次群发可使用多个通道，即使一个运营商，也可按照比率，使用不同的通道进行下发。故账单费用的计算，需要一个详细表格 */
/* 具体有多少个通道？在 map_project_channel有配置。*/
CREATE TABLE `bill_detail` (
  `id` int(10) unsigned NOT NULL auto_increment,
  
  `bill_id` int(10) unsigned NOT NULL auto_increment, /*FK.*/
  `map_pj_chnl_id` int(10) unsigned NOT NULL auto_increment, /*FK. */
  
  `gateway` int(10) unsigned NOT NULL default '0', /* channel_list.FK: 通道的网关号。知道了网关号，也就知道了哪个通道。*/  
  `isp_type` varchar(10) NOT NULL default 'ALL', /* 指明对该项目的该 channel_id，将用于哪个运营商的下发。CM=只移动。CU只联通。CT只电信。*/
  `channel_price` int(16) unsigned NOT NULL default '0', /* 删除。因为一次群发可以使用多个通道。按照运营商分通道。channel_list.FK: 通道价格。精确到厘。1元=1000厘*/

  `to_isp_cnt`  int(10) unsigned NOT NULL default '0', /* 我们的平台提交给运营商的总量。只是统计，不能显示到界面上 */
  `mr_ok_cnt` int(10) unsigned NOT NULL default '0', /* 用户手机成功接收的总量 */
  `succ_rate`  int(10) unsigned NOT NULL default '100', /* mr_ok_cnt / to_isp_cnt */

  `our_cost` int(16) unsigned NOT NULL default '0', /* 我们这次群发的成本。for cm, cu, ct, cost=to_isp_cnt*channel_price */
  
  PRIMARY KEY  (`id`),
  INDEX `bill_id` (`bill_id`),
  INDEX `map_pj_chnl_id` (`map_pj_chnl_id`),
  INDEX `gateway` (`gateway`)
) 
  
--
-- Table structure for table `resources`
--

DROP TABLE IF EXISTS `resources`;
CREATE TABLE `resources` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `res_name` varchar(64) NOT NULL default '',
  `class` varchar(32) NOT NULL default '',
  `method` varchar(32) NOT NULL default '',
  `ext` varchar(5) NOT NULL default '',
  `res_desc` varchar(128) NOT NULL default '',
  `status` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `res_name` (`res_name`),
  UNIQUE KEY `class_method_ext` (`class`,`method`,`ext`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

--
-- Table structure for table `role_resource_map`
--

DROP TABLE IF EXISTS `role_resource_map`;
CREATE TABLE `role_resource_map` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `role_id` int(10) NOT NULL default '0',
  `res_id` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `role_res` (`role_id`,`res_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `role_resource_map`
--

LOCK TABLES `role_resource_map` WRITE;
/*!40000 ALTER TABLE `role_resource_map` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_resource_map` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `role_name` varchar(64) NOT NULL default '',
  `role_desc` varchar(128) NOT NULL default '',
  `status` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `role_name` (`role_name`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

-
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `task_list` 没用啦！
-- 

DROP TABLE IF EXISTS `task_list`;
CREATE TABLE `task_list` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `project_no` int(10) unsigned NOT NULL default '0',
  `task_name` varchar(255) NOT NULL default '',
  `task_popular` varchar(255) NOT NULL default '',
  `app_date` datetime default NULL,
  `task_type` tinyint(3) unsigned default NULL,
  `task_msg` varchar(255) default NULL,
  `task_start` datetime default NULL,
  `task_end` datetime default NULL,
  `status` tinyint(3) unsigned default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `task_name` (`task_name`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;


--
-- Table structure for table `tmp_users`
--

DROP TABLE IF EXISTS `tmp_users`;
CREATE TABLE `tmp_users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `app_name` varchar(30) default NULL,
  `app_dept` varchar(255) default NULL,
  `app_phone` varchar(20) default NULL,
  `app_ext` varchar(6) default NULL,
  `app_email` varchar(255) default NULL,
  `add_app_name` varchar(255) default NULL,
  `add_app_phone` varchar(12) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `tmp_users`
--

LOCK TABLES `tmp_users` WRITE;
/*!40000 ALTER TABLE `tmp_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `tmp_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_role_map`
--

DROP TABLE IF EXISTS `user_role_map`;
CREATE TABLE `user_role_map` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_id` int(10) NOT NULL default '0',
  `role_id` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `user_role` (`user_id`,`role_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;


--
-- Dumping data for table `user_role_map`
--

LOCK TABLES `user_role_map` WRITE;
/*!40000 ALTER TABLE `user_role_map` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_role_map` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

