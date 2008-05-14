<?php
$sh_add = $row->address1 . ', ';
$sh_add .= (empty($row->address2) ? '' : $row->address2 . ', ');
$sh_add .= $row->city . ', ' . $row->state . ', ' . $row->zip;
?>


<h2><?=$row->name?><?php echo ($row->shakha_status == 0) ? '<font color="red"> (Inactive)</font>': '';?></h2>
<p><?=$sh_add?>&nbsp;(<?php echo anchor_popup('http://maps.google.com/maps?q=' . str_replace(' ', '+', $sh_add), 'Map'); ?>)</p>
<p><?=$row->frequency_day?>, <?php echo (substr($row->time_from, 0, 2) > 12) ? substr($row->time_from, 0, 2) - 12 . substr($row->time_from, 2,3).' PM ' : substr($row->time_from,0,5); ?> - <?php echo (substr($row->time_to, 0, 2) > 12) ? substr($row->time_to, 0, 2) - 12 . substr($row->time_to, 2,3).' PM ' : substr($row->time_to,0,5); ?></p>
<p>&nbsp;</p>
<?php 
if(isset($row->kk))
{
	echo '<h3>Shakha Karyakartas:</h3>'."\n";
	foreach ($row->kk as $kk)
		echo '<p><strong>'.$kk->resp_title.'</strong>: '.anchor('profile/view/'.$kk->contact_id, $kk->first_name.' '.$kk->last_name).'</p>';
}
?>

<p>&nbsp;</p>
<!-- <h3>Upcoming Events:</h3>
<p>Vijyadashmi Utsav (October 20th) (Vibhag)</p>
<p>&nbsp;</p>-->
<h3>Sankhya:</h3>
<?php
$i = 0;
if(isset($row->sankhyas)){
	foreach($row->sankhyas as $sankhya){
		echo '<p><strong>'.anchor('shakha/add_sankhya/'.$this->uri->segment(3).'/'.$sankhya->date, $sankhya->date).'</strong>: '.$sankhya->total."</p>\n";
		if(++$i == 5) break;
	}
}
?>
<p>&nbsp;  </p>
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
