<?php

use CRM_Smsinbox_ExtensionUtil as E;

/**
 * Utility functions for the SMS Inbox.
 */
class CRM_Smsinbox_Utils {
  /**
   * If there are unread SMS messages, this displays a message.
   */
  public static function checkForUnreadMessageStatus() {

    $unreadMessageCount = CRM_Smsinbox_SmsInbound::count_unread(); 

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

}
