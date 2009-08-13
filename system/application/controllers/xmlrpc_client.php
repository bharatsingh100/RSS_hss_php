<?php

class Xmlrpc_client extends Controller {

  /*
  function Xmlrpc_client() {
    parent::Controller();
    $this->output->enable_profiler(TRUE);
  }

  function index() {

		$this->load->helper ( 'url' );
		$server_url = site_url ( 'xmlrpc_server' );

		$this->load->library ( 'xmlrpc' );
		$this->xmlrpc->set_debug(TRUE);
		$this->xmlrpc->server ( $server_url, 80 );
		$this->xmlrpc->method ( 'getShakhaInfo' );

		$request = array ('NJ' );
		$this->xmlrpc->request ( $request );

		if (! $this->xmlrpc->send_request ()) {
			echo $this->xmlrpc->display_error ();
		} else {
			echo '<pre>';
			print_r ( $this->xmlrpc->display_response () );
			echo '</pre>';
		}
	} */

    /*Return List of Shakhas along with other info as JSON Object*/
    function getShakhas($id) {

        $this->db->select('shakha_id, name, city, state, zip, shakha_status, frequency, frequency_day, time_from, time_to');
        $this->db->order_by('city');

        if(strlen($id) === 2)
            $rs = $this->db->get_where('shakhas', array('state' => $id, "shakha_status" => 1));
        else if($id == 'ALL')
            $rs = $this->db->get('shakhas');
        else
            exit();

        if($rs->num_rows() == 0) exit();

        $shakhas = $rs->result_array();

	//Moving static query part out of the foreach loop
        $this->db->start_cache();
        $this->db->select('swayamsevaks.contact_id, swayamsevaks.first_name, swayamsevaks.last_name, responsibilities.responsibility');
        $this->db->from('swayamsevaks');
        $this->db->order_by('responsibilities.responsibility');
        $this->db->join('responsibilities', "responsibilities.swayamsevak_id = swayamsevaks.contact_id");
        $this->db->where_in('responsibilities.responsibility', array('020','021','030','031'));
        $this->db->stop_cache();

        foreach($shakhas as &$shakha) {

            $this->db->where('responsibilities.shakha_id',$shakha['shakha_id']);
            $contacts = $this->db->get();

            if($contacts->num_rows() > 0){
                $shakha['contacts'] = $contacts->result_array();
            }//End if
        }//End Foreach

        $this->db->flush_cache();
        print(json_encode($shakhas));
    }

    function syncUsers($lastTime) {
        $this->db->select('ss.contact_id, ss.first_name, ss.last_name, ss.email, ss.passwordmd5, UNIX_TIMESTAMP(ss.modified) as modified, responsibilities.level, responsibilities.created', FALSE);
        $this->db->from('swayamsevaks ss');
        $this->db->join('responsibilities', 'responsibilities.swayamsevak_id = ss.contact_id');
        $this->db->having("modified > $lastTime OR UNIX_TIMESTAMP(responsibilities.created) > $lastTime");
        //$this->db->having("modified >= $lastTime");
        $rs = $this->db->get();

        if($rs->num_rows() > 0)
            print(json_encode($rs->result_array()));
    }

	/*
	function getShakhaContacts($shakha_id) {

		$this->db->select('swayamsevaks.contact_id, swayamsevaks.first_name, swayamsevaks.last_name, responsibilities.responsibility');
		$this->db->from('swayamsevaks');
		$this->db->order_by('responsibilities.responsibility');
		$this->db->join('responsibilities', "responsibilities.shakha_id = $shakha_id AND (responsibilities.responsibility = 020 OR responsibilities.responsibility = 030) AND responsibilities.swayamsevak_id = swayamsevaks.contact_id");
		$rs = $this->db->get();
        print_r(json_encode($rs->result_array()));

	}*/


}
?>
