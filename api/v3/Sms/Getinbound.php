<?php
use CRM_Smsinbox_ExtensionUtil as E;

/**
 * Sms.Getinbound API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/api-architecture/
 */
function _civicrm_api3_sms_Getinbound_spec(&$spec) {
  $spec['options']['title'] = E::ts("An array of options to pass in - limit and offset keys are supported to restrict records returned.");
}

/**
 * Sms.Getinbound API
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
function civicrm_api3_sms_Getinbound($params) {
  // Run a sanity check on input parameters.
  $options = array();
  if (array_key_exists('options', $params)) {
    $options = $params['options'];
    $keys = [ 'limit', 'offset' ];
    foreach($keys as $key) {
      if (array_key_exists($key, $options)) {
        if (!is_numeric($options[$key])) {
          throw new API_Exception(E::ts("The %1 option must be numeric.", array(1 => $key)), 'type_error');
        }
        $options[$key] = intval($options[$key]);
      }
    }
  }
  return civicrm_api3_create_success(CRM_Smsinbox_GetInbound::get($options), $params, 'Sms', 'Getinbound');
}
