<?php
class Ajax extends Controller
{
    function Ajax()
    {
        parent::Controller();
		$this->load->library('layout', 'layout_user');
		$this->load->helper('security');
		//$this->load->scaffolding('swayamsevaks');
    }
	
	function resp_autocomplete($id)
	{
		//$this->db->like('first_name', $_POST['name']); 
		$names = $this->db->select("CONCAT(first_name, ' ' , last_name) as name")->getwhere('swayamsevaks', array('shakha_id' => $id));
		//$names = $names->result();
		$result = '';
		if($names->num_rows()){
			//$names = $names->result();
			$result .= "<ul>";
			foreach ($names->result() as $row)
				$result .= "<li>$row->name</li>";
			$result .= '</ul>';
		}
//		return $result;
		$this->output->set_output($result);
	}
}

?>