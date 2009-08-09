<?php
class Profile extends Controller
{
    function Profile()
    {
        parent::Controller();
		if(!$this->session->userdata('logged_in'))
		{
			$this->session->set_userdata('message', 'Please login to access the system.');
			$this->session->set_userdata('redirect', $this->uri->uri_string());
			redirect('user');
		}

		//Check Permissions
		$perm = array('edit_profile', 'add_to_family', 'change_password');
		if(in_array($this->uri->segment(2), $perm)){
			if(!$this->permission->allow_profile_edit($this->uri->segment(3)))
			{
				$this->session->set_userdata('message', 'Your are not allowed to access the requested URL');
				redirect('profile/view/'.$this->session->userdata('contact_id'));
			}
		}

		$this->output->enable_profiler($this->config->item('debug'));
		$this->load->model('Profile_model');
		$this->load->library('layout');
		$this->layout->setLayout("layout_profile");
		//$this->load->scaffolding('swayamsevaks');

		$exception = array('search','del_ss');
		if(!in_array( $this->uri->segment(2), $exception))
		{
			$t1 = $this->db->select('shakha_id')->get_where('swayamsevaks', array('contact_id' => $this->uri->segment(3)))->row()->shakha_id;
			$rs = $this->db->get_where('shakhas', array('shakha_id' => $t1))->row();
			$this->session->set_userdata('bc_shakha', $rs->name);
			$this->session->set_userdata('bc_shakha_id', $rs->shakha_id);
			if(trim($rs->nagar_id) != '') {
				$this->session->set_userdata('bc_nagar_id', $rs->nagar_id);
				$this->session->set_userdata('bc_nagar', $this->Profile_model->getShortDesc($rs->nagar_id));
			}
			$this->session->set_userdata('bc_vibhag', $this->Profile_model->getShortDesc($rs->vibhag_id));
			$this->session->set_userdata('bc_vibhag_id', $rs->vibhag_id);
			$this->session->set_userdata('bc_sambhag', $this->Profile_model->getShortDesc($rs->sambhag_id));
			$this->session->set_userdata('bc_sambhag_id', $rs->sambhag_id);
		}

		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
    }

	function view($id)
	{
		$data['query'] = $this->db->get_where('swayamsevaks', array('contact_id' => $id));
		$data['shakha'] = $this->db->get_where('shakhas', array('shakha_id' => $data['query']->row()->shakha_id))->row();
		if ($data['shakha']->nagar_id != '') {
			$this->db->select('REF_CODE, short_desc');
			$data['nagar'] = $this->db->get_where('Ref_Code', array('DOM_ID' => 3, 'REF_CODE' => $data['shakha']->nagar_id))->row();
		}
		$this->db->select('REF_CODE, short_desc');
		$data['vibhag'] = $this->db->get_where('Ref_Code', array('DOM_ID' => 2, 'REF_CODE' => $data['shakha']->vibhag_id))->row();
		$this->db->select('REF_CODE, short_desc');
		$data['sambhag'] = $this->db->get_where('Ref_Code', array('DOM_ID' => 1, 'REF_CODE' => $data['shakha']->sambhag_id))->row();
		$this->db->select('contact_id, first_name, last_name, birth_year')->from('swayamsevaks')->where('household_id', $data['query']->row()->household_id);
		$data['households'] = $this->db->get();
		$data['resp'] = $this->Profile_model->getResponsibilities($id);
		$data['pageTitle'] = $data['query']->row()->first_name . ' ' . $data['query']->row()->last_name;
		$data['gata'] = $this->Profile_model->getGata($id);
		$data['ctype'] = $this->db->select('REF_CODE, short_desc')->get_where('Ref_Code', 'DOM_ID = 11')->result();
		if(strlen($data['query']->row()->gatanayak))
		{
			$this->db->select('contact_id,first_name,last_name');
			$this->db->where('contact_id', $data['query']->row()->gatanayak);
			$data['gatanayak'] = $this->db->get('swayamsevaks')->row();
		}
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
		$data['resp'] = $this->Profile_model->getResponsibilities($id);
		$data['gatanayak'] = $this->Profile_model->getGatanayaks($data['query']->row(0)->shakha_id);
		$data['shakha_name'] = $this->Profile_model->getShakhaName($data['query']->row(0)->shakha_id);
		$data['ctype'] = $this->db->select('REF_CODE, short_desc')->get_where('Ref_Code', 'DOM_ID = 11')->result();
		$data['ganas'] = $this->db->select('REF_CODE, short_desc')->get_where('Ref_Code', 'DOM_ID = 12')->result();
		//$data['shakhas'] = $this->db->get_where('shakhas', array('state' => $data['query']->row(0)->state))->result();
		/* Changed to show all shakhas in Edit Profile page */
		$data['shakhas'] = $this->db->order_by('state, name')->get('shakhas')->result();
		$this->layout->view('profile/edit-profile', $data);

	}

	function add_to_family($id)
	{
		if(isset($_POST['button']))
		{
			$d['household_id'] = $_POST['household_id'];
			if($_POST['household_id'] == '-999') {
				$this->db->select('MAX(household_id) as maxhid');
				$result = $this->db->get('swayamsevaks');
				$d['household_id'] = $result->row()->maxhid + 1;
			}
			$this->db->update('swayamsevaks', $d, array('contact_id' => $id));
			$this->session->set_userdata('message', 'Contact\'s family was successfully updated.&nbsp;');
			redirect('profile/view/' . $id);
		}
		$data['contact'] = $this->db->get_where('swayamsevaks', array('contact_id' => $id))->row();
		$this->db->order_by('first_name');
		$this->db->where('state', $data['contact']->state);
		$temp = $this->db->select('contact_id, household_id, first_name, last_name')->get('swayamsevaks');
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

	function change_password($id)
	{
		if($this->input->post('password'))
		{
			$oldpass = $this->db->select('password')->get_where('swayamsevaks', array('contact_id' => $id))->row()->password;
			if(trim($oldpass) != '' && sha1(trim($this->input->post('old_pass'))) != $oldpass)
			{
				$this->session->set_userdata('message', 'Your old password didn\'t match. Please try again.');
				redirect('profile/change_password/'.$id);
			}
			if(trim($this->input->post('pass1')) != trim($this->input->post('pass2')))
			{
				$this->session->set_userdata('message', 'Your new passwords did not match. Please try again.');
				redirect('profile/change_password/'.$id);
			}
			elseif(strlen(trim($this->input->post('pass1'))) < 5)
			{
				$this->session->set_userdata('message', 'Your passwords should be at least 5 characters long.');
				redirect('profile/change_password/'.$id);
			}
			else
			{
				//$ci = $this->input->post('contact_id');
				$this->db->where('contact_id', $id);
				$m['password'] = sha1(trim($this->input->post('pass1')));
				$m['passwordmd5'] = md5(trim($this->input->post('pass1')));
				$this->db->update('swayamsevaks', $m);
				$this->session->set_userdata('message', 'Your password has been updated.');
				redirect('profile/view/'.$id);
			}
		}
		else
		{
			//$rs = $rs->row();
			$j = $this->db->get_where('swayamsevaks', array('contact_id' => $id))->row();
			//$j = $j->row();
			$k['pageTitle'] = 'Change Password';
			$k['name'] = $j->first_name . ' ' . $j->last_name;
			//$k['contact_id'] = $j->contact_id;
			$this->layout->view('profile/change_pass', $k);
		}

	}
}

?>