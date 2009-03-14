<?php

class Email_model extends Model 
{
   
    function Email_model()
    {
        parent::Model();
    }
    
	function get_email_addresses($list_id) {
		
		$list = $this->db->getwhere('lists', array('id' => $list_id))->row_array();
		//var_dump($list);
		switch($list['level']) {
			case 'SH':
				return $this->get_shakha_emails($list['level_id'], $list['members']);
			case 'NA':
				return $this->get_nagar_emails($list['level_id'], $list['members']);
			case 'VI':
				return $this->get_vibhag_emails($list['level_id'], $list['members']);
			case 'SA':
				return $this->get_sambhag_emails($list['level_id'], $list['members']);
			case 'NT':
				return $this->get_national_emails($list['members']);
		}
	}
	
	function get_shakha_emails($lid, $group) {
		
		$i = $this->db->select('vibhag_id', 'nagar_id')->getwhere('shakhas',array('shakha_id' => $lid))->row();

		$vibhag_id = $i->vibhag_id;
		$nagar_id = isset($i->nagar_id) ? $i->nagar_id : '';
		
		$members = unserialize($group);
		$temp = array();
		
		if(in_array('allss', $members)) {
			$this->get_email_addresses_common_sql();
			$t = $this->clean_email_array($this->db->where('shakha_id', $lid)->get());
			if(is_array($t)) $temp = array_merge($temp, $t);
			
			//Include Vibhag and Nagar Team in the All Swayamsevak List
			$this->get_email_addresses_common_sql();
			$this->db->from('responsibilities r');
			if(strlen(trim($nagar_id)) > 0)
				$this->db->where("r.nagar_id = '{$nagar_id}' AND r.swayamsevak_id = s.contact_id");
			else
				$this->db->where("r.vibhag_id = '{$vibhag_id}' AND r.swayamsevak_id = s.contact_id");
			$t = $this->clean_email_array($this->db->get());
			if(is_array($t)) $temp = array_merge($temp, $t);
		}

		if(in_array('allkk', $members)) {
			$this->get_email_addresses_common_sql();
			$this->db->from('responsibilities r');
			$this->db->where("r.shakha_id = $lid AND r.swayamsevak_id = s.contact_id");
			$t = $this->clean_email_array($this->db->get());
			if($t) $temp = array_merge($temp, $t);	
		}
		
		return array_unique($temp);
	}
	
	function get_nagar_emails($lid, $group){
		
		$tids = array();
		$shakha_ids = $this->db->select('shakha_id')->getwhere('shakhas', array('nagar_id' => $lid))->result();
		foreach($shakha_ids as $ids)
			$tids[] = $ids->shakha_id;
		$shakha_ids = '('.implode(',',$tids).')';
		
		$vibhag_id = substr($lid, 0, 4);
		$members = unserialize($group);
		$temp = array();
		
		if(in_array('allss', $members)) {
			$this->get_email_addresses_common_sql();
			$this->db->where('s.shakha_id IN ' . $shakha_ids);
			$t = $this->clean_email_array($this->db->get());
			if(is_array($t)) $temp = array_merge($temp, $t);
			
			//Include Vibhag Team
			$this->get_email_addresses_common_sql();
			$this->db->from('responsibilities r');
			$this->db->where("r.vibhag_id = '{$vibhag_id}' AND r.swayamsevak_id = s.contact_id");
			$t = $this->clean_email_array($this->db->get());
			if(is_array($t)) $temp = array_merge($temp, $t);
		}

		if(in_array('allkk', $members)) {
			$this->get_email_addresses_common_sql();
			$this->db->from('responsibilities r');
			$this->db->where("r.nagar_id = '$lid' AND r.swayamsevak_id = s.contact_id");
			$t = $this->clean_email_array($this->db->get());
			if($t) $temp = array_merge($temp, $t);	
		}
		return array_unique($temp);
	}
	
	function get_vibhag_emails($lid, $group){
		
		//$this->load->model('Vibhag_model');
		//$shakha_ids = $this->Vibhag_model->get_shakhas($lid);
		$tids = array();
		$shakha_ids = $this->db->select('shakha_id')->getwhere('shakhas', array('vibhag_id' => $lid))->result();
		foreach($shakha_ids as $ids)
			$tids[] = $ids->shakha_id;
		$shakha_ids = '('.implode(',',$tids).')';
		
		$sambhag_id = substr($lid, 0, 2);
		
		$members = unserialize($group);
		$temp = array();
		
		if(in_array('allss', $members)) {
			$this->get_email_addresses_common_sql();
			$this->db->where('s.shakha_id IN ' . $shakha_ids);
			$t = $this->clean_email_array($this->db->get());
			if(is_array($t)) $temp = array_merge($temp, $t);
			
			//Include Sambhag Team
			$this->get_email_addresses_common_sql();
			$this->db->from('responsibilities r');
			$this->db->where("r.sambhag_id = '{$sambhag_id}' AND r.swayamsevak_id = s.contact_id");
			$t = $this->clean_email_array($this->db->get());
			if(is_array($t)) $temp = array_merge($temp, $t);
		}

		if(in_array('allkk', $members)) {
			$this->get_email_addresses_common_sql();
			$this->db->from('responsibilities r');
			$this->db->where("r.vibhag_id = '$lid' AND r.swayamsevak_id = s.contact_id");
			$t = $this->clean_email_array($this->db->get());
			if($t) $temp = array_merge($temp, $t);	
		}
		
		return array_unique($temp);
	}
	
	function get_sambhag_emails($lid, $group){
		
		$tids = array();
		$shakha_ids = $this->db->select('shakha_id')->getwhere('shakhas', array('sambhag_id' => $lid))->result();
		foreach($shakha_ids as $ids)
			$tids[] = $ids->shakha_id;
		$shakha_ids = '('.implode(',',$tids).')';
				
		$members = unserialize($group);
		$temp = array();
		
		if(in_array('allss', $members)) {
			$this->get_email_addresses_common_sql();
			$this->db->where('s.shakha_id IN ' . $shakha_ids);
			$t = $this->clean_email_array($this->db->get());
			if(is_array($t)) $temp = array_merge($temp, $t);
		}

		if(in_array('allkk', $members)) {
			$this->get_email_addresses_common_sql();
			$this->db->from('responsibilities r');
			$this->db->where("r.sambhag_id = '$lid' AND r.swayamsevak_id = s.contact_id");
			$t = $this->clean_email_array($this->db->get());
			if($t) $temp = array_merge($temp, $t);	
		}
		
		return array_unique($temp);
	}
	
	function get_national_emails($group){
		
		$members = unserialize($group);
		$temp = array();
		
		if(in_array('allss', $members)) {
			$this->get_email_addresses_common_sql();
			$t = $this->clean_email_array($this->db->get());
			if(is_array($t)) $temp = array_merge($temp, $t);
		}

		if(in_array('allkk', $members)) {
			$this->get_email_addresses_common_sql();
			$this->db->from('responsibilities r');
			$this->db->where("r.swayamsevak_id = s.contact_id");
			$t = $this->clean_email_array($this->db->get());
			if($t) $temp = array_merge($temp, $t);	
		}
		return array_unique($temp);
	}

	function get_email_addresses_common_sql() {
		$this->db->distinct();
		$this->db->select('s.email');
		$this->db->from('swayamsevaks s');
		$this->db->where('email_status', 'Active');
	}
	
	function clean_email_array($result) {
		if($result->num_rows()) {
			$temp = array();
			foreach ($result->result_array() as $r)
				$temp[] = $r['email'];
			return $temp;
		}
		return NULL;
	}
	
}

?>