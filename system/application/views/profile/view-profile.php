<?php
//print_r($this->db->select('MAX(household_id)')->get('swayamsevaks')->result_array());
//echo '<br />';
//print_r($query->row());
//echo '<br />';
$row = $query->row();
//print_r($row);
$sh_add = (!empty($row->street_add1) ?  $row->street_add1 : '');
$sh_add .= (!empty($row->street_add2) ?  ', '.$row->street_add2 : '');
$sh_add .= strlen(trim($sh_add)) ? '<br />' : '';
$sh_add .= (!empty($row->city) ?  $row->city: '');
$sh_add .= (!empty($row->state) ?  ', '.$row->state: '');
$sh_add .= (!empty($row->zip) ?  ', '.$row->zip : '');
$sh_add = ltrim($sh_add,',');

if(strlen($row->gana)){
	switch ($row->gana) {
		case 1: $row->gana = 'Shishu'; break;
		case 2: $row->gana = 'Bala'; break;
		case 3: $row->gana = 'Kishor'; break;
		case 4: $row->gana = 'Yuva'; break;
		case 5: $row->gana = 'Tarun'; break;
		case 6: $row->gana = 'Praudh'; break;
	}
}
?>
<?php $name = $row->first_name.' '.$row->last_name;?>
<h2><?php /*if(!empty($resp)) echo '<img src="/images/aum.gif" height="25" width="24" title="Aum image signifies that swayamsevak has some responsiblity in Sangh"/>&nbsp;'; */
echo strlen(trim($name)) ? $name : 'Name Unavailable';?>
<?php

	if(!empty($row->birth_year)){
		$age = date("Y") -  $row->birth_year;
		echo ', ',$age;
		}
	echo strlen($row->gana) ? '&#8212;'.$row->gana : '';	
		?></h2>
<span class="leftcol">
<p><?php echo '<strong>',anchor('shakha/view/' . $shakha->shakha_id, $shakha->name),'</strong>,&nbsp;';
		 echo anchor('vibhag/view/' . $vibhag->REF_CODE, $vibhag->short_desc),',&nbsp;';
		 echo anchor('sambhag/view/' . $sambhag->REF_CODE, $sambhag->short_desc);?></p>

<?php //Characters to replace for Google Map link
$gmaps = array("<br />", " ", "#",','); ?>
<p><?=$sh_add?>&nbsp;(<?php echo (!empty($sh_add) ? anchor_popup('http://maps.google.com/maps?q=' . str_replace($gmaps, '+', $sh_add.', USA'), 'Map') : ''); ?>)</p><p>&nbsp;</p>
<?php //if($this->permission->is_shakha_kkl($shakha->shakha_id)){ ?>

<!--<h3>Demographics:</h3>
<strong>Gender:</strong>&nbsp;<?php echo strlen($row->gender) ? (($row->gender == 'M') ? 'Swayamsevak' : 'Sevika') : 'Not Available', '<br />'; ?>
<strong>Gana:</strong>&nbsp;<?php echo strlen($row->gana) ? $row->gana   : 'Not Available', '<br />'; ?>-->

	   <?php
       if(strlen($row->gatanayak)) {
		 echo '<strong>Gatanayak: </strong>';
		 echo anchor('profile/view/'.$gatanayak->contact_id, $gatanayak->first_name.' '.$gatanayak->last_name);
		 echo '<br />';
	}
		if(empty($resp)) {
			foreach($ctype as $t){
				if($t->REF_CODE == $row->contact_type)
					echo '<strong>Contact Type: </strong>',$t->short_desc,'<br />';
		 	}
		 }
    ?>
<p>&nbsp;</p>
<?php

if(!empty($resp))
{
	echo '<h3>Sangh Responsibilities: </h3>';
	foreach ($resp as $temp){
		echo $temp->level.' '.$temp->resp_title . '<br />';
	}
	echo '<br />';
}
?>
<?php 
if(!empty($gata)) {
	echo '<h3>Gata: </h3>';
	foreach($gata as $g)
		echo (anchor('profile/view/'. $g->contact_id, $g->first_name.' '.$g->last_name) . '<br />');
}
?>
</span>

<span class="rightcol">
	<?php if(!empty($row->ph_mobile) || !empty($row->ph_home) || !empty($row->ph_work) || !empty($row->email)): ?>
	<h3>Contact Information: </h3>
	<?php echo((!empty($row->ph_mobile)) ? "$row->ph_mobile (Mobile)<br />" : '');?>
	<?php echo((!empty($row->ph_home)) ? "$row->ph_home (Home)<br />" : '');?>
	<?php echo((!empty($row->ph_work)) ? "$row->ph_work (Work)<br />" : '');?>
	<?php echo(($row->email != '') ? mailto($row->email, $row->email) : '');?>
	<?php echo(($row->email != '' && $row->email_status != 'Active') ? '<span style="color:#FF0000;"> ('.$row->email_status.')</span><br /><br />' :'<br /><br />'); endif;?>
	<?php 
		$count = $households->num_rows();
		if(($count - 1) > 0)
		{
			echo '<h3>Family Members: </h3>';
			for($i=0; $i < $count; $i++)
			{
				$fams = $households->row($i);
				if($fams->contact_id != $row->contact_id)
				{
			    	//Set Name to N/A if empty
					  if(trim($fams->first_name) == '' && trim($fams->last_name) == '') $fams->first_name == 'N/A';
			    	
					  echo '<p>' . anchor('profile/view/'. $fams->contact_id, $fams->first_name . ' ' . $fams->last_name);
						echo (strlen($fams->birth_year)) ? '&nbsp;&mdash;&nbsp;'. (date("Y") -  $fams->birth_year) : '' , '</p>'; 
				}
			} 
		}
	?>
	<br />
	<?php if(strlen(trim($row->position)) || strlen(trim($row->company))): ?>
    <h3>Work/School Information: </h3>
    <?php if(strlen(trim($row->position))) echo $row->position.', ';?><?php if(strlen(trim($row->company))) echo $row->company;?><br /><br />
	<?php endif; ?>
	<br />
</span>

<div style="clear:both;"><br /></div>
<h4>Last Update on: <?php echo date('F jS, Y h:i A' , strtotime($row->modified)) ?></h4>