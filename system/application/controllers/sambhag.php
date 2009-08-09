<?php
class Sambhag extends Controller
{
    function Sambhag()
    {
        parent::Controller();
		if(!$this->session->userdata('logged_in'))
		{
			$this->session->set_userdata('message', 'Please login to access the system.');
			$this->session->set_userdata('redirect', $this->uri->uri_string());
			redirect('user');
		}
		
		//Check Permissions
		$perm = array('browse', 'add_vibhag','responsibilities','statistics','email_lists','create_list');
		if(in_array($this->uri->segment(2), $perm)){
			if(!$this->permission->is_sambhag_kk($this->uri->segment(3)))
			{
				$this->session->set_userdata('message', 'Your are not allowed to access the requested URL');
				redirect('sambhag/view/'.$this->uri->segment(3));
			}
		}
		
		//$this->output->enable_profiler(TRUE);
		$this->load->model('Sambhag_model');
		$this->load->library('layout');
		$this->layout->setLayout("layout_sambhag");

		//$rs = $this->db->get_where('shakhas', array('shakha_id' => $this->uri->segment(3)))->row();
		$id = $this->uri->segment(3);	
/*		$this->session->set_userdata('bc_vibhag', $this->Sambhag_model->getShortDesc($id));	
		$this->session->set_userdata('bc_vibhag_id', $id);
		$sid = str_split($id, 2);*/
		$this->session->set_userdata('bc_sambhag', $this->Sambhag_model->getShortDesc($id));			
		$this->session->set_userdata('bc_sambhag_id', $id);
		
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
			$list['address'] .= '@lists.hssusa.org';
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
            $list['details'] = ($list['status'] == 'Active') ? anchor('sambhag/edit_list/'.$list['level_id'].'/'.$list['id'], 'Details/Edit') : '';
			$list['style'] = ($list['style']) ? 'Un-Moderated' : 'Moderated';
            unset($list['id']);
            unset($list['mod1']);
            unset($list['mod2']);
            unset($list['mod3']);
	     	unset($list['level_id']);
        }
        $d['sambhag'] = $this->Sambhag_model->getSambhagInfo($id);
		$d['pageTitle'] = 'Email Lists';
        $this->load->library('table');
        $this->table->set_heading('List Name', 'Status', 'Style', 'Size', 'Moderators', 'Details');
		//TODO: Create view for View Lists
		$this->layout->view('sambhag/email_lists', $d);
	}
	
	
/*	function email_lists($id)
	{
        $this->db->select('id,address,level_id,status,style,size,mod1,mod2,mod3');
        //$this->db->where('level_id = '.$id.' AND status = \'Active\' OR status = \'Creating'\');
        $d['lists'] = $this->db->get_where('lists', array('level_id' => $id))->result_array();
        foreach($d['lists'] as &$list)
        {
			$list['address'] .= '@hssusa.org';
            if($list['mod1'] != '')
            {
                $this->db->select('contact_id,first_name,last_name');
                $t = $this->db->get_where('swayamsevaks', array('contact_id' => $list['mod1']))->row();
                $list['mod1'] = anchor('profile/view/'.$t->contact_id, $t->first_name.' '.$t->last_name) . '<br \>';
            }
            if($list['mod2'] != '')
            {
                $this->db->select('contact_id,first_name,last_name');
                $t = $this->db->get_where('swayamsevaks', array('contact_id' => $list['mod2']))->row();
                $list['mod2'] = anchor('profile/view/'.$t->contact_id,$t->first_name.' '.$t->last_name) . '<br \>';
            }
            if($list['mod3'] != '')
            {
                $this->db->select('contact_id,first_name,last_name');
                $t = $this->db->get_where('swayamsevaks', array('contact_id' => $list['mod3']))->row();
                $list['mod3'] = anchor('profile/view/'.$t->contact_id,$t->first_name.' '.$t->last_name) . '<br \>';
            }
            $list['moderators'] = $list['mod1'] . $list['mod2'] . $list['mod3'];
            $list['details'] = anchor('sambhag/edit_list/'.$list['level_id'].'/'.$list['id'], 'Details/Edit');
			$list['style'] = ($list['style']) ? 'Un-Moderated' : 'Moderated';
            unset($list['id']);
            unset($list['mod1']);
            unset($list['mod2']);
            unset($list['mod3']);
	     	unset($list['level_id']);
        }
        $d['sambhag'] = $this->Sambhag_model->getSambhagInfo($id);
		$d['pageTitle'] = 'Email Lists';
        $this->load->library('table');
        $this->table->set_heading('List Name', 'Status', 'Style', 'Size', 'Moderators', 'Details');
		//TODO: Create view for View Lists
		$this->layout->view('sambhag/email_lists', $d);
	}

    function edit_list($id, $list_id, $error = '')
    	{
		if($error != '')
			$c['data'] = $error;
        	
		$c['lists'] = $this->db->get_where('lists', array('level_id' => $list_id))->row();
		$c['sambhag'] = $this->Sambhag_model->getSambhagInfo($c['lists']->level_id);
		$c['pageTitle'] = 'Edit E-mail list';
		$this->layout->view('sambhag/edit_list', $c);	
    	}*/

	function edit_list($id, $list_id, $error = '')
    {
		//if($error != '')
		//	$c['data'] = $error;
		if(isset($_POST['button']))
		{
			if(!isset($_POST['members']))
			{
				$this->session->set_userdata('message', 'Please select at least 1 group for E-mail list members.');
				redirect('sambhag/edit_list/'.$id.'/'.$list_id);
			}
			if(!isset($_POST['mod_pass']) || strlen($_POST['mod_pass']) < 5)
			{
				$this->session->set_userdata('message', 'Your password should be at least 5 characters long.');
				redirect('sambhag/edit_list/'.$id.'/'.$list_id);
			}
			
			foreach($_POST as $key => $val)
				$d[$key] = $val;
			
			unset($d['button']);
			if(!isset($d['mod3']) || $d['mod3'] == '') $d['mod3'] = 0;
			if(!isset($d['mod2']) || $d['mod2'] == '') $d['mod2'] = 0;
			$d['members'] = serialize($d['members']);
			$this->db->update('lists', $d, array('id' => $list_id));
			
			$this->session->set_userdata('message', 'Your list was updated successfully.');
			redirect('sambhag/email_lists/'.$id);
			
		}

        	
		$c['lists'] = $this->db->get_where('lists', array('id' => $list_id))->row();
		$c['sambhag'] = $this->Sambhag_model->getSambhagInfo($c['lists']->level_id);
		$c['pageTitle'] = 'Edit E-mail list';
		$this->layout->view('sambhag/edit_list', $c);	
    }
	
	function create_list($id, $error = '')
	{
		if($error != '')
			$c['d'] = $error;
		$c['sambhag'] = $this->Sambhag_model->getSambhagInfo($id);
		$c['pageTitle'] = 'Create new e-mail list';
		$this->layout->view('sambhag/create_list', $c);
	}
	
	function del_list($id, $list_id)
	{
		if(isset($_POST['button2']))
		{
			$d['status'] = 'Deleting';
			$this->db->update('lists', $d, array('id' => $list_id));
			$this->session->set_userdata('message', 'Your list was queued for deleting');
			redirect('sambhag/email_lists/'.$id);
		}
		else redirect('sambhag/email_lists/'.$id);
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
			$this->Sambhag_model->add_email_list();
			$this->session->set_userdata('message', 'Your list '.$this->input->post('address').'@hssusa.org has been requested.');
			redirect('sambhag/email_lists/'.$this->input->post('level_id'));
		}
		
		//TODO: Add hidden shakha id parameter to from page
		//$d['level'] = 'sh';
		//level = 0-Shakha 1-sambhag 2-sambhag
		//type = 0 Moderated | 1 Unmoderated
		//members = 0 All Swayamsevaks | 1 Bala swayamsevaks | 2 Kishor Swayamsevaks | 3 Yuva Swayamsevaks
		// 4 Tarun Swayamsevaks | 5 Praudh swayamsevaks | 6 All Karyakartas
	}
	
	function statistics($id)
	{

		$data['sambhag'] = $this->Sambhag_model->getSambhagInfo($id);
	    $data['pageTitle'] = $data['sambhag']->name . ' Sambhag Statistics';
		//$data['vibhag'] = $this->Sambhag_model->getSambhagShakhaStats($id);
		//$data['sambhag']->sankhya = $this->Sambhag_model->getSambhagSankhya($id, $data['shakhas']);
		$data['sambhag']->contacts = $this->Sambhag_model->getSambhagContacts($id);
		$data['stats'] = $this->Sambhag_model->getSambhagStatistics($id);

		$this->layout->view('sambhag/statistics', $data);
	}

	function del_responsibility($sambhag_id, $ss_id, $resp_id)
	{
		$this->db->where('sambhag_id', $sambhag_id);
		$this->db->where('responsibility', $resp_id);
		$this->db->where('swayamsevak_id', $ss_id);
		$this->db->delete('responsibilities');
		$this->session->set_userdata('message', 'Responsibility deleted');
		redirect('sambhag/responsibilities/' . $sambhag_id);
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
				if($this->Sambhag_model->add_responsibility())
					$this->session->set_userdata('message', 'Responsibility added sucessfully');
			}
		}
		//$this->load->library('ajax');
		$data['sambhag'] = $id;
		$data['row'] = $this->Sambhag_model->getSambhagInfo($id);
		$data['resp'] = $this->Sambhag_model->getRefCodes(4)->result();
		$data['pageTitle'] = $data['row']->name . ' Sambhag';
		$data['names'] = $this->Sambhag_model->get_swayamsevaks(18000000, 0, $id, 'name')->result();
		$this->layout->view('sambhag/add_responsibility', $data);
	}

		
	function csv_out($id)
	{
		$this->output->enable_profiler(FALSE);
		$this->load->dbutil();
		$shakha_ids = $this->Sambhag_model->get_shakhas($id);
		$shakha_ids = '('.implode(',',$shakha_ids).')';
		$this->db->select('contact_id, household_id, shakha_id, first_name, last_name, gender, birth_year, company, position, email, email_status, ph_mobile, ph_home, ph_work, street_add1, street_add2, city, state, zip, ssv_completed, notes');
		$data['query'] = $this->db->get_where('swayamsevaks', 'shakha_id IN ' . $shakha_ids);
		
/*		$temp = $data['query']->result();
		//Get Shakha Names and replace Shakha IDs with Names
		$shakhas = $this->db->get_where('shakhas', 'shakha_id IN ' . $shakha_ids);
		$sh = '';
		foreach($shakhas->result() as $s1)
			$sh[$s1->shakha_id] = $s1->name;
		*/
		/*for($i = 1; $i < $data['query']->num_rows(); $i++)
			$temp[$i]->Shakha = $sh[$temp[$i]->Shakha];*/
			
		$this->output->set_header("Content-type: application/vnd.ms-excel");
		$this->output->set_header("Content-disposition: csv; filename=". date("M-d_H-i") .".csv");
		$this->load->view('sambhag/csv', $data);
	}
	
	function browse($id = '', $order = 'name') {
		//if($id == '') $id = $this->session->userdata('sambhag_id');
		
		$shakha_ids = $this->Sambhag_model->get_shakhas($id);
		$shakha_ids = '('.implode(',',$shakha_ids).')';
		
		$this->load->library('pagination');
		$config['base_url'] = base_url()."sambhag/browse/$id/$order/";
    	$config['total_rows'] = $this->db->get_where('swayamsevaks', 'shakha_id IN ' . $shakha_ids)->num_rows();//$this->db->count_all('swayamsevaks');
    	$config['per_page'] = '35';
    	$config['full_tag_open'] = '<p>';
    	$config['full_tag_close'] = '</p>';
		$config['uri_segment'] = 5;
//		$config['post_url'] = $data['order'].'/'.$data['orderDir'];
		$this->pagination->initialize($config);
		
		$data['results'] = $this->Sambhag_model->get_swayamsevaks($config['per_page'], $this->uri->segment(5), $id, $order);
		$data['pageTitle'] = 'Browse Swayamsevaks';
		$data['sambhag_name'] = $this->Sambhag_model->getShortDesc($id);
	    	
		$this->load->library('table');
    		$this->table->set_heading('Name', 'City', 'Phone', 'E-mail', 'Gana', 'Shakha');
		$this->layout->view('sambhag/list_ss', $data);
	}
		
	function view($id)
	{

		$data['row'] = $this->Sambhag_model->getSambhagInfo($id);
		$data['pageTitle'] = $data['row']->name.' Sambhag';
		$this->layout->view('sambhag/view-sambhag', $data);
	}
	
	function all_sambhag_karyakarta_csv($id)
	{
		$this->load->dbutil();

		//Get list of Shakhas in the Vibhag
		//$shakha_ids = $this->Vibhag_model->get_shakhas($id);
		//$shakha_ids = '('.implode(',',$shakha_ids).')';

		$data['query'] = $this->db->query("SELECT s.first_name as FirstName, s.last_name as LastName, s.email Email,
											s.city as City, s.state as State, sh.name as Shakha,
											rc.short_desc as Nagar, rc0.short_desc as Vibhag, rc1.short_desc as Sambhag, rc2.short_desc as Responsibility
											FROM swayamsevaks s, responsibilities r 
											LEFT JOIN shakhas sh ON r.shakha_id = sh.shakha_id
											LEFT JOIN Ref_Code rc ON r.nagar_id = rc.REF_CODE AND rc.DOM_ID = 3
											LEFT JOIN Ref_Code rc0 ON r.vibhag_id = rc0.REF_CODE AND rc0.DOM_ID = 2
											LEFT JOIN Ref_Code rc1 ON r.sambhag_id = rc1.REF_CODE AND rc1.DOM_ID = 1
											LEFT JOIN Ref_Code rc2 ON r.responsibility = rc2.REF_CODE AND rc2.DOM_ID = 4
											WHERE s.shakha_id IN (SELECT shakha_id FROM shakhas WHERE sambhag_id LIKE '{$id}') 
											AND r.swayamsevak_id = s.contact_id
											ORDER BY Shakha, Nagar, Vibhag, Sambhag ASC;");
		//Get the database of Swayamsevaks of this Vibhag
		/*$this->db->select('swayamsevaks.contact_id, swayamsevaks.household_id, shakhas.name as shakhka, Ref_Code.short_desc as contact_type, swayamsevaks.first_name, swayamsevaks.last_name, swayamsevaks.gender, birth_year, swayamsevaks.company, swayamsevaks.position, swayamsevaks.email, swayamsevaks.email_status, swayamsevaks.ph_mobile, swayamsevaks.ph_home, swayamsevaks.ph_work, swayamsevaks.street_add1, swayamsevaks.street_add2, swayamsevaks.city, swayamsevaks.state, swayamsevaks.zip, swayamsevaks.ssv_completed, swayamsevaks.notes');
		$this->db->from('swayamsevaks, shakhas, Ref_Code');
		$this->db->where('swayamsevaks.shakha_id IN ' . $shakha_ids. ' AND shakhas.shakha_id = swayamsevaks.shakha_id AND Ref_Code.DOM_ID = 11 AND Ref_Code.REF_CODE = swayamsevaks.contact_type');
		$this->db->order_by('shakhas.name, swayamsevaks.household_id');
		$data['query'] = $this->db->get();*/

		$this->output->set_header("Content-type: application/vnd.ms-excel");
		$this->output->set_header("Content-disposition: csv; filename=All-Karyakartas-{$id}-". date("M-d_H-i") .".csv");
		$this->load->view('vibhag/csv', $data);
	}
	
/*	function add_shakha($id)
	{
		if(isset($_POST['save']))
		{
			$this->Sambhag_model->insert_shakha();
			$this->session->set_userdata('message', 'Shakha was successfully added.&nbsp;');
			redirect('sambhag/view/'.$id);
		}
		$d['row'] = $this->Sambhag_model->getSambhagInfo($id);
		$d['states'] = $this->Sambhag_model->getStates();
		$d['pageTitle'] = 'Add Shakha';
		$d['row'] = $this->Sambhag_model->getSambhagInfo($id);
		$this->layout->view('sambhag/add-shakha.php', $d);
	}*/
}

?>