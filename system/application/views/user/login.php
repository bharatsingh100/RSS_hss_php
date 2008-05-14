<?=form_open('user/login')?>
<table width="200" border="0" align="center">
  <tr>
    <td colspan="2"><h3>Login to System</h3></td>
  </tr>
  <tr>
    <td width="78"><div align="right">Username:</div></td>
    <td width="112"><label>
      <input name="username" type="text" id="username" size="35" maxlength="50">
    </label></td>
  </tr>
  <tr>
    <td><div align="right">Password:</div></td>
    <td><input name="password" type="password" id="password" size="35" maxlength="50"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><label>
      <input type="submit" name="login" id="login" value="Login">
    </label></td>
  </tr>
</table>

<?=form_close()?>