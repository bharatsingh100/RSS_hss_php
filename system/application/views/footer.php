<!-- Begin Footer -->
		 <div id="footer">
		   <div align="center">
		     <p><a href="/admin/info">General Information</a>&nbsp;&nbsp;|&nbsp;&nbsp;  <a href="/admin/recent_updates">Recent Updates</a>&nbsp;&nbsp;|&nbsp;&nbsp;  <a href="/admin/contact">Contact System Administrator</a><br /><br />
		       Copyright &copy; Hindu Swayamsevak Sangh (HSS) USA, Inc. All Rights Reserved. <br />
		       Tel: 973.860.2HSS&nbsp;&nbsp;|&nbsp;&nbsp;Fax: 973.302.8HSS&nbsp;&nbsp;| E-mail:&nbsp;&#105;&#110;&#102;&#111;&#64;&#104;&#115;&#115;&#117;&#115;&#46;&#111;&#114;&#103; </p>
	       </div>
	 </div>
	 <!-- End Footer -->

   </div>
   <!-- End Wrapper -->

<script type="text/javascript" src="/css/jgcharts.pack.js"></script>
<script type="text/javascript" src="/css/jquery.autocomplete.js"></script>
<script type="text/javascript" src="/css/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="<?=site_url();?>javascript/jquery-1.10.2.js"></script>
<script type="text/javascript" src="<?=site_url();?>javascript/jquery-ui.js"></script>
<script type="text/javascript" src="/css/all.js"></script>
<script type="text/javascript">
<!--
	<?php /* Execute Javascript function from all.js based on URL */ ?>
	<?php $url_parts = $this->uri->segment(1) . '.' . $this->uri->segment(2); ?>

	if(typeof(mylib.<?php echo $this->uri->segment(1); ?>) !== "undefined") {
    	if(typeof(mylib.<?php echo $url_parts; ?>) !== "undefined") {
        	$(document).ready(function(){
    			mylib.<?php echo $url_parts;; ?>();
        	});
    	}
	}

//-->
</script>
<?php if($this->session->userdata('logged_in')) { 
	$uid = $this->session->userdata('contact_id') . '-' . $this->session->userdata('first_name') . '-' . $this->session->userdata('last_name');
?>
<script type="text/javascript">
<!--
	gtag('set', {'user_id': '<?php echo $uid; ?>'}); // Set the user ID using signed-in user_id.
//-->
</script>
<?php } ?>
</body>
</html>
