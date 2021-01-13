<?php
use CRM_Smsinbox_ExtensionUtil as E;

/**
 * Update SMS state.
 */
class CRM_Smsinbox_SmsState {

  public function update($activity_id, $read_status) {
    $sql = "REPLACE INTO civicrm_smsinbox_state SET activity_id = %0, read_status = %1";
    $params = [ 0 => [ $activity_id, 'Integer' ], 1 => [ $read_status, 'Integer' ] ];
    CRM_Core_DAO::executeQuery($sql, $params);
    return [ 'activity_id' => $activity_id, 'read_status' => $read_status ];
  }

}
