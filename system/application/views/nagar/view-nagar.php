<h2><?=$row->name?> Nagar</h2>
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
<p>&nbsp;</p>
<h3>Shakhas:</h3>
<p>
  <?php /*
$resp_ar = array('030','020');
echo "<ol>\n";
foreach($row->shakhas as $shakha)
{
	echo '<li>',anchor('shakha/view/'.$shakha->shakha_id, $shakha->name);
	echo ($shakha->shakha_status == 0) ? '<font color="red"> (Unactive)</font>': '';
	echo "</li>\n";
	if(isset($shakha->kk))
	{
		foreach($shakha->kk as $k)
		{
			echo '<ul>';
			if(in_array($k->responsibility, $resp_ar))
			{
				//echo "<li>$k->resp_title: ";
				echo '<li>',anchor('profile/view/'.$k->contact_id, $k->first_name.' '.$k->last_name);
				echo "&nbsp;($k->resp_title)",'</li>';
			}
			echo '</ul>';
		}
	}

}
echo "</ol>\n"
*/?>
</p>
<table id="tb">
  <tr align="left">
  	<th>&nbsp;</th>
    <th>Shakha</th>
    <th>Karyavah</th>
    <th>Mukhya Shikshak</th>
  </tr>
<?php $i = 1; foreach($row->shakhas as $shakha) { ?>
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
<p>&nbsp; </p>
