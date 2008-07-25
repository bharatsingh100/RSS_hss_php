<?php
class Email extends Controller
{
    
	var $userdir;
	
	function Email()
    {
        parent::Controller();
	//$this->output->enable_profiler(TRUE);
		$this->load->helper('file');
		$this->load->model('Vibhag_model');
		$this->userdir = explode('/',$_SERVER['DOCUMENT_ROOT']);
		$this->userdir = $this->userdir[2];
    }
	
	function zip_code_fixes($id)
	{
		//$state = $this->db->select('state')->getwhere('shakhas', array('shakha_id' => $id))->row()->state;
		$ss = $this->db->select('contact_id, city, state')->getwhere('swayamsevaks', array('shakha_id'=>$id, 'zip' => ''));
		if($ss->num_rows())
		{
			$ss = $ss->result();
			foreach($ss as $s)
			{
				$zip = $this->db->select('zip')->getwhere('zipcodes', array('state' => $s->state, 'town' => $s->city));
				if($zip->num_rows() == 1)
				{
					$d['zip'] = $zip->row()->zip;
					echo $s->contact_id,' ',$s->city,' ',$s->state,' ',$d['zip'],'<br />';
					//$this->db->update('swayamsevaks', 
				}
			}
		}
	}
/*    function index()
    {
        //Load in the files we'll need
        require_once "Swift.php";
        require_once "Swift/Connection/SMTP.php";

        //Start Swift
        $swift =& new Swift(new Swift_Connection_SMTP("localhost"));

        //Create the message
        $message =& new Swift_Message("My subject", "My body");

        //Now check if Swift actually sends it
        if ($swift->send($message, "zzzabhi@gmail.com", "admin@theuniversalwisdom.org")) echo "Sent";
        else echo "Failed";
    }*/
	function login_log_rss()
	{
	//	require_once "Swift.php";
  //      require_once "Swift/Connection/SMTP.php";
	//	$swift =& new Swift(new Swift_Connection_SMTP("localhost"));

		$logs = $this->db->getwhere('loginlog', "login >= '".date('o-m-d')." 00:00:00'");
	//	$subject = 'Login Logs Details';
	  $message = '<?xml version="1.0" ?>'."\n";
    $message .= '<rss version="2.0">'."\n";
    $message .= '<channel>'."\n\n";
    $message .= '<title>Login Logs for CRM</title>'."\n";
    $message .= '<description>Time when everyone logs in.</description>'."\n";
    $message .= '<link>https://crm.hssusa.org</link>'."\n";
	//	$message = 'Login Logs for '.date("F j, Y").'<br /><br />';
	//	$message = "Name\t\tIP-Address\t\tTime\t<br />";
		if($logs->num_rows())
		{
			foreach($logs->result() as $log)
			{
				$message .= "\n".'<item>'."\n";
        $message .= '<title>'.anchor(site_url('profile/view/'.$log->contact_id), $log->name).'</title>'."\n";
				$message .= '<description>' . anchor('http://www.melissadata.com/lookups/iplocation.asp?ipaddress='.$log->ip_addr, $log->ip_addr)."\n";
				$message .= "$log->login".'</description>'."\n";
				$message .= '</item>'."\n";
			}
		
    $message .= '</channel>'."\n\n";
    $message .= '</rss>'."\n";
    echo $message;	
      //$message =& new Swift_Message($subject, $message, "text/html");	
			//$swift->send($message, 'zzzabhi@gmail.com', "crm_admin@hssusa.org");
		}
	}
	
	function login_log()
	{
		require_once "Swift.php";
        require_once "Swift/Connection/SMTP.php";
		$swift =& new Swift(new Swift_Connection_SMTP("localhost"));

		$logs = $this->db->getwhere('loginlog', "login >= '".date('o-m-d')." 00:00:00'");
		$subject = 'Login Logs Details';
		$message = 'Login Logs for '.date("F j, Y").'<br /><br />';
		$message = "Name\t\tIP-Address\t\tTime\t<br />";
		if($logs->num_rows())
		{
			foreach($logs->result() as $log)
			{
				$message .= anchor(site_url('profile/view/'.$log->contact_id), $log->name);
				$message .= "\t\t" . anchor('http://www.melissadata.com/lookups/iplocation.asp?ipaddress='.$log->ip_addr, $log->ip_addr);
				$message .="\t\t$log->login\t<br />";
			}
			$message =& new Swift_Message($subject, $message, "text/html");	
			$swift->send($message, 'zzzabhi@gmail.com', "crm_admin@hssusa.org");
		}
	}
	
	function email_lists()
	{
		require_once "Swift.php";
        require_once "Swift/Connection/SMTP.php";
		$swift =& new Swift(new Swift_Connection_SMTP("localhost"));

		$lists = $this->db->getwhere('lists', "modified >= '".date('o-m-d')." 00:00:00'");
		$subject = 'E-mail list updates';
		$message = 'E-mail list updates for '.date("F j, Y").'<br /><br />';
		$message = "Name\t\tStatus\t\tLevel\t\tLevel ID\t<br />";
		if($lists->num_rows())
		{
			foreach($lists->result() as $list)
			{
				//$list_type = ($list == 'SH') ? 'shakha' : 'vibhag';
				$list_type = '';
				switch($list){
					case 'SH':
						$list_type = 'shakha';
						break;
					case 'VI':
						$list_type = 'vibhag';
						break;
					case 'SA':
						$list_type = 'sambhag';
						break;
					case 'NT':
						$list_type = 'national';
						break;
				}																		
				$message .= anchor(site_url($list_type.'/email_lists/'.$list->level_id), $list->address.'@hssusa.org');
				//$message .= "\t\t" . anchor('http://www.melissadata.com/lookups/iplocation.asp?ipaddress='.$log->ip_addr, $log->ip_addr);
				$message .="\t\t$list->status\t\t$list->level\t\t".anchor(site_url($list_type.'/view/'.$list->level_id), $list->level_id);				
				$message .= '<br />';
			}
			$message =& new Swift_Message($subject, $message, "text/html");	
			$swift->send($message, 'zzzabhi@gmail.com', "crm_admin@hssusa.org");
		}
	}
	function initialize_sankhya()
	{
		$day = date('l');
		$shakhas = $this->db->getwhere('shakhas', array('frequency_day' => $day, 'frequency' => 'WK', 'shakha_status' => 1));
		if($shakhas->num_rows()){
			foreach($shakhas->result() as $shakha)
			{
				$data['total'] = $data['shishu_m'] = $data['shishu_f'] = $data['bala_f'] = $data['bala_m'] = $data['kishor_m'] = $data['kishor_f'] = $data['yuva_m'] = $data['yuva_f'] = $data['tarun_m'] = $data['tarun_f'] = $data['praudh_m'] = $data['praudh_f'] = 0;
				$data['contact_id'] = $this->session->userdata('0');
				$data['ip'] = $this->input->ip_address();
				$data['date'] = date('Y-m-d');
				$data['shakha_id'] = $shakha->shakha_id;
				
				$exists = $this->db->getwhere('sankhyas', array('date' => $data['date'], 'shakha_id' => $data['shakha_id']));
				if(!$exists->num_rows())
					$this->db->insert('sankhyas', $data);
			}
		}
	}
		
	function sankhya_reminder()
	{
		require_once "Swift.php";
        require_once "Swift/Connection/SMTP.php";
		$swift =& new Swift(new Swift_Connection_SMTP("localhost"));

		$this->initialize_sankhya();
		$day = date('l');
		$shakhas = $this->db->getwhere('shakhas', array('frequency_day' => $day, 'frequency' => 'WK', 'shakha_status' => 1));
		if($shakhas->num_rows()){
			$shakhas = $shakhas->result();
			foreach($shakhas as $shakha){
				$this->db->where('shakha_id', $shakha->shakha_id);
				$this->db->where("responsibility IN ('030','031')");
				$kks = $this->db->select('swayamsevak_id')->get('responsibilities');
				
				$message = "Please enter the sankhya of $shakha->name for " . date("F j, Y") . ' at ';
				$message .= site_url('shakha/add_sankhya/'.$shakha->shakha_id);
				$message .= "\n\nThanks\ncrm_admin@hssusa.org\n";
				$subject = "Sankhya reminder for $shakha->name";
				$recipients =& new Swift_RecipientList();
				
				if($kks->num_rows()){
					$kks = $kks->result();
					foreach ($kks as $k){
						$t = $this->db->select('first_name, last_name,  email')->getwhere('swayamsevaks', array('contact_id' => $k->swayamsevak_id, 'email_status' => 'Active'));
						if($t->num_rows()) $recipients->addTo($t->row()->email, $t->row()->first_name .' '.$t->row()->last_name);
					}
					
					//Create the message
					$message =& new Swift_Message($subject, $message);

					//Now check if Swift actually sends it
					if ($swift->send($message, $recipients, "crm_admin@hssusa.org")) echo "Sent";
					else echo "Failed";
				}
			}	
		}

	}
	
/*	function unsubscribed()
	{
	
	    // Define the full path to your folder from root
	    $path = "/home/$this->userdir/www/emails/unsubscribed/";

	    // Open the folder
	    $dir_handle = @opendir($path) or die("Unable to open $path");
	    // Loop through the files
	    while ($file = readdir($dir_handle)) {
		    if($file == "." || $file == "..") continue;

			$fh = fopen($path.$file, 'r') or die("Can't open file $file");

			$theData = '';
			while(!feof($fh)) $theData .= fgets($fh);
			fclose($fh);
			$values = explode("\n", $theData); 
			$t['email_status'] = 'Unsubscribed';
			foreach($values as $val)
			{
				if($val == '') continue;
				$this->db->where('email', $val);
				$this->db->update('swayamsevaks', $t);
			}
			
			//echo "<a href=\"$file\">$file</a><br />";
	    }
	    // Close
	    closedir($dir_handle);
	}*/

	function bounced()
	{
	
	    // Define the full path to your folder from root
	    $path = "/home/$this->userdir/www/emails/bounced/";

	    // Open the folder
	    $dir_handle = @opendir($path) or die("Unable to open $path");
	    // Loop through the files
	    while ($file = readdir($dir_handle)) {
		    if($file == "." || $file == "..") continue;
	        //$list = explode('_', $file);
			//$list = $list[0];
			$fh = fopen($path.$file, 'r') or die("Can't open file $file");
			//$theData = fgets($fh);
			$theData = '';
			while(!feof($fh)) $theData .= fgets($fh);
			fclose($fh);
			$values = explode("\n", $theData); 
			$t['email_status'] = 'Bounced';
			foreach($values as $val)
			{
				if($val == '') continue;
				$this->db->where('email', $val);
				$this->db->update('swayamsevaks', $t);
			}
			
			//echo "<a href=\"$file\">$file</a><br />";
	    }
	    // Close
	    closedir($dir_handle);
	}
	
	function config_files()
	{
		//List Lists
		$host = '_hssusa.org';
		$lists = $this->db->getwhere('lists', array('status' => 'Active'));
		$p = '/home/'.$this->userdir.'/www/emails/';
		if($lists->num_rows())
		{
			$lists = $lists->result();
			foreach($lists as $list)
			{
				$conf = ($list->style) ? 'unmoderated' : 'moderated';
				$conf_file = $p.$conf.'_config.txt ';
				$file = $p.'configs/'.$list->address.$host;
				$cmd = 'cp '.$conf_file.$file;
				shell_exec($cmd);
				$t = $this->db->select('email')->getwhere('swayamsevaks', array('contact_id' => $list->mod1))->row();
				$mods = "moderator = ['$t->email'";
				if($list->mod2)
				{
					$m = $this->db->select('email')->getwhere('swayamsevaks', array('contact_id' => $list->mod2, 'email_status' => 'Active'));
				    if($m->num_rows())
					{
						$m = $m->row();
						$mods .= ", '".$m->email."'";
					}
				}
				if($list->mod3)
				{
					$t = $this->db->select('email')->getwhere('swayamsevaks', array('contact_id' => $list->mod3, 'email_status' => 'Active'));
				    if($t->num_rows()){
						$t = $t->row();
						$mods .= ", '$t->email']\n";
					}
					else $mods .= "]\n";
				}
				else $mods .= "]\n";			
				//Append moderaters to config file
				$fh = fopen($file, 'a') or die("can't open file");
				fwrite($fh, $mods);
				fclose($fh);
				
				$file = $p.'configs_pass/'.$list->address.$host;
				$fh = fopen($file, 'w') or die("Can't open file");
				$txt = "import sha\n";
				$txt .= "mlist.mod_password = sha.new('" . $list->mod_pass . '\').hexdigest()';
				shell_exec('chmod 0666 '.$file);
				fwrite($fh, $txt);
				fclose($fh);
			}
		} 
	}

	function shakha_lists()
	{
		$host = '_hssusa.org';
		$lists = $this->db->getwhere('lists', array('level' => 'SH', 'status' => 'Active'));
		if($lists->num_rows())
		{
			$lists = $lists->result();
			$l = '';
			foreach($lists as $list)
			{
				$l .= $list->address . $host . "\n";
			}
			echo write_file('/home/'.$this->userdir.'/www/emails/elists.txt', $l);
			echo shell_exec("chmod 0666 /home/$this->userdir/www/emails/elists.txt");
			
			foreach($lists as $list)
			{
				$list_name = $list->address . $host;
				$file = '/home/'.$this->userdir.'/www/emails/synch/' . $list_name;
				$members = unserialize($list->members);
				$emails = '';
				echo $list_name . '<br />';
				foreach($members as $member)
				{
					switch($member)
					{
						case 'allss' :
							$t = $this->db->select('email')->getwhere('swayamsevaks', array('shakha_id' => $list->level_id, 'email_status' => 'Active'))->result();
							foreach($t as $j) $emails .= $j->email . "\n";
							break;
						case 'allkk' :
							$this->db->select('swayamsevaks.email');
							$this->db->from('swayamsevaks');
							$this->db->join('responsibilities', "responsibilities.shakha_id = $list->level_id AND responsibilities.swayamsevak_id = swayamsevaks.contact_id");
							$t = $this->db->get()->result();
							foreach($t as $j) $emails .= $j->email . "\n";
							break;
					}	
				}
				$e_arr = explode("\n",$emails);
				$q['size'] = count(array_unique($e_arr));
				$this->db->where('id',$list->id);
				$this->db->update('lists',$q);
				
				if($emails != '' && write_file($file, $emails))
				{
					echo $file . '<br />';
					echo $emails;
					shell_exec("chmod 0666 $file");
				}
				echo '<br />' . '==============================';
			}
		}
		else echo '=======No Lists Found=======';
	}
	
	function vibhag_lists()
	{
		$host = '_hssusa.org';
		$lists = $this->db->getwhere('lists', array('level' => 'VI', 'status' => 'Active'));
		if($lists->num_rows())
		{
			$lists = $lists->result();
			
			foreach($lists as $list)
			{
				$list_name = $list->address . $host;
				$file = '/home/'.$this->userdir.'/www/emails/synch/' . $list_name;
				$members = unserialize($list->members);
				$emails = '';
//				echo $list_name . '<br />';
				
				$shakha_ids = $this->Vibhag_model->get_shakhas($list->level_id);
				$shakha_ids = '('.implode(',',$shakha_ids).')';
				foreach($members as $member)
				{
					switch($member)
					{
						case 'allss' :
							$this->db->where('shakha_id IN ' . $shakha_ids);
							$this->db->where('email_status', 'Active');
							$t = $this->db->select('email')->get('swayamsevaks')->result();
							foreach($t as $j) $emails .= $j->email . "\n";
							break;
						case 'allkk' :
							$this->db->select('swayamsevaks.email');
							$this->db->from('swayamsevaks');
							$this->db->join('responsibilities', "responsibilities.shakha_id IN $shakha_ids AND responsibilities.swayamsevak_id = swayamsevaks.contact_id");
							$t = $this->db->get()->result();
							foreach($t as $j) $emails .= $j->email . "\n";
							break;
					}
						
				}
				$e_arr = explode("\n",$emails);
				$q['size'] = count(array_unique($e_arr));
				$this->db->where('id',$list->id);
				$this->db->update('lists',$q);
				
				if($emails != '' && write_file($file, $emails))
				{
					echo $file . '<br />';
					echo $emails;
					shell_exec("chmod 0666 $file");
				}
				echo '<br />' . '==============================';
			}
		}
		else echo '=======No Lists Found=======';
	}
	
	function sambhag_lists()
	{
		$host = '_hssusa.org';
		$lists = $this->db->getwhere('lists', array('level' => 'SA', 'status' => 'Active'));
		if($lists->num_rows())
		{
			$lists = $lists->result();
			
			foreach($lists as $list)
			{
				$list_name = $list->address . $host;
				$file = '/home/'.$this->userdir.'/www/emails/synch/' . $list_name;
				$members = unserialize($list->members);
				$emails = '';
//				echo $list_name . '<br />';
				
				$shakha_ids = $this->Sambhag_model->get_shakhas($list->level_id);
				$shakha_ids = '('.implode(',',$shakha_ids).')';
				foreach($members as $member)
				{
					switch($member)
					{
						case 'allss' :
							$this->db->where('shakha_id IN ' . $shakha_ids);
							$this->db->where('email_status', 'Active');
							$t = $this->db->select('email')->get('swayamsevaks')->result();
							foreach($t as $j) $emails .= $j->email . "\n";
							break;
						case 'allkk' :
							$this->db->select('swayamsevaks.email');
							$this->db->from('swayamsevaks');
							$this->db->join('responsibilities', "responsibilities.shakha_id IN $shakha_ids AND responsibilities.swayamsevak_id = swayamsevaks.contact_id");
							$t = $this->db->get()->result();
							foreach($t as $j) $emails .= $j->email . "\n";
							break;
					}
						
				}
				$e_arr = explode("\n",$emails);
				$q['size'] = count(array_unique($e_arr));
				$this->db->where('id',$list->id);
				$this->db->update('lists',$q);
				
				if($emails != '' && write_file($file, $emails))
				{
					echo $file . '<br />';
					echo $emails;
					shell_exec("chmod 0666 $file");
				}
				echo '<br />' . '==============================';
			}
		}
		else echo '=======No Lists Found=======';
	}
	
	function national_lists()
	{
		$host = '_hssusa.org';
		$lists = $this->db->getwhere('lists', array('level' => 'NT', 'status' => 'Active'));
		if($lists->num_rows())
		{
			$lists = $lists->result();
			
			foreach($lists as $list)
			{
				$list_name = $list->address . $host;
				$file = '/home/'.$this->userdir.'/www/emails/synch/' . $list_name;
				$members = unserialize($list->members);
				$emails = '';

				foreach($members as $member)
				{
					switch($member)
					{
						case 'allss' :
						//	$this->db->where('shakha_id IN ' . $shakha_ids);
							$this->db->where('email_status', 'Active');
							$t = $this->db->select('email')->get('swayamsevaks')->result();
							foreach($t as $j) $emails .= $j->email . "\n";
							break;
						case 'allkk' :
							$this->db->select('swayamsevaks.email');
							$this->db->from('swayamsevaks');
							$this->db->join('responsibilities', "responsibilities.swayamsevak_id = swayamsevaks.contact_id");
							$t = $this->db->get()->result();
							foreach($t as $j) $emails .= $j->email . "\n";
							break;
						case 'ntkk' :
							$this->db->select('swayamsevaks.email');
							$this->db->from('swayamsevaks');
							$this->db->join('responsibilities', "responsibilities.level = 'NT' AND responsibilities.swayamsevak_id = swayamsevaks.contact_id");
							$t = $this->db->get()->result();
							foreach($t as $j) $emails .= $j->email . "\n";
							break;
						case 'sakk' :
							$this->db->select('swayamsevaks.email');
							$this->db->from('swayamsevaks');
							$this->db->join('responsibilities', "responsibilities.level = 'SA' AND responsibilities.swayamsevak_id = swayamsevaks.contact_id");
							$t = $this->db->get()->result();
							foreach($t as $j) $emails .= $j->email . "\n";
							break;
						case 'vikk' :
							$this->db->select('swayamsevaks.email');
							$this->db->from('swayamsevaks');
							$this->db->join('responsibilities', "responsibilities.level = 'VI' AND responsibilities.swayamsevak_id = swayamsevaks.contact_id");
							$t = $this->db->get()->result();
							foreach($t as $j) $emails .= $j->email . "\n";
							break;
					}
						
				}
				$e_arr = explode("\n",$emails);
				$q['size'] = count(array_unique($e_arr));
				$this->db->where('id',$list->id);
				$this->db->update('lists',$q);
				
				if($emails != '' && write_file($file, $emails))
				{
					echo $file . '<br />';
					echo $emails;
					shell_exec("chmod 0666 $file");
				}
				echo '<br />' . '==============================';
			}
		}
		else echo '=======No Lists Found=======';
	}
}

?>