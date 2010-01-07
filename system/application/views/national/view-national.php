<h2><?=$row->name?></h2>
<?php
if(isset($row->kk))
{
	echo '<h3>Responsibilities:</h3>';
	$karyakartas = array();
	$out = '';
	foreach ($row->kk as $kk)
	{
	  $karyakartas[$kk->resp_title][] = anchor('profile/view/'.$kk->contact_id, $kk->first_name.' '.$kk->last_name);
	}
	foreach($karyakartas as $responsibility => $names) {
	  $out .= '<p><strong>'.$responsibility.'</strong>: '. implode(', ', $names) .'</p>';
	}
	echo $out;
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
  	<td valign="top"><?=$i++?></td>
    <td valign="top"><?=anchor('sambhag/view/'.$sambhag->REF_CODE, $sambhag->short_desc)?></td>
    <?php if(isset($sambhag->kk))
	{
		echo '<td valign="top">';
		foreach($sambhag->kk as $kk)
			if($kk->responsibility == '020') echo anchor('profile/view/'.$kk->contact_id, $kk->first_name.' '.$kk->last_name) . '<br />';
		echo '</td><td valign="top">';
		foreach($sambhag->kk as $kk)
			if($kk->responsibility == '021') echo anchor('profile/view/'.$kk->contact_id, $kk->first_name.' '.$kk->last_name) . '<br />';
		echo '</td><td valign="top">';
		foreach($sambhag->kk as $kk)
			if($kk->responsibility == '010') echo anchor('profile/view/'.$kk->contact_id, $kk->first_name.' '.$kk->last_name) . '<br />';
		echo '</td>';
	}
	else { ?>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <?php } }?>
  </tr>
</table>
