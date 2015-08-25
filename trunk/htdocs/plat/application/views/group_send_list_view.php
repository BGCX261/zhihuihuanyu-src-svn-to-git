<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/big_css.css">

<body>
<form name="gsend_list_view" method="POST" action="<?php echo site_url('group_send/gsend_search') ?>">
<div id="body_box_r_text"><table width="755" border="0" cellspacing="0" cellpadding="0" class="inputbiaodan">
    <tr>
      <td width="55" align="right">查询：</td>
      <td width="200"><input name="content" type="text" /> <select name="gs_status">
        <option value="">所有</option><?php foreach($gs_status as $k => $v) { echo '<option value="'.$k.'">'.$v.'</option>'; }?>
      </select></td>
       <td width="200"><input name="input" type="submit" value="查询"/></td>
    </tr>
	</table>
</div>
</form>
<div id="body_box_r_text"><table width="755" border="0" cellspacing="0" cellpadding="0" class="inputbiaodan">
    <tr>
      <td colspan="3" >
       <table width="754" border="0" cellspacing="0" cellpadding="0" class="biaoge">
        <tr>
          <td width="40" align="center" valign="middle" bgcolor="#EFEFEF">群发ID</td>
          <td width="80" align="center" valign="middle" bgcolor="#EFEFEF">群发时间</td>
          <td width="60" align="center" valign="middle" bgcolor="#EFEFEF">群发名称</td>
          <td width="60" align="center" valign="middle" bgcolor="#EFEFEF">所在项目</td>
          <td width="20" align="center" valign="middle" bgcolor="#EFEFEF">From</td>
          <td width="100" align="center" valign="middle" bgcolor="#EFEFEF">内容</td>
          <td width="60" align="center" valign="middle" bgcolor="#EFEFEF">上传文件</td>
          <td width="40" align="center" valign="middle" bgcolor="#EFEFEF">申请人</td>
          <td width="80" align="center" valign="middle" bgcolor="#EFEFEF">申请时间</td>
          <td width="40" align="center" valign="middle" bgcolor="#EFEFEF">审核人</td>
          <td width="80" align="center" valign="middle" bgcolor="#EFEFEF">审核时间</td>
          <td width="40" align="center" valign="middle" bgcolor="#EFEFEF">状态</td>
          <td width="40" align="center" valign="middle" bgcolor="#EFEFEF">管理</td>
        </tr>
		<?php if (!empty($list)) { foreach ($list as $v) { ?>
        <tr>
          <td align="center" valign="middle"><?php echo $v['gsend_id']; ?></td>
          <td align="center" valign="middle"><?php echo $v['mt_date']; ?></td>
          <td align="center" valign="middle"><?php echo $v['gsend_name']; ?></td>
          <td align="center" valign="middle"><?php echo $v['pj_name']; ?></td>
          <td align="center" valign="middle"><?php echo $v['project_no']; ?></td>
          <td align="center" valign="middle"><?php echo $v['sms_msg']; ?></td>
          <td align="center" valign="middle"><?php echo $v['upload_client_filename']; ?></td>
          <td align="center" valign="middle"><?php echo $v['apply_user_name']; ?></td>
          <td align="center" valign="middle"><?php echo $v['apply_date']; ?></td>
          <td align="center" valign="middle"><?php echo $v['audit_user_name']; ?></td>
          <td align="center" valign="middle"><?php echo $v['audit_date']; ?></td>
          <td align="center" valign="middle"><?php echo $gs_status[$v['gs_status']]; ?></td>
          <td align="center" valign="middle">
		  <?php 
		  	if($v['gs_status'] == 0) {
		  		if ($this->access->is_admin()) {
					echo '<a href="'.site_url('group_send/upt_status/2/'.$v['gsend_id']).'" onclick="if(confirm(\'确实要批准该群发吗？\')) return true;else return false;">审核</a> | <a href="'.site_url('group_send/upt_status/1/'.$v['gsend_id']).'" onclick="if(confirm(\'确实要驳回该群发吗？\')) return true;else return false;">驳回</a> | <a href="'.site_url('group_send/upt_status/5/'.$v['gsend_id']).'" onclick="if(confirm(\'确实要删除该群发吗？\')) return true;else return false;">删除</a>';
				}else {
					echo '<a href="'.site_url('group_send/gsend_detail/'.$v['gsend_id']).'">查看</a> | <a href="'.site_url('group_send/upt_status/5/'.$v['gsend_id']).'" onclick="if(confirm(\'确实要删除该群发吗？\')) return true;else return false;">删除</a>';
				}
		  	}elseif($v['gs_status'] == 1) {
				echo '<a href="'.site_url('group_send/group_send_change/'.$v['gsend_id']).'">重新申请</a>  | <a href="'.site_url('group_send/upt_status/5/'.$v['gsend_id']).'" onclick="if(confirm(\'确实要删除该群发吗？\')) return true;else return false;">删除</a>';
		  	}elseif($v['gs_status'] == 2) {
		  		if ($this->access->is_admin()) {
					echo '<a href="'.site_url('group_send/gsend_detail/'.$v['gsend_id']).'">查看</a> | <a href="'.site_url('group_send/upt_status/5/'.$v['gsend_id']).'" onclick="if(confirm(\'确实要删除该群发吗？\')) return true;else return false;">删除</a>';
				}else {
					echo '<a href="'.site_url('group_send/gsend_detail/'.$v['gsend_id']).'">查看</a>';
				}
		  	}else{
		  		echo '<a href="'.site_url('group_send/gsend_detail/'.$v['gsend_id']).'">查看</a>';
		  	}		  		
		  ?>
		 </td>
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