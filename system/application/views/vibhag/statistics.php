<?php //print_r($vibhag);//echo '<pre>' . print_r($shakha) . '</pre>';/* ?>
<h2><?=$vibhag->name?> Vibhag - Statistics</h2>
<p>&nbsp;</p>
<h3>Average Sankhya:</h3>
<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td><strong>Shakha</strong></td>
    <td><strong><?=date('F Y')?></strong></td>
    <td><strong><?=date('F Y', strtotime('-1 Month', strtotime(date('F Y'))))?></strong></td>
    <td><strong><?=date('F Y', strtotime('-2 Month', strtotime(date('F Y'))))?></strong></td>
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
  <tr><td colspan="6">&nbsp;</tr>
  <tr>
    <td><strong>Vibhag Average</strong></td>
    <td><?=(int)$vibhag->sankhya['this_month']['total']?></td>
    <td><?=(int)$vibhag->sankhya['last_month']['total']?></td>
    <td><?=(int)$vibhag->sankhya['last_last_month']['total']?></td>
    <td><?=(int)$vibhag->sankhya['this_year']['total']?></td>
    <td><?=(int)$vibhag->sankhya['last_year']['total']?></td>
  </tr>

</table>