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
		$r = $this->db->get_where('responsibilities', array('swayamsevak_id' => $data['swayamsevak_id'], 'responsibility' => $data['responsibility'], 'sambhag_id' => $data['sambhag_id']));
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

	function get_swayamsevaks($num, $offset, $sambhag_id, $sort_by)
	{
		/*$shakhas = $this->db->get('shakhas', array('sambhag_id' => $sambhag_id))->result();
		$shakha_ids = '';
		foreach($shakhas as $shakha)
			$shakha_ids[] = "$shakha->shakha_id";*/
		$shakha_ids = $this->get_shakhas($sambhag_id);
		$shakha_ids = '('.implode(',',$shakha_ids).')';

		$query = $this->db->select('contact_id, CONCAT(first_name, \' \', last_name) as name, city, ph_home as phone, ph_home, ph_mobile, ph_work, email, birth_year, shakha_id', FALSE)->order_by($sort_by, 'asc')->get_where('swayamsevaks', 'shakha_id IN ' . $shakha_ids, $num, $offset);

		return $query;
	}

	function get_shakhas($sambhag_id, $active = false)
	{
		if($active) $this->db->where('shakha_status', 1);
		$shakhas = $this->db->get_where('shakhas', array('sambhag_id' => $sambhag_id))->result();
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

	function getSambhagStatistics($id)
	{
		//Get Vibhag Information
		$this->db->where('DOM_ID', 2);
		$this->db->like('REF_CODE', $id);
		$this->db->order_by('short_desc', 'asc');
		$vibhags = $this->db->select('REF_CODE as vibhag_id, short_desc as name')->get('Ref_Code')->result();


		foreach($vibhags as &$vibhag) {
			$vibhag->active_shakhas = $this->db->select('COUNT(shakha_id) as count')
												->get_where('shakhas', array('shakha_status' => 1, 'vibhag_id' => $vibhag->vibhag_id))
												->row()->count;
			$vibhag->sampark_kendras = $this->db->select('COUNT(shakha_id) as count')
												->get_where('shakhas', array('shakha_status' => 0, 'vibhag_id' => $vibhag->vibhag_id))
												->row()->count;
			$vibhag->weekly_shakhas = $this->db->select('COUNT(shakha_id) as count')
												->get_where('shakhas', array('shakha_status' => 1, 'vibhag_id' => $vibhag->vibhag_id, 'frequency' => 'WK'))
												->row()->count;
            $vibhag->karyakartas = $this->db->query("SELECT count(distinct(swayamsevak_id)) as count
														FROM `responsibilities`
														WHERE vibhag_id = '{$vibhag->vibhag_id}'
															OR nagar_id LIKE '{$vibhag->vibhag_id}%'
															OR shakha_id IN (select shakha_id FROM shakhas WHERE vibhag_id = '{$vibhag->vibhag_id}')")
												->row()->count;

		}
		//$data['total_shakhas'] = count($this->get_shakhas($id));
		//$data['active_shakhas'] = count($this->get_shakhas($id, true));
		//$data['sampark_kendras'] = $data['total_shakhas'] - $data['active_shakhas'];

		//$this->db->where(array('shakha_status' => 1, 'frequency' => 'WK', 'sambhag_id' => $id));
		//$data['weekly_shakhas'] = $this->db->select('COUNT(shakha_id) as count')->get('shakhas')->row()->count;

		return $vibhags;
	}

	function getSambhagInfo($id)
	{

		//Get Vibhag Information
		$this->db->where('DOM_ID', 2);
		$this->db->like('REF_CODE', $id);
		$this->db->order_by('short_desc', 'asc');
		$v->vibhags = $this->db->select('REF_CODE, short_desc')->get('Ref_Code')->result();

		//Get Vibhag Karyakarta Information
		foreach($v->vibhags as & $temp)
		{
			$vibhag_id = $temp->REF_CODE;
			$this->db->select('swayamsevaks.contact_id, swayamsevaks.first_name, swayamsevaks.last_name, responsibilities.responsibility');
			$this->db->from('swayamsevaks');
			$this->db->order_by('responsibilities.responsibility');
			$this->db->join('responsibilities', "responsibilities.swayamsevak_id = swayamsevaks.contact_id");
			$this->db->where("responsibilities.vibhag_id = '$vibhag_id'");
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
		$this->db->order_by('responsibilities.responsibility');
		$this->db->join('responsibilities', "responsibilities.swayamsevak_id = swayamsevaks.contact_id");
		$this->db->where("responsibilities.sambhag_id = '$id'");
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

    //Get total counts of contacts for Vibhag by Categories
    function getSambhagContacts($id) {

        $shakhas = $this->db->select('shakha_id, name, shakha_status')->get_where('shakhas', array('sambhag_id' => $id, 'shakha_status' => 1));

        $v = '';
        if($shakhas->num_rows()) {
            $shakhas = $shakhas->result();
            $sid = array();
            foreach($shakhas as $shakha)
                $sid[] = $shakha->shakha_id;

            $sid = implode(', ', $sid);

            //Replace so many queries with Single Query for Vibhag
            $yr = date('Y');

            $ag['shishu'] = $yr - 6;
            $ag['bala'] = $yr - 12;
            $ag['kishor'] = $yr - 19;
            $ag['yuva'] = $yr - 25;
            $ag['tarun'] = $yr - 50;

            //$v['shakha'] = $this->db->select('shakha_id')->get_where('shakhas', "shakha_id IN ($sid)")->row();
            $this->db->where("shakha_id IN ($sid)");
            $v['sevikas'] = $this->db->select('contact_id')->get_where('swayamsevaks', array('gender' => 'F'))->num_rows();
            $this->db->where("shakha_id IN ($sid)");
            $v['swayamsevaks'] = $this->db->select('contact_id')->get_where('swayamsevaks', array('gender' => 'M'))->num_rows();
            $v['families'] = $this->db->select('DISTINCT household_id', FALSE)->get_where('swayamsevaks', "shakha_id IN ($sid)")->num_rows();
            $v['contacts'] = $this->db->select('contact_id')->get_where('swayamsevaks', "shakha_id IN ($sid)")->num_rows();
            $v['shishu'] = $this->db->select('contact_id')->get_where('swayamsevaks', 'birth_year > '. $ag['shishu']." AND shakha_id IN ($sid)")->num_rows();
            $v['bala'] = $this->db->select('contact_id')->get_where('swayamsevaks', 'birth_year BETWEEN '.$ag['bala'].' AND '. $ag['shishu']." AND shakha_id IN ($sid)")->num_rows();
            $v['kishor'] = $this->db->select('contact_id')->get_where('swayamsevaks', 'birth_year BETWEEN '.$ag['kishor'].' AND '. $ag['bala']." AND shakha_id IN ($sid)")->num_rows();
            $v['yuva'] = $this->db->select('contact_id')->get_where('swayamsevaks', 'birth_year BETWEEN '.$ag['yuva'].' AND '. $ag['kishor']." AND shakha_id IN ($sid)")->num_rows();
            $v['tarun'] = $this->db->select('contact_id')->get_where('swayamsevaks', 'birth_year BETWEEN '.$ag['tarun'].' AND '. $ag['yuva']." AND shakha_id IN ($sid)")->num_rows();
            $v['praudh'] = $this->db->select('contact_id')->get_where('swayamsevaks', 'birth_year > '.$ag['tarun'] . " AND shakha_id IN ($sid)")->num_rows();
            $v['phone'] = $this->db->select('contact_id')->get_where('swayamsevaks', '(ph_mobile != \'\' OR ph_home != \'\' OR ph_work != \'\')' . " AND shakha_id IN ($sid)")->num_rows();
            $v['email'] = $this->db->select('contact_id')->get_where('swayamsevaks', 'email != \'\' AND email_status = \'Active\' ' . " AND shakha_id IN ($sid)")->num_rows();
            $v['email_unactive'] = $this->db->get_where('swayamsevaks', 'email != \'\' AND email_status != \'Active\' ' . " AND shakha_id IN ($sid)")->num_rows();

        }

        return $v;
    }
}

?>
