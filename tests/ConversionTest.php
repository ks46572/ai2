<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Measurement;


class ConversionTest extends TestCase
{
    /** @test */
    public function isFahrenheitConversionCorrect(): void
    {
        $measure = new Measurement();
        $measure->setTemperature(10);

        $fahrenheit = $measure->getFahrenheit();

        $this->assertEquals(50, $fahrenheit);
    }

    /** 
     * @test
     * @dataProvider provideTestData
     *  */
    public function isFahrenheitConversionCorrect_($expected, $celsius): void
    {
        $measure = new Measurement();
        $measure->setTemperature($celsius);

        $fahrenheitResult = $measure->getFahrenheit();

        $this->assertEquals($expected, $fahrenheitResult);
    }

    public function provideTestData()
    {
        return [
            [
                50,
                10,
            ],
            [
                275,
                135
            ],
            [
                32,
                0
            ],
            [
                14,
                -10
            ],
            [
                -193,
                -125
            ]
        ];
    }
}
