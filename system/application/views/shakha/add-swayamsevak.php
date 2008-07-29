<?php $fam = (isset($family)) ? true : false; ?>
<?php //if($fam) print_r($family); ?>
<?=form_open('shakha/add_family/')?>
<?=form_hidden('shakha_id', $shakha_id);?>
<?php if($fam) echo form_hidden('household_id', $family['household_id']); ?>

			 <h2><?=$shakha_name?> - Add Contact</h2>
		     <h3>Personal Information</h3>
		     <table width="100%" border="0" cellpadding="2" cellspacing="4">
               <tr>
                 <td width="19%"><div align="right">Name:
                   </div>                   <label></label></td>
                 <td colspan="3"><input name="first_name" type="text" id="textfield" size="30" maxlength="50" />
                   &nbsp;                   
                   <label>
                   <input name="last_name" type="text" id="textfield2" size="30" maxlength="50" value = "<?php if($fam) echo ($family['last_name']); ?>"/>
                   </label></td>
               </tr>
               <tr>
                 <td><div align="right">Gender:</div></td>
                 <td colspan="3" valign="middle"><label></label>
                   <p>
                     <label>
                       <input name="gender" type="radio" id="RadioGroup1_0" value="M" checked="checked" />
                       Swayamsevak</label>
                     <label>
                       <input type="radio" name="gender" value="F" id="RadioGroup1_1" />
                       Sevika</label>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Age 
                     <label>
                     <select name="birth_year" id="birth_year">
                       <option value="" selected="selected"></option>
                     	<?php
								$year = date("Y");
								$max_age = 100;
								for($i = 0; $i <= $max_age; $i++)
								{
									$yr = $year - $i;
									echo "<option value=\"$yr\">$i&nbsp;</option>";
								}
						?>
                     </select>
                     </label>
                     <br />
                 </p></td>
               </tr>
               <tr>
                 <td align="right">Contact Type:</td>
                 <td colspan="3" valign="middle"><select name="contact_type" id="contact_type">
                 <?php foreach($ctype as $t){ ?>
                 		<option value="<?php echo $t->REF_CODE; ?> "
                    <?php echo (($t->REF_CODE == "RA") ? ' selected="selected" ' : ''); ?>
                     ><?php echo $t->short_desc,'&nbsp;';?></option>
                 <?php } ?>
                 </select>                 </td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td colspan="3" valign="middle">&nbsp;</td>
               </tr>
               <tr>
                 <td><div align="right">Company/Institution:</div></td>
                 <td colspan="3" valign="middle"><input name="company" type="text" id="textfield7" size="35" maxlength="50" /></td>
               </tr>
               <tr>
                 <td><div align="right">Position:</div></td>
                 <td colspan="3" valign="middle"><input name="position" type="text" id="textfield8" size="35" maxlength="50" /></td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td colspan="3" valign="middle">&nbsp;</td>
               </tr>
               <tr>
                 <td colspan="4"><h3>Contact Information</h3></td>
               </tr>
               <tr>
                 <td valign="top"><div align="right">E-mail:</div></td>
               <td colspan="3"><label>
                   <input name="email" type="text" id="email" size="35" maxlength="60" />
               <br />
                 <span class="style2">(Email Addresses must be unique. Two contacts cannot share same e-mail address)                   </span></label></td>
               </tr>
               <tr>
                 <td valign="top"><div align="right">Phone:</div></td>
                 <td width="9%">
                   <input name="ph_mobile" type="text" id="ph_mobile" value="Mobile..." onClick="this.value = '';" size="15" maxlength="14"/>
                   <br />
                   <span class="style2">Mobile                 </span></td>
                 <td width="9%"><input name="ph_home" type="text" id="ph_home" size="15" onclick="this.value = '';" maxlength="14" 
                 		value="<?php echo (($fam) ? $family['ph_home'] : 'Home...' );?>"/>
                 <br />
                 <span class="style2">Home</span></td>
                 <td width="63%"><input name="ph_work" type="text" id="ph_work" maxlength="14" value="Work..." onclick="this.value = '';" size="15" />
                 <br />
                 <span class="style2">Work</span></td>
               </tr>
               <tr>
                 <td><div align="right"></div></td>
                 <td colspan="3">&nbsp;</td>
               </tr>
               <tr>
                 <td><div align="right">Street Address:</div></td>
                 <td colspan="3"><input name="street_add1" type="text" id="textfield9" size="35" maxlength="50" value="<?php if($fam) echo ($family['street_add1']); ?>"/></td>
               </tr>
               <tr>
                 <td><div align="right"></div></td>
                 <td colspan="3"><input name="street_add2" type="text" id="textfield10" size="35" maxlength="50" value="<?php if($fam) echo ($family['street_add2']); ?>"/></td>
               </tr>
               <tr>
                 <td><div align="right">City:</div></td>
                 <td colspan="3"><input name="city" type="text" id="textfield11" size="35" maxlength="50" value="<?php if($fam) echo ($family['city']); ?>"/>                   <label></label></td>
               </tr>
               <tr>
                 <td><div align="right">State: </div></td>
                 <td colspan="3" valign="middle"><select name="state" id="select">
                   <?php foreach($states as $var) 
				   {
				   		echo "<option value=\"$var->REF_CODE\"";
						echo (($var->REF_CODE == $shakha_st) ? ' selected="selected"' : '');
						echo ">$var->short_desc</option>\n";
					}
					?>
                 </select>
                 &nbsp;&nbsp;Zip: 
                 <label>
                 <input name="zip" type="text" id="zip" size="12" maxlength="10" value="<?php if($fam) echo ($family['zip']); ?>"/>
                 </label></td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td colspan="3" valign="middle">&nbsp;</td>
               </tr>
               <tr>
                 <td colspan="4"><h3>Sangh Information</h3></td>
               </tr>
               <tr>
                 <td><div align="right">SSV Completed:</div></td>
                 <td colspan="3" valign="middle"><label>
                   <select name="ssv_completed" id="select3">
                     <option value="" selected="selected"></option>
                   <?php  
					//foreach($ssv_completed as $var)
					//   		echo "<option value=\"$var->REF_CODE\">$var->short_desc</option>";
					?>
                     <option value="USA1">Prathmik (1st year)</option>
                     <option value="USA2">Pravesh (2nd year)</option>
                     <option value="USA3">Praveen (3rd year)</option>
                     <option value="USA4">Prathm Varsh in Bharat</option>
                     <option value="USA5">Divitaya Varsh in Bharat</option>
                     <option value="USA6">Tritya Varsh in Bharat</option>
                     <option value="OTH1">Other</option>
                   </select>
                 </label></td>
               </tr>
               <tr>
                 <td><div align="right">Gatanayak:</div></td>
                 <td colspan="3" valign="middle"><label>
                   <select name="gatanayak" id="select7">
                   <option value="" selected="selected"></option>
                   <?php foreach($gatanayak as $v) {
				   			echo '<option value="' . $v->contact_id . "\">$v->first_name $v->last_name ($v->num)</option>";
					} ?>
                 </select>
                 </label></td>
               </tr>
               <tr>
                 <td><div align="right">Notes:</div></td>
                 <td colspan="3" rowspan="2" valign="top"><label>
                   <textarea name="notes" id="textarea" cols="45" rows="5"></textarea>
                 </label></td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td colspan="3" valign="middle"><label>
                   <input type="submit" name="save" id="save" value="Save Information" />
                   <input type="submit" name="add_family" id="add_family" value="Save & Add Family Members" />
                 </label></td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td colspan="3" valign="middle">&nbsp;</td>
               </tr>
             </table>
		     <p>&nbsp;</p>
		     <p>&nbsp;</p>
<?=form_close(); ?>		   </form>

<?php /*
<?php 
$row = $query->row();
$sh_add = $row->street_add1 . ', ';
$sh_add .= (empty($row->stree_add2) ? '' : $row->street_add2 . ', ');
$sh_add .= $row->city . ', ' . $row->state . ', ' . $row->zip;
?>

<h2><?=$row->first_name;?>&nbsp;<?=$row->last_name?>, <?=date("Y")-$row->birth_year;?></h2>
<p><?=$sh_add?>&nbsp;<?php echo (!empty($sh_add) ? anchor_popup('http://maps.google.com/maps?q=' . str_replace(' ', '+', $sh_add), 'Google Map') : ''); ?></p>
<p><?=anchor('shakha/view/' . $shakha->shakha_id, $shakha->name);?>, New Jersey, North East</p>
<p>&nbsp;</p>
<?php

if(!empty($resp))
{
	echo '<h3>Responsibilities: </h3>';
	foreach ($resp as $temp)
	{
//		if($temp->level != 'Shakha')
//			echo $temp->level.' ';
		echo $temp->level.' '.$temp->resp_title . '<br />';
	}
}
?>
<br />
<h3>Contact Information: </h3>
<?php echo((!empty($row->ph_mobile)) ? "$row->ph_mobile (Mobile)<br />" : '');?>
<?php echo((!empty($row->ph_home)) ? "$row->ph_home (Home)<br />" : '');?>
<?php echo((!empty($row->email)) ? mailto($row->email, $row->email).'<br /><br />' : '<br />');?>
<?php 
	$count = $households->num_rows();
	if($count)
	{
		echo '<h3>Family: </h3>';
		for($i=0; $i < $count; $i++)
		{
			$fams = $households->row($i);
			if($fams->contact_id != $row->contact_id)
		    	echo '<p>' . anchor('profile/view/'. $fams->contact_id, $fams->first_name . ' ' . $fams->last_name) . '</p>'; 
		} 
	}
?>
<br />
<?php if(!empty($row->position) OR !empty($row->company)): ?>
<h3>Work: </h3>
<?=$row->position?>, <?=$row->company?><br /><br />
<? endif; ?>

<h3>Gata: </h3>
*/ ?>