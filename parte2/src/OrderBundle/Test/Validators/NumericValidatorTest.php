<?php

namespace OrderBundle\Validators\Test;

use OrderBundle\Validators\NumericValidator;
use PHPUnit\Framework\TestCase;

class NumericValidatorTest extends TestCase {

    /**
     * @dataProvider dataProvider
     */
    public function testValueShouldBeANumber($value, $expectedValue)
    {
        $numericValidator = new NumericValidator($value);

        $isValid = $numericValidator->isValid();

        $this->assertEquals($expectedValue, $isValid);
    }

    public function dataProvider()
    {
        return [
            'shouldBeValidWhenValueIsANumber' => ['value' => 15, 'expectedValue' => true],
            'shouldBeValidWhenValueIsANumericString' => ['value' => '15', 'expectedValue' => true],
            'shouldNotBeValidWhenValueIsAStringWithNumber' => ['value' => '15F', 'expectedValue' => false],
            'shouldNotBeValidWhenValueIsAString' => ['value' => 'foo', 'expectedValue' => false],
            'shouldNotBeValidWhenValueIsEmpty' => ['value' => '', 'expectedValue' => false]

        ];
    }
}