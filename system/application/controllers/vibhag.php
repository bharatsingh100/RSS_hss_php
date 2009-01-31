<?php
class Vibhag extends Controller
{
    function Vibhag()
    {
        parent::Controller();
		if(!$this->session->userdata('logged_in'))
		{
			$this->session->set_userdata('message', 'Please login to access the system.');
			$this->session->set_userdata('redirect', $this->uri->uri_string());
			redirect('user');
		}

		//Check Permissions
		$perm = array('browse', 'add_shakha','responsibilities','statistics','email_lists','create_list');
		if(in_array($this->uri->segment(2), $perm)){
			if(!$this->permission->is_vibhag_kk($this->uri->segment(3)))
			{
				$this->session->set_userdata('message', 'Your are not allowed to access the requested URL');
				redirect('vibhag/view/'.$this->uri->segment(3));
			}
		}

		$this->output->enable_profiler($this->config->item('debug'));
		$this->load->model('Vibhag_model');
		$this->load->library('layout', 'layout_vibhag');

		//$rs = $this->db->getwhere('shakhas', array('shakha_id' => $this->uri->segment(3)))->row();
		$id = $this->uri->segment(3);
		$this->session->set_userdata('bc_vibhag', $this->Vibhag_model->getShortDesc($id));
		$this->session->set_userdata('bc_vibhag_id', $id);
		$sid = str_split($id, 2);
		$this->session->set_userdata('bc_sambhag', $this->Vibhag_model->getShortDesc($sid[0]));
		$this->session->set_userdata('bc_sambhag_id', $sid[0]);

		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');

    }

	function email_lists($id)
	{
        $this->db->select('id,address,level_id,status,style,size,mod1,mod2,mod3');
        //$this->db->where('level_id = '.$id.' AND status = \'Active\' OR status = \'Creating'\');
        $d['lists'] = $this->db->getwhere('lists', array('level_id' => $id))->result_array();
        foreach($d['lists'] as &$list)
        {
			$list['address'] .= '@hssusa.org';
            if($list['mod1'])
            {
                $this->db->select('contact_id,first_name,last_name');
                $t = $this->db->getwhere('swayamsevaks', array('contact_id' => $list['mod1']))->row();
                $list['mod1'] = anchor('profile/view/'.$t->contact_id, $t->first_name.' '.$t->last_name) . '<br \>';
            }
            if($list['mod2'])
            {
                $this->db->select('contact_id,first_name,last_name');
                $t = $this->db->getwhere('swayamsevaks', array('contact_id' => $list['mod2']))->row();
                $list['mod2'] = anchor('profile/view/'.$t->contact_id,$t->first_name.' '.$t->last_name) . '<br \>';
            }
			else $list['mod2'] = '';
            if($list['mod3'])
            {
                $this->db->select('contact_id,first_name,last_name');
                $t = $this->db->getwhere('swayamsevaks', array('contact_id' => $list['mod3']))->row();
                $list['mod3'] = anchor('profile/view/'.$t->contact_id,$t->first_name.' '.$t->last_name) . '<br \>';
            }
			else $list['mod3'] = '';
            $list['moderators'] = $list['mod1'] . $list['mod2'] . $list['mod3'];
            $list['details'] = anchor('vibhag/edit_list/'.$list['level_id'].'/'.$list['id'], 'Details/Edit');
			$list['style'] = ($list['style']) ? 'Un-Moderated' : 'Moderated';
            unset($list['id']);
            unset($list['mod1']);
            unset($list['mod2']);
            unset($list['mod3']);
	     	unset($list['level_id']);
        }
        $d['vibhag'] = $this->Vibhag_model->getVibhagInfo($id);
		$d['pageTitle'] = 'Email Lists';
        $this->load->library('table');
        $this->table->set_heading('List Name', 'Status', 'Style', 'Size', 'Moderators', 'Details');
		//TODO: Create view for View Lists
		$this->layout->view('vibhag/email_lists', $d);
	}

	function edit_list($id, $list_id, $error = '')
    {
		//if($error != '')
		//	$c['data'] = $error;
		if(isset($_POST['button']))
		{
			if(!isset($_POST['members']))
			{
				$this->session->set_userdata('message', 'Please select at least 1 group for E-mail list members.');
				redirect('vibhag/edit_list/'.$id.'/'.$list_id);
			}
			if(!isset($_POST['mod_pass']) || strlen($_POST['mod_pass']) < 5)
			{
				$this->session->set_userdata('message', 'Your password should be at least 5 characters long.');
				redirect('vibhag/edit_list/'.$id.'/'.$list_id);
			}

			foreach($_POST as $key => $val)
				$d[$key] = $val;

			unset($d['button']);
			if(!isset($d['mod3']) || $d['mod3'] == '') $d['mod3'] = 0;
			if(!isset($d['mod2']) || $d['mod2'] == '') $d['mod2'] = 0;
			$d['members'] = serialize($d['members']);
			$this->db->update('lists', $d, array('id' => $list_id));

			$this->session->set_userdata('message', 'Your list was updated successfully.');
			redirect('vibhag/email_lists/'.$id);

		}


		$c['lists'] = $this->db->getwhere('lists', array('id' => $list_id))->row();
		$c['vibhag'] = $this->Vibhag_model->getVibhagInfo($c['lists']->level_id);
		$c['pageTitle'] = 'Edit E-mail list';
		$this->layout->view('vibhag/edit_list', $c);
    }

	function create_list($id, $error = '')
	{
		if($error != '')
			$c['d'] = $error;
		$c['vibhag'] = $this->Vibhag_model->getVibhagInfo($id);
		$c['pageTitle'] = 'Create new e-mail list';
		$this->layout->view('vibhag/create_list', $c);
	}

	function del_list($id, $list_id)
	{
		if(isset($_POST['button2']))
		{
			$d['status'] = 'Deleting';
			$this->db->update('lists', $d, array('id' => $list_id));
			$this->session->set_userdata('message', 'Your list was queued for deleting');
			redirect('vibhag/email_lists/'.$id);
		}
		else redirect('vibhag/email_lists/'.$id);
	}

	function add_email_list()
	{
		$ers = false;
		$error = array();

		foreach($_POST as $key => &$value){
			if($key == 'members'){
				foreach($value as $v)
					$error['members'][] = $v; }
			$error[$key] = $value;}

		if(!isset($error['address']) || $error['address'] == '')
		{
			$error['msg'][] = 'You must enter the List Name';
			$ers = true;
		}
		if(!isset($error['mod_pass']) || $error['mod_pass'] == '')
		{
			$error['msg'][] = 'Your must enter a password.';
			$ers = true;
		}
		if(!is_array($error['members']) || !count($error['members']))
		//Count returns 0 is variable is not set or array is empty
		{
			$error['msg'][] = 'You must select at least one member for your list';
			$ers = true;
		}
        	if($this->db->getwhere('lists', array('address' => $error['address']))->num_rows())
        	{
            		$error['msg'][] = 'The list name you selected, already exists. Choose another one.';
            		$error['address'] = '';
            		$ers = true;
        	}
		if($ers)
		{
			$this->session->set_userdata('message', 'Please correct the errors.');
			$this->create_list($this->input->post('level_id'), $error);
		}
		else
		{
			$this->Vibhag_model->add_email_list();
			$this->session->set_userdata('message', 'Your list '.$this->input->post('address').'@hssusa.org has been requested.');
			redirect('vibhag/email_lists/'.$this->input->post('level_id'));
		}

		//TODO: Add hidden shakha id parameter to from page
		//$d['level'] = 'sh';
		//level = 0-Shakha 1-vibhag 2-sambhag
		//type = 0 Moderated | 1 Unmoderated
		//members = 0 All Swayamsevaks | 1 Bala swayamsevaks | 2 Kishor Swayamsevaks | 3 Yuva Swayamsevaks
		// 4 Tarun Swayamsevaks | 5 Praudh swayamsevaks | 6 All Karyakartas
	}

	function statistics($id)
	{
		/*
	    $yr = date('Y');
		$ag['shishu'] = $yr - 6;
		$ag['bala'] = $yr - 12;
		$ag['kishor'] = $yr - 19;
		$ag['yuva'] = $yr - 25;
		$ag['tarun'] = $yr - 50;
		$v['shakha'] = $this->db->getwhere('shakhas', array('shakha_id', $id))->row();
		$v['families'] = $this->db->select('DISTINCT household_id')->getwhere('swayamsevaks', array('shakha_id' => $id))->num_rows();
		$v['swayamsevaks'] = $this->db->getwhere('swayamsevaks', array('shakha_id' => $id))->num_rows();
		$v['shishu'] = $this->db->getwhere('swayamsevaks', 'birth_year > '. $ag['shishu'].' AND shakha_id =' . $id)->num_rows();
		$v['bala'] = $this->db->getwhere('swayamsevaks', 'birth_year BETWEEN '.$ag['bala'].' AND '. $ag['shishu'].' AND shakha_id =' . $id)->num_rows();
		$v['kishor'] = $this->db->getwhere('swayamsevaks', 'birth_year BETWEEN '.$ag['kishor'].' AND '. $ag['bala'].' AND shakha_id =' . $id)->num_rows();
		$v['yuva'] = $this->db->getwhere('swayamsevaks', 'birth_year BETWEEN '.$ag['yuva'].' AND '. $ag['kishor'].' AND shakha_id =' . $id)->num_rows();
		$v['tarun'] = $this->db->getwhere('swayamsevaks', 'birth_year BETWEEN '.$ag['tarun'].' AND '. $ag['yuva'].' AND shakha_id =' . $id)->num_rows();
		$v['phone'] = $this->db->getwhere('swayamsevaks', 'ph_mobile != \'\' OR ph_home != \'\' OR ph_work != \'\' AND shakha_id =' . $id)->num_rows();
		$v['email'] = $this->db->getwhere('swayamsevaks', 'email != \'\' AND email_status = \'Active\' AND shakha_id =' . $id)->num_rows();
		$v['email_unactive'] = $this->db->getwhere('swayamsevaks', 'email != \'\' AND email_status != \'Active\' AND shakha_id =' . $id)->num_rows();
		$v['sankhya'] = $this->db->getwhere('sankhyas', array('shakha_id' => $id))->result();
		$v['pageTitle'] = 'Vibhag Statistics'; */
        $data['vibhag'] = $this->Vibhag_model->getVibhagInfo($id);
	    $data['pageTitle'] = $data['vibhag']->name . ' Vibhag Statistics';
		$data['shakhas'] = $this->Vibhag_model->getVibhagShakhaStats($id);
		$data['vibhag']->sankhya = $this->Vibhag_model->getVibhagSankhya($id, $data['shakhas']);
		$data['vibhag']->contacts = $this->Vibhag_model->getVibhagContacts($id);

		$this->layout->view('vibhag/statistics', $data);
	}

	function del_responsibility($vibhag_id, $ss_id, $resp_id)
	{
		$this->db->where('vibhag_id', $vibhag_id);
		$this->db->where('responsibility', $resp_id);
		$this->db->where('swayamsevak_id', $ss_id);
		$this->db->delete('responsibilities');
		$this->session->set_userdata('message', 'Responsibility deleted');
		redirect('vibhag/responsibilities/' . $vibhag_id);
	}

	function responsibilities($id)
	{
		$submit = $this->input->post('button');
		if($submit != '')
		{
			if($_POST['name'] == 0 || $_POST['resp'] == 0)
				$this->session->set_userdata('message', 'Please fill the form with required information');
			else
			{
				if($this->Vibhag_model->add_responsibility())
					$this->session->set_userdata('message', 'Responsibility added sucessfully');
			}
		}
		//$this->load->library('ajax');
		$data['shakha'] = $id;
		$data['row'] = $this->Vibhag_model->getVibhagInfo($id);
		$data['resp'] = $this->Vibhag_model->getRefCodes(4)->result();
		$data['pageTitle'] = $data['row']->name;
		$data['names'] = $this->Vibhag_model->get_swayamsevaks(18000000, 0, $id, 'name')->result();
		$this->layout->view('vibhag/add_responsibility', $data);
	}


	function csv_out1($id)
	{
		$this->load->dbutil();
		$shakha_ids = $this->Vibhag_model->get_shakhas($id);
		$shakha_ids = '('.implode(',',$shakha_ids).')';
		$this->db->select('contact_id, household_id, shakha_id, contact_type, first_name, last_name, gender, birth_year, company, position, email, email_status, ph_mobile, ph_home, ph_work, street_add1, street_add2, city, state, zip, ssv_completed, notes');
		$data['query'] = $this->db->getwhere('swayamsevaks', 'shakha_id IN ' . $shakha_ids);

		$this->output->set_header("Content-type: application/vnd.ms-excel");
		$this->output->set_header("Content-disposition: csv; filename=". date("M-d_H-i") .".csv");
		$this->load->view('vibhag/csv', $data);
	}

	function csv_out($id)
	{
		$this->load->dbutil();

		//Get list of Shakhas in the Vibhag
		$shakha_ids = $this->Vibhag_model->get_shakhas($id);
		$shakha_ids = '('.implode(',',$shakha_ids).')';

		//Get the database of Swayamsevaks of this Vibhag
		$this->db->select('swayamsevaks.contact_id, swayamsevaks.household_id, shakhas.name as shakhka, Ref_Code.short_desc as contact_type, swayamsevaks.first_name, swayamsevaks.last_name, swayamsevaks.gender, birth_year, swayamsevaks.company, swayamsevaks.position, swayamsevaks.email, swayamsevaks.email_status, swayamsevaks.ph_mobile, swayamsevaks.ph_home, swayamsevaks.ph_work, swayamsevaks.street_add1, swayamsevaks.street_add2, swayamsevaks.city, swayamsevaks.state, swayamsevaks.zip, swayamsevaks.ssv_completed, swayamsevaks.notes');
		$this->db->from('swayamsevaks, shakhas, Ref_Code');
		$this->db->where('swayamsevaks.shakha_id IN ' . $shakha_ids. ' AND shakhas.shakha_id = swayamsevaks.shakha_id AND Ref_Code.DOM_ID = 11 AND Ref_Code.REF_CODE = swayamsevaks.contact_type');
		$this->db->orderby('shakhas.name, swayamsevaks.household_id');
		$data['query'] = $this->db->get();

		$this->output->set_header("Content-type: application/vnd.ms-excel");
		$this->output->set_header("Content-disposition: csv; filename=". date("M-d_H-i") .".csv");
		$this->load->view('vibhag/csv', $data);
	}

	function browse($id = '', $order = 'name') {
		//if($id == '') $id = $this->session->userdata('vibhag_id');

		$shakha_ids = $this->Vibhag_model->get_shakhas($id);
		$shakha_ids = '('.implode(',',$shakha_ids).')';

		$this->load->library('pagination');
		$config['base_url'] = base_url()."vibhag/browse/$id/$order/";
    	$config['total_rows'] = $this->db->getwhere('swayamsevaks', 'shakha_id IN ' . $shakha_ids)->num_rows();//$this->db->count_all('swayamsevaks');
    	$config['per_page'] = '35';
    	$config['full_tag_open'] = '<p>';
    	$config['full_tag_close'] = '</p>';
		$config['uri_segment'] = 5;
//		$config['post_url'] = $data['order'].'/'.$data['orderDir'];
		$this->pagination->initialize($config);

		$data['results'] = $this->Vibhag_model->get_swayamsevaks($config['per_page'], $this->uri->segment(5), $id, $order);
		$data['pageTitle'] = 'Browse Swayamsevaks';
		$data['vibhag_name'] = $this->Vibhag_model->getShortDesc($id);

		$this->load->library('table');
    		$this->table->set_heading('Name', 'City', 'Phone', 'E-mail', 'Gana', 'Shakha');
		$this->layout->view('vibhag/list_ss', $data);
	}

	function view($id)
	{

		$data['row'] = $this->Vibhag_model->getVibhagInfo($id);
		$data['pageTitle'] = $data['row']->name;
		$this->layout->view('vibhag/view-vibhag', $data);
	}

	function add_shakha($id)
	{
		if(isset($_POST['save']))
		{
			$this->Vibhag_model->insert_shakha();
			$this->session->set_userdata('message', 'Shakha was successfully added.&nbsp;');
			redirect('vibhag/view/'.$id);
		}
		$d['row'] = $this->Vibhag_model->getVibhagInfo($id);
		$d['states'] = $this->Vibhag_model->getStates();
		$d['pageTitle'] = 'Add Shakha';
		$d['row'] = $this->Vibhag_model->getVibhagInfo($id);
		$this->layout->view('vibhag/add-shakha.php', $d);
	}
}

?>