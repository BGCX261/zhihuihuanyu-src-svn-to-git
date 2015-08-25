<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/big_css.css">

<body>
<form name="task_search" method="POST" action="<?php echo site_url('task/task_search') ?>">
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
          <td width="27" align="center" valign="middle" bgcolor="#EFEFEF">&nbsp;</td>
          <td width="51" align="center" valign="middle" bgcolor="#EFEFEF">任务序号</td>
          <td width="99" align="center" valign="middle" bgcolor="#EFEFEF">审批状态</td>
          <td width="65" align="center" valign="middle" bgcolor="#EFEFEF">申请日期</td>
          <td width="132" align="center" valign="middle" bgcolor="#EFEFEF">任务名称</td>
          <td width="51" align="center" valign="middle" bgcolor="#EFEFEF">项目编号</td>
          <td width="58" align="center" valign="middle" bgcolor="#EFEFEF">通道号码</td>
          <td width="46" align="center" valign="middle" bgcolor="#EFEFEF">申请人</td>
          <td width="202" align="center" valign="middle" bgcolor="#EFEFEF">管理</td>
          </tr>
		<?php if (!empty($p_list)) foreach ($p_list as $v): ?>
		<tr>
          <td height="33" align="center" valign="middle">
            <input type="checkbox" name="checkbox" id="checkbox" />          </td>
          <td align="center" valign="middle"><?php echo empty($v['id'])?'无':$v['id']; ?></td>
          <td align="center" valign="middle"><?php echo empty($task_status[$v['status']])?'无':$task_status[$v['status']]; ?></td>
          <td align="center" valign="middle"><?php echo empty($v['app_date'])?'无':$v['app_date']; ?></td>
          <td align="center" valign="middle"><?php echo empty($v['task_name'])?'无':$v['task_name']; ?></td>
          <td align="center" valign="middle"><?php echo empty($pj_info[$v['project_no']]['project_no'])?'无':$pj_info[$v['project_no']]['project_no']; ?></td>
          <td align="center" valign="middle"><?php echo empty($pj_info[$v['project_no']]['longcode'])?'无':$pj_info[$v['project_no']]['longcode']; ?></td>
          <td align="center" valign="middle"><?php echo empty($pj_info[$v['project_no']]['app_name'])?'无':$pj_info[$v['project_no']]['app_name']; ?></td>
          <td align="center" valign="middle">
		  <a href="<?php echo site_url('task/task_change/'.$v['id']); ?>">查看</a> |
		  <a href="<?php echo site_url('task/task_status/2/'.$v['id']); ?>">通过</a> |
		  <a href="<?php echo site_url('task/task_status/1/'.$v['id']); ?>">驳回</a>
          </td>
          </tr> 
		<?php endforeach; ?>           
      </table></td>
       </tr>
	 <tr>      
		<td colspan="3" align="center" >
        总共 <span class="cu red"><?php echo count($p_list)?> </span>条  当前第 <span class="cu red">1 </span>页/总共 <span class="cu red">1</span> 页 <a href="#" class="cu red">上一页</a><a href="#" class="cu red">下一页</a>  跳转 <input name="textfield2" type="text" id="textfield2" size="3" maxlength="3" /> 页 </td>

    </tr>
  </table>
</div>
</body>
</html>