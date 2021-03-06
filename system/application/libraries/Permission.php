<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Permission
{
  private $CI;
	private $cid;
	private $sh_resp = array(); //Shakha Responsibility array
	private $is_admin = false;
	private $is_nt_kk = false;
	private $is_sambhag_kk;
	private $is_vibhag_kk;
	private $is_nagar_kk;
	private $is_shakha_kkl;
	private $is_shakha_kkh;
	private $shakha_id;

    //020 - Karyavah
	//021 - Sah-Karyavah
	//030 - Mukhya Shikshak
	//031 - Sah-Mukhya Shikshak
	//170 - Palak
	//150 - Sampark Pramukh
	//151 - Sah-Sampark Pramukh
	//230 - SNY Pramukh
	//240 - Dharma Bee Pramukh
	//250 - SV150 Pramukh
	private $shakha_kkh_respons = array('020','021','030','031','170', '150', '151', '230', '240', '250');

    function Permission()
    {
		$this->CI =& get_instance();
		log_message('debug', 'Permission class loaded');
		$this->cid = $this->CI->session->userdata('contact_id');
		$this->shakha_id = $this->CI->session->userdata('shakha_id');
		//$this->vibhag_id = $this->CI->session->userdata('vibhag_id');
		//$this->sambhag_id = $this->CI->session->userdata('sambhag_id');

		//Sort Responsibility by order so that highest one is used
		$this->CI->db->order_by('responsibility');
		$temp = $this->CI->db->get_where('responsibilities', array('swayamsevak_id' => $this->cid, 'level' => 'SH'));
    if($temp->num_rows() > 0){
      foreach ($temp->result() as $row) {
        $this->sh_resp[] = $row->responsibility;
      }
    }

		//IS_ADMIN
		$a = array(1, 4717, 2832, 11275, 11274);
		//Abhi Gupta, Ramesh Subramaniam, Rajesh Acharya, Vani Ji, Sharvani Ji
		//Super Admin (CID = 1)
		$this->is_admin = in_array($this->cid, $a);

		//IS_NT_KK
		$rs = $this->CI->db->get_where('responsibilities', array('swayamsevak_id' => $this->cid, 'level' => 'NT'));
		$this->is_nt_kk = ($rs->num_rows() || $this->is_admin) ? true : false;

    }

	function is_admin() { return $this->is_admin; }

	function is_nt_kk() { return $this->is_nt_kk; }

	function is_sambhag_kk($id)
	{
		if($this->is_nt_kk) return true;
		$rs = $this->CI->db->get_where('responsibilities', array('swayamsevak_id' => $this->cid, 'level' => 'SA', 'sambhag_id' => $id));
		$this->is_sambhag_kk = ($rs->num_rows() || $this->is_nt_kk) ? true : false;
		return $this->is_sambhag_kk;
	}

	function is_vibhag_kk($id)
	{
		//if($this->vibhag_id != $id) return false;

		//if($this->is_sambhag_kk(substr($id, 0, 2))) return true;
		$rs = $this->CI->db->get_where('responsibilities', array('swayamsevak_id' => $this->cid, 'level' => 'VI', 'vibhag_id' => $id));
		$this->is_vibhag_kk = ($rs->num_rows() || $this->is_sambhag_kk) ? true : false;
		return $this->is_vibhag_kk || $this->is_sambhag_kk(substr($id, 0, 2));
	}

	function is_nagar_kk($id)
	{
		$rs = $this->CI->db->get_where('responsibilities', array('swayamsevak_id' => $this->cid, 'level' => 'NA', 'nagar_id' => $id));
		$this->is_nagar_kk = ($rs->num_rows() || $this->is_vibhag_kk) ? true : false;
		return $this->is_nagar_kk || $this->is_vibhag_kk(substr($id, 0, 4));
	}

	function is_shakha_kkh($id)
	{
		$rs = $this->CI->db->get_where('shakhas', array('shakha_id' => $id))->row();
		//if($this->is_vibhag_kk($rs->vibhag_id)) return true;

		if (count(array_intersect($this->shakha_kkh_respons, $this->sh_resp)) && $this->shakha_id == $id){
			$this->is_shakha_kkh = true;
		}
		elseif ($rs->nagar_id != '' && $this->is_nagar_kk($rs->nagar_id)){
			$this->is_shakha_kkh = true;
		}
		elseif ($this->is_vibhag_kk($rs->vibhag_id)){
			$this->is_shakha_kkh = true;
		}
		else
			$this->is_shakha_kkh = false;

		/*$this->is_shakha_kkh = (((in_array($this->sh_resp, $a) && $this->shakha_id == $id)
								  ||  $this->is_vibhag_kk($rs->vibhag_id) ) ? true : false);
		*/
		return $this->is_shakha_kkh;
	}

	function is_shakha_kkl($id)
	{
		/*$allowed_res = array('040','050',
		'060','070','080','090','100','130','140','150','160','180','190','200','999');
		$a = array('040','050','051','060','061','070','071','080','081','010',
		'011','090','091','100','101','110','120','130','131','140', '150', '151', '160', '180', '190' '999','140');
		*/

		$this->is_shakha_kkl = (((!count(array_intersect($this->shakha_kkh_respons, $this->sh_resp)) && $this->shakha_id == $id) || $this->is_shakha_kkh($id)) ? true : false);
		return $this->is_shakha_kkl;
	}

	function allow_profile_edit($id)
	{
		if($id == $this->cid) return true;
		$rs = $this->CI->db->select('shakha_id')->get_where('swayamsevaks', array('contact_id' => $id))->row();
		if($rs->shakha_id != '')
		{
			$ps = $this->CI->db->get_where('shakhas', array('shakha_id' => $rs->shakha_id))->row();
			if($this->is_shakha_kkl($ps->shakha_id)) return true;
		}
		else
			return true;

	}

}
?>
