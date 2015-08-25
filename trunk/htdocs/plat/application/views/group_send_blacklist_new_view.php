<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/big_css.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/thickbox.css">
<script type="text/javascript" src="<?php echo base_url()?>js/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/thickbox_plus.js"></script>

<body>
<div id="body_box_r_tit" class="cu">黑名单群添加</div>
<form name="group_send_info" method="POST" enctype='multipart/form-data' action="<?php echo site_url('group_send/group_send_blacklist_new'); ?>">
<input type="hidden" name="isPost" value="TRUE">
<div id="body_box_r_text"><table width="755" border="0" cellspacing="0" cellpadding="0" class="inputbiaodan">

    
    <tr>
      <td align="left">输入手机号码、from值以及备注(用空格隔开)<br>每条消息之间用回车隔开</td>
      <td width="450" colspan="2"><textarea name="input_phone" type="text" id="input_phone" rows="20" cols="40"/><?php 
		if (!empty($pj_list['phone_list'])) 
		{
			if ($pj_list['phone_list'][0] === 'M')
			{
				echo "";
			}
			else
			{
				echo $pj_list['phone_list']; 
			}
		}
		else 
			echo ""; 
      ?></textarea></td>
    </tr>
         <tr>
      <td align="right">批量导入号码(优先文件方式提交的号码,最多30万)：</td>
      <td colspan="2"><input type="file" name="upload_phone" id="upload_phone" value=" 批量添加 " /><?php 
		if (!empty($pj_list['phone_list'])) 
		{
			if ($pj_list['phone_list'][0] === 'M')
			{
				echo "号码文件已填加";
			}
			else
			{
				echo "";
			}
		}
		else 
			echo ""; 
      ?></td>
      
    </tr> 


  </table>
</div>
  <div id="body_box_r_btn"><input type="submit" value="新增" />&nbsp;</div>
 </form>
</body>
</html>