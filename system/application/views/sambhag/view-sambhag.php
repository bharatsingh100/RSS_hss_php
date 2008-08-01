<h2><?=$row->name?> Sambhag</h2>
<?php 
if(isset($row->kk))
{
	echo '<h3>Responsibilities:</h3>';
	foreach ($row->kk as $kk)
	{
		echo '<p><strong>',$kk->resp_title.'</strong>: ',anchor('profile/view/'.$kk->contact_id, $kk->first_name.' '.$kk->last_name),'</p>';
	}
	echo '<p>&nbsp;</p>';
}
?>
<h3>Vibhags:</h3>
<?php /*
echo "<ol>\n";
foreach($row->vibhags as $vibhag)
{
	echo '<li>'.anchor('vibhag/view/'.$vibhag->REF_CODE, $vibhag->short_desc);
	//echo ($shakha->shakha_status == 0) ? '<font color="red"> (Unactive)</font>': '';
	echo "</li>\n"; 
}
echo "</ol>\n" */
?>
<table id="tb">
  <tr align="left">
  	<th>&nbsp;</th>
    <th>Vibhag</th>
    <th>Karyavah</th>
    <th>Sah-Karyavah</th>
    <th>Sanghchalak</th>	
  </tr>
<?php $i = 1; foreach($row->vibhags as $vibhag) { ?>
  <tr>
  	<td valign="top"><?=$i++?></td>
    <td valign="top"><?=anchor('vibhag/view/'.$vibhag->REF_CODE, $vibhag->short_desc)?></td>
    <?php if(isset($vibhag->kk)) 
	{
		echo '<td valign="top">';
		foreach($vibhag->kk as $kk)
			if($kk->responsibility == '020') echo anchor('profile/view/'.$kk->contact_id, $kk->first_name.' '.$kk->last_name) . '<br />';
		echo '</td><td valign="top">';
		foreach($vibhag->kk as $kk)
			if($kk->responsibility == '021') echo anchor('profile/view/'.$kk->contact_id, $kk->first_name.' '.$kk->last_name) . '<br />';
		echo '</td><td valign="top">';
		foreach($vibhag->kk as $kk)
			if($kk->responsibility == '010') echo anchor('profile/view/'.$kk->contact_id, $kk->first_name.' '.$kk->last_name) . '<br />';
		echo '</td>';
	}
	else { ?>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <?php } }?>
  </tr>
</table>
