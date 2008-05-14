<h2>Contact System Admin</h2>
<form id="form1" name="form1" method="post" action="">
  <table width="100" border="0" cellspacing="5">
    <tr>
      <td align="right" valign="top">Name:</td>
      <td><input name="name" type="text" id="name" value="<?=$this->session->userdata('first_name').' '.$this->session->userdata('last_name')?>" size="35" maxlength="50" /></td>
    </tr>
    <tr>
      <td align="right" valign="top">E-mail:</td>
      <td><input name="email" type="text" id="email" value="<?=$this->session->userdata('email')?>" size="35" maxlength="50" /></td>
    </tr>
    <tr>
      <td align="right" valign="top">Comment:</td>
      <td><textarea name="message" id="message" cols="60" rows="7"></textarea></td>
    </tr>
    <tr>
      <td align="right" valign="top">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right" valign="top">&nbsp;</td>
      <td><input type="submit" name="button" id="button" value="Send your Comment" /></td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
