<?php

namespace OrderBundle\Validators\Test;

use OrderBundle\Validators\CreditCardExpirationValidator;
use PHPUnit\Framework\TestCase;

class CreditCardExpirationValidatorTest extends TestCase
{

    /**
     * @dataProvider dataProvider
     */
    public function testCreditCardShouldNotBeExpired($value, $expectedResult)
    {
        $dateTime = new \DateTime($value);
        $creditCardExpirationValidator = new CreditCardExpirationValidator($dateTime);

        $isValid = $creditCardExpirationValidator->isValid();

        $this->assertEquals($expectedResult, $isValid);

    }

    public function dataProvider()
    {
        return [
            'shouldBeValidWhenCreditCardIsNotExpired' => ['value' => '2050-01-01', 'expectedResult' => true],
            'shouldNotBeValidWhenCreditCardIsExpired' => ['value' => '2005-01-01', 'expectedResult' => false],
        ];
    }
}