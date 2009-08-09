<?php
class Maps extends Controller
{
    function Maps()
    {
        parent::Controller();
		//$this->load->library('layout', 'layout_search');
    }
	
	function index1()
	{
		$this->load->view('maps');
	}
	
	function shakha_add()
	{		
		define("MAPS_HOST", "maps.google.com");
		define("KEY", "ABQIAAAA3YhufDkYqOHKG2z8jvcBihQK5XxO090GIrn3NyD4AHxbxASjyRTh_R6GRFjUtMrcyREqqDtoDZmZQA");
		
		// Select all the rows in the markers table
		$query = $this->db->get('shakhas');//->result_array();
		
		// Initialize delay in geocode speed
		$delay = 0;
		$base_url = "http://" . MAPS_HOST . "/maps/geo?output=xml" . "&key=" . KEY;
		
		// Iterate through the rows, geocoding each address
		foreach($query->result_array() as $row) {
		  $geocode_pending = true;
		
		  while ($geocode_pending) {
//			$address = $row["address"].','.$row['address2'].','.$row['city'].;
			$address = $row["address1"].','.$row['address2'].','.$row['city'].','.$row['state'];
			$id = $row["shakha_id"];
			$request_url = $base_url . "&q=" . urlencode($address);
			$xml = simplexml_load_file($request_url) or die("url not loading");
		
			$status = $xml->Response->Status->code;
			if (strcmp($status, "200") == 0) {
			  // Successful geocode
			  $geocode_pending = false;
			  $coordinates = $xml->Response->Placemark->Point->coordinates;
			  $coordinatesSplit = split(",", $coordinates);
			  // Format: Longitude, Latitude, Altitude
			  $d['name'] = $row['name'];
			  $d['lat'] = $coordinatesSplit[1];
			  $d['lng'] = $coordinatesSplit[0];
			  $d['address'] = $address;
			  $d['type'] = 'Shakha';
			  $this->db->insert('markers', $d);
			  
			} else if (strcmp($status, "620") == 0) {
			  // sent geocodes too fast
			  $delay += 100000;
			} else {
			  // failure to geocode
			  $geocode_pending = false;
			  echo "Address " . $address . " failed to geocoded. ";
			  echo "Received status " . $status . "\n";
			}
			usleep($delay);
		  }
		}
}
	function parseToXML($htmlStr) 
	{ 
	$xmlStr=str_replace('<','&lt;',$htmlStr); 
	$xmlStr=str_replace('>','&gt;',$xmlStr); 
	$xmlStr=str_replace('"','&quot;',$xmlStr); 
	$xmlStr=str_replace("'",'&#39;',$xmlStr); 
	$xmlStr=str_replace("&",'&amp;',$xmlStr); 
	return $xmlStr; 
	} 

	function shakha_xml()
	{		
		// Select all the rows in the markers table
		$query = $this->db->get('markers');//->result_array();
		// Start XML file, echo parent node
		echo '<markers>';

				
		// Iterate through the rows, geocoding each address
		foreach($query->result_array() as $row) {
		  // ADD TO XML DOCUMENT NODE
		  echo '<marker ';
		  echo 'name="' . $this->parseToXML($row['name']) . '" ';
		  echo 'address="' . $this->parseToXML(ltrim(',',$row['address'])) . '" ';
		  echo 'lat="' . $row['lat'] . '" ';
		  echo 'lng="' . $row['lng'] . '" ';
		  echo 'type="' . $row['type'] . '" ';
		  echo '/>';
		}
		
		// End XML file
		echo '</markers>';

	}
	
	function index($term = '') 
	{

		if($term == '' && isset($_POST['term'])) { $term = $_POST['term']; $this->session->set_userdata('term', $term); }
		if($term == 'Search...' || strlen($term) <= 3 || $term == '')
		{
			$this->session->set_userdata('message', 'Please enter a meaningful search term, with at least 4 characters.');
			redirect($this->session->ro_userdata('redirect_url'));
		}
		
		$this->load->library('pagination');
		$limit = $_POST['limit'];
		$lim = explode('_', $limit);
		switch($lim[0]){
			case 'SH': 
				$limit = "shakha_id = $lim[1]";
				$this->session->set_userdata('within', 'SH');
				break;
			case 'VI': 
				$limit = $this->_get_shakhas($lim[1], 'vibhag_id');
				$this->session->set_userdata('within', 'VI');				
				break;
			case 'SA': 
				$limit = $this->_get_shakhas($lim[1], 'sambhag_id');
				$this->session->set_userdata('within', 'SA');				
				break;
			case 'NT': 
				$limit = '1';
				$this->session->set_userdata('within', 'NT');	
				break;
		}
		$config['base_url'] = base_url()."/search/index/$term/";
    	$config['total_rows'] = $this->db->get_where('swayamsevaks', $limit);
    	$config['per_page'] = '20';
    	$config['full_tag_open'] = '<p>';
    	$config['full_tag_close'] = '</p>';
		$config['uri_segment'] = 5;
//		$config['post_url'] = $data['order'].'/'.$data['orderDir'];
		$this->pagination->initialize($config);
		$data['results'] = $this->_search($config['per_page'], $this->uri->segment(5), $limit, $term);
		$data['pageTitle'] = 'Search Results';
		$this->layout->view('search/index', $data);
	}
	
	function _search($num, $offset, $limit, $term)
	{
		//$term = explode(' ', $term);
		//foreach(
		$this->db->select('contact_id, CONCAT(first_name, \' \', last_name) as name, city, state, ph_home as phone, ph_home, ph_mobile, ph_work, email');
//		$this->db->order_by($sort_by, 'asc');
		$this->db->where("MATCH(first_name, last_name, company, position, city, notes, email) AGAINST ('+($term)')");
		$this->db->where($limit);
		$query = $this->db->get('swayamsevaks', $num, $offset);
				 
		return $query;
	}
	
	
	function _get_shakhas($id, $type)
	{
		//$shakha_ids = $this->get_shakhas($vibhag_id);
		$this->db->where($type, $id);
		$shakhas = $this->db->select('shakha_id')->get('shakhas')->result();
		$shakha_id = '';
		foreach($shakhas as $shakha)
			$shakha_id[] = $shakha->shakha_id;
		$shakhas = 'shakha_id IN ('.implode(',',$shakha_id).')';
		return $shakhas;
	}
}

?>