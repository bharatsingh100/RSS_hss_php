<style type="text/css">
<!--
.style1 {font-weight: bold}
.style2 {font-weight: bold}
.style3 {color: #666666}
-->
</style>
<h2>Request E-mail List for <?=$vibhag->name?> Vibhag</h2>
<div style="font-weight:bold;">
<ul> <?php $ers = false; //if(isset($d) || isset($d['msg'])) { $ers = true; foreach($d['msg'] as $i) { echo '<li>'. $i . '</li>';} } ?> </ul>
</div>
<form id="createlist" name="createlist" method="post" onfocus="elistCheck()" action="/vibhag/add_email_list">
<?=form_hidden('level_id', $this->uri->segment(3));?>
<?=form_hidden('level', 'VI');?>
  <table width="650" border="0" align="left" cellpadding="2" cellspacing="5">
    <tr valign="middle">
      <td width="139" align="right"><strong>List Name:</strong></td>
      <td colspan="2"><label>
        <input name="address" type="text" id="address" size="35" maxlength="50" onBlur="elistCheck()" value="<?php if($ers) echo $d['address'];?>"/>
        <strong>@lists.hssusa.org</strong></label></td>
    </tr>
        <tr valign="middle">
      <td align="right">&nbsp;</td>
      <td colspan="2"><span class="style3">(No spaces. Only alphanumeric characters, dash and underscores are allowd.)</span></td>
    </tr>
    <tr valign="middle">
      <td align="right" valign="top"><strong>List Style:</strong></td>
      <td colspan="2"><p>
        <label>
          <input type="radio" name="style" value="0" id="style_0" <?php echo (($ers && $d['style'] == 1) ? '' : 'CHECKED');?>/>
          Moderated</label>
        <span class="style3">(Recommended for general swayamsevaks list)</span><br />
        <label>
          <input type="radio" name="style" value="1" id="style_1" <?php echo (($ers && $d['style'] == 1) ? 'CHECKED' : '');?>/>
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
		  		echo (($this->session->userdata('contact_id') == $kk->contact_id) ? 'selected="selected" ' : '');
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
		  		//echo (($this->session->userdata('contact_id') == $kk->contact_id) ? 'selected="selected" ' : '');
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
		  		//echo (($this->session->userdata('contact_id') == $kk->contact_id) ? 'selected="selected" ' : '');
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
        <strong>swayamsevak</strong>
        <input name="mod_pass" type="hidden" id="mod_pass" value='swayamsevak'/>
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
        <span class="style2">
        <input name="members[]" type="checkbox" id="checkbox0" onClick="elistCheck()" value="allss" />
        </span></label>
        <strong>All Contacts in Vibhag</strong></td>
      <td width="276"><label>
        <span class="style1">
        <input name="members[]" type="checkbox" id="checkbox1" onClick="elistCheck()" value="allgc" />
        </span></label>
        <strong>All General Contacts in Vibhag</strong></td>
    </tr>
    <tr valign="middle">
      <td align="right">&nbsp;</td>
      <td><label>
        <input name="members[]" type="checkbox" id="checkbox2" onClick="elistCheck()" value="shakhaTeamVI" />
      </label> 
        All Shakha Karyakarta</td>
      <td><label>
        <input name="members[]" type="checkbox" id="checkbox3" onClick="elistCheck()" value="shakhaKaryakartaVI" />
      </label> 
        All Mukhya Shikshaks + Karyavahs</td>
    </tr>
    <tr valign="middle">
      <td align="right">&nbsp;</td>
      <td><label>
        <input name="members[]" type="checkbox" id="checkbox4" onClick="elistCheck()" value="allkk" />
      </label> 
        Complete Vibhag Team</td>
      <td><label>
        <input name="members[]" type="checkbox" id="checkbox5" onClick="elistCheck()" value="sambhagTeamAll" />
      </label> 
        Complete Sambhag Team</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="middle">
      <td align="right">&nbsp;</td>
      <td><label>
        <input name="members[]" type="checkbox" id="checkbox6" onClick="elistCheck()" value="sambhagTeamVI" />
      </label> 
        All Sambhag Karyakarta in Vibhag</td>
      <td><label>
        <input name="members[]" type="checkbox" id="checkbox7" onClick="elistCheck()" value="nationalTeamVI" />
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
      <td colspan="2"><span class="style3">It may take up to few hours for your changes to process.</span></td>
    </tr>
    <tr valign="middle">
      <td align="right">&nbsp;</td>
      <td><label>
        <input type="submit" name="button" id="button" value="Request E-mail List" />
      </label></td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="middle">
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>