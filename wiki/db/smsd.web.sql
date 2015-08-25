CREATE TABLE mbcookie (
  mobile varchar(11) NOT NULL default '',
  ck_name varchar(31) NOT NULL default '',
  ck_value varchar(255) NOT NULL default '',
  ck_set_time datetime NOT NULL default '0000-00-00 00:00:00',
  UNIQUE KEY mobile (mobile,ck_name),
  KEY mobile_2 (mobile),
  KEY ck_name (ck_name)
) TYPE=MyISAM;

CREATE TABLE sw_cmd (
  c_id int(11) NOT NULL auto_increment,
  c_pid int(11) NOT NULL default '0',
  c_up_receiver varchar(31) NOT NULL default '',
  c_title varchar(255) NOT NULL default '',
  c_up_command varchar(255) NOT NULL default '',
  c_type enum('autotext','rawtext','script','program','php','pl','cgi') default NULL,
  c_content longtext,
  c_who varchar(31) NOT NULL default '',
  c_when datetime NOT NULL default '0000-00-00 00:00:00',
  c_where varchar(16) NOT NULL default '',
  c_flag int(11) NOT NULL default '0',
  c_owner varchar(31) NOT NULL default '',
  c_priority tinyint(1) default '1',
  PRIMARY KEY  (c_id),
  UNIQUE KEY c_up_receiver (c_up_receiver,c_up_command)
) TYPE=MyISAM;


CREATE TABLE sw_log (
  l_id int(11) NOT NULL auto_increment,
  l_type enum('MODIFY','REMOVE') NOT NULL default 'MODIFY',
  l_who varchar(31) NOT NULL default '',
  l_when datetime NOT NULL default '0000-00-00 00:00:00',
  l_where varchar(16) NOT NULL default '',
  c_id int(11) NOT NULL default '0',
  c_up_receiver varchar(31) NOT NULL default '',
  c_up_command varchar(255) NOT NULL default '',
  c_title varchar(255) NOT NULL default '',
  c_type enum('autotext','rawtext','script','program','php','pl','cgi') default NULL,
  c_content longtext,
  c_priority tinyint(1) default '1',
  PRIMARY KEY  (l_id),
  KEY c_id (c_id),
  KEY l_when (l_when),
  KEY l_type (l_type)
) TYPE=MyISAM;

CREATE TABLE sw_user (
  u_id int(11) NOT NULL auto_increment,
  u_account varchar(31) NOT NULL default '',
  u_password varchar(41) NOT NULL default '',
  u_power varchar(15) NOT NULL default '',
  u_realname varchar(15) NOT NULL default '',
  u_phone varchar(31) NOT NULL default '',
  u_email varchar(127) NOT NULL default '',
  u_remark varchar(255) NOT NULL default '',
  u_enable int(11) NOT NULL default '0',
  PRIMARY KEY  (u_id),
  UNIQUE KEY u_account (u_account)
) TYPE=MyISAM;
grant select,insert,update,delete on smsd_10628888_7.* to smsd1062@localhost IDENTIFIED BY "4w0Jj4wR";
// 添加管理员的权限
insert into sw_user(u_account,u_password,u_power,u_enable) values('admin','6a43c9212fa5166e','ADEV',1);
密码为：1q@W#E$R

// 因mysql的password功能，高版本的mysql用这个：
insert into sw_user(u_account,u_password,u_power,u_enable) values('admin','*C8E2E6CB99F843B824E0DF1A47F7C72A38A6EB9F','ADEV',1);
