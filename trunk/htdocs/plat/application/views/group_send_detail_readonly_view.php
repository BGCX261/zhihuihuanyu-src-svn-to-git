<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/big_css.css">

<body>
<div id="body_box_r_tit" class="cu"><?php echo $action['title']?></div>
<form name="group_send_detail_info" method="POST" action="<?php echo $action['value'] ?>">
<input type="hidden" name="isPost" value="TRUE">
<div id="body_box_r_text"><table width="755" border="0" cellspacing="0" cellpadding="0" class="inputbiaodan">
    <tr>
      <td width="132" align="right">群发ID：</td><td colspan="2"><?php if (!empty($gsend_info['gsend_id'])) echo $gsend_info['gsend_id']; else echo ''; ?></td>
    </tr>
    <tr>
      <td width="132" align="right">群发时间：</td><td colspan="2"><?php if (!empty($gsend_info['mt_date'])) echo $gsend_info['mt_date']; else echo '未设置'; ?></td>
    </tr>
    <tr>
      <td width="132" align="right">状态：</td><td colspan="2"><?php echo $gs_status_list[$gsend_info['gs_status']];?></td>
    </tr>
    <tr>
      <td width="132" align="right">群发名称：</td><td colspan="2"><?php if (!empty($gsend_info['gsend_name'])) echo $gsend_info['gsend_name']; else echo '未设置'; ?></td>
    </tr>
    <tr>
      <td width="132" align="right">所在项目：</td><td colspan="2"><?php if (!empty($gsend_info['pj_name'])) echo $gsend_info['pj_name']; else echo '未设置'; ?></td>
    </tr>
    <tr>
      <td width="132" align="right">项目From：</td><td colspan="2"><?php if (!empty($gsend_info['project_no'])) echo $gsend_info['project_no']; else echo '未设置'; ?></td>
    </tr>
    <tr>
      <td width="132" align="right">下发内容：</td><td colspan="2"><?php if (!empty($gsend_info['sms_msg'])) echo $gsend_info['sms_msg']; else echo '未设置'; ?></td>
    </tr>
    <tr>
      <td width="132" align="right">上传文件名：</td><td colspan="2"><?php if (!empty($gsend_info['upload_client_filename'])) echo $gsend_info['upload_client_filename']; else echo '未设置'; ?></td>
    </tr>
    <tr>
      <td width="132" align="right">申请人：</td><td colspan="2"><?php if (!empty($gsend_info['apply_user_name'])) echo $gsend_info['apply_user_name']; else echo '获取失败，请重试';?></td>
    </tr>
    <tr>
      <td width="132" align="right">申请时间：</td><td colspan="2"><?php if (!empty($gsend_info['apply_date'])) echo $gsend_info['apply_date']; else echo '无效时间';?></td>
    </tr>
    <tr>
      <td width="132" align="right">审核人：</td><td colspan="2"><?php echo $gsend_info['audit_user_name'];?></td>
    </tr>
    <tr>
      <td width="132" align="right">审核时间：</td><td colspan="2"><?php echo $gsend_info['audit_date'];?></td>
    </tr>
 </table>
</div>
  <div id="body_box_r_btn"><input type="submit" value=" <?php echo $action['name']?> " /></div>
</form>
</body>
</html>