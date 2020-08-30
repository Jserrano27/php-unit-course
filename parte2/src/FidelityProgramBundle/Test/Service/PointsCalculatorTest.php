<?php
namespace FidelityProgramBundle\Test\Service;

use FidelityProgramBundle\Service\PointsCalculator;
use PHPUnit\Framework\TestCase;

class PointsCalculatorTest extends TestCase
{
    /**
     * @dataProvider valueDataProvider
     */
    public function testPointsToReceive($value, $expectedPoints)
    {
        $pointsCalculator = new PointsCalculator();

        $pointsReceived = $pointsCalculator->calculatePointsToReceive($value);

        $this->assertEquals($expectedPoints, $pointsReceived);
    }

    public function valueDataProvider()
    {
        return [
            'shouldNotRecievePointsWhenValueIsBelow50' => ['value' => 50, 'expectedPoints' => 0],
            'shouldRecievePointsWhenValueIsBetween51And70' => ['value' => 55, 'expectedPoints' => 1100],
            'shouldRecievePointsWhenValueIsBetween71And100' => ['value' => 80, 'expectedPoints' => 2400],
            'shouldRecievePointsWhenValueIsAbove100' => ['value' => 120, 'expectedPoints' => 6000]
        ];
    }
}
