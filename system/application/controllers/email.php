<?php
class Email extends Controller
{
	var $userdir;
	var $docpath;
	
	function Email()
    {
        parent::Controller();
	    	
		$this->load->helper('file');
		$this->load->model('Vibhag_model');
		$this->load->library('email');
		$this->userdir = explode('/',$_SERVER['DOCUMENT_ROOT']);
		$this->userdir = $this->userdir[2];
		$this->docpath = '/var/www/web2/web/';
    }

	function zip_code_fixes($id)
	{
		//$state = $this->db->select('state')->getwhere('shakhas', array('shakha_id' => $id))->row()->state;
		$ss = $this->db->select('contact_id, city, state')->getwhere('swayamsevaks', array('shakha_id'=>$id, 
'zip' => ''));
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

	//Testing E-mail
	function email_test() {

        $this->email->from('your@example.com', 'Your Name');
        $this->email->to('zzzabhi@gmail.com');
        //$this->email->cc('another@another-example.com');
        //$this->email->bcc('them@their-example.com');

        $this->email->subject('Email Test');
        $this->email->message('Testing the email class.');

        $this->email->send();

        echo $this->email->print_debugger();
	}

	function login_log_rss()
	{
		$this->db->select('contact_id, UNIX_TIMESTAMP(login) as time, name, ip_addr');
		$logs = $this->db->getwhere('loginlog', "login >= '".date('o-m-d')." 00:00:00'");
        $message = '<?xml version="1.0" ?>'."\n";
        $message .= '<rss version="2.0">'."\n";
        $message .= '<channel>'."\n\n";
        $message .= '<title>Login Logs for CRM</title>'."\n";
        $message .= '<description>Time when everyone logs in.</description>'."\n";
        $message .= '<link>' . base_url() . '</link>'."\n";

        if($logs->num_rows()){
        	$logs = $logs->result();
        	
        	//Get Information on all contacts
        	$k = array();
        	foreach($logs as $l)
        		$k[] = $l->contact_id;
        	$k = implode(',', $k);
        	$query = $this->db->select('contact_id, email, city, state')->getwhere('swayamsevaks', "contact_id IN ($k)")->result();
        	$contacts = array();
        	foreach($query as $contact){
        		$contacts[$contact->contact_id] = $contact;}
        		reset($logs);

			foreach($logs as $log){
				$message .= "\n".'<item>'."\n";
                $message .= '<title>'.$log->name.'</title>'."\n";
				$message .= '<description>' . $contacts[$log->contact_id]->email . ' - ' . $contacts[$log->contact_id]->city . ', ' . $contacts[$log->contact_id]->state;
				$message .= '</description>'."\n";
				$message .= '<pubDate>' . date(DATE_RFC2822, $log->time) . '</pubDate>';
				$message .= "<guid>" . base_url() . 'profile/view/'. $log->contact_id . '</guid>';
				$message .= '</item>'."\n";
			}

            $message .= '</channel>'."\n\n";
            $message .= '</rss>'."\n";
            echo $message;
      	}
	}

	function login_log()
	{
		return 0;
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

    //Every evening send emails for lists that need to be created.
	function email_lists()
	{
		/*require_once "Swift.php";
        require_once "Swift/Connection/SMTP.php";
		$swift =& new Swift(new Swift_Connection_SMTP("localhost"));*/

		return 0;
		$lists = $this->db->getwhere('lists', "modified >= '".date('o-m-d')." 00:00:00'");

		if($lists->num_rows()){
			foreach($lists->result() as $list){
			    if($list->status == 'Creating')
                    $this->email->subject('Create E-mail List ' . $list->address . '@hssusa.org');
                elseif ($list->status == 'Deleting')
                    $this->email->subject('Delete E-mail List ' . $list->address . '@hssusa.org');
                else
                    exit(1);

                $ss = $this->db->select('email, first_name, last_name, city, state')->getwhere('swayamsevaks', array('contact_id' => $list->owner))->row();

                $this->email->from($ss->email, $ss->first_name . ' ' . $ss->last_name);
                $this->email->reply_to($ss->email, $ss->first_name . ' ' . $ss->last_name);

                $message = 'Update E-mail list '. $list->address
                            . '@hssusa.org for ' . $ss->first_name . ' ' . $ss->last_name
                            . ' (' . $ss->city . ', ' . $ss->state . ")\n\n";

                $this->email->message($message);
                $this->email->to('crm_admin@hssusa.org');
                $this->email->send();
                //print $this->email->print_debugger();
			}

		}
	}

	//Set Shakha sankhya to 0 if there isn't sankhya entered for the Shakha
	function initialize_sankhya()
	{
		//Find All Shakhas that are held Today (i.e. Monday, Tuesday)
	    $day = date('l');
		$this->db->select('shakha_id');
		$shakhas = $this->db->getwhere('shakhas', array('frequency_day' => $day, 'frequency' => 'WK', 'shakha_status' => 1));
        $exists = '';
		if($shakhas->num_rows()){

		    $shakha_ids = $shakhas->result_array();
		    $ids = array();
		    foreach($shakha_ids as $j)
		        $ids[] = $j['shakha_id'];

		    $shakha_ids = implode(',',array_values($ids));

		    //Build Array of Shakahs that already have their Sankhya Entered
		    $this->db->select('shakha_id');
		    $this->db->where("shakha_id IN ($shakha_ids)");
		    $exists = $this->db->getwhere('sankhyas', array('date' => date('Y-m-d')));

		    if($exists->num_rows()) {
		        $exists = $exists->result_array();
		        foreach($exists as $k)
		            $keys[] = $k['shakha_id'];
		        $exists = $keys;
		    } else {
                $exists = array();
		    }

			foreach($shakhas->result() as $shakha)
			{
				if(in_array($shakha->shakha_id, $exists)) continue;

			    $data['total'] = $data['shishu_m'] = $data['shishu_f'] = 0;
			    $data['bala_f'] = $data['bala_m'] = $data['kishor_m'] = 0;
			    $data['kishor_f'] = $data['yuva_m'] = $data['yuva_f'] = 0;
			    $data['tarun_m'] = $data['tarun_f'] = $data['praudh_m'] = $data['praudh_f'] = 0;
				$data['contact_id'] = 0;//$this->session->userdata('0');
				$data['ip'] = $this->input->ip_address();
				$data['date'] = date('Y-m-d');
				$data['shakha_id'] = $shakha->shakha_id;
				$this->db->insert('sankhyas', $data);
			}
		}

		return $exists;
	}

	function sankhya_reminder()
	{

		$exclude_shakhas = $this->initialize_sankhya();

		$shakhas = $this->db->getwhere('shakhas', array('frequency_day' => date('l'), 'frequency' => 'WK', 'shakha_status' => 1));
		if($shakhas->num_rows()){
			$shakhas = $shakhas->result();
			foreach($shakhas as $shakha){

			    if(in_array($shakha->shakha_id, $exclude_shakhas)) continue;

				$this->db->where('shakha_id', $shakha->shakha_id);
				$this->db->where("responsibility IN ('020', '030', '031')");
				$kks = $this->db->select('swayamsevak_id')->get('responsibilities');

				if($kks->num_rows()){
    				$message    = "Can you please enter the sankhya of $shakha->name for " . date("F j, Y") . ' at ';
                    $message   .= site_url('shakha/add_sankhya/'.$shakha->shakha_id);
                    $message   .= "\n\n\nThanks\nHSS Sampark System\n";
                    $subject    = "Sankhya reminder for $shakha->name";


                    $this->email->from('crm_admin@hssusa.org', 'HSS Sampark System');
                    $this->email->reply_to('crm_admin@hssusa.org', 'HSS Sampark System');
    				//$recipients =& new Swift_RecipientList();

					$kks = $kks->result();
					foreach ($kks as $k){
						$t = $this->db->select('first_name, last_name,  email')->getwhere('swayamsevaks', array('contact_id' => $k->swayamsevak_id, 'email_status' => 'Active'));
						if($t->num_rows() && trim($t->row()->email) != '' ){
                            $text   = 'Namaste ' . $t->row()->first_name. " Ji\n\n";
                            $text  .= $message;

                            $this->email->to($t->row()->email);
                            //$this->email->to('zzzabhi@gmail.com');
                            $this->email->message($text);
                            $this->email->subject($subject);
                            $this->email->send();
                            //print $this->email->print_debugger();
						}
					}

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
	    //$path = "/home/$this->userdir/www/emails/bounced/";
		$path = $this->docpath . 'emails/bounced/';
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

	//Create file for adding and removing lists
	function edit_lists()
	{
		//List Lists
		//$host = '_hssusa.org';
		$host = '@hssusa.org';
		
		//Mark Old Lists as Active
		$this->db->where('status','Creating');
		$this->db->where('modified < ', date('Y-m-d H:i:s', strtotime('1 hour ago')));
		$this->db->update('lists', array('status' => 'Active'));
		
		$lists = $this->db->getwhere('lists', array('status' => 'Creating'));
		//$p = '/home/'.$this->userdir.'/www/emails/';
		$p = $this->docpath . 'emails/';
		if($lists->num_rows())
		{
			$lists = $lists->result();
			foreach($lists as $list)
			{
				$new_lists[] = strtolower($list->address.$host);
				$new_email_lists = implode("\n",$new_lists);
				$file = $p.'create-lists.txt';
				$fh = fopen($file, 'w') or die("Can't open file");
				fwrite($fh, $new_email_lists);
				fclose($fh);
				shell_exec('chmod 0666 '.$file);
			}
		}
	}
	function config_files()
	{
		//List Lists
		//$host = '_hssusa.org';
		$host = '';
		$lists = $this->db->getwhere('lists', array('status' => 'Active'));
		//$p = '/home/'.$this->userdir.'/www/emails/';
		$p = $this->docpath . 'emails/';
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
		//$host = '_hssusa.org';
		$host = '';
		$path = $this->docpath . 'emails/';
		$lists = $this->db->getwhere('lists', array('level' => 'SH', 'status' => 'Active'));
		if($lists->num_rows())
		{
			$lists = $lists->result();
			$l = '';
			foreach($lists as $list)
			{
				$l .= $list->address . $host . "\n";
			}
			//echo write_file('/home/'.$this->userdir.'/www/emails/elists.txt', $l);
			echo write_file($path.'elists.txt', $l);
			echo shell_exec('chmod 0666 ' . $path . 'elists.txt');

			foreach($lists as $list)
			{
				$list_name = $list->address . $host;
				//$file = '/home/'.$this->userdir.'/www/emails/synch/' . $list_name;
				$file = $path . 'synch/' . $list_name;
				$members = unserialize($list->members);
				$emails = '';
				echo $list_name . '<br />';
				foreach($members as $member)
				{
					switch($member)
					{
						case 'allss' :
							$t = $this->db->select('email')->getwhere('swayamsevaks', array('shakha_id' => $list->level_id, 'email_status' => 'Active'))->result();
							foreach($t as $j) if($this->isValidEmail($j->email)) $emails .= $j->email . "\n";
							break;
						case 'allkk' :
							$this->db->select('swayamsevaks.email');
							$this->db->from('swayamsevaks');
							$this->db->join('responsibilities', "responsibilities.shakha_id = $list->level_id AND responsibilities.swayamsevak_id = swayamsevaks.contact_id");
							$t = $this->db->get()->result();
							foreach($t as $j) if($this->isValidEmail($j->email)) $emails .= $j->email . "\n";
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
		//$host = '_hssusa.org';
		$host = '';
		$path = $this->docpath . 'emails/';
		$lists = $this->db->getwhere('lists', array('level' => 'VI', 'status' => 'Active'));
		if($lists->num_rows())
		{
			$lists = $lists->result();

			foreach($lists as $list)
			{
				$list_name = $list->address . $host;
				//$file = '/home/'.$this->userdir.'/www/emails/synch/' . $list_name;
				$file = $path . 'synch/' . $list_name;
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
							foreach($t as $j) if($this->isValidEmail($j->email)) $emails .= $j->email . "\n";
							break;
						case 'allkk' :
							$this->db->select('swayamsevaks.email');
							$this->db->from('swayamsevaks');
							$this->db->join('responsibilities', "responsibilities.shakha_id IN $shakha_ids AND responsibilities.swayamsevak_id = swayamsevaks.contact_id");
							$t = $this->db->get()->result();
							foreach($t as $j) if($this->isValidEmail($j->email)) $emails .= $j->email . "\n";
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
		//$host = '_hssusa.org';
		$host = '';
		$path = $this->docpath . 'emails/';
		$lists = $this->db->getwhere('lists', array('level' => 'SA', 'status' => 'Active'));
		if($lists->num_rows())
		{
			$lists = $lists->result();

			foreach($lists as $list)
			{
				$list_name = $list->address . $host;
				//$file = '/home/'.$this->userdir.'/www/emails/synch/' . $list_name;
				$file = $path . 'synch/' . $list_name;
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
							foreach($t as $j) if($this->isValidEmail($j->email)) $emails .= $j->email . "\n";
							break;
						case 'allkk' :
							$this->db->select('swayamsevaks.email');
							$this->db->from('swayamsevaks');
							$this->db->join('responsibilities', "responsibilities.shakha_id IN $shakha_ids AND responsibilities.swayamsevak_id = swayamsevaks.contact_id");
							$t = $this->db->get()->result();
							foreach($t as $j) if($this->isValidEmail($j->email)) $emails .= $j->email . "\n";
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
		//$host = '_hssusa.org';
		$host = '';
		$path = $this->docpath . 'emails/';
		$lists = $this->db->getwhere('lists', array('level' => 'NT', 'status' => 'Active'));
		if($lists->num_rows())
		{
			$lists = $lists->result();

			foreach($lists as $list)
			{
				$list_name = $list->address . $host;
				//$file = '/home/'.$this->userdir.'/www/emails/synch/' . $list_name;
				$file = $path . 'synch/' . $list_name;
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
							foreach($t as $j) if($this->isValidEmail($j->email)) $emails .= $j->email . "\n";
							break;
						case 'allkk' :
							$this->db->select('swayamsevaks.email');
							$this->db->from('swayamsevaks');
							$this->db->join('responsibilities', "responsibilities.swayamsevak_id = swayamsevaks.contact_id");
							$t = $this->db->get()->result();
							foreach($t as $j) if($this->isValidEmail($j->email)) $emails .= $j->email . "\n";
							break;
						case 'ntkk' :
							$this->db->select('swayamsevaks.email');
							$this->db->from('swayamsevaks');
							$this->db->join('responsibilities', "responsibilities.level = 'NT' AND responsibilities.swayamsevak_id = swayamsevaks.contact_id");
							$t = $this->db->get()->result();
							foreach($t as $j) if($this->isValidEmail($j->email)) $emails .= $j->email . "\n";
							break;
						case 'sakk' :
							$this->db->select('swayamsevaks.email');
							$this->db->from('swayamsevaks');
							$this->db->join('responsibilities', "responsibilities.level = 'SA' AND responsibilities.swayamsevak_id = swayamsevaks.contact_id");
							$t = $this->db->get()->result();
							foreach($t as $j) if($this->isValidEmail($j->email)) $emails .= $j->email . "\n";
							break;
						case 'vikk' :
							$this->db->select('swayamsevaks.email');
							$this->db->from('swayamsevaks');
							$this->db->join('responsibilities', "responsibilities.level = 'VI' AND responsibilities.swayamsevak_id = swayamsevaks.contact_id");
							$t = $this->db->get()->result();
							foreach($t as $j) if($this->isValidEmail($j->email)) $emails .= $j->email . "\n";
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
	
	function isValidEmail($email){
		return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
	}

}

?>
