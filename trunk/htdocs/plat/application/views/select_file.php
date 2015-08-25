<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>选择文件</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/big_css.css">
<script type="text/javascript" src="<?php echo base_url()?>js/jquery-1.5.1.min.js"></script>
</head>

<body>  
<form name="frm"> 
<table> 
	<tr><td>
		<div class="gCaption">发送申请表</div>
				<?php 
					$qlw_user_id = $this->access->get_user_id(); 
					$qlw_user_info = $this->users->get_user_info($qlw_user_id); 
					$dir = '/data0/apache/rsyncdata/'.$qlw_user_info['username'];
  				$handle = opendir($dir);
  				$file_list = array();
   				while (false !== ($file = readdir($handle)))
   				{
    				if ($file != "." && $file != "..")
    				{
     					$file_list[]=$file; 
    				}
   				}
				?>
				
      	<?php 
        	foreach ($file_list as $key => $value)
        	{        	
        		echo "<input type=\"radio\" name=\"upload_phone_new\" id=\"upload_phone_new\" value=\"$value\"/>$value | ";
      	  }         
     		?>			 
     		
     		<td><input type="submit" accesskey="s" id="submit1" /></td>

			 
          </div>
	</td></tr>
</table>
 </form>
 


</body>
</html>