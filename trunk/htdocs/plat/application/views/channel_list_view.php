<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/big_css.css">
</head>

<body>
<div id="body_box_r_text">
  <table width="755" border="0" cellspacing="0" cellpadding="0" class="inputbiaodan">
    <tr>
      <td colspan="3" ><table width="732" border="0" cellspacing="0" cellpadding="0" class="biaoge">
		<!-- <tr><a href="<?php echo site_url('channels/channel_change'); ?>">添加公司</a></tr>  -->
        <tr>
          <td width="90" align="center" valign="middle" bgcolor="#EFEFEF">序号</td>
          <td width="92" align="center" valign="middle" bgcolor="#EFEFEF">通道名</td>
          <td width="90" align="center" valign="middle" bgcolor="#EFEFEF">公司名</td>
          <td width="89" align="center" valign="middle" bgcolor="#EFEFEF">有效期</td>
          <td width="94" align="center" valign="middle" bgcolor="#EFEFEF">单价(分)</td>
          <td width="94" align="center" valign="middle" bgcolor="#EFEFEF">最大汉字数</td>
          <td width="94" align="center" valign="middle" bgcolor="#EFEFEF">上/下行</td>
          <td width="94" align="center" valign="middle" bgcolor="#EFEFEF">运营商</td>
          <td width="94" align="center" valign="middle" bgcolor="#EFEFEF">网关号</td>
		  <td width="94" align="center" valign="middle" bgcolor="#EFEFEF">长号码</td>
		  <td width="94" align="center" valign="middle" bgcolor="#EFEFEF">管理</td>
        </tr>
		<?php if (!empty($c_list)) { foreach ($c_list as $list) { ?>
        <tr>
          <td align="center" valign="middle"><?php echo empty($list['id'])?'':$list['id'];?></td>
          <td align="center" valign="middle"><?php echo empty($list['channel_name'])?'未设置':$list['channel_name'];?></td>
          <td align="center" valign="middle"><?php echo empty($list['company_name'])?'未设置':$list['company_name'];?></td>
          <td align="center" valign="middle"><?php echo empty($list['contract_end'])?'未设置':$list['contract_end'];?></td>
          <td align="center" valign="middle"><?php echo empty($list['channel_price'])?'未设置':$list['channel_price']/10;?></td>
          <td align="center" valign="middle"><?php echo empty($list['max_len'])?'未设置':$list['max_len'];?></td>
          <td align="center" valign="middle"><?php echo empty($list['mo_mt_type'])?'未设置':$mo_mt[$list['mo_mt_type']];?></td>
          <td align="center" valign="middle"><?php echo empty($list['isp_type'])?'未设置':$isp_type[$list['isp_type']];?></td>
          <td align="center" valign="middle"><?php echo empty($list['gateway'])?'未设置':$list['gateway'];?></td>
		  <td align="center" valign="middle"><?php echo empty($list['longcode'])?'未设置':$list['longcode'];?></td>
          <td align="center" valign="middle"><a href="<?php echo site_url('channels/channel_change/'.$list['id']); ?>">编辑</a> | <a href="<?php echo site_url('channels/delete/'.$list['id']); ?>" onclick="if(confirm('确实要删除此条记录吗？')) return true;else return false;"> 删除</a></td>
        </tr>        
		<?php }}?>
      </table></td>
       </tr>
    <tr>
      <td colspan="2" > [当前第 <span class="cu red">1</span> 页] [总共 <span class="cu red"><?php echo count($c_list);?></span> 条]</td>
      <td align="right" style="padding-right:15px">
        <input type="submit" name="button2" id="button2" value="上一页" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="submit" name="button2" id="button2" value="下一页" />      </td>
    </tr>
  </table> 
</div>
</body>
</html>