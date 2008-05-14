<h2><?=$name?> - Change Password</h2>
<?=form_open('profile/change_password/'.$this->uri->segment(3));?>
<p>&nbsp;</p>
<table width="433" border="0" align="center">
  <tr>
    <td align="right">Old Password</td>
    <td><input name="old_pass" type="password" id="email3" size="35" maxlength="50" /></td>
  </tr>
  <tr>
    <td width="213" align="right"><div align="right">New Password</div></td>
    <td width="210">
    
      <input name="pass1" type="password" id="email" size="35" maxlength="50">
    </td>
  </tr>
  <tr>
    <td align="right"><div align="right">Confirm New Password </div></td>
    <td><input name="pass2" type="password" id="email2" size="35" maxlength="50" /></td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td>
      <input type="submit" name="password" id="password" value="Submit">
   </td>
  </tr>
</table>

<?=form_close()?>