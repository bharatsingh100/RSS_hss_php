<?php
class Help extends Controller
{
	function Help()
	{
		parent::Controller();
		//$this->output->enable_profiler(TRUE);
//		$this->load->model('Profile_model');
		$this->load->library('layout');
		$this->layout->setLayout("layout_help");
		//$this->load->scaffolding('swayamsevaks');

		/*$exception = array('search','del_ss');
		if(!in_array( $this->uri->segment(2), $exception))
		{
			$t1 = $this->db->select('shakha_id')->get_where('swayamsevaks', array('contact_id' => $this->uri->segment(3)))->row()->shakha_id;
			$rs = $this->db->get_where('shakhas', array('shakha_id' => $t1))->row();
			$this->session->set_userdata('bc_shakha', $rs->name);
			$this->session->set_userdata('bc_shakha_id', $rs->shakha_id);
//			$this->session->set_userdata('bc_nagar_id', $rs->nagar_id);
//			$this->session->set_userdata('bc_nagar_id', $rs->nagar_id);		
			$this->session->set_userdata('bc_vibhag', $this->Profile_model->getShortDesc($rs->vibhag_id));	
			$this->session->set_userdata('bc_vibhag_id', $rs->vibhag_id);
			$this->session->set_userdata('bc_sambhag', $this->Profile_model->getShortDesc($rs->sambhag_id));		
			$this->session->set_userdata('bc_sambhag_id', $rs->sambhag_id);
		}*/
	}
	
/*	function recent_updates()
	{
		if(!$this->session->userdata('logged_in'))
		{
			$this->session->set_userdata('message', 'Please login to access the system.');
			$this->session->set_userdata('redirect', $this->uri->uri_string());
			redirect('user');
		}
		$d['pageTitle'] = 'Recent Updates';
		$this->layout->view('admin/recent_updates', $d);
	}*/

	function index()
	{
		if (!$this->session->userdata('logged_in')) {
			$this->session->set_userdata('message', 'Please login to access the system.');
			$this->session->set_userdata('redirect', $this->uri->uri_string());
			redirect('user');
		}
		$d['pageTitle'] = 'Help';
		$this->layout->view('help/index', $d);
	}
		
/*	function contact()
	{
		if(isset($_POST['button']))
		{
			foreach($_POST as $key => $val)
				$d[$key] = $val;
			$headers = 'From: '.$d['name'].' <'.$d['email'].">\r\n";
			$message = "IP Address: http://www.melissadata.com/lookups/iplocation.asp?ipaddress=".$this->session->userdata('session_ip_address')."\r\n\n";
			$message .= $d['message'];
			$subject = 'Message from HSS CRM';
			if(mail('zzzabhi@gmail.com',$subject,$message,$headers))
				$this->session->set_userdata('message', 'Your E-mail has been sent to Sys. Admin. Thanks!&nbsp;');
			else
				$this->session->set_userdata('message', 'Your E-mail wasn\'t sent. Please contact us at crm_admin@hssusa.org&nbsp;');
			redirect('admin/contact');
		}
		$d['pageTitle'] = 'Contact Sys Admin';
		$this->layout->view('admin/contact', $d);
	}*/
	/*
	function view($id)
	{		
		//$this->db->where('contact_id', $id);
		//$data['query'] = $this->db->get('swayamsevaks');
		//$this->load->helper('url');
		$data['query'] = $this->db->get_where('swayamsevaks', array('contact_id' => $id));
		$data['shakha'] = $this->db->get_where('shakhas', array('shakha_id' => $data['query']->row()->shakha_id))->row();
		$this->db->select('contact_id, first_name, last_name')->from('swayamsevaks')->where('household_id', $data['query']->row()->household_id);
		$data['households'] = $this->db->get();
		$data['resp'] = $this->Profile_model->getResponsibilities($id);
		$data['pageTitle'] = $data['query']->row()->first_name . ' ' . $data['query']->row()->last_name;
		$data['gata'] = $this->Profile_model->getGata($id);
		$this->layout->view('profile/view-profile', $data);
	}
	
	function edit_profile($id)
	{
		$submit = $this->input->post('save');
		if($submit == "Update Information")
		{
			$this->Profile_model->insert_ss();
			$this->session->set_userdata('message', 'Profile was updated successfully.&nbsp;');
			redirect('profile/view/' . $_POST['contact_id']);
		}
			
		$data['query'] = $this->db->get_where('swayamsevaks', array('contact_id' => $id));
		$data['pageTitle'] = 'Edit Profile';
		$data['states'] = $this->Profile_model->getStates();
		$data['gatanayak'] = $this->Profile_model->getGatanayaks($data['query']->row(0)->shakha_id);
		$data['shakha_name'] = $this->Profile_model->getShakhaName($data['query']->row(0)->shakha_id);
		$this->layout->view('profile/edit-profile', $data);
		
	}
	
	function add_to_family($id)
	{
		if(isset($_POST['button']))
		{
			$d['household_id'] = $_POST['household_id'];
			$this->db->update('swayamsevaks', $d, array('contact_id' => $id));
			$this->session->set_userdata('message', 'Contact\'s family was successfully updated.&nbsp;');
			redirect('profile/view/' . $id);
		}
		$data['contact'] = $this->db->get_where('swayamsevaks', array('contact_id' => $id))->row();
		$this->db->order_by('household_id');
		$temp = $this->db->select('contact_id, household_id, first_name, last_name')->get_where('swayamsevaks', array('state' => $data['contact']->state));
		if($temp->num_rows())
			$data['households'] = $temp->result();
		$data['shakha_name'] = $this->Profile_model->getShakhaName($data['contact']->shakha_id);
		$data['pageTitle'] = 'Connect to a Family';
		$this->layout->view('profile/add_family.php', $data);
			
	}
		
	function del_ss()
	{
		//Get Shakha ID for redirect
		$ss = $this->db->select('shakha_id')->get_where('swayamsevaks', array('contact_id' => $_POST['contact_id']))->row();
		$shakha_id = $ss->shakha_id;
		
		//Remove the contact as Gatanayak for anyone
		$d['gatanayak'] = '';
		$this->db->update('swayamsevaks', $d, array('gatanayak' =>  $_POST['contact_id']));
		
		//Remove contact from moderatership of any email lists
		$e['mod1'] = '';
		$this->db->update('lists', $e, array('mod1' =>  $_POST['contact_id']));
		$f['mod2'] = '';		
		$this->db->update('lists', $f, array('mod2' =>  $_POST['contact_id']));
		$g['mod3'] = '';				
		$this->db->update('lists', $g, array('mod3' =>  $_POST['contact_id']));	
		
		//Delete the contact			
		$this->db->delete('swayamsevaks', array('contact_id' =>  $_POST['contact_id']));
		
		//Delete all the responsibilities of the contact
		$this->db->delete('responsibilities', array('swayamsevak_id' =>  $_POST['contact_id']));
		
		$this->session->set_userdata('message', 'The Contact was successfully deleted');
		
		//Redirect to contact's shakha list page
		redirect('shakha/browse/'.$shakha_id.'/name');
	}
	
	function search($state = '', $term = '') 
	{
		if($term == '' && $state == '' && $_POST['search'] != '' && $_POST['state'] != '')
		{
			//$pieces = explode(" ", $_POST['search']);
			//$search_str = implode("_", $pieces);
			redirect('profile/search/'.$_POST['state'].'/'.$_POST['search']);
		}
		
		if($term == 'Search...' || strlen($term) <= 3)
		{
			$this->session->set_userdata('message', 'Please enter a meaningful search term, with at least 4 characters.');
			redirect('profile/view/1');
		}
		$this->session->set_userdata('sh_state', $state);
		//$pieces = explode(" ", $term);
		//$search_str = implode("_", $pieces);
		//if($state == '') redirect('profile/search'.$state.'/'.$search_str); //More than 1 term?
		$this->load->library('pagination');
		
		$config['base_url'] = base_url()."profile/search/$state/$term/";
    		$config['total_rows'] = $this->db->count_all('swayamsevaks');
    		$config['per_page'] = '20';
    		$config['full_tag_open'] = '<p>';
    		$config['full_tag_close'] = '</p>';
		$config['uri_segment'] = 5;
//		$config['post_url'] = $data['order'].'/'.$data['orderDir'];
		$this->pagination->initialize($config);
		$data['results'] = $this->Profile_model->search($config['per_page'], $this->uri->segment(5), $state, $term);
		//$data['results'] = $this->Shakha_model->get_swayamsevaks($config['per_page'], $this->uri->segment(5), $id, $order);
		$data['pageTitle'] = 'Search Results';
		//$data['shakha_name'] = $this->Shakha_model->getShakhaName($id);
	   	//$this->load->library('table');
    		//$this->table->set_heading('Name', 'City', 'Phone', 'E-mail', 'Gana', 'Gatanayak');
		$this->layout->view('profile/search', $data);
	}*/
}

?>