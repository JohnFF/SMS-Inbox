<?php

use Civi\Test\EndToEndInterface;

/**
 * FIXME - Add test description.
 *
 * Tips:
 *  - The global variable $_CV has some properties which may be useful, such as:
 *    CMS_URL, ADMIN_USER, ADMIN_PASS, ADMIN_EMAIL, DEMO_USER, DEMO_PASS, DEMO_EMAIL.
 *  - To spawn a new CiviCRM thread and execute an API call or PHP code, use cv(), e.g.
 *      cv('api system.flush');
 *      $data = cv('eval "return Civi::settings()->get(\'foobar\')"');
 *      $dashboardUrl = cv('url civicrm/dashboard');
 *  - This template uses the most generic base-class, but you may want to use a more
 *    powerful base class, such as \PHPUnit_Extensions_SeleniumTestCase or
 *    \PHPUnit_Extensions_Selenium2TestCase.
 *    See also: https://phpunit.de/manual/4.8/en/selenium.html
 *
 * @group e2e
 * @see cv
 */
class CRM_Smsinbox_SmsSenderTest extends \PHPUnit_Framework_TestCase implements EndToEndInterface {

  public static function setUpBeforeClass() {
    // See: https://github.com/civicrm/org.civicrm.testapalooza/blob/master/civi-test.md

    // Example: Install this extension. Don't care about anything else.
    \Civi\Test::e2e()->installMe(__DIR__)->apply();

    // Example: Uninstall all extensions except this one.
    // \Civi\Test::e2e()->uninstall('*')->installMe(__DIR__)->apply();

    // Example: Install only core civicrm extensions.
    // \Civi\Test::e2e()->uninstall('*')->install('org.civicrm.*')->apply();
  }

  public function setUp() {
    parent::setUp();
  }

  public function tearDown() {
    parent::tearDown();
  }

  public function testGetAddressCoordinatesWithException() {

    $testContact = civicrm_api3('Contact', 'create', array(
      'first_name' => 'test first name',
      'last_name' => 'test last name',
      'contact_type' => 'Individual',
    ));

    try {
      CRM_Bedsafe_Utils::getAddressCoordinatesWithException($testContact['id']);
      $this->fail('This should have asserted.');
    }
    catch (CiviCRM_API3_Exception $exception) {
      $this->assertEquals(0, $exception->getCode());
    }

    civicrm_api3('Address', 'create', array(
      'street_address' => 'Will not translate into a geocode',
      'city' => 'London',
      'country_id' => CRM_Bedsafe_Utils::UK_COUNTRY_CODE,
      'contact_id' => $testContact['id'],
      'location_type_id' => 1,
    ));

    try {
      CRM_Bedsafe_Utils::getAddressCoordinatesWithException($testContact['id']);
      $this->fail('This should have asserted.');
    }
    catch (CRM_Exception $exception) {
      $this->assertEquals(CRM_Bedsafe_Utils::EXCEPTION_CODE_NO_GEOCODE_DATA, $exception->getCode(), $exception->getMessage());
    }
  }

  public function testGetAddressString() {
    $this->assertEquals('172 New Kent Road, Southwark, London, SE1 4YT', CRM_Bedsafe_Utils::getAddressString(8, FALSE));
  }

  public function testGetCurrentDistricts() {
    $shouldContainDistricts = array(); // TODO populate with districts.

    $districts = CRM_Bedsafe_Utils::getCurrentDistricts();

    foreach($shouldContainDistricts as $eachShouldContainDistrict) {
      $this->assertTrue(in_array($eachShouldContainDistrict, $districts));
    }
  }

  public function testGetDistanceBetweenAddresses() {
    $guestCoordinates = array('latitude' => 51.5438399, 'longitude' => -0.1223639);

    // Distance = 1.288km.
    $address1 = array('latitude' => 51.5323538, 'longitude' => -0.1199529);
    $this->assertEquals(1.288, CRM_Bedsafe_Utils::getDistanceBetweenCoordinates(
      $guestCoordinates['latitude'],
      $guestCoordinates['longitude'],
      $address1['latitude'],
      $address1['longitude']
    ));

    // Distance = 10.134km.
    $address2 = array('latitude' => 51.48862265, 'longitude' => -0.00584516);
    $this->assertEquals(10.134, CRM_Bedsafe_Utils::getDistanceBetweenCoordinates(
      $guestCoordinates['latitude'],
      $guestCoordinates['longitude'],
      $address2['latitude'],
      $address2['longitude']
    ));
  }

  public function testGetNumNightsBetweenDates() {
    $this->assertEquals(1, CRM_Bedsafe_Utils::getNumNightsBetweenDates(new DateTime('2017-03-01'), new DateTime('2017-03-02')));
    $this->assertEquals(2, CRM_Bedsafe_Utils::getNumNightsBetweenDates(new DateTime('2017-03-01'), new DateTime('2017-03-03')));
    $this->assertEquals(3, CRM_Bedsafe_Utils::getNumNightsBetweenDates(new DateTime('2017-03-01'), new DateTime('2017-03-04')));
  }

  public function __testValidationFunction(callable $validationFunction, array $invalidEntries) {
    $exceptionsCaught = 0;
    foreach($invalidEntries as $eachInvalidEntry) {
      try {
        forward_static_call($validationFunction, $eachInvalidEntry);
      }
      catch (CRM_Exception $exception) {
        $exceptionsCaught++;
      }
    }
    $this->assertEquals($exceptionsCaught, count($invalidEntries));
  }

  public function testValidationFunctions() {
    $invalidFloats = array('string', array(), NULL, FALSE, TRUE, 100);
    $this->__testValidationFunction('CRM_Bedsafe_Utils::validateFloat', $invalidFloats);

    $invalidInts = array('string', array(), NULL, FALSE, TRUE, 9.01);
    $this->__testValidationFunction('CRM_Bedsafe_Utils::validateInt', $invalidInts);

    $invalidLinks = array('string', array(), NULL, FALSE, TRUE, 9.01, 100);
    $this->__testValidationFunction('CRM_Bedsafe_Utils::validateLink', $invalidLinks);
  }

  public function testHostelsHaveGeocodedCoordinates() {
    $hostelDetails = civicrm_api3('Contact', 'get', array(
      'sequential' => 1,
      'return' => array('id'),
      'contact_type' => 'Organization',
      'contact_sub_type' => 'Hostel',
      'options' => array('limit' => 0),
    ));

    foreach ($hostelDetails['values'] as $eachHostelDetails) {
      $this->assertTrue(is_array(CRM_Bedsafe_Utils::getAddressCoordinatesWithException($eachHostelDetails['contact_id'])));
    }
  }
}
