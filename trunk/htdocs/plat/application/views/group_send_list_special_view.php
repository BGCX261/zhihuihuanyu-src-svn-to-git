<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/big_css.css">

<body>
<div id="body_box_r_text"><table width="755" border="0" cellspacing="0" cellpadding="0" class="inputbiaodan">
    <tr>
      <td colspan="3" ><table width="754" border="0" cellspacing="0" cellpadding="0" class="biaoge">
        <tr>
          <!--  <td width="28" align="center" valign="middle" bgcolor="#EFEFEF">序号</td>-->
          <td width="80" align="center" valign="middle" bgcolor="#EFEFEF">群发时间</td>
          <td width="45" align="center" valign="middle" bgcolor="#EFEFEF">项目编号</td>
          <td width="45" align="center" valign="middle" bgcolor="#EFEFEF">长号码</td>
          <td width="58" align="center" valign="middle" bgcolor="#EFEFEF">群发内容</td>
          <td width="40" align="center" valign="middle" bgcolor="#EFEFEF">长度</td>
          <td width="40" align="center" valign="middle" bgcolor="#EFEFEF">数量</td>
          <td width="81" align="center" valign="middle" bgcolor="#EFEFEF">申请人</td>
          <td width="66" align="center" valign="middle" bgcolor="#EFEFEF">运行状态</td>
          <td width="88" align="center" valign="middle" bgcolor="#EFEFEF">管理</td>
          </tr>
		<?php if (!empty($list)) { foreach ($list as $v) { ?>
        <tr>
      <!--    <td height="33" align="center" valign="middle"><?php echo $v['id']; ?></td>  -->
          <td align="center" valign="middle"><?php echo $v['gs_start']; ?></td>
          <td align="center" valign="middle"><?php echo $v['project_no']; ?></td>
          <td align="center" valign="middle"><?php echo $v['longnum']; ?></td>
          <td align="center" valign="middle"><?php echo $v['sms_msg']; ?></td>
          <td align="center" valign="middle"><?php echo iconv_strlen($v['sms_msg'],"UTF-8");  ?></td>
          <td align="center" valign="middle"><?php 
			if ($v['phone_list'][0] === 'M')
			{
				$path = $this->config->item('groupsend_path');
				$path = $path . $v['phone_list'];
				echo count(explode("\r\n",file_get_contents($path))); 
			}
			else
          		echo count(explode("\r\n",$v['phone_list'])); ?></td>
          <td align="center" valign="middle"><?php echo $user_list[$v['user_id']]; ?></td>
          <td align="center" valign="middle"><?php echo $gs_status[$v['status']]; ?></td>
          <td align="center" >
		  <?php if ($this->access->is_admin() ): ?>
		  <a href="<?php echo site_url('group_send/upt_status/2/'.$v['id'])?>">审核</a> 
		  <a href="<?php echo site_url('group_send/upt_status/1/'.$v['id'])?>">驳回</a> 
		  <a href="<?php echo site_url('group_send/group_send_change/'.$v['id'])?>">查看</a> 
		  <a href="<?php 
		  	if ($v['phone_list'][0] === 'M')
				echo site_url('group_send/delete/'.$v['id'].'/'.$v['phone_list']) ;
			else
          		echo site_url('group_send/delete/'.$v['id']) ;
		  	?>"
		  	onclick="if(confirm('确实要删除此条记录吗？')) return true;else return false;">删除</a>
		  <?php else: ?>
		  <a>&nbsp</a>
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