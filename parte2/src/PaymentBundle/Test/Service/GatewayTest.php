<?php

namespace PaymentBundle\Test\Service;

use MyFramework\HttpClientInterface;
use MyFramework\LoggerInterface;
use PaymentBundle\Service\Gateway;
use PHPUnit\Framework\TestCase;

class GatewayTest extends TestCase
{

    public function testShouldNotPayWhenAuthenticationFails()
    {
        $httpClient = $this->createMock(HttpClientInterface::class);

        $user = 'test';
        $password = 'invalid-password';
        $map = [
            [
                'POST',
                Gateway::BASE_URL . '/authenticate',
                [
                    'user' => $user,
                    'password' => $password
                ],
                null
            ]
        ];

        $httpClient
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValueMap($map));

        $logger = $this->createMock(LoggerInterface::class);


        $gateway = new Gateway($httpClient, $logger, $user, $password);

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
        $logger = $this->createMock(LoggerInterface::class);

        $user = 'test';
        $password = 'valid-password';
        $name = 'Joaquin Serrano';
        $creditCardNumber = 2222444466668888;
        $validity = new \DateTime('now');
        $value = 50;
        $map = [
            [
                'POST',
                Gateway::BASE_URL . '/authenticate',
                [
                    'user' => $user,
                    'password' => $password
                ],
                'my-token'
            ],
            [
                'POST',
                Gateway::BASE_URL . '/pay',
                [
                    'name' => $name,
                    'credit_card_number' => $creditCardNumber,
                    'validity' => $validity,
                    'value' => $value,
                    'token' => 'my-token'
                ],
                ['paid' => false]
            ]
        ];

        $httpClient
            ->expects($this->atLeast(2))
            ->method('send')
            ->will($this->returnValueMap($map));

        $logger
            ->expects($this->once())
            ->method('log')
            ->with('Payment failed');

        $gateway = new Gateway($httpClient, $logger, $user, $password);

        $paid = $gateway->pay(
            $name,
            $creditCardNumber,
            $validity,
            $value
        );

        $this->assertEquals(false, $paid, 'Payment was supposed to fail and return false');
    }

    // Usando ordem das chamadas do mock para definir os retornos
    public function testShouldPayWhenGatewayAndAuthenticationSucceed()
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $logger = $this->createMock(LoggerInterface::class);

        $httpClient
            ->expects($this->at(0))
            ->method('send')
            ->willReturn('my-token');

        $httpClient
            ->expects($this->at(1))
            ->method('send')
            ->willReturn(['paid' => true]);

        $user = 'test';
        $password = 'valid-password';
        $name = 'Joaquin Serrano';
        $creditCardNumber = 2222444466668888;
        $validity = new \DateTime('now');
        $value = 50;

        $gateway = new Gateway($httpClient, $logger, $user, $password);

        $paid = $gateway->pay(
            $name,
            $creditCardNumber,
            $validity,
            $value
        );

        $this->assertTrue($paid, 'Payment was suposed to succeed and return true');
    }
}