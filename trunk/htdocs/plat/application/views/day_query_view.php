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
<form name="search_form" id="search_form" method="post" action="<?php echo site_url('data_query/day_query'); ?>">
<div id="body_box_r_tit" class="cu">选择搜索条件（日）</div>
<div id="body_box_r_text">
	<table width="755" border="0" cellspacing="0" cellpadding="0" class="inputbiaodan">
    <tr>
    	<td align="right">日期：</td>
    	<td>
	      <?php if (empty($param['getdate'])) { $now_y = date('Y'); $now_m = date('m'); $now_d = date('d'); $now_stime= 0; $now_etime= 24;} else {$now_y = substr($param['getdate'], 0, 4); $now_m = intval(substr($param['getdate'], 4, 2)); $now_d = intval(substr($param['getdate'], 6, 2));} ?>
		  <select name="year"><?php for($i=2013; $i<2020; $i++) { echo '<option value="'.$i.'"'.($i==$now_y ?' selected':'').'>'.$i.'年</option>'; }?></select> 
		  <select name="month"><?php for ($i=1; $i<13; $i++) { echo '<option value="'.$i.'"'.($i==$now_m ?' selected':'').'>'.$i.'月</option>';} ?></select> 
		  <select name="day"><?php for ($i=1; $i<32; $i++) { echo '<option value="'.$i.'"'.($i==$now_d ?' selected':'').'>'.$i.'日</option>';} ?></select> 
      	</td>
    </tr>
    <tr>
    	<td align="right" valign="top">上下行：</td>
    	<td>
    		<select name="query_type"  disabled="true">
				<option value="1" <?php if (!empty($param['query_type']) && ($param['query_type'] == 1)) echo ' selected';?>>下行</option>
				<option value="2" <?php if (!empty($param['query_type']) && ($param['query_type'] == 2)) echo ' selected';?>>上行</option>
			</select>
		</td>
    </tr>
	<tr>
		<td align="right">项目编号(from值)：</td>
		<td width="601">
        <select name="project_no">
		<?php
		echo '<option value="all"';
		if (!empty($param['project_no']) && false !== strpos($param['project_no'], ',')) {
			echo ' selected';
		}
		echo '>全部</option>';
		
		foreach ($pj as $one_project) { 
			echo '<option value="'.$one_project['project_no'].'"';
			$with_semicolon = "'".$one_project['project_no']."'";
			if (!empty($param['project_no']) && 0 == strcmp($param['project_no'], $with_semicolon)) {
				echo ' selected';
			}
			echo '>'.$one_project['project_no'].'</option>';
		}
		?>
		</select>
		<font color='red'>当查询上行数据时，本项无效</font>
		</td>
    </tr>
    <tr>
    	<td align="right">显示字段：</td>
    	<td>
    		<input name="showfield[]" type="checkbox" value="msgtime" <?php if (!empty($param['check_field']) && in_array('msgtime', $param['check_field'])) echo 'checked'; ?> />时间
    		<input name="showfield[]" type="checkbox" value="src_no" <?php if (!empty($param['check_field']) && in_array('src_no', $param['check_field'])) echo 'checked'; ?>/>长号码
    		<input name="showfield[]" type="checkbox" value="msg" <?php if (!empty($param['check_field']) && in_array('msg', $param['check_field'])) echo 'checked'; ?>/>上/下行内容
    		<input name="showfield[]" type="checkbox" value="project_no" <?php if (!empty($param['check_field']) && in_array('project_no', $param['check_field'])) echo 'checked'; ?>/>项目编号(from值)
	  	</td>
    </tr>
	<tr>
      <td align="right" valign="top">手机号码：</td>
      <td>
      	<input type="text" name="charge" id="charge" maxlength="11" value="<?php echo empty($param['charge'])? '':$param['charge']; ?>" />
		<font color='red'>不填写本项，则表示查询全部数据</font>
      </td>
    </tr>    	
	<tr>
      <td align="right" valign="top">长号码：</td>
      	<td>
	      	<input type="text" name="src_no" id="src_no" maxlength="20" value="<?php echo empty($param['src_no'])? '':$param['src_no']; ?>" />
			<input name="sql_like[]" type="checkbox" value="sql_like_src_no" <?php if (!empty($param['sql_like']) && in_array('sql_like_src_no', $param['sql_like'])) echo 'checked'; ?>/>启用模糊匹配
			<font color='red'>当查询上行数据时，本项必填</font>
		</td>
    </tr>    	
    <tr>
	  <td colspan="2" align="center" valign="top"><input type="submit" value="搜索"/></td>
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
			页/总共 <span class="cu red"><?php echo $list['total_pages'] ?></span>
			页 跳转至第
			<input name="jumppage" type="text" id="jumppage" size="3" maxlength="4" /><input type='button' id='searchByPage' value="页" />
		</td>
    </tr>
    <tr>
      <td colspan="2" >
      	<table width="732" border="0" cellspacing="0" cellpadding="0" class="biaoge">
        <tr>
		<?php foreach ($showfield as $v1) { echo '<td align="center" valign="middle" bgcolor="#EFEFEF">'.$filed_map[$v1].'</td>'; }?>
        </tr>
		<?php foreach ($list['result'] as $v2): ?>
		<tr>
		<?php foreach ($showfield as $v3)
		{
			if(0 == strcmp('stat_rpt', $v3) && 0 == strcmp('0', $v2[$v3])) {
				echo '<td align="center" valign="middle">成功</td>';				
			}else {
				echo '<td align="center" valign="middle">'.iconv('gbk', 'utf-8', $v2[$v3]).'</td>';
			}
		}
		?>
		</tr>
		<?php endforeach; ?>
		</table>
	  </td>
	</tr>  
</table>
</div>
<?php else :?>
<div id="body_box_r_tit" class="cu">搜索结果</div>
<div id="body_box_r_text_result"><?php echo $err_msg;?></div>
<?php endif;?>
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