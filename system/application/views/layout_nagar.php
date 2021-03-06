<?php require_once 'header.php';?>

<!-- Begin Wrapper -->
   <div id="wrapper">
   		<div id="header">
        <div id="info">
        <?php if($this->session->userdata('message')): ?>
        <span style="float:left; color:#FFCC33; font-weight: bolder; padding: 2px 0px 0px 10px;"><?php echo $this->session->ro_userdata('message');?></span>
        <?php endif;?>
		<?php echo mdate("%l, %F %j%S", time()); ?><?php echo ' | <strong>' . $this->session->userdata('email') . '</strong> | ';?>
		<?php echo anchor('user/logout', 'Logout'); ?>
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
             <?php $nagar_link = anchor('shakha/view/' . $this->session->userdata('nagar_id'), 'My Nagar'); ?>
             <?php if($this->uri->segment(3) == $this->session->userdata('nagar_id'))
					echo '<span class="active">' . $nagar_link . '</span>';
				   else
				   	echo $nagar_link;
		   	 ?>
             <?php echo anchor('vibhag/view/' . $this->session->userdata('vibhag_id'), 'My Vibhag'); ?>
             <?php echo anchor('sambhag/view/' . $this->session->userdata('sambhag_id'), 'My Sambhag'); ?>
             <?php echo anchor('national/view', 'National'); ?>
             </span>
            <span class="right">
                <?php echo anchor('admin/maps','Maps'); ?>
                <?php echo anchor('admin/faq','FAQ'); ?>
                <?php echo anchor('tour', 'Tutorial Video'); ?>
            </span>
         </div>
<!-- End Navigation -->
		 <!-- Breadcrumb -->
         <div id="breadcrumb">
         <?php //echo anchor('shakha/view/'.$this->session->userdata('bc_shakha_id'), $this->session->userdata('bc_shakha')); ?>
         <?php echo anchor('nagar/view/'.$this->session->ro_userdata('bc_nagar_id'), $this->session->userdata('bc_nagar')); ?>
         <?php echo ' / ',anchor('vibhag/view/'.$this->session->ro_userdata('bc_vibhag_id'), $this->session->userdata('bc_vibhag')); ?>
	     <?php echo ' / ',anchor('sambhag/view/'.$this->session->ro_userdata('bc_sambhag_id'), $this->session->userdata('bc_sambhag')); ?>         			         </div>
         <!-- End BreadCrumb -->
		 <!-- Begin Left Column -->
		 <div id="leftcolumn"> <?php echo $content_for_layout; ?> </div>
		 <!-- End Left Column -->

		 <!-- Begin Right Column -->
		 <div id="rightcolumn">
          <?php if($this->permission->is_shakha_kkl($this->session->userdata('shakha_id'))):?>
         <form id="form1" name="form1" method="post" action="/search/index">
              <input type="text" name="term"
				<?php if($this->uri->segment(1) == 'search'){
                echo 'value="'.$this->uri->segment(4).'"';}
            else
                echo 'value="Search for..."';
            ?>
              size="18" onclick="this.value = ''" id="term" />&nbsp;<input type="submit" name="submit" id="submit" value="Go" />
              <br />
              Within:&nbsp;<select name="limit" id="limit">
              	<option <?php if($this->session->userdata('within') == 'SH') echo ' selected="selected" '; ?> value="<?php echo 'SH_'.$this->session->userdata('shakha_id')?>">My Shakha&nbsp;</option>
                <option <?php if($this->session->userdata('within') == 'VI') echo ' selected="selected" '; ?> value="<?php echo 'VI_'.$this->session->userdata('vibhag_id'); ?>">My Vibhag&nbsp;</option>
                <?php //if($this->permission->is_sambhag_kk($this->session->userdata('sambhag_id'))):?>
                <option <?php if($this->session->userdata('within') == 'SA') echo ' selected="selected" '; ?> value="<?php echo 'SA_'.$this->session->userdata('sambhag_id'); ?>">My Sambhag&nbsp;</option><?php // endif; ?>
                 <?php //if($this->permission->is_nt_kk()):?>
                <option <?php if($this->session->userdata('within') == 'NT') echo ' selected="selected" '; ?> value="<?php echo 'NT_'; ?>">Everything&nbsp;</option><?php //endif; ?>
              </select>
              <?php $this->session->set_userdata('redirect_url', $this->uri->uri_string());?>

            </form>
         <br />
		 <hr />
         <?php endif; ?>
         <br />
         <?php if($this->permission->is_nagar_kk($this->uri->segment(3))): ?>
         <h3>Navigation </h3>
         <p><?php echo anchor('nagar/browse/'.$this->uri->segment(3). '/name/', 'List All Contacts');?></p>
         <p><?php echo anchor('nagar/add_shakha/'.$this->uri->segment(3), 'Add New Shakha');?></p>
         <p><?php echo anchor('nagar/responsibilities/'.$this->uri->segment(3), 'Manage Responsibilities');?></p>
	   	 <p>Nagar Statistics</p>
	  <!--  <p>&nbsp;</p>
	  <p><?php echo anchor('nagar/email_lists/'.$this->uri->segment(3), 'View Email Lists');?></p>
	  <p><?php echo anchor('nagar/create_list/'.$this->uri->segment(3), 'Request E-mail List');?></p>
		-->
      <?php endif; ?>
         </div>

		 <!-- End Right Column -->

		 <?php require_once 'footer.php';?>