<h2><?=$shakha_name?> - Family Connect</h2>
		   <form id="responsibility" method="post" action="<?php echo '/'.$this->uri->segment(1) .'/'.$this->uri->segment(2).'/'.$this->uri->segment(3);?>">
		     <table width="100%" border="0" cellspacing="2" cellpadding="2">
               <tr>
                 <td width="40%"><h3><?=$contact->first_name?> <?=$contact->last_name?>&nbsp;</td>
                 <td width="60%"><label>
                 	<select name="household_id" id="household_id">
                       <option value="0" selected="selected">Select Person to Connect to ...</option>
                     	<?php
								foreach($households as $h){
									if($h->household_id != $contact->household_id)
									echo "<option value=\"$h->household_id\">$h->first_name $h->last_name&nbsp;</option>\n";
								}
						?>
                     </select>
                 </label></td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td><label>
                   <input type="submit" name="button" id="button" value="Add to Family" />
                 </label></td>
               </tr>
             </table>
           </form>