<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/big_css.css">
 
<body>
<form name="project_info" method="POST" action="<?php echo $action['value'] ?>">
<input type="hidden" name="isPost" value="TRUE">
<div id="body_box_r_tit" class="cu">项目详情</div>
<div id="body_box_r_text"><table width="755" border="0" cellspacing="0" cellpadding="0" class="inputbiaodan">
    <tr>
      <td width="211" align="right">项目名称：</td>
      <td width="544">
        <input name="pj_name" type="text" id="pj_name" size="30" value="<?php echo empty($pj_apv_info['pj_name'])?'':$pj_apv_info['pj_name'];?>" /> <span class="hui">（参考格式：公司_业务）</span>
      </td>
    </tr>
    <tr>
      <td align="right">所属行业：</td>
      <td colspan="2"><select name="industry_type"><?php foreach($industry_type as $k => $v) { if ($pj_apv_info['industry_type'] != $k) { echo '<option value="'.$k.'">'.$v.'</option>'; } else echo '<option value="'.$k.'" selected>'.$v.'</option>'; } ?></select></td>
    </tr>
    <tr>
      <td align="right">使用时间：</td>
     <td colspan="2">
     <select name="validdate_s_y"><?php for($i=2013; $i<2021; $i++) { if (date('Y',strtotime($pj_apv_info['pj_start'])) != $i) echo '<option value="'.$i.'">'.$i.'年</option>'; else echo '<option value="'.$i.'" selected>'.$i.'年</option>';} ?></select> 
	 <select name="validdate_s_m"><?php for ($i=1; $i<13; $i++) { if (date('n',strtotime($pj_apv_info['pj_start'])) != $i) echo '<option value="'.$i.'">'.$i.'月</option>'; else  echo '<option value="'.$i.'" selected>'.$i.'月</option>';} ?></select> 
	 <select name="validdate_s_d"><?php for ($i=1; $i<32; $i++) { if (date('j',strtotime($pj_apv_info['pj_start'])) != $i) echo '<option value="'.$i.'">'.$i.'日</option>'; else echo '<option value="'.$i.'" selected>'.$i.'日</option>';} ?></select> 
	 到 
	 <select name="validdate_e_y"><?php for($i=2013; $i<2021; $i++) { if (date('Y',strtotime($pj_apv_info['pj_end'])) != $i)  echo '<option value="'.$i.'">'.$i.'年</option>'; else echo '<option value="'.$i.'" selected>'.$i.'年</option>';} ?></select> 
	 <select name="validdate_e_m"><?php for ($i=1; $i<13; $i++) { if (date('n',strtotime($pj_apv_info['pj_end'])) != $i) echo '<option value="'.$i.'">'.$i.'月</option>'; else  echo '<option value="'.$i.'" selected>'.$i.'月</option>';} ?></select> 
	 <select name="validdate_e_d"><?php for ($i=1; $i<32; $i++) { if (date('j',strtotime($pj_apv_info['pj_end'])) != $i) echo '<option value="'.$i.'">'.$i.'日</option>'; else echo '<option value="'.$i.'" selected>'.$i.'日</option>';} ?></select></td>
    </tr>
    <tr>
      <td align="right">每天预计下发量(条)：</td>
      <td><input name="avg_cnt" type="text" id="avg_cnt" size="30" value="<?php echo empty($pj_apv_info['avg_cnt'])?'':$pj_apv_info['avg_cnt'];?>" /></td>
    </tr>
    <tr>
      <td align="right">紧急联系人姓名：</td>
      <td><input name="emergent_user_name" type="text" id="emergent_user_name" size="30" value="<?php echo empty($pj_apv_info['emergent_user_name'])?'':$pj_apv_info['emergent_user_name'];?>" /></td>
    </tr>
     <tr>
      <td align="right">紧急联系人手机：</td>
      <td><input name="emergent_user_phone" type="text" id="emergent_user_phone" size="30" value="<?php echo empty($pj_apv_info['emergent_user_phone'])?'':$pj_apv_info['emergent_user_phone'];?>" /></td>
    </tr>
    <tr>
      <td align="right">紧急联系人邮箱：</td>
      <td><input name="emergent_user_email" type="text" id="emergent_user_email" size="30" value="<?php echo empty($pj_apv_info['emergent_user_email'])?'':$pj_apv_info['emergent_user_email'];?>" /></td>
    </tr>
    <tr>
      <td align="right" valign="top">项目描述：</td>
      <td><textarea name="pj_desc" cols="27" rows="3" id="pj_desc"><?php echo empty($pj_apv_info['pj_desc'])?'':$pj_apv_info['pj_desc'];?></textarea> </td>
    </tr>
      <tr>
      <td align="right" valign="top">IP权限：</td>
      <td><textarea name="pj_restrict" cols="27" rows="3" id="pj_restrict"><?php echo empty($pj_apv_info['pj_restrict'])?'':$pj_apv_info['pj_restrict'];?></textarea> </td>
    </tr>
    <tr>
      <td align="right" valign="top">备 注：</td>
      <td><textarea name="pj_memo" cols="27" rows="3" id="pj_memo"><?php echo empty($pj_apv_info['pj_memo'])?'':$pj_apv_info['pj_memo'];?></textarea> </td>
    </tr>
  </table></div>
  
  <div id="body_box_r_btn"><input type="submit" value=" <?php echo $action['name']?> " />
  </div>
</form>
</body>
</html>

