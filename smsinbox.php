<?php

require_once 'smsinbox.civix.php';
use CRM_Smsinbox_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function smsinbox_civicrm_config(&$config) {
  _smsinbox_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function smsinbox_civicrm_xmlMenu(&$files) {
  _smsinbox_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function smsinbox_civicrm_install() {
  _smsinbox_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function smsinbox_civicrm_postInstall() {
  _smsinbox_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function smsinbox_civicrm_uninstall() {
  _smsinbox_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function smsinbox_civicrm_enable() {
  _smsinbox_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function smsinbox_civicrm_disable() {
  _smsinbox_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function smsinbox_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _smsinbox_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function smsinbox_civicrm_managed(&$entities) {
  _smsinbox_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function smsinbox_civicrm_caseTypes(&$caseTypes) {
  _smsinbox_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function smsinbox_civicrm_angularModules(&$angularModules) {
  _smsinbox_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function smsinbox_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _smsinbox_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function smsinbox_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function smsinbox_civicrm_navigationMenu(&$menu) {
  _smsinbox_civix_insert_navigation_menu($menu, NULL, array(
    'label' => E::ts('The Page'),
    'name' => 'the_page',
    'url' => 'civicrm/the-page',
    'permission' => 'access CiviReport,access CiviContribute',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _smsinbox_civix_navigationMenu($menu);
} // */

/**
 * Implements hook_civicrm_inboundSms
 */
function smsinbox_civicrm_inboundSMS($message) {
  watchdog('SMS Inbox', print_r($message, TRUE), array(), WATCHDOG_ERROR);
}

/**
 * Implements hook_civicrm_navigationMenu().
 */
function smsinbox_civicrm_navigationMenu(&$params) {
  $sMailingMenuId = CRM_Core_DAO::getFieldValue('CRM_Core_BAO_Navigation', 'Mailings', 'id', 'name');

  //  Get the maximum key of $params
  $maxKey = max(array_keys($params));

  $params[$sMailingMenuId]['child'][$maxKey + 1] = array(
    'attributes' => array(
      'label'      => 'SMS Inbox',
      'name'       => 'SMSInbox',
      'url'        => 'civicrm/smsinbox',
      'permission' => NULL,
      'operator'   => NULL,
      'separator'  => NULL,
      'parentID'   => $sMailingMenuId,
      'navID'      => $maxKey + 1,
      'active'     => 1,
    ),
  );
}