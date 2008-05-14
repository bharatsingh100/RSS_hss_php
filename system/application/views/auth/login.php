<?=form_open('auth/login')?>
	<p><label><span><?=$this->lang->line('sentry_user_name_label')?>: </span>
	<?=form_input(array('name'=>$this->config->item('auth_user_name_field'), 
	                       'id'=>$this->config->item('auth_user_name_field'),
	                       'maxlength'=>'30', 
	                       'size'=>'30',
	                       'value'=>(isset($this->validation) ? $this->validation->{$this->config->item('auth_user_name_field')} : '')))?>
    </label><span><?=(isset($this->validation) ? $this->validation->{$this->config->item('auth_user_name_field').'_error'} : '')?></span></p>
	<p><label><span><?=$this->lang->line('sentry_user_password_label')?>: </span>
	<?=form_password(array('name'=>$this->config->item('auth_user_password_field'), 
	                       'id'=>$this->config->item('auth_user_password_field'),
	                       'maxlength'=>'30', 
	                       'size'=>'30',
	                       'value'=>(isset($this->validation) ? $this->validation->{$this->config->item('auth_user_password_field')} : '')))?>
    </label><span><?=(isset($this->validation) ? $this->validation->{$this->config->item('auth_user_password_field').'_error'} : '')?></span></p>
    <p><label>
    <?=form_checkbox(array('name'=>$this->config->item('auth_user_autologin_field'), 
	                       'id'=>$this->config->item('auth_user_autologin_field'),
	                       'checked'=>false))?>
    <?=$this->lang->line('sentry_user_autologin_label')?>? (uses cookies)
    </label><em></p>
	<p><label>
	<?=form_submit(array('name'=>'login', 
	                     'id'=>'login', 
	                     'value'=>$this->lang->line('sentry_login_label')))?>
    </label></p>
	<p><?=anchor('auth/forgotten_password_index', $this->lang->line('sentry_forgotten_password_label'))?></p>
	<p><?=anchor('auth/register_index', $this->lang->line('sentry_register_label'))?></p>
<?=form_close()?>