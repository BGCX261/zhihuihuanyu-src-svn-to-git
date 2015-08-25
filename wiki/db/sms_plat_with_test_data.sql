/*
SQLyog Enterprise - MySQL GUI v8.12 
MySQL - 5.5.29-log : Database - sms_plat
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/`sms_plat` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `sms_plat`;

/*Table structure for table `bill` */

DROP TABLE IF EXISTS `bill`;

CREATE TABLE `bill` (
  `bill_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(32) NOT NULL DEFAULT '',
  `gsend_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK.group_send_list',
  `gsend_name` varchar(64) NOT NULL DEFAULT '',
  `project_no` int(10) unsigned NOT NULL DEFAULT '0',
  `pj_name` varchar(64) NOT NULL DEFAULT '',
  `pj_bill_type` varchar(16) NOT NULL DEFAULT 'PRE' COMMENT 'FK. PRE or POST',
  `pj_fee` int(16) unsigned NOT NULL DEFAULT '0' COMMENT 'FK. defined when contract ok.',
  `bill_date` datetime NOT NULL,
  `upload_cnt` int(16) unsigned NOT NULL DEFAULT '0',
  `user_cost` int(16) unsigned NOT NULL DEFAULT '0',
  `pre_account_fee` int(16) unsigned NOT NULL DEFAULT '0',
  `post_account_fee` int(16) unsigned NOT NULL DEFAULT '0',
  `total_account_fee` int(16) unsigned NOT NULL DEFAULT '0',
  `our_cost` int(16) unsigned NOT NULL DEFAULT '0',
  `our_profix` int(16) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`bill_id`),
  KEY `user_id` (`user_id`),
  KEY `gsendid` (`gsend_id`),
  KEY `project_no` (`project_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `bill` */

/*Table structure for table `bill_detail` */

DROP TABLE IF EXISTS `bill_detail`;

CREATE TABLE `bill_detail` (
  `detail_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bill_id` int(10) unsigned NOT NULL DEFAULT '0',
  `map_pj_chnl_id` int(10) unsigned NOT NULL DEFAULT '0',
  `project_no` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK. from.',
  `channel_id` int(10) unsigned NOT NULL DEFAULT '0',
  `gateway` int(10) unsigned NOT NULL DEFAULT '0',
  `isp_type` varchar(8) NOT NULL DEFAULT '' COMMENT 'CM or CU or CT',
  `channel_price` int(16) unsigned NOT NULL DEFAULT '0',
  `pj_fee` int(16) unsigned NOT NULL DEFAULT '0' COMMENT 'defined when contract ok. show for apply_user.',
  `user_upload_cnt` int(16) unsigned NOT NULL DEFAULT '0' COMMENT 'for each $isp_type, user upload count.',
  `our_hidden_cnt` int(16) unsigned NOT NULL DEFAULT '0' COMMENT 'before mt to isp, we hid some mobile.',
  `to_isp_cnt` int(16) unsigned NOT NULL DEFAULT '0',
  `mr_ok_cnt` int(16) unsigned NOT NULL DEFAULT '0',
  `succ_rate` int(16) unsigned NOT NULL DEFAULT '0' COMMENT 'mr_ok_cnt / to_isp_cnt',
  `user_cost` int(16) unsigned NOT NULL DEFAULT '0' COMMENT 'user_upload_cnt * pj_fee',
  `our_cost` int(16) unsigned NOT NULL DEFAULT '0' COMMENT 'to_isp_cnt * channel_price',
  `detail_date` datetime NOT NULL COMMENT 'this detail bill create time.',
  PRIMARY KEY (`detail_id`),
  KEY `bill_id` (`bill_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `bill_detail` */

/*Table structure for table `channel_list` */

DROP TABLE IF EXISTS `channel_list`;

CREATE TABLE `channel_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `channel_name` varchar(128) NOT NULL DEFAULT '',
  `company_name` varchar(128) NOT NULL DEFAULT '',
  `channel_price` int(16) unsigned NOT NULL DEFAULT '0',
  `max_len` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'max mt CHN words.',
  `gateway` int(10) unsigned NOT NULL DEFAULT '0',
  `longcode` varchar(32) NOT NULL DEFAULT '' COMMENT 'the root longcode',
  `mo_mt_type` varchar(16) NOT NULL DEFAULT 'MOMT',
  `isp_type` varchar(16) NOT NULL DEFAULT 'CMCUCT',
  `restrict_city` varchar(1024) NOT NULL DEFAULT '' COMMENT 'can NOT send city list, like 0010,0020. if one provice restrict, then all citys in this county is list here.',
  `contract_begin` datetime DEFAULT NULL,
  `contract_end` datetime DEFAULT NULL,
  `have_contract` tinyint(1) NOT NULL DEFAULT '1',
  `memo` varchar(256) NOT NULL DEFAULT '',
  `app_no` int(16) unsigned NOT NULL DEFAULT '0',
  `fin_code` varchar(64) NOT NULL DEFAULT '' COMMENT 'finance_contract_id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `channel_name` (`channel_name`),
  UNIQUE KEY `gateway` (`gateway`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `channel_list` */

insert  into `channel_list`(`id`,`channel_name`,`company_name`,`channel_price`,`max_len`,`gateway`,`longcode`,`mo_mt_type`,`isp_type`,`restrict_city`,`contract_begin`,`contract_end`,`have_contract`,`memo`,`app_no`,`fin_code`) values (1,'测试通道-1','测试通道所在公司-1',38,62,101001,'10690090','MOMT','CMCUCT','','2013-01-01 00:00:00','2014-01-01 00:00:00',1,'here is memo 2.',0,'2013.test.1'),(3,'测试通道-3','测试通道所在公司-2',25,70,101002,'10690091','MOMT','CUCT','','2013-01-02 00:00:00','2014-01-02 00:00:00',1,'22222',0,'2013.test.2');

/*Table structure for table `group_send_list` */

DROP TABLE IF EXISTS `group_send_list`;

CREATE TABLE `group_send_list` (
  `gsend_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gsend_name` varchar(64) NOT NULL DEFAULT '',
  `project_no` int(10) unsigned NOT NULL DEFAULT '0',
  `pj_name` varchar(64) NOT NULL DEFAULT '',
  `sms_msg` varchar(256) NOT NULL DEFAULT '',
  `phone_list` mediumtext NOT NULL,
  `upload_client_filename` varchar(64) NOT NULL DEFAULT '' COMMENT 'user upload file original name.',
  `upload_file_fullname` varchar(128) NOT NULL DEFAULT '' COMMENT 'full path. filename=applyuserid_projectno_date.txt',
  `upload_cnt` int(16) unsigned NOT NULL DEFAULT '0',
  `mt_date` datetime DEFAULT NULL COMMENT 'mt time user defined.',
  `apply_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `apply_user_name` varchar(32) NOT NULL DEFAULT '',
  `apply_date` datetime DEFAULT NULL,
  `audit_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `audit_user_name` varchar(32) NOT NULL DEFAULT '',
  `audit_date` datetime DEFAULT NULL,
  `gs_start` datetime DEFAULT NULL COMMENT 'the real mt start time.',
  `gs_end` datetime DEFAULT NULL COMMENT 'the real mt end time.',
  `gs_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 => ''未审核'', 1 => ''驳回'', 2 => ''通过审核'', 3 => ''正在群发'', 4 => ''群发结束'', 5 => ''删除''',
  PRIMARY KEY (`gsend_id`),
  KEY `project_no` (`project_no`),
  KEY `apply_user_id` (`apply_user_id`),
  KEY `status` (`gs_status`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `group_send_list` */

insert  into `group_send_list`(`gsend_id`,`gsend_name`,`project_no`,`pj_name`,`sms_msg`,`phone_list`,`upload_client_filename`,`upload_file_fullname`,`upload_cnt`,`mt_date`,`apply_user_id`,`apply_user_name`,`apply_date`,`audit_user_id`,`audit_user_name`,`audit_date`,`gs_start`,`gs_end`,`gs_status`) values (1,'test-gsend-1',98017,'','test-gsend-sms-msg-1','','gum-all-info.txt','D:/temp/uploads/2_98017_20130525_172651.txt',0,'2013-09-26 12:23:00',2,'测试群发外部用户-1','2013-05-25 17:26:51',0,'',NULL,NULL,NULL,5),(3,'test-gsend-2',98013,'','msg-for2016','','','',0,'2016-03-04 03:06:00',1,'内部用户-1','2013-05-25 22:59:44',0,'','2013-05-26 22:24:01',NULL,NULL,0),(4,'test-gsend-4',98017,'','msg-4','','Task.txt','D:/temp/uploads/4_1_98017_20130525_230112.txt',0,'2015-03-03 02:08:00',1,'内部用户-1','2013-05-25 23:01:12',1,'内部用户-1','2013-05-26 22:36:52',NULL,NULL,2),(5,'test-gsend-5',98015,'测试公司-测试业务-3','msg5','','worklog.txt','D:/temp/uploads/5_1_98015_20130525_230318.txt',0,'2016-06-05 06:05:00',1,'内部用户-1','2013-05-25 23:03:18',0,'','2013-05-26 22:22:31',NULL,NULL,5),(6,'gsend6',98017,'外部测试公司-测试业务-7','msg6','','gum-all-info.txt','D:/temp/uploads/6_2_98017_20130525_230509.txt',0,'2017-05-27 17:24:00',2,'测试群发外部用户-1','2013-05-25 23:05:09',1,'内部用户-1','2013-05-26 22:36:44',NULL,NULL,5),(7,'test-gsend-77',98017,'外部测试公司-测试业务-7','test-msg-7777777','','gum-all-info.txt','D:/temp/uploads/7_2_98017_20130526_225817.txt',0,'2016-04-06 11:07:00',2,'测试群发外部用户-1','2013-05-26 23:30:20',1,'内部用户-1','2013-05-26 23:30:42',NULL,NULL,2),(8,'test-gsend-888',98016,'外部测试公司-测试业务-6','msg0=8888','','Task.txt','D:/temp/uploads/8_2_98016_20130526_232812.txt',0,'2015-04-05 05:12:00',2,'测试群发外部用户-1','2013-05-26 23:34:35',0,'',NULL,NULL,NULL,0);

/*Table structure for table `map_project_channel` */

DROP TABLE IF EXISTS `map_project_channel`;

CREATE TABLE `map_project_channel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pj_id` int(10) NOT NULL DEFAULT '0',
  `project_no` int(10) NOT NULL DEFAULT '0',
  `channel_id` int(10) NOT NULL DEFAULT '0',
  `channel_name` varchar(128) NOT NULL DEFAULT '',
  `gateway` int(16) NOT NULL DEFAULT '0',
  `isp_type` varchar(8) NOT NULL DEFAULT '' COMMENT 'CM or CU or CT',
  `longcode` varchar(32) NOT NULL DEFAULT '',
  `channel_percent` int(10) NOT NULL DEFAULT '100',
  PRIMARY KEY (`id`),
  KEY `project_no` (`project_no`),
  KEY `gateway` (`gateway`),
  KEY `channel_id` (`channel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `map_project_channel` */

insert  into `map_project_channel`(`id`,`pj_id`,`project_no`,`channel_id`,`channel_name`,`gateway`,`isp_type`,`longcode`,`channel_percent`) values (1,5,98013,1,'测试通道-1',101001,'CM','1069009001',100),(2,5,98013,3,'测试通道-3',101002,'CU','1069009001',100),(3,5,98013,3,'测试通道-3',101002,'CT','1069009001',100),(4,4,98015,1,'测试通道-1',101001,'CM','1069009005',100),(5,4,98015,3,'测试通道-3',101002,'CU','1069009005',100),(6,4,98015,1,'测试通道-1',101002,'CT','1069009005',100),(10,7,98016,1,'测试通道-1',101001,'CM','1069009006',100),(11,7,98016,3,'测试通道-3',101002,'CU','1069009006',100),(12,7,98016,3,'测试通道-3',101002,'CT','1069009006',100),(13,8,98017,3,'测试通道-3',101002,'CT','1069009007',100),(14,9,98018,3,'测试通道-3',101002,'CU','1069009008',100);

/*Table structure for table `project_list` */

DROP TABLE IF EXISTS `project_list`;

CREATE TABLE `project_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_no` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'that is from.',
  `pj_name` varchar(64) NOT NULL DEFAULT '',
  `pj_bill_type` varchar(16) NOT NULL DEFAULT 'PRE' COMMENT 'PRE or POST',
  `pj_fee` int(16) unsigned NOT NULL DEFAULT '0',
  `isp_type` varchar(16) NOT NULL DEFAULT 'CMCUCT' COMMENT 'CMCUCT',
  `pj_start` datetime DEFAULT NULL,
  `pj_end` datetime DEFAULT NULL,
  `industry_type` varchar(32) NOT NULL DEFAULT '',
  `mo_mt_type` varchar(16) NOT NULL DEFAULT 'MOMT' COMMENT 'mo or mt or momt',
  `pj_desc` varchar(256) NOT NULL DEFAULT '',
  `pj_restrict` varchar(256) NOT NULL DEFAULT '' COMMENT 'ip-list',
  `pj_memo` varchar(256) NOT NULL DEFAULT '',
  `apply_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `apply_company` varchar(128) NOT NULL DEFAULT '' COMMENT 'FK = users.company',
  `apply_user_name` varchar(64) NOT NULL DEFAULT '',
  `apply_user_phone` varchar(64) NOT NULL DEFAULT '',
  `apply_date` datetime DEFAULT NULL,
  `audit_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `audit_user_name` varchar(32) NOT NULL DEFAULT '',
  `audit_date` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 => ''待审核'',  1 => ''驳回'', 2 => ''开通'', 3 => ''暂停'', 4=>''删除''',
  `stop_date` datetime DEFAULT NULL,
  `avg_cnt` int(16) unsigned NOT NULL DEFAULT '0',
  `app_no` int(16) unsigned NOT NULL DEFAULT '0' COMMENT 'useless',
  `emergent_user_email` varchar(128) NOT NULL DEFAULT '',
  `emergent_user_name` varchar(32) NOT NULL DEFAULT '',
  `emergent_user_phone` varchar(128) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `pj_name` (`pj_name`),
  UNIQUE KEY `project_no` (`project_no`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `project_list` */

insert  into `project_list`(`id`,`project_no`,`pj_name`,`pj_bill_type`,`pj_fee`,`isp_type`,`pj_start`,`pj_end`,`industry_type`,`mo_mt_type`,`pj_desc`,`pj_restrict`,`pj_memo`,`apply_user_id`,`apply_company`,`apply_user_name`,`apply_user_phone`,`apply_date`,`audit_user_id`,`audit_user_name`,`audit_date`,`status`,`stop_date`,`avg_cnt`,`app_no`,`emergent_user_email`,`emergent_user_name`,`emergent_user_phone`) values (4,98015,'测试公司-测试业务-3','POST',75,'CMCUCT','2013-04-03 00:00:00','2013-05-08 00:00:00','ECOM','MT','desc-3','127.0.0.5','meme-5',1,'test.company.1','内部用户-1','13800138001','2013-05-20 19:02:12',1,'内部用户-1','2013-05-21 19:40:47',2,NULL,200003,0,'test3@163.com','紧急姓名-3','13800138003'),(5,98013,'测试公司-测试业务-4','PRE',31,'CMCUCT','2013-04-08 00:00:00','2018-07-09 00:00:00','','MT','desc-4','122.0.67.155;127.0.0.1','memo-4-3',1,'test.company.1','内部用户-1','13800138001','2013-05-21 12:26:56',1,'内部用户-1','2013-05-21 15:59:37',2,NULL,200004,0,'test4@163.com','紧急姓名-4','13800138004'),(7,98016,'外部测试公司-测试业务-6','PRE',41,'CMCUCT','2013-06-27 00:00:00','2015-05-27 00:00:00','ECOM','MOMT','6','6','6',2,'test.company.2','测试群发外部用户-1','13800138002','2013-05-22 09:20:06',1,'内部用户-1','2013-05-22 09:26:14',2,NULL,30,0,'6','6','6'),(8,98017,'外部测试公司-测试业务-7','PRE',77,'CT','2013-05-30 00:00:00','2015-04-27 00:00:00','ECOM','MOMT','7','7','7',2,'test.company.2','测试群发外部用户-1','13800138002','2013-05-22 12:11:45',1,'内部用户-1','2013-05-22 12:14:16',2,NULL,77777,0,'7','7','7'),(9,98018,'外部测试公司-测试业务-8','PRE',80,'CU','2013-09-26 00:00:00','2015-06-28 00:00:00','IT','MOMT','88','88','88',2,'test.company.2','测试群发外部用户-1','13800138002','2013-05-22 12:45:35',1,'内部用户-1','2013-05-22 12:46:44',4,NULL,8888,0,'88','88','88'),(10,0,'外部测试公司-测试业务-9','PRE',0,'CMCUCT','2013-05-27 00:00:00','2016-04-28 00:00:00','ANY','MOMT','99','99','99',2,'test.company.2','测试群发外部用户-1','13800138002','2013-05-22 12:50:58',0,'',NULL,4,NULL,9999,0,'99','99','99');

/*Table structure for table `resources` */

DROP TABLE IF EXISTS `resources`;

CREATE TABLE `resources` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `res_name` varchar(64) NOT NULL DEFAULT '',
  `class` varchar(32) NOT NULL DEFAULT '',
  `method` varchar(32) NOT NULL DEFAULT '',
  `ext` varchar(5) NOT NULL DEFAULT '',
  `res_desc` varchar(128) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `res_name` (`res_name`),
  UNIQUE KEY `class_method_ext` (`class`,`method`,`ext`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `resources` */

/*Table structure for table `role_resource_map` */

DROP TABLE IF EXISTS `role_resource_map`;

CREATE TABLE `role_resource_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) NOT NULL DEFAULT '0',
  `res_id` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_res` (`role_id`,`res_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `role_resource_map` */

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '1=admin',
  `role_name` varchar(64) NOT NULL DEFAULT '',
  `role_desc` varchar(128) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_name` (`role_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `roles` */

insert  into `roles`(`id`,`role_name`,`role_desc`,`status`) values (1,'系统管理员','拥有所有权限',1),(2,'公司内部用户','内部用户',1),(3,'群发外部用户','外部',1);

/*Table structure for table `task_list` */

DROP TABLE IF EXISTS `task_list`;

CREATE TABLE `task_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_no` int(10) unsigned NOT NULL DEFAULT '0',
  `task_name` varchar(255) NOT NULL DEFAULT '',
  `task_popular` varchar(255) NOT NULL DEFAULT '',
  `app_date` datetime DEFAULT NULL,
  `task_type` tinyint(3) unsigned DEFAULT NULL,
  `task_msg` varchar(255) DEFAULT NULL,
  `task_start` datetime DEFAULT NULL,
  `task_end` datetime DEFAULT NULL,
  `status` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `task_name` (`task_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `task_list` */

/*Table structure for table `tmp_users` */

DROP TABLE IF EXISTS `tmp_users`;

CREATE TABLE `tmp_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_name` varchar(30) DEFAULT NULL,
  `app_dept` varchar(255) DEFAULT NULL,
  `app_phone` varchar(20) DEFAULT NULL,
  `app_ext` varchar(6) DEFAULT NULL,
  `app_email` varchar(255) DEFAULT NULL,
  `add_app_name` varchar(255) DEFAULT NULL,
  `add_app_phone` varchar(12) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `tmp_users` */

/*Table structure for table `user_charge` */

DROP TABLE IF EXISTS `user_charge`;

CREATE TABLE `user_charge` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_name` varchar(32) NOT NULL DEFAULT '',
  `operator_id` int(10) unsigned NOT NULL DEFAULT '0',
  `operator_name` varchar(32) NOT NULL DEFAULT '',
  `charge_date` datetime DEFAULT NULL,
  `charge_fee` int(16) unsigned NOT NULL DEFAULT '0',
  `pre_account_fee` int(16) unsigned NOT NULL DEFAULT '0',
  `post_account_fee` int(16) unsigned NOT NULL DEFAULT '0',
  `total_account_fee` int(16) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `charge_date` (`charge_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `user_charge` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL DEFAULT '',
  `password` varchar(256) NOT NULL DEFAULT '',
  `role_id` int(10) unsigned NOT NULL DEFAULT '0',
  `create_date` datetime DEFAULT NULL,
  `lastvisit_date` datetime DEFAULT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1=admin',
  `pre_account_fee` int(16) unsigned NOT NULL DEFAULT '0',
  `post_account_fee` int(16) unsigned NOT NULL DEFAULT '0',
  `email` varchar(64) NOT NULL DEFAULT '',
  `app_name` varchar(64) NOT NULL DEFAULT '' COMMENT 'user''s real chiness name',
  `company` varchar(128) NOT NULL DEFAULT '',
  `app_phone` varchar(64) NOT NULL DEFAULT '',
  `app_ext` varchar(64) NOT NULL DEFAULT '',
  `add_app_name` varchar(64) NOT NULL DEFAULT '',
  `add_app_phone` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `users` */

insert  into `users`(`id`,`username`,`password`,`role_id`,`create_date`,`lastvisit_date`,`status`,`pre_account_fee`,`post_account_fee`,`email`,`app_name`,`company`,`app_phone`,`app_ext`,`add_app_name`,`add_app_phone`) values (1,'admin','117b4d6e68ccd03dbd6533f54f25e8a1',1,'2010-10-11 10:09:55','2013-05-28 08:33:45',1,10000,0,'','内部用户-1','test.company.1','13800138001','pwd=TestName01','',''),(2,'groupsend','117b4d6e68ccd03dbd6533f54f25e8a1',3,'2010-10-11 10:09:55','2013-05-26 21:10:57',1,0,0,'','测试群发外部用户-1','test.company.2','13800138002','pwd=TestName01','',''),(3,'groupsend2','117b4d6e68ccd03dbd6533f54f25e8a1',3,'2013-05-26 23:47:59','2013-05-27 08:52:28',1,0,0,'test@123.com','测试群发用户2','test-company','13900139000','010-12345678','name-1','13900139001');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
