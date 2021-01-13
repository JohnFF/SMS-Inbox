<?php
use CRM_Smsinbox_ExtensionUtil as E;

/**
 * Retreive inbound SMS messages along with metadata.
 */
class CRM_Smsinbox_SmsInbound {

  /**
   * Retrieve all inbound SMS messages along with their state.
   *
   * This function includes contact display_name when possible and email if there
   * is no display name. It also includes whether or not the inbound sms message
   * has been read.
   *
   **/
  public function get($options) {
    $limit = 25;
    $offset = 0;

    $keys = [ 'limit', 'offset' ];
    foreach ($keys as $key) {
      if (array_key_exists($key, $options)) {
        $$key = $options[$key];
      }
    }
    $return = [];
    $source_record_type_id = CRM_Core_PseudoConstant::getKey(                                  
      'CRM_Activity_BAO_ActivityContact',  
      'record_type_id',                                
      'Activity Source'                                                                              
    );
    $query = "
      SELECT DISTINCT contact.display_name, email.email, state.read_status, activity.id, activity.details, activity.activity_date_time, activity_contact.contact_id
      FROM civicrm_activity activity 
        JOIN civicrm_option_value ov ON activity.activity_type_id = ov.value AND name = 'Inbound SMS'
        JOIN civicrm_option_group og ON ov.option_group_id = og.id AND og.name = 'activity_type'
        LEFT JOIN civicrm_activity_contact activity_contact ON activity.id = activity_contact.activity_id AND record_type_id = %0
        LEFT JOIN civicrm_smsinbox_state state ON activity.id = state.activity_id
        LEFT JOIN civicrm_contact contact ON activity_contact.contact_id = contact.id
        LEFT JOIN civicrm_email email ON email.contact_id = activity_contact.contact_id AND email.is_primary = 1
      LIMIT %1, %2
      ";
    $params = [ 0 => [ $source_record_type_id, 'Integer' ], 1 => [ $limit, 'Integer' ], 2 => [ $offset, 'Integer' ] ];
    $dao = CRM_Core_DAO::executeQuery($query, $params);
    while ($dao->fetch()) {
      $from = $dao->display_name;
      if (empty($from)) {
        $from = $dao->email;
        if (empty($from)) {
          $from = E::ts("Unknown");
        }
      }
      // intval ensures NULL is converted to 0 for consistency.
      $read = intval($dao->read_status);
      $row = [ 
        'id' => $dao->id,
        'from' => $from,
        'read' => $read,
        'activity_date_time' => $dao->activity_date_time,
        'details' => $dao->details,
        'source_contact_id' => $dao->contact_id
      ];
      $return[] = $row;
    }

    return $return;
  }

  /**
   * Update the state of an inbound SMS message
   *
   * Specifically, change the read_status to 0 (unread) or 1 (read).
   *
   **/
  public function update_state($activity_id, $read_status) {
    $sql = "REPLACE INTO civicrm_smsinbox_state SET activity_id = %0, read_status = %1";
    $params = [ 0 => [ $activity_id, 'Integer' ], 1 => [ $read_status, 'Integer' ] ];
    CRM_Core_DAO::executeQuery($sql, $params);
    return [ 'activity_id' => $activity_id, 'read_status' => $read_status ];
  }

  /**
   * Count the number of unread SMS messages.
   *
   **/
  public function count_unread() {
    $sql = "
      SELECT COUNT(DISTINCT activity.id) AS count 
      FROM civicrm_activity activity 
        JOIN civicrm_option_value ov ON activity.activity_type_id = ov.value AND name = 'Inbound SMS'
        JOIN civicrm_option_group og ON ov.option_group_id = og.id AND og.name = 'activity_type'
        LEFT JOIN civicrm_smsinbox_state state ON activity.id = state.activity_id 
      WHERE state.read_status IS NULL OR state.read_status = 0";
    $dao = CRM_Core_DAO::executeQuery($sql);
    $dao->fetch();
    return $dao->count;
  }

}
