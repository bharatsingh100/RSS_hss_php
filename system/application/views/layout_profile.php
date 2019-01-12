<?php require_once 'header.php';?>

   <!-- Begin Wrapper -->
   <div id="wrapper">
   		<div id="header">
        <div id="info">
        <?php if($this->session->userdata('message')): ?>
        <span style="float:left; color:#FFCC33; font-weight: bolder; padding: 2px 0px 0px 10px;"><?=$this->session->ro_userdata('message');?></span>
        <?php endif;?>
		<?=mdate("%l, %F %j%S", time())?><?php echo ' | <strong>' . $this->session->userdata('email') . '</strong> | ';?>
		<?=anchor('user/logout', 'Logout')?>
        </div>
         <!-- Begin Header -->
         <br />
           <div style="text-align:center;">
             <h1>Hindu Swayamsevak Sangh</h1>
           </div>
         </div>
		 <!-- End Header -->
		 <!-- Begin Navigation -->
		 <div id="navigation">
			<span class="left">
             <?php if($this->session->userdata('contact_id') == $this->uri->segment(3)) {$t = TRUE; echo '<span class="active">';}?>
			       <?php echo anchor('profile/view/' . $this->session->userdata('contact_id'), 'My Profile'); ?><?php if(isset($t)) echo '</span>';?>
             <?php echo anchor('shakha/view/' . $this->session->userdata('shakha_id'), 'My Shakha'); ?>
             <?php if($this->session->userdata('nagar_id')) {
			 		echo anchor('nagar/view/' . $this->session->userdata('nagar_id'), 'My Nagar');
			 }?>
             <?php echo anchor('vibhag/view/' . $this->session->userdata('vibhag_id'), 'My Vibhag'); ?>
             <?php echo anchor('sambhag/view/' . $this->session->userdata('sambhag_id'), 'My Sambhag'); ?>
             <?php echo anchor('national/view', 'National'); ?>
<!--             <?php //echo anchor('email/view/', 'E-mail'); ?>  |
             <?php //echo anchor('events', 'Events'); ?>  |
             <?php //echo anchor('organization', 'Organization'); ?>-->
          	</span>
            <span class="right">
                <?php echo anchor('admin/faq','FAQ'); ?>
                <?php echo anchor('tour', 'Tutorial Video'); ?>
            </span>
          </div>
<!-- End Navigation -->
		 <!-- Breadcrumb -->
         <div id="breadcrumb">
         <?php echo anchor('shakha/view/'.$this->session->ro_userdata('bc_shakha_id'), $this->session->userdata('bc_shakha')); ?>
         <?php if($this->session->userdata('bc_nagar_id'))
         			echo ' / ' . anchor('nagar/view/'.$this->session->ro_userdata('bc_nagar_id'), $this->session->ro_userdata('bc_nagar')); ?>
         <?php echo ' / ',anchor('vibhag/view/'.$this->session->ro_userdata('bc_vibhag_id'), $this->session->userdata('bc_vibhag')); ?>
	     <?php echo ' / ',anchor('sambhag/view/'.$this->session->ro_userdata('bc_sambhag_id'), $this->session->userdata('bc_sambhag')); ?>
         </div>
         <!-- End BreadCrumb -->
		 <!-- Begin Left Column -->
		 <div id="leftcolumn"> <?=$content_for_layout?> </div>
		 <!-- End Left Column -->

		 <!-- Begin Right Column -->
		 <div id="rightcolumn">
         <?php if($this->permission->is_shakha_kkl($this->session->userdata('shakha_id'))):?>
         <form id="form1" name="form1" method="post" action="/search/searchRedirect">              
              <input type="text" name="term"
				<?php if($this->uri->segment(1) == 'search'){
                echo 'value="'.$this->uri->segment(3).'"';}
            else
                echo 'placeholder="Search for..."';
            ?>
              size="18" id="term" autocomplete="off" />&nbsp;<input type="submit" name="submit" id="submit" value="Go" />
              <br />              
              <?php $this->session->set_userdata('redirect_url', $this->uri->uri_string());?>
            </form>
         <span id="autosuggest-error"></span><br/>
		     <hr />
         <span id="email-error" class="span_error_font">
            <?php if(!empty($this->session->userdata('emailError'))){
                     echo $this->session->userdata('emailError'); $this->session->unset_userdata('emailError');
                   } ?></span>
            <p id="p_addcontact">Add new contact to <span id="span_shakhaname"><?php echo $this->session->userdata('bc_shakha') ?></span></p>
            <form name="add_karyakarta" autocomplete="off" method="post" id="add_karyakarta" action="<?php echo '/shakha/add_quick_form/'.$this->session->userdata('shakha_id') ?>"/>
              <input type="text" id="k_name" name="name" placeholder="Enter Name" required />
              <input type="text" id="k_email" name="email" placeholder="Enter Email" />
              <input type="text" id="k_mobile" name="ph_mobile" placeholder="Enter Mobile Number" />  
              <input type="hidden" name="shakha_id" value="<?php echo $this->session->userdata('shakha_id') ?>" />  
              <input type="hidden" name="contact_type" value="<?php echo defaultContactType; ?>" />          
              <input type="hidden" name="gana" value="<?php echo defaultGana;  ?>" />          
              <input type="hidden" name="state" value="<?php echo $this->session->userdata('state') ?>" />          
              <input type="button" id="btn_addcontact" value="Add Contact" />
            </form>
         <br />
         <hr />
         <?php endif; ?>
         <br />
         <?php if($this->permission->allow_profile_edit($this->uri->segment(3))): ?>
         <h3>Navigation</h3>
         <p><?=anchor('profile/edit_profile/' . $this->uri->segment(3), 'Edit Profile')?></p>
         <?php $b = $this->db->select('shakha_id')->get_where('swayamsevaks', array('contact_id'=>$this->uri->segment(3)))->row(); ?>
         <p><?=anchor('shakha/add_family_member/' . $b->shakha_id . '/' . $this->uri->segment(3), 'Add Family Member')?></p>
         <p><?=anchor('profile/add_to_family/' . $this->uri->segment(3), 'Connect to Family')?></p>
         <p><?=anchor('profile/change_password/' . $this->uri->segment(3), 'Change Password')?></p>
		 <?php endif; ?>
         </div>
     <script type="text/javascript" src="<?=site_url();?>javascript/quickadd.js"></script>    
     <script  type="text/javascript" src="<?=site_url();?>javascript/customautocomplete.js">  </script>
		 <?php require_once 'footer.php';?>