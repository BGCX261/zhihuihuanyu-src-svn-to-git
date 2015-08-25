<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/big_css.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/thickbox.css">
<script type="text/javascript" src="<?php echo base_url()?>js/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/thickbox_plus.js"></script>

<body>
<div id="body_box_r_tit" class="cu">短信群发</div>
<form name="group_send_info" method="POST" enctype='multipart/form-data' action="<?php echo $action['value'] ?>">
<input type="hidden" name="isPost" value="TRUE">
<div id="body_box_r_text"><table width="755" border="0" cellspacing="0" cellpadding="0" class="inputbiaodan">
    <tr>
      <td width="213" align="right">FROM值：</td>
		<td width="300">
	  
	  <?php if ($this->access->is_admin()): ?>
	  	<input name="project_no" type="text" id="project_no" size="30" value="<?php if (!empty($pj_list['project_no'])) echo $pj_list['project_no'];?>" />
	  <?php else: ?>
	     
	    <select name="project_no">
		<?php foreach ($from_list as $k => $v) { if ($pj_list['project_no'] != $k) echo "<option value=".$k.">$v</option>"; else echo "<option value=".$k." selected>$v</option>";} ?>
		</select>
	  <?php endif; ?>
	  
      </td>
    </tr>

    <tr>
      <td width="211" align="right">长号码：</td>
      <td width="300">
        <input name="longnum" type="text" id="longnum" size="30" value="<?php 

        if (!empty($pj_list['longnum'])) echo $pj_list['longnum'];?>" />
      </td>
    </tr>
    
    
    <tr>
      <td align="right">下发时间：</td>
      <td>
      	<select name="sendtime_y"><?php for($i=2012; $i<2013; $i++) { if (date('y',strtotime($pj_list['gs_start'])) != $i) echo '<option value="'.$i.'">'.$i.'年</option>'; else echo '<option value="'.$i.'" selected>'.$i.'年</option>';} ?></select> 
      
      	<select name="sendtime_m"><?php for ($i=1; $i<13; $i++) { if (date('n',strtotime($pj_list['gs_start'])) != $i) echo '<option value="'.$i.'">'.$i.'月</option>'; else echo '<option value="'.$i.'" selected>'.$i.'月</option>';} ?></select> 

      	<select name="sendtime_d"><?php for ($i=1; $i<32; $i++){ if (date('j',strtotime($pj_list['gs_start'])) != $i) echo '<option value="'.$i.'">'.$i.'日</option>'; else echo '<option value="'.$i.'" selected>'.$i.'日</option>';} ?></select> 
      	
      	<select name="sendtime_h"><?php for ($i=0; $i<24; $i++) { if (date('G',strtotime($pj_list['gs_start'])) != $i) echo '<option value="'.$i.'">'.$i.'时</option>'; else echo '<option value="'.$i.'" selected>'.$i.'时</option>';} ?></select> 
      	
      	<select name="sendtime_i"><?php for ($i=0; $i<60; $i++) { if (date('i',strtotime($pj_list['gs_start'])) != $i) echo '<option value="'.$i.'">'.$i.'分</option>'; else echo '<option value="'.$i.'" selected>'.$i.'分</option>';} ?></select></td>
    </tr>
    <tr>
    <tr>
      <td align="right">输入手机号码(最多100个)：</td>
      <td width="263" colspan="2"><textarea name="input_phone" type="text" id="input_phone" rows="10" cols="12"/><?php 
		if (!empty($pj_list['phone_list'])) 
		{
			if ($pj_list['phone_list'][0] === 'M')
			{
				echo "";
			}
			else
			{
				echo $pj_list['phone_list']; 
			}
		}
		else 
			echo ""; 
      ?></textarea></td>
    </tr>
    
     <tr>
      <td align="right">批量导入号码(最多30万)：</td>
      <td colspan="2"><select name="upload_phone_special" id="upload_phone_special" value=" 批量添加 " />
				<?php 
					$qlw_user_id = $this->access->get_user_id(); 
					$qlw_user_info = $this->users->get_user_info($qlw_user_id); 
					$dir = '/data0/apache/rsyncdata/'.$qlw_user_info['username'];
					
					if(!is_dir($dir))
  				{
						echo "<option value=\"\">无号码</option>";
				  }			
					else
					{
  				$handle = opendir($dir);
  				$file_list = array();
   				while (false !== ($file = readdir($handle)))
   				{
    				if ($file != "." && $file != "..")
    				{
     					$file_list[]=$file; 
    				}
   				}
   			
				
					echo "<option value=\"\"></option>";
				
     
        	foreach ($file_list as $key => $value)
        	{        	
        		echo "<option value=\"$value\">$value</option>";
      	  }   
      	}      
     		?>			 

				
				</select><?php 
			if (!empty($pj_list['phone_list'])) 
		{
			if ($pj_list['phone_list'][0] === 'M')
			{
				echo "号码文件已填加";
			}
			else
			{
				echo "";
			}
		}
		else 
			echo ""; 
      ?></td>
      
      

      
    </tr> 
    <tr>
      <td align="right">优先文件方式提交的号码</td>
    </tr>       
    <tr>
      <td colspan="3" align="left" valign="top">短信内容：</td>
    </tr>
    <tr>
    
      <td colspan="3" align="center" valign="top">
<!--        <textarea name="sms_msg" id="sms_msg" cols="70" rows="4" class="hui" onfocus="if(value=='请输入短信内容') {value=''}" onblur="if (value=='') {value='请输入短信内容'}">请输入短信内容</textarea>  -->
		<textarea name="sms_msg" id="sms_msg" cols="70" rows="4" class="hui" value="" ><?php if (!empty($pj_list['sms_msg'])) echo $pj_list['sms_msg']; else echo ""; ?></textarea> 

		 
      </td>
    </tr>
  </table>
</div>
  <div id="body_box_r_btn"><input type="submit" value=" <?php echo $action['name']?> " />&nbsp;</div>
 </form>
</body>
</html>