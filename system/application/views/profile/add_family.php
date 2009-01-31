<h2><?=$shakha_name?> - Family Connect</h2>
		   <form id="responsibility" method="post" action="<?php echo '/'.$this->uri->segment(1) .'/'.$this->uri->segment(2).'/'.$this->uri->segment(3);?>">
		     <table width="100%" border="0" cellspacing="2" cellpadding="2">
               <tr>
                 <td width="40%"><h3><?=$contact->first_name?> <?=$contact->last_name?>&nbsp;</td>
                 <td width="60%"><label>
                 	<select name="household_id" id="household_id">
                       <option value="0" selected="selected">Select Person to Connect to ...</option>
                       <option value="-999">CREATE OWN FAMILY</option>
                     	<?php
								foreach($households as $h){
									if($h->household_id != $contact->household_id && trim($h->first_name) != '' && trim($h->last_name) != '') {
										echo "<option value=\"$h->household_id\">$h->first_name $h->last_name&nbsp;</option>\n"; }
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

<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<strong>Hints:</strong>
<ul>
  <li> <strong>To Connect a Person to a Family</strong>: Select any one person from the family that you want to connect to and click Add to Family.</li>
  <li> <strong>To Separate a Person from a Family</strong>: Select <em>CREATE OWN FAMILY</em>, 1st option, from the list and click Add to Family. </li>
</ul>

