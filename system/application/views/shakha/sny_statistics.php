<h2>SNY - Statistics</h2>
<?php echo anchor('shakha/sny_stats/'.$this->uri->segment(3), 'Complete SNY Stats in Excel Format');?>
<p></p>
<h3>Click the header to sort table by different columns:</h3>
<table id="sny_statistics" cellspacing=10>
	<thead>
		<tr>
			<th>No.</th>
    		<th>Shakha</th>
    		<th>Participants</th>
    		<th>SNY Counts</th>
    		<th>Nagar</th>
    		<th>Vibhag</th>
    		<th>Sambhag</th>
		</tr>
	</thead>
	<tbody>
		<?php
    		$i = 0;
    		foreach($counts['counts'] as $count):
		?>
		<tr>
			<th><?php echo ++$i;?></th>
			<td><?php echo anchor('shakha/view/'.$count->shakha_id, $count->name);?></td>
			<td><?php echo $count->total; ?></td>
			<td><?php echo $count->total_ss + $count->total_s;?></td>
			<td><?php echo !empty($count->nagar_id) ? anchor('nagar/view/'.$count->nagar_id, $counts['descriptions'][$count->nagar_id . '3']) : '';?></td>
			<td><?php echo anchor('vibhag/view/'.$count->vibhag_id, $counts['descriptions'][$count->vibhag_id . '2']);?></td>
			<td><?php echo anchor('sambhag/view/'.$count->sambhag_id, $counts['descriptions'][$count->sambhag_id . '1']);?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php //print_r($counts); ?>
