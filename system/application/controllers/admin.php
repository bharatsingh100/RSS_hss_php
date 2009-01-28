<?php
class Admin extends Controller
{
    function Admin()
    {
        parent::Controller();
		$this->load->library('layout', 'layout_admin');
		$this->output->enable_profiler($this->config->item('debug'));

    }

	function recent_updates()
	{
		if(!$this->session->userdata('logged_in'))
		{
			$this->session->set_userdata('message', 'Please login to access the system.');
			$this->session->set_userdata('redirect', $this->uri->uri_string());
			redirect('user');
		}
		$d['pageTitle'] = 'Recent Updates';
		$this->layout->view('admin/recent_updates', $d);
	}

	function info()
	{
		if(!$this->session->userdata('logged_in'))
		{
			$this->session->set_userdata('message', 'Please login to access the system.');
			$this->session->set_userdata('redirect', $this->uri->uri_string());
			redirect('user');
		}
		$d['pageTitle'] = 'Recent Updates';
		$this->layout->view('admin/general_info', $d);
	}

	function contact()
	{
		if(isset($_POST['button']))
		{
			foreach($_POST as $key => $val)
				$d[$key] = $val;
			$headers = 'From: '.$d['name'].' <'.$d['email'].">\r\n";
			$message = "IP Address: http://www.melissadata.com/lookups/iplocation.asp?ipaddress=".$this->session->userdata('session_ip_address')."\r\n\n";
			$message .= $d['message'];
			$subject = 'Message from HSS CRM';
			if(mail('crm_admin@hssusa.org',$subject,$message,$headers)){
				$this->session->set_userdata('message', 'Your E-mail has been sent to System Admin. Thanks!&nbsp;');
				unset($d['button']);
				$d['ip_add'] = $this->session->userdata('session_ip_address');
				$d['status'] = 'Pending';
				$this->db->insert('tickets',$d);

				$headers = 'From: HSS Sampark System <crm_admin@hssusa.org>'."\r\n";
				$message = "We have received your message and we will reply to you shortly. If you do hear back from us soon. Please contact us at crm_admin@hssusa.org\r\n\r\n";
				$message .= "Your Message:\r\n".$d['message'];
				$message .= "\r\n\r\n--\r\nHSS Sampark System Team\r\n";
				$subject = 'Your message to HSS Sampark System Admin';
				mail($d['email'],$subject,$message,$headers);
			}
			else
				$this->session->set_userdata('message', 'Your E-mail wasn\'t sent. Please contact us at crm_admin@hssusa.org&nbsp;');
			redirect('admin/contact');
		}
		$d['pageTitle'] = 'Contact System Admin';
		$this->layout->view('admin/contact', $d);
	}
}

?>