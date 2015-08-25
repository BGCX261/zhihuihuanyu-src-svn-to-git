<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/big_css.css">

<body>
<div id="body_box_r_text"><table width="755" border="0" cellspacing="0" cellpadding="0" class="inputbiaodan">
    <tr>
      <td colspan="3" >
       <table width="754" border="0" cellspacing="0" cellpadding="0" class="biaoge">
        <tr>
          <td width="40" align="center" valign="middle" bgcolor="#EFEFEF">充值ID</td>
          <td width="40" align="center" valign="middle" bgcolor="#EFEFEF">给谁充值</td>
          <td width="40" align="center" valign="middle" bgcolor="#EFEFEF">充值金额(元)</td>
          <td width="40" align="center" valign="middle" bgcolor="#EFEFEF">操作员</td>
          <td width="80" align="center" valign="middle" bgcolor="#EFEFEF">充值时间</td>
          <td width="40" align="center" valign="middle" bgcolor="#EFEFEF">预付费子账户余额(元)</td>
          <td width="40" align="center" valign="middle" bgcolor="#EFEFEF">后付费子账户欠款(元)</td>
          <td width="40" align="center" valign="middle" bgcolor="#EFEFEF">您的总余额(元)</td>
        </tr>
		<?php if (!empty($list)) { foreach ($list as $v) { ?>
        <tr>
          <td align="center" valign="middle"><?php echo $v['id']; ?></td>
          <td align="center" valign="middle"><?php echo $v['user_name']; ?></td>
          <td align="center" valign="middle"><?php echo $v['charge_fee']/1000; ?></td>
          <td align="center" valign="middle"><?php echo $v['operator_name']; ?></td>
          <td align="center" valign="middle"><?php echo $v['charge_date']; ?></td>
          <td align="center" valign="middle"><?php echo $v['pre_account_fee']/1000; ?></td>
          <td align="center" valign="middle"><?php echo $v['post_account_fee']/1000; ?></td>
          <td align="center" valign="middle"><?php echo $v['total_account_fee']/1000; ?></td>
        </tr>
		<?php } } ?>
      </table>
     </td>
    </tr>
  </table>
</div>
<div id="body_box_r_btn"><input type="button" value=" 返回 " />
</div>
</body>
</html>