<?php

namespace PaymentBundle\Test\Service;

use OrderBundle\Entity\CreditCard;
use OrderBundle\Entity\Customer;
use OrderBundle\Entity\Item;
use PaymentBundle\Exception\PaymentErrorException;
use PaymentBundle\Repository\PaymentTransactionRepository;
use PaymentBundle\Service\Gateway;
use PaymentBundle\Service\PaymentService;
use PHPUnit\Framework\TestCase;

class PaymentServiceTest extends TestCase {

    /**
     * o PHPUnit instancia uma clase (PaymentServiceTest) por cada teste
     * desse jeito nao se compartilha estado entre testes, nenhum teste depende do outro
     */
    private $gateway;
    private $paymentTransactionRepository;
    private $paymentService;
    private $customer;
    private $item;
    private $creditCard;

    /**
     * setUp e chamado antes da execuçao de CADA teste
     */
    public function setUp()
    {
        $this->gateway = $this->createMock(Gateway::class);
        $this->paymentTransactionRepository = $this->createMock(PaymentTransactionRepository::class);
        $this->paymentService = new PaymentService($this->gateway, $this->paymentTransactionRepository);

        $this->customer = $this->createMock(Customer::class);
        $this->item = $this->createMock(Item::class);
        $this->creditCard = $this->createMock(CreditCard::class);
    }

    /**
     * setUpBeforeClass é chamado só uma vez antes do inicio dos testes da clase
     */
    public static function setUpBeforeClass()
    {
        // Abre conexao com DB;
    }

    /**
     * @test
     */
    public function shouldSaveWhenGatewayReturnOkWithRetries()
    {
        $this->gateway
            ->expects($this->atLeast(3))
            ->method('pay')
            ->will($this->onConsecutiveCalls(
                false, false, true
            ));

        $this->paymentTransactionRepository
            ->expects($this->once())
            ->method('save');

        $this->paymentService->pay($this->customer, $this->item, $this->creditCard);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenGatewayFails()
    {
        $this->gateway
            ->expects($this->atLeast(3))
            ->method('pay')
            ->will($this->onConsecutiveCalls(
                false, false, false
            ));

        $this->paymentTransactionRepository
            ->expects($this->never())
            ->method('save');

        $this->expectException(PaymentErrorException::class);

        $this->paymentService->pay($this->customer, $this->item, $this->creditCard);
    }

    /**
     * Chamado no final do teste, utilizado mais com testes de integraçao (ex. fechar conexao com DB)
     * Pode ser utilizado para liberar a memoria das variaveis dos mocks
     */
    public function tearDown()
    {
        unset($this->gateway);
        unset($this->paymentTransactionRepository);
        unset($this->paymentService);
        unset($this->customer);
        unset($this->item);
        unset($this->creditCard);

        // Fecha conexao com DB;
    }
}