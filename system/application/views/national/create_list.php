<style type="text/css">
<!--
.style1 {font-weight: bold}
.style2 {font-weight: bold}
.style3 {color: #666666}
-->
</style>
<h2><?=$national->name?> - Request E-mail List</h2>
<div style="font-weight:bold;">
<ul> <?php $ers = false; //if(isset($d) || isset($d['msg'])) { $ers = true; foreach($d['msg'] as $i) { echo '<li>'. $i . '</li>';} } ?> </ul>
</div>
<form id="createlist" name="createlist" method="post" onfocus="elistCheck()" action="/national/add_email_list">
<?=form_hidden('level', 'NT');?>
  <table width="650" border="0" align="left" cellpadding="2" cellspacing="5">
    <tr valign="middle">
      <td width="139" align="right"><strong>List Name:<font color="#FF0000">*</font></strong></td>
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
        <?php if(isset($national->kk)) {
				foreach ($national->kk as $kk) {
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
        <?php if(isset($national->kk)) {
				foreach ($national->kk as $kk) {
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
        <?php if(isset($national->kk)) {
				foreach ($national->kk as $kk) {
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
      <td align="right"><strong>Moderator Password:<font color="#FF0000">*</font></strong></td>
      <td colspan="2"><label>
        <input name="mod_pass" type="text" id="mod_pass" onBlur="elistCheck()" size="35" maxlength="50" />
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
      <td align="right"><strong>List Members:<font color="#FF0000">*</font></strong></td>
      <td width="203"><label>
        <span class="style2">
        <input name="members[]" type="checkbox" id="checkbox0" onClick="elistCheck()" value="allss" />
        </span></label>
        <strong>      All Swayamsevaks</strong></td>
      <td width="276"><label>
        <span class="style1">
        <input name="members[]" type="checkbox" id="checkbox1" onClick="elistCheck()" value="ntkk" />
        </span></label>
        <strong> National Karyakartas</strong></td>
    </tr>
    <tr valign="middle">
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td><label><span class="style2">
        <input name="members[]" type="checkbox" id="checkbox2" onclick="elistCheck()" value="sakk" />
      </span></label>
        <strong> Sambhag Karyakartas</strong></td>
    </tr>
    <tr valign="middle">
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td><label><span class="style2">
        <input name="members[]" type="checkbox" id="checkbox3" onclick="elistCheck()" value="vikk" />
      </span></label>
        <strong> Vibhag Karyakartas</strong></td>
    </tr>
    <tr valign="middle">
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="middle">
      <td align="right">&nbsp;</td>
      <td colspan="2"><span class="style3">It may take up to 2-3 days to process your request. You will be notified via e-mail once your list has been created.</span></td>
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