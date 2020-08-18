<?php

namespace OrderBundle\Validators\Test;

use OrderBundle\Validators\NotEmptyValidator;
use PHPUnit\Framework\TestCase;

class NotEmptyValidatorTest extends TestCase {

    /**
     * @dataProvider dataProvider
     */
    public function testDescriptionShouldNotBeEmpty($value, $expectedResult)
    {
        $notEmptyValidator = new NotEmptyValidator($value);

        $isValid = $notEmptyValidator->isValid();

        $this->assertEquals($expectedResult, $isValid);

    }

    public function dataProvider()
    {
        return [
            'shouldNotBeValidWhenValueIsEmpty' => ['value' => '', 'expectedResult' => false],
            'shouldBeValidWhenValueIsNotEmpty' => ['value' => 'foo', 'expectedResult' => true]
        ];
    }
}