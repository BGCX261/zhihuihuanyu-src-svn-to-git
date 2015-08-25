<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/big_css.css">
<table width="755" border="0" cellspacing="0" cellpadding="0" class="inputbiaodan">
    <tr>
      <td width="643" align="right">&nbsp;</td>
      <td width="112" align="center"><a href="<?php echo site_url('data_query/day_query')?>">返回</a></td>
    </tr>

    <tr>
      <td colspan="2" ><table width="732" border="0" cellspacing="0" cellpadding="0" class="biaoge">
        <tr>
		<?php foreach ($showfield as $v1) { echo '<td align="center" valign="middle" bgcolor="#EFEFEF">'.$filed_map[$v1].'</td>'; }?>
        </tr>
		<?php foreach ($list as $v2): ?>
		<tr>
		<?php foreach ($showfield as $v3)
			{
				echo '<td align="center" valign="middle">'.iconv('gbk', 'utf-8', $v2[$v3]).'</td>';
			}
		?>
		</tr>
		<?php endforeach; ?>
	</table></td>
	</tr>
 </table>