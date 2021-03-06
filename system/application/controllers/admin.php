<?php
class Admin extends Controller
{
	function Admin()
	{
		parent::Controller();
		$this->load->library('layout');
		$this->layout->setLayout("layout_admin");
		$this->output->enable_profiler($this->config->item('debug'));
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');

	}

	function recent_updates()
	{
		if (!$this->session->userdata('logged_in')) {
			$this->session->set_userdata('message', 'Please login to access the system.');
			$this->session->set_userdata('redirect', $this->uri->uri_string());
			redirect('user');
		}
		$d['pageTitle'] = 'Recent Updates';
		$this->layout->view('admin/recent_updates', $d);
	}

	function info()
	{
		if (!$this->session->userdata('logged_in')) {
			$this->session->set_userdata('message', 'Please login to access the system.');
			$this->session->set_userdata('redirect', $this->uri->uri_string());
			redirect('user');
		}
		$d['pageTitle'] = 'Recent Updates';
		$this->layout->view('admin/general_info', $d);
	}

	function contact()
	{
		if (isset($_POST['button'])) {
			foreach ($_POST as $key => $val)
				$d[$key] = $val;
			$headers = 'From: ' . $d['name'] . ' <' . $d['email'] . ">\r\n";
			$message = "IP Address: http://www.melissadata.com/lookups/iplocation.asp?ipaddress=" . $this->session->userdata('session_ip_address') . "\r\n\n";
			$message .= $d['message'];
			$subject = 'Message from HSS CRM';
			if (mail('abhi@hssus.org', $subject, $message, $headers)) {
				$this->session->set_userdata('message', 'Your E-mail has been sent to System Admin. Thanks!&nbsp;');
				unset($d['button']);
				$d['ip_add'] = $this->session->userdata('session_ip_address');
				$d['status'] = 'Pending';
				$this->db->insert('tickets', $d);

				$headers = 'From: HSS Sampark System <abhi@hssus.org>' . "\r\n";
				$message = "We have received your message and we will reply to you shortly. If you do hear back from us soon. Please contact us at crm_admin@hssusa.org\r\n\r\n";
				$message .= "Your Message:\r\n" . $d['message'];
				$message .= "\r\n\r\n--\r\nHSS Sampark System Team\r\n";
				$subject = 'Your message to HSS Sampark System Admin';
				mail($d['email'], $subject, $message, $headers);
			} else
				$this->session->set_userdata('message', 'Your E-mail wasn\'t sent. Please contact us at abhi@hssus.org&nbsp;');
			redirect('admin/contact');
		}
		$d['pageTitle'] = 'Contact System Admin';
		$this->layout->view('admin/contact', $d);
	}

	function hssdocs()
	{
		if (!$this->session->userdata('logged_in')) {
			$this->session->set_userdata('message', 'Please login to access the system.');
			$this->session->set_userdata('redirect', $this->uri->uri_string());
			redirect('user');
		}

		$d['pageTitle'] = 'Share Documents';
		$this->layout->view('admin/hssdocs', $d);
	}

	function faq()
	{
		if (!$this->session->userdata('logged_in')) {
			$this->session->set_userdata('message', 'Please login to access the system.');
			$this->session->set_userdata('redirect', $this->uri->uri_string());
			redirect('user');
		}

		$d['pageTitle'] = 'FAQ';
		$this->layout->view('admin/faq', $d);
	}

	function maps()
	{
		if (!$this->session->userdata('logged_in')) {
			$this->session->set_userdata('message', 'Please login to access the system.');
			$this->session->set_userdata('redirect', $this->uri->uri_string());
			redirect('user');
		}

		$d['pageTitle'] = 'Maps';
		$this->layout->view('admin/maps', $d);
	}
}

?>