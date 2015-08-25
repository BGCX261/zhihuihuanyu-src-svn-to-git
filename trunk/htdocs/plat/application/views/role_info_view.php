<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/big_css.css">

<body>
<div id="body_box_r_tit" class="cu">新增角色</div>
<form name="role_form" method="POST" action="<?php echo $setting['post_url']; ?>">
<input type="hidden" name="isPost" value="TRUE">
<div id="body_box_r_text"><table width="755" border="0" cellspacing="0" cellpadding="0" class="inputbiaodan">
    <tr>
      <td width="213" align="right">角色名称</td>
		<td width="300">
	 
        <input name="name" type="text" id="name" size="30" value="<?php echo empty($role['role_name'])?'':$role['role_name']; ?>" />
      </td>
    </tr>
	<tr>
      <td width="213" align="right">角色描述</td>
		<td width="300">
	 
        <input name="desc" type="text" id="desc" size="30" value="<?php echo empty($role['role_desc'])?'':$role['role_desc']; ?>" />
      </td>
    </tr>
  </table>
</div>
  <div id="body_box_r_btn"><input type="submit" value=" <?php echo $setting['button_name'] ?> " />&nbsp;</div>
 </form>
</body>
</html>