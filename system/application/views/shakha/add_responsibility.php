<h2><?=$row->name;?> - Manage Responsibilities</h2>
<p>&nbsp;</p>
<?php
//echo $this->ajax->link_to_remote("Login", array('url' => '/login', 'update' => 'divblock'));
$shakha_id = $row->shakha_id;
//echo $this->ajax->link_to_remote("Login", array('url' => "/ajax/resp_autocomplete/$shakha_id", 'update' => 'name_auto_complete'));

//echo $this->ajax->auto_complete_field('name', array('url'=> "/ajax/resp_autocomplete/$shakha_id", 'paramName' => 'value'));

//Show Responsibilities that are relevant to the Shakha context
$allowed_res = array('010','011', '020','021','030','031','040','050','060', '061','070','080','090', '091','100','130','140','150','160','170','180','190','200','210','211','220','221', '230','999', '240', '250', '260','270', '272');
if(isset($row->kk))
{
	echo '<h3>Current Responsibilities</h3>';
	foreach ($row->kk as $kk)
	{
		echo '<p><strong>'.$kk->resp_title.'</strong>: '.anchor('profile/view/'.$kk->contact_id, $kk->first_name.' '.$kk->last_name);
		//Karyakarta cannot remove his own responsibility
		if($this->permission->is_admin() || $kk->contact_id != $this->session->userdata('contact_id')) {
		echo anchor('shakha/del_responsibility/'.$shakha_id.'/'.$kk->contact_id.'/'.$kk->responsibility, '&nbsp;(remove)&nbsp;'); }
		echo '</p>';
	}
	echo '<br />';
}
?>
		   <form id="responsibility" method="post" action="<?php echo '/'.$this->uri->segment(1) .'/'.$this->uri->segment(2).'/'.$this->uri->segment(3);?>">
           <?=form_hidden('shakha_id', $row->shakha_id);?>
           <?=form_hidden('level', 'SH');?>
           <h3>Assign New Responsibility</h3><br />
		     <table width="100%" border="0" cellspacing="2" cellpadding="2">
               <tr>
                 <td width="23%"><div align="right"><select name="name" id="name">
                 <option value="0" selected="selected">Select Karyakarta&nbsp;</option>
                 <?php
				 		foreach($names as $j)
							if(strlen(trim($j->name))) echo "<option value=\"$j->contact_id\">$j->name&nbsp;</option>\n";
							?>
				</select>
                 </div></td>
                 <td width="77%">&nbsp;
                 	<select name="resp" id="resp">
                       <option value="0" selected="selected">Select Responsibility&nbsp;</option>
                     	<?php
								foreach($resp as $var){
									if(in_array($var['REF_CODE'], $allowed_res))
										echo "<option value=\"",$var['REF_CODE'],"\">",$var['short_desc']," &nbsp;</option>\n";
								}
						?>
                     </select>
                 </td>
               </tr>
               <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
               <tr>
                 <td>&nbsp;</td>
                 <td><label>
                   <input type="submit" name="button" id="button" value="Add Responsibility" />
                 </label></td>
               </tr>
             </table>
           </form>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<strong>Hints:</strong>
			<ul>
			  <li> If a swayamsevak has any responsibility, they are also assumed to be a gatanayak. Therefore, you don't need to assign <strong>Gatanayak</strong> responsibility to an existing karyakarta.</li>
			</ul>
