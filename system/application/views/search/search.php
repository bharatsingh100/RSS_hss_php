<?php 

$vars = $results->result_array();
$total_rows = $results->num_rows();
for($i = 0; $i < $total_rows; $i++)
{
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
		
	unset($vars[$i]['contact_id']);
	unset($vars[$i]['ph_home']);
	unset($vars[$i]['ph_mobile']);
	unset($vars[$i]['ph_work']);
}

?>

<h2>Search Results</h2>
<h3>Search Term: <?=$this->session->ro_userdata('term');?></h3>
<p>&nbsp;</p>
<?php if(sizeof($vars)){?>
<?php foreach($vars as $t) :?>
<?php 
	echo '<strong>'.$t['name'].'</strong>&nbsp;';
	echo $t['city'] . '&nbsp;';
	echo ', '. $t['state'] . '&nbsp;';
	echo $t['phone'].'&nbsp;';
	echo $t['email'];
?>
<p>&nbsp;</p>
<?php endforeach;
} else { 
//$this->load->helper('smiley');
//$str = parse_smileys(':-(', "http://extranet.hssus.org/css/smileys/");?>
<h4>No Results matched your query.</h4>
<strong>Please try different spellings.</strong>
<? } ?>