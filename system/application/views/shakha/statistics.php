<?php //echo '<pre>' . print_r($shakha) . '</pre>'; ?>
<h2><?=$shakha->name;?> - Statistics</h2>
<h3>Contact List:</h3>
<table width="50%" border="1" cellspacing="2" cellpadding="2">
  <tr>
    <td width="45%"><div align="right">Total Families</div></td>
    <td width="18%" align="center"><?=$families?></td>
    <td width="21%" align="center" valign="middle">Swayamsevak</td>
    <td width="16%" align="center" valign="middle">Sevika</td>
  </tr>
  <tr>
    <td><div align="right">Total Contacts</div></td>
    <td align="center"><?=$contacts?></td>
    <td align="center"><?=$swayamsevaks?></td>
    <td align="center"><?=$sevikas?></td>
  </tr>
  <tr>
    <td><div align="right">Shishu ( &lt; 6)</div></td>
    <td align="center"><?=$shishu?></td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td><div align="right">Bala (6 - 12)</div></td>
    <td align="center"><?=$bala?></td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td><div align="right">Kishor (13 - 18)</div></td>
    <td align="center"><?=$kishor?></td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td><div align="right">Yuva(18 - 25)</div></td>
    <td align="center"><?=$yuva?></td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td><div align="right">Tarun (26 - 50)</div></td>
    <td align="center"><?=$tarun?></td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td><div align="right">Praudh</div></td>
    <td align="center"><?=$praudh?></td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<h3>Sankhya:</h3>
<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td><strong>Date</strong></td>
    <td><strong>Total</strong></td>
    <td><strong>Shishu</strong></td>
    <td><strong>Bala</strong></td>
    <td><strong>Kishor</strong></td>
    <td><strong>Yuva</strong></td>
    <td><strong>Tarun</strong></td>
    <td><strong>Praudh</strong></td>
    <td><strong>Families</strong></td>
  </tr>
  <?php $i = 0; foreach($sankhyas as $sankhya): ?>
  <tr>
    <td><?php echo anchor('shakha/add_sankhya/'.$shakha->shakha_id.'/'.$sankhya->date, $sankhya->date);?></td>
    <td><?=$sankhya->total?></td>
    <td><?=($sankhya->shishu_m + $sankhya->shishu_f)?></td>
    <td><?=($sankhya->bala_m + $sankhya->bala_f)?></td>
    <td><?=($sankhya->kishor_m + $sankhya->kishor_f)?></td>
    <td><?=($sankhya->yuva_m + $sankhya->yuva_f)?></td>
    <td><?=($sankhya->tarun_m + $sankhya->tarun_f)?></td>
    <td><?=($sankhya->praudh_m + $sankhya->praudh_f)?></td>
    <td><?=($sankhya->families)?></td>
  </tr>
  <?php if($sankhya->shakha_info != ''): ?>
  	<tr><td colspan="8"><?=$sankhya->shakha_info?></td></tr>
  <?php endif; if(++$i == 10) break; endforeach; ?>
</table>
<p>&nbsp;</p>
<h3>Bouncing or Unsubscribed E-mail Addresses:</h3>
<?php 
	$out = '<ol>';
	foreach($invalid as $contact): 
		$out .= '<li>' . anchor('profile/view/'.$contact->contact_id, "{$contact->first_name} {$contact->last_name} ({$contact->email})") . '</li>';
	endforeach;
	$out .= '</ol>';
	echo $out;
?>


<p>&nbsp;</p>
<h3>Other:</h3>
<p>Swayamsevaks with at least 1 phone # : <?=$phone;?></p>
<p>Swayamsevaks with active e-mail: <?=$email?></p>
<p>Swayamsevaks with inactive (unsubscribed, bounced) e-mails: <?=$email_unactive?></p>
<p>&nbsp;</p>
