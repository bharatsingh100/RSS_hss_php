<h2><?=$row->name?></h2>
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
<h3>Sambhags:</h3>
<table id="tb">
  <tr align="left">
  	<th>&nbsp;</th>
    <th>Sambhag</th>
    <th>Karyavah</th>
    <th>Sah-Karyavah</th>
    <th>Sanghchalak</th>	
  </tr>
<?php $i = 1; foreach($row->sambhags as $sambhag) { ?>
  <tr>
  	<td><?=$i++?></td>
    <td><?=anchor('sambhag/view/'.$sambhag->REF_CODE, $sambhag->short_desc)?></td>
    <?php if(isset($sambhag->kk)) 
	{
		echo '<td>';
		foreach($sambhag->kk as $kk)
			if($kk->responsibility == '020') echo anchor('profile/view/'.$kk->contact_id, $kk->first_name.' '.$kk->last_name);
		echo '</td><td>';
		foreach($sambhag->kk as $kk)
			if($kk->responsibility == '021') echo anchor('profile/view/'.$kk->contact_id, $kk->first_name.' '.$kk->last_name);
		echo '</td><td>';
		foreach($sambhag->kk as $kk)
			if($kk->responsibility == '010') echo anchor('profile/view/'.$kk->contact_id, $kk->first_name.' '.$kk->last_name);
		echo '</td>';
	}
	else { ?>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <?php } }?>
  </tr>
</table>
