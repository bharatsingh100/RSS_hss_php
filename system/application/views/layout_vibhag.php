<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?=$pageTitle?> - Swayamsevak Information Management - HSS</title>
<link rel="stylesheet" type="text/css" href="/css/main.css" />
<link rel="shortcut icon" href="/favicon.ico" type="image/vnd.microsoft.icon" />
<script type="text/javascript" src="/css/all.js"></script>
</head>

<body>

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
			  <?php
			  		$vibhag_link = anchor('vibhag/view/' . $this->session->userdata('vibhag_id'), 'My Vibhag');
			  		if($this->uri->segment(3) == $this->session->userdata('vibhag_id'))
						echo '<span class="active">'. $vibhag_link . '</span>';
				   	else
				   		echo $vibhag_link;
		   	 ?>
             <?php echo anchor('sambhag/view/' . $this->session->userdata('sambhag_id'), 'My Sambhag'); ?>
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
		 <!-- Breadcrumb -->
         <div id="breadcrumb">
         <?php //echo anchor('shakha/view/'.$this->session->userdata('bc_shakha_id'), $this->session->userdata('bc_shakha')); ?>
         <?php echo anchor('vibhag/view/'.$this->session->ro_userdata('bc_vibhag_id'), $this->session->userdata('bc_vibhag')); ?>
	     <?php echo ' / '.anchor('sambhag/view/'.$this->session->ro_userdata('bc_sambhag_id'), $this->session->userdata('bc_sambhag')); ?>         			         </div>
         <!-- End BreadCrumb -->
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
                echo 'value="Search for..."';
            ?>
              size="18" onclick="this.value = ''" id="term" />&nbsp;<input type="submit" name="submit" id="submit" value="Go" />
              <br />
              Within:&nbsp;<select name="limit" id="limit">
              	<option <?php if($this->session->userdata('within') == 'SH') echo ' selected="selected" '; ?> value="<?='SH_'.$this->session->userdata('shakha_id')?>">My Shakha&nbsp;</option>
                <option <?php if($this->session->userdata('within') == 'VI') echo ' selected="selected" '; ?> value="<?='VI_'.$this->session->userdata('vibhag_id')?>">My Vibhag&nbsp;</option>
                <?php //if($this->permission->is_sambhag_kk($this->session->userdata('sambhag_id'))):?>
                <option <?php if($this->session->userdata('within') == 'SA') echo ' selected="selected" '; ?> value="<?='SA_'.$this->session->userdata('sambhag_id')?>">My Sambhag&nbsp;</option><?php // endif; ?>
                 <?php //if($this->permission->is_nt_kk()):?>
                <option <?php if($this->session->userdata('within') == 'NT') echo ' selected="selected" '; ?> value="<?='NT_'?>">Everything&nbsp;</option><?php //endif; ?>
              </select>
              <?php $this->session->set_userdata('redirect_url', $this->uri->uri_string());?>

            </form>
         <br />
		 <hr />
         <?php endif; ?>
         <br />
         <?php if($this->permission->is_vibhag_kk($this->uri->segment(3))): ?>
             <h3>Navigation </h3>
             <p><?=anchor('vibhag/browse/'.$this->uri->segment(3). '/name/', 'List All Contacts');?></p>
             <p><?=anchor('vibhag/add_shakha/'.$this->uri->segment(3), 'Add New Shakha');?></p>
             <p><?=anchor('vibhag/responsibilities/'.$this->uri->segment(3), 'Manage Responsibilities');?></p>
    	   	 <p><?=anchor('vibhag/statistics/'.$this->uri->segment(3), 'Statistics');?></p>
    	     <p>&nbsp;</p>
    	     <p><?=anchor('vibhag/email_lists/'.$this->uri->segment(3), 'View Email Lists');?></p>
    	     <p><?=anchor('vibhag/create_list/'.$this->uri->segment(3), 'Request E-mail List');?></p>
         <?php endif; ?>
         </div>

		 <!-- End Right Column -->

		 <!-- Begin Footer -->
		 <div id="footer">
		   <div align="center">
		     <p><a href="/admin/info">General Information</a>&nbsp;&nbsp;|&nbsp;&nbsp;  <a href="/admin/recent_updates">Recent Updates</a>&nbsp;&nbsp;|&nbsp;&nbsp;  <a href="/admin/contact">Contact System Administrator</a><br /><br />
		       Copyright &copy; Hindu Swayamsevak Sangh (HSS) USA, Inc. All Rights Reserved. <br />
		       Tel: 973.860.2HSS&nbsp;&nbsp;|&nbsp;&nbsp;Fax: 973.302.8HSS&nbsp;&nbsp;| E-mail:&nbsp;info@hssus.org </p>
	       </div>
	 </div>
	 <!-- End Footer -->

   </div>
   <!-- End Wrapper -->

</body>
</html>
