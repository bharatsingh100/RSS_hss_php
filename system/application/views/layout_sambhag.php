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
			 <?php echo anchor('profile/view/' . $this->session->userdata('contact_id'), 'My Profile'); ?>
             <?php echo anchor('shakha/view/' . $this->session->userdata('shakha_id'), 'My Shakha'); ?>
             <?php if($this->session->userdata('nagar_id')) {
			 		echo anchor('nagar/view/' . $this->session->userdata('nagar_id'), 'My Nagar');
			 }?>
             <?php echo anchor('vibhag/view/' . $this->session->userdata('vibhag_id'), 'My Vibhag'); ?>
             <?php if($this->session->userdata('sambhag_id') == $this->uri->segment(3)) {$t = TRUE; echo '<span class="active">';}?>
			 <?php echo anchor('sambhag/view/' . $this->session->userdata('sambhag_id'), 'My Sambhag'); ?><?php if(isset($t)) echo '</span>';?>
             <?php echo anchor('national/view', 'National'); ?>
<!--             <?php //echo anchor('email/view/', 'E-mail'); ?>  |
             <?php //echo anchor('events', 'Events'); ?>  |
             <?php //echo anchor('organization', 'Organization'); ?>-->
             </span>
            <span class="right">
                <?php echo anchor('admin/hssdocs','Share Documents'); ?>
                <?php echo anchor('tour', 'Tutorial Video'); ?>
            </span>
         </div>
<!-- End Navigation -->
		 <!-- Begin Left Column -->
		 <div id="leftcolumn"> <?=$content_for_layout?> </div>
		 <!-- End Left Column -->

		 <!-- Begin Right Column -->
		 <div id="rightcolumn">
          <?php if($this->permission->is_shakha_kkl($this->session->userdata('shakha_id'))):?>
          <form id="form1" name="form1" method="post" action="/search/index">
              <input type="text" name="term"
				<?php if($this->uri->segment(1) == 'search'){
                echo 'value="'.$this->uri->segment(4).'"';}
            else
                echo 'placeholder="Search for..."';
            ?>
              size="18" autocomplete="off" id="term" />&nbsp;<input type="submit" name="submit" id="submit" value="Go" />
              <br />
              <?php $this->session->set_userdata('redirect_url', $this->uri->uri_string());?>
          </form>
          <span id="autosuggest-error"></span><br/>
		     <hr />
         <?php endif; ?>
         <br />
         <?php if($this->permission->is_sambhag_kk($this->uri->segment(3))): ?>
         <h3>Navigation </h3>
         <p><?php //anchor('vibhag/browse/'.$this->uri->segment(3). '/name/', 'List Swayamsevaks');?></p>
         <p><?php echo anchor('sambhag/responsibilities/'.$this->uri->segment(3), 'Manage Responsibilities');?></p>
	   	 <p><?php echo anchor('sambhag/statistics/'.$this->uri->segment(3), 'Reports & Statistics');?></p>
	  <p>&nbsp;</p>
	  <p><?=anchor('sambhag/email_lists/'.$this->uri->segment(3), 'View Email Lists');?></p>
	  <p><?=anchor('sambhag/create_list/'.$this->uri->segment(3), 'Request E-mail List');?></p>

      <?php endif; ?>
         </div>
     <script  type="text/javascript" src="<?=site_url();?>javascript/customautocomplete.js">  </script>
	   <?php require_once 'footer.php';?>
