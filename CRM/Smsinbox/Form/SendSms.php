<?php

use CRM_Smsinbox_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */
class CRM_Smsinbox_Form_SendSms extends CRM_Core_Form {
  public function buildQuickForm() {

    $smsProviders = civicrm_api3('SmsProvider', 'get', array(
      'return' => array('title'),
      'options' => array('limit' => 0),
    ));

    $smsProviderOptions = array();

    foreach($smsProviders['values'] as $eachSmsProviderKey => $eachSmsProviderValue) {
      if (empty($eachSmsProviderValue['title'])) {
        continue;
      }
      $smsProviderOptions[$eachSmsProviderKey] = $eachSmsProviderValue['title'];
    }

    // add form elements
    $this->add(
      'select', // field type
      'sms_provider', // field name
      'SMS Provider', // field label
      $smsProviderOptions, // list of options
      TRUE // is required
    );

    $this->addEntityRef('recipient_contact_id', 'Recipient', array(), TRUE);

    $this->add(
      'textarea', // field type
      'message_text', // field name
      'Message', // field label
      TRUE // is required
    );

    $this->addButtons(array(
      array(
        'type' => 'submit',
        'name' => E::ts('Send SMS message'),
        'isDefault' => TRUE,
      ),
    ));

    // export form elements
    $this->assign('elementNames', $this->getRenderableElementNames());
    parent::buildQuickForm();
  }

  public function postProcess() {
    $values = $this->exportValues();
    $smsSender = new CRM_Smsinbox_SmsSender();
    $smsSender->sendSmsMessage($values['recipient_contact_id'], $values['message_text'], $values['sms_provider']);

    CRM_Core_Session::setStatus(E::ts('You picked color "%1"', array(
      1 => "message sent",
    )));
    parent::postProcess();
  }

  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  public function getRenderableElementNames() {
    // The _elements list includes some items which should not be
    // auto-rendered in the loop -- such as "qfKey" and "buttons".  These
    // items don't have labels.  We'll identify renderable by filtering on
    // the 'label'.
    $elementNames = array();
    foreach ($this->_elements as $element) {
      /** @var HTML_QuickForm_Element $element */
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }

}
