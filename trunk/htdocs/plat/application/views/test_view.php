<html>
<head>
<script type="text/javascript" src="<?php echo base_url()?>js/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/jquery.form.js"></script>
<script>
function set_height()
{
	var hh = $(document).height();
	alert(hh);
	//$(document).height('888');
	document.getElementById("main").height=1000;
	//document.height=main.document.body.scrollHeight;
	//$(document).height(800);
}
</script>
</head>
<body>
<input type=button onclick="javascript:set_height()" value="设置">
</body>
</html>