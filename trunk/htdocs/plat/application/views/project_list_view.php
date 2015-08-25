<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/big_css.css">

<body>
<form name="project_apv_info" method="POST" action="<?php echo site_url('projects/project_search') ?>">
<div id="body_box_r_text"><table width="755" border="0" cellspacing="0" cellpadding="0" class="inputbiaodan">
    <tr>
      <td width="111" align="right">查询：</td>
      <td width="240"><input name="content" type="text" /> <select name="pj_status">
        <option value="">所有</option><?php foreach($pj_status as $k => $v) { echo '<option value="'.$k.'">'.$v.'</option>'; }?>
      </select></td>
       <td width="404"><input name="input" type="submit" value="查询"/></td>
    </tr>
	</table>
</div>
</form>
 <div id="body_box_r_text_result"><table width="755" border="0" cellspacing="0" cellpadding="0" class="inputbiaodan">
    <tr>
      <td colspan="3" ><table width="732" border="0" cellspacing="0" cellpadding="0" class="biaoge">
        <tr>
		  <td width="90" align="center" valign="middle" bgcolor="#EFEFEF">From</td>
		  <td width="90" align="center" valign="middle" bgcolor="#EFEFEF">公司名称</td>
          <td width="92" align="center" valign="middle" bgcolor="#EFEFEF">项目名称</td>
          <td width="92" align="center" valign="middle" bgcolor="#EFEFEF">付费类型</td>
          <td width="92" align="center" valign="middle" bgcolor="#EFEFEF">运营商</td>          
          <td width="92" align="center" valign="middle" bgcolor="#EFEFEF">每条(分)</td>
          <td width="90" align="center" valign="middle" bgcolor="#EFEFEF">申请人</td>
          <td width="90" align="center" valign="middle" bgcolor="#EFEFEF">申请时间</td>
          <td width="90" align="center" valign="middle" bgcolor="#EFEFEF">审核人</td>
          <td width="90" align="center" valign="middle" bgcolor="#EFEFEF">审核时间</td>
          <td width="92" align="center" valign="middle" bgcolor="#EFEFEF">状态</td>
          <td width="94" align="center" valign="middle" bgcolor="#EFEFEF">管理</td>
        </tr>
		<?php if (!empty($p_list)) foreach ($p_list as $v): ?>
        <tr>
          <td align="center" valign="middle"><?php echo empty($v['project_no'])?'未设置':$v['project_no']; ?></td>
          <td align="center" valign="middle"><?php echo empty($v['apply_company'])?'未设置':$v['apply_company']; ?></td>
		  <td align="center" valign="middle"><?php echo empty($v['pj_name'])?'未设置':$v['pj_name']; ?></td>
          <td align="center" valign="middle"><?php echo empty($v['pj_bill_type'])?'未设置':$pj_bill_type[$v['pj_bill_type']]; ?></td>
          <td align="center" valign="middle"><?php echo empty($v['isp_type'])?'未设置':$isp_type[$v['isp_type']]; ?></td>
		  <td align="center" valign="middle"><?php echo empty($v['pj_fee'])?'未设置':$v['pj_fee']/10; ?></td>
          <td align="center" valign="middle"><?php echo empty($v['apply_user_name'])?'未设置':$v['apply_user_name']; ?></td>
          <td align="center" valign="middle"><?php echo empty($v['apply_date'])?'未知':$v['apply_date']; ?></td>
          <td align="center" valign="middle"><?php echo empty($v['audit_user_name'])?'未知':$v['audit_user_name']; ?></td>
          <td align="center" valign="middle"><?php echo empty($v['audit_date'])?'未知':$v['audit_date']; ?></td>
          <td align="center" valign="middle"><?php echo empty($pj_status[$v['status']])?'未知':$pj_status[$v['status']]; ?></td>		  
          <td align="center" valign="middle">
		<?php 
		if ($v['status'] == 0) {
		  	if ($this->access->is_admin())
			{
				echo '<a href="'.site_url('projects/project_apv_change/'.$v['id']).'">审核</a> | <a href="'.site_url('projects/project_update/1/'.$v['id']).'" onclick="if(confirm(\'确实要驳回此项目吗？\')) return true;else return false;">驳回</a> | <a href="'.site_url('projects/project_update/4/'.$v['id']).'" onclick="if(confirm(\'确实要删除此项目吗？\')) return true;else return false;">删除</a> ';

			}
			else
			{
				echo '<a href="'.site_url('projects/project_detail/'.$v['id']).'">查看</a> | <a href="'.site_url('projects/project_update/4/'.$v['id']).'" onclick="if(confirm(\'确实要删除此项目吗？\')) return true;else return false;">删除</a> ';
			}
		}elseif($v['status'] == 1) {
				echo '<a href="'.site_url('projects/project_app_change/'.$v['id']).'">重新申请</a>  | <a href="'.site_url('projects/project_update/4/'.$v['id']).'" onclick="if(confirm(\'确实要删除此项目吗？\')) return true;else return false;">删除</a>';
		}elseif($v['status'] == 2) {
			if ($this->access->is_admin())
			{
				echo '<a href="'.site_url('projects/project_detail/'.$v['id']).'">查看</a> | <a href="'.site_url('projects/project_update/3/'.$v['id']).'" onclick="if(confirm(\'确实要暂停此项目吗？\')) return true;else return false;">暂停</a>  | <a href="'.site_url('projects/project_update/4/'.$v['id']).'" onclick="if(confirm(\'确实要删除此项目吗？\')) return true;else return false;">删除</a>';
			}
			else
			{
				echo '<a href="'.site_url('projects/project_detail/'.$v['id']).'">查看</a>';
			}
		}elseif($v['status'] == 3) {
			if ($this->access->is_admin())
			{
				echo '<a href="'.site_url('projects/project_detail/'.$v['id']).'">查看</a> | <a href="'.site_url('projects/project_update/2/'.$v['id']).'">重新开通</a>  | <a href="'.site_url('projects/project_update/4/'.$v['id']).'" onclick="if(confirm(\'确实要删除此项目吗？\')) return true;else return false;">删除</a>';
			}
			else
			{
				echo '<a href="'.site_url('projects/project_detail/'.$v['id']).'">查看</a>';
			}
		}elseif($v['status'] == 4) {
			echo '<a href="'.site_url('projects/project_detail/'.$v['id']).'">查看</a>';
		}else {
			echo '无';
		}
		?>
			</td>
          </tr>
		<?php endforeach; ?>
      </table></td>
       </tr>
    <tr>      
		<td colspan="3" align="center" >
        总共 <span class="cu red"><?php echo count($p_list)?> </span>条  当前第 <span class="cu red">1 </span>页/总共 <span class="cu red">1</span> 页   <a href="#" class="cu red">上一页</a>  <a href="#" class="cu red">下一页</a>  跳转
          <input name="textfield2" type="text" id="textfield2" size="3" maxlength="3" />
         
          页</td>

    </tr>
  </table>
</div>
</body>
</html>