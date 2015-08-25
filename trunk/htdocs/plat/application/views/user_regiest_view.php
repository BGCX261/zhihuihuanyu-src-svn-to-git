<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>申请用户</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/big_css.css">
</head>

<body>
<div id="body_box_r_tit" class="cu">申请用户</div>
<form name="user" method="POST" action="<?php echo site_url('welcome/regiest') ?>">
<input type="hidden" name="isPost" value="TRUE">
<div id="body_box_r_text">
  <table width="80%" border="0" cellspacing="0" cellpadding="0" class="inputbiaodan">
    <tr>
      <td width="211" align="right">姓 名：</td>
      <td width="544">
        <input name="name" type="text" id="name" size="30" value="" />&nbsp;&nbsp;&nbsp;[必填]
      </td>
    </tr>
    <tr>
      <td align="right">部 门：</td>
      <td><input name="dept" type="text" id="dept" size="30" value="" />&nbsp;&nbsp;&nbsp;[必填]</td>
    </tr>
    <tr>
      <td align="right">手 机：</td>
      <td><input name="phone" type="text" id="phone" size="30" value="" />&nbsp;&nbsp;&nbsp;[必填]</td>
    </tr>
    <tr>
      <td align="right">分 机：</td>
      <td><input name="ext" type="text" id="ext" size="30" value="" />&nbsp;&nbsp;&nbsp;[必填]</td>
    </tr>
    <tr>
      <td align="right">邮 箱：</td>
      <td><input name="email" type="text" id="email" size="30" value="" />&nbsp;&nbsp;&nbsp;[必填]</td>
    </tr>
    <tr>
      <td align="right">紧急联系人姓名：</td>
      <td><input name="add_name" type="text" id="add_name" size="30" value="" /></td>
    </tr>
     <tr>
      <td align="right">紧急联系人手机：</td>
      <td><input name="add_phone" type="text" id="add_phone" size="30" value="" /></td>
    </tr>
	<tr>
	 <td align="right"><input type="submit" value=" 提交 " /></td>
	  <td><input name="" type="reset" value=" 清空重填 " /></td>
	</tr>
  </table>
</div>
</form>
</body>
</html>