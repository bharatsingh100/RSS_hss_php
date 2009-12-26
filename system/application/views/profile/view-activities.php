<?php
$row = $query->row();

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
//print_r($row);

?>
<?php $name = $row->first_name.' '.$row->last_name;?>
<h2>
  <?php echo strlen(trim($name)) ? $name : 'Name Unavailable';?>
  <?php
    if(!empty($row->birth_year)){
      $age = date("Y") -  $row->birth_year;
      echo ', ',$age;
    }
    echo strlen($row->gana) ? '&#8212;'.$row->gana : '';
  ?>
</h2>
<p class="leftcol">
<?php /* Show latest activities of the person */ ?>
<?php if($activities):?>
	<h3>Latest Activities:</h3>
	<?php foreach($activities as $activity):?>
		<h4><?php echo $activity;?></h4>
	<?php endforeach;?>
<?php endif;?>
</p>