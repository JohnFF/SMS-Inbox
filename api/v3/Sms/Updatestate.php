<?php
use CRM_Smsinbox_ExtensionUtil as E;

/**
 * Sms.Updatestate API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/api-architecture/
 */
function _civicrm_api3_sms_Updatestate_spec(&$spec) {
  $spec['activity_id']['api.required'] = 1;
  $spec['read_status']['api.required'] = 1;
}

/**
 * Sms.Updatestate API
 *
 * @param array $params
 *
 * @return array
 *   API result descriptor
 *
 * @see civicrm_api3_create_success
 *
 * @throws API_Exception
 */
function civicrm_api3_sms_Updatestate($params) {
  return civicrm_api3_create_success(CRM_Smsinbox_SmsState::update($params['activity_id'], $params['read_status']), $params, 'Sms', 'Updatestate');
}
