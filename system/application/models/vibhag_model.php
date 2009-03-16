<?php

class Vibhag_model extends Model
{

    function Vibhag_model()
    {
        parent::Model();
    }


	function add_email_list()
	{
		foreach($_POST as $key => $value)
			$d[$key] = $value;
		unset($d['button']);
		$d['address'] = strtolower($d['address']);
        $d['members'] = serialize($d['members']);
        $d['status'] = 'Creating';
        $d['owner'] = $this->session->userdata('contact_id');
        $d['owner_pass'] = 'abhi1986';

        //Set Moderator to 0 if none is assigned
        if(!isset($d['mod2']) || $d['mod2'] == '') $d['mod2'] = 0;
        if(!isset($d['mod3']) || $d['mod3'] == '') $d['mod3'] = 0;
		$this->db->insert('lists', $d);

	}

	function add_responsibility()
	{
		//Get Information from POST array
		foreach($_POST as $key => $val)
			$data[$key] = trim($val);
		$data['swayamsevak_id'] = $data['name'];
		$data['responsibility'] = $data['resp'];
		unset($data['button']);
		unset($data['name']);
		unset($data['resp']);

		//Check if the KK already has that responsibility assigned
		$r = $this->db->getwhere('responsibilities', array('swayamsevak_id' => $data['swayamsevak_id'], 'responsibility' => $data['responsibility'], 'vibhag_id' => $data['vibhag_id']));
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

		//Get the Contact's information and set password = to his email if blank.
		$var = $this->db->getwhere('swayamsevaks', array('contact_id' => $data['swayamsevak_id']));
		if($var->num_rows() && $var->row(0)->password == '' && trim($var->row(0)->email) != '' )
		{
			$t['password'] = sha1($var->row(0)->email);
			$t['passwordmd5'] = md5($var->row(0)->email);
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

	function insert_shakha()
	{
		foreach($_POST as $key => $val)
			$data[$key] = trim($val);
		$data['last_mod'] = $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name');
		unset($data['save']);
		$this->db->insert('shakhas', $data);
	}

	function get_swayamsevaks($num, $offset, $vibhag_id, $sort_by)
	{
		/*$shakhas = $this->db->get('shakhas', array('vibhag_id' => $vibhag_id))->result();
		$shakha_ids = '';
		foreach($shakhas as $shakha)
			$shakha_ids[] = "$shakha->shakha_id";*/
		$shakha_ids = $this->get_shakhas($vibhag_id);
		$shakha_ids = '('.implode(',',$shakha_ids).')';

		$query = $this->db->select('household_id, contact_id, CONCAT(first_name, \' \', last_name) as name, city, ph_home as phone, ph_home, ph_mobile, ph_work, email, birth_year, shakha_id')->orderby($sort_by, 'asc')->getwhere('swayamsevaks', 'shakha_id IN ' . $shakha_ids, $num, $offset);

		return $query;
	}

	function get_shakhas($vibhag_id)
	{
		$shakhas = $this->db->getwhere('shakhas', array('vibhag_id' => $vibhag_id))->result();
		$shakha_ids = '';
		foreach($shakhas as $shakha)
			$shakha_ids[] = $shakha->shakha_id;
		return $shakha_ids;
	}

  function getShakhaName($id)
	{
		$query = $this->db->select('name')->getwhere('shakhas', array('shakha_id' => $id));
		return $query->row()->name;
	}

    function getVibhagShakhaStats($id) {

        $shakhas = $this->db->select('shakha_id, name, shakha_status')->getwhere('shakhas', array('vibhag_id' => $id, 'shakha_status' => 1));
        //$shakhas = $query->result();
        $today = date('Y-m-d');
        $month_beg = date('Y-m') . '-00';//strtotime(date('F Y'));
        $last_month_beg = date('Y-m-d', strtotime('-1 month', strtotime(date('F Y'))));
        $last_last_month = date('Y-m-d', strtotime('-2 month', strtotime(date('F Y'))));
        $this_year = date('Y') . '-00-00';
        $last_year = date('Y') - 1 .  '-00-00';

        if($shakhas->num_rows()) {
            $shakhas = $shakhas->result_array();
            foreach($shakhas as &$shakha) {
                $shakha['this_month']       = $this->getSankhya($shakha['shakha_id'], $month_beg, $today);
                $shakha['last_month']       = $this->getSankhya($shakha['shakha_id'], $last_month_beg, $month_beg);
                $shakha['last_last_month']  = $this->getSankhya($shakha['shakha_id'], $last_last_month, $last_month_beg);
                $shakha['this_year']        = $this->getSankhya($shakha['shakha_id'], $this_year, $today);
                $shakha['last_year']        = $this->getSankhya($shakha['shakha_id'], $last_year, $this_year);
                //$shakha['contacts']         = $this->getShakhaContacts($shakha['shakha_id']);
            }
        }

        return $shakhas;
        /*

        $v['sankhya'] = $this->db->getwhere('sankhyas', array('shakha_id' => $id))->result();
        $v['pageTitle'] = 'Vibhag Statistics';*/
    }

/*
    function getVibhagContacts($id) {
        $shakha['contacts']     = $this->getShakhaContacts($id);
        $temp['swayamsevaks']   = $shakha['contacts']['swayamsevaks'];
        $temp['families']       = $shakha['contacts']['families'];
        $temp['shishu']         = $shakha['contacts']['shishu'];
        $temp['bala']           = $shakha['contacts']['bala'];
        $temp['kishor']         = $shakha['contacts']['kishor'];
        $temp['yuva']           = $shakha['contacts']['yuva'];
        $temp['tarun']          = $shakha['contacts']['tarun'];
        $temp['praudh']         = $shakha['contacts']['praudh'];
        $temp['phone']          = $shakha['contacts']['phone'];
        $temp['email']          = $shakha['contacts']['email'];
        $temp['email_unactive'] = $shakha['contacts']['email_unactive'];

        return $temp;
    }*/

    //Get Sankhya for Each Shakha based on dates
    function getSankhya($shakha_id, $start, $end, $function = 'avg'){
        $this->db->where("date <= '$end'");
        $this->db->where("date >= '$start'");
        $this->db->where('shakha_id', $shakha_id);
        if('avg' == $function) {
            $this->db->where('total != 0');
            $this->db->select('AVG(total) as ans');
        } elseif ('sum' == $function) {
            $this->db->select('SUM(total) as ans');
        }

        $result = $this->db->get('sankhyas')->row();

        return $result->ans;

    }

    //Sum the Sankhya for Vibhag from Shakhas
    function getVibhagSankhya($id, $shakhas = '') {
        $temp = '';
        if($shakhas == '') $shakhas = $this->getVibhagShakhaStats($id);
        foreach($shakhas as $shakha) {
            $temp['this_month'][]     = $shakha['this_month'];
            $temp['last_month'][]     = $shakha['last_month'];
            $temp['last_last_month'][] = $shakha['last_last_month'];
            $temp['this_year'][]      = $shakha['this_year'];
            $temp['last_year'][]      = $shakha['last_year'];
        }

        foreach($temp as &$t1) {
            $t1['total'] = array_sum($t1);
        }

        return $temp;
    }

    //Get total counts of contacts for Vibhag by Categories
    function getVibhagContacts($id) {

        $shakhas = $this->db->select('shakha_id, name, shakha_status')->getwhere('shakhas', array('vibhag_id' => $id, 'shakha_status' => 1));

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

            //$v['shakha'] = $this->db->select('shakha_id')->getwhere('shakhas', "shakha_id IN ($sid)")->row();
            $this->db->where("shakha_id IN ($sid)");
            $v['sevikas'] = $this->db->select('contact_id')->getwhere('swayamsevaks', array('gender' => 'F'))->num_rows();
            $this->db->where("shakha_id IN ($sid)");
            $v['swayamsevaks'] = $this->db->select('contact_id')->getwhere('swayamsevaks', array('gender' => 'M'))->num_rows();
            $v['families'] = $this->db->select('DISTINCT household_id')->getwhere('swayamsevaks', "shakha_id IN ($sid)")->num_rows();
            $v['contacts'] = $this->db->select('contact_id')->getwhere('swayamsevaks', "shakha_id IN ($sid)")->num_rows();
            $v['shishu'] = $this->db->select('contact_id')->getwhere('swayamsevaks', 'birth_year > '. $ag['shishu']." AND shakha_id IN ($sid)")->num_rows();
            $v['bala'] = $this->db->select('contact_id')->getwhere('swayamsevaks', 'birth_year BETWEEN '.$ag['bala'].' AND '. $ag['shishu']." AND shakha_id IN ($sid)")->num_rows();
            $v['kishor'] = $this->db->select('contact_id')->getwhere('swayamsevaks', 'birth_year BETWEEN '.$ag['kishor'].' AND '. $ag['bala']." AND shakha_id IN ($sid)")->num_rows();
            $v['yuva'] = $this->db->select('contact_id')->getwhere('swayamsevaks', 'birth_year BETWEEN '.$ag['yuva'].' AND '. $ag['kishor']." AND shakha_id IN ($sid)")->num_rows();
            $v['tarun'] = $this->db->select('contact_id')->getwhere('swayamsevaks', 'birth_year BETWEEN '.$ag['tarun'].' AND '. $ag['yuva']." AND shakha_id IN ($sid)")->num_rows();
            $v['praudh'] = $this->db->select('contact_id')->getwhere('swayamsevaks', 'birth_year > '.$ag['tarun'] . " AND shakha_id IN ($sid)")->num_rows();
            $v['phone'] = $this->db->select('contact_id')->getwhere('swayamsevaks', '(ph_mobile != \'\' OR ph_home != \'\' OR ph_work != \'\')' . " AND shakha_id IN ($sid)")->num_rows();
            $v['email'] = $this->db->select('contact_id')->getwhere('swayamsevaks', 'email != \'\' AND email_status = \'Active\' ' . " AND shakha_id IN ($sid)")->num_rows();
            $v['email_unactive'] = $this->db->getwhere('swayamsevaks', 'email != \'\' AND email_status != \'Active\' ' . " AND shakha_id IN ($sid)")->num_rows();

        }

        return $v;
    }

  function getVibhagInfo($id)
	{

		//Get Vibhag's Nagar's Information
		$this->db->select('REF_CODE, short_desc');
		$this->db->where("REF_CODE LIKE '$id%'");
		$this->db->where('DOM_ID', 3);
		$query = $this->db->get('Ref_Code');

		if($query->num_rows()) {
			$nagar = $query->result();
			foreach ($nagar as &$n)
			{
				$n->nagar_id = $nagar_id = $n->REF_CODE;
				$n->name = $n->short_desc;
				$this->db->select('swayamsevaks.contact_id, swayamsevaks.first_name, swayamsevaks.last_name, responsibilities.responsibility');
				$this->db->from('swayamsevaks');
				$this->db->orderby('responsibilities.responsibility');
				$this->db->join('responsibilities', "responsibilities.nagar_id = '$nagar_id' AND responsibilities.swayamsevak_id = swayamsevaks.contact_id");
				$query = $this->db->get();

				if($query->num_rows())
				{
					$i = 0;
					foreach($query->result() as $row)
					{
						$n->kk->$i = $row;
						$n->kk->$i->responsibility = $row->responsibility;
						$n->kk->$i->resp_title = $this->getShortDesc($row->responsibility);
						$i++;
					}
				}
			}
		}
		//Get Vibhag's Shakha Information
		$query = $this->db->getwhere('shakhas', array('vibhag_id' => $id));
		$t = $query->result();
		foreach($t as & $temp)
		{
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
			$j = $this->db->getwhere('sankhyas', array('shakha_id' => $shakha_id));
			$temp->sankhyas = $j->result();
		}


		//Get Vibhag Information
		if(isset($nagar)) $v->nagars = $nagar;
		$v->shakhas = $t;
		$v->name = $this->getShortDesc($id);

		$this->db->select('swayamsevaks.contact_id, swayamsevaks.first_name, swayamsevaks.last_name, responsibilities.responsibility');
		$this->db->from('swayamsevaks');
		$this->db->orderby('responsibilities.responsibility');
		$this->db->join('responsibilities', "responsibilities.vibhag_id = '$id' AND responsibilities.swayamsevak_id = swayamsevaks.contact_id");
		$query = $this->db->get();
		if($query->num_rows())
		{
			$i = 0;
			foreach($query->result() as $row)
			{
				$v->kk->$i = $row;
			//	$v->kk->$i->responsibility = $row->responsibility;
				$v->kk->$i->resp_title = $this->getShortDesc($row->responsibility);
				$i++;
			}
		}
		return $v;
	}

	function insert_ss()
	{
		foreach($_POST as $key => $val)
			$data[$key] = trim($val);
		$max_hh = $this->db->select('MAX(household_id)')->get('swayamsevaks')->result_array();
		$max_hh = $max_hh[0]['MAX(household_id)'] + 1;

		$data['ph_home'] = ($data['ph_home']== 'Home...') ? '' : $this->reformat_phone_dash($data['ph_home']);
		$data['ph_mobile']= ($data['ph_mobile']== 'Mobile...') ? '' : $this->reformat_phone_dash($data['ph_mobile']);
		$data['ph_work'] = ($data['ph_work']== 'Work...') ? '' : $this->reformat_phone_dash($data['ph_work']);
		$data['email_status'] = ($data['email'] != '') ? 'Active' : '';
		$data['household_id'] = ((isset($data['household_id']) && !empty($data['household_id'])) ? $data['household_id'] : $max_hh);
		$data['first_name'] = ucwords(strtolower($data['first_name']));
		$data['last_name'] = ucwords(strtolower($data['last_name']));
		$data['city'] = ucwords(strtolower($data['city']));
		$data['street_add1'] = ucwords(strtolower($data['street_add1']));
		$data['street_add2'] = ucwords(strtolower($data['street_add2']));
		unset($data['save']);
		unset($data['add_family']);
		$this->db->insert('swayamsevaks', $data);
		//$temp->household_id = $max_hh;
		return $data;
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