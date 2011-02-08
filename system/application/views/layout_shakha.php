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
             <?php if($this->session->userdata('shakha_id') == $this->uri->segment(3))
             		{$t = TRUE; echo '<span class="active">';}?>
             <?php echo anchor('shakha/view/' . $this->session->userdata('shakha_id'), 'My Shakha'); ?>
             	<?php if(isset($t)) echo '</span>';?>
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
                <?php echo anchor('admin/hssdocs','Share Documents'); ?>
                <?php echo anchor('tour', 'Tutorial Video'); ?>
            </span>
         </div>
<!-- End Navigation -->
		 <!-- Breadcrumb -->
         <div id="breadcrumb">
         <?php echo anchor('shakha/view/'.$this->session->ro_userdata('bc_shakha_id'), $this->session->userdata('bc_shakha')); ?>
         <?php if($this->session->userdata('bc_nagar_id'))
         			echo ' / ' . anchor('nagar/view/'.$this->session->ro_userdata('bc_nagar_id'), $this->session->ro_userdata('bc_nagar')); ?>
         <?php echo ' / '.anchor('vibhag/view/'.$this->session->ro_userdata('bc_vibhag_id'), $this->session->ro_userdata('bc_vibhag')); ?>
	     <?php echo ' / '.anchor('sambhag/view/'.$this->session->ro_userdata('bc_sambhag_id'), $this->session->ro_userdata('bc_sambhag')); ?>         			         </div>
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
                <option <?php if($this->session->userdata('within') == 'SA') echo ' selected="selected" '; ?> value="<?='SA_'.$this->session->userdata('sambhag_id')?>">My Sambhag&nbsp;</option><?php //endif; ?>
                 <?php //if($this->permission->is_nt_kk()):?>
                <option <?php if($this->session->userdata('within') == 'NT') echo ' selected="selected" '; ?> value="<?='NT_'?>">Everything&nbsp;</option><?php //endif; ?>
              </select>
              <?php $this->session->set_userdata('redirect_url', $this->uri->uri_string());?>

            </form>
         <br />
		 <hr />
         <?php endif; ?>
         <br />
         <?php if($this->uri->segment(3)) $shakha_id = $this->uri->segment(3); $is_kkh = false; ?>
         <?php if($this->permission->is_shakha_kkl($shakha_id)):
					if($this->permission->is_shakha_kkh($shakha_id)) $is_kkh = true;?>
         <h3>Navigation </h3>

         <?php echo '<p>',anchor('shakha/browse/'.$shakha_id. '/name/', 'List All Contacts'),'</p>';?>
         <?php echo '<p>',anchor('shakha/gata/'.$shakha_id, 'List Gatas'),'</p>';?>
		 <?php echo '<p>',anchor('shakha/addss/'.$shakha_id, 'Add New Contact'),'</p>';?>
		 <?php if($is_kkh):?>
  		 <?php echo '<p>',anchor('shakha/upload_contacts/'.$shakha_id, 'Import Contacts'),'</p>';?>
           <?php echo '<p>',anchor('shakha/add_sankhya/'.$shakha_id, 'Report Sankhya'),'</p>';?>
           <?php echo '<p>',anchor('shakha/responsibilities/'.$shakha_id, 'Manage Responsibilities'),'</p>';?>
           <?php //echo '<p>',anchor('shakha/sny_count/'.$shakha_id, 'Report SNY Counts'),'</p>';?>
           <?php echo '<p>',anchor('shakha/edit_shakha/'.$shakha_id, 'Change Shakha Details'),'</p>';?>
         <?php endif; ?>
	     <?php echo '<p>',anchor('shakha/statistics/'.$shakha_id, 'Shakha Statistics'),'</p>';?>
	     <p>&nbsp;</p>
	     <?php echo '<p>',anchor('shakha/sny_statistics/'.$shakha_id . '/2011', 'SNY Statistics'),'</p>';?>
	     <?php echo '<p>',anchor('shakha/sny_statistics/'.$shakha_id . '/2010', 'SNY Statistics (Last Year)'),'</p>';?>
	     <?php if($is_kkh) echo '<p>To Update 2011 SNY Stats for your Shakha, e-mail SNY team sny@hssus.org</p>';?>

	  <p>&nbsp;</p>
	  <?php if($is_kkh) echo '<p>',anchor('shakha/email_lists/'.$shakha_id, 'View Email Lists'),'</p>';?>
	  <?php if($is_kkh) echo '<p>',anchor('shakha/create_list/'.$shakha_id, 'Request E-mail List'),'</p>';?>

      <?php endif; ?>
         </div>

		 <!-- End Right Column -->

      <?php require_once 'footer.php';?>
