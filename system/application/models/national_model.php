<?php

class National_model extends Model 
{
   
    function National_model()
    {
        parent::Model();
    }
	
	
	function add_email_list()
	{
		foreach($_POST as $key => $value)
			$d[$key] = $value;
		unset($d['button']);
	$d['address'] = strtolower($d['address']);
	$d['address'] = filter_var($d['address'], FILTER_SANITIZE_EMAIL);
        $d['members'] = serialize($d['members']);
        $d['status'] = 'Creating';
        $d['owner'] = $this->session->userdata('contact_id');
        $d['owner_pass'] = 'abhi1986';
        if(!isset($d['mod2']) || $d['mod2'] == '') $d['mod2'] = 0;
        if(!isset($d['mod3']) || $d['mod3'] == '') $d['mod3'] = 0;
		$this->db->insert('lists', $d);
		//$d['level'] = 'sh';
		
	}
	
	function add_responsibility()
	{
		foreach($_POST as $key => $val)
			$data[$key] = trim($val);
		$data['swayamsevak_id'] = $data['name'];
		$data['responsibility'] = $data['resp'];
		unset($data['button']);
		unset($data['name']);
		unset($data['resp']);
		$r = $this->db->get_where('responsibilities', array('swayamsevak_id' => $data['swayamsevak_id'], 'responsibility' => $data['responsibility'], 'level' => 'NT'));
		if(!$r->num_rows())
		{
			$this->db->insert('responsibilities', $data);
			$d['contact_type'] = 'RA';
			$this->db->where('contact_id', $data['swayamsevak_id'])->update('swayamsevaks', $d);
		}
		else{
			$this->session->set_userdata('message', 'You cannot assign same responsibility more than once.');
			return false;
		}
		$var = $this->db->get_where('swayamsevaks', array('contact_id' => $data['swayamsevak_id']));
		//$var = $var->row();
		if($var->num_rows() && $var->row(0)->password == '' && trim($var->row(0)->email) != '' )
		{
			//$this->load->library('encrypt');
			$t['password'] = sha1($var->row(0)->email);
			$t['passwordmd5'] = md5($var->row(0)->email);
			$this->db->where('contact_id', $data['swayamsevak_id']);
			$this->db->update('swayamsevaks', $t);
		}
		return true;
	}
	
	function get_swayamsevaks($num, $offset, $sort_by)
	{
		$query = $this->db->select('contact_id, CONCAT(first_name, \' \', last_name) as name, city, ph_home as phone, ph_home, ph_mobile, ph_work, email, birth_year, shakha_id', FALSE)->order_by($sort_by, 'asc')->get('swayamsevaks', $num, $offset);

		return $query;
	}
	
	function get_shakhas()
	{
		$shakhas = $this->db->get('shakhas')->result();
		$shakha_ids = '';
		foreach($shakhas as $shakha)
			$shakha_ids[] = "$shakha->shakha_id";
		return $shakha_ids;
	}
	
	function getShakhaName($id)
	{
		$query = $this->db->select('name')->get_where('shakhas', array('shakha_id' => $id));
		return $query->row()->name;
	}
	
	function getNationalInfo()
	{
		//Get Sambhag Information
		$this->db->order_by('short_desc', 'asc'); 
		$v->sambhags = $this->db->select('REF_CODE, short_desc')->get_where('Ref_Code', array('DOM_ID' => 1))->result();
		
		//Get Sambhag Karyakarta Information
		foreach($v->sambhags as & $temp)
		{
			$sambhag_id = $temp->REF_CODE;
			$this->db->select('swayamsevaks.contact_id, swayamsevaks.first_name, swayamsevaks.last_name, responsibilities.responsibility');
			$this->db->from('swayamsevaks');
			$this->db->order_by('responsibilities.responsibility');
			$this->db->join('responsibilities', "responsibilities.swayamsevak_id = swayamsevaks.contact_id");
			$this->db->where("responsibilities.sambhag_id = '$sambhag_id'");
			$query = $this->db->get();
			if($query->num_rows())
			{
				$i = 0;
				foreach($query->result() as $row)
				{
					$temp->kk->$i = $row;
					$temp->kk->$i->resp_title = $this->getShortDesc($row->responsibility);
					$i++;
				}
			}		
		}
		
		//Set National Information
		$v->name = 'National';
		
		//Get National Karyakarta List
		$this->db->select('swayamsevaks.contact_id, swayamsevaks.first_name, swayamsevaks.last_name, responsibilities.responsibility');
		$this->db->from('swayamsevaks');
		$this->db->order_by('responsibilities.responsibility');
		$this->db->join('responsibilities', "responsibilities.swayamsevak_id = swayamsevaks.contact_id");
		$this->db->where("responsibilities.level = 'NT'");
		$query = $this->db->get();
		if($query->num_rows())
		{
			$i = 0;
			foreach($query->result() as $row)
			{
				$v->kk->$i = $row;
				$v->kk->$i->resp_title = $this->getShortDesc($row->responsibility);
				$i++;
			}
		}
		
		return $v;
	}
	
	function getShortDesc($var1)
	{
		$this->db->select('short_desc');
		$query = $this->db->get_where('Ref_Code', array('REF_CODE' => $var1));
		
		return ($query->num_rows()) ? $query->row()->short_desc : '';
	}
	
	function getRefCodes($var1)
	{
		return $this->db->get_where('Ref_Code', array('DOM_ID' => $var1));
	}
	
	function getNationalContacts() {

		//Set Up Age Brackets
		$yr = date('Y');
		$ag['shishu'] = $yr - 6;
        $ag['bala'] = $yr - 12;
        $ag['kishor'] = $yr - 19;
        $ag['yuva'] = $yr - 25;
        $ag['tarun'] = $yr - 50;

        //Get Statistics on Contacts
        $v['sevikas'] = $this->db->select('contact_id')->get_where('swayamsevaks', array('gender' => 'F'))->num_rows();
        $v['swayamsevaks'] = $this->db->select('contact_id')->get_where('swayamsevaks', array('gender' => 'M'))->num_rows();
        $v['families'] = $this->db->select('DISTINCT household_id', FALSE)->get('swayamsevaks')->num_rows();
        $v['contacts'] = $this->db->select('contact_id')->get('swayamsevaks')->num_rows();
        $v['shishu'] = $this->db->select('contact_id')->get_where('swayamsevaks', 'birth_year > '. $ag['shishu'])->num_rows();
        $v['bala'] = $this->db->select('contact_id')->get_where('swayamsevaks', 'birth_year BETWEEN '.$ag['bala'].' AND '. $ag['shishu'])->num_rows();
        $v['kishor'] = $this->db->select('contact_id')->get_where('swayamsevaks', 'birth_year BETWEEN '.$ag['kishor'].' AND '. $ag['bala'])->num_rows();
        $v['yuva'] = $this->db->select('contact_id')->get_where('swayamsevaks', 'birth_year BETWEEN '.$ag['yuva'].' AND '. $ag['kishor'])->num_rows();
        $v['tarun'] = $this->db->select('contact_id')->get_where('swayamsevaks', 'birth_year BETWEEN '.$ag['tarun'].' AND '. $ag['yuva'])->num_rows();
        $v['praudh'] = $this->db->select('contact_id')->get_where('swayamsevaks', 'birth_year > '.$ag['tarun'])->num_rows();
        $v['phone'] = $this->db->select('contact_id')->get_where('swayamsevaks', '(ph_mobile != \'\' OR ph_home != \'\' OR ph_work != \'\')')->num_rows();
        $v['email'] = $this->db->select('contact_id')->get_where('swayamsevaks', 'email != \'\' AND email_status = \'Active\' ')->num_rows();
        $v['email_unactive'] = $this->db->get_where('swayamsevaks', 'email != \'\' AND email_status != \'Active\' ')->num_rows();

        return $v;
    }
    
	function getNationalStatistics()
	{
		//Get Sambhag Information
		$this->db->where('DOM_ID', 1);
		//$this->db->like('REF_CODE', $id);
		$this->db->order_by('short_desc', 'asc'); 
		$sambhags = $this->db->select('REF_CODE as sambhag_id, short_desc as name')->get('Ref_Code')->result();
		
		
		foreach($sambhags as &$sambhag) {
			$sambhag->active_shakhas = $this->db->select('COUNT(shakha_id) as count')
												->get_where('shakhas', array('shakha_status' => 1, 'sambhag_id' => $sambhag->sambhag_id))
												->row()->count;
			$sambhag->sampark_kendras = $this->db->select('COUNT(shakha_id) as count')
												->get_where('shakhas', array('shakha_status' => 0, 'sambhag_id' => $sambhag->sambhag_id))
												->row()->count;
			$sambhag->weekly_shakhas = $this->db->select('COUNT(shakha_id) as count')
												->get_where('shakhas', array('shakha_status' => 1, 'sambhag_id' => $sambhag->sambhag_id, 'frequency' => 'WK'))
												->row()->count;
			$sambhag->karyakartas = $this->db->query("SELECT count(distinct(swayamsevak_id)) as count 
														FROM `responsibilities` 
														WHERE sambhag_id = '{$sambhag->sambhag_id}' 
															OR vibhag_id LIKE '{$sambhag->sambhag_id}%' 
															OR nagar_id LIKE '{$sambhag->sambhag_id}%' 
															OR shakha_id IN (select shakha_id FROM shakhas WHERE sambhag_id = '{$sambhag->sambhag_id}')")
												->row()->count;
												
		}
		//$data['total_shakhas'] = count($this->get_shakhas($id));
		//$data['active_shakhas'] = count($this->get_shakhas($id, true));
		//$data['sampark_kendras'] = $data['total_shakhas'] - $data['active_shakhas'];
		
		//$this->db->where(array('shakha_status' => 1, 'frequency' => 'WK', 'sambhag_id' => $id));
		//$data['weekly_shakhas'] = $this->db->select('COUNT(shakha_id) as count')->get('shakhas')->row()->count;
		
		return $sambhags;
	}
}

?>
