<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Activities
{
    private $CI;

    function Activities()
    {
      $this->CI =& get_instance();
      log_message('debug', 'Activities class loaded');

    }

    /**
     * Add a activity
     * @param $subject_id - ID of Person who performed the action
     * @param $object_id - ID of Person on whom the action was perfromed
     * @param $type - Name of activity performed ( 'note',
     * 'responsibility',  'sankhya',  'profile',  'information',  'event', 'email')
     * @param $verb - Action performed on the activity ('updated',
     * 'assigned',  'removed',  'created',  'registered',  'attended', 'bounced')
     * @param $object_id2 - Defaul NULL, id of second object where
     * action was perfromed i.e. Shakha/Nagar/Vibhag/Sambhag ID
     * @param $data - Array of Object of data related to activity
     */
    public function add_activity($subject_id, $object_id, $type, $verb, $data = NULL, $object_id2 = NULL) {
      $param = new stdClass;
      $param->subject_id   = (int)$subject_id;
      $param->object_id    = (int)$object_id;
      $param->object_id2   = $object_id2; //Id of Shakha/Nagar/Vibhag/Sambhag
      $param->type         = $type;
      $param->verb         = $verb;
      $param->data         = serialize($data);
      $this->CI->db->insert('activities', $param);
      //log_message('debug', 'Activities class loaded for testing');
    }

    public function get_activities($object_type, $object_id, $type = NULL, $limit = NULL, $raw = FALSE) {

      //Get Records from Database
      $events = $this->_get_activities_from_db($object_type, $object_id, $type, $limit);
      //var_dump($events);
      if(is_null($events) || $raw) return $events;

      $activities = $this->_gets_events_output_html($events);
      //var_dump($activities);
      return $activities;
    }

    private function _get_activities_from_db($object_type, $object_id, $type, $limit) {

      switch($object_type) {
        case 'contact':
          $this->CI->db->where("(subject_id = {$object_id} OR object_id = {$object_id})");
          //$this->CI->db->or_where('object_id', (int)$object_id);
          break;
        case 'sambhag':
        case 'vibhag' :
        case 'nagar'  :
        case 'shakha' :
        case 'national':
          $this->CI->db->where('object_id2', $object_id);
          break;
      }
      $this->CI->db->distinct();
      $this->CI->db->order_by('created', 'desc');

      if(is_numeric($limit)) {
        $this->CI->db->limit($limit);
      }

      if(!is_null($type)) {
        $this->CI->db->where('type', $type);
      }
      $query = $this->CI->db->get('activities');

      return $query->num_rows() ? $query->result() : NULL;
    }

    private function _gets_events_output_html($events) {

      $output = array();

      foreach($events as $event) {
        $temp = '';
        $temp = $this->_get_profile_link($event->subject_id);

        switch($event->type) {
          case 'profile' :
            $temp .= " {$event->verb} {$event->type} ";
            $temp .= ' of ' . $this->_get_profile_link($event->object_id);
            break;
          case 'information' :
            $temp .= " {$event->verb} {$event->type} ";
            $temp .= ' of ' . $this->_get_object_link($event->object_id2);
            break;
          case 'responsibility' :
            $temp .= " {$event->verb} ";
            $temp .= $this->_get_responsibility_link($event->object_id2, $event->data) . ' responsibility';
            $temp .= ($event->verb === 'assigned') ? ' to ' : ' from ';
            $temp .= $this->_get_profile_link($event->object_id);
            break;
          case 'sankhya' :
            $temp .= " {$event->verb} {$event->type} ";
            $temp .= ' of ' . $this->_get_object_link($event->object_id2);
            $d = unserialize($event->data);
            $temp .= ' for ' . anchor("shakha/add_sankhya/{$event->object_id2}/{$d['date']}", $d['date']) . '.';
        }

        $temp .= ' <span class="time_ago">' . $this->_nicetime($event->created) . '</span>';
        $output[] = $temp;
      }

      return $output;
    }

    private function _get_responsibility_link($level_id, $data){
      $data = unserialize($data);
      $this->CI->db->select('short_desc')->limit(1);
      $this->CI->db->where('DOM_ID', 4);
      $query = $this->CI->db->get_where('Ref_Code', array('REF_CODE' => $data['responsibility']));
      $responsibility = $query->row();

      $link = $this->_get_object_link($level_id);
      $link .= ' ' . $responsibility->short_desc;

      return $link;
    }

    private function _get_object_link($object_id) {

      if(is_numeric($object_id)) {
        $this->CI->db->select('name')->limit(1);
        $query = $this->CI->db->get_where('shakhas', array('shakha_id' => $object_id));
        $shakha = $query->row();
        $link = anchor("shakha/view/{$object_id}",  $shakha->name);
      }
      else {
        $this->CI->db->select('DOM_ID, short_desc')->limit(1);
        $this->CI->db->where_in('DOM_ID', array(1,2,3));
        $query = $this->CI->db->get_where('Ref_Code', array('REF_CODE' => $object_id));
        $place = $query->row();

        if($place->DOM_ID == 1) {
          $link = anchor("sambhag/view/{$object_id}", "{$place->short_desc} Sambhag");
        }
        elseif($place->DOM_ID == 2) {
          $link = anchor("vibhag/view/{$object_id}", "{$place->short_desc} Vibhag");
        }
        else {
          $link = anchor("nagar/view/{$object_id}", "{$place->short_desc} Nagar");
        }
      }

      return $link;
    }
    private function _get_profile_link($profile_id) {
      $this->CI->db->select('first_name, last_name')->limit(1);
      $object = $this->CI->db->get_where('swayamsevaks', array('contact_id' => $profile_id));
      $object = $object->row();
      $link = anchor("profile/view/{$profile_id}", "{$object->first_name} {$object->last_name}");
      return $link;
    }

    private function _nicetime($date)
    {
        if(empty($date)) {
            return "No date provided";
        }

        $periods         = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
        $lengths         = array("60","60","24","7","4.35","12","10");

        $now             = time();
        $unix_date         = strtotime($date);

           // check validity of date
        if(empty($unix_date)) {
            return "Bad date";
        }

        // is it future date or past date
        if($now > $unix_date) {
            $difference     = $now - $unix_date;
            $tense         = "ago";

        } else {
            $difference     = $unix_date - $now;
            $tense         = "from now";
        }

        for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
            $difference /= $lengths[$j];
        }

        $difference = round($difference);

        if($difference != 1) {
            $periods[$j].= "s";
        }

        return "$difference $periods[$j] {$tense}";
    }

}