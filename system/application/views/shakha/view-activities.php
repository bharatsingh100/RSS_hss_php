<h2><?php echo $row->name?><?php echo ($row->shakha_status == 0) ? '<font color="red"> (Inactive)</font>': '';?></h2>

<?php if($activities):?>
  <h3 class="margin-top-15px">Latest Activities</h3>
  <?php foreach($activities as $activity):?>
  	<h4><?php echo $activity;?></h4>
  <?php endforeach;?>
<?php endif;?>
