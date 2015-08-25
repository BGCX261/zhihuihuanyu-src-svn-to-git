<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/big_css.css">
<script type="text/javascript" src="<?php echo base_url()?>js/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/common.js"></script>
<style>.inputbiaodan td{padding:0 5px}</style>
</head>
<body>
	
<form name="add_form" id="add_form" method="post" action="<?php echo site_url('group_send/add_black'); ?>">
<div id="body_box_r_tit" class="cu">新增黑名单(from为1，表示所有from都过滤)</div>
<div id="body_box_r_text">
	<table width="755" border="0" cellspacing="0" cellpadding="0" class="inputbiaodan">  
	<tr>
		<td width="80" align="left" valign="top">手机号码：</td>
		<td>
      	<input type="text" name="mobile" id="mobile" maxlength="11" value="<?php echo empty($param['mobile'])? '':$param['mobile']; ?>" />
      	</td>

		<td width="80" align="left" valign="top">FROM：</td>
		<td>
      	<input type="text" name="from" id="from" maxlength="8" value="<?php echo empty($param['from'])? '':$param['from']; ?>" />
      	</td>
	</tr>	
	<tr>
	  <td width="80" align="left" valign="top">备注：</td>
	  <td><input width="400" type="text" name="mark" id="mark" maxlength="120" value="<?php echo empty($param['mark'])? '':$param['mark']; ?>" /> </td>
	  <td  align="left" valign="top"><input type="submit" value="新增"/></td>
    </tr>
	</table>	
</div>	
</form>

<form name="search_form" id="search_form" method="post" action="<?php echo site_url('group_send/blacklist'); ?>">
<div id="body_box_r_tit" class="cu">选择搜索条件</div>
<div id="body_box_r_text">
	<table width="755" border="0" cellspacing="0" cellpadding="0" class="inputbiaodan">  
	<tr>
      <td width="200" align="right" valign="top">手机号码：</td>
      <td>
      	<input type="text" name="phone" id="phone" maxlength="11" value="<?php echo empty($phone)? '':$phone; ?>" />
      </td>    
	  <td  width="550" align="left" valign="top"><input type="submit" value="搜索"/></td>
    </tr>
  </table>
</div>

<div id="body_box_r_tit" class="cu">搜索结果(from为1，表示所有from都过滤)</div>
<div id="body_box_r_text_result">
<table width="755" border="0" cellspacing="0" cellpadding="0" class="inputbiaodan">
    <tr>
      <td colspan="2" >
      	<table width="732" border="0" cellspacing="0" cellpadding="0" class="biaoge">
        <tr>
		<td width="60" align="center"  bgcolor="#EFEFEF">手机号码</td>
        <td width="60" align="center"  bgcolor="#EFEFEF">FROM</td>
        <td width="60" align="center"  bgcolor="#EFEFEF">设置人</td>
        <td width="542" align="center"  bgcolor="#EFEFEF">备注</td>
        <td width="40" align="center"  bgcolor="#EFEFEF">操作</td>
        </tr>
		<?php if (!empty($list)) { foreach ($list as $v) { ?>
		<tr>
          <td align="center" valign="middle"><?php echo $v['mobile']; ?></td>
          <td align="center" valign="middle"><?php echo $v['msgfrom']; ?></td>
          <td align="center" valign="middle"><?php echo $v['user']; ?></td>
          <td align="center" valign="middle"><?php echo $v['mark']; ?></td>
          <td align="center" valign="middle">
          	<a href="<?php 	  
				echo site_url('group_send/del_black/'.$v['mobile'].'/'.$v['msgfrom']) ;
		  	?>"
		  	onclick="if(confirm('确实要删除此条记录吗？')) return true;else return false;">删除</a></td>
        </tr>
		<?php } } ?>
		</table>
	  </td>
	</tr>  
</table>
</div>

</form>
<script>
try{
	document.getElementById('searchByPage').onclick = searchSubmit;
}catch(e){
	
}
function searchSubmit()
{
	document.getElementById('search_form').submit();
}
</script>
</body>
</html>