<?php

namespace PaymentBundle\Test\Service;

use MyFramework\HttpClientInterface;
use MyFramework\LoggerInterface;
use PaymentBundle\Service\Gateway;
use PHPUnit\Framework\TestCase;

class GatewayTest extends TestCase {

    public function testShouldNotPayWhenAuthenticationFails()
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('send')
            ->willReturnCallback(function($method, $location, $body) {
                return $this->fakeHttpClientSend($method, $location, $body);
            });

        $logger = $this->createMock(LoggerInterface::class);

        $user = 'test';
        $pass = 'invalid-password';
        $gateway = new Gateway($httpClient, $logger, $user, $pass);

        $paid = $gateway->pay(
          'Joaquin Serrano',
          4444666688882222,
          new \DateTime('now'),
          50
        );

        $this->assertEquals(false, $paid);
    }

    public function testShouldNotPayWhenGatewayFails()
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('send')
            ->willReturnCallback(function($method, $location, $body) {
                return $this->fakeHttpClientSend($method, $location, $body);
            });

        $logger = $this->createMock(LoggerInterface::class);

        $user = 'test';
        $pass = 'valid-password';
        $gateway = new Gateway($httpClient, $logger, $user, $pass);

        $paid = $gateway->pay(
            'Joaquin Serrano',
            5486924854934821,
            new \DateTime('now'),
            50
        );

        $this->assertEquals(false, $paid);
    }

    public function testShouldPayWhenGatewayAndAuthenticationSucceed()
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('send')
            ->willReturnCallback(function($method, $location, $body) {
                return $this->fakeHttpClientSend($method, $location, $body);
            });

        $logger = $this->createMock(LoggerInterface::class);

        $user = 'test';
        $pass = 'valid-password';
        $gateway = new Gateway($httpClient, $logger, $user, $pass);

        $paid = $gateway->pay(
            'Joaquin Serrano',
            1111222233334444,
            new \DateTime('now'),
            50
        );

        $this->assertEquals(true, $paid);
    }

    public function fakeHttpClientSend($method, $location, $body)
    {
        switch($location) {
            case Gateway::BASE_URL . '/authenticate':
                if($body['password'] !== 'valid-password') {
                    return null;
                }
                return 'my-token';

                break;

            case Gateway::BASE_URL . '/pay':

                if ($body['credit_card_number'] !== 1111222233334444) {
                    return ['paid' => false];
                }
                return ['paid' => true];

                break;
        }
    }
}