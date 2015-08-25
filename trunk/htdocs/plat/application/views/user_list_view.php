<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/big_css.css">

<body>
<form name="user_info" method="POST" action="<?php echo site_url('user/user_search') ?>">
<div id="body_box_r_text"><table width="755" border="0" cellspacing="0" cellpadding="0" class="inputbiaodan">
    <tr> 
      <td width="111" align="right">查询：</td>
      <td width="240"><input name="content" type="text" /> </td>
       <td width="404"><input name="input" type="submit" value="查询"/></td>
    </tr>
	</table>
</div>
</form>

<div id="body_box_r_text"><table width="755" border="0" cellspacing="0" cellpadding="0" class="inputbiaodan">
    
    <tr>
      <td colspan="3" ><table width="727" border="0" cellspacing="0" cellpadding="0" class="biaoge">
        <tr>
          <td width="28" align="center" valign="middle" bgcolor="#EFEFEF">序号</td>
          <td width="59" align="center" valign="middle" bgcolor="#EFEFEF">账户名</td>
          <td width="45" align="center" valign="middle" bgcolor="#EFEFEF">姓名</td>
          <td width="58" align="center" valign="middle" bgcolor="#EFEFEF">账户类型</td>
          <td width="64" align="center" valign="middle" bgcolor="#EFEFEF">状态</td>
          <td width="81" align="center" valign="middle" bgcolor="#EFEFEF">管理项目编号</td>
          <td width="66" align="center" valign="middle" bgcolor="#EFEFEF">创建时间</td>
          <td width="61" align="center" valign="middle" bgcolor="#EFEFEF">公司</td>
          <td width="52" align="center" valign="middle" bgcolor="#EFEFEF">分机</td>
          <td width="88" align="center" valign="middle" bgcolor="#EFEFEF">邮箱</td>
          <td width="124" align="center" valign="middle" bgcolor="#EFEFEF">管理</td>
          
          </tr>
		<?php if (!empty($list)) foreach ($list as $v): ?>
        <tr>
          <td height="33" align="center" valign="middle"><?php echo $v['id']; ?>&nbsp</td>
          <td align="center" valign="middle"><?php echo $v['username']; ?>&nbsp</td>
          <td align="center" valign="middle"><?php echo $v['app_name']; ?>&nbsp</td>
          <td align="center" valign="middle"><?php echo $rolelist[$v['role_id']]; ?>&nbsp</td>
          <td align="center" valign="middle"><?php echo $user_status[$v['status']]; ?>&nbsp</td>
          <td align="center" valign="middle"><?php echo '无'; ?>&nbsp</td>
          <td align="center" valign="middle"><?php echo $v['create_date']; ?>&nbsp</td>
          <td align="center" valign="middle"><?php echo $v['company']; ?>&nbsp</td>
          <td align="center" valign="middle"><?php echo $v['app_ext']; ?>&nbsp</td>
          <td align="center" valign="middle"><?php echo $v['email']; ?>&nbsp</td>
          <td align="center" valign="middle">
		  <a href="<?php echo site_url('user/user_change/'.$v['id'])?>">编辑</a> |
		  <a href="<?php echo site_url('user/user_status/3/'.$v['id'])?>">删除</a><br />
		  <a href="<?php echo site_url('user/user_status/1/'.$v['id'])?>">开通</a> |
		  <a href="<?php echo site_url('user/user_status/2/'.$v['id'])?>">冻结</a><br /> 
		  <!-- <a href="<?php echo site_url('projects/project_app_change/'.$v['id'])?>">通道开通</a> -->
          </tr>
		<?php endforeach; ?>
      </table></td>
       </tr>
    <!--<tr>
      <td colspan="3" align="left" ><b>账号统计信息：</b><br />
        账号总计：<span class="red">N</span> 个<br />
        管理员：<span class="red">N</span> 个<br />
      普通账号：<span class="red">N</span> 个<br />
      合作方账号：<span class="red">N</span> 个</p>
        </td>
    </tr>-->
  </table>
</div>
<div id="body_box_r_btn"><input type="button" value=" 返回 " />
</div>
</body>
</html>