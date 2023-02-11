<?php

namespace Drupal\class_info;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;

class ClassInfoManager implements ClassInfoManagerInterface {

  /**
   * @var Client
   */
  protected Client $httpClient;

  public function __construct(ClientInterface $http_client) {
    $this->httpClient = $http_client;
  }

  /**
   * @inheritDoc
   */
  public function getClassInfo(string $class_id) : ?array {
    $domain_name = \Drupal::request()->getHost();
    try {
      $response = $this->httpClient->request(
        'GET',
        'https://' . $domain_name . '/bmd-class-api/public/class-info/' . $class_id,
      );
      $class_info = json_decode($response->getBody()->getContents(), TRUE, 512, JSON_THROW_ON_ERROR);
      return $class_info;
    } catch (RequestException $e) {
      return NULL;
    }
    return NULL;
  }

}
