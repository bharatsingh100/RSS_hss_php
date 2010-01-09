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
  }

  public function get_activities($object_type, $object_id, $type = NULL, $limit = NULL, $raw = FALSE) {

    //Get Records from Database
    $events = $this->_get_activities_from_db($object_type, $object_id, $type, $limit);
    if(is_null($events) || $raw) return $events;

    $activities = $this->_gets_events_output_html($events, $type);
    return $activities;
  }

  /**
   * Find all the records in database regarding the specific activity
   * @param int $object_type
   * @param int $object_id
   * @param string $type
   * @param int $limit
   * @return object
   */
  private function _get_activities_from_db($object_type, $object_id, $type, $limit) {

    switch($object_type) {
      case 'contact':
        //Only pull the activities from object1 if we want to display notes
        if($type === 'note') {
          $this->CI->db->where('object_id', $object_id);
        }
        else {
          $this->CI->db->where("(subject_id = {$object_id} OR object_id = {$object_id})");
        }
        break;
      case 'sambhag':
      case 'vibhag' :
      case 'nagar'  :
      case 'shakha' :
      case 'national':
        $this->CI->db->where('object_id2', $object_id);
        break;
      default:
        return NULL;
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

  /**
   * Create HTML Output of the Events/Activities
   * @param array of ojbects $events
   * @param string $type
   * @return Array of Links
   */
  private function _gets_events_output_html($events, $type) {

    $output = array();

    foreach($events as $event) {
      $temp = '';
      $temp = $this->_get_profile_link($event->subject_id);

      switch($event->type) {
        case 'profile' :
          $temp .= " {$event->verb} {$event->type} ";
          $temp .= ' of ';

          if($event->subject_id !== $event->object_id) {
            $contact = $this->_get_profile_link($event->object_id);
            //Fix to prevent blowing up when a Person has been deleted from system.
            $temp = is_null($contact) ? '' : $temp . $contact;
          }
          else {
            $temp .= 'self';
          }
          $temp .= ($event->verb === 'added') ? ' to ' . $this->_get_object_link($event->object_id2) : '';
          break;

        case 'information' :
          $temp .= " {$event->verb} {$event->type} ";
          $temp .= ' of ' . $this->_get_object_link($event->object_id2);
          break;

        case 'responsibility' :
          $temp .= " {$event->verb} ";
          $temp .= $this->_get_responsibility_link($event->object_id2, $event->data) . ' responsibility';
          $temp .= ($event->verb === 'assigned') ? ' to ' : ' from ';
          if($event->subject_id !== $event->object_id) {
            $contact = $this->_get_profile_link($event->object_id);
            //Fix to prevent blowing up when a Person has been deleted from system.
            $temp = is_null($contact) ? '' : $temp . $contact;
          }
          else {
            $temp .= 'self';
          }
          break;

        case 'sankhya' :
          $temp .= " {$event->verb} {$event->type} ";
          $temp .= ' of ' . $this->_get_object_link($event->object_id2);
          $d = unserialize($event->data);
          $temp .= ' for ' . anchor("shakha/add_sankhya/{$event->object_id2}/{$d['date']}", $d['date']) . '.';
          break;

        case 'sny' :
          $temp .= " {$event->verb} SNY Count";
          $temp .= ' of ' . $this->_get_object_link($event->object_id2);
          break;

        case 'note' :
          //In this case only notes are requested and nothing else
          if($type === 'note') {
            $note = unserialize($event->data);
            $temp .= ' <span class="small-text">said</span> ' . $note['note'];
          }
          else {
            $temp .= " {$event->verb} {$event->type} ";
            $temp .= ' on ' .  $this->_get_profile_link($event->object_id);
            $temp .= '\'s profile.';
          }
          break;

        default:
          continue;
      }

      if(!empty($temp)) {
        $temp .= ' <span class="small-text">' . $this->_nicetime($event->created) . '</span>';
        $output[] = $temp;
      }

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

  /**
   * Fetch the name of contact and return it as link HTML
   */
  private function _get_profile_link($profile_id) {

    $link = NULL;

    $this->CI->db->select('first_name, last_name')->limit(1);
    $object = $this->CI->db->get_where('swayamsevaks', array('contact_id' => $profile_id));

    if($object = $object->row()) {
      $link = anchor("profile/view/{$profile_id}", "{$object->first_name} {$object->last_name}");
    }
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
    if($now >= $unix_date) {
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