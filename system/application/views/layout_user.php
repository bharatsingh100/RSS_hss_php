<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" 
/>
<title><?=$pageTitle?> - Swayamsevak Information Management - HSS</title>
<link rel="stylesheet" type="text/css" href="/css/main.css" />
<link rel="shortcut icon" href="/favicon.ico" type="image/vnd.microsoft.icon">
</head>

<body>

<!-- Begin Wrapper -->
   <div id="wrapper">
   		<div id="header">
        <div id="info">
		<?=mdate("%l, %F %j%S", time())?> | Your IP Address: <strong><?=$this->session->userdata('session_ip_address')?></strong>
        </div>
         <br />
           <div style="text-align:center;">
             <h1>Hindu Swayamsevak Sangh</h1>
           </div>
         </div>
		 <!-- End Header -->
		 <!-- Begin Navigation -->
		 <div id="navigation">
         <?php echo($this->session->userdata('message') ? $this->session->ro_userdata('message') : ""); ?>
         </div>
<!-- End Navigation -->
		 <!-- Begin Left Column -->
		 <div id="leftcolumn"> <?=$content_for_layout?> </div>
		 <!-- End Left Column -->
		 
		 <!-- Begin Right Column -->
		 <div id="rightcolumn"> 
         <p><?=anchor('user/forgot_password', 'Forgot Password')?></p> 
         </div>

		 <!-- End Right Column -->
		 
		 <!-- Begin Footer -->
		 <div id="footer">
		   <div align="center">
		     <p><a href="/admin/contact">Contact System Administrator</a><br /><br />
		       Copyright &copy; Hindu Swayamsevak Sangh (HSS) USA, Inc. All Rights Reserved. <br />
		       Tel: 973.860.2HSS&nbsp;&nbsp;|&nbsp;&nbsp;Fax: 973.302.8HSS&nbsp;&nbsp;| E-mail:&nbsp;info@hssus.org </p>
	       </div>
	 </div>
	 <!-- End Footer -->
		 
   </div>
   <!-- End Wrapper -->
   
</body>
</html>
