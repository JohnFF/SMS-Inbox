<?php
use CRM_Smsinbox_ExtensionUtil as E;

/**
 * Sms.Send API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_sms_Send_spec(&$spec) {
  $spec['recipient_contact_id']['api.required'] = 1;
  $spec['message_text']['api.required'] = 1;
  $spec['sms_provider_id']['api.required'] = 0;
}

/**
 * Sms.Send API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_sms_Send($params) {
  $smsProviderId = array_key_exists('sms_provider_id', $params) ? $params['sms_provider_id'] : NULL;

  $smsSender = new CRM_Smsinbox_SmsSender();
  $smsSender->sendSmsMessage($params['recipient_contact_id'], $params['message_text'], $smsProviderId);
}
