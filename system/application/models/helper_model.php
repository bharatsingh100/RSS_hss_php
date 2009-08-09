<?php

class helper_model extends Model 
{
   
    function helper_model()
    {
        parent::Model();
    }

	function variable_set($name, $value)
	{
		$temp = $this->db->get_where('variables', array('name' => $name));
		if($temp->num_rows() == 0)
			$this->db->insert('variables', array('name'=>$name, 'value' => $value));
		else
			$this->db->update('variables', array('value' => $value), array('name' => $name));
	}

	function variable_get($name, $default = '')
	{
		$result = $this->db->select('value')->get_where('variables', array('name' => $name));
		if($result->num_rows() > 0):
			return $result->row()->value;
		else:
			return $default;
		endif;
	}

}

?>