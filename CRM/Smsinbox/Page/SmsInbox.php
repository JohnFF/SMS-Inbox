<?php
use CRM_Smsinbox_ExtensionUtil as E;

class CRM_Smsinbox_Page_SmsInbox extends CRM_Core_Page {

  private $messageReadCustomFieldId;

  public function run() {

    $inboundSmsMessages = civicrm_api3('Smsinbound', 'get', array(
      'options' => array('limit' => 0, 'offset' => 25),
    ));

    $this->assign('inboundSmsMessages', $inboundSmsMessages['values']);

    parent::run();
  }

}
