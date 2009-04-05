<?php
class National extends Controller
{
    function National()
    {
        parent::Controller();
		if(!$this->session->userdata('logged_in'))
		{
			$this->session->set_userdata('message', 'Please login to access the system.');
			$this->session->set_userdata('redirect', $this->uri->uri_string());
			redirect('user');
		}
		
		//Check Permissions
		$perm = array('browse', 'responsibilities','statistics','email_lists','create_list','all_karyakarta_csv','csv_out');
		if(in_array($this->uri->segment(2), $perm)){
			if(!$this->permission->is_nt_kk())
			{
				$this->session->set_userdata('message', 'Your are not allowed to access the requested URL');
				redirect('national/view/');
			}
		}
		
		//$this->output->enable_profiler(TRUE);
		$this->load->model('National_model');
		$this->load->library('layout', 'layout_national');

		//$rs = $this->db->getwhere('shakhas', array('shakha_id' => $this->uri->segment(3)))->row();
	//	$id = $this->uri->segment(3);	
/*		$this->session->set_userdata('bc_vibhag', $this->National_model->getShortDesc($id));	
		$this->session->set_userdata('bc_vibhag_id', $id);
		$sid = str_split($id, 2);*/
/*		$this->session->set_userdata('bc_national', $this->National_model->getShortDesc());			
		$this->session->set_userdata('bc_national_id', $id);*/
		
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
			
    }
	
	function email_lists()
	{
        $this->db->select('id,address,level_id,status,style,size,mod1,mod2,mod3');
        //$this->db->where('level_id = '.$id.' AND status = \'Active\' OR status = \'Creating'\');
        $d['lists'] = $this->db->getwhere('lists', array('level' => 'NT'))->result_array();
        foreach($d['lists'] as &$list)
        {
			$list['address'] .= '@lists.hssusa.org';
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
            $list['details'] = anchor('national/edit_list/'.$list['level_id'].'/'.$list['id'], 'Details/Edit');
			$list['style'] = ($list['style']) ? 'Un-Moderated' : 'Moderated';
            unset($list['id']);
            unset($list['mod1']);
            unset($list['mod2']);
            unset($list['mod3']);
	     	unset($list['level_id']);
        }
        $d['national'] = $this->National_model->getNationalInfo();
		$d['pageTitle'] = 'Email Lists';
        $this->load->library('table');
        $this->table->set_heading('List Name', 'Status', 'Style', 'Size', 'Moderators', 'Details');
		//TODO: Create view for View Lists
		$this->layout->view('national/email_lists', $d);
	}

	function edit_list($list_id, $error = '')
    {
		//if($error != '')
		//	$c['data'] = $error;
		if(isset($_POST['button']))
		{
			if(!isset($_POST['members']))
			{
				$this->session->set_userdata('message', 'Please select at least 1 group for E-mail list members.');
				redirect('national/edit_list/'.$list_id);
			}
			if(!isset($_POST['mod_pass']) || strlen($_POST['mod_pass']) < 5)
			{
				$this->session->set_userdata('message', 'Your password should be at least 5 characters long.');
				redirect('national/edit_list/'.$list_id);
			}
			
			foreach($_POST as $key => $val)
				$d[$key] = $val;
			
			unset($d['button']);
			if(!isset($d['mod3']) || $d['mod3'] == '') $d['mod3'] = 0;
			if(!isset($d['mod2']) || $d['mod2'] == '') $d['mod2'] = 0;
			$d['members'] = serialize($d['members']);
			$this->db->update('lists', $d, array('id' => $list_id));
			
			$this->session->set_userdata('message', 'Your list was updated successfully.');
			redirect('national/email_lists/');
			
		}

        	
		$c['lists'] = $this->db->getwhere('lists', array('id' => $list_id))->row();
		$c['national'] = $this->National_model->getNationalInfo();
		$c['pageTitle'] = 'Edit E-mail list';
		$this->layout->view('national/edit_list', $c);	
    }
	
	function create_list($error = '')
	{
		if($error != '')
			$c['d'] = $error;
		$c['national'] = $this->National_model->getNationalInfo();
		$c['pageTitle'] = 'Create new e-mail list';
		$this->layout->view('national/create_list', $c);
	}
	
	function del_list($list_id)
	{
		if(isset($_POST['button2']))
		{
			$d['status'] = 'Deleting';
			$this->db->update('lists', $d, array('id' => $list_id));
			$this->session->set_userdata('message', 'Your list was queued for deleting');
			redirect('national/email_lists/');
		}
		else redirect('national/email_lists/');
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
			$this->National_model->add_email_list();
			$this->session->set_userdata('message', 'Your list '.$this->input->post('address').'@hssusa.org has been requested.');
			redirect('national/email_lists/');
		}
		
		//TODO: Add hidden shakha id parameter to from page
		//$d['level'] = 'sh';
		//level = 0-Shakha 1-national 2-national
		//type = 0 Moderated | 1 Unmoderated
		//members = 0 All Swayamsevaks | 1 Bala swayamsevaks | 2 Kishor Swayamsevaks | 3 Yuva Swayamsevaks
		// 4 Tarun Swayamsevaks | 5 Praudh swayamsevaks | 6 All Karyakartas
	}
	
	function statistics()
	{
/*		$yr = date('Y');
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
		$v['pageTitle'] = 'Shakha Statistics';*/
		//$data['national'] = $this->National_model->getNationalInfo($id);
	    $data['pageTitle'] = 'National Statistics';
		//$data['vibhag'] = $this->Sambhag_model->getSambhagShakhaStats($id);
		//$data['sambhag']->sankhya = $this->Sambhag_model->getSambhagSankhya($id, $data['shakhas']);
		$data['national']->contacts = $this->National_model->getNationalContacts();
		$data['stats'] = $this->National_model->getNationalStatistics();		
		$this->layout->view('national/statistics', $data);
	}

	function del_responsibility($ss_id, $resp_id)
	{
		$this->db->where('level', 'NT');
		$this->db->where('responsibility', $resp_id);
		$this->db->where('swayamsevak_id', $ss_id);
		$this->db->delete('responsibilities');
		$this->session->set_userdata('message', 'Responsibility deleted');
		redirect('national/responsibilities/');
	}

	function responsibilities()
	{
		$submit = $this->input->post('button');
		if($submit != '')
		{
			if($_POST['name'] == 0 || $_POST['resp'] == 0)
				$this->session->set_userdata('message', 'Please fill the form with required information');
			else
			{
				if($this->National_model->add_responsibility())
					$this->session->set_userdata('message', 'Responsibility added sucessfully');
			}
		}
		//$this->load->library('ajax');
		//$data['national'] = $id;
		$data['row'] = $this->National_model->getNationalInfo();
		$data['resp'] = $this->National_model->getRefCodes(4)->result();
		$data['pageTitle'] = $data['row']->name . ' Responsibilities';
		$data['names'] = $this->National_model->get_swayamsevaks(18000000, 0, 'name')->result();
		$this->layout->view('national/add_responsibility', $data);
	}

		
	function csv_out($id)
	{
		$this->output->enable_profiler(FALSE);
		$this->load->dbutil();
		$shakha_ids = $this->National_model->get_shakhas($id);
		$shakha_ids = '('.implode(',',$shakha_ids).')';
		$this->db->select('contact_id, household_id, shakha_id, first_name, last_name, gender, birth_year, company, position, email, email_status, ph_mobile, ph_home, ph_work, street_add1, street_add2, city, state, zip, ssv_completed, notes');
		$data['query'] = $this->db->getwhere('swayamsevaks', 'shakha_id IN ' . $shakha_ids);
			
		$this->output->set_header("Content-type: application/vnd.ms-excel");
		$this->output->set_header("Content-disposition: csv; filename=". date("M-d_H-i") .".csv");
		$this->load->view('national/csv', $data);
	}
	
	function browse($id = '', $order = 'name') {
		//if($id == '') $id = $this->session->userdata('national_id');
		
		$shakha_ids = $this->National_model->get_shakhas($id);
		$shakha_ids = '('.implode(',',$shakha_ids).')';
		
		$this->load->library('pagination');
		$config['base_url'] = base_url()."national/browse/$id/$order/";
    	$config['total_rows'] = $this->db->getwhere('swayamsevaks', 'shakha_id IN ' . $shakha_ids)->num_rows();//$this->db->count_all('swayamsevaks');
    	$config['per_page'] = '35';
    	$config['full_tag_open'] = '<p>';
    	$config['full_tag_close'] = '</p>';
		$config['uri_segment'] = 5;
//		$config['post_url'] = $data['order'].'/'.$data['orderDir'];
		$this->pagination->initialize($config);
		
		$data['results'] = $this->National_model->get_swayamsevaks($config['per_page'], $this->uri->segment(5), $id, $order);
		$data['pageTitle'] = 'Browse Swayamsevaks';
		$data['national_name'] = $this->National_model->getShortDesc($id);
	    	
		$this->load->library('table');
    		$this->table->set_heading('Name', 'City', 'Phone', 'E-mail', 'Gana', 'Shakha');
		$this->layout->view('national/list_ss', $data);
	}
		
	function view()
	{

		$data['row'] = $this->National_model->getNationalInfo();
		$data['pageTitle'] = $data['row']->name.' National';
		$this->layout->view('national/view-national', $data);
	}
	
	function all_karyakarta_csv()
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
											WHERE r.swayamsevak_id = s.contact_id
											ORDER BY Shakha, Nagar, Vibhag, Sambhag ASC;");
		//Get the database of Swayamsevaks of this Vibhag
		/*$this->db->select('swayamsevaks.contact_id, swayamsevaks.household_id, shakhas.name as shakhka, Ref_Code.short_desc as contact_type, swayamsevaks.first_name, swayamsevaks.last_name, swayamsevaks.gender, birth_year, swayamsevaks.company, swayamsevaks.position, swayamsevaks.email, swayamsevaks.email_status, swayamsevaks.ph_mobile, swayamsevaks.ph_home, swayamsevaks.ph_work, swayamsevaks.street_add1, swayamsevaks.street_add2, swayamsevaks.city, swayamsevaks.state, swayamsevaks.zip, swayamsevaks.ssv_completed, swayamsevaks.notes');
		$this->db->from('swayamsevaks, shakhas, Ref_Code');
		$this->db->where('swayamsevaks.shakha_id IN ' . $shakha_ids. ' AND shakhas.shakha_id = swayamsevaks.shakha_id AND Ref_Code.DOM_ID = 11 AND Ref_Code.REF_CODE = swayamsevaks.contact_type');
		$this->db->orderby('shakhas.name, swayamsevaks.household_id');
		$data['query'] = $this->db->get();*/

		$this->output->set_header("Content-type: application/vnd.ms-excel");
		$this->output->set_header("Content-disposition: csv; filename=All-Sangh-Karyakartas-". date("M-d_H-i") .".csv");
		$this->load->view('vibhag/csv', $data);
	}

}

?>