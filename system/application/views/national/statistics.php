<?php //echo '<pre>' . print_r($shakha) . '</pre>'; ?>
<h2><?=$shakha->name;?></h2>
<h3>Contact List:</h3>
<table width="50%" border="1" cellspacing="2" cellpadding="2">
  <tr>
    <td width="45%">Total Families:</td>
    <td width="18%" align="center"><?=$families?></td>
    <td width="21%" align="center" valign="middle">Swayamsevak</td>
    <td width="16%" align="center" valign="middle">Sevika</td>
  </tr>
  <tr>
    <td>Total Swayamsevaks</td>
    <td align="center"><?=$swayamsevaks?></td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td>Shishu ( &lt; 6)</td>
    <td align="center"><?=$shishu?></td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td>Bala (6 - 12)</td>
    <td align="center"><?=$bala?></td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td>Kishor (13 - 18)</td>
    <td align="center"><?=$kishor?></td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td>Yuva(18 - 25)</td>
    <td align="center"><?=$yuva?></td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td>Tarun (26 - 50)</td>
    <td align="center"><?=$tarun?></td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td>Praudh</td>
    <td align="center">12</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<h3>Sankhya:</h3>
<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td>Date</td>
    <td>Info</td>
    <td>Total</td>
    <td>Shishu</td>
    <td>Bala</td>
    <td>Kishor</td>
    <td>Yuva</td>
    <td>Tarun</td>
    <td>Mahila</td>
    <td>Praudh</td>
  </tr>
  <tr>
    <td>10/14/07</td>
    <td>&nbsp;</td>
    <td>15</td>
    <td>5</td>
    <td>4</td>
    <td>2</td>
    <td>3</td>
    <td>0</td>
    <td>4</td>
    <td>0</td>
  </tr>
  <tr>
    <td>10/07/07</td>
    <td>No Shakha</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>09/30/07</td>
    <td>Celebrated Utsav</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<h3>Other:</h3>
<p>Swayamsevaks with at least 1 phone # : <?=$phone;?></p>
<p>Swayamsevaks with active e-mail: <?=$email?></p>
<p>Swayamsevaks with inactive (unsubscribed, bounced) e-mails: <?=$email_unactive?></p>
<p>&nbsp;</p>
