<?php if(!$this->uri->segment(4)) redirect('shakha/add_sankhya/'.$this->uri->segment(3).'/'.$dates[0]['datemysql']); ?>
<?php $set = isset($sankhya) ? true : false ; ?>
<h2><?=$shakha->name;?> - Report Sankhya</h2>
<p>&nbsp;</p>
		   <form id="sankhya" method="post" action="/shakha/insert_sankhya/<?php echo $shakha->shakha_id; ?>">
           <?=form_hidden('shakha_id', $shakha->shakha_id);?>
		     <h3>Date:
		     <label>
		     <select name="date" id="date" onchange="MM_jumpMenu('parent',this,1)">
		     
			<?php 
				//Output the Dates ... If the date in URL doesn't exists, add it.
				$match = false;
				$ops = '';
				foreach($dates as $date) {
					$ops .= '<option value="'.$date['datemysql'].'" ';
					$ops .= (($date['datemysql'] == $this->uri->segment(4)) ? 'selected="selected" ' : '');
					$ops .= '>'.$date['date']."&nbsp;</option>\n";
					if($date['datemysql'] == $this->uri->segment(4)) $match = true;
				}
				
				if(false == $match) {
					$ops .= '<option value="'.$this->uri->segment(4).'" ';
					$ops .= 'selected="selected" >' . date('l, F j, o', strtotime($this->uri->segment(4))) . "&nbsp;</option>\n";
				}
				echo $ops;
			?>
	         </select>
		     </label></h3>
		     <?php if($set && $contact) {
		     			echo '<strong>Last Updated By: </strong>';
		     			echo anchor('profile/view/'.$contact->contact_id, $contact->first_name . ' ' . $contact->last_name);
		     			echo '<br /><br />';
		     }
		     ?>
		     <table border="0" cellspacing="2" cellpadding="2">
               <tr>
                 <td width="102">&nbsp;</td>
                 <td width="95">&nbsp;Swayamsevak&nbsp;</td>
                 <td width="61">&nbsp;Sawayamsevika&nbsp;</td>
                 <td width="151">&nbsp;</td>
               </tr>
               <tr>
                 <td><div align="right">Shishu ( &lt; 6 yrs)</div></td>
                 <td><div align="center"><input name="shishu_m" type="text" id="shishu_m" onblur="totalSankhya()" value="<?php if($set) echo $sankhya->shishu_m; else echo '0';?>" size="4" maxlength="3" /></div></td>
                 <td><div align="center"><input name="shishu_f" type="text" id="shishu_f" onblur="totalSankhya()" value="<?php if($set) echo $sankhya->shishu_f; else echo '0';?>" size="4" maxlength="3" /></div></td>
                 <td>&nbsp;</td>
               </tr>
               <tr>
                 <td><div align="right">Bala (&lt;12 yrs)</div></td>
                 <td><div align="center"><input name="bala_m" type="text" id="bala_m" size="4" onblur="totalSankhya()" value="<?php if($set) echo $sankhya->bala_m; else echo '0';?>"  maxlength="3" /></div></td>
                 <td><div align="center"><input name="bala_f" type="text" id="bala_f" size="4" onblur="totalSankhya()" value="<?php echo (($set) ? $sankhya->bala_f : '0');?>"  maxlength="3" /></div></td>
                 <td>&nbsp;</td>
               </tr>
               <tr>
                 <td><div align="right">Kishor (&lt;19 yrs)</div></td>
                 <td><div align="center"><input name="kishor_m" type="text" id="kishor_m" size="4" onblur="totalSankhya()" value="<?php echo (($set) ? $sankhya->kishor_m : '0');?>"   maxlength="3" /></div></td>
                 <td><div align="center"><input name="kishor_f" type="text" id="kishor_f" size="4" onblur="totalSankhya()" value="<?php echo (($set) ? $sankhya->kishor_f : '0');?>"   maxlength="3" /></div></td>
                 <td>&nbsp;</td>
               </tr>
               <tr>
                 <td><div align="right">Yuva (&lt;26 yrs)</div></td>
                 <td><div align="center"><input name="yuva_m" type="text" id="yuva_m" size="4" onblur="totalSankhya()" value="<?php echo (($set) ? $sankhya->yuva_m : '0');?>"  maxlength="3" /></div></td>
                 <td><div align="center"><input name="yuva_f" type="text" id="yuva_f" size="4" onblur="totalSankhya()" value="<?php echo (($set) ? $sankhya->yuva_f : '0');?>"  maxlength="3" /></div></td>
                 <td>&nbsp;</td>
               </tr>
               <tr>
                 <td><div align="right">Tarun(&lt; 50 yrs)</div></td>
                 <td><div align="center"><input name="tarun_m" type="text" id="tarun_m" size="4" onblur="totalSankhya()"  value="<?php echo (($set) ? $sankhya->tarun_m : '0');?>"  maxlength="3" /></div></td>
                 <td><div align="center"><input name="tarun_f" type="text" id="tarun_f" size="4" onblur="totalSankhya()"  value="<?php echo (($set) ? $sankhya->tarun_f : '0');?>"  maxlength="3" /></div></td>
                 <td>&nbsp;</td>
               </tr>
               <tr>
                 <td><div align="right">Praudh</div></td>
                 <td><div align="center"><input name="praudh_m" type="text" id="praudh_m" size="4" onblur="totalSankhya()"   value="<?php echo (($set) ? $sankhya->praudh_m : '0');?>" maxlength="3" /></div></td>
                 <td><div align="center"><input name="praudh_f" type="text" id="praudh_f" size="4" onblur="totalSankhya()"   value="<?php echo (($set) ? $sankhya->praudh_f : '0');?>" maxlength="3" /></div></td>
                 <td>&nbsp;</td>
               </tr>
               <tr>
                 <td><div align="right">
                     <h3>Sub-Total</h3>
                 </div></td>
                 <td><div align="center" id="subtt_m"></div></td>
                 <td><div align="center" id="subtt_f"></div></td>
                 <td>&nbsp;</td>
               </tr>
               <tr>
                 <td><div align="right">
                     <h3>Total</h3>
                 </div></td>
                 <td colspan="2" align="center"><div id="total"><?php echo (($set) ? $sankhya->total : '0');?></div></td>
                 <td align="center">&nbsp;</td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td>&nbsp;</td>
                 <td>&nbsp;</td>
                 <td>&nbsp;</td>
               </tr>
               <tr>
                 <td valign="top"><div align="right">Shakha Notes:</div></td>
                 <td colspan="3"><textarea name="shakha_info" id="shakha_info" cols="45" rows="5"><?php echo (($set) ? $sankhya->shakha_info : '');?></textarea></td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td>&nbsp;</td>
                 <td>&nbsp;</td>
                 <td>&nbsp;</td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td colspan="3"><label>
                   <div align="center">
                     <input type="submit" name="button" id="button" value="Submit Sankhya" />
                     </div>
                 </label></td>
               </tr>
             </table>
</form>