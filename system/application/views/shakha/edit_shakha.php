<?=form_open($this->uri->uri_string())?>
			 <h2><?=$row->name?> - Change Shakha Information</h2>
		     <h3>Shakha Information</h3>
		     <table width="100%" border="0" cellpadding="2" cellspacing="4">
               <tr>
                 <td width="19%"><div align="right">Shakha Name:
                   </div>                   <label></label></td>
                 <td><input name="name" type="text" id="name" size="40" value = "<?=$row->name?>" maxlength="60" />
                 <label></label></td>
               </tr>
               <tr>
                 <td><div align="right">Street Address:</div></td>
                 <td><input name="address1" type="text" id="textfield9" size="35" value = "<?=$row->address1?>" maxlength="50" /></td>
               </tr>
               <tr>
                 <td><div align="right"></div></td>
                 <td><input name="address2" type="text" id="textfield10" size="35" value = "<?=$row->address2?>" maxlength="50" /></td>
               </tr>
               <tr>
                 <td><div align="right">City:</div></td>
                 <td><input name="city" type="text" id="textfield11" size="35" value = "<?=$row->city?>" maxlength="50" />                   <label></label></td>
               </tr>
               <tr>
                 <td><div align="right">State: </div></td>
                 <td valign="middle"><select name="state" id="select">
                   <?php foreach($states as $var) 
				   {
				   		echo "<option value=\"$var->REF_CODE\"";
						echo (($var->REF_CODE == $row->state) ? ' selected="selected"' : '');
						echo ">$var->short_desc</option>\n";
					}
					?>
                 </select>
                 &nbsp;&nbsp;Zip: 
                 <label>
                 <input name="zip" type="text" id="zip" size="8" value = "<?=$row->zip?>" maxlength="5"/>
                 </label></td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td valign="middle">&nbsp;</td>
               </tr>
               <tr>
                 <td><div align="right">Frequency:</div></td>
                 <td valign="middle">
                   <select name="frequency" id="frequency">
                     <option value="WK" <?php echo (($row->frequency == 'WK') ? 'selected="selected"' : '');?>>Weekly</option>
                     <option value="BW" <?php echo (($row->frequency == 'BW') ? 'selected="selected"' : '');?>>Every other Week&nbsp;</option>
                     <option value="DL" <?php echo (($row->frequency == 'DL') ? 'selected="selected"' : '');?>>Daily</option>
                     <option value="ML" <?php echo (($row->frequency == 'ML') ? 'selected="selected"' : '');?>>Monthly</option>
                   </select>
                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Day: 
                 <select name="frequency_day" id="frequency_day">
                   <option>&nbsp;</option>
                   <option value="Saturday" <?php echo (($row->frequency_day == 'Saturday') ? 'selected="selected"' : '');?>>Saturday</option>
                   <option value="Sunday" <?php echo (($row->frequency_day == 'Sunday') ? 'selected="selected"' : '');?>>Sunday</option>
                   <option value="Monday" <?php echo (($row->frequency_day == 'Monday') ? 'selected="selected"' : '');?>>Monday</option>
                   <option value="Tuesday" <?php echo (($row->frequency_day == 'Tuesday') ? 'selected="selected"' : '');?>>Tuesday</option>
                   <option value="Wednesday" <?php echo (($row->frequency_day == 'Wednesday') ? 'selected="selected"' : '');?>>Wednesday&nbsp;</option>
                   <option value="Thursday" <?php echo (($row->frequency_day == 'Thursday') ? 'selected="selected"' : '');?>>Thursday</option>
                   <option value="Friday" <?php echo (($row->frequency_day == 'Friday') ? 'selected="selected"' : '');?>>Friday</option>
                 </select>                 </td>
               </tr>
               <tr>
                 <td><div align="right">Start Time::</div></td>
                 <td valign="middle"><label>
                   <select name="time_from" id="time_from">
                   <option value="" selected="selected">Select Time&nbsp;</option>
                   <?php 
				   $time = mktime(5, 0, 0, 1, 1, 2007);
				   while($time <= 1167706800){
				   		echo '<option value="'.date('H:i:s', $time).'" ';
						echo ((date('H:i:s', $time) == $row->time_from) ? ' selected="selected"' : '');
						echo ' >'.date('h:i A', $time)."&nbsp;</option>\n";
						$time += 900;
					}?>
                 </select>
                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;End Time: </label>
                 <select name="time_to" id="time_to">
                   <option value="" selected="selected">Select Time&nbsp;</option>
                   <?php 
				   $time = mktime(6, 0, 0, 1, 1, 2007);
				   while($time <= 1167710400){
				   		echo '<option value="'.date('H:i:s', $time).'" ';
						echo ((date('H:i:s', $time) == $row->time_to) ? ' selected="selected"' : '');
						echo ' >'.date('h:i A', $time)."&nbsp;</option>\n";
						$time += 900;
					}?>
                 </select></td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td valign="middle">&nbsp;</td>
               </tr>
               <tr>
                 <td align="right">Shakha Status:</td>
                 <td valign="middle"><select name="shakha_status" id="shakha_status">
                   <option value="1" <?php echo ($row->shakha_status == 1) ? ' selected="selected"' : ''; ?>>Active&nbsp;</option>
                   <option value="0" <?php echo ($row->shakha_status == 0) ? ' selected="selected"' : ''; ?>>Inactive&nbsp;</option>
                 </select>
                 </td>
               </tr>
               <tr>
                 <td align="right">Vibhag:</td>
                 <td valign="middle"><select name="vibhag_id" id="vibhag_id">
                 <?php foreach($vibhags as $key => $vibhag): ?>
                 <option value="<?=$key?>" 
                 	<?php if($row->vibhag_id == $key || $row->nagar_id == $key) echo ' selected="selected"';?>>
                 <?=$vibhag?>&nbsp;</option>
                 <?php endforeach; ?>
                 </select>
                 </td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td valign="middle"><label>
                   <input type="submit" name="save" id="save" value="Update Shakha Information" />
                 </label></td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td valign="middle">&nbsp;</td>
               </tr>
             </table>
<?=form_close(); ?>