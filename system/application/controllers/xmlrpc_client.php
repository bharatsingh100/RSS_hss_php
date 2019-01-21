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
        $this->output->set_header('Content-Type: application/json');
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

	function getShakhaContacts($shakha_id) {
		$this->output->enable_profiler(FALSE);
		$this->db->select('contact_id, household_id, contact_type, gana, first_name, last_name, gender, birth_year, company, position, email, email_status, ph_mobile, ph_home, ph_work, street_add1, street_add2, city, state, zip, ssv_completed, notes');
		$this->db->get_where('swayamsevaks', array('shakha_id' => $shakha_id));
		$this->db->order_by('last_name');
		$rs = $this->db->get();
		$this->output->set_header('Content-Type: application/json');
		print(json_encode($rs->result_array()));
    }
    
    // Geocode all shakha_geocode table data
    function geodata_json() {
        $this->output->enable_profiler(FALSE);
        $data = $this->db->get_where('shakha_geocoded', array('match_indicator' => 'Match'))->result_array();
        $this->output->set_header('Content-Type: application/json');
        echo "var shakhas_geocoded = " . json_encode($data);
    }

    // Generate JSON for Shakha Lat+Long coordinates for map
    function shakha_geodata_places() {
        $this->output->enable_profiler(FALSE);
        $this->db->select('*');
        $this->db->from('shakha_geocoded');
        $this->db->join('shakhas', 'shakhas.shakha_id = shakha_geocoded.shakha_id');
        $this->db->where('shakha_geocoded.match_indicator','Match');
        $query = $this->db->get();
        
        // Template for the feature point
        $format = array(
            'type' => 'Feature',
            'geometry' => array(
                'type' => 'Point',
                'coordinates' => array(125.6, 10.1),
            ),
            'properties' => array(
                'name' => 'Bhakti Shakha'
            )
        );
        $points = array();
        $properties = array('shakha_id', 'name', 'frequency', 'frequency_day', 'time_from', 'time_to', 'output_add');
        
        // Build individual feature point
        foreach($query->result() as $row) {
            if (empty($row->long_lat)) {
                continue;
            }
            $item = $format;
            $item['geometry']['coordinates'] = array_map('floatval', explode(',', $row->long_lat));
            foreach($properties as $prop) {
                $item['properties'][$prop] = $row->$prop; 
            }
            $points[] = $item;
        }

        $features = array(
            'type' => 'FeatureCollection',
            'features' => $points
        );
        
        $this->output->set_header('Content-Type: application/json');
        echo "var shakhas = " . json_encode($features);
    }
    
    // Fetch and Generate coodinates and county / state using Google Geocode API
    function geocode_missing_shakha() {
        $limit = 5;
        $data = $this->db->get_where('shakha_geocoded', array('match_indicator' => 'No_Match'), $limit)->result_array();
        $states = $this->db->select('state_fips, area_name')
            ->get_where('geocodes', array('summary_level' => '040'))->result_array();
        
        $state_fips = array_column($states, 'state_fips', 'area_name');
        
        foreach ($data as $item) {
            // Bail out if canada address
            if (strpos(strtolower($item['input_add']), 'canada') !== FALSE) {
                continue;
            };
            
            $optons = array(
                'address' => str_replace(' ', '+', $item['input_add']),
                'key' => $this->config->item('gmaps_key')
            );
            $path = 'https://maps.googleapis.com/maps/api/geocode/json?' . http_build_query($optons);
            $resp = file_get_contents($path);
            
            if ($resp = json_decode($resp)) {
                if ($resp->status == 'OK') {
                    $location = $resp->results[0];
                    print_r($location);
                    
                    // Get County FIPS
                    $i = 0;
                    $county = array();
                    $state = array();
                    $country = 'United States';
                    foreach($location->address_components as $comp) {
                        if ($comp->types[0] == 'administrative_area_level_1') {
                            $state['long_name'] = $comp->long_name;
                        }
                        elseif ($comp->types[0] == 'administrative_area_level_2') {
                            $county['long_name'] = $comp->long_name;
                        }
                        elseif ($comp->types[0] == 'country') {
                            $country = $comp->long_name;
                        }
                    }

                    // Bail out if not US address because we don't have county map for Canada
                    if ($country !== 'United States') { continue; }

                    $ops = array(
                        'summary_level' => '050',
                        'state_fips' => $state_fips[$state['long_name']],
                    );

                    $this->db->where($ops);
                    $this->db->where("LOWER(area_name) LIKE LOWER('%" . $county['long_name'] . "%')");
                    // $this->db->like('area_name', );
                    // print_r($this->db->_compile_select());
                    $geocode = $this->db->get('geocodes', 1)->row_array();
                    
                    if (!$geocode) {
                        print_r($geocode);
                        exit();
                    }
                    
                    $updates = array(
                        'long_lat' => $location->geometry->location->lng . ',' . $location->geometry->location->lat,
                        'match_indicator' => 'Match',
                        'match_type' => 'GMaps',
                        'output_add' => $location->formatted_address,
                        'state_fips' => $geocode['state_fips'],
                        'county_fips' => $geocode['county_fips']
                    );
                    $this->db->where('shakha_id', $item['shakha_id']);
                    $this->db->update('shakha_geocoded', $updates);
                    print_r($item['shakha_id']);
                    print_r($updates);

                } else {
                    echo $resp['status'];
                    print_r($resp);
                }
            }
        }
    }
}
?>
