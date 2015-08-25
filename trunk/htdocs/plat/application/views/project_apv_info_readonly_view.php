<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/big_css.css">

<body>
<div id="body_box_r_tit" class="cu"><?php echo $action['title']?></div>
<form name="project_apv_info" method="POST" action="<?php echo $action['value'] ?>">

<input type="hidden" name="isPost" value="TRUE">
<div id="body_box_r_text"><table width="755" border="0" cellspacing="0" cellpadding="0" class="inputbiaodan">
    <tr>
      <td width="132" align="right">项目名称：</td><td colspan="2"><?php if (!empty($pj_apv_info['pj_name'])) echo $pj_apv_info['pj_name']; else echo '无'; ?></td>
    </tr>
    <tr>
      <td width="132" align="right">公司名称：</td><td colspan="2"><?php if (!empty($pj_apv_info['apply_company'])) echo $pj_apv_info['apply_company']; else echo '无'; ?></td>
    </tr>
    <tr>
      <td width="132" align="right">申请人：</td><td colspan="2"><?php if (!empty($pj_apv_info['apply_user_name'])) echo $pj_apv_info['apply_user_name']; else echo '获取失败，请重试';?></td>
    </tr>
    <tr>
      <td width="132" align="right">申请人电话：</td><td colspan="2"><?php if (!empty($pj_apv_info['apply_user_phone'])) echo $pj_apv_info['apply_user_phone']; else echo '无';?></td>
    </tr>
    <tr>
      <td width="132" align="right">申请时间：</td><td colspan="2"><?php if (!empty($pj_apv_info['apply_date'])) echo $pj_apv_info['apply_date']; else echo '无效时间';?></td>
    </tr>
    <tr>
      <td width="132" align="right">项目开始时间：</td><td colspan="2"><?php if (!empty($pj_apv_info['pj_start'])) echo $pj_apv_info['pj_start']; else echo '无效时间';?></td>
    </tr>
    <tr>
      <td width="132" align="right">项目结束时间：</td><td colspan="2"><?php if (!empty($pj_apv_info['pj_end'])) echo $pj_apv_info['pj_end']; else echo '无效时间';?></td>
    </tr>
    <tr>
      <td width="132" align="right">每天预计下发量：</td><td colspan="2"><?php if (!empty($pj_apv_info['avg_cnt'])) echo $pj_apv_info['avg_cnt']; else echo '未设置';?></td>
    </tr>
	<tr>
      <td width="132" align="right">所在行业：</td><td colspan="2"><?php if (!empty($pj_apv_info['industry_type'])) echo $industry_type[$pj_apv_info['industry_type']]; else echo '未设置';?></td>
    </tr>    
     <tr>
      <td width="132" align="right">每条短信的费用(分)：</td><td colspan="2"><?php if (!empty($pj_apv_info['pj_fee'])) echo $pj_apv_info['pj_fee']/10; else echo '未设置';?></td>
    </tr>
     <tr>
      <td width="132" align="right">项目from：</td><td colspan="2"><?php if (!empty($pj_apv_info['project_no'])) echo $pj_apv_info['project_no']; else echo '未设置';?></td>
    </tr>
	<tr>
      <td width="132" align="right">账单类型：</td><td colspan="2"><?php if (!empty($pj_apv_info['pj_bill_type'])) echo $pj_bill_type[$pj_apv_info['pj_bill_type']]; else echo '未设置';?></td>
    </tr>
	<tr>
      <td width="132" align="right">上/下行方式：</td><td colspan="2"><?php if (!empty($pj_apv_info['mo_mt_type'])) echo $mo_mt[$pj_apv_info['mo_mt_type']]; else echo '未设置';?></td>
    </tr>
	<tr>
      <td width="132" align="right">下发到哪些用户：</td><td colspan="2"><?php if (!empty($pj_apv_info['isp_type'])) echo $isp_type[$pj_apv_info['isp_type']]; else echo '未设置';?></td>
    </tr>
    <tr>
      <td width="132" align="right">选择的移动通道：</td><td colspan="2">
      <?php
       	$selected = false;
       	foreach($channels_in_proj as $one_channel_in_proj) {
	       	if ($one_channel_in_proj['isp_type'] == 'CM') {
	       		echo $one_channel_in_proj['channel_name'];
	       		$selected = true;
	       	}
	    }
        if(!$selected) {
        	echo '未设置';
        }
       ?>
      </select></td>
    </tr>
    <tr>
      <td width="132" align="right">设置的移动通道长号码：</td><td colspan="2"><?php if (!empty($cm_longcode)) echo $cm_longcode; else echo '未设置';?></td>
    </tr>
    <tr>
      <td width="132" align="right">选择的联通通道：</td><td colspan="2">
      <?php
       	$selected = false;
       	foreach($channels_in_proj as $one_channel_in_proj) {
	       	if ($one_channel_in_proj['isp_type'] == 'CU') {
	       		echo $one_channel_in_proj['channel_name'];
	       		$selected = true;
	       	}
	    }
        if(!$selected) {
        	echo '未设置';
        }
       ?>
      </select></td>
    </tr>
    <tr>
      <td width="132" align="right">设置的联通通道长号码：</td><td colspan="2"><?php if (!empty($cu_longcode)) echo $cu_longcode; else echo '未设置';?></td>
    </tr>
    <tr>
      <td width="132" align="right">选择的电信通道：</td><td colspan="2">
      <?php
       	$selected = false;
       	foreach($channels_in_proj as $one_channel_in_proj) {
	       	if ($one_channel_in_proj['isp_type'] == 'CT') {
	       		echo $one_channel_in_proj['channel_name'];
	       		$selected = true;
	       	}
	    }
        if(!$selected) {
        	echo '未设置';
        }
       ?>
      </select></td>
    </tr>
    <tr>
      <td width="132" align="right">设置的电信通道长号码：</td><td colspan="2"><?php if (!empty($ct_longcode)) echo $ct_longcode; else echo '未设置';?></td>
    </tr>
    <tr>
      <td width="132" align="right">项目描述：</td><td colspan="2"><?php if (!empty($pj_apv_info['pj_desc'])) echo substr($pj_apv_info['pj_desc'], 0, 100); else echo '未设置';?></td>
    </tr>
    <tr>
      <td width="132" align="right">IP权限：</td><td colspan="2"><?php if (!empty($pj_apv_info['pj_restrict'])) echo substr($pj_apv_info['pj_restrict'], 0, 100); else echo '未设置';?></td>
    </tr>
    <tr>
      <td width="132" align="right">项目备注：</td><td colspan="2"><?php if (!empty($pj_apv_info['pj_memo'])) echo substr($pj_apv_info['pj_memo'], 0, 100); else echo '未设置';?></td>
    </tr>
 </table>
</div>
  <div id="body_box_r_btn"><input type="submit" value=" <?php echo $action['name']?> " /></div>
<!--<?php if ($this->access->is_admin()): ?>
  <div id="body_box_r_btn"><input type="submit" value=" <?php echo $action['name']?> " />
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <input type="button" value=" 驳回该项目 " onclick="document.location.href='<?php echo site_url('projects/project_update/1/'.$pj_apv_info['id'])?>'"/>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <input type="button" value="邮件通知" onclick="document.location.href='<?php echo site_url('projects/project_mail/'.$pj_apv_info['id'])?>'"/>
 </div>
 <?php endif; ?> --> 
</form>
</body>
</html>