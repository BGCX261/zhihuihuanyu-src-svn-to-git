<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/big_css.css">
<title><?php echo $title; ?></title>
</head>

<body>
<div id="top_banner"></div>
<div id="tit_bg"><div id="tit_bg_l">登陆用户：<b><?php echo $user_info['app_name']; ?></b>&nbsp; <img src="<?php echo base_url()?>images/icon_01.gif" width="11" height="14" /> &nbsp;<a href="<?php echo site_url('home/logout'); ?>">安全退出</a></div>
<div id="tit_bg_r"><img src="<?php echo base_url()?>images/icon_02.gif" width="11" height="14" /> &nbsp;</div>
</div>

<div id="body_box">
<div id="body_box_l">
<iframe name="menu" width="100%" frameborder=0 height="520" src="<?php echo site_url('home/menu'); ?>" id="menu" ></iframe>
</div>
<div id="body_box_r">
<iframe marginwidth=0 marginheight=0 SRC="" width="100%"  height="100%"  id="main" name="main"    frameborder="0"   scrolling="no" onload="this.height=main.document.body.scrollHeight;"></iframe>

</div>
<div class="both"></div>
<div id="inc">短信平台 版权所有</div>
</div>

</body>
<script>
document.getElementById('menu').onload = function(){
        //this.style.height = this.contentWindow.document.body.offsetHeight + 'px';
        //menuClick();
        this.contentWindow.$('div').eq(0).find('>div:first').click();
};

function menuClick(){
        document.getElementById('main').style.height = document.getElementById('menu').offsetHeight +  'px';
}
</script>
</html>