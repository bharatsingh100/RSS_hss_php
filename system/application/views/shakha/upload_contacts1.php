<?php $this->session->set_userdata('import_st', 1); ?>
<style type="text/css">
<!--
.style1 {color: #FF0000}
-->
</style>
<h2><?=$shakha->name?> : Uploading Contacts</h2>
<form action="/shakha/upload_result/<?=$this->uri->segment(3)?>" method="post" enctype="multipart/form-data" name="form1">
  <h3>Match contacts columns:</h3>
  <p>&nbsp;</p>
  <table width="400" border="0">
    <tr>
      <td align="right"><strong>Name<span class="style1">*</span></strong></td>
      <td><select name="name" id="name">
        <option value="" selected="selected">&nbsp;</option>
        <?php foreach($list as $l => $v) {
		  		echo "<option value=\"$l\">$v&nbsp;</option>\n"; } ?>
      </select></td>
    </tr>
    <tr>
      <td width="110" align="right"><h4>First Name<span class="style1">*</span></h4></td>
      <td width="280"><label>
        <select name="first_name" id="first_name">
          <option value="" selected="selected">&nbsp;</option>
          <?php foreach($list as $l => $v) {
		  		echo "<option value=\"$l\">$v&nbsp;</option>\n"; } ?>
        </select>
      </label></td>
    </tr>
    <tr>
      <td align="right"><h4>Last Name<span class="style1">*</span></h4></td>
      <td><select name="last_name" id="last_name">
        <option value="" selected="selected">&nbsp;</option>
          <?php foreach($list as $l => $v) {
		  		echo "<option value=\"$l\">$v&nbsp;</option>\n"; } ?>
        </select>
      </select></td>
    </tr>
    <tr>
      <td align="right"><strong>Contact Type</strong></td>
      <td><select name="contact_type" id="contact_type">
        <option value="" selected="selected">&nbsp;</option>
        <?php foreach($list as $l => $v) {
		  		echo "<option value=\"$l\">$v&nbsp;</option>\n"; } ?>
      </select></td>
    </tr>
    <tr>
      <td align="right"><h4>Same Family ID</h4></td>
      <td><select name="family_id" id="family_id">
        <option value="" selected="selected">&nbsp;</option>
          <?php foreach($list as $l => $v) {
		  		echo "<option value=\"$l\">$v&nbsp;</option>\n"; } ?>
        </select>
      </select></td>
    </tr>
    <tr>
      <td align="right"><h4>Gender</h4></td>
      <td><select name="gender" id="gender">
        <option value="" selected="selected">&nbsp;</option>
          <?php foreach($list as $l => $v) {
		  		echo "<option value=\"$l\">$v&nbsp;</option>\n"; } ?>
        </select>
      </select></td>
    </tr>
    <tr>
      <td align="right"><h4>Birth Year</h4></td>
      <td><select name="birth_year" id="birth_year">
        <option value="" selected="selected">&nbsp;</option>
          <?php foreach($list as $l => $v) {
		  		echo "<option value=\"$l\">$v&nbsp;</option>\n"; } ?>
        </select>
      </select></td>
    </tr>
    <tr>
      <td align="right"><h4>Company</h4></td>
      <td><select name="company" id="company">
       <option value="" selected="selected">&nbsp;</option>
          <?php foreach($list as $l => $v) {
		  		echo "<option value=\"$l\">$v&nbsp;</option>\n"; } ?>
        </select>
      </select></td>
    </tr>
    <tr>
      <td align="right"><h4>Job Title</h4></td>
      <td><select name="position" id="position">
        <option value="" selected="selected">&nbsp;</option>
          <?php foreach($list as $l => $v) {
		  		echo "<option value=\"$l\">$v&nbsp;</option>\n"; } ?>
        </select>
      </select></td>
    </tr>
    <tr>
      <td align="right"><h4>E-mail</h4></td>
      <td><select name="email" id="email">
        <option value="" selected="selected">&nbsp;</option>
          <?php foreach($list as $l => $v) {
		  		echo "<option value=\"$l\">$v&nbsp;</option>\n"; } ?>
        </select>
      </select></td>
    </tr>
    <tr>
      <td align="right"><h4>Phone (Mobile)</h4></td>
      <td><select name="ph_mobile" id="ph_mobile">
        <option value="" selected="selected">&nbsp;</option>
          <?php foreach($list as $l => $v) {
		  		echo "<option value=\"$l\">$v&nbsp;</option>\n"; } ?>
        </select>
      </select></td>
    </tr>
    <tr>
      <td align="right"><h4>Phone (Home)</h4></td>
      <td><select name="ph_home" id="ph_home">
        <option value="" selected="selected">&nbsp;</option>
          <?php foreach($list as $l => $v) {
		  		echo "<option value=\"$l\">$v&nbsp;</option>\n"; } ?>
        </select>
      </select></td>
    </tr>
    <tr>
      <td align="right"><h4>Phone (Work)</h4></td>
      <td><select name="ph_work" id="ph_work">
        <option value="" selected="selected">&nbsp;</option>
          <?php foreach($list as $l => $v) {
		  		echo "<option value=\"$l\">$v&nbsp;</option>\n"; } ?>
        </select>
      </select></td>
    </tr>
    <tr>
      <td align="right"><h4>Address 1</h4></td>
      <td><select name="street_add1" id="street_add1">
        <option value="" selected="selected">&nbsp;</option>
          <?php foreach($list as $l => $v) {
		  		echo "<option value=\"$l\">$v&nbsp;</option>\n"; } ?>
        </select>
      </select></td>
    </tr>
    <tr>
      <td align="right"><h4>Address 2</h4></td>
      <td><select name="street_add2" id="street_add2">
        <option value="" selected="selected">&nbsp;</option>
          <?php foreach($list as $l => $v) {
		  		echo "<option value=\"$l\">$v&nbsp;</option>\n"; } ?>
        </select>
      </select></td>
    </tr>
    <tr>
      <td align="right"><h4>City</h4></td>
      <td><select name="city" id="city">
        <option value="" selected="selected">&nbsp;</option>
          <?php foreach($list as $l => $v) {
		  		echo "<option value=\"$l\">$v&nbsp;</option>\n"; } ?>
        </select>
      </select></td>
    </tr>
    <tr>
      <td align="right"><h4>State</h4></td>
      <td><select name="state" id="state">
        <option value="" selected="selected">&nbsp;</option>
          <?php foreach($list as $l => $v) {
		  		echo "<option value=\"$l\">$v&nbsp;</option>\n"; } ?>
        </select>
      </select></td>
    </tr>
    <tr>
      <td align="right"><h4>Zip</h4></td>
      <td><select name="zip" id="zip">
        <option value="" selected="selected">&nbsp;</option>
          <?php foreach($list as $l => $v) {
		  		echo "<option value=\"$l\">$v&nbsp;</option>\n"; } ?>
        </select>
      </select></td>
    </tr>
    <tr>
      <td align="right"><h4>Notes</h4></td>
      <td><select name="notes" id="notes">
        <option value="" selected="selected">&nbsp;</option>
          <?php foreach($list as $l => $v) {
		  		echo "<option value=\"$l\">$v&nbsp;</option>\n"; } ?>
        </select>
      </select></td>
    </tr>
    <tr align="right">
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr align="right">
      <td colspan="2"><h4>
        <label>
          <input type="submit" name="button" id="button" value="Submit" />
          </label>
      </h4></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</form>
