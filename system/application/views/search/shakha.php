<script type="text/javascript" src="<?=site_url();?>javascript/prototype.js"></script>
<div id="shakha-list">
<table>
	<tr>
		 <th>Name</th>
		 <th>City</th>
		 <th>State</th>
	</tr>
	<?php if(!empty($shakhaDetails)):
	          foreach ($shakhaDetails as $key => $value) { ?>
					<tr>
						 <td><a href="<?php echo site_url().'shakha/view/'.$value['shakha_id'] ?>"><?php echo $value['name'] ?></a></td>
						 <td><?php echo $value['city'] ?></td>
						 <td><?php echo $value['state'] ?></td>
					</tr>
		 <?php 
				}else:
				echo "<tr><td colspan='3'>No Record</td></tr>"; endif; ?>
</table>

<?php
$config['first_link'] = 'First';
$config['div'] = 'ui-id-4';
$config['base_url'] = site_url().'search/getShakha/'.$keyword;
$config['total_rows'] = $getTotalData;
$config['per_page'] = $perPage;
$config['postVar'] = 'page';
$this->ajax_pagination->initialize($config);
echo "<div class='page-links>".$this->ajax_pagination->create_links();

?>
</div>