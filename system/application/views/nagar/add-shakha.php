<?=form_open($this->uri->uri_string())?>
<?=form_hidden('nagar_id', $this->uri->segment(3));?>
<?=form_hidden('vibhag_id', substr($this->uri->segment(3),0,4));?>
<?=form_hidden('sambhag_id', substr($this->uri->segment(3),0,2));?>
			 <h2><?=$row->name?> - Add Shakha</h2>
		     <h3>Shakha Information</h3>
		     <table width="100%" border="0" cellpadding="2" cellspacing="4">
               <tr>
                 <td width="19%"><div align="right"><strong>Shakha Name:
                   </strong></div>                   
                   <strong>
                   <label></label>
                   </strong></td>
                 <td><input name="name" type="text" id="name" size="40" maxlength="60" />
                 <label></label></td>
               </tr>
               <tr>
                 <td><div align="right"><strong>Street Address:</strong></div></td>
                 <td><input name="address1" type="text" id="textfield9" size="35" maxlength="50" /></td>
               </tr>
               <tr>
                 <td><div align="right"></div></td>
                 <td><input name="address2" type="text" id="textfield10" size="35" maxlength="50" /></td>
               </tr>
               <tr>
                 <td><div align="right"><strong>City:</strong></div></td>
                 <td><input name="city" type="text" id="textfield11" size="35" maxlength="50" />                   <label></label></td>
               </tr>
               <tr>
                 <td><div align="right"><strong>State: </strong></div></td>
                 <td valign="middle"><select name="state" id="select">
                   <?php foreach($states as $var) 
				   {
				   		echo "<option value=\"$var->REF_CODE\"";
						/*echo (($var->REF_CODE == $shakha_st) ? ' selected="selected"' : '');*/
						echo ">$var->short_desc</option>\n";
					}
					?>
                   <!--<option value="New Jersey">New Jersey</option>
                   <option value="New York">New York</option>-->
                 </select>
                 &nbsp;<strong>&nbsp;Zip:</strong> 
                 <label>
                 <input name="zip" type="text" id="zip" size="8" maxlength="5"/>
                 </label></td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td valign="middle">&nbsp;</td>
               </tr>
               <tr>
                 <td><div align="right"><strong>Frequency:</strong></div></td>
                 <td valign="middle">
                   <select name="frequency" id="frequency">
                     <option value="WK" selected="selected">Weekly</option>
                     <option value="BW">Every Other Week&nbsp;</option>
                     <option value="DL">Daily</option>
                     <option value="ML">Monthly&nbsp;</option>
                   </select>
                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>&nbsp;Day: </strong>                 <select name="frequency_day" id="frequency_day">
                   <option selected="selected">&nbsp;</option>
                   <option value="Saturday" >Saturday</option>
                   <option value="Sunday">Sunday</option>
                   <option value="Monday">Monday</option>
                   <option value="Tuesday">Tuesday</option>
                   <option value="Wednesday">Wednesday&nbsp;</option>
                   <option value="Thursday">Thursday</option>
                   <option value="Friday">Friday</option>
                 </select>                 </td>
               </tr>
               <tr>
                 <td><div align="right"><strong>Start Time::</strong></div></td>
                 <td valign="middle">
                   <select name="time_from" id="time_from">
                   <option value="" selected="selected">Select Time&nbsp;</option>
                   <?php 
				   $time = mktime(5, 0, 0, 1, 1, 2007);
				   while($time <= 1167706800){
				   		echo '<option value="'.date('H:i:s', $time).'">'.date('h:i A', $time)."&nbsp;</option>\n";
						$time += 900;
					}?>
                 </select>
                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>End Time:</strong>                 <select name="time_to" id="time_to">
                   <option value="" selected="selected">Select Time&nbsp;</option>
                   <?php 
				   $time = mktime(6, 0, 0, 1, 1, 2007);
				   while($time <= 1167710400){
				   		echo '<option value="'.date('H:i:s', $time).'">'.date('h:i A', $time)."&nbsp;</option>\n";
						$time += 900;
					}?>
                 </select></td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td valign="middle">&nbsp;</td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td valign="middle">
                   <input type="submit" name="save" id="save" value="Add Shakha" />                 </td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td valign="middle">&nbsp;</td>
               </tr>
             </table>
<?=form_close(); ?>