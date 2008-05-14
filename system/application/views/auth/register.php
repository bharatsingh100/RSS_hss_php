

<h2>Registration</h2>
<?=form_open('auth/register')?>
	<p><label><span><?=$this->lang->line('sentry_user_name_label')?>: </span>
	<?=form_input(array('name'=>$this->config->item('auth_user_name_field'), 
	                       'id'=>$this->config->item('auth_user_name_field'),
	                       'maxlength'=>'45', 
	                       'size'=>'45',
	                       'value'=>(isset($this->validation) ? $this->validation->{$this->config->item('auth_user_name_field')} : '')))?>
    </label><span><?=(isset($this->validation) ? $this->validation->{$this->config->item('auth_user_name_field').'_error'} : '')?></span></p>
	<p><label><span><?=$this->lang->line('sentry_user_password_label')?>: </span>
	<?=form_password(array('name'=>$this->config->item('auth_user_password_field'), 
	                       'id'=>$this->config->item('auth_user_password_field'),
	                       'maxlength'=>'16', 
	                       'size'=>'16',
	                       'value'=>(isset($this->validation) ? $this->validation->{$this->config->item('auth_user_password_field')} : '')))?>
    </label><span><?=(isset($this->validation) ? $this->validation->{$this->config->item('auth_user_password_field').'_error'} : '')?></span></p>
    <p><label><span><?=$this->lang->line('sentry_user_password_confirm_label')?>: </span>
	<?=form_password(array('name'=>$this->config->item('auth_user_password_confirm_field'), 
	                       'id'=>$this->config->item('auth_user_password_confirm_field'),
	                       'maxlength'=>'16', 
	                       'size'=>'16',
	                       'value'=>(isset($this->validation) ? $this->validation->{$this->config->item('auth_user_password_confirm_field')} : '')))?>
    </label><span><?=(isset($this->validation) ? $this->validation->{$this->config->item('auth_user_password_confirm_field').'_error'} : '')?></span></p>
    <p><label><span><?=$this->lang->line('sentry_user_email_label')?>: </span>
	<?=form_input(array('name'=>$this->config->item('auth_user_email_field'), 
	                       'id'=>$this->config->item('auth_user_email_field'),
	                       'maxlength'=>'120', 
	                       'size'=>'60',
	                       'value'=>(isset($this->validation) ? $this->validation->{$this->config->item('auth_user_email_field')} : '')))?>
    </label><span><?=(isset($this->validation) ? $this->validation->{$this->config->item('auth_user_email_field').'_error'} : '')?></span></p>
<?php
if ($this->config->item('auth_use_country'))
{?>    
    <p><label><span><?=$this->lang->line('sentry_user_country_label')?>: </span>
	<?=form_dropdown($this->config->item('auth_user_country_field'),
	                 $countries,
	                 (isset($this->validation) ? $this->validation->{$this->config->item('auth_user_email_field')} : 0))?>
    </label><span><?=(isset($this->validation) ? $this->validation->{$this->config->item('auth_user_email_field').'_error'} : '')?></span></p>
<?php
}
$buttonSubmit = $this->lang->line('sentry_register_label');
$buttonCancel = $this->lang->line('sentry_cancel_label');
$callConfirm = '';
if ($this->lang->line('sentry_terms_of_service_message') != '')
{
    $buttonSubmit = $this->lang->line('sentry_agree_label');
    $buttonCancel = $this->lang->line('sentry_donotagree_label');
    $callConfirm = 'confirmDecline();';
?>
<textarea name='rules' class='textarea' rows='8' cols='50' readonly>
<?=$this->lang->line('sentry_terms_of_service_message')?>
</textarea>
<?php    
}?>
    <p><label>
	<?=form_submit(array('name'=>'register', 
	                     'id'=>'register', 
	                     'value'=>$buttonSubmit))?>
    </label></p>
	<p><label>
	<?=form_submit(array('type'=>'button',
	                     'name'=>'cancel', 
	                     'id'=>'cancel', 
	                     'value'=>$buttonCancel,
	                     'onclick'=>$callConfirm))?>
    </label></p>
<?=form_close()?>
<script language="JavaScript" type="text/javascript">
<!--
function confirmDecline() 
{
    if (confirm('<?=$this->lang->line('sentry_register_cancel_confirm')?>')) 
		location = '<?=site_url('auth/login')?>';
} 
//-->
</script>