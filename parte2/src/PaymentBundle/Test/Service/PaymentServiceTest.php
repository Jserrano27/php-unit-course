<?php

namespace PaymentBundle\Test\Service;

use OrderBundle\Entity\CreditCard;
use OrderBundle\Entity\Customer;
use OrderBundle\Entity\Item;
use PaymentBundle\Repository\PaymentTransactionRepository;
use PaymentBundle\Service\Gateway;
use PaymentBundle\Service\PaymentService;
use PHPUnit\Framework\TestCase;

class PaymentServiceTest extends TestCase {
    /**
     * @test
     */
    public function shouldSaveWhenGatewayReturnOkWithRetries()
    {
        $gateway = $this->createMock(Gateway::class);
        $paymentTransactionRepository = $this->createMock(PaymentTransactionRepository::class);

        $gateway
            ->expects($this->atLeast(3))
            ->method('pay')
            ->will($this->onConsecutiveCalls(
                false, false, true
            ));

        $paymentTransactionRepository
            ->expects($this->once())
            ->method('save');

        $paymentService = new PaymentService($gateway, $paymentTransactionRepository);

        $customer = $this->createMock(Customer::class);
        $item = $this->createMock(Item::class);
        $creditCard = $this->createMock(CreditCard::class);

        $paymentService->pay($customer, $item, $creditCard);
    }
}