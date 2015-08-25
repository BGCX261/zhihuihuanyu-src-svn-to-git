<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/big_css.css">
<script type="text/javascript" src="<?php echo base_url()?>js/jquery-1.5.1.min.js"></script>
<script language="javascript">
function menu_open(num){	
	$(".childmenu").css("display", "none");
	var str_id = 'list'+num;
	document.getElementById(str_id).style.display = "block"
}
</script>
</head>

<body>
<?php if (!empty($menu_list)) { foreach ($menu_list as $k => $v1) { ?>
<div id="body_box_l_clouse">
	<div id="list02_css" style="font-weight:100; cursor:hand" onclick="menu_open('<?php echo $k;?>')">
		<img src="<?php echo base_url()?>images/icon_03.gif" width="6" height="10" />&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $menu_map[$k]; ?>
	</div>
	<div style="padding-left:10px; display: block" id="list<?php echo $k;?>" class="childmenu">
	<?php if (!empty($v1)) {  foreach ($v1 as $v2) { ?>	
		<img src="<?php echo base_url()?>images/icon_04.gif" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $v2['href']; ?>" target="main"><?php echo $v2['name']; ?></a><br />	
	<?php } } ?>
	</div>
</div>
<?php } } ?>
</body>
</html>