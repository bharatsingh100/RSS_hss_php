<?php //print_r($shakhas);//echo '<pre>' . print_r($shakha) . '</pre>';/* ?>
<h2><?=$vibhag->name?> Vibhag - Statistics</h2>
<p>&nbsp;</p>
<h3>Downloadable Reports</h3>
<ul>
  <li>
    <?php echo anchor('vibhag/all_vibhag_karyakarta_csv/'.$this->uri->segment(3),
                        'All Karyakartas in Vibhag'); ?> (MS Excel)
  </li>
  <li>
    <?php echo anchor('vibhag/all_shakhas_csv/'.$this->uri->segment(3),
                        'All Shakhas in Vibhag'); ?> (MS Excel)
  </li>
  <li><strong>Sankhya Reports</strong>
    <ul>
      <li><?php echo anchor('vibhag/all_sankhyas_csv/'. $this->uri->segment(3). '/0',
                        'This Months\'s Sankhya'); ?></li>
      <li><?php echo anchor('vibhag/all_sankhyas_csv/'. $this->uri->segment(3). '/1',
                        'Last Months\'s Sankhya'); ?></li>
      <li><?php echo anchor('vibhag/all_sankhyas_csv/'. $this->uri->segment(3). '/6',
                        'Last 6 Months\'s Sankhya'); ?></li>
      <li><?php echo anchor('vibhag/all_sankhyas_csv/'.$this->uri->segment(3),
                        'All Time Sankhya'); ?></li>
    </ul>
  </li>
</ul>
<p>&nbsp;</p>
<h3>Average Sankhya:</h3>
<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td><strong>Shakha</strong></td>
    <td><strong><?=date('M Y')?></strong></td>
    <td><strong><?=date('M Y', strtotime('-1 Month', strtotime(date('F Y'))))?></strong></td>
    <td><strong><?=date('M Y', strtotime('-2 Month', strtotime(date('F Y'))))?></strong></td>
    <td><strong><?=date('Y')?></strong></td>
    <td><strong><?=date('Y', strtotime('-1 Year', strtotime(date('Y'))))?></strong></td>
  </tr>
  <?php $i = 0; foreach($shakhas as $shakha): ?>
  <tr>
    <td><?=anchor('shakha/statistics/'.$shakha['shakha_id'], $shakha['name'])?></td>
    <td><?=(int)$shakha['this_month']?></td>
    <td><?=(int)$shakha['last_month']?></td>
    <td><?=(int)$shakha['last_last_month']?></td>
    <td><?=(int)$shakha['this_year']?></td>
    <td><?=(int)$shakha['last_year']?></td>
  </tr><?php endforeach; ?>
  <tr><td colspan="6">&nbsp;</td></tr>
  <tr>
    <td><strong>Vibhag Average</strong></td>
    <td><?=(int)$vibhag->sankhya['this_month']['total']?></td>
    <td><?=(int)$vibhag->sankhya['last_month']['total']?></td>
    <td><?=(int)$vibhag->sankhya['last_last_month']['total']?></td>
    <td><?=(int)$vibhag->sankhya['this_year']['total']?></td>
    <td><?=(int)$vibhag->sankhya['last_year']['total']?></td>
  </tr>

</table>
<p>&nbsp;</p>
<h3>Contact List:</h3>
<table width="50%" border="1" cellspacing="2" cellpadding="2">
  <tr>
    <td width="45%"><div align="right">Total Families</div></td>
    <td width="18%" align="center"><?=$vibhag->contacts['families']?></td>
    <td width="21%" align="center" valign="middle">Swayamsevak</td>
    <td width="16%" align="center" valign="middle">Sevika</td>
  </tr>
  <tr>
    <td><div align="right">Total Contacts</div></td>
    <td align="center"><?=$vibhag->contacts['contacts']?></td>
    <td align="center"><?=$vibhag->contacts['swayamsevaks']?></td>
    <td align="center"><?=$vibhag->contacts['sevikas']?></td>
  </tr>
  <tr>
    <td><div align="right">Shishu ( &lt; 6)</div></td>
    <td align="center"><?=$vibhag->contacts['shishu']?></td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td><div align="right">Bala (6 - 12)</div></td>
    <td align="center"><?=$vibhag->contacts['bala']?></td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td><div align="right">Kishor (13 - 18)</div></td>
    <td align="center"><?=$vibhag->contacts['kishor']?></td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td><div align="right">Yuva(18 - 25)</div></td>
    <td align="center"><?=$vibhag->contacts['yuva']?></td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td><div align="right">Tarun (26 - 50)</div></td>
    <td align="center"><?=$vibhag->contacts['tarun']?></td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td><div align="right">Praudh</div></td>
    <td align="center"><?=$vibhag->contacts['praudh']?></td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
</table>
