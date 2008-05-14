<?php
//print_r($this->db->select('MAX(household_id)')->get('swayamsevaks')->result_array());
//echo '<br />';
//print_r($query->row());
//echo '<br />';
$row = $query->row();
//print_r($row);
$sh_add = (!empty($row->street_add1) ?  $row->street_add1 : '');
$sh_add .= (!empty($row->street_add2) ?  ', '.$row->street_add2 : '');
$sh_add .= (!empty($row->city) ?  ', '.$row->city: '');
$sh_add .= (!empty($row->state) ?  ', '.$row->state: '');
$sh_add .= (!empty($row->zip) ?  ', '.$row->zip : '');
$sh_add = ltrim($sh_add,',');
//$sh_add .= $row->city . ', ' . $row->state . ', ' . $row->zip;
?>
<?php $name = $row->first_name.' '.$row->last_name;?>
<h2><?php if(strlen(trim($name))) echo $name; else echo 'NO NAME';?>
<?php if(!empty($row->birth_year)){
		$age = date("Y") -  $row->birth_year;
		echo ', ' . $age;
		}?></h2>

<p><?=$sh_add?>&nbsp;(<?php echo (!empty($sh_add) ? anchor_popup('http://maps.google.com/maps?q=' . str_replace(' ', '+', $sh_add.', USA'), 'Map') : ''); ?>)</p>

<p><?php echo anchor('shakha/view/' . $shakha->shakha_id, $shakha->name),',&nbsp;';
		 echo anchor('vibhag/view/' . $vibhag->REF_CODE, $vibhag->short_desc),',&nbsp;';
		 echo anchor('sambhag/view/' . $sambhag->REF_CODE, $sambhag->short_desc);?></p>
<?php if($this->permission->is_shakha_kkl($shakha->shakha_id)){
	   if(strlen($row->gatanayak)) {
		 echo '<br /><p><strong>Gatanayak: </strong>';
		 echo anchor('profile/view/'.$gatanayak->contact_id, $gatanayak->first_name.' '.$gatanayak->last_name);
		 echo '&nbsp;&nbsp;&nbsp;&nbsp;';
	}
		if(empty($resp)) {
		foreach($ctype as $t)
		 {
		 	if($t->REF_CODE == $row->contact_type)
				echo '<p><strong>Contact Type: </strong>',$t->short_desc,'</p>';
		 }
		 }
		 echo '</p>';
	}?>
<p>&nbsp;</p>
<?php

if(!empty($resp))
{
	echo '<h3>Responsibilities: </h3>';
	foreach ($resp as $temp)
	{
//		if($temp->level != 'Shakha')
//			echo $temp->level.' ';
		echo $temp->level.' '.$temp->resp_title . '<br />';
	}
	echo '<br />';
}
?>
<h3>Contact Information: </h3>
<?php echo((!empty($row->ph_mobile)) ? "$row->ph_mobile (Mobile)<br />" : '');?>
<?php echo((!empty($row->ph_home)) ? "$row->ph_home (Home)<br />" : '');?>
<?php echo((!empty($row->ph_work)) ? "$row->ph_work (Work)<br />" : '');?>
<?php echo(($row->email != '') ? mailto($row->email, $row->email) : '');?>
<?php echo(($row->email != '' && $row->email_status != 'Active') ? '<span style="color:#FF0000;"> ('.$row->email_status.')</span><br /><br />' :'<br /><br />'); ?>
<?php 
	$count = $households->num_rows();
	if($count - 1)
	{
		echo '<h3>Family: </h3>';
		for($i=0; $i < $count; $i++)
		{
			$fams = $households->row($i);
			if($fams->contact_id != $row->contact_id)
			{
		    	echo '<p>' . anchor('profile/view/'. $fams->contact_id, $fams->first_name . ' ' . $fams->last_name);
				
				echo (strlen($fams->birth_year)) ? '&nbsp;&mdash;&nbsp;'. (date("Y") -  $fams->birth_year) : '' , '</p>'; 
			}
		} 
	}
?>
<br />
<?php if(!empty($row->position) OR !empty($row->company)): ?>
<h3>Work: </h3>
<?php if(strlen($row->position)) echo $row->position.', ';?><?php if(strlen($row->position)) echo $row->company;?><br /><br />
<? endif; ?>

<?php 
if(!empty($gata)) {
	echo '<h3>Gata: </h3>';
	foreach($gata as $g)
		echo (anchor('profile/view/'. $g->contact_id, $g->first_name.' '.$g->last_name) . '<br />');
}
?>
<?php
$datefromdb = $row->modified;
$year = substr($datefromdb,0,4);
$mon  = substr($datefromdb,5,2);
$day  = substr($datefromdb,8,2);
$hour = substr($datefromdb,11,2);
$min  = substr($datefromdb,14,2);
$sec  = substr($datefromdb,17,2);
$orgdate = date('F dS, Y h:i A' , mktime($hour,$min,$sec,$mon,$day,$year));
?>
<p>&nbsp;</p>
<h4>Profile Last Updated On: <? echo $orgdate; ?></h4>