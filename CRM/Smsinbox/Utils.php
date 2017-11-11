<?php

use CRM_Smsinbox_ExtensionUtil as E;

/**
 * Utility functions for the SMS Inbox.
 */
class CRM_Smsinbox_Utils {

  public static function getDisplayNameWithFallback($contactId) {
     $contactDetails = civicrm_api3('contact', 'getsingle', array(
      'sequential' => 1,
      'id' => $contactId,
      'return' => array('display_name', 'email'),
    ));

    return !empty($contactDetails['display_name']) ? $contactDetails['display_name'] : $contactDetails['email'];
  }

  /**
   * If there are unread SMS messages, this displays a message.
   */
  public static function checkForUnreadMessageStatus() {

    $unreadMessageCount = civicrm_api3('Activity', 'getcount', array(
      'sequential' => 1,
      self::getMessageReadCustomFieldId() => 0,
    ));

    if (0 == $unreadMessageCount) {
      return;
    }
    elseif (1 == $unreadMessageCount) {
      $message = 'You have one unread SMS message. Click <a href="/civicrm/smsinbox">here</a> to read it.';
    }
    else {
      $message = 'You have %1 unread SMS messages. Click <a href="/civicrm/smsinbox">here</a> to read them.';
    }

    CRM_Core_Session::setStatus(E::ts($message, array(
      1 => $unreadMessageCount,
    )), '', 'info');
  }

  /**
   *
   * @return string the message read field key to pass into API calls.
   */
  public static function getMessageReadCustomFieldId() {
    return 'custom_' . civicrm_api3('CustomField', 'getvalue', array(
      'name' => 'message_read',
      'return' => 'id',
    ));
  }

}