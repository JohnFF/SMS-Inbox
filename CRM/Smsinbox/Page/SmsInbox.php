<?php
use CRM_Smsinbox_ExtensionUtil as E;

class CRM_Smsinbox_Page_SmsInbox extends CRM_Core_Page {

  private $messageReadCustomFieldId;

  public function run() {

    $records_per_page = 25;
    $page = 1;
    if (isset($_GET['crmPID'])) {
      $page = $_GET['crmPID'];
    }

    $start = ($page - 1) * $records_per_page;

    $requestedMessages = civicrm_api3('Smsinbound', 'get', array(
      'options' => array('limit' => $records_per_page, 'offset' => $start),
    ));

    $totalMessages = civicrm_api3('Smsinbound', 'getcount');

    $this->assign('inboundSmsMessages', $requestedMessages['values']);

    $params = [
      'total' => $totalMessages,
      'rowCount' => $records_per_page,
      'status' => E::ts("SMS %%StatusMessage%%")
    ];

    $pager = new CRM_Utils_Pager($params);

    $this->assign('pager', $pager);
    parent::run();
  }

}
