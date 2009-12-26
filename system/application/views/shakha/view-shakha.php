<?php
//print_r($row);
$sh_add = $row->address1 . ', ';
$sh_add .= (empty($row->address2) ? '' : $row->address2 . ', ');
$sh_add .= $row->city . ', ' . $row->state . ', ' . $row->zip;
?>

<h2><?php echo $row->name?><?php echo ($row->shakha_status == 0) ? '<font color="red"> (Inactive)</font>': '';?></h2>
<p><?php echo $sh_add?>&nbsp;(<?php echo anchor_popup('http://maps.google.com/maps?q=' . str_replace(' ', '+', $sh_add), 'Map'); ?>)</p>
<p><?php echo 'Every ' . $row->frequency_day?>, <?php echo date('g:i A', strtotime($row->time_from)) . ' - ' .  date('g:i A', strtotime($row->time_to));?>
<?php
if(isset($row->kk))
{
	echo '<h3 class="margin-top-15px">Shakha Karyakartas:</h3>'."\n";
	foreach ($row->kk as $kk)
		echo '<p><strong>'.$kk->resp_title.'</strong>: '.anchor('profile/view/'.$kk->contact_id, $kk->first_name.' '.$kk->last_name).'</p>';
}
?>

<h3 class="margin-top-15px">Sankhya:</h3>
<?php
$i = 0;
if(isset($row->sankhyas)){
	foreach($row->sankhyas as $sankhya){
		echo '<p><strong>'.anchor('shakha/add_sankhya/'.$this->uri->segment(3).'/'.$sankhya->date, $sankhya->date).'</strong>: '.$sankhya->total."</p>\n";
		if(++$i == 5) break;
	}
}
?>
<!--
<h3>Latest E-mails to Swayamsevaks:</h3>
<p>Sep 9: <a href="#">Parudh Shivir Organized in Houston, TX</a></p>
<p>Sep 2: <a href="#">Vijyadashmi Utsav at Ved Mandir on October 20th</a> </p>

<p><a href="#">more ...</a> </p>
<p>&nbsp;</p>
<h3>Latest E-mails to Karyakartas: </h3>
<p>Sep 5: <a href="#">Conference call for Vijyadashmi Utsav tonight</a></p>
<p><a href="#">more ...</a> </p>
-->
<?php if($this->permission->is_shakha_kkl($row->shakha_id)): ?>
  <?php if($activities = $this->activities->get_activities('shakha', $row->shakha_id, NULL, 8)):?>
  	<h3 class="margin-top-15px">Latest Activities: (<?php echo anchor("shakha/activities/{$row->shakha_id}", 'View All'); ?>)</h3>
  	<?php foreach($activities as $activity):?>
  		<h4><?php echo $activity;?></h4>
  	<?php endforeach;?>
  <?php endif;?>
<?php endif; ?>
<!--<p><h4>Last Update on: <?php //echo date('F jS, Y h:i A' , strtotime($row->modified)) ?></h4></p>-->