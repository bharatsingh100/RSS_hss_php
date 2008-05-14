<?php if(!$this->uri->segment(4)) redirect('shakha/add_sankhya/'.$this->uri->segment(3).'/'.$dates[0]['datemysql']); ?>
<?php $set = isset($sankhya) ? true : false ; ?>
<h2><?=$shakha->name;?> - Report Sankhya</h2>
<p>&nbsp;</p>
		   <form id="sankhya" method="post" action="/shakha/insert_sankhya/">
           <?=form_hidden('shakha_id', $shakha->shakha_id);?>
		     <h3>Date:
		     <label>
		     <select name="date" id="date" onchange="MM_jumpMenu('parent',this,1)">
			<?php foreach($dates as $date) { 
			echo '<option value="'.$date['datemysql'].'" ';
			echo (($date['datemysql'] == $this->uri->segment(4)) ? 'selected="selected" ' : '');
			echo '>'.$date['date']."&nbsp;</option>\n";
			} ?>
	         </select>
		     </label></h3><br />
		     <table border="0" cellspacing="2" cellpadding="2">
               <tr>
                 <td>&nbsp;</td>
                 <td>&nbsp;Swayamsevak&nbsp;</td>
                 <td>&nbsp;Sevika&nbsp;</td>
               </tr>
               <tr>
                 <td><div align="right">Shishu ( &lt; 6 yrs)</div></td>
                 <td><div align="center"><input name="shishu_m" type="text" id="shishu_m" onblur="totalSankhya()" value="<?php if($set) echo $sankhya->shishu_m; else echo '0';?>" size="4" maxlength="3" /></div></td>
                 <td><div align="center"><input name="shishu_f" type="text" id="shishu_f" onblur="totalSankhya()" value="<?php if($set) echo $sankhya->shishu_f; else echo '0';?>" size="4" maxlength="3" /></div></td>
               </tr>
               <tr>
                 <td><div align="right">Bala (&lt;12 yrs)</div></td>
                 <td><div align="center"><input name="bala_m" type="text" id="bala_m" size="4" onblur="totalSankhya()" value="<?php if($set) echo $sankhya->bala_m; else echo '0';?>"  maxlength="3" /></div></td>
                 <td><div align="center"><input name="bala_f" type="text" id="bala_f" size="4" onblur="totalSankhya()" value="<?php echo (($set) ? $sankhya->bala_f : '0');?>"  maxlength="3" /></div></td>
               </tr>
               <tr>
                 <td><div align="right"><div align="right">Kishor (&lt;19 yrs)</div></td>
                 <td><div align="center"><input name="kishor_m" type="text" id="kishor_m" size="4" onblur="totalSankhya()" value="<?php echo (($set) ? $sankhya->kishor_m : '0');?>"   maxlength="3" /></div></td>
                 <td><div align="center"><input name="kishor_f" type="text" id="kishor_f" size="4" onblur="totalSankhya()" value="<?php echo (($set) ? $sankhya->kishor_f : '0');?>"   maxlength="3" /></div></td>
               </tr>
               <tr>
                 <td><div align="right"><div align="right">Yuva (&lt;26 yrs)</div></td>
                 <td><div align="center"><input name="yuva_m" type="text" id="yuva_m" size="4" onblur="totalSankhya()" value="<?php echo (($set) ? $sankhya->yuva_m : '0');?>"  maxlength="3" /></div></td>
                 <td><div align="center"><input name="yuva_f" type="text" id="yuva_f" size="4" onblur="totalSankhya()" value="<?php echo (($set) ? $sankhya->yuva_f : '0');?>"  maxlength="3" /></div></td>
               </tr>
               <tr>
                 <td><div align="right"><div align="right">Tarun(&lt; 50 yrs)</div></td>
                 <td><div align="center"><input name="tarun_m" type="text" id="tarun_m" size="4" onblur="totalSankhya()"  value="<?php echo (($set) ? $sankhya->tarun_m : '0');?>"  maxlength="3" /></div></td>
                 <td><div align="center"><input name="tarun_f" type="text" id="tarun_f" size="4" onblur="totalSankhya()"  value="<?php echo (($set) ? $sankhya->tarun_f : '0');?>"  maxlength="3" /></div></td>

               </tr>
               <tr>
                 <td><div align="right"><div align="right">Praudh</div></td>
                 <td><div align="center"><input name="praudh_m" type="text" id="praudh_m" size="4" onblur="totalSankhya()"   value="<?php echo (($set) ? $sankhya->praudh_m : '0');?>" maxlength="3" /></div></td>
                 <td><div align="center"><input name="praudh_f" type="text" id="praudh_f" size="4" onblur="totalSankhya()"   value="<?php echo (($set) ? $sankhya->praudh_f : '0');?>" maxlength="3" /></div></td>
               </tr>
               <tr>
                 <td><div align="right">
                     <h3>Sub-Total</h3>
                 </div></td>
                 <td><div id="subtt_m"></div></td>
                 <td><div id="subtt_f"></div></td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td>&nbsp;</td>
                 <td>&nbsp;</td>
               </tr>
               <tr>
                 <td><div align="right">
                     <h3>Total</h3>
                 </div></td>
                 <td colspan="2" align="center"><div id="total"><?php echo (($set) ? $sankhya->total : '0');?></div></td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td>&nbsp;</td>
                 <td>&nbsp;</td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td colspan="2"><label>
                   <input type="submit" name="button" id="button" value="Sumbit Sankhya" />
                 </label></td>
               </tr>
             </table>
</form>