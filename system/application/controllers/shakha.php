<?php
class Shakha extends Controller {


  function Shakha() {
    parent :: Controller();

    //If the user is not logged in, redirect them to login page
    //Set the redirect so that user comes back here after login
    if (!$this->session->userdata('logged_in')) {
      $this->session->set_userdata('message', 'Please login to access the system.');
      $this->session->set_userdata('redirect', $this->uri->uri_string());
      redirect('user');
    }

    $this->output->enable_profiler($this->config->item('debug'));
    $this->load->model('Shakha_model');
    $this->load->library('layout');
    $this->layout->setLayout("layout_shakha");

    $this->sny_year = $this->config->item('sny_year');

    //Check Permissions
    //$perml = Array of functions for lower shakha level permissions
    $perml = array('activities', 'browse', 'gata', 'addss', 'add_family_member', 'import_contacts', 'add_sankhya', 'responsibilities', 'edit_shakha', 'statistics', 'email_lists', 'create_list');
    $permh = array('import_contacts', 'add_sankhya', 'responsibilities', 'edit_shakha', 'email_lists', 'create_list');

    if (in_array($this->uri->segment(2), $perml)) {
      if (!$this->permission->is_shakha_kkl($this->uri->segment(3))) {
        $this->session->set_userdata('message', 'Your are not allowed to access the requested URL');
        redirect('shakha/view/' . $this->session->userdata('shakha_id'));
      }
      elseif (in_array($this->uri->segment(2), $permh) && !$this->permission->is_shakha_kkh($this->uri->segment(3))) {
        $this->session->set_userdata('message', 'Your are not allowed to access the requested URL');
        redirect('shakha/view/' . $this->session->userdata('shakha_id'));
      }
    }

    //Set Breadcrump variables
    $exception = array('add_email_list', 'insert_sankhya', 'add_family');
    if (!in_array($this->uri->segment(2), $exception)) {
      $rs = $this->db->get_where('shakhas', array('shakha_id' => $this->uri->segment(3)))->row();
      $this->session->set_userdata('bc_shakha', $rs->name);
      $this->session->set_userdata('bc_shakha_id', $rs->shakha_id);

      if (trim($rs->nagar_id) != '') {
        $this->session->set_userdata('bc_nagar_id', $rs->nagar_id);
        $this->session->set_userdata('bc_nagar', $this->Shakha_model->getShortDesc($rs->nagar_id));
      }

      $this->session->set_userdata('bc_vibhag', $this->Shakha_model->getShortDesc($rs->vibhag_id));
      $this->session->set_userdata('bc_vibhag_id', $rs->vibhag_id);
      $this->session->set_userdata('bc_sambhag', $this->Shakha_model->getShortDesc($rs->sambhag_id));
      $this->session->set_userdata('bc_sambhag_id', $rs->sambhag_id);
    }

    $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
    $this->output->set_header('Pragma: no-cache');

  }

  function email_lists($id) {
    $this->db->select('id,address,level_id,status,style,size,mod1,mod2,mod3');
    //$this->db->where('level_id = '.$id.' AND status = \'Active\' OR status = \'Creating'\');
    //Hide the Deleted E-mail Lists after 2 Days
    $this->db->where("modified > " . date('o-m-d', strtotime('-2 Days')));
    $d['lists'] = $this->db->get_where('lists', array('level_id' => $id))->result_array();

    foreach ($d['lists'] as & $list) {
      $list['address'] .= '@lists.hssusa.org';
      if ($list['mod1']) {
        $this->db->select('contact_id,first_name,last_name');
        $t = $this->db->get_where('swayamsevaks', array('contact_id' => $list['mod1']))->row();
        $list['mod1'] = anchor('profile/view/' . $t->contact_id, $t->first_name . ' ' . $t->last_name) . '<br \>';
      }
      if ($list['mod2']) {
        $this->db->select('contact_id,first_name,last_name');
        $t = $this->db->get_where('swayamsevaks', array('contact_id' => $list['mod2']))->row();
        $list['mod2'] = anchor('profile/view/' . $t->contact_id, $t->first_name . ' ' . $t->last_name) . '<br \>';
      }
      else
        $list['mod2'] = '';
      if ($list['mod3']) {
        $this->db->select('contact_id,first_name,last_name');
        $t = $this->db->get_where('swayamsevaks', array('contact_id' => $list['mod3']))->row();
        $list['mod3'] = anchor('profile/view/' . $t->contact_id, $t->first_name . ' ' . $t->last_name) . '<br \>';
      }
      else
        $list['mod3'] = '';
      $list['moderators'] = $list['mod1'] . $list['mod2'] . $list['mod3'];
      $list['details'] = ($list['status'] == 'Active') ? anchor('shakha/edit_list/' . $list['level_id'] . '/' . $list['id'], 'Details/Edit') : '';
      $list['style'] = ($list['style']) ? 'Un-Moderated' : 'Moderated';
      unset ($list['id']);
      unset ($list['mod1']);
      unset ($list['mod2']);
      unset ($list['mod3']);
      unset ($list['level_id']);
    }
    $d['shakha'] = $this->Shakha_model->getShakhaInfo($id);
    $d['pageTitle'] = 'Email Lists';
    $this->load->library('table');
    $this->table->set_heading('List Name', 'Status', 'Style', 'Size', 'Moderators', 'Details');
    $this->layout->view('shakha/email_lists', $d);
  }

  function edit_list($id, $list_id, $error = '') {

    if (isset ($_POST['button'])) {
      if (!isset ($_POST['members'])) {
        $this->session->set_userdata('message', 'Please select at least 1 group for E-mail list members.');
        redirect('shakha/edit_list/' . $id . '/' . $list_id);
      }
      if (!isset ($_POST['mod_pass']) || strlen($_POST['mod_pass']) < 5) {
        $this->session->set_userdata('message', 'Your password should be at least 5 characters long.');
        redirect('shakha/edit_list/' . $id . '/' . $list_id);
      }

      foreach ($_POST as $key => $val){
        $d[$key] = $val;
      }

      unset($d['button']);

      if (!isset ($d['mod3']) || $d['mod3'] == '') {
        $d['mod3'] = 0;
      }

      if (!isset ($d['mod2']) || $d['mod2'] == '') {
         $d['mod2'] = 0;
      }

      $d['members'] = serialize($d['members']);
      $this->db->update('lists', $d, array('id' => $list_id));

      $this->session->set_userdata('message', 'Your list was updated successfully.');
      redirect('shakha/email_lists/' . $id);

    }

    $c['lists'] = $this->db->get_where('lists', array('id' => $list_id))->row();
    $c['emails'] = $this->Shakha_model->list_members($list_id);
    $c['shakha'] = $this->Shakha_model->getShakhaInfo($c['lists']->level_id);
    $c['pageTitle'] = 'Edit E-mail list';
    $this->layout->view('shakha/edit_list', $c);
  }

  function activities($id) {
    $data['activities'] = $this->activities->get_activities('shakha', $id);
    $data['row'] = $this->Shakha_model->getShakhaInfo($id);
    $data['pageTitle'] = $data['row']->name . ' Activities';
    $this->layout->view('shakha/view-activities', $data);
  }

  function autocomplete(){
    $this->db->select('company')->like('company',$this->input->get('q'));
    $results = $this->db->get('swayamsevaks', 10);
    $out = '';
    foreach ($results->result() as $rs){
      $out .= $rs->company . "\n";
    }

    return $out;

  }

  function create_list($id, $error = '') {
    if ($error != '')
      $c['d'] = $error;
    $c['shakha'] = $this->Shakha_model->getShakhaInfo($id);
    $c['pageTitle'] = 'Create new e-mail list';
    $this->layout->view('shakha/create_list', $c);
  }

  function del_list($id, $list_id) {
    if (isset ($_POST['button2'])) {
      $d['status'] = 'Deleting';
      $this->db->update('lists', $d, array('id' => $list_id));
      $this->session->set_userdata('message', 'Your list was queued for deleting');
      redirect('shakha/email_lists/' . $id);
    }
    else
      redirect('shakha/email_lists/' . $id);
  }

  function add_email_list() {
    $ers = false;
    $error = array();

    foreach ($_POST as $key => & $value) {
      if ($key == 'members') {
        foreach ($value as $v)
          $error['members'][] = $v;
      }
      $error[$key] = $value;
    }

    if (!isset ($error['address']) || $error['address'] == '') {
      $error['msg'][] = 'You must enter the List Name';
      $ers = true;
    }
    if (!isset ($error['mod_pass']) || $error['mod_pass'] == '') {
      $error['msg'][] = 'Your must enter a password.';
      $ers = true;
    }
    if (!is_array($error['members']) || !count($error['members']))
      //Count returns 0 is variable is not set or array is empty
      {
      $error['msg'][] = 'You must select at least one member for your list';
      $ers = true;
    }
    if ($this->db->get_where('lists', array('address' => $error['address']))->num_rows()) {
      $error['msg'][] = 'The list name you selected, already exists. Choose another one.';
      $error['address'] = '';
      $ers = true;
    }

    if ($ers) {
      $this->session->set_userdata('message', 'Please correct the errors.');
      $this->create_list($this->input->post('level_id'), $error);
    }

    $this->Shakha_model->add_email_list();
    $this->session->set_userdata('message', 'Your list ' . $this->input->post('address') . '@lists.hssusa.org has been requested.');
    redirect('shakha/email_lists/' . $this->input->post('level_id'));

    //TODO: Add hidden shakha id parameter to from page
    //$d['level'] = 'sh';
    //level = 0-Shakha 1-vibhag 2-sambhag
    //type = 0 Moderated | 1 Unmoderated
    //members = 0 All Swayamsevaks | 1 Bala swayamsevaks | 2 Kishor Swayamsevaks | 3 Yuva Swayamsevaks
    // 4 Tarun Swayamsevaks | 5 Praudh swayamsevaks | 6 All Karyakartas
  }

  function statistics($id) {
    $yr = date('Y');
    $ag['shishu'] = $yr - 6;
    $ag['bala'] = $yr - 12;
    $ag['kishor'] = $yr - 19;
    $ag['yuva'] = $yr - 25;
    $ag['tarun'] = $yr - 50;
    $v['shakha'] = $this->db->get_where('shakhas', array('shakha_id' => $id))->row();
    $v['families'] = $this->db->select('DISTINCT household_id', false)->get_where('swayamsevaks', array('shakha_id' => $id))->num_rows();
    $v['contacts'] = $this->db->get_where('swayamsevaks', array('shakha_id' => $id))->num_rows();
    $v['swayamsevaks'] = $this->db->get_where('swayamsevaks', array('shakha_id' => $id, 'gender' => 'M'))->num_rows();
    $v['sevikas'] = $this->db->get_where('swayamsevaks', array('shakha_id' => $id, 'gender' => 'F'))->num_rows();
    $v['shishu'] = $this->db->get_where('swayamsevaks', 'birth_year > ' . $ag['shishu'] . ' AND shakha_id =' . $id)->num_rows();
    $v['bala'] = $this->db->get_where('swayamsevaks', 'birth_year BETWEEN ' . $ag['bala'] . ' AND ' . $ag['shishu'] . ' AND shakha_id =' . $id)->num_rows();
    $v['kishor'] = $this->db->get_where('swayamsevaks', 'birth_year BETWEEN ' . $ag['kishor'] . ' AND ' . $ag['bala'] . ' AND shakha_id =' . $id)->num_rows();
    $v['yuva'] = $this->db->get_where('swayamsevaks', 'birth_year BETWEEN ' . $ag['yuva'] . ' AND ' . $ag['kishor'] . ' AND shakha_id =' . $id)->num_rows();
    $v['tarun'] = $this->db->get_where('swayamsevaks', 'birth_year BETWEEN ' . $ag['tarun'] . ' AND ' . $ag['yuva'] . ' AND shakha_id =' . $id)->num_rows();
    $v['praudh'] = $this->db->get_where('swayamsevaks', 'birth_year < ' . $ag['tarun'] . ' AND shakha_id =' . $id)->num_rows();
    $v['phone'] = $this->db->get_where('swayamsevaks', '(ph_mobile != \'\' OR ph_home != \'\' OR ph_work != \'\') AND shakha_id =' . $id)->num_rows();
    $v['email'] = $this->db->get_where('swayamsevaks', 'email != \'\' AND email_status = \'Active\' AND shakha_id =' . $id)->num_rows();
    $v['email_unactive'] = $this->db->get_where('swayamsevaks', 'email != \'\' AND email_status != \'Active\' AND shakha_id =' . $id)->num_rows();
    $v['sankhyas'] = $this->db->order_by('date', 'desc')->get_where('sankhyas', array('shakha_id' => $id))->result();
    $v['pageTitle'] = $v['shakha']->name . ' Shakha Statistics';
    $v['invalid'] = $this->db->select('contact_id,first_name,last_name,email')->where('shakha_id', $id)->get_where('swayamsevaks', "email_status IN ('Unsubscribed','Bounced')")->result();

    $this->layout->view('shakha/statistics', $v);
  }

  function sny_stats($id, $year = NULL) {
    $this->output->enable_profiler(false);
    $this->load->dbutil();

    // Year maybe missing because it is not passed in via URL
    $year = empty($year) ? $this->config->item('sny_year') : $year;

    $this->db->select('sh.name, sh.city, sh.state, sh.vibhag_id, sh.nagar_id, sh.sambhag_id, sny.*');
    $this->db->from("sny_{$year} sny");
    $this->db->join('shakhas sh', 'sh.shakha_id = sny.shakha_id');

    $data['query'] = $this->db->get();
    $this->output->set_header("Content-type: application/vnd.ms-excel");
    $this->output->set_header("Content-disposition: csv; filename=SNY_Stats_" . date("M-d_H-i") . ".csv");
    $this->load->view('shakha/csv', $data);
    //$this->layout->view('shakha/sny_statistics', $v);
  }

  function sny_statistics($shakha_id, $year = NULL) {

    // Year maybe missing because it is not passed in via URL
    $year = empty($year) ? $this->config->item('sny_year') : $year;

    $data['counts'] = $this->Shakha_model->sny_statistics($shakha_id, $year);
    $data['year']   = $year;
    $data['pageTitle'] = 'SNY Statistics';
    $this->layout->view('shakha/sny_statistics', $data);
  }

  function del_responsibility($shakha_id, $ss_id, $resp_id) {
    $this->Shakha_model->delete_responsibility($shakha_id, $ss_id, $resp_id);
    //Remove Karyakarta from his gata members.
    //$d['gatanayak'] = '';
    //$this->db->update('swayamsevaks', $d, array('gatanayak' => $ss_id));

    $this->session->set_userdata('message', 'Responsibility deleted.');
    redirect('shakha/responsibilities/' . $shakha_id);

  }

  function responsibilities($id) {
    $submit = $this->input->post('button');
    if ($submit != '') {
      if ($_POST['name'] == 0 || $_POST['resp'] == 0)
        $this->session->set_userdata('message', 'Please fill the form with required information');
      else {
        if ($this->Shakha_model->add_responsibility())
          $this->session->set_userdata('message', 'Responsibility added sucessfully');
      }
    }
    //$this->load->library('ajax');
    $data['shakha'] = $id;
    $data['row'] = $this->Shakha_model->getShakhaInfo($id);
    $data['resp'] = $this->Shakha_model->getRefCodes(4)->result_array();
    $data['pageTitle'] = $data['row']->name;
    $data['names'] = $this->Shakha_model->get_swayamsevaks(1800000, 0, $id, 'name')->result();
    $this->layout->view('shakha/add_responsibility', $data);
  }

  function add_sankhya($id, $date = '') {
    $data['dates'] = $this->_getShakhaDate($id);

    if ($date == '') {
      if (sizeof($data['dates']) == 1) {
        $date = $data['dates'][0]['datemysql'];
      }
      else {
        foreach ($data['dates'] as $d) {
          $date = $d['selected'] ? $d['datemysql'] : '';
        }
      }
    }

    $v = $this->db->get_where('sankhyas', array('shakha_id' => $id, 'date' => $date));
    if ($v->num_rows()) {
      $data['sankhya'] = $v->row();
      $data['contact'] = $this->db->get_where('swayamsevaks', array('contact_id' => $data['sankhya']->contact_id))->row();
    }

    $data['shakha'] = $this->db->get_where('shakhas', array('shakha_id' => $id))->row();

    $data['pageTitle'] = 'Add Sankhya';

    $this->layout->view('shakha/add-sankhya', $data);
  }

  /**
   * Add SNY Count for each Shakha
   * @param $id Shakha ID
   * @param $year 4 Digits of the SNY Year
   */
  function sny_count($id, $year = NULL) {

    $data['year'] = empty($year) ? $this->config->item('sny_year') : $year;

    $record = $this->db->get_where('sny_' . $data['year'], array('shakha_id' => $id), 1);

    if ($record->num_rows()) {
      $data['sankhya'] = $record->row();

      $this->db->where('contact_id', $data['sankhya']->contact_id);
      $data['contact'] = $this->db->get('swayamsevaks')->row();
    }

    $data['shakha'] = $this->db->get_where('shakhas', array('shakha_id' => $id), 1)->row();

    $data['pageTitle'] = 'Submit SNY Count';

    $this->layout->view('shakha/add-sny-count', $data);
  }

  private function _getShakhaDate($id) {
    $shakha = $this->db->get_where('shakhas', array('shakha_id' => $id))->row();

    $wd = array(
      "Sunday" => 0,
      "Monday" => 1,
      "Tuesday" => 2,
      "Wednesday" => 3,
      "Thursday" => 4,
      "Friday" => 5,
      "Saturday" => 6
    );

    $day_number = $wd[$shakha->frequency_day];
    $t = getdate();
    //This Week//
    $start = $t[0] - (86400 * $t['wday']);
    $time = $start + (86400 * $day_number);

    $i = 0;
    do {
      $data[$i]['date'] = date('l, F j, o', $time);
      $data[$i]['datemysql'] = date('Y-m-d', $time);
      $data[$i]['selected'] = (date('W', $time) == date('W')) ? true : false;
      $time -= 604800;      //Subtract a week
    }while (++$i < 5);    //Last 5 weeks oonly

    return $data;
  }

  //Insert or Update Sankhya
  function insert_sankhya($id) {
    if ($this->input->post('shakha_id') && $this->input->post('shakha_id') != '') {
      $this->Shakha_model->insert_sankhya();
      $this->session->set_userdata('message', 'Sankhya added for your Shakha');
      redirect('shakha/view/' . $this->input->post('shakha_id'));
    }
    else {
      redirect('shakha/add_sankhya/' . $id);
    }
  }

  //Insert or Update SNY Count
  function insert_sny_count($id) {
    if ($this->input->post('shakha_id') && $this->input->post('shakha_id') != '') {
      $this->Shakha_model->insert_sny_count();
      $this->session->set_userdata('message', 'SNY Count added for your Shakha');
      redirect('shakha/sny_count/' . $this->input->post('shakha_id'));
    }
    else {
      redirect('shakha/sny_count/' . $id);
    }
  }

  function edit_shakha($id) {
    if (isset ($_POST['save'])) {
      $this->Shakha_model->update_shakha($id);
      $this->session->set_userdata('message', 'Shakha information was successfully updated.&nbsp;');
      redirect('shakha/view/' . $id);
    }
    $data['states'] = $this->Shakha_model->getStates();
    $data['row'] = $this->Shakha_model->getShakhaInfo($id);
    $data['pageTitle'] = $data['row']->name;
    $this->db->order_by('short_desc', 'desc');
    $vibhags = $this->db->select('REF_CODE, short_desc')->get_where('Ref_Code', array('DOM_ID' => 2))->result();

    foreach ($vibhags as $vibhag)
      $data['vibhags'][$vibhag->REF_CODE] = $vibhag->short_desc;

    $nagars = $this->db->select('REF_CODE, short_desc')->get_where('Ref_Code', array('DOM_ID' => 3))->result();

    foreach ($nagars as $nagar)
      $data['vibhags'][$nagar->REF_CODE] = $data['vibhags'][substr($nagar->REF_CODE, 0, 4)] . ' - ' . $nagar->short_desc . ' Nagar';

    ksort($data['vibhags']);
    $this->layout->view('shakha/edit_shakha', $data);
  }

  function karyakarta_csv_out($id) {
    $this->output->enable_profiler(false);
    $this->load->dbutil();

    $this->db->select('swayamsevaks.contact_id, swayamsevaks.first_name, swayamsevaks.last_name, responsibilities.responsibility');
    $this->db->from('swayamsevaks');
    $this->db->order_by('responsibilities.responsibility');
    $this->db->join('responsibilities', "responsibilities.swayamsevak_id = swayamsevaks.contact_id");
    $this->db->where("responsibilities.shakha_id = $id");
    $q = $this->db->get();
    $i = $q->num_rows() - 1;

    $query = $q->result_array();
    for (; $i > - 1; $i--)
      $query[$i]['responsibility'] = $this->Shakha_model->getShortDesc($query[$i]['responsibility']);

    //Set proper parameters for delimineting CSV file
    $delim = ",";
    $newline = "\r\n";

    $out = '';

    //Output CSV File headers (i.e. Name, E-mail etc.)
    foreach ($q->list_fields() as $name)
      $out .= ucwords($name) . $delim;

    $out = rtrim($out);
    $out .= $newline;

    // Next blast through the result array and build out the rows
    foreach ($query as $row) {
      foreach ($row as $item) {
        $out .= $item . $delim;
      }

      $out = rtrim($out);
      $out .= $newline;
    }

    $data['out'] = $out;

    //Set headers so that Browser prompots to download CSV file rather than show.
    $this->output->set_header("Content-type: application/vnd.ms-excel");
    $this->output->set_header("Content-disposition: csv; filename=" . url_title($this->Shakha_model->getShakhaName($id)) . '-Karyakartas-' . date("M-d_H-i") . ".csv");

    //Send data to special View file to print $out contents
    $this->load->view('shakha/kk_csv', $data);
  }

  function csv_out($id) {
    $this->output->enable_profiler(false);
    $this->load->dbutil();
    $this->db->select('contact_id, household_id, contact_type, gana, first_name, last_name, gender, birth_year, company, position, email, email_status, ph_mobile, ph_home, ph_work, street_add1, street_add2, city, state, zip, ssv_completed, notes');
    $data['query'] = $this->db->get_where('swayamsevaks', array('shakha_id' => $id));
    $this->output->set_header("Content-type: application/vnd.ms-excel");
    $this->output->set_header("Content-disposition: csv; filename=" . url_title($this->Shakha_model->getShakhaName($id)) . '-' . date("M-d_H-i") . ".csv");
    $this->load->view('shakha/csv', $data);
  }

  function browse($id = '', $order = 'name') {
    if ($id == '')
      $id = $this->session->userdata('shakha_id');
    $this->load->library('pagination');
    $config['base_url'] = base_url() . "shakha/browse/$id/$order/";
    $config['total_rows'] = $this->db->get_where('swayamsevaks', array('shakha_id' => $id))->num_rows();
    //$this->db->count_all('swayamsevaks');
    $config['per_page'] = '35';
    $config['full_tag_open'] = '<p>';
    $config['full_tag_close'] = '</p>';
    $config['uri_segment'] = 5;
    //		$config['post_url'] = $data['order'].'/'.$data['orderDir'];
    $this->pagination->initialize($config);

    $data['results'] = $this->Shakha_model->get_swayamsevaks($config['per_page'], $this->uri->segment(5), $id, $order);
    $data['pageTitle'] = 'Browse Swayamsevaks';
    $data['shakha_name'] = $this->Shakha_model->getShakhaName($id);

    $this->load->library('table');
    $this->table->set_heading('Name', 'City', 'Phone', 'E-mail', 'Gana', 'Gatanayak');
    $this->layout->view('shakha/list_ss', $data);
  }

  function view($id) {
    $data['row'] = $this->Shakha_model->getShakhaInfo($id);
    $data['pageTitle'] = $data['row']->name;
    $this->layout->view('shakha/view-shakha', $data);
  }

  function gata($id) {
    $data['row'] = $this->Shakha_model->getShakhaInfo($id);
    $data['gatas'] = '';
    foreach ($data['row']->kk as $kk) {
      $data['gatas'][$kk->contact_id] = $kk;
      $this->db->select('contact_id, first_name, last_name');
      $this->db->order_by('first_name');
      $this->db->where('gatanayak', $kk->contact_id);
      $t = $this->db->get('swayamsevaks');
      $data['gatas'][$kk->contact_id]->gata = ($t->num_rows()) ? $t->result() : array();
    }

    $data['pageTitle'] = $data['row']->name . ' Gatas';
    $this->layout->view('shakha/gata', $data);
  }

  function gata_csv($id) {
    $data['shakha'] = $this->Shakha_model->getShakhaInfo($id);

    //Get Gatanayak Information to find Swayamsevaks and populate CSV file
    $gatanayaks = '';
    $gataIDs = '';
    foreach ($data['shakha']->kk as $k) {
      $gataIDs[] = $k->contact_id;
      $gatanayaks[$k->contact_id] = $k->first_name . ' ' . $k->last_name;
    }

    // Create MySQL compatible format to find Swayamsevaks with specific Gatayanayaks only
    $g = '(' . implode(',', $gataIDs) . ')';

    $this->db->select('gatanayak, contact_id, household_id, first_name, last_name, gender, birth_year, email, email_status, ph_mobile, ph_home, ph_work, street_add1, street_add2, city, state, zip, notes');
    $this->db->order_by('gatanayak, household_id');
    $this->db->where('gatanayak IN ' . $g);
    $data['ss'] = $this->db->get('swayamsevaks');

    //Replace Gatanayak Contact ID with Full Name in CSV
    $q = $data['ss']->result_array();

    if ($data['ss']->num_rows()) {
      foreach ($q as & $y)
        $y['gatanayak'] = $gatanayaks[$y['gatanayak']];
    }

    //CSV File Output setters
    $delim = ",";
    $newline = "\r\n";
    $headers = $out = '';

    //Output CSV File header ...
    foreach ($data['ss']->list_fields() as $name) {
      $headers .= ucwords($name) . $delim;
    }

    $headers = rtrim($headers);
    $headers .= $newline;

    // Next blast through the result array and build out the rows
    foreach ($q as $row) {
      foreach ($row as $item) {
        $out .= $item . $delim;
      }
      $out = rtrim($out);
      $out .= $newline;
    }
    $data['out'] = $headers . $out;

    $this->output->set_header("Content-type: application/vnd.ms-excel");
    $this->output->set_header("Content-disposition: csv; filename=" . url_title($data['shakha']->name) . '-Gatas-' . date("M-d_H-i") . ".csv");
    $this->load->view('shakha/kk_csv', $data);

    /*$data['pageTitle'] = $data['row']->name.' Gatas';
    $this->layout->view('shakha/gata', $data); */
  }

  function addss($id, $var = '') {
    $data['shakha_name'] = $this->Shakha_model->getShakhaName($id);
    if ($var != '')
      $data['family'] = $var;
    $data['states'] = $this->Shakha_model->getStates();
    $data['gatanayak'] = $this->Shakha_model->getGatanayaks($id);
    $data['shakha_id'] = $id;
    $data['shakha_st'] = $this->db->select('state')->get_where('shakhas', array('shakha_id' => $id))->row()->state;
    $data['ctype'] = $this->db->select('REF_CODE, short_desc')->get_where('Ref_Code', 'DOM_ID = 11')->result();
    $data['ganas'] = $this->db->select('REF_CODE, short_desc')->get_where('Ref_Code', 'DOM_ID = 12')->result();
    $data['pageTitle'] = 'Add New Contact';
    $this->layout->view('shakha/add-swayamsevak', $data);
  }

  function add_family_member($shakha_id, $id) {
    $results = $this->db->get_where('swayamsevaks', array('contact_id' => $id))->row_array();
    $this->addss($shakha_id, $results);
  }

  function add_family($id) {
    //Redirect back to form if the Name is not set
    if (isset ($_POST) && trim($_POST['name']) == '') {
      redirect('shakha/addss/' . $this->input->post('shakha_id'));
    }

    $hhid = (isset ($_POST['household_id'])) ? $_POST['household_id'] : '';
    $data = $this->Shakha_model->insert_ss();
    $this->session->set_userdata('message', $this->input->post('name') . ' successfully added to the database.');
    if ($this->input->post('add_family')) {
      $data['household_id'] = (($hhid != '') ? $hhid : $data['household_id']);
      $this->addss($this->input->post('shakha_id'), $data);
    }
    else
      redirect('shakha/addss/' . $this->input->post('shakha_id'));
  }

  function import_contacts($id) {
    $data['pageTitle'] = 'Import Contacts';
    $data['shakha'] = $this->Shakha_model->getShakhaInfo($id);
    $this->layout->view('shakha/upload_contacts', $data);
    //User session error message to pass form failures
  }

  //E-mail me the uploaded file

  private function _processUpload(& $filename) {

    /*		if (!is_array($_FILES['contacts']))
    {
    $this->session->set_userdata('errs', true);
    $errors['msg'][] = "Please select a file to upload.";
    $this->session->set_userdata('errors', $errors);
    return false;
    }*/
    //$userdir = explode('/',$_SERVER['DOCUMENT_ROOT']);
    //$userdir = $userdir[2];
    $target_path = '/var/www/web2/uploads/';

    //$target_path = "/home/$userdir/uploads/";
    $filename = time() . '_' . $_FILES['contacts']['name'];
    $target_path = $target_path . basename($filename);

    $t = explode('.', trim($filename));
    if ($t[1] != 'csv' && $t[1] != 'xls' && $t[1] != 'xlsx') {
      $this->session->set_userdata('errs', true);
      $errors['msg'][] = "Please select a CSV/XLS file to upload.";
      $this->session->set_userdata('errors', $errors);
      return false;
    }

    if (move_uploaded_file($_FILES['contacts']['tmp_name'], $target_path)) {
      $filename = $target_path;
      shell_exec('chmod 0777 ' . $filename);
      return true;      //echo "The file ".  basename( $_FILES['uploadedfile']['name']). " has been uploaded";
    }
    else {
      $this->session->set_userdata('errs', true);
      $errors['msg'][] = "There was an error uploading the file, please try again!";
      $this->session->set_userdata('errors', $errors);
      return false;
    }
  }

  function upload_contacts($id) {
    if (isset ($_POST['button'])) {
      $file = '';
      if ($this->_processUpload($file)) {
        $shakha = $this->Shakha_model->getShakhaInfo($id);
        require_once "Swift.php";
        require_once "Swift/Connection/SMTP.php";
        $swift = & new Swift(new Swift_Connection_SMTP("localhost"));
        $subject = 'Uploaded Contacts for ' . $shakha->name;
        $msg = 'Contacts uploaded for ' . $shakha->name . ' ' . $shakha->city . ',' . $shakha->state . ' by ' . $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name');
        $msg .= "\n\n";

        $message = & new Swift_Message($subject);
        $message->attach(new Swift_Message_Part($msg));
        $message->attach(new Swift_Message_Attachment(new Swift_File($file), $file, 'text/csv'));
        $swift->send($message, 'zzzabhi@gmail.com', "crm_admin@hssusa.org");
      }
      else
        redirect('shakha/upload_contacts/' . $id);

      $this->session->set_userdata('message', 'Your File has been uploaded. You will be notified via e-mail when your contacts are added to database.');
      redirect('shakha/upload_contacts/' . $id);
    }

    $data['pageTitle'] = 'Import Contacts';
    $data['shakha'] = $this->Shakha_model->getShakhaInfo($id);
    $this->layout->view('shakha/email_contacts', $data);
  }

  function match_columns($id) {
    //Set status to 0 if notthing set
    //if($this->session->userdata('import_st') == '') $this->session->set_userdata('import_st', 0);
    //Upload File
    //$this->_processUploads(); //User session error message to pass form failures
    $d['pageTitle'] = 'Import Contacts';
    $file = '';
    if ($this->_processUpload($file)) {
      $fh = fopen($file, 'r+') or die("Coundn't open the file $file.");
      $buffer = fgets($fh);
      $d['list'] = explode(',', $buffer);
      $d['shakha'] = $this->Shakha_model->getShakhaInfo($id);
      $this->session->set_userdata('filename', $file);
      $this->layout->view('shakha/upload_contacts1', $d);
      //$this->session->set_userdata('import_st',0);

    }
    else
      redirect('shakha/import_contacts/' . $id);
    /*
    case 1:
    $fh = fopen($this->session->userdata('import_file'), 'r');
    $d = fgets($fh);
    $data['header'] = explode(',',$d);
    $this->session->set_userdata('import_st', 2);
    $this->layout->view('shakha/import_csv', $data);
    }
    */  }
  function upload_result($id) {
    $data = '';
    foreach ($_POST as $key => $val)
      if ($val != '')
        $data[$key] = $val;
      if (isset ($data['button']))
        unset ($data['button']);

      $fh = fopen($this->session->userdata('filename'), 'r+') or die("Couldn't open the file .. please try again.");
    $buffer = '';
    while (!feof($fh)) $buffer[] = explode(',', fgets($fh));
    fclose($fh);
    $d['data'] = '';
    $temp = array();
    foreach ($buffer as $buff) {
      foreach ($data as $key => $val)
        $temp[$key] = trim($buff[$val]);
      $d['data'][] = $temp;
      unset ($temp);
    }
    $this->session->set_userdata('import_c', serialize($d['data']));
    $d['pageTitle'] = 'Confirm Imports';
    $d['shakha'] = $this->Shakha_model->getShakhaInfo($id);
    $this->layout->view('shakha/confirm_imports', $d);
  }

  function finish_import($id) {
    $this->Shakha_model->import_contacts($id);
    $d['pageTitle'] = 'Contacts Imported';
    $this->layout->view('shakha/finish_import', $d);
  }

  private function _chart_data($values) {
    // Port of JavaScript from http://code.google.com/apis/chart/
    // http://james.cridland.net/code

    // First, find the maximum value from the values given

    $maxValue = max($values);

    // A list of encoding characters to help later, as per Google's example
    $simpleEncoding = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    $chartData = "s:";
    for ($i = 0; $i < count($values); $i++) {
      $currentValue = $values[$i];

      if ($currentValue > - 1) {
        $chartData .= substr($simpleEncoding, 61 * ($currentValue / $maxValue), 1);
      }
      else {
        $chartData .= '_';
      }
    }

    // Return the chart data - and let the Y axis to show the maximum value
    //return $chartData."&chxt=y&chxl=1:|0|".$maxValue;
    return $chartData . "&chxl=1:|0|" . $maxValue;
  }
}

?>
