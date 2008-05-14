<?php
class Search extends Controller
{
    function Search()
    {
        parent::Controller();
		if(!$this->session->userdata('logged_in'))
		{
			$this->session->set_userdata('message', 'Please login to access the system.');
			$this->session->set_userdata('redirect', $this->uri->uri_string());
			redirect('user');
		}
		
		//Check Permissions
		if(!$this->permission->is_shakha_kkh($this->session->userdata('shakha_id')))
		{
				$this->session->set_userdata('message', 'Your are not allowed to access the requested URL');
				redirect('profile/view/'.$this->session->userdata('contact_id'));
		}
			
		//$this->output->enable_profiler(TRUE);
//		$this->load->model('Profile_model');
		$this->load->library('layout', 'layout_search');
		//$this->load->scaffolding('swayamsevaks');

/*		$exception = array('search','del_ss');
		if(!in_array( $this->uri->segment(2), $exception))
		{
			$t1 = $this->db->select('shakha_id')->getwhere('swayamsevaks', array('contact_id' => $this->uri->segment(3)))->row()->shakha_id;
			$rs = $this->db->getwhere('shakhas', array('shakha_id' => $t1))->row();
			$this->session->set_userdata('bc_shakha', $rs->name);
			$this->session->set_userdata('bc_shakha_id', $rs->shakha_id);
//			$this->session->set_userdata('bc_nagar_id', $rs->nagar_id);
//			$this->session->set_userdata('bc_nagar_id', $rs->nagar_id);		
			$this->session->set_userdata('bc_vibhag', $this->Profile_model->getShortDesc($rs->vibhag_id));	
			$this->session->set_userdata('bc_vibhag_id', $rs->vibhag_id);
			$this->session->set_userdata('bc_sambhag', $this->Profile_model->getShortDesc($rs->sambhag_id));		
			$this->session->set_userdata('bc_sambhag_id', $rs->sambhag_id);
		}
		*/
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
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
    	$config['total_rows'] = $this->db->getwhere('swayamsevaks', $limit);
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
//		$this->db->orderby($sort_by, 'asc');
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