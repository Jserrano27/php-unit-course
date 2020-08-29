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

    private $orderService;

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
        $this->withOrderService()
            ->withCustomerNotAllowed();

        $this->expectException(CustomerNotAllowedException::class);

        $this->orderService->process(
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
        $this->withOrderService()
            ->withCustomerAllowed()
            ->withItemNotAvailable();

        $this->expectException(ItemNotAvailableException::class);

        $this->orderService->process(
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
        $this->withOrderService()
            ->withCustomerAllowed()
            ->withItemAvailable()
            ->withBadWords();

        $this->expectException(BadWordsFoundException::class);

        $this->orderService->process(
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
        $this->withOrderService()
            ->withCustomerAllowed()
            ->withItemAvailable()
            ->withBadWordsNotFound();

        $paymentTransaction = $this->createMock(PaymentTransaction::class);

        $this->paymentService
            ->method('pay')
            ->willReturn($paymentTransaction);

        $this->orderRepository
            ->expects($this->once())
            ->method('save');

        $createdOrder = $this->orderService->process(
            $this->customer,
            $this->item,
            $this->description,
            $this->creditCard
        );

        $this->assertNotEmpty($createdOrder->getPaymentTransaction());
    }

    public function withOrderService()
    {
        $this->orderService = new OrderService(
            $this->badWordsValidator,
            $this->paymentService,
            $this->orderRepository,
            $this->fidelityProgramService
        );

        return $this;
    }

    public function withCustomerNotAllowed()
    {
        $this->customer
            ->method('isAllowedToOrder')
            ->willReturn(false);

        return $this;
    }

    public function withCustomerAllowed()
    {
        $this->customer
            ->method('isAllowedToOrder')
            ->willReturn(true);

        return $this;
    }

    public function withItemNotAvailable()
    {
        $this->item
            ->method('isAvailable')
            ->willReturn(false);

        return $this;
    }

    public function withItemAvailable()
    {
        $this->item
            ->method('isAvailable')
            ->willReturn(true);

        return $this;
    }

    public function withBadWords()
    {
        $this->badWordsValidator
            ->method('hasBadWords')
            ->willReturn(true);

        return $this;
    }

    public function withBadWordsNotFound()
    {
        $this->badWordsValidator
            ->method('hasBadWords')
            ->willReturn(false);

        return $this;
    }
}
