<h2><?=$row->name;?> - Manage Responsibilities</h2>
<p>&nbsp;</p>
<?php 
//echo $this->ajax->link_to_remote("Login", array('url' => '/login', 'update' => 'divblock'));
//$shakha_id = $row->shakha_id;
//echo $this->ajax->link_to_remote("Login", array('url' => "/ajax/resp_autocomplete/$shakha_id", 'update' => 'name_auto_complete'));

//echo $this->ajax->auto_complete_field('name', array('url'=> "/ajax/resp_autocomplete/$shakha_id", 'paramName' => 'value'));
$allowed_res = array('010', '020', '021', '011', '110', '050', '060', '070', '080', '090', '100', '130', '150', '160', '170', '999', '120');
$vibhag_id = $this->uri->segment(3);
if(isset($row->kk))
{
	echo '<h3>Current Responsibilities</h3>';
	foreach ($row->kk as $kk)
	{
		echo '<p><strong>'.$kk->resp_title.'</strong>: '.anchor('profile/view/'.$kk->contact_id, $kk->first_name.' '.$kk->last_name);
		if($this->permission->is_admin() || $kk->contact_id != $this->session->userdata('contact_id')) {
		echo anchor('vibhag/del_responsibility/'.$vibhag_id.'/'.$kk->contact_id.'/'.$kk->responsibility, '&nbsp;(remove)&nbsp;');}
		echo '</p>';
	}
	echo '<br />';
}
?>
		   <form id="responsibility" method="post" action="<?php echo '/'.$this->uri->segment(1) .'/'.$this->uri->segment(2).'/'.$this->uri->segment(3);?>">
           <?=form_hidden('vibhag_id', $vibhag_id);?>
           <?=form_hidden('level', 'VI');?>
           <h3>Assign New Responsibility</h3><br />           
		     <table width="100%" border="0" cellspacing="2" cellpadding="2">
               <tr>
                 <td width="23%"><div align="right"><select name="name" id="name">
                 <option value="0" selected="selected">Select Karyakarta&nbsp;</option>
                 <?php
				 		foreach($names as $j)
							echo "<option value=\"$j->contact_id\">$j->name&nbsp;</option>\n";
							?>
				</select>
                 </div></td>
                 <td width="77%"><label>
                 	<select name="resp" id="resp">
                       <option value="0" selected="selected">Select Responsibility&nbsp;</option>
                     	<?php
								foreach($resp as $var){
									if(in_array($var->REF_CODE, $allowed_res))
										echo "<option value=\"$var->REF_CODE\">$var->short_desc &nbsp;</option>\n";
								}
						?>
                     </select>
                 </label></td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td><label>
                   <input type="submit" name="button" id="button" value="Add Responsibility" />
                 </label></td>
               </tr>
             </table>
           </form>