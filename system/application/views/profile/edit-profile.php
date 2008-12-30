<?php $m = $query->row(0); //$shakhas = $shakhas->result()?>
<?php //echo '<pre>' . var_dump($shakhas) . '</pre>'; ?>
<?=form_open($this->uri->uri_string())?>
<?php //form_hidden('shakha_id', $m->shakha_id);?>
<?=form_hidden('household_id', $m->household_id);?>
<?=form_hidden('contact_id', $m->contact_id);?>
<?php //if($fam) echo form_hidden('household_id', $family['household_id']); ?>
			 <h2><?=$shakha_name?></h2>
		     <h3>Update Information for <?=$m->first_name;?> <?=$m->last_name;?></h3>
		     <table width="100%" border="0" cellpadding="2" cellspacing="4">
               <tr>
                 <td width="19%"><div align="right"><strong>Name:
                   </strong></div>                   <label></label></td>
                 <td><input name="first_name" type="text" id="textfield" size="30" maxlength="50" value="<?=$m->first_name;?>"/>
                   &nbsp;                   
                   <label>
                   <input name="last_name" type="text" id="textfield2" size="30" maxlength="50" value="<?=$m->last_name;?>"/>
                   </label></td>
               </tr>
               <tr>
                 <td><div align="right"><strong>Gender:</strong></div></td>
                 <td valign="middle"><label></label>
                   <p>
                     <label>
                       <input name="gender" type="radio" id="RadioGroup1_0" value="M" <?php echo ($m->gender == 'M') ? 'checked="checked"' : '';?>/>
                       Swayamevak&nbsp;</label>
                     <label>
                       <input type="radio" name="gender" value="F" id="RadioGroup1_1" <?php echo ($m->gender == 'F') ? 'checked="checked"' : '';?>/>
                       Sevika</label>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Age</strong> 
                     <label>
                     <select name="birth_year" id="birth_year">
                       <option value="" <?php echo ($m->birth_year == '') ? 'selected="selected"' : '';?>></option>
                     	<?php
								$year = date("Y");
								$max_age = 100;
								for($i = 0; $i <= $max_age; $i++)
								{
									$yr = $year - $i;
									echo "<option value=\"$yr\"";
									echo ($m->birth_year == $yr) ? 'selected="selected"' : '';
									echo ">$i</option>";
								}
						?>
                     </select>
                     </label>
                     <br />
                 </p></td>
               </tr>
               <tr>
                 <td align="right"><strong>Contact Type:</strong></td>
                 <td valign="middle"><select name="contact_type" id="contact_type">
                     <?php foreach($ctype as $t){ ?>
                     <option value="<?php echo $t->REF_CODE; ?>" <?php if($t->REF_CODE == $m->contact_type) echo 'selected="selected"';?>><?php echo $t->short_desc,'&nbsp;';?></option>
                     <?php } ?>
                   </select>
                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Gana:</strong><select name="gana" id="gana">
                     <option>Select Gana&nbsp;</option>
					 <?php foreach($ganas as $t){ ?>
                     <option value="<?php echo $t->REF_CODE; ?>" <?php if($t->REF_CODE == $m->gana) echo 'selected="selected"';?>><?php echo $t->short_desc,'&nbsp;';?></option>
                     <?php } ?>
                   </select>                 </td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td valign="middle">&nbsp;</td>
               </tr>
               <tr>
                 <td><div align="right"><strong>Company/School:</strong></div></td>
                 <td valign="middle"><input name="company" type="text" id="company" size="30" maxlength="50" value="<?=$m->company;?>"/>
                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Position/Grade:</strong>:
                   <input name="position" type="text" id="position" size="25" maxlength="50" value="<?=$m->position;?>"/></td>
               </tr>
               <tr>
                 <td colspan="2"><h3>Contact Information</h3></td>
               </tr>
               <tr>
                 <td><div align="right"><strong>E-mail:</strong></div></td>
                 <td><label>
                   <input name="email" type="text" id="email" size="35" maxlength="60" value="<?=$m->email;?>"/>
                 </label><?php if($m->email != ''): ?>
                 &nbsp;<select name="email_status" id="email_status">
                       <option value="Active" <?php echo (($m->email_status == 'Active') ? 'selected="selected"' : '');?>>Active</option>
                       <option value="Bounced" <?php echo (($m->email_status == 'Bounced') ? 'selected="selected"' : '');?>>Inactive (Bounced)&nbsp;</option>
                       <option value="Unsubscribed" <?php echo (($m->email_status == 'Unsubscribed') ? 'selected="selected"' : '');?>>Inactive (Unsubscribed)&nbsp;</option>
                     </select>
                     <?php endif; ?>                 </td>
               </tr>
               <tr>
                 <td><div align="right"><strong>Phone:</strong></div></td>
                 <td>
                   <input name="ph_mobile" type="text" id="ph_mobile" value="<?php echo ($m->ph_mobile == '') ? 'Mobile...' : $m->ph_mobile;?>" size="15" maxlength="12"/>
                 &nbsp;
                 <input name="ph_home" type="text" id="ph_home" size="15" maxlength="12" value="<?php echo ($m->ph_home == '') ? 'Home...' : $m->ph_home;?>"/>
&nbsp;           <input name="ph_work" type="text" id="ph_work" maxlength="12" value="<?php echo ($m->ph_work == '') ? 'Work...' : $m->ph_work;?>" size="15" /></td>
               </tr>
               <tr>
                 <td><div align="right"></div></td>
                 <td>&nbsp;</td>
               </tr>
               <tr>
                 <td><div align="right"><strong>Street Address:</strong></div></td>
                 <td><input name="street_add1" type="text" id="street_add1" size="35" maxlength="50" value="<?=$m->street_add1;?>"/></td>
               </tr>
               <tr>
                 <td><div align="right"></div></td>
                 <td><input name="street_add2" type="text" id="street_add2" size="35" maxlength="50" value="<?=$m->street_add2;?>"/></td>
               </tr>
               <tr>
                 <td><div align="right"><strong>City:</strong></div></td>
                 <td><input name="city" type="text" id="city" size="35" maxlength="50" value="<?=$m->city;?>"/>                   <label></label></td>
               </tr>
               <tr>
                 <td><div align="right"><strong>State: </strong></div></td>
                 <td valign="middle"><strong>
                   <select name="state" id="select">
                     <?php foreach($states as $var) 
				   {
				   		echo "<option value=\"$var->REF_CODE\"";
						echo (($m->state == $var->REF_CODE) ? ' selected="selected"' : '');
						echo ">$var->short_desc</option>\n";
					}
					?>
                     </select>
&nbsp;&nbsp;Zip:
<input name="zip" type="text" id="zip" size="12" maxlength="10" value="<?=$m->zip;?>"/>
                 </strong></td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td valign="middle"><input name="add_update" type="checkbox" id="add_update" value="1" checked="checked" />
                   Update address of all family members?</td>
               </tr>
               <tr>
                 <td colspan="2"><h3>Sangh Information</h3></td>
               </tr>
               <tr>
                 <td align="right"><strong>Shakha:</strong></td>
                 <td valign="middle"><select name="shakha_id" id="shakha_id">
                 	
                   <?php 
				   $iState = $shakhas[0]->state;
				   echo "<option>------------------$iState Shakhas------------------</option>";
				   foreach($shakhas as $var){
				   		if($iState != $var->state){
							$iState = $var->state;
							echo "<option>------------------$iState Shakhas------------------</option>";
						}
						echo "<option value=\"$var->shakha_id\"";
						echo (($m->shakha_id == $var->shakha_id) ? ' selected="selected"' : '');
						echo ">$var->name - $var->city, $var->state</option>\n";
					}
					?>
                 </select>                 </td>
               </tr>
               <tr>
                 <td><div align="right"><strong>Gatanayak:</strong></div></td>
                 <td valign="middle"><label>
                   <select name="gatanayak" id="select7">
                   <option value="" <?php echo (($m->gatanayak == '') ? 'selected="selected"' : ''); ?>></option>
                   <?php foreach($gatanayak as $v) {
				   			echo '<option value="' . $v->contact_id . '" ';
							echo ($m->gatanayak == $v->contact_id) ? 'selected="selected"' : '';
							echo ">$v->first_name $v->last_name ($v->num)&nbsp;</option>";
					} ?>
                 </select>
                 </label></td>
               </tr>
               <tr>
                 <td><div align="right"><strong>SSV Completed:</strong></div></td>
                 <td valign="middle"><label>
                   <select name="ssv_completed" id="select3">
                     <option value="" <?php echo ($m->ssv_completed == '') ? 'selected="selected"' : '';?>></option>
                     <option value="USA1" <?php echo ($m->ssv_completed == 'USA1') ? 'selected="selected"' : '';?>>Prathmik (1st year)</option>
                     <option value="USA2" <?php echo ($m->ssv_completed == 'USA2') ? 'selected="selected"' : '';?>>Pravesh (2nd year)</option>
                     <option value="USA3" <?php echo ($m->ssv_completed == 'USA3') ? 'selected="selected"' : '';?>>Praveen (3rd year)</option>
                     <option value="IND1" <?php echo ($m->ssv_completed == 'IND1') ? 'selected="selected"' : '';?>>Prathm Varsh in Bharat</option>
                     <option value="IND2" <?php echo ($m->ssv_completed == 'IND2') ? 'selected="selected"' : '';?>>Divitaya Varsh in Bharat</option>
                     <option value="IND3" <?php echo ($m->ssv_completed == 'IND3') ? 'selected="selected"' : '';?>>Tritya Varsh in Bharat</option>
                     <option value="OTH1" <?php echo ($m->ssv_completed == 'OTH1') ? 'selected="selected"' : '';?>>Other</option>
                   </select>
                 </label></td>
               </tr>               
               <tr>
                 <td><div align="right"><strong>Notes:</strong></div></td>
                 <td rowspan="2" valign="top"><label>
                   <textarea name="notes" id="textarea" cols="45" rows="5"><?=$m->notes;?></textarea>
                 </label></td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td valign="middle">
                   <input type="submit" name="save" id="save" value="Update Information" />
                 <?=anchor('profile/view/'.$this->uri->segment(3),'Cancel');?></td>
               </tr>
		</form>
               <tr>
                 <td>&nbsp;</td>
                 <td valign="middle">&nbsp;</td>
               </tr>
          <?php if($this->session->userdata('contact_id') != $m->contact_id && empty($resp)) { ?>
               <tr>
                 <td>&nbsp;</td>
                 <td valign="middle">&nbsp;</td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td valign="middle">&nbsp;</td>
               </tr>

		<form id="del_ss" name="del_ss" onsubmit="return confirmSubmit()" method="post" action="/profile/del_ss/">
              <tr>
                 <td>&nbsp;</td>
		   <?=form_hidden('contact_id', $m->contact_id);?>
                 <td valign="middle"><input type="submit" name="button" id="button" value="DELETE THIS CONTACT" /></td>
               </tr>
		</form>
        <?php } ?>
               <tr>
                 <td>&nbsp;</td>
                 <td valign="middle">&nbsp;</td>
               </tr>
             </table>
