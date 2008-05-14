<?=form_open('user/reset_pass');?>
<?=form_hidden('contact_id', $contact_id)?>
<p><?=$name?> please reset your password below:</p>
<p>&nbsp;</p>
<table width="433" border="0" align="center">
  <tr>
    <td width="213"><div align="right">New Password</div></td>
    <td width="210"><label>
    
      <input name="pass1" type="password" id="email" size="35" maxlength="50">
    </label></td>
  </tr>
  <tr>
    <td><div align="right">New Password again</div></td>
    <td><input name="pass2" type="password" id="email2" size="35" maxlength="50" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><label>
      <input type="submit" name="password" id="password" value="Submit">
    </label></td>
  </tr>
</table>

<?=form_close()?>