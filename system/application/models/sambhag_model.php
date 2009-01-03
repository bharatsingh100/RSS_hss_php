<?php

class Sambhag_model extends Model 
{
   
    function Sambhag_model()
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
		$r = $this->db->getwhere('responsibilities', array('swayamsevak_id' => $data['swayamsevak_id'], 'responsibility' => $data['responsibility'], 'sambhag_id' => $data['sambhag_id']));
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
	
	function get_swayamsevaks($num, $offset, $sambhag_id, $sort_by)
	{
		/*$shakhas = $this->db->get('shakhas', array('sambhag_id' => $sambhag_id))->result();
		$shakha_ids = '';
		foreach($shakhas as $shakha)
			$shakha_ids[] = "$shakha->shakha_id";*/
		$shakha_ids = $this->get_shakhas($sambhag_id);
		$shakha_ids = '('.implode(',',$shakha_ids).')';

		$query = $this->db->select('contact_id, CONCAT(first_name, \' \', last_name) as name, city, ph_home as phone, ph_home, ph_mobile, ph_work, email, birth_year, shakha_id')->orderby($sort_by, 'asc')->getwhere('swayamsevaks', 'shakha_id IN ' . $shakha_ids, $num, $offset);

		return $query;
	}
	
	function get_shakhas($sambhag_id)
	{
		$shakhas = $this->db->get('shakhas', array('sambhag_id' => $sambhag_id))->result();
		$shakha_ids = '';
		foreach($shakhas as $shakha)
			$shakha_ids[] = "$shakha->shakha_id";
		return $shakha_ids;
	}
	
	function getShakhaName($id)
	{
		$query = $this->db->select('name')->getwhere('shakhas', array('shakha_id' => $id));
		return $query->row()->name;
	}
	
	function getSambhagInfo($id)
	{
		
		//Get Vibhag Information
		$this->db->like('REF_CODE', $id);
		$this->db->orderby('short_desc', 'asc'); 
		$v->vibhags = $this->db->select('REF_CODE, short_desc')->getwhere('Ref_Code', array('DOM_ID' => 2))->result();

		//Get Vibhag Karyakarta Information
		foreach($v->vibhags as & $temp) 
		{
			$vibhag_id = $temp->REF_CODE;
			$this->db->select('swayamsevaks.contact_id, swayamsevaks.first_name, swayamsevaks.last_name, responsibilities.responsibility');
			$this->db->from('swayamsevaks');
			$this->db->orderby('responsibilities.responsibility');
			$this->db->join('responsibilities', "responsibilities.vibhag_id = '$vibhag_id' AND responsibilities.swayamsevak_id = swayamsevaks.contact_id");
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
		
		//Get Sambhag Name
		$v->name = $this->getShortDesc($id);
		
		//Get Sambhag Karyakarta Information
		$this->db->select('swayamsevaks.contact_id, swayamsevaks.first_name, swayamsevaks.last_name, responsibilities.responsibility');
		$this->db->from('swayamsevaks');
		$this->db->orderby('responsibilities.responsibility');
		$this->db->join('responsibilities', "responsibilities.sambhag_id = '$id' AND responsibilities.swayamsevak_id = swayamsevaks.contact_id");
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
		$query = $this->db->getwhere('Ref_Code', array('REF_CODE' => $var1));
		
		return ($query->num_rows()) ? $query->row()->short_desc : '';
	}
	function getRefCodes($var1)
	{
		return $this->db->getwhere('Ref_Code', array('DOM_ID' => $var1));
	}

}

?>