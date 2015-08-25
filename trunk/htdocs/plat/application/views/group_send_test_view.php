<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/big_css.css">

<body>
<div id="body_box_r_tit" class="cu"><?php echo $action['name']?></div>
<form name="group_send_test" method="POST" action="<?php echo $action['value'] ?>">
<input type="hidden" name="isPost" value="TRUE">
<div id="body_box_r_text"><table width="755" border="0" cellspacing="0" cellpadding="0" class="inputbiaodan">
    <tr>
      <td align="right">本次测试属于这个项目：</td>
      <td colspan="2"><select name="project_no"><?php foreach($total_pj as $k => $v) { if ($gsend_list['project_no'] != $v['project_no']) { echo '<option value="'.$v['project_no'].'">'.$v['pj_name'].'</option>'; } else echo '<option value="'.$v['project_no'].'" selected>'.$v['pj_name'].'</option>'; } ?></select></td>
    </tr>
    <tr>
      <td align="right">下发给这个手机号：</td>
      <td><input name="dest_mobile" type="text" id="dest_mobile" size="30" value="" /><font color="red"><b>本项必填</b></font></td>
    </tr>
    <tr>
      <td align="right" valign="top">短信内容：</td>
      <td><textarea name="sms_msg" cols="40" rows="6" id="sms_msg"></textarea></td>
    </tr>
  </table>
</div>
  <div id="body_box_r_btn"><input type="submit" value=" <?php echo $action['name']?> " />&nbsp;</div>
 </form>
</body>
</html>