<?php

class Shakha_model extends Model
{

  function Shakha_model()
  {
    parent::Model();
  }

  function add_email_list()
  {
    foreach($_POST as $key => $value){
    	$d[$key] = $value;
    }
    unset($d['button']);
    $d['address'] = strtolower($d['address']);
    $d['members'] = serialize($d['members']);
    $d['status'] = 'Creating';
    $d['owner'] = $this->session->userdata('contact_id');
    $d['owner_pass'] = 'abhi1986';
    if(!isset($d['mod2']) || $d['mod2'] == '') $d['mod2'] = 0;
    if(!isset($d['mod3']) || $d['mod3'] == '') $d['mod3'] = 0;
    $this->db->insert('lists', $d);
    //$d['level'] = 'sh';

  }

  /**
   * Remove responsibility of a swayamsevak at shakha level
   * @param int $shakha_id
   * @param int $ss_id
   * @param int $resp_id
   * @return void
   */
  function delete_responsibility($shakha_id, $ss_id, $resp_id) {
    $this->db->where('shakha_id', $shakha_id);
    $this->db->where('responsibility', $resp_id);
    $this->db->where('swayamsevak_id', $ss_id);

    if($this->db->delete('responsibilities')) {
      //Create Activities log
      $data = array('responsibility' => $resp_id);
      $contact_id = $this->session->userdata('contact_id') ? $this->session->userdata('contact_id') : 0;
      $this->activities->add_activity($contact_id, $ss_id, 'responsibility', 'removed', $data, $shakha_id);
    }
  }

  function add_responsibility()
  {
    foreach($_POST as $key => $val){
    	$data[$key] = trim($val);
    }
    $data['swayamsevak_id'] = $data['name'];
    $data['responsibility'] = $data['resp'];
    unset($data['button']);
    unset($data['name']);
    unset($data['resp']);

    //Add responsibility if it doesn't alreayd exits
    $r = $this->db->get_where('responsibilities', array('swayamsevak_id' => $data['swayamsevak_id'], 'responsibility' => $data['responsibility'], 'shakha_id' => $data['shakha_id']));
    if(!$r->num_rows())
    {
      $this->db->insert('responsibilities', $data);

      //Mark the contact as regular attendee if he has been assigned a responsibility
      $d['contact_type'] = 'RA';
      $this->db->where('contact_id', $data['swayamsevak_id'])->update('swayamsevaks', $d);

      //Create Activities log
      $contact_id = $this->session->userdata('contact_id') ? $this->session->userdata('contact_id') : 0;
      $this->activities->add_activity($contact_id, $data['swayamsevak_id'], 'responsibility', 'assigned', $data, $data['shakha_id']);

      //Set the initial password of swayamsevak who has been assigned the responsibilty
      $var = $this->db->get_where('swayamsevaks', array('contact_id' => $data['swayamsevak_id']));
    		//$var = $var->row();
    		if($var->num_rows() && $var->row()->password == '' && trim($var->row(0)->email) != '' )
    		{
    		  //$this->load->library('encrypt');
    		  $t['password'] = sha1($var->row()->email);
    		  $t['passwordmd5'] = md5($var->row()->email);
    		  $this->db->where('contact_id', $data['swayamsevak_id']);
    		  $this->db->update('swayamsevaks', $t);
    		}
    		return true;
    }
    else{
      $this->session->set_userdata('message', 'You cannot assign same responsibility more than once.');
      return false;
    }

  }

  function get_sankhyas($id, $date)
  {
    $q = $this->db->get_where('sankhyas', array('shakha_id' => $id, 'date' => $date));
    return $q->result();
  }

  function insert_sankhya()
  {
    foreach($_POST as $key => $val){
    	$data[$key] = trim($val);
    }
    $data['contact_id'] = $this->session->userdata('contact_id');
    if(empty($data['contact_id']) || $data['contact_id'] == '' ) $data['contact_id'] = 0;
    if(empty($data['shakha_id']) || $data['shakha_id'] == '' ) $data['shakha_id'] = 0;

    $data['ip'] = $this->input->ip_address();
    $data['total'] = $data['shishu_m'] + $data['shishu_f'] + $data['bala_f']
                      + $data['bala_m'] + $data['kishor_m'] + $data['kishor_f']
                      + $data['yuva_m'] + $data['yuva_f'] + $data['tarun_m']
                      + $data['tarun_f'] + $data['praudh_m'] + $data['praudh_f'];
    unset($data['button']);

    //If sankhya for that week already exists then update
    $exists = $this->db->get_where('sankhyas', array('date' => $data['date'], 'shakha_id' => $data['shakha_id']));
    if($exists->num_rows()) {
      $this->db->where('shakha_id', $data['shakha_id']);
      $this->db->where('date', $data['date']);
      $this->db->update('sankhyas', $data);
    }
    else {
      $this->db->insert('sankhyas', $data);
    }

    //Update activities table
    $contact_id = $this->session->userdata('contact_id') ? $this->session->userdata('contact_id') : 0;
    $this->activities->add_activity($contact_id, $this->db->insert_id(), 'sankhya', 'updated', $data, $data['shakha_id']);
  }

  function insert_sny_count()
  {
    $keys = array_keys($_POST);
    foreach($keys as $key){
      $data[$key] = $this->input->post($key);
    }

    $data['contact_id'] = $this->session->userdata('contact_id');
    if(empty($data['contact_id']) || $data['contact_id'] == '' ) $data['contact_id'] = 0;
    if(empty($data['shakha_id']) || $data['shakha_id'] == '' ) $data['shakha_id'] = 0;

    $data['ip'] = $this->input->ip_address();
    $data['total'] = $data['others_m'] + $data['others_f'] + $data['bala_f']
                + $data['bala_m'] + $data['kishor_m'] + $data['kishor_f']
                + $data['yuva_m'] + $data['yuva_f'] + $data['tarun_m']
                + $data['tarun_f'] + $data['praudh_m'] + $data['praudh_f'];

    $data['total_ss'] = $data['week1_ss'] + $data['week2_ss'] + $data['week3_ss'];
    $data['total_s'] = $data['week1_s'] + $data['week2_s'] + $data['week3_s'];

    unset($data['button']);

    //If sankhya for that week already exists then update
    $exists = $this->db->get_where('sny', array('shakha_id' => $data['shakha_id']), 1);
    if($exists->num_rows()) {
      $this->db->where('shakha_id', $data['shakha_id']);
      $this->db->update('sny', $data);
    }
    else {
      $this->db->insert('sny', $data);
    }

    //Update activities table
    $contact_id = $this->session->userdata('contact_id') ? $this->session->userdata('contact_id') : 0;
    $this->activities->add_activity($contact_id, NULL, 'sny', 'updated', $data, $data['shakha_id']);
  }

  function update_shakha($id)
  {
    foreach($_POST as $key => $val){
    	$data[$key] = trim($val);
    }

    $data['sambhag_id'] = substr($data['vibhag_id'], 0, 2);
    $data['nagar_id'] = '';
    //In case Shakha is part of a Nagar
    if(strlen($data['vibhag_id']) == 6) {
      $data['nagar_id'] = $data['vibhag_id'];
      $data['vibhag_id'] = substr($data['nagar_id'], 0, 4);
    }

    unset($data['save']);
    if($this->db->update('shakhas', $data, array('shakha_id' => $id))) {
      $contact_id = $this->session->userdata('contact_id') ? $this->session->userdata('contact_id') : 0;
      $this->activities->add_activity($contact_id, NULL, 'information', 'updated', $data, $id);
    }

  }

  function get_swayamsevaks($num, $offset, $shakha_id, $sort_by)
  {
    $sort_by = ($sort_by === 'name') ? 'first_name' : $sort_by;

    $query = $this->db->select('household_id, contact_id, CONCAT(first_name, \' \', last_name) as name, city, ph_home as phone, ph_home, ph_mobile, ph_work, email, birth_year, gana, gatanayak', FALSE)->order_by($sort_by, 'asc')->get_where('swayamsevaks', array('shakha_id' => (int)$shakha_id), $num, $offset);
    return $query;
  }
  function getShakhaName($id)
  {
    $query = $this->db->select('name')->get_where('shakhas', array('shakha_id' => $id));
    return $query->row()->name;
  }
  function getShakhaInfo($id)
  {
    $query = $this->db->get_where('shakhas', array('shakha_id' => $id));
    $temp = $query->row();

    $shakha_id = $temp->shakha_id;
    $this->db->select('swayamsevaks.contact_id, swayamsevaks.first_name, swayamsevaks.last_name, responsibilities.responsibility');
    $this->db->from('swayamsevaks');
    $this->db->order_by('responsibilities.responsibility');
    $this->db->join('responsibilities','responsibilities.swayamsevak_id = swayamsevaks.contact_id');
    $this->db->where("responsibilities.shakha_id = $shakha_id");

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
    $this->db->order_by('date', 'desc');
    $j = $this->db->get_where('sankhyas', array('shakha_id' => $id));
    $temp->sankhyas = $j->result();
    return $temp;
  }
  function insert_ss()
  {
    foreach($_POST as $key => $val)
    $data[$key] = trim($val);
    $max_hh = $this->db->select('MAX(household_id)')->get('swayamsevaks')->result_array();
    $max_hh = $max_hh[0]['MAX(household_id)'] + 1;

    //Split Name into First and Last
    $name = str_word_count(trim($data['name']), 2);
    unset($data['name']);

    //If there is Last Name then set it otherwise set it to none.
    if(count($name) > 1)
    {
      $data['last_name'] = ucwords(strtolower(array_pop($name)));
      $data['first_name'] = ucwords(strtolower(implode(' ',$name)));
    }
    else
    {
      $data['first_name'] = ucwords(strtolower(array_pop($name)));
      $data['last_name'] = '';
    }

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

    if($this->db->insert('swayamsevaks', $data)) {
      $contact_id = $this->session->userdata('contact_id') ? $this->session->userdata('contact_id') : 0;
      $this->activities->add_activity($contact_id, $this->db->insert_id(), 'profile', 'added', $data, $data['shakha_id']);
    }
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

        //if($d['last_name'] == '') $d['last_name'] = '';
        //if($d['first_name'] == '') $d['first_name'] = '';
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
      $d['gana'] = isset($contacts[$i]['gana']) ? $this->_format_gana(ucwords(strtolower($contacts[$i]['gana']))) : '';
      //$d['gana'] = $this->_format_gana($d['gana']);

      $this->db->insert('swayamsevaks', $d);
    }
  }

  //Convert Gana from Words to Numbers for DB
  function _format_gana($gana){

    switch($gana){
      case 'Shishu': $gana = 1; break;
      case 'Bala': $gana = 2; break;
      case 'Kishor': $gana = 3; break;
      case 'Yuva': $gana = 4; break;
      case 'Tarun': $gana = 5; break;
      case 'Praudh': $gana = 6; break;
      //default: $gana = ''; break;
    }
    return $gana;
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
    $query = $this->db->get_where('Ref_Code', array('REF_CODE' => $var1));

    return ($query->num_rows()) ? $query->row()->short_desc : '';
  }
  function getRefCodes($var1)
  {
    return $this->db->get_where('Ref_Code', array('DOM_ID' => $var1));
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
    $this->db->join('responsibilities', 'responsibilities.swayamsevak_id = swayamsevaks.contact_id')->where('responsibilities.shakha_id = '.$id);

    $result = $this->db->get();
    //Task: Fix Gatanayak Query
    if($result->num_rows())
    {
      $count = $result->num_rows();
      for($i = 0; $i < $count; $i++)
      $result->row($i)->num = $this->db->get_where('swayamsevaks', array('gatanayak' => $result->row($i)->contact_id))->num_rows();
    }
    return $result->result();
  }

  function capitalizeName($name) {
    $name = strtolower($name);
    $name = join("-", array_map('ucwords', explode("-", $name)));
    return $name;
  }

  function list_members($list_id) {
    $l = $this->db->get_where('lists', array('id' => $list_id))->row();
    $emails = unserialize(gzuncompress($l->emails));
    //var_dump($emails);
    $emails = '(\'' . implode("','", $emails) . "')";
    $this->db->select('contact_id, first_name, last_name');
    $this->db->order_by('first_name');
    $result = $this->db->get_where('swayamsevaks', 'email IN ' . $emails)->result_array();
    return $result;
    //var_dump($result);
    //die();
  }

  function sny_statistics($shakha_id)
  {
    $results = array();

    $this->db->select('sh.name, sh.city, sh.state, sh.vibhag_id, sh.nagar_id, sh.sambhag_id, sny.*');
    $this->db->from('sny');
    $this->db->join('shakhas sh', 'sh.shakha_id = sny.shakha_id');
    $this->db->order_by('sh.sambhag_id, sh.vibhag_id');
    $results['counts'] = $this->db->get()->result();

    //Get Short Description names of Nagar / Vibhag and Sambhag
    $results['descriptions'] = array();
    $this->db->select('REF_CODE, short_desc, DOM_ID');
    $records = $this->db->get('Ref_Code')->result();

    foreach($records as $record) {
      $results['descriptions'][$record->REF_CODE . $record->DOM_ID] = $record->short_desc;
    }

    //Total Counts
    $this->db->select('sum(total) as participants, sum(total_ss) as ss_counts, sum(total_s) as s_counts');
    $results['totals'] = $this->db->get('sny')->row();
    return $results;
  }
}

?>