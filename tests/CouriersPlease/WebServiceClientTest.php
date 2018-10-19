<?php
namespace Treloar\CouriersPlease;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Constraint\IsType as PHPUnit_IsType;
use Treloar\CouriersPlease\WebServiceClient;

class WebServiceClientTest extends TestCase
{
  /**
   * Initialises WebServiceClient to be reused
   * across multiple tests
   */
  protected function setUp() {
    // initialise the client with dev credentials
    // defined as constants in bootstrap file
    $this->client = new WebServiceClient(CP_API_USER, CP_API_PASS);
  }

  /**
   * Tests whether client gets location suggestions
   * for a valid suburb name or postcode
   */
  public function testGetLocations() {
    $locations = $this->client->getLocations('5000');
    //$locations = $this->client->getLocations('Adelaide');

    $this->assertInternalType(PHPUnit_IsType::TYPE_ARRAY, $locations);
  }
}