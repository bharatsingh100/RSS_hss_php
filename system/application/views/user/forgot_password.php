<?=form_open('user/forgot_password');?>
<p>Please enter your e-mail below. If your e-mail is in our records, we will send you a link via e-mail, which will allow you to reset your password.</p>
<p>&nbsp;</p>
<table width="433" border="0" align="center">
  <tr>
    <td width="213"><div align="right">Your E-mail:</div></td>
    <td width="210"><label>
    
      <input name="email" type="text" id="email" size="35" maxlength="50">
    </label></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><label>
      <input type="submit" name="login" id="login" value="Submit">
    </label></td>
  </tr>
</table>

<?=form_close()?>