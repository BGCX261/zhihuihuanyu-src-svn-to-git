<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/big_css.css">

<body>
<div id="body_box_r_text"><table width="755" border="0" cellspacing="0" cellpadding="0" class="inputbiaodan">
    <tr>
      <td colspan="3" ><table width="727" border="0" cellspacing="0" cellpadding="0" class="biaoge">
        <tr>
          <td width="28" align="center" valign="middle" bgcolor="#EFEFEF">序号</td>
          <td width="59" align="center" valign="middle" bgcolor="#EFEFEF">创建时间</td>
          <td width="45" align="center" valign="middle" bgcolor="#EFEFEF">项目编号</td>
          <td width="81" align="center" valign="middle" bgcolor="#EFEFEF">申请人</td>
          <td width="66" align="center" valign="middle" bgcolor="#EFEFEF">运行状态</td>
          <td width="61" align="center" valign="middle" bgcolor="#EFEFEF">管理</td>
          </tr>
		<?php if (!empty($list)) { foreach ($list as $v) { ?>
        <tr>
          <td height="33" align="center" valign="middle"><?php echo $v['id']; ?></td>
          <td align="center" valign="middle"><?php echo $v['app_date']; ?></td>
          <td align="center" valign="middle"><?php echo $v['project_no']; ?></td>
          <td align="center" valign="middle"><?php echo $user_list[$v['user_id']]; ?></td>
          <td align="center" valign="middle"><?php echo $gs_status[$v['status']]; ?></td>
          <td align="center" valign="middle">
		  <?php if ($this->access->is_admin() && $v['status'] == 0): ?>
		  <a href="<?php echo site_url('group_send/upt_mms_status/2/'.$v['id'])?>">开通</a> |
		  <a href="<?php echo site_url('group_send/upt_mms_status/1/'.$v['id'])?>">驳回</a>
		  <?php endif; ?>
		  <?php if ($v['status'] == 2): ?>
		  <a href="<?php echo site_url('group_send/upt_mms_status/3/'.$v['id'])?>">开始群发</a> 
		  <?php endif; ?>
          </tr>
		<?php } } ?>
      </table></td>
       </tr>
  </table>
</div>
<div id="body_box_r_btn"><input type="button" value=" 返回 " />
</div>
</body>
</html>