<?php

class Profile_model extends Model 
{
    function Profile_model()
    {
        parent::Model();
    }
	
   	function insert_ss()
	{
		foreach($_POST as $key => $val)
			$data[$key] = trim($val);
		//$max_hh = $this->db->select('MAX(household_id)')->get('swayamsevaks')->result_array();
		//$max_hh = $max_hh[0]['MAX(household_id)'] + 1;
		
		$data['ph_home'] = ($data['ph_home']== 'Home...') ? '' : $this->reformat_phone_dash($data['ph_home']);
		$data['ph_mobile']= ($data['ph_mobile']== 'Mobile...') ? '' : $this->reformat_phone_dash($data['ph_mobile']);
		$data['ph_work'] = ($data['ph_work']== 'Work...') ? '' : $this->reformat_phone_dash($data['ph_work']);
		if(!isset($data['email_status']) || trim($data['email_status']) == ''
			|| !isset($data['email']) || trim($data['email']) == '')
			$data['email_status'] = (isset($data['email']) && $data['email'] != '') ? 'Active' : '';
		
		//$data['household_id'] = ((isset($data['household_id']) && !empty($data['household_id'])) ? $data['household_id'] : $max_hh);
		$data['first_name'] = ucwords(strtolower($data['first_name']));
		$data['last_name'] = ucwords(strtolower($data['last_name']));
		$data['city'] = ucwords(strtolower($data['city']));
		$data['street_add1'] = ucwords(strtolower($data['street_add1']));
		$data['street_add2'] = ucwords(strtolower($data['street_add2']));
		if(isset($data['add_update'])) //If update address of other family membmers
		{
			$this->db->where('household_id', $data['household_id']);
			//$this->db->where('date', $data['date']);
			$temp['street_add1'] = $data['street_add1'];
			$temp['street_add2'] = $data['street_add2'];
			$temp['city'] = $data['city'];
			$temp['state'] = $data['state'];
			$temp['zip'] = $data['zip'];
			$this->db->update('swayamsevaks', $temp);
		}
		unset($data['save']);
		//unset($data['add_family']);
		unset($data['add_update']);
		$this->db->where('contact_id', $data['contact_id']);
		$this->db->update('swayamsevaks', $data);
		//$temp->household_id = $max_hh;
		//return $data;
	}
	
	function search($num, $offset, $state, $term)
	{
		//$term = explode(' ', $term);
		//foreach(
		$this->db->select('contact_id, CONCAT(first_name, \' \', last_name) as name, city, state, ph_home as phone, ph_home, ph_mobile, ph_work, email');
//		$this->db->orderby($sort_by, 'asc');
		$this->db->where("MATCH(first_name, last_name, company, position, city, notes, email) AGAINST ('+($term)')");
		$this->db->where('state', $state);
		$query = $this->db->get('swayamsevaks', $num, $offset);
				 
		return $query;
	}
	
	function getRefCodes($var1)
	{
		return $this->db->getwhere('Ref_Code', array('DOM_ID' => $var1));
	}
	
	function getStates()
	{
		return $this->getRefCodes(6)->result();
	}
	
	function getGatanayaks($id)
	{
		$this->db->select('swayamsevaks.contact_id, swayamsevaks.first_name, swayamsevaks.last_name');
		$this->db->from('swayamsevaks');
		$this->db->join('responsibilities', 'responsibilities.swayamsevak_id = swayamsevaks.contact_id AND responsibilities.shakha_id = '.$id);
		//.' AND responsibilities.responsibility = 140');
		$result = $this->db->get();
		if($result->num_rows())
		{
			$count = $result->num_rows();
			for($i = 0; $i < $count; $i++)
				$result->row($i)->num = $this->db->getwhere('swayamsevaks', array('gatanayak' => $result->row($i)->contact_id))->num_rows();
		}
		return $result->result();
	}
	
	function getGata($id)
	{
		$this->db->select('contact_id, first_name, last_name');
		$q = $this->db->getwhere('swayamsevaks', array('gatanayak' => $id));
		return $q->result();
		
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
	
	function getResponsibilities($id)
	{
		$query = $this->db->getwhere('responsibilities', array('swayamsevak_id' => $id));
		$count = $query->num_rows();
		for($i = 0; $i < $count; $i++)
		{
			$query->row($i)->resp_title = $this->getShortDesc($query->row($i)->responsibility);
			$query->row($i)->level = $this->getShortDesc($query->row($i)->level);
		}
		return $query->result();
	}
	
	function getShakhaInfo($id)
	{
		$query = $this->db->getwhere('shakhas', array('shakha_id' => $id));
		$temp = $query->row();
		$this->db->select('swayamsevaks.contact_id, swayamsevaks.first_name, swayamsevaks.last_name, responsibilities.responsibility');
		$this->db->from('swayamsevaks');
		$this->db->join('responsibilities', 'responsibilities.shakha_id = ' . $temp->shakha_id . ' AND responsibilities.swayamsevak_id = swayamsevaks.contact_id');
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
		//$query = $this->db->select('contact_id, first_name, last_name')->from('swayamsevaks')->where('household_id', $data['query']->row()->household_id);
		//$temp->kk = $this->vsort($temp->kk, 'responsibility', true, false);
		return $temp;
	}

	function getShortDesc($var1)
	{
		$this->db->select('short_desc');
		$query = $this->db->getwhere('Ref_Code', array('REF_CODE' => $var1));
		return $query->row()->short_desc;
	}
	
	function getShakhaName($id)
	{
		$query = $this->db->select('name')->getwhere('shakhas', array('shakha_id' => $id));
		return $query->row()->name;
	}
}

?>