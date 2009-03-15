<?php //print_r($notify);//echo '<pre>' . print_r($shakha) . '</pre>';/* ?>
<h2><?=$vibhag->name?> Vibhag - Details</h2>
<p>&nbsp;</p>
<?php //echo validation_errors(); ?>
<?php echo form_open($this->uri->uri_string()); ?>
<h3>Vibhag Name</h3>
<input type="text" name="name" value="<?php echo $vibhag->name ?>" size="50" />
<p>&nbsp;</p>
<h3>Send Weekly Sankhya Reminder to Shakha Mukhya Shikshaks and Karyavahs?</h3>
<label>
      <input type="radio" name="notify" value="true" id="notify_0" <?php if($notify == 'true') echo 'checked="checked"'; ?>/>
      Yes</label>
    <label>
      <input type="radio" name="notify" value="false" id="notify_1" <?php if($notify == 'false') echo 'checked="checked"'; ?>/>
      No</label>
    <p>&nbsp;</p>
<input type="submit" value="Save" />
<?php echo form_close(); ?>