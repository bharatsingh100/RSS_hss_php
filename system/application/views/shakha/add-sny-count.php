<?php $set = isset($sankhya) ? true : false ; ?>
<h2><?php echo $shakha->name;?> - Report SNY Count</h2>
<form id="sny_count" method="post"
  action="/shakha/insert_sny_count/<?php echo $shakha->shakha_id; ?>">
  <?php echo form_hidden('shakha_id', $shakha->shakha_id); ?>

  <?php if($set && $contact) {
      echo '<strong>Last Updated By: </strong>';
      echo anchor('profile/view/'.$contact->contact_id, $contact->first_name . ' ' . $contact->last_name);
      echo '<br /><br />';
    }
  ?>
  <table border="0" cellspacing="2" cellpadding="2" width="300" class="float-left">
    <tr>
      <td colspan="3" align="center"><strong>Participants Count</strong></td>
    </tr>
    <tr>
      <td nowrap="nowrap">&nbsp;</td>
      <td align="center">&nbsp;Swayamsevak&nbsp;</td>
      <td align="center">&nbsp;Sevika&nbsp;</td>
    </tr>
    <tr>
      <td nowrap="nowrap">
        <div align="right">Bala (&lt;12 yrs)</div>
      </td>
      <td>
        <div align="center"><input name="bala_m" type="text" id="bala_m"
            size="4"
            value="<?php if($set) echo $sankhya->bala_m; else echo '0';?>"
            maxlength="3" /></div>
      </td>
      <td>
        <div align="center"><input name="bala_f" type="text" id="bala_f"
            size="4"
            value="<?php echo (($set) ? $sankhya->bala_f : '0');?>" maxlength="3" /></div>
      </td>
	</tr>
    <tr>
      <td nowrap="nowrap">
        <div align="right">Kishor (&lt;19 yrs)</div>
      </td>
      <td>
        <div align="center"><input name="kishor_m" type="text" id="kishor_m"
            size="4"
            value="<?php echo (($set) ? $sankhya->kishor_m : '0');?>"
            maxlength="3" /></div>
      </td>
      <td>
        <div align="center"><input name="kishor_f" type="text" id="kishor_f"
            size="4"
            value="<?php echo (($set) ? $sankhya->kishor_f : '0');?>"
            maxlength="3" /></div>
      </td>
      </tr>
    <tr>
      <td nowrap="nowrap">
        <div align="right">Yuva (&lt;26 yrs)</div>
      </td>
      <td>
        <div align="center"><input name="yuva_m" type="text" id="yuva_m"
            size="4"
            value="<?php echo (($set) ? $sankhya->yuva_m : '0');?>" maxlength="3" /></div>
      </td>
      <td>
        <div align="center"><input name="yuva_f" type="text" id="yuva_f"
            size="4"
            value="<?php echo (($set) ? $sankhya->yuva_f : '0');?>" maxlength="3" /></div>
      </td>
    </tr>
    <tr>
      <td nowrap="nowrap">
        <div align="right">Tarun(&lt; 50 yrs)</div>
      </td>
      <td>
        <div align="center"><input name="tarun_m" type="text" id="tarun_m"
            size="4"
            value="<?php echo (($set) ? $sankhya->tarun_m : '0');?>"
            maxlength="3" /></div>
      </td>
      <td>
        <div align="center"><input name="tarun_f" type="text" id="tarun_f"
            size="4"
            value="<?php echo (($set) ? $sankhya->tarun_f : '0');?>"
            maxlength="3" /></div>
      </td>
      </tr>
    <tr>
      <td nowrap="nowrap">
        <div align="right">Praudh</div>
      </td>
      <td>
        <div align="center"><input name="praudh_m" type="text" id="praudh_m"
            size="4"
            value="<?php echo (($set) ? $sankhya->praudh_m : '0');?>"
            maxlength="3" /></div>
      </td>
      <td>
        <div align="center"><input name="praudh_f" type="text" id="praudh_f"
            size="4"
            value="<?php echo (($set) ? $sankhya->praudh_f : '0');?>"
            maxlength="3" /></div>
      </td>
    </tr>
    <tr>
      <td nowrap="nowrap">
        <div align="right">Others</div>
      </td>
      <td>
        <div align="center"><input name="others_m" type="text" id="others_m"
            size="4"
            value="<?php echo (($set) ? $sankhya->others_m : '0');?>"
            maxlength="3" /></div>
      </td>
      <td>
        <div align="center"><input name="others_f" type="text" id="others_f"
            size="4"
            value="<?php echo (($set) ? $sankhya->others_f : '0');?>"
            maxlength="3" /></div>
      </td>
    </tr>
    <tr>
      <td nowrap="nowrap">
        <br /><div align="right">Total Families</div>
      </td>
      <td colspan="2">
      	<br />
        <div align="center"><input name="families" type="text" id="families"
            size="4"
            value="<?php echo (($set) ? $sankhya->families : '0');?>"
            maxlength="3" /></div>
      </td>
      </tr>
    <tr>
      <td>
        <div align="right">
          <h3>Sub-Total</h3>
        </div>
      </td>
      <td>
        <div align="center" id="subtt_m"></div>
      </td>
      <td>
        <div align="center" id="subtt_f"></div>
      </td>
    </tr>
    <tr>
      <td>
        <div align="right">
          <h3>Total</h3>
        </div>
      </td>
      <td colspan="2" align="center">
        <div id="total"><?php echo (($set) ? $sankhya->total : '');?></div>
      </td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td valign="top">
        <div align="right">Shakha Notes:</div>
      </td>
      <td colspan="2"><textarea name="shakha_info" id="shakha_info"
          cols="30" rows="7"><?php echo (($set) ? $sankhya->shakha_info : '');?></textarea></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2">
          <div align="center"><input type="submit" name="button" id="button"
              value="Submit Sankhya" /></div>
      </td>
    </tr>
  </table>

  <?php
    if(!$set) {$sankhya = NULL;}
    function sny_text_field($name, $sankhya){
      $value = isset($set, $sankhya->{$name}) ? (int)$sankhya->{$name} : 0;

      return $data = array(
              'name'        => $name,
              'id'          => $name,
              'value'       => $value,
              'maxlength'   => '8',
              'size'        => '10',
             );
    }
  ?>
  <table border="0" cellspacing="2" cellpadding="2" width="300" class="float-right">
  <tbody>
  	<tr>
  		<td colspan="3" align="center"><strong>Surya Namaskar Count</strong></td>
  	</tr>
  	<tr>
  		<td>&nbsp;</td>
  		<td>Swayamsevak</td>
  		<td>Sevika</td>
  	</tr>
  	<tr>
  		<td>Week 1</td>
  		<td><?php echo form_input(sny_text_field('week1_ss', $sankhya)); ?></td>
  		<td><?php echo form_input(sny_text_field('week1_s', $sankhya)); ?></td>
  	</tr>
  	<tr>
  		<td>Week 2</td>
  		<td><?php echo form_input(sny_text_field('week2_ss', $sankhya)); ?></td>
        <td><?php echo form_input(sny_text_field('week2_s', $sankhya)); ?></td>
  	</tr>
  	<tr>
  		<td>Week 3</td>
  		<td><?php echo form_input(sny_text_field('week3_ss', $sankhya)); ?></td>
        <td><?php echo form_input(sny_text_field('week3_s', $sankhya)); ?></td>
  	</tr>
  	<tr>
  		<td><h3>Sub-Total</h3></td>
  		<td id="counts_ss"><?php echo $set ? $sankhya->total_ss : ''; ?></td>
  		<td id="counts_s"><?php echo $set ? $sankhya->total_s : ''; ?></td>
  	</tr>
  	<tr>
  		<td><h3>Total</h3></td>
  		<td id="counts" colspan="2">
  		<?php echo $set ? $sankhya->total_ss + $sankhya->total_s : ''; ?></td>
  	</tr>

  </tbody>
  </table>
</form>
