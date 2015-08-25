/* 一个用户 -- 多个项目。一旦确定了某个项目，则确定了是先付费、后付费。即：提交群发号码txt，还是用接口群发。*/
/* 				一个项目 -- 可以组合使用多个通道，完成对cm、cu、ct用户的下发。参考 map_project_channel。但是目前只支持一个项目使用一个通道。*/
/* 				一个项目 -- 多次群发。*/
/* 							一次群发 -- 一个账单。*/
/* 代发用户 -- 目前，属于管理员权限。也跟普通用户一样，申请项目，申请群发，等等。只不过是管理员自己申请，自己审批。*/

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
  `gsendid` int(10) unsigned NOT NULL DEFAULT '0',
  `gsend_name` varchar(64) NOT NULL DEFAULT '',
  `pj_id` int(10) unsigned NOT NULL DEFAULT '0',
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
  KEY `gsendid` (`gsendid`),
  KEY `project_no` (`project_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `bill` */

/*Table structure for table `bill_detail` */

DROP TABLE IF EXISTS `bill_detail`;

CREATE TABLE `bill_detail` (
  `detail_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bill_id` int(10) unsigned NOT NULL DEFAULT '0',
  `map_pj_chnl_id` int(10) unsigned NOT NULL DEFAULT '0',
  `gateway` int(16) unsigned NOT NULL DEFAULT '0',
  `isp_type` varchar(8) NOT NULL DEFAULT '' COMMENT 'CM or CU or CT',
  `channel_price` int(16) unsigned NOT NULL DEFAULT '0',
  `to_isp_cnt` int(16) unsigned NOT NULL DEFAULT '0',
  `mr_ok_cnt` int(16) unsigned NOT NULL DEFAULT '0',
  `succ_rate` int(16) unsigned NOT NULL DEFAULT '0',
  `our_cost` int(16) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`detail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `bill_detail` */

/*Table structure for table `channel_list` */

DROP TABLE IF EXISTS `channel_list`;

CREATE TABLE `channel_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `channel_name` varchar(128) NOT NULL DEFAULT '',
  `company_name` varchar(128) NOT NULL DEFAULT '',
  `channel_price` int(16) unsigned NOT NULL DEFAULT '0',
  `gateway` int(16) unsigned NOT NULL DEFAULT '0',
  `longcode` varchar(32) NOT NULL DEFAULT '',
  `mo_mt_type` varchar(16) NOT NULL DEFAULT 'MOMT',
  `isp_type` varchar(16) NOT NULL DEFAULT 'CMCUCT',
  `contract_begin` datetime DEFAULT NULL,
  `contract_end` datetime DEFAULT NULL,
  `project_no` int(10) unsigned NOT NULL DEFAULT '0',
  `have_contract` tinyint(1) NOT NULL DEFAULT '1',
  `memo` varchar(256) NOT NULL DEFAULT '',
  `app_no` int(16) unsigned NOT NULL DEFAULT '0',
  `fin_code` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `channel_name` (`channel_name`),
  UNIQUE KEY `gateway` (`gateway`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `channel_list` */

/*Table structure for table `group_send_list` */

DROP TABLE IF EXISTS `group_send_list`;

CREATE TABLE `group_send_list` (
  `gsendid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gsend_name` varchar(64) NOT NULL DEFAULT '',
  `pj_id` int(10) unsigned NOT NULL DEFAULT '0',
  `project_no` int(10) unsigned NOT NULL DEFAULT '0',
  `pj_name` varchar(64) NOT NULL DEFAULT '',
  `sms_msg` varchar(256) NOT NULL DEFAULT '',
  `phone_list` mediumtext NOT NULL,
  `upload_cnt` int(16) unsigned NOT NULL DEFAULT '0',
  `app_date` datetime DEFAULT NULL COMMENT 'mt time user defined.',
  `apply_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `apply_user_name` varchar(32) NOT NULL DEFAULT '',
  `apply_date` datetime DEFAULT NULL,
  `audit_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `audit_user_name` varchar(32) NOT NULL DEFAULT '',
  `audit_date` datetime DEFAULT NULL,
  `gs_start` datetime DEFAULT NULL,
  `gs_end` datetime DEFAULT NULL,
  `status` varchar(32) NOT NULL DEFAULT 'NOT.AUDIT' COMMENT 'BEGIN, SENDING, END, NOT.AUDIT, AUDIT, AUDIT.FAILED',
  PRIMARY KEY (`gsendid`),
  KEY `project_no` (`project_no`),
  KEY `apply_user_id` (`apply_user_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `group_send_list` */

/*Table structure for table `map_project_channel` */

DROP TABLE IF EXISTS `map_project_channel`;

CREATE TABLE `map_project_channel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pj_id` int(10) NOT NULL DEFAULT '0',
  `project_no` int(10) NOT NULL DEFAULT '0',
  `channel_id` int(10) NOT NULL DEFAULT '0',
  `gateway` int(16) NOT NULL DEFAULT '0',
  `isp_type` varchar(8) NOT NULL DEFAULT '' COMMENT 'CM or CU or CT',
  `channel_percent` int(10) NOT NULL DEFAULT '100',
  PRIMARY KEY (`id`),
  KEY `project_no` (`project_no`),
  KEY `gateway` (`gateway`),
  KEY `channel_id` (`channel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `map_project_channel` */

/*Table structure for table `project_list` */

DROP TABLE IF EXISTS `project_list`;

CREATE TABLE `project_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_no` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'that is from.',
  `pj_name` varchar(64) NOT NULL DEFAULT '',
  `pj_bill_type` varchar(16) NOT NULL DEFAULT 'PRE' COMMENT 'PRE or POST',
  `pj_fee` int(16) unsigned NOT NULL DEFAULT '0',
  `longcode` varchar(32) NOT NULL DEFAULT '',
  `pj_start` datetime DEFAULT NULL,
  `pj_end` datetime DEFAULT NULL,
  `industry_type` varchar(32) NOT NULL DEFAULT '',
  `upline_date` datetime DEFAULT NULL,
  `pj_desc` varchar(256) NOT NULL DEFAULT '',
  `pj_popluar` varchar(256) NOT NULL DEFAULT '',
  `pj_restrict` varchar(256) NOT NULL DEFAULT '',
  `pj_msg` varchar(256) NOT NULL DEFAULT '',
  `pj_memo` varchar(256) NOT NULL DEFAULT '',
  `apply_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `apply_user_name` varchar(32) NOT NULL DEFAULT '',
  `apply_date` datetime DEFAULT NULL,
  `audit_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `audit_user_name` varchar(32) NOT NULL DEFAULT '',
  `audit_date` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=invalid',
  `stop_date` datetime DEFAULT NULL,
  `avg_cnt` int(16) unsigned NOT NULL DEFAULT '0',
  `pj_type` varchar(16) NOT NULL DEFAULT '' COMMENT 'useless, mo or mt?',
  `app_no` int(16) unsigned NOT NULL DEFAULT '0' COMMENT 'useless',
  `app_name` varchar(64) NOT NULL DEFAULT '' COMMENT 'apply_user_name?',
  `app_dept` varchar(128) NOT NULL DEFAULT '',
  `app_phone` varchar(128) NOT NULL DEFAULT '',
  `app_ext` varchar(128) NOT NULL DEFAULT '',
  `app_email` varchar(128) NOT NULL DEFAULT '',
  `add_app_name` varchar(32) NOT NULL DEFAULT '',
  `add_app_phone` varchar(128) NOT NULL DEFAULT '',
  `app_date` datetime DEFAULT NULL,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `apv_user` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `pj_name` (`pj_name`),
  UNIQUE KEY `project_no` (`project_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `project_list` */

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
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(64) NOT NULL DEFAULT '',
  `role_desc` varchar(128) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_name` (`role_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `roles` */

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
/*充值流程：先抵消后付费账户消费额，使后付费账户值变成0，再充值到先付费账户中，使先付费账户值变>0. */
/*充值操作员、充值时间、充值金额、充值完成后的先后付费账户数值 */

CREATE TABLE `user_charge` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0', /* 充值人id */
  `user_name` varchar(32) NOT NULL DEFAULT '',
  `operator_id` int(10) unsigned NOT NULL DEFAULT '0', /* 充值操作员id */
  `operator_name` varchar(32) NOT NULL DEFAULT '',
  `charge_date` datetime DEFAULT NULL,
  `charge_fee` int(16) unsigned NOT NULL DEFAULT '0', /* 本次充值的金额。精确到厘。*/
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
  `role_id` int(10) NOT NULL DEFAULT '0',
  `create_date` datetime DEFAULT NULL,
  `lastvisit_date` datetime DEFAULT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pre_account_fee` int(16) unsigned NOT NULL DEFAULT '0', /*先付费用户的账户总余额*/
  `post_account_fee` int(16) unsigned NOT NULL DEFAULT '0',  /* 后付费用户累计的消费总额*/
  `email` varchar(64) NOT NULL DEFAULT '',
  `app_name` varchar(128) NOT NULL DEFAULT '',
  `app_dept` varchar(128) NOT NULL DEFAULT '',
  `app_phone` varchar(128) NOT NULL DEFAULT '',
  `app_ext` varchar(128) NOT NULL DEFAULT '',
  `add_app_name` varchar(128) NOT NULL DEFAULT '',
  `add_app_phone` varchar(128) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `users` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
