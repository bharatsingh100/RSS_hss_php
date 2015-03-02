<?php $fam = (isset($family)) ? true : false; ?>
<?php //if($fam) print_r($family); ?>
<?php $attributes = array('id' => 'addContactForm'); ?>
<?=form_open('shakha/add_family/'.$shakha_id,$attributes)?>
<?=form_hidden('shakha_id', $shakha_id);?>
<?php if($fam) echo form_hidden('household_id', $family['household_id']); ?>

			 <h2><?=$shakha_name?> - Add Contact</h2>
		     <h3>Personal Information</h3>
		     <table width="100%" border="0" cellpadding="2" cellspacing="4">
               <tr>
                 <td width="19%"><div align="right"><strong>Name:
                   </strong></div>                   <label></label></td>
                 <td colspan="3">
                 	<input name="name" type="text" id="name" value="<?php if($fam) echo ($family['last_name']); ?>" size="45" maxlength="50" />
                   &nbsp;                   
                 <!--  <label>
                   <input name="last_name" type="text" id="textfield2" size="30" maxlength="50" value = "<?php if($fam) echo ($family['last_name']); ?>"/>
                   </label> -->
                 </td>
               </tr>
               <tr>
                 <td valign="top"><div align="right"><strong>Gender:</strong></div></td>
                 <td colspan="3" valign="top"><label></label>
                   <p>
                       <input name="gender" type="radio" id="RadioGroup1_0" value="M" checked="checked" />
                       Swayamsevak
                       <input type="radio" name="gender" value="F" id="RadioGroup1_1" />
                       Sevika
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Age:</strong> 
                     <label>
                     <select name="birth_year" id="birth_year" onchange="setGana()">
                       <option value="" selected="selected" ></option>
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
                 <td align="right" valign="top"><strong>Contact Type:</strong></td>
                 <td colspan="3" valign="top"><select name="contact_type" id="contact_type">
                 <?php foreach($ctype as $t){ ?>
                 		<option value="<?php echo $t->REF_CODE; ?> "
                    <?php echo (($t->REF_CODE == "RA") ? ' selected="selected" ' : ''); ?>
                     ><?php echo $t->short_desc,'&nbsp;';?></option>
                 <?php } ?>
                 </select>
                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Gana:&nbsp;
                 </strong>
                 <select name="gana" id="gana">
                   <?php foreach($ganas as $t){ ?>
                   <option value="<?php echo $t->REF_CODE; ?> "
                    <?php echo (($t->REF_CODE == "5") ? ' selected="selected" ' : ''); ?>
                     ><?php echo $t->short_desc,'&nbsp;';?></option>
                   <?php } ?>
                 </select></td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td colspan="3" valign="top">&nbsp;</td>
               </tr>
               <tr>
                 <td><div align="right"><strong>Company/School:</strong></div></td>
                 <td colspan="3" valign="top"><input name="company" type="text" id="textfield7" size="30" maxlength="50" />
                   <strong>Position/Grade:</strong>
                   <input name="position" type="text" id="textfield8" size="25" maxlength="50" /></td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td colspan="3" valign="middle">&nbsp;</td>
               </tr>
               <tr>
                 <td colspan="4"><h3>Contact Information</h3></td>
               </tr>
               <tr>
                 <td valign="top"><div align="right"><strong>E-mail:</strong></div></td>
               <td colspan="3"><label>
                   <input name="email" type="text" id="email" size="35" maxlength="60" /><span id="email-error"></span>
               <br />
                 <span class="style2">(Email Addresses must be unique. Two contacts cannot share same e-mail address)                   </span></label></td>
               </tr>
               <tr>
                 <td valign="top"><div align="right"><strong>Phone:</strong></div></td>
                 <td width="9%">
                   <input name="ph_mobile" type="text" id="ph_mobile" size="15" maxlength="14"/>
                   <span class="style2">Mobile</span></td>
                 <td width="9%"><input name="ph_home" type="text" id="ph_home" size="15" onclick="this.value = '';" maxlength="14" 
                 		value="<?php echo (($fam) ? $family['ph_home'] : '' );?>"/>
                 <span class="style2">Home</span></td>
                 <td width="63%"><input name="ph_work" type="text" id="ph_work" maxlength="14" size="15" />
                 <br />
                 <span class="style2">Work</span></td>
               </tr>
               <tr>
                 <td><div align="right"></div></td>
                 <td colspan="3">&nbsp;</td>
               </tr>
               <tr>
                 <td><div align="right"><strong>Street Address:</strong></div></td>
                 <td colspan="3"><input name="street_add1" type="text" id="textfield9" size="35" maxlength="50" value="<?php if($fam) echo ($family['street_add1']); ?>"/></td>
               </tr>
               <tr>
                 <td><div align="right"></div></td>
                 <td colspan="3"><input name="street_add2" type="text" id="textfield10" size="35" maxlength="50" value="<?php if($fam) echo ($family['street_add2']); ?>"/></td>
               </tr>
               <tr>
                 <td><div align="right"><strong>City:</strong></div></td>
                 <td colspan="3"><input name="city" type="text" id="textfield11" size="35" maxlength="50" value="<?php if($fam) echo ($family['city']); ?>"/>                   <label></label></td>
               </tr>
               <tr>
                 <td><div align="right"><strong>State: </strong></div></td>
                 <td colspan="3" valign="middle"><select name="state" id="select">
                   <?php foreach($states as $var) 
				   {
				   		echo "<option value=\"$var->REF_CODE\"";
						echo (($var->REF_CODE == $shakha_st) ? ' selected="selected"' : '');
						echo ">$var->short_desc</option>\n";
					}
					?>
                 </select>
                 &nbsp;<strong>&nbsp;Zip:</strong> 
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
               <tr valign="top">
                 <td><div align="right"><strong>Gatanayak:</strong></div></td>
                 <td colspan="3"><select name="gatanayak" id="select7">
                   <option value="" selected="selected"></option>
                   <?php foreach($gatanayak as $v) {
				   			echo '<option value="' . $v->contact_id . "\">$v->first_name $v->last_name ($v->num)</option>";
					} ?>
                 </select></td>
               </tr>
               <tr valign="top">
                 <td><div align="right"><strong>SSV Completed::</strong></div></td>
                 <td colspan="3"><select name="ssv_completed" id="select3">
                     <option value="" selected="selected"></option>
                     <?php  
					?>
                     <option value="USA1">Prathmik (1st year)</option>
                     <option value="USA2">Pravesh (2nd year)</option>
                     <option value="USA3">Praveen (3rd year)</option>
                     <option value="USA4">Prathm Varsh in Bharat</option>
                     <option value="USA5">Divitaya Varsh in Bharat</option>
                     <option value="USA6">Tritya Varsh in Bharat</option>
                     <option value="OTH1">Other</option>
                   </select>                 </td>
               </tr>
               <tr>
                 <td><div align="right"><strong>Notes:</strong></div></td>
                 <td colspan="3" rowspan="2" valign="top"><label>
                   <textarea name="notes" id="notes" cols="45" rows="5"></textarea>
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
<?=form_close(); ?>
<script type="text/javascript" src="<?=site_url();?>javascript/validateEmail.js"></script>

