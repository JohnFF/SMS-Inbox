<?php
use CRM_Smsinbox_ExtensionUtil as E;

class CRM_Smsinbox_Page_SmsInbox extends CRM_Core_Page {

  public function run() {
    $inboundSmsMessages = civicrm_api3('activity', 'get', array(
      'activity_type_id' => 'Inbound SMS',
      'options' => array('limit' => 0),
    ));

    foreach ($inboundSmsMessages as &$eachInboundSmsMessage) {
      $eachInboundSmsMessage['to'] = civicrm_api3('contact', 'getvalue', array(
        'return' => 'display_name',
        'id' => $eachInboundSmsMessage['source_contact_id'],
      ));

      $eachInboundSmsMessage['from'] = civicrm_api3('contact', 'get', array(
        'return' => 'display_name',
        'activity_id' => $eachInboundSmsMessage['id'],
      ));
    }

    $this->assign('inboundSmsMessages', $inboundSmsMessages);

    parent::run();
  }

}
