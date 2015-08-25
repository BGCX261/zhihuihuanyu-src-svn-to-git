<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/big_css.css">

<body>
<div id="body_box_r_tit" class="cu">账户信息</div>
<div id="body_box_r_text">
<form name="user_add" method="POST" action="<?php echo $action['value'] ?>">
<input type="hidden" name="isPost" value="TRUE">
<table width="755" border="0" cellspacing="0" cellpadding="0" class="inputbiaodan">
	<?php if ($this->access->is_admin()): ?>
     <tr>
      <td width="132" align="right">用户名：</td>
      <td width="623"><input type="text" name="username" id="username" value="<?php echo empty($user_info['username'])?'':$user_info['username'];?>" />      </td>
    </tr>
    <tr>
      <td width="132" align="right">密码：</td>
      <td><input type="password" name="pwd" id="pwd" value="" />      </td>
    </tr>
    <tr>
      <td width="132" align="right">确认密码：</td>
      <td><input type="password" name="repwd" id="repwd" value="" />      </td>
    </tr>
    <tr>
      <td width="132" align="right">真实姓名：</td>
      <td><input type="text" name="app_name" id="app_name" value="<?php echo empty($user_info['app_name'])?'':$user_info['app_name'];?>" /></td>
    </tr>	
	<?php endif; ?>
    <tr>
      <td width="132" align="right">公司：</td>
      <td><input type="text" name="company" id="company" value="<?php echo empty($user_info['company'])?'':$user_info['company'];?>" /></td>
    </tr>
    <tr>
      <td width="132" align="right">座机：</td>
      <td><input type="text" name="app_ext" id="app_ext" value="<?php echo empty($user_info['app_ext'])?'':$user_info['app_ext'];?>" />      </td>
    </tr>
    <tr>
      <td width="132" align="right">手机：</td>
      <td><input type="text" name="app_phone" id="app_phone" value="<?php echo empty($user_info['app_phone'])?'':$user_info['app_phone'];?>" />      </td>
    </tr>
    <tr>
      <td width="132" align="right">邮箱：</td>
      <td><input type="text" name="email" id="email" value="<?php echo empty($user_info['email'])?'':$user_info['email'];?>" />      </td>
    </tr>
    <tr>
      <td width="132" align="right">紧急联系人姓名：</td>
      <td><input type="text" name="add_app_name" id="add_app_name" value="<?php echo empty($user_info['add_app_name'])?'':$user_info['add_app_name'];?>" />      </td>
    </tr>
    <tr>
      <td width="132" align="right">紧急联系人手机：</td>
      <td><input type="text" name="add_app_phone" id="add_app_phone" value="<?php echo empty($user_info['add_app_phone'])?'':$user_info['add_app_phone'];?>" />      </td>
    </tr>
	<?php if ($this->access->is_admin()): ?>
     <tr>
      <td align="right">账户类型：</td>
      <td><select name="role_id"><?php foreach($rolelist as $k => $v) { if ($k != $user_info['role_id']) echo '<option value="'.$k.'">'.$v.'</option>'; else echo '<option value="'.$k.'" selected>'.$v.'</option>';} ?></select></td>
    </tr>   
	<?php endif; ?>
  </table>
</div>
  
  <div id="body_box_r_btn"><input type="submit" value=" <?php echo $action['name']?> " />
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <input type="reset" value=" 重填 " />
<!--   
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <input type="button" value=" 生成通知邮件 " />
 -->  
  </form>
</div>
</body>
</html>