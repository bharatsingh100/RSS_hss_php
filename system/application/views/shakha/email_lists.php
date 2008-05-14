<?php //print_r($lists); ?>
<?php //print_r($pagination); ?>
<?php
$tmpl = array ( 
		'table_open'  => '<table border="0" cellpadding="20" cellspacing="10">',
		'heading_row_start'   => '<tr align="left">',
		'row_alt_start'	=> '<tr bgcolor="#E5E5E5">'
		);

$this->table->set_template($tmpl);
?>
<h2><?=$shakha->name?> - Email Lists</h2>
<?php echo $this->table->generate($lists);?>
<p>&nbsp;</p>
<?php echo anchor('shakha/create_list/'.$this->uri->segment(3), 'Request New E-mail List'); ?>