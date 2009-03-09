<style type="text/css">
<!--
.style1 {font-weight: bold}
.style2 {font-weight: bold}
.style3 {color: #666666}
-->
</style>

<h2><?=$sambhag->name?> - Edit E-mail List</h2>
<div style="font-weight:bold;">
<ul> <?php //foreach($data['msg'] as $i) 	echo '<li>'. $i . '</li>'; ?> </ul>
</div>
	
<?=form_open($this->uri->uri_string());?>
<?=form_hidden('level_id', $this->uri->segment(3));?>
<?=form_hidden('level', 'SA');?>
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
        <?php if(isset($sambhag->kk)) {
				foreach ($sambhag->kk as $kk) {
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
        <?php if(isset($sambhag->kk)) {
				foreach ($sambhag->kk as $kk) {
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
        <?php if(isset($sambhag->kk)) {
				foreach ($sambhag->kk as $kk) {
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
        <input name="mod_pass" type="text" id="pass1" size="35" maxlength="50" value="<?=$lists->mod_pass?>"/>
      </label></td>
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
        <input name="members[]" type="checkbox" id="checkbox" value="allss" <?php if(in_array('allss', $members)) echo 'checked="checked"';?>/>
        </span></label>
        <strong>      All Swayamsevaks</strong></td>
      <td width="276"><label>
        <span class="style1">
        <input name="members[]" type="checkbox" id="checkbox2" value="allkk" <?php if(in_array('allkk', $members)) echo 'checked="checked"';?>/>
        </span></label>
        <strong> All Karyakartas</strong></td>
    </tr>
    <!--<tr valign="middle">
      <td align="right">&nbsp;</td>
      <td><label>
        <input name="members[]" type="checkbox" id="checkbox3" value="bala" />
      </label> 
        Bala only</td>
      <td><label>
        <input name="members[]" type="checkbox" id="checkbox8" value="gatanayak" />
      </label> 
        Gatanayaks Only</td>
    </tr>
    <tr valign="middle">
      <td align="right">&nbsp;</td>
      <td><label>
        <input name="members[]" type="checkbox" id="checkbox4" value="kishor" />
      </label> 
        Kishor only</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="middle">
      <td align="right">&nbsp;</td>
      <td><label>
        <input name="members[]" type="checkbox" id="checkbox5" value="yuva" />
      </label> 
        Yuva only</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="middle">
      <td align="right">&nbsp;</td>
      <td><label>
        <input name="members[]" type="checkbox" id="checkbox6" value="tarun" />
      </label> 
        Tarun only</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="middle">
      <td align="right">&nbsp;</td>
      <td><label>
        <input name="members[]" type="checkbox" id="checkbox7" value="praudh" />
      </label>
Praudh only</td>
      <td>&nbsp;</td>
    </tr>-->
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
      <td><form id="form1" name="form1" method="post" onsubmit="return confirmSubmit()" action="<?php echo '/sambhag/del_list/',$this->uri->segment(3),'/',$this->uri->segment(4)?>">
        <input type="submit" name="button2" id="button2" value="Delete This e-mail list" />
            </form>
      </td>
      <td>&nbsp;</td>
    </tr><?php endif; ?>
  </table>