<?php
use CRM_Smsinbox_ExtensionUtil as E;

class CRM_Smsinbox_Page_SmsInbox extends CRM_Core_Page {

  public function run() {
    $inboundSmsMessages = civicrm_api3('activity', 'get', array(
      'activity_type_id' => 'Inbound SMS',
      'options' => array('limit' => 0),
    ));

    $this->assign('inboundSmsMessages', $inboundSmsMessages);

    parent::run();
  }

}
