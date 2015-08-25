<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/big_css.css">

<body>
<div id="body_box_r_tit" class="cu">新建任务</div>
<form name="task_info" method="POST" action="<?php echo $action['value'] ?>">
<input type="hidden" name="isPost" value="TRUE">
<div id="body_box_r_text"><table width="755" border="0" cellspacing="0" cellpadding="0" class="inputbiaodan">
<?php if (!empty($task_info)) :?>
<tr>
      <td width="132" align="right">任务序号：</td>
      <td colspan="2"><?php echo $task_info['id']; ?></td>
    </tr>
    <tr>
      <td width="132" align="right">创建日期：</td>
      <td colspan="2"><?php echo $task_info['app_date']; ?></td>
    </tr>
<?php endif; ?>
    <tr>
      <td align="right">任务名称：</td>
      <td colspan="2"><input type="text" name="task_name" id="task_name" value="<?php echo empty($task_info['task_name'])?'':$task_info['task_name']; ?>" /></td>
    </tr>
    <tr>
      <td width="132" align="right">项目管理：</td>
      <td colspan="2"><select name="project_no"><?php foreach ($pj_info as $v) { if ($task_info['project_no'] != $v['project_no']) echo '<option value="'.$v['project_no'].'">'.$v['pj_name'].'</option>';  else echo '<option value="'.$v['project_no'].'" selected>'.$v['pj_name'].'</option>';} ?></select></td>
    </tr>
     <tr>
      <td align="right">通道形式：</td>
      <td colspan="2"><select name="task_type"><?php foreach($task_type as $k => $v) { if ($project_apv_info['task_type'] != $k) { echo '<option value="'.$k.'">'.$v.'</option>'; } else echo '<option value="'.$k.'" selected>'.$v.'</option>'; } ?></select></td>
    </tr>    
    <tr>
      <td align="right">推广渠道：</td>
      <td colspan="2"><textarea name="task_popular" cols="30" rows="3" id="task_popular"><?php echo empty($task_info['task_popular'])?'':$task_info['task_popular'];?></textarea></td>
    </tr>
     <tr>
      <td align="right">使用时间：</td>
       <td colspan="2"><select name="validdate_s_y"><?php for($i=2011; $i<2013; $i++) { if (date('y',strtotime($task_info['task_start'])) != $i) echo '<option value="'.$i.'">'.$i.'年</option>'; else echo '<option value="'.$i.'" selected>'.$i.'年</option>';} ?></select> 
	 <select name="validdate_s_m"><?php for ($i=1; $i<13; $i++) { if (date('n',strtotime($task_info['task_start'])) != $i) echo '<option value="'.$i.'">'.$i.'月</option>'; else  echo '<option value="'.$i.'" selected>'.$i.'月</option>';} ?></select> 
	 <select name="validdate_s_d"><?php for ($i=1; $i<32; $i++) { if (date('j',strtotime($task_info['task_start'])) != $i) echo '<option value="'.$i.'">'.$i.'日</option>'; else echo '<option value="'.$i.'" selected>'.$i.'日</option>';} ?></select> 
	 到 
	 <select name="validdate_e_y"><?php for($i=2011; $i<2013; $i++) { if (date('y',strtotime($task_info['task_end'])) != $i) echo '<option value="'.$i.'">'.$i.'年</option>'; else echo '<option value="'.$i.'" selected>'.$i.'年</option>';} ?></select> 
	 <select name="validdate_e_m"><?php for ($i=1; $i<13; $i++) { if (date('n',strtotime($task_info['task_end'])) != $i) echo '<option value="'.$i.'">'.$i.'月</option>'; else  echo '<option value="'.$i.'" selected>'.$i.'月</option>';} ?></select> 
	 <select name="validdate_e_d"><?php for ($i=1; $i<32; $i++) { if (date('j',strtotime($task_info['task_end'])) != $i) echo '<option value="'.$i.'">'.$i.'日</option>'; else echo '<option value="'.$i.'" selected>'.$i.'日</option>';} ?></select></td>
    </tr>
      <tr>
      <td align="right" valign="top">短信内容：</td>
      <td colspan="2"><textarea name="task_msg" cols="30" rows="3" id="task_msg"><?php echo empty($task_info['task_msg'])?'':$task_info['task_msg'];?></textarea> </td>
    </tr>   
  </table>
</div>
  
  <div id="body_box_r_btn"><input type="submit" value=" <?php echo $action['name']; ?>" />
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <input type="reset" value=" 重新填写 " />
</div>
</form>
</body>
</html>