<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/big_css.css">

<body>
<div id="body_box_r_tit" class="cu"><?php echo $action['name']?></div>
<form name="group_send_info" method="POST" enctype='multipart/form-data' action="<?php echo $action['value'] ?>">
<?php echo isset($upload_file_error)?$upload_file_error:'';?>
<input type="hidden" name="isPost" value="TRUE">
<div id="body_box_r_text"><table width="755" border="0" cellspacing="0" cellpadding="0" class="inputbiaodan">
    <tr>
      <td align="right">群发名称：</td>
      <td><input name="gsend_name" type="text" id="gsend_name" size="30" value="<?php echo empty($gsend_list['gsend_name'])?'':$gsend_list['gsend_name'];?>" /><font color="red"><b>本项必填</b></font></td>
    </tr>
    <tr>
      <td align="right">本次群发属于这个项目：</td>
      <td colspan="2"><select name="project_no"><?php foreach($total_pj as $k => $v) { if ($gsend_list['project_no'] != $v['project_no']) { echo '<option value="'.$v['project_no'].'">'.$v['pj_name'].'</option>'; } else echo '<option value="'.$v['project_no'].'" selected>'.$v['pj_name'].'</option>'; } ?></select></td>
    </tr>
    <tr>
      <td align="right">下发时间：</td>
      <td>
      	<select name="sendtime_y"><?php for($i=2013; $i<2021; $i++) { if (date('Y',strtotime($gsend_list['mt_date'])) != $i) echo '<option value="'.$i.'">'.$i.'年</option>'; else echo '<option value="'.$i.'" selected>'.$i.'年</option>';} ?></select> 
      	<select name="sendtime_m"><?php for ($i=1; $i<13; $i++) { if (date('n',strtotime($gsend_list['mt_date'])) != $i) echo '<option value="'.$i.'">'.$i.'月</option>'; else echo '<option value="'.$i.'" selected>'.$i.'月</option>';} ?></select> 
      	<select name="sendtime_d"><?php for ($i=1; $i<32; $i++){ if (date('j',strtotime($gsend_list['mt_date'])) != $i) echo '<option value="'.$i.'">'.$i.'日</option>'; else echo '<option value="'.$i.'" selected>'.$i.'日</option>';} ?></select> 
      	<select name="sendtime_h"><?php for ($i=0; $i<24; $i++) { if (date('G',strtotime($gsend_list['mt_date'])) != $i) echo '<option value="'.$i.'">'.$i.'时</option>'; else echo '<option value="'.$i.'" selected>'.$i.'时</option>';} ?></select> 
      	<select name="sendtime_i"><?php for ($i=0; $i<60; $i++) { if (date('i',strtotime($gsend_list['mt_date'])) != $i) echo '<option value="'.$i.'">'.$i.'分</option>'; else echo '<option value="'.$i.'" selected>'.$i.'分</option>';} ?></select></td>
    </tr>
    <tr>
    <!-- <tr>
      <td align="right">输入手机号码(最多100个)：</td>
      <td width="263" colspan="2"><textarea name="input_phone" type="text" id="input_phone" rows="10" cols="12"/><?php 
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
     -->
     
    <tr>
      <td align="right">批量导入号码(最多30万)：</td>
      <td colspan="2"><input type="file" name="upload_phone" id="upload_phone"/></td>
    </tr>
     
    <tr>
      <td align="right" valign="top">短信内容：</td>
      <td><textarea name="sms_msg" cols="40" rows="6" id="sms_msg"><?php echo empty($gsend_list['sms_msg'])?'':$gsend_list['sms_msg'];?></textarea></td>
    </tr>
  </table>
</div>
  <div id="body_box_r_btn"><input type="submit" value=" <?php echo $action['name']?> " />&nbsp;</div>
 </form>
</body>
</html>