<style type="text/css">
<!--
.style1 {font-weight: bold}
.style2 {font-weight: bold}
.style3 {color: #666666}
-->
</style>

<h2><?=$vibhag->name?> - Edit E-mail List</h2>
<div style="font-weight:bold;">
<ul> <?php //foreach($data['msg'] as $i) 	echo '<li>'. $i . '</li>'; ?> </ul>
</div>
	
<?=form_open($this->uri->uri_string());?>
<?=form_hidden('level_id', $this->uri->segment(3));?>
<?=form_hidden('level', 'VI');?>
  <table width="650" border="0" align="left" cellpadding="2" cellspacing="5">
    <tr valign="middle">
      <td width="139" align="right"><strong>List Name:</strong></td>
      <td colspan="2"><label>
        <strong><?=$lists->address?>@lists.hssusa.org</strong></label></td>
    </tr>
    <tr valign="middle">
      <td align="right" valign="top"><strong>List Style:</strong></td>
      <td colspan="2"><p>
        <label>
          <input type="radio" name="style" value="0" id="style_0" <?php if($lists->style == 0) echo 'CHECKED';?>/>
          Moderated</label>
        <span class="style3">(Recommended for general swayamsevaks list)</span><br />
        <label>
          <input type="radio" name="style" value="1" id="style_1" <?php if($lists->style == 1) echo 'CHECKED';?>/>
          Un-Moderated</label>
        <span class="style3">(Recommended for Kayarakartas list)</span><br />
      </p></td>
    </tr>
    <tr valign="middle">
      <td align="right">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr valign="middle">
      <td align="right"><strong>Moderator 1:</strong></td>
      <td colspan="2"><label>
        <select name="mod1" id="mod1">
        <?php if(isset($vibhag->kk)) {
				foreach ($vibhag->kk as $kk) {
         		echo '<option value="'.$kk->contact_id.'" ';
		  		echo (($lists->mod1 == $kk->contact_id) ? 'selected="selected" ' : '');
				echo ">$kk->first_name $kk->last_name &nbsp;</option>";
				} } ?>
        </select>
      </label></td>
    </tr>
    <tr valign="middle">
      <td align="right"><strong>Moderator 2:</strong></td>
      <td colspan="2"><label>
        <select name="mod2" id="mod2">
          <option value=""></option>
        <?php if(isset($vibhag->kk)) {
				foreach ($vibhag->kk as $kk) {
				echo '<option value="'.$kk->contact_id.'" ';
		  		echo (($lists->mod2 == $kk->contact_id) ? 'selected="selected" ' : '');
				echo ">$kk->first_name $kk->last_name &nbsp;</option>";
				} } ?>
                        </select>
      </label></td>
    </tr>
    <tr valign="middle">
      <td align="right"><strong>Moderator 3:</strong></td>
      <td colspan="2"><label>
        <select name="mod3" id="mod3">
          <option value=""></option>
        <?php if(isset($vibhag->kk)) {
				foreach ($vibhag->kk as $kk) {
         		echo '<option value="'.$kk->contact_id.'" ';
		  		echo (($lists->mod3 == $kk->contact_id) ? 'selected="selected" ' : '');
				echo ">$kk->first_name $kk->last_name &nbsp;</option>";
				} } ?>
                        </select>
      </label></td>
    </tr>
    <tr valign="middle">
      <td align="right">&nbsp;</td>
      <td colspan="2"><span class="style3">(Moderators receive notifications whenever a message needs approval)</span></td>
    </tr>
    <tr valign="middle">
      <td align="right"><strong>Moderator Password:</strong></td>
      <td colspan="2"><label>
        <?php if($lists->mod_pass == 'swayamsevak') : ?>
            <strong>swayamsevak</strong>
            <input name="mod_pass" type="hidden" id="mod_pass" value='swayamsevak'/>
        <?php else: ?>
            <input name="mod_pass" type="text" id="pass1" size="35" maxlength="50" value="<?=$lists->mod_pass?>"/>
        <?php endif; ?>
        <!--<input name="mod_pass" type="text" id="pass1" size="35" maxlength="50" value="<?=$lists->mod_pass?>"/>
      --></label></td>
    </tr>
    <tr valign="middle">
      <td align="right">&nbsp;</td>
      <td colspan="2"><span class="style3">(Moderators share same password)</span></td>
    </tr>
    <tr valign="middle">
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="middle">
      <td align="right"><strong>List Members:</strong></td>
      <td width="203"><label>
        <?php $members = unserialize($lists->members); ?>
        <span class="style2">
        <input name="members[]" type="checkbox" id="checkbox0" onClick="elistCheck()" value="allss" <?php if(in_array('allss', $members)) echo 'checked="checked"';?>/>
        </span></label>
        <strong>All Contacts in Vibhag</strong></td>
      <td width="276"><label>
        <span class="style1">
        <input name="members[]" type="checkbox" id="checkbox1" onClick="elistCheck()" value="allgc" <?php if(in_array('allgc', $members)) echo 'checked="checked"';?>/>
        </span></label>
        <strong>All General Contacts in Vibhag</strong></td>
    </tr>
    <tr valign="middle">
      <td align="right">&nbsp;</td>
      <td><label>
        <input name="members[]" type="checkbox" id="checkbox2" onClick="elistCheck()" value="shakhaTeamVI" <?php if(in_array('shakhaTeamVI', $members)) echo 'checked="checked"';?>/>
      </label> 
        All Shakha Karyakarta</td>
      <td><label>
        <input name="members[]" type="checkbox" id="checkbox3" onClick="elistCheck()" value="shakhaKaryakartaVI" <?php if(in_array('shakhaKaryakartaVI', $members)) echo 'checked="checked"';?>/>
      </label> 
        All Mukhya Shikshaks + Karyavahs</td>
    </tr>
    <tr valign="middle">
      <td align="right">&nbsp;</td>
      <td><label>
        <input name="members[]" type="checkbox" id="checkbox4" onClick="elistCheck()" value="allkk" <?php if(in_array('allkk', $members)) echo 'checked="checked"';?>/>
      </label> 
        Complete Vibhag Team</td>
      <td><label>
        <input name="members[]" type="checkbox" id="checkbox5" onClick="elistCheck()" value="sambhagTeamAll" <?php if(in_array('sambhagTeamAll', $members)) echo 'checked="checked"';?>/>
      </label> 
        Complete Sambhag Team</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="middle">
      <td align="right">&nbsp;</td>
      <td><label>
        <input name="members[]" type="checkbox" id="checkbox6" onClick="elistCheck()" value="sambhagTeamVI" <?php if(in_array('sambhagTeamVI', $members)) echo 'checked="checked"';?>/>
      </label> 
        All Sambhag Karyakarta in Vibhag</td>
      <td><label>
        <input name="members[]" type="checkbox" id="checkbox7" onClick="elistCheck()" value="nationalTeamVI" <?php if(in_array('nationalTeamVI', $members)) echo 'checked="checked"';?>/>
      </label>
		All National Karyakarta in Vibhag</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="middle">
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="middle">
      <td align="right">&nbsp;</td>
      <td><label>
        <input type="submit" name="button" id="button" value="Update E-mail List" />
      </label></td>
      <td>&nbsp;</td>
    </tr>
    <?=form_close()?>
    <tr valign="middle">
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <?php if($lists->status != 'Deleting'): ?>
    <tr valign="middle">
      <td align="right">&nbsp;</td>
      <td><form id="form1" name="form1" method="post" onsubmit="return confirmSubmit()" action="<?php echo '/vibhag/del_list/',$this->uri->segment(3),'/',$this->uri->segment(4)?>">
        <input type="submit" name="button2" id="button2" value="Delete This e-mail list" />
            </form>
      </td>
      <td>&nbsp;</td>
    </tr><?php endif; ?>
  </table>
  <p>&nbsp;</p>
  <table>
  	<tr><th>List Members</th></tr>
  	<?php 
  		$count = count($emails);
  		$j = 0;
  		$max = ceil($count / 3);
  		for($i = 0; $i < $max; $i++): ?>
  	<tr><td>
  		<?php
  			 if($j == $count): 
  		 		echo ''; 
  		 	else:
  				echo anchor('profile/view/'.$emails[$j]['contact_id'], $emails[$j]['first_name'] . ' ' . $emails[$j]['last_name']);
  				$j++;
  			endif; ?>
  		</td>
  		<td>
  		<?php
  			 if($j == $count): 
  		 		echo ''; 
  		 	else:
  				echo anchor('profile/view/'.$emails[$j]['contact_id'], $emails[$j]['first_name'] . ' ' . $emails[$j]['last_name']);
  				$j++;
  			endif; ?>
  		</td>
  		<td>
  		<?php
  			 if($j == $count): 
  		 		echo ''; 
  		 	else:
  				echo anchor('profile/view/'.$emails[$j]['contact_id'], $emails[$j]['first_name'] . ' ' . $emails[$j]['last_name']);
  				$j++;
  			endif; ?>
  		</td>
  	</tr>
  	<?php endfor; ?>
  	</table>