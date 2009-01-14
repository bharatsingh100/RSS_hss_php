<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"
/>
<title><?=$pageTitle?> - Swayamsevak Information Management - HSS</title>
<link rel="stylesheet" type="text/css" href="/css/main.css" />
<link rel="shortcut icon" href="/favicon.ico" type="image/vnd.microsoft.icon" />
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
			 <?php if($this->session->userdata('logged_in')): ?>
			 <?php echo anchor('profile/view/' . $this->session->userdata('contact_id'), 'My Profile'); ?>
             <?php echo anchor('shakha/view/' . $this->session->userdata('shakha_id'), 'My Shakha'); ?>
             <?php echo anchor('vibhag/view/' . $this->session->userdata('vibhag_id'), 'My Vibhag'); ?>
             <?php echo anchor('sambhag/view/' . $this->session->userdata('sambhag_id'), 'My Sambhag'); ?>
             <?php echo anchor('national/view', 'National'); ?>
             <?php endif; ?>
           </span>
            <span class="right active"><?php echo anchor('tour', 'Tutorial Video'); ?></span>
         </div>
<!-- End Navigation -->
		 <!-- Begin Left Column -->
		 <div id="leftcolumn"><?=$content_for_layout?></div>
		 <!-- End Left Column -->

		 <!-- Begin Right Column -->
		 <div id="rightcolumn">
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
