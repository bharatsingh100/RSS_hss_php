<?php
class Nagar extends Controller
{
    function Nagar()
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
			if(!$this->permission->is_nagar_kk($this->uri->segment(3)))
			{
				$this->session->set_userdata('message', 'Your are not allowed to access the requested URL');
				redirect('nagar/view/'.$this->uri->segment(3));
			}
		}
		
		$this->output->enable_profiler($this->config->item('debug'));
		$this->load->model('Nagar_model');
		$this->load->library('layout');
		$this->layout->setLayout("layout_nagar");

		//Set Things for Breadcrumbs
		$id = $this->uri->segment(3);
		$this->session->set_userdata('bc_nagar', $this->Nagar_model->getShortDesc($id));	
		$this->session->set_userdata('bc_nagar_id', $id);
		
		$sid = substr($id, 0, 4);
		$this->session->set_userdata('bc_vibhag', $this->Nagar_model->getShortDesc($sid));	
		$this->session->set_userdata('bc_vibhag_id', $sid);
		
		$sid = substr($id, 0, 2);
		$this->session->set_userdata('bc_sambhag', $this->Nagar_model->getShortDesc($sid));			
		$this->session->set_userdata('bc_sambhag_id', $sid);
		
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
			
    }
	
	function email_lists($id)
	{
        $this->db->select('id,address,level_id,status,style,size,mod1,mod2,mod3');
        //$this->db->where('level_id = '.$id.' AND status = \'Active\' OR status = \'Creating'\');
        $d['lists'] = $this->db->get_where('lists', array('level_id' => $id))->result_array();
        foreach($d['lists'] as &$list)
        {
			$list['address'] .= '@hssusa.org';
            if($list['mod1'])
            {
                $this->db->select('contact_id,first_name,last_name');
                $t = $this->db->get_where('swayamsevaks', array('contact_id' => $list['mod1']))->row();
                $list['mod1'] = anchor('profile/view/'.$t->contact_id, $t->first_name.' '.$t->last_name) . '<br \>';
            }
            if($list['mod2'])
            {
                $this->db->select('contact_id,first_name,last_name');
                $t = $this->db->get_where('swayamsevaks', array('contact_id' => $list['mod2']))->row();
                $list['mod2'] = anchor('profile/view/'.$t->contact_id,$t->first_name.' '.$t->last_name) . '<br \>';
            }
			else $list['mod2'] = '';
            if($list['mod3'])
            {
                $this->db->select('contact_id,first_name,last_name');
                $t = $this->db->get_where('swayamsevaks', array('contact_id' => $list['mod3']))->row();
                $list['mod3'] = anchor('profile/view/'.$t->contact_id,$t->first_name.' '.$t->last_name) . '<br \>';
            }
			else $list['mod3'] = '';
            $list['moderators'] = $list['mod1'] . $list['mod2'] . $list['mod3'];
            $list['details'] = ($list['status'] == 'Active') ? anchor('nagar/edit_list/'.$list['level_id'].'/'.$list['id'], 'Details/Edit') : '';
			$list['style'] = ($list['style']) ? 'Un-Moderated' : 'Moderated';
            unset($list['id']);
            unset($list['mod1']);
            unset($list['mod2']);
            unset($list['mod3']);
	     	unset($list['level_id']);
        }
        $d['nagar'] = $this->Nagar_model->getNagarInfo($id);
		$d['pageTitle'] = 'Email Lists';
        $this->load->library('table');
        $this->table->set_heading('List Name', 'Status', 'Style', 'Size', 'Moderators', 'Details');
		//TODO: Create view for View Lists
		$this->layout->view('nagar/email_lists', $d);
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
				redirect('nagar/edit_list/'.$id.'/'.$list_id);
			}
			if(!isset($_POST['mod_pass']) || strlen($_POST['mod_pass']) < 5)
			{
				$this->session->set_userdata('message', 'Your password should be at least 5 characters long.');
				redirect('nagar/edit_list/'.$id.'/'.$list_id);
			}
			
			foreach($_POST as $key => $val)
				$d[$key] = $val;
			
			unset($d['button']);
			if(!isset($d['mod3']) || $d['mod3'] == '') $d['mod3'] = 0;
			if(!isset($d['mod2']) || $d['mod2'] == '') $d['mod2'] = 0;
			$d['members'] = serialize($d['members']);
			$this->db->update('lists', $d, array('id' => $list_id));
			
			$this->session->set_userdata('message', 'Your list was updated successfully.');
			redirect('nagar/email_lists/'.$id);
			
		}

        	
		$c['lists'] = $this->db->get_where('lists', array('id' => $list_id))->row();
		$c['nagar'] = $this->Nagar_model->getNagarInfo($c['lists']->level_id);
		$c['pageTitle'] = 'Edit E-mail list';
		$this->layout->view('nagar/edit_list', $c);	
    }
	
	function create_list($id, $error = '')
	{
		if($error != '')
			$c['d'] = $error;
		$c['nagar'] = $this->Nagar_model->getNagarInfo($id);
		$c['pageTitle'] = 'Create new e-mail list';
		$this->layout->view('nagar/create_list', $c);
	}
	
	function del_list($id, $list_id)
	{
		if(isset($_POST['button2']))
		{
			$d['status'] = 'Deleting';
			$this->db->update('lists', $d, array('id' => $list_id));
			$this->session->set_userdata('message', 'Your list was queued for deleting');
			redirect('nagar/email_lists/'.$id);
		}
		else redirect('nagar/email_lists/'.$id);
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
        	if($this->db->get_where('lists', array('address' => $error['address']))->num_rows())
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
			$this->Nagar_model->add_email_list();
			$this->session->set_userdata('message', 'Your list '.$this->input->post('address').'@hssusa.org has been requested.');
			redirect('nagar/email_lists/'.$this->input->post('level_id'));
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
		$yr = date('Y');
		$ag['shishu'] = $yr - 6;
		$ag['bala'] = $yr - 12;
		$ag['kishor'] = $yr - 19;
		$ag['yuva'] = $yr - 25;
		$ag['tarun'] = $yr - 50;
		$v['shakha'] = $this->db->get_where('shakhas', array('shakha_id', $id))->row();
		$v['families'] = $this->db->select('DISTINCT household_id', FALSE)->get_where('swayamsevaks', array('shakha_id' => $id))->num_rows();
		$v['swayamsevaks'] = $this->db->get_where('swayamsevaks', array('shakha_id' => $id))->num_rows();
		$v['shishu'] = $this->db->get_where('swayamsevaks', 'birth_year > '. $ag['shishu'].' AND shakha_id =' . $id)->num_rows();
		$v['bala'] = $this->db->get_where('swayamsevaks', 'birth_year BETWEEN '.$ag['bala'].' AND '. $ag['shishu'].' AND shakha_id =' . $id)->num_rows();
		$v['kishor'] = $this->db->get_where('swayamsevaks', 'birth_year BETWEEN '.$ag['kishor'].' AND '. $ag['bala'].' AND shakha_id =' . $id)->num_rows();
		$v['yuva'] = $this->db->get_where('swayamsevaks', 'birth_year BETWEEN '.$ag['yuva'].' AND '. $ag['kishor'].' AND shakha_id =' . $id)->num_rows();
		$v['tarun'] = $this->db->get_where('swayamsevaks', 'birth_year BETWEEN '.$ag['tarun'].' AND '. $ag['yuva'].' AND shakha_id =' . $id)->num_rows();
		$v['phone'] = $this->db->get_where('swayamsevaks', 'ph_mobile != \'\' OR ph_home != \'\' OR ph_work != \'\' AND shakha_id =' . $id)->num_rows();
		$v['email'] = $this->db->get_where('swayamsevaks', 'email != \'\' AND email_status = \'Active\' AND shakha_id =' . $id)->num_rows();
		$v['email_unactive'] = $this->db->get_where('swayamsevaks', 'email != \'\' AND email_status != \'Active\' AND shakha_id =' . $id)->num_rows();
		$v['sankhya'] = $this->db->get_where('sankhyas', array('shakha_id' => $id))->result();
		$v['pageTitle'] = 'Shakha Statistics';
		$this->layout->view('shakha/statistics', $v);
	}

	function del_responsibility($nagar_id, $ss_id, $resp_id)
	{
		$this->db->where('nagar_id', $nagar_id);
		$this->db->where('responsibility', $resp_id);
		$this->db->where('swayamsevak_id', $ss_id);
		$this->db->delete('responsibilities');
		$this->session->set_userdata('message', 'Responsibility deleted');
		redirect('nagar/responsibilities/' . $nagar_id);
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
				if($this->Nagar_model->add_responsibility())
					$this->session->set_userdata('message', 'Responsibility added sucessfully');
			}
		}
		//$this->load->library('ajax');
		$data['shakha'] = $id;
		$data['row'] = $this->Nagar_model->getNagarInfo($id);
		$data['resp'] = $this->Nagar_model->getRefCodes(4)->result();
		$data['pageTitle'] = $data['row']->name;
		
		//List Swayamsevaks from Nagar for Adding Responsibilities
		$nagar_shakhas = $this->Nagar_model->get_swayamsevaks(18000000, 0, $id, 'name');
		$data['names'] = '';
		if(!empty($nagar_shakhas))
			$data['names'] = $nagar_shakhas->result();
		
		$this->layout->view('nagar/add_responsibility', $data);
	}

		
	function csv_out1($id)
	{
		$this->load->dbutil();
		$shakha_ids = $this->Nagar_model->get_shakhas($id);
		$shakha_ids = '('.implode(',',$shakha_ids).')';
		$this->db->select('contact_id, household_id, shakha_id, contact_type, first_name, last_name, gender, birth_year, company, position, email, email_status, ph_mobile, ph_home, ph_work, street_add1, street_add2, city, state, zip, ssv_completed, notes');
		$data['query'] = $this->db->get_where('swayamsevaks', 'shakha_id IN ' . $shakha_ids);
		
		$this->output->set_header("Content-type: application/vnd.ms-excel");
		$this->output->set_header("Content-disposition: csv; filename=". date("M-d_H-i") .".csv");
		$this->load->view('nagar/csv', $data);
	}
	
	function csv_out($id)
	{
		$this->load->dbutil();
		
		//Get list of Shakhas in the Nagar
		$shakha_ids = $this->Nagar_model->get_shakhas($id);
		$shakha_ids = '('.implode(',',$shakha_ids).')';
		
		//Get the database of Swayamsevaks of this Nagar
		$this->db->select('swayamsevaks.contact_id, swayamsevaks.household_id, shakhas.name as shakhka, Ref_Code.short_desc as contact_type, swayamsevaks.first_name, swayamsevaks.last_name, swayamsevaks.gender, birth_year, swayamsevaks.company, swayamsevaks.position, swayamsevaks.email, swayamsevaks.email_status, swayamsevaks.ph_mobile, swayamsevaks.ph_home, swayamsevaks.ph_work, swayamsevaks.street_add1, swayamsevaks.street_add2, swayamsevaks.city, swayamsevaks.state, swayamsevaks.zip, swayamsevaks.ssv_completed, swayamsevaks.notes');
		$this->db->from('swayamsevaks, shakhas, Ref_Code');
		$this->db->where('swayamsevaks.shakha_id IN ' . $shakha_ids. ' AND shakhas.shakha_id = swayamsevaks.shakha_id AND Ref_Code.DOM_ID = 11 AND Ref_Code.REF_CODE = swayamsevaks.contact_type');
		$this->db->order_by('shakhas.name, swayamsevaks.household_id');
		$data['query'] = $this->db->get();
		
		$this->output->set_header("Content-type: application/vnd.ms-excel");
		$this->output->set_header("Content-disposition: csv; filename=". date("M-d_H-i") .".csv");
		$this->load->view('nagar/csv', $data);
	}
	
	function browse($id = '', $order = 'name') {
		//if($id == '') $id = $this->session->userdata('vibhag_id');
		
		$shakha_ids = $this->Nagar_model->get_shakhas($id);
		$shakha_ids = '('.implode(',',$shakha_ids).')';
		
		$this->load->library('pagination');
		$config['base_url'] = base_url()."nagar/browse/$id/$order/";
    	$config['total_rows'] = $this->db->get_where('swayamsevaks', 'shakha_id IN ' . $shakha_ids)->num_rows();//$this->db->count_all('swayamsevaks');
    	$config['per_page'] = '35';
    	$config['full_tag_open'] = '<p>';
    	$config['full_tag_close'] = '</p>';
		$config['uri_segment'] = 5;
//		$config['post_url'] = $data['order'].'/'.$data['orderDir'];
		$this->pagination->initialize($config);
		
		$data['results'] = $this->Nagar_model->get_swayamsevaks($config['per_page'], $this->uri->segment(5), $id, $order);
		$data['pageTitle'] = 'Browse Swayamsevaks';
		$data['nagar_name'] = $this->Nagar_model->getShortDesc($id);
	    	
		$this->load->library('table');
    		$this->table->set_heading('Name', 'City', 'Phone', 'E-mail', 'Gana', 'Shakha');
		$this->layout->view('nagar/list_ss', $data);
	}
		
	function view($id)
	{

		$data['row'] = $this->Nagar_model->getNagarInfo($id);
		$data['pageTitle'] = $data['row']->name;
		$this->layout->view('nagar/view-nagar', $data);
	}
	
	function add_shakha($id)
	{
		if(isset($_POST['save']))
		{
			$this->Nagar_model->insert_shakha();
			$this->session->set_userdata('message', 'Shakha was successfully added.&nbsp;');
			redirect('nagar/view/'.$id);
		}
		$d['row'] = $this->Nagar_model->getNagarInfo($id);
		$d['states'] = $this->Nagar_model->getStates();
		$d['pageTitle'] = 'Add Shakha';
		//$d['row'] = $this->Nagar_model->getNagarInfo($id);
		$this->layout->view('nagar/add-shakha.php', $d);
	}
}

?>