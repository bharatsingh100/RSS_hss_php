<h2><?=$row->name?> - Gatas</h2>
<?php 
if(isset($row->kk))
{ ?>
  <div align="right"><?=anchor('/shakha/gata_csv/' . $this->uri->segment(3), 'Download Gatanayak Info to Excel');?></div>
  <p>&nbsp;</p>
	<?php
  foreach ($gatas as $gata)
	{
		echo '<h3>',anchor('profile/view/'.$gata->contact_id, $gata->first_name.' '.$gata->last_name);
		echo ' - ',$gata->resp_title,'</h3>',"\n",'<p>';
		//var_dump($gata);
		foreach($gata->gata as $g)
			echo anchor('profile/view/'.$g->contact_id, $g->first_name.' '.$g->last_name),'<br />';
		echo '</p>',"\n";
	}
}
?>