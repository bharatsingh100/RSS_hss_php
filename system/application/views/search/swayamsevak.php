<script type="text/javascript" src="<?=site_url();?>javascript/prototype.js"></script>
<div id="swayamsevak-list">
<table>
	<tr>
		 <th>Name</th>
		 <th>Shakha</th>
		 <th>State</th>
	</tr>
	<?php if(!empty($swayamsevaksDetails)):
	          foreach ($swayamsevaksDetails as $key => $value) { ?>
					<tr>
						 <td><a href="<?php echo site_url().'profile/view/'.$value['contact_id'] ?>"><?php echo $value['first_name']." ".$value['last_name'] ?></a></td>
						 <td><?php echo $value['name'] ?></td>
						 <td><?php echo $value['state'] ?></td>
					</tr>
		  <?php 
				}else:
				echo "<tr><td colspan='3'>No Record</td></tr>"; endif; ?>
</table>

<?php

$config['first_link'] = 'First';
$config['div'] = 'ui-id-2';
$config['base_url'] = site_url().'search/getSwayamsevak/'.$keyword;
$config['total_rows'] = $getTotalData;
$config['per_page'] = $perPage;
$config['postVar'] = 'page';
$this->ajax_pagination->initialize($config);
echo "<div class='page-links'>".$this->ajax_pagination->create_links()."</div>";

?>
</div>