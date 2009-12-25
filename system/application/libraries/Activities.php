<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Activities
{
    var $CI;

    function Activities()
    {
      $this->CI =& get_instance();
      log_message('debug', 'Activities class loaded');

    }
    function test_activity($test = NULL) {
      log_message('debug', 'Activities class loaded for testing');
    }
    function add_activity(Array $params) {
      $this->CI->db->insert($params);
    }
}