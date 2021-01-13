<?php
use CRM_Smsinbox_ExtensionUtil as E;

/**
 * SmsInbound.Updatestate API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/api-architecture/
 */
function _civicrm_api3_smsinbound_Updatestate_spec(&$spec) {
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
function civicrm_api3_smsinbound_Updatestate($params) {
  return civicrm_api3_create_success(CRM_Smsinbox_SmsInbound::update_state($params['activity_id'], $params['read_status']), $params, 'SmsInbound', 'Updatestate');
}
