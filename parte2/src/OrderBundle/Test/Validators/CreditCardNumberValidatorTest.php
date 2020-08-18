<?php

namespace OrderBundle\Validators\Test;

use OrderBundle\Validators\CreditCardNumberValidator;
use PHPUnit\Framework\TestCase;

class CreditCardNumberValidatorTest extends TestCase {

    /**
     * @dataProvider dataProvider
     */
    public function testCreditCardNumberIsValid($value, $expectedValue)
    {
        $creditCardValidator = new CreditCardNumberValidator($value);

        $isValid = $creditCardValidator->isValid();

        $this->assertEquals($expectedValue, $isValid);
    }

    public function dataProvider()
    {
        return [
            'shouldBeValidWhenValueIsACreditCard' => ['value' => 4546826130018223, 'expectedValue' => true],
            'shouldBeValidWhenValueIsACreditCardAsString' => ['value' => '1234567890123456', 'expectedValue' => true],
            'shouldNotBeValidWhenValueIsAString' => ['value' => 'foo', 'expectedValue' => false],
            'shouldNotBeValidWhenValueIsEmpty' => ['value' => '', 'expectedValue' => false],
            'shouldNotBeValidWhenValueHasMoreThan16Numbers' => ['value' => 45468261300182230, 'expectedValue' => false],
            'shouldNotBeValidWhenValueHasLessThan16Numbers' => ['value' => 454682613001822, 'expectedValue' => false]

        ];
    }
}
