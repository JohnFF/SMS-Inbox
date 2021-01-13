<?php
use CRM_Smsinbox_ExtensionUtil as E;

class CRM_Smsinbox_Page_SmsInbox extends CRM_Core_Page {

  private $messageReadCustomFieldId;

  /**
   * @param array $inboundSmsActivity
   */
  private function setMessageInformation(&$inboundSmsActivity) {
    $inboundSmsActivity['read'] = $inboundSmsActivity[$this->messageReadCustomFieldId];
    // unset($inboundSmsActivity[$this->messageReadCustomFieldId]);

    if (!empty($inboundSmsActivity['source_contact_id'])) {
      $inboundSmsActivity['from'] = civicrm_api3('contact', 'getvalue', array(
        'return' => 'display_name',
        'id' => $inboundSmsActivity['source_contact_id'],
      ));
    }
    else {
      $inboundSmsActivity['from'] = 'Unknown';
    }

    $inboundSmsActivity['from'] = CRM_Smsinbox_Utils::getDisplayNameWithFallback($inboundSmsActivity['source_contact_id']);

    // If there's no display name we show the email address, consistent with
    // CRM_Contact_BAO_Contact getDisplayAndImage

    // $eachInboundSmsMessage['from_contact_id'] = civicrm_api3('activity_contact', 'getvalue', array(
    //   'return' => 'contact_id',
    //   'activity_id' => $eachInboundSmsMessage['id'],
    //   'record_type_id' => 3,
    // ));

    // $eachInboundSmsMessage['to'] =  civicrm_api3('contact', 'getvalue', array(
    //     'return' => 'display_name',
    //     'id' => $eachInboundSmsMessage['from_contact_id'],
    // ));
  }

  public function run() {

    $inboundSmsMessages = civicrm_api3('Sms', 'getinbound', array(
      'options' => array('limit' => 0, 'offset' => 25),
    ));

    $this->assign('inboundSmsMessages', $inboundSmsMessages['values']);

    parent::run();
  }

}
