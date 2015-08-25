<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/big_css.css">

<body>
<div id="body_box_r_tit" class="cu">密码修改</div>
<?php echo empty($errors) ? '' : "<font color=\"red\">提示信息:<br>$errors</font>"?>
<form name="uptpwd" action="" method="post">
<div id="body_box_r_text">
<table width="755" border="0" cellspacing="0" cellpadding="0" class="inputbiaodan">
    <tr>
      <td width="132" align="right">账户名：</td>
      <td width="623" class="red"><?php echo $user_info['app_name'];?></td>
    </tr>
     <tr>
      <td align="right">原始密码：</td>
      <td><input type="password" name="oldpwd" id="oldpwd" />      </td>
    </tr>
    <tr>
      <td align="right">新密码：</td>
      <td><input type="password" name="newpwd" id="newpwd" />      </td>
    </tr>
     <tr>
      <td align="right">确认新密码：</td>
      <td><input type="password" name="repwd" id="repwd" />      </td>
    </tr>
  </table>
</div>  
  <div id="body_box_r_btn"><input type="submit" value=" 确认" />
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <input type="reset" value=" 取消 " />
	</div>
</form>
</body>
</html>