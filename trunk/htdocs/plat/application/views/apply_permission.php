<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>申请用户</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/big_css.css">
<script type="text/javascript" src="<?php echo base_url()?>js/jquery-1.5.1.min.js"></script>
</head>

<body>
<div id="body_box_r_tit" class="cu">申请用户</div>
<form name="user" method="POST" action="<?php echo site_url('welcome/send_apply_email') ?>">
<input type="hidden" name="isPost" value="TRUE">
<div id="body_box_r_text">
  <table width="80%" border="0" cellspacing="0" cellpadding="0" class="inputbiaodan">
    <tr>
      <td align="right">您的邮箱地址：</td>
      <td><input name="email" type="text" id="email" size="30" value="" />&nbsp;&nbsp;&nbsp;[必填]</td>
    </tr>
	<tr>
	 <td align="right"><input type="submit" value="发送申请表" id="send_email" /></td>
	  <td><input name="" type="reset" value=" 清空重填 " /></td>
	</tr>
  </table>
</div>
</form>
<script>
$(document).ready(function(){
   $('#send_email').click(function(){
        var email=$.trim($('#email').val());
        if(email==""){
            alert("请先将信息填写完整后再点击发送邮件！");
            return false;
            }else{
                alert('邮件已发送，请耐心等待相关审核信息！');
            }    
   });     
});
</script>
</body>
</html>