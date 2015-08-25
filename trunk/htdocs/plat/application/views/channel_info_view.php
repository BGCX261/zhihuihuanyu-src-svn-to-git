<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/big_css.css">
</head>

<body>
<div id="body_box_r_tit" class="cu">通道详情</div>
<div id="body_box_r_text">
<form name="channel_info" method="POST" enctype='multipart/form-data' action="<?php echo $action['value'] ?>">
  <table width="755" border="0" cellspacing="0" cellpadding="0" class="inputbiaodan">
  <input type="hidden" name="isPost" value="TRUE">
    <tr>
      <td align="right">通道名称：</td>
      <td><input name="channel_name" type="text" id="channel_name" size="30" value="<?php echo empty($channel_info['channel_name'])?'':$channel_info['channel_name'];?>" /></td>
    </tr>
    <tr>
      <td width="211" align="right">公司名称：</td>
      <td width="544"><input name="company_name" type="text" id="company_name" size="30" value="<?php echo empty($channel_info['company_name'])?'':$channel_info['company_name'];?>" /></td>
    </tr>
    <tr>
      <td align="right">财务编号：</td>
      <td><input name="fin_code" type="text" id="fin_code" size="30" value="<?php echo empty($channel_info['fin_code'])?'':$channel_info['fin_code'];?>" /></td>
    </tr>
    <tr>
      <td align="right">合同开始日期：</td>
      <td><input name="contract_begin" type="text" id="contract_begin" size="30" value="<?php echo empty($channel_info['contract_begin'])?'':$channel_info['contract_begin'];?>" />(格式: 2013-01-01)</td>
    </tr>    
    <tr>
      <td align="right">合同截止日期：</td>
      <td><input name="contract_end" type="text" id="contract_end" size="30" value="<?php echo empty($channel_info['contract_end'])?'':$channel_info['contract_end'];?>" />(格式: 2020-01-01)</td>
    </tr>
    <tr>
      <td align="right">单价（厘/条）：</td>
      <td><input name="channel_price" type="text" id="channel_price" size="30" value="<?php echo empty($channel_info['channel_price'])?'':$channel_info['channel_price'];?>" /></td>
    </tr>
    <tr>
      <td align="right">最大汉字数：</td>
      <td><input name="max_len" type="text" id="max_len" size="30" value="<?php echo empty($channel_info['max_len'])?'':$channel_info['max_len'];?>" /></td>
    </tr>
    <tr>
      <td align="right">网关号：</td>
      <td><input name="gateway" type="text" id="gateway" size="30" value="<?php echo empty($channel_info['gateway'])?'':$channel_info['gateway'];?>" /></td>
    </tr>
    <tr>
      <td align="right">长号码：</td>
      <td><input name="longcode" type="text" id="longcode" size="30" value="<?php echo empty($channel_info['longcode'])?'':$channel_info['longcode'];?>" /></td>
    </tr>    
	<tr>
      <td align="right">上/下行：</td>
      <td colspan="2"><select name="mo_mt"><?php foreach($mo_mt as $k => $v) { if ($channel_info['mo_mt_type'] != $k) { echo '<option value="'.$k.'">'.$v.'</option>'; } else echo '<option value="'.$k.'" selected>'.$v.'</option>'; } ?></select></td>
    </tr>
	<tr>
      <td align="right">支持的运营商：</td>
      <td colspan="2"><select name="isp_type"><?php foreach($isp_type as $k => $v) { if ($channel_info['isp_type'] != $k) { echo '<option value="'.$k.'">'.$v.'</option>'; } else echo '<option value="'.$k.'" selected>'.$v.'</option>'; } ?></select></td>
    </tr>
	<tr>
      <td align="right">备注：</td>
      <td><textarea name="memo" rows="5" cols="25"><?php echo empty($channel_info['memo'])?'':$channel_info['memo'];?></textarea></td>
    </tr>
  </table>  
</div>
<div id="body_box_r_btn"><input type="submit" value=" <?php echo $action['name']?> " /></div>
</form>
</body>
</html>