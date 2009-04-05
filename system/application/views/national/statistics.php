<?php //echo '<pre>' . print_r($shakha) . '</pre>'; ?>
<h2>National - Statistics</h2>
<p>&nbsp;</p>
<h3>Downloadable Reports</h3>
<ul><li><?php echo anchor('national/all_karyakarta_csv/', 'All Karyakartas in HSS'); ?> (MS Excel)</li></ul>
<p>&nbsp;</p>
<?php //print_r($stats); ?>
<h3>Sambhag Statistics:</h3>
<table width="100%" border="0" cellspacing="2" cellpadding="2" class="sambhags">
	<tr>
		<th>Sambhag</th>
		<th>Active Shakhas</th>
		<th>Sampark Kendras</th>
		<th>Weekly Shakhas</th>
		<th>Karyakartas*</th>
	</tr>
	<?php 
		$active_shakhas = $sampark_kendras = $weekly_shakhas = $karyakartas = 0;
		foreach($stats as $sambhag): ?>
	<tr>
		<td><?php echo anchor('sambhag/statistics/'.$sambhag->sambhag_id, $sambhag->name); ?></td>
		<td><?php echo $sambhag->active_shakhas; $active_shakhas +=  $sambhag->active_shakhas;?></td>
		<td><?php echo $sambhag->sampark_kendras; $sampark_kendras += $sambhag->sampark_kendras;?></td>
		<td><?php echo $sambhag->weekly_shakhas;  $weekly_shakhas += $sambhag->weekly_shakhas;?></td>
		<td><?php echo $sambhag->karyakartas;  $karyakartas += $sambhag->karyakartas;?></td>
	</tr>
	<?php endforeach; ?>
	<tr><td colspan="4">&nbsp;</tr>
	<tr>
	    <td><strong>Total:</strong></td>
	    <td><?php echo $active_shakhas;?></td>
	    <td><?php echo $sampark_kendras;?></td>
	    <td><?php echo $weekly_shakhas;?></td>
	    <td><?php echo $karyakartas;?></td>
	</tr>
</table>
<p>&nbsp;</p>
<h4>Karyakarta = Swayamsevak with a responsibility.</h4>
<p>&nbsp;</p>
<h3>Contact List:</h3>
<table width="50%" border="1" cellspacing="2" cellpadding="2">
  <tr>
    <td width="45%"><div align="right"><strong>Total Families</strong></div></td>
    <td width="18%" align="center"><?=$national->contacts['families']?></td>
    <td width="21%" align="center" valign="middle">Swayamsevak</td>
    <td width="16%" align="center" valign="middle">Sevika</td>
  </tr>
  <tr>
    <td><div align="right">Total Contacts</div></td>
    <td align="center"><?=$national->contacts['contacts']?></td>
    <td align="center"><?=$national->contacts['swayamsevaks']?></td>
    <td align="center"><?=$national->contacts['sevikas']?></td>
  </tr>
  <tr>
    <td><div align="right">Shishu ( &lt; 6)</div></td>
    <td align="center"><?=$national->contacts['shishu']?></td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td><div align="right">Bala (6 - 12)</div></td>
    <td align="center"><?=$national->contacts['bala']?></td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td><div align="right">Kishor (13 - 18)</div></td>
    <td align="center"><?=$national->contacts['kishor']?></td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td><div align="right">Yuva(18 - 25)</div></td>
    <td align="center"><?=$national->contacts['yuva']?></td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td><div align="right">Tarun (26 - 50)</div></td>
    <td align="center"><?=$national->contacts['tarun']?></td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td><div align="right">Praudh</div></td>
    <td align="center"><?=$national->contacts['praudh']?></td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p><!--
<strong>Active Shakhas:</strong> <?php //echo $stats['active_shakhas']; ?><br />
<strong>Sampark Kendras:</strong> <?php //echo $stats['sampark_kendras']; ?><br />
<strong>Weekly Shakhas:</strong> <?php //echo $stats['weekly_shakhas']; ?><br />

--><!--
<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td>Date</td>
    <td>Info</td>
    <td>Total</td>
    <td>Shishu</td>
    <td>Bala</td>
    <td>Kishor</td>
    <td>Yuva</td>
    <td>Tarun</td>
    <td>Mahila</td>
    <td>Praudh</td>
  </tr>
  <tr>
    <td>10/14/07</td>
    <td>&nbsp;</td>
    <td>15</td>
    <td>5</td>
    <td>4</td>
    <td>2</td>
    <td>3</td>
    <td>0</td>
    <td>4</td>
    <td>0</td>
  </tr>
  <tr>
    <td>10/07/07</td>
    <td>No Shakha</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>09/30/07</td>
    <td>Celebrated Utsav</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<h3>Other:</h3>
<p>Swayamsevaks with at least 1 phone # : <?=$phone;?></p>
<p>Swayamsevaks with active e-mail: <?=$email?></p>
<p>Swayamsevaks with inactive (unsubscribed, bounced) e-mails: <?=$email_unactive?></p>
<p>&nbsp;</p>
-->