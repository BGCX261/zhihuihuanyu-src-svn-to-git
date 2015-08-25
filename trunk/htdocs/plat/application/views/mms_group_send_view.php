<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/big_css.css">

<body>
<div id="body_box_r_tit" class="cu">彩信群发</div>
<form name="group_send_info" method="POST" enctype='multipart/form-data' action="<?php echo site_url('group_send/mms_group_send_change')?>">
<div id="body_box_r_text"><table width="755" border="0" cellspacing="0" cellpadding="0" class="inputbiaodan">
    <tr>
      <td width="213" align="right">项目号码选择：</td>
      <td colspan="2"><select name="project_no">
	  <?php foreach($pj_list as $k => $v): ?>
        <option value="<?php echo $k; ?>"><?php echo $map_pj[$v['project_no']]['longcode']?>-<?php echo strlen(trim($v['pj_name'])) > 30 ? substr($v['pj_name'], 0, 30).'...' : trim($v['pj_name']) ?></option>
	  <?php endforeach; ?>
      </select></td>
    </tr>
     <tr>
      <td align="right">导入手机号码：</td>
      <td colspan="2"><input type="file" name="upload_phone" id="upload_phone" value=" 导入 " /></td>
    </tr>
	 <tr>
      <td align="right">导入彩信包：</td>
      <td colspan="2"><input type="file" name="upload_mmsfile" id="upload_phone" value=" 导入 " /></td>
    </tr>
  </table>
</div>
  <div id="body_box_r_btn"><input type="submit" value=" 群 发 " />&nbsp;</div>
 </form>
</body>
</html>