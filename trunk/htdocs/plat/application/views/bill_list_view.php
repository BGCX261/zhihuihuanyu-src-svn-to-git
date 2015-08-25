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
          <td width="40" align="center" valign="middle" bgcolor="#EFEFEF">账单ID</td>
          <td width="80" align="center" valign="middle" bgcolor="#EFEFEF">账单日期</td>
          <td width="60" align="center" valign="middle" bgcolor="#EFEFEF">账单类型</td>
          <td width="60" align="center" valign="middle" bgcolor="#EFEFEF">姓名</td>
          <td width="80" align="center" valign="middle" bgcolor="#EFEFEF">所属项目</td>
          <td width="40" align="center" valign="middle" bgcolor="#EFEFEF">From</td>
          <td width="80" align="center" valign="middle" bgcolor="#EFEFEF">群发名称</td>
          <td width="40" align="center" valign="middle" bgcolor="#EFEFEF">下发量</td>
          <td width="40" align="center" valign="middle" bgcolor="#EFEFEF">每条(分)</td>
          <td width="40" align="center" valign="middle" bgcolor="#EFEFEF">总费用(元)</td>
          <td width="40" align="center" valign="middle" bgcolor="#EFEFEF">预付费子账户余额(元)</td>
          <td width="40" align="center" valign="middle" bgcolor="#EFEFEF">后付费子账户欠款(元)</td>
          <td width="40" align="center" valign="middle" bgcolor="#EFEFEF">您的总余额(元)</td>
          <!-- <td width="100" align="center" valign="middle" bgcolor="#EFEFEF">管理，可查看账单细节，对于管理员，可看到利润</td> -->
        </tr>
		<?php if (!empty($list)) { foreach ($list as $v) { ?>
        <tr>
          <td align="center" valign="middle"><?php echo $v['bill_id']; ?></td>
          <td align="center" valign="middle"><?php echo $v['bill_date']; ?></td>
          <td align="center" valign="middle"><?php echo $pj_bill_type[$v['pj_bill_type']]; ?></td>
          <td align="center" valign="middle"><?php echo $v['username']; ?></td>
          <td align="center" valign="middle"><?php echo $v['pj_name']; ?></td>
          <td align="center" valign="middle"><?php echo $v['project_no']; ?></td>
          <td align="center" valign="middle"><?php echo $v['gsend_name']; ?></td>
          <td align="center" valign="middle"><?php echo $v['upload_cnt']; ?></td>
          <td align="center" valign="middle"><?php echo $v['pj_fee']/10; ?></td>
          <td align="center" valign="middle"><?php echo $v['user_cost']/1000; ?></td>
          <td align="center" valign="middle"><?php echo $v['pre_account_fee']/1000; ?></td>
          <td align="center" valign="middle"><?php echo $v['post_account_fee']/1000; ?></td>
          <td align="center" valign="middle"><?php echo $v['total_account_fee']/1000; ?></td>

		  <!-- 
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
		 </td>-->
		 
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