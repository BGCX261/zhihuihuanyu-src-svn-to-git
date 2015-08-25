<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/big_css.css">
<link type="text/css" href="<?php echo base_url()?>css/checktree.css" rel="stylesheet" />	
	<script type="text/javascript" src="<?php echo base_url()?>js/jquery-1.4.4.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url()?>js/jquery.checktree_yctin.min.js"></script>
	
	<script type="text/javascript" src="<?php echo base_url()?>js/jquery.updateWithJSON.min.js"></script>

	<script type="text/javascript">
	var $checktree;
	$(function(){
		$checktree = $("ul.tree").checkTree({collapseAll:true});
		$("#access_roles input[name='roleids']").click(function(){set_checkbox($(this).val())});
	});
	function set_checkbox(id)
	{
		$.getJSON("<?php echo site_url('acl/get_res_from_role');?>" + "/"+id,{},function(json){
			$checktree.clear();
			$.updateWithJSON(json);
			$checktree.update();
		});
	}

	function clearAll(){
		$checktree.clear();
		$checktree.update();
	}	
	
	</script>
</head>

<body>
	<form method="post" id="aclform" action="<?php echo site_url('acl/modify_acl'); ?>">
	<table width=90% cellspacing=0>
    <tr>
        <td width=33%><b>角色</b></td>
        <td width=33%><b>模块</b></td>
    </tr>
    <tr>
        <td>
		<div class="advanced_view_tree">
			<ul id="access_roles" class="treeview">
				<?php foreach ($rolelist as $roleid => $v1): ?>
				<li id="roles" class="open">
				<input type="radio" name="roleids" value="<?php echo $roleid ?>">
				<span><a href='<?php echo site_url('acl/modify_role/'.$roleid) ?>' onclick="JavaScript:ajax_get(this.href, 'maincontent');return false;"><?php echo $v1 ?></a></span></li>
				<?php endforeach; ?>
			</ul>
		</div>
		</td>
        <td>
	<div class="advanced_view_tree">
		<ul class="tree" id="access_resources">
			<?php foreach ($reslist as $cl_name => $value1)	{ ?>
            <li><input type="checkbox">
			<label><?php echo $cl_name;?></label>
			<ul><?php foreach ($value1 as $v2) { ?>
				<li id="resources"><input type="checkbox" name="resids[]" value="<?php echo $v2['id'];?>"><span class="icon_cross"><a href="<?php echo site_url('acl/modify_res/'.$v2['id']) ?>" onclick="JavaScript:ajax_get(this.href, 'maincontent');return false;"><?php echo $v2['res_name'] ?></a></span></li><?php } ?>
			</ul>
			</li><?php } ?>
		</ul>
	</div>
	</td>
    </tr>
	<tr>
		<td colspan="2" align="center"><input type="submit" name="submit" value="提交权限修改"></td>
	</tr>
	</table>
	</form>
</body>
</html>