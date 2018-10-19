<?php
namespace Treloar\CouriersPlease;

use Treloar\CouriersPlease\Exception\RequestFailedException;

class WebServiceClient
{
  // API user and pass
  protected $auth;

  /**
   * @param string $user
   *    Self Service API user ID
   * @param string $pass
   *    Self Service API token
   * @param string $uri
   *    The base URI for the API
   */
  public function __construct($user, $pass, $uri = 'https://api-test.couriersplease.com.au/v1/') {
    $this->auth = [ $user, $pass ];
    $this->client = new \GuzzleHttp\Client([
      'base_uri' => $uri,
    ]);
  }

  /**
   * Sands an HTTP request to the web service and
   * 
   * @param string $method
   *    The HTTP method to be used for the request
   * @param string $path
   *    The path for the endpoint
   * @param array  $options
   *    Extra request options
   * @param array  $json
   *    The data to be sent with the request
   * 
   * @return array
   *    The response data
   */
  public function request($method, $path, $options= [], $json = []) {
    $options = array_merge($options, [
      'auth' => $this->auth,
    ]);
    
    if ($json) $options['json'] = $json;

    $response = $this->client->request($method, $path, $options, ['debug' => true]);
    return $response;
  }

  /**
   * Calls the Locations endpoint
   * 
   * @param string $suburbOrPostcode 
   *    An Australian suburb name or postcode
   * 
   * @return array
   *    Suggested locations
   */
  public function getLocations($suburbOrPostcode) {
    $response =  $this->request('GET', 'locations', [
      'query' => [ 'suburbOrPostcode' => $suburbOrPostcode ]
    ]);
    $data = json_decode($response->getBody(), true);

    if ($data['responseCode'] == 'SUCCESS') {
      // return array of suggested locations
      return $data['data'];
    }
    else {
      // include server's error messages in the exception
      throw new RequestFailedException(
        'Error: ' . $data['responseCode'],
        $data['data']['errors']
      );
    }
  }
}