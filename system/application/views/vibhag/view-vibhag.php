<h2><?=$row->name?> Vibhag</h2>
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
<?php if(isset($row->nagars)) : ?>
<p>&nbsp;</p>
<h3>Nagars:</h3>
<table id="tb">
  <tr align="left">
  	<th>&nbsp;</th>
    <th>Nagar</th>
    <th>Karyavah</th>
    <th>Sah-Karyavah</th>
  </tr>
<?php $i = 1; foreach($row->nagars as $nagar) { ?>
  <tr>
  	<td valign="top"><?=$i++?></td>
    <td valign="top"><?=anchor('nagar/view/'.$nagar->nagar_id, $nagar->name)?></td>
    <?php 	if(isset($nagar->kk))
	{
		echo '<td valign="top">';
		foreach($nagar->kk as $kk)
			if($kk->responsibility == '020') echo anchor('profile/view/'.$kk->contact_id, $kk->first_name.' '.$kk->last_name) . '<br />';
		echo '</td><td valign="top">';
		foreach($nagar->kk as $kk)
			if($kk->responsibility == '021') echo anchor('profile/view/'.$kk->contact_id, $kk->first_name.' '.$kk->last_name) . '<br />';
		echo '</td>';
	}
	else { ?>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <?php } }?>
  </tr>
</table>
<?php endif; ?>
<?php $shakha_count = $sp_count = 0;
		foreach($row->shakhas as $shakha):
			if($shakha->shakha_status == 0) $sp_count++; else $shakha_count++; endforeach; ?>
<p>&nbsp;</p>
<?php if($shakha_count > 0): ?>
<h3>Shakhas:</h3>
<table id="tb">
  <tr align="left">
  	<th>&nbsp;</th>
    <th>Shakha</th>
    <th>Karyavah</th>
    <th>Mukhya Shikshak</th>
  </tr>
<?php
	$i = 1;
	foreach($row->shakhas as $shakha) {
		if($shakha->shakha_status == 0) continue;
?>
  <tr>
  	<td valign="top"><?=$i++?></td>
    <td valign="top"><?=anchor('shakha/view/'.$shakha->shakha_id, $shakha->name)?><?php echo ($shakha->shakha_status == 0) ? '<font color="red"> (Inactive)</font>': '';?></td>
    <?php 	if(isset($shakha->kk))
	{
		echo '<td valign="top">';
		foreach($shakha->kk as $kk)
			if($kk->responsibility == '020') echo anchor('profile/view/'.$kk->contact_id, $kk->first_name.' '.$kk->last_name) . '<br />';
		echo '</td><td valign="top">';
		foreach($shakha->kk as $kk)
			if($kk->responsibility == '030') echo anchor('profile/view/'.$kk->contact_id, $kk->first_name.' '.$kk->last_name) . '<br />';
		echo '</td>';
	}
	else { ?>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <?php } }?>
  </tr>
</table>
<?php endif; if($sp_count > 0): ?>
<p>&nbsp; </p>
<h3>Sampark Kendras: (Inactive Shakhas)</h3>
<table id="tb">
  <tr align="left">
  	<th>&nbsp;</th>
    <th>Name</th>
    <th>Karyavah</th>
    <th>Mukhya Shikshak</th>
  </tr>
<?php
	$i = 1;
	foreach($row->shakhas as $shakha) {
		if($shakha->shakha_status == 1) continue;
?>
  <tr>
  	<td valign="top"><?=$i++?></td>
    <td valign="top"><?=anchor('shakha/view/'.$shakha->shakha_id, $shakha->name)?></td>
    <?php 	if(isset($shakha->kk))
	{
		echo '<td valign="top">';
		foreach($shakha->kk as $kk)
			if($kk->responsibility == '020') echo anchor('profile/view/'.$kk->contact_id, $kk->first_name.' '.$kk->last_name) . '<br />';
		echo '</td><td valign="top">';
		foreach($shakha->kk as $kk)
			if($kk->responsibility == '030') echo anchor('profile/view/'.$kk->contact_id, $kk->first_name.' '.$kk->last_name) . '<br />';
		echo '</td>';
	}
	else { ?>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <?php } }?>
  </tr>
</table>
<p>&nbsp; </p>
<?php endif; ?>
