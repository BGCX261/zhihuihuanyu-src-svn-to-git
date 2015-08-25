<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/big_css.css">

<body>
<div id="body_box_r_tit" class="cu">新增模块</div>
<form name="role_form" method="POST" action="<?php echo $setting['post_url']; ?>">
<input type="hidden" name="isPost" value="TRUE">
<div id="body_box_r_text"><table width="755" border="0" cellspacing="0" cellpadding="0" class="inputbiaodan">
    <tr>
      <td width="213" align="right">模块名称</td>
		<td width="300">
	 
        <input name="name" type="text" id="name" size="30" value="<?php echo empty($role['res_name'])?'':$role['res_name']; ?>" />
      </td>
    </tr>
	<tr>
      <td width="213" align="right">模块类名</td>
		<td width="300">
	 
        <input name="class" type="text" id="class" size="30" value="<?php echo empty($role['class'])?'':$role['class']; ?>" />
      </td>
    </tr>
	<tr>
      <td width="213" align="right">模块方法</td>
		<td width="300">
	 
        <input name="method" type="text" id="method" size="30" value="<?php echo empty($role['method'])?'':$role['method']; ?>" />
      </td>
    </tr>
	<tr>
      <td width="213" align="right">模块扩展名</td>
		<td width="300">
	 
        <input name="ext" type="text" id="ext" size="30" value="<?php echo empty($role['ext'])?'':$role['ext']; ?>" />
      </td>
    </tr>
	<tr>
      <td width="213" align="right">角色描述</td>
		<td width="300">
	 
        <input name="desc" type="text" id="desc" size="30" value="<?php echo empty($role['res_desc'])?'':$role['res_desc']; ?>" />
      </td>
    </tr>
  </table>
</div>
  <div id="body_box_r_btn"><input type="submit" value=" <?php echo $setting['button_name'] ?> " />&nbsp;</div>
 </form>
</body>
</html>