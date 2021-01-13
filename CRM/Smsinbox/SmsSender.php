<?php

/**
 * Tiny class to allow the mocking of core sendSMS.
 * There is probably a better way to do this.
 *
 * @author john
 */
class CRM_Smsinbox_SmsSender {

  const EXCEPTION_CODE_SMS_FAILED_CHECK = 9600;
  const EXCEPTION_CODE_SMS_FAILED_INTERNAL = 9601;

  public function sendSms(&$contactDetails, &$activityParams, &$contactIds, &$smsParams = array(), $userID = NULL) {
    return CRM_Activity_BAO_Activity::sendSMS($contactDetails, $activityParams, $smsParams, $contactIds, $userID);
  }

  public function sendSmsMessage($recipientContactId, $messageText, $smsProviderId) {

    // This API call takes place first because it allows us to check that the passed contact id refers to an actual contact.
    $recipientContactDetails = civicrm_api3('Contact', 'getsingle', array('id' => $recipientContactId));
    $recipientContactDetails['contact_id'] = $recipientContactId; // This is used by the sendSMS function below.

    $recipientContactDetailArray = array($recipientContactDetails);

    // This API call acts as a check to make sure that there is a phone configured
    // that will be used. Twilio fails to send the message silently if it can't find
    // a Mobile for the contact.
    civicrm_api3('Phone', 'getsingle', array(
      'contact_id' => $recipientContactId,
      'phone_type_id' => 'Mobile',
      'default' => 1,
    ));

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

    try {
      list($sent, $activityId, $countSuccess) = CRM_Activity_BAO_Activity::sendSMS($recipientContactDetailArray, $activityParams, $smsParams, $contactIdArray, NULL);
    }
    catch (Exception $exception) {
      throw new CRM_Core_Exception($exception->getMessage(), self::EXCEPTION_CODE_SMS_FAILED_INTERNAL);
    }

    if ($countSuccess !== 1) {
      $errorMessage = "SMS to contact id " . $recipientContactId . " failed.";

      if (get_class($sent[0]) == 'PEAR_Error') {
        $errorMessage .= $sent[0]->getMessage();
      }

      throw new CRM_Core_Exception($errorMessage, self::EXCEPTION_CODE_SMS_FAILED_CHECK);
    }
  }

}
