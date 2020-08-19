<?php

namespace OrderBundle\Entity\Test;

use OrderBundle\Entity\Customer;
use PHPUnit\Framework\TestCase;

class EntityTest extends TestCase {

  /**
   * @dataProvider customerAllowedDataProvider
   * @param $isActive
   * @param $isBlocked
   * @param $expectedResult
   */
  public function testCustomerIsAllowedToOrder ($isActive, $isBlocked, $expectedResult)
  {
    $customer = new Customer(
      $isActive,
      $isBlocked,
      'Joaquin Serrano',
      '48999996666'
    );

    $isAllowedToOrder = $customer->isAllowedToOrder();

    $this->assertEquals($expectedResult, $isAllowedToOrder);
  }

  public function customerAllowedDataProvider()
  {
    return [
      'shouldBeAbleToOrderWhenIsActiveAndIsNotBlocked' => [
        'isActive' => true,
        'isBlocked' => false,
        'expectedAllowed' => true
      ],
      'shouldNotBeAbleToOrderWhenIsActiveAndIsBlocked' => [
        'isActive' => true,
        'isBlocked' => true,
        'expectedAllowed' => false
      ],
      'shouldNotBeAbleToOrderWhenIsNotActive' => [
        'isActive' => false,
        'isBlocked' => false,
        'expectedAllowed' => false
      ],
      'shouldNotBeAbleToOrderWhenIsNotActiveAndIsBlocked' => [
        'isActive' => false,
        'isBlocked' => true,
        'expectedAllowed' => false
      ],
    ];
  }
}