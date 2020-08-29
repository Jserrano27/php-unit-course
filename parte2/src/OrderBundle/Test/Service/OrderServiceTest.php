<?php

namespace OrderBundle\Test\Service;

use FidelityProgramBundle\Service\FidelityProgramService;
use OrderBundle\Entity\CreditCard;
use OrderBundle\Entity\Customer;
use OrderBundle\Entity\Item;
use OrderBundle\Exception\BadWordsFoundException;
use OrderBundle\Exception\CustomerNotAllowedException;
use OrderBundle\Exception\ItemNotAvailableException;
use OrderBundle\Repository\OrderRepository;
use OrderBundle\Service\BadWordsValidator;
use OrderBundle\Service\OrderService;
use PaymentBundle\Entity\PaymentTransaction;
use PaymentBundle\Service\PaymentService;
use PHPUnit\Framework\TestCase;

class OrderServiceTest extends TestCase
{
    private $badWordsValidator;
    private $paymentService;
    private $orderRepository;
    private $fidelityProgramService;

    private $customer;
    private $item;
    private $description;
    private $creditCard;

    public function setUp()
    {
        $this->badWordsValidator = $this->createMock(BadWordsValidator::class);
        $this->paymentService = $this->createMock(PaymentService::class);
        $this->orderRepository = $this->createMock(OrderRepository::class);
        $this->fidelityProgramService = $this->createMock(FidelityProgramService::class);

        $this->customer = $this->createMock(Customer::class);
        $this->item = $this->createMock(Item::class);
        $this->description = 'Batatas com chedar';
        $this->creditCard = $this->createMock(CreditCard::class);
    }

    /**
     * @test
     */
    public function shouldNotProcessWhenCustomerIsNotAllowed()
    {
        $orderService = new OrderService(
            $this->badWordsValidator,
            $this->paymentService,
            $this->orderRepository,
            $this->fidelityProgramService
        );

        $this->customer
            ->method('isAllowedToOrder')
            ->willReturn(false);

        $this->expectException(CustomerNotAllowedException::class);

        $orderService->process(
            $this->customer,
            $this->item,
            $this->description,
            $this->creditCard
        );
    }

    /**
     * @test
     */
    public function shouldNotProcessWhenItemIsNotAvailable()
    {
        $orderService = new OrderService(
            $this->badWordsValidator,
            $this->paymentService,
            $this->orderRepository,
            $this->fidelityProgramService
        );

        $this->customer
            ->method('isAllowedToOrder')
            ->willReturn(true);

        $this->item
            ->method('isAvailable')
            ->willReturn(false);

        $this->expectException(ItemNotAvailableException::class);

        $orderService->process(
            $this->customer,
            $this->item,
            $this->description,
            $this->creditCard
        );
    }

    /**
     * @test
     */
    public function shouldNotProcessWhenHasBadWords()
    {
        $orderService = new OrderService(
            $this->badWordsValidator,
            $this->paymentService,
            $this->orderRepository,
            $this->fidelityProgramService
        );

        $this->customer
            ->method('isAllowedToOrder')
            ->willReturn(true);

        $this->item
            ->method('isAvailable')
            ->willReturn(true);

        $this->badWordsValidator
            ->method('hasBadWords')
            ->willReturn(true);

        $this->expectException(BadWordsFoundException::class);

        $orderService->process(
            $this->customer,
            $this->item,
            $this->description,
            $this->creditCard
        );
    }

    /**
     * @test
     */
    public function shouldSuccessfullyProcess()
    {
        $orderService = new OrderService(
            $this->badWordsValidator,
            $this->paymentService,
            $this->orderRepository,
            $this->fidelityProgramService
        );

        $this->customer
            ->method('isAllowedToOrder')
            ->willReturn(true);

        $this->item
            ->method('isAvailable')
            ->willReturn(true);

        $this->badWordsValidator
            ->method('hasBadWords')
            ->willReturn(false);

        $paymentTransaction = $this->createMock(PaymentTransaction::class);

        $this->paymentService
            ->method('pay')
            ->willReturn($paymentTransaction);

        $this->orderRepository
            ->expects($this->once())
            ->method('save');

        $createdOrder = $orderService->process(
            $this->customer,
            $this->item,
            $this->description,
            $this->creditCard
        );

        $this->assertNotEmpty($createdOrder->getPaymentTransaction());
    }
}
