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
		if(!$this->permission->is_shakha_kkl($this->session->userdata('shakha_id')))
		{
				$this->session->set_userdata('message', 'Your are not allowed to access the requested URL');
				redirect('profile/view/'.$this->session->userdata('contact_id'));
		}
		$this->load->library('Ajax_pagination');
			
		$this->output->enable_profiler($this->config->item('debug'));
//		$this->load->model('Profile_model');
		$this->load->library('layout');
		$this->layout->setLayout("layout_search");
		//$this->load->scaffolding('swayamsevaks');

/*		$exception = array('search','del_ss');
		if(!in_array( $this->uri->segment(2), $exception))
		{
			$t1 = $this->db->select('shakha_id')->get_where('swayamsevaks', array('contact_id' => $this->uri->segment(3)))->row()->shakha_id;
			$rs = $this->db->get_where('shakhas', array('shakha_id' => $t1))->row();
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
		$data['term'] = $_POST['term'];
		$this->layout->view('search/index', $data);

	}	

	function getShakha($keyword){	
	   $page = $this->input->post('page');
	    if(!$page):
		    $offset = 0;
		else:            
		    $offset = $page;
		endif;	
		$shakhaTotalQuery = $this->db->select('shakha_id')									 
							         ->like('name',$keyword,'after')
							         ->get('shakhas'); 

		$shakhaQuery = $this->db->select('name,shakha_id,city,state')								
						        ->like('name',$keyword,'after')
					            ->limit(10,$offset)
			         	        ->get('shakhas');	
		
		$data['getTotalData'] = $shakhaTotalQuery->num_rows();
        $data['perPage'] = '10';
        $data['shakhaDetails'] = $shakhaQuery->result_array();
        $data['keyword'] = $keyword;        
        echo $this->load->view('search/shakha',$data);

	}

	function getSwayamsevak($keyword){
       $page = $this->input->post('page');
        if(!$page):
            $offset = 0;
        else:            
            $offset = $page;
        endif;
		$swayamsevaksTotalQuery = $this->db->select('contact_id')						                 						         					         
								           ->like('swayamsevaks.first_name',$keyword,'after')
								           ->or_like('swayamsevaks.last_name',$keyword,'after')						        
					                       ->get('swayamsevaks');	

        $swayamsevaksQuery = $this->db->select('swayamsevaks.contact_id,swayamsevaks.first_name, swayamsevaks.last_name,shakhas.name,swayamsevaks.state')
						              ->from('swayamsevaks')
						              ->join('shakhas', 'swayamsevaks.shakha_id = shakhas.shakha_id')						         
						              ->like('swayamsevaks.first_name',$keyword,'after')
						              ->or_like('swayamsevaks.last_name',$keyword,'after')
						              ->limit(10,$offset)
			                          ->get();	                    
		
        $data['getTotalData'] = $swayamsevaksTotalQuery->num_rows();
        $data['perPage'] = '10';
        $data['swayamsevaksDetails'] = $swayamsevaksQuery->result_array();
        $data['keyword'] = $keyword;       
		echo $this->load->view('search/swayamsevak',  $data);
	}

	function auto_suggest($keyword){ 
		if(strlen(trim($keyword)) >= 3){
			
			$shakhaQuery = $this->db->select('name,shakha_id')									 
							         ->like('name',$keyword,'after')
							         ->get('shakhas');
			$shakhaResult = $shakhaQuery->result_array();

			$swayamsevaksQuery = $this->db->select('swayamsevaks.contact_id,swayamsevaks.first_name, swayamsevaks.last_name,shakhas.name,swayamsevaks.state')
						         ->from('swayamsevaks')
						         ->join('shakhas', 'swayamsevaks.shakha_id = shakhas.shakha_id')						         
						         ->like('swayamsevaks.first_name',$keyword,'after')
						         ->or_like('swayamsevaks.last_name',$keyword,'after')
			                     ->get();
			
			$swayamsevaksResult = $swayamsevaksQuery->result_array();			
			$response = array();
			

			if(count($swayamsevaksResult)){ 
				foreach ($swayamsevaksResult as $key => $value) {
				         $response[] = ['title'=>
				                        $value['first_name']." ".$value['last_name']."(".$value['state'].") - ".$value['name'],
				                        'id' => $value['contact_id'],
				                        'type'=>'user'];					
					
				}
			
			}

			if(count($shakhaResult)){ 
				foreach ($shakhaResult as $key => $value) {
					$response[] = ['title'=>$value['name'],'id'=>$value['shakha_id'],'type'=>'shakha'];
				}			
			}
			echo json_encode($response);			
		}
		
	}
}

?>