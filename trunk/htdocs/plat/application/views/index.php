<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/login_css.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/thickbox.css">
<script type="text/javascript" src="<?php echo base_url()?>js/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/thickbox_plus.js"></script>

<title><?php echo $title; ?></title>
</head>

<body>
<div id="login">
<form name="login" method="POST" action="<?php echo site_url('welcome/login'); ?>">
<div id="login_login">
<font color="red" align="middle"><?php echo empty($errors)?'':$errors; ?></font>
<div id="login_input">
<div id="login_input_l">账号 ：</div>
<div id="login_input_r">
  <input name="username" type="text" id="username" size="29" />
</div>
</div>
<div id="login_input">
<div id="login_input_l">密码 ：</div>
<div id="login_input_r">
  
  <input name="password" type="password" id="password" size="29" />
  
</div>
</div>
<!--<div id="login_input">
<div id="login_input_l">验证码 ：</div>
<div id="login_input_yz">
  
  <input name="validcode" type="text" id="validcode" size="8" />
  
</div>
<div id="login_input_yz"><img src="<?php echo base_url()?>images/login_yz.gif" width="68" height="28" /></div>
</div>-->
<div id="login_input">
<div id="login_input_btn">
<input name="" type="image" src="<?php echo base_url()?>images/login_btn.gif" />
<!--&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo site_url('welcome/apply_for_permission'); ?>?height=150&width=650" class="thickbox">申请权限</a>-->
</div>
</div>
</div>
<div id="login_bg"></div>
</form>
</div>
</body>
</html>