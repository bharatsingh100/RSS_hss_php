<?php

class Shakha_model extends Model 
{
   
    function Shakha_model()
    {
        parent::Model();
    }
	
	function add_email_list()
	{
		foreach($_POST as $key => $value)
			$d[$key] = $value;
		unset($d['button']);
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
		$r = $this->db->getwhere('responsibilities', array('swayamsevak_id' => $data['swayamsevak_id'], 'responsibility' => $data['responsibility'], 'shakha_id' => $data['shakha_id']));
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
		$var = $this->db->getwhere('swayamsevaks', array('contact_id' => $data['swayamsevak_id']));
		//$var = $var->row();
		if($var->num_rows() && $var->row()->password == '')
		{
			//$this->load->library('encrypt');
			$t['password'] = sha1($var->row()->email);
			$t['passwordmd5'] = md5($var->row()->email);
			$this->db->where('contact_id', $data['swayamsevak_id']);
			$this->db->update('swayamsevaks', $t);
		}
		return true;
	}

	function get_sankhyas($id, $date)
	{
		$q = $this->db->getwhere('sankhyas', array('shakha_id' => $id, 'date' => $date));
		return $q->result();
	}
	
	function insert_sankhya()
	{
		foreach($_POST as $key => $val)
			$data[$key] = trim($val);
		$data['contact_id'] = $this->session->userdata('contact_id');
		$data['ip'] = $this->input->ip_address();
		$data['total'] = $data['shishu_m'] + $data['shishu_f'] + $data['bala_f'] + $data['bala_m'] + $data['kishor_m'] + $data['kishor_f'] + $data['yuva_m'] + $data['yuva_f'] + $data['tarun_m'] + $data['tarun_f'] + $data['praudh_m'] + $data['praudh_f'];
		unset($data['button']);
		$exists = $this->db->getwhere('sankhyas', array('date' => $data['date'], 'shakha_id' => $data['shakha_id']));
		if($exists->num_rows()) { //If sankhya for that week already exists then update
			$this->db->where('shakha_id', $data['shakha_id']);
			$this->db->where('date', $data['date']);
			$this->db->update('sankhyas', $data);
		}
		else
			$this->db->insert('sankhyas', $data);
	}
	
	function update_shakha($id)
	{
		foreach($_POST as $key => $val)
			$data[$key] = trim($val);
		unset($data['save']);
		$this->db->update('shakhas', $data, array('shakha_id' => $id));
	}
	
	function get_swayamsevaks($num, $offset, $shakha_id, $sort_by)
	{
		$query = $this->db->select('contact_id, CONCAT(first_name, \' \', last_name) as name, city, ph_home as phone, ph_home, ph_mobile, ph_work, email, birth_year, gatanayak')->orderby($sort_by, 'asc')->getwhere('swayamsevaks', array('shakha_id' => $shakha_id/*$this->session->userdata('shakha_id')*/), $num, $offset);
		return $query;
	}
	function getShakhaName($id)
	{
		$query = $this->db->select('name')->getwhere('shakhas', array('shakha_id' => $id));
		return $query->row()->name;
	}
	function getShakhaInfo($id)
	{
		$query = $this->db->getwhere('shakhas', array('shakha_id' => $id));
		$temp = $query->row();
		//if($temp->shakha_id = '') { $temp->shakha_id = 0;}
		//$temp->shakha_id = 0;
		$shakha_id = $temp->shakha_id;
		$this->db->select('swayamsevaks.contact_id, swayamsevaks.first_name, swayamsevaks.last_name, responsibilities.responsibility');
		$this->db->from('swayamsevaks');
		$this->db->orderby('responsibilities.responsibility');
		$this->db->join('responsibilities', "responsibilities.shakha_id = $shakha_id AND responsibilities.swayamsevak_id = swayamsevaks.contact_id");
		$query = $this->db->get();
		if($query->num_rows())
		{
			$i = 0;
			foreach($query->result() as $row)
			{
				$temp->kk->$i = $row;
				$temp->kk->$i->responsibility = $row->responsibility;
				$temp->kk->$i->resp_title = $this->getShortDesc($row->responsibility);
				$i++;
			}
		}
		$this->db->orderby('date', 'desc');
		$j = $this->db->getwhere('sankhyas', array('shakha_id' => $id));
		$temp->sankhyas = $j->result();
		return $temp;
	}
	function insert_ss()
	{
		foreach($_POST as $key => $val)
			$data[$key] = trim($val);
		$max_hh = $this->db->select('MAX(household_id)')->get('swayamsevaks')->result_array();
		$max_hh = $max_hh[0]['MAX(household_id)'] + 1;
		
		/*$data['ph_home'] = (isset($data['ph_home']) && $data['ph_home'] == 'Home...') ? '' : $this->reformat_phone_dash($data['ph_home']);
		$data['ph_mobile']= (isset($data['ph_mobile']) && $data['ph_mobile'] == 'Mobile...') ? '' : $this->reformat_phone_dash($data['ph_mobile']);
		$data['ph_work'] = (isset($data['ph_work']) && $data['ph_work'] == 'Work...') ? '' : $this->reformat_phone_dash($data['ph_work']);
		*/
		$data['ph_home'] = (isset($data['ph_home']) && strlen(trim($data['ph_home'])) < 10) ? '' : $this->reformat_phone_dash($data['ph_home']);
		$data['ph_mobile']= (isset($data['ph_mobile']) && strlen(trim($data['ph_mobile'])) < 10) ? '' : $this->reformat_phone_dash($data['ph_mobile']);
		$data['ph_work'] = (isset($data['ph_work']) && strlen(trim($data['ph_work'])) < 10) ? '' : $this->reformat_phone_dash($data['ph_work']);
		$data['email'] = (isset($data['email'])) ? (strtolower($data['email'])) : '';
		$data['email_status'] = ($data['email'] != '') ? 'Active' : '';
		$data['household_id'] = (isset($data['household_id']) && !empty($data['household_id'])) ? $data['household_id'] : $max_hh;
		$data['first_name'] = (isset($data['first_name'])) ? ucwords(strtolower($data['first_name'])) : '';
		$data['last_name'] = (isset($data['last_name'])) ? ucwords(strtolower($data['last_name'])) : '';
		$data['city'] = (isset($data['city'])) ? ucwords(strtolower($data['city'])) : '';
		$data['street_add1'] = (isset($data['street_add1'])) ? ucwords(strtolower($data['street_add1'])) : '';
		$data['street_add2'] = (isset($data['street_add2'])) ? ucwords(strtolower($data['street_add2'])) : '';
		unset($data['save']);
		unset($data['add_family']);
		$this->db->insert('swayamsevaks', $data);
		//$temp->household_id = $max_hh;
		return $data;
	}
	function import_contacts($id)
	{
		//$shakha_name = $this->getShakhaName($id);
		//$date = date("m-d-y-H-i-s");
		$userdir = explode('.',$_SERVER['SERVER_NAME']);
		$userdir = $userdir[0];		
		$file = '/home/'.$userdir.'/backups/'.str_replace(' ', '', $this->getShakhaName($id)).'-'.date("m-d-y-H-i-s").'.txt';
		shell_exec("mysqldump -ucrmhss_crm -pcrm crmhss_crm > $file"); 
		log_message('info', "Created a database dump in file $file");
		//shell_exec("chmod 0777 $file");
		$addarr = $_POST['fin'];
		$contacts = unserialize($this->session->userdata('import_c'));
		//$contacts = array_shift($contacts); //Remove file headers Name, Email etc.
		//$max_hh = $this->db->select('MAX(household_id)')->get('swayamsevaks')->result_array();
		$hi = '';//$max_hh[0]['MAX(household_id)'] + 1;
		//$household_id = '';
		$fam_id = '';
		
		$shakha = $this->getShakhaInfo($id);
		for($i = 1; $i < sizeof($contacts); $i++)
		{
			if(!in_array($i-1, $addarr)) continue;
			if(isset($contacts[$i]['family_id']) && $contacts[$i]['family_id'] != '' && $contacts[$i]['family_id'] == $fam_id)
				$d['household_id'] = $hi;
			else {
				$temp = $this->db->select('MAX(household_id)')->get('swayamsevaks')->result_array();
				$d['household_id'] = $hi = $temp[0]['MAX(household_id)'] + 1;
				}
			
			//In case the uploaded file has Name column instead of First and Last name
			if(isset($contacts[$i]['name']))
			{
				$name = str_word_count(trim($contacts[$i]['name']), 2);
				//If there is Last Name then set it otherwise set it to none.
				if(count($name) > 1)
				{
					$d['last_name'] = ucwords(strtolower(array_pop($name)));
					$d['first_name'] = ucwords(strtolower(implode(' ',$name)));
				}
				else
				{ 
					$d['first_name'] = ucwords(strtolower(array_pop($name)));
					$d['last_name'] = '';
				}
					
				if($d['last_name'] == '') $d['last_name'] = '';	
				if($d['first_name'] == '') $d['first_name'] = '';
			}
			else
			{
				$d['first_name'] = isset($contacts[$i]['first_name']) ? trim(ucwords(strtolower($contacts[$i]['first_name']))) : '';
				$d['last_name'] = isset($contacts[$i]['last_name']) ? trim(ucwords(strtolower($contacts[$i]['last_name']))) : '';
			}
			
			$fam_id = isset($contacts[$i]['family_id']) ? $contacts[$i]['family_id'] : '';//$contacts[$i]['family_id'];
			$d['shakha_id'] = $id;

			$d['gender'] = isset($contacts[$i]['gender']) ? ucwords(strtolower($contacts[$i]['gender'])) : '';
			$d['birth_year'] = isset($contacts[$i]['birth_year']) ? $contacts[$i]['birth_year'] : '';
			$d['company'] = isset($contacts[$i]['company']) ? ucwords(strtolower($contacts[$i]['company'])) : '';
			$d['position'] = isset($contacts[$i]['position']) ? ucwords(strtolower($contacts[$i]['position'])) : '';			
			$d['email'] = isset($contacts[$i]['email']) ? trim(strtolower($contacts[$i]['email'])) : '';
			$d['email_status'] = ($d['email'] != '') ? 'Active' : '';
			$d['ph_mobile'] = (isset($contacts[$i]['ph_mobile']) && $contacts[$i]['ph_mobile'] != '') ? $this->reformat_phone_dash($contacts[$i]['ph_mobile']): '';
			$d['ph_home'] = (isset($contacts[$i]['ph_home']) && $contacts[$i]['ph_home'] != '' ) ? $this->reformat_phone_dash($contacts[$i]['ph_home']) : '';
			$d['ph_work'] = (isset($contacts[$i]['ph_work']) && $contacts[$i]['ph_work'] != '' ) ? $this->reformat_phone_dash($contacts[$i]['ph_work']) : '';		
			$d['street_add1'] = isset($contacts[$i]['street_add1']) ? ucwords(strtolower($contacts[$i]['street_add1'])) : '';			
			$d['street_add2'] = isset($contacts[$i]['street_add2']) ? ucwords(strtolower($contacts[$i]['street_add2'])) : '';			
			$d['city'] = isset($contacts[$i]['city']) ? trim(ucwords(strtolower($contacts[$i]['city']))) : '';			
			$d['state'] = (isset($contacts[$i]['state']) && $contacts[$i]['state'] != '') ? strtoupper($contacts[$i]['state']) : $shakha->state;			
			$d['zip'] = isset($contacts[$i]['zip']) ? $contacts[$i]['zip'] : '';
			$d['notes'] = isset($contacts[$i]['notes']) ? $contacts[$i]['notes'] : '';
			$this->db->insert('swayamsevaks', $d);														
		}
	}
	function reformat_phone_dash($ph) 
	{
		$onlynums = preg_replace('/[^0-9]/','',$ph);
		if (strlen($onlynums)==10) 
		{
			$areacode = substr($onlynums, 0,3);
			$exch = substr($onlynums,3,3);
			$num = substr($onlynums,6,4);
			$thisphone = $areacode . "-" . $exch . "-" . $num;
			return $thisphone;
	    }
   		return $ph;
	}
	function getShortDesc($var1)
	{
		$this->db->select('short_desc');
		$query = $this->db->getwhere('Ref_Code', array('REF_CODE' => $var1));
		
		return ($query->num_rows()) ? $query->row()->short_desc : '';
	}
	function getRefCodes($var1)
	{
		return $this->db->getwhere('Ref_Code', array('DOM_ID' => $var1));
	}
	function getStates()
	{
		return $this->getRefCodes(6)->result();
	}
	function getSSVCompleted()
	{
		return $this->getRefCodes(5)->result();
	}
	function getGatanayaks($id)
	{
		$this->db->select('swayamsevaks.contact_id, swayamsevaks.first_name, swayamsevaks.last_name');
		$this->db->from('swayamsevaks');
		$this->db->join('responsibilities', 'responsibilities.swayamsevak_id = swayamsevaks.contact_id AND responsibilities.shakha_id = '.$id);
		//.' AND responsibilities.responsibility = 140');
		$result = $this->db->get();
		//Task: Fix Gatanayak Query
		if($result->num_rows())
		{
			$count = $result->num_rows();
			for($i = 0; $i < $count; $i++)
				$result->row($i)->num = $this->db->getwhere('swayamsevaks', array('gatanayak' => $result->row($i)->contact_id))->num_rows();
		}
		return $result->result();
	}
}

?>