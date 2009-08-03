<?php
class User extends Controller
{
    function User()
    {
        parent::Controller();
        $this->output->enable_profiler($this->config->item('debug'));
		$this->load->library('layout', 'layout_user');
		$this->load->helper('security');
		//$this->output->enable_profiler(TRUE);
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');

    }

	function index()
	{
			$data['pageTitle'] = 'User Login';
			$this->layout->view('user/login', $data);
	}

	function logout()
	{
		$this->session->sess_destroy();
		//if(!$this->session->userdata('message'))
		$this->session->set_userdata('message', 'You have successfully logged out!');
		$data['pageTitle'] = 'Logged Out';
		$this->layout->view('user/login', $data);
	}
	function login()
	{

		if($this->session->userdata('redirect'))
			$redirect_url = $this->session->ro_userdata('redirect');
		if($this->session->userdata('logged_in')){// == $this->input->post('username')) {
			//User is already logged in.
			redirect('profile/view/' . $this->session->userdata('contact_id'));
		}
		elseif($this->_logincheck($this->input->post('username'), $this->input->post('password')))
		{
		    if($this->input->post('username') == $this->input->post('password'))
			    redirect('profile/change_password/' . $this->session->userdata('contact_id'));
		    else { //Add MD5 hash of password if wrong or not present (Shouldn't need this After Some Time)
		      $this->db->select('passwordmd5');
		      $pass = $this->db->getwhere('swayamsevaks', array('contact_id' => $this->session->userdata('contact_id')));
		      $pass = $pass->row();
		      if(md5($this->input->post('password')) != $pass->passwordmd5){
		        $p['passwordmd5'] = md5($this->input->post('password'));
		        $this->db->where('contact_id', $this->session->userdata('contact_id'));
		        $this->db->update('swayamsevaks', $p);
		      }

		    }

		    if(isset($redirect_url))
				  redirect($redirect_url);
			  else
				  redirect('shakha/view/' . $this->session->userdata('shakha_id'));
		}
		else
		{
			//$this->session->set_flashdata('message', 'Your password and email didn\'t match our records. Please try again.');
			$data['pageTitle'] = 'Try logging again';
			$this->layout->view('user/login', $data);
		}

	}

	function reset_pass($code = '')
	{
		if($this->input->post('password'))
		{
			if(trim($this->input->post('pass1')) != trim($this->input->post('pass2')))
			{
				$this->session->set_userdata('message', 'Your passwords did not match. Please try again.');
				redirect('user/reset_pass/'.$code);
			}
			elseif(strlen(trim($this->input->post('pass1'))) < 5)
			{
				$this->session->set_userdata('message', 'Your passwords should be at least 5 characters long.');
				redirect('user/reset_pass/'.$code);
			}
			else
			{
				$ci = $this->input->post('contact_id');
				$this->db->where('contact_id', $ci);
				$m['passwordmd5'] = md5(trim($this->input->post('pass1')));
				$m['password'] = sha1(trim($this->input->post('pass1')));
				$this->db->update('swayamsevaks', $m);
				$this->session->set_userdata('message', 'Your password has been updated.');
				redirect('user');
			}
		}
		elseif($code != '')
		{
			$rs = $this->db->getwhere('pass_reset', array('enc_email' => $code));
			if($rs->num_rows())
			{
				$rs = $rs->row();
				$j = $this->db->getwhere('swayamsevaks', array('contact_id' => $rs->contact_id));
				$j = $j->row();
				$k['pageTitle'] = 'Reset Password';
				$k['name'] = $j->first_name . ' ' . $j->last_name;
				$k['contact_id'] = $j->contact_id;
				$this->layout->view('user/reset-pass', $k);
			}
			else
			{
				$this->session->set_userdata('message', 'Your URL has expired. Please request reset password again');
				redirect('user/forgot_password');
			}
		}
		else
		{
			//$this->session->set_userdata('message', 'Your URL has expired. Please request reset password again');
			redirect('user/forgot_password');
		}
	}

	function forgot_password()
	{
		if($this->input->post('login'))
		{
			$email = trim($this->input->post('email'));
			if($email && strlen($email) > 6)
			{
				//$email = trim($this->input->post('email'));
				$result = $this->db->getwhere('swayamsevaks', array('email' => $email));
				if($result->num_rows())
				{
					$v = $result->row();
					$t['contact_id'] = $v->contact_id;
					$t['email'] = $email;
					$t['enc_email'] = sha1($email);
					$t['ip_addr'] = $this->input->ip_address();
					$this->db->insert('pass_reset', $t);

					//Removing Swift
					$this->load->library('email');
					$this->email->from('crm_admin@hssusa.org', 'HSS Sampark System');
                    $this->email->reply_to('crm_admin@hssusa.org', 'HSS Sampark System');
					//require_once "Swift.php";
					//require_once "Swift/Connection/SMTP.php";

					//Start Swift
					//$swift =& new Swift(new Swift_Connection_SMTP("localhost"));

					$body = "Namaste \n\n";
					$body .= 'Someone from the IP Address: '.$this->input->ip_address(). " requested to reset your password. \n\n";
					$body .= 'If you want to reset your password, click here ' . base_url() . 'user/reset_pass/' . sha1($email);
					$body .= "\n\nOtherwise, just ignore this e-mail.\n";
					$body .= "\n-----\nWed Admin Team\ncrm_admin@hssusa.org\n\n";
					//Create the message
					$this->email->to($email);
                    $this->email->message($body);
                    $this->email->subject("Password Reset Request for HSS CRM");

                    //$this->email->print_debugger();
					//$message =& new Swift_Message("Password Reset Request for HSS CRM", $body);

					//Now check if the mail is sent
					if ($this->email->send())
					{
						$this->session->set_userdata('message', 'Please check your email ' . $email . ' for further instructions.');
						redirect('user/forgot_password');
					} else
					{
						$this->session->set_userdata('message', 'We couldn\'t send you e-mail. Please try again later.');
						redirect('user/forgot_password');
					}
				}
				else
				{
					$this->session->set_userdata('message', 'Your email address is not in our records. Please contact your Karyavah for more info.');
					redirect('user/forgot_password');
				}
			}
			else
			{
				$this->session->set_userdata('message', 'Please enter a proper e-mail address.');
				redirect('user/forgot_password');
			}

		}
		else
		{
			$t['pageTitle'] = 'Reset Password';
			$this->layout->view('user/forgot_password', $t);
		}
	}


	function _logincheck($user = '', $password = '')
	{
		if($user == '' || $password == '')
		{
			//$this->session->set_flashdata('message', 'Your password or email address field was blank.');
			$this->session->set_userdata('message', 'Your password or email address field was blank. Try again');
			return false;
//		$this->->db->where('email', $user);
		}

		$query = $this->db->getwhere('swayamsevaks', array('email' => $user));
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			//Only Karyakartas are allowed to access this system
			$t = $this->db->getwhere('responsibilities', array('swayamsevak_id' => $row->contact_id));
			if($t->num_rows() == 0)
			{
				$this->session->set_userdata('message', 'Your are not allowed to access this system. Please contact us for more info.');
				return false;
			}
			if($row->password != dohash($password))
			{
				//$this->session->set_flashdata('message', 'Your password did\'t match our records.');
				$this->session->set_userdata('message', 'Your password did\'t match our records.');
				return false;
			}
			else
			{
				//Destroy old session
				$this->session->sess_destroy();

				//Create a fresh, brand new session
				$this->session->sess_create();

				//Remove the password fields
				unset($row->password);
				unset($row->passwordmd5);

				//Set session data
				$this->session->set_userdata($row);
				if($row->shakha_id != '')
				{
					$t = $this->db->getwhere('shakhas', array('shakha_id' => $row->shakha_id))->row();
					if(trim($t->nagar_id) != '')
						$this->session->set_userdata('nagar_id', $t->nagar_id);
					$this->session->set_userdata('vibhag_id', $t->vibhag_id);
					$this->session->set_userdata('sambhag_id', $t->sambhag_id);
				}

				//Set logged_in to true
				$this->session->set_userdata(array('logged_in' => true));

				//Store Login Info
				$v['contact_id'] = $row->contact_id;
				$v['name'] = $row->first_name . ' ' . $row->last_name;
				$v['ip_addr'] = $this->input->ip_address();
				$this->db->insert('loginlog', $v);

				//Login was successful
				return true;
			}
		}
		else
		{
			$this->session->set_userdata('message', 'Your email address was not found in our database.');
			return false;
		}


	}
}

?>