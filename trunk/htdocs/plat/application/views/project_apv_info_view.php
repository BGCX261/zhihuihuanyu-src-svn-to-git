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
      <td width="132" align="right">项目描述：</td><td colspan="2"><?php if (!empty($pj_apv_info['pj_desc'])) echo substr($pj_apv_info['pj_desc'], 0, 100); else echo '未设置';?></td>
    </tr>
	<tr>
      <td align="right">所在行业：</td>
      <td colspan="2"><select name="industry_type"><?php foreach($industry_type as $k => $v) { if ($pj_apv_info['industry_type'] != $k) { echo '<option value="'.$k.'">'.$v.'</option>'; } else echo '<option value="'.$k.'" selected>'.$v.'</option>'; } ?></select></td>
    </tr>    
     <tr>
      <td align="right">每条短信的费用(厘)：</td>
      <td><input name="pj_fee" type="text" id="pj_fee" size="30" value="<?php echo empty($pj_apv_info['pj_fee'])?'':$pj_apv_info['pj_fee'];?>" /><font color="red"><b>本项必填</b></font></td>
    </tr>
     <tr>
      <td align="right">项目from：</td>
      <td><input name="project_no" type="text" id="project_no" size="30" value="<?php echo empty($pj_apv_info['project_no'])?'':$pj_apv_info['project_no'];?>" /><font color="red"><b>本项必填</b></font></td>
    </tr>
	<tr>
      <td align="right">账单类型：</td>
      <td colspan="2"><select name="pj_bill_type"><?php foreach($pj_bill_type as $k => $v) { if ($pj_apv_info['pj_bill_type'] != $k) { echo '<option value="'.$k.'">'.$v.'</option>'; } else echo '<option value="'.$k.'" selected>'.$v.'</option>'; } ?></select></td>
    </tr>
	<tr>
      <td align="right">上/下行方式：</td>
      <td colspan="2"><select name="mo_mt"><?php foreach($mo_mt as $k => $v) { if ($pj_apv_info['mo_mt_type'] != $k) { echo '<option value="'.$k.'">'.$v.'</option>'; } else echo '<option value="'.$k.'" selected>'.$v.'</option>'; } ?></select></td>
    </tr>
	<tr>
    <td align="right">下发到哪些用户：</td>
      <td colspan="2"><select name="isp_type"><?php foreach($isp_type as $k => $v) { if ($pj_apv_info['isp_type'] != $k) { echo '<option value="'.$k.'">'.$v.'</option>'; } else echo '<option value="'.$k.'" selected>'.$v.'</option>'; } ?></select></td>
    </tr>
    <tr>
      <td align="right">选择使用哪个移动通道：</td>
      <td colspan="2"><select name="cm_channel">
      <?php
       foreach($channels_group_by_ips['CM'] as $k => $v) {
       	$selected = false;
       	foreach($channels_in_proj as $one_channel_in_proj) {
	       	if ($one_channel_in_proj['channel_id'] == $v['id'] 
	       		&& $one_channel_in_proj['isp_type'] == 'CM') { 
	       		echo '<option value="'.$v['id'].'" selected>'.$v['channel_name'].'</option>';
	       		$selected = true;
	       	}
	    }
        if(!$selected) {
        	echo '<option value="'.$v['id'].'">'.$v['channel_name'].'</option>';
        }
       }?>
      </select></td>
    </tr>
    <tr>
      <td align="right">设置移动通道的长号码：</td><td><input name="cm_longcode" type="text" id="cm_longcode" size="30" value="<?php echo $cm_longcode;?>" /><font color="red"><b>若使用移动通道，则本项必填</b></font></td>
    </tr>
    <tr>
      <td align="right">选择使用哪个联通通道：</td>
      <td colspan="2"><select name="cu_channel">
      <?php
       foreach($channels_group_by_ips['CU'] as $k => $v) {
       	$selected = false;
       	foreach($channels_in_proj as $one_channel_in_proj) {
	       	if ($one_channel_in_proj['channel_id'] == $v['id']
	       		&& $one_channel_in_proj['isp_type'] == 'CU') {  
	       		echo '<option value="'.$v['id'].'" selected>'.$v['channel_name'].'</option>';
	       		$selected = true;
	       	}
	    }
        if(!$selected) {
        	echo '<option value="'.$v['id'].'">'.$v['channel_name'].'</option>';
        }
       } 
      ?>
      </select></td>
    </tr>
    <tr>
      <td align="right">设置联通通道的长号码：</td><td><input name="cu_longcode" type="text" id="cu_longcode" size="30" value="<?php echo $cu_longcode;?>" /><font color="red"><b>若使用联通通道，则本项必填</b></font></td>
    </tr>
    <tr>
      <td align="right">选择使用哪个电信通道：</td>
      <td colspan="2"><select name="ct_channel">
      <?php
       foreach($channels_group_by_ips['CT'] as $k => $v) {
       	$selected = false;
       	foreach($channels_in_proj as $one_channel_in_proj) {
	       	if ($one_channel_in_proj['channel_id'] == $v['id']
	       		&& $one_channel_in_proj['isp_type'] == 'CT') {  
	       		echo '<option value="'.$v['id'].'" selected>'.$v['channel_name'].'</option>';
	       		$selected = true;
	       	}
	    }
        if(!$selected) {
        	echo '<option value="'.$v['id'].'">'.$v['channel_name'].'</option>';
        }
       } 
      ?>
      </select></td>
    </tr>
    <tr>
      <td align="right">设置电信通道的长号码：</td><td><input name="ct_longcode" type="text" id="ct_longcode" size="30" value="<?php echo $ct_longcode;?>" /><font color="red"><b>若使用电信通道，则本项必填</b></font></td>
    </tr>
    <tr>
      <td align="right" valign="top">IP权限：</td>
      <td><textarea name="pj_restrict" cols="27" rows="3" id="pj_restrict"><?php if (!empty($pj_apv_info['pj_restrict']) )echo $pj_apv_info['pj_restrict'];?></textarea> </td>
    </tr>
    <tr>
      <td align="right" valign="top">项目备注：</td>
      <td><textarea name="pj_memo" cols="27" rows="3" id="pj_memo"><?php if (!empty($pj_apv_info['pj_memo']) )echo $pj_apv_info['pj_memo'];?></textarea> </td>
    </tr>  </table>
</div>
<?php if ($this->access->is_admin()): ?>
  <div id="body_box_r_btn"><input type="submit" value=" <?php echo $action['name']?> " />
<!--   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <input type="button" value=" 驳回该项目 " onclick="document.location.href='<?php echo site_url('projects/project_update/1/'.$pj_apv_info['id'])?>'"/>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <input type="button" value="邮件通知" onclick="document.location.href='<?php echo site_url('projects/project_mail/'.$pj_apv_info['id'])?>'"/>
-->  
 </div>
 <?php endif; ?>
</form>
</body>
</html>