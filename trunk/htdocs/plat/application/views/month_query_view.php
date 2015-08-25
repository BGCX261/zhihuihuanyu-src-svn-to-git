<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/big_css.css">
<script type="text/javascript" src="<?php echo base_url()?>js/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/common.js"></script>
<style>.inputbiaodan td{padding:0 5px}</style>
</head>
<body>
<form name="search_form" id="search_form" method="post" action="<?php echo site_url('data_query/month_query'); ?>">
<div id="body_box_r_tit" class="cu">选择搜索条件（月）</div>
<div id="body_box_r_text">
	<table width="755" border="0" cellspacing="0" cellpadding="0" class="inputbiaodan">
    <tr>
      <td align="right">日 期：</td>
      <td><?php
      if (empty($param['getdate'])) { 
      	$now_y = date('Y'); $now_m = date('m'); $now_ds = 1; $now_de = date('t');
      }else {
      	$now_y = substr($param['getdate'], 0, 4); 
      	$now_m = intval(substr($param['getdate'], 4, 2));
      	$now_ds= intval($param['day_s']); $now_de= intval($param['day_e']);
      }?>
	  <select name="year"><?php for($i=2013; $i<2020; $i++) { echo '<option value="'.$i.'"'.($i==$now_y ?' selected':'').'>'.$i.'年</option>'; }?></select> 
	  <select name="month"><?php for ($i=1; $i<13; $i++) { echo '<option value="'.$i.'"'.($i==$now_m ?' selected':'').'>'.$i.'月</option>';} ?></select> 
	  <select name="s_day"><?php for ($i=1; $i<32; $i++) { echo '<option value="'.$i.'"'.($i==$now_ds ?' selected':'').'>'.$i.'日</option>';} ?></select>
	  	到
      <select name="e_day"><?php for ($i=1; $i<32; $i++) { echo '<option value="'.$i.'"'.($i==$now_de ?' selected':'').'>'.$i.'日</option>';} ?></select></td>
    </tr>    
    <tr>
      <td align="right">分组显示：</td>
      <td>
	  <input name="showfield[]" type="checkbox" value="date" <?php if (!empty($param['check_field']) && in_array('date', $param['check_field'])) echo 'checked'; ?>/>日期
      <input name="showfield[]" type="checkbox" value="from" <?php if (!empty($param['check_field']) && in_array('from', $param['check_field'])) echo 'checked'; ?>/>项目编号(from值)
	  <input name="showfield[]" type="checkbox" value="longcode" <?php if (!empty($param['check_field']) && in_array('longcode', $param['check_field'])) echo 'checked'; ?>/>长号码
	  </td>
    </tr>
	<tr>
      <td align="right" valign="top">长号码：</td>
      	<td>
	      	<input type="text" name="src_no" id="src_no" maxlength="20" value="<?php echo empty($param['src_no'])? '':$param['src_no']; ?>" />
			<input name="sql_like[]" type="checkbox" value="sql_like_src_no" <?php if (!empty($param['sql_like']) && in_array('sql_like_src_no', $param['sql_like'])) echo 'checked'; ?>/>启用模糊匹配
			<font color='red'>请填写数字</font>
		</td>
    </tr>    	
	<tr>
		<td align="right">项目编号(from值)：</td>
		<td>
	      	<input type="text" name="project_no" id="project_no" maxlength="5" value="<?php echo empty($param['project_no'])? '':$param['project_no']; ?>" />
			<font color='red'>请填写数字</font>
		</td>
    </tr>
    <tr>
	  <td colspan="2" align="center" valign="top"><input type="submit" value="搜索 "/></td>
    </tr>
  </table>
</div>
<?php if (!empty($list['result'])) : ?>
  <div id="body_box_r_tit" class="cu">搜索结果</div> 
  <div id="body_box_r_text_result">
	<table width="755" border="0" cellspacing="0" cellpadding="0" class="inputbiaodan">
	<tr>
		<td colspan="3" align="center">
			总共	<span class="cu red"><?php echo $list['total_rows'] ?></span>
			条 当前第	<span class="cu red"><?php echo $param['page'];?></span>
			页/总共<span class="cu red"><?php echo $list['total_pages'] ?></span>
			页 跳转
			<input name="jumppage" type="text" id="jumppage" size="3" maxlength="3" /><input type='submit' value="页"/>
		</td>
    </tr>
    <tr>
      <td colspan="2" ><table width="732" border="0" cellspacing="0" cellpadding="0" class="biaoge">
        <tr>
		<?php foreach ($list['showfield'] as $v1) { echo '<td align="center" valign="middle" bgcolor="#EFEFEF">'.$filed_map[$v1].'</td>'; }?>
		<?php echo '<td align="center" valign="middle" bgcolor="#EFEFEF">合计总量</td>'; $s_cnt_total = 0; $s_cnt_gw = 0; $s_cnt_rpt = 0; $s_smo_total = 0; $all_new = 0; ?>
        </tr>
		<!--Array ( [date] => 2011-05-24 [mark] => 98214 [app_no] => 102490 [s_cnt_total] => 5937 [s_cnt_gw] => 5937 [s_cnt_rpt] => 0 [s_smo_total] => 0 ) -->
		<?php foreach ($list['result'] as $v2): ?>
		<tr>
		<?php foreach ($list['showfield'] as $v3)
			{				
				if ($v3 == 's_cnt_total')
				{
					$s_cnt_total += $v2['s_cnt_total'];
				}
				elseif ($v3 == 's_cnt_gw')
				{
					$s_cnt_gw += $v2['s_cnt_gw'];
				}
				elseif ($v3 == 's_cnt_rpt')
				{
					$s_cnt_rpt += $v2['s_cnt_rpt'];
				}
				elseif ($v3 == 's_smo_total')
				{
					$s_smo_total += $v2['s_smo_total'];
				}
				echo '<td align="center" valign="middle">'.$v2[$v3].'</td>';
			}
			
			$show = $v2['s_cnt_gw'] + $v2['s_smo_total'];
			echo '<td align="center" valign="middle">'.$show.'</td>';
			$all_new += $show;
		?>
		</tr>
		<?php endforeach; ?>
		<tr>
		<td colspan="<?php echo (count($v2)-4); ?>">总量统计</td>
		<?php foreach ($list['showfield'] as $v3)
			{
				if (!in_array($v3, array('s_cnt_total', 's_cnt_gw', 's_cnt_rpt', 's_smo_total')))
					continue;
				else
					echo '<td align="center" valign="middle">'.${$v3}.'</td>';
			}	
			
			echo '<td align="center" valign="middle">'.$all_new.'</td>';
		?>
		</tr>
	</table></td>
	</tr>
</table>
</div>
<?php else :?>
<div id="body_box_r_tit" class="cu">搜索结果</div>
<div id="body_box_r_text_result"><?php echo $err_msg;?></div>
<?php endif; ?>
</form>
<script>
try{
	document.getElementById('searchByPage').onclick = searchSubmit;
}catch(e){
	
}
function searchSubmit()
{
	document.getElementById('search_form').submit();
}
</script>
</body>
</html>