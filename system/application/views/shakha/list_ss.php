<?php //print_r($results); ?>
<?php //print_r($pagination); ?>
<?php
$tmpl = array ( 
		'table_open'  => '<table border="0" cellpadding="20" cellspacing="4">',
		'heading_row_start'   => '<tr align="left">',
		'row_alt_start'	=> '<tr bgcolor="#E5E5E5">'
		);
$this->table->set_template($tmpl);
$vars = $results->result_array();
$total_rows = $results->num_rows();
$yr = date('Y');
$ag['shishu'] = $yr - 6;
$ag['bala'] = $yr - 12;
$ag['kishor'] = $yr - 19;
$ag['yuva'] = $yr - 25;
$ag['tarun'] = $yr - 50;
for($i = 0; $i < $total_rows; $i++)
{
	if(trim($vars[$i]['name']) == '')
		$vars[$i]['name'] = anchor('profile/view/' . $vars[$i]['contact_id'], 'N/A');
	else
		$vars[$i]['name'] = anchor('profile/view/' . $vars[$i]['contact_id'], $vars[$i]['name']);
		
	$vars[$i]['email'] = mailto($vars[$i]['email'], $vars[$i]['email']);
	
	if($vars[$i]['ph_mobile'] != '')
		$vars[$i]['phone'] = $vars[$i]['ph_mobile'];
	elseif($vars[$i]['ph_home'] != '')
		$vars[$i]['phone'] = $vars[$i]['ph_home'];
	elseif($vars[$i]['ph_work'] != '')
		$vars[$i]['phone'] = $vars[$i]['ph_work'];
	else
		$vars[$i]['phone'] = '';
		
	if($vars[$i]['gatanayak'] != '')
    {
        $r = $this->db->select('contact_id, first_name')->getwhere('swayamsevaks', array('contact_id'=> $vars[$i]['gatanayak']))->row();
        $vars[$i]['gatanayak'] = anchor('profile/view/'.$r->contact_id, $r->first_name);
    }
	
	if($vars[$i]['birth_year'] != '')
	{
		$by = $vars[$i]['birth_year'];
		if($by >= $ag['shishu']) $vars[$i]['birth_year'] = 'Shishu';
		elseif($by >= $ag['bala'] && $by < $ag['shishu']) $vars[$i]['birth_year'] = 'Bala';
		elseif($by >= $ag['kishor'] && $by < $ag['bala']) $vars[$i]['birth_year'] = 'Kishor';
		elseif($by >= $ag['yuva'] && $by < $ag['kishor']) $vars[$i]['birth_year'] = 'Yuva';
		elseif($by >= $ag['tarun'] && $by < $ag['yuva']) $vars[$i]['birth_year'] = 'Tarun';
		elseif($by < $ag['tarun']) $vars[$i]['birth_year'] = 'Praudh';
	}
	unset($vars[$i]['contact_id']);
	unset($vars[$i]['ph_home']);
	unset($vars[$i]['ph_mobile']);
	unset($vars[$i]['ph_work']);
}
//print_r($results);
?>
<h2><?=$shakha_name?> - List Contacts</h2>
<div align="right"><?=anchor('/shakha/csv_out/' . $this->uri->segment(3), 'Download to Excel');?></div>
<form name="form" id="form" >
  <table border="0">
    <tr>
      <td width="100" align="right" "nowrap"><h3>Sort by:</h3></td>
      <td width="80" align="left"><select name="jumpMenu" id="jumpMenu" onchange="MM_jumpMenu('parent',this,0)">
  	<?php $val = array("name", "city", "email", "gatanayak");
	foreach($val as $var){
     echo '<option value="/' . $this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3)."/$var\" ";
	 echo (($this->uri->segment(4) == $var) ? ' selected="selected" ' : '');
	 echo '>'. ucwords($var) . "</option>/n";
	 }?>
  </select></td>
      <!--<td width="102"><input type="button" name="go_button" id= "go_button" value="Go" onclick="MM_jumpMenuGo('jumpMenu','parent',0)" /></td>-->
    </tr>
  </table>
</form>
<div align="right">
<?php echo $this->pagination->create_links(); ?>
</div>
<?php echo $this->table->generate($vars);?>
<p>&nbsp;</p>
<?php echo $this->pagination->create_links(); ?>