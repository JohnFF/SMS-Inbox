<?php

/**
 * Class with sole responsibility to send SMS message.
 * Tiny class allows mocking.
 *
 * @author john
 */
class CRM_Smsinbox_SmsSender {

  const EXCEPTION_CODE_SMS_FAILED_CHECK = 9600;
  const EXCEPTION_CODE_SMS_FAILED_INTERNAL = 9601;

  /**
   * Sends the SMS message using CRM_Activity_BAO_Activity::sendSMS.
   *
   * @param int $recipientContactId
   * @param string $messageText
   * @param int $smsProviderId
   * @throws CRM_Core_Exception
   */
  public function sendSmsMessage($recipientContactId, $messageText, $smsProviderId) {
    // Twilio fails to send the message silently if it can't find
    // a Mobile for the contact.
    $mobilePhoneTypeId = CRM_Core_PseudoConstant::getKey('CRM_Core_BAO_Phone', 'phone_type_id', 'Mobile');

    $result = civicrm_api3('Phone', 'get', array(
      'contact_id' => $recipientContactId,
      'phone_type_id' => $mobilePhoneTypeId,
    ));

    if (0 == $result['count']) {
        throw new CRM_Core_Exception('No mobile phone found for this contact.');
    }

    // Select the first mobile phone for the recipient.
    $phone_values = array_shift($result['values']);
    $phone = $phone_values['phone_numeric'];

    if (!$phone) {
      throw new CRM_Core_Exception("Contact id has no mobile phone numbers.", self::EXCEPTION_CODE_SMS_FAILED_INTERNAL);
    }

    // Assemble the call parameters.
    $smsParams = array();

    // Use default SMS provider unless one is explicitly passed.
    if (empty($smsProviderId)) {
      $defaultSmsProvider = civicrm_api3('SmsProvider', 'getvalue', array(
        'sequential' => 1,
        'return' => array('id'),
        'is_default' => 1,
      ));

      $smsParams['provider_id'] = $defaultSmsProvider['values'][0]['id'];
    }
    else {
      $smsParams['provider_id'] = $smsProviderId;
    }

    $activityParams['sms_text_message'] = $messageText;
    $activityParams['activity_subject'] = 'Message via SMS Inbox: ' . substr($messageText, 0, 30);

    $contactIdArray = array($recipientContactId);

    $recipientDetailsArray = [ [ 'contact_id' => $recipientContactId, 'phone' => $phone, 'phone_type_id' => $mobilePhoneTypeId ] ];

    // Try sending the message.
    try {
      list($sent, $activityId, $countSuccess) = CRM_Activity_BAO_Activity::sendSMS($recipientDetailsArray, $activityParams, $smsParams, $contactIdArray, NULL);
    }
    catch (Exception $exception) {
      throw new CRM_Core_Exception($exception->getMessage(), self::EXCEPTION_CODE_SMS_FAILED_INTERNAL);
    }

    // If the count is 0, nothing was sent.
    if ($countSuccess !== 1) {
      $errorMessage = "SMS to contact id " . $recipientContactId . " failed.";

      if (get_class($sent[0]) == 'PEAR_Error') {
        $errorMessage .= $sent[0]->getMessage();
      }
      if (isset($sent[0])) {
        $errorMessage .= $sent[0];
      }

      throw new CRM_Core_Exception($errorMessage, self::EXCEPTION_CODE_SMS_FAILED_CHECK);
    }
  }
}
